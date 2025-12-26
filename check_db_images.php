<?php
$mysqli = new mysqli('localhost', 'root', '', 'metelebrasil');
if ($mysqli->connect_error) {
    die('Error: ' . $mysqli->connect_error);
}

echo "=== SERVICIOS CON IMÁGENES EN BD LOCAL ===\n";
echo str_repeat("-", 60) . "\n";

$result = $mysqli->query('SELECT idServicio, COUNT(*) as cant FROM servicio_img GROUP BY idServicio ORDER BY idServicio LIMIT 20');
$servicios = [];
while ($row = $result->fetch_assoc()) {
    echo 'ID ' . $row['idServicio'] . ': ' . $row['cant'] . ' fotos' . PHP_EOL;
    $servicios[] = $row['idServicio'];
}

echo "\n=== DETALLES DE ALGUNOS SERVICIOS ===\n";

// Revisar si se registraron imágenes después del 589
foreach ([589, 590, 591, 645] as $id) {
    echo "\nServicio $id:\n";
    $result = $mysqli->query("SELECT ruta, miniatura, portada FROM servicio_img WHERE idServicio = $id LIMIT 1");
    if ($row = $result->fetch_assoc()) {
        echo "  Ruta: " . $row['ruta'] . "\n";
        echo "  Es miniatura: " . ($row['miniatura'] ? 'SÍ' : 'NO') . "\n";
        echo "  Es portada: " . ($row['portada'] ? 'SÍ' : 'NO') . "\n";
    } else {
        echo "  NO hay imágenes\n";
    }
}

// Estadísticas
echo "\n" . str_repeat("=", 60) . "\n";
echo "ESTADÍSTICAS:\n";
$result = $mysqli->query('SELECT COUNT(*) as total FROM servicio_img');
$row = $result->fetch_assoc();
echo "Total de imágenes: " . $row['total'] . "\n";
echo "Servicios con imágenes: " . count($servicios) . "\n";
?>
