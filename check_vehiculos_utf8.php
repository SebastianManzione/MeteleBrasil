<?php
require_once __DIR__ . '/admin/classes/db.php';

$pdo->exec("SET NAMES utf8mb4");

echo "<h2>Verificación UTF-8 en Vehículos</h2>";

// Obtener todos los vehículos con sus modelos
$consulta = "SELECT v.idVehiculo, v.patente, m.nombre as modelo, v.observaciones
             FROM vehiculo_transporte v
             LEFT JOIN modelo_vehiculo_transporte m ON v.idModelo = m.idModelo
             ORDER BY v.idVehiculo DESC LIMIT 10";

echo "<table border='1' cellpadding='10' cellspacing='0'>";
echo "<tr><th>ID Veh</th><th>Patente</th><th>Modelo</th><th>Observaciones</th></tr>";

$resultado = $pdo->query($consulta)->fetchAll(PDO::FETCH_ASSOC);
foreach ($resultado as $row) {
    echo "<tr>";
    echo "<td>{$row['idVehiculo']}</td>";
    echo "<td><strong>{$row['patente']}</strong></td>";
    echo "<td>{$row['modelo']}</td>";
    echo "<td>{$row['observaciones']}</td>";
    echo "</tr>";
}
echo "</table>";

echo "<p><a href='admin/vehiculosTransporteLista.php'>Ir a lista de vehículos →</a></p>";
?>
