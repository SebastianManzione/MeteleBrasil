<?php
require_once "admin/classes/conexion.php";

try {
    $pdo->exec("ALTER TABLE servicios_adicionales ADD COLUMN precio DECIMAL(10, 2) DEFAULT 0 AFTER descripcion_servicio_adicional");
    echo "✓ Columna 'precio' agregada\n";
} catch (Exception $e) {
    if (strpos($e->getMessage(), 'already exists') !== false) {
        echo "✓ Columna 'precio' ya existe\n";
    } else {
        echo "✗ Error: " . $e->getMessage() . "\n";
    }
}

try {
    $pdo->exec("ALTER TABLE servicios_adicionales ADD COLUMN idMoneda INT DEFAULT 1 AFTER precio");
    echo "✓ Columna 'idMoneda' agregada\n";
} catch (Exception $e) {
    if (strpos($e->getMessage(), 'already exists') !== false) {
        echo "✓ Columna 'idMoneda' ya existe\n";
    } else {
        echo "✗ Error: " . $e->getMessage() . "\n";
    }
}

try {
    $pdo->exec("ALTER TABLE servicios_adicionales ADD COLUMN habilitado INT DEFAULT 1 AFTER idMoneda");
    echo "✓ Columna 'habilitado' agregada\n";
} catch (Exception $e) {
    if (strpos($e->getMessage(), 'already exists') !== false) {
        echo "✓ Columna 'habilitado' ya existe\n";
    } else {
        echo "✗ Error: " . $e->getMessage() . "\n";
    }
}

echo "\n=== Nueva estructura ===\n";
$r = $pdo->query("DESCRIBE servicios_adicionales");
while($row = $r->fetch()) {
    echo $row['Field'] . " | " . $row['Type'] . "\n";
}
?>
