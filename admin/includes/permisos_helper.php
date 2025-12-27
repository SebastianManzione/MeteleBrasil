<?php
/**
 * Helper de Verificación de Permisos
 * Incluir este archivo al inicio de las páginas protegidas
 * 
 * Uso:
 * require_once(__DIR__ . '/includes/header.php');
 * require_once(__DIR__ . '/classes/permisos.php');
 * 
 * $permisos = new PermisosManager($GLOBALS['pdo'], $_SESSION['login'] ?? []);
 * $permisos->verificarAcceso('altaServicio'); // Redirecciona si no tiene permiso
 * 
 * O para solo verificar sin redireccionar:
 * if (!$permisos->tienePermiso('altaServicio')) {
 *     echo "No tienes permiso para acceder aquí";
 *     exit;
 * }
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Incluir conexión si no está incluida
if (!isset($GLOBALS['pdo'])) {
    require_once(__DIR__ . '/../classes/conexion.php');
}

// Incluir clase de permisos si no está cargada
if (!class_exists('PermisosManager')) {
    require_once(__DIR__ . '/../classes/permisos.php');
}

// Crear instancia global de permisos
if (!isset($GLOBALS['permisos'])) {
    $GLOBALS['permisos'] = new PermisosManager(
        $GLOBALS['pdo'],
        $_SESSION['login'] ?? []
    );
}

// Función helper para usar en cualquier lado
function verificarPermisoAcceso($ruta, $redirect = 'index') {
    global $permisos;
    return $permisos->verificarAcceso($ruta, $redirect);
}

// Función helper para solo verificar sin redireccionar
function tienePermiso($ruta) {
    global $permisos;
    return $permisos->tienePermiso($ruta);
}

// Función helper para obtener el usuario actual
function obtenerUsuarioActual() {
    global $permisos;
    return $permisos->obtenerUsuarioActual();
}

// Función helper para debugging
function debugRolesUsuario() {
    global $permisos;
    return $permisos->debugRoles();
}

?>
