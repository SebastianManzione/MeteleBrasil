<?php
require_once(__DIR__ . '/classes/conexion.php');

echo "=== VERIFICACIÓN DEL SISTEMA DE ROLES Y PERMISOS ===\n\n";

// Verificar conexión
try {
    $pdo = $GLOBALS['pdo'];
    echo "✓ Conexión a base de datos: OK\n\n";
} catch (Exception $e) {
    echo "✗ Error de conexión: " . $e->getMessage() . "\n";
    exit;
}

// Contar registros
$stmt = $pdo->query('SELECT COUNT(*) FROM roles');
echo "Roles en sistema: " . $stmt->fetchColumn() . "\n";

$stmt = $pdo->query('SELECT idRol, rol, descripcion FROM roles ORDER BY idRol');
$roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "\nRoles existentes:\n";
foreach ($roles as $r) {
    echo "  ID {$r['idRol']}: {$r['rol']} - {$r['descripcion']}\n";
}

$stmt = $pdo->query('SELECT COUNT(*) FROM admin_menu WHERE enabled = 1');
echo "\n\nMenús activos: " . $stmt->fetchColumn() . "\n";

$stmt = $pdo->query('SELECT COUNT(*) FROM admin_menu_roles');
echo "Asignaciones de permisos: " . $stmt->fetchColumn() . "\n";

// Permisos por rol
echo "\n\nDistribución de permisos:\n";
$stmt = $pdo->query('
    SELECT r.idRol, r.rol, COUNT(amr.menu_id) as num_permisos
    FROM roles r
    LEFT JOIN admin_menu_roles amr ON r.idRol = amr.role_id
    GROUP BY r.idRol, r.rol
    ORDER BY r.idRol
');
$distribucion = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($distribucion as $d) {
    echo "  {$d['rol']}: {$d['num_permisos']} permisos\n";
}

echo "\n\n✓ Sistema listo para usar en rolesPermisos.php\n";
?>
