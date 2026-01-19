<?php
// Test directo del controller
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Test Controller AJAX</h1>";

// Simular POST
$_POST['accion'] = 'getClasesTarifas';
$_POST['idViaje'] = 5;
$_POST['idViajeClase'] = 1; // Asumiendo que existe

echo "<h2>Request:</h2>";
echo "<pre>";
print_r($_POST);
echo "</pre>";

echo "<h2>Response del Controller:</h2>";
echo "<pre>";

// Capturar output
ob_start();
include('admin/ctrl/ctrlTarifasViaje.php');
$output = ob_get_clean();

echo htmlspecialchars($output);
echo "</pre>";

echo "<h2>Parsed JSON:</h2>";
$json = json_decode($output, true);
if ($json) {
    echo "<pre>";
    print_r($json);
    echo "</pre>";
} else {
    echo "<p style='color:red;'>ERROR: No se pudo parsear JSON</p>";
    echo "<p>Error: " . json_last_error_msg() . "</p>";
}
?>
