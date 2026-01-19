<?php
// Test de viaje 5
require_once("admin/classes/transporte.php");

$idViaje = 5;
$viaje = getViaje($idViaje);

echo "<h2>Viaje #5</h2>";
echo "<pre>" . json_encode($viaje, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "</pre>";

if (!empty($viaje)) {
    echo "<h3>tipo_tarifa: " . ($viaje['tipo_tarifa'] ?? 'NO DEFINIDO') . "</h3>";
    
    $idRuta = $viaje['idRuta'];
    $ruta = getRuta($idRuta);
    echo "<h3>Ruta #$idRuta</h3>";
    echo "<pre>" . json_encode($ruta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "</pre>";
    
    // Si tipo_tarifa = 'clases'
    if (isset($viaje['tipo_tarifa']) && $viaje['tipo_tarifa'] === 'clases') {
        echo "<h3>Clases disponibles:</h3>";
        $clases = getViajeClasesServicio($idViaje);
        echo "<pre>" . json_encode($clases, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "</pre>";
        
        if (!empty($clases)) {
            foreach ($clases as $clase) {
                echo "<h4>Clase: " . $clase['nombre_clase'] . " (ID: " . $clase['idViajeClase'] . ")</h4>";
                $tarifas = getViajeClaseTarifas($clase['idViajeClase']);
                echo "<pre>" . json_encode($tarifas, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "</pre>";
            }
        }
    } else {
        echo "<h3>Sistema SEGMENTADO - viaje_tarifa</h3>";
        $tarifas = getTarifasViaje($idViaje);
        echo "<pre>" . json_encode($tarifas, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "</pre>";
    }
}
?>
