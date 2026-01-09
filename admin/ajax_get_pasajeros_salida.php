<?php
session_start();
require_once("classes/reserva.php");
require_once("classes/tarifas.php");
require_once("classes/convierte_monedas.php");

header('Content-Type: application/json');

if (!isset($_POST['idReservaHorarios'])) {
    echo json_encode(['success' => false, 'message' => 'ID no proporcionado']);
    exit;
}

$idReservaHorarios = (int)$_POST['idReservaHorarios'];

try {
    $tarifas = getReservaTarifas($idReservaHorarios);
    
    if (empty($tarifas)) {
        echo json_encode(['success' => false, 'message' => 'No se encontraron tarifas']);
        exit;
    }

    $pasajeros = [];

    foreach ($tarifas as $tarifa) {
        $pasajerosTarifa = getPasajeros($tarifa['idReservaTarifas']);
        $nombreTarifa = $tarifa['nombreTarifa'] ?? $tarifa['nombre'] ?? 'N/A';
        
        // Convertir precio total a moneda de sesión
        $precioTotal = ConvierteMoneda($tarifa["monedaSel"], $_SESSION["moneda_sel"], $tarifa["valor"]);
        
        // Si hay pasajeros específicos, mostrar uno por uno
        if (!empty($pasajerosTarifa)) {
            foreach ($pasajerosTarifa as $p) {
                // El campo correcto es 'nombrePasajero', no 'nombre' + 'apellido'
                $nombrePasajero = trim($p['nombrePasajero'] ?? '');
                if (empty($nombrePasajero)) {
                    $nombrePasajero = 'Pasajero sin nombre';
                }
                
                // Calcular el valor por pasajero
                $cantidadTotal = count($pasajerosTarifa);
                $valorPorPasajero = $precioTotal / $cantidadTotal;
                $valorFormateado = $_SESSION["moneda_sel_sym"] . number_format($valorPorPasajero, 2);
                
                $pasajeros[] = [
                    'nombre' => $nombrePasajero,
                    'tarifa' => $nombreTarifa,
                    'cantidad' => 1,
                    'valor' => $valorFormateado,
                    'aPagar' => $valorFormateado
                ];
            }
        } else {
            // Si no hay pasajeros en la tabla, mostrar la cantidad de la tarifa
            $cantidad = $tarifa['cantidad'] ?? 1;
            $valorFormateado = $_SESSION["moneda_sel_sym"] . number_format($precioTotal, 2);
            
            for ($i = 1; $i <= $cantidad; $i++) {
                $pasajeros[] = [
                    'nombre' => 'Pasajero ' . $i,
                    'tarifa' => $nombreTarifa,
                    'cantidad' => 1,
                    'valor' => $_SESSION["moneda_sel_sym"] . number_format($precioTotal / $cantidad, 2),
                    'aPagar' => $_SESSION["moneda_sel_sym"] . number_format($precioTotal / $cantidad, 2)
                ];
            }
        }
    }

    echo json_encode([
        'success' => true,
        'pasajeros' => $pasajeros
    ]);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>
