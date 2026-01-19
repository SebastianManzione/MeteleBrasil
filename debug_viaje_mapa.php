<?php
require_once("admin/classes/transporte.php");
require_once("config/config.php");

$idViaje = 5;
$viaje = getViaje($idViaje);

if (!empty($viaje)) {
    $idRuta = $viaje['idRuta'];
    $paradas = getParadasRuta($idRuta);
    
    echo "=== DEBUG COMPLETO ===\n\n";
    
    echo "1. DATOS DEL VIAJE:\n";
    echo "   ID Viaje: $idViaje\n";
    echo "   ID Ruta: $idRuta\n";
    echo "   Nombre Ruta: {$viaje['ruta_nombre']}\n\n";
    
    echo "2. PARADAS CARGADAS:\n";
    echo "   Total: " . count($paradas) . "\n\n";
    
    if (!empty($paradas)) {
        foreach ($paradas as $p) {
            echo "   Parada {$p['orden']}:\n";
            echo "     - Nombre: {$p['terminal_nombre']}\n";
            echo "     - Ciudad: {$p['ciudad']}\n";
            echo "     - Latitud: {$p['latitud']}\n";
            echo "     - Longitud: {$p['longitud']}\n";
            echo "     - Es origen: {$p['es_origen']}\n";
            echo "     - Es destino: {$p['es_destino']}\n\n";
        }
    }
    
    echo "3. JSON QUE SE GENERARÍA:\n";
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
    
    echo "\n\n4. VERIFICAR API KEY:\n";
    echo "   Google Maps API Key: " . (defined('GOOGLE_MAPS_API_KEY') ? 'DEFINIDA' : 'NO DEFINIDA') . "\n";
    if (defined('GOOGLE_MAPS_API_KEY')) {
        echo "   Valor: " . GOOGLE_MAPS_API_KEY . "\n";
    }
    
    echo "\n5. SCRIPT GENERADO:\n";
    echo "<script>\n";
    echo "var paradasViaje = " . json_encode($paradasJSON, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK) . ";\n";
    echo "console.log('Paradas:', paradasViaje);\n";
    echo "</script>\n";
    
} else {
    echo "ERROR: Viaje no encontrado\n";
}
