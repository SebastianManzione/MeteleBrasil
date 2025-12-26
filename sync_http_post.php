<?php
/**
 * SINCRONIZACIÓN VIA PHP EJECUTADO EN SERVIDOR
 * Último intento: Enviar datos vía HTTP POST a un script en el servidor
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

echo "=== SINCRONIZACIÓN VIA HTTP POST ===\n\n";

// 1. Leer datos locales
echo "1. Leyendo datos de BD local...\n";
$mysqli = new mysqli('localhost', 'root', '', 'metelebrasil');
$result = $mysqli->query("SELECT * FROM servicio_img");
$datos = [];
while ($row = $result->fetch_assoc()) {
    $datos[] = $row;
}
echo "   ✓ " . count($datos) . " registros\n\n";

// 2. Crear script receptor
echo "2. Preparando script en servidor...\n";

$receptor = '<?php
header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die(json_encode(["error" => "POST only"]));
}

$input = file_get_contents("php://input");
$datos = json_decode($input, true);

if (!is_array($datos)) {
    die(json_encode(["error" => "Invalid data"]));
}

$mysqli = new mysqli("__DB_HOST__", "__DB_USER__", "__DB_PASS__", "__DB_NAME__");

if ($mysqli->connect_error) {
    die(json_encode(["error" => "DB: " . $mysqli->connect_error]));
}

// Truncate
$mysqli->query("TRUNCATE TABLE servicio_img");

$insertados = 0;
$errores = 0;

foreach ($datos as $row) {
    $sql = "INSERT INTO servicio_img (idImgServicio, idServicio, ruta, portada) VALUES(" . 
        (int)$row["idImgServicio"] . ", " .
        (int)$row["idServicio"] . ", " .
        "\\"" . $mysqli->real_escape_string($row["ruta"]) . "\\", " .
        (int)$row["portada"] .
    ")";
    
    if ($mysqli->query($sql)) {
        $insertados++;
    } else {
        $errores++;
    }
}

// Verificar
$result = $mysqli->query("SELECT COUNT(*) as total FROM servicio_img");
$row = $result->fetch_assoc();
$total = $row["total"];

$result = $mysqli->query("SELECT COUNT(DISTINCT idServicio) as servicios FROM servicio_img");
$row = $result->fetch_assoc();
$servicios = $row["servicios"];

die(json_encode([
    "insertados" => $insertados,
    "errores" => $errores,
    "total" => $total,
    "servicios" => $servicios
]));
?>';

$receptor = str_replace('__DB_HOST__', addslashes($dbHost), $receptor);
$receptor = str_replace('__DB_USER__', addslashes($dbUser), $receptor);
$receptor = str_replace('__DB_PASS__', addslashes($dbPass), $receptor);
$receptor = str_replace('__DB_NAME__', addslashes($dbName), $receptor);

file_put_contents('receptor.php', $receptor);
echo "   ✓ Script creado\n\n";

// 3. Subir receptor
echo "3. Subiendo receptor a servidor...\n";
$sftp = new SFTP($sshHost, $sshPort);
$sftp->login($sshUser, $sshPass);
$remoteReceptor = '/home/' . $sshUser . '/domains/metelebrasil.com/public_html/receptor.php';
$sftp->put($remoteReceptor, 'receptor.php');
echo "   ✓ Subido\n\n";

// 4. Enviar datos
echo "4. Enviando datos vía HTTP POST...\n";

$json = json_encode($datos);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://metelebrasil.com/receptor.php');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 60);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "   HTTP Status: $http_code\n";

if ($http_code === 200) {
    $result = json_decode($response, true);
    echo "   ✓ Insertados: " . $result['insertados'] . "\n";
    echo "   ✓ Total en BD: " . $result['total'] . "\n";
    echo "   ✓ Servicios: " . $result['servicios'] . "\n\n";
} else {
    echo "   ✗ Error: " . substr($response, 0, 100) . "\n\n";
}

// 5. Limpiar
echo "5. Limpiando...\n";
$ssh = new SSH2($sshHost, $sshPort);
$ssh->login($sshUser, $sshPass);
$ssh->exec('rm -f ' . $remoteReceptor);
unlink('receptor.php');
echo "   ✓ Archivos eliminados\n\n";

echo "✅ ¡SINCRONIZACIÓN COMPLETADA!\n";
echo "\nVerifica en https://metelebrasil.com\n";

$mysqli->close();
?>
