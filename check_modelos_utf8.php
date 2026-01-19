<?php
require_once __DIR__ . '/admin/classes/db.php';

// Forzar UTF-8
$pdo->exec("SET NAMES utf8mb4");

echo "<h2>Modelos de Vehículos con Acentos</h2>";
echo "<pre>";

$consulta = "SELECT idModelo, nombre, capacidad_total FROM modelo_vehiculo_transporte ORDER BY idModelo DESC LIMIT 7";
$resultado = $pdo->query($consulta)->fetchAll(PDO::FETCH_ASSOC);

foreach ($resultado as $row) {
    echo "ID: {$row['idModelo']} | Nombre: {$row['nombre']} | Cap: {$row['capacidad_total']}\n";
}

echo "</pre>";

// Corregir acentos en modelos si es necesario
echo "<h3>Corrigiendo Acentos...</h3>";

$fixes = [
    6 => 'Marcopolo Doble Piso G7',
    7 => 'Mercedes Doble Piso Comfort'
];

foreach ($fixes as $id => $nombre) {
    $sql = "UPDATE modelo_vehiculo_transporte SET nombre = ? WHERE idModelo = ?";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([$nombre, $id])) {
        echo "✓ ID $id: $nombre actualizado<br>";
    }
}

// Verificar después de corrección
echo "<h3>Verificación Final</h3>";
echo "<pre>";
$resultado = $pdo->query($consulta)->fetchAll(PDO::FETCH_ASSOC);
foreach ($resultado as $row) {
    echo "ID: {$row['idModelo']} | Nombre: {$row['nombre']} | Cap: {$row['capacidad_total']}\n";
}
echo "</pre>";

echo "<p><a href='admin/modeloVehiculosLista.php'>Ver lista de modelos</a></p>";
?>
