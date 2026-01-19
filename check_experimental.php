<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== VERIFICACIÓN BASE DE DATOS EXPERIMENTAL ===\n\n";

try {
    // Conectar a metelebrasil_experimental
    $pdo = new PDO('mysql:host=localhost;dbname=metelebrasil_experimental;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✓ Conectado a metelebrasil_experimental\n\n";

    // Buscar tablas relacionadas a terminales
    echo "1. Tablas relacionadas:\n";
    $stmt = $pdo->query("SHOW TABLES LIKE '%terminal%'");
    while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
        echo "   - {$row[0]}\n";
    }
    
    $stmt = $pdo->query("SHOW TABLES LIKE '%ubicacion%'");
    while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
        echo "   - {$row[0]}\n";
    }

    // Verificar tabla terminal_transporte
    echo "\n2. Tabla terminal_transporte:\n";
    $stmt = $pdo->query("SHOW TABLES LIKE 'terminal_transporte'");
    if ($stmt->rowCount() > 0) {
        echo "   ✓ Existe\n";
        
        echo "\n   Estructura:\n";
        $stmt = $pdo->query('DESCRIBE terminal_transporte');
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "   - {$row['Field']} ({$row['Type']})\n";
        }
        
        echo "\n   Total registros: ";
        $stmt = $pdo->query("SELECT COUNT(*) as cnt FROM terminal_transporte");
        $cnt = $stmt->fetch(PDO::FETCH_ASSOC)['cnt'];
        echo "$cnt\n";
        
        if ($cnt > 0) {
            echo "\n3. Ejemplo de datos (primeros 3):\n";
            $stmt = $pdo->query("SELECT idTerminal, nombre, ciudad, latitud, longitud FROM terminal_transporte LIMIT 3");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "   ID {$row['idTerminal']}: {$row['nombre']} - {$row['ciudad']}\n";
                echo "      Coords: ({$row['latitud']}, {$row['longitud']})\n";
            }
        }
    } else {
        echo "   ✗ NO existe\n";
    }

} catch (PDOException $e) {
    echo "✗ Error de BD: " . $e->getMessage() . "\n";
}
?>
