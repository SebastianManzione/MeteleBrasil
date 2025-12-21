<?php
include("../classes/prestador_comision.php");

session_start();

if ($_SERVER["REQUEST_METHOD"]=="POST") {

  if (isset($_POST["setComisionPrestador"])) {
    $idPrestador = $_POST["idPrestador"];
    $nombre = $_POST["nombre"];
    $comisionVendedor = $_POST["comisionVendedor"];
    $comisionSistema = $_POST["comisionSistema"];

    $resultado = setComisionPrestador($idPrestador, $nombre, $comisionVendedor, $comisionSistema);
    echo $resultado;
  }

  if (isset($_POST["updateComisionPrestador"])) {
    $idPrestadorComision = $_POST["idPrestadorComision"];
    $nombre = $_POST["nombre"];
    $comisionVendedor = $_POST["comisionVendedor"];
    $comisionSistema = $_POST["comisionSistema"];

    $resultado = updateComisionPrestador($idPrestadorComision, $nombre, $comisionVendedor, $comisionSistema);

    // Retornar información completa de las actualizaciones
    if (is_array($resultado)) {
        echo json_encode($resultado);
    } else {
        echo $resultado;
    }
  }

  if (isset($_POST["deleteComisionPrestador"]) && is_numeric($_POST["deleteComisionPrestador"])) {
    $idPrestadorComision = $_POST["deleteComisionPrestador"];
    $resultado = deleteComisionPrestador($idPrestadorComision);

    // Retornar información completa de lo eliminado
    if (is_array($resultado)) {
        echo json_encode($resultado);
    } else {
        echo $resultado;
    }
  }

  if (isset($_POST["getComisionesPrestador"])) {
    $idPrestador = $_POST["idPrestador"];
    $comisiones = getComisionesPrestador($idPrestador);
    echo json_encode($comisiones);
  }

  if (isset($_POST["checkComisionDuplicada"])) {
    $idPrestador = $_POST["idPrestador"];
    $comisionVendedor = $_POST["comisionVendedor"];
    $comisionSistema = $_POST["comisionSistema"];

    // Verificar si ya existe una comisión con los mismos porcentajes para este prestador
    require("../classes/conexion.php");
    $data = ["idPrestador" => $idPrestador, "comisionVendedor" => $comisionVendedor, "comisionSistema" => $comisionSistema];
    $consulta = "SELECT COUNT(*) as total FROM prestador_comision WHERE idPrestador = :idPrestador AND comisionVendedor = :comisionVendedor AND comisionSistema = :comisionSistema";
    $comando = $pdo->prepare($consulta);
    $comando->execute($data);
    $resultado = $comando->fetch(PDO::FETCH_ASSOC);

    echo json_encode(array(
        'duplicada' => $resultado['total'] > 0,
        'mensaje' => $resultado['total'] > 0 ? 'Ya existe una comisión con los mismos porcentajes (' . $comisionVendedor . '% vendedor, ' . $comisionSistema . '% sistema) para este prestador.' : 'Configuración de porcentajes disponible.'
    ));
  }

}
?>
