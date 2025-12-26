<?php
require_once __DIR__ . '/classes/configuracion.php';
$config = new Configuracion();

// Recargar SMTP
$config->guardar('smtp_host', 'mail.metelebrasil.com', 'string', 'SMTP Host');
$config->guardar('smtp_port', 465, 'number', 'SMTP Port');
$config->guardar('smtp_usuario', 'mails@metelebrasil.com', 'string', 'SMTP Usuario');
$config->guardar('smtp_password', '-s8k73Qrpy}lL)B&', 'string', 'SMTP Password');
$config->guardar('smtp_de', 'mails@metelebrasil.com', 'string', 'SMTP Remitente');

echo "SMTP recargado\n";
