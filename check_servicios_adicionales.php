<?php
require_once "admin/classes/conexion.php";

echo "=== Estructura servicios_adicionales ===\n";
$r = $pdo->query("DESCRIBE servicios_adicionales");
while($row = $r->fetch()) {
    echo $row['Field'] . " | " . $row['Type'] . " | " . ($row['Null'] == 'YES' ? 'NULL' : 'NOT NULL') . "\n";
}

echo "\n=== Registros existentes ===\n";
$r = $pdo->query("SELECT * FROM servicios_adicionales LIMIT 5");
while($row = $r->fetch()) {
    print_r($row);
}
?>
