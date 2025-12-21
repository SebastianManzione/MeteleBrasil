<?php 

$produccion=true;
$url_ebanx='';
$integration_key='';
if ($produccion) {
   $url_ebanx='https://api.ebanxpay.com/ws/request';
   $url_ebanxQuery='https://api.ebanxpay.com/ws/query';
   $integration_key='live_ik_T_mCEpugxdKRnjsIaZrwUQ'; //ponto praia production
}
else{
    $url_ebanx='https://sandbox.ebanxpay.com/ws/request';
    $url_ebanxQuery='https://sandbox.ebanxpay.com/ws/query';
    $integration_key='test_ik_BSWornvqoXPL1wFfy89olQ';//ponto praia test
}








 ?>