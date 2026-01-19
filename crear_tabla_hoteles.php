<?php
echo "=== CREANDO TABLA HOTELES ===\n\n";

$mysqli = new mysqli('localhost', 'root', '', 'metelebrasil');

if ($mysqli->connect_error) {
    die("Error: " . $mysqli->connect_error);
}

echo "1. Conectado a metelebrasil\n";

// Leer SQL
$sql = file_get_contents(__DIR__ . '/migrations/011_crear_tabla_hoteles.sql');

echo "2. Ejecutando migración...\n";

if ($mysqli->multi_query($sql)) {
    echo "   ✓ Tabla 'hoteles' creada exitosamente\n\n";
    
    // Esperar a que se complete
    while ($mysqli->next_result()) {;}
    
    // Verificar
    $result = $mysqli->query("DESCRIBE hoteles");
    echo "3. Estructura de la tabla:\n";
    while ($row = $result->fetch_assoc()) {
        echo "   • {$row['Field']} ({$row['Type']})\n";
    }
    
    echo "\n✓✓✓ TABLA HOTELES LISTA PARA USAR\n";
    
} else {
    echo "   ✗ Error: " . $mysqli->error . "\n";
}

$mysqli->close();
?>
