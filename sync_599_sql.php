<?php
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

// Leer local
$mysqli = new mysqli('localhost','root','','metelebrasil');
$idServicio = 599;
$res = $mysqli->query("SELECT ruta, portada FROM servicio_img WHERE idServicio=$idServicio ORDER BY idImgServicio");
$rows = [];
while ($r = $res->fetch_assoc()) { $rows[] = $r; }

echo "Local filas 599: ".count($rows)."\n";

// Crear SQL
$sql = "SET FOREIGN_KEY_CHECKS=0;\n";
foreach ($rows as $r) {
    $ruta = str_replace("'","''", $r['ruta']);
    $portada = (int)$r['portada'];
    $sql .= "INSERT INTO servicio_img (idImgServicio,idServicio,ruta,portada) SELECT IFNULL(MAX(idImgServicio),0)+1, $idServicio, '$ruta', $portada FROM servicio_img;\n";
}
$sql .= "SET FOREIGN_KEY_CHECKS=1;\n";
file_put_contents('rows_599.sql', $sql);

echo "SQL generado (".strlen($sql)." bytes)\n";

// Subir y ejecutar
$ssh = new SSH2($sshHost, $sshPort); if (!$ssh->login($sshUser,$sshPass)) die('SSH');
$sftp = new SFTP($sshHost, $sshPort); if (!$sftp->login($sshUser,$sshPass)) die('SFTP');
$remoteRows = '/home/' . $sshUser . '/rows_599.sql';
$sftp->put($remoteRows,'rows_599.sql');

echo "Importando...\n";
$out = $ssh->exec('cat ' . $remoteRows . ' | mysql -h ' . $dbHost . ' -u ' . $dbUser . ' -p' . $dbPass . ' ' . $dbName . ' 2>/dev/null');
echo $out."\n";

// Verificar
$out = $ssh->exec('mysql -h ' . $dbHost . ' -u ' . $dbUser . ' -p' . $dbPass . ' -e "SELECT COUNT(*) as total FROM ' . $dbName . '.servicio_img WHERE idServicio=599" 2>/dev/null');
echo $out;

// Limpiar
$ssh->exec('rm -f ' . $remoteRows);
unlink('rows_599.sql');
?>