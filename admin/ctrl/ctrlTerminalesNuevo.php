<?php
/**
 * Controller para gestión de terminales (tabla terminal_transporte en BD experimental)
 */

header('Content-Type: application/json');

// Conectar a BD experimental
try {
    $pdo = new PDO(
        'mysql:host=localhost;dbname=metelebrasil_experimental;charset=utf8mb4',
        'root',
        ''
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error de conexión: ' . $e->getMessage()]);
    exit;
}

$action = isset($_POST['action']) ? $_POST['action'] : '';
$response = ['success' => false, 'message' => 'Acción no especificada'];

try {
    switch ($action) {
        case 'insert':
            $nombre = trim($_POST['nombre'] ?? '');
            $idTipoTransporte = intval($_POST['idTipoTransporte'] ?? 0);
            $codigo_iata = trim($_POST['codigo_iata'] ?? '');
            $direccion = trim($_POST['direccion'] ?? '');
            $ciudad = trim($_POST['ciudad'] ?? '');
            $estado = trim($_POST['estado'] ?? '');
            $pais = trim($_POST['pais'] ?? '');
            $latitud = trim($_POST['latitud'] ?? '');
            $longitud = trim($_POST['longitud'] ?? '');
            $observaciones = trim($_POST['observaciones'] ?? '');
            $habilitado = isset($_POST['habilitado']) ? 1 : 1; // Por defecto habilitado
            
            // Validaciones
            if (empty($nombre) || !$idTipoTransporte || empty($ciudad) || empty($pais)) {
                throw new Exception("Campos requeridos: nombre, tipo de transporte, ciudad y país");
            }
            
            $stmt = $pdo->prepare(
                "INSERT INTO terminal_transporte 
                (nombre, idTipoTransporte, codigo_iata, direccion, ciudad, estado, pais, 
                 latitud, longitud, observaciones, habilitado)
                VALUES 
                (:nombre, :idTipoTransporte, :codigo_iata, :direccion, :ciudad, :estado, :pais, 
                 :latitud, :longitud, :observaciones, :habilitado)"
            );
            
            $stmt->execute([
                ':nombre' => $nombre,
                ':idTipoTransporte' => $idTipoTransporte,
                ':codigo_iata' => !empty($codigo_iata) ? strtoupper($codigo_iata) : null,
                ':direccion' => !empty($direccion) ? $direccion : null,
                ':ciudad' => $ciudad,
                ':estado' => !empty($estado) ? $estado : null,
                ':pais' => $pais,
                ':latitud' => !empty($latitud) ? floatval($latitud) : null,
                ':longitud' => !empty($longitud) ? floatval($longitud) : null,
                ':observaciones' => !empty($observaciones) ? $observaciones : null,
                ':habilitado' => $habilitado
            ]);
            
            $response = [
                'success' => true,
                'message' => "Terminal creada exitosamente",
                'id' => $pdo->lastInsertId()
            ];
            break;

        case 'update':
            $idTerminal = intval($_POST['idTerminal'] ?? 0);
            $nombre = trim($_POST['nombre'] ?? '');
            $idTipoTransporte = intval($_POST['idTipoTransporte'] ?? 0);
            $codigo_iata = trim($_POST['codigo_iata'] ?? '');
            $direccion = trim($_POST['direccion'] ?? '');
            $ciudad = trim($_POST['ciudad'] ?? '');
            $estado = trim($_POST['estado'] ?? '');
            $pais = trim($_POST['pais'] ?? '');
            $latitud = trim($_POST['latitud'] ?? '');
            $longitud = trim($_POST['longitud'] ?? '');
            $observaciones = trim($_POST['observaciones'] ?? '');
            $habilitado = isset($_POST['habilitado']) ? 1 : 1;
            
            if (!$idTerminal) {
                throw new Exception("ID de terminal inválido");
            }
            
            if (empty($nombre) || !$idTipoTransporte || empty($ciudad) || empty($pais)) {
                throw new Exception("Campos requeridos: nombre, tipo de transporte, ciudad y país");
            }
            
            $stmt = $pdo->prepare(
                "UPDATE terminal_transporte SET 
                nombre = :nombre, 
                idTipoTransporte = :idTipoTransporte,
                codigo_iata = :codigo_iata,
                direccion = :direccion,
                ciudad = :ciudad,
                estado = :estado,
                pais = :pais,
                latitud = :latitud,
                longitud = :longitud,
                observaciones = :observaciones,
                habilitado = :habilitado
                WHERE idTerminal = :id"
            );
            
            $stmt->execute([
                ':nombre' => $nombre,
                ':idTipoTransporte' => $idTipoTransporte,
                ':codigo_iata' => !empty($codigo_iata) ? strtoupper($codigo_iata) : null,
                ':direccion' => !empty($direccion) ? $direccion : null,
                ':ciudad' => $ciudad,
                ':estado' => !empty($estado) ? $estado : null,
                ':pais' => $pais,
                ':latitud' => !empty($latitud) ? floatval($latitud) : null,
                ':longitud' => !empty($longitud) ? floatval($longitud) : null,
                ':observaciones' => !empty($observaciones) ? $observaciones : null,
                ':habilitado' => $habilitado,
                ':id' => $idTerminal
            ]);
            
            $response = [
                'success' => true,
                'message' => "Terminal actualizada exitosamente"
            ];
            break;

        case 'delete':
            $idTerminal = intval($_POST['idTerminal'] ?? 0);

            if (!$idTerminal) {
                throw new Exception("ID inválido");
            }

            // Verificar existencia
            $stmt = $pdo->prepare("SELECT idTerminal FROM terminal_transporte WHERE idTerminal = :id");
            $stmt->execute([':id' => $idTerminal]);
            $terminal = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$terminal) {
                throw new Exception("Terminal no encontrada");
            }

            // Intentar eliminar
            $stmt = $pdo->prepare("DELETE FROM terminal_transporte WHERE idTerminal = :id");
            $stmt->execute([':id' => $idTerminal]);

            $response = [
                'success' => true,
                'message' => "Terminal eliminada exitosamente"
            ];
            break;

        default:
            throw new Exception("Acción desconocida");
    }
} catch (Exception $e) {
    $response = [
        'success' => false,
        'message' => $e->getMessage()
    ];
}

echo json_encode($response);
exit;
