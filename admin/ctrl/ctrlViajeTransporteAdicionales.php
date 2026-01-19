<?php
ob_start();
header('Content-Type: application/json');

require_once(__DIR__ . "/../classes/transporte.php");

$response = ['success' => false, 'message' => ''];

try {
    $accion = $_POST['accion'] ?? '';
    
    switch ($accion) {
        case 'agregar':
            $idViaje = $_POST['idViaje'] ?? null;
            $idServiciosAdicionales = $_POST['idServiciosAdicionales'] ?? null;
            $precio = $_POST['precio'] ?? 0;
            $idMoneda = $_POST['idMoneda'] ?? 1;
            
            if (!$idViaje || !$idServiciosAdicionales) {
                throw new Exception('Parámetros incompletos');
            }
            
            if (setServicioAdicionalViaje($idViaje, $idServiciosAdicionales, $precio, $idMoneda)) {
                $response['success'] = true;
                $response['message'] = 'Servicio agregado correctamente';
            } else {
                throw new Exception('Error al agregar servicio');
            }
            break;
            
        case 'actualizar':
            $idViajeAdicional = $_POST['idViajeAdicional'] ?? null;
            $precio = $_POST['precio'] ?? 0;
            $idMoneda = $_POST['idMoneda'] ?? 1;
            
            if (!$idViajeAdicional) {
                throw new Exception('ID de viaje adicional requerido');
            }
            
            if (updateServicioAdicionalViaje($idViajeAdicional, $precio, $idMoneda)) {
                $response['success'] = true;
                $response['message'] = 'Servicio actualizado correctamente';
            } else {
                throw new Exception('Error al actualizar servicio');
            }
            break;
            
        case 'eliminar':
            $idViajeAdicional = $_POST['idViajeAdicional'] ?? null;
            
            if (!$idViajeAdicional) {
                throw new Exception('ID de viaje adicional requerido');
            }
            
            if (deleteServicioAdicionalViaje($idViajeAdicional)) {
                $response['success'] = true;
                $response['message'] = 'Servicio eliminado correctamente';
            } else {
                throw new Exception('Error al eliminar servicio');
            }
            break;
            
        default:
            throw new Exception('Acción no reconocida');
    }
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

$output = json_encode($response);
ob_end_clean();
echo $output;
?>
