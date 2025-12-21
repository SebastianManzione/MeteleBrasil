<?php 

session_start();

include("../classes/servicio.php");

include("../classes/tarifas.php");

include("../classes/idiomas.php");

include("../classes/edades.php");

	include("../classes/comisiones.php");

include("../classes/cancelaciones.php");

include("../classes/convierte_monedas.php");

include_once("../classes/tipos_tarifa.php");

include_once("../classes/servicio.php");



if ($_SERVER["REQUEST_METHOD"]=="POST") {



  if (isset($_POST["idServicio"])) {
$idServicio=$_POST['idServicio'];
$servicio=getServicio($idServicio);

			if (!isset($_SESSION["reserva"])) {

				$servicio=array();

			}


	

	echo json_encode($servicio[0]);

		 exit();

		 	

  }









}	










    ?>