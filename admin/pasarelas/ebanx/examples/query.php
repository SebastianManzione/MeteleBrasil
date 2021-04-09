<?php

require_once 'bootstrap.php';

$request = \Ebanx\Ebanx::doRequest(array(
    'currency_code'     => 'USD'
  , 'amount'            => $total
  , 'name'              => 'lucas nueto'
  , 'email'             => 'lucas@ebanx.com'
  , 'payment_type_code' => '_all'
  , 'merchant_payment_code' => time()
));
var_dump($request);
echo "<br><br>";
$hash="5da27f3e97da756cb37a62278f1a63e937bb9573ae4a36c8";
$response = \Ebanx\Ebanx::doQuery(array(
    'hash' => $hash
));
var_dump($response);
echo "<br>";
echo "<br>";




