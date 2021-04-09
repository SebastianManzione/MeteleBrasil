
<?php 

include('sistema/functions.php');
 
  if(isset($_POST["data"])){

  $datos=   json_decode($_POST["data"]["datos"]);
     CuponValido($datos);
    
}



?>