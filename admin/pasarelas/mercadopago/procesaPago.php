<?php
// SDK de Mercado Pago
//$accessTokenML="TEST-199473761358972-101620-70a7e8bfecaf9ae72e86f2b908c1f0d8-342426513"; //MODO SANDBOX
$accessTokenML="APP_USR-199473761358972-101620-cae572d51b80f9e4152070592e0fcf84-342426513";//MODO PRODUCCION

require __DIR__ .  '/vendor/autoload.php';

// Agrega credenciales
MercadoPago\SDK::setAccessToken($accessTokenML);

// Crea un objeto de preferencia
$preference = new MercadoPago\Preference(); 
// Crea un ítem en la preferencia
$item = new MercadoPago\Item();
$item->title = "Reserva ".$codigoAmigable." en metelebrasil.com";
$item->quantity = 1;
$item->unit_price = $totalMercadopagoArgentina;
$preference->external_reference =$codigoAmigable;

$bkurls=$parametros[0]["site"]."consultaReserva.php?reserva=".$codigoAmigable;
$preference->back_urls = array(
    "success" => $bkurls,
    "failure" => $bkurls,
    "pending" => $bkurls
);
$preference->auto_return = "approved";

$preference->items = array($item);

$preference->save();

?>