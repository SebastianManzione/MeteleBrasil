<?php
if (session_status() === PHP_SESSION_NONE) session_start();
header('Content-Type: application/json; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'message' => 'Método no permitido']);
    exit;
}

if (!isset($_SESSION['login']['idUsuario']) || (int)$_SESSION['login']['idUsuario'] !== 1) {
    http_response_code(403);
    echo json_encode(['ok' => false, 'message' => 'Forbidden']);
    exit;
}

require_once __DIR__ . '/classes/configuracion.php';

$raw = file_get_contents('php://input');
$data = json_decode($raw, true);
if (!is_array($data)) {
    echo json_encode(['ok' => false, 'message' => 'Payload inválido']);
    exit;
}

$cfg = new Configuracion();

function sv($cfg, $key, $type, $label, $data) {
    if (!array_key_exists($key, $data)) return;
    $val = $data[$key];
    if ($type === 'number') { $val = (int)$val; }
    if ($type === 'boolean') { $val = $val ? 1 : 0; }
    $cfg->guardar($key, $val, $type, $label);
}

try {
    // Replicar whitelist del endpoint en /ctrl/
    sv($cfg, 'sitio_nombre', 'string', 'Nombre del Sitio', $data);
    sv($cfg, 'sitio_email', 'string', 'Email del Sitio', $data);
    sv($cfg, 'sitio_telefono', 'string', 'Teléfono del Sitio', $data);

    sv($cfg, 'google_client_id', 'string', 'Google OAuth Client ID', $data);
    sv($cfg, 'google_client_secret', 'string', 'Google OAuth Client Secret', $data);
    sv($cfg, 'facebook_app_id', 'string', 'Facebook App ID', $data);
    sv($cfg, 'facebook_app_secret', 'string', 'Facebook App Secret', $data);

    sv($cfg, 'recaptcha_site_key', 'string', 'reCAPTCHA Site Key', $data);
    sv($cfg, 'recaptcha_secret_key', 'string', 'reCAPTCHA Secret Key', $data);

    sv($cfg, 'smtp_host', 'string', 'SMTP Host', $data);
    sv($cfg, 'smtp_port', 'number', 'SMTP Port', $data);
    sv($cfg, 'smtp_usuario', 'string', 'SMTP Usuario', $data);
    sv($cfg, 'smtp_password', 'string', 'SMTP Password', $data);
    sv($cfg, 'smtp_de', 'string', 'SMTP De (Remitente)', $data);

    sv($cfg, 'mp_environment', 'string', 'MercadoPago Entorno', $data);
    sv($cfg, 'mp_ar_sandbox_public_key', 'string', 'MP AR Sandbox Public Key', $data);
    sv($cfg, 'mp_ar_sandbox_access_token', 'string', 'MP AR Sandbox Access Token', $data);
    sv($cfg, 'mp_ar_production_public_key', 'string', 'MP AR Production Public Key', $data);
    sv($cfg, 'mp_ar_production_access_token', 'string', 'MP AR Production Access Token', $data);
    sv($cfg, 'mp_br_sandbox_public_key', 'string', 'MP BR Sandbox Public Key', $data);
    sv($cfg, 'mp_br_sandbox_access_token', 'string', 'MP BR Sandbox Access Token', $data);
    sv($cfg, 'mp_br_production_public_key', 'string', 'MP BR Production Public Key', $data);
    sv($cfg, 'mp_br_production_access_token', 'string', 'MP BR Production Access Token', $data);

    sv($cfg, 'openpix_app_id', 'string', 'OpenPix App ID', $data);
    sv($cfg, 'openpix_api_key', 'string', 'OpenPix API Key', $data);

    sv($cfg, 'paypal_environment', 'string', 'PayPal Entorno', $data);
    sv($cfg, 'paypal_client_id_brl', 'string', 'PayPal Client ID BRL', $data);
    sv($cfg, 'paypal_client_secret_brl', 'string', 'PayPal Client Secret BRL', $data);
    sv($cfg, 'paypal_client_id_usd', 'string', 'PayPal Client ID USD', $data);
    sv($cfg, 'paypal_client_secret_usd', 'string', 'PayPal Client Secret USD', $data);

    sv($cfg, 'index_categorias_iniciales', 'number', 'Categorías iniciales en index', $data);
    sv($cfg, 'index_categorias_ver_mas', 'number', 'Categorías al hacer Ver Más', $data);
    sv($cfg, 'index_servicios_iniciales', 'number', 'Servicios iniciales en index', $data);
    sv($cfg, 'index_servicios_ver_mas', 'number', 'Servicios al hacer Ver Más', $data);

    sv($cfg, 'mantenimiento_activo', 'boolean', 'Modo Mantenimiento Activo', $data);
    sv($cfg, 'mantenimiento_mensaje', 'string', 'Mensaje de Mantenimiento', $data);

    echo json_encode(['ok' => true, 'message' => 'Configuración guardada correctamente']);
} catch (Exception $e) {
    echo json_encode(['ok' => false, 'message' => 'Error al guardar: ' . $e->getMessage()]);
}
exit;
