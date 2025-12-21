<?php

// SDK de Mercado Pago
echo "test mlbr";


include("config.php");







// Agrega credenciales

MercadoPago\SDK::setAccessToken($accessTokenMLTest);



// Crea un objeto de preferencia

$preferenceBr = new MercadoPago\Preference(); 

// Crea un ítem en la preferencia

$item = new MercadoPago\Item();

$item->title = "Reserva ".$codigoAmigable." en meteleargentina.com";

$item->quantity = 1;

$item->unit_price = $totalMercadopagoBrasil;

$preferenceBr->external_reference =$idReserva;



$bkurls=$parametros[0]["site"]."/consultaReserva?reserva=".$codigoAmigable;

$preferenceBr->back_urls = array(

    "success" => $bkurls,

    "failure" => $bkurls,

    "pending" => $bkurls

);

$preferenceBr->auto_return = "approved";



$preferenceBr->items = array($item);



$preferenceBr->save();



?>