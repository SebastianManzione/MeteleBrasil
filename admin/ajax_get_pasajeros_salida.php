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
$tipo = isset($_POST['tipo']) ? strtolower(trim($_POST['tipo'])) : 'prestador';

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
        // Valor SIN impuestos (si existe), convertido a moneda de sesión
        $valorSinImpuestosTotal = null;
        if (isset($tarifa['valorSinIva'])) {
            $valorSinImpuestosTotal = ConvierteMoneda($tarifa["monedaSel"], $_SESSION["moneda_sel"], $tarifa["valorSinIva"]);
        } elseif (isset($tarifa['valorDeIva'])) {
            $valorSinImpuestosTotal = ConvierteMoneda($tarifa["monedaSel"], $_SESSION["moneda_sel"], max(0, ($tarifa["valor"] - $tarifa["valorDeIva"])));
        } else {
            // Fallback: si no hay desglose, aproximar al total
            $valorSinImpuestosTotal = $precioTotal;
        }
        // Comisiones en moneda de sesión
        $comisionVendedorMonto = ConvierteMoneda($tarifa["monedaSel"], $_SESSION["moneda_sel"], $tarifa['comisionVendedor'] ?? 0);
        $comisionSistemaMonto  = ConvierteMoneda($tarifa["monedaSel"], $_SESSION["moneda_sel"], $tarifa['comisionSistema']  ?? 0);
        // Porcentaje comisión vendedor para modal vendedor
        $comisionVendedorPorc = 0;
        if (!empty($tarifa['comisionVendedorPorcentaje'])) {
            $comisionVendedorPorc = (float)$tarifa['comisionVendedorPorcentaje'] * 100;
        } elseif ($precioTotal > 0) {
            $comisionVendedorPorc = round(($comisionVendedorMonto / $precioTotal) * 100, 2);
        }
        
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
                $valorPorPasajeroSinImp = $valorSinImpuestosTotal / $cantidadTotal;
                $valorSinImpFormateado = $_SESSION["moneda_sel_sym"] . number_format($valorPorPasajeroSinImp, 2);
                // Calcular a pagar por pasajero según contexto
                if ($tipo === 'vendedor') {
                    $aPagarPorPasajero = $comisionVendedorMonto / $cantidadTotal;
                } else { // prestador
                    $aPagarPorPasajero = ($precioTotal - $comisionSistemaMonto - $comisionVendedorMonto) / $cantidadTotal;
                }
                $aPagarFormateado = $_SESSION["moneda_sel_sym"] . number_format($aPagarPorPasajero, 2);
                
                $pasajeros[] = [
                    'nombre' => $nombrePasajero,
                    'tarifa' => $nombreTarifa,
                    'cantidad' => 1,
                    'valorSinImpuestos' => $valorSinImpFormateado,
                    'aPagar' => $aPagarFormateado,
                    'comisionPorc' => ($tipo === 'vendedor') ? $comisionVendedorPorc : null
                ];
            }
        } else {
            // Si no hay pasajeros en la tabla, mostrar la cantidad de la tarifa
            $cantidad = $tarifa['cantidad'] ?? 1;
            $valorSinImpFormateadoTotal = $_SESSION["moneda_sel_sym"] . number_format($valorSinImpuestosTotal, 2);
            // Calcular a pagar total según contexto
            if ($tipo === 'vendedor') {
                $aPagarTotal = $comisionVendedorMonto;
            } else {
                $aPagarTotal = ($precioTotal - $comisionSistemaMonto - $comisionVendedorMonto);
            }
            
            for ($i = 1; $i <= $cantidad; $i++) {
                $pasajeros[] = [
                    'nombre' => 'Pasajero ' . $i,
                    'tarifa' => $nombreTarifa,
                    'cantidad' => 1,
                    'valorSinImpuestos' => $_SESSION["moneda_sel_sym"] . number_format($valorSinImpuestosTotal / $cantidad, 2),
                    'aPagar' => $_SESSION["moneda_sel_sym"] . number_format($aPagarTotal / $cantidad, 2),
                    'comisionPorc' => ($tipo === 'vendedor') ? $comisionVendedorPorc : null
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
