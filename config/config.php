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
        define('DB_NAME', 'metelebrasil_experimental'); // BD para cambios grosos
        define('DB_USER', 'root');
        define('DB_PASS', '');
    } else {
        // PRODUCCIÓN
        define('DB_HOST', '127.0.0.1');
        define('DB_NAME', 'u925692129_metelebr');
        define('DB_USER', 'u925692129_metelebr');
        define('DB_PASS', 'Nueva3322112233');
    }
}

// =======================================
// CONFIGURACIÓN GOOGLE MAPS API
// =======================================
define('GOOGLE_MAPS_API_KEY', 'AIzaSyDdetJDksIXOsWVt7UQx9EF3ulkhYNJsmE');
