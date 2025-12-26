<?php
/**
 * SINCRONIZACIÓN CORRECTA DE BD
 * Usando la ubicación real del sitio:
 * /home/u925692129/domains/metelebrasil.com/public_html/
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

echo "=== SINCRONIZACIÓN DE BD (UBICACIÓN CORRECTA) ===\n\n";

// ==========================================
// PASO 1: Leer datos desde BD local
// ==========================================
echo "PASO 1: Leyendo datos de BD local...\n";

$mysqli = new mysqli('localhost', 'root', '', 'metelebrasil');
if ($mysqli->connect_error) {
    die("❌ Error conexión: " . $mysqli->connect_error);
}

$result = $mysqli->query("SELECT * FROM servicio_img");
$datos = [];
while ($row = $result->fetch_assoc()) {
    $datos[] = $row;
}

echo "✓ Leídos " . count($datos) . " registros\n\n";

// ==========================================
// PASO 2: Crear script PHP con los datos inline
// ==========================================
echo "PASO 2: Preparando script de inserción...\n";

// Crear un PHP que se ejecutará en el servidor
$php_code = '<?php
echo "Iniciando inserción de datos...\\n";

$mysqli = new mysqli("__DB_HOST__", "__DB_USER__", "__DB_PASS__", "__DB_NAME__");

if ($mysqli->connect_error) {
    echo "Error de BD: " . $mysqli->connect_error;
    exit(1);
}

echo "Conectado a BD\\n";

// Datos a insertar
$datos = ' . var_export($datos, true) . ';

// Limpiar tabla existente
echo "Limpiando tabla...\\n";
$mysqli->query("TRUNCATE TABLE servicio_img");

// Insertar datos
echo "Insertando " . count($datos) . " registros...\\n";
$insertados = 0;
$errores = 0;

foreach ($datos as $row) {
    $sql = "INSERT INTO servicio_img VALUES(" . 
        (int)$row["idImgServicio"] . ", " .
        (int)$row["idServicio"] . ", " .
        "\\"" . $mysqli->real_escape_string($row["ruta"]) . "\\", " .
        (int)$row["portada"] .
    ")";
    
    if ($mysqli->query($sql)) {
        $insertados++;
    } else {
        $errores++;
        if ($errores <= 3) {
            echo "Error: " . $mysqli->error . PHP_EOL;
        }
    }
}

echo "Insertados: $insertados\\n";
if ($errores > 0) {
    echo "Errores: $errores\\n";
}

// Verificación
$result = $mysqli->query("SELECT COUNT(*) as total FROM servicio_img");
$row = $result->fetch_assoc();
echo "Total en BD: " . $row["total"] . "\\n";

$result = $mysqli->query("SELECT COUNT(DISTINCT idServicio) as servicios FROM servicio_img");
$row = $result->fetch_assoc();
echo "Servicios: " . $row["servicios"] . "\\n";

echo "\\n✅ INSERCIÓN COMPLETADA\\n";
?>';

file_put_contents('insert_data.php', $php_code);

// Inyectar credenciales en script remoto
$php_code = str_replace('__DB_HOST__', addslashes($dbHost), $php_code);
$php_code = str_replace('__DB_USER__', addslashes($dbUser), $php_code);
$php_code = str_replace('__DB_PASS__', addslashes($dbPass), $php_code);
$php_code = str_replace('__DB_NAME__', addslashes($dbName), $php_code);
file_put_contents('insert_data.php', $php_code);
echo "✓ Script creado\n\n";

// ==========================================
// PASO 3: Subir a servidor correcto
// ==========================================
echo "PASO 3: Subiendo a servidor...\n";

$sftp = new SFTP($sshHost, $sshPort);
if (!$sftp->login($sshUser, $sshPass)) {
    die("❌ Error SFTP\n");
}
$remote_path = '/home/' . $sshUser . '/domains/metelebrasil.com/public_html/insert_data.php';
$sftp->put($remote_path, 'insert_data.php');
echo "✓ Subido a $remote_path\n\n";

// ==========================================
// PASO 4: Ejecutar en servidor
// ==========================================
echo "PASO 4: Ejecutando en servidor...\n";

$ssh = new SSH2($sshHost, $sshPort);
if (!$ssh->login($sshUser, $sshPass)) {
    die("❌ Error SSH\n");
}

$output = $ssh->exec('cd /home/u925692129/domains/metelebrasil.com/public_html && php insert_data.php');
echo $output . "\n";

// ==========================================
// PASO 5: Verificar imágenes en servidor
// ==========================================
echo "\nPASO 5: Verificando estructura de archivos...\n";

$cmd = 'ls -la /home/' . $sshUser . '/domains/metelebrasil.com/public_html/admin/classes/imgServicio/ 2>/dev/null | wc -l';
$output = $ssh->exec($cmd);
echo "Archivos en imgServicio: " . trim($output) . "\n";

$cmd = 'find /home/' . $sshUser . '/domains/metelebrasil.com/public_html/admin/classes/imgServicio/ -type f 2>/dev/null | wc -l';
$output = $ssh->exec($cmd);
echo "Total de imágenes (servicios): " . trim($output) . "\n";

// ==========================================
// PASO 6: Limpiar
// ==========================================
echo "\nPASO 6: Limpiando...\n";
$ssh->exec("rm -f $remote_path");
unlink('insert_data.php');
echo "✓ Archivos temporales eliminados\n\n";

// ==========================================
// PASO 7: Verificación FINAL
// ==========================================
echo "PASO 7: Verificación final...\n";

$output = $ssh->exec('mysql -h ' . $dbHost . ' -u ' . $dbUser . ' -p' . $dbPass . ' ' . $dbName . ' -e "SELECT COUNT(*) as total FROM servicio_img; SELECT COUNT(DISTINCT idServicio) as servicios FROM servicio_img" 2>/dev/null');

echo $output;

echo "\n✅ ¡SINCRONIZACIÓN COMPLETADA!\n";
echo "\nSiguientes pasos:\n";
echo "1. Visita https://metelebrasil.com\n";
echo "2. Limpia cache del navegador (Ctrl+Shift+Supr)\n";
echo "3. Las imágenes deberían aparecer ahora\n";

$ssh->disconnect();
$mysqli->close();
?>
