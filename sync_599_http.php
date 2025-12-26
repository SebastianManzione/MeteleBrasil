<?php
require_once 'vendor/autoload.php';
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

// Leer local filas
$mysqli = new mysqli('localhost','root','','metelebrasil');
$idServicio = 599;
$res = $mysqli->query("SELECT ruta, portada FROM servicio_img WHERE idServicio=$idServicio ORDER BY idImgServicio");
$rows = [];
while ($r = $res->fetch_assoc()) { $rows[] = $r; }
$rows_json = json_encode($rows);

echo "Filas locales 599: ".count($rows)."\n";

$token = md5('sync_599_' . date('Y-m-d'));
$script = <<<'PHPREMOTE'
<?php
$token = '__TOKEN__';
if (!isset($_GET['t']) || $_GET['t'] !== $token) { http_response_code(403); die('denied'); }
$rows = json_decode('__ROWS__', true);

$mysqli = new mysqli('__DB_HOST__','__DB_USER__','__DB_PASS__','__DB_NAME__');
if ($mysqli->connect_error) die('db');

$ok = 0; $err = 0;
foreach ($rows as $r) {
  $ruta = $mysqli->real_escape_string($r['ruta']);
  $portada = (int)$r['portada'];
  // Usar nuevo id auto incremental basado en MAX
  $res = $mysqli->query("SELECT IFNULL(MAX(idImgServicio),0) as m FROM servicio_img");
  $row = $res->fetch_assoc(); $id = (int)$row['m'] + 1;
  $sql = "INSERT INTO servicio_img (idImgServicio,idServicio,ruta,portada) VALUES ($id, 599, '$ruta', $portada)";
  if ($mysqli->query($sql)) { $ok++; } else { $err++; }
}

$res = $mysqli->query("SELECT COUNT(*) as total FROM servicio_img WHERE idServicio=599");
$row = $res->fetch_assoc();
header('Content-Type: application/json');
echo json_encode(['insertados'=>$ok,'errores'=>$err,'total'=>$row['total']]);
PHPREMOTE;

$script = str_replace('__TOKEN__',$token,$script);
$script = str_replace('__ROWS__', addslashes($rows_json), $script);
// Inyectar credenciales de BD
$script = str_replace('__DB_HOST__', addslashes($dbHost), $script);
$script = str_replace('__DB_USER__', addslashes($dbUser), $script);
$script = str_replace('__DB_PASS__', addslashes($dbPass), $script);
$script = str_replace('__DB_NAME__', addslashes($dbName), $script);

file_put_contents('_sync_599.php',$script);

$sftp = new SFTP($sshHost,$sshPort); $sftp->login($sshUser,$sshPass);
$remoteHttpScript = '/home/' . $sshUser . '/domains/metelebrasil.com/public_html/_sync_599.php';
$sftp->put($remoteHttpScript,'_sync_599.php');

echo "Ejecutando vía HTTP...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://metelebrasil.com/_sync_599.php?t='.$token);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP $code\n";
echo "Respuesta: $response\n";

// Limpiar
$ssh = new phpseclib3\Net\SSH2($sshHost,$sshPort); $ssh->login($sshUser,$sshPass);
$ssh->exec('rm -f ' . $remoteHttpScript);
unlink('_sync_599.php');
?>