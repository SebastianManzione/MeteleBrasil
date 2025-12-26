<?php
/**
 * Script para proteger automáticamente todas las páginas del menú
 * Este script MODIFICA los archivos .php para agregar verificación de permisos
 */

require_once(__DIR__ . '/classes/conexion.php');

$paginas = json_decode(file_get_contents(__DIR__ . '/paginas_protegibles.json'), true);

if (empty($paginas)) {
    echo "Error: No se encontró paginas_protegibles.json\n";
    exit;
}

echo "=== PROTEGIENDO PÁGINAS ===\n\n";

$protegidas = 0;
$ya_protegidas = 0;
$errores = 0;

foreach ($paginas as $pagina) {
    $archivo = $pagina['archivo'];
    $ruta = $pagina['ruta'];
    
    echo "Procesando: {$pagina['relativo']} ... ";
    
    $contenido = file_get_contents($archivo);
    
    // Verificar si ya tiene protección
    if (strpos($contenido, 'verificarAcceso') !== false) {
        echo "Ya protegida ✓\n";
        $ya_protegidas++;
        continue;
    }
    
    // Buscar el patrón de includes iniciales
    if (preg_match('/(<\?php\s*)(include\("includes\/header\.php"\);|require\("includes\/header\.php"\);)/i', $contenido, $matches)) {
        
        // Crear el código de protección
        $codigo_proteccion = <<<PROT
<?php
include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");

// Verificar permisos de acceso
require("classes/permisos.php");
require("includes/permisos_helper.php");
\$permisos = new PermisosManager(\$GLOBALS['pdo'], \$_SESSION['login'] ?? []);
\$permisos->verificarAcceso('$ruta');

PROT;
        
        // Reemplazar el código anterior con el nuevo
        $contenido_nuevo = preg_replace(
            '/(<\?php\s*)(include\("includes\/header\.php"\);\s*include\("includes\/navbar\.php"\);\s*include\("includes\/sidebar\.php"\);)/i',
            $codigo_proteccion,
            $contenido,
            1
        );
        
        // Si no funciona con includes, intentar con requires
        if ($contenido_nuevo === $contenido) {
            $contenido_nuevo = preg_replace(
                '/(<\?php\s*)(require\("includes\/header\.php"\);\s*require\("includes\/navbar\.php"\);\s*require\("includes\/sidebar\.php"\);)/i',
                $codigo_proteccion,
                $contenido,
                1
            );
        }
        
        if ($contenido_nuevo !== $contenido) {
            // Crear backup
            copy($archivo, $archivo . '.backup');
            
            // Escribir el archivo modificado
            file_put_contents($archivo, $contenido_nuevo);
            echo "Protegida ✓\n";
            $protegidas++;
        } else {
            echo "Error: No se pudo modificar\n";
            $errores++;
        }
    } else {
        echo "Estructura no compatible\n";
        $errores++;
    }
}

echo "\n=== RESULTADO ===\n";
echo "Protegidas: $protegidas\n";
echo "Ya protegidas: $ya_protegidas\n";
echo "Errores: $errores\n";
echo "Total: " . ($protegidas + $ya_protegidas) . "\n";
echo "\nNota: Se crearon backups con extensión .backup\n";
?>