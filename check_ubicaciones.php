<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=metelebrasil;charset=utf8mb4', 'root', '');
    
    echo "=== DIAGNÓSTICO DE ubicaciones ===\n\n";
    
    echo "1. Estructura:\n";
    $stmt = $pdo->query('DESCRIBE ubicaciones');
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "   " . $row['Field'] . " (" . $row['Type'] . ")\n";
    }
    
    echo "\n2. Buscando Guarulhos...\n";
    $stmt = $pdo->prepare("SELECT * FROM ubicaciones WHERE nombre LIKE ?");
    $stmt->execute(['%Guarulhos%']);
    $guarulhos = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($guarulhos) {
        echo "   ✓ Encontrado!\n";
        foreach ($guarulhos as $k => $v) {
            echo "     $k: " . ($v !== null ? $v : 'NULL') . "\n";
        }
    } else {
        echo "   ✗ No encontrado\n\n";
        
        echo "3. Primeros 5 registros de ubicaciones:\n";
        $stmt = $pdo->query("SELECT id, nombre, latitud, longitud, tipo FROM ubicaciones LIMIT 5");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "   {$row['nombre']} (Lat: {$row['latitud']}, Lng: {$row['longitud']})\n";
        }
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
