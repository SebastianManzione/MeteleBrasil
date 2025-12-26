<?php
require_once(__DIR__ . '/classes/conexion.php');

echo "=== Estructura de tabla roles ===\n\n";

try {
    $stmt = $GLOBALS['pdo']->query('DESCRIBE roles');
    while ($row = $stmt->fetch()) {
        echo $row['Field'] . ' - ' . $row['Type'] . "\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "La tabla no existe aún\n";
}
?>
