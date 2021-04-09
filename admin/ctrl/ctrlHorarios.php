<?php 
session_start();
include("../classes/salidas.php");
include("../classes/tarifas.php");
include("../classes/idiomas.php");
include("../classes/edades.php");
	include("../classes/comisiones.php");
include("../classes/cancelaciones.php");
include("../classes/convierte_monedas.php");



if ($_SERVER["REQUEST_METHOD"]=="POST") {

  if (isset($_POST["reserva"])) {

			if (!isset($_SESSION["reserva"])) {
				$_SESSION["reserva"]=array();
			}
			//print_r($_POST["reserva"]);
			array_push($_SESSION["reserva"], array($_POST["reserva"],$_POST["reservaAdicionales"] ));
		//print_r($_SESSION["reserva"]);	unset($_SESSION["reserva"]);
	echo json_encode($_SESSION["reserva"]);
		 exit();
		 	
  }




}	

if ($_SERVER["REQUEST_METHOD"]=="POST") {
  if (isset($_POST["fecha"])&&isset($_POST["idServicio"])&&is_numeric($_POST["idServicio"])) {
		$idServicio=$_POST["idServicio"];
			$fecha=$_POST["fecha"];

			$salidas=getSalidasFechaIdServicio($fecha,$idServicio);
	echo json_encode($salidas);
		 exit();
		 	
  }





}	

if ($_SERVER["REQUEST_METHOD"]=="POST") {
  if (isset($_POST["idSalida"])&&is_numeric($_POST["idSalida"])) {
			$idSalida=$_POST["idSalida"];
			$tarifas= getTarifas($idSalida);

			$salida=getSalida($tarifas[0]['idServicioSalidas']);
				//print_r($salida);
			include("../classes/servicios_adicionales.php");
			
			
	
		
			$retorno=array();
			for ($i=0; $i < count($tarifas); $i++) { 
				$idServicioSalidasTarifas=$tarifas[$i]['idServicioSalidasTarifas'];
	
			$comisionVendedor=getComisionIdServicioSalidasTarifas($idServicioSalidasTarifas,1);
$comisionSistema=getComisionIdServicioSalidasTarifas($idServicioSalidasTarifas,2);


			$retorno[$i]['idServicioSalidasTarifas']=$idServicioSalidasTarifas;
			$retorno[$i]['idServicioSalidas']=$tarifas[$i]['idServicioSalidas'];
			$retorno[$i]['idSalida']=$idSalida;
				$retorno[$i]['nombre']=$tarifas[$i]['nombre'];
			$retorno[$i]['edadFrom']=getEdad($tarifas[$i]['idFromEdad'])[0]["valor"];
			$retorno[$i]['edadTo']=getEdad($tarifas[$i]['idToEdad'])[0]["valor"];
			$retorno[$i]['idTipoTarifa']=$tarifas[$i]['idTipoTarifa'];
			$precio=$tarifas[$i]['valor'];
			$precio=convierteMoneda( $salida[0]["idMoneda"],$_SESSION['moneda_sel'],$precio);
			$retorno[$i]["disponibilidad"]=$salida[0]["disponibilidad"];
			$retorno[$i]['comisionVendedor']=$precio*$comisionVendedor; //retornamos la suma de comisiones
			$retorno[$i]['comisionSistema']=$precio*$comisionSistema; //retornamos la suma de comisiones
			
			//$precio=$tarifas[$i]['valor']*$comision;
			
			$precio=$precio*($_SESSION["impuestos_pais"]+1);
			$precio=round($precio,2, PHP_ROUND_HALF_UP);
			$retorno[$i]['valor']=$_SESSION['moneda_sel_sym']."".$precio;
			$retorno[$i]['minimo']=$tarifas[$i]['minimo'];
			$retorno[$i]['cancelaciones']=getTipoCancelaciones($tarifas[$i]['idCancelaciones'])[0];
			$retorno[$i]['idiomas']=getIdiomaSalida($retorno[$i]['idServicioSalidas']);
		
			}
			$retorno[0]['adicionalesIncluidos']=getServiciosAdicionalesSalidaIncluidos($idSalida);
			$retorno[0]['adicionalesNoIncluidos']=getServiciosAdicionalesSalidaNoIncluidos($idSalida);
			
	echo json_encode($retorno);
		 exit();
		 	
  }

  if (isset($_POST["idServicioSalidasTarifas"])&&is_numeric($_POST["idServicioSalidasTarifas"])) {

		echo json_encode(calculaTarifa($_POST["idServicioSalidasTarifas"],
$_POST["cantidad"]));
		 	
  }



  if (isset($_POST["idServicioSalidasAdicionales"])&&is_numeric($_POST["idServicioSalidasAdicionales"])) {

  		
  	include("../classes/servicios_adicionales.php");
			$idServicioSalidasAdicionales=$_POST["idServicioSalidasAdicionales"];
			$cantidad=$_POST["cantidad"];
		$adicional=getValorServiciosAdicionalesSalida($idServicioSalidasAdicionales,$cantidad);
	//	print_r($adicional);
	
		
				
	echo json_encode($adicional);
		 exit();
		 	
  }





}	



    ?>