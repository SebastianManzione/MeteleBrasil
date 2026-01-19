<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=metelebrasil;charset=utf8mb4', 'root', '');
    
    echo "=== ANÁLISIS COMPLETO TABLA ubicaciones ===\n\n";
    
    echo "1. Todas las columnas:\n";
    $stmt = $pdo->query('DESCRIBE ubicaciones');
    $campos = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "   " . $row['Field'] . " (" . $row['Type'] . ") " . ($row['Null'] === 'NO' ? 'NOT NULL' : 'NULL') . "\n";
        $campos[] = $row['Field'];
    }
    
    echo "\n2. Total de registros:\n";
    $stmt = $pdo->query("SELECT COUNT(*) as cnt FROM ubicaciones");
    $cnt = $stmt->fetch(PDO::FETCH_ASSOC)['cnt'];
    echo "   " . $cnt . " registros\n";
    
    echo "\n3. 10 primeros registros:\n";
    $stmt = $pdo->query("SELECT * FROM ubicaciones LIMIT 10");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "   ID: " . $row['idUbicacion'] . " | ";
        if (isset($row['nombre'])) echo "Nombre: " . $row['nombre'];
        if (isset($row['lat'])) echo " | Lat: " . $row['lat'];
        if (isset($row['longitud'])) echo " | Lng: " . $row['longitud'];
        echo "\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Código: " . $e->getCode();
}
?>
