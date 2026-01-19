<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== DIAGNÓSTICO GUARULHOS (LOCAL) ===\n\n";

try {
    // Conexión local (desarrollo)
    $pdo = new PDO(
        'mysql:host=localhost;dbname=metelebrasil;charset=utf8mb4',
        'root',
        ''
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✓ Conectado a metelebrasil (local)\n\n";

    echo "1. Estructura de tabla ubicacion:\n";
    $stmt = $pdo->query('DESCRIBE ubicacion');
    $campos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($campos as $campo) {
        echo "   - " . $campo['Field'] . " ({$campo['Type']})\n";
    }
    
    echo "\n2. Buscando Guarulhos...\n";
    $stmt = $pdo->prepare("SELECT * FROM ubicacion WHERE nombre LIKE ?");
    $stmt->execute(['%Guarulhos%']);
    $guarulhos = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($guarulhos) {
        echo "   ✓ Encontrado:\n";
        echo "     ID: " . $guarulhos['idUbicacion'] . "\n";
        echo "     Nombre: " . $guarulhos['nombre'] . "\n";
        echo "     Latitud: " . ($guarulhos['latitud'] ?? 'NULL') . "\n";
        echo "     Longitud: " . ($guarulhos['longitud'] ?? 'NULL') . "\n";
        echo "     Ciudad: " . ($guarulhos['ciudad'] ?? 'N/A') . "\n";
        echo "     Estado: " . ($guarulhos['estado'] ?? 'N/A') . "\n";
    } else {
        echo "   ✗ No encontrado - buscando en tabla...\n";
        
        $stmt = $pdo->prepare("SELECT idUbicacion, nombre, latitud, longitud FROM ubicacion WHERE tipo='terminal' LIMIT 5");
        $stmt->execute();
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "   Primeros 5 terminales:\n";
        foreach ($resultados as $r) {
            echo "   - {$r['nombre']} (Lat: {$r['latitud']}, Lng: {$r['longitud']})\n";
        }
    }

} catch (PDOException $e) {
    echo "✗ Error de BD: " . $e->getMessage() . "\n";
    echo "  Código: " . $e->getCode() . "\n";
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}
?>
