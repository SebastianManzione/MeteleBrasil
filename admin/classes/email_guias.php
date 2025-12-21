<?php 


function enviaMailAdjunto($receptor, $asunto, $cuerpo, $site, $adjunto){

    $direccion_remitente='mails@metelebrasil.com';

require_once($_SERVER['DOCUMENT_ROOT'].'/admin/email/PHPMailerAutoload.php');

$mail = new PHPMailer;

//$mail->SMTPDebug = 3;                               // Enable verbose debug output

$mail->isSMTP();                                      // Set mailer to use SMTP

$mail->Host = 'mail.metelebrasil.com';  // Specify main and backup SMTP servers

$mail->SMTPAuth = true;                               // Enable SMTP authentication

$mail->Username = $direccion_remitente;                 // SMTP username

$mail->Password = '-s8k73Qrpy}lL)B&';                           // SMTP password

$mail->SMTPSecure = 'ssl';                         // Enable TLS encryption, `ssl` also accepted

$mail->Port = 465;                                    // TCP port to connect to

$mail->Helo = "www.metelebrasil.com"; //Muy importante para que llegue a hotmail y otros

$mail->From = $direccion_remitente;

$mail->FromName = 'Reservas METELEBRASIL.COM';

$mail->addAddress($receptor);     // Add a recipient

$mail->addBCC($direccion_remitente);     // Add a recipient
// Activo condificacción utf-8
$mail->CharSet = 'UTF-8';
$mail->isHTML(true);                                  // Set email format to HTML
$mail->AddAttachment($adjunto);
$mail->Subject = $asunto;

$mail->Body    = $cuerpo;

//$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

if(!$mail->send()) {

    return 'El mensaje no se pudo enviar.'.'Envie este error al programador: ' . $mail->ErrorInfo;

} else {

    return 'Mensaje enviado correctamente';

}

}

function cuerpoEmailGuia($nombre, $categoria) {
    session_start();
    $idioma = isset($_SESSION["idioma"]) ? $_SESSION["idioma"] : "ES"; // Padrão: espanhol

    $titulo = "";
    $mensagem = "";

    switch ($idioma) {
        case 'EN': // Inglês
            $titulo = "Great! You already have your guide in hand!";
            $mensagem = "Hello $nombre, Metele Brasil has forwarded your guide for ($categoria). You can download it for free at no cost! We thought of every detail for your trip, and we also sent a tutorial on how to pack your suitcase for this $categoria. <br><br> Thank you for your trust! <br> Have a great trip!";
            break;
        case 'PT': // Português
            $titulo = "Ótimo! Já tem sua guia na mão!";
            $mensagem = "Olá $nombre, Metele Brasil encaminhou sua guia para ($categoria). Poderá descarregar ela grátis sem custo! Pensamos em cada detalhe para sua viagem diante a isso também encaminhamos um tutorial para montar sua mala para este $categoria. <br><br> Agradecemos sua confiança! <br> Boa Viagem!";
            break;
        default: // Espanhol (ES)
            $titulo = "¡Genial! Ya tienes tu guía en mano!";
            $mensagem = "Hola $nombre, Metele Brasil ha enviado tu guía para ($categoria). Puedes descargarla gratis sin costo. Hemos pensado en cada detalle para tu viaje, por eso también te enviamos un tutorial para armar tu maleta para este $categoria. <br><br> ¡Gracias por tu confianza! <br> ¡Buen viaje!";
            break;
    }

    $retorno = '
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
  <head>
    <title></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style type="text/css">
      #outlook a{padding: 0;}
      .ReadMsgBody{width: 100%;}
      .ExternalClass{width: 100%;}
      .ExternalClass *{line-height: 100%;}
      body{margin: 0; padding: 0; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%;}
      table, td{border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;}
      img{border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic;}
      p{display: block; margin: 13px 0;}
    </style>
    <style type="text/css">
      @media only screen and (max-width:480px) {
        @-ms-viewport {width: 320px;}
        @viewport { width: 320px; }
      }
    </style>
    <style type="text/css">
      @media only screen and (min-width:480px) {
        .dys-column-per-100 { width: 100% !important; max-width: 100%; }
        .dys-column-per-5 { width: 5% !important; max-width: 5%; }
        .dys-column-per-45 { width: 45% !important; max-width: 45%; }
        .dys-column-per-90 { width: 90% !important; max-width: 90%; }
      }
    </style>
  </head>
  <body>
    <div>
      <table align="center" background="http://s3.amazonaws.com/swu-filepicker/4E687TRe69Ld95IDWyEg_bg_top_02.jpg" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:#029ce2 top center / auto repeat;width:100%;line-height:20px;">
        <tbody>
          <tr>
            <td>
              <div style="margin:0px auto;max-width:600px;">
                <div style="font-size:0;line-height:0;">
                  <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;">
                    <tbody>
                      <tr>
                        <td style="direction:ltr;font-size:0px;padding:20px 0px 30px 0px;text-align:center;vertical-align:top;">
                          <div class="dys-column-per-100 outlook-group-fix" style="direction:ltr;display:inline-block;font-size:13px;text-align:left;vertical-align:top;width:100%;">
                            <table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%">
                              <tbody>
                                <tr>
                                  <td style="padding:0px 20px;vertical-align:top;">
                                    <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="" width="100%">
                                      <tr>
                                        <td align="left" style="font-size:0px;padding:0px;word-break:break-word;">
                                          <table border="0" cellpadding="0" cellspacing="0" style="cellpadding:0;cellspacing:0;color:#000000;font-family:Helvetica, Arial, sans-serif;font-size:13px;line-height:50px;table-layout:auto;width:100%;" width="100%">
                                            <tr>
                                              <td align="left">
                                                <a href="https://metelebrasil.com">
                                                  <img align="left"  height="40" padding="5px" src="https://metelebrasil.com/img/favicon.png" width="40" />
                                                </a>
                                                <div style="color:#ffffff;font-family:Oxygen, Helvetica neue, sans-serif;font-size:23px;font-weight:700;line-height:50px;text-align:center;font-style:bold;">
                                                  METELE BRASIL
                                                </div>
                                              </td>
                                              <td align="right" style="vertical-align:bottom;" width="34px">
                                                <a href="https://www.facebook.com/metelebrasil">
                                                  <img height="10" src="https://swu-cs-assets.s3.amazonaws.com/OSET/social/f_grey.png" width="22" />
                                                </a>
                                              </td>
                                              <td align="right" style="vertical-align:bottom;" width="34px">
                                                <a href="https://www.instagram.com/metelebrasil">
                                                  <img height="10" src="https://swu-cs-assets.s3.amazonaws.com/OSET/social/instagrey.png" width="22" />
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
      <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:#f7f7f7;background-color:#f7f7f7;width:100%;">
        <tbody>
          <tr>
            <td>
              <div style="margin:0px auto;max-width:600px;">
                <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;">
                  <tbody>
                    <tr>
                      <td style="direction:ltr;font-size:0px;padding:20px 0;text-align:center;vertical-align:top;">
                        <div class="dys-column-per-100 outlook-group-fix" style="direction:ltr;display:inline-block;font-size:13px;text-align:left;vertical-align:top;width:100%;">
                          <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
                            <tr>
                              <td align="center" style="font-size:0px;padding:10px 25px;word-break:break-word;">
                                <div style="color:#52AC0D;font-family:Oxygen, Helvetica neue, sans-serif;font-size:32px;font-weight:700;line-height:37px;text-align:center;">
                                  '.$titulo.'
                                </div>
                              </td>
                            </tr>
                            <tr>
                              <td align="center" style="font-size:0px;padding:10px 25px;word-break:break-word;">
                                <div style="color:#777777;font-family:Oxygen, Helvetica neue, sans-serif;font-size:14px;line-height:21px;text-align:center;">
                                  '.$mensagem.'
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
      <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:#f7f7f7;background-color:#f7f7f7;width:100%;">
        <tbody>
          <tr>
            <td>
              <div style="margin:0px auto;max-width:600px;">
                <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;">
                  <tbody>
                    <tr>
                      <td style="direction:ltr;font-size:0px;padding:20px 0;text-align:center;vertical-align:top;">
                        <div class="dys-column-per-100 outlook-group-fix" style="direction:ltr;display:inline-block;font-size:13px;text-align:left;vertical-align:top;width:100%;">
                          <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
                            <tr>
                              <td align="center" style="font-size:0px;padding:5px 25px;word-break:break-word;">
                                <div style="color:#777777;font-family:Oxygen, Helvetica neue, sans-serif;font-size:14px;font-style:bold;line-height:1;text-align:center;">
                                  Você está recebendo este e-mail porque realizou um cadastro no site metelebrasil.com. Caso você desconheça essa ação ignore este e-mail.
                                </div>
                              </td>
                            </tr>
                            <tr>
                              <td align="center" style="font-size:0px;padding:5px 25px;word-break:break-word;">
                                <div style="color:#777777;font-family:Oxygen, Helvetica neue, sans-serif;font-size:14px;font-style:bold;font-weight:700;line-height:21px;text-align:center;">
                                  Reservate Software ®
                                </div>
                                <div style="color:#777777;font-family:Oxygen, Helvetica neue, sans-serif;font-size:14px;font-style:bold;line-height:1;text-align:center;">
                                  Balneário Camboriú. SC - BRASIL
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
  </body>
</html>';
    return $retorno;
}


?>
