<?php


function url_ebanx($codigoAmigable, $total_reales){

include($_SERVER['DOCUMENT_ROOT']."/admin/pasarelas/ebanx/configEbanx.php");



/*


$ch = curl_init();
$post = [
    'integration_key' => $integration_key,
 'hash' => '610864c37f16cf3320c2b8bb3203bbd3e7db0a35501c14d2',
 "description"=> "Order did not arrive",
"merchant_refund_code"=> 787653
];
curl_setopt($ch, CURLOPT_URL,"https://sandbox.ebanxpay.com/ws/refundOrCancel");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$server_output = curl_exec($ch);
print_r($server_output);
curl_close ($ch);
*/


//echo "**************************************************************<br>**********************************************************";





$ch = curl_init();
$post = [
    'integration_key' => $integration_key,
    'payment_type_code' => '_all',    
    'merchant_payment_code' => $codigoAmigable,
    'currency_code'   => 'BRL',
    'amount'=> $total_reales
];
curl_setopt($ch, CURLOPT_URL,"https://sandbox.ebanxpay.com/ws/request");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$server_output = curl_exec($ch);
//print_r($server_output);
curl_close ($ch);
$resultado= json_decode($server_output, true);
return ($resultado['redirect_url']);

}


?>