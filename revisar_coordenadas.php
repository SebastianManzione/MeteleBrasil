<?php
require_once('config/config.php');
require_once('admin/classes/conexion.php');

echo "═══════════════════════════════════════════════════════════════\n";
echo "VERIFICACIÓN DE COORDENADAS EN BD\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

// Buscar Guarulhos
$stmt = $pdo->prepare('SELECT idUbicacion, nombre, ciudad, pais, latitud, longitud FROM ubicacion WHERE tipo = ? AND nombre LIKE ? LIMIT 1');
$stmt->execute(['terminal', '%Guarulhos%']);
$resultado = $stmt->fetch(PDO::FETCH_ASSOC);

if ($resultado) {
    echo "ENCONTRADO: " . $resultado['nombre'] . "\n";
    echo "Ciudad: " . $resultado['ciudad'] . "\n";
    echo "País: " . $resultado['pais'] . "\n";
    echo "ID: " . $resultado['idUbicacion'] . "\n\n";
    echo "COORDENADAS ACTUALES:\n";
    echo "  Latitud: " . $resultado['latitud'] . "\n";
    echo "  Longitud: " . $resultado['longitud'] . "\n\n";
    
    // Coordenadas correctas de Guarulhos
    echo "COORDENADAS CORRECTAS (Aeropuerto Guarulhos):\n";
    echo "  Latitud: -23.4328 (São Paulo, Brasil)\n";
    echo "  Longitud: -46.4751\n\n";
    
    echo "Verificación: https://maps.google.com/?q=-23.4328,-46.4751\n";
} else {
    echo "❌ No se encontró Guarulhos\n\n";
    echo "Buscando todos los terminales con 'Paulo' o 'Brasil':\n\n";
    
    $stmt = $pdo->prepare('SELECT idUbicacion, nombre, ciudad, pais, latitud, longitud FROM ubicacion WHERE tipo = ? ORDER BY nombre');
    $stmt->execute(['terminal']);
    $todos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($todos as $t) {
        echo "ID " . $t['idUbicacion'] . " | " . $t['nombre'] . " (" . $t['ciudad'] . ", " . $t['pais'] . ")\n";
        echo "  Coords: " . $t['latitud'] . ", " . $t['longitud'] . "\n\n";
    }
}
?>
