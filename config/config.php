<?php
// =======================================
// CONFIGURACIÓN GLOBAL
// =======================================
// Detectar entorno: usa APP_ENV si está definido; si no, fuerza prod por defecto
if (!defined('APP_ENV')) {
    $debug = true;
    $envFromVar = getenv('APP_ENV');

    if ($envFromVar) {
        define('APP_ENV', $envFromVar);
    } else {
        define('APP_ENV', 'prod');
    }

// Mostrar errores solo en desarrollo
    if (APP_ENV === 'dev' && $debug) {
        ini_set('display_errors', '1');
        ini_set('display_startup_errors', '1');
        error_reporting(E_ALL);
    } else {
        ini_set('display_errors', '0');
        ini_set('log_errors', '1');
    }

// =======================================
// CONFIGURACIÓN BASE DE DATOS
// =======================================

    if (APP_ENV === 'dev') {
        define('DB_HOST', 'localhost');
        define('DB_NAME', 'metelebrasil');
        define('DB_USER', 'root');
        define('DB_PASS', '');
    } else {
        define('DB_HOST', 'localhost');
        define('DB_NAME', 'u925692129_metelebrasil');
        define('DB_USER', 'u925692129_metelebrasil');
        define('DB_PASS', 'Cambiar2026');
    }
}
