
<?php 

include('sistema/functions.php');
 
include($GLOBALS['path'].'/conectar.php');

 $datos=json_decode($_POST["data"]["data"],true);
 $contador=0;
$hoteles=Array();
 $id=$datos[6];
$query2=mysqli_query($conection,"SELECT * FROM hoteles  WHERE idServicio=".$id);
				$result2=mysqli_num_rows($query2);
				
	if ($result2 > 0) {
			while ($data2 = mysqli_fetch_array($query2)) {
			$idHotel= $data2['idHotel'];
			$nombre= $data2['nombre'];
			$hoteles[$contador]=Array();
				array_push($hoteles[$contador] ,
				 $idHotel,//0
				 $nombre

				);
			 $contador++;
		}
	}    

	$hoteles=json_encode($hoteles);
    echo $hoteles;
	mysqli_close($conection);
  if(isset($_POST["data"])){

  //$datos=   json_decode($_POST["data"]["datos"]);

    // CuponValido($datos);
    
}



?>