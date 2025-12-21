<?php 



if ($_SERVER["REQUEST_METHOD"]=="POST") {

session_start();
include("../classes/moneda.php");

  if (isset($_POST["cambiaMoneda"])) {
  	$monedaCambio=$_POST["cambiaMoneda"];
  	 $moneda=getMoneda($monedaCambio);
  	
if (is_array($moneda)) {
$_SESSION["moneda_sel"]=$moneda[0]["idMoneda"];
$_SESSION["moneda_sel_sym"]=$moneda[0]["Symbol"];
	
	echo 1;
}


		 	
  }




}	








    ?>