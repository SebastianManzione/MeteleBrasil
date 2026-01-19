<?php
require_once(__DIR__ . "/../classes/permisos.php");
require_once(__DIR__ . "/../includes/permisos_helper.php");
require_once(__DIR__ . "/../classes/transporte.php");

header('Content-Type: application/json');

// Verificar permisos
$permisos = new PermisosManager($GLOBALS['pdo'], $_SESSION['login'] ?? []);
if (!$permisos->tienePermiso('viajeSegmentosPreciosEditor')) {
    echo json_encode(['success' => false, 'message' => 'No tiene permisos']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

$idViaje = isset($_POST['idViaje']) ? intval($_POST['idViaje']) : 0;
$idClaseServicio = isset($_POST['idClaseServicio']) ? intval($_POST['idClaseServicio']) : 0;
$segmentos = isset($_POST['segmentos']) ? $_POST['segmentos'] : [];

if ($idViaje == 0 || $idClaseServicio == 0) {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    exit;
}

$insertados = 0;
$actualizados = 0;
$errores = 0;

try {
    foreach ($segmentos as $key => $datos) {
        // Solo procesar si tiene precio
        if (empty($datos['precio']) || $datos['precio'] <= 0) {
            continue;
        }
        
        $datosSegmento = [
            'idViaje' => $idViaje,
            'idOrigenParada' => intval($datos['idOrigenParada']),
            'idDestinoParada' => intval($datos['idDestinoParada']),
            'idClaseServicio' => $idClaseServicio,
            'precio' => floatval($datos['precio']),
            'idMoneda' => intval($datos['idMoneda']),
            'asientos_disponibles' => isset($datos['asientos_disponibles']) ? intval($datos['asientos_disponibles']) : 0,
            'comisiona' => isset($datos['comisiona']) ? 1 : 0,
            'habilitado' => isset($datos['habilitado']) ? 1 : 0
        ];
        
        // Si existe idSegmentoPrecio, es actualización
        if (isset($datos['idSegmentoPrecio']) && !empty($datos['idSegmentoPrecio'])) {
            $resultado = updatePrecioSegmento(intval($datos['idSegmentoPrecio']), $datosSegmento);
            if ($resultado) {
                $actualizados++;
            } else {
                $errores++;
            }
        } else {
            // Es inserción
            $resultado = insertPrecioSegmento($datosSegmento);
            if ($resultado) {
                $insertados++;
            } else {
                $errores++;
            }
        }
    }
    
    $mensaje = [];
    if ($insertados > 0) $mensaje[] = "$insertados segmentos creados";
    if ($actualizados > 0) $mensaje[] = "$actualizados segmentos actualizados";
    if ($errores > 0) $mensaje[] = "$errores errores";
    
    echo json_encode([
        'success' => true,
        'message' => implode(', ', $mensaje),
        'insertados' => $insertados,
        'actualizados' => $actualizados,
        'errores' => $errores
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
?>
