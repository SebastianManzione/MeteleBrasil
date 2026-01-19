<?php
require_once('config/config.php');
require_once('admin/classes/conexion.php');

echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║  CORRECCIÓN DE COORDENADAS DE TERMINALES                 ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n\n";

// Datos correctos de terminales (lat, lng por nombre)
$coordenadas_correctas = [
    'Terminal de Retiro' => [-34.5641, -58.3753],
    'Aeropuerto Ezeiza' => [-34.8222, -58.5358],
    'Aeropuerto Aeroparque' => [-34.5559, -58.4147],
    'Terminal Mar del Plata' => [-37.9577, -57.5565],
    'Aeropuerto de Mar del Plata' => [-37.9738, -57.5749],
    'Terminal Córdoba' => [-31.4100, -64.1808],
    'Aeropuerto Córdoba' => [-31.3136, -64.2043],
    'Terminal Mendoza' => [-32.8853, -68.8451],
    'Aeropuerto Governal Benjamín Matienzo' => [-32.8312, -68.7939],
    'Terminal Rosario' => [-32.9453, -60.6301],
    'Aeropuerto Rosario' => [-32.9132, -60.7661],
    'Terminal de Ómnibus Asunción' => [-25.2637, -57.5759],
    'Aeropuerto Silvio Pettirossi' => [-25.2396, -57.5183],
    'Aeroporto de Guarulhos' => [-23.4328, -46.4751],
    'Aeroporto de Congonhas' => [-23.6250, -46.4658],
    'Aeroporto Santos Dumont' => [-22.9068, -43.1729],
    'Terminal Rio de Janeiro' => [-22.8998, -43.2110],
    'Aeropuerto Jorge Chávez' => [-12.0219, -77.1144],
];

$actualizados = 0;
$vacios = 0;

foreach ($coordenadas_correctas as $nombre => $coords) {
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
echo "║  RESUMEN                                                  ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n";
echo "Terminales actualizados: " . $actualizados . "\n";
echo "Terminales con error: " . $vacios . "\n\n";

// Verificar que se actualizaron
echo "VERIFICACIÓN POST-ACTUALIZACIÓN:\n\n";
$stmt = $pdo->prepare('SELECT nombre, latitud, longitud FROM ubicacion WHERE tipo = ? ORDER BY nombre');
$stmt->execute(['terminal']);
$todos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sin_coords = 0;
foreach ($todos as $t) {
    if (empty($t['latitud']) || empty($t['longitud'])) {
        echo "❌ SIN COORDENADAS: " . $t['nombre'] . "\n";
        $sin_coords++;
    }
}

if ($sin_coords == 0) {
    echo "✓ TODOS LOS TERMINALES TIENEN COORDENADAS\n";
} else {
    echo "\n⚠️ Aún hay " . $sin_coords . " terminales sin coordenadas\n";
}
?>
