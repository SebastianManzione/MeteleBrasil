<?php
include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");
require("classes/configuracion.php");

// Verificar permisos (solo admin)
if (!isset($_SESSION['login']['idUsuario']) || $_SESSION['login']['idUsuario'] != 1) {
    echo '<div class="content-wrapper" style="margin-top: 20px;"><div class="container-fluid"><div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-top: 20px; padding: 20px;">';
    echo '<h4 class="alert-heading">⛔ Acceso Denegado</h4>';
    echo '<p>Solo los administradores pueden acceder a este panel.</p>';
    echo '<hr><a href="index.php" class="btn btn-primary btn-sm">← Volver al Dashboard</a>';
    echo '</div></div></div>';
    include("includes/footer.php");
    die();
}

$config = new Configuracion();
$mensaje = '';
$test_email_data = [
    'para' => isset($_POST['test_email_para']) ? trim($_POST['test_email_para']) : $config->obtener('sitio_email', ''),
    'asunto' => isset($_POST['test_email_asunto']) ? trim($_POST['test_email_asunto']) : 'Prueba SMTP MeteleBrasil',
    'html' => isset($_POST['test_email_html']) ? $_POST['test_email_html'] : '<p><strong>Test SMTP MeteleBrasil</strong></p><p>Si ves este correo, la configuración SMTP funciona.</p>'
];

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['enviar_test_email'])) {
        $para = trim($_POST['test_email_para'] ?? '');
        $asunto = trim($_POST['test_email_asunto'] ?? 'Prueba SMTP MeteleBrasil');
        $html = $_POST['test_email_html'] ?? '';

        if ($para === '') {
            $mensaje = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-times-circle"></i> Debes indicar un destinatario para la prueba.
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>';
        } else {
            require_once('classes/reservaEmail.php');
            $resp = enviaMail($para, $asunto, $html, $_SERVER['HTTP_HOST'] ?? '/');
            $ok = stripos($resp, 'correctamente') !== false;
            $mensaje = '<div class="alert alert-' . ($ok ? 'success' : 'danger') . ' alert-dismissible fade show" role="alert">
                <i class="fas fa-' . ($ok ? 'check-circle' : 'times-circle') . '"></i> ' . htmlspecialchars($resp) . '
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>';
        }
    } elseif (isset($_POST['guardar_config'])) {
    // General
    if (isset($_POST['sitio_nombre'])) {
        $config->guardar('sitio_nombre', $_POST['sitio_nombre'], 'string', 'Nombre del Sitio');
    }
    if (isset($_POST['sitio_email'])) {
        $config->guardar('sitio_email', $_POST['sitio_email'], 'string', 'Email del Sitio');
    }
    if (isset($_POST['sitio_telefono'])) {
        $config->guardar('sitio_telefono', $_POST['sitio_telefono'], 'string', 'Teléfono del Sitio');
    }
    
    // Google OAuth
    if (isset($_POST['google_client_id'])) {
        $config->guardar('google_client_id', $_POST['google_client_id'], 'string', 'Google OAuth Client ID');
    }
    if (isset($_POST['google_client_secret'])) {
        $config->guardar('google_client_secret', $_POST['google_client_secret'], 'string', 'Google OAuth Client Secret');
    }
    
    // Facebook OAuth
    if (isset($_POST['facebook_app_id'])) {
        $config->guardar('facebook_app_id', $_POST['facebook_app_id'], 'string', 'Facebook App ID');
    }
    if (isset($_POST['facebook_app_secret'])) {
        $config->guardar('facebook_app_secret', $_POST['facebook_app_secret'], 'string', 'Facebook App Secret');
    }
    
    // reCAPTCHA
    if (isset($_POST['recaptcha_site_key'])) {
        $config->guardar('recaptcha_site_key', $_POST['recaptcha_site_key'], 'string', 'reCAPTCHA Site Key');
    }
    if (isset($_POST['recaptcha_secret_key'])) {
        $config->guardar('recaptcha_secret_key', $_POST['recaptcha_secret_key'], 'string', 'reCAPTCHA Secret Key');
    }
    
    // Email SMTP
    if (isset($_POST['smtp_host'])) {
        $config->guardar('smtp_host', $_POST['smtp_host'], 'string', 'SMTP Host');
    }
    if (isset($_POST['smtp_port'])) {
        $config->guardar('smtp_port', $_POST['smtp_port'], 'number', 'SMTP Port');
    }
    if (isset($_POST['smtp_usuario'])) {
        $config->guardar('smtp_usuario', $_POST['smtp_usuario'], 'string', 'SMTP Usuario');
    }
    if (isset($_POST['smtp_password'])) {
        $config->guardar('smtp_password', $_POST['smtp_password'], 'string', 'SMTP Password');
    }
    if (isset($_POST['smtp_de'])) {
        $config->guardar('smtp_de', $_POST['smtp_de'], 'string', 'SMTP De (Remitente)');
    }

    // MercadoPago
    if (isset($_POST['mp_environment'])) {
        $config->guardar('mp_environment', $_POST['mp_environment'], 'string', 'MercadoPago Entorno');
    }
    if (isset($_POST['mp_ar_sandbox_public_key'])) {
        $config->guardar('mp_ar_sandbox_public_key', $_POST['mp_ar_sandbox_public_key'], 'string', 'MP AR Sandbox Public Key');
    }
    if (isset($_POST['mp_ar_sandbox_access_token'])) {
        $config->guardar('mp_ar_sandbox_access_token', $_POST['mp_ar_sandbox_access_token'], 'string', 'MP AR Sandbox Access Token');
    }
    if (isset($_POST['mp_ar_production_public_key'])) {
        $config->guardar('mp_ar_production_public_key', $_POST['mp_ar_production_public_key'], 'string', 'MP AR Production Public Key');
    }
    if (isset($_POST['mp_ar_production_access_token'])) {
        $config->guardar('mp_ar_production_access_token', $_POST['mp_ar_production_access_token'], 'string', 'MP AR Production Access Token');
    }
    if (isset($_POST['mp_br_sandbox_public_key'])) {
        $config->guardar('mp_br_sandbox_public_key', $_POST['mp_br_sandbox_public_key'], 'string', 'MP BR Sandbox Public Key');
    }
    if (isset($_POST['mp_br_sandbox_access_token'])) {
        $config->guardar('mp_br_sandbox_access_token', $_POST['mp_br_sandbox_access_token'], 'string', 'MP BR Sandbox Access Token');
    }
    if (isset($_POST['mp_br_production_public_key'])) {
        $config->guardar('mp_br_production_public_key', $_POST['mp_br_production_public_key'], 'string', 'MP BR Production Public Key');
    }
    if (isset($_POST['mp_br_production_access_token'])) {
        $config->guardar('mp_br_production_access_token', $_POST['mp_br_production_access_token'], 'string', 'MP BR Production Access Token');
    }
    
    // OpenPix (PIX)
    if (isset($_POST['openpix_app_id'])) {
        $config->guardar('openpix_app_id', $_POST['openpix_app_id'], 'string', 'OpenPix App ID');
    }
    if (isset($_POST['openpix_api_key'])) {
        $config->guardar('openpix_api_key', $_POST['openpix_api_key'], 'string', 'OpenPix API Key');
    }
    
    // PayPal
    if (isset($_POST['paypal_environment'])) {
        $config->guardar('paypal_environment', $_POST['paypal_environment'], 'string', 'PayPal Entorno');
    }
    if (isset($_POST['paypal_client_id_brl'])) {
        $config->guardar('paypal_client_id_brl', $_POST['paypal_client_id_brl'], 'string', 'PayPal Client ID BRL');
    }
    if (isset($_POST['paypal_client_secret_brl'])) {
        $config->guardar('paypal_client_secret_brl', $_POST['paypal_client_secret_brl'], 'string', 'PayPal Client Secret BRL');
    }
    if (isset($_POST['paypal_client_id_usd'])) {
        $config->guardar('paypal_client_id_usd', $_POST['paypal_client_id_usd'], 'string', 'PayPal Client ID USD');
    }
    if (isset($_POST['paypal_client_secret_usd'])) {
        $config->guardar('paypal_client_secret_usd', $_POST['paypal_client_secret_usd'], 'string', 'PayPal Client Secret USD');
    }
    
    // Parámetros (head/body injection)
    if (isset($_POST['parametros_head'])) {
        $config->guardar('parametros_head', $_POST['parametros_head'], 'text', 'Código Head');
    }
    if (isset($_POST['parametros_body'])) {
        $config->guardar('parametros_body', $_POST['parametros_body'], 'text', 'Código Body');
    }
    
    // Mantenimiento
    // El checkbox solo envía valor si está marcado, así que si no existe en POST = 0
    $mantenimiento_val = isset($_POST['mantenimiento_activo']) && $_POST['mantenimiento_activo'] === 'on' ? 1 : 0;
    $config->guardar('mantenimiento_activo', $mantenimiento_val, 'boolean', 'Modo Mantenimiento Activo');
    
    if (isset($_POST['mantenimiento_mensaje'])) {
        $config->guardar('mantenimiento_mensaje', $_POST['mantenimiento_mensaje'], 'string', 'Mensaje de Mantenimiento');
    }
    
        $mensaje = '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> <strong>¡Éxito!</strong> Configuración guardada correctamente.
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>';
    }
}

// Obtener configuraciones actuales
$config_data = [
    'sitio_nombre' => $config->obtener('sitio_nombre', 'MeteleBrasil'),
    'sitio_email' => $config->obtener('sitio_email', ''),
    'sitio_telefono' => $config->obtener('sitio_telefono', ''),
    'google_client_id' => $config->obtener('google_client_id', ''),
    'google_client_secret' => $config->obtener('google_client_secret', ''),
    'facebook_app_id' => $config->obtener('facebook_app_id', ''),
    'facebook_app_secret' => $config->obtener('facebook_app_secret', ''),
    'recaptcha_site_key' => $config->obtener('recaptcha_site_key', ''),
    'recaptcha_secret_key' => $config->obtener('recaptcha_secret_key', ''),
    'smtp_host' => $config->obtener('smtp_host', ''),
    'smtp_port' => $config->obtener('smtp_port', 587),
    'smtp_usuario' => $config->obtener('smtp_usuario', ''),
    'smtp_password' => $config->obtener('smtp_password', ''),
    'smtp_de' => $config->obtener('smtp_de', ''),
    'mantenimiento_activo' => $config->obtener('mantenimiento_activo', false),
    'mantenimiento_mensaje' => $config->obtener('mantenimiento_mensaje', ''),
    
    // MercadoPago
    'mp_environment' => $config->obtener('mp_environment', 'production'),
    'mp_ar_sandbox_public_key' => $config->obtener('mp_ar_sandbox_public_key', ''),
    'mp_ar_sandbox_access_token' => $config->obtener('mp_ar_sandbox_access_token', ''),
    'mp_ar_production_public_key' => $config->obtener('mp_ar_production_public_key', ''),
    'mp_ar_production_access_token' => $config->obtener('mp_ar_production_access_token', ''),
    'mp_br_sandbox_public_key' => $config->obtener('mp_br_sandbox_public_key', ''),
    'mp_br_sandbox_access_token' => $config->obtener('mp_br_sandbox_access_token', ''),
    'mp_br_production_public_key' => $config->obtener('mp_br_production_public_key', ''),
    'mp_br_production_access_token' => $config->obtener('mp_br_production_access_token', ''),
    
    // OpenPix (PIX)
    'openpix_app_id' => $config->obtener('openpix_app_id', ''),
    'openpix_api_key' => $config->obtener('openpix_api_key', ''),
    
    // PayPal
    'paypal_environment' => $config->obtener('paypal_environment', 'production'),
    'paypal_client_id_brl' => $config->obtener('paypal_client_id_brl', ''),
    'paypal_client_secret_brl' => $config->obtener('paypal_client_secret_brl', ''),
    'paypal_client_id_usd' => $config->obtener('paypal_client_id_usd', ''),
    'paypal_client_secret_usd' => $config->obtener('paypal_client_secret_usd', ''),
    
    // Parámetros
    'parametros_head' => $config->obtener('parametros_head', ''),
    'parametros_body' => $config->obtener('parametros_body', '')
];
?>

<!-- Content Wrapper -->
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">
                        <i class="fas fa-cog"></i> Configuración del Sistema
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item active">Configuración</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <?php echo $mensaje; ?>
            
            <form method="post" class="form-horizontal">
                
                <!-- Nav Tabs -->
                <ul class="nav nav-tabs nav-fill" id="configTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="general-tab" data-toggle="tab" href="#general" role="tab">
                            <i class="fas fa-info-circle"></i> General
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="oauth-tab" data-toggle="tab" href="#oauth" role="tab">
                            <i class="fas fa-key"></i> OAuth
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="email-tab" data-toggle="tab" href="#email" role="tab">
                            <i class="fas fa-envelope"></i> Email
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="seguridad-tab" data-toggle="tab" href="#seguridad" role="tab">
                            <i class="fas fa-shield-alt"></i> Seguridad
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pasarelas-tab" data-toggle="tab" href="#pasarelas" role="tab">
                            <i class="fas fa-credit-card"></i> Pasarelas de Pago
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="parametros-tab" data-toggle="tab" href="#parametros" role="tab">
                            <i class="fas fa-code"></i> Parámetros
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="mantenimiento-tab" data-toggle="tab" href="#mantenimiento" role="tab">
                            <i class="fas fa-exclamation-triangle"></i> Mantenimiento
                        </a>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="configTabContent" style="border: 1px solid #dee2e6; border-top: none; padding: 20px;">
                    
                    <!-- General Tab -->
                    <div class="tab-pane fade show active" id="general" role="tabpanel">
                        <div class="card">
                            <div class="card-header bg-light">
                                <h5 class="card-title">Información General del Sitio</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Nombre del Sitio</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="sitio_nombre" value="<?=$config_data['sitio_nombre']?>" placeholder="MeteleBrasil" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Email Principal</label>
                                    <div class="col-sm-9">
                                        <input type="email" class="form-control" name="sitio_email" value="<?=$config_data['sitio_email']?>" placeholder="info@metelebrasil.com">
                                        <small class="form-text text-muted">Email de contacto principal del sitio</small>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Teléfono Principal</label>
                                    <div class="col-sm-9">
                                        <input type="tel" class="form-control" name="sitio_telefono" value="<?=$config_data['sitio_telefono']?>" placeholder="+55 11 9999-9999">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- OAuth Tab -->
                    <div class="tab-pane fade" id="oauth" role="tabpanel">
                        <!-- Google OAuth -->
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <h5 class="card-title"><i class="fab fa-google"></i> Google OAuth 2.0</h5>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info">
                                    <strong>Obtener credenciales:</strong>
                                    <ol>
                                        <li>Ir a <a href="https://console.developers.google.com" target="_blank">Google Cloud Console</a></li>
                                        <li>Crear proyecto: "MeteleBrasil"</li>
                                        <li>Habilitar API: Google+ API</li>
                                        <li>Crear credenciales: OAuth 2.0 Client ID</li>
                                        <li>Autorizar URI: <code><?php echo $_SERVER['HTTP_HOST']; ?>/admin/configuracion.php</code></li>
                                    </ol>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Client ID</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="google_client_id" value="<?=$config_data['google_client_id']?>" placeholder="xxx.apps.googleusercontent.com">
                                        <small class="form-text text-muted">ID único de la aplicación Google</small>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Client Secret</label>
                                    <div class="col-sm-9">
                                        <input type="password" class="form-control" name="google_client_secret" value="<?=$config_data['google_client_secret']?>" placeholder="GOCSPX-...">
                                        <small class="form-text text-muted">Clave secreta (mantén confidencial)</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Facebook OAuth -->
                        <div class="card">
                            <div class="card-header bg-light">
                                <h5 class="card-title"><i class="fab fa-facebook"></i> Facebook OAuth 2.0</h5>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info">
                                    <strong>Obtener credenciales:</strong>
                                    <ol>
                                        <li>Ir a <a href="https://developers.facebook.com/apps" target="_blank">Facebook Developers</a></li>
                                        <li>Crear App → tipo: "Consumer"</li>
                                        <li>Agregar producto: "Facebook Login"</li>
                                        <li>En Configuración → URI de redirección válidos</li>
                                    </ol>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">App ID</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="facebook_app_id" value="<?=$config_data['facebook_app_id']?>" placeholder="123456789">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">App Secret</label>
                                    <div class="col-sm-9">
                                        <input type="password" class="form-control" name="facebook_app_secret" value="<?=$config_data['facebook_app_secret']?>" placeholder="abc123...">
                                        <small class="form-text text-muted">Clave secreta (mantén confidencial)</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Email Tab -->
                    <div class="tab-pane fade" id="email" role="tabpanel">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> <strong>Gestión de plantillas de email:</strong> 
                            Puedes personalizar los emails del sistema (reserva confirmada, pendiente, recuperar contraseña, etc.) en diferentes idiomas.
                            <a href="email_templates.php" class="btn btn-sm btn-primary ml-2">
                                <i class="fas fa-envelope-open-text"></i> Editar plantillas de email
                            </a>
                        </div>

                        <div class="card">
                            <div class="card-header bg-light">
                                <h5 class="card-title"><i class="fas fa-envelope"></i> Configuración SMTP</h5>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-warning">
                                    <strong>Ejemplo Gmail:</strong>
                                    <ul class="mb-0">
                                        <li><strong>Host:</strong> smtp.gmail.com</li>
                                        <li><strong>Puerto:</strong> 587 (TLS) o 465 (SSL)</li>
                                        <li><strong>Usuario:</strong> tu_email@gmail.com</li>
                                        <li><strong>Password:</strong> <a href="https://support.google.com/accounts/answer/185833" target="_blank">Contraseña de Aplicación</a></li>
                                    </ul>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Host SMTP</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="smtp_host" value="<?=$config_data['smtp_host']?>" placeholder="smtp.gmail.com">
                                        <small class="form-text text-muted">Servidor SMTP (ej: smtp.gmail.com)</small>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Puerto</label>
                                    <div class="col-sm-9">
                                        <input type="number" class="form-control" name="smtp_port" value="<?=$config_data['smtp_port']?>" placeholder="587" min="1" max="65535">
                                        <small class="form-text text-muted">587 (TLS) o 465 (SSL)</small>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Usuario/Email</label>
                                    <div class="col-sm-9">
                                        <input type="email" class="form-control" name="smtp_usuario" value="<?=$config_data['smtp_usuario']?>" placeholder="tu_email@gmail.com">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Contraseña</label>
                                    <div class="col-sm-9">
                                        <input type="password" class="form-control" name="smtp_password" value="<?=$config_data['smtp_password']?>" placeholder="••••••••">
                                        <small class="form-text text-muted">Para Gmail usa Contraseña de Aplicación</small>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Remitente (De)</label>
                                    <div class="col-sm-9">
                                        <input type="email" class="form-control" name="smtp_de" value="<?=$config_data['smtp_de']?>" placeholder="noreply@metelebrasil.com">
                                        <small class="form-text text-muted">Email que aparecerá en el "De" de los emails enviados</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header bg-light">
                                <h5 class="card-title"><i class="fas fa-paper-plane"></i> Enviar correo de prueba</h5>
                            </div>
                            <div class="card-body">
                                <p>Redacta un correo de prueba con CKEditor para validar el estilo y la configuración SMTP.</p>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Para</label>
                                    <div class="col-sm-9">
                                        <input type="email" class="form-control" name="test_email_para" value="<?=htmlspecialchars($test_email_data['para'])?>" placeholder="tu_email@dominio.com">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Asunto</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="test_email_asunto" value="<?=htmlspecialchars($test_email_data['asunto'])?>" placeholder="Prueba SMTP">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Contenido</label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control" id="test_email_html" name="test_email_html"><?=htmlspecialchars($test_email_data['html'])?></textarea>
                                        <small class="form-text text-muted">Usa el editor para dar estilo al email de prueba.</small>
                                    </div>
                                </div>
                                <button type="submit" name="enviar_test_email" value="1" class="btn btn-success">
                                    <i class="fas fa-paper-plane"></i> Enviar prueba
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Seguridad Tab -->
                    <div class="tab-pane fade" id="seguridad" role="tabpanel">
                        <div class="card">
                            <div class="card-header bg-light">
                                <h5 class="card-title"><i class="fas fa-shield-alt"></i> reCAPTCHA v3</h5>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-success">
                                    <strong>Obtener credenciales:</strong>
                                    <ol class="mb-0">
                                        <li>Ir a <a href="https://www.google.com/recaptcha/admin" target="_blank">Google reCAPTCHA Console</a></li>
                                        <li>Click en "+" para crear sitio</li>
                                        <li>Label: "MeteleBrasil"</li>
                                        <li>reCAPTCHA type: <strong>reCAPTCHA v3</strong></li>
                                        <li>Dominios: metelebrasil.com, localhost</li>
                                    </ol>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Site Key</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="recaptcha_site_key" value="<?=$config_data['recaptcha_site_key']?>" placeholder="6Le...">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Secret Key</label>
                                    <div class="col-sm-9">
                                        <input type="password" class="form-control" name="recaptcha_secret_key" value="<?=$config_data['recaptcha_secret_key']?>" placeholder="6Le...">
                                        <small class="form-text text-muted">Clave secreta (mantén confidencial)</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



                    <!-- Pasarelas de Pago Tab -->
                    <div class="tab-pane fade" id="pasarelas" role="tabpanel">
                        <!-- Selector Entorno MercadoPago -->
                        <div class="alert alert-warning">
                            <h5><i class="fas fa-globe"></i> Entorno MercadoPago</h5>
                            <p class="mb-2">Selecciona el entorno activo para todas las operaciones de MercadoPago:</p>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="mp_environment" id="mp_env_sandbox" value="sandbox" <?=$config_data['mp_environment'] === 'sandbox' ? 'checked' : ''?>>
                                <label class="form-check-label" for="mp_env_sandbox">
                                    <span class="badge badge-warning">SANDBOX (Pruebas)</span>
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="mp_environment" id="mp_env_production" value="production" <?=$config_data['mp_environment'] === 'production' ? 'checked' : ''?>>
                                <label class="form-check-label" for="mp_env_production">
                                    <span class="badge badge-success">PRODUCTION (Producción)</span>
                                </label>
                            </div>
                        </div>

                        <!-- MercadoPago Argentina -->
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <h5 class="card-title">
                                    <img src="https://http2.mlstatic.com/frontend-assets/ui-navigation/5.18.5/mercadolibre/logo__large_plus.png" height="20" alt="MercadoPago">
                                    MercadoPago Argentina
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info">
                                    <strong>Obtener credenciales:</strong>
                                    <ol class="mb-0">
                                        <li>Ir a <a href="https://www.mercadopago.com.ar/developers/panel" target="_blank">Panel de Desarrolladores MercadoPago AR</a></li>
                                        <li>Aplicaciones → Crear aplicación</li>
                                        <li>Copiar Public Key y Access Token de Sandbox y Producción</li>
                                    </ol>
                                </div>
                                
                                <h6 class="text-warning"><i class="fas fa-vial"></i> Sandbox (Pruebas)</h6>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Public Key</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="mp_ar_sandbox_public_key" value="<?=htmlspecialchars($config_data['mp_ar_sandbox_public_key'])?>" placeholder="TEST-...">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Access Token</label>
                                    <div class="col-sm-9">
                                        <input type="password" class="form-control" name="mp_ar_sandbox_access_token" value="<?=htmlspecialchars($config_data['mp_ar_sandbox_access_token'])?>" placeholder="TEST-...">
                                    </div>
                                </div>
                                
                                <hr class="my-4">
                                
                                <h6 class="text-success"><i class="fas fa-check-circle"></i> Producción</h6>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Public Key</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="mp_ar_production_public_key" value="<?=htmlspecialchars($config_data['mp_ar_production_public_key'])?>" placeholder="APP_USR-...">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Access Token</label>
                                    <div class="col-sm-9">
                                        <input type="password" class="form-control" name="mp_ar_production_access_token" value="<?=htmlspecialchars($config_data['mp_ar_production_access_token'])?>" placeholder="APP_USR-...">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- MercadoPago Brasil -->
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <h5 class="card-title">
                                    <img src="https://http2.mlstatic.com/frontend-assets/ui-navigation/5.18.5/mercadolibre/logo__large_plus.png" height="20" alt="MercadoPago">
                                    MercadoPago Brasil
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info">
                                    <strong>Obtener credenciales:</strong>
                                    <ol class="mb-0">
                                        <li>Ir a <a href="https://www.mercadopago.com.br/developers/panel" target="_blank">Panel de Desenvolvolvimento MercadoPago BR</a></li>
                                        <li>Aplicações → Criar aplicação</li>
                                        <li>Copiar Public Key e Access Token de Sandbox e Produção</li>
                                    </ol>
                                </div>
                                
                                <h6 class="text-warning"><i class="fas fa-vial"></i> Sandbox (Testes)</h6>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Public Key</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="mp_br_sandbox_public_key" value="<?=htmlspecialchars($config_data['mp_br_sandbox_public_key'])?>" placeholder="TEST-...">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Access Token</label>
                                    <div class="col-sm-9">
                                        <input type="password" class="form-control" name="mp_br_sandbox_access_token" value="<?=htmlspecialchars($config_data['mp_br_sandbox_access_token'])?>" placeholder="TEST-...">
                                    </div>
                                </div>
                                
                                <hr class="my-4">
                                
                                <h6 class="text-success"><i class="fas fa-check-circle"></i> Produção</h6>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Public Key</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="mp_br_production_public_key" value="<?=htmlspecialchars($config_data['mp_br_production_public_key'])?>" placeholder="APP_USR-...">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Access Token</label>
                                    <div class="col-sm-9">
                                        <input type="password" class="form-control" name="mp_br_production_access_token" value="<?=htmlspecialchars($config_data['mp_br_production_access_token'])?>" placeholder="APP_USR-...">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- PayPal -->
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <h5 class="card-title">
                                    <img src="https://www.paypalobjects.com/webstatic/en_US/i/buttons/pp-acceptance-small.png" height="20" alt="PayPal">
                                    PayPal
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info">
                                    <strong>Obtener credenciales:</strong>
                                    <ol class="mb-0">
                                        <li>Ir a <a href="https://developer.paypal.com/dashboard/" target="_blank">PayPal Developer Dashboard</a></li>
                                        <li>My Apps & Credentials → Create App</li>
                                        <li>Copiar Client ID y Secret para Sandbox y Live</li>
                                    </ol>
                                </div>
                                
                                <div class="alert alert-warning mb-3">
                                    <h5><i class="fas fa-globe"></i> Entorno PayPal</h5>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="paypal_environment" id="paypal_env_sandbox" value="sandbox" <?=$config_data['paypal_environment'] === 'sandbox' ? 'checked' : ''?>>
                                        <label class="form-check-label" for="paypal_env_sandbox">
                                            <span class="badge badge-warning">SANDBOX (Pruebas)</span>
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="paypal_environment" id="paypal_env_production" value="production" <?=$config_data['paypal_environment'] === 'production' ? 'checked' : ''?>>
                                        <label class="form-check-label" for="paypal_env_production">
                                            <span class="badge badge-success">PRODUCTION (Producción)</span>
                                        </label>
                                    </div>
                                </div>
                                
                                <h6 class="text-primary"><i class="fas fa-flag-checkered"></i> BRL (Brasil)</h6>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Client ID</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="paypal_client_id_brl" value="<?=htmlspecialchars($config_data['paypal_client_id_brl'])?>" placeholder="AcfLam...">
                                        <small class="form-text text-muted">Para pagos en Reales</small>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Client Secret</label>
                                    <div class="col-sm-9">
                                        <input type="password" class="form-control" name="paypal_client_secret_brl" value="<?=htmlspecialchars($config_data['paypal_client_secret_brl'])?>" placeholder="EMk...">
                                    </div>
                                </div>
                                
                                <hr class="my-4">
                                
                                <h6 class="text-success"><i class="fas fa-dollar-sign"></i> USD (Internacional)</h6>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Client ID</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="paypal_client_id_usd" value="<?=htmlspecialchars($config_data['paypal_client_id_usd'])?>" placeholder="AcekW...">
                                        <small class="form-text text-muted">Para pagos en Dólares, Pesos, Guaraníes, etc.</small>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Client Secret</label>
                                    <div class="col-sm-9">
                                        <input type="password" class="form-control" name="paypal_client_secret_usd" value="<?=htmlspecialchars($config_data['paypal_client_secret_usd'])?>" placeholder="EPv...">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- OpenPix (PIX) -->
                        <div class="card">
                            <div class="card-header bg-light">
                                <h5 class="card-title">
                                    <i class="fas fa-qrcode"></i> OpenPix (PIX Brasil)
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info">
                                    <strong>Obtener credenciales:</strong>
                                    <ol class="mb-0">
                                        <li>Ir a <a href="https://openpix.com.br" target="_blank">OpenPix</a></li>
                                        <li>Criar conta e validar empresa</li>
                                        <li>Em API/Plugins → Obter App ID e API Key</li>
                                    </ol>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">App ID</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="openpix_app_id" value="<?=htmlspecialchars($config_data['openpix_app_id'])?>" placeholder="630d0002a81a3d5d7027bdd0">
                                        <small class="form-text text-muted">ID da aplicação OpenPix</small>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">API Key</label>
                                    <div class="col-sm-9">
                                        <input type="password" class="form-control" name="openpix_api_key" value="<?=htmlspecialchars($config_data['openpix_api_key'])?>" placeholder="Q2hhcmdlOj...">
                                        <small class="form-text text-muted">Chave secreta da API (mantém confidencial)</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Parámetros Tab -->
                    <div class="tab-pane fade" id="parametros" role="tabpanel">
                        <div class="card">
                            <div class="card-header bg-light">
                                <h5 class="card-title"><i class="fas fa-code"></i> Inyección de Código</h5>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i> <strong>Advertencia:</strong> 
                                    Este código se inyectará en todas las páginas del sitio. Asegúrate de que el código sea válido para evitar errores.
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Código en &lt;head&gt;</label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control" id="parametros_head" name="parametros_head" rows="8"></textarea>
                                        <small class="form-text text-muted">
                                            Scripts, meta tags, CSS, Google Analytics, Facebook Pixel, etc.
                                        </small>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Código en &lt;body&gt;</label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control" id="parametros_body" name="parametros_body" rows="8"></textarea>
                                        <small class="form-text text-muted">
                                            Scripts de seguimiento, widgets, chat en vivo, etc.
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Mantenimiento Tab -->
                    <div class="tab-pane fade" id="mantenimiento" role="tabpanel">
                        <div class="card">
                            <div class="card-header bg-light">
                                <h5 class="card-title"><i class="fas fa-exclamation-triangle"></i> Modo Mantenimiento</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Estado</label>
                                    <div class="col-sm-9">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" name="mantenimiento_activo" id="switch_mantenimiento" <?=$config_data['mantenimiento_activo'] ? 'checked' : ''?>>
                                            <label class="custom-control-label" for="switch_mantenimiento">
                                                Activar modo mantenimiento
                                                <span class="badge badge-<?=$config_data['mantenimiento_activo'] ? 'danger' : 'success'?> ml-2">
                                                    <?=$config_data['mantenimiento_activo'] ? 'ACTIVO' : 'INACTIVO'?>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Mensaje</label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control" name="mantenimiento_mensaje" rows="4" placeholder="El sitio está en mantenimiento. Regresa pronto..."><?=$config_data['mantenimiento_mensaje']?></textarea>
                                        <small class="form-text text-muted">Mensaje que verán los usuarios cuando mantenimiento esté activo</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Botones -->
                <div class="row mt-4">
                    <div class="col-12">
                        <button type="submit" name="guardar_config" class="btn btn-primary btn-lg">
                            <i class="fas fa-save"></i> Guardar Configuración
                        </button>
                        <a href="index.php" class="btn btn-secondary btn-lg">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>

            </form>
        </div>
    </section>
</div>

<style>
.nav-tabs .nav-link {
    color: #333;
    border: 1px solid #dee2e6;
    border-bottom: 2px solid #dee2e6;
    padding: 0.75rem 1.5rem;
}

.nav-tabs .nav-link:hover {
    background-color: #f8f9fa;
}

.nav-tabs .nav-link.active {
    color: #fff;
    background-color: #007bff;
    border-color: #007bff;
    border-bottom-color: #007bff;
}

.card-header.bg-light {
    background-color: #f8f9fa !important;
    border-bottom: 2px solid #007bff;
}

.card-header.bg-light .card-title {
    margin: 0;
    color: #333;
    font-weight: 600;
}

.tab-content {
    background-color: #fff;
}

.form-group row {
    margin-bottom: 1.5rem;
}
</style>

<link href="plugins/summernote/summernote.css" rel="stylesheet">
<script src="plugins/summernote/summernote.min.js"></script>
<script>
    $(document).ready(function() {
        // Debug del formulario
        $('form').on('submit', function(e) {
            $('.nav-tabs a[href="' + window.location.hash + '"]').tab('show');
        }
        
        // Actualizar hash al cambiar de pestaña
        $('.nav-tabs a').on('shown.bs.tab', function (e) {
            window.location.hash = e.target.hash;
        });
        
        // Summernote para test email
        $('#test_email_html').summernote({
            height: 220,
            toolbar: [
                ['style', ['bold', 'italic', 'underline']],
                ['para', ['ul', 'ol']],
                ['insert', ['link']],
                ['view', ['codeview']]
            ]
        });
        
        // Summernote para parámetros HEAD
        var parametros_head_content = <?=json_encode($config_data['parametros_head'])?>;
        $('#parametros_head').val(parametros_head_content);
        
        // Summernote para parámetros BODY
        var parametros_body_content = <?=json_encode($config_data['parametros_body'])?>;
        $('#parametros_body').val(parametros_body_content);
    });
</script>

<?php include("includes/footer.php"); ?>
