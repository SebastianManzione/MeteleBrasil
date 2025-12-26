<?php
/**
 * Inserta registros de servicio 599 en BD producción con nuevos idImgServicio
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

// Leer registros locales
$mysqli = new mysqli('localhost', 'root', '', 'metelebrasil');
if ($mysqli->connect_error) die('BD local: ' . $mysqli->connect_error);

$idServicio = 599;
$res = $mysqli->query("SELECT ruta, portada FROM servicio_img WHERE idServicio = $idServicio ORDER BY idImgServicio");
$rows = [];
while ($r = $res->fetch_assoc()) {
    $rows[] = $r;
}

if (empty($rows)) die("No hay registros locales para servicio $idServicio\n");

echo "Registros locales servicio $idServicio: " . count($rows) . "\n";

// Preparar datos como JSON para embebido seguro
$rows_json = json_encode($rows);

// Conectar SSH y SFTP
$ssh = new SSH2($sshHost, $sshPort);
if (!$ssh->login($sshUser, $sshPass)) die('Error SSH');
$sftp = new SFTP($sshHost, $sshPort);
if (!$sftp->login($sshUser, $sshPass)) die('Error SFTP');

// Crear script remoto con datos embebidos (JSON)
// Usar NOWDOC para evitar problemas de comillas
$php = <<<'PHPSCRIPT'
<?php
$mysqli = new mysqli("__DB_HOST__", "__DB_USER__", "__DB_PASS__", "__DB_NAME__");
if ($mysqli->connect_error) { echo "BD: " . $mysqli->connect_error; exit(1);} 

$idServicio = 599;
$rows = json_decode('__ROWS_JSON__', true);

// Obtener MAX idImgServicio actual
$max = 0;
$qr = $mysqli->query("SELECT IFNULL(MAX(idImgServicio),0) as m FROM servicio_img");
if ($qr) { $row = $qr->fetch_assoc(); $max = (int)$row["m"]; }

$insertados = 0;
foreach ($rows as $r) {
    $max++;
    $ruta = $mysqli->real_escape_string($r["ruta"]);
    $portada = (int)$r["portada"];
    $sql = "INSERT INTO servicio_img (idImgServicio, idServicio, ruta, portada) VALUES($max, $idServicio, '".$ruta."', $portada)";
    if ($mysqli->query($sql)) { $insertados++; } else { echo "Error: " . $mysqli->error . "\n"; }
}

echo "Insertados: $insertados\n";
// Verificación final
$qr = $mysqli->query("SELECT COUNT(*) as total FROM servicio_img WHERE idServicio = $idServicio");
$row = $qr->fetch_assoc();
echo "Total servicio $idServicio: " . $row["total"] . "\n";
?>
PHPSCRIPT;

// Inyectar JSON escapado
$php = str_replace('__ROWS_JSON__', addslashes($rows_json), $php);
// Inyectar credenciales de BD
$php = str_replace('__DB_HOST__', addslashes($dbHost), $php);
$php = str_replace('__DB_USER__', addslashes($dbUser), $php);
$php = str_replace('__DB_PASS__', addslashes($dbPass), $php);
$php = str_replace('__DB_NAME__', addslashes($dbName), $php);

file_put_contents('remote_insert_599.php', $php);

// Subir y ejecutar
echo "Subiendo script remoto...\n";
$remoteTemp = '/home/' . $sshUser . '/remote_insert_599.php';
$uploaded = $sftp->put($remoteTemp, 'remote_insert_599.php');
echo $uploaded ? "✓ Subido\n" : "✗ Fallo al subir\n";

echo "Ejecutando en servidor...\n";
$out = $ssh->exec('php ' . $remoteTemp);
echo "Salida remota:\n" . $out . "\n";

// Limpiar
$ssh->exec('rm -f ' . $remoteTemp);
unlink('remote_insert_599.php');
echo "Limpieza realizada\n";
?>