<?php
require_once 'vendor/autoload.php';
use phpseclib3\Net\SSH2;

$mysqli = new mysqli('localhost','root','','metelebrasil');
$idServicio = 599;
$res = $mysqli->query("SELECT ruta, portada FROM servicio_img WHERE idServicio=$idServicio ORDER BY idImgServicio");
$rows = [];
while ($r = $res->fetch_assoc()) { $rows[] = $r; }

echo "Filas locales 599: ".count($rows)."\n";

$creds = require __DIR__ . '/config/creds_loader.php';
$sshHost = $creds['ssh']['host'] ?? 'localhost';
$sshPort = (int)($creds['ssh']['port'] ?? 22);
$sshUser = $creds['ssh']['user'] ?? '';
$sshPass = $creds['ssh']['pass'] ?? '';

$dbHost = $creds['db']['host'] ?? 'localhost';
$dbUser = $creds['db']['user'] ?? '';
$dbPass = $creds['db']['pass'] ?? '';
$dbName = $creds['db']['name'] ?? '';

$ssh = new SSH2($sshHost,$sshPort); if(!$ssh->login($sshUser,$sshPass)) die('SSH');

$ok=0;$err=0;
foreach ($rows as $r) {
  $ruta = str_replace("'","''", $r['ruta']);
  $portada = (int)$r['portada'];
  $cmd = "/usr/bin/mysql -h $dbHost -u $dbUser -p$dbPass $dbName -e \"INSERT INTO servicio_img (idImgServicio,idServicio,ruta,portada) SELECT IFNULL(MAX(idImgServicio),0)+1, 599, '$ruta', $portada FROM servicio_img\" 2>/dev/null";
  $out = $ssh->exec($cmd);
  if ($out === null) { $err++; } else { $ok++; }
}

echo "Comandos OK: $ok, errores: $err\n";

$out = $ssh->exec("/usr/bin/mysql -h $dbHost -u $dbUser -p$dbPass -e \"SELECT COUNT(*) as total FROM $dbName.servicio_img WHERE idServicio=599\" 2>/dev/null");
echo $out;
?>