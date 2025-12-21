<?php
// Endpoint AJAX - incluir conexión y clases necesarias
require_once("classes/conexion.php");
require_once("classes/prestador.php");
require_once("classes/prestador_comision.php");
require_once("classes/comision_prestador.php");

// Configurar header para JSON
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["idPrestador"]) && isset($_POST["idServicio"])) {
    $idPrestador = $_POST["idPrestador"];
    $idServicio = $_POST["idServicio"];
    $soloDisponibles = isset($_POST["solo_disponibles"]) && $_POST["solo_disponibles"];

    $response = array();

    if ($soloDisponibles) {
        // PARA altaSalidas.php: Obtener comisiones YA ASIGNADAS al servicio para este prestador
        // (mostrar las comisiones disponibles para usar en la salida)
        $comisionesAsignadas = getComisionesPrestadorServicioIdPrestadorIdServicio($idServicio, $idPrestador);

        if (count($comisionesAsignadas) > 0) {
            $response['success'] = true;
            $response['comisiones'] = array();
            foreach ($comisionesAsignadas as $comision) {
                $response['comisiones'][] = array(
                    'id' => $comision["idServicioComisionPrestador"],
                    'nombre' => $comision["nombre_comision"] ?? "Comisión asignada",
                    'vendedor' => $comision["comisionVendedor"],
                    'sistema' => $comision["comisionSistema"]
                );
            }
        } else {
            $response['success'] = false;
            $response['message'] = 'Este prestador no tiene comisiones asignadas a este servicio';
        }
    } else {
        // PARA servicioComisionPrestador.php: Obtener comisiones disponibles del prestador
        // (comisiones que NO están asignadas al servicio actual)
        $todasComisiones = getComisionesPrestador($idPrestador);
        $comisionesAsignadas = getComisionesPrestadorServicioIdPrestadorIdServicio($idServicio, $idPrestador);

        // Filtrar para mostrar solo las comisiones que NO están asignadas aún
        $comisionesDisponibles = array_filter($todasComisiones, function($comision) use ($comisionesAsignadas) {
            foreach ($comisionesAsignadas as $asignada) {
                // Si esta comisión específica ya está asignada, no la mostramos
                if ($asignada['idPrestadorComision'] == $comision["idPrestadorComision"]) {
                    return false;
                }
            }
            return true; // No está asignada aún
        });

        if (count($comisionesDisponibles) > 0) {
            $response['success'] = true;
            $response['comisiones'] = array();
            foreach ($comisionesDisponibles as $comision) {
                $response['comisiones'][] = array(
                    'id' => $comision["idPrestadorComision"],
                    'nombre' => $comision["nombre"] ?? "Comisión",
                    'vendedor' => $comision["comisionVendedor"],
                    'sistema' => $comision["comisionSistema"]
                );
            }
        } else {
            $response['success'] = false;
            $response['message'] = 'Este prestador no tiene comisiones disponibles para asignar';
        }
    }

    echo json_encode($response);
} else {
    echo json_encode(array(
        'success' => false,
        'message' => 'Error: parámetros inválidos'
    ));
}
?>
