
<?php 

include('sistema/functions.php');
 
  if(isset($_POST["data"])){
     
     $id=($_POST["data"]["id"]);
    
  $datos=json_decode($_POST["data"]["datos"],true);  
  $id=json_decode($_POST["data"]["id"],true); 
   $moneda=json_decode($_POST["data"]["money"],true); 
   $impuestosPais=json_decode($_POST["data"]["impuestosPais"],true); 



$date = new DateTime($datos['date']);
$date=$date->format('Y-m-d');
//echo "id:". $id ." date=".$date;
echo devuelveHorarios($date, $id, $moneda,  $impuestosPais);
exit();
    
}



?>