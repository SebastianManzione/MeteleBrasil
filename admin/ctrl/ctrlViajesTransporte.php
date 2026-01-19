<?php
require_once("../classes/conexion.php");
require_once("../classes/transporte.php");

// Fuerza respuesta JSON y suprime salida accidental
header('Content-Type: application/json; charset=utf-8');
// En controladores AJAX evitamos mostrar errores en pantalla
@ini_set('display_errors', '0');
@ini_set('log_errors', '1');
// Inicia buffer para poder limpiar cualquier echo/HTML previo
ob_start();

try {
    $action = $_GET['action'] ?? $_POST['action'] ?? '';

    switch($action) {
        case 'insert':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $datos = [
                    'idRuta' => $_POST['idRuta'] ?? null,
                    'idModelo' => $_POST['idModelo'] ?? null,
                    'idDesdeParada' => isset($_POST['idDesdeParada']) ? $_POST['idDesdeParada'] : null,
                    'idHastaParada' => isset($_POST['idHastaParada']) ? $_POST['idHastaParada'] : null,
                    'fecha' => $_POST['fecha'] ?? null,
                    'hora_salida' => $_POST['hora_salida'] ?? null,
                    'hora_llegada' => $_POST['hora_llegada'] ?? null,
                    'asientos_totales' => $_POST['asientos_totales'] ?? null,
                    'asientos_disponibles' => $_POST['asientos_disponibles'] ?? null,
                    'numero_vuelo_bus' => $_POST['numero_vuelo_bus'] ?? null,
                    'observaciones' => $_POST['observaciones'] ?? null,
                    'habilitado' => isset($_POST['habilitado']) ? (int)$_POST['habilitado'] : 1,
                ];

                $result = insertViaje($datos);
                ob_clean();
                if ($result) {
                    echo json_encode(['success' => true, 'idViaje' => $result]);
                } else {
                    echo json_encode(['success' => false, 'error' => 'Error al crear el viaje']);
                }
                exit;
            }
            break;

        case 'update':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $idViaje = $_POST['idViaje'] ?? null;
                $datos = [
                    'idRuta' => $_POST['idRuta'] ?? null,
                    'idModelo' => $_POST['idModelo'] ?? null,
                    'idDesdeParada' => isset($_POST['idDesdeParada']) ? $_POST['idDesdeParada'] : null,
                    'idHastaParada' => isset($_POST['idHastaParada']) ? $_POST['idHastaParada'] : null,
                    'fecha' => $_POST['fecha'] ?? null,
                    'hora_salida' => $_POST['hora_salida'] ?? null,
                    'hora_llegada' => $_POST['hora_llegada'] ?? null,
                    'asientos_totales' => $_POST['asientos_totales'] ?? null,
                    'asientos_disponibles' => $_POST['asientos_disponibles'] ?? null,
                    'numero_vuelo_bus' => $_POST['numero_vuelo_bus'] ?? null,
                    'observaciones' => $_POST['observaciones'] ?? null,
                    'habilitado' => isset($_POST['habilitado']) ? (int)$_POST['habilitado'] : 1,
                ];

                $result = updateViaje($idViaje, $datos);
                ob_clean();
                if ($result) {
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false, 'error' => 'Error al actualizar el viaje']);
                }
                exit;
            }
            break;

        case 'delete':
            if (isset($_GET['id'])) {
                $idViaje = $_GET['id'];
                $result = deleteViaje($idViaje);
                ob_clean();
                if ($result) {
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false, 'error' => 'Error al eliminar el viaje']);
                }
                exit;
            }
            break;

        case 'getAll':
            $viajes = getAllViajes();
            ob_clean();
            echo json_encode($viajes);
            exit;

        case 'getById':
            if (isset($_GET['id'])) {
                $viaje = getViaje($_GET['id']);
                ob_clean();
                echo json_encode($viaje);
                exit;
            }
            break;

        case 'getByRuta':
            if (isset($_GET['idRuta'])) {
                $fecha_desde = $_GET['fecha_desde'] ?? null;
                $viajes = getViajesRuta($_GET['idRuta'], $fecha_desde);
                ob_clean();
                echo json_encode($viajes);
                exit;
            }
            break;

        case 'getDisponibles':
            if (isset($_GET['idRuta'])) {
                $fecha_desde = $_GET['fecha_desde'] ?? date('Y-m-d');
                $viajes = getViajesDisponibles($_GET['idRuta'], $fecha_desde);
                ob_clean();
                echo json_encode($viajes);
                exit;
            }
            break;

        default:
            ob_clean();
            echo json_encode(['success' => false, 'error' => 'Acción no válida']);
            exit;
    }
} catch (Throwable $e) {
    // En caso de excepción, limpiar y responder en JSON
    ob_clean();
    echo json_encode([
        'success' => false,
        'error' => 'Excepción en controlador: ' . $e->getMessage(),
    ]);
    exit;
}
?>
