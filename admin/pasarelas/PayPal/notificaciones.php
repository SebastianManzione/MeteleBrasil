<?php
include("../../classes/functions.php");
include("../../classes/comprobantes.php");
include("../../classes/reservaEmail.php");
include("../../classes/reserva.php");
include("../../classes/moneda.php");
include("../../classes/usuario.php");
include("../../classes/convierte_monedas.php");
// Reply with an empty 200 response to indicate to paypal the IPN was received correctly


header("HTTP/1.1 200 OK");

$request_body = file_get_contents('php://input');



$bodyArray= json_decode($request_body, true);




$event_type=$bodyArray["event_type"];
$id=$bodyArray["id"];
$fp = fopen('archivo.txt', 'a+');
fwrite($fp, "comienza   *********************/n/n".PHP_EOL);
fwrite($fp, json_encode($bodyArray["resource"]));
fwrite($fp, "termina*********************/n/n".PHP_EOL);
$custom_id=$bodyArray["resource"]["purchase_units"][0]["custom_id"];
$reference_id=$bodyArray["resource"]["purchase_units"][0]["reference_id"];
$summary=$bodyArray["summary"];

$total_dolares=$bodyArray["resource"]["purchase_units"][0]["amount"]["value"];

if ( $event_type=="CHECKOUT.ORDER.APPROVED") {
f
//write($fp,"Resultado del pago".InsertaPago($custom_id,$value,4, 188,$id));



$comprobante=insertaComprobante($reference_id, $total_dolares, 4, 188, $id, $total_dolares);

}
//$contacto=DevuelveContacto($custom_id);
//$mail=$contacto[3];

/*enviaMail($mail,"Recibimos tu pago correctamente!","   ");

*/



?>