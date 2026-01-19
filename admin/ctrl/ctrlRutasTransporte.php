<?php
/**
 * CONTROLLER: RUTAS DE TRANSPORTE
 * Gestión CRUD de rutas
 */

require_once("../classes/conexion.php");
require_once("../classes/transporte.php");

// Leer action desde POST (si viene en formulario) o GET (si viene por URL)
$action = isset($_POST['action']) ? $_POST['action'] : (isset($_GET['action']) ? $_GET['action'] : '');
$idRuta = isset($_GET['id']) ? intval($_GET['id']) : 0;

switch ($action) {
    
    case 'insert':
        // Insertar nueva ruta
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'nombre' => $_POST['nombre'],
                'nombre_en' => $_POST['nombre_en'] ?? '',
                'nombre_pt' => $_POST['nombre_pt'] ?? '',
                'nombre_it' => $_POST['nombre_it'] ?? '',
                'descripcion' => $_POST['descripcion'] ?? '',
                'descripcion_en' => $_POST['descripcion_en'] ?? '',
                'descripcion_pt' => $_POST['descripcion_pt'] ?? '',
                'descripcion_it' => $_POST['descripcion_it'] ?? '',
                'idTipoTransporte' => intval($_POST['idTipoTransporte']),
                'idEmpresa' => !empty($_POST['idEmpresa']) ? intval($_POST['idEmpresa']) : null,
                'idPrestador' => !empty($_POST['idPrestador']) ? intval($_POST['idPrestador']) : null,
                'duracion_estimada' => $_POST['duracion_estimada'] ?? '',
                'distancia_km' => !empty($_POST['distancia_km']) ? intval($_POST['distancia_km']) : null,
                'foto_principal' => $_POST['foto_principal'] ?? '',
                'habilitado' => isset($_POST['habilitado']) ? 1 : 0
            ];
            
            $idRuta = insertRuta($datos);
            
            if ($idRuta > 0) {
                header("Location: ../rutasTransporteLista.php?success=1");
                exit();
            } else {
                header("Location: ../rutaTransporteAlta.php?error=1");
                exit();
            }
        }
        break;
    
    case 'update':
        // Actualizar ruta existente
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $idRuta > 0) {
            $datos = [
                'nombre' => $_POST['nombre'],
                'nombre_en' => $_POST['nombre_en'] ?? '',
                'nombre_pt' => $_POST['nombre_pt'] ?? '',
                'nombre_it' => $_POST['nombre_it'] ?? '',
                'descripcion' => $_POST['descripcion'] ?? '',
                'descripcion_en' => $_POST['descripcion_en'] ?? '',
                'descripcion_pt' => $_POST['descripcion_pt'] ?? '',
                'descripcion_it' => $_POST['descripcion_it'] ?? '',
                'idTipoTransporte' => intval($_POST['idTipoTransporte']),
                'idEmpresa' => !empty($_POST['idEmpresa']) ? intval($_POST['idEmpresa']) : null,
                'idPrestador' => !empty($_POST['idPrestador']) ? intval($_POST['idPrestador']) : null,
                'duracion_estimada' => $_POST['duracion_estimada'] ?? '',
                'distancia_km' => !empty($_POST['distancia_km']) ? intval($_POST['distancia_km']) : null,
                'foto_principal' => $_POST['foto_principal'] ?? '',
                'habilitado' => isset($_POST['habilitado']) ? 1 : 0
            ];
            
            $resultado = updateRuta($idRuta, $datos);
            
            if ($resultado) {
                header("Location: ../rutasTransporteLista.php?success=1");
                exit();
            } else {
                header("Location: ../rutaTransporteAlta.php?id=$idRuta&error=1");
                exit();
            }
        }
        break;
    
    case 'delete':
        // Eliminar ruta
        if ($idRuta > 0) {
            $resultado = deleteRuta($idRuta);
            
            if ($resultado) {
                header("Location: ../rutasTransporteLista.php?success=2");
                exit();
            } else {
                header("Location: ../rutasTransporteLista.php?error=2");
                exit();
            }
        }
        break;
    
    case 'getAll':
        // API: Obtener todas las rutas
        header('Content-Type: application/json');
        echo json_encode(getAllRutas());
        exit();
        break;
    
    case 'getByTipo':
        // API: Obtener rutas por tipo
        $idTipoTransporte = isset($_GET['idTipoTransporte']) ? intval($_GET['idTipoTransporte']) : 0;
        header('Content-Type: application/json');
        
        $todasRutas = getAllRutas();
        $rutasFiltradas = array_filter($todasRutas, function($ruta) use ($idTipoTransporte) {
            return $ruta['idTipoTransporte'] == $idTipoTransporte;
        });
        
        echo json_encode(array_values($rutasFiltradas));
        exit();
        break;
    
    default:
        header("Location: ../rutasTransporteLista.php");
        exit();
        break;
}
?>
