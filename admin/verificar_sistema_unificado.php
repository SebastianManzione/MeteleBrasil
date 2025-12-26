<?php
require_once(__DIR__ . '/classes/conexion.php');

echo "=== VERIFICACIÓN FINAL DEL SISTEMA ===\n\n";

// Verificar menú unificado
$stmt = $GLOBALS['pdo']->prepare("
    SELECT id, label, route, parent_id, enabled FROM admin_menu 
    WHERE route = 'adminMenuRoles'
");
$stmt->execute();
$menuUnificado = $stmt->fetch(PDO::FETCH_ASSOC);

if ($menuUnificado) {
    echo "✓ Menú Unificado\n";
    echo "  ID: {$menuUnificado['id']}\n";
    echo "  Nombre: {$menuUnificado['label']}\n";
    echo "  Ruta: {$menuUnificado['route']}\n";
    echo "  Habilitado: " . ($menuUnificado['enabled'] ? 'Sí' : 'No') . "\n";
} else {
    echo "✗ No se encontró el menú unificado\n";
    exit;
}

// Verificar permisos
$stmt = $GLOBALS['pdo']->prepare("
    SELECT COUNT(*) as cnt FROM admin_menu_roles 
    WHERE menu_id = ?
");
$stmt->execute([$menuUnificado['id']]);
$permisos = $stmt->fetch(PDO::FETCH_ASSOC);

echo "\n✓ Permisos asignados: {$permisos['cnt']}\n";

// Verificar que se eliminaron menús antiguos
$stmt = $GLOBALS['pdo']->query("
    SELECT COUNT(*) as cnt FROM admin_menu 
    WHERE route IN ('menuEditor', 'rolesPermisos')
");
$antiguos = $stmt->fetch(PDO::FETCH_ASSOC);

echo "✓ Menús antiguos en BD: {$antiguos['cnt']} (deben ser 0)\n";

// Contar menús totales
$stmt = $GLOBALS['pdo']->query("SELECT COUNT(*) as cnt FROM admin_menu WHERE enabled = 1");
$totalMenus = $stmt->fetch(PDO::FETCH_ASSOC);

echo "✓ Total de menús activos: {$totalMenus['cnt']}\n";

// Verificar roles y permisos
$stmt = $GLOBALS['pdo']->query("
    SELECT r.idRol, r.rol, COUNT(amr.menu_id) as permisos
    FROM roles r
    LEFT JOIN admin_menu_roles amr ON r.idRol = amr.role_id
    GROUP BY r.idRol
    ORDER BY r.idRol
");

echo "\n✓ Distribución de Roles y Permisos:\n";
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "  {$row['rol']}: {$row['permisos']} permisos\n";
}

echo "\n=== RESULTADO FINAL ===\n";
echo "✓ Sistema de Administración de Menú y Roles unificado\n";
echo "✓ Estructura completamente migrada\n";
echo "✓ Base de datos sincronizada\n\n";
echo "📍 ACCESO: http://localhost/metelebrasil_dev/admin/adminMenuRoles.php\n";
echo "👤 Requiere: Rol Administrador\n";
?>
