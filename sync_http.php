<?php
/**
 * SINCRONIZACIÓN VIA HTTP POST
 * Envía datos JSON al servidor y ejecuta inserción
 */

require_once 'vendor/autoload.php';
use phpseclib3\Net\SSH2;
use phpseclib3\Net\SFTP;

$creds = require __DIR__ . '/config/creds_loader.php';
$sshHost = $creds['ssh']['host'] ?? 'localhost';
$sshPort = (int)($creds['ssh']['port'] ?? 22);
$sshUser = $creds['ssh']['user'] ?? '';
$sshPass = $creds['ssh']['pass'] ?? '';

$dbHost = $creds['db']['host'] ?? 'localhost';
$dbUser = $creds['db']['user'] ?? '';
$dbPass = $creds['db']['pass'] ?? '';
$dbName = $creds['db']['name'] ?? '';

echo "=== SINCRONIZACIÓN VIA HTTP ===\n\n";

// Paso 1: Leer datos locales
echo "PASO 1: Leyendo datos locales...\n";
$mysqli = new mysqli('localhost', 'root', '', 'metelebrasil');
$result = $mysqli->query("SELECT * FROM servicio_img ORDER BY idServicio");

$registros = [];
while ($row = $result->fetch_assoc()) {
    $registros[] = $row;
}

echo "✓ Leídos " . count($registros) . " registros\n\n";

// Paso 2: Subir script PHP helper
echo "PASO 2: Subiendo script helper...\n";
$sftp = new SFTP($sshHost, $sshPort);
$sftp->login($sshUser, $sshPass);

// Script que va a insertarlos en BD
$helper_script = '<?php
\$data = json_decode(file_get_contents("php://input"), true);
if (!is_array(\$data)) { die("Error: data not array"); }

\$mysqli = new mysqli("__DB_HOST__", "__DB_USER__", "__DB_PASS__", "__DB_NAME__");
if (\$mysqli->connect_error) die("DB Error: " . \$mysqli->connect_error);

// Truncate
\$mysqli->query("TRUNCATE TABLE servicio_img");

\$insertados = 0;
foreach (\$data as \$row) {
    \$sql = "INSERT INTO servicio_img VALUES(" . 
        (int)\$row["idImgServicio"] . ", " .
        (int)\$row["idServicio"] . ", " .
        "\"" . \$mysqli->real_escape_string(\$row["ruta"]) . "\", " .
        (int)\$row["portada"] .
    ")";
    
    if (\$mysqli->query(\$sql)) {
        \$insertados++;
    }
}

\$result = \$mysqli->query("SELECT COUNT(*) as total FROM servicio_img");
\$row = \$result->fetch_assoc();

header("Content-Type: application/json");
echo json_encode(["insertados" => \$insertados, "total" => \$row["total"]]);
\$mysqli->close();
?>';

// Inyectar credenciales de BD
$helper_script = str_replace('__DB_HOST__', addslashes($dbHost), $helper_script);
$helper_script = str_replace('__DB_USER__', addslashes($dbUser), $helper_script);
$helper_script = str_replace('__DB_PASS__', addslashes($dbPass), $helper_script);
$helper_script = str_replace('__DB_NAME__', addslashes($dbName), $helper_script);

file_put_contents('helper.php', $helper_script);
$remoteHelper = '/home/' . $sshUser . '/public_html/helper_sync.php';
$sftp->put($remoteHelper, 'helper.php');
echo "✓ Script subido\n\n";

// Paso 3: Enviar datos via HTTP POST
echo "PASO 3: Enviando datos via HTTP...\n";

$json_data = json_encode($registros);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://metelebrasil.com/helper_sync.php');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "✓ HTTP Status: $http_code\n";

if ($http_code === 200) {
    $result = json_decode($response, true);
    echo "✓ Respuesta: " . json_encode($result) . "\n\n";
    
    if ($result['insertados'] === count($registros)) {
        echo "✅ ÉXITO: Se sincronizaron todos los " . count($registros) . " registros\n";
    }
} else {
    echo "⚠ Respuesta: " . $response . "\n";
}

// Paso 4: Limpiar
echo "\nPASO 4: Limpiando archivos...\n";
$ssh = new SSH2($sshHost, $sshPort);
$ssh->login($sshUser, $sshPass);
$ssh->exec('rm -f ' . $remoteHelper);
unlink('helper.php');
echo "✓ Archivos eliminados\n\n";

echo "✅ SINCRONIZACIÓN COMPLETADA\n";
echo "Las imágenes deberían aparecer en producción.\n";

$mysqli->close();
?>
