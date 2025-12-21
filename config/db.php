<?php
$servidor_db = 'localhost';
$usuario_db = 'root';
$senha_db = '';
$banco_db = 'metelebrasil';
$mysqli = new mysqli($servidor_db, $usuario_db, $senha_db, $banco_db);
if ($mysqli->connect_errno) {
    error_log("Fallo la conexion a la DB: " . $mysqli->connect_error);
    exit('Error de conexión a la base de datos.');
}

