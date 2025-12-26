<?php
// Define entorno si aún no está definido; default a prod si no hay variable
if (!defined('APP_ENV')) {
    $envFromVar = getenv('APP_ENV');
    if ($envFromVar) {
        define('APP_ENV', $envFromVar);
    } else {
        define('APP_ENV', 'prod');
    }
}

$isDev = APP_ENV === 'dev';

$servidor_db = 'localhost';
$usuario_db = $isDev ? 'root' : 'u925692129_metelebrasil';
$senha_db = $isDev ? '' : 'Cambiar2026';
$banco_db = $isDev ? 'metelebrasil' : 'u925692129_metelebrasil';
$mysqli = new mysqli($servidor_db, $usuario_db, $senha_db, $banco_db);
if ($mysqli->connect_errno) {
    error_log("Fallo la conexion a la DB: " . $mysqli->connect_error);
    exit('Error de conexión a la base de datos.');
}
$mysqli->set_charset("utf8mb4");
