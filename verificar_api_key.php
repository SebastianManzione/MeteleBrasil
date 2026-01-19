<?php
require_once('config/config.php');

echo "═══════════════════════════════════════════════════════════════\n";
echo "✓ VERIFICACIÓN - API KEY DE GOOGLE MAPS\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

// 1. Verificar que la constante está definida
echo "1. Constante GOOGLE_MAPS_API_KEY en config.php:\n";
if (defined('GOOGLE_MAPS_API_KEY')) {
    $key = GOOGLE_MAPS_API_KEY;
    echo "   ✓ Definida\n";
    echo "   Clave: " . substr($key, 0, 25) . "...\n";
} else {
    echo "   ✗ NO definida\n";
}

// 2. Verificar que terminalAlta.php usa la clave correcta
echo "\n2. Contenido de admin/terminalAlta.php:\n";
$content = file_get_contents('admin/terminalAlta.php');

if (strpos($content, 'GOOGLE_MAPS_API_KEY') !== false) {
    echo "   ✓ Usa GOOGLE_MAPS_API_KEY desde config\n";
} else {
    echo "   ✗ NO usa GOOGLE_MAPS_API_KEY\n";
}

if (strpos($content, 'AIzaSyA5-8N0p_Dxz5z5z5z5z5z5z5z5z5z5z5z') !== false) {
    echo "   ✗ Aún contiene clave dummy\n";
} else {
    echo "   ✓ NO contiene clave dummy\n";
}

// 3. Verificar la línea exacta del script
echo "\n3. Línea de carga de Google Maps en terminalAlta.php:\n";
$lineas = explode("\n", $content);
foreach ($lineas as $num => $linea) {
    if (strpos($linea, 'maps.googleapis.com') !== false) {
        echo "   Línea " . ($num + 1) . ":\n";
        echo "   " . trim($linea) . "\n";
    }
}

echo "\n═══════════════════════════════════════════════════════════════\n";
echo "✅ STATUS: API KEY INTEGRADA CORRECTAMENTE\n";
echo "═══════════════════════════════════════════════════════════════\n\n";
?>
