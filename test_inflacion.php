<?php
session_start();
require_once("admin/classes/conexion.php");
require_once("admin/classes/salidas.php");
require_once("admin/classes/tarifas.php");
require_once("admin/classes/convierte_monedas.php");
require_once("admin/classes/edades.php");
require_once("admin/classes/cancelaciones.php");
require_once("admin/classes/comisiones.php");

// Simular sesión con ARS
$_SESSION['moneda_sel'] = 270;
$_SESSION['moneda_sel_sym'] = 'AR$';
$_SESSION['impuestos_pais'] = 0;

// Calcular tarifa 183834 con cantidad 1
$idTarifa = 183834;
$cantidad = 1;

echo "<h2>Test de Inflación - Tarifa $idTarifa con cantidad $cantidad</h2>";

$resultado = calculaTarifa($idTarifa, $cantidad);

echo "<pre>";
print_r($resultado);
echo "</pre>";

echo "<h3>Valor final: " . $resultado[0]['valor'] . "</h3>";
echo "<h3>Diferencia: " . $resultado[0]['redondeoDiferencia'] . "</h3>";
echo "<h3>Valor formateado: " . $resultado[0]['valorSym'] . "</h3>";
?>
