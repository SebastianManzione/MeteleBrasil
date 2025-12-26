<?php
require_once(__DIR__ . '/classes/conexion.php');

echo "=== Asignación de permisos por rol ===\n\n";

// Roles:
// 1 = Admin (acceso total)
// 2 = Vendedor
// 3 = Prestador
// 4 = Cobrador

// Primero, limpiar permisos existentes para reconfigurar
$GLOBALS['pdo']->exec("DELETE FROM admin_menu_roles");
echo "✅ Permisos anteriores eliminados\n\n";

// Obtener todos los menús
$stmt = $GLOBALS['pdo']->query("SELECT id, label, route, parent_id FROM admin_menu ORDER BY id");
$menus = $stmt->fetchAll(PDO::FETCH_ASSOC);

$assigned = 0;

foreach ($menus as $menu) {
    $menuId = $menu['id'];
    $label = $menu['label'];
    $route = $menu['route'];
    $roles = [];
    
    // Admin siempre tiene acceso a todo
    $roles[] = 1;
    
    // === PRESTADORES (rol 3) ===
    // Solo: Reservas (estado), Comisiones Prestador
    if (in_array($route, ['reservasEstado', 'financieroSalidas']) ||
        ($menu['parent_id'] == 5 && $route === '#')) { // Parent de Reservas
        $roles[] = 3;
    }
    
    // === VENDEDORES (rol 2) ===
    // Reservas completas, comisiones vendedor, usuarios
    if (stripos($label, 'reserva') !== false || 
        stripos($label, 'carrito') !== false ||
        in_array($route, ['carritosLista', 'reservaDetalles', 'reservasEstado', 
                          'comisionesLista', 'usuariosLista']) ||
        ($menu['parent_id'] == 5)) { // Children de Reservas
        $roles[] = 2;
    }
    
    // === COBRADORES (rol 4) ===
    // Financiero completo
    if (stripos($label, 'financiero') !== false || 
        stripos($label, 'cobro') !== false ||
        stripos($label, 'comprobante') !== false ||
        in_array($route, ['financieroLista', 'financieroSalidas', 'cobroSignal', 
                          'comprobantesLista', 'comisionesLista']) ||
        ($menu['parent_id'] == 7)) { // Children de Financiero
        $roles[] = 4;
    }
    
    // Home para todos (solo el item raíz, no todo)
    if ($route === 'index' && $menu['parent_id'] === null) {
        $roles[] = 2;
        $roles[] = 3;
        $roles[] = 4;
    }
    
    // Insertar permisos
    $roles = array_unique($roles);
    foreach ($roles as $roleId) {
        $stmt = $GLOBALS['pdo']->prepare("INSERT IGNORE INTO admin_menu_roles (menu_id, role_id) VALUES (?, ?)");
        $stmt->execute([$menuId, $roleId]);
        $assigned++;
    }
    
    echo "  {$label} → Roles: " . implode(', ', $roles) . "\n";
}

echo "\n✅ Total de asignaciones: $assigned\n\n";

// Mostrar resumen por rol
echo "=== Resumen por Rol ===\n";
$stmt = $GLOBALS['pdo']->query("
    SELECT r.role_id, COUNT(*) as total 
    FROM admin_menu_roles r 
    GROUP BY r.role_id 
    ORDER BY r.role_id
");
while ($row = $stmt->fetch()) {
    $roleName = match($row['role_id']) {
        1 => 'Admin',
        2 => 'Vendedor',
        3 => 'Prestador',
        4 => 'Cobrador',
        default => "Rol {$row['role_id']}"
    };
    echo "  {$roleName}: {$row['total']} ítems\n";
}

echo "\n✅ Permisos configurados correctamente\n";
?>
