<?php
require_once __DIR__ . '/admin/classes/db.php';

$pdo->exec("SET NAMES utf8mb4");
header('Content-Type: text/html; charset=utf-8');

echo "<h2>Instanciando Nuevos Vehículos</h2>";

// Obtener IDs de los modelos recién creados
$modelos = $pdo->query("SELECT idModelo, nombre FROM modelo_vehiculo_transporte WHERE nombre IN ('Micro Ejecutivo Brasileño', 'Ferry Fluvial')")->fetchAll(PDO::FETCH_ASSOC);

echo "<h3>Modelos Encontrados</h3>";
echo "<table border='1' cellpadding='8'>";
echo "<tr><th>ID</th><th>Nombre</th></tr>";

$idMicroEjecutivo = null;
$idFerry = null;

foreach ($modelos as $m) {
    echo "<tr><td>{$m['idModelo']}</td><td>{$m['nombre']}</td></tr>";
    if (strpos($m['nombre'], 'Micro') !== false) $idMicroEjecutivo = $m['idModelo'];
    if (strpos($m['nombre'], 'Ferry') !== false) $idFerry = $m['idModelo'];
}
echo "</table>";

echo "<h3>Creando Vehículos Instanciados</h3>";

// Vehículos para Micro Ejecutivo (2 unidades)
$vehiculosMicro = [
    ['patente' => 'MB 500', 'ano' => 2024, 'observaciones' => 'Micro ejecutivo brasileño con Wi-Fi y enchufes USB'],
    ['patente' => 'MB 501', 'ano' => 2024, 'observaciones' => 'Micro ejecutivo brasileño reclining seats premium']
];

// Vehículos para Ferry (2 unidades)
$vehiculosFerry = [
    ['patente' => 'FERRY-01', 'ano' => 2023, 'observaciones' => 'Ferry fluvial de pasada rápida, 400 pasajeros'],
    ['patente' => 'FERRY-02', 'ano' => 2022, 'observaciones' => 'Ferry fluvial con cabinas VIP']
];

if ($idMicroEjecutivo) {
    echo "<h4>Micro Ejecutivo (ID: $idMicroEjecutivo)</h4>";
    foreach ($vehiculosMicro as $v) {
        $sql = "INSERT INTO vehiculo_transporte (patente, idModelo, ano, estado, observaciones, habilitado)
                VALUES (:patente, :idModelo, :ano, 'activo', :obs, 1)";
        $stmt = $pdo->prepare($sql);
        
        if ($stmt->execute([
            ':patente' => $v['patente'],
            ':idModelo' => $idMicroEjecutivo,
            ':ano' => $v['ano'],
            ':obs' => $v['observaciones']
        ])) {
            $idVehiculo = $pdo->lastInsertId();
            echo "<p style='color:green;'>✓ {$v['patente']} (ID: $idVehiculo) creado</p>";
        } else {
            echo "<p style='color:red;'>✗ Error creando {$v['patente']}</p>";
        }
    }
}

if ($idFerry) {
    echo "<h4>Ferry Fluvial (ID: $idFerry)</h4>";
    foreach ($vehiculosFerry as $v) {
        $sql = "INSERT INTO vehiculo_transporte (patente, idModelo, ano, estado, observaciones, habilitado)
                VALUES (:patente, :idModelo, :ano, 'activo', :obs, 1)";
        $stmt = $pdo->prepare($sql);
        
        if ($stmt->execute([
            ':patente' => $v['patente'],
            ':idModelo' => $idFerry,
            ':ano' => $v['ano'],
            ':obs' => $v['observaciones']
        ])) {
            $idVehiculo = $pdo->lastInsertId();
            echo "<p style='color:green;'>✓ {$v['patente']} (ID: $idVehiculo) creado</p>";
        } else {
            echo "<p style='color:red;'>✗ Error creando {$v['patente']}</p>";
        }
    }
}

echo "<h3>Verificación Final</h3>";
$resultado = $pdo->query("SELECT v.idVehiculo, v.patente, m.nombre as modelo, m.capacidad_total 
                         FROM vehiculo_transporte v
                         LEFT JOIN modelo_vehiculo_transporte m ON v.idModelo = m.idModelo
                         WHERE m.nombre IN ('Micro Ejecutivo Brasileño', 'Ferry Fluvial')
                         ORDER BY v.idVehiculo DESC")->fetchAll(PDO::FETCH_ASSOC);

if (count($resultado) > 0) {
    echo "<table border='1' cellpadding='8'>";
    echo "<tr><th>ID Veh</th><th>Patente</th><th>Modelo</th><th>Capacidad</th></tr>";
    foreach ($resultado as $row) {
        echo "<tr>";
        echo "<td>{$row['idVehiculo']}</td>";
        echo "<td><strong>{$row['patente']}</strong></td>";
        echo "<td>{$row['modelo']}</td>";
        echo "<td>{$row['capacidad_total']}</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No se encontraron vehículos</p>";
}

echo "<p><a href='admin/vehiculosTransporteLista.php'>Ver lista de vehículos en admin →</a></p>";
?>
