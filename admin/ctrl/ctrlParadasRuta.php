<?php
/**
 * CONTROLLER: PARADAS DE RUTA
 * Gestión CRUD de paradas (origen/destino múltiples)
 */

require_once("../classes/conexion.php");
require_once("../classes/transporte.php");

$action = isset($_GET['action']) ? $_GET['action'] : '';
$idParada = isset($_GET['idParada']) ? intval($_GET['idParada']) : 0;
$idRuta = isset($_GET['idRuta']) ? intval($_GET['idRuta']) : (isset($_POST['idRuta']) ? intval($_POST['idRuta']) : 0);

switch ($action) {
    
    case 'insert':
        // Insertar nueva parada
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $idRuta > 0) {
            $datos = [
                'idRuta' => $idRuta,
                'idTerminal' => intval($_POST['idTerminal']),
                'orden' => intval($_POST['orden']),
                'es_origen' => isset($_POST['es_origen']) ? 1 : 0,
                'es_destino' => isset($_POST['es_destino']) ? 1 : 0,
                'tiempo_desde_inicio' => $_POST['tiempo_desde_inicio'] ?? ''
            ];
            
            $idParadaNew = insertParadaRuta($datos);
            
            if ($idParadaNew > 0) {
                header("Location: ../rutaTransporteParadas.php?id=$idRuta&success=1");
                exit();
            } else {
                header("Location: ../rutaTransporteParadas.php?id=$idRuta&error=1");
                exit();
            }
        }
        break;
    
    case 'delete':
        // Eliminar parada
        if ($idParada > 0 && $idRuta > 0) {
            $resultado = deleteParadaRuta($idParada);
            
            if ($resultado) {
                header("Location: ../rutaTransporteParadas.php?id=$idRuta&success=2");
                exit();
            } else {
                header("Location: ../rutaTransporteParadas.php?id=$idRuta&error=2");
                exit();
            }
        }
        break;
    
    case 'getOrigenes':
        // API: Obtener orígenes de una ruta
        $idRuta = isset($_GET['idRuta']) ? intval($_GET['idRuta']) : 0;
        header('Content-Type: application/json');
        echo json_encode(getOrigenesRuta($idRuta));
        exit();
        break;
    
    case 'getDestinos':
        // API: Obtener destinos de una ruta
        $idRuta = isset($_GET['idRuta']) ? intval($_GET['idRuta']) : 0;
        header('Content-Type: application/json');
        echo json_encode(getDestinosRuta($idRuta));
        exit();
        break;
    
    default:
        if ($idRuta > 0) {
            header("Location: ../rutaTransporteParadas.php?id=$idRuta");
        } else {
            header("Location: ../rutasTransporteLista.php");
        }
        exit();
        break;
}

// Función auxiliar para eliminar parada
function deleteParadaRuta($idParada) {
    require("../classes/conexion.php");
    
    $consulta = "DELETE FROM ruta_paradas WHERE idParada = :idParada";
    $comando = $pdo->prepare($consulta);
    return $comando->execute(['idParada' => $idParada]);
}
?>
