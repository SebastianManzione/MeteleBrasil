
<?php 
include("../classes/cupones_descuento.php");
include("../classes/usuario.php");
if ($_SERVER["REQUEST_METHOD"]=="POST") {
session_start();
	$codigoAmigable=$_POST["data"]["cupon"];
	$codigoAmigable=json_decode(($codigoAmigable));
	$cupon=CuponValido($codigoAmigable);
	
if (count($cupon)==1) {
	
$idUsuario=$cupon[0]["idUsuario"];
$_SESSION["cupon_descuento"]["idUsuario"]=$cupon[0]["idUsuario"];
$_SESSION["cupon_descuento"]["descuentoPorcentual"]=$cupon[0]["descuentoPorcentual"];
$_SESSION["cupon_descuento"]["idCuponDescuento"]=$cupon[0]["idCuponDescuento"];
$_SESSION["cupon_descuento"]["CodigoAmigable"]=$cupon[0]["CodigoAmigable"];
$_SESSION["cupon_descuento"]["anfitrion"]=getUsuario($idUsuario)[0]["usuario"];


	$cuponNuevo=["idCuponDescuento"=>$cupon[0]["idCuponDescuento"],
"CodigoAmigable"=>$cupon[0]["CodigoAmigable"],
"anfitrion"=>getUsuario($idUsuario)[0]["usuario"],
"descuentoPorcentual"=>$cupon[0]["descuentoPorcentual"],
"cupon_usado"=>$cupon[0]["cupon_usado"]



];


echo json_encode($cuponNuevo);
}
else{
	unset($_SESSION["cupon_descuento"]);
}
	
	exit();

}

?>