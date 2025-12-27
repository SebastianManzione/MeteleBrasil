<?php

// Session will be started by navbar.php when included
// Initialize session variables with defaults if not set
if (!isset($_SESSION["idioma"])) {
    $_SESSION["idioma"] = "es";
}
if (!isset($_SESSION["idioma_bandera"])) {
    $_SESSION["idioma_bandera"] = "es.png";
}
if (!isset($_SESSION["moneda_sel_sym"])) {
    $_SESSION["moneda_sel_sym"] = "USD";
}
if (!isset($_SESSION["impuestos_pais"])) {
    $_SESSION["impuestos_pais"] = 0;
}
if (!isset($_SESSION["cupon_descuento"])) {
    $_SESSION["cupon_descuento"] = [];
}

include('includes/navbar.php');

// Initialize $lang if not defined by navbar.php
if (!isset($lang)) {
    $lang = [
        "actividades_en" => "actividades en",
        "espanol" => "Español",
        "ingles" => "English",
        "portugues" => "Português"
    ];
}

include('admin/classes/categoria.php');
include('admin/classes/fotos_categoria.php');
include('admin/classes/opiniones_categoria.php');
require_once('admin/classes/servicio.php');
require_once('admin/classes/servicio_opiniones.php');
require_once('admin/classes/salidas.php');
require_once('admin/classes/fotos_servicio.php');
require_once('admin/classes/tarifas.php');
require_once('admin/classes/cancelaciones.php');
require("admin/classes/texto_miniaturas.php");


$busqueda = "";
$cantidad_por_pagina = 5;
$desde = 0;
$pagina=1;
$orden = isset($_GET['orden']) ? $_GET['orden'] : '';
if (isset($_GET['pagina'])) {
  $pagina = $_GET['pagina'];
  if ($pagina == 0) {
    $desde = 0;
  } else {
    $desde = ($pagina - 1) * $cantidad_por_pagina;
  }
}

if (isset($_GET["idCategoria"]) && $_GET['idCategoria'] > 0) {



  $idCategoria = $_GET["idCategoria"];

  $categorias = getCategoria($idCategoria);



  $servicios = getServiciosidCategoria_servicioPaginado($idCategoria, $desde, $cantidad_por_pagina);
  $cantidad_servicios_categoria = count(getServiciosidCategoria_servicio($idCategoria));


  $nViajeros = $categorias[0]["nViajeros"];

  $id = $categorias[0]["idCategoria_servicio"];

  $nombre_categoria = $categorias[0]["nombre_categoria_servicio"];

  $opiniones_categoria = OpinionesCategoria($id);

  $cantidad_opiniones_categoria = count($opiniones_categoria);

  $fotos = $categorias[0]["img_categoria_servicio"];
} else if (isset($_GET["buscar"])) {

  $busqueda = $_GET["buscar"];

  $idCategoria = 0;



  $servicios = getServiciosBusquedaPaginada($_GET["buscar"], $desde, $cantidad_por_pagina);

  $cantidad_servicios_categoria = count(getServiciosBusqueda($_GET["buscar"]));



  $categorias = getCategorias();

  $nViajeros = rand(690, 1200);

  $nombre_categoria = "todas_las_categorias";

  $opiniones_categoria = array();

  $cantidad_opiniones_categoria = rand(100, 500);;

  $fotos = "sinCategoria.jpg";
} else {

  $idCategoria = 0;
  $desde = 0;
  $pagina = 1;
  if (isset($_GET['pagina'])) {
    $pagina = (int)$_GET['pagina'];
    if ($pagina < 1) $pagina = 1;
    $desde = ($pagina - 1) * $cantidad_por_pagina;
  }
  // Get total count for pagination
  $cantidad_servicios_categoria = count(getServicios());

  // Get only the services for this page
  $servicios = getServiciosPaginado($desde, $cantidad_por_pagina);

  $nViajeros = rand(690, 1200);

  $nombre_categoria = "todas_las_categorias";

  $opiniones_categoria = array();

  $cantidad_opiniones_categoria = rand(100, 500);;

  $fotos = "sinCategoria.jpg";
}











?>

<!--SECCION HEADER-->

<section id="header-destinos" style="background-image: url('admin/img/categoria_servicio/<?= $fotos; ?> ');">

  <div class="container mb-5">

    <div class="row mb-4">

      <div class="col-lg-12">



        <!--BUCLE DE LOS RESULTADOS AQUI-->

        <div class="badge badge-primary badge-ciudad"><?php //echo NombreCategoria($id); 
                                                      ?></div>

        <!--FIN BUCLE DE LOS RESULTADOS AQUI-->



        <!--TITULO-->

        <h1 class="text-white titulo-categoria py-2 bold texto-shadow"><?= isset($lang[$nombre_categoria]) ? $lang[$nombre_categoria] : $nombre_categoria; ?></h1>

        <!--TITULO-->





        <!--DESPLIEGUE DE LISTA

        <ul class="lista-ciudad d-md-block d-none">

          <li><a href="#" class="text-white texto-shadow">Actividades</a></li>

          <li><a href="#" class="text-white texto-shadow">Visitas guiadas</a></li>

          <li><a href="#" class="text-white texto-shadow">Excursiones</a></li>

          <li><a href="#" class="text-white texto-shadow">Traslados aeropuerto</a></li>

        </ul>

    FIN DESPLIEGUE DE LISTA-->



      </div>

    </div>

  </div>

  <!--header-->







  <!-- CONTENEDOR DE CARACTERISTICAS-->

  <div class="container z-index d-md-block d-none ">

    <div class="row text-white">

      <div class="col-lg-3 col-md-3">

        <h2 class="title-numeros mb-0 bold"><?= $cantidad_servicios_categoria; ?></h2>

        <p class="text-d-number"> <?= isset($lang[$nombre_categoria]) ? $lang[$nombre_categoria] : $nombre_categoria; ?></p>

      </div>

      <div class="col-lg-3 col-md-3">

        <h2 class="title-numeros mb-0 bold"><?= $nViajeros; ?></h2>

        <p class="text-d-number"><?= $lang["viajeros_lo_han_disfrutado"] ?></p>

      </div>

      <div class="col-lg-3 col-md-3">

        <h2 class="title-numeros mb-0 bold"><?= $cantidad_opiniones_categoria; ?></h2>

        <p class="text-d-number"><?= $lang["opiniones_reales"] ?></p>

      </div>

      <div class="col-lg-3 col-md-3">

        <h2 class="title-numeros mb-0 bold">9,2</h2>

        <p class="text-d-number"><?= $lang["asi_nos_puntuan"] ?></p>

      </div>

    </div>

  </div>

  <!-- FIN CONTENEDOR DE CARACTERISTICAS-->



  <br>



  <!-- CONTENEDOR DE FRANJA TRANSPARENTE-->

  <div class="container-fluid d-md-block d-none">

    <div class="row">

      <div class="col-lg-12">

        <div class="div-fondo"></div>

      </div>

    </div>

  </div>

  <!-- FIN CONTENEDOR DE FRANJA TRANSPARENTE-->



</section>

<!--FIN SECCION HEADER-->







<!--SECCION ACTIVIDADES-->



<section class="mt-4">

  <div class="container">

    <div class="row">



      <!--COLUMNA DERECHA DE BUSQUEDA-->

      <div class="col-lg-4 d-md-block d-none">



        <!--CARD PRINCIPAL DE BUSQUEDA-->

        <div class="card card-seccion-right  ">

          <div class="card-body">

            <div>

              <form class="form-buscar mb-5 " action="categorias.php" method="get">

                <label class="sr-only" for="s"><?= $lang["que_hacemos"]; ?></label>

                <div class="input-group">

                  <input class="field form-control form-control-search" name="buscar" type="text" placeholder="<?= $lang["que_hacemos"]; ?>" value="<?= $busqueda ?>">

                  <span class="input-group-append">

                    <button class="submit btn btn-primary" id="searchsubmit" name="submit" type="submit"><?= $lang["buscar"]; ?> <i class="fa fa-arrow-right"></i></button>

                  </span>

                </div>

              </form>

            </div>

            <!--ACORDEON PARA ORDENAR EN PC-->
            <div class="accordion" id="accordionOrdenar" style="margin-bottom: 0;">

              <div class="card card-accordion" style="margin-bottom: 0; border: none; border-bottom: 1px solid #dee2e6;">

                <div class="" id="headingOrdenar">

                  <h5 class="mb-0" style="margin-bottom: 0;">

                    <a class="btn btn-accordion show" href="#" data-toggle="collapse" data-target="#precioOrdenar" aria-expanded="true" aria-controls="collapseOrdenar" style="padding: 6px 12px; font-size: 13px; display: block; width: 100%; text-align: left;"><?= $lang["ordenar"]; ?><i class="fa fa-sort-down float-right"></i>

                    </a>

                  </h5>

                </div>

                <div id="precioOrdenar" class="collapse show " aria-labelledby="headingOrdenar" data-parent="#accordionOrdenar" style="padding: 0;">

                  <form class="padding" style="padding: 6px 12px; margin: 0;">

                    <div class="btn-group" role="group" aria-label="Basic example">

                      <?php
                      $btnMenorPrecio = ($orden === 'price_asc') ? "btn btn-primary-selected btn-size" : "btn btn-primary btn-size";
                      $btnMayorPrecio = ($orden === 'price_desc') ? "btn btn-primary-selected btn-size" : "btn btn-primary btn-size";
                      ?>

                      <form action="categorias.php" method="get" class="mr-2 mb-0">
                        <?php if (!empty($busqueda)) { ?><input type="hidden" name="buscar" value="<?= htmlspecialchars($busqueda, ENT_QUOTES, 'UTF-8'); ?>"><?php } ?>
                        <?php if (!empty($idCategoria)) { ?><input type="hidden" name="idCategoria" value="<?= $idCategoria; ?>"><?php } ?>
                        <?php if (isset($_GET["hoy"])) { ?><input type="hidden" name="hoy" value="1"><?php } ?>
                        <?php if (isset($_GET["manana"])) { ?><input type="hidden" name="manana" value="1"><?php } ?>
                        <input type="hidden" name="orden" value="price_asc">
                        <button type="submit" class="<?= $btnMenorPrecio; ?>"><?= $lang["menor_precio"]; ?></button>

                      </form>

                      <form action="categorias.php" method="get" class="mb-0">
                        <?php if (!empty($busqueda)) { ?><input type="hidden" name="buscar" value="<?= htmlspecialchars($busqueda, ENT_QUOTES, 'UTF-8'); ?>"><?php } ?>
                        <?php if (!empty($idCategoria)) { ?><input type="hidden" name="idCategoria" value="<?= $idCategoria; ?>"><?php } ?>
                        <?php if (isset($_GET["hoy"])) { ?><input type="hidden" name="hoy" value="1"><?php } ?>
                        <?php if (isset($_GET["manana"])) { ?><input type="hidden" name="manana" value="1"><?php } ?>
                        <input type="hidden" name="orden" value="price_desc">

                        <button type="submit" class="<?= $btnMayorPrecio; ?>"><?= $lang["mayor_precio"]; ?></button>

                      </form>

                    </div>

                  </form>

                </div>

              </div>

            </div>

            <br>

            <!--ACORDEON PARA FILTRO DE DISPONIBILIDAD EN PC-->

            <div class="accordion" id="Disponibilidad" style="margin-bottom: 0;">

              <div class="card card-accordion" style="margin-bottom: 0; border: none; border-bottom: 1px solid #dee2e6;">

                <div class="" id="headingOne">

                  <h5 class="mb-0" style="margin-bottom: 0;">

                    <a class="btn btn-accordion " href="#" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne" style="padding: 6px 12px; font-size: 13px; display: block; width: 100%; text-align: left;">

                      <?= $lang["disponibilidad"] ?><i class="fa fa-sort-down float-right"></i>

                    </a>

                  </h5>

                </div>

                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#Disponibilidad" style="padding: 0;">

                  <div class="card-body" style="padding: 6px 12px; margin: 0;">

                    <div class="btn-group" role="group" aria-label="Basic example">

                      <?php



                      $btnHoy = "btn btn-primary btn-size";

                      $btnManana = "btn btn-primary btn-size";

                      if (isset($_GET["hoy"])) {

                        $btnHoy = "btn btn-primary-selected btn-size";
                      }

                      if (isset($_GET["manana"])) {

                        $btnManana = "btn btn-primary-selected btn-size";
                      }







                      ?>

                      <form action="categorias.php" method="get" class="mr-2 mb-0">
                        <?php if (!empty($busqueda)) { ?><input type="hidden" name="buscar" value="<?= htmlspecialchars($busqueda, ENT_QUOTES, 'UTF-8'); ?>"><?php } ?>
                        <?php if (!empty($idCategoria)) { ?><input type="hidden" name="idCategoria" value="<?= $idCategoria; ?>"><?php } ?>
                        <?php if (!empty($orden)) { ?><input type="hidden" name="orden" value="<?= htmlspecialchars($orden, ENT_QUOTES, 'UTF-8'); ?>"><?php } ?>
                        <input type="hidden" name="hoy" value="1">
                        <button type="submit" class="<?= $btnHoy; ?>"><?= $lang["hoy"]; ?></button>

                      </form>

                      <form action="categorias.php" method="get" class="mb-0">
                        <?php if (!empty($busqueda)) { ?><input type="hidden" name="buscar" value="<?= htmlspecialchars($busqueda, ENT_QUOTES, 'UTF-8'); ?>"><?php } ?>
                        <?php if (!empty($idCategoria)) { ?><input type="hidden" name="idCategoria" value="<?= $idCategoria; ?>"><?php } ?>
                        <?php if (!empty($orden)) { ?><input type="hidden" name="orden" value="<?= htmlspecialchars($orden, ENT_QUOTES, 'UTF-8'); ?>"><?php } ?>
                        <input type="hidden" name="manana" value="1">
                        <button type="submit" class="<?= $btnManana; ?>"><?= $lang["manana"]; ?></button>

                      </form>



                    </div>

                  </div>

                </div>

              </div>

            </div>

            <br>

            <div class="accordion" id="accordionExample">

              <div class="card card-accordion">

                <div class="" id="headingOne">

                  <h5 class="mb-0">

                    <a class="btn btn-accordion " href="#" data-toggle="collapse" data-target="#categorias" aria-expanded="true" aria-controls="collapseOne"><?= $lang["categoria"]; ?><i class="fa fa-sort-down float-right"></i>

                    </a>

                  </h5>

                </div>



                <div id="categorias" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">

                  <div class="card-body">

                    <?php

                    $todas_las_categorias = getCategorias();

                    // Build URL for "Todas las categorías" with current filters
                    $urlParamsAll = "";
                    if (!empty($busqueda)) {
                      $urlParamsAll .= "buscar=" . urlencode($busqueda);
                    }
                    if (!empty($orden)) {
                      $urlParamsAll .= (!empty($urlParamsAll) ? "&" : "") . "orden=" . urlencode($orden);
                    }
                    if (isset($_GET["hoy"])) {
                      $urlParamsAll .= (!empty($urlParamsAll) ? "&" : "") . "hoy=1";
                    }
                    if (isset($_GET["manana"])) {
                      $urlParamsAll .= (!empty($urlParamsAll) ? "&" : "") . "manana=1";
                    }

                    $checkedAll = ($idCategoria == 0) ? "checked" : "";
                    $typeAll = ($idCategoria == 0) ? "radio" : "";

                    echo '<a href="categorias?' . $urlParamsAll . '">
                         <div class="custom-control custom-checkbox mb-2">
                          <input type="' . $typeAll . '" class="custom-control-input" id="" ' . $checkedAll . '>
                          <label class="custom-control-label" for="">' . $lang["todas_las_categorias"] . '</label>
                        </div></a>';

                    for ($i = 0; $i < count($todas_las_categorias); $i++) {

                      $idCategoria_todas = $todas_las_categorias[$i]["idCategoria_servicio"];

                      $nombre_categoria_servicio_todas = $todas_las_categorias[$i]["nombre_categoria_servicio"];

                      $checked = "";

                      $type = "";



                      if ($idCategoria == $idCategoria_todas) {

                        $checked = "checked";

                        $type = "radio";
                      }

                      // Build URL with all filter parameters
                      $urlParams = "idCategoria=" . $idCategoria_todas;
                      if (!empty($busqueda)) {
                        $urlParams .= "&buscar=" . urlencode($busqueda);
                      }
                      if (!empty($orden)) {
                        $urlParams .= "&orden=" . urlencode($orden);
                      }
                      if (isset($_GET["hoy"])) {
                        $urlParams .= "&hoy=1";
                      }
                      if (isset($_GET["manana"])) {
                        $urlParams .= "&manana=1";
                      }

                      echo '    

<a href="categorias?' . $urlParams . '">

                         <div class="custom-control custom-checkbox mb-2">

                          <input type="' . $type . '" class="custom-control-input" id="" ' . $checked . '>

                          <label class="custom-control-label" for="">' . $nombre_categoria_servicio_todas . '</label>

                        </div></a>';
                    }

                    ?>







                  </div>

                </div>

              </div>

            </div>

            <br>

            <!-- <div class="accordion" id="accordionExample">

                 <div class="card card-accordion">

                    <div class="" id="headingOne">

                      <h5 class="mb-0">

                        <a class="btn btn-accordion " href="#" data-toggle="collapse" data-target="#accesibiliad" aria-expanded="true" aria-controls="collapseOne">

                          Accesibilidad <i class="fa fa-sort-down float-right"></i>

                        </a>

                      </h5>

                    </div>



                    <div id="accesibiliad" class="collapse  show" aria-labelledby="headingOne" data-parent="#accordionExample">

                      <div class="card-body">

                        <div class="custom-control custom-checkbox mb-2">

                          <input type="checkbox" class="custom-control-input" id="customControlInlinea">

                          <label class="custom-control-label" for="customControlInlinea">Accesible</label>

                        </div>

                      </div>

                    </div>

                  </div>

              </div>-->

            <br>

            <!--  <div class="accordion" id="accordionExample">

                 <div class="card card-accordion">

                    <div class="" id="headingOne">

                      <h5 class="mb-0">

                        <a class="btn btn-accordion show" href="#" data-toggle="collapse" data-target="#Duración" aria-expanded="true" aria-controls="collapseOne">

                          Duración <i class="fa fa-sort-down float-right"></i>

                        </a>

                      </h5>

                    </div>



                    <div id="Duración" class="collapse show " aria-labelledby="headingOne" data-parent="#accordionExample">

                       <form class="padding">

                      <div class="form-group">

                        <input type="range" class="form-control-range custom-range"  id="formControlRange2">

                      </div>

                    </form>

                    </div>

                  </div>

              </div>-->

            <!--FIN ACORDEON PARA FILTRO DE BUSQUEDA EN PC-->

          </div>

        </div>

        <br>

        <!--CARD ULTIMAS OPINIONES-->



        <div class="card card-ultimas-o d-md-block d-none">

          <?php

          for ($b = 0; $b < count($opiniones_categoria); $b++) {

            if ($b <= 2) {

              # code...



          ?>

              <div class="card-body">

                <p class="text-primary"><?= $opiniones_categoria[$b]['opinion'] ?></p>

                <p>

                  <i class="fa fa-star text-primary"></i>

                  <i class="fa fa-star text-primary"></i>

                  <i class="fa fa-star text-primary"></i>

                  <i class="fa fa-star text-primary"></i>

                  <i class="fa fa-star text-primary"></i>

                  <?= $opiniones_categoria[$b]['nombre'] ?>
                </p>

                <hr>





              </div>

          <?php

            }
          }







          ?>



        </div>

        <!--FIN CARD ULTIMAS OPINIONES-->

        <br>

        <!--CARD GUIA PC-->

        <div class="card card-ultimas-o d-md-block d-none">

          <div class="card-body">

            <form action="guias.php" method="get">

              <input type="hidden" name="idCategoria" value="<?= $idCategoria; ?>">

              <h4><i class="fa fa-map"></i><?= isset($lang["conoce_nuestra_guia_de"]) ? $lang["conoce_nuestra_guia_de"] : "Conoce nuestra guía de"; ?> <?= isset($lang[$nombre_categoria]) ? $lang[$nombre_categoria] : $nombre_categoria; ?></h4>

              <a class="text-white">

                <img src="admin/img/categoria_servicio/<?= $fotos; ?>" class="img-fluid img-guia">

                <button class="submit btn btn-primary"> <?= isset($lang[$nombre_categoria]) ? $lang[$nombre_categoria] : $nombre_categoria; ?></button>

              </a>

            </form>

          </div>

        </div>

        <!--FIN CARD GUIA PC-->

        <div id="sidebar" class="mb-5" style="display: none;">

          <div class="sidebar__inner" style="bottom:50px !important">

            <!--CARD PRINCIPAL DE BUSQUEDA-->

            <div class="card card-seccion-right  ">

              <div class="card-body">



                <!--ACORDEON PARA FILTRO DE BUSQUEDA EN PC-->

                <div class="accordion" id="Disponibilidad" style="display: none;">

                  <div class="card card-accordion" style="display: none;">

                    <div class="" id="headingOne">

                      <h5 class="mb-0">

                        <a class="btn btn-accordion " href="#" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne"><?= $lang["disponibilidad"] ?> <i class="fa fa-sort-down float-right"></i>

                        </a>

                      </h5>

                    </div>



                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#Disponibilidad">

                      <div class="card-body">

                        <div class="btn-group" role="group" aria-label="Basic example">

                          <form action="categorias.php">

                            <input type="hidden" name="hoy"></input>

                            <button type="submit" class="<?= $btnHoy; ?>"><?= $lang["Hoy"] ?></button>

                          </form>

                          <form action="categorias.php">

                            <input type="hidden" name="manana"></input>

                            <button type="submit" class="<?= $btnManana; ?>"><?= $lang["manana"] ?></button>

                          </form>

                        </div>

                      </div>

                    </div>

                  </div>

                </div>

                <br>

                <div class="accordion" id="accordionExample">

                  <div class="card card-accordion">

                    <div class="" id="headingOne">

                      <h5 class="mb-0">

                        <a class="btn btn-accordion " href="#" data-toggle="collapse" data-target="#categorias" aria-expanded="true" aria-controls="collapseOne">

                          <?= $lang["categorias"] ?><i class="fa fa-sort-down float-right"></i>

                        </a>

                      </h5>

                    </div>



                    <div id="categorias" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">

                      <div class="card-body">









                        <div class="custom-control custom-checkbox mb-2">

                          <input type="checkbox" class="custom-control-input" id="customControlInline">

                          <label class="custom-control-label" for="customControlInline"><?= $lang["visitas_guiadas"] ?></label>

                        </div>

                        <div class="custom-control custom-checkbox mb-2">

                          <input type="checkbox" class="custom-control-input" id="customControlInline2">

                          <label class="custom-control-label" for="customControlInline2"><?= $lang["visitas_guiadas"] ?></label>

                        </div>

                        <div class="custom-control custom-checkbox mb-2">

                          <input type="checkbox" class="custom-control-input" id="customControlInline3">

                          <label class="custom-control-label" for="customControlInline3"><?= $lang["visitas_guiadas"] ?></label>

                        </div>

                      </div>

                    </div>

                  </div>

                </div>

                <br>

                <div class="accordion" id="accordionExample">

                  <div class="card card-accordion">

                    <div class="" id="headingOne">

                      <h5 class="mb-0">

                        <a class="btn btn-accordion " href="#" data-toggle="collapse" data-target="#accesibiliad" aria-expanded="true" aria-controls="collapseOne">

                          <?= $lang["accesibiliad"] ?><i class="fa fa-sort-down float-right"></i>

                        </a>

                      </h5>

                    </div>



                    <div id="accesibiliad" class="collapse  show" aria-labelledby="headingOne" data-parent="#accordionExample">

                      <div class="card-body">

                        <div class="custom-control custom-checkbox mb-2">

                          <input type="checkbox" class="custom-control-input" id="customControlInlinea">

                          <label class="custom-control-label" for="customControlInlinea"><?= $lang["accesible"] ?></label>

                        </div>

                      </div>

                    </div>

                  </div>

                </div>

                <br>

                <div class="accordion" id="accordionExample">

                  <div class="card card-accordion">

                    <div class="" id="headingOne">

                      <h5 class="mb-0">

                        <a class="btn btn-accordion show" href="#" data-toggle="collapse" data-target="#precio" aria-expanded="true" aria-controls="collapseOne">

                          <?= $lang["precio"] ?><i class="fa fa-sort-down float-right"></i>

                        </a>

                      </h5>

                    </div>



                    <div id="precio" class="collapse show " aria-labelledby="headingOne" data-parent="#accordionExample">

                      <form class="padding">

                        <div class="form-group">

                          <label for="formControlRange">gratis</label>

                          <input type="range" class="form-control-range custom-range" id="formControlRange">

                        </div>

                      </form>

                    </div>

                  </div>

                </div>

                <br>

                <div class="accordion" id="accordionExample">

                  <div class="card card-accordion">

                    <div class="" id="headingOne">

                      <h5 class="mb-0">

                        <a class="btn btn-accordion show" href="#" data-toggle="collapse" data-target="#Duración" aria-expanded="true" aria-controls="collapseOne">

                          <?= $lang["duracion"] ?><i class="fa fa-sort-down float-right"></i>

                        </a>

                      </h5>

                    </div>



                    <div id="Duración" class="collapse show " aria-labelledby="headingOne" data-parent="#accordionExample">

                      <form class="padding">

                        <div class="form-group">

                          <input type="range" class="form-control-range custom-range" id="formControlRange2">

                        </div>

                      </form>

                    </div>

                  </div>

                </div>

                <!--FIN ACORDEON PARA FILTRO DE BUSQUEDA EN PC-->

              </div>

            </div>

          </div>

        </div>



      </div>

      <!--FIN COLUMNA DERECHA DE BUSQUEDA-->







      <!--COLUMNA IZQUIERDA RESULTADOS DE BUSQUEDA-->

      <div class="col-lg-8 padding-lr-0">











        <!--BUCLE DE RESULTADOS -->





        <div class="container d-md-none py-2">

          <div class="row ">

            <div class="col-4 ">

              <a href="#" style="font-size: 10px;" class="btn btn-dark btn-filtar d-md-none " data-toggle="modal" data-target="#exampleModal"><i class="fa fa-sliders-h "></i><?= $lang["filtrar"] ?></a>

            </div>

            <div class="col-8 my-auto">



              <p class="text-left text-filtrar"><?= $cantidad_servicios_categoria; ?> <?= isset($lang["actividades_en"]) ? $lang["actividades_en"] : "actividades en"; ?> <?= isset($lang[$nombre_categoria]) ? $lang[$nombre_categoria] : $nombre_categoria; ?></p>

            </div>

          </div>

        </div>



        <p class="text-left d-md-block d-none " style="font-size: 30px;"><?= $cantidad_servicios_categoria; ?> <?= isset($lang["actividades_en"]) ? $lang["actividades_en"] : "actividades en"; ?> <?= isset($lang[$nombre_categoria]) ? $lang[$nombre_categoria] : $nombre_categoria; ?></p>









        <?php
        echo "<!-- DEBUG: count(servicios)=" . count($servicios) . " -->";
        
        // Precompile service data with prices for sorting
        $servicios_calculados = [];
        
        for ($i = 0; $i < count($servicios); $i++) {
          $idServicio = $servicios[$i]["idServicio"];

          // Get the MOST RECENT salida for this service (no date filter for dev where data is old)
          require_once('admin/classes/conexion.php');
          $consulta_salidas = "SELECT * FROM servicio_salidas WHERE idServicio=:idServicio ORDER BY fecha DESC LIMIT 1";
          $cmd_salidas = $pdo->prepare($consulta_salidas);
          $cmd_salidas->bindParam(":idServicio", $idServicio, PDO::PARAM_INT);
          $cmd_salidas->execute();
          $salidas = $cmd_salidas->fetchAll(PDO::FETCH_ASSOC);

          // Default values
          $idMoneda = 1;
          $idServicioSalidas = 0;
          $precioSugerido = "Consultar";
          $precioValor = PHP_INT_MAX; // For ESGOTADO sorting to end
          $cancelacion = "";

          // Get price directly from database (simple approach)
          if (!empty($salidas)) {
            $idServicioSalidas = $salidas[0]['idServicioSalidas'];
            $idMoneda = $salidas[0]['idMoneda'];
            
            // Get minimum price > 0
            $consulta_precio = "SELECT MIN(valor) as precio FROM servicio_salidas_tarifas WHERE idServicioSalidas=:idServicioSalidas AND valor > 0 LIMIT 1";
            $cmd_precio = $pdo->prepare($consulta_precio);
            $cmd_precio->bindParam(":idServicioSalidas", $idServicioSalidas, PDO::PARAM_INT);
            $cmd_precio->execute();
            $precio_resultado = $cmd_precio->fetch(PDO::FETCH_ASSOC);
            
            if ($precio_resultado && $precio_resultado['precio'] > 0) {
              $precio_base = $precio_resultado['precio'];
              // Convert currency if needed
              $precio_convertido = ConvierteMoneda($idMoneda, $_SESSION['moneda_sel'], $precio_base);
              // Add taxes
              $precio_final = $precio_convertido * (1 + $_SESSION['impuestos_pais']);
              // Store numeric value for sorting
              $precioValor = $precio_final;
              // Format for display
              if (isset($_SESSION['moneda_sel_sym']) && $_SESSION['moneda_sel_sym'] === 'AR$') {
                $precioSugerido = $_SESSION['moneda_sel_sym'] . number_format($precio_final, 0, '', '.');
              } else {
                $precioSugerido = $_SESSION['moneda_sel_sym'] . number_format($precio_final, 2, ',', '.');
              }
            }
            
            // Get cancellations
            $tarifas = getTarifas($idServicioSalidas);
            if (!empty($tarifas) && isset($tarifas[0])) {
              $cancelaciones = getTipoCancelaciones($tarifas[0]['idCancelaciones']);

              $cancelacion = "";
              if (!empty($cancelaciones) && isset($cancelaciones[0])) {
                switch ($cancelaciones[0]["idCancelacion"]) {
                  case 1:
                  case 3:
                  case 7:
                    $cancelacion = "Cancelamento gratis!";
                    break;

                  default:
                    // code...
                    break;
                }
              }
            }
          }
          
          $nombre_servicio = $servicios[$i]["nombre_servicio"];
          $descripcion_corta = $servicios[$i]["descripcion_corta"];
          $opiniones_servicio = getOpinionesServicio($idServicio);
          $estrellas_servicio = getEstrellasServicio($idServicio);
          $cantidad_opiniones_servicio = count($opiniones_servicio);
          $duracion_servicio = getDuracionServicio($idServicio);
          $fotos_servicio = getFotoMiniaturaServicio($idServicio);
          $fotos_servicio = !empty($fotos_servicio) && isset($fotos_servicio[0]["ruta"]) ? $fotos_servicio[0]["ruta"] : "default.jpg";
          $textoMiniatura_temp = getTextoMiniatura($servicios[$i]["idTextoMiniaturas"]);
          $textoMiniatura = !empty($textoMiniatura_temp) && isset($textoMiniatura_temp[0]["texto"]) ? $textoMiniatura_temp[0]["texto"] : "";

          $servicios_calculados[] = [
            'idServicio' => $idServicio,
            'nombre_servicio' => $nombre_servicio,
            'descripcion_corta' => $descripcion_corta,
            'precioSugerido' => $precioSugerido,
            'precioValor' => $precioValor,
            'cancelacion' => $cancelacion,
            'estrellas_servicio' => $estrellas_servicio,
            'cantidad_opiniones_servicio' => $cantidad_opiniones_servicio,
            'duracion_servicio' => $duracion_servicio,
            'fotos_servicio' => $fotos_servicio,
            'textoMiniatura' => $textoMiniatura,
          ];
        }

        // Apply sorting if orden parameter is set
        if ($orden === 'price_asc') {
          usort($servicios_calculados, function ($a, $b) {
            return $a['precioValor'] <=> $b['precioValor'];
          });
        } elseif ($orden === 'price_desc') {
          usort($servicios_calculados, function ($a, $b) {
            return $b['precioValor'] <=> $a['precioValor'];
          });
        }

        // Render all precompiled services
        foreach ($servicios_calculados as $svc) {
          $mostrarPrecio = ($svc['precioSugerido'] !== 'ESGOTADO' && $svc['precioSugerido'] !== 'ESGOTADO!');
        ?>



            <a href="servicio?id=<?= $svc['idServicio'] ?>">
             <div class="mb-4">
                <div class="card card-visitas">
                  <div class="row no-gutters d-md-none" style="position: absolute;z-index: 999;">
                    <div class="col-6">
                      <div class="badge badge-primary badge-destacado"><?= $svc['textoMiniatura']; ?></div>
                    </div>
                  </div>
                  <div class="card-body padding-body">
                    <div class="row ">
                      <div class="col-md-4 col-4">
                        <img src="admin/classes/imgServicio/<?= $svc['fotos_servicio']; ?>" class="w-100 img-fluid img-card-destinos">
                      </div>
                      <div class="col-md-8 col-8" style="padding-left:0px !important;">
                        <div class="card-block ">
                          <h4 class="text-left titulo-card-destinos semibold"><?= $svc['nombre_servicio'] ?></h4>
                          <h5 class="texto-opinion-desta"><strong><?= $svc['estrellas_servicio']; ?>/10</strong> <small class="text-gris"><?= $svc['cantidad_opiniones_servicio']; ?> opiniones</small></h5>
                          <p class="text-gris d-md-block"><?= $svc['descripcion_corta']; ?></p>
                        </div>
                        <ul class="lista-caracteristicas d-md-none">
                          <li><i class="fa fa-hourglass-half"></i> <?= $svc['duracion_servicio']["duracionMinima"]; ?> - <?= $svc['duracion_servicio']["duracionMaxima"]; ?></li>
                        </ul>
                        <h4 class="text-success text-cancelacion  float-left d-md-none semibold"><?= $svc['cancelacion'] ?></h4>
                        <?php if ($mostrarPrecio) { ?>
                          <p class="float-right d-md-none semibold"><?= $svc['precioSugerido']; ?></p>
                        <?php } ?>
                      </div>
                    </div>

                    <div class=" d-md-block mt-2 d-none">
                      <div class="row no-gutters">
                        <div class="col-lg-4 col-12">
                          <ul class="lista-caracteristicas">
                            <li><i class="fa fa-hourglass-half"></i> <?= $svc['duracion_servicio']["duracionMinima"]; ?> - <?= $svc['duracion_servicio']["duracionMaxima"]; ?> </li>
                         </ul>
                        </div>
                        <div class="col-lg-4 col-12">
                          <h4 class="text-success text-cancelacion semibold"><?= $svc['cancelacion']; ?></h4>
                        </div>

                       <div class="col-lg-4 col-12">
                          <?php if ($mostrarPrecio) { ?>
                            <h4 class="float-right semibold"><?= $svc['precioSugerido']; ?></h4>
                          <?php } ?>
                        </div>

                      </div>

                    </div>

                  </div>

                  <div class="destacado d-md-block d-none">

                    <h5 class="text-uppercase text-white"><?= $svc['textoMiniatura']; ?></h5>

                  </div>

                </div>

              </div>

            </a>















        <?php
        }
        ?>




















        <!--BUCLE DE RESULTADOS -->



        <!--PAGINACION DE RESULTADOS -->




<style type="text/css">
  .active{
    color: white !important;
    background-color: lightgrey;
    zoom:  1.05;
}

</style>

<div class="col-12" style="margin: 10%; width: 90%;">
        <nav aria-label="Page navigation example">

          <ul class="pagination pagination-sm  justify-content-center">

            <?php

            $cantidad_de_paginas = $cantidad_servicios_categoria / $cantidad_por_pagina;
            if ($cantidad_servicios_categoria % $cantidad_por_pagina > 0) {
              $cantidad_de_paginas += 1;
            }
            if ($cantidad_de_paginas > 1) {


            ?>


<?php if($pagina>1){
  ?>
    <li class=" flechas" >
                <!--  <a class="page-link" href="categorias.php?id='.$id.'&pagina='.($pagina-1).'" aria-label="Previous">  </a>-->  
                <a class="page-link" href="categorias.php?idCategoria=<?= $idCategoria ?>&pagina=<?= $pagina-1 ?>"><<</a>
              </li>
  
  <?php
} ?>
            

              <?php

              for ($i = 1; $i < $cantidad_de_paginas; $i++) {
                $activada="";
                if(($pagina)==$i){
                  $activada="active";
                }
              ?>
                <li class=" <?=$activada?>"><a class="page-link <?=$activada?>" href="categorias.php?idCategoria=<?= $idCategoria ?>&pagina=<?= $i ?>"><?= $i; ?></a></li>
              <?php
              } ?>
              
              <?php  
    
              if(($pagina)<($cantidad_de_paginas-1)){  ?>
                <li class=" flechas" >
                <!--  <a class="page-link" href="categorias.php?id='.$id.'&pagina='.($pagina-1).'" aria-label="Previous">  </a>-->  
                <a class="page-link" href="categorias.php?idCategoria=<?= $idCategoria ?>&pagina=<?= $pagina+1 ?>">>></a>
              </li>
              <?php  }  ?>
          
          </ul>

        <?php
            } ?>
        </nav>


</div>


        <!--FIN PAGINACION DE RESULTADOS -->















        <br>





        <div class="card card-ultimas-o d-block d-sm-none">

          <?php

          for ($b = 0; $b < count($opiniones_categoria); $b++) {

            if ($b <= 2) { // para maximo 2 resultados

              # code...



          ?>

              <div class="card-body">

                <p class="text-primary"><?= $opiniones_categoria[$b]['opinion'] ?></p>

                <p>

                  <i class="fa fa-star text-primary"></i>

                  <i class="fa fa-star text-primary"></i>

                  <i class="fa fa-star text-primary"></i>

                  <i class="fa fa-star text-primary"></i>

                  <i class="fa fa-star text-primary"></i>

                  <?= $opiniones_categoria[$b]['nombre'] ?>
                </p>

                <hr>





              </div>

          <?php

            }
          }





          ?>



        </div>



        <!--CARD GUIA MOVIL -->

        <div class="card card-ultimas-o d-md-none">

          <div class="card-body">

            <form action="guias.php" method="post">

              <input type="hidden" name="idCategoria" value="<?= $id; ?>">



              <h4><i class="fa fa-map"></i><?= isset($lang["conoce_nuestra_guia_de"]) ? $lang["conoce_nuestra_guia_de"] : "Conoce nuestra guía de"; ?> <?= isset($lang[$nombre_categoria]) ? $lang[$nombre_categoria] : $nombre_categoria; ?></h4>

              <a href="#" class="text-white">



                <img src="admin/img/categoria_servicio/<?= $fotos; ?>" class="img-fluid img-guia mx-auto d-block">

                <h4 class="text-guia2"> <?= isset($lang[$nombre_categoria]) ? $lang[$nombre_categoria] : $nombre_categoria; ?></h4>

              </a>

              <button class="submit btn btn-primary"> <?= isset($lang[$nombre_categoria]) ? $lang[$nombre_categoria] : $nombre_categoria; ?></button>

            </form>

          </div>

        </div>

        <!--FIN CARD GUIA MOVIL -->

        <div class="container_r clearfix">



        </div>

      </div>

      <!--FIN COLUMNA IZQUIERDA RESULTADOS DE BUSQUEDA-->

    </div>

  </div>

</section>

<!--SECCION ACTIVIDADES-->





<!-- MODAL HERRAMIENTAS MOVIL-->

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

  <div class="modal-dialog" role="document">

    <div class="modal-content">

      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">

          <span aria-hidden="true">&times;</span>

        </button>

      </div>

      <div class="modal-body">

        <div class="card card-seccion-right  ">

          <div class="card-body">

            <!--ACORDEON PARA FILTRO DE BUSQUEDA EN MOVIL-->



            <div name="buscadorOPT">
              <div>

                <form class="form-buscar mb-5 " action="categorias.php" method="get">

                  <label class="sr-only" for="s"><?= $lang["que_hacemos"]; ?></label>

                  <div class="input-group">

                    <input class="field form-control form-control-search" name="buscar" type="text" placeholder="<?= $lang["que_hacemos"]; ?>" value="<?= $busqueda ?>">

                    <span class="input-group-append">

                      <button class="submit btn btn-primary" id="searchsubmit" name="submit" type="submit"><?= $lang["buscar"]; ?> <i class="fa fa-arrow-right"></i></button>

                    </span>

                  </div>

                </form>

              </div>

              <div class="accordion" id="Disponibilidad" style="display: none;">

                <div class="card card-accordion">

                  <div class="" id="headingOne">

                    <h5 class="mb-0">

                      <a class="btn btn-accordion " href="#" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">

                        <?= $lang["disponibilidad"] ?><i class="fa fa-sort-down float-right"></i>

                      </a>

                    </h5>

                  </div>



                  <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#Disponibilidad">

                    <div class="card-body">

                      <div class="btn-group" role="group" aria-label="Basic example">

                        <?php



                        $btnHoy = "btn btn-primary btn-size";

                        $btnManana = "btn btn-primary btn-size";

                        if (isset($_GET["hoy"])) {

                          $btnHoy = "btn btn-primary-selected btn-size";
                        }

                        if (isset($_GET["manana"])) {

                          $btnManana = "btn btn-primary-selected btn-size";
                        }







                        ?>

                        <form action="categorias.php" style="display: none;">

                          <input type="hidden" name="hoy">

                          <button type="submit" class="<?= $btnHoy; ?>"><?= $lang["hoy"]; ?></button>

                        </form>

                        <form action="categorias.php" style="display: none;">

                          <input type="hidden" name="manana">

                          <button type="submit" class="<?= $btnManana; ?>"><?= $lang["manana"]; ?></button>

                        </form>



                      </div>

                    </div>

                  </div>

                </div>

              </div>

              <br>

              <div class="accordion" id="accordionExample">

                <div class="card card-accordion">

                  <div class="" id="headingOne">

                    <h5 class="mb-0">

                      <a class="btn btn-accordion " href="#" data-toggle="collapse" data-target="#categorias" aria-expanded="true" aria-controls="collapseOne"><?= $lang["categoria"]; ?><i class="fa fa-sort-down float-right"></i>

                      </a>

                    </h5>

                  </div>



                  <div id="categorias" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">

                    <div class="card-body">

                      <?php

                      $todas_las_categorias = getCategorias();

                      // Build URL for "Todas las categorías" with current filters
                      $urlParamsAll = "";
                      if (!empty($busqueda)) {
                        $urlParamsAll .= "buscar=" . urlencode($busqueda);
                      }
                      if (!empty($orden)) {
                        $urlParamsAll .= (!empty($urlParamsAll) ? "&" : "") . "orden=" . urlencode($orden);
                      }
                      if (isset($_GET["hoy"])) {
                        $urlParamsAll .= (!empty($urlParamsAll) ? "&" : "") . "hoy=1";
                      }
                      if (isset($_GET["manana"])) {
                        $urlParamsAll .= (!empty($urlParamsAll) ? "&" : "") . "manana=1";
                      }

                      $checkedAll = ($idCategoria == 0) ? "checked" : "";
                      $typeAll = ($idCategoria == 0) ? "radio" : "";

                      echo '<a href="categorias?' . $urlParamsAll . '">
                         <div class="custom-control custom-checkbox mb-2">
                          <input type="' . $typeAll . '" class="custom-control-input" id="" ' . $checkedAll . '>
                          <label class="custom-control-label" for="">' . $lang["todas_las_categorias"] . '</label>
                        </div></a>';

                      for ($i = 0; $i < count($todas_las_categorias); $i++) {

                        $idCategoria_todas = $todas_las_categorias[$i]["idCategoria_servicio"];

                        $nombre_categoria_servicio_todas = $todas_las_categorias[$i]["nombre_categoria_servicio"];

                        $checked = "";

                        $type = "";



                        if ($idCategoria == $idCategoria_todas) {

                          $checked = "checked";

                          $type = "radio";
                        }

                        // Build URL with all filter parameters
                        $urlParams = "idCategoria=" . $idCategoria_todas;
                        if (!empty($busqueda)) {
                          $urlParams .= "&buscar=" . urlencode($busqueda);
                        }
                        if (!empty($orden)) {
                          $urlParams .= "&orden=" . urlencode($orden);
                        }
                        if (isset($_GET["hoy"])) {
                          $urlParams .= "&hoy=1";
                        }
                        if (isset($_GET["manana"])) {
                          $urlParams .= "&manana=1";
                        }

                        echo '    

<a href="categorias?' . $urlParams . '">

                         <div class="custom-control custom-checkbox mb-2">

                          <input type="' . $type . '" class="custom-control-input" id="" ' . $checked . '>

                          <label class="custom-control-label" for="">' . $nombre_categoria_servicio_todas . '</label>

                        </div></a>';
                      }

                      ?>







                    </div>

                  </div>

                </div>

              </div>


            </div>





            <div class="accordion" id="Disponibilidad">

              <div class="card card-accordion">

                <div class="" id="headingOne">

                  <h5 class="mb-0">

                    <a class="btn btn-accordion " href="#" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">

                      <?= $lang["disponibilidad"] ?><i class="fa fa-sort-down float-right"></i>

                    </a>

                  </h5>

                </div>



                <div id="collapseOne" class="collapse show " aria-labelledby="headingOne" data-parent="#Disponibilidad">

                  <div class="card-body">

                    <div class="btn-group" role="group" aria-label="Basic example">

                      <form action="categorias.php" method="get" class="mr-2 mb-0">
                        <?php if (!empty($busqueda)) { ?><input type="hidden" name="buscar" value="<?= htmlspecialchars($busqueda, ENT_QUOTES, 'UTF-8'); ?>"><?php } ?>
                        <?php if (!empty($idCategoria)) { ?><input type="hidden" name="idCategoria" value="<?= $idCategoria; ?>"><?php } ?>
                        <?php if (!empty($orden)) { ?><input type="hidden" name="orden" value="<?= htmlspecialchars($orden, ENT_QUOTES, 'UTF-8'); ?>"><?php } ?>
                        <input type="hidden" name="hoy" value="1">
                        <button type="submit" class="<?= $btnHoy; ?>"><?= $lang["hoy"] ?></button>

                      </form>

                      <form action="categorias.php" method="get" class="mb-0">
                        <?php if (!empty($busqueda)) { ?><input type="hidden" name="buscar" value="<?= htmlspecialchars($busqueda, ENT_QUOTES, 'UTF-8'); ?>"><?php } ?>
                        <?php if (!empty($idCategoria)) { ?><input type="hidden" name="idCategoria" value="<?= $idCategoria; ?>"><?php } ?>
                        <?php if (!empty($orden)) { ?><input type="hidden" name="orden" value="<?= htmlspecialchars($orden, ENT_QUOTES, 'UTF-8'); ?>"><?php } ?>
                        <input type="hidden" name="manana" value="1">
                        <button type="submit" class="<?= $btnManana; ?>"><?= $lang["manana"] ?></button>

                      </form>



                    </div>

                  </div>

                </div>

              </div>

            </div>

            <br>

            <!--  <div class="accordion" id="accordionExample">

                 <div class="card card-accordion">

                    <div class="" id="headingOne">

                      <h5 class="mb-0">

                        <a class="btn btn-accordion " href="#" data-toggle="collapse" data-target="#categorias" aria-expanded="true" aria-controls="collapseOne">

                          Categorias <i class="fa fa-sort-down float-right"></i>

                        </a>

                      </h5>

                    </div>



                    <div id="categorias" class="collapse show " aria-labelledby="headingOne" data-parent="#accordionExample">

                      <div class="card-body">

 <?php



  //$cant=count(DevuelveCategorias());

  //$devuelveCat=DevuelveCategorias();



  for ($i = 0; $i < $cant; $i++) {

    $checked = "";

    $type = "";

    if ($id == $devuelveCat[$i][0]) {

      $checked = "checked";

      $type = "radio";
    }

    /*                       echo '    

<a href="categorias.php?id='.DevuelveCategorias()[$i][0].'">

               <div class="custom-control custom-checkbox mb-2" >

                      

    <input type="'.$type.'" class="custom-control-input" id="" '.$checked.'>

   <label class="custom-control-label" for="">'.DevuelveCategorias()[$i][1].'</label>

                    </div> </a>   ';*/
  }

  ?>

                    



                    

                        

                      </div>

                    </div>

                  </div>

              </div>-->

            <br>

            <!-- <div class="accordion" id="accordionExample">

                 <div class="card card-accordion">

                    <div class="" id="headingOne">

                      <h5 class="mb-0">

                        <a class="btn btn-accordion " href="#" data-toggle="collapse" data-target="#accesibiliad" aria-expanded="true" aria-controls="collapseOne">

                          Accesibilidad <i class="fa fa-sort-down float-right"></i>

                        </a>

                      </h5>

                    </div>



                    <div id="accesibiliad" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">

                      <div class="card-body">

                        <div class="custom-control custom-checkbox mb-2">

                          <input type="checkbox" class="custom-control-input" id="customControlInlinea">

                          <label class="custom-control-label" for="customControlInlinea">Accesible</label>

                        </div>

                      </div>

                    </div>

                  </div>

              </div>-->

            <br>

            <div class="accordion" id="accordionExample">

              <div class="card card-accordion">

                <div class="" id="headingOne">

                  <h5 class="mb-0">

                    <a class="btn btn-accordion " href="#" data-toggle="collapse" data-target="#precio" aria-expanded="true" aria-controls="collapseOne">

                      <?= $lang["precio"] ?><i class="fa fa-sort-down float-right"></i>

                    </a>

                  </h5>

                </div>



                <div id="precio" class="collapse show " aria-labelledby="headingOne" data-parent="#accordionExample">

                  <form class="padding">

                    <div class="btn-group" role="group" aria-label="Basic example">

                      <form action="categorias.php" method="get" class="mr-2 mb-0">
                        <?php if (!empty($busqueda)) { ?><input type="hidden" name="buscar" value="<?= htmlspecialchars($busqueda, ENT_QUOTES, 'UTF-8'); ?>"><?php } ?>
                        <?php if (!empty($idCategoria)) { ?><input type="hidden" name="idCategoria" value="<?= $idCategoria; ?>"><?php } ?>
                        <?php if (isset($_GET["hoy"])) { ?><input type="hidden" name="hoy" value="1"><?php } ?>
                        <?php if (isset($_GET["manana"])) { ?><input type="hidden" name="manana" value="1"><?php } ?>
                        <input type="hidden" name="orden" value="price_asc">
                        <button type="submit" class="<?= $btnMenorPrecio; ?>"><?= $lang["menor_precio"] ?></button>

                      </form>

                      <form action="categorias.php" method="get" class="mb-0">
                        <?php if (!empty($busqueda)) { ?><input type="hidden" name="buscar" value="<?= htmlspecialchars($busqueda, ENT_QUOTES, 'UTF-8'); ?>"><?php } ?>
                        <?php if (!empty($idCategoria)) { ?><input type="hidden" name="idCategoria" value="<?= $idCategoria; ?>"><?php } ?>
                        <?php if (isset($_GET["hoy"])) { ?><input type="hidden" name="hoy" value="1"><?php } ?>
                        <?php if (isset($_GET["manana"])) { ?><input type="hidden" name="manana" value="1"><?php } ?>
                        <input type="hidden" name="orden" value="price_desc">

                        <button type="submit" class="<?= $btnMayorPrecio; ?>"><?= $lang["mayor_precio"] ?></button>

                      </form>

                    </div>

                  </form>

                </div>

              </div>

            </div>

            <br>

            <!-- <div class="accordion" id="accordionExample">

                 <div class="card card-accordion">

                    <div class="" id="headingOne">

                      <h5 class="mb-0">

                        <a class="btn btn-accordion " href="#" data-toggle="collapse" data-target="#Duración" aria-expanded="true" aria-controls="collapseOne">

                          Duración <i class="fa fa-sort-down float-right"></i>

                        </a>

                      </h5>

                    </div>



                    <div id="Duración" class="collapse show " aria-labelledby="headingOne" data-parent="#accordionExample">

                       <form class="padding">

                      <div class="form-group">

                        <input type="range" class="form-control-range custom-range"  id="formControlRange2">

                      </div>

                    </form>

                    </div>

                  </div>

              </div>-->

            <!--ACORDEON PARA FILTRO DE BUSQUEDA EN MOVIL-->

          </div>

        </div>

      </div>



    </div>

  </div>

</div>

<!-- FIN MODAL HERRAMIENTAS MOVIL-->







<!-- MODAL BUSCAR MOVIL-->

<div class="modal fade" id="modalbuscar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

  <div class="modal-dialog" role="document">

    <div class="modal-content">

      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">

          <span aria-hidden="true">&times;</span>

        </button>

      </div>

      <div class="modal-body">

        <form class="form-buscar">

          <label class="sr-only" for="s"><?= $lang["donde_vamos"] ?></label>

          <div class="input-group ">

            <input class="field form-control" name="buscar" type="text" placeholder="¿Dónde vamos?" value="">

            <span class="input-group-append">

              <button class="submit btn btn-primary" id="searchsubmit" name="submit" type="submit"><?= $lang["buscar"] ?><i class="fa fa-arrow-right"></i></button>

            </span>

          </div>

          <!--EMPIEZA DESPLEGABLE DEL BANNER-->

          <div class="form-group">

            <div class="container">

              <div class="row">

                <div class="col-lg-12">

                  <div class="top-destinos-movil">

                    <div class="container">

                      <div class="row mb-4">

                        <div class="col-lg-12">

                          <h3 class="text-center text-primary"><?= $lang["top_actividades"] ?></h3>

                        </div>

                      </div>

                      <div class="row  mb-4">



                        <!--EL BUCLE DE LOS RESULTADOS DEBE IR ACA-->

                        <div class="col-md-3 col-6 mb-3">

                          <h4 class=" mb-0"><a href="#" class="text-destinos">Rio de Janeiro</a></h4>

                          <small>Florianopolis</small>

                        </div>

                        <!--FIN BUCLE DE LOS RESULTADOS DEBE IR ACA-->



                      </div>

                      <div class="row py-4">

                        <div class="col-lg-12">

                          <h3 class="text-center"><a href="" class="btn btn-outline-primary btn-white" style="border-radius:25px;"><?= $lang["ver_todos_los_destinos"] ?></a></h3>

                        </div>

                      </div>

                    </div>

                  </div>

                </div>

              </div>

            </div>

          </div>

          <!--EMPIEZA DESPLEGABLE DEL BANNER-->



        </form>

      </div>

    </div>

  </div>

</div>

<!-- FIN MODAL BUSCAR MOVIL-->



<!-- Footer -->

<?php include "footer.php";

?>

<!--- Fin del footer --->





<!-- BOTON SUBIR-->

<div class="scroll-to-top  position-fixed ">

  <a class="js-scroll-trigger d-block text-center text-white rounded" href="#page-top">

    <i class="fa fa-chevron-up"></i>

  </a>

</div>

<!-- FIN BOTON SUBIR-->





<!-- SCRIPTS NECESARIOS-->



<!-- JQUERY-->

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

<!-- JQUERY-->



<!-- UNDERSCORE-->

<script src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js"></script>

<!-- UNDERSCORE-->



<!-- MOMENT -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment.min.js"></script>

<!-- MOMENT -->



<!-- WOW ANIMACION -->

<script src="js/wow.min.js"></script>

<!-- WOW ANIMACION -->





<!-- BOOTSTRAP BUNDLE -->

<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- BOOTSTRAP BUNDLE -->



<!-- JQUERY EASING -->

<script src="vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- JQUERY EASING -->



<!-- CUSTOM -->

<script src="js/script.js"></script>

<!-- CUSTOM -->



<script type="text/javascript" src="js/rAF.js"></script>

<script type="text/javascript" src="js/ResizeSensor.js"></script>

<script type="text/javascript" src="js/sticky-sidebar.js"></script>

<script type="text/javascript">
  var stickySidebar = new StickySidebar('#sidebar', {

    topSpacing: 90,

    bottomSpacing: 100,

    containerSelector: '.container_r',

    innerWrapperSelector: '.sidebar__inner'

  });
</script>



<!-- FIN SCRIPTS NECESARIOS-->

</body>



</html>