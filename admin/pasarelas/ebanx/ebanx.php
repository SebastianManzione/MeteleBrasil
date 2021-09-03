<?php


function url_ebanx($codigoAmigable, $currencyEbanx, $countryEbanx, $totalEbanx){

include($_SERVER['DOCUMENT_ROOT']."/admin/pasarelas/ebanx/configEbanx.php");
$rand= mt_rand(99999,mt_getrandmax())/mt_getrandmax();
   $ch = curl_init();
$post = [
    'integration_key' => $integration_key,
    'payment_type_code' => '_all',    
   "country"=> $countryEbanx,
    'merchant_payment_code' => $rand,
    'currency_code'   => $currencyEbanx,
    'amount'=> $totalEbanx,
    'user_value_1'=>$codigoAmigable

];
curl_setopt($ch, CURLOPT_URL, $url_ebanx);


curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$server_output = curl_exec($ch);
$resultado= json_decode($server_output, true);

curl_close ($ch);




if (isset($resultado['redirect_url'])) {
    return ($resultado['redirect_url']);
}
else{
    return (-5);
}

}


?>