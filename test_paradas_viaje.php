<?php
require_once('admin/classes/transporte.php');

$idViaje = 5;
$viaje = getViaje($idViaje);

if (!empty($viaje)) {
    $idRuta = $viaje['idRuta'];
    $paradas = getParadasRuta($idRuta);
    
    echo "=== VIAJE #$idViaje ===\n";
    echo "Ruta ID: $idRuta\n";
    echo "Ruta: {$viaje['ruta_nombre']}\n";
    echo "Total paradas: " . count($paradas) . "\n\n";
    
    echo "=== PARADAS ===\n";
    foreach ($paradas as $p) {
        echo "Orden: {$p['orden']}, Nombre: {$p['terminal_nombre']}, ";
        echo "Lat: {$p['latitud']}, Lng: {$p['longitud']}\n";
    }
    
    echo "\n=== JSON GENERADO ===\n";
    $paradasJSON = array_map(function($p) {
        return [
            'orden' => (int)$p['orden'],
            'nombre' => $p['terminal_nombre'],
            'ciudad' => $p['ciudad'],
            'latitud' => (float)$p['latitud'],
            'longitud' => (float)$p['longitud'],
            'es_origen' => (int)$p['es_origen'],
            'es_destino' => (int)$p['es_destino']
        ];
    }, $paradas);
    
    echo json_encode($paradasJSON, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
} else {
    echo "ERROR: Viaje no encontrado\n";
}
