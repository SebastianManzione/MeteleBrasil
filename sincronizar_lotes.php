<?php
/**
 * SINCRONIZACIÓN CON LOTES: Dividir en múltiples requests para evitar timeout
 */

require_once 'vendor/autoload.php';

use phpseclib3\Net\SFTP;

echo "=== SINCRONIZACIÓN POR LOTES VIA HTTP ===\n\n";

// Leer datos
echo "Leyendo datos...\n";
$mysqli = new mysqli('localhost', 'root', '', 'metelebrasil');
$result = $mysqli->query("SELECT * FROM servicio_img");
$registros = [];
while ($row = $result->fetch_assoc()) {
    $registros[] = $row;
}
echo "✓ " . count($registros) . " registros leídos\n\n";

// Dividir en lotes
$lote_size = 100;
$lotes = array_chunk($registros, $lote_size);

echo "Dividiendo en " . count($lotes) . " lotes de $lote_size registros...\n\n";

// Crear scripts de inserción por lote
$token = md5('metelebrasil_sync_' . date('Y-m-d'));

for ($i = 0; $i < count($lotes); $i++) {
    $lote = $lotes[$i];
    
    // PASO 1: Limpiar tabla en primer lote
    $limpia = ($i == 0) ? '
    if ($_GET["t"] !== "' . $token . '") die("Acceso denegado");
    
    if ($_GET["lote"] == 0) {
        $mysqli->query("TRUNCATE TABLE servicio_img");
        echo "Tabla truncada\\n";
    }
    ' : '
    if ($_GET["t"] !== "' . $token . '") die("Acceso denegado");
    ';
    
    $script = '<?php
    header("Content-Type: text/plain");
    ' . $limpia . '
    
    $lote_num = (int)$_GET["lote"];
    $registros = ' . var_export($lote, true) . ';
    
    $mysqli = new mysqli("localhost", "u925692129_metelebrasil", "Cambiar2026", "u925692129_metelebrasil");
    
    if ($mysqli->connect_error) {
        die("Error: " . $mysqli->connect_error);
    }
    
    $ok = 0;
    foreach ($registros as $r) {
        $sql = "INSERT INTO servicio_img (idImgServicio, idServicio, ruta, portada) VALUES(" . 
            (int)$r["idImgServicio"] . ", " .
            (int)$r["idServicio"] . ", " .
            "\\"" . $mysqli->real_escape_string($r["ruta"]) . "\\", " .
            (int)$r["portada"] .
        ")";
        
        if ($mysqli->query($sql)) {
            $ok++;
        } else {
            echo "Error: " . $mysqli->error . PHP_EOL;
        }
    }
    
    echo "Lote ' . ($i+1) . ': insertados $ok registros\\n";
    
    if ($lote_num == ' . (count($lotes)-1) . ') {
        $result = $mysqli->query("SELECT COUNT(*) as total FROM servicio_img");
        $row = $result->fetch_assoc();
        echo "TOTAL: " . $row["total"] . " registros en BD\\n";
    }
    ?>';
    
    // Subir script
    $filename = "_lote_$i.php";
    file_put_contents($filename, $script);
    
    $sftp = new SFTP('185.173.111.212', 65002);
    $sftp->login('u925692129', 'Nueva$312');
    $sftp->put("/home/u925692129/domains/metelebrasil.com/public_html/$filename", $script);
    
    echo "  Lote " . ($i+1) . "/" . count($lotes) . " subido\n";
}

echo "\n✓ Todos los lotes subidos\n\n";

// Ejecutar lotes
echo "Ejecutando lotes...\n\n";

for ($i = 0; $i < count($lotes); $i++) {
    $url = "https://metelebrasil.com/_lote_$i.php?t=$token&lote=$i";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($http_code === 200) {
        echo "  Lote " . ($i+1) . ": " . trim($response) . "\n";
    } else {
        echo "  Lote " . ($i+1) . ": ERROR HTTP $http_code\n";
    }
    
    sleep(1); // Pequeña pausa entre lotes
}

echo "\n✓ Todos los lotes procesados\n\n";

// Limpiar archivos
echo "Limpiando...\n";

require_once 'vendor/autoload.php';
use phpseclib3\Net\SSH2;

$ssh = new SSH2('185.173.111.212', 65002);
$ssh->login('u925692129', 'Nueva$312');

for ($i = 0; $i < count($lotes); $i++) {
    $ssh->exec("rm -f /home/u925692129/domains/metelebrasil.com/public_html/_lote_$i.php");
    unlink("_lote_$i.php");
}

echo "✓ Archivos limpios\n\n";

echo "✅ ¡SINCRONIZACIÓN COMPLETADA!\n";
echo "Las imágenes deberían estar disponibles en https://metelebrasil.com\n";

$ssh->disconnect();
$mysqli->close();
?>
