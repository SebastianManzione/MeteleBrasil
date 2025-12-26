<?php
/**
 * Verificar permisos actuales del rol Prestador
 */

require_once(__DIR__ . '/classes/conexion.php');

echo "=== DIAGNÓSTICO DE PERMISOS - ROL PRESTADOR ===\n\n";

// Obtener rol Prestador
$stmt = $GLOBALS['pdo']->query("SELECT idRol FROM roles WHERE rol LIKE '%Prestador%'");
$rolPrestador = $stmt->fetch(PDO::FETCH_ASSOC);
$rolId = $rolPrestador['idRol'] ?? null;

if (!$rolId) {
    echo "Error: No se encontró rol Prestador\n";
    exit;
}

echo "Rol Prestador ID: $rolId\n\n";

// Obtener permisos del rol Prestador
$stmt = $GLOBALS['pdo']->prepare("
    SELECT am.id, am.label, am.route
    FROM admin_menu am
    INNER JOIN admin_menu_roles amr ON am.id = amr.menu_id
    WHERE amr.role_id = ?
    ORDER BY am.id
");
$stmt->execute([$rolId]);
$permisos = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Permisos actuales del Prestador (" . count($permisos) . "):\n";
echo str_repeat("-", 80) . "\n";

foreach ($permisos as $p) {
    echo sprintf("ID %-3d | %-40s | %s\n", $p['id'], substr($p['label'], 0, 38), $p['route']);
}

// Verificar específicamente altaServicio
echo "\n\n=== VERIFICACIÓN ESPECÍFICA ===\n";
$stmt = $GLOBALS['pdo']->prepare("
    SELECT am.id, am.label, am.route, 
           CASE WHEN amr.menu_id IS NOT NULL THEN 'SÍ' ELSE 'NO' END as tiene_permiso
    FROM admin_menu am
    LEFT JOIN admin_menu_roles amr ON am.id = amr.menu_id AND amr.role_id = ?
    WHERE am.route = 'altaServicio'
");
$stmt->execute([$rolId]);
$altaServicio = $stmt->fetch(PDO::FETCH_ASSOC);

if ($altaServicio) {
    echo "Página: altaServicio\n";
    echo "Label: " . $altaServicio['label'] . "\n";
    echo "Prestador tiene permiso: " . $altaServicio['tiene_permiso'] . "\n";
    
    if ($altaServicio['tiene_permiso'] === 'SÍ') {
        echo "\n⚠️  PROBLEMA ENCONTRADO: Prestador SÍ tiene acceso a altaServicio\n";
        echo "El rol Prestador tiene permisos que no debería tener.\n";
        echo "\nSolución: Usar adminMenuRoles.php para remover este permiso\n";
    } else {
        echo "\n✓ OK: Prestador NO tiene permiso a altaServicio\n";
    }
}

?>
