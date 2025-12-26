<?php
/**
 * Script para obtener todas las rutas del menú y sus archivos correspondientes
 */

require_once(__DIR__ . '/classes/conexion.php');

echo "=== ANÁLISIS DE PÁGINAS A PROTEGER ===\n\n";

// Obtener todas las rutas habilitadas (excepto padres con #)
$stmt = $GLOBALS['pdo']->query("
    SELECT id, label, route, parent_id, enabled
    FROM admin_menu
    WHERE route NOT IN ('#', '', NULL) AND enabled = 1
    ORDER BY parent_id, id
");

$menus = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Total de rutas encontradas: " . count($menus) . "\n\n";

$archivos_protegibles = [];
$archivos_encontrados = [];

foreach ($menus as $menu) {
    $ruta = $menu['route'];
    $archivo = __DIR__ . '/' . $ruta . '.php';
    
    if (file_exists($archivo)) {
        $archivos_encontrados[] = [
            'id' => $menu['id'],
            'label' => $menu['label'],
            'ruta' => $ruta,
            'archivo' => $archivo,
            'relativo' => $ruta . '.php'
        ];
    } else {
        echo "⚠️  No encontrado: {$ruta}.php\n";
    }
}

echo "\n=== ARCHIVOS ENCONTRADOS Y LISTOS PARA PROTEGER ===\n\n";

printf("%-5s %-40s %-30s\n", "ID", "DESCRIPCIÓN", "ARCHIVO");
echo str_repeat("-", 80) . "\n";

foreach ($archivos_encontrados as $item) {
    printf("%-5s %-40s %-30s\n", $item['id'], substr($item['label'], 0, 38), $item['relativo']);
}

echo "\n=== TOTAL: " . count($archivos_encontrados) . " archivos listos para proteger ===\n";

// Guardar en JSON para uso posterior
$json_output = json_encode($archivos_encontrados, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
file_put_contents(__DIR__ . '/paginas_protegibles.json', $json_output);

echo "\nLista guardada en: paginas_protegibles.json\n";

// Crear script de protección automática
$script_proteger = <<<'PHP'
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
PHP;

file_put_contents(__DIR__ . '/proteger_paginas.php', $script_proteger);

echo "\n✓ Script de protección creado: proteger_paginas.php\n";
echo "  Ejecutar con: php admin/proteger_paginas.php\n";

?>
