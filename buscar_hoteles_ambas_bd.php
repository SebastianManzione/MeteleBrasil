<?php
echo "=== BUSCANDO HOTELES EN AMBAS BDs ===\n\n";

// BD Principal
try {
    echo "1. Base de datos: metelebrasil\n";
    $pdo1 = new PDO('mysql:host=localhost;dbname=metelebrasil;charset=utf8mb4', 'root', '');
    
    // Buscar tablas de hoteles
    $stmt = $pdo1->query("SHOW TABLES LIKE '%hotel%'");
    $tablas = $stmt->fetchAll(PDO::FETCH_NUM);
    echo "   Tablas con 'hotel': " . count($tablas) . "\n";
    foreach ($tablas as $t) {
        echo "   - {$t[0]}\n";
    }
    
    // Buscar en servicio
    $stmt = $pdo1->query("SELECT COUNT(*) as cnt FROM servicio");
    $cnt = $stmt->fetch(PDO::FETCH_ASSOC)['cnt'];
    echo "   Servicios totales: $cnt\n";
    
    // Ver categorías
    $stmt = $pdo1->query("DESCRIBE categoria_servicio");
    $campos = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "   Campos categoria_servicio: " . implode(', ', $campos) . "\n";
    
    $stmt = $pdo1->query("SELECT * FROM categoria_servicio LIMIT 10");
    echo "   Categorías:\n";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "     - ID {$row['idCategoria_servicio']}: ";
        if (isset($row['nombre'])) echo $row['nombre'];
        if (isset($row['categoria'])) echo $row['categoria'];
        echo "\n";
    }
    
} catch (Exception $e) {
    echo "   Error: " . $e->getMessage() . "\n";
}

echo "\n";

// BD Experimental
try {
    echo "2. Base de datos: metelebrasil_experimental\n";
    $pdo2 = new PDO('mysql:host=localhost;dbname=metelebrasil_experimental;charset=utf8mb4', 'root', '');
    
    $stmt = $pdo2->query("SHOW TABLES LIKE '%hotel%'");
    $tablas = $stmt->fetchAll(PDO::FETCH_NUM);
    echo "   Tablas con 'hotel': " . count($tablas) . "\n";
    foreach ($tablas as $t) {
        echo "   - {$t[0]}\n";
    }
    
    $stmt = $pdo2->query("SELECT COUNT(*) as cnt FROM servicio");
    $cnt = $stmt->fetch(PDO::FETCH_ASSOC)['cnt'];
    echo "   Servicios totales: $cnt\n";
    
} catch (Exception $e) {
    echo "   Error: " . $e->getMessage() . "\n";
}
?>
