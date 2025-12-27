<?php
/**
 * =========================================
 * BOOTSTRAP DE BASE DE DATOS CENTRALIZADO
 * =========================================
 * Archivo único para conexiones mysqli + PDO
 * Desarrollado: 2025-12-27
 * 
 * USO:
 *   require_once __DIR__ . '/db.php';
 *   // $pdo está disponible en $pdo y $GLOBALS['pdo']
 *   // $mysqli está disponible en $mysqli y $conection (legacy)
 */

// Protección contra inclusiones múltiples
// (ya que estamos usando require_once, esto es redundante pero seguro)
static $__once = false;
if ($__once) {
    return;
}
$__once = true;

// ========== CONFIGURACIÓN ==========
$DB_CHARSET = 'utf8mb4';

// Credenciales DEV (XAMPP defecto)
$DEV_HOST = '127.0.0.1';
$DEV_USER = 'root';
$DEV_PASS = '';
$DEV_NAME = 'metelebrasil';

// Credenciales PROD (override con env vars)
$PROD_HOST = getenv('DB_HOST') ?: 'localhost';
$PROD_USER = getenv('DB_USER') ?: 'u925692129_metelebrasil';
$PROD_PASS = getenv('DB_PASS') ?: 'Cambiar2026';
$PROD_NAME = getenv('DB_NAME') ?: 'u925692129_metelebrasil';

// ========== MYSQLI ==========
mysqli_report(MYSQLI_REPORT_OFF);

// Intenta DEV primero
$mysqli = @new mysqli($DEV_HOST, $DEV_USER, $DEV_PASS, $DEV_NAME);
if ($mysqli instanceof mysqli && !$mysqli->connect_errno) {
    @mysqli_set_charset($mysqli, $DB_CHARSET);
} else {
    // Fallback a PROD
    $mysqli = @new mysqli($PROD_HOST, $PROD_USER, $PROD_PASS, $PROD_NAME);
    if ($mysqli instanceof mysqli && !$mysqli->connect_errno) {
        @mysqli_set_charset($mysqli, $DB_CHARSET);
    } else {
        $err = $mysqli ? $mysqli->connect_error : 'unknown';
        @file_put_contents(__DIR__ . '/../../logs/db_bootstrap.log', date('c') . " [MYSQLI] Dev fail + Prod fail: {$err}\n", FILE_APPEND);
        $mysqli = null;
    }
}

// Alias para código legado
$conection = $mysqli;

// ========== PDO ==========
try {
    // Intenta DEV primero
    $dsn = "mysql:host={$DEV_HOST};dbname={$DEV_NAME};charset={$DB_CHARSET}";
    $pdo = new PDO($dsn, $DEV_USER, $DEV_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (Throwable $e_dev) {
    // Fallback a PROD
    try {
        $dsn = "mysql:host={$PROD_HOST};dbname={$PROD_NAME};charset={$DB_CHARSET}";
        $pdo = new PDO($dsn, $PROD_USER, $PROD_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    } catch (Throwable $e_prod) {
        @file_put_contents(__DIR__ . '/../../logs/db_bootstrap.log', date('c') . " [PDO] Dev: {$e_dev->getMessage()} | Prod: {$e_prod->getMessage()}\n", FILE_APPEND);
        $pdo = null;
    }
}

// Expone globalmente
if ($pdo instanceof PDO) {
    $GLOBALS['pdo'] = $pdo;
}
if ($mysqli instanceof mysqli && !$mysqli->connect_errno) {
    $GLOBALS['mysqli'] = $mysqli;
}
if ($conection instanceof mysqli) {
    $GLOBALS['conection'] = $conection;
}

// Crear función helper para acceder a PDO desde cualquier scope
if (!function_exists('getPDO')) {
    function getPDO() {
        global $pdo;
        return $pdo ?? $GLOBALS['pdo'] ?? null;
    }
}

// ========== VERIFICACIÓN ==========
if (!($pdo instanceof PDO) && !($mysqli instanceof mysqli)) {
    @file_put_contents(__DIR__ . '/../../logs/db_bootstrap.log', date('c') . " [CRITICAL] Ninguna conexión disponible\n", FILE_APPEND);
}

