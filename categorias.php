<?php

include('includes/navbar.php');

// Initialize session variables with defaults if not set (AFTER navbar.php)
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


// --- Lógica de obtención de datos (sin cambios) ---
$busqueda = "";
$cantidad_por_pagina = 10; // Aumentado para mejor experiencia móvil
$desde = 0;
$pagina = 1;

$params = $_GET; // Guardar todos los parámetros para la paginación

if (isset($_GET['pagina'])) {
  $pagina = max(1, (int)$_GET['pagina']);
  $desde = ($pagina - 1) * $cantidad_por_pagina;
  unset($params['pagina']); // Remover para no duplicar en URL
}

$queryString = http_build_query($params);
$orden = isset($_GET['orden']) ? $_GET['orden'] : '';

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
  $cantidad_opiniones_categoria = rand(100, 500);
  $fotos = "sinCategoria.jpg";
} else {
  $idCategoria = 0;
  $servicios = getServiciosPaginado($desde, $cantidad_por_pagina);
  $cantidad_servicios_categoria = count(getServicios());
  $categorias = getCategorias();
  $nViajeros = rand(690, 1200);
  $nombre_categoria = "todas_las_categorias";
  $opiniones_categoria = array();
  $cantidad_opiniones_categoria = rand(100, 500);
  $fotos = "sinCategoria.jpg";
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <title>Actividades en <?= $lang[$nombre_categoria] ?? 'Destinos' ?></title>
  <style>
    /* ========== HEADER DESKTOP ========== */
    #header-destinos {
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
      color: white;
      padding: 6rem 1rem;
      text-align: center;
      position: relative;
    }

    #header-destinos::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0, 0, 0, 0.5);
      z-index: -1;
    }

    .titulo-categoria {
      font-size: 3rem;
      font-weight: 700;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.6);
      margin-bottom: 2rem;
    }

    .stats-container {
      background: rgba(255, 255, 255, 0.1);
      padding: 2rem;
      border-radius: 8px;
      margin-top: 2rem;
    }

    .stat-item {
      text-align: center;
      margin: 0 1rem;
    }

    .stat-number {
      font-size: 2.5rem;
      font-weight: 700;
      color: #fff;
    }

    .stat-label {
      font-size: 0.95rem;
      color: rgba(255, 255, 255, 0.9);
      margin-top: 0.5rem;
    }

    /* ========== CONTROLES BÚSQUEDA ========== */
    .controles-busqueda {
      background-color: #ffffff;
      padding: 1.5rem;
      border-bottom: 1px solid #dee2e6;
      position: sticky;
      top: 0;
      z-index: 1020;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .form-control-search {
      border-radius: 50px 0 0 50px;
      border: 2px solid #e9ecef;
      padding: 0.8rem 1.2rem;
      font-size: 1rem;
    }

    .btn-search {
      border-radius: 0 50px 50px 0;
      padding: 0.8rem 1.5rem;
      font-size: 1rem;
      border: 2px solid #e9ecef;
    }

    .btn-lg-filter {
      padding: 0.8rem 1rem;
      font-size: 1.1rem;
      font-weight: 600;
      border-radius: 8px;
    }

    /* ========== TARJETAS DE SERVICIOS ========== */
    .service-card {
      border: none;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
      height: 100%;
      display: flex;
      flex-direction: column;
      text-decoration: none;
      color: inherit;
    }

    .service-card:hover {
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
      transform: translateY(-2px);
    }

    .service-card a {
      text-decoration: none;
      color: inherit;
      display: flex;
      flex-direction: column;
      height: 100%;
    }

    .card-img-container {
      position: relative;
      overflow: hidden;
      height: 200px;
      background-color: #f0f0f0;
    }

    .card-img-container img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.3s ease;
    }

    .service-card:hover .card-img-container img {
      transform: scale(1.05);
    }

    .badge-top {
      position: absolute;
      top: 10px;
      left: 10px;
      background-color: #007bff;
      color: white;
      padding: 0.5rem 1rem;
      border-radius: 20px;
      font-size: 0.85rem;
      font-weight: 600;
      z-index: 10;
    }

    .card-body {
      flex-grow: 1;
      display: flex;
      flex-direction: column;
      padding: 1.2rem;
    }

    .card-title {
      font-size: 1.1rem;
      font-weight: 600;
      margin-bottom: 0.5rem;
      color: #333;
      line-height: 1.4;
    }

    .rating-text {
      color: #007bff;
      font-size: 0.95rem;
      margin-bottom: 0.5rem;
    }

    .rating-text strong {
      color: #007bff;
      font-weight: 700;
    }

    .description-text {
      color: #495057;
      font-size: 0.9rem;
      margin-bottom: 0.8rem;
      line-height: 1.5;
    }

    .features-list {
      list-style: none;
      padding: 0;
      margin: 0.5rem 0;
      font-size: 0.9rem;
      color: #6c757d;
    }

    .features-list li {
      margin-bottom: 0.3rem;
    }

    .features-list i {
      color: #007bff;
      margin-right: 8px;
    }

    .price-section {
      border-top: 1px solid #f0f0f0;
      padding-top: 0.8rem;
      margin-top: auto;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .cancellation-text {
      font-weight: 600;
      font-size: 0.85rem;
      color: #28a745;
    }

    .price-text {
      font-size: 1.5rem;
      font-weight: 700;
      color: #28a745;
    }

    /* ========== SIDEBAR DESKTOP ========== */
    .sidebar-container {
      background: white;
      border-radius: 12px;
      padding: 1.5rem;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
      top: 120px;
      position: sticky;
    }

    .sidebar-title {
      font-size: 1.2rem;
      font-weight: 700;
      margin-bottom: 1rem;
      color: #333;
    }

    .accordion .card {
      border: none;
      border-bottom: 1px solid #f0f0f0;
    }

    .accordion .card:first-child {
      border-top: 1px solid #f0f0f0;
    }

    .accordion .card-header {
      background-color: transparent;
      border: none;
      padding: 0;
    }

    .btn-accordion {
      width: 100%;
      text-align: left;
      padding: 0.8rem 0;
      color: #333;
      font-weight: 600;
      text-decoration: none;
      border: none;
      background: none;
    }

    .btn-accordion:hover {
      color: #007bff;
    }

    /* ========== PAGINACIÓN ========== */
    .pagination .page-item .page-link {
      border-radius: 50px;
      margin: 0 5px;
      border: none;
      background-color: #e9ecef;
      color: #007bff;
      font-weight: 600;
    }

    .pagination .page-item.active .page-link {
      background-color: #007bff;
      color: white;
      box-shadow: 0 2px 5px rgba(0, 123, 255, 0.5);
    }

    .pagination .page-item.disabled .page-link {
      background-color: #f8f9fa;
      color: #6c757d;
    }

    /* ========== MODAL FILTROS MÓVIL ========== */
    .modal-body .btn-group-vertical .btn {
      text-align: left;
      border-radius: 8px !important;
      margin-bottom: 0.5rem;
      padding: 1rem;
      font-size: 1rem;
      border: 1px solid #dee2e6;
    }

    .modal-body .btn-group-vertical .btn.active {
      background-color: #007bff;
      color: white;
      font-weight: bold;
      border-color: #007bff;
    }

    /* ========== NO RESULTADOS ========== */
    .no-results {
      text-align: center;
      padding: 3rem 1rem;
    }

    .no-results h3 {
      color: #333;
      font-weight: 600;
      margin-bottom: 0.5rem;
    }

    .no-results p {
      color: #6c757d;
    }

  </style>
</head>

<body>

  <!-- HEADER DESKTOP -->
  <header id="header-destinos" class="d-none d-md-block" style="background-image: url('admin/img/categoria_servicio/<?= $fotos; ?>');">
    <div class="container">
      <h1 class="titulo-categoria"><?= isset($lang[$nombre_categoria]) ? $lang[$nombre_categoria] : $nombre_categoria; ?></h1>
      <div class="stats-container">
        <div class="d-flex justify-content-around flex-wrap">
          <div class="stat-item">
            <div class="stat-number"><?= $cantidad_servicios_categoria; ?></div>
            <div class="stat-label"><?= isset($lang["actividades"]) ? $lang["actividades"] : 'actividades'; ?></div>
          </div>
          <div class="stat-item">
            <div class="stat-number"><?= $nViajeros; ?></div>
            <div class="stat-label"><?= isset($lang["viajeros_lo_han_disfrutado"]) ? $lang["viajeros_lo_han_disfrutado"] : 'viajeros'; ?></div>
          </div>
          <div class="stat-item">
            <div class="stat-number"><?= $cantidad_opiniones_categoria; ?></div>
            <div class="stat-label"><?= isset($lang["opiniones_reales"]) ? $lang["opiniones_reales"] : 'opiniones reales'; ?></div>
          </div>
          <div class="stat-item">
            <div class="stat-number">9,2</div>
            <div class="stat-label"><?= isset($lang["asi_nos_puntuan"]) ? $lang["asi_nos_puntuan"] : 'así nos puntúan'; ?></div>
          </div>
        </div>
      </div>
    </div>
  </header>

  <!-- BÚSQUEDA Y FILTROS RESPONSIVOS -->
  <section class="controles-busqueda">
    <div class="container">
      <!-- BÚSQUEDA -->
      <form class="form-buscar mb-3" method="get">
        <div class="input-group">
          <input class="form-control form-control-lg form-control-search" name="buscar" type="text" placeholder="<?= isset($lang["que_hacemos"]) ? $lang["que_hacemos"] : 'Qué hacemos'; ?>" value="<?= htmlspecialchars($busqueda) ?>">
          <div class="input-group-append">
            <button class="btn btn-primary btn-lg btn-search" type="submit"><i class="fa fa-search"></i></button>
          </div>
        </div>
      </form>

      <!-- CONTROLES ORDENAMIENTO DESKTOP -->
      <div class="d-none d-md-flex gap-2">
      </div>

      <!-- BOTÓN FILTRO MÓVIL -->
      <button class="btn btn-outline-primary btn-lg btn-block btn-lg-filter d-md-none" data-toggle="modal" data-target="#filterModal">
        <i class="fa fa-sliders-h"></i> Filtrar y Ordenar
      </button>
    </div>
  </section>

  <!-- CONTENEDOR PRINCIPAL -->
  <main class="container py-4">
    <div class="row">

      <!-- SIDEBAR FILTROS (DESKTOP ONLY) -->
      <div class="col-lg-3 d-none d-lg-block">
        <div class="sidebar-container">
          <h3 class="sidebar-title">Filtrar resultados</h3>

          <!-- FILTRO DE BÚSQUEDA EN SIDEBAR -->
          <div class="accordion" id="accordionSearch">
            <div class="card">
              <div class="card-header" id="headingSearch">
                <h5 class="mb-0">
                  <a class="btn-accordion" href="#" data-toggle="collapse" data-target="#collapseSearch" aria-expanded="true" aria-controls="collapseSearch">
                    Búsqueda <i class="fa fa-sort-down float-right"></i>
                  </a>
                </h5>
              </div>
              <div id="collapseSearch" class="collapse show" aria-labelledby="headingSearch" data-parent="#accordionSearch">
                <div class="card-body">
                  <form method="get">
                    <div class="input-group">
                      <input type="text" class="form-control form-control-sm" name="buscar" placeholder="Buscar..." value="<?= htmlspecialchars($busqueda) ?>">
                      <div class="input-group-append">
                        <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>

          <!-- FILTRO DE PRECIO -->
          <div class="accordion" id="accordionPrice">
            <div class="card">
              <div class="card-header" id="headingPrice">
                <h5 class="mb-0">
                  <a class="btn-accordion" href="#" data-toggle="collapse" data-target="#collapsePrice" aria-expanded="true" aria-controls="collapsePrice">
                    Ordenar por Precio <i class="fa fa-sort-down float-right"></i>
                  </a>
                </h5>
              </div>
              <div id="collapsePrice" class="collapse show" aria-labelledby="headingPrice" data-parent="#accordionPrice">
                <div class="card-body">
                  <a href="?<?php echo !empty($queryString) ? $queryString . '&' : ''; ?>orden=price_asc" class="btn btn-sm btn-block btn-outline-primary mb-2">Menor Precio</a>
                  <a href="?<?php echo !empty($queryString) ? $queryString . '&' : ''; ?>orden=price_desc" class="btn btn-sm btn-block btn-outline-primary">Mayor Precio</a>
                </div>
              </div>
            </div>
          </div>

          <!-- FILTRO DE CATEGORÍAS -->
          <div class="accordion" id="accordionCategory">
            <div class="card">
              <div class="card-header" id="headingCategory">
                <h5 class="mb-0">
                  <a class="btn-accordion" href="#" data-toggle="collapse" data-target="#collapseCategory" aria-expanded="true" aria-controls="collapseCategory">
                    Categorías <i class="fa fa-sort-down float-right"></i>
                  </a>
                </h5>
              </div>
              <div id="collapseCategory" class="collapse show" aria-labelledby="headingCategory" data-parent="#accordionCategory">
                <div class="card-body">
                  <?php
                  $todas_las_categorias = getCategorias();
                  $urlParamsAll = "";
                  if (!empty($busqueda)) {
                    $urlParamsAll .= "buscar=" . urlencode($busqueda);
                  }
                  if (!empty($orden)) {
                    $urlParamsAll .= (!empty($urlParamsAll) ? "&" : "") . "orden=" . urlencode($orden);
                  }

                  $checkedAll = ($idCategoria == 0) ? "checked" : "";
                  echo '<a href="categorias?' . $urlParamsAll . '">
                         <div class="custom-control custom-checkbox mb-2">
                          <input type="' . ($idCategoria == 0 ? 'radio' : '') . '" class="custom-control-input" id="" ' . $checkedAll . '>
                          <label class="custom-control-label" for="">' . (isset($lang["todas_las_categorias"]) ? $lang["todas_las_categorias"] : 'Todas las categorías') . '</label>
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

                    $urlParams = "idCategoria=" . $idCategoria_todas;
                    if (!empty($busqueda)) {
                      $urlParams .= "&buscar=" . urlencode($busqueda);
                    }
                    if (!empty($orden)) {
                      $urlParams .= "&orden=" . urlencode($orden);
                    }

                    echo '<a href="categorias?' . $urlParams . '">
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

          <!-- ÚLTIMAS OPINIONES (si hay) -->
          <?php if (!empty($opiniones_categoria) && count($opiniones_categoria) > 0) : ?>
            <div class="card" style="border: none; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); margin-top: 2rem;">
              <div class="card-header" style="background-color: #f8f9fa; border-bottom: 1px solid #dee2e6; padding: 1rem;">
                <h5 class="mb-0" style="color: #333; font-weight: 700;">
                  <i class="fa fa-comments" style="margin-right: 0.5rem; color: #007bff;"></i>
                  <?= isset($lang["ultimas_opiniones"]) ? $lang["ultimas_opiniones"] : "Últimas opiniones"; ?>
                </h5>
              </div>
              <div class="card-body" style="padding: 0;">
                <?php 
                $opiniones_mostradas = 0;
                for ($b = 0; $b < count($opiniones_categoria) && $opiniones_mostradas < 3; $b++, $opiniones_mostradas++) : 
                ?>
                  <div style="padding: 1rem; border-bottom: 1px solid #f0f0f0;">
                    <p style="color: #007bff; font-weight: 600; margin-bottom: 0.5rem; font-style: italic;">
                      "<?= htmlspecialchars(substr($opiniones_categoria[$b]['opinion'], 0, 150)); ?><?= strlen($opiniones_categoria[$b]['opinion']) > 150 ? '...' : ''; ?>"
                    </p>
                    <p style="margin-bottom: 0; color: #6c757d; font-size: 0.9rem;">
                      <i class="fa fa-star" style="color: #007bff;"></i>
                      <strong><?= $opiniones_categoria[$b]['nombre']; ?></strong>
                    </p>
                  </div>
                <?php endfor; ?>
              </div>
            </div>
          <?php endif; ?>

        </div>
      </div>

      <!-- ÁREA DE SERVICIOS -->
      <div class="col-lg-9">

        <!-- STATS MÓVIL -->
        <div class="d-md-none py-3">
          <div class="row text-center">
            <div class="col-6 mb-2">
              <div class="stat-number" style="font-size: 1.8rem;"><?= $cantidad_servicios_categoria; ?></div>
              <div class="stat-label" style="font-size: 0.85rem;"><?= isset($lang["actividades"]) ? $lang["actividades"] : 'actividades'; ?></div>
            </div>
            <div class="col-6 mb-2">
              <div class="stat-number" style="font-size: 1.8rem;"><?= $nViajeros; ?></div>
              <div class="stat-label" style="font-size: 0.85rem;"><?= isset($lang["viajeros"]) ? $lang["viajeros"] : 'viajeros'; ?></div>
            </div>
          </div>
        </div>

        <!-- SERVICIOS -->
        <?php if (count($servicios) > 0) : ?>
          <!-- GRID DESKTOP (3 columnas) / STACK MÓVIL (1 columna) -->
          <div class="row">
            <?php for ($i = 0; $i < count($servicios); $i++) {
              $idServicio = $servicios[$i]["idServicio"];
              $fecha = date("Y-m-d");
              $salidas = getSalidasFechaLuegoIdServicio($fecha, $idServicio);

              if (!empty($salidas)) {
                $idMoneda = $salidas[0]['idMoneda'];
                $idServicioSalidas = $salidas[0]['idServicioSalidas'];
                $tarifas = getTarifas($idServicioSalidas);

                $precioSugerido = "Consultar";
                if (!empty($tarifas)) {
                  $tarifa = calculaTarifa($tarifas[0]['idServicioSalidasTarifas'], 1);
                  $precioSugerido = ($tarifa[0]["valorSym"]);

                  $cancelaciones = getTipoCancelaciones($tarifas[0]['idCancelaciones']);
                  $cancelacion = "";
                  switch ($cancelaciones[0]["idCancelacion"]) {
                    case 1:
                    case 3:
                    case 7:
                      $cancelacion = isset($lang["cancelamento_gratis"]) ? $lang["cancelamento_gratis"] : "Cancelamento gratis!";
                      break;
                  }
                }

                $nombre_servicio = $servicios[$i]["nombre_servicio"];
                $descripcion_corta = $servicios[$i]["descripcion_corta"];
                $opiniones_servicio = getOpinionesServicio($idServicio);
                $estrellas_servicio = getEstrellasServicio($idServicio);
                $cantidad_opiniones_servicio = count($opiniones_servicio);
                $duracion_servicio = getDuracionServicio($idServicio);
                $fotos_servicio = getFotoMiniaturaServicio($idServicio);
                $ruta_foto = !empty($fotos_servicio) ? $fotos_servicio[0]["ruta"] : 'placeholder.jpg';
                $textoMiniatura = getTextoMiniatura($servicios[$i]["idTextoMiniaturas"])[0]["texto"] ?? '';

            ?>
              <!-- TARJETA SERVICIO - 3 COL DESKTOP, 1 COL MÓVIL -->
              <div class="col-lg-4 col-md-6 col-12 mb-4">
                <div class="service-card">
                  <a href="servicio?id=<?= $idServicio ?>">
                    <div class="card-img-container">
                      <img src="admin/classes/imgServicio/<?= $ruta_foto; ?>" class="img-fluid w-100" alt="<?= htmlspecialchars($nombre_servicio) ?>">
                      <?php if (!empty($textoMiniatura)) : ?>
                        <div class="badge-top"><?= $textoMiniatura; ?></div>
                      <?php endif; ?>
                    </div>
                    <div class="card-body">
                      <h5 class="card-title"><?= $nombre_servicio ?></h5>
                      <p class="rating-text">
                        <strong><?= $estrellas_servicio; ?>/10</strong> 
                        <span>(<?= $cantidad_opiniones_servicio; ?> opiniones)</span>
                      </p>
                      <!-- DESCRIPCIÓN: SOLO DESKTOP -->
                      <p class="description-text d-none d-md-block"><?= $descripcion_corta; ?></p>

                      <!-- DURACIÓN -->
                      <?php if(!empty($duracion_servicio)): ?>
                        <ul class="features-list">
                          <li><i class="fa fa-hourglass-half"></i> <?= $duracion_servicio["duracionMinima"]; ?> - <?= $duracion_servicio["duracionMaxima"]; ?></li>
                        </ul>
                      <?php endif; ?>

                      <!-- PRECIO Y CANCELACIÓN -->
                      <div class="price-section">
                        <div>
                          <?php if (!empty($cancelacion)) : ?>
                            <h6 class="cancellation-text mb-0"><i class="fa fa-check-circle"></i> <?= $cancelacion ?></h6>
                          <?php endif; ?>
                        </div>
                        <p class="price-text mb-0"><?= $precioSugerido; ?></p>
                      </div>
                    </div>
                  </a>
                </div>
              </div>

            <?php
              }
            }
            ?>
          </div>

          <!-- PAGINACIÓN -->
          <?php
          $cantidad_de_paginas = ceil($cantidad_servicios_categoria / $cantidad_por_pagina);
          if ($cantidad_de_paginas > 1) {
          ?>
            <nav aria-label="Paginación de resultados" class="mt-4">
              <ul class="pagination justify-content-center">
                <li class="page-item <?= ($pagina <= 1) ? 'disabled' : '' ?>">
                  <a class="page-link" href="?pagina=<?= $pagina - 1 ?>&<?= $queryString ?>" aria-label="Anterior">
                    <span aria-hidden="true">&laquo;</span>
                  </a>
                </li>

                <li class="page-item disabled d-none d-sm-block">
                  <span class="page-link">Página <?= $pagina ?> de <?= $cantidad_de_paginas ?></span>
                </li>

                <li class="page-item <?= ($pagina >= $cantidad_de_paginas) ? 'disabled' : '' ?>">
                  <a class="page-link" href="?pagina=<?= $pagina + 1 ?>&<?= $queryString ?>" aria-label="Siguiente">
                    <span aria-hidden="true">&raquo;</span>
                  </a>
                </li>
              </ul>
            </nav>
          <?php } ?>

        <?php else : ?>
          <div class="no-results">
            <h3>No se encontraron resultados</h3>
            <p>Intenta cambiar tus filtros o términos de búsqueda.</p>
          </div>
        <?php endif; ?>

        <!-- TARJETA GUÍA (DESKTOP ONLY) -->
        <?php if ($idCategoria > 0) : ?>
          <div class="card card-guia d-none d-lg-block mt-5" style="border: none; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);">
            <div class="card-body" style="padding: 0;">
              <form action="guias.php" method="get" style="display: flex; height: 250px; text-decoration: none; color: inherit;">
                <input type="hidden" name="idCategoria" value="<?= $idCategoria; ?>">
                
                <!-- IMAGEN IZQUIERDA -->
                <div style="flex: 0 0 40%; background-color: #f0f0f0; overflow: hidden;">
                  <img src="admin/img/categoria_servicio/<?= $fotos; ?>" class="img-fluid w-100" style="height: 100%; object-fit: cover;">
                </div>

                <!-- CONTENIDO DERECHA -->
                <div style="flex: 1; padding: 2rem; display: flex; flex-direction: column; justify-content: center; background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);">
                  <h4 style="color: white; font-weight: 700; margin-bottom: 0.5rem;">
                    <i class="fa fa-map" style="margin-right: 0.5rem;"></i>
                    <?= isset($lang["conoce_nuestra_guia_de"]) ? $lang["conoce_nuestra_guia_de"] : "Conoce nuestra guía de"; ?>
                  </h4>
                  <p style="color: rgba(255,255,255,0.9); font-size: 1.2rem; margin: 0;">
                    <?= isset($lang[$nombre_categoria]) ? $lang[$nombre_categoria] : $nombre_categoria; ?>
                  </p>
                  <button type="submit" class="btn btn-light mt-3" style="align-self: flex-start;">
                    Ver Guía <i class="fa fa-arrow-right ml-2"></i>
                  </button>
                </div>
              </form>
            </div>
          </div>
        <?php endif; ?>

      </div>

    </div>
  </main>

  <!-- MODAL FILTROS MÓVIL -->
  <div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="filterModalLabel">Filtrar y Ordenar</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <h6 class="font-weight-bold mb-3">Ordenar por precio:</h6>
          <div class="btn-group-vertical d-flex w-100 mb-4">
            <a href="?<?php echo !empty($queryString) ? $queryString . '&' : ''; ?>orden=price_asc" class="btn btn-outline-primary <?= $orden === 'price_asc' ? 'active' : ''; ?>">
              <i class="fa fa-arrow-up"></i> Menor Precio
            </a>
            <a href="?<?php echo !empty($queryString) ? $queryString . '&' : ''; ?>orden=price_desc" class="btn btn-outline-primary <?= $orden === 'price_desc' ? 'active' : ''; ?>">
              <i class="fa fa-arrow-down"></i> Mayor Precio
            </a>
            <a href="?<?php echo !empty($queryString) ? $queryString . '&' : ''; ?>" class="btn btn-outline-secondary">
              <i class="fa fa-times"></i> Limpiar Filtros
            </a>
          </div>

          <h6 class="font-weight-bold mb-3">Categorías:</h6>
          <div class="btn-group-vertical d-flex w-100">
            <?php
            $todas_las_categorias = getCategorias();
            $urlParamsAll = "";
            if (!empty($busqueda)) {
              $urlParamsAll .= "buscar=" . urlencode($busqueda);
            }
            if (!empty($orden)) {
              $urlParamsAll .= (!empty($urlParamsAll) ? "&" : "") . "orden=" . urlencode($orden);
            }

            echo '<a href="categorias?' . $urlParamsAll . '" class="btn btn-outline-primary ' . ($idCategoria == 0 ? 'active' : '') . '">' . (isset($lang["todas_las_categorias"]) ? $lang["todas_las_categorias"] : 'Todas') . '</a>';

            for ($i = 0; $i < count($todas_las_categorias); $i++) {
              $idCategoria_todas = $todas_las_categorias[$i]["idCategoria_servicio"];
              $nombre_categoria_servicio_todas = $todas_las_categorias[$i]["nombre_categoria_servicio"];

              $urlParams = "idCategoria=" . $idCategoria_todas;
              if (!empty($busqueda)) {
                $urlParams .= "&buscar=" . urlencode($busqueda);
              }
              if (!empty($orden)) {
                $urlParams .= "&orden=" . urlencode($orden);
              }

              $isActive = ($idCategoria == $idCategoria_todas) ? 'active' : '';
              echo '<a href="categorias?' . $urlParams . '" class="btn btn-outline-primary ' . $isActive . '">' . $nombre_categoria_servicio_todas . '</a>';
            }
            ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php include('footer.php'); ?>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
