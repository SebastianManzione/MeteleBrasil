<?php
/**
 * ALIAS SIMPLE - Por compatibilidad con código legado
 * 
 * Simplemente incluye db.php que tiene toda la lógica centralizada.
 * También garantiza que $pdo esté disponible en el scope local.
 */

// Incluye el bootstrap centralizado
require_once __DIR__ . '/db.php';

// IMPORTANTE: Hace que $pdo esté disponible en el scope que lo llamó
// Esto es crítico para funciones que hacen require("conexion.php")
// y luego usan $pdo localmente
global $pdo, $mysqli, $conection;

// Si $pdo no existe localmente, obtenerlo del global
if (!isset($pdo) || !($pdo instanceof PDO)) {
    $pdo = $GLOBALS['pdo'] ?? null;
}

// Si $mysqli no existe localmente, obtenerlo del global
if (!isset($mysqli) || !($mysqli instanceof mysqli)) {
    $mysqli = $GLOBALS['mysqli'] ?? null;
}

// Si $conection no existe, obtenerlo del global
if (!isset($conection) || !($conection instanceof mysqli)) {
    $conection = $GLOBALS['conection'] ?? null;
}

// Función helper para obtener PDO desde cualquier scope
if (!function_exists('getPDO')) {
    function getPDO() {
        global $pdo;
        return $pdo ?? $GLOBALS['pdo'] ?? null;
    }
}







		
