<?php
/**
 * Script para proteger automáticamente todas las páginas
 * Agrega verificación de permisos basado en adminMenuRoles.php
 */

require_once(__DIR__ . '/classes/conexion.php');

$paginas_json = file_get_contents(__DIR__ . '/archivos_protegibles.json');
$paginas = json_decode($paginas_json, true);

if (empty($paginas)) {
    echo "Error: No se encontró archivos_protegibles.json\n";
    exit;
}

echo "=== PROTEGIENDO PÁGINAS CON VERIFICACIÓN DE PERMISOS ===\n\n";

$protegidas = 0;
$ya_protegidas = 0;
$errores = [];

foreach ($paginas as $pagina) {
    $ruta = trim($pagina['route']);
    $archivo = __DIR__ . '/' . $ruta . '.php';
    
    // Validar que existe
    if (!file_exists($archivo)) {
        continue;
    }
    
    echo "Procesando: $ruta.php ... ";
    
    $contenido = file_get_contents($archivo);
    
    // Verificar si ya tiene la protección
    if (strpos($contenido, 'verificarAcceso') !== false || 
        strpos($contenido, 'PermisosManager') !== false) {
        echo "Ya protegida ✓\n";
        $ya_protegidas++;
        continue;
    }
    
    // Buscar el patrón de includes del header
    // Buscar: include("includes/header.php"); + include("includes/navbar.php"); + include("includes/sidebar.php");
    
    $patron_includes = '/include\(["\']includes\/header\.php["\']\);\s*include\(["\']includes\/navbar\.php["\']\);\s*include\(["\']includes\/sidebar\.php["\']\);/i';
    
    if (!preg_match($patron_includes, $contenido)) {
        echo "⚠ Estructura no compatible\n";
        $errores[] = $ruta;
        continue;
    }
    
    // Crear el código de protección
    $codigo_proteccion = <<<PROT
include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");

// Verificar permisos de acceso
require("classes/permisos.php");
require("includes/permisos_helper.php");
\$permisos = new PermisosManager(\$GLOBALS['pdo'], \$_SESSION['login'] ?? []);
\$permisos->verificarAcceso('$ruta');

PROT;
    
    // Reemplazar los includes con el código de protección
    $contenido_nuevo = preg_replace(
        $patron_includes,
        $codigo_proteccion,
        $contenido,
        1
    );
    
    if ($contenido_nuevo === $contenido) {
        echo "✗ Error: No se pudo modificar\n";
        $errores[] = $ruta;
        continue;
    }
    
    // Crear backup
    $backup_file = $archivo . '.backup';
    if (!file_exists($backup_file)) {
        copy($archivo, $backup_file);
    }
    
    // Escribir archivo modificado
    if (file_put_contents($archivo, $contenido_nuevo)) {
        echo "✓ Protegida\n";
        $protegidas++;
    } else {
        echo "✗ Error al escribir\n";
        $errores[] = $ruta;
    }
}

echo "\n" . str_repeat("=", 70) . "\n";
echo "RESULTADO\n";
echo str_repeat("=", 70) . "\n";
echo "Protegidas:      " . $protegidas . "\n";
echo "Ya protegidas:   " . $ya_protegidas . "\n";
echo "Errores:         " . count($errores) . "\n";
echo "TOTAL:           " . ($protegidas + $ya_protegidas) . " / " . count($paginas) . "\n";

if (!empty($errores)) {
    echo "\n⚠ Páginas con error:\n";
    foreach ($errores as $err) {
        echo "  - $err\n";
    }
}

echo "\n✓ Se crearon backups con extensión .backup\n";
echo "✓ Si hay problemas, restaurar: cp archivo.php.backup archivo.php\n";

?>
