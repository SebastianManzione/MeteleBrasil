<?php
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
if (!$ssh->login($sshUser, $sshPass)) die('Error SSH');

$cmd = 'php -r "'
	. '\\$mysqli = new mysqli(\"' . $dbHost . '\", \"' . $dbUser . '\", \"' . $dbPass . '\", \"' . $dbName . '\");'
	. '\\$res = \\$mysqli->query(\"SELECT COUNT(*) as total FROM servicio_img WHERE idServicio=599\");'
	. '\\$row = \\$res->fetch_assoc();'
	. 'echo \\\"Servicio 599: \\\" . \\$row[\"total\"] . PHP_EOL;'
	. '"';

$out = $ssh->exec($cmd);

echo $out;
?>