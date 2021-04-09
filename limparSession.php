<?php
 session_start();
 if(isset($_POST['excluir']) &&  $_POST['excluir'] == 'carrinho'){
  if($_POST['tipo'] == 'actividades'){
   if(isset($_SESSION["carrito"]["actividades"]) && sizeof($_SESSION["carrito"]["actividades"]) > 0){
	 $id = $_POST['id'];  
	 unset($_SESSION["carrito"]["actividades"][$id]);  
	 unset($_SESSION["valorcarrinho"]["actividades"][$id]);
	 
	 if (count($_SESSION["carrito"]["actividades"])==0 &&count($_SESSION["carrito"]["paquetes"])==0) {
	  $str = array(
	   'sucesso' => true,
	   'vazio'   => 0
	  );
	  echo ( json_encode($str) );
	  exit;
	 } else {
	  $totalqtd = count($_SESSION["carrito"]["actividades"]);	 
	  $txtactividade = $totalqtd > 1 ? '' . $totalqtd . ' Actividades' : '' . $totalqtd . ' Actividade';	 
	  $str = array(
	   'sucesso'     => true,
	   'vazio'       => 1,
	   'actividade' => $txtactividade
	  );
	  echo ( json_encode($str) );
	  exit;	 
	 }
   }
  }  
  
  if($_POST['tipo'] == 'paquetes'){
   if(isset($_SESSION["carrito"]["paquetes"]) && sizeof($_SESSION["carrito"]["paquetes"]) > 0){
	 $id = $_POST['id'];  
	 unset($_SESSION["carrito"]["paquetes"][$id]);  
	 unset($_SESSION["valorcarrinho"]["paquetes"][$id]);
	 
	 if (count($_SESSION["carrito"]["actividades"])==0 &&count($_SESSION["carrito"]["paquetes"])==0) {
	  $str = array(
	   'sucesso' => true,
	   'vazio'   => 0
	  );
	  echo ( json_encode($str) );
	  exit;
	 } else {
	  $totalqtd = count($_SESSION["carrito"]["paquetes"]);	 
	  $txtpaquete = $totalqtd > 1 ? '' . $totalqtd . ' Paquetes' : '' . $totalqtd . ' Paquete';	 
	  $str = array(
	   'sucesso' => true,
	   'vazio'   => 1,
	   'paquete' => $txtpaquete
	  );
	  echo ( json_encode($str) );
	  exit; 
	 }
   }
  }  
  
 }
?>