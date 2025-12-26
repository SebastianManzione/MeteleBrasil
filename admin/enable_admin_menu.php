<?php
require_once(__DIR__ . '/classes/conexion.php');
try {
    $stmt = $pdo->prepare("UPDATE configuracion SET valor='true' WHERE clave='admin_menu_db_enabled'");
    $stmt->execute();
    echo "admin_menu_db_enabled = true\n";
} catch (Exception $e) {
    http_response_code(500);
    echo "Error: " . $e->getMessage() . "\n";
}
