<?php
/**
 * Verificar si el JSON de paradas se genera correctamente
 */
require_once("admin/classes/transporte.php");

$viaje = getViaje(5);
$paradas = getParadasRuta($viaje['idRuta']);

echo "=== VERIFICAR JSON ===\n\n";

// Generar exactamente como en servicio_contransporte.php
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

$json = json_encode($paradasJSON, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);

echo "JSON generado:\n";
echo $json . "\n\n";

echo "Verificación JSON:\n";
$decoded = json_decode($json, true);
if ($decoded === null) {
    echo "ERROR: JSON inválido\n";
    echo "Error: " . json_last_error_msg() . "\n";
} else {
    echo "OK: JSON válido\n";
    echo "Elementos: " . count($decoded) . "\n";
}

echo "\n=== SCRIPT QUE SE GENERARÍA ===\n";
echo "<script>\n";
echo "var paradasViaje = " . $json . ";\n";
echo "console.log('Paradas:', paradasViaje);\n";
echo "</script>\n";

// Verificar cierre de script
echo "\n=== VERIFICAR CIERRE ===\n";
$script = "<script>\nvar paradasViaje = " . $json . ";\nconsole.log('Paradas:', paradasViaje);\n</script>";
$count_open = substr_count($script, '<script');
$count_close = substr_count($script, '</script');
echo "Script tags abiertos: $count_open\n";
echo "Script tags cerrados: $count_close\n";

if ($count_open !== $count_close) {
    echo "ERROR: Mismatch de script tags!\n";
}
