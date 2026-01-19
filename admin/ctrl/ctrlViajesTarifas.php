<?php
/**
 * AJAX Controller para Viajes y Tarifas
 * Maneja CRUD de viajes y tarifas de transporte
 */

require_once(__DIR__ . '/../classes/transporte.php');

$action = isset($_GET['action']) ? $_GET['action'] : (isset($_POST['action']) ? $_POST['action'] : '');

header('Content-Type: application/json; charset=utf-8');

$response = [
    'success' => false,
    'message' => 'Acción no especificada',
    'data'    => []
];

try {
    switch ($action) {
        // =====================
        // VIAJES
        // =====================
        
        case 'getViajes':
            $idRuta = isset($_GET['idRuta']) ? intval($_GET['idRuta']) : null;
            $viajes = getAllViajes($idRuta);
            $response['success'] = true;
            $response['data'] = $viajes;
            break;
        
        case 'getViaje':
            $idViaje = isset($_GET['idViaje']) ? intval($_GET['idViaje']) : null;
            if (!$idViaje) throw new Exception('ID de viaje requerido');
            
            $viaje = getViaje($idViaje);
            $response['success'] = $viaje !== null;
            $response['data'] = $viaje;
            if (!$viaje) $response['message'] = 'Viaje no encontrado';
            break;
        
        case 'insertViaje':
            $datos = $_POST;
            $idViaje = insertViaje($datos);
            if ($idViaje) {
                $response['success'] = true;
                $response['message'] = 'Viaje creado exitosamente';
                $response['data'] = ['idViaje' => $idViaje];
            } else {
                $response['message'] = 'Error al crear viaje';
            }
            break;
        
        case 'updateViaje':
            $idViaje = isset($_POST['idViaje']) ? intval($_POST['idViaje']) : null;
            if (!$idViaje) throw new Exception('ID de viaje requerido');
            
            $datos = $_POST;
            unset($datos['action'], $datos['idViaje']);
            
            if (updateViaje($idViaje, $datos)) {
                $response['success'] = true;
                $response['message'] = 'Viaje actualizado exitosamente';
            } else {
                $response['message'] = 'Error al actualizar viaje';
            }
            break;
        
        case 'deleteViaje':
            $idViaje = isset($_POST['idViaje']) ? intval($_POST['idViaje']) : null;
            if (!$idViaje) throw new Exception('ID de viaje requerido');
            
            if (deleteViaje($idViaje)) {
                $response['success'] = true;
                $response['message'] = 'Viaje cancelado exitosamente';
            } else {
                $response['message'] = 'Error al cancelar viaje';
            }
            break;
        
        // =====================
        // TARIFAS
        // =====================
        
        case 'getTarifas':
            $idViaje = isset($_GET['idViaje']) ? intval($_GET['idViaje']) : null;
            $tarifas = getAllTarifas($idViaje);
            $response['success'] = true;
            $response['data'] = $tarifas;
            break;
        
        case 'getTarifa':
            $idTarifa = isset($_GET['idTarifa']) ? intval($_GET['idTarifa']) : null;
            if (!$idTarifa) throw new Exception('ID de tarifa requerido');
            
            $tarifa = getTarifa($idTarifa);
            $response['success'] = $tarifa !== null;
            $response['data'] = $tarifa;
            if (!$tarifa) $response['message'] = 'Tarifa no encontrada';
            break;
        
        case 'insertTarifa':
            $datos = $_POST;
            $idTarifa = insertTarifa($datos);
            if ($idTarifa) {
                $response['success'] = true;
                $response['message'] = 'Tarifa creada exitosamente';
                $response['data'] = ['idTarifa' => $idTarifa];
            } else {
                $response['message'] = 'Error al crear tarifa';
            }
            break;
        
        case 'updateTarifa':
            $idTarifa = isset($_POST['idTarifa']) ? intval($_POST['idTarifa']) : null;
            if (!$idTarifa) throw new Exception('ID de tarifa requerido');
            
            $datos = $_POST;
            unset($datos['action'], $datos['idTarifa']);
            
            if (updateTarifa($idTarifa, $datos)) {
                $response['success'] = true;
                $response['message'] = 'Tarifa actualizada exitosamente';
            } else {
                $response['message'] = 'Error al actualizar tarifa';
            }
            break;
        
        case 'deleteTarifa':
            $idTarifa = isset($_POST['idTarifa']) ? intval($_POST['idTarifa']) : null;
            if (!$idTarifa) throw new Exception('ID de tarifa requerido');
            
            if (deleteTarifa($idTarifa)) {
                $response['success'] = true;
                $response['message'] = 'Tarifa eliminada exitosamente';
            } else {
                $response['message'] = 'Error al eliminar tarifa';
            }
            break;
        
        // =====================
        // DATOS MAESTROS
        // =====================
        
        case 'getTiposTarifa':
            $tipos = getTiposTarifa();
            $response['success'] = true;
            $response['data'] = $tipos;
            break;
        
        case 'getParadasParaTarifas':
            $idRuta = isset($_GET['idRuta']) ? intval($_GET['idRuta']) : null;
            if (!$idRuta) throw new Exception('ID de ruta requerido');
            
            $paradas = getParadasParaTarifas($idRuta);
            $response['success'] = true;
            $response['data'] = $paradas;
            break;
        
        default:
            $response['message'] = 'Acción no reconocida: ' . $action;
    }

} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
?>
