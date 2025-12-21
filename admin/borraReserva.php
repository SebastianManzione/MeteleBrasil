<?php 

setlocale(LC_TIME, "es_ES");


include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");
require("classes/functions.php");
require("classes/categoria.php");
require("classes/texto_miniaturas.php");
require("classes/tipos_tarifa.php");
require("classes/accesibilidad.php");
require("classes/idiomas.php");
require("classes/reserva.php");
require("classes/edades.php");
require("classes/salidas.php");
require("classes/tarifas.php");
require("classes/tarifas_ubicacion.php");
require("classes/prestador.php");
require("classes/servicio.php"); 
require("classes/cancelaciones.php"); 
require("classes/fotos_servicio.php"); 
require("classes/servicios_adicionales.php");
require("classes/convierte_monedas.php");


$resul=eliminarReserva(688);
echo "******************************************************";
print_r($resul);
 ?>