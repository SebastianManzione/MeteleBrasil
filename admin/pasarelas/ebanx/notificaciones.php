<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("configEbanx.php");
include("../../classes/functions.php");
include("../../classes/reserva.php");
include("../../classes/comprobantes.php");
include("../../classes/convierte_monedas.php");

 http_response_code(200);


header('Access-Control-Allow-Origin: *');


  $bodyy = file_get_contents('php://input');
    $data = array();
if (strlen($bodyy)>10) {
echo "ok";
  parse_str($bodyy, $data);


}
else{

 $data["hash_codes"]=$_GET["hash"];


}
$post = [
    'integration_key' => $integration_key,
    'hash' => $data["hash_codes"]
];
 $ch = curl_init();
 curl_setopt($ch, CURLOPT_URL,"https://sandbox.ebanxpay.com/ws/query");
//curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$server_output = curl_exec($ch);

$server_output=json_decode($server_output, true);

$total=$server_output["payment"]["amount_ext"];

$status=$server_output["payment"]["status"];

$merchant_payment_code=$server_output["payment"]["merchant_payment_code"];

$reserva=getReserva($merchant_payment_code);
$idReserva=$reserva[0]["idReserva"];

$total_dolares=ConvierteMoneda(283,188, $total);
$insert=insertaComprobante($idReserva, $total, 5, 283, $data["hash_codes"], $total_dolares, 1);
if (strlen($bodyy)<1) {
redireccionar("../../../consultaReserva?reserva=".$merchant_payment_code);
}
 ?>
