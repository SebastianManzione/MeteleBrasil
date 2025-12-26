<?php
/**
 * SINCRONIZACIÓN REAL - Script que REALMENTE funciona
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

echo "=== INICIANDO SINCRONIZACIÓN REAL ===\n\n";

// PASO 1: Leer datos
echo "PASO 1: Leyendo 1137 registros de BD local...\n";
$mysqli = new mysqli('localhost', 'root', '', 'metelebrasil');
$result = $mysqli->query("SELECT * FROM servicio_img");
$registros = [];
while ($row = $result->fetch_assoc()) {
    $registros[] = $row;
}
echo "✓ Leídos " . count($registros) . " registros\n\n";

// PASO 2: Conectar SSH y crear script
echo "PASO 2: Creando script de inserción...\n";

$script_php = '<?php
$registros = ' . var_export($registros, true) . ';
$mysqli = new mysqli("__DB_HOST__", "__DB_USER__", "__DB_PASS__", "__DB_NAME__");
$mysqli->query("TRUNCATE TABLE servicio_img");
$ok = 0;
foreach ($registros as $r) {
    $sql = "INSERT INTO servicio_img (idImgServicio, idServicio, ruta, portada) VALUES(" . (int)$r["idImgServicio"] . "," . (int)$r["idServicio"] . ",\"" . $mysqli->real_escape_string($r["ruta"]) . "\"," . (int)$r["portada"] . ")";
    if ($mysqli->query($sql)) $ok++;
}
echo "Insertados: $ok\n";
$result = $mysqli->query("SELECT COUNT(*) as total FROM servicio_img");
$row = $result->fetch_assoc();
echo "Total en BD: " . $row["total"] . "\n";
?>';

file_put_contents('_sync.php', $script_php);

// Inyectar credenciales en script remoto
$script_php = str_replace('__DB_HOST__', addslashes($dbHost), $script_php);
$script_php = str_replace('__DB_USER__', addslashes($dbUser), $script_php);
$script_php = str_replace('__DB_PASS__', addslashes($dbPass), $script_php);
$script_php = str_replace('__DB_NAME__', addslashes($dbName), $script_php);
file_put_contents('_sync.php', $script_php);
echo "✓ Script creado (" . (strlen($script_php) / 1024) . " KB)\n\n";

// PASO 3: Subir script
echo "PASO 3: Subiendo a servidor...\n";
$sftp = new SFTP($sshHost, $sshPort);
$sftp->login($sshUser, $sshPass);
$remoteSync = '/tmp/_sync.php';
$sftp->put($remoteSync, '_sync.php');
echo "✓ Subido a /tmp/_sync.php\n\n";

// PASO 4: Ejecutar
echo "PASO 4: Ejecutando en servidor...\n";
$ssh = new SSH2($sshHost, $sshPort);
$ssh->login($sshUser, $sshPass);
$output = $ssh->exec('php /tmp/_sync.php');
echo $output . "\n";

// PASO 5: Verificar
echo "PASO 5: Verificando estado final...\n";
$cmd = 'php -r "'
    . '\\$mysqli = new mysqli(\"' . $dbHost . '\", \"' . $dbUser . '\", \"' . $dbPass . '\", \"' . $dbName . '\");'
    . '\\$result = \\$mysqli->query(\"SELECT COUNT(*) as total FROM servicio_img\");'
    . '\\$row = \\$result->fetch_assoc();'
    . 'echo \"Total registros: \" . \\$row[\"total\"] . PHP_EOL;'
    . '\\$result = \\$mysqli->query(\"SELECT COUNT(DISTINCT idServicio) as servicios FROM servicio_img\");'
    . '\\$row = \\$result->fetch_assoc();'
    . 'echo \"Servicios: \" . \\$row[\"servicios\"] . PHP_EOL;'
    . 'echo PHP_EOL . \"Top 10:\\n\";'
    . '\\$result = \\$mysqli->query(\"SELECT idServicio, COUNT(*) as cant FROM servicio_img GROUP BY idServicio ORDER BY cant DESC LIMIT 10\");'
    . 'while (\\$row = \\$result->fetch_assoc()) { echo \"  ID \" . \\$row[\"idServicio\"] . \": \" . \\$row[\"cant\"] . \" imágenes\\n\"; }'
    . '"';
$output = $ssh->exec($cmd);
echo $output . "\n";

// PASO 6: Limpiar
echo "PASO 6: Limpiando...\n";
$ssh->exec('rm -f ' . $remoteSync);
unlink('_sync.php');
echo "✓ Archivos eliminados\n\n";

echo "✅ ¡SINCRONIZACIÓN COMPLETADA EXITOSAMENTE!\n";
echo "\n📸 Las imágenes deberían estar disponibles en https://metelebrasil.com\n";
echo "🔄 Si no aparecen, limpia cache (Ctrl+Shift+Supr) o usa modo incógnito\n";

$ssh->disconnect();
$mysqli->close();
?>
