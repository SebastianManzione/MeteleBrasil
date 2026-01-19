<?php
/**
 * Controller para gestión de clases de servicio por viaje
 */

require_once("../classes/transporte.php");
require_once("../classes/conexion.php");

header('Content-Type: application/json; charset=utf-8');

$action = $_GET['action'] ?? $_POST['action'] ?? null;

try {
    switch ($action) {
        case 'getClases':
            $idViaje = $_GET['idViaje'] ?? null;
            if (!$idViaje) throw new Exception("idViaje requerido");
            
            $clases = getViajeClasesServicio($idViaje);
            echo json_encode(['success' => true, 'data' => $clases]);
            break;
        
        case 'updateClase':
            $datos = json_decode(file_get_contents('php://input'), true);
            $idViajeClase = $datos['idViajeClase'] ?? null;
            
            if (!$idViajeClase) throw new Exception("idViajeClase requerido");
            
            $actualizar = [
                'asientos_totales' => $datos['asientos_totales'] ?? null,
                'asientos_disponibles' => $datos['asientos_disponibles'] ?? null,
                'precio_base' => $datos['precio_base'] ?? null,
                'habilitado' => $datos['habilitado'] ?? 1
            ];
            
            // Filtrar nulos
            $actualizar = array_filter($actualizar, fn($v) => $v !== null);
            
            if (updateViajeClase($idViajeClase, $actualizar)) {
                echo json_encode(['success' => true, 'message' => 'Clase actualizada']);
            } else {
                throw new Exception("Error al actualizar");
            }
            break;
        
        case 'deleteClase':
            $idViajeClase = $_POST['idViajeClase'] ?? null;
            if (!$idViajeClase) throw new Exception("idViajeClase requerido");
            
            if (deleteViajeClase($idViajeClase)) {
                echo json_encode(['success' => true, 'message' => 'Clase eliminada']);
            } else {
                throw new Exception("Error al eliminar");
            }
            break;
        
        case 'addClase':
            $datos = json_decode(file_get_contents('php://input'), true);
            
            $idViajeClase = insertViajeClase([
                'idViaje' => $datos['idViaje'],
                'idClaseServicio' => $datos['idClaseServicio'],
                'asientos_totales' => $datos['asientos_totales'] ?? 20,
                'asientos_disponibles' => $datos['asientos_disponibles'] ?? 20,
                'precio_base' => $datos['precio_base'] ?? 0,
                'idMoneda' => $datos['idMoneda'] ?? 270,
                'comisiona' => $datos['comisiona'] ?? 1
            ]);
            
            if ($idViajeClase) {
                echo json_encode(['success' => true, 'idViajeClase' => $idViajeClase, 'message' => 'Clase agregada']);
            } else {
                throw new Exception("Error al agregar clase");
            }
            break;
        
        case 'getTarifas':
            $idViajeClase = $_GET['idViajeClase'] ?? null;
            if (!$idViajeClase) throw new Exception("idViajeClase requerido");
            
            $tarifas = getViajeClaseTarifas($idViajeClase);
            echo json_encode(['success' => true, 'data' => $tarifas]);
            break;
        
        case 'updateTarifa':
            $datos = json_decode(file_get_contents('php://input'), true);
            $idViajeClaseTarifa = $datos['idViajeClaseTarifa'] ?? null;
            
            if (!$idViajeClaseTarifa) throw new Exception("idViajeClaseTarifa requerido");
            
            if (updateViajeClaseTarifa($idViajeClaseTarifa, $datos['precio'])) {
                echo json_encode(['success' => true, 'message' => 'Tarifa actualizada']);
            } else {
                throw new Exception("Error al actualizar tarifa");
            }
            break;
        
        case 'getClasesDisponibles':
            $idViaje = $_GET['idViaje'] ?? null;
            if (!$idViaje) throw new Exception("idViaje requerido");
            
            // Obtener tipo de transporte del viaje
            $stmt = $pdo->prepare("
                SELECT DISTINCT cs.idClaseServicio, cs.nombre, cs.descripcion, cs.orden
                FROM clase_servicio_transporte cs
                INNER JOIN ruta_transporte rt ON rt.idTipoTransporte = cs.idTipoTransporte
                INNER JOIN viaje_transporte vt ON vt.idRuta = rt.idRuta
                WHERE vt.idViaje = ?
                AND cs.habilitado = 1
                AND cs.idClaseServicio NOT IN (
                    SELECT idClaseServicio FROM viaje_clase_servicio WHERE idViaje = ?
                )
                ORDER BY cs.orden
            ");
            $stmt->execute([$idViaje, $idViaje]);
            $clases = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo json_encode(['success' => true, 'data' => $clases]);
            break;
        
        default:
            throw new Exception("Acción no válida: $action");
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
