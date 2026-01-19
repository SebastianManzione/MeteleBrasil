<?php
require_once __DIR__ . '/admin/classes/db.php';

$pdo->exec("SET NAMES utf8mb4");
header('Content-Type: text/html; charset=utf-8');

echo "<h2>Actualizando Micros a Doble Piso</h2>";

// Modelos actuales a actualizar
$actualizaciones = [
    [
        'id' => 4,
        'nombre_actual' => 'Iveco Minibus',
        'nombre_nuevo' => 'Iveco Doble Piso',
        'capacidad_nueva' => 60,
        'filas_nuevas' => 12,
        'columnas_nuevas' => 5
    ],
    [
        'id' => 5,
        'nombre_actual' => 'Hino Bus Urbano',
        'nombre_nuevo' => 'Hino Doble Piso Urbano',
        'capacidad_nueva' => 70,
        'filas_nuevas' => 14,
        'columnas_nuevas' => 5
    ]
];

echo "<h3>Actualizaciones Planificadas</h3>";
echo "<table border='1' cellpadding='10'>";
echo "<tr><th>Modelo Actual</th><th>Nuevo Modelo</th><th>Capacidad</th><th>Filas</th><th>Columnas</th></tr>";

foreach ($actualizaciones as $upd) {
    echo "<tr>";
    echo "<td>{$upd['nombre_actual']}</td>";
    echo "<td><strong>{$upd['nombre_nuevo']}</strong></td>";
    echo "<td>{$upd['capacidad_nueva']} asientos</td>";
    echo "<td>{$upd['filas_nuevas']}</td>";
    echo "<td>{$upd['columnas_nuevas']}</td>";
    echo "</tr>";
}
echo "</table>";

echo "<h3>Ejecutando Actualizaciones...</h3>";

foreach ($actualizaciones as $upd) {
    $sql = "UPDATE modelo_vehiculo_transporte 
            SET nombre = :nombre,
                capacidad_total = :capacidad,
                filas = :filas,
                columnas = :columnas
            WHERE idModelo = :id";
    
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute([
        ':nombre' => $upd['nombre_nuevo'],
        ':capacidad' => $upd['capacidad_nueva'],
        ':filas' => $upd['filas_nuevas'],
        ':columnas' => $upd['columnas_nuevas'],
        ':id' => $upd['id']
    ])) {
        echo "<p style='color:green;'><strong>✓</strong> {$upd['nombre_actual']} → {$upd['nombre_nuevo']}</p>";
    } else {
        echo "<p style='color:red;'><strong>✗</strong> Error actualizando {$upd['nombre_actual']}</p>";
    }
}

echo "<h3>Verificación Final</h3>";
$resultado = $pdo->query("SELECT idModelo, nombre, capacidad_total, filas, columnas 
                         FROM modelo_vehiculo_transporte 
                         ORDER BY idModelo ASC")->fetchAll(PDO::FETCH_ASSOC);

echo "<table border='1' cellpadding='10'>";
echo "<tr><th>ID</th><th>Nombre</th><th>Capacidad</th><th>Filas×Columnas</th><th>Doble Piso</th></tr>";

foreach ($resultado as $row) {
    $esDoble = $row['filas'] > 15 ? '✓ Sí (Verde/Azul)' : '• No (Único nivel)';
    echo "<tr>";
    echo "<td><strong>{$row['idModelo']}</strong></td>";
    echo "<td>{$row['nombre']}</td>";
    echo "<td>{$row['capacidad_total']}</td>";
    echo "<td>{$row['filas']}×{$row['columnas']}</td>";
    echo "<td>$esDoble</td>";
    echo "</tr>";
}
echo "</table>";

echo "<p style='margin-top: 20px;'>";
echo "<a href='admin/modeloVehiculosLista.php' class='btn btn-primary'>Ver lista de modelos</a>";
echo "</p>";
?>
