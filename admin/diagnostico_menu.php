<?php
require_once(__DIR__ . '/classes/conexion.php');

echo "=== DIAGNÓSTICO DE MENÚ ===\n\n";

// Contar total de menús
$stmt = $GLOBALS['pdo']->query("SELECT COUNT(*) as cnt FROM admin_menu");
$row = $stmt->fetch(PDO::FETCH_ASSOC);
echo "Total menús en BD: " . $row['cnt'] . "\n\n";

// Mostrar todos los menús
$stmt = $GLOBALS['pdo']->query("
    SELECT id, label, route, parent_id, enabled
    FROM admin_menu
    ORDER BY id
");

$menus = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Primeros 20 menús:\n";
echo str_repeat("-", 80) . "\n";

$count = 0;
foreach ($menus as $menu) {
    if ($count >= 20) break;
    
    $habilitado = $menu['enabled'] ? '✓' : '✗';
    $parent = $menu['parent_id'] ?? 'NULL';
    printf("%d | %-40s | %-20s | Parent: %-5s | %s\n", 
        $menu['id'],
        substr($menu['label'], 0, 38),
        $menu['route'],
        $parent,
        $habilitado
    );
    $count++;
}

// Menús con rutas válidas
echo "\n\nMenús con rutas válidas (no son #):\n";
echo str_repeat("-", 80) . "\n";

$stmt = $GLOBALS['pdo']->query("
    SELECT id, label, route, enabled
    FROM admin_menu
    WHERE route NOT IN ('#', '', NULL) AND enabled = 1
");

$menus_validos = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($menus_validos)) {
    echo "No hay menús con rutas válidas habilitados\n";
} else {
    foreach ($menus_validos as $m) {
        echo "ID " . $m['id'] . ": " . $m['label'] . " → " . $m['route'] . ".php\n";
    }
}

?>
