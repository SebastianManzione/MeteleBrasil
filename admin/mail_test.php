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

require_once __DIR__ . '/classes/reservaEmail.php';

function et_write_log($msg) {
    $paths = [
        __DIR__ . '/../logs/email_test.log',
        __DIR__ . '/email_test.log'
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

$to = isset($payload['to']) ? trim($payload['to']) : '';
$sub = isset($payload['sub']) ? trim($payload['sub']) : 'Prueba SMTP MeteleBrasil';
$b64 = isset($payload['b64']) ? $payload['b64'] : '';

if ($to === '' || !filter_var($to, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['ok' => false, 'message' => 'Debes indicar un email válido para la prueba.']);
    exit;
}

$html = '';
if ($b64 !== '') {
    $decoded = base64_decode($b64, true);
    if ($decoded === false) {
        echo json_encode(['ok' => false, 'message' => 'Contenido inválido (base64)']);
        exit;
    }
    $html = substr($decoded, 0, 200000);
}

$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
$logline = date('c') . " MAIL_TEST hit by user " . ($_SESSION['login']['idUsuario'] ?? 'N/A') . " CT=" . ($_SERVER['CONTENT_TYPE'] ?? '') . "\n";
et_write_log($logline);

$resp = enviaMail($to, $sub, $html, $host);
$ok = stripos($resp, 'correctamente') !== false;

echo json_encode(['ok' => $ok, 'message' => $resp]);
exit;
