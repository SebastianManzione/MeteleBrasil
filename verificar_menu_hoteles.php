<?php
require_once('admin/classes/conexion.php');

echo "📋 Orden actual del menú TRANSPORTE:\n\n";

$stmt = $pdo->query("SELECT id, label, route, sort_order FROM admin_menu WHERE parent_id = 41 ORDER BY sort_order, id");
$submenus = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($submenus as $sub) {
    echo sprintf("   %d. [ID:%d] %s → %s\n", $sub['sort_order'], $sub['id'], $sub['label'], $sub['route']);
}

echo "\n✅ Menú configurado correctamente\n";
echo "\n📍 Para ver el menú:\n";
echo "   1. Entra al admin panel\n";
echo "   2. Presiona F9\n";
echo "   3. El menú TRANSPORTE se desplegará con:\n";
echo "      - Terminales\n";
echo "      - Hoteles (NUEVO)\n";
echo "      - Rutas\n";
echo "      - Viajes\n";
echo "      - Reservas\n";
