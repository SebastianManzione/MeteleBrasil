<?php
echo "=== VERIFICACIÓN FINAL HOTELES ===\n\n";

$mysqli = new mysqli('localhost', 'root', '', 'metelebrasil');
mysqli_set_charset($mysqli, 'utf8mb4');

if ($mysqli->connect_error) {
    die("Error: " . $mysqli->connect_error);
}

// 1. Verificar tabla
$result = $mysqli->query("SHOW TABLES LIKE 'hoteles'");
if ($result->num_rows > 0) {
    echo "✓ Tabla 'hoteles' existe en metelebrasil\n\n";
    
    // 2. Estructura
    echo "ESTRUCTURA:\n";
    $result = $mysqli->query("DESCRIBE hoteles");
    while ($row = $result->fetch_assoc()) {
        echo "  • {$row['Field']} ({$row['Type']})\n";
    }
    
    // 3. Total
    $result = $mysqli->query("SELECT COUNT(*) as cnt FROM hoteles");
    $total = $result->fetch_assoc()['cnt'];
    echo "\nTOTAL: $total hoteles\n\n";
    
    // 4. Ejemplos con servicios
    echo "HOTELES CON SERVICIOS (primeros 10):\n";
    $result = $mysqli->query("
        SELECT h.idHotel, h.nombre, h.idServicio, s.nombre as nombre_servicio
        FROM hoteles h
        LEFT JOIN servicio s ON h.idServicio = s.idServicio
        LIMIT 10
    ");
    
    while ($row = $result->fetch_assoc()) {
        echo "  ID {$row['idHotel']}: {$row['nombre']}";
        if ($row['idServicio']) {
            echo " → Servicio {$row['idServicio']}: {$row['nombre_servicio']}";
        }
        echo "\n";
    }
    
    // 5. Verificar si hay campos de ubicación
    echo "\n¿TIENE CAMPOS DE UBICACIÓN?\n";
    $result = $mysqli->query("DESCRIBE hoteles");
    $tiene_ciudad = false;
    $tiene_direccion = false;
    $tiene_coords = false;
    
    while ($row = $result->fetch_assoc()) {
        if ($row['Field'] == 'ciudad') $tiene_ciudad = true;
        if ($row['Field'] == 'direccion') $tiene_direccion = true;
        if ($row['Field'] == 'latitud' || $row['Field'] == 'lat') $tiene_coords = true;
    }
    
    echo "  Ciudad: " . ($tiene_ciudad ? "✓ SÍ" : "✗ NO") . "\n";
    echo "  Dirección: " . ($tiene_direccion ? "✓ SÍ" : "✗ NO") . "\n";
    echo "  Coordenadas: " . ($tiene_coords ? "✓ SÍ" : "✗ NO") . "\n";
    
} else {
    echo "✗ Tabla 'hoteles' NO existe en metelebrasil\n";
}

$mysqli->close();
?>
