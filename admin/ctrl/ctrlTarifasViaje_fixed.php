<?php
/**
 * Controller para obtener tarifas de viajes de transporte - VERSIÓN CORREGIDA
 */

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Includes con rutas absolutas
require_once(__DIR__ . "/../classes/conexion.php");
require_once(__DIR__ . "/../classes/transporte.php");
require_once(__DIR__ . "/../classes/convierte_monedas.php");

// Set header
header('Content-Type: application/json');

try {
    // Soportar tanto 'action' como 'accion'
    $accion = isset($_POST['action']) ? $_POST['action'] : (isset($_POST['accion']) ? $_POST['accion'] : (isset($_GET['accion']) ? $_GET['accion'] : ''));
    
    if (empty($accion)) {
        echo json_encode(['success' => false, 'error' => 'Acción no especificada', 'debug' => $_POST]);
        exit;
    }

    switch ($accion) {
        case 'getClasesViaje':
            // Obtener todas las clases/butacas disponibles de un viaje
            $idViaje = isset($_POST['idViaje']) ? (int)$_POST['idViaje'] : 0;
            
            if ($idViaje > 0) {
                $clases = getViajeClasesServicio($idViaje);
                
                if (!empty($clases)) {
                    $monedaUsuario = isset($_SESSION['moneda_sel']) ? $_SESSION['moneda_sel'] : 1;
                    
                    foreach ($clases as &$clase) {
                        $precioBase = (float)$clase['precio_base'];
                        $idMonedaOrigen = (int)$clase['idMoneda'];
                        
                        $precioConvertido = ConvierteMoneda($idMonedaOrigen, $monedaUsuario, $precioBase);
                        $clase['precio_base_convertido'] = $precioConvertido;
                        $clase['precio_formateado'] = number_format($precioConvertido, 0, '', '.');
                        $clase['moneda_usuario'] = $_SESSION['moneda_sel_sym'] ?? 'ARS';
                    }
                    
                    echo json_encode([
                        'success' => true,
                        'clases' => array_values($clases)
                    ]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'No hay clases configuradas']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'idViaje inválido']);
            }
            break;
            
        case 'getClasesTarifas':
            // Obtener tarifas de una clase específica (por tipo de pasajero)
            $idViaje = isset($_POST['idViaje']) ? (int)$_POST['idViaje'] : 0;
            $idViajeClase = isset($_POST['idViajeClase']) ? (int)$_POST['idViajeClase'] : 0;
            
            if ($idViaje > 0 && $idViajeClase > 0) {
                // Obtener tarifas de la clase específica
                $tarifas = getViajeClaseTarifas($idViajeClase);
                
                if (!empty($tarifas)) {
                    // Convertir a moneda del usuario
                    $monedaUsuario = isset($_SESSION['moneda_sel']) ? $_SESSION['moneda_sel'] : 1;
                    
                    foreach ($tarifas as &$tarifa) {
                        $precioOriginal = (float)$tarifa['precio'];
                        $idMonedaOrigen = (int)$tarifa['idMoneda'];
                        
                        // Convertir moneda
                        $precioConvertido = ConvierteMoneda($idMonedaOrigen, $monedaUsuario, $precioOriginal);
                        $tarifa['precio_convertido'] = $precioConvertido;
                        $tarifa['precio_formateado'] = number_format($precioConvertido, 0, '', '.');
                        $tarifa['moneda_usuario'] = $_SESSION['moneda_sel_sym'] ?? 'ARS';
                    }
                    
                    echo json_encode([
                        'success' => true,
                        'tarifas' => array_values($tarifas),
                        'debug' => [
                            'total_tarifas' => count($tarifas),
                            'idViajeClase' => $idViajeClase
                        ]
                    ]);
                } else {
                    echo json_encode([
                        'success' => false,
                        'message' => 'No se encontraron tarifas para esta clase',
                        'debug' => [
                            'idViaje' => $idViaje,
                            'idViajeClase' => $idViajeClase
                        ]
                    ]);
                }
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Parámetros inválidos',
                    'debug' => [
                        'idViaje' => $idViaje,
                        'idViajeClase' => $idViajeClase
                    ]
                ]);
            }
            break;
            
        default:
            echo json_encode(['success' => false, 'error' => 'Acción desconocida: ' . $accion]);
            break;
    }
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Error en controller: ' . $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ]);
}
?>
