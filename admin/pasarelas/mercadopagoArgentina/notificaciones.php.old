<?php 


include("config.php");
include("../../classes/reserva.php");
include("../../classes/comprobantes.php");
include("../../classes/convierte_monedas.php");

 http_response_code(200);


header('Access-Control-Allow-Origin: *');

header('Content-type: application/json;  charset=UTF-8');



  $bodyy = file_get_contents('php://input');

  $body = json_decode($bodyy, true);

$geet=$_GET;



$fp = fopen("./MPResu.csv", 'a+');

fputcsv($fp, $body,";");



 http_response_code(200);



    if(!is_null($geet["type"])){

   

     $topic=$geet["type"];





  if($topic=="payment"){   //aca consultamos el pago a la api de mercadopago pasandole

    // el token del collector



$id = $geet["data_id"];



$url = 'https://api.mercadopago.com/v1/payments/'.$id.'?access_token='.$accessTokenML;



    //  Iniciamos curl

    $curl = curl_init();

    // Desactivamos verificación SSL

    curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, 0 );

    // Devuelve respuesta aunque sea falsa

    curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );

    // Especificamo los MIME-Type que son aceptables para la respuesta.

    curl_setopt( $curl, CURLOPT_HTTPHEADER, [ 'Accept: application/json' ] );

    // Establecemos la URL

    curl_setopt( $curl, CURLOPT_URL, $url );

    // Ejecutmos curl

    $json = curl_exec( $curl );

    // Cerramos curl

    curl_close( $curl );

    $respuestas = json_decode( $json, true );



print_r($respuestas);



if (!isset($respuestas['message'])) {



//csv on



 switch ($respuestas['status']) {

      case 'rejected':

      $resu=2;

        

        break;

      

      default:

        $resu=1;

        break;

    }



$nro_venta=$respuestas['external_reference'];

$importe=$respuestas['transaction_details']['total_paid_amount'];

$fecha = (new DateTime)->format('d/m/y'); 

$hora= (new DateTime)->format('H:i:s'); 

$nombre=$respuestas['payer']['first_name'];

$apellido=$respuestas['payer']['last_name'];

$lista =array( $respuestas['external_reference'] ,$nombre,$apellido,$respuestas['payer']['identification']['number'],$id,$respuestas['transaction_amount']);


$idReserva=$respuestas['external_reference'];
$total=$respuestas['transaction_amount'];
$total_dolares=ConvierteMoneda(270,188, $total);


insertaComprobante($idReserva, $total, 1, 270, $id, $total_dolares);


exit();






}











}







      }











    

  

  

    

 ?>