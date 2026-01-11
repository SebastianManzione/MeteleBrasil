



<?php 



include("../classes/reserva.php");

include("../classes/comprobantes.php");

include("../classes/convierte_monedas.php");

if ($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST['data'])) {



$codigoAmigable=json_decode($_POST['data']['codigoAmigable']);
$idMonedaSel=$_POST['data']['idMonedaSel'];

$reserva=getReserva($codigoAmigable);	

session_start();

if (count($reserva)==1) {

	$reserva=$reserva[0];

	$idReserva=$reserva["idReserva"];

	$nombreResponsable=$reserva["nombreResponsable"]." ".$reserva["apellidoResponsable"];

	$comprobantes= getComprobantesIdReservaDolar($idReserva);

	$total_dolares=$reserva["total_dolares"];

	

	$aPagar=$total_dolares-$comprobantes;

	$aPagar=convierteMoneda(188, $idMonedaSel, $aPagar);

	$aPagar=round($aPagar,2);

	$html='';

	if ($aPagar>0) {

		$html=' <ul >Total devido '.$_SESSION['moneda_sel_sym'].'<input type="text" name="total_cobrado" size="20" id="' .$aPagar.'"; value="'.$aPagar.'"/></ul>';

		$html=$html.'<ul>Responsable: '.$nombreResponsable.'</ul>';
			$html=$html.'<input type="hidden" name="idReserva" value="'.$idReserva.'">';

	}
	else{
		$html='<div class="alert alert-success p-4">';
		$html.='<h5 class="mb-3"><i class="fa fa-check-circle"></i> Reserva Confirmada!!!</h5>';
		$html.='<p class="mb-3">El pago ha sido procesado correctamente.</p>';
		$html.='<div class="btn-group btn-group-sm" role="group">';
		$html.='<a href="../../consultaReserva.php?reserva='.$codigoAmigable.'" class="btn btn-info" target="_blank"><i class="fa fa-eye"></i> Ver Reserva como Cliente</a>';
		$html.='<form id="formVerDetalles" method="POST" action="../../admin/carritoDetalles.php" style="display:inline;">';
		$html.='<input type="hidden" name="detallesCarrito" value="'.$idReserva.'">';
		$html.='<button type="submit" class="btn btn-primary"><i class="fa fa-list"></i> Ver Detalles</button>';
		$html.='</form>';
		$html.='</div>';
		$html.='</div>';
	}

	



	$retorno=["aPagar"=>$aPagar, "html"=>$html, "reserva"=>$reserva];

	echo json_encode($retorno);



}



exit();











	$codigoAmigable=$_POST["data"]["cupon"];



	$codigoAmigable=json_decode(($codigoAmigable));



	$cupon=CuponValido($codigoAmigable);



	



if (count($cupon)==1) {



	



$idUsuario=$cupon[0]["idUsuario"];



$_SESSION["cupon_descuento"]["descuentoPorcentual"]=$cupon[0]["descuentoPorcentual"];



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