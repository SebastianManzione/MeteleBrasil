<?php 



if ($_SERVER["REQUEST_METHOD"]=="POST") {
require("../classes/servicios_adicionales.php");

  if (isset($_POST["consultaReservaAdicionales"])) {
$idServiciosAdicionales=$_POST["consultaReservaAdicionales"];
$idServicioSalidas=$_POST["idServicioSalidas"];
  $reservados=	 getServiciosAdicionalesReservados($idServiciosAdicionales, $idServicioSalidas);
  echo json_encode($reservados);
 



		 	
  }

}










    ?>