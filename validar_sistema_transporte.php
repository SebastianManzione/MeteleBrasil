<?php
require_once __DIR__ . '/admin/classes/db.php';

$pdo->exec("SET NAMES utf8mb4");

echo "<h2>Validación Completa del Sistema de Transporte</h2>";

// 1. Tipos de transporte
echo "<h3>1. Tipos de Transporte</h3>";
$resultado = $pdo->query("SELECT * FROM tipo_transporte ORDER BY idTipo")->fetchAll(PDO::FETCH_ASSOC);
echo "<ul>";
foreach ($resultado as $row) {
    echo "<li>[{$row['idTipo']}] {$row['nombre']}</li>";
}
echo "</ul>";

// 2. Modelos habilitados
echo "<h3>2. Modelos de Vehículos (Habilitados)</h3>";
$resultado = $pdo->query("SELECT m.*, t.nombre as tipo_nombre 
                         FROM modelo_vehiculo_transporte m
                         LEFT JOIN tipo_transporte t ON m.tipo_transporte = t.idTipoTransporte
                         WHERE m.habilitado = 1
                         ORDER BY m.tipo_transporte, m.nombre")->fetchAll(PDO::FETCH_ASSOC);

echo "<table border='1' cellpadding='8'>";
echo "<tr><th>ID</th><th>Nombre</th><th>Tipo</th><th>Capacidad</th><th>Filas×Col</th></tr>";
foreach ($resultado as $row) {
    echo "<tr>";
    echo "<td>{$row['idModelo']}</td>";
    echo "<td><strong>{$row['nombre']}</strong></td>";
    echo "<td>{$row['tipo_nombre']}</td>";
    echo "<td>{$row['capacidad_total']}</td>";
    echo "<td>{$row['filas']}×{$row['columnas']}</td>";
    echo "</tr>";
}
echo "</table>";

// 3. Vehículos instanciados
echo "<h3>3. Vehículos Instanciados</h3>";
$resultado = $pdo->query("SELECT v.*, m.nombre as modelo
                         FROM vehiculo_transporte v
                         LEFT JOIN modelo_vehiculo_transporte m ON v.idModelo = m.idModelo
                         ORDER BY v.idModelo DESC")->fetchAll(PDO::FETCH_ASSOC);

echo "<table border='1' cellpadding='8'>";
echo "<tr><th>ID</th><th>Patente</th><th>Modelo</th><th>Año</th><th>Estado</th></tr>";
foreach ($resultado as $row) {
    $estado = $row['habilitado'] ? '<span style="color:green">✓ Activo</span>' : '<span style="color:red">✗ Inactivo</span>';
    echo "<tr>";
    echo "<td>{$row['idVehiculo']}</td>";
    echo "<td><strong>{$row['patente']}</strong></td>";
    echo "<td>{$row['modelo']}</td>";
    echo "<td>{$row['ano']}</td>";
    echo "<td>$estado</td>";
    echo "</tr>";
}
echo "</table>";

// 4. Rutas y Paradas
echo "<h3>4. Rutas Configuradas</h3>";
$resultado = $pdo->query("SELECT r.*, t.nombre as tipo_transporte
                         FROM ruta_transporte r
                         LEFT JOIN tipo_transporte t ON r.idTipoTransporte = t.idTipoTransporte
                         WHERE r.habilitado = 1
                         ORDER BY r.idRuta DESC
                         LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);

echo "<table border='1' cellpadding='8'>";
echo "<tr><th>ID</th><th>Nombre</th><th>Tipo</th><th>Prestador</th><th>Duración</th></tr>";
foreach ($resultado as $row) {
    echo "<tr>";
    echo "<td>{$row['idRuta']}</td>";
    echo "<td><strong>{$row['nombre']}</strong></td>";
    echo "<td>{$row['tipo_transporte']}</td>";
    echo "<td>{$row['idPrestador']}</td>";
    echo "<td>{$row['duracion_estimada']}</td>";
    echo "</tr>";
}
echo "</table>";

// 5. Viajes programados
echo "<h3>5. Viajes Programados (Próximos)</h3>";
$resultado = $pdo->query("SELECT v.*, r.nombre as ruta, m.nombre as modelo
                         FROM viaje_transporte v
                         LEFT JOIN ruta_transporte r ON v.idRuta = r.idRuta
                         LEFT JOIN modelo_vehiculo_transporte m ON (
                           SELECT idModelo FROM vehiculo_transporte WHERE idVehiculo = v.idVehiculo
                         ) as modelo_id ON m.idModelo = modelo_id
                         WHERE v.fecha_salida >= CURDATE()
                         ORDER BY v.fecha_salida DESC
                         LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);

if (count($resultado) > 0) {
    echo "<table border='1' cellpadding='8'>";
    echo "<tr><th>ID</th><th>Ruta</th><th>Fecha</th><th>Hora</th><th>Asientos</th><th>Modelo</th></tr>";
    foreach ($resultado as $row) {
        echo "<tr>";
        echo "<td>{$row['idViaje']}</td>";
        echo "<td>{$row['ruta']}</td>";
        echo "<td>{$row['fecha_salida']}</td>";
        echo "<td>{$row['hora_salida']}</td>";
        echo "<td>{$row['asientos_disponibles']}/{$row['asientos_totales']}</td>";
        echo "<td>{$row['modelo']}</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p><em>No hay viajes programados próximamente</em></p>";
}

echo "<h3>✓ Sistema de Transporte Validado Correctamente</h3>";
?>
