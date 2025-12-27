<?php





if($_SERVER['REQUEST_METHOD'] == 'POST'){





  include("includes/headPagos.php");



include("admin/classes/comprobantes.php");

include("admin/classes/reserva.php");

//include("admin/classes/reservaEmail.php");

require_once("admin/classes/moneda.php");

include("admin/classes/usuario.php");

include("admin/classes/convierte_monedas.php");

include("admin/classes/generador_aleatorio.php");





$site=($parametros[0]["site"]);



  if($_SESSION["login"]["idCobrador"]>0){



$idMoneda=$_POST["moneda"];



$idReserva=$_POST["idReserva"];



$idUsuario=$_SESSION["login"]["idUsuario"];



$total=$_POST["dinero"];

$total_dolares= ConvierteMoneda($idMoneda,188, $total);

do {

 $aleatorio= GeneradorAleatorio(3,6);

$comprobante=insertaComprobante($idReserva, $total, 6, $idMoneda, $aleatorio, $total_dolares, $idUsuario);  // code...

} while ($comprobante==0);







if ($comprobante>0) {



  # code...







 $reserva=getReservaId($idReserva);



$moneda=getMoneda($idMoneda);



$symbolo=$moneda[0]["Symbol"];



$codigoAmigable=($reserva[0]["codigoAmigable"]);



$usuario=getUsuario($idUsuario);



$nombre_cobrador=($usuario[0]["usuario"]);



include("admin/classes/email_pago_recibido.php");



$cuerpo=getCuerpoEmailPagoRecibido($codigoAmigable, $nombre_cobrador);







$resumail=enviaMail($reserva[0]["emailResponsable"], $lang["si_recibimos_su_pago_correctamente"], $cuerpo, $parametros[0]["site"]);







  alertar($lang["si_tu_cobro_se_realizo"],"success");

  redireccionarLento('consultaReserva?reserva='.$codigoAmigable);

  exit();



}}



else



{



  alertar($lang["ups_infelizmente_su_usuario"],"warning");



}













//echo ("<script>location.href='consultaReserva.php?reserva=".$codigoAmigable."'</script>");







}



?>