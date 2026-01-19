<?php
require_once('admin/classes/conexion.php');

echo "📋 Estructura de la tabla 'parada':\n\n";

$stmt = $pdo->query('DESCRIBE parada');
$columnas = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($columnas as $col) {
    echo sprintf("%-20s %s\n", $col['Field'], $col['Type']);
}
