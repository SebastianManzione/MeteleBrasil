```php
<?php
include('includes/navbar.php');
include('admin/classes/categoria.php');
include('admin/classes/fotos_categoria.php');
include('admin/classes/opiniones_categoria.php');
include('admin/classes/servicio_opiniones.php');
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
  $servicios = getServiciosPaginado($desde, $cantidad_por_pagina);
  $cantidad_servicios_categoria = count(getServicios());
  $categorias = getCategorias();
  $nViajeros = rand(690, 1200);
  $nombre_categoria = "todas_las_categorias";
  $opiniones_categoria = array();
  $cantidad_opiniones_categoria = rand(100, 500);;
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
    body {
      background-color: #f4f7f6;
    }

    .header-categoria {
      background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('admin/img/categoria_servicio/<?= $fotos; ?>');
      background-size: cover;
      background-position: center;
      color: white;
      padding: 4rem 1rem;
      text-align: center;
    }

    .header-categoria h1 {
      font-weight: 700;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.6);
    }

    .controles-busqueda {
      background-color: #ffffff;
      padding: 1rem;
      border-bottom: 1px solid #dee2e6;
      position: sticky;
      top: 0;
      z-index: 1020;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .btn-lg-filter {
      padding: 0.8rem 1rem;
      font-size: 1.1rem;
      font-weight: 600;
    }

    .service-card {
      background-color: #fff;
      border: none;
      border-radius: 12px;
      overflow: hidden;
      margin-bottom: 1.5rem;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
      transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }

    .service-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
    }

    .service-card a {
      text-decoration: none;
      color: inherit;
    }

    .service-card .card-img-container {
      position: relative;
    }

    .service-card .badge-top {
      position: absolute;
      top: 15px;
      left: 15px;
      font-size: 0.85rem;
      padding: 0.5em 0.9em;
      border-radius: 50px;
      font-weight: 600;
    }

    .service-card .card-body {
      padding: 1.25rem;
    }

    .service-card .card-title {
      font-weight: 600;
      font-size: 1.2rem;
      color: #333;
      margin-bottom: 0.5rem;
    }

    .service-card .rating-text {
      color: #6c757d;
      font-size: 0.9rem;
    }

    .service-card .rating-text strong {
      color: #007bff;
    }

    .service-card .description-text {
      color: #495057;
      font-size: 0.95rem;
      margin-top: 0.75rem;
    }

    .service-card .features-list {
      list-style: none;
      padding: 0;
      margin: 1rem 0;
      font-size: 0.9rem;
      color: #6c757d;
    }

    .service-card .features-list li {
      margin-bottom: 0.3rem;
    }

    .service-card .features-list i {
      color: #007bff;
      margin-right: 8px;
    }

    .service-card .price-section {
      border-top: 1px solid #f0f0f0;
      padding-top: 1rem;
      margin-top: 1rem;
    }
    
    .service-card .cancellation-text {
      font-weight: 600;
      font-size: 0.9rem;
    }

    .service-card .price-text {
      font-size: 1.6rem;
      font-weight: 700;
      color: #28a745;
    }

    .modal-body .btn-group .btn {
      flex: 1;
    }

    .modal-body .btn-group-vertical .btn {
        text-align: left;
        border-radius: 8px !important;
        margin-bottom: 0.5rem;
        padding: 1rem;
        font-size: 1.1rem;
    }
    .modal-body .btn-group-vertical .btn.active {
        background-color: #007bff;
        color: white;
        font-weight: bold;
    }
    .form-control-search {
        border-radius: 50px 0 0 50px;
    }
    .btn-search {
        border-radius: 0 50px 50px 0;
    }
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

  </style>
</head>

<body>

  <header class="header-categoria">
    <h1><?= $lang[$nombre_categoria]; ?></h1>
    <p class="lead mb-0"><?= $cantidad_servicios_categoria; ?> <?= $lang["actividades_encontradas"] ?? 'actividades encontradas' ?></p>
  </header>

  <section class="controles-busqueda">
    <div class="container">
        <form class="form-buscar mb-2" action="categorias.php" method="get">
            <div class="input-group">
              <input class="form-control form-control-lg form-control-search" name="buscar" type="text" placeholder="<?= $lang["que_hacemos"]; ?>" value="<?= htmlspecialchars($busqueda) ?>">
              <div class="input-group-append">
                <button class="btn btn-primary btn-lg btn-search" type="submit"><i class="fa fa-search"></i></button>
              </div>
            </div>
        </form>
        <button class="btn btn-outline-primary btn-lg btn-block btn-lg-filter" data-toggle="modal" data-target="#filterModal">
            <i class="fa fa-sliders-h"></i> Filtrar y Ordenar
        </button>
    </div>
  </section>

  <main class="container py-4">
    <div class="row">
      <div class="col-12">
        <?php if (count($servicios) > 0) : ?>
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
                    $cancelacion = $lang["cancelamento_gratis"] ?? "Cancelamento gratis!";
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
              $textoMiniatura = getTextoMiniatura($servicios[$i]["idTextoMiniaturas"])[0]["texto"];
          ?>

              <div class="service-card">
                <a href="servicio?id=<?= $idServicio ?>">
                  <div class="card-img-container">
                    <img src="admin/classes/imgServicio/<?= $ruta_foto; ?>" class="img-fluid w-100" alt="<?= htmlspecialchars($nombre_servicio) ?>">
                    <?php if ($textoMiniatura) : ?>
                      <div class="badge badge-primary badge-top"><?= $textoMiniatura; ?></div>
                    <?php endif; ?>
                  </div>
                  <div class="card-body">
                    <h5 class="card-title"><?= $nombre_servicio ?></h5>
                    <p class="rating-text">
                      <strong><?= $estrellas_servicio; ?>/10</strong> 
                      <span>(<?= $cantidad_opiniones_servicio; ?> opiniones)</span>
                    </p>
                    <p class="description-text d-none d-sm-block"><?= $descripcion_corta; ?></p>
                    
                    <ul class="features-list">
                        <?php if(!empty($duracion_servicio)): ?>
                            <li><i class="fa fa-hourglass-half"></i> <?= $duracion_servicio["duracionMinima"]; ?> - <?= $duracion_servicio["duracionMaxima"]; ?></li>
                        <?php endif; ?>
                    </ul>

                    <div class="price-section d-flex justify-content-between align-items-center">
                        <div>
                            <?php if (!empty($cancelacion)) : ?>
                                <h6 class="text-success cancellation-text mb-0"><i class="fa fa-check-circle"></i> <?= $cancelacion ?></h6>
                            <?php endif; ?>
                        </div>
                      <p class="price-text mb-0"><?= $precioSugerido; ?></p>
                    </div>
                  </div>
                </a>
              </div>
          <?php
            }
          }
          ?>
        <?php else : ?>
          <div class="text-center py-5">
            <h3>No se encontraron resultados</h3>
            <p>Intenta cambiar tus filtros o términos de búsqueda.</p>
          </div>
        <?php endif; ?>

        <!-- PAGINACIÓN -->
        <?php
        $cantidad_de_paginas = ceil($cantidad_servicios_categoria / $cantidad_por_pagina);
        if ($cantidad_de_paginas > 1) {
        ?>
          <nav aria-label="Paginación de resultados">
            <ul class="pagination justify-content-center mt-4">
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
      </div>
    </div>
  </main>

  <!-- MODAL DE FILTROS -->
  <div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="filterModalLabel">Filtrar y Ordenar</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- DISPONIBILIDAD -->
          <h6 class="font-weight-bold">Disponibilidad</h6>
          <div class="btn-group btn-group-toggle d-flex mb-4" data-toggle="buttons">
            <label class="btn btn-outline-primary btn-lg flex-fill">
              <input type="radio" name="options" id="option1" autocomplete="off"> <?= $lang["hoy"]; ?>
            </label>
            <label class="btn btn-outline-primary btn-lg flex-fill">
              <input type="radio" name="options" id="option2" autocomplete="off"> <?= $lang["manana"]; ?>
            </label>
          </div>

          <!-- CATEGORÍAS -->
          <h6 class="font-weight-bold">Categorías</h6>
          <div class="btn-group-vertical d-flex mb-4 w-100">
             <?php
                $todas_las_categorias = getCategorias();
                foreach ($todas_las_categorias as $cat) {
                    $isActive = ($idCategoria == $cat["idCategoria_servicio"]) ? 'active' : '';
                    echo '<a href="categorias.php?idCategoria=' . $cat["idCategoria_servicio"] . '" class="btn btn-outline-primary ' . $isActive . '">' . $cat["nombre_categoria_servicio"] . '</a>';
                }
             ?>
          </div>

          <!-- ORDENAR -->
          <h6 class="font-weight-bold">Ordenar por Precio</h6>
           <div class="btn-group-vertical d-flex w-100">
                <a href="?priceMin=1&<?= $queryString ?>" class="btn btn-outline-primary <?= isset($_GET['priceMin']) ? 'active' : '' ?>"><?= $lang["menor_precio"]; ?></a>
                <a href="?priceMax=1&<?= $queryString ?>" class="btn btn-outline-primary <?= isset($_GET['priceMax']) ? 'active' : '' ?>"><?= $lang["mayor_precio"]; ?></a>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary btn-lg btn-block" data-dismiss="modal">Ver Resultados</button>
        </div>
      </div>
    </div>
  </div>

  <?php include "footer.php"; ?>

  <!-- SCRIPTS NECESARIOS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="js/script.js"></script>
</body>

</html>
```