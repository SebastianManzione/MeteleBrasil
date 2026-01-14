<?php
// Endpoint AJAX para enviar email de prueba sin enviar todo el formulario
if (session_status() === PHP_SESSION_NONE) session_start();

header('Content-Type: application/json; charset=UTF-8');

// Solo POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'message' => 'Método no permitido']);
    exit;
}

// Verificar login y permisos (solo admin id=1)
if (!isset($_SESSION['login']['idUsuario']) || (int)$_SESSION['login']['idUsuario'] !== 1) {
    http_response_code(403);
    echo json_encode(['ok' => false, 'message' => 'Forbidden']);
    exit;
}

require_once __DIR__ . '/../classes/reservaEmail.php';

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

// Permitir JSON (application/json) para reducir disparadores del WAF
$raw = file_get_contents('php://input');
$json = null;
if (!empty($_SERVER['CONTENT_TYPE']) && stripos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
    $json = json_decode($raw, true);
}

$para = '';
$asunto = 'Prueba SMTP MeteleBrasil';
$html = '';

if (is_array($json)) {
    $para = isset($json['to']) ? trim($json['to']) : '';
    $asunto = isset($json['sub']) ? trim($json['sub']) : $asunto;
    if (!empty($json['b64'])) {
        $decoded = base64_decode($json['b64'], true);
        if ($decoded !== false) {
            // Limitar tamaño para evitar abusos
            $html = substr($decoded, 0, 200000);
        }
    } elseif (isset($json['html'])) {
        $html = $json['html'];
    }
} else {
    // Fallback a x-www-form-urlencoded
    $para = isset($_POST['para']) ? trim($_POST['para']) : '';
    $asunto = isset($_POST['asunto']) ? trim($_POST['asunto']) : $asunto;
    $html = isset($_POST['html']) ? $_POST['html'] : '';
}

if ($para === '' || !filter_var($para, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['ok' => false, 'message' => 'Debes indicar un email válido para la prueba.']);
    exit;
}

// Enviar
$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
$logline = date('c') . " CTRL_EMAIL_TEST hit by user " . ($_SESSION['login']['idUsuario'] ?? 'N/A') . " CT=" . ($_SERVER['CONTENT_TYPE'] ?? '') . "\n";
et_write_log($logline);
$resp = enviaMail($para, $asunto, $html, $host);

$ok = stripos($resp, 'correctamente') !== false;
echo json_encode(['ok' => $ok, 'message' => $resp]);
exit;
?>
