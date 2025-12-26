<?php
/**
 * Sistema Anti-Bot con múltiples capas de protección
 * - Honeypot (campo trampa)
 * - Rate limiting (límite por IP)
 * - Timestamp validation (tiempo mínimo de llenado)
 */

require_once(__DIR__ . "/../../config/recaptcha.php");
require_once(__DIR__ . "/conexion.php");

/**
 * Verifica honeypot field (campo trampa que debe estar vacío)
 * Los bots generalmente llenan todos los campos, los humanos no ven este campo
 */
function verificarHoneypot($fieldName = 'website') {
    // Si el campo está presente y NO está vacío, es un bot
    if (isset($_POST[$fieldName]) && !empty($_POST[$fieldName])) {
        registrarIntentoBot('honeypot', 'Campo trampa llenado: ' . $fieldName);
        return false;
    }
    return true;
}

/**
 * Verifica rate limiting por IP
 * @param string $action Acción del formulario (contact, register, etc)
 * @param int $maxAttempts Intentos máximos permitidos
 * @param int $timeWindow Ventana de tiempo en segundos (default: 1 hora)
 * @return bool true si está permitido, false si excede el límite
 */
function verificarRateLimit($action, $maxAttempts = 5, $timeWindow = 3600) {
    $ip = obtenerIPReal();
    $pdo = $GLOBALS['pdo'] ?? null;
    
    if (!$pdo) {
        return true; // Si no hay BD, permitir el acceso
    }
    
    try {
        // Crear tabla si no existe
        $sql = "CREATE TABLE IF NOT EXISTS form_rate_limit (
            id INT AUTO_INCREMENT PRIMARY KEY,
            ip_address VARCHAR(45) NOT NULL,
            action VARCHAR(50) NOT NULL,
            attempt_time DATETIME NOT NULL,
            INDEX idx_ip_action (ip_address, action),
            INDEX idx_time (attempt_time)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        
        $pdo->exec($sql);

        // Limpiar intentos antiguos
        $stmt = $pdo->prepare("DELETE FROM form_rate_limit WHERE attempt_time < DATE_SUB(NOW(), INTERVAL ? SECOND)");
        $stmt->execute([$timeWindow]);

        // Contar intentos recientes
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM form_rate_limit 
                                WHERE ip_address = ? AND action = ? 
                                AND attempt_time > DATE_SUB(NOW(), INTERVAL ? SECOND)");
        $stmt->execute([$ip, $action, $timeWindow]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result['count'] >= $maxAttempts) {
            registrarIntentoBot('rate_limit', "Exceso de intentos para $action: " . $result['count']);
            return false;
        }

        // Registrar este intento
        $stmt = $pdo->prepare("INSERT INTO form_rate_limit (ip_address, action, attempt_time) VALUES (?, ?, NOW())");
        $stmt->execute([$ip, $action]);
        
        return true;
    } catch (Exception $e) {
        return true; // Si hay error, permitir acceso
    }
}

/**
 * Verifica tiempo mínimo de llenado del formulario
 * @param int $minSeconds Segundos mínimos para llenar el formulario
 * @return bool true si pasó suficiente tiempo
 */
function verificarTiempoMinimo($minSeconds = 3) {
    if (!isset($_POST['form_timestamp']) || empty($_POST['form_timestamp'])) {
        return true; // No fallar si no está el campo (backward compatibility)
    }
    
    $startTime = intval($_POST['form_timestamp']);
    $currentTime = time();
    $elapsed = $currentTime - $startTime;
    
    // Si llenó el formulario demasiado rápido, probablemente es un bot
    if ($elapsed < $minSeconds) {
        registrarIntentoBot('tiempo_rapido', "Formulario llenado en $elapsed segundos");
        return false;
    }
    
    return true;
}

/**
 * Validación completa anti-bot (todas las capas)
 * @param string $action Acción del formulario
 * @param string $recaptchaToken Token de reCAPTCHA v3
 * @return array ['success' => bool, 'error' => string]
 */
function validarAntiBot($action, $recaptchaToken = null) {
    // 1. Verificar honeypot
    if (!verificarHoneypot()) {
        return [
            'success' => false,
            'error' => 'Validación de seguridad fallida (H)'
        ];
    }

    // 2. Verificar tiempo mínimo
    if (!verificarTiempoMinimo(3)) {
        return [
            'success' => false,
            'error' => 'Por favor, tómate un momento para completar el formulario'
        ];
    }

    // 3. Verificar rate limiting
    if (!verificarRateLimit($action, 5, 3600)) {
        return [
            'success' => false,
            'error' => 'Has excedido el límite de intentos. Intenta nuevamente en 1 hora'
        ];
    }

    // 4. Verificar reCAPTCHA v3
    if (!empty($recaptchaToken)) {
        $recaptchaResult = verificarRecaptcha($recaptchaToken, $action);
        if (!$recaptchaResult['success']) {
            registrarIntentoBot('recaptcha', $recaptchaResult['error'] . ' (Score: ' . $recaptchaResult['score'] . ')');
            return [
                'success' => false,
                'error' => 'Validación de seguridad fallida. Si eres humano, intenta de nuevo'
            ];
        }
    }

    return ['success' => true, 'error' => ''];
}

/**
 * Obtiene la IP real del usuario (considera proxies)
 */
function obtenerIPReal() {
    $ip = $_SERVER['REMOTE_ADDR'];
    
    // Verificar si viene detrás de un proxy
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
    }
    
    return filter_var($ip, FILTER_VALIDATE_IP) ? $ip : $_SERVER['REMOTE_ADDR'];
}

/**
 * Registra intentos de bot para análisis
 */
function registrarIntentoBot($tipo, $detalles) {
    $pdo = $GLOBALS['pdo'] ?? null;
    
    if (!$pdo) {
        return; // Si no hay BD, no registrar
    }
    
    try {
        // Crear tabla si no existe
        $sql = "CREATE TABLE IF NOT EXISTS bot_attempts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            ip_address VARCHAR(45) NOT NULL,
            tipo VARCHAR(50) NOT NULL,
            detalles TEXT,
            user_agent TEXT,
            attempt_time DATETIME NOT NULL,
            INDEX idx_ip (ip_address),
            INDEX idx_time (attempt_time)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        
        $pdo->exec($sql);

        $ip = obtenerIPReal();
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        
        $stmt = $pdo->prepare("INSERT INTO bot_attempts (ip_address, tipo, detalles, user_agent, attempt_time) 
                               VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([$ip, $tipo, $detalles, $userAgent]);
    } catch (Exception $e) {
        // Silenciar errores de BD
    }
}

/**
 * Genera el HTML del campo honeypot (incluir en formularios)
 */
function generarHoneypot() {
    return '<input type="text" name="website" value="" style="position:absolute;left:-5000px;" tabindex="-1" autocomplete="off" aria-hidden="true">';
}

/**
 * Genera el campo timestamp oculto (incluir en formularios)
 */
function generarTimestamp() {
    return '<input type="hidden" name="form_timestamp" value="' . time() . '">';
}

/**
 * Genera todos los campos anti-bot necesarios
 */
function generarCamposAntiBot() {
    return generarHoneypot() . generarTimestamp();
}
