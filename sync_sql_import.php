<?php
/**
 * FINAL SYNC: Copia BD desde local y ejecuta inserción en servidor
 * Este es el último intento, usando un enfoque más simple
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

echo "=== SINCRONIZACIÓN FINAL ===\n\n";

// Leer datos locales
echo "1. Leyendo BD local...\n";
$mysqli = new mysqli('localhost', 'root', '', 'metelebrasil');
$result = $mysqli->query("SELECT * FROM servicio_img");
$datos = [];
while ($row = $result->fetch_assoc()) {
    $datos[] = $row;
}
echo "   ✓ " . count($datos) . " registros leídos\n\n";

// Conectar SSH
echo "2. Conectando a servidor...\n";
$ssh = new SSH2($sshHost, $sshPort);
$ssh->login($sshUser, $sshPass);
echo "   ✓ Conectado\n\n";

// Crear archivo con los INSERTs en SQL puro  
echo "3. Creando archivo SQL...\n";

$sql = "TRUNCATE TABLE servicio_img;\n";

foreach ($datos as $d) {
    $idImgServicio = (int)$d['idImgServicio'];
    $idServicio = (int)$d['idServicio'];
    $ruta = str_replace("'", "''", $d['ruta']);
    $portada = (int)$d['portada'];
    
    $sql .= "INSERT INTO servicio_img (idImgServicio, idServicio, ruta, portada) VALUES ($idImgServicio, $idServicio, '$ruta', $portada);\n";
}

file_put_contents('import.sql', $sql);
echo "   ✓ Archivo creado (" . number_format(strlen($sql) / 1024, 2) . " KB)\n\n";

// Subir SQL
echo "4. Subiendo archivo a servidor...\n";
$sftp = new SFTP($sshHost, $sshPort);
$sftp->login($sshUser, $sshPass);
$remoteImport = '/home/' . $sshUser . '/import.sql';
$sftp->put($remoteImport, 'import.sql');
echo "   ✓ Subido\n\n";

// Ejecutar con mariadb CLI
echo "5. Ejecutando SQL en servidor...\n";

$cmd = '/usr/bin/mariadb -h ' . $dbHost . ' -u ' . $dbUser . ' -p' . $dbPass . ' ' . $dbName . ' < ' . $remoteImport . ' 2>&1';

$output = $ssh->exec($cmd);

if (empty($output)) {
    echo "   ✓ SQL ejecutado sin errores\n\n";
} else {
    echo "   Salida: $output\n\n";
}

// Verificar
echo "6. Verificando resultado...\n";

$output = $ssh->exec('/usr/bin/mariadb -h ' . $dbHost . ' -u ' . $dbUser . ' -p' . $dbPass . ' ' . $dbName . ' -e "SELECT COUNT(*) FROM servicio_img" 2>/dev/null');

$count = trim(str_replace(['COUNT(*)', '---', '---'], '', $output));
$count = preg_replace('/[^0-9]/', '', $count);

echo "   Registros en BD producción: " . $count . "\n\n";

// Top 10
echo "7. Top 10 servicios...\n";
$output = $ssh->exec('/usr/bin/mariadb -h ' . $dbHost . ' -u ' . $dbUser . ' -p' . $dbPass . ' ' . $dbName . ' -e "SELECT idServicio, COUNT(*) as cant FROM servicio_img GROUP BY idServicio ORDER BY cant DESC LIMIT 10" 2>/dev/null');
echo $output . "\n";

// Limpiar
echo "8. Limpiando...\n";
$ssh->exec('rm -f ' . $remoteImport);
unlink('import.sql');
echo "   ✓ Archivos temporales eliminados\n\n";

echo "✅ ¡SINCRONIZACIÓN COMPLETADA!\n";
echo "\nLas imágenes deberían estar disponibles en https://metelebrasil.com\n";
echo "Si no aparecen, limpia cache del navegador (Ctrl+Shift+Supr)\n";

$ssh->disconnect();
$mysqli->close();
?>
