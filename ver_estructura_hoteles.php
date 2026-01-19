<?php
echo "=== ESTRUCTURA COMPLETA DE HOTELES ===\n\n";

try {
    $pdo = new PDO('mysql:host=localhost;dbname=metelebrasil;charset=utf8mb4', 'root', '');
    
    echo "1. Estructura tabla 'hoteles':\n";
    $stmt = $pdo->query("DESCRIBE hoteles");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "   {$row['Field']} ({$row['Type']}) " . ($row['Null'] === 'NO' ? 'NOT NULL' : 'NULL') . "\n";
    }
    
    echo "\n2. Total de hoteles: ";
    $stmt = $pdo->query("SELECT COUNT(*) as cnt FROM hoteles");
    echo $stmt->fetch(PDO::FETCH_ASSOC)['cnt'] . "\n";
    
    echo "\n3. Hoteles existentes (primeros 10):\n";
    $stmt = $pdo->query("SELECT h.*, s.nombre as nombre_servicio 
                         FROM hoteles h 
                         LEFT JOIN servicio s ON h.idServicio = s.idServicio 
                         LIMIT 10");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "   ID {$row['idHotel']}: {$row['nombre']}";
        if ($row['idServicio']) echo " (Servicio: {$row['nombre_servicio']})";
        echo "\n";
    }
    
    echo "\n4. Servicios que tienen hoteles:\n";
    $stmt = $pdo->query("SELECT s.idServicio, s.nombre, COUNT(h.idHotel) as cant_hoteles
                         FROM servicio s
                         LEFT JOIN hoteles h ON s.idServicio = h.idServicio
                         WHERE h.idHotel IS NOT NULL
                         GROUP BY s.idServicio
                         LIMIT 10");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "   Servicio {$row['idServicio']}: {$row['nombre']} ({$row['cant_hoteles']} hoteles)\n";
    }
    
    echo "\n5. Ubicaciones de hoteles:\n";
    $stmt = $pdo->query("SELECT DISTINCT ciudad FROM hoteles WHERE ciudad IS NOT NULL LIMIT 20");
    $ciudades = $stmt->fetchAll(PDO::FETCH_COLUMN);
    if (count($ciudades) > 0) {
        echo "   Ciudades: " . implode(', ', $ciudades) . "\n";
    } else {
        echo "   No hay campo 'ciudad' o está vacío\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
