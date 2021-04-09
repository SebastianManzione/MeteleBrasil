<?php 
include("../functions.php");
include("config.php");
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



echo(InsertaPago($respuestas['external_reference'],$respuestas['transaction_amount'],1, 270,$id));
$contacto=DevuelveContacto($respuestas['external_reference']);

$mail=$contacto[3];

enviaMail($mail,"Recibimos tu pago correctamente!","
<!doctype html>
<html xmlns='http://www.w3.org/1999/xhtml' xmlns:v='urn:schemas-microsoft-com:vml' xmlns:o='urn:schemas-microsoft-com:office:office'>
  <head>
    <title>
    </title>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <style type='text/css'>
      #outlook a{padding: 0;}
            .ReadMsgBody{width: 100%%;}
            .ExternalClass{width: 100%%;}
            .ExternalClass *{line-height: 100%%;}
            body{margin: 0; padding: 0; -webkit-text-size-adjust: 100%%; -ms-text-size-adjust: 100%%;}
            table, td{border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;}
            img{border: 0; height: auto; line-height: 100%%; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic;}
            p{display: block; margin: 13px 0;}
    </style>
   
    <style type='text/css'>
      @media only screen and (max-width:480px) {
                  @-ms-viewport {width: 320px;}
                  @viewport { width: 320px; }
              }
    </style>

    <style type='text/css'>
      @media only screen and (min-width:480px) {
      .dys-column-per-100 {
        width: 100.000000% !important;
        max-width: 100.000000%;
      }
      }
      @media only screen and (min-width:480px) {
      .dys-column-per-100 {
        width: 100.000000% !important;
        max-width: 100.000000%;
      }
      }
      @media only screen and (min-width:480px) {
      .dys-column-per-5 {
        width: 5% !important;
        max-width: 5%;
      }
      .dys-column-per-45 {
        width: 45% !important;
        max-width: 45%;
      }
      }
      @media only screen and (min-width:480px) {
      .dys-column-per-100 {
        width: 100.000000% !important;
        max-width: 100.000000%;
      }
      }
      @media only screen and (min-width:480px) {
      .dys-column-per-100 {
        width: 100.000000% !important;
        max-width: 100.000000%;
      }
      }
      @media only screen and (min-width:480px) {
      .dys-column-per-90 {
        width: 90% !important;
        max-width: 90%;
      }
      }
    </style>
  </head>
  <body>
    <div>
      <table align='center' background='http://s3.amazonaws.com/swu-filepicker/4E687TRe69Ld95IDWyEg_bg_top_02.jpg' border='0' cellpadding='0' cellspacing='0' role='presentation' style='background:url(http://s3.amazonaws.com/swu-filepicker/4E687TRe69Ld95IDWyEg_bg_top_02.jpg) top center / auto repeat;width:100%;'>
        <tbody>
          <tr>
            <td>
    
              <div style='margin:0px auto;max-width:600px;'>
                <div style='font-size:0;line-height:0;'>
                  <table align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='width:100%;'>
                    <tbody>
                      <tr>
                        <td style='direction:ltr;font-size:0px;padding:20px 0px 30px 0px;text-align:center;vertical-align:top;'>
        
                          <div class='dys-column-per-100 outlook-group-fix' style='direction:ltr;display:inline-block;font-size:13px;text-align:left;vertical-align:top;width:100%;'>
                            <table border='0' cellpadding='0' cellspacing='0' role='presentation' width='100%'>
                              <tbody>
                                <tr>
                                  <td style='padding:0px 20px;vertical-align:top;'>
                                    <table border='0' cellpadding='0' cellspacing='0' role='presentation' style='' width='100%'>
                                      <tr>
                                        <td align='left' style='font-size:0px;padding:0px;word-break:break-word;'>
                                          <table border='0' cellpadding='0' cellspacing='0' style='cellpadding:0;cellspacing:0;color:#000000;font-family:Helvetica, Arial, sans-serif;font-size:13px;line-height:22px;table-layout:auto;width:100%;' width='100%'>
                                            <tr>
                                              <td align='left'>
                                                <a href='#'>
                                                  <img align='left'  height='33' padding='5px' src='https://metelebrasil.com/metelebrasil.png' width='120' />
                                                </a>
                                              </td>
                                              <td align='right' style='vertical-align:bottom;' width='34px'>
                                                <a href='#'>
                                                  <img  height='22' src='http://s3.amazonaws.com/swu-cs-assets/OSET/social/Twitter_grey.png' width='22' />
                                                </a>
                                              </td>
                                              <td align='right' style='vertical-align:bottom;' width='34px'>
                                                <a href='#'>
                                                  <img height='22' src='https://swu-cs-assets.s3.amazonaws.com/OSET/social/f_grey.png' width='22' />
                                                </a>
                                              </td>
                                              <td align='right' style='vertical-align:bottom;' width='34px'>
                                                <a href='#'>
                                                  <img height='22' src='https://swu-cs-assets.s3.amazonaws.com/OSET/social/instagrey.png' width='22' />
                                                </a>
                                              </td>
                                            </tr>
                                          </table>
                                        </td>
                                      </tr>
                                    </table>
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
         
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
        
            </td>
          </tr>
        </tbody>
      </table>
      <table align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='background:#f7f7f7;background-color:#f7f7f7;width:100%;'>
        <tbody>
          <tr>
            <td>
              <div style='margin:0px auto;max-width:600px;'>
                <table align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='width:100%;'>
                  <tbody>
                    <tr>
                      <td style='direction:ltr;font-size:0px;padding:20px 0;text-align:center;vertical-align:top;'>
         
                        <div class='dys-column-per-100 outlook-group-fix' style='direction:ltr;display:inline-block;font-size:13px;text-align:left;vertical-align:top;width:100%;'>
                          <table border='0' cellpadding='0' cellspacing='0' role='presentation' style='vertical-align:top;' width='100%'>
                            <tr>
                              <td align='center' style='font-size:0px;padding:10px 25px;word-break:break-word;'>
                                <div style='color:#4d4d4d;font-family:Oxygen, Helvetica neue, sans-serif;font-size:32px;font-weight:700;line-height:37px;text-align:center;'>
                                  Recibimos tu pago correctamente
                                </div>
                              </td>
                            </tr>
                            <tr>
                              <td align='center' style='font-size:0px;padding:10px 25px;word-break:break-word;'>
                                <div style='color:#777777;font-family:Oxygen, Helvetica neue, sans-serif;font-size:14px;line-height:21px;text-align:center;'>
                                 Gracias por abonar ".$respuestas['transaction_amount']." para el numero de reserva ".$respuestas['external_reference']." mediante Mercadopago Argentina. 
                                </div>
                              </td>
                            </tr>
                          </table>
                        </div>
        
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
      
                        </div>
  

      <div style='margin:0px auto;max-width:600px;'>
        <table align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='width:100%;'>
          <tbody>
            <tr>
              <td style='direction:ltr;font-size:0px;padding:20px 0;text-align:center;vertical-align:top;'>
              <div class='dys-column-per-100 outlook-group-fix' style='direction:ltr;display:inline-block;font-size:13px;text-align:left;vertical-align:top;width:100%;'>
                  <table border='0' cellpadding='0' cellspacing='0' role='presentation' style='vertical-align:top;' width='100%'>
                    <tr>
                      <td align='center' style='font-size:0px;padding:10px 25px;word-break:break-word;' vertical-align='middle'>
                        <table border='0' cellpadding='0' cellspacing='0' role='presentation' style='border-collapse:separate;line-height:100%;'>
                          <tr>
                            <td align='center' bgcolor='#029ce2' role='presentation' style='background-color:#029ce2;border:none;border-radius:5px;cursor:auto;padding:10px 25px;' valign='middle'>
                              <a href='metelebrasil.com/sistema/metelebrasil/consultaReserva.php?id=".$respuestas['external_reference']."' style='background:#029ce2;color:#ffffff;font-family:Oxygen, Helvetica neue, sans-serif;font-size:14px;font-weight:400;line-height:21px;margin:0;text-decoration:none;text-transform:none;' target='_blank'>
                               Ver online
                              </a>
                            </td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                </div>
  
              </td>
            </tr>
          </tbody>
        </table>
      </div>
  
      <table align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='background:#f7f7f7;background-color:#f7f7f7;width:100%;'>
        <tbody>
          <tr>
            <td>
              <div style='margin:0px auto;max-width:600px;'>
                <table align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='width:100%;'>
                  <tbody>
                    <tr>
                      <td style='direction:ltr;font-size:0px;padding:20px 0;text-align:center;vertical-align:top;'>
      
                        <div class='dys-column-per-100 outlook-group-fix' style='direction:ltr;display:inline-block;font-size:13px;text-align:left;vertical-align:top;width:100%;'>
                          <table border='0' cellpadding='0' cellspacing='0' role='presentation' style='vertical-align:top;' width='100%'>
                            <tr>
                              <td align='center' style='font-size:0px;padding:5px 25px;word-break:break-word;'>
                                <div style='color:#777777;font-family:Oxygen, Helvetica neue, sans-serif;font-size:14px;font-style:bold;font-weight:700;line-height:21px;text-align:center;'>
                                  Reservate Software ®
                                </div>
                              </td>
                            </tr>
                            <tr>
                              <td align='center' style='font-size:0px;padding:5px 25px;word-break:break-word;'>
                                <div style='color:#777777;font-family:Oxygen, Helvetica neue, sans-serif;font-size:14px;font-style:bold;line-height:1;text-align:center;'>
                                  Rua 101, Balneario Camboriu
                                </div>
                              </td>
                            </tr>
                            <tr>
                              <td align='center' style='font-size:0px;padding:5px 25px;word-break:break-word;'>
                                <div style='color:#777777;font-family:Oxygen, Helvetica neue, sans-serif;font-size:14px;font-style:bold;line-height:1;text-align:center;'>
                                 Santa Catarina, Brasil
                                </div>
                              </td>
                            </tr>
                          </table>
                        </div>
    
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
      <table align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='background:#f7f7f7;background-color:#f7f7f7;width:100%;'>
        <tbody>
          <tr>
            <td>
              <div style='margin:0px auto;max-width:600px;'>
                <table align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='width:100%;'>
                  <tbody>
                    <tr>
                      <td style='direction:ltr;font-size:0px;padding:20px 0;text-align:center;vertical-align:top;'>
        
                        <div class='dys-column-per-90 outlook-group-fix' style='direction:ltr;display:inline-block;font-size:13px;text-align:left;vertical-align:top;width:100%;'>
                          <table border='0' cellpadding='0' cellspacing='0' role='presentation' width='100%'>
                            <tbody>
                              <tr>
                                <td style='background-color:#ffffff;border:1px solid #ccc;padding:30px 75px;vertical-align:top;'>
                                  <table border='0' cellpadding='0' cellspacing='0' role='presentation' style='' width='100%'>
                                    <tr>
                                      <td align='center' style='font-size:0px;padding:10px 25px;word-break:break-word;'>
                                        <div style='color:#4d4d4d;font-family:Oxygen, Helvetica neue, sans-serif;font-size:18px;font-weight:400;line-height:21px;text-align:center;'>
                                          Califica nuestros servicios
                                        </div>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td align='left' style='font-size:0px;padding:10px 25px;padding-bottom:5px;word-break:break-word;'>
                                        <table border='0' cellpadding='0' cellspacing='0' style='cellpadding:0;cellspacing:0;color:#000000;font-family:Helvetica, Arial, sans-serif;font-size:13px;line-height:22px;table-layout:auto;width:100%;' width='100%'>
                                          <tr>
                                            <td style='text-align: center;'>
                                              <a href='# 1'>
                                                <img alt='Alt 1' src='http://s3.amazonaws.com/swu-filepicker/JJ7YmPzNQJujBPJq6yVi_star_03.jpg' />
                                              </a>
                                            </td>
                                            <td style='text-align: center;'>
                                              <a href='# 2'>
                                                <img alt='Alt 2' src='http://s3.amazonaws.com/swu-filepicker/JJ7YmPzNQJujBPJq6yVi_star_03.jpg' />
                                              </a>
                                            </td>
                                            <td style='text-align: center;'>
                                              <a href='# 3'>
                                                <img alt='Alt 3' src='http://s3.amazonaws.com/swu-filepicker/JJ7YmPzNQJujBPJq6yVi_star_03.jpg' />
                                              </a>
                                            </td>
                                            <td style='text-align: center;'>
                                              <a href='# 4'>
                                                <img alt='Alt 4' src='http://s3.amazonaws.com/swu-filepicker/JJ7YmPzNQJujBPJq6yVi_star_03.jpg' />
                                              </a>
                                            </td>
                                            <td style='text-align: center;'>
                                              <a href='# 5'>
                                                <img alt='Alt 5' src='http://s3.amazonaws.com/swu-filepicker/JJ7YmPzNQJujBPJq6yVi_star_03.jpg' />
                                              </a>
                                            </td>
                                          </tr>
                                        </table>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td align='left' style='font-size:0px;padding:10px 25px;padding-top:0px;word-break:break-word;'>
                                        <table border='0' cellpadding='0' cellspacing='0' style='cellpadding:0;cellspacing:0;color:#000000;font-family:Helvetica, Arial, sans-serif;font-size:13px;line-height:22px;table-layout:auto;width:100%;' width='100%'>
                                          <tr style='font-family: Helvetica, Arial, sans-serif; font-size: 14px; color: #777777; text-align: center; line-height: 21px;'>
                                            <td style='text-align: left; font-weight:bold;'>
                                              No satisfecho
                                            </td>
                                            <td style='text-align: left; font-weight:bold;'>
                                              Neutro
                                            </td>
                                            <td style='text-align: right; font-weight:bold;'>
                                              Muy satisfecho
                                            </td>
                                          </tr>
                                        </table>
                                      </td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
          
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </body>
</html>


   ");



exit();




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