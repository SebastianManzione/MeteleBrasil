<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if($_SERVER['REQUEST_METHOD'] == 'POST'){

	

  include("includes/headPagos.php");

include("admin/classes/functions.php");

include("admin/classes/comprobantes.php");



include("admin/classes/reserva.php");

include("admin/classes/moneda.php");

include("admin/classes/usuario.php");
include("admin/classes/convierte_monedas.php");



$site=($parametros[0]["site"]);

  if($_SESSION["login"]["idCobrador"]>0){

$idMoneda=$_POST["moneda"];

$idReserva=$_POST["idReserva"];

$idUsuario=$_SESSION["login"]["idUsuario"];

$total=$_POST["dinero"];
$total_dolares= ConvierteMoneda($idMoneda,188, $total);
$comprobante=insertaComprobante($idReserva, $total, 6, $idMoneda, $idUsuario, $total_dolares);


if ($comprobante>0) {

  # code...



 $reserva=getReservaId($idReserva);

$moneda=getMoneda($idMoneda);

$symbolo=$moneda[0]["Symbol"];

$codigoAmigable=($reserva[0]["codigoAmigable"]);

$usuario=getUsuario($idUsuario);

$nombre_cobrador=($usuario[0]["usuario"]);

include("admin/classes/email_pago_recibido.php");
include("admin/classes/reservaEmail.php");
$cuerpo=getCuerpoEmailPagoRecibido($codigoAmigable, $nombre_cobrador);



$resumail=enviaMail($reserva[0]["emailResponsable"], "Recibimos Su pago correctamente", $cuerpo, $parametros[0]["site"]);



  alertar("Cobro Realizado con exito","success");

}}

else

{

  alertar("No es usted un usuario que puede cobrar","warning");

}






//echo ("<script>location.href='consultaReserva.php?reserva=".$codigoAmigable."'</script>");



}

?>