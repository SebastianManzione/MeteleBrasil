<?php
require_once __DIR__ . '/admin/classes/db.php';

// Asegurar UTF-8
$pdo->exec("SET NAMES utf8mb4");
header('Content-Type: text/html; charset=utf-8');

echo "<h2>Agregando Nuevos Modelos de Vehículos</h2>";

// Nuevo modelo 1: Micro ejecutivo brasileño
$modelo1 = [
    'nombre' => 'Micro Ejecutivo Brasileño',
    'descripcion' => 'Micro ejecutivo cómodo. 40 asientos, baño, aire muy cómodo. Ideal para recorridos turísticos premium.',
    'tipo_transporte' => 1, // Bus
    'capacidad_total' => 40,
    'filas' => 8,
    'columnas' => 5,
    'habilitado' => 1
];

// Nuevo modelo 2: Ferry para travesías fluviales
$modelo2 = [
    'nombre' => 'Ferry Fluvial',
    'descripcion' => 'Ferry para travesías fluviales. 400 pasajeros, múltiples cubiertas, cabinas VIP, restaurante a bordo.',
    'tipo_transporte' => 4, // Barco
    'capacidad_total' => 400,
    'filas' => 20,
    'columnas' => 20,
    'habilitado' => 1
];

echo "<h3>Modelo 1: Micro Ejecutivo Brasileño</h3>";
$sql1 = "INSERT INTO modelo_vehiculo_transporte (nombre, descripcion, tipo_transporte, capacidad_total, filas, columnas, habilitado)
         VALUES (:nombre, :descripcion, :tipo, :capacidad, :filas, :columnas, :habilitado)";
$stmt1 = $pdo->prepare($sql1);

if ($stmt1->execute([
    ':nombre' => $modelo1['nombre'],
    ':descripcion' => $modelo1['descripcion'],
    ':tipo' => $modelo1['tipo_transporte'],
    ':capacidad' => $modelo1['capacidad_total'],
    ':filas' => $modelo1['filas'],
    ':columnas' => $modelo1['columnas'],
    ':habilitado' => $modelo1['habilitado']
])) {
    $idModelo1 = $pdo->lastInsertId();
    echo "<p style='color:green;'><strong>✓ Creado correctamente</strong></p>";
    echo "<table border='1' cellpadding='8'>";
    echo "<tr><th>Campo</th><th>Valor</th></tr>";
    echo "<tr><td>ID</td><td><strong>$idModelo1</strong></td></tr>";
    echo "<tr><td>Nombre</td><td>{$modelo1['nombre']}</td></tr>";
    echo "<tr><td>Descripción</td><td>{$modelo1['descripcion']}</td></tr>";
    echo "<tr><td>Tipo</td><td>Bus (ID 1)</td></tr>";
    echo "<tr><td>Capacidad</td><td>{$modelo1['capacidad_total']} asientos</td></tr>";
    echo "<tr><td>Configuración</td><td>{$modelo1['filas']} filas × {$modelo1['columnas']} columnas</td></tr>";
    echo "</table>";
} else {
    echo "<p style='color:red;'><strong>✗ Error al crear modelo 1</strong></p>";
}

echo "<h3>Modelo 2: Ferry Fluvial</h3>";
$sql2 = "INSERT INTO modelo_vehiculo_transporte (nombre, descripcion, tipo_transporte, capacidad_total, filas, columnas, habilitado)
         VALUES (:nombre, :descripcion, :tipo, :capacidad, :filas, :columnas, :habilitado)";
$stmt2 = $pdo->prepare($sql2);

if ($stmt2->execute([
    ':nombre' => $modelo2['nombre'],
    ':descripcion' => $modelo2['descripcion'],
    ':tipo' => $modelo2['tipo_transporte'],
    ':capacidad' => $modelo2['capacidad_total'],
    ':filas' => $modelo2['filas'],
    ':columnas' => $modelo2['columnas'],
    ':habilitado' => $modelo2['habilitado']
])) {
    $idModelo2 = $pdo->lastInsertId();
    echo "<p style='color:green;'><strong>✓ Creado correctamente</strong></p>";
    echo "<table border='1' cellpadding='8'>";
    echo "<tr><th>Campo</th><th>Valor</th></tr>";
    echo "<tr><td>ID</td><td><strong>$idModelo2</strong></td></tr>";
    echo "<tr><td>Nombre</td><td>{$modelo2['nombre']}</td></tr>";
    echo "<tr><td>Descripción</td><td>{$modelo2['descripcion']}</td></tr>";
    echo "<tr><td>Tipo</td><td>Barco (ID 4)</td></tr>";
    echo "<tr><td>Capacidad</td><td>{$modelo2['capacidad_total']} pasajeros</td></tr>";
    echo "<tr><td>Configuración</td><td>{$modelo2['filas']} filas × {$modelo2['columnas']} columnas</td></tr>";
    echo "</table>";
} else {
    echo "<p style='color:red;'><strong>✗ Error al crear modelo 2</strong></p>";
}

echo "<h3>Verificación Final</h3>";
$resultado = $pdo->query("SELECT idModelo, nombre, tipo_transporte, capacidad_total FROM modelo_vehiculo_transporte ORDER BY idModelo DESC LIMIT 3")->fetchAll(PDO::FETCH_ASSOC);

echo "<table border='1' cellpadding='8'>";
echo "<tr><th>ID</th><th>Nombre</th><th>Tipo</th><th>Capacidad</th></tr>";
foreach ($resultado as $row) {
    echo "<tr>";
    echo "<td><strong>{$row['idModelo']}</strong></td>";
    echo "<td>{$row['nombre']}</td>";
    echo "<td>";
    $tipo = $row['tipo_transporte'];
    if ($tipo == 1) echo "Bus";
    elseif ($tipo == 2) echo "Avión";
    elseif ($tipo == 3) echo "Tren";
    else echo "Barco";
    echo " (ID $tipo)</td>";
    echo "<td>{$row['capacidad_total']}</td>";
    echo "</tr>";
}
echo "</table>";

echo "<p><a href='admin/modeloVehiculosLista.php'>Ver lista de modelos en admin →</a></p>";
?>
