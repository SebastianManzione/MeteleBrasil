<?php
require_once __DIR__ . '/configuracion.php';
require_once __DIR__ . '/../email/PHPMailerAutoload.php';

/**
 * Obtiene configuración SMTP desde la tabla configuracion con fallbacks razonables.
 */
function obtenerConfigSMTP() {
    $config = new Configuracion();

    $port = (int)$config->obtener('smtp_port', 465);
    $secure = $config->obtener('smtp_secure', '');
    if (!$secure) {
        $secure = ($port === 465) ? 'ssl' : 'tls';
    }

    $user = $config->obtener('smtp_usuario', 'mails@metelebrasil.com');
    $from = $config->obtener('smtp_de', $config->obtener('sitio_email', $user));

    return [
        'host' => $config->obtener('smtp_host', 'mail.metelebrasil.com'),
        'port' => $port,
        'user' => $user,
        'pass' => $config->obtener('smtp_password', getenv('SMTP_PASSWORD') ?: ''),
        'secure' => $secure,
        'from' => $from ?: $user,
        'from_name' => $config->obtener('sitio_nombre', 'MeteleBrasil'),
        'helo' => $_SERVER['HTTP_HOST'] ?? 'www.metelebrasil.com'
    ];
}

/**
 * Configura PHPMailer con los valores leídos de BD.
 */
function configurarMailer(PHPMailer $mail, array $smtpConfig) {
    $mail->isSMTP();
    $mail->Host = $smtpConfig['host'];
    $mail->SMTPAuth = true;
    $mail->Username = $smtpConfig['user'];
    $mail->Password = $smtpConfig['pass'];
    $mail->SMTPSecure = $smtpConfig['secure'];
    $mail->Port = $smtpConfig['port'];
    $mail->Helo = $smtpConfig['helo'];
    $mail->From = $smtpConfig['from'];
    $mail->FromName = $smtpConfig['from_name'];
    $mail->CharSet = 'UTF-8';
}

function enviaMail($receptor, $asunto, $cuerpo, $site) {
    $smtpConfig = obtenerConfigSMTP();
    if (empty($smtpConfig['pass'])) {
        return 'SMTP no configurado: completa las credenciales en admin/configuracion.php (smtp_password).';
    }

    $mail = new PHPMailer;
    configurarMailer($mail, $smtpConfig);

    $mail->addAddress($receptor);
    // Garantizar copia en mails@metelebrasil.com
    $mail->addBCC('mails@metelebrasil.com');
    $mail->isHTML(true);
    $mail->Subject = $asunto;
    $mail->Body = $cuerpo;

    if (!$mail->send()) {
        return 'El mensaje no se pudo enviar. Envie este error al programador: ' . $mail->ErrorInfo;
    }

    return 'Mensaje enviado correctamente';
}

function enviaMailPagos($receptor, $asunto, $cuerpo, $site) {
    $smtpConfig = obtenerConfigSMTP();
    if (empty($smtpConfig['pass'])) {
        return 'SMTP no configurado: completa las credenciales en admin/configuracion.php (smtp_password).';
    }

    $mail = new PHPMailer;
    configurarMailer($mail, $smtpConfig);

    $mail->addAddress($receptor);
    // Garantizar copia en mails@metelebrasil.com (BCC para no exponer email al cliente)
    $mail->addBCC('mails@metelebrasil.com');
    $mail->isHTML(true);
    $mail->Subject = $asunto;
    $mail->Body = $cuerpo . " Mensaje generado automaticamente por Reservate software, si ud no desea recibir estos emails, haga click <a href='" . $site . "unSuscribe.php?email=" . $receptor . "'> Aqui </a>";

    if (!$mail->send()) {
        return 'El mensaje no se pudo enviar. Envie este error al programador: ' . $mail->ErrorInfo;
    }

    return 'Mensaje enviado correctamente';
}

?>