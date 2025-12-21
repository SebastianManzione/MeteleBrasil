<?php 
include('sistema/functions.php');
  if(isset($_POST["data"])){



  	 $datos= json_decode($_POST['data']['datos'],true);

 $contacto=($datos[7]);

  if (isset($datos[6])) {

  $adicionales= json_decode($datos[6]);
  $cantAdc=count( $adicionales);
 


  }



InsertaReserva($datos[0], $datos[1], $datos[2], $datos[3], $datos[4],$datos[5], $adicionales, $contacto, $datos[8]);
//$horarioId=$datos["horarioId"];
   //  CuponValido($datos);
    
}
 ?>