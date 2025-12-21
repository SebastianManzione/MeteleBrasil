<?php
include("../classes/comision_prestador.php");

session_start();

if ($_SERVER["REQUEST_METHOD"]=="POST") {

  if (isset($_POST["borraComisionServicio"]) && is_numeric($_POST["borraComisionServicio"])) {
    $idServicioComisionPrestador = $_POST["borraComisionServicio"];
    $resultado = deleteComisionPrestadorServicio($idServicioComisionPrestador);
    echo $resultado;
  }

}
?>
