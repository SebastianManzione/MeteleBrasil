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

$h = $payload['head_b64'] ?? '';
$b = $payload['body_b64'] ?? '';
$f = $payload['footer_b64'] ?? '';

$decode = function($x){ $d = base64_decode($x, true); return $d === false ? '' : substr($d, 0, 200000); };

$logline = date('c') . " SECURE_PARAMS_SAVE by user " . ($_SESSION['login']['idUsuario'] ?? 'N/A') . " CT=" . ($_SERVER['CONTENT_TYPE'] ?? '') . "\n";
et_write_log($logline);

$config = new Configuracion();
try {
    $config->guardar('parametros_head', $decode($h), 'text', 'Código Head');
    $config->guardar('parametros_body', $decode($b), 'text', 'Código Body');
    $config->guardar('parametros_footer', $decode($f), 'text', 'Código Footer');
    echo json_encode(['ok' => true, 'message' => 'Parámetros guardados correctamente']);
} catch (Exception $e) {
    echo json_encode(['ok' => false, 'message' => 'Error al guardar: ' . $e->getMessage()]);
}
exit;
