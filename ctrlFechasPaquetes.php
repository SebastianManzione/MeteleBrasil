
<?php 

include('sistema/functions.php');

  if(isset($_POST["data"])){
    

     $id=($_POST["data"]["id"]);
    
  $datos=json_decode($_POST["data"]["datos"],true);  
  $id=json_decode($_POST["data"]["id"],true); 
   $moneda=json_decode($_POST["data"]["money"],true); 


//print_r( $datos['date']); 
$fechaSalida=$datos['date'];
$date = new DateTime($datos['date']);
$date=$date->format('Y-m-d');
//echo "id:". $id ." date=".$date;


include($GLOBALS['path'].'/conectar.php');

	 $query=mysqli_query($conection,"SELECT * FROM horarios_paquetes_salidas hs

     
     WHERE (  hs.idServicio='$id' )");

    $result=mysqli_num_rows($query);
    $id; //DECLARO EL ID DE SERVICIO ACA PARA PASARLO A JS y usarlo en imp y desc de functions
       $datos=Array();
       $contador=0;
    if ($result > 0) {
      while ($data = mysqli_fetch_array($query)) {
     
      $idHorarioPaquete=$data['idHorarioPaqueteSalida'];
      $horaSalida=$data['horaSalida'];
      $direccion=$data['direccion'];
      $fechaRegreso=$data['fechaRegreso'];  //USO DE IMPUESTOS
   

	$datos[$contador]=array();
   	$datos[$contador][0]= $idHorarioPaquete;//0

	$datos[$contador][1]= 270;//1 
	$datos[$contador][2]= $horaSalida;//1 
	$datos[$contador][3]= $direccion;//1 
	$datos[$contador][4]= $fechaRegreso;//1 
   $date = new DateTime($fechaSalida);
  $datos[$contador][5]= $date->format('d-m-Y');
 


  $datos[$contador][6]= $id;//1 
        $contador++;

        }
    }

    $datos=json_encode($datos);
    echo $datos;
mysqli_close($conection);


exit();
    
}



?>