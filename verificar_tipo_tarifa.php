<?php
require_once("admin/classes/conexion.php");

// Verificar estructura de tabla viaje
$sql = "DESCRIBE viaje";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<h2>Estructura de tabla 'viaje':</h2>";
echo "<table border='1' cellpadding='10'>";
echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";

foreach ($columns as $col) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($col['Field']) . "</td>";
    echo "<td>" . htmlspecialchars($col['Type']) . "</td>";
    echo "<td>" . htmlspecialchars($col['Null']) . "</td>";
    echo "<td>" . htmlspecialchars($col['Key']) . "</td>";
    echo "<td>" . htmlspecialchars($col['Default'] ?? '') . "</td>";
    echo "</tr>";
}
echo "</table>";

// Buscar campo tipo_tarifa
$tipoTarifaExists = array_filter($columns, function($col) {
    return $col['Field'] === 'tipo_tarifa';
});

echo "<h3>Campo 'tipo_tarifa':</h3>";
if (!empty($tipoTarifaExists)) {
    echo "<p style='color:green;'><strong>✓ Campo EXISTE</strong></p>";
} else {
    echo "<p style='color:red;'><strong>✗ Campo NO EXISTE - Es necesario ejecutar setup_dual_tarifa.php</strong></p>";
}

// Verificar valores de tipo_tarifa en algunos viajes
echo "<h3>Valores de 'tipo_tarifa' en tabla viaje:</h3>";
$sql2 = "SELECT idViaje, tipo_tarifa FROM viaje LIMIT 10";
$stmt2 = $pdo->prepare($sql2);
$stmt2->execute();
$viajes = $stmt2->fetchAll(PDO::FETCH_ASSOC);

if (!empty($viajes)) {
    echo "<table border='1' cellpadding='10'>";
    echo "<tr><th>idViaje</th><th>tipo_tarifa</th></tr>";
    foreach ($viajes as $v) {
        echo "<tr>";
        echo "<td>" . $v['idViaje'] . "</td>";
        echo "<td>" . htmlspecialchars($v['tipo_tarifa'] ?? 'NULL') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}
?>
