<?php
/**
 * Configuración de Google reCAPTCHA v3
 * NOTA: Las claves ahora se recuperan de la BD vía admin/configuracion.php
 * Obtén tus claves en: https://www.google.com/recaptcha/admin
 */

// Cargar configuración desde BD
require_once(__DIR__ . '/../admin/classes/conexion.php');
require_once(__DIR__ . '/../admin/classes/configuracion.php');

try {
    $config = new Configuracion();
    
    // Obtener claves de reCAPTCHA desde BD (con fallback a TEST KEYS)
    define('RECAPTCHA_SITE_KEY', $config->obtener('recaptcha_site_key', '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI'));
    define('RECAPTCHA_SECRET_KEY', $config->obtener('recaptcha_secret_key', '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe'));
} catch (Exception $e) {
    // Si hay error en BD, usar TEST KEYS como fallback
    define('RECAPTCHA_SITE_KEY', '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI');
    define('RECAPTCHA_SECRET_KEY', '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe');
}

// Umbral de score de reCAPTCHA (0.0 a 1.0, donde 1.0 es muy probablemente humano)
define('RECAPTCHA_SCORE_THRESHOLD', 0.5);

/**
 * Verifica el token de reCAPTCHA v3
 * @param string $token Token generado por reCAPTCHA en el frontend
 * @param string $action Acción específica del formulario (contact_form, register_form, etc)
 * @return array ['success' => bool, 'score' => float, 'action' => string, 'error' => string]
 */
function verificarRecaptcha($token, $action = 'submit') {
    if (empty($token)) {
        return [
            'success' => false,
            'score' => 0,
            'error' => 'Token reCAPTCHA vacío'
        ];
    }

    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = [
        'secret' => RECAPTCHA_SECRET_KEY,
        'response' => $token,
        'remoteip' => $_SERVER['REMOTE_ADDR']
    ];

    $options = [
        'http' => [
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data)
        ]
    ];

    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    
    if ($result === FALSE) {
        return [
            'success' => false,
            'score' => 0,
            'error' => 'Error al conectar con reCAPTCHA'
        ];
    }

    $resultJson = json_decode($result, true);
    
    // Verificar que el action coincide (previene reuso de tokens)
    if (isset($resultJson['action']) && $resultJson['action'] !== $action) {
        return [
            'success' => false,
            'score' => 0,
            'error' => 'Acción inválida'
        ];
    }

    // Verificar el score
    $score = isset($resultJson['score']) ? $resultJson['score'] : 0;
    $success = $resultJson['success'] && $score >= RECAPTCHA_SCORE_THRESHOLD;

    return [
        'success' => $success,
        'score' => $score,
        'action' => $resultJson['action'] ?? '',
        'error' => !$success ? 'Score muy bajo: ' . $score : ''
    ];
}
