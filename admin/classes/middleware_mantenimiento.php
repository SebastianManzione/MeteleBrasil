<?php
/**
 * Middleware de Mantenimiento
 * Incluir al inicio de includes/navbar.php para verificar estado
 */

// Verificar si está en modo mantenimiento
function verificarMantenimiento() {
    // No ejecutar en CLI o si REQUEST_URI no existe
    if (php_sapi_name() === 'cli' || !isset($_SERVER['REQUEST_URI'])) {
        return;
    }
    
    // No ejecutar el middleware si estamos en rutas del admin
    $es_ruta_admin = strpos($_SERVER['REQUEST_URI'], '/admin/') !== false;
    if ($es_ruta_admin) {
        return; // Salir sin verificar
    }
    
    // Cargar configuración (tolerante si falla)
    try {
        require_once(__DIR__ . '/configuracion.php');
        $config = new Configuracion();
        
        // Si está en mantenimiento y NO es admin, redirigir
        if ($config->mantenimientoActivo()) {
            // Detectar ruta actual
            $ruta_actual = basename($_SERVER['PHP_SELF']);
            
            // Permitir acceso a login, mantenimiento.php
            $rutas_permitidas = ['login.php', 'mantenimiento.php', 'googleLogin.php', 'recuperar_contrasena.php'];
            
            // Si es admin logueado, dejar pasar
            $es_admin_logueado = isset($_SESSION['login']['rol']) && $_SESSION['login']['rol'] == 1;
            
            if (!$es_admin_logueado && !in_array($ruta_actual, $rutas_permitidas)) {
                // Construir base del subdirectorio actual (p.ej. /metelebrasil_dev)
                $base_dir = rtrim(dirname($_SERVER['PHP_SELF']), '/');
                // Redirigir a página de mantenimiento dentro del subdirectorio
                header('Location: ' . $base_dir . '/mantenimiento.php');
                exit;
            }
        }
    } catch (Throwable $e) {
        // En dev, solo registrar y continuar
        @file_put_contents(__DIR__ . '/../../logs/middleware.log', date('c') . ' error: ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
    }
}

// Ejecutar al cargar
verificarMantenimiento();
