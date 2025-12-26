<?php
/**
 * DEBUG: Ver qué responde el servidor
 */

require_once 'vendor/autoload.php';
use phpseclib3\Net\SFTP;

// Crear un receptor simple que responda
$creds = require __DIR__ . '/config/creds_loader.php';
$sshHost = $creds['ssh']['host'] ?? 'localhost';
$sshPort = (int)($creds['ssh']['port'] ?? 22);
$sshUser = $creds['ssh']['user'] ?? '';
$sshPass = $creds['ssh']['pass'] ?? '';

$dbHost = $creds['db']['host'] ?? 'localhost';
$dbUser = $creds['db']['user'] ?? '';
$dbPass = $creds['db']['pass'] ?? '';
$dbName = $creds['db']['name'] ?? '';
$receptor = '<?php
header("Content-Type: application/json");

$mysqli = new mysqli("localhost", "u925692129_metelebrasil", "Cambiar2026", "u925692129_metelebrasil");
$mysqli = new mysqli("__DB_HOST__", "__DB_USER__", "__DB_PASS__", "__DB_NAME__");
if ($mysqli->connect_error) {
    die(json_encode(["error" => $mysqli->connect_error]));
}

$result = $mysqli->query("SELECT COUNT(*) as total FROM servicio_img");
$row = $result->fetch_assoc();

die(json_encode(["total" => $row["total"]]));
?>';

file_put_contents('test_receptor.php', $receptor);
$receptor = str_replace('__DB_HOST__', addslashes($dbHost), $receptor);
$receptor = str_replace('__DB_USER__', addslashes($dbUser), $receptor);
$receptor = str_replace('__DB_PASS__', addslashes($dbPass), $receptor);
$receptor = str_replace('__DB_NAME__', addslashes($dbName), $receptor);
$file_put = file_put_contents('test_receptor.php', $receptor);
// Subir
$sftp = new SFTP('185.173.111.212', 65002);
$sftp = new SFTP($sshHost, $sshPort);
$sftp->login($sshUser, $sshPass);
$remotePath = '/home/' . $sshUser . '/domains/metelebrasil.com/public_html/test_receptor.php';
$sftp->put($remotePath, 'test_receptor.php');
// Probar
echo "Enviando petición...\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://metelebrasil.com/test_receptor.php');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([]));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_VERBOSE, true);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP: $http_code\n";
echo "Response: " . $response . "\n";
echo "Response length: " . strlen($response) . "\n";
echo "Decoded: " . json_encode(json_decode($response, true)) . "\n";
?>
