<?php 



include("config.php");

include("../../classes/functions.php");

include("../../classes/comprobantes.php");

include("../../classes/reservaEmail.php");

include("../../classes/reserva.php");

include("../../classes/moneda.php");

include("../../classes/usuario.php");

include("../../classes/convierte_monedas.php");

include("../../classes/parametros.php");

header('Access-Control-Allow-Origin: *');

header('Content-type: application/json;  charset=UTF-8');







$parametros=getParametros();







  $bodyy = file_get_contents('php://input');

  $body = json_decode($bodyy, true);

$geet=$_GET;





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





if (!isset($respuestas['message'])) {



//csv on



 switch ($respuestas['status']) {

      case 'rejected':

      $resu=2;

        

        break;

         case 'approved':
         $resu=3;
         break;

      

      default:

        $resu=1;

        break;

    }


if ($resu==3) {


$nro_venta=$respuestas['external_reference'];

$importe=$respuestas['transaction_details']['total_paid_amount'];

$fecha = (new DateTime)->format('d/m/y'); 

$hora= (new DateTime)->format('H:i:s'); 

$nombre=$respuestas['payer']['first_name'];

$apellido=$respuestas['payer']['last_name'];

$lista =array( $respuestas['external_reference'] ,$nombre,$apellido,$respuestas['payer']['identification']['number'],$id,$respuestas['transaction_amount']);

$total_transaccion=$respuestas['transaction_amount'];

$total_dolares=  $total_dolares=ConvierteMoneda(270,188, $total_transaccion);

$comprobante=insertaComprobante($respuestas['external_reference'], $total_transaccion, 1, 270, $id, $total_dolares, 1);



$reserva=getReservaId($nro_venta);

$email=$reserva[0]["emailResponsable"]; 



$total_abonado="$".$total_transaccion;

$codigo_amigable=$reserva[0]["codigoAmigable"]; 

 $pasarela="Mercadopago Argentina";

include("../../classes/email_pago_recibido.php");


exit();

}







/*

    echo "el pago tiene respuesta ok"."<br>";

    echo "Refe_externa: ".$respuestas['external_reference']."<br>";

echo "payer: ".$respuestas['payer']['first_name']." ".$respuestas['payer']['last_name']."<br>";

echo "payer email: ".$respuestas['payer']['email']."<br>";

echo "payer dni: ".$respuestas['payer']['identification']['type'].$respuestas['payer']['identification']['number']."<br>";

echo "id: ".$respuestas['id']."<br>";



echo "<br><br>"; */





}











}







      }











    

  

  

    

 ?>