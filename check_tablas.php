<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=metelebrasil;charset=utf8mb4', 'root', '');
    
    // Buscar tabla ubicacion
    echo "Buscando tabla 'ubicacion'...\n";
    $stmt = $pdo->query("SHOW TABLES LIKE 'ubicacion'");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        echo "✓ Tabla 'ubicacion' existe\n";
        echo "\nEstructura:\n";
        $stmt = $pdo->query('DESCRIBE ubicacion');
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "   " . $row['Field'] . " (" . $row['Type'] . ")\n";
        }
    } else {
        echo "✗ Tabla 'ubicacion' NO existe\n";
        echo "\nTablas que existen con 'ubicacion' en nombre:\n";
        $stmt = $pdo->query("SHOW TABLES LIKE '%ubicacion%'");
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            echo "   - " . $row[0] . "\n";
        }
    }
    
    echo "\n\nBuscando tabla 'terminal_transporte'...\n";
    $stmt = $pdo->query("SHOW TABLES LIKE 'terminal_transporte'");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        echo "✓ Tabla 'terminal_transporte' existe\n";
        echo "\nEstructura:\n";
        $stmt = $pdo->query('DESCRIBE terminal_transporte');
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "   " . $row['Field'] . " (" . $row['Type'] . ")\n";
        }
        
        echo "\nTotal de registros: ";
        $stmt = $pdo->query("SELECT COUNT(*) as cnt FROM terminal_transporte");
        echo $stmt->fetch(PDO::FETCH_ASSOC)['cnt'] . "\n";
    } else {
        echo "✗ Tabla 'terminal_transporte' NO existe\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
