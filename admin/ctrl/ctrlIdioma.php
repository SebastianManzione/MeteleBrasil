<?php 



if ($_SERVER["REQUEST_METHOD"]=="POST") {

session_start();
include("../classes/moneda.php");

  if (isset($_POST["cambiaIdioma"])) {

  	$idiomaNuevo=$_POST["cambiaIdioma"];

$_SESSION["idioma"]=$_POST["cambiaIdioma"];
$_SESSION["idioma_manual"] = 1;
switch ($idiomaNuevo) {
		case 'ES':
		$_SESSION["idioma_bandera"]='img/countries/Spain-icon.png';
		break;
			case 'EN':
		$_SESSION["idioma_bandera"]='img/countries/United-States-of-Americ-icon.png';
		break;
		case 'IT':
		$_SESSION["idioma_bandera"]='img/countries/italy-icon.png';
		break;
		
			case 'PT':
		$_SESSION["idioma_bandera"]='img/countries/Brazil-icon.png';
		break;
			case 'FR':
		$_SESSION["idioma_bandera"]='img/countries/France-icon.png';
		break;

}
	
echo 1;

		 	
  }




}	




    ?>