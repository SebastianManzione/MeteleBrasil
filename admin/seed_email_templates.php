<?php
// Script temporal ya ejecutado para sembrar plantillas base.
echo "seed_email_templates deshabilitado\n";
/*
require_once __DIR__ . '/classes/email_templates.php';

$templates = [
    'reserva_confirmada' => [
        'subjects' => [
            'ES' => 'Reserva confirmada {{codigo_reserva}}',
            'EN' => 'Booking confirmed {{codigo_reserva}}',
            'PT' => 'Reserva confirmada {{codigo_reserva}}',
            'IT' => 'Prenotazione confermata {{codigo_reserva}}'
        ],
        'html' => [
            'ES' => '<h2>Tu reserva está confirmada</h2><p>Código: {{codigo_reserva}}</p><p>Consulta tu reserva aquí: <a href="{{enlace_reserva}}">{{enlace_reserva}}</a></p>',
            'EN' => '<h2>Your booking is confirmed</h2><p>Code: {{codigo_reserva}}</p><p>View your booking: <a href="{{enlace_reserva}}">{{enlace_reserva}}</a></p>',
            'PT' => '<h2>Sua reserva está confirmada</h2><p>Código: {{codigo_reserva}}</p><p>Veja sua reserva: <a href="{{enlace_reserva}}">{{enlace_reserva}}</a></p>',
            'IT' => '<h2>La tua prenotazione è confermata</h2><p>Codice: {{codigo_reserva}}</p><p>Vedi la tua prenotazione: <a href="{{enlace_reserva}}">{{enlace_reserva}}</a></p>'
        ]
    ],
    'prestador_reserva_confirmada' => [
        'subjects' => [
            'ES' => 'Nueva reserva {{codigo_reserva}}',
            'EN' => 'New booking {{codigo_reserva}}',
            'PT' => 'Nova reserva {{codigo_reserva}}',
            'IT' => 'Nuova prenotazione {{codigo_reserva}}'
        ],
        'html' => [
            'ES' => '<p>Hola {{nombre_prestador}},</p><p>Tienes una nueva reserva.</p><ul><li>Código: {{codigo_reserva}}</li><li>Salida: {{id_salida}}</li><li>Fecha: {{fecha_salida}}</li></ul>',
            'EN' => '<p>Hello {{nombre_prestador}},</p><p>You have a new booking.</p><ul><li>Code: {{codigo_reserva}}</li><li>Departure: {{id_salida}}</li><li>Date: {{fecha_salida}}</li></ul>',
            'PT' => '<p>Olá {{nombre_prestador}},</p><p>Você tem uma nova reserva.</p><ul><li>Código: {{codigo_reserva}}</li><li>Saída: {{id_salida}}</li><li>Data: {{fecha_salida}}</li></ul>',
            'IT' => '<p>Ciao {{nombre_prestador}},</p><p>Hai una nuova prenotazione.</p><ul><li>Codice: {{codigo_reserva}}</li><li>Uscita: {{id_salida}}</li><li>Data: {{fecha_salida}}</li></ul>'
        ]
    ],
    'reserva_pendiente' => [
        'subjects' => [
            'ES' => 'Reserva pendiente {{codigo_reserva}}',
            'EN' => 'Pending booking {{codigo_reserva}}',
            'PT' => 'Reserva pendente {{codigo_reserva}}',
            'IT' => 'Prenotazione in attesa {{codigo_reserva}}'
        ],
        'html' => [
            'ES' => '<p>Recibimos tu reserva. Está pendiente de pago.</p><p>Código: {{codigo_reserva}}</p>',
            'EN' => '<p>We received your booking. It is pending payment.</p><p>Code: {{codigo_reserva}}</p>',
            'PT' => '<p>Recebemos sua reserva. Está pendente de pagamento.</p><p>Código: {{codigo_reserva}}</p>',
            'IT' => '<p>Abbiamo ricevuto la tua prenotazione. È in attesa di pagamento.</p><p>Codice: {{codigo_reserva}}</p>'
        ]
    ],
    'pago_recibido' => [
        'subjects' => [
            'ES' => 'Pago recibido {{codigo_reserva}}',
            'EN' => 'Payment received {{codigo_reserva}}',
            'PT' => 'Pagamento recebido {{codigo_reserva}}',
            'IT' => 'Pagamento ricevuto {{codigo_reserva}}'
        ],
        'html' => [
            'ES' => '<p>¡Gracias! Registramos tu pago para la reserva {{codigo_reserva}}.</p>',
            'EN' => '<p>Thank you! We recorded your payment for booking {{codigo_reserva}}.</p>',
            'PT' => '<p>Obrigado! Registramos seu pagamento da reserva {{codigo_reserva}}.</p>',
            'IT' => '<p>Grazie! Abbiamo registrato il pagamento per la prenotazione {{codigo_reserva}}.</p>'
        ]
    ],
    'recuperar_contrasena' => [
        'subjects' => [
            'ES' => 'Recuperar contraseña',
            'EN' => 'Reset your password',
            'PT' => 'Recuperar senha',
            'IT' => 'Recupera la password'
        ],
        'html' => [
            'ES' => '<p>Para resetear tu contraseña haz clic aquí: <a href="{{enlace_reset}}">{{enlace_reset}}</a></p>',
            'EN' => '<p>To reset your password click here: <a href="{{enlace_reset}}">{{enlace_reset}}</a></p>',
            'PT' => '<p>Para redefinir sua senha clique aqui: <a href="{{enlace_reset}}">{{enlace_reset}}</a></p>',
            'IT' => '<p>Per reimpostare la password clicca qui: <a href="{{enlace_reset}}">{{enlace_reset}}</a></p>'
        ]
    ],
    'registro_usuario' => [
        'subjects' => [
            'ES' => 'Bienvenido a MeteleBrasil',
            'EN' => 'Welcome to MeteleBrasil',
            'PT' => 'Bem-vindo à MeteleBrasil',
            'IT' => 'Benvenuto su MeteleBrasil'
        ],
        'html' => [
            'ES' => '<p>Hola {{nombre}}, gracias por registrarte.</p>',
            'EN' => '<p>Hi {{nombre}}, thanks for registering.</p>',
            'PT' => '<p>Olá {{nombre}}, obrigado por se registrar.</p>',
            'IT' => '<p>Ciao {{nombre}}, grazie per la registrazione.</p>'
        ]
    ],
    'registro_prestador' => [
        'subjects' => [
            'ES' => 'Cuenta de prestador creada',
            'EN' => 'Provider account created',
            'PT' => 'Conta de prestador criada',
            'IT' => 'Account fornitore creata'
        ],
        'html' => [
            'ES' => '<p>Hola {{nombre}}, tu cuenta de prestador está lista.</p>',
            'EN' => '<p>Hi {{nombre}}, your provider account is ready.</p>',
            'PT' => '<p>Olá {{nombre}}, sua conta de prestador está pronta.</p>',
            'IT' => '<p>Ciao {{nombre}}, il tuo account fornitore è pronto.</p>'
        ]
    ]
];

$emailTpl = new EmailTemplates();

foreach ($templates as $clave => $data) {
    foreach (['ES','EN','PT','IT'] as $lang) {
        $asunto = $data['subjects'][$lang] ?? $data['subjects']['ES'];
        $html = $data['html'][$lang] ?? $data['html']['ES'];
        $emailTpl->guardar($clave, $lang, $asunto, $html);
    }
}

echo "Plantillas base cargadas\n";
*/