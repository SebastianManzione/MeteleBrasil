<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config/config.php';

echo "=== DIAGNÓSTICO GUARULHOS ===\n\n";
echo "1. Conectando a BD...\n";

try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "   ✓ Conectado\n\n";

    echo "2. Estructura de tabla ubicacion:\n";
    $stmt = $pdo->query('DESC ubicacion');
    $campos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($campos as $campo) {
        echo "   - " . $campo['Field'] . " ({$campo['Type']})\n";
    }
    
    echo "\n3. Buscando Guarulhos...\n";
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
    } else {
        echo "   ✗ No encontrado\n";
        
        echo "\n4. Buscando São Paulo...\n";
        $stmt = $pdo->prepare("SELECT idUbicacion, nome, latitud, longitud FROM ubicacion WHERE cidade LIKE ? OR estado = 'São Paulo'");
        $stmt->execute(['%Paulo%']);
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "   Encontrados: " . count($resultados) . "\n";
    }

} catch (PDOException $e) {
    echo "   ✗ Error de BD: " . $e->getMessage() . "\n";
    echo "   Código: " . $e->getCode() . "\n";
} catch (Exception $e) {
    echo "   ✗ Error general: " . $e->getMessage() . "\n";
}
?>
