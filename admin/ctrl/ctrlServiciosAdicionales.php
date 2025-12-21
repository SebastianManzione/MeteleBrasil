<?php 



if ($_SERVER["REQUEST_METHOD"]=="POST") {
require("../classes/servicios_adicionales.php");

  if (isset($_POST["borraAdicionalSalida"])) {
$idServiciosAdicionales=$_POST["borraAdicionalSalida"];
$idServicioSalidas=$_POST["idServicioSalidas"];
  $reservados=	 getServiciosAdicionalesReservados($idServiciosAdicionales, $idServicioSalidas);
 
 if (count($reservados)==0) {
  borraServicioAdicionalSalida($idServiciosAdicionales, $idServicioSalidas);

echo (-5); 
 }
 else{
$retorno=array();
  for ($i=0; $i < count($reservados); $i++) { 
    array_push($retorno, $reservados[$i]["codigoAmigable"]);
   
  }
echo json_encode($retorno);
 }



		 	
  }

}










    ?>