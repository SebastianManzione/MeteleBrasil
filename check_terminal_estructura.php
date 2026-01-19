<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=metelebrasil_experimental;charset=utf8mb4', 'root', '');
    
    echo "=== ESTRUCTURA terminal_transporte ===\n\n";
    
    $stmt = $pdo->query('DESCRIBE terminal_transporte');
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "{$row['Field']} ({$row['Type']}) " . 
             ($row['Null'] === 'NO' ? 'NOT NULL' : 'NULL') . 
             ($row['Default'] ? " DEFAULT {$row['Default']}" : '') . "\n";
    }
    
    echo "\n=== DATOS DE UN TERMINAL ===\n\n";
    $stmt = $pdo->query("SELECT * FROM terminal_transporte WHERE idTerminal = 33");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($row) {
        foreach ($row as $k => $v) {
            echo "$k: " . ($v !== null ? $v : 'NULL') . "\n";
        }
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
