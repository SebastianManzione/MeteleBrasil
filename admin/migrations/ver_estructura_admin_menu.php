<?php
require_once(__DIR__ . '/../classes/conexion.php');
header('Content-Type: text/plain; charset=utf-8');
try {
    $stmt = $pdo->query('DESCRIBE admin_menu');
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (Exception $e) {
    echo 'ERROR: ' . $e->getMessage();
}
?>