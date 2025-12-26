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

$cmd = 'mysql -h ' . $dbHost . ' -u ' . $dbUser . ' -p' . $dbPass . ' -e "SELECT COUNT(*) as total FROM ' . $dbName . '.servicio_img WHERE idServicio=599" 2>/dev/null';
$out = $ssh->exec($cmd);

echo $out;
?>
