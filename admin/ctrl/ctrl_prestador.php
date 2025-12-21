<?php 
include("../classes/prestador.php");

session_start();

if ($_SERVER["REQUEST_METHOD"]=="POST") {


  if (isset($_POST["borraPrestador"])&&is_numeric($_POST["borraPrestador"])) {
			$idPrestador=$_POST["borraPrestador"];
			$resultado=borraPrestador($idPrestador);
			echo $resultado;  
  }

  if (isset($_POST["getPrestadores"])) {
	$prestadores=getPrestadores();

	echo json_encode($prestadores);
  }




}	




    ?>