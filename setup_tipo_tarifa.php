<?php
require_once("admin/classes/conexion.php");

// Verificar si campo tipo_tarifa existe
$sql_check = "SHOW COLUMNS FROM viaje LIKE 'tipo_tarifa'";
$stmt = $pdo->prepare($sql_check);
$stmt->execute();
$exists = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$exists) {
    // Agregar columna
    $sql_alter = "ALTER TABLE viaje ADD COLUMN tipo_tarifa VARCHAR(20) DEFAULT 'clases' AFTER idMoneda";
    $pdo->exec($sql_alter);
    echo "<p style='color:green;'><strong>✓ Columna 'tipo_tarifa' agregada a tabla 'viaje'</strong></p>";
} else {
    echo "<p style='color:blue;'><strong>ℹ Columna 'tipo_tarifa' ya existe</strong></p>";
}

// Actualizar viaje 5 a tipo 'clases' (si es que tiene viaje_clase_tarifa)
$sql_update = "UPDATE viaje SET tipo_tarifa = 'clases' WHERE idViaje = 5";
$pdo->exec($sql_update);
echo "<p style='color:green;'><strong>✓ Viaje 5 configurado como tipo 'clases'</strong></p>";

// Mostrar resumen
echo "<h3>Resumen:</h3>";
$sql_check = "SELECT idViaje, tipo_tarifa FROM viaje LIMIT 10";
$stmt = $pdo->prepare($sql_check);
$stmt->execute();
$viajes = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<table border='1' cellpadding='10'>";
echo "<tr><th>idViaje</th><th>tipo_tarifa</th></tr>";
foreach ($viajes as $v) {
    echo "<tr>";
    echo "<td>" . $v['idViaje'] . "</td>";
    echo "<td>" . htmlspecialchars($v['tipo_tarifa'] ?? 'NULL') . "</td>";
    echo "</tr>";
}
echo "</table>";
?>
