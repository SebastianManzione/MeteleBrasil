<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=metelebrasil_experimental;charset=utf8mb4', 'root', '');
    
    echo "=== TABLAS DE HOTELES ===\n\n";
    
    // Buscar tablas relacionadas a hoteles
    $stmt = $pdo->query("SHOW TABLES LIKE '%hotel%'");
    $tablas = $stmt->fetchAll(PDO::FETCH_NUM);
    
    if (count($tablas) > 0) {
        echo "Tablas encontradas:\n";
        foreach ($tablas as $tabla) {
            echo "  - {$tabla[0]}\n";
            
            // Mostrar estructura
            $stmt = $pdo->query("DESCRIBE {$tabla[0]}");
            $campos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($campos as $campo) {
                echo "    • {$campo['Field']} ({$campo['Type']})\n";
            }
            
            // Contar registros
            $stmt = $pdo->query("SELECT COUNT(*) as cnt FROM {$tabla[0]}");
            $cnt = $stmt->fetch(PDO::FETCH_ASSOC)['cnt'];
            echo "    Total registros: $cnt\n\n";
        }
    } else {
        echo "No hay tablas de hoteles.\n\n";
        
        // Verificar tabla servicio (donde podrían estar hoteles)
        echo "Verificando tabla 'servicio'...\n";
        $stmt = $pdo->query("SHOW TABLES LIKE 'servicio'");
        if ($stmt->rowCount() > 0) {
            echo "✓ Tabla servicio existe\n";
            
            // Ver categorías de servicio
            $stmt = $pdo->query("SELECT * FROM categoria_servicio LIMIT 10");
            echo "\nCategorías de servicio:\n";
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "  {$row['idCategoria_servicio']}: {$row['nombre']}\n";
            }
        }
    }
    
    // Verificar ubicaciones de costa atlántica
    echo "\n=== UBICACIONES COSTA ATLÁNTICA ===\n\n";
    $ciudades_costa = ['San Clemente', 'Las Toninas', 'Mar del Tuyú', 'Santa Teresita', 'Mar de Ajó', 'Pinamar', 'Villa Gesell', 'Mar del Plata'];
    
    foreach ($ciudades_costa as $ciudad) {
        $stmt = $pdo->prepare("SELECT * FROM terminal_transporte WHERE ciudad LIKE ? OR nombre LIKE ?");
        $stmt->execute(["%$ciudad%", "%$ciudad%"]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            echo "✓ {$ciudad}: {$result['nombre']}\n";
        } else {
            echo "✗ {$ciudad}: NO existe\n";
        }
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
