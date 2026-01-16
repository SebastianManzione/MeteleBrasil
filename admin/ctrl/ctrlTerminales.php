<?php
session_start();
require_once("../classes/transporte.php");

// Validar sesión admin
if (!isset($_SESSION['login'])) {
    header("Location: ../../login.php");
    exit();
}

$action = $_REQUEST['action'] ?? '';

switch ($action) {
    case 'getAll':
        // Devolver todos los terminales en JSON
        header('Content-Type: application/json');
        echo json_encode(getAllTerminales());
        break;
    
    case 'getByTipo':
        // Devolver terminales por tipo (para select)
        $idTipo = $_GET['idTipo'] ?? 0;
        $terminales = getTerminalesByTipo($idTipo);
        foreach ($terminales as $t) {
            echo "<option value='{$t['idTerminal']}'>{$t['nombre']} ({$t['ciudad']})</option>";
        }
        break;
    
    case 'getByCiudad':
        // Devolver terminales por ciudad (para autocomplete)
        $ciudad = $_GET['ciudad'] ?? '';
        header('Content-Type: application/json');
        echo json_encode(getTerminalesByCiudad($ciudad));
        break;
    
    case 'insert':
        // Alta de terminal
        $datos = [
            'nombre' => $_POST['nombre'],
            'direccion' => $_POST['direccion'] ?? '',
            'latitud' => !empty($_POST['latitud']) ? floatval($_POST['latitud']) : null,
            'longitud' => !empty($_POST['longitud']) ? floatval($_POST['longitud']) : null,
            'idPais' => !empty($_POST['idPais']) ? intval($_POST['idPais']) : null,
            'idEstado' => !empty($_POST['idEstado']) ? intval($_POST['idEstado']) : null,
            'ciudad' => $_POST['ciudad'],
            'codigo_iata' => !empty($_POST['codigo_iata']) ? strtoupper($_POST['codigo_iata']) : null,
            'idTipoTransporte' => intval($_POST['idTipoTransporte']),
            'observaciones' => $_POST['observaciones'] ?? '',
            'habilitado' => isset($_POST['habilitado']) ? 1 : 0
        ];
        
        try {
            $idTerminal = insertTerminal($datos);
            header("Location: ../terminalesLista.php?success=1");
        } catch (Exception $e) {
            header("Location: ../terminalAlta.php?error=" . urlencode($e->getMessage()));
        }
        exit();
        break;
    
    case 'update':
        // Actualización de terminal
        require("../classes/conexion.php");
        
        $idTerminal = intval($_POST['idTerminal']);
        $consulta = "UPDATE terminal_transporte SET 
                     nombre = :nombre,
                     direccion = :direccion,
                     latitud = :latitud,
                     longitud = :longitud,
                     ciudad = :ciudad,
                     codigo_iata = :codigo_iata,
                     idTipoTransporte = :idTipoTransporte,
                     observaciones = :observaciones,
                     habilitado = :habilitado
                     WHERE idTerminal = :idTerminal";
        
        $datos = [
            'idTerminal' => $idTerminal,
            'nombre' => $_POST['nombre'],
            'direccion' => $_POST['direccion'] ?? '',
            'latitud' => !empty($_POST['latitud']) ? floatval($_POST['latitud']) : null,
            'longitud' => !empty($_POST['longitud']) ? floatval($_POST['longitud']) : null,
            'ciudad' => $_POST['ciudad'],
            'codigo_iata' => !empty($_POST['codigo_iata']) ? strtoupper($_POST['codigo_iata']) : null,
            'idTipoTransporte' => intval($_POST['idTipoTransporte']),
            'observaciones' => $_POST['observaciones'] ?? '',
            'habilitado' => isset($_POST['habilitado']) ? 1 : 0
        ];
        
        try {
            $comando = $pdo->prepare($consulta);
            $comando->execute($datos);
            header("Location: ../terminalesLista.php?success=1");
        } catch (Exception $e) {
            header("Location: ../terminalAlta.php?id={$idTerminal}&error=" . urlencode($e->getMessage()));
        }
        exit();
        break;
    
    case 'delete':
        // Eliminar terminal (soft delete)
        require("../classes/conexion.php");
        
        $idTerminal = intval($_GET['id']);
        
        // Verificar si hay rutas usando esta terminal
        $check = $pdo->prepare("SELECT COUNT(*) as total FROM ruta_paradas WHERE idTerminal = :idTerminal");
        $check->execute(['idTerminal' => $idTerminal]);
        $result = $check->fetch(PDO::FETCH_ASSOC);
        
        if ($result['total'] > 0) {
            // No eliminar, solo deshabilitar
            $consulta = "UPDATE terminal_transporte SET habilitado = 0 WHERE idTerminal = :idTerminal";
            $comando = $pdo->prepare($consulta);
            $comando->execute(['idTerminal' => $idTerminal]);
            header("Location: ../terminalesLista.php?success=deleted&warning=disabled");
        } else {
            // Eliminar completamente si no hay referencias
            $consulta = "DELETE FROM terminal_transporte WHERE idTerminal = :idTerminal";
            $comando = $pdo->prepare($consulta);
            $comando->execute(['idTerminal' => $idTerminal]);
            header("Location: ../terminalesLista.php?success=deleted");
        }
        exit();
        break;
    
    case 'getOne':
        // Devolver una terminal específica en JSON
        $idTerminal = $_GET['id'] ?? 0;
        header('Content-Type: application/json');
        echo json_encode(getTerminal($idTerminal));
        break;
    
    default:
        header("Location: ../terminalesLista.php");
        exit();
}
?>
