<?php 
include('sistema/functions.php');
 
  if(isset($_POST["data"])){
    $datos= json_decode($_POST["data"]["origen"]);
print_r($_POST["data"]);
$total= ConvierteMoneda($datos[0], $datos[1], $datos[2]); //origen, destino, valor
echo json_encode($total);

exit();
    
}

 ?>