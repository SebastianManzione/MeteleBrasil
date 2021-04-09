<?php 
include("../classes/servicio_opiniones.php");

session_start();

if ($_SERVER["REQUEST_METHOD"]=="POST") {


  if (isset($_POST["borraComentarioServicio"])&&is_numeric($_POST["borraComentarioServicio"])) {
			$idComentario=$_POST["borraComentarioServicio"];
			$resultado=borraOpinionServicio($idComentario);
			echo $resultado;  
  }






}	




    ?>