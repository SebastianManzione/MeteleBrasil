#!/usr/bin/env php
<?php
/**
 * Script de Sincronización de Imágenes a Producción vía SSH/SFTP
 * ==============================================================
 * 
 * Uso:
 *   php sync_images.php [opción]
 * 
 * Opciones:
 *   servicios     - Sincroniza solo imágenes de servicios
 *   blog          - Sincroniza solo imágenes de blog
 *   categorias    - Sincroniza solo imágenes de categorías
 *   all           - Sincroniza todo (por defecto)
 *   test          - Prueba la conexión SSH sin sincronizar
 * 
 * Ejemplos:
 *   php sync_images.php all
 *   php sync_images.php servicios
 *   php sync_images.php test
 */

// Incluir configuración
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/ftp.php';  // Ahora contiene SSH_* defines
require_once __DIR__ . '/admin/classes/SSHSync.php';

// Colores para terminal
class Colors {
    const GREEN = "\033[32m";
    const RED = "\033[31m";
    const YELLOW = "\033[33m";
    const BLUE = "\033[34m";
    const CYAN = "\033[36m";
    const RESET = "\033[0m";
}

function printHeader() {
    echo Colors::BLUE . "╔════════════════════════════════════════════════════╗\n";
    echo "║   SINCRONIZADOR DE IMÁGENES - METELEBRASIL (SSH)  ║\n";
    echo "╚════════════════════════════════════════════════════╝\n" . Colors::RESET;
    echo "\n";
}

function printSection($title) {
    echo Colors::BLUE . "\n→ $title\n" . Colors::RESET;
    echo str_repeat("-", 50) . "\n";
}

function printSuccess($message) {
    echo Colors::GREEN . "✓ $message\n" . Colors::RESET;
}

function printError($message) {
    echo Colors::RED . "✗ $message\n" . Colors::RESET;
}

function printInfo($message) {
    echo Colors::YELLOW . "ℹ $message\n" . Colors::RESET;
}

function printDebug($message) {
    echo Colors::CYAN . "► $message\n" . Colors::RESET;
}

// Verificar que se ejecuta desde CLI
if (php_sapi_name() !== 'cli') {
    die("Este script debe ejecutarse desde línea de comandos (CLI).\n");
}

// Mostrar encabezado
printHeader();

// Obtener opción
$option = isset($argv[1]) ? strtolower($argv[1]) : 'all';

if (!in_array($option, ['all', 'servicios', 'blog', 'categorias', 'test'])) {
    printError("Opción inválida: $option");
    echo "\nOpciones válidas: all, servicios, blog, categorias, test\n";
    exit(1);
}

// Verificar ambiente
printSection("VERIFICACIÓN DE ENTORNO");
printInfo("Ambiente detectado: " . APP_ENV);

if (APP_ENV !== 'prod') {
    printError("⚠️  Estás en ambiente de DESARROLLO");
    printInfo("Este script está configurado para PRODUCCIÓN");
    echo "\nPara cambiar a producción, configura APP_ENV en config/config.php\n";
    exit(0);
}

// Verificar extensión SSH2
printSection("VERIFICACIÓN DE DEPENDENCIAS");
if (!class_exists('phpseclib3\Net\SFTP')) {
    printError("phpseclib NO está instalado");
    echo "\n📦 INSTALACIÓN:\n";
    echo "   composer require phpseclib/phpseclib\n";
    exit(1);
}

printSuccess("phpseclib disponible");

// Intentar conexión SSH
printSection("CONEXIÓN SSH/SFTP");
try {
    printInfo("Conectando a: " . SSH_HOST . ":" . SSH_PORT);
    printDebug("Usuario: " . SSH_USER);
    printDebug("Autenticación: " . (SSH_AUTH_TYPE === 'key' ? 'Clave Privada (RSA)' : 'Contraseña'));
    
    $sync = new SSHSync();
    printSuccess("Conexión SSH/SFTP establecida ✓");
    
} catch (Exception $e) {
    printError("Error de conexión: " . $e->getMessage());
    printError("Verifica las credenciales en config/ftp.php");
    exit(1);
}

// Si es test, solo verifica conexión
if ($option === 'test') {
    printSection("PRUEBA DE CONEXIÓN");
    printSuccess("La conexión SSH funciona correctamente");
    printInfo("Estás conectado a: " . SSH_HOST . ":" . SSH_PORT);
    printInfo("Usuario: " . SSH_USER);
    echo "\n✓ Test completado exitosamente\n\n";
    exit(0);
}

// Ejecutar sincronización
printSection("SINCRONIZACIÓN");

try {
    switch ($option) {
        case 'servicios':
            printInfo("Sincronizando imágenes de servicios...");
            $sync->syncImagenesServicios();
            break;
        case 'blog':
            printInfo("Sincronizando imágenes de blog...");
            $sync->syncImagenesBlog();
            break;
        case 'categorias':
            printInfo("Sincronizando imágenes de categorías...");
            $sync->syncImagenesCategorias();
            break;
        case 'all':
        default:
            printInfo("Sincronizando TODAS las imágenes...");
            $sync->syncAll();
    }

    // Mostrar log
    printSection("DETALLES DE SINCRONIZACIÓN");
    $log = $sync->getLog();
    foreach ($log as $line) {
        if (strpos($line, '✓') === 0) {
            printSuccess(substr($line, 2));
        } elseif (strpos($line, '✗') === 0) {
            printError(substr($line, 2));
        } elseif (strpos($line, '===') === 0) {
            echo Colors::CYAN . $line . Colors::RESET . "\n";
        } elseif (strpos($line, '→') === 0) {
            echo Colors::BLUE . $line . Colors::RESET . "\n";
        } else {
            echo $line . "\n";
        }
    }

    // Resumen final
    printSection("RESUMEN");
    printSuccess("Archivos subidos: " . $sync->getSuccessCount());
    
    if ($sync->getErrorCount() > 0) {
        printError("Errores encontrados: " . $sync->getErrorCount());
    }

    // Desconectar
    $sync->disconnect();
    printSuccess("Desconexión SSH completada");

    echo "\n✓ Sincronización finalizada exitosamente\n\n";
    exit(0);

} catch (Exception $e) {
    printError("Error durante sincronización: " . $e->getMessage());
    exit(1);
}
?>
