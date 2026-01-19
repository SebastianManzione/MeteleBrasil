<?php
require_once('config/config.php');
require_once('admin/classes/conexion.php');

echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║  CORRECCIÓN COMPLETA DE COORDENADAS FALTANTES             ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n\n";

// Datos completos de todos los terminales (con nombres exactos de BD)
$coordenadas_completas = [
    // Argentina
    'Aeroporto do Galeão' => [-22.8068, -43.2431],  // Rio de Janeiro
    'Aeroporto Hercílio Luz' => [-27.5352, -48.6949],  // Florianópolis
    'Aeroporto Salgado Filho' => [-29.1945, -51.1711],  // Porto Alegre
    'Aeropuerto Arturo Merino Benítez' => [-33.3899, -70.7854],  // Santiago
    'Aeropuerto Astor Piazzolla' => [-34.5895, -58.2158],  // Buenos Aires
    'Aeropuerto Bariloche' => [-41.1496, -71.3150],  // Bariloche
    'Aeropuerto Capitán Corbeta' => [-38.7450, -62.1650],  // Puerto Madryn
    'Aeropuerto de Carrasco' => [-34.8381, -56.1613],  // Montevideo
    'Aeropuerto El Plumerillo' => [-32.8312, -68.7939],  // Mendoza (mismo que "Governal")
    'Estación Retiro - Mitre' => [-34.5820, -58.3744],  // Buenos Aires
    'Rodoviária de Porto Alegre' => [-29.1619, -51.1914],  // Porto Alegre
    'Rodoviária Novo Rio' => [-22.9006, -43.1797],  // Rio de Janeiro
    'Terminal Alameda' => [-23.5505, -46.6333],  // São Paulo
    'Terminal Bariloche' => [-41.1342, -71.3079],  // Bariloche
    'Terminal Barra Funda' => [-23.5301, -46.6629],  // São Paulo
    'Terminal de Ómnibus Mariano Moreno' => [-32.9453, -60.6301],  // Rosario
    'Terminal del Sol Mendoza' => [-32.8853, -68.8451],  // Mendoza
    'Terminal Plaza Norte' => [-34.5700, -58.4500],  // Buenos Aires
    'Terminal Punta del Este' => [-34.2600, -55.0200],  // Punta del Este
    'Terminal Rita Maria' => [-29.1659, -51.1975],  // Porto Alegre
    'Terminal San Borja' => [-29.3467, -51.5333],  // Porto Alegre
    'Terminal Tietê' => [-23.5472, -46.6184],  // São Paulo
    'Terminal Tres Cruces' => [-34.8947, -56.1650],  // Montevideo
];

$actualizados = 0;

foreach ($coordenadas_completas as $nombre => $coords) {
    list($lat, $lng) = $coords;
    
    $stmt = $pdo->prepare('
        UPDATE ubicacion 
        SET latitud = ?, longitud = ? 
        WHERE tipo = ? AND nombre = ?
    ');
    
    $result = $stmt->execute([$lat, $lng, 'terminal', $nombre]);
    
    if ($stmt->rowCount() > 0) {
        echo "✓ " . $nombre . " → " . $lat . ", " . $lng . "\n";
        $actualizados++;
    } else {
        echo "✗ No encontrado: " . $nombre . "\n";
    }
}

echo "\n╔════════════════════════════════════════════════════════════╗\n";
echo "║  RESUMEN FINAL                                            ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n";
echo "Terminales actualizados: " . $actualizados . "\n\n";

// Verificación final
$stmt = $pdo->prepare('SELECT COUNT(*) as total, SUM(CASE WHEN latitud IS NOT NULL AND latitud != "" THEN 1 ELSE 0 END) as con_coords FROM ubicacion WHERE tipo = ?');
$stmt->execute(['terminal']);
$stats = $stmt->fetch(PDO::FETCH_ASSOC);

echo "ESTADÍSTICAS FINALES:\n";
echo "  Total de terminales: " . $stats['total'] . "\n";
echo "  Con coordenadas: " . $stats['con_coords'] . "\n";
echo "  Sin coordenadas: " . ($stats['total'] - $stats['con_coords']) . "\n\n";

if ($stats['con_coords'] == $stats['total']) {
    echo "✅ TODOS LOS TERMINALES TIENEN COORDENADAS CORRECTAS\n";
} else {
    echo "⚠️ Aún hay " . ($stats['total'] - $stats['con_coords']) . " terminales sin coordenadas\n";
}
?>
