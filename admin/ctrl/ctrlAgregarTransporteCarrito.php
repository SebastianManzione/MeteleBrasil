<?php
/**
 * Controller: Agregar transporte al carrito
 * Compatible con estructura de reservas existente
 */
session_start();
header('Content-Type: application/json');

require_once("../classes/conexion.php");
require_once("../classes/transporte.php");

try {
    // Validar entrada
    if (!isset($_POST['idViaje'], $_POST['fecha'], $_POST['origen'], $_POST['destino'], $_POST['asientos'])) {
        throw new Exception("Faltan parámetros requeridos");
    }
    
    $idViaje = intval($_POST['idViaje']);
    $fecha = $_POST['fecha'];
    $idOrigen = intval($_POST['origen']);
    $idDestino = intval($_POST['destino']);
    $asientos = $_POST['asientos'];
    $precioPorAsiento = floatval($_POST['precio'] ?? 1200);
    
    if (!is_array($asientos) || empty($asientos)) {
        throw new Exception("Debe seleccionar al menos un asiento");
    }
    
    // Obtener info del viaje
    $sql = "SELECT v.*, r.nombre as ruta_nombre,
                   t_origen.nombre as origen_nombre, t_origen.ciudad as origen_ciudad,
                   t_destino.nombre as destino_nombre, t_destino.ciudad as destino_ciudad,
                   e.nombre as empresa_nombre
            FROM viaje_transporte v
            INNER JOIN ruta_transporte r ON v.idRuta = r.idRuta
            INNER JOIN ruta_paradas p_origen ON r.idRuta = p_origen.idRuta
            INNER JOIN ruta_paradas p_destino ON r.idRuta = p_destino.idRuta
            INNER JOIN terminal_transporte t_origen ON p_origen.idTerminal = t_origen.idTerminal
            INNER JOIN terminal_transporte t_destino ON p_destino.idTerminal = t_destino.idTerminal
            LEFT JOIN empresa_transporte e ON r.idEmpresa = e.idEmpresa
            WHERE v.idViaje = :idViaje
              AND p_origen.idRutaParada = :origen
              AND p_destino.idRutaParada = :destino";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':idViaje' => $idViaje, ':origen' => $idOrigen, ':destino' => $idDestino]);
    $viaje = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$viaje) {
        throw new Exception("Viaje no encontrado");
    }
    
    // Verificar disponibilidad de asientos
    $sql_ocupados = "SELECT numero_asiento
                     FROM reserva_transporte_pasajeros rtp
                     INNER JOIN reserva_transporte_items rti ON rtp.idReservaTransporte = rti.idReservaTransporte
                     WHERE rti.idViaje = :idViaje
                       AND rti.fecha_viaje = :fecha";
    $stmt_ocupados = $pdo->prepare($sql_ocupados);
    $stmt_ocupados->execute([':idViaje' => $idViaje, ':fecha' => $fecha]);
    $ocupados = array_column($stmt_ocupados->fetchAll(PDO::FETCH_ASSOC), 'numero_asiento');
    
    foreach ($asientos as $asiento) {
        if (in_array($asiento, $ocupados)) {
            throw new Exception("El asiento $asiento ya no está disponible");
        }
    }
    
    // Agregar al carrito (estructura compatible con servicios)
    if (!isset($_SESSION['reserva'])) {
        $_SESSION['reserva'] = [];
    }
    
    $itemCarrito = [
        'tipo' => 'transporte',
        'idViaje' => $idViaje,
        'fecha_viaje' => $fecha,
        'idOrigen' => $idOrigen,
        'idDestino' => $idDestino,
        'origen_nombre' => $viaje['origen_nombre'] . ' (' . $viaje['origen_ciudad'] . ')',
        'destino_nombre' => $viaje['destino_nombre'] . ' (' . $viaje['destino_ciudad'] . ')',
        'empresa' => $viaje['empresa_nombre'],
        'ruta' => $viaje['ruta_nombre'],
        'hora_salida' => $viaje['hora_salida'],
        'asientos' => $asientos,
        'cantidad_pasajeros' => count($asientos),
        'precio_unitario' => $precioPorAsiento,
        'precio_total' => $precioPorAsiento * count($asientos),
        'idMoneda' => 1, // ARS por defecto
        'pasajeros' => [] // Se completará en checkout
    ];
    
    $_SESSION['reserva'][] = $itemCarrito;
    
    echo json_encode([
        'success' => true,
        'message' => 'Pasajes agregados al carrito',
        'item' => $itemCarrito
    ]);
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
