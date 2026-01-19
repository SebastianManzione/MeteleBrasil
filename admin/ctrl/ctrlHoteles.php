<?php
/**
 * Controller para gestión de hoteles
 */

header('Content-Type: application/json');

require_once(__DIR__ . "/../classes/conexion.php");

$action = isset($_POST['action']) ? $_POST['action'] : '';
$response = ['success' => false, 'message' => 'Acción no especificada'];

try {
    switch ($action) {
        case 'insert':
            $nombre = trim($_POST['nombre'] ?? '');
            $direccion = trim($_POST['direccion'] ?? '');
            $ciudad = trim($_POST['ciudad'] ?? '');
            $estado = trim($_POST['estado'] ?? '');
            $pais = trim($_POST['pais'] ?? '');
            $latitud = trim($_POST['latitud'] ?? '');
            $longitud = trim($_POST['longitud'] ?? '');
            $codigo_iata = trim($_POST['codigo_iata'] ?? '');
            $descripcion = trim($_POST['descripcion'] ?? '');
            $telefono = trim($_POST['telefono'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $sitio_web = trim($_POST['sitio_web'] ?? '');
            $horario_atencion = trim($_POST['horario_atencion'] ?? '');
            $habilitado = isset($_POST['habilitado']) ? 1 : 0;
            
            // Validaciones
            if (empty($nombre) || empty($direccion) || empty($ciudad) || empty($estado) || empty($pais)) {
                throw new Exception("Campos requeridos faltantes");
            }
            
            // Si no hay coordenadas, intentar geocodificar
            if (empty($latitud) || empty($longitud)) {
                $geoResult = geocodificarDireccion("$direccion, $ciudad, $pais");
                if ($geoResult) {
                    $latitud = $geoResult['lat'];
                    $longitud = $geoResult['lon'];
                }
            }
            
            $stmt = $pdo->prepare("INSERT INTO ubicacion 
                                (nombre, tipo, direccion, ciudad, estado, pais, latitud, longitud, 
                                 codigo_iata, descripcion, telefono, email, sitio_web, horario_atencion, habilitado)
                                VALUES 
                                (:nombre, 'hotel', :direccion, :ciudad, :estado, :pais, :latitud, :longitud,
                                 :codigo_iata, :descripcion, :telefono, :email, :sitio_web, :horario_atencion, :habilitado)");
            
            $stmt->execute([
                ':nombre' => $nombre,
                ':direccion' => $direccion,
                ':ciudad' => $ciudad,
                ':estado' => $estado,
                ':pais' => $pais,
                ':latitud' => $latitud,
                ':longitud' => $longitud,
                ':codigo_iata' => $codigo_iata,
                ':descripcion' => $descripcion,
                ':telefono' => $telefono,
                ':email' => $email,
                ':sitio_web' => $sitio_web,
                ':horario_atencion' => $horario_atencion,
                ':habilitado' => $habilitado
            ]);
            
            $response = [
                'success' => true,
                'message' => "Hotel creado exitosamente",
                'id' => $pdo->lastInsertId()
            ];
            break;

        case 'update':
            $idUbicacion = intval($_POST['idUbicacion'] ?? 0);
            $nombre = trim($_POST['nombre'] ?? '');
            $direccion = trim($_POST['direccion'] ?? '');
            $ciudad = trim($_POST['ciudad'] ?? '');
            $estado = trim($_POST['estado'] ?? '');
            $pais = trim($_POST['pais'] ?? '');
            $telefono = trim($_POST['telefono'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $sitio_web = trim($_POST['sitio_web'] ?? '');
            $horario_atencion = trim($_POST['horario_atencion'] ?? '');
            $latitud = trim($_POST['latitud'] ?? '');
            $longitud = trim($_POST['longitud'] ?? '');
            $codigo_iata = trim($_POST['codigo_iata'] ?? '');
            $descripcion = trim($_POST['descripcion'] ?? '');
            $habilitado = isset($_POST['habilitado']) ? 1 : 0;
            
            if (!$idUbicacion) {
                throw new Exception("ID inválido");
            }
            
            if (empty($nombre) || empty($direccion) || empty($ciudad) || empty($estado) || empty($pais)) {
                throw new Exception("Campos requeridos faltantes");
            }
            
            // Si no hay coordenadas, intentar geocodificar
            if (empty($latitud) || empty($longitud)) {
                $geoResult = geocodificarDireccion("$direccion, $ciudad, $pais");
                if ($geoResult) {
                    $latitud = $geoResult['lat'];
                    $longitud = $geoResult['lon'];
                }
            }
            
            $stmt = $pdo->prepare("UPDATE ubicacion SET 
                                nombre = :nombre, 
                                direccion = :direccion,
                                ciudad = :ciudad,
                                estado = :estado,
                                pais = :pais,
                                latitud = :latitud,
                                longitud = :longitud,
                                codigo_iata = :codigo_iata,
                                descripcion = :descripcion,
                                telefono = :telefono,
                                email = :email,
                                sitio_web = :sitio_web,
                                horario_atencion = :horario_atencion,
                                habilitado = :habilitado
                                WHERE idUbicacion = :id AND tipo = 'hotel'");
            
            $stmt->execute([
                ':nombre' => $nombre,
                ':direccion' => $direccion,
                ':ciudad' => $ciudad,
                ':estado' => $estado,
                ':pais' => $pais,
                ':latitud' => $latitud,
                ':longitud' => $longitud,
                ':codigo_iata' => $codigo_iata,
                ':descripcion' => $descripcion,
                ':telefono' => $telefono,
                ':email' => $email,
                ':sitio_web' => $sitio_web,
                ':horario_atencion' => $horario_atencion,
                ':habilitado' => $habilitado,
                ':id' => $idUbicacion
            ]);
            
            $response = [
                'success' => true,
                'message' => "Hotel actualizado exitosamente"
            ];
            break;

        case 'delete':
            $idUbicacion = intval($_POST['idUbicacion'] ?? 0);
            
            if (!$idUbicacion) {
                throw new Exception("ID inválido");
            }
            
            // Verificar que sea un hotel
            $stmt = $pdo->prepare("SELECT tipo FROM ubicacion WHERE idUbicacion = :id");
            $stmt->execute(['id' => $idUbicacion]);
            $parada = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$parada || $parada['tipo'] !== 'hotel') {
                throw new Exception("Hotel no encontrado");
            }
            
            // Eliminar
            $stmt = $pdo->prepare("DELETE FROM ubicacion WHERE idUbicacion = :id");
            $stmt->execute(['id' => $idUbicacion]);
            
            $response = [
                'success' => true,
                'message' => "Hotel eliminado exitosamente"
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
    error_log("Error en ctrlHoteles.php: " . $e->getMessage());
}

echo json_encode($response);

/**
 * Geocodificar dirección usando Nominatim (OpenStreetMap)
 */
function geocodificarDireccion($direccion) {
    try {
        $url = "https://nominatim.openstreetmap.org/search?q=" . urlencode($direccion) . "&format=json&limit=1";
        $response = @file_get_contents($url, false, stream_context_create(['http' => ['timeout' => 5]]));
        
        if ($response !== false) {
            $data = json_decode($response, true);
            if (!empty($data) && isset($data[0]['lat']) && isset($data[0]['lon'])) {
                return [
                    'lat' => $data[0]['lat'],
                    'lon' => $data[0]['lon']
                ];
            }
        }
    } catch (Exception $e) {
        // Silenciosamente fallar si no se puede geocodificar
    }
    return null;
}
?>
