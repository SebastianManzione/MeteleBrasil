<?php
/**
 * BOOTSTRAP - REDIRECCIÓN CENTRALIZADA
 * 
 * Este archivo redirecciona al bootstrap centralizado en admin/classes/db.php
 * TODAS las conexiones (mysqli + PDO) están centralizadas en esa ubicación.
 */

// Incluye el archivo centralizado
require_once __DIR__ . '/../admin/classes/db.php';

// Las variables $pdo, $mysqli, $conection están disponibles
// gracias al include anterior


