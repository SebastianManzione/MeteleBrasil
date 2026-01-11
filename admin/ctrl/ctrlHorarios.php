<?php 



session_start();
include("../classes/salidas.php");
include("../classes/tarifas.php");
include("../classes/idiomas.php");
include("../classes/edades.php");
include("../classes/comisiones.php");
include("../classes/cancelaciones.php");
include("../classes/convierte_monedas.php");
include_once("../classes/tipos_tarifa.php");
include_once("../classes/servicio.php");


if ($_SERVER["REQUEST_METHOD"]=="POST") {

  if (isset($_POST["reserva"])) {
			if (!isset($_SESSION["reserva"])) {
				$_SESSION["reserva"]=array();
			}

			// Limpiar descuento AR$ cuando se modifica el carrito
			// para que se recalcule con el nuevo total
			unset($_SESSION['descuento_ars_aceptado']);
			unset($_SESSION['descuento_ars_monto']);

			// Guardar código de cupón si se envió
			if (isset($_POST["codCupon"]) && !empty($_POST["codCupon"])) {
				$_SESSION['codCupon'] = $_POST["codCupon"];
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

            // Agregar categoría de servicio a cada salida para que JavaScript pueda formatear duración
            if (count($salidas) > 0) {
                $servicio = getServicio($idServicio);
                $idCategoria = $servicio[0]["idCategoria_servicio"];
                for ($i = 0; $i < count($salidas); $i++) {
                    $salidas[$i]["idCategoria_servicio"] = $idCategoria;
                }
            }

	echo json_encode($salidas);



		 exit();



		 	



  }























}	







if ($_SERVER["REQUEST_METHOD"]=="POST") {



  if (isset($_POST["idSalida"])&&is_numeric($_POST["idSalida"])) {



			$idSalida=$_POST["idSalida"];



			$tarifas= getTarifas($idSalida);







			$salida=getSalida($tarifas[0]['idServicioSalidas']);



			



				$servicio= getServicio($salida[0]["idServicio"]);



					$idCategoria_servicio=($servicio[0]["idCategoria_servicio"]);



			include("../classes/servicios_adicionales.php");



			



			



	



		



			$retorno=array();



			for ($i=0; $i < count($tarifas); $i++) { 



				$idServicioSalidasTarifas=$tarifas[$i]['idServicioSalidasTarifas'];



	



			$comisionVendedor=getComisionIdServicioSalidasTarifas($idServicioSalidasTarifas,1);



$comisionSistema=getComisionIdServicioSalidasTarifas($idServicioSalidasTarifas,2);



$idTipoTarifa=$tarifas[$i]['idTipoTarifa'];



 $tipoTarifaNombre=getTipoTarifa($idTipoTarifa)[0]["nombre"];



			$retorno[$i]['idServicioSalidasTarifas']=$idServicioSalidasTarifas;



			$retorno[$i]['idServicioSalidas']=$tarifas[$i]['idServicioSalidas'];



			$retorno[$i]['idSalida']=$idSalida;



			$retorno[$i]['idCategoria_servicio']=$idCategoria_servicio;



			$retorno[$i]['menor']=isset($tarifas[$i]['menor']) ? (int)$tarifas[$i]['menor'] : 0;


				$retorno[$i]['nombre']=$tarifas[$i]['nombre'];



			$retorno[$i]['edadFrom']=getEdad($tarifas[$i]['idFromEdad'])[0]["valor"];



			$retorno[$i]['edadTo']=getEdad($tarifas[$i]['idToEdad'])[0]["valor"];



			$retorno[$i]['idTipoTarifa']=$idTipoTarifa;



			$retorno[$i]['tipoTarifaNombre']=$tipoTarifaNombre;







			$precio=$tarifas[$i]['valor'];



			$precio=convierteMoneda( $salida[0]["idMoneda"],$_SESSION['moneda_sel'],$precio);



			$retorno[$i]["disponibilidad"]=$salida[0]["disponibilidad"];



			$retorno[$i]['comisionVendedor']=$precio*$comisionVendedor; //retornamos la suma de comisiones



			$retorno[$i]['comisionSistema']=$precio*$comisionSistema; //retornamos la suma de comisiones



			



			//$precio=$tarifas[$i]['valor']*$comision;



			



			// Comentar impuesto $precio=$precio*($_SESSION["impuestos_pais"]+1);

			$precio=round($precio,2, PHP_ROUND_HALF_UP);

			// Redondeo especial para monedas devaluadas (ARS, CLP, PYG): al siguiente múltiplo de 1000 hacia arriba
			$valorOriginal = $precio;
			$redondeoDiferencia = 0;

			if (in_array($_SESSION['moneda_sel'], [270, 271, 225])) { // ARS, CLP, PYG
				// Redondear hacia arriba al siguiente múltiplo de 1000
				$redondeoDiferencia = ceil($valorOriginal / 1000) * 1000 - $valorOriginal;
				$precio = $valorOriginal + $redondeoDiferencia;
			}

			// Formatear con separadores de miles (coma como separador de miles)
			$precioFormateado = number_format($precio, 0,'', '.');



			$retorno[$i]['valor'] = $_SESSION['moneda_sel_sym'] . $precioFormateado;
			$retorno[$i]['valorFormateado'] = $precioFormateado;
			$retorno[$i]['valorNumerico'] = $precio; // Valor numérico sin formato para JavaScript
			$retorno[$i]['redondeoDiferencia'] = $redondeoDiferencia;



			$retorno[$i]['minimo']=$tarifas[$i]['minimo'];



			$cancelacionData = getTipoCancelaciones($tarifas[$i]['idCancelaciones']);
			$retorno[$i]['cancelaciones'] = !empty($cancelacionData) ? $cancelacionData : [];



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