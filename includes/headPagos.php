<?php
session_start();
include("admin/classes/functions.php");
include("admin/classes/parametros.php");
include("admin/classes/geolocalizacion.php");
include("admin/classes/impuestos_pais.php");
$parametros = getParametros();

// verificamos la sesion creada

if (isset($_SESSION['idioma'])) {

  // si es true, se crea el require y la variable lang

  $lang = $_SESSION["idioma"];



  require "admin/lang/" . $lang . ".php";



  // si no hay sesion por default se carga el lenguaje espanol

} else {

  $_SESSION["idioma_bandera"] = 'img/countries/Brazil-icon.png';

  $_SESSION["idioma"] = "PT";

  require "admin/lang/PT.php";
}



$url = getUrlGeoUser();


if (!isset($_SESSION['geo'])) {
  $geo = (geoLocalizacionIp($url, 0, 0));
  $_SESSION['geo'] = $geo;
  $langd = $geo["lang"];
  $idPais = $geo["idPais"];
}





$impuestos_pais = getImpuestosPais($_SESSION['geo']['idPais']);

//echo "impuestos_pais".$impuestos_pais;



$_SESSION['impuestos_pais'] = $impuestos_pais;

if (!isset($_SESSION["moneda_sel"])) {

  $_SESSION['moneda_sel'] = 283;

  $_SESSION['moneda_sel_sym'] = 'R$';

  $monedaSelSym = 'R$';
}





?>

<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script> -->
<!-- <script src="./js/jquery.redirect.js"></script> -->
<script  src="./js/jquery-3.4.1.min.js"></script>
<script  src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<!DOCTYPE html>
<html lang="en">

<head>

  <title>Metele Brasil</title>

  <meta name="title" content="Metele Brasil" />
  <meta name="description" content="Actividades, traslados, entradas, visitas guiadas y excursiones en español en todo el mundo. Reserva online com precio mínimo garantizado." />
  <meta name="keywords" content="excursiones, visitas guiadas, tours, actividades, traslados, transfers, circuitos, guias turísticas, guias de viaje" />
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link rel="icon" href="img/favicon.ico" sizes="32x32">

  <!-- ESTILOS NECESARIOS -->

  <!-- FONT-AWESOME -->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <!-- FONT-AWESOME -->

  <!-- ANIMATE -->
  <link rel="stylesheet" href="css/animate.min.css">
  <!-- ANIMATE -->

  <!-- BOOTSTRAP V4-->
  <link href="css/bootstrap.css" rel="stylesheet">
  <!-- BOOTSTRAP V4 -->

  <!-- STYLES GENERALES -->
  <link href="css/styles.css" rel="stylesheet">
  <!-- STYLES GENERALES -->

  <!-- RESPONSIVE DESING-->
  <link href="css/responsive.css" rel="stylesheet">
  <!-- RESPONSIVE DESING -->

  <!-- ESTILOS CALENDARIO-->
  <link href="css/clnr.css" rel="stylesheet">
  <!-- ESTILOS CALENDARIO-->

  <!-- FUENTES-->
  <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,600,700" rel="stylesheet">
  <!-- FUENTES-->



  <!-- ESTILOS NECESARIOS -->
  <?= $parametros[0]["head"] ?>

</head>

<body id="page-top">

  <div id="bodyCarga"></div>

  <div id="body">
    <?= $parametros[0]["body"] ?>
    <!--HEADER PAGO SEGURO-->


    <section class="py-2 bg-primary" style="background-color: #029ce2 !important;">



      <div class="container">


        <div class="row">

          <div class='' style='width: unset;'>

            <img src="img/favicon.png">
          </div>
          <div class="col-lg-3 col-6">


            <a href="index" class="navbar-brand text-white">
              <h3> METELE BRASIL </h3>
            </a>

          </div>

          <div class="col-lg col-6">

            <p class="text-white text-pagos mb-0"> <i class="fa fa-lock mx-2 "></i><?= $lang["pago_seguro"] ?></p>

          </div>

        </div>

      </div>

    </section>