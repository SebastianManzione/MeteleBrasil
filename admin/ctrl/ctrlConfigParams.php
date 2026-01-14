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

require_once __DIR__ . '/../classes/configuracion.php';

function et_write_log($msg) {
    $paths = [
        __DIR__ . '/../../logs/email_test.log',
        __DIR__ . '/../email_test.log'
    ];
    foreach ($paths as $p) {
        $dir = dirname($p);
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
        if (@file_put_contents($p, $msg, FILE_APPEND) !== false) return true;
    }
    @error_log($msg);
    return false;
}

$raw = file_get_contents('php://input');
$payload = json_decode($raw, true);
if (!is_array($payload)) {
    echo json_encode(['ok' => false, 'message' => 'Payload inválido']);
    exit;
}

$head_b64 = $payload['head_b64'] ?? '';
$body_b64 = $payload['body_b64'] ?? '';
$footer_b64 = $payload['footer_b64'] ?? '';

// Decodificar base64 (máximo 200k por campo)
function safe_b64($b64) {
    if ($b64 === '') return '';
    $dec = base64_decode($b64, true);
    if ($dec === false) return '';
    return substr($dec, 0, 200000);
}

$head = safe_b64($head_b64);
$body = safe_b64($body_b64);
$footer = safe_b64($footer_b64);

$logline = date('c') . " SAVE_PARAMS hit by user " . ($_SESSION['login']['idUsuario'] ?? 'N/A') . " CT=" . ($_SERVER['CONTENT_TYPE'] ?? '') . "\n";
et_write_log($logline);

$config = new Configuracion();

try {
    $config->guardar('parametros_head', $head, 'text', 'Código Head');
    $config->guardar('parametros_body', $body, 'text', 'Código Body');
    $config->guardar('parametros_footer', $footer, 'text', 'Código Footer');
    echo json_encode(['ok' => true, 'message' => 'Parámetros guardados correctamente']);
} catch (Exception $e) {
    echo json_encode(['ok' => false, 'message' => 'Error al guardar: ' . $e->getMessage()]);
}
exit;
?>
