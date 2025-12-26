<?php
require_once __DIR__ . '/config/db.php';

echo "=== ESTRUCTURA DE TABLAS ===\n\n";

echo "TABLA USUARIO:\n";
$result = $mysqli->query('DESCRIBE usuario');
while ($row = $result->fetch_assoc()) {
    echo "  " . $row['Field'] . " - " . $row['Type'] . "\n";
}

echo "\nTABLA CONTACTO:\n";
$result = $mysqli->query('DESCRIBE contacto');
while ($row = $result->fetch_assoc()) {
    echo "  " . $row['Field'] . " - " . $row['Type'] . "\n";
}

echo "\nTABLA SOLICITUD:\n";
$result = $mysqli->query('DESCRIBE solicitud');
while ($row = $result->fetch_assoc()) {
    echo "  " . $row['Field'] . " - " . $row['Type'] . "\n";
}
?>
