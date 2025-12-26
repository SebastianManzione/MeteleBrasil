<?php
require_once(__DIR__ . '/classes/conexion.php');

// IDs que debe tener el Prestador (role 2)
$ROLE_PRESTADOR = 2;
$PERMISOS_PRESTADOR = [1, 5, 17, 33, 34]; // Home, Reservas, Estado de reservas, Comisiones Vendedor, Comisiones Prestador

echo "<h2>Configurando permisos para Prestador (role 2)</h2>";

try {
    // Primero, eliminar permisos existentes de role 2
    $stmt = $GLOBALS['pdo']->prepare("DELETE FROM admin_menu_roles WHERE role_id = ?");
    $stmt->execute([$ROLE_PRESTADOR]);
    echo "<p>✓ Borrados permisos existentes de Prestador</p>";
    
    // Insertar los permisos correctos
    $stmt = $GLOBALS['pdo']->prepare("INSERT INTO admin_menu_roles (menu_id, role_id) VALUES (?, ?)");
    foreach ($PERMISOS_PRESTADOR as $menu_id) {
        $stmt->execute([$menu_id, $ROLE_PRESTADOR]);
    }
    echo "<p>✓ Insertados " . count($PERMISOS_PRESTADOR) . " permisos para Prestador</p>";
    
    // Verificar que se insertaron
    $checkStmt = $GLOBALS['pdo']->prepare("SELECT COUNT(*) as total FROM admin_menu_roles WHERE role_id = ?");
    $checkStmt->execute([$ROLE_PRESTADOR]);
    $count = $checkStmt->fetch()['total'];
    
    echo "<p><strong>Permisos actuales del Prestador: " . $count . "</strong></p>";
    
    // Mostrar lista de permisos
    $listStmt = $GLOBALS['pdo']->prepare("
        SELECT m.id, m.label, m.route 
        FROM admin_menu_roles r
        INNER JOIN admin_menu m ON m.id = r.menu_id
        WHERE r.role_id = ?
        ORDER BY m.id
    ");
    $listStmt->execute([$ROLE_PRESTADOR]);
    $permisos = $listStmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h3>Permisos asignados:</h3>";
    echo "<ul>";
    foreach ($permisos as $p) {
        echo "<li>" . $p['id'] . " - " . htmlspecialchars($p['label']) . " (" . htmlspecialchars($p['route']) . ")</li>";
    }
    echo "</ul>";
    
    echo "<p style='color:green'><strong>✅ Permisos configurados correctamente</strong></p>";
    
} catch (Exception $e) {
    echo "<p style='color:red'><strong>❌ Error: " . htmlspecialchars($e->getMessage()) . "</strong></p>";
}
