<?php
// =======================================
// CONFIGURACIÓN GLOBAL
// =======================================
// Detectar entorno: usa APP_ENV si está definido, si el hostname contiene "server" asume prod; cualquier otro valor -> dev
if (!defined('APP_ENV')) {
    $host = gethostname();
    $debug = true;
    $envFromVar = getenv('APP_ENV');

    if ($envFromVar) {
        define('APP_ENV', $envFromVar);
    } else {
        define('APP_ENV', (strpos($host, 'server') !== false) ? 'prod' : 'dev');
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
        define('DB_NAME', 'metelebr_metelebrasil');
        define('DB_USER', 'metelebr_admin');
        define('DB_PASS', 'EjGLC(7~lolq7WeW');
    }
}
