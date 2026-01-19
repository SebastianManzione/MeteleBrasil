<?php
/**
 * CONTROLLER: MODELOS Y VEHÍCULOS DE TRANSPORTE
 * AJAX API para gestión de modelos y vehículos
 * Fecha: 2026-01-16
 */

session_start();

require_once "../classes/transporte.php";

header('Content-Type: application/json; charset=utf-8');

$action = $_REQUEST['action'] ?? '';

// ========================================
// MODELOS
// ========================================

if ($action === 'getModelos') {
    $modelos = getAllModelos();
    echo json_encode([
        'success' => true,
        'data' => $modelos
    ]);
    exit;
}

if ($action === 'getModeloPorTipo') {
    $tipo = $_REQUEST['tipo_transporte'] ?? 0;
    $modelos = getAllModelos();
    
    $resultado = array_filter($modelos, function($m) use ($tipo) {
        return $m['tipo_transporte'] == $tipo;
    });
    
    echo json_encode([
        'success' => true,
        'data' => array_values($resultado)
    ]);
    exit;
}

if ($action === 'insertModelo') {
    $datos = [
        'nombre' => $_POST['nombre'] ?? '',
        'tipo_transporte' => $_POST['tipo_transporte'] ?? 1,
        'capacidad_total' => $_POST['capacidad_total'] ?? 0,
        'filas' => $_POST['filas'] ?? 0,
        'columnas' => $_POST['columnas'] ?? 0,
        'descripcion' => $_POST['descripcion'] ?? '',
        'distribucion_json' => $_POST['distribucion_json'] ?? '{}',
        'habilitado' => $_POST['habilitado'] ?? 1
    ];
    
    $idModelo = insertModelo($datos);
    
    if ($idModelo) {
        echo json_encode([
            'success' => true,
            'message' => 'Modelo creado exitosamente',
            'idModelo' => $idModelo
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'error' => 'Error al crear modelo'
        ]);
    }
    exit;
}

if ($action === 'getModelo') {
    $idModelo = $_REQUEST['idModelo'] ?? 0;
    $modelo = getModelo($idModelo);
    echo json_encode([
        'success' => $modelo !== null,
        'data' => $modelo,
        'error' => $modelo ? null : 'Modelo no encontrado'
    ]);
    exit;
}

if ($action === 'updateModelo') {
    $idModelo = $_POST['idModelo'] ?? 0;
    $datos = [];
    
    if (isset($_POST['nombre'])) $datos['nombre'] = $_POST['nombre'];
    if (isset($_POST['tipo_transporte'])) $datos['tipo_transporte'] = $_POST['tipo_transporte'];
    if (isset($_POST['filas'])) $datos['filas'] = $_POST['filas'];
    if (isset($_POST['columnas'])) $datos['columnas'] = $_POST['columnas'];
    if (isset($_POST['capacidad_total'])) $datos['capacidad_total'] = $_POST['capacidad_total'];
    if (isset($_POST['descripcion'])) $datos['descripcion'] = $_POST['descripcion'];
    if (isset($_POST['distribucion_json'])) $datos['distribucion_json'] = $_POST['distribucion_json'];
    if (isset($_POST['habilitado'])) $datos['habilitado'] = $_POST['habilitado'];
    
    $resultado = updateModelo($idModelo, $datos);
    
    echo json_encode([
        'success' => $resultado,
        'message' => $resultado ? 'Modelo actualizado' : 'Error al actualizar'
    ]);
    exit;
}

// ========================================
// VEHÍCULOS
// ========================================

if ($action === 'getVehiculos') {
    $vehiculos = getAllVehiculos();
    echo json_encode([
        'success' => true,
        'data' => $vehiculos
    ]);
    exit;
}

if ($action === 'getVehiculo') {
    $idVehiculo = $_REQUEST['idVehiculo'] ?? 0;
    $vehiculo = getVehiculo($idVehiculo);
    
    echo json_encode([
        'success' => $vehiculo !== null,
        'data' => $vehiculo
    ]);
    exit;
}

if ($action === 'insertVehiculo') {
    $datos = [
        'idModelo' => $_POST['idModelo'] ?? 1,
        'patente' => $_POST['patente'] ?? '',
        'idEmpresa' => $_POST['idEmpresa'] ?? null,
        'estado' => $_POST['estado'] ?? 'activo',
        'fecha_alta' => $_POST['fecha_alta'] ?? date('Y-m-d'),
        'observaciones' => $_POST['observaciones'] ?? ''
    ];
    
    $idVehiculo = insertVehiculo($datos);
    
    if ($idVehiculo) {
        echo json_encode([
            'success' => true,
            'message' => 'Vehículo creado exitosamente',
            'idVehiculo' => $idVehiculo
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'error' => 'Error al crear vehículo'
        ]);
    }
    exit;
}

if ($action === 'updateVehiculo') {
    $idVehiculo = $_POST['idVehiculo'] ?? 0;
    $datos = [];
    
    if (isset($_POST['patente'])) $datos['patente'] = $_POST['patente'];
    if (isset($_POST['estado'])) $datos['estado'] = $_POST['estado'];
    if (isset($_POST['observaciones'])) $datos['observaciones'] = $_POST['observaciones'];
    
    $resultado = updateVehiculo($idVehiculo, $datos);
    
    echo json_encode([
        'success' => $resultado,
        'message' => $resultado ? 'Vehículo actualizado' : 'Error al actualizar'
    ]);
    exit;
}

// ========================================
// ASIENTOS
// ========================================

if ($action === 'crearMapa') {
    $idViaje = $_POST['idViaje'] ?? 0;
    $idVehiculo = $_POST['idVehiculo'] ?? 0;
    
    $resultado = crearMapaAsientos($idViaje, $idVehiculo);
    
    echo json_encode([
        'success' => $resultado,
        'message' => $resultado ? 'Mapa de asientos creado' : 'Error al crear mapa'
    ]);
    exit;
}

if ($action === 'getAsientos') {
    $idViaje = $_REQUEST['idViaje'] ?? 0;
    $asientos = getAsientosViaje($idViaje);
    
    echo json_encode([
        'success' => true,
        'data' => $asientos
    ]);
    exit;
}

if ($action === 'getAsientosDisponibles') {
    $idViaje = $_REQUEST['idViaje'] ?? 0;
    $asientos = getAsientosDisponibles($idViaje);
    
    echo json_encode([
        'success' => true,
        'data' => $asientos
    ]);
    exit;
}

// Default
echo json_encode([
    'success' => false,
    'error' => 'Acción no válida'
]);
