<?php

$totalEbanx=ConvierteMoneda($monedaNativa,188, $totalAPagar);
$idOperacion=$idReserva;
$country="ar";
// abrimos la sesión cURL
$ch = curl_init();

// definimos la URL a la que hacemos la petición
curl_setopt($ch, CURLOPT_URL,"https://sandbox.ebanxpay.com/ws/request");
// indicamos el tipo de petición: POST
curl_setopt($ch, CURLOPT_POST, TRUE);
// definimos cada uno de los parámetros
curl_setopt($ch, CURLOPT_POSTFIELDS, "integration_key=test_ik_BSWornvqoXPL1wFfy89olQ&name=value2&email=adsads@asdasd.com&country=".$country."&payment_type_code=_all&merchant_payment_code=".$idOperacion."&currency_code=USD&amount=".$totalEbanx);
 

 
// recibimos la respuesta y la guardamos en una variable
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$remote_server_output = curl_exec ($ch);
 
// cerramos la sesión cURL
curl_close ($ch);
 
// hacemos lo que queramos con los datos recibidos
// por ejemplo, los mostramos
$remote_server_output=json_decode($remote_server_output,true);
$urlEbanxs=$remote_server_output["redirect_url"];

//echo ('<script> 
  // window.location.href="'.$urll.'"</script>');



 ?>