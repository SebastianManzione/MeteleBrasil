<?php
/**
 * Test de Sincronización y Rutas de Imágenes
 * Verifica que las imágenes se subieron correctamente a producción
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/ftp.php';
require_once __DIR__ . '/admin/classes/SSHSync.php';

use phpseclib3\Net\SFTP;

echo "=== TEST DE IMÁGENES EN PRODUCCIÓN ===\n\n";

// Conectar a producción vía SFTP
$sftp = new SFTP(SSH_HOST, SSH_PORT);

if (!$sftp) {
    echo "❌ No se pudo conectar a " . SSH_HOST . ":" . SSH_PORT . "\n";
    exit(1);
}

if (!$sftp->login(SSH_USER, SSH_PASS)) {
    echo "❌ Autenticación SSH fallida\n";
    exit(1);
}

echo "✅ Conectado a SFTP\n\n";

// Verificar carpetas remotas
$carpetas = [
    'servicios' => SSH_REMOTE_IMG_SERVICIO,
    'blog' => SSH_REMOTE_IMG_BLOG,
];

foreach ($carpetas as $tipo => $ruta_remota) {
    echo "Verificando: $tipo\n";
    echo "Ruta remota: $ruta_remota\n";
    
    try {
        if ($sftp->is_dir($ruta_remota)) {
            $files = $sftp->nlist($ruta_remota);
            $count = count($files) - 2; // -2 por . y ..
            echo "  ✅ Carpeta existe\n";
            echo "  📊 Archivos: " . max(0, $count) . "\n";
            
            if ($count > 0) {
                echo "  📂 Primeros archivos:\n";
                $primeros = array_slice($files, 2, 5);
                foreach ($primeros as $file) {
                    echo "     - $file\n";
                }
            }
        } else {
            echo "  ❌ Carpeta NO existe\n";
        }
    } catch (Exception $e) {
        echo "  ⚠️ Error: " . $e->getMessage() . "\n";
    }
    
    echo "\n";
}

// Test de URL accesible
echo "=== TEST DE RUTAS WEB ===\n\n";
echo "Para verificar en el navegador, visita:\n";
echo "1. Servicios: https://metelebrasil.com/admin/classes/imgServicio/\n";
echo "2. Blog:      https://metelebrasil.com/admin/classes/imgBlog/\n\n";

// Verificar código HTML
echo "=== VERIFICACIÓN DE CÓDIGO HTML ===\n\n";
echo "Revisa que el código use rutas correctas:\n";
echo "✅ Correcto:   <img src=\"admin/classes/imgServicio/{nombre_foto}\">\n";
echo "✅ También:    <img src=\"/admin/classes/imgServicio/{nombre_foto}\">\n\n";

echo "Archivos a revisar:\n";
echo "- app.index.php (líneas 1853, 2245)\n";
echo "- servicios.php\n";
echo "- index.php\n";

$sftp->disconnect();
echo "\n✅ Test completado\n";
?>
