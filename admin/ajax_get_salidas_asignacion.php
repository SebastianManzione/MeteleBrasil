<?php
// Endpoint AJAX - información de salidas que usan una asignación específica
require_once("classes/conexion.php");
require_once("classes/comision_prestador.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["idServicioComisionPrestador"])) {
    $idServicioComisionPrestador = $_POST["idServicioComisionPrestador"];

    // Obtener las salidas que usan esta asignación específica
    $salidas = obtenerSalidasConAsignacion($idServicioComisionPrestador);

    // Obtener el total de salidas para mostrar si hay más de 5
    require("classes/conexion.php");
    $data = ["idServicioComisionPrestador" => $idServicioComisionPrestador];
    $consulta = "SELECT COUNT(*) as total FROM servicio_salidas WHERE idComisionPrestador = :idServicioComisionPrestador";
    $comando = $pdo->prepare($consulta);
    $comando->execute($data);
    $resultado = $comando->fetch(PDO::FETCH_ASSOC);

    if (count($salidas) > 0) {
        echo json_encode(array(
            'success' => true,
            'salidas' => $salidas,
            'total' => $resultado['total']
        ));
    } else {
        echo json_encode(array(
            'success' => false,
            'message' => 'No se encontraron salidas usando esta asignación.'
        ));
    }
} else {
    echo json_encode(array(
        'success' => false,
        'message' => 'Parámetros inválidos.'
    ));
}
