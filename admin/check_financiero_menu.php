<?php
require 'classes/conexion.php';
echo "=== ITEMS DENTRO DEL MENÚ FINANCIERO ===\n\n";
$stmt = $pdo->query('SELECT id, label, route, parent_id FROM admin_menu WHERE parent_id = 7 ORDER BY sort_order, id');
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "ID {$row['id']} | " . str_pad($row['label'], 35) . " | route={$row['route']}\n";
}

echo "\n=== PERMISOS DEL PRESTADOR (role_id=2) ===\n\n";
$stmt = $pdo->query('SELECT m.id, m.label, m.route FROM admin_menu m INNER JOIN admin_menu_roles r ON m.id = r.menu_id WHERE r.role_id = 2 AND m.parent_id = 7 ORDER BY m.id');
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "ID {$row['id']} | " . str_pad($row['label'], 35) . " | route={$row['route']}\n";
}
