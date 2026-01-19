<?php
// Extraer todo el JavaScript de servicio_contransporte.php y verificarlo
require_once("admin/classes/transporte.php");

$viaje = getViaje(5);
$paradas = getParadasRuta($viaje['idRuta']);

// Variable JavaScript 1
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

$json1 = json_encode($paradasJSON, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);

echo "<!DOCTYPE html>\n";
echo "<html>\n<head>\n<title>Debug JavaScript</title>\n</head>\n<body>\n";
echo "<h1>Verificar JavaScript generado</h1>\n";
echo "<pre>\n";

echo "SCRIPT 1 - Variable paradasViaje:\n";
echo "var paradasViaje = " . $json1 . ";\n";
echo "console.log('Paradas:', paradasViaje);\n\n";

echo "SCRIPT 2 - Document Ready:\n";
echo "$(document).ready(function() {\n";
echo "  console.log('Ready');\n";
echo "});\n\n";

echo "SCRIPT 3 - inicializarMapa:\n";
echo "function inicializarMapa() {\n";
echo "  console.log('Callback');\n";
echo "  if (typeof initMapRecorrido === 'function') {\n";
echo "    initMapRecorrido();\n";
echo "  }\n";
echo "}\n\n";

echo "SCRIPT 4 - initMapRecorrido:\n";
echo "function initMapRecorrido() {\n";
echo "  console.log('Inicializando mapa');\n";
echo "  // ... resto de código\n";
echo "}\n";

echo "</pre>\n";

// Ahora vamos a mostrar el HTML generado
echo "<h2>HTML Generado:</h2>\n";
echo "<textarea rows='30' cols='100' readonly>\n";
?>
<script>
// Variable global con las paradas del viaje
var paradasViaje = <?php 
    echo json_encode($paradasJSON, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
?>;
console.log('=== PARADAS DEL VIAJE ===');
console.log('Total paradas:', paradasViaje.length);
console.log('Datos completos:', paradasViaje);
</script>


<script>
$(document).ready(function() {
    console.log('Document ready');
});
</script>

<!-- Callback para Google Maps (DEBE estar en scope global) -->
<script>
function inicializarMapa() {
    console.log('=== Google Maps callback ejecutado ===');
    if (typeof initMapRecorrido === 'function') {
        initMapRecorrido();
    } else {
        console.error('ERROR: initMapRecorrido no está definida');
    }
}
</script>
<?php
echo "</textarea>\n";
echo "</body>\n</html>\n";
