<?php



  

function enviaMail($receptor, $asunto, $cuerpo, $site){

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
// Activo condificacción utf-8
$mail->CharSet = 'UTF-8';
$mail->addAddress($receptor);     // Add a recipient

$mail->addBCC($direccion_remitente);     // Add a recipient

$mail->isHTML(true);                                  // Set email format to HTML

$mail->Subject = $asunto;

$mail->Body    = $cuerpo;

//$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

if(!$mail->send()) {

    return 'El mensaje no se pudo enviar.'.'Envie este error al programador: ' . $mail->ErrorInfo;

} else {

    return 'Mensaje enviado correctamente';

}

}





  

function enviaMailPagos($receptor, $asunto, $cuerpo, $site){

    $direccion_remitente='mails@metelebrasil.com';

require('../../email/PHPMailerAutoload.php');

$mail = new PHPMailer;

//$mail->SMTPDebug = 3;                               // Enable verbose debug output

$mail->isSMTP();                                      // Set mailer to use SMTP

$mail->Host =  'mail.metelebrasil.com';  // Specify main and backup SMTP servers

$mail->SMTPAuth = true;                               // Enable SMTP authentication

$mail->Username = $direccion_remitente;                 // SMTP username

$mail->Password = 'Nueva$123';                           // SMTP password

$mail->SMTPSecure = 'ssl';                         // Enable TLS encryption, `ssl` also accepted

$mail->Port = 465;                                    // TCP port to connect to

$mail->Helo = "www.metelebrasil.com"; //Muy importante para que llegue a hotmail y otros

$mail->From = $direccion_remitente;

$mail->FromName = 'Reservas METELEBRASIL.COM';

$mail->addAddress($receptor);     // Add a recipient

$mail->addAddress($direccion_remitente);     // Add a recipient

$mail->isHTML(true);                                  // Set email format to HTML

$mail->Subject = $asunto;

$mail->Body    = $cuerpo." Mensaje generado automaticamente por Reservate software, si ud no desea recibir estos emails, haga click <a href='".$site."unSuscribe.php?email=".$receptor."'> Aqui </a>";

//$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

if(!$mail->send()) {

    return 'El mensaje no se pudo enviar.'.'Envie este error al programador: ' . $mail->ErrorInfo;

} else {

    return 'Mensaje enviado correctamente';

}

}



?>