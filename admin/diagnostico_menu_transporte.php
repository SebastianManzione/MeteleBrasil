<?php
/**
 * Diagnóstico del menú TRANSPORTE
 */
session_start();
require_once(__DIR__ . '/classes/menu.php');
require_once(__DIR__ . '/classes/conexion.php');

$_SESSION['login']['rol'] = $_SESSION['login']['rol'] ?? 1; // Admin
$_SESSION['login']['idUsuario'] = $_SESSION['login']['idUsuario'] ?? 1;

$menu = new AdminMenu();
$tree = $menu->getMenuTreeForRole(1, $_SESSION['login'] ?? []);

echo "<pre>";
echo "=== DIAGNÓSTICO MENÚ TRANSPORTE ===\n\n";

// Buscar TRANSPORTE en el árbol
function findNode($tree, $label, $depth = 0) {
    $indent = str_repeat('  ', $depth);
    foreach ($tree as $node) {
        if ($node['label'] === $label) {
            echo $indent . "✓ {$node['label']} (ID: {$node['id']}, Parent: {$node['parent_id']})\n";
            echo $indent . "  Route: {$node['route']}\n";
            echo $indent . "  Children: " . count($node['children'] ?? []) . "\n";
            
            if (!empty($node['children'])) {
                echo $indent . "  Sub-items:\n";
                foreach ($node['children'] as $child) {
                    echo $indent . "    ├─ {$child['label']} ({$child['route']})\n";
                    if (!empty($child['children'])) {
                        foreach ($child['children'] as $subchild) {
                            echo $indent . "    │  ├─ {$subchild['label']} ({$subchild['route']})\n";
                        }
                    }
                }
            }
            return true;
        }
        
        if (!empty($node['children'])) {
            if (findNode($node['children'], $label, $depth + 1)) {
                return true;
            }
        }
    }
    return false;
}

if (!findNode($tree, 'TRANSPORTE')) {
    echo "✗ TRANSPORTE no encontrado en el árbol\n";
    echo "\nÁrbol completo:\n";
    foreach ($tree as $item) {
        echo "- {$item['label']} (ID: {$item['id']})\n";
    }
}

// Verificar en BD directamente
echo "\n=== VERIFICACIÓN DIRECTA EN BD ===\n";
$stmt = $pdo->query("SELECT id, parent_id, label, route, sort_order FROM admin_menu WHERE id = 41 OR parent_id = 41 ORDER BY parent_id, sort_order");
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($results as $row) {
    $indent = ($row['parent_id'] === 41) ? '  ' : '';
    echo $indent . "ID {$row['id']}: {$row['label']} → {$row['route']}\n";
}

echo "\n=== VERIFICACIÓN DE RUTAS ===\n";
$rutas = ['terminalesLista.php', 'vehiculosTransporteLista.php', 'modeloVehiculosLista.php', 'rutasTransporteLista.php', 'viajesTransporteLista.php'];
foreach ($rutas as $ruta) {
    $file = __DIR__ . '/' . $ruta;
    $existe = file_exists($file) ? '✓ Existe' : '✗ NO existe';
    echo "$ruta: $existe\n";
}

echo "\n</pre>";
?>
