<?php
require_once __DIR__ . '/admin/classes/db.php';

$pdo->exec("SET NAMES utf8mb4");

echo "<h2>Estado de Modelos 6 y 7</h2>";
echo "<table border='1' cellpadding='10'>";
echo "<tr><th>ID</th><th>Nombre</th><th>Habilitado</th><th>Tipo</th></tr>";

$consulta = "SELECT idModelo, nombre, habilitado, tipo_transporte FROM modelo_vehiculo_transporte WHERE idModelo >= 6";
$resultado = $pdo->query($consulta)->fetchAll(PDO::FETCH_ASSOC);

foreach ($resultado as $row) {
    echo "<tr>";
    echo "<td>{$row['idModelo']}</td>";
    echo "<td>{$row['nombre']}</td>";
    echo "<td>" . ($row['habilitado'] ? '✓ Sí' : '✗ No') . "</td>";
    echo "<td>{$row['tipo_transporte']}</td>";
    echo "</tr>";
}

echo "</table>";

echo "<h3>Habilitando modelos 6 y 7...</h3>";
$sql = "UPDATE modelo_vehiculo_transporte SET habilitado = 1 WHERE idModelo IN (6,7)";
if ($pdo->exec($sql)) {
    echo "✓ Modelos habilitados correctamente<br>";
}

echo "<h3>Verificación Final</h3>";
echo "<table border='1' cellpadding='10'>";
echo "<tr><th>ID</th><th>Nombre</th><th>Habilitado</th><th>Tipo</th></tr>";

$resultado = $pdo->query($consulta)->fetchAll(PDO::FETCH_ASSOC);
foreach ($resultado as $row) {
    echo "<tr>";
    echo "<td>{$row['idModelo']}</td>";
    echo "<td>{$row['nombre']}</td>";
    echo "<td>" . ($row['habilitado'] ? '✓ Sí' : '✗ No') . "</td>";
    echo "<td>{$row['tipo_transporte']}</td>";
    echo "</tr>";
}
echo "</table>";

echo "<p><a href='admin/modeloVehiculosLista.php'>Ir a lista de modelos →</a></p>";
?>
