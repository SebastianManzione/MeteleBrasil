<?php
require 'PHPMailerAutoload.php';

$mail = new PHPMailer;

//$mail->SMTPDebug = 3;                               // Enable verbose debug output

$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'smtp.ipower.com';  // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'metelebrasil@metelebrasil.com';                 // SMTP username
$mail->Password = 'Nueva$123';                           // SMTP password
$mail->SMTPSecure = 'ssl';                         // Enable TLS encryption, `ssl` also accepted
$mail->Port = 465;                                    // TCP port to connect to
$mail->Helo = "www.metelebrasil.com"; //Muy importante para que llegue a hotmail y otros

$mail->From = 'metelebrasil@metelebrasil.com';
$mail->FromName = 'Reservas METELEBRASIL.COM';
$mail->addAddress('pontopraia@gmail.com');     // Add a recipient
$mail->addAddress('informatica.manz@gmail.com');     // Add a recipient
$mail->isHTML(false);                                  // Set email format to HTML

$mail->Subject = 'Tu reserva en metelebrasil esta pendiente';
$mail->Body    = "<p>Texto lo suficientemente largo y con pocos links para que no sea interpretado como SPAM</p><p>Es importante que el texto sea lo suficientemente largo, ya que si sólo tienes por ejemplo, una frase y un link, asume tanto Outlook como la mayoría de los destinatarios, que se trata de SPAM</p><p>Pero si tu texto es largo y tienes un link a <a href\"http://www.metelebrasil.com\">Foros del web</a> por ejemplo, funcionará perfectamente bien.</p>";
//$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

if(!$mail->send()) {
    echo 'El mensaje no se pudo enviar.';
    echo 'Envie este error al programador: ' . $mail->ErrorInfo;
} else {
    echo 'Mensaje enviado correctamente';
}