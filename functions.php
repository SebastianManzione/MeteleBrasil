<?php 
		
function alertar($mensaje, $tipo){

  echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>'."<script> Swal.fire('".$mensaje."','','".$tipo."');</script>";
 ob_flush();
        flush();sleep(3);
}

function enviaMail($asunto, $cuerpo){

require 'email/PHPMailerAutoload.php';

$mail = new PHPMailer;

//$mail->SMTPDebug = 3;                               // Enable verbose debug output

$mail->isSMTP();                                      // Set mailer to use SMTP


$mail->Host = 'smtp.ipower.com';  // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'info@wpsoft.com.ar';                 // SMTP username
$mail->Password = 'Nueva$123';                           // SMTP password
$mail->SMTPSecure = 'ssl';                         // Enable TLS encryption, `ssl` also accepted
$mail->Port = 465;                                    // TCP port to connect to
$mail->Helo = "www.wpsoft.com.ar"; //Muy importante para que llegue a hotmail y otros
$mail->CharSet = 'UTF-8';
$mail->From = 'info@wpsoft.com.ar';
$mail->FromName = 'WEB WPSOFT';

$mail->addAddress('info@wpsoft.com.ar');     // Add a recipient
$mail->isHTML(true);                                  // Set email format to HTML


$mail->Subject = "Contacto desde www.wpsoft.com.ar ".$asunto;
$mail->Body    = $cuerpo;
//$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

if(!$mail->send()) {
    return 'El mensaje no se pudo enviar.'.'Envie este error al programador: ' . $mail->ErrorInfo;

} else {
    return 'Mensaje enviado correctamente';
}

}



function enviaMailContacto($asunto, $cuerpo, $email){

require 'email/PHPMailerAutoload.php';

$mail = new PHPMailer;

//$mail->SMTPDebug = 3;                               // Enable verbose debug output

$mail->isSMTP();                                      // Set mailer to use SMTP

$mail->Host = 'smtp.ipower.com';  // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'info@wpsoft.com.ar';                 // SMTP username
$mail->Password = 'Nueva$123';                           // SMTP password
$mail->SMTPSecure = 'ssl';                         // Enable TLS encryption, `ssl` also accepted
$mail->Port = 465;                                    // TCP port to connect to
$mail->Helo = "www.wpsoft.com.ar"; //Muy importante para que llegue a hotmail y otros
$mail->CharSet = 'UTF-8';
$mail->From = 'info@wpsoft.com.ar';
$mail->FromName = 'WEB WPSOFT';
   $mail->addReplyTo($email);
$mail->addAddress('info@wpsoft.com.ar');     // Add a recipient

$mail->isHTML(true);                                  // Set email format to HTML


$mail->Subject = "Contacto desde www.wpsoft.com.ar ".$asunto;
$mail->Body    = $cuerpo;
//$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

if(!$mail->send()) {
    return 'El mensaje no se pudo enviar.'.'Envie este error al programador: ' . $mail->ErrorInfo;

} else {
    return 'Mensaje enviado correctamente';
}

}

 ?>