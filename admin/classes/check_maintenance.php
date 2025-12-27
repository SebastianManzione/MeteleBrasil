<?php
/**
 * Endpoint AJAX para verificar estado de mantenimiento
 * Usado por página de mantenimiento para auto-recarga
 */

header('Content-Type: application/json');

require_once(__DIR__ . '/configuracion.php');
$config = new Configuracion();

echo json_encode([
    'maintenance_active' => $config->mantenimientoActivo(),
    'message' => $config->obtenerMensajeMantenimiento(),
    'timestamp' => date('Y-m-d H:i:s')
]);
