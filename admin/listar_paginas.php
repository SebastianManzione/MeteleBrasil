<?php
require_once(__DIR__ . '/classes/conexion.php');

echo "=== LISTADO DE PÁGINAS PROTEGIBLES ===\n\n";

// Obtener menús con rutas válidas
$stmt = $GLOBALS['pdo']->query("
    SELECT id, label, route, parent_id, enabled
    FROM admin_menu
    WHERE route != '#' AND route != '' AND route IS NOT NULL
    ORDER BY id
");

$menus = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Total de rutas en BD: " . count($menus) . "\n\n";

$archivos_encontrados = [];
$archivos_no_encontrados = [];

foreach ($menus as $menu) {
    $ruta = trim($menu['route']);
    $archivo = __DIR__ . '/' . $ruta . '.php';
    
    echo sprintf("%-5d %-35s %-25s ", $menu['id'], substr($menu['label'], 0, 33), $ruta . '.php');
    
    if (file_exists($archivo)) {
        echo "✓ EXISTE\n";
        $archivos_encontrados[] = $menu;
    } else {
        echo "✗ NO EXISTE\n";
        $archivos_no_encontrados[] = $menu;
    }
}

echo "\n=== RESUMEN ===\n";
echo "Archivos encontrados: " . count($archivos_encontrados) . "\n";
echo "Archivos no encontrados: " . count($archivos_no_encontrados) . "\n";

if (!empty($archivos_encontrados)) {
    echo "\n=== ARCHIVOS LISTOS PARA PROTEGER ===\n";
    foreach ($archivos_encontrados as $m) {
        echo "  " . $m['route'] . ".php\n";
    }
}

// Guardar lista para el script de protección
file_put_contents(__DIR__ . '/archivos_protegibles.json', json_encode($archivos_encontrados, JSON_PRETTY_PRINT));

echo "\n✓ Guardado en: archivos_protegibles.json\n";

?>
