<?php
// Debug completo - mostrar TODO lo que se genera
require_once("admin/classes/transporte.php");
require_once("config/config.php");

$viaje = getViaje(5);
$paradas = getParadasRuta($viaje['idRuta']);

echo "<!DOCTYPE html>\n<html>\n<head>\n<title>DEBUG MAPA</title>\n</head>\n<body>\n";
echo "<h1>DEBUG MAPA - Servicio Contransporte</h1>\n";

echo "<h2>1. Verificar paradas:</h2>\n";
echo "<pre>\n";
var_dump($paradas);
echo "</pre>\n";

echo "<h2>2. Verificar JSON generado:</h2>\n";
$json = json_encode(array_map(function($p) {
    return [
        'orden' => (int)$p['orden'],
        'nombre' => $p['terminal_nombre'],
        'ciudad' => $p['ciudad'],
        'latitud' => (float)$p['latitud'],
        'longitud' => (float)$p['longitud'],
        'es_origen' => (int)$p['es_origen'],
        'es_destino' => (int)$p['es_destino']
    ];
}, $paradas), JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);

echo "<textarea rows='10' cols='100'>" . $json . "</textarea>\n";

echo "<h2>3. Verificar API Key:</h2>\n";
echo "<pre>\n";
echo "API Key definida: " . (defined('GOOGLE_MAPS_API_KEY') ? 'SI' : 'NO') . "\n";
if (defined('GOOGLE_MAPS_API_KEY')) {
    echo "Valor: " . GOOGLE_MAPS_API_KEY . "\n";
}
echo "</pre>\n";

echo "<h2>4. HTML generado:</h2>\n";
echo "<pre id='html-generated'></pre>\n";

echo "<h2>5. TEST MAPA AQUI:</h2>\n";
echo "<div id='mapRecorrido' style='width:100%; height:500px; border:3px solid red;'></div>\n";

echo "<script>\n";
echo "console.clear();\n";
echo "console.log('=== INICIANDO DEBUG ===');\n";
echo "var paradasViaje = " . $json . ";\n";
echo "console.log('1. Paradas cargadas:', paradasViaje.length);\n";
echo "console.log('2. Datos:', paradasViaje);\n";
echo "console.log('3. API Key disponible:', typeof GOOGLE_MAPS_API_KEY);\n";

echo "function inicializarMapa() {\n";
echo "  console.log('=== CALLBACK GOOGLE MAPS EJECUTADO ===');\n";
echo "  console.log('Google disponible?', typeof google !== 'undefined');\n";
echo "  console.log('google.maps disponible?', typeof google.maps !== 'undefined');\n";
echo "  \n";
echo "  if (!paradasViaje || paradasViaje.length === 0) {\n";
echo "    console.error('ERROR: No hay paradas');\n";
echo "    return;\n";
echo "  }\n";
echo "  \n";
echo "  var mapContainer = document.getElementById('mapRecorrido');\n";
echo "  if (!mapContainer) {\n";
echo "    console.error('ERROR: Contenedor no encontrado');\n";
echo "    return;\n";
echo "  }\n";
echo "  \n";
echo "  console.log('Creando mapa...');\n";
echo "  var map = new google.maps.Map(mapContainer, {\n";
echo "    zoom: 6,\n";
echo "    center: {lat: paradasViaje[0].latitud, lng: paradasViaje[0].longitud}\n";
echo "  });\n";
echo "  console.log('✓ Mapa creado');\n";
echo "}\n";

echo "</script>\n";

echo "<script async defer src='https://maps.googleapis.com/maps/api/js?key=" . GOOGLE_MAPS_API_KEY . "&callback=inicializarMapa'></script>\n";

echo "</body>\n</html>\n";
