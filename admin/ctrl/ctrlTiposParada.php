<?php
/**
 * Controller para CRUD de Tipos de Parada
 */

header('Content-Type: application/json');

require_once(__DIR__ . "/../classes/transporte.php");
require_once(__DIR__ . "/../classes/conexion.php");

$action = isset($_POST['action']) ? $_POST['action'] : '';
$response = ['success' => false, 'message' => 'Acción no especificada'];

try {
    switch ($action) {
        case 'insert':
            $nombre = trim($_POST['nombre'] ?? '');
            $icono = trim($_POST['icono'] ?? 'fa-map-marker-alt');
            $color = trim($_POST['color'] ?? '#6c757d');
            $habilitado = isset($_POST['habilitado']) ? 1 : 0;
            
            if (empty($nombre)) {
                throw new Exception("El nombre es requerido");
            }
            
            // Validar que no sea duplicado
            $stmt = $pdo->prepare("SELECT COUNT(*) as cnt FROM tipo_parada WHERE nombre = :nombre");
            $stmt->execute(['nombre' => $nombre]);
            if ($stmt->fetch(PDO::FETCH_ASSOC)['cnt'] > 0) {
                throw new Exception("Ya existe un tipo con ese nombre");
            }
            
            $stmt = $pdo->prepare("INSERT INTO tipo_parada (nombre, icono, color, habilitado) 
                                  VALUES (:nombre, :icono, :color, :habilitado)");
            $stmt->execute([
                ':nombre' => $nombre,
                ':icono' => $icono,
                ':color' => $color,
                ':habilitado' => $habilitado
            ]);
            
            $response = [
                'success' => true,
                'message' => "Tipo creado exitosamente",
                'id' => $pdo->lastInsertId()
            ];
            break;

        case 'update':
            $idTipoPrada = intval($_POST['idTipoPrada'] ?? 0);
            $nombre = trim($_POST['nombre'] ?? '');
            $icono = trim($_POST['icono'] ?? 'fa-map-marker-alt');
            $color = trim($_POST['color'] ?? '#6c757d');
            $habilitado = isset($_POST['habilitado']) ? 1 : 0;
            
            if (!$idTipoPrada) {
                throw new Exception("ID inválido");
            }
            if (empty($nombre)) {
                throw new Exception("El nombre es requerido");
            }
            
            // Validar que no sea duplicado (excepto el actual)
            $stmt = $pdo->prepare("SELECT COUNT(*) as cnt FROM tipo_parada 
                                  WHERE nombre = :nombre AND idTipoPrada != :id");
            $stmt->execute(['nombre' => $nombre, 'id' => $idTipoPrada]);
            if ($stmt->fetch(PDO::FETCH_ASSOC)['cnt'] > 0) {
                throw new Exception("Ya existe otro tipo con ese nombre");
            }
            
            $stmt = $pdo->prepare("UPDATE tipo_parada SET nombre = :nombre, icono = :icono, 
                                 color = :color, habilitado = :habilitado 
                                 WHERE idTipoPrada = :id");
            $stmt->execute([
                ':nombre' => $nombre,
                ':icono' => $icono,
                ':color' => $color,
                ':habilitado' => $habilitado,
                ':id' => $idTipoPrada
            ]);
            
            $response = [
                'success' => true,
                'message' => "Tipo actualizado exitosamente"
            ];
            break;

        case 'delete':
            $idTipoPrada = intval($_POST['idTipoPrada'] ?? 0);
            
            if (!$idTipoPrada) {
                throw new Exception("ID inválido");
            }
            
            // Verificar que no tenga paradas asociadas
            $stmt = $pdo->prepare("SELECT COUNT(*) as cnt FROM parada WHERE idTipoPrada = :id");
            $stmt->execute(['id' => $idTipoPrada]);
            $cantParadas = $stmt->fetch(PDO::FETCH_ASSOC)['cnt'];
            
            if ($cantParadas > 0) {
                throw new Exception("No se puede eliminar: hay $cantParadas parada(s) usando este tipo");
            }
            
            // Eliminar
            $stmt = $pdo->prepare("DELETE FROM tipo_parada WHERE idTipoPrada = :id");
            $stmt->execute(['id' => $idTipoPrada]);
            
            $response = [
                'success' => true,
                'message' => "Tipo eliminado exitosamente"
            ];
            break;

        case 'get_all':
            $tipos = $pdo->query("SELECT * FROM tipo_parada ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);
            $response = [
                'success' => true,
                'data' => $tipos
            ];
            break;

        default:
            throw new Exception("Acción desconocida: " . htmlspecialchars($action));
    }
} catch (Exception $e) {
    $response = [
        'success' => false,
        'message' => $e->getMessage()
    ];
    error_log("Error en ctrlTiposParada.php: " . $e->getMessage());
}

echo json_encode($response);
?>
