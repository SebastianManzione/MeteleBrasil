<?php
/**
 * SOLUCIÓN: Sincronizar tabla servicio_img desde desarrollo a producción
 * Ejecuta la sincronización remotamente en el servidor de producción
 */

require_once 'vendor/autoload.php';

use phpseclib3\Net\SSH2;

$creds = require __DIR__ . '/config/creds_loader.php';
$sshHost = $creds['ssh']['host'] ?? 'localhost';
$sshPort = (int)($creds['ssh']['port'] ?? 22);
$sshUser = $creds['ssh']['user'] ?? '';
$sshPass = $creds['ssh']['pass'] ?? '';

$dbHost = $creds['db']['host'] ?? 'localhost';
$dbUser = $creds['db']['user'] ?? '';
$dbPass = $creds['db']['pass'] ?? '';
$dbName = $creds['db']['name'] ?? '';

$ssh = new SSH2($sshHost, $sshPort);
if (!$ssh->login($sshUser, $sshPass)) {
    die("❌ Error: No se puede conectar a SSH\n");
}

echo "✅ Conectado a servidor de producción\n\n";

// =========================================
// PASO 1: Crear tabla de respaldo
// =========================================
echo "=== PASO 1: Crear respaldo de BD actual ===\n";
$output = $ssh->exec('php -r "
    \$mysqli = new mysqli(\"' . $dbHost . '\", \"' . $dbUser . '\", \"' . $dbPass . '\", \"' . $dbName . '\");
    if (\$mysqli->connect_error) die(\"Error BD: \" . \$mysqli->connect_error);
    
    // Hacer backup de tabla actual
    \$mysqli->query(\"DROP TABLE IF EXISTS servicio_img_backup\");
    \$mysqli->query(\"CREATE TABLE servicio_img_backup LIKE servicio_img\");
    \$mysqli->query(\"INSERT INTO servicio_img_backup SELECT * FROM servicio_img\");
    
    \$result = \$mysqli->query(\"SELECT COUNT(*) as total FROM servicio_img_backup\");
    \$row = \$result->fetch_assoc();
    echo \"✓ Respaldo creado con \" . \$row[\"total\"] . \" registros\" . PHP_EOL;
"');
echo $output . "\n";

// =========================================
// PASO 2: Preparar datos de desarrollo
// =========================================
echo "=== PASO 2: Leer imágenes desde BD de desarrollo ===\n";

$mysqli_local = new mysqli('localhost', 'root', '', 'metelebrasil');
if ($mysqli_local->connect_error) {
    die("❌ Error conexión local: " . $mysqli_local->connect_error);
}

// Obtener todos los registros de imágenes
$result = $mysqli_local->query("SELECT * FROM servicio_img ORDER BY idServicio, idImgServicio");
$registros = [];
while ($row = $result->fetch_assoc()) {
    $registros[] = $row;
}

echo "✓ Leídos " . count($registros) . " registros de desarrollo\n\n";

// =========================================
// PASO 3: Crear archivo SQL temporal
// =========================================
echo "=== PASO 3: Crear archivo SQL para insertar en producción ===\n";

$sql_inserts = "SET FOREIGN_KEY_CHECKS=0;\n";
$sql_inserts .= "TRUNCATE TABLE servicio_img;\n";

foreach ($registros as $row) {
    $idImgServicio = (int)$row['idImgServicio'];
    $idServicio = (int)$row['idServicio'];
    $ruta = $mysqli_local->real_escape_string($row['ruta']);
    $portada = (int)$row['portada'];
    
    $sql_inserts .= "INSERT INTO servicio_img (idImgServicio, idServicio, ruta, portada) VALUES ($idImgServicio, $idServicio, '$ruta', $portada);\n";
}

$sql_inserts .= "SET FOREIGN_KEY_CHECKS=1;\n";

// Guardar archivo localmente primero
file_put_contents('sync_images.sql', $sql_inserts);
echo "✓ Archivo SQL creado: " . filesize('sync_images.sql') . " bytes\n\n";

// =========================================
// PASO 4: Subir y ejecutar en producción
// =========================================
echo "=== PASO 4: Subir archivo SQL a producción ===\n";

require_once 'vendor/autoload.php';
use phpseclib3\Net\SFTP;

$sftp = new SFTP($sshHost, $sshPort);
if (!$sftp->login($sshUser, $sshPass)) {
    die("❌ Error SFTP\n");
}

$remoteSql = '/home/' . $sshUser . '/public_html/sync_images.sql';
$sftp->put($remoteSql, 'sync_images.sql');
echo "✓ Archivo SQL subido a producción\n\n";

// =========================================
// PASO 5: Ejecutar SQL en producción
// =========================================
echo "=== PASO 5: Ejecutar sincronización en producción ===\n";

$output = $ssh->exec('php -r "
    \$mysqli = new mysqli(\"' . $dbHost . '\", \"' . $dbUser . '\", \"' . $dbPass . '\", \"' . $dbName . '\");
    if (\$mysqli->connect_error) die(\"Error: \" . \$mysqli->connect_error);
    
    // Leer y ejecutar el SQL
    \$sql_file = \"' . $remoteSql . '\";
    \$sql = file_get_contents(\$sql_file);
    
    // Ejecutar línea por línea para mejor control
    \$lines = explode(\";\", \$sql);
    \$ejecutados = 0;
    foreach (\$lines as \$line) {
        \$line = trim(\$line);
        if (!empty(\$line)) {
            \$mysqli->query(\$line);
            if (\$mysqli->error) {
                echo \"Error en query: \" . \$mysqli->error . PHP_EOL;
            } else {
                \$ejecutados++;
            }
        }
    }
    
    echo \"✓ Ejecutadas \" . \$ejecutados . \" consultas\" . PHP_EOL;
    
    // Verificar resultado
    \$result = \$mysqli->query(\"SELECT COUNT(*) as total FROM servicio_img\");
    \$row = \$result->fetch_assoc();
    echo \"✓ Registros finales en servicio_img: \" . \$row[\"total\"] . PHP_EOL;
    
    \$result = \$mysqli->query(\"SELECT COUNT(DISTINCT idServicio) as servicios FROM servicio_img\");
    \$row = \$result->fetch_assoc();
    echo \"✓ Servicios con imágenes: \" . \$row[\"servicios\"] . PHP_EOL;
"');

echo $output . "\n";

// =========================================
// PASO 6: Limpiar
// =========================================
echo "=== PASO 6: Limpiar archivos temporales ===\n";

$ssh->exec('rm -f ' . $remoteSql);
echo "✓ Archivos temporales eliminados en servidor\n";

unlink('sync_images.sql');
echo "✓ Archivo local eliminado\n\n";

// =========================================
// VERIFICACIÓN FINAL
// =========================================
echo "=== VERIFICACIÓN FINAL ===\n";

$output = $ssh->exec('php -r "
    \$mysqli = new mysqli(\"' . $dbHost . '\", \"' . $dbUser . '\", \"' . $dbPass . '\", \"' . $dbName . '\");
    
    // Mostrar servicios CON imágenes
    \$result = \$mysqli->query(\"
        SELECT idServicio, COUNT(*) as cant 
        FROM servicio_img 
        GROUP BY idServicio 
        ORDER BY cant DESC 
        LIMIT 10
    \");
    
    echo \"Top 10 servicios por imágenes:\" . PHP_EOL;
    while (\$row = \$result->fetch_assoc()) {
        echo \"  ID \" . \$row[\"idServicio\"] . \": \" . \$row[\"cant\"] . \" imágenes\" . PHP_EOL;
    }
"');

echo $output . "\n";

echo "✅ ¡SINCRONIZACIÓN COMPLETADA!\n";
echo "\nLas imágenes deberían aparecer en producción en los próximos segundos.\n";
echo "Si no aparecen, limpia el cache del navegador (Ctrl+Shift+Supr)\n";

$ssh->disconnect();
$mysqli_local->close();
?>
