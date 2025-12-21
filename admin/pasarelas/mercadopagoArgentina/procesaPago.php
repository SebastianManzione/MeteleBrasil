<?php

// SDK de Mercado Pago



include("config.php");



require __DIR__ .  '/vendor/autoload.php';



// Agrega credenciales

MercadoPago\SDK::setAccessToken($accessTokenML);



// Crea un objeto de preferencia

$preference = new MercadoPago\Preference(); 

// Crea un ítem en la preferencia

$item = new MercadoPago\Item();

$item->title = "Reserva ".$codigoAmigable." en meteleargentina.com";

$item->quantity = 1;

$item->unit_price = $totalMercadopagoArgentina;

$preference->external_reference =$idReserva;



$bkurls=$parametros[0]["site"]."/consultaReserva?reserva=".$codigoAmigable;

$preference->back_urls = array(

    "success" => $bkurls,

    "failure" => $bkurls,

    "pending" => $bkurls

);

$preference->auto_return = "approved";



$preference->items = array($item);



$preference->save();



?>