<?php
echo "=== MIGRACI\u00d3N: Agregar campos pais y estado ===\n\n";

try {
    $pdo = new PDO('mysql:host=localhost;dbname=metelebrasil_experimental;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // 1. Agregar columna pais
    echo "1. Agregando columna 'pais'...\n";
    $pdo->exec("ALTER TABLE terminal_transporte ADD COLUMN pais VARCHAR(100) NULL AFTER ciudad");
    echo "   \u2713 Agregada\n\n";
    
    // 2. Agregar columna estado
    echo "2. Agregando columna 'estado'...\n";
    $pdo->exec("ALTER TABLE terminal_transporte ADD COLUMN estado VARCHAR(200) NULL AFTER pais");
    echo "   \u2713 Agregada\n\n";
    
    // 3. Actualizar registros con datos conocidos
    echo "3. Actualizando datos existentes...\n";
    
    $updates = [
        ['Buenos Aires', 'Argentina', 'Buenos Aires'],
        ['C\u00f3rdoba', 'Argentina', 'C\u00f3rdoba'],
        ['Mendoza', 'Argentina', 'Mendoza'],
        ['Rosario', 'Argentina', 'Santa Fe'],
        ['S\u00e3o Paulo', 'Brasil', 'S\u00e3o Paulo'],
        ['Rio de Janeiro', 'Brasil', 'Rio de Janeiro'],
        ['Bras\u00edlia', 'Brasil', 'Distrito Federal'],
        ['Florian\u00f3polis', 'Brasil', 'Santa Catarina'],
        ['Asunci\u00f3n', 'Paraguay', 'Central'],
        ['Montevideo', 'Uruguay', 'Montevideo']
    ];
    
    $stmt = $pdo->prepare("
        UPDATE terminal_transporte 
        SET pais = :pais, estado = :estado
        WHERE ciudad = :ciudad AND pais IS NULL
    ");
    
    foreach ($updates as $data) {
        list($ciudad, $pais, $estado) = $data;
        $stmt->execute(['ciudad' => $ciudad, 'pais' => $pais, 'estado' => $estado]);
        $rows = $stmt->rowCount();
        if ($rows > 0) {
            echo "   \u2713 Actualizados $rows terminales de $ciudad\n";
        }
    }
    
    echo "\n\u2713\u2713\u2713 MIGRACI\u00d3N COMPLETADA\n\n";
    
    // 4. Verificar estructura
    echo "4. Estructura actualizada:\n";
    $stmt = $pdo->query("DESCRIBE terminal_transporte");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "   - {$row['Field']} ({$row['Type']})\n";
    }
    
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate column') !== false) {
        echo "\u2713 Las columnas ya existen, omitiendo...\n";
    } else {
        echo "\u2717 Error: " . $e->getMessage() . "\n";
    }
}
?>
