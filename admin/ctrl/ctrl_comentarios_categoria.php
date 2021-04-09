<?php 
include("../classes/opiniones_categoria.php");

session_start();

if ($_SERVER["REQUEST_METHOD"]=="POST") {


  if (isset($_POST["borraComentarioCategoria"])&&is_numeric($_POST["borraComentarioCategoria"])) {
			$idComentario=$_POST["borraComentarioCategoria"];
			$resultado=borraOpinionCategoria($idComentario);
			echo $resultado;  
  }






}	




    ?>