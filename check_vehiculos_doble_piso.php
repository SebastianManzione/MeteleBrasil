<?php
require_once __DIR__ . '/admin/classes/db.php';

$pdo->exec("SET NAMES utf8mb4");

echo "<h2>Verificación de Vehículos Doble Piso</h2>";

// Buscar vehículos con modelos 6 o 7 (doble piso)
$consulta = "SELECT v.idVehiculo, v.patente, v.ano, v.estado, m.idModelo, m.nombre as modelo
             FROM vehiculo_transporte v
             LEFT JOIN modelo_vehiculo_transporte m ON v.idModelo = m.idModelo
             WHERE m.idModelo IN (6, 7)
             ORDER BY m.idModelo, v.patente";

$resultado = $pdo->query($consulta)->fetchAll(PDO::FETCH_ASSOC);

echo "<h3>Vehículos Doble Piso en BD</h3>";
if (count($resultado) > 0) {
    echo "<table border='1' cellpadding='10'>";
    echo "<tr><th>ID Veh</th><th>Patente</th><th>Modelo</th><th>Año</th><th>Estado</th></tr>";
    
    foreach ($resultado as $row) {
        $estado_badge = $row['estado'] !== 'retirado' ? '<span style="color:green">✓ '.$row['estado'].'</span>' : '<span style="color:red">✗ '.$row['estado'].'</span>';
        echo "<tr>";
        echo "<td>{$row['idVehiculo']}</td>";
        echo "<td><strong>{$row['patente']}</strong></td>";
        echo "<td>{$row['modelo']}</td>";
        echo "<td>{$row['ano']}</td>";
        echo "<td>$estado_badge</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color:red;'><strong>⚠️ No hay vehículos asignados a los modelos doble piso</strong></p>";
}

// Mostrar información de todos los vehículos
echo "<h3>Todos los Vehículos (para comparación)</h3>";
$todos = $pdo->query("SELECT v.idVehiculo, v.patente, v.ano, v.estado, m.idModelo, m.nombre as modelo
                     FROM vehiculo_transporte v
                     LEFT JOIN modelo_vehiculo_transporte m ON v.idModelo = m.idModelo
                     ORDER BY m.idModelo, v.patente")->fetchAll(PDO::FETCH_ASSOC);

echo "<table border='1' cellpadding='8' style='font-size:12px;'>";
echo "<tr><th>ID Veh</th><th>Patente</th><th>Modelo ID</th><th>Modelo</th><th>Año</th><th>Estado</th></tr>";

foreach ($todos as $row) {
    $estado_badge = $row['estado'] !== 'retirado' ? '<span style="color:green">✓</span>' : '<span style="color:red">✗</span>';
    $modelo_highlight = in_array($row['idModelo'], [6, 7]) ? '<strong style="color:blue">' : '';
    $modelo_highlight_close = in_array($row['idModelo'], [6, 7]) ? '</strong>' : '';
    
    echo "<tr>";
    echo "<td>{$row['idVehiculo']}</td>";
    echo "<td><strong>{$row['patente']}</strong></td>";
    echo "<td>{$modelo_highlight}{$row['idModelo']}{$modelo_highlight_close}</td>";
    echo "<td>{$modelo_highlight}{$row['modelo']}{$modelo_highlight_close}</td>";
    echo "<td>{$row['ano']}</td>";
    echo "<td>$estado_badge {$row['estado']}</td>";
    echo "</tr>";
}
echo "</table>";

echo "<p><a href='admin/vehiculosTransporteLista.php'>Ir a lista de vehículos →</a></p>";
?>
