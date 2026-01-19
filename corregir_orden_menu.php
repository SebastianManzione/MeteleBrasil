<?php
require_once('admin/classes/conexion.php');

echo "🔧 Corrigiendo orden del menú...\n\n";

// Actualizar órdenes
$pdo->exec("UPDATE admin_menu SET sort_order = 3 WHERE id = 43"); // Rutas
$pdo->exec("UPDATE admin_menu SET sort_order = 4 WHERE id = 45"); // Viajes  
$pdo->exec("UPDATE admin_menu SET sort_order = 5 WHERE id = 46"); // Reservas

echo "✅ Órdenes actualizados:\n";
echo "   1. Terminales (ID: 42)\n";
echo "   2. Hoteles (ID: 52) ← NUEVO\n";
echo "   3. Rutas (ID: 43)\n";
echo "   4. Viajes (ID: 45)\n";
echo "   5. Reservas (ID: 46)\n\n";

// Verificar
$stmt = $pdo->query("SELECT id, label, sort_order FROM admin_menu WHERE parent_id = 41 ORDER BY sort_order, id");
$submenus = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "📋 Verificación:\n";
foreach ($submenus as $sub) {
    echo sprintf("   %d. [ID:%d] %s\n", $sub['sort_order'], $sub['id'], $sub['label']);
}
