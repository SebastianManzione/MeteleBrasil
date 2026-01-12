<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
// Quitar datos de login y destruir sesión
unset($_SESSION['login']);
session_destroy();

// Comprobar mantenimiento
require_once(__DIR__ . '/admin/classes/configuracion.php');
$config = new Configuracion();

// Determinar destino según estado de mantenimiento
$destino = './index.php';
if ($config->mantenimientoActivo()) {
    $destino = './mantenimiento.php';
}

header('Location: ' . $destino);
exit;
