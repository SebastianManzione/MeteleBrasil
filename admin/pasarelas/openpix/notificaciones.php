<?php 


include("../../classes/functions.php");
include("../../classes/comprobantes.php");
include("../../classes/reservaEmail.php");
include("../../classes/reserva.php");
include("../../classes/moneda.php");
include("../../classes/usuario.php");
include("../../classes/convierte_monedas.php");

header('Access-Control-Allow-Origin: *');
header('Content-type: application/json;  charset=UTF-8');

$bodyy = file_get_contents('php://input');

$resul=( json_decode($bodyy,true));

$value=$resul['charge']['value'];

if ($resul['charge']['status']=="COMPLETED") {
    $total=substr($value, 0,-2).".".substr($value, -2,2);
    $total=floatval($total);
    $correlationID=$resul['charge']['correlationID'];
        $identifier=$resul['charge']['identifier'];
    
 

$idReserva=getReserva($correlationID)[0]['idReserva'];

$total_dolares=  $total_dolares=ConvierteMoneda(283,188, $total);
$comprobante=insertaComprobante($idReserva, $total, 7, 283, $identifier, $total_dolares, 1);

}







 ?>