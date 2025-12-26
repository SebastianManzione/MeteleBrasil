<?php
require 'classes/conexion.php';
$stmt = $pdo->query('SELECT id, label, route, parent_id FROM admin_menu WHERE id IN (1,5,17,33,34) ORDER BY id');
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo $row['id'] . ' | ' . str_pad($row['label'], 30) . ' | parent=' . ($row['parent_id'] ?: 'NULL') . ' | route=' . $row['route'] . PHP_EOL;
}
