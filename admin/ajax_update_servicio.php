<?php
/**
 * Endpoint AJAX simple para actualizar servicios
 * Sin validaciones complejas para evitar ModSecurity
 */
session_start();

// Solo aceptar AJAX
if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
    http_response_code(403);
    die('Solo AJAX');
}

// Verificar autenticación básica
if (!isset($_SESSION['login']['idUsuario'])) {
    http_response_code(401);
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

require_once(__DIR__ . "/classes/conexion.php");

try {
    $accion = $_POST['accion'] ?? '';
    
    if ($accion === 'update_campo') {
        // Actualizar un campo específico
        $idServicio = intval($_POST['idServicio'] ?? 0);
        $campo = $_POST['campo'] ?? '';
        $valor = $_POST['valor'] ?? '';
        
        if ($idServicio <= 0) {
            throw new Exception('ID servicio inválido');
        }
        
        // Whitelist de campos permitidos
        $camposPermitidos = [
            'nombre_servicio', 'nombre_servicio_en', 'nombre_servicio_pt', 'nombre_servicio_it',
            'descripcion_servicio', 'descripcion_servicio_en', 'descripcion_servicio_pt', 'descripcion_servicio_it',
            'descripcion_corta', 'descripcion_corta_en', 'descripcion_corta_pt', 'descripcion_corta_it',
            'documentacionViajero', 'documentacionViajero_en', 'documentacionViajero_pt', 'documentacionViajero_it',
            'observaciones', 'observaciones_en', 'observaciones_pt', 'observaciones_it',
            'idCategoria_servicio', 'idTextoMiniaturas', 'idOrigen', 'idDestino'
        ];
        
        if (!in_array($campo, $camposPermitidos)) {
            throw new Exception('Campo no permitido');
        }
        
        $sql = "UPDATE servicio SET $campo = :valor WHERE idServicio = :idServicio";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['valor' => $valor, 'idServicio' => $idServicio]);
        
        echo json_encode(['success' => true, 'campo' => $campo]);
        
    } else {
        throw new Exception('Acción no válida');
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
