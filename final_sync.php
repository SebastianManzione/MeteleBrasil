<?php
/**
 * SINCRONIZACIÓN FINAL - EJECUTADA DIRECTAMENTE VIA SSH
 * Este es el enfoque más confiable
 */

require_once 'vendor/autoload.php';

use phpseclib3\Net\SSH2;
use phpseclib3\Net\SFTP;

echo "=== SINCRONIZACIÓN FINAL VIA SSH ===\n\n";

// ===== PASO 1: Leer datos =====
echo "PASO 1: Leyendo datos de BD local...\n";

$mysqli = new mysqli('localhost', 'root', '', 'metelebrasil');
$result = $mysqli->query("SELECT * FROM servicio_img ORDER BY idServicio");
$datos = [];
while ($row = $result->fetch_assoc()) {
    $datos[] = $row;
}

echo "✓ Leídos " . count($datos) . " registros\n\n";

// ===== PASO 2: Crear script con los datos embebidos =====
echo "PASO 2: Creando script de inserción con datos embebidos...\n";

$php_code = '<?php
$datos = ' . var_export($datos, true) . ';

$mysqli = new mysqli("localhost", "u925692129_metelebrasil", "Cambiar2026", "u925692129_metelebrasil");

if ($mysqli->connect_error) {
    die("Error: " . $mysqli->connect_error);
}

echo "Truncando tabla...\\n";
$mysqli->query("TRUNCATE TABLE servicio_img");

echo "Insertando " . count($datos) . " registros...\\n";

$insertados = 0;
foreach ($datos as $row) {
    $sql = "INSERT INTO servicio_img (idImgServicio, idServicio, ruta, portada) VALUES(" . 
        (int)$row["idImgServicio"] . ", " .
        (int)$row["idServicio"] . ", " .
        "\\"" . $mysqli->real_escape_string($row["ruta"]) . "\\", " .
        (int)$row["portada"] .
    ")";
    
    if ($mysqli->query($sql)) {
        $insertados++;
    }
}

echo "Insertados: $insertados\\n";

// Verificación
$result = $mysqli->query("SELECT COUNT(*) as total FROM servicio_img");
$row = $result->fetch_assoc();
echo "Total en BD: " . $row["total"] . "\\n";

$result = $mysqli->query("SELECT COUNT(DISTINCT idServicio) as servicios FROM servicio_img");
$row = $result->fetch_assoc();
echo "Servicios con imágenes: " . $row["servicios"] . "\\n";

// Top 10
echo "\\nTop 5 servicios:\\n";
$result = $mysqli->query("SELECT idServicio, COUNT(*) as cant FROM servicio_img GROUP BY idServicio ORDER BY cant DESC LIMIT 5");
while ($row = $result->fetch_assoc()) {
    echo "  ID " . $row["idServicio"] . ": " . $row["cant"] . " imágenes\\n";
}
?>';

file_put_contents('sync_final.php', $php_code);
echo "✓ Script creado\n\n";

// ===== PASO 3: Subir script =====
echo "PASO 3: Subiendo script a servidor...\n";

$sftp = new SFTP('185.173.111.212', 65002);
$sftp->login('u925692129', 'Nueva$312');
$sftp->put('/home/u925692129/sync_final.php', 'sync_final.php');

echo "✓ Subido a /home/u925692129/sync_final.php\n\n";

// ===== PASO 4: Ejecutar via SSH =====
echo "PASO 4: Ejecutando en servidor...\n\n";

$ssh = new SSH2('185.173.111.212', 65002);
$ssh->login('u925692129', 'Nueva$312');

$output = $ssh->exec('php /home/u925692129/sync_final.php');
echo $output . "\n";

// ===== PASO 5: Limpiar =====
echo "\nPASO 5: Limpiando...\n";

$ssh->exec('rm -f /home/u925692129/sync_final.php');
unlink('sync_final.php');

echo "✓ Archivos temporales eliminados\n\n";

echo "✅ ¡SINCRONIZACIÓN COMPLETADA!\n";
echo "\nVerifica en https://metelebrasil.com\n";
echo "Si las imágenes no aparecen, limpia cache (Ctrl+Shift+Supr)\n";

$ssh->disconnect();
$mysqli->close();
?>
