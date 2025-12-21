<?php
// API para obtener servicios paginados en JSON
header('Content-Type: application/json; charset=utf-8');

session_start();

// Inicializar variables de sesión
if (!isset($_SESSION['idioma'])) $_SESSION['idioma'] = 'ES';
if (!isset($_SESSION['moneda_sel'])) $_SESSION['moneda_sel'] = 1; // USD por defecto
if (!isset($_SESSION['moneda_sel_sym'])) $_SESSION['moneda_sel_sym'] = '$';
if (!isset($_SESSION['impuestos_pais'])) $_SESSION['impuestos_pais'] = 0.21;
if (!isset($_SESSION['cupon_descuento'])) $_SESSION['cupon_descuento'] = array();

// Permitir override de moneda por parámetro GET
if (isset($_GET['moneda'])) {
    $_SESSION['moneda_sel'] = (int)$_GET['moneda'];
}

// Incluir todas las clases necesarias
require_once('admin/classes/conexion.php');
require_once('admin/classes/servicio.php');
require_once('admin/classes/salidas.php');
require_once('admin/classes/tarifas.php');
require_once('admin/classes/edades.php');
require_once('admin/classes/convierte_monedas.php');
require_once('admin/classes/functions.php');
require_once('admin/classes/fotos_servicio.php');
require_once('admin/classes/cancelaciones.php');
require_once('admin/classes/texto_miniaturas.php');
require_once('admin/classes/comisiones.php');

// Parámetros
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$cantidad_por_pagina = 5;
$desde = ($pagina - 1) * $cantidad_por_pagina;

// Obtener servicios
$servicios = getServiciosPaginado($desde, $cantidad_por_pagina);
$cantidad_total = count(getServicios());

// Procesar servicios con cálculo completo de tarifas
$servicios_procesados = array();
foreach ($servicios as $servicio) {
    $idServicio = $servicio["idServicio"];
    
    // Obtener salidas
    $consulta_salidas = "SELECT * FROM servicio_salidas WHERE idServicio=:idServicio ORDER BY fecha DESC LIMIT 1";
    $cmd_salidas = $pdo->prepare($consulta_salidas);
    $cmd_salidas->bindParam(":idServicio", $idServicio, PDO::PARAM_INT);
    $cmd_salidas->execute();
    $salidas = $cmd_salidas->fetchAll(PDO::FETCH_ASSOC);
    
    $precio = "Consultar";
    
    if (!empty($salidas)) {
        $tarifas = getTarifas($salidas[0]['idServicioSalidas']);
        if (!empty($tarifas) && isset($tarifas[0])) {
            try {
                $tarifa = calculaTarifa($tarifas[0]['idServicioSalidasTarifas'], 1);
                if (!empty($tarifa) && isset($tarifa[0])) {
                    $precio = $tarifa[0]["valorSym"];
                }
            } catch (Exception $e) {
                $precio = "Consultar";
            }
        }
    }
    
    $servicio['precio'] = $precio;
    $servicios_procesados[] = $servicio;
}

echo json_encode(array(
    'servicios' => $servicios_procesados,
    'pagina' => $pagina,
    'cantidad_total' => $cantidad_total,
    'cantidad_por_pagina' => $cantidad_por_pagina,
    'cantidad_paginas' => ceil($cantidad_total / $cantidad_por_pagina),
    'moneda_sel' => $_SESSION['moneda_sel'],
    'moneda_sel_sym' => $_SESSION['moneda_sel_sym']
));
?>
