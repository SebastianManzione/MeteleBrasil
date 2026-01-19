<?php
echo "=== VERIFICANDO TABLA HOTELES CON MYSQLI ===\n\n";

$mysqli = new mysqli('localhost', 'root', '', 'metelebrasil');

if ($mysqli->connect_error) {
    die("Error de conexión: " . $mysqli->connect_error);
}

echo "✓ Conectado a metelebrasil\n\n";

// 1. Verificar si existe tabla hoteles
$result = $mysqli->query("SHOW TABLES LIKE 'hoteles'");
if ($result->num_rows > 0) {
    echo "✓ Tabla 'hoteles' existe\n\n";
    
    // 2. Estructura
    echo "ESTRUCTURA:\n";
    $result = $mysqli->query("DESCRIBE hoteles");
    while ($row = $result->fetch_assoc()) {
        echo "  {$row['Field']} ({$row['Type']})\n";
    }
    
    // 3. Total
    echo "\nTOTAL: ";
    $result = $mysqli->query("SELECT COUNT(*) as cnt FROM hoteles");
    $row = $result->fetch_assoc();
    echo $row['cnt'] . " hoteles\n\n";
    
    // 4. Ejemplos
    echo "EJEMPLOS (primeros 5):\n";
    $result = $mysqli->query("SELECT * FROM hoteles LIMIT 5");
    while ($row = $result->fetch_assoc()) {
        echo "  ID {$row['idHotel']}: {$row['nombre']}";
        if (isset($row['idServicio'])) echo " (idServicio: {$row['idServicio']})";
        echo "\n";
    }
    
} else {
    echo "✗ Tabla 'hoteles' NO existe\n";
    echo "\nBuscando tablas similares:\n";
    $result = $mysqli->query("SHOW TABLES");
    while ($row = $result->fetch_array()) {
        if (stripos($row[0], 'hotel') !== false || stripos($row[0], 'aloja') !== false) {
            echo "  - {$row[0]}\n";
        }
    }
}

$mysqli->close();
?>
