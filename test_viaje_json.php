<?php
require_once 'admin/classes/transporte.php';

$idViaje = 5;
$viaje = getViaje($idViaje);
$paradas = getParadasRuta($viaje['idRuta']);

echo "<h2>Test JSON Generation</h2>";
echo "<p>Viaje ID: $idViaje</p>";
echo "<p>Ruta ID: " . $viaje['idRuta'] . "</p>";
echo "<p>Total paradas: " . count($paradas) . "</p>";

echo "<h3>Paradas:</h3>";
echo "<pre>";
print_r($paradas);
echo "</pre>";

echo "<h3>JSON que se genera:</h3>";
echo "<textarea style='width:100%; height:300px;'>";

$paradasJSON = [];
foreach ($paradas as $p) {
    $paradasJSON[] = [
        'orden' => intval($p['orden']),
        'nombre' => strval($p['terminal_nombre']),
        'ciudad' => strval($p['ciudad']),
        'latitud' => floatval($p['latitud']),
        'longitud' => floatval($p['longitud']),
        'es_origen' => intval($p['es_origen']),
        'es_destino' => intval($p['es_destino'])
    ];
}

echo json_encode($paradasJSON, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT);

echo "</textarea>";

echo "<h3>Código JavaScript:</h3>";
echo "<code>var paradasViaje = ";
echo json_encode($paradasJSON, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
echo ";</code>";
?>
