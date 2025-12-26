<?php
/**
 * Test directo: ejecutar PHP command en el servidor para ver si funciona
 */

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

echo "=== TEST: Ejecutando PHP via SSH ===\n\n";

// Test 1: Ver versión de PHP
echo "1. Versión de PHP:\n";
$output = $ssh->exec('php -v');
echo $output . "\n";

// Test 2: Crear script de test
echo "2. Crear script de test...\n";

$script = '<?php
echo "PHP FUNCIONA\n";
echo "BD disponible\n";

\$mysqli = new mysqli("__DB_HOST__", "__DB_USER__", "__DB_PASS__", "__DB_NAME__");
if (\$mysqli->connect_error) {
    echo "Error BD: " . \$mysqli->connect_error . "\n";
} else {
    echo "Conectado a BD\n";
    
    // Verificar estado actual
    \$result = \$mysqli->query("SELECT COUNT(*) as total FROM servicio_img");
    \$row = \$result->fetch_assoc();
    echo "Registros actuales: " . \$row["total"] . "\n";
}
?>';

file_put_contents('test.php', $script);

$script = str_replace('__DB_HOST__', addslashes($dbHost), $script);
$script = str_replace('__DB_USER__', addslashes($dbUser), $script);
$script = str_replace('__DB_PASS__', addslashes($dbPass), $script);
$script = str_replace('__DB_NAME__', addslashes($dbName), $script);

// Subir y ejecutar
$sftp = new \phpseclib3\Net\SFTP($sshHost, $sshPort);
$sftp->login($sshUser, $sshPass);
$remoteTest = '/home/' . $sshUser . '/test_direct.php';
$sftp->put($remoteTest, 'test.php');

echo "   Subido test_direct.php\n\n";

// Test 3: Ejecutar via SSH
echo "3. Ejecutando test_direct.php via SSH:\n";
$output = $ssh->exec('php ' . $remoteTest);
echo $output . "\n";

// Test 4: Ejecutar query directa en BD
echo "4. Ejecutando consulta directa a BD:\n";
$output = $ssh->exec('php -r "'
    . '\\$mysqli = new mysqli(\"' . $dbHost . '\", \"' . $dbUser . '\", \"' . $dbPass . '\", \"' . $dbName . '\");'
    . '\\$result = \\$mysqli->query(\"SELECT COUNT(*) as total FROM servicio_img\");'
    . '\\$row = \\$result->fetch_assoc();'
    . 'echo \"Registros: \" . \\$row[\"total\"] . PHP_EOL;'
    . '"');
echo $output . "\n";

// Limpiar
$ssh->exec('rm -f ' . $remoteTest);

$ssh->disconnect();
?>
