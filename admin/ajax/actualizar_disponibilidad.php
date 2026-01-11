<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
header('Content-Type: application/json; charset=utf-8');

// Verificar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar autenticación básica
if (!isset($_SESSION['login']) || empty($_SESSION['login'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Sesión no válida']);
    exit;
}

$idUsuario = $_SESSION['login']['idUsuario'] ?? 0;
$idPrestador = $_SESSION['login']['idPrestador'] ?? 0;

// Verificar permisos mínimos
if ($idUsuario != 1 && $idPrestador <= 0) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

require_once("../classes/conexion.php");
require_once("../classes/salidas.php");

$idServicioSalidas = intval($_POST['idServicioSalidas'] ?? 0);
$nuevaDisponibilidad = intval($_POST['disponibilidad'] ?? 0);

if ($idServicioSalidas <= 0 || $nuevaDisponibilidad < 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Parámetros inválidos']);
    exit;
}

try {
    // Obtener la salida actual para verificar permisos
    $salida = getSalida($idServicioSalidas);
    
    if (empty($salida)) {
        echo json_encode(['success' => false, 'message' => 'Salida no encontrada']);
        exit;
    }

    // Verificar permisos (admin o propietario)
    $salida_idPrestador = intval($salida[0]['idPrestador'] ?? 0);
    if ($idUsuario != 1 && $salida_idPrestador != $idPrestador) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'No tiene permisos']);
        exit;
    }

    // Obtener disponibilidad actual
    $disponibilidadActual = intval($salida[0]['disponibilidad'] ?? 0);
    
    // Actualizar disponibilidad
    $sql = "UPDATE servicio_salidas SET disponibilidad = :disponibilidad WHERE idServicioSalidas = :idServicioSalidas";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([
        ':disponibilidad' => $nuevaDisponibilidad,
        ':idServicioSalidas' => $idServicioSalidas
    ]);

    if ($result && $stmt->rowCount() > 0) {
        $diferencia = $nuevaDisponibilidad - $disponibilidadActual;
        echo json_encode([
            'success' => true, 
            'message' => 'OK',
            'diferencia' => $diferencia,
            'disponibilidad_anterior' => $disponibilidadActual,
            'disponibilidad_nueva' => $nuevaDisponibilidad
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se realizaron cambios']);
    }
} catch (Exception $e) {
    http_response_code(500);
    error_log("Error en actualizar_disponibilidad.php: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
    exit;
}
?>
