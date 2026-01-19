<?php
/**
 * CONTROLLER: PARADAS DE RUTA
 * Gestión CRUD de paradas unificadas
 */

require_once("../classes/conexion.php");
require_once("../classes/transporte.php");

$action = isset($_GET['action']) ? $_GET['action'] : (isset($_POST['action']) ? $_POST['action'] : '');
$idParada = isset($_GET['idParada']) ? intval($_GET['idParada']) : 0;
$idRuta = isset($_GET['idRuta']) ? intval($_GET['idRuta']) : (isset($_POST['idRuta']) ? intval($_POST['idRuta']) : 0);

switch ($action) {
    
    case 'insert':
        // Insertar parada existente en la ruta
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $idRuta > 0) {
            $datos = [
                ':idRuta' => $idRuta,
                ':idTerminal' => intval($_POST['idParada']),  // Frontend envía como idParada, backend usa idTerminal
                ':orden' => intval($_POST['orden']),
                ':es_origen' => isset($_POST['es_origen']) ? 1 : 0,
                ':es_destino' => isset($_POST['es_destino']) ? 1 : 0,
                ':tiempo_desde_inicio' => $_POST['tiempo_desde_inicio'] ?? ''
            ];
            
            $idParadaRuta = insertParadaRuta($datos);
            
            if ($idParadaRuta > 0) {
                header("Location: ../rutaTransporteParadas.php?id=$idRuta&success=1");
                exit();
            } else {
                header("Location: ../rutaTransporteParadas.php?id=$idRuta&error=1");
                exit();
            }
        }
        break;
    
    case 'insert_nueva':
        // Crear nueva parada e insertarla en la ruta
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $idRuta > 0) {
            // Primero crear el terminal
            $datosTerminal = [
                ':nombre' => $_POST['nombre'],
                ':idTipoTransporte' => 1, // Por defecto bus
                ':direccion' => $_POST['direccion'],
                ':ciudad' => $_POST['ciudad'] ?? '',
                ':estado' => $_POST['estado'] ?? '',
                ':pais' => $_POST['pais'] ?? 'Argentina',
                ':latitud' => !empty($_POST['latitud']) ? floatval($_POST['latitud']) : null,
                ':longitud' => !empty($_POST['longitud']) ? floatval($_POST['longitud']) : null,
                ':habilitado' => 1
            ];
            
            $idTerminalNuevo = insertTerminal($datosTerminal);
            
            if ($idTerminalNuevo > 0) {
                // Ahora agregar a la ruta
                $datosRuta = [
                    ':idRuta' => $idRuta,
                    ':idTerminal' => $idTerminalNuevo,
                    ':orden' => intval($_POST['orden']),
                    ':es_origen' => isset($_POST['es_origen']) ? 1 : 0,
                    ':es_destino' => isset($_POST['es_destino']) ? 1 : 0,
                    ':tiempo_desde_inicio' => $_POST['tiempo_desde_inicio'] ?? ''
                ];
                
                $idParadaRuta = insertParadaRuta($datosRuta);
                
                if ($idParadaRuta > 0) {
                    header("Location: ../rutaTransporteParadas.php?id=$idRuta&success=1");
                    exit();
                }
            }
            
            header("Location: ../rutaTransporteParadas.php?id=$idRuta&error=1");
            exit();
        }
        break;
    
    case 'reorder':
        // Reordenar paradas (mover arriba o abajo)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idRutaParada = isset($_POST['idRutaParada']) ? intval($_POST['idRutaParada']) : 0;
            $direction = isset($_POST['direction']) ? $_POST['direction'] : '';
            
            if ($idRutaParada > 0 && in_array($direction, ['up', 'down'])) {
                require_once("../classes/conexion.php");
                
                // Obtener la parada actual
                $stmtCurrent = $pdo->prepare("SELECT idRutaParada, idRuta, orden FROM ruta_paradas WHERE idRutaParada = :id");
                $stmtCurrent->execute(['id' => $idRutaParada]);
                $current = $stmtCurrent->fetch(PDO::FETCH_ASSOC);
                
                if ($current) {
                    $ordenActual = $current['orden'];
                    $idRuta = $current['idRuta'];
                    
                    // Calcular nuevo orden
                    $ordenNuevo = ($direction === 'up') ? ($ordenActual - 1) : ($ordenActual + 1);
                    
                    // Buscar la parada con la que intercambiar
                    $stmtTarget = $pdo->prepare("SELECT idRutaParada, orden FROM ruta_paradas WHERE idRuta = :idRuta AND orden = :orden");
                    $stmtTarget->execute(['idRuta' => $idRuta, 'orden' => $ordenNuevo]);
                    $target = $stmtTarget->fetch(PDO::FETCH_ASSOC);
                    
                    if ($target) {
                        // Intercambiar órdenes
                        $pdo->beginTransaction();
                        
                        try {
                            // Temporalmente asignar orden negativo para evitar conflicto
                            $stmtTemp = $pdo->prepare("UPDATE ruta_paradas SET orden = -1 WHERE idRutaParada = :id");
                            $stmtTemp->execute(['id' => $idRutaParada]);
                            
                            // Mover la parada target al orden actual
                            $stmtMove1 = $pdo->prepare("UPDATE ruta_paradas SET orden = :orden WHERE idRutaParada = :id");
                            $stmtMove1->execute(['orden' => $ordenActual, 'id' => $target['idRutaParada']]);
                            
                            // Mover la parada actual al nuevo orden
                            $stmtMove2 = $pdo->prepare("UPDATE ruta_paradas SET orden = :orden WHERE idRutaParada = :id");
                            $stmtMove2->execute(['orden' => $ordenNuevo, 'id' => $idRutaParada]);
                            
                            $pdo->commit();
                            
                            header('Content-Type: application/json');
                            echo json_encode(['success' => true]);
                            exit();
                        } catch (Exception $e) {
                            $pdo->rollBack();
                            header('Content-Type: application/json');
                            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
                            exit();
                        }
                    }
                }
            }
        }
        
        header('Content-Type: application/json');
        echo json_encode(['success' => false]);
        exit();
        break;
    
    case 'reorder_drag':
        // Reordenar paradas con drag and drop
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idParadaDragged = isset($_POST['idParadaDragged']) ? intval($_POST['idParadaDragged']) : 0;
            $idParadaTarget = isset($_POST['idParadaTarget']) ? intval($_POST['idParadaTarget']) : 0;
            $ordenDragged = isset($_POST['ordenDragged']) ? intval($_POST['ordenDragged']) : 0;
            $ordenTarget = isset($_POST['ordenTarget']) ? intval($_POST['ordenTarget']) : 0;
            $idRuta = isset($_POST['idRuta']) ? intval($_POST['idRuta']) : 0;
            
            if ($idParadaDragged > 0 && $idParadaTarget > 0 && $idRuta > 0) {
                require_once("../classes/conexion.php");
                
                try {
                    $pdo->beginTransaction();
                    
                    // Determinar si se mueve hacia arriba o abajo
                    if ($ordenDragged < $ordenTarget) {
                        // Mover hacia abajo: decrementar orden de las filas intermedias
                        $stmt = $pdo->prepare("
                            UPDATE ruta_paradas 
                            SET orden = orden - 1 
                            WHERE idRuta = :idRuta 
                            AND orden > :ordenDragged 
                            AND orden <= :ordenTarget
                        ");
                        $stmt->execute([
                            'idRuta' => $idRuta,
                            'ordenDragged' => $ordenDragged,
                            'ordenTarget' => $ordenTarget
                        ]);
                        
                        // Mover la fila arrastrada al nuevo orden
                        $stmt2 = $pdo->prepare("
                            UPDATE ruta_paradas 
                            SET orden = :ordenTarget 
                            WHERE idRutaParada = :idParada
                        ");
                        $stmt2->execute([
                            'ordenTarget' => $ordenTarget,
                            'idParada' => $idParadaDragged
                        ]);
                        
                    } else {
                        // Mover hacia arriba: incrementar orden de las filas intermedias
                        $stmt = $pdo->prepare("
                            UPDATE ruta_paradas 
                            SET orden = orden + 1 
                            WHERE idRuta = :idRuta 
                            AND orden >= :ordenTarget 
                            AND orden < :ordenDragged
                        ");
                        $stmt->execute([
                            'idRuta' => $idRuta,
                            'ordenTarget' => $ordenTarget,
                            'ordenDragged' => $ordenDragged
                        ]);
                        
                        // Mover la fila arrastrada al nuevo orden
                        $stmt2 = $pdo->prepare("
                            UPDATE ruta_paradas 
                            SET orden = :ordenTarget 
                            WHERE idRutaParada = :idParada
                        ");
                        $stmt2->execute([
                            'ordenTarget' => $ordenTarget,
                            'idParada' => $idParadaDragged
                        ]);
                    }
                    
                    $pdo->commit();
                    
                    header('Content-Type: application/json');
                    echo json_encode(['success' => true]);
                    exit();
                    
                } catch (Exception $e) {
                    $pdo->rollBack();
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
                    exit();
                }
            }
        }
        
        header('Content-Type: application/json');
        echo json_encode(['success' => false]);
        exit();
        break;
    
    case 'delete':
        // Eliminar parada de ruta
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

// Función auxiliar para eliminar parada de ruta
function deleteParadaRuta($idRutaParada) {
    require("../classes/conexion.php");
    
    $consulta = "DELETE FROM ruta_paradas WHERE idRutaParada = :idRutaParada";
    $comando = $pdo->prepare($consulta);
    return $comando->execute(['idRutaParada' => $idRutaParada]);
}
?>
