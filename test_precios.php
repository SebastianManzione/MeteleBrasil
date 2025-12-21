<?php
// Script para verificar precios en API
session_start();
if (!isset($_SESSION['idioma'])) $_SESSION['idioma'] = 'ES';
if (!isset($_SESSION['moneda_sel'])) $_SESSION['moneda_sel'] = 1;
if (!isset($_SESSION['moneda_sel_sym'])) $_SESSION['moneda_sel_sym'] = '$';
if (!isset($_SESSION['impuestos_pais'])) $_SESSION['impuestos_pais'] = 0.21;
if (!isset($_SESSION['cupon_descuento'])) $_SESSION['cupon_descuento'] = array();

require_once('admin/classes/conexion.php');
require_once('admin/classes/servicio.php');
require_once('admin/classes/tarifas.php');
require_once('admin/classes/salidas.php');

$servicios = getServiciosPaginado(0, 5);

echo "Servicios y Precios:\n";
echo "===================\n";

foreach ($servicios as $s) {
    $idServicio = $s["idServicio"];
    $consulta_salidas = "SELECT * FROM servicio_salidas WHERE idServicio=:idServicio ORDER BY fecha DESC LIMIT 1";
    $cmd_salidas = $pdo->prepare($consulta_salidas);
    $cmd_salidas->bindParam(":idServicio", $idServicio, PDO::PARAM_INT);
    $cmd_salidas->execute();
    $salidas = $cmd_salidas->fetchAll(PDO::FETCH_ASSOC);
    
    $precio = "Consultar";
    if (!empty($salidas)) {
        $tarifas = getTarifas($salidas[0]['idServicioSalidas']);
        if (!empty($tarifas)) {
            echo $s["nombre_servicio"] . " - Tarifa ID: " . $tarifas[0]['idServicioSalidasTarifas'] . " - Valor base: " . $tarifas[0]['valor'] . "\n";
        }
    }
    echo "  Precio: $precio\n\n";
}
?>
