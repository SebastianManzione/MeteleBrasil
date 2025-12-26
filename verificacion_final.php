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
$ssh->login($sshUser, $sshPass);

echo "=== VERIFICACIÓN FINAL DE SINCRONIZACIÓN ===\n\n";

$cmd = 'php -r "'
    . '\\$mysqli = new mysqli(\"' . $dbHost . '\", \"' . $dbUser . '\", \"' . $dbPass . '\", \"' . $dbName . '\");'
    . '\\$result = \\$mysqli->query(\"SELECT COUNT(*) as total FROM servicio_img\");'
    . '\\$row = \\$result->fetch_assoc();'
    . 'echo \"Total de imágenes registradas: \" . \\$row[\"total\"] . PHP_EOL;'
    . '\\$result = \\$mysqli->query(\"SELECT COUNT(DISTINCT idServicio) as servicios FROM servicio_img\");'
    . '\\$row = \\$result->fetch_assoc();'
    . 'echo \"Servicios con imágenes: \" . \\$row[\"servicios\"] . PHP_EOL;'
    . 'echo PHP_EOL . \"Top 20 servicios:\" . PHP_EOL;'
    . '\\$result = \\$mysqli->query(\"SELECT idServicio, COUNT(*) as cant FROM servicio_img GROUP BY idServicio ORDER BY cant DESC LIMIT 20\");'
    . 'while (\\$row = \\$result->fetch_assoc()) { echo \"  Servicio \" . \\$row[\"idServicio\"] . \": \" . \\$row[\"cant\"] . \" imágenes\\n\"; }'
    . '"';

$output = $ssh->exec($cmd);

echo $output;

$ssh->disconnect();
?>
