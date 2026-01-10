<?php
include("includes/navbar.php");
require("admin/classes/opiniones_categoria.php");
require_once("admin/classes/salidas.php");
require("admin/classes/categoria.php");
require("admin/classes/servicio_opiniones.php");
require("admin/classes/texto_miniaturas.php");

if (!function_exists('Visitante')) {
    function Visitante($arr) {
        $_SESSION['visitante'] = $arr;
    }
}

$ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
$geoFinal = getGeolocalizacionData();
$visitante = [$ip, $geoFinal['nombre_pais'] ?? 'Desconocido', 'index.php'];
Visitante($visitante);

// Usar la conexión global desde navbar.php que ya cargó db.php
$mysqli = $GLOBALS['mysqli'] ?? null;

// Si no hay MySQLi global, usar configuración centralizada
if (!($mysqli instanceof mysqli)) {
    require_once(__DIR__ . '/config/config.php');
    @$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($mysqli->connect_error) {
        die("Error de conexión: " . $mysqli->connect_error);
    }
}

$latUsuario = floatval($geoFinal['latitud'] ?? 0);
$lonUsuario = floatval($geoFinal['longitud'] ?? 0);

// Seleccionar el campo de nombre seg├║n el idioma
$idioma = $_SESSION['idioma'] ?? 'ES';
$campoNombre = 'nombre_servicio'; // Por defecto es espa├▒ol
$campoDescripcion = 'descripcion_corta'; // Por defecto es espa├▒ol
if ($idioma === 'EN') {
    $campoNombre = 'nombre_servicio_en';
    $campoDescripcion = 'descripcion_corta_en';
} elseif ($idioma === 'PT') {
    $campoNombre = 'nombre_servicio_pt';
    $campoDescripcion = 'descripcion_corta_pt';
} elseif ($idioma === 'IT') {
    $campoNombre = 'nombre_servicio_it';
    $campoDescripcion = 'descripcion_corta_it';
}

// Query rápida: primero obtener servicios, luego coordenadas en una sola query adicional
$sql = "SELECT s.idServicio, s.$campoNombre as nombre_servicio, s.$campoDescripcion as descripcion_corta, 
        s.destacado, s.idTextoMiniaturas
        FROM servicio s 
        WHERE s.destacado = 1 AND s.habilitado = 1 
        LIMIT 24";
$result = $mysqli->query($sql);
$servicios_geo = [];
$servicioIds = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $servicios_geo[$row['idServicio']] = $row;
        $servicioIds[] = $row['idServicio'];
    }
}

// Obtener coordenadas para todos los servicios en una sola query
if (!empty($servicioIds)) {
    $ids = implode(',', array_map('intval', $servicioIds));
    $sqlGeo = "SELECT ss.idServicio, 
               AVG(CAST(u.latitud AS DECIMAL(10,7))) as latitud,
               AVG(CAST(u.longitud AS DECIMAL(10,7))) as longitud
               FROM servicio_salidas ss
               JOIN servicio_salidas_tarifas st ON ss.idServicioSalidas = st.idServicioSalidas
               JOIN servicio_tarifas_ubicacion u ON st.idServicioSalidasTarifas = u.idServicioSalidasTarifas
               WHERE ss.idServicio IN ($ids)
               GROUP BY ss.idServicio";
    $resGeo = $mysqli->query($sqlGeo);
    if ($resGeo && $resGeo->num_rows > 0) {
        while ($geo = $resGeo->fetch_assoc()) {
            $servicios_geo[$geo['idServicio']]['latitud'] = $geo['latitud'] ?? 0;
            $servicios_geo[$geo['idServicio']]['longitud'] = $geo['longitud'] ?? 0;
        }
    }
}

// Convertir a array indexado y agregar distancia placeholder
$servicios_geo = array_values($servicios_geo);
foreach ($servicios_geo as &$servicio) {
    if (!isset($servicio['latitud'])) $servicio['latitud'] = 0;
    if (!isset($servicio['longitud'])) $servicio['longitud'] = 0;
    $servicio['distancia'] = 999999;
}

// Calcular distancia y ordenar servicios por proximidad (más cercanos primero)
if ($latUsuario != 0 && $lonUsuario != 0) {
    foreach ($servicios_geo as &$servicio) {
        $servicio['distancia'] = calcularDistancia($latUsuario, $lonUsuario, floatval($servicio['latitud'] ?? 0), floatval($servicio['longitud'] ?? 0));
    }
    
    usort($servicios_geo, function($a, $b) {
        $distA = $a['distancia'] ?? PHP_FLOAT_MAX;
        $distB = $b['distancia'] ?? PHP_FLOAT_MAX;
        return $distA <=> $distB;
    });
}

$servicios = array_slice($servicios_geo, 0, 6);
$servicios_restantes = array_slice($servicios_geo, 6);

// Funci├│n para calcular distancia entre dos puntos (Haversine)
function calcularDistancia($lat1, $lon1, $lat2, $lon2) {
    if ($lat2 == 0 && $lon2 == 0) return PHP_FLOAT_MAX; // Si no tiene coordenadas, ponerlo al final
    $R = 6371; // Radio de la Tierra en km
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);
    $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2) * sin($dLon/2);
    $c = 2 * atan2(sqrt($a), sqrt(1-$a));
    return $R * $c;
}
?>
<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
  <ol class="carousel-indicators">
    <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
    <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
    <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
    <li data-target="#carouselExampleIndicators" data-slide-to="3"></li>
  </ol>
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img class="img-fluid img-slider" src="img/slider4.jpg" alt="Slider 1">
    </div>
    <div class="carousel-item">
      <img class="img-fluid img-slider" src="img/slider2.jpg" alt="Slider 2">
    </div>
    <div class="carousel-item">
      <img class="img-fluid img-slider" src="img/slider3.jpg" alt="Slider 3">
    </div>
    <div class="carousel-item">
      <img class="img-fluid img-slider" src="img/slider1.jpg" alt="Slider 4">
    </div>
  </div>
  <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
  </a>
  <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
  </a>
</div>
<section class="div-absolute" id="capa2" >
  <div class="container">
    <div class="row">
      <div class="col-lg-6 offset-lg-3 mb-5">
        <h1 class="text-white text-uppercase titulo">
          <span class="semibold"><?= $lang["crea_tu_viaje"] ?? "Crea tu viaje" ?></span><br>
          <?= $lang["excursiones_en_brasil"] ?? "Excursiones en Brasil" ?>
        </h1>
        <form class="form-buscar mb-5" action="categorias" method="get">
          <label class="sr-only" for="buscar"><?= $lang["que_hacemos"] ?? "┬┐Qu├® hacemos?" ?></label>
          <div class="input-group">
            <input class="field form-control form-control-search" id="buscar" name="buscar" type="text" placeholder="<?= $lang["que_hacemos"] ?? "┬┐Qu├® hacemos?" ?>" value="">
            <span class="input-group-append">
              <button class="submit btn btn-primary" name="submit" type="submit"><?= $lang["buscar"] ?? "Buscar" ?> <i class="fa fa-arrow-right"></i></button>
            </span>
          </div>
        </form>
      </div>
      <!-- Beneficios en el Banner -->
      <div class="col-lg-12 text-center text-white div-bottom" >
        <div class="container">
          <div class="row">
            <div class="col-lg-3 col-md-3 col-3">
              <i class="text-white fa fa-calendar-check fa-2x mb-2"></i>
              <p class="texto-bottom">Las mejores actividades</p>
            </div>
            <div class="col-lg-3 col-md-3 col-3">
              <i class="text-white fa fa-headset fa-2x mb-2"></i>
              <p class="texto-bottom">Atención al cliente 24/7</p>
            </div>
            <div class="col-lg-3 col-md-3 col-3">
              <i class="text-white fa fa-comment-dots fa-2x mb-2"></i>
              <p class="texto-bottom">Miles de opiniones</p>
            </div>
            <div class="col-lg-3 col-md-3 col-3">
              <i class="text-white fa fa-hand-holding-usd fa-2x mb-2"></i>
              <p class="texto-bottom">Sin sobreprecios ni costos ocultos</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<section class="py-5"  style="background-color: rgb(245, 244, 245);">
  <div class="container" style="background-color: rgb(245, 244, 245);" >
    <div class="row mb-4">
      <div class="col-lg-12">
        <h2 class="page-section-heading text-center text-uppercase text-secondary mb-0">
          <?= $lang["principales_actividades"] ?? "Principales Actividades" ?>
        </h2>
      </div>
    </div>
    <div class="row" >
      <?php
      $categorias = getCategoriasLimit6();
      for ($i = 0; $i < count($categorias); $i++) {
          $idCategoria_servicio = $categorias[$i]['idCategoria_servicio'];
          $OpinionesCategoria = OpinionesCategoria($idCategoria_servicio);
          $CantOpinionesCategoria = count($OpinionesCategoria);
          $estrellas = getEstrellasCategoria($idCategoria_servicio);
      ?>
      <div class="col-lg-4 col-md-6 col-12 mb-4" >
        <a href="categorias?idCategoria=<?= $categorias[$i]['idCategoria_servicio'] ?>" class="imagen">
          <div class="img-c" style="background-image: url(admin/img/categoria_servicio/<?= $categorias[$i]['img_categoria_servicio'] ?>)">
            <div class="info d-md-block d-none">
              <div class="row">
                <div class="col-lg-12 texto-categoria">
                  <h3 class="headline text-uppercase semibold mb-3"><?= $categorias[$i]['nombre_categoria_servicio'] ?></h3>
                  <div class="row">
                    <div class="col-md-6">
                      <p class="mb-0"><strong style="font-size:30px;"><?= $categorias[$i]['nViajeros'] ?></strong></p>
                      <p class="mb-0 p"><?= $lang["viajeros_ya_lo_han_disfrutado"] ?? "Viajeros lo disfrutaron" ?></p>
                    </div>
                    <div class="col-md-6">
                      <p class="mb-0"><strong style="font-size:30px;"><?= $estrellas ?>*****</strong></p>
                      <p class="mb-0 p"><?= $CantOpinionesCategoria ?> <?= $lang["opiniones"] ?? "Opiniones" ?></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <h3 class="title-categoria text-uppercase texto-shadow text-white"><?= $categorias[$i]['nombre_categoria_servicio'] ?></h3>
        </a>
      </div>
      <?php } ?>
      <?php
      $idiomaSel = $_SESSION['idioma'] ?? 'ES';
      $labelVerMas = $lang["ver_mas"] ?? ($idiomaSel === 'EN' ? 'See more' : ($idiomaSel === 'PT' ? 'Ver mais' : ($idiomaSel === 'IT' ? 'Vedi di più' : 'Ver más')));
      $labelVerMenos = $lang["ver_menos"] ?? ($idiomaSel === 'EN' ? 'See less' : ($idiomaSel === 'PT' ? 'Ver menos' : ($idiomaSel === 'IT' ? 'Vedi meno' : 'Ver menos')));
      
      // Obtener límite configurado
      require_once("admin/classes/configuracion.php");
      $configIndex = new Configuracion();
      $limiteInicial = $configIndex->obtener('index_categorias_iniciales', 6);
      $totalCategorias = count(getCategorias()); // Total de categorías habilitadas
      ?>
    </div>
    <?php if ($totalCategorias > $limiteInicial) { ?>
    <div class="row mt-4">
      <div class="col-lg-12 text-center">
        <button id="btn-ver-mas-VerMasActividades" class="btn btn-white" type="button" onclick="toggleDiv('VerMasActividades', this)">
          <?= $labelVerMas ?>
        </button>
      </div>
    </div>
    <?php } ?>
    <div class="row toggle-section" id="VerMasActividades" style="max-height:0; overflow:hidden;">
      <?php
      $categorias = getCategoriasLimit612();
      for ($i = 0; $i < count($categorias); $i++) {
          $idCategoria_servicio = $categorias[$i]['idCategoria_servicio'];
          $OpinionesCategoria = OpinionesCategoria($idCategoria_servicio);
          $CantOpinionesCategoria = count($OpinionesCategoria);
          $estrellas = getEstrellasCategoria($idCategoria_servicio);
      ?>
      <div class="col-lg-4 col-md-6 col-12 mb-4">
        <a href="categorias?idCategoria=<?= $categorias[$i]['idCategoria_servicio'] ?>" class="imagen">
          <div class="img-c" style="background-image: url(admin/img/categoria_servicio/<?= $categorias[$i]['img_categoria_servicio'] ?>)">
            <div class="info d-md-block d-none" >
              <div class="row">
                <div class="col-lg-12 texto-categoria">
                  <h3 class="headline text-uppercase semibold mb-3"><?= $categorias[$i]['nombre_categoria_servicio'] ?></h3>
                  <div class="row">
                    <div class="col-md-6">
                      <p class="mb-0"><strong style="font-size:30px;"><?= $categorias[$i]['nViajeros'] ?></strong></p>
                      <p class="mb-0 p"><?= $lang["viajeros_ya_lo_han_disfrutado"] ?? "Viajeros lo disfrutaron" ?></p>
                    </div>
                    <div class="col-md-6">
                      <p class="mb-0"><strong style="font-size:30px;"><?= $estrellas ?>*****</strong></p>
                      <p class="mb-0 p"><?= $CantOpinionesCategoria ?> <?= $lang["opiniones"] ?? "Opiniones" ?></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <h3 class="title-categoria text-uppercase texto-shadow text-white"><?= $categorias[$i]['nombre_categoria_servicio'] ?></h3>
        </a>
      </div>
      <?php } ?>
      <!-- Bot├│n Ver menos removido -->
    </div>
  </div>
</section>
<section class="py-5"  style="background-color: rgb(245, 244, 245);">
  <div class="container"  style="background-color: rgb(245, 244, 245);">
    <div class="row mb-4">
      <div class="col-lg-12">
        <h2 class="page-section-heading text-center text-uppercase text-secondary mb-0">
          <?= $lang["actividades_destacadas"] ?? "Actividades Destacadas" ?>
        </h2>
      </div>
    </div>
    <div class="row">
      <?php
      $fecha = date("Y-m-d");
      for ($i = 0; $i < count($servicios); $i++) {
          $idServicio = $servicios[$i]["idServicio"];
          $descripcionCorta = $servicios[$i]["descripcion_corta"] ?? "";
          $salidas = getSalidasFechaLuegoIdServicio($fecha, $idServicio);
          if (count($salidas) > 0) {
              $idServicioSalidas = $salidas[0]['idServicioSalidas'];
              $tarifas = getTarifas($idServicioSalidas);
              if (!empty($tarifas)) {
                  $tarifa = @calculaTarifa($tarifas[0]['idServicioSalidasTarifas'] ?? null, 1);
                  $precioSugerido = (!empty($tarifa) && isset($tarifa[0]["valorSym"])) ? $tarifa[0]["valorSym"] : "ESGOTADO";
              } else {
                  $precioSugerido = "ESGOTADO";
              }
          } else {
              $precioSugerido = "ESGOTADO";
          }
          $OpinionesServicio = getOpinionesServicio($idServicio);
          $estrellasServicio = getEstrellasServicio($idServicio);
          $textoMiniaturaData = getTextoMiniatura($servicios[$i]["idTextoMiniaturas"] ?? 0);
          $textoMiniatura = (!empty($textoMiniaturaData)) ? $textoMiniaturaData[0]["texto"] : "";
          $fotos = getFotoMiniaturaServicio($idServicio);
      ?>
      <div class="col-lg-4 col-md-6 mb-4" >
        <a href="servicio?id=<?= $idServicio ?>" class="destacados">
          <div class="d-destacado d-md-block d-none" >
            <div class="row wow animated bounceInUp animated" data-wow-duration="2s" style="visibility: visible; animation-duration: 2s; animation-name: bounceInUp;">
              <div class="col-lg-12 texto-destacado">
                <p class="title-big mb-0" style="margin-bottom:-15px !important; margin-top: 20px; line-height: 24px;"><?= $servicios[$i]["nombre_servicio"] ?></p>
                <?php if (count($OpinionesServicio) > 0) { ?>
                <div class="d-flex flex-row">
                  <div class="">
                    <p class="title-number mb-0"><?= $estrellasServicio ?></p>
                  </div>
                  <div class="p-1 my-auto">
                    <p class="mb-0" style="margin-top:20px;">
                      <i class="fa fa-star"></i>
                      <i class="fa fa-star"></i>
                      <i class="fa fa-star"></i>
                      <i class="fa fa-star"></i>
                      <i class="fa fa-star"></i>
                    </p>
                    <p><small class="text--rating-total"><?= count($OpinionesServicio) ?>  <?= $lang["opiniones"] ?? "opiniones" ?></small></p>
                  </div>
                </div>
                <?php } ?>
                <p class="p-text" style="margin-top:-5px;"><?= $descripcionCorta ?></p>
              </div>
            </div>
          </div>
          <div class="card card-destacadas" >
            <img src="admin/classes/imgServicio/<?= (!empty($fotos) && isset($fotos[0]['ruta'])) ? $fotos[0]['ruta'] : 'placeholder.jpg' ?>" class="img-fluid img-card-top img-destacada">
            <div class="destacado">
              <h5 class="text-uppercase text-white"><?= $textoMiniatura ?></h5>
            </div>
            <div class="card-body card-body-10">
              <div class="d-flex justify-content-between align-items-start mb-2">
                <h5 class="flex-grow-1 pe-2 mb-0"><?= $servicios[$i]["nombre_servicio"] ?></h5>
                <h5 class="precio-card mb-0"><?= $precioSugerido ?></h5>
              </div>
              <?php if (count($OpinionesServicio) > 0) { ?>
              <p class="mb-0"><small><?= $estrellasServicio ?>/10 - <?= count($OpinionesServicio) ?> <?= $lang["opiniones"] ?? "opiniones" ?></small></p>
              <?php } ?>
            </div>
          </div>
        </a>
      </div>
      <?php } ?>
    </div>
    <?php if (count($servicios_restantes) > 0) { ?>
    <div class="row mt-4"  >
      <div class="col-lg-12 text-center">
        <button id="btn-ver-mas-VerMasActividades-d" class="btn btn-white" type="button" onclick="toggleDiv('VerMasActividades-d', this)">
          <?= $labelVerMas ?>
        </button>
      </div>
    </div>
    <?php } ?>
    <div class="row toggle-section" id="VerMasActividades-d" style="max-height:0; overflow:hidden; background-color: rgb(245, 244, 245);">
      <?php
      for ($i = 0; $i < count($servicios_restantes); $i++) {
          $idServicio = $servicios_restantes[$i]["idServicio"];
          $descripcionCorta = $servicios_restantes[$i]["descripcion_corta"] ?? "";
          $salidas = getSalidasFechaLuegoIdServicio($fecha, $idServicio);
          if (count($salidas) > 0) {
              $idServicioSalidas = $salidas[0]['idServicioSalidas'];
              $tarifas = getTarifas($idServicioSalidas);
              if (!empty($tarifas)) {
                  $tarifa = @calculaTarifa($tarifas[0]['idServicioSalidasTarifas'] ?? null, 1);
                  $precioSugerido = (!empty($tarifa) && isset($tarifa[0]["valorSym"])) ? $tarifa[0]["valorSym"] : "ESGOTADO";
              } else {
                  $precioSugerido = "ESGOTADO";
              }
          } else {
              $precioSugerido = "ESGOTADO";
          }
          $OpinionesServicio = getOpinionesServicio($idServicio);
          $estrellasServicio = getEstrellasServicio($idServicio);
          $textoMiniaturaData = getTextoMiniatura($servicios_restantes[$i]["idTextoMiniaturas"] ?? 0);
          $textoMiniatura = (!empty($textoMiniaturaData)) ? $textoMiniaturaData[0]["texto"] : "";
          $fotos = getFotoMiniaturaServicio($idServicio);
      ?>
      <div class="col-lg-4 col-md-6 mb-4">
        <a href="servicio?id=<?= $idServicio ?>" class="destacados">
          <div class="d-destacado d-md-block d-none">
            <div class="row wow animated bounceInUp animated" data-wow-duration="2s" style="visibility: visible; animation-duration: 2s; animation-name: bounceInUp;">
              <div class="col-lg-12 texto-destacado">
                <p class="title-big mb-0" style="margin-bottom:-15px !important; margin-top: 20px; line-height: 24px;"><?= $servicios_restantes[$i]["nombre_servicio"] ?></p>
                <?php if (count($OpinionesServicio) > 0) { ?>
                <div class="d-flex flex-row">
                  <div class="">
                    <p class="title-number mb-0"><?= $estrellasServicio ?></p>
                  </div>
                  <div class="p-1 my-auto">
                    <p class="mb-0" style="margin-top:20px;">
                      <i class="fa fa-star"></i>
                      <i class="fa fa-star"></i>
                      <i class="fa fa-star"></i>
                      <i class="fa fa-star"></i>
                      <i class="fa fa-star"></i>
                    </p>
                    <p><small class="text--rating-total"><?= count($OpinionesServicio) ?>  <?= $lang["opiniones"] ?? "opiniones" ?></small></p>
                  </div>
                </div>
                <?php } ?>
                <p class="p-text" style="margin-top:-5px;"><?= $descripcionCorta ?></p>
              </div>
            </div>
          </div>
          <div class="card card-destacadas">
            <img src="admin/classes/imgServicio/<?= (!empty($fotos) && isset($fotos[0]['ruta'])) ? $fotos[0]['ruta'] : 'placeholder.jpg' ?>" class="img-fluid img-card-top img-destacada">
            <div class="destacado">
              <h5 class="text-uppercase text-white"><?= $textoMiniatura ?></h5>
            </div>
            <div class="card-body card-body-10">
              <div class="d-flex justify-content-between align-items-start mb-2">
                <h5 class="flex-grow-1 pe-2 mb-0"><?= $servicios_restantes[$i]["nombre_servicio"] ?></h5>
                <h5 class="precio-card mb-0"><?= $precioSugerido ?></h5>
              </div>
              <?php if (count($OpinionesServicio) > 0) { ?>
              <p class="mb-0"><small><?= $estrellasServicio ?>/10 - <?= count($OpinionesServicio) ?> <?= $lang["opiniones"] ?? "opiniones" ?></small></p>
              <?php } ?>
            </div>
          </div>
        </a>
      </div>
      <?php } ?>
      <!-- Botón Ver menos removido -->
    </div>
  </div>
</section>

<?php include "footer.php"; ?>
<div class="modal fade" id="modalbuscar" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <form class="form-buscar">
          <label class="sr-only" for="buscar"><?= $lang["donde_vamos"] ?? "┬┐D├│nde vamos?" ?></label>
          <div class="input-group">
            <input class="field form-control" id="buscar" name="buscar" type="text" placeholder="<?= $lang["donde_vamos"] ?? "┬┐D├│nde vamos?" ?>" value="">
            <span class="input-group-append">
              <button class="submit btn btn-primary" name="submit" type="submit"><?= $lang["buscar"] ?? "Buscar" ?><i class="fa fa-arrow-right"></i></button>
            </span>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php if (!isset($_COOKIE['politicaCookies'])){ ?>
<div class="cookies" id="cookies">
  <div class="container">
    <div class="row">
      <div class="col-8">
        <p class="text-white mb-0 text-justify cookies-sm" style="font-size:14px;">
          <?= $lang["utilizamos_cookies_propias"] ?? "Utilizamos cookies propias" ?>
          <a href="cookies.php"><?= $lang["politicas_de_cookies"] ?? "Pol├¡tica de cookies" ?></a>
        </p>
      </div>
      <div class="col-4">
        <a style="cursor: pointer;" id="cerrar-cookies"><p class="text-center"><button type="button" class="btn btn-info btn-circle"><i class="fa fa-check"></i></button></p></a>
      </div>
    </div>
  </div>
</div>
<?php } ?>
<!-- jQuery ya est├í cargado en navbar.php; remover duplicado para evitar conflictos con Bootstrap modal -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment.min.js"></script>
<script src="js/wow.min.js?v=<?php echo $version ?? '1.0'; ?>"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js?v=<?php echo $version ?? '1.0'; ?>"></script>
<script src="vendor/jquery-easing/jquery.easing.min.js?v=<?php echo $version ?? '1.0'; ?>"></script>
<script src="js/script.js?v=<?php echo $version ?? '1.0'; ?>"></script>
<script type="text/javascript">
// Reinitializar dropdowns de Bootstrap despu├®s de cargar DOM
$(document).ready(function(){
  // Asegurar que Bootstrap dropdowns funcionen correctamente
  $('[data-toggle="dropdown"]').dropdown();
});

// Animaciones vivas para expansi├│n/colapso
var _toggleAnimating = {};
function toggleDiv(divId) {
  var div = document.getElementById(divId);
  if (!div || _toggleAnimating[divId]) return;
  var verMasBtn = document.getElementById('btn-ver-mas-' + divId);
  var verMenosBtn = document.getElementById('btn-ver-menos-' + divId);
  var isOpen = div.classList.contains('open');

  _toggleAnimating[divId] = true;
  if (!isOpen) {
    // Abrir: calcula altura real y anima
    div.classList.add('open');
    // Fuerza reflow para que el navegador calcule scrollHeight correctamente
    var scrollHeight = div.scrollHeight;
    div.style.maxHeight = scrollHeight + 'px';
    div.style.opacity = '1';
    div.style.overflow = 'visible'; // Permitir que el hover overlay sea visible
    if (verMasBtn) verMasBtn.style.display = 'none';
    if (verMenosBtn) { verMenosBtn.style.display = ''; verMenosBtn.classList.add('show'); }
    setTimeout(function(){ _toggleAnimating[divId] = false; }, 380);
  } else {
    // Cerrar
    div.style.maxHeight = '0px';
    div.style.opacity = '0';
    div.style.overflow = 'hidden'; // Volver a hidden al cerrar
    div.classList.remove('open');
    if (verMasBtn) verMasBtn.style.display = '';
    if (verMenosBtn) { verMenosBtn.classList.remove('show'); verMenosBtn.style.display = 'none'; }
    setTimeout(function(){ _toggleAnimating[divId] = false; }, 380);
  }
}

$(document).ready(function(){
});
</script>
<script>
(function() {
  function cerrarCookies(ev) {
    if (ev) ev.preventDefault();
    document.cookie = 'politicaCookies=1;path=/;max-age=' + (60 * 60 * 24 * 365);
    var c1 = document.getElementById('cookies');
    if (c1) c1.style.display = 'none';
  }
  var btn1 = document.getElementById('cerrar-cookies');
  if (btn1) btn1.onclick = cerrarCookies;
  
  // Hover para mostrar info en categorías
  var enlaces = document.querySelectorAll('a.imagen');
  enlaces.forEach(function(enlace) {
    var info = enlace.querySelector('div.info');
    if (info) {
      enlace.addEventListener('mouseenter', function() {
        info.style.opacity = '1';
        info.style.visibility = 'visible';
        info.style.display = 'block';
      });
      enlace.addEventListener('mouseleave', function() {
        info.style.opacity = '0';
        info.style.visibility = 'hidden';
      });
    }
  });
})();
</script>

