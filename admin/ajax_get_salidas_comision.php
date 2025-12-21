<?php
// Endpoint AJAX - incluir conexión y clases necesarias
require_once("classes/conexion.php");
require_once("classes/prestador_comision.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["idPrestadorComision"])) {
    $idPrestadorComision = $_POST["idPrestadorComision"];

    // Obtener las salidas que usan esta comisión
    $salidas = obtenerSalidasConComision($idPrestadorComision);

    // Obtener el total de salidas para mostrar si hay más de 5
    require("classes/conexion.php");
    $data = ["idPrestadorComision" => $idPrestadorComision];
    $consulta = "SELECT COUNT(*) as total FROM servicio_salidas ss
                 INNER JOIN servicio_comision_prestador scp ON scp.idServicioComisionPrestador = ss.idComisionPrestador
                 WHERE scp.idPrestadorComision = :idPrestadorComision";
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
            'message' => 'No se encontraron salidas usando esta comisión.'
        ));
    }
} else {
    echo json_encode(array(
        'success' => false,
        'message' => 'Parámetros inválidos.'
    ));
}
?>
