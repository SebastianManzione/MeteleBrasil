<?php
/**
 * Configuración de Base de Datos EXPERIMENTAL
 * Usar este archivo durante el desarrollo experimental
 */

// Para usar esta base de datos, agregar al inicio de db.php:
// if (file_exists(__DIR__ . '/../config/db_experimental.php')) {
//     require_once __DIR__ . '/../config/db_experimental.php';
// }

define('DB_EXPERIMENTAL_MODE', true);
define('DB_EXPERIMENTAL_NAME', 'metelebrasil_experimental');
define('DB_EXPERIMENTAL_HOST', '127.0.0.1');
define('DB_EXPERIMENTAL_USER', 'root');
define('DB_EXPERIMENTAL_PASS', '');
