<?php
/**
 * SINCRONIZACIÓN DIRECTA: Insertar registros vía SSH en BD producción
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

echo "=== SINCRONIZACIÓN DIRECTA DE TABLA servicio_img ===\n\n";

// Paso 1: Leer datos de desarrollo
echo "PASO 1: Leyendo datos de BD local...\n";
$mysqli = new mysqli('localhost', 'root', '', 'metelebrasil');

if ($mysqli->connect_error) {
    die("❌ Error conexión local: " . $mysqli->connect_error);
}

$result = $mysqli->query("SELECT * FROM servicio_img ORDER BY idServicio, idImgServicio");
$registros = [];
while ($row = $result->fetch_assoc()) {
    $registros[] = $row;
}

echo "✓ Leídos " . count($registros) . " registros\n\n";

// Paso 2: Conectar a SSH
echo "PASO 2: Conectando a SSH...\n";
$ssh = new SSH2($sshHost, $sshPort);
if (!$ssh->login($sshUser, $sshPass)) {
    die("❌ Error SSH\n");
}
echo "✓ Conectado\n\n";

// Paso 3: Crear script PHP para insertar datos
echo "PASO 3: Preparando inserción en BD producción...\n";

// Crear PHP script que se ejecutará en el servidor
$php_script = <<<'REMOTE'
<?php
$mysqli = new mysqli("__DB_HOST__", "__DB_USER__", "__DB_PASS__", "__DB_NAME__");
if ($mysqli->connect_error) {
    echo "Error: " . $mysqli->connect_error;
    exit(1);
}

// Limpiar tabla
$mysqli->query("TRUNCATE TABLE servicio_img");

$datos = json_decode('__JSON__', true);

$insertados = 0;
$errores = 0;

foreach ($datos as $row) {
    $idImgServicio = (int)$row["idImgServicio"];
    $idServicio = (int)$row["idServicio"];
    $ruta = $mysqli->real_escape_string($row["ruta"]);
    $portada = (int)$row["portada"];
    
    $sql = "INSERT INTO servicio_img (idImgServicio, idServicio, ruta, portada) VALUES ($idImgServicio, $idServicio, '".$ruta."', $portada)";
    
    if ($mysqli->query($sql)) {
        $insertados++;
    } else {
        $errores++;
        if ($errores < 5) echo "Error: " . $mysqli->error . PHP_EOL;
    }
}

echo "✓ Insertados: $insertados" . PHP_EOL;
if ($errores > 0) echo "⚠ Errores: $errores" . PHP_EOL;

// Verificación final
$result = $mysqli->query("SELECT COUNT(*) as total FROM servicio_img");
$row = $result->fetch_assoc();
echo "Total en BD: " . $row["total"] . PHP_EOL;

$result = $mysqli->query("SELECT COUNT(DISTINCT idServicio) as servicios FROM servicio_img");
$row = $result->fetch_assoc();
echo "Servicios: " . $row["servicios"] . PHP_EOL;
?>
REMOTE;

// Guardar script localmente
// Inyectar JSON y credenciales antes de guardar
$php_script = str_replace('__JSON__', addslashes(json_encode($registros)), $php_script);
file_put_contents('insert_images.php', $php_script);

// Subir a servidor
echo "✓ Script preparado\n";
echo "✓ Subiendo a servidor...\n";

require_once 'vendor/autoload.php';
use phpseclib3\Net\SFTP;

// Inyectar credenciales de BD en script
$php_script = str_replace('__DB_HOST__', addslashes($dbHost), $php_script);
$php_script = str_replace('__DB_USER__', addslashes($dbUser), $php_script);
$php_script = str_replace('__DB_PASS__', addslashes($dbPass), $php_script);
$php_script = str_replace('__DB_NAME__', addslashes($dbName), $php_script);

$sftp = new SFTP($sshHost, $sshPort);
$sftp->login($sshUser, $sshPass);
$remoteInsert = '/home/' . $sshUser . '/public_html/insert_images.php';
$sftp->put($remoteInsert, 'insert_images.php');

echo "✓ Subido\n\n";

// Paso 4: Ejecutar en servidor
echo "PASO 4: Ejecutando en servidor producción...\n";
$output = $ssh->exec('cd /home/' . $sshUser . '/public_html && php insert_images.php');
echo $output . "\n";

// Paso 5: Limpiar
echo "PASO 5: Limpiando...\n";
$ssh->exec('rm -f ' . $remoteInsert);
unlink('insert_images.php');
echo "✓ Archivos temporales eliminados\n\n";

// Paso 6: Verificación
echo "PASO 6: Verificación final...\n";
$cmd = 'php -r "'
    . '\\$mysqli = new mysqli(\"' . $dbHost . '\", \"' . $dbUser . '\", \"' . $dbPass . '\", \"' . $dbName . '\");'
    . '\\$result = \\$mysqli->query(\"SELECT COUNT(*) as total FROM servicio_img\");'
    . '\\$row = \\$result->fetch_assoc();'
    . 'echo \"Total registros: \" . \\$row[\"total\"] . PHP_EOL;'
    . '\\$result = \\$mysqli->query(\"SELECT COUNT(DISTINCT idServicio) as servicios FROM servicio_img\");'
    . '\\$row = \\$result->fetch_assoc();'
    . 'echo \"Servicios: \" . \\$row[\"servicios\"] . PHP_EOL;'
    . '\\$result = \\$mysqli->query(\"SELECT idServicio, COUNT(*) as cant FROM servicio_img GROUP BY idServicio ORDER BY cant DESC LIMIT 5\");'
    . 'echo PHP_EOL . \"Top 5 servicios por imágenes:\" . PHP_EOL;'
    . 'while (\\$row = \\$result->fetch_assoc()) { echo \"  ID \" . \\$row[\"idServicio\"] . \": \" . \\$row[\"cant\"] . \" imágenes\" . PHP_EOL; }'
    . '"';
$output = $ssh->exec($cmd);

echo $output;

echo "\n✅ ¡SINCRONIZACIÓN COMPLETADA!\n";
echo "Las imágenes deberían aparecer en el sitio producción.\n";
echo "Si no aparecen, limpia el cache del navegador (Ctrl+Shift+Supr en Chrome/Firefox)\n";

$ssh->disconnect();
$mysqli->close();
?>
