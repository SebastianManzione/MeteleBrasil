<?php
/**
 * ÚLTIMA ALTERNATIVA: Crear script en webroot y ejecutar por HTTP
 */

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

echo "=== SINCRONIZACIÓN VIA WEBROOT + HTTP ===\n\n";

// Leer datos locales
echo "Leyendo datos locales...\n";
$mysqli = new mysqli('localhost', 'root', '', 'metelebrasil');
$result = $mysqli->query("SELECT * FROM servicio_img");
$registros = [];
while ($row = $result->fetch_assoc()) {
    $registros[] = $row;
}
echo "✓ " . count($registros) . " registros leídos\n\n";

// Crear script PHP con token de seguridad
$token = md5('metelebrasil_sync_' . date('Y-m-d'));

$script = '<?php
// Token: ' . $token . '

if ($_GET["t"] !== "' . $token . '") {
    die("Acceso denegado");
}

$registros = ' . var_export($registros, true) . ';
$mysqli = new mysqli("__DB_HOST__", "__DB_USER__", "__DB_PASS__", "__DB_NAME__");

if ($mysqli->connect_error) {
    die("Error BD: " . $mysqli->connect_error);
}

echo "<pre>";
echo "Truncando tabla...\\n";
$mysqli->query("TRUNCATE TABLE servicio_img");

echo "Insertando registros...\\n";
$ok = 0;
foreach ($registros as $r) {
    $sql = "INSERT INTO servicio_img (idImgServicio, idServicio, ruta, portada) VALUES(" . 
        (int)$r["idImgServicio"] . ", " .
        (int)$r["idServicio"] . ", " .
        "\\\"" . $mysqli->real_escape_string($r["ruta"]) . "\\\", " .
        (int)$r["portada"] .
    ")";
    
    if ($mysqli->query($sql)) {
        $ok++;
        if ($ok % 100 == 0) echo ".";
    }
}

echo "\\n✓ Insertados: $ok\\n";

$result = $mysqli->query("SELECT COUNT(*) as total FROM servicio_img");
$row = $result->fetch_assoc();
echo "Total en BD: " . $row["total"] . "\\n\\n";

$result = $mysqli->query("SELECT COUNT(DISTINCT idServicio) as servicios FROM servicio_img");
$row = $result->fetch_assoc();
echo "Servicios: " . $row["servicios"] . "\\n";

echo "\\nTop 10:\\n";
$result = $mysqli->query("SELECT idServicio, COUNT(*) as cant FROM servicio_img GROUP BY idServicio ORDER BY cant DESC LIMIT 10");
while ($row = $result->fetch_assoc()) {
    echo "  ID " . $row["idServicio"] . ": " . $row["cant"] . " imágenes\\n";
}

echo "</pre>";
echo "<script>setTimeout(() => window.location=\"/\", 3000);</script>";
?>';

// Subir a webroot
echo "Subiendo script a webroot...\n";
$script = str_replace('__DB_HOST__', addslashes($dbHost), $script);
$script = str_replace('__DB_USER__', addslashes($dbUser), $script);
$script = str_replace('__DB_PASS__', addslashes($dbPass), $script);
$script = str_replace('__DB_NAME__', addslashes($dbName), $script);

$sftp = new SFTP($sshHost, $sshPort);
$sftp->login($sshUser, $sshPass);
$remoteWeb = '/home/' . $sshUser . '/domains/metelebrasil.com/public_html/_sincronizar_db.php';
$sftp->put($remoteWeb, $script);
echo "✓ Subido\n\n";

// Acceder via HTTP
echo "Ejecutando via HTTP...\n";
echo "URL: https://metelebrasil.com/_sincronizar_db.php?t=$token\n\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://metelebrasil.com/_sincronizar_db.php?t=$token");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 120);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$err = curl_error($ch);
curl_close($ch);

echo "HTTP Code: $http_code\n";
if ($err) echo "Error: $err\n";

if ($http_code === 200) {
    echo "\nRespuesta del servidor:\n";
    echo $response . "\n";
} else {
    echo "Respuesta: " . substr($response, 0, 200) . "\n";
}

// Limpiar
echo "\n\nLimpiando...\n";
require_once 'vendor/autoload.php';
use phpseclib3\Net\SSH2;

$ssh = new SSH2($sshHost, $sshPort);
$ssh->login($sshUser, $sshPass);
$ssh->exec('rm -f ' . $remoteWeb);

echo "✓ Script eliminado\n\n";

echo "✅ Proceso completado\n";

$mysqli->close();
?>
