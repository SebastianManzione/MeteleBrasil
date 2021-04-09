
<?php 

include('sistema/functions.php');
 
include($GLOBALS['path'].'/conectar.php');

 if(isset($_POST["data"]["data"])){
 	$datos=json_decode($_POST["data"]["data"],true);
 $contador=0;


$hoteles=Array();
 $id=$datos[0];
$query2=mysqli_query($conection,"SELECT * FROM hoteles_cuartos ht
INNER JOIN tipo_cuarto tc ON ht.idTipoCuarto=tc.idTipoCuarto
WHERE ht.idHotel=".$id);
				$result2=mysqli_num_rows($query2);
				
	if ($result2 > 0) {
			while ($data2 = mysqli_fetch_array($query2)) {
			$idHotel= $data2['idHotel'];
			$cuarto= $data2['nombreTipoCuarto'];
			$hoteles[$contador]=Array();
				array_push($hoteles[$contador] ,
				 $idHotel,//0
				 $cuarto,//1
				 $data2['idHotelCuarto']//2

				);
			 $contador++;
		}
	}    

	$hoteles=json_encode($hoteles);
    echo $hoteles;
	mysqli_close($conection);
  

  //$datos=   json_decode($_POST["data"]["datos"]);

    // CuponValido($datos);
    exit();
}
	
if(isset($_POST["data"]["seleccionaCuarto"])){

 	$datos=json_decode($_POST["data"]["seleccionaCuarto"],true);
	$money=json_decode($_POST["data"]["money"],true);
 $contador=0;
   $impuestosPais=json_decode($_POST["data"]["impuestosPais"],true); 

$hoteles=Array();
 $id=$datos[0];
$query2=mysqli_query($conection,"SELECT * FROM hoteles_cuartos ht
INNER JOIN tipo_cuarto tc ON ht.idTipoCuarto=tc.idTipoCuarto
INNER JOIN hoteles hot on ht.idHotel=hot.idHotel
INNER JOIN servicio sv on hot.idServicio=sv.idServicio
WHERE ht.idHotelCuarto=".$id);
				$result2=mysqli_num_rows($query2);






	if ($result2 > 0) {
			while ($data2 = mysqli_fetch_array($query2)) {
				$idServicio=$data2['idServicio'];
			$idHotel= $data2['idHotel'];
			$cuarto= $data2['nombreTipoCuarto'];
			$idMoneda=$data2['idMoneda'];


$impuestosServicio = DevuelveImpuestosServicio($idServicio);
$DevuelveComisionReservateServicio=DevuelveComisionReservateServicio($idServicio);
$comisionVentaServicio=DevuelveComisionVentaServicio($idServicio);
$ComisionCompensatoria=DevuelveComisionCompensatoria($idServicio);


$pAdulto=$data2['precioAdulto'];
$precioFinal=($pAdulto*$impuestosServicio)+$pAdulto;
$precioFinal=($pAdulto*$comisionVentaServicio)+$precioFinal;
$precioFinal=($pAdulto*$DevuelveComisionReservateServicio)+$precioFinal;
$precioFinal=($pAdulto*$ComisionCompensatoria)+$precioFinal;
$precioFinal=($precioFinal*$impuestosPais)+$precioFinal;

$pAdulto=round($precioFinal, 2, PHP_ROUND_HALF_EVEN);

			$hoteles[$contador]=Array();
				array_push($hoteles[$contador] ,
				 $idHotel,//0
				 $cuarto,//1
				 $data2['idHotelCuarto'],//2
				round(ConvierteMoneda( $idMoneda, $money,$pAdulto),2) ,//3
				 $data2['precioMenor'],//4
				 $data2['camaAdicional'],//5
				round(ConvierteMoneda( $idMoneda, $money, $data2['precioCamaAdicional']),2),//6
				round(ConvierteMoneda( $idMoneda, $money, $data2['precioCamaAdicionalMenor']),2),//7
				 $data2['cantCamasAdicionales'],//8
				  $data2['maxPersonas'],//9
				  $data2['idMoneda'],
				  $money
				);
			 $contador++;
		}
	}    

	$hoteles=json_encode($hoteles);
    echo $hoteles;
	mysqli_close($conection);
  

  //$datos=   json_decode($_POST["data"]["datos"]);

    // CuponValido($datos);
    
}

?>