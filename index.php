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

$ip = $_SERVER['REMOTE_ADDR'] ?? '186.22.17.78';
$geoFinal = getGeolocalizacionData();
$visitante = [$ip, $geoFinal['nombre_pais'], 'index.php'];
Visitante($visitante);

// Detectar entorno y usar credenciales apropiadas
$envFromVar = getenv('APP_ENV');
$isProd = $envFromVar ? ($envFromVar === 'prod') : true;

if ($isProd) {
    $mysqli = new mysqli("localhost", "u925692129_metelebrasil", "Cambiar2026", "u925692129_metelebrasil");
} else {
    $mysqli = new mysqli("localhost", "root", "", "metelebrasil");
}
if ($mysqli->connect_error) {
    die("Error de conexión: " . $mysqli->connect_error);
}

$latUsuario = floatval($geoFinal['latitud'] ?? 0);
$lonUsuario = floatval($geoFinal['longitud'] ?? 0);

// Seleccionar el campo de nombre según el idioma
$idioma = $_SESSION['idioma'] ?? 'ES';
$campoNombre = 'nombre_servicio'; // Por defecto es español
$campoDescripcion = 'descripcion_corta'; // Por defecto es español
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

$sql = "SELECT s.idServicio, s.$campoNombre as nombre_servicio, s.$campoDescripcion as descripcion_corta, s.destacado, s.idTextoMiniaturas, u.latitud, u.longitud FROM servicio s LEFT JOIN servicio_tarifas_ubicacion u ON s.idServicio = u.idServicioSalidasTarifas WHERE s.destacado = 1 AND s.habilitado = 1 LIMIT 12";
$result = $mysqli->query($sql);
$servicios_geo = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $servicios_geo[] = $row;
    }
}

// Ordenar servicios por distancia (más cercanos primero)
if ($latUsuario != 0 && $lonUsuario != 0) {
    usort($servicios_geo, function($a, $b) use ($latUsuario, $lonUsuario) {
        $distA = calcularDistancia($latUsuario, $lonUsuario, floatval($a['latitud'] ?? 0), floatval($a['longitud'] ?? 0));
        $distB = calcularDistancia($latUsuario, $lonUsuario, floatval($b['latitud'] ?? 0), floatval($b['longitud'] ?? 0));
        return $distA <=> $distB;
    });
}

$servicios = array_slice($servicios_geo, 0, 6);
$servicios_restantes = array_slice($servicios_geo, 6);

// Función para calcular distancia entre dos puntos (Haversine)
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
<script>
var latUsuario = <?= $geoFinal['latitud'] ?? 0 ?>;
var lngUsuario = <?= $geoFinal['longitud'] ?? 0 ?>;
function distancia(lat1, lng1, lat2, lng2) {
    var R = 6371;
    var dLat = (lat2 - lat1) * Math.PI / 180;
    var dLng = (lng2 - lng1) * Math.PI / 180;
    var a = Math.sin(dLat/2)**2 + Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * Math.sin(dLng/2)**2;
    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    return R * c;
}
var servicios = <?= json_encode($servicios_geo) ?>;
servicios.sort((a, b) => distancia(a.latitud, a.longitud, latUsuario, lngUsuario) - distancia(b.latitud, b.longitud, latUsuario, lngUsuario));
console.log(servicios);
</script>
<style>
/* Animación de las secciones Ver más/Ver menos */
.toggle-section { 
  transition: max-height 350ms ease, opacity 350ms ease; 
  max-height: 0;
  opacity: 0; 
  overflow: hidden;
  will-change: max-height, opacity; 
}
.toggle-section.open { 
  opacity: 1; 
}
/* Aparición suave del botón Ver menos */
.fade-toggle { 
  opacity: 0; 
  transform: translateY(6px); 
  transition: opacity 250ms ease, transform 250ms ease; 
}
.fade-toggle.show { 
  opacity: 1; 
  transform: translateY(0); 
}
@media (max-width: 576px) {
  #btn-ver-menos-VerMasActividades,
  #btn-ver-menos-VerMasActividades-d { 
    margin-top: 8px; 
  }
}
</style>
<style>
#capa1 { position: absolute; z-index: 1; }
#capa2 { position: absolute; z-index: 0; }
</style>
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
  </div>
  <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
  </a>
  <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
  </a>
</div>
<section class="div-absolute" id="capa2">
  <div class="container">
    <div class="row">
      <div class="col-lg-6 offset-lg-3 mb-5">
        <h1 class="text-white text-uppercase titulo">
          <span class="semibold"><?= $lang["crea_tu_viaje"] ?? "Crea tu viaje" ?></span><br>
          <?= $lang["excursiones_en_brasil"] ?? "Excursiones en Brasil" ?>
        </h1>
        <form class="form-buscar mb-5" action="categorias" method="get">
          <label class="sr-only" for="buscar"><?= $lang["que_hacemos"] ?? "¿Qué hacemos?" ?></label>
          <div class="input-group">
            <input class="field form-control form-control-search" id="buscar" name="buscar" type="text" placeholder="<?= $lang["que_hacemos"] ?? "¿Qué hacemos?" ?>" value="">
            <span class="input-group-append">
              <button class="submit btn btn-primary" name="submit" type="submit"><?= $lang["buscar"] ?? "Buscar" ?> <i class="fa fa-arrow-right"></i></button>
            </span>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>
<section class="py-5">
  <div class="container">
    <div class="row mb-4">
      <div class="col-lg-12">
        <h2 class="page-section-heading text-center text-uppercase text-secondary mb-0">
          <?= $lang["principales_actividades"] ?? "Principales Actividades" ?>
        </h2>
      </div>
    </div>
    <div class="row">
      <?php
      $categorias = getCategoriasLimit6();
      for ($i = 0; $i < count($categorias); $i++) {
          $idCategoria_servicio = $categorias[$i]['idCategoria_servicio'];
          $OpinionesCategoria = OpinionesCategoria($idCategoria_servicio);
          $CantOpinionesCategoria = count($OpinionesCategoria);
          $estrellas = getEstrellasCategoria($idCategoria_servicio);
      ?>
      <div class="col-lg-4 col-md-6 col-12 mb-4">
        <a href="categorias?idCategoria=<?= $categorias[$i]['idCategoria_servicio'] ?>" class="imagen">
          <div class="img-c" style="background-image: url(admin/img/categoria_servicio/<?= $categorias[$i]['img_categoria_servicio'] ?>)">
            <div class="info d-md-block d-none">
              <h3 class="headline text-uppercase semibold"><?= $categorias[$i]['nombre_categoria_servicio'] ?></h3>
              <div class="container">
                <div class="row">
                  <div class="col-md-6">
                    <div class="descripcion text-white">
                      <p class="mb-0"><strong style="font-size:30px;"><?= $categorias[$i]['nViajeros'] ?></strong></p>
                      <p class="mb-0 p"><?= $lang["viajeros_ya_lo_han_disfrutado"] ?? "Viajeros lo disfrutaron" ?></p>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="descrip-opinion text-white">
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
      ?>
    </div>
    <?php if (count(getCategoriasLimit6()) > 6) { ?>
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
            <div class="info d-md-block d-none">
              <h3 class="headline text-uppercase semibold"><?= $categorias[$i]['nombre_categoria_servicio'] ?></h3>
              <div class="container">
                <div class="row">
                  <div class="col-md-6">
                    <div class="descripcion text-white">
                      <p class="mb-0"><strong style="font-size:30px;"><?= $categorias[$i]['nViajeros'] ?></strong></p>
                      <p class="mb-0 p"><?= $lang["viajeros_ya_lo_han_disfrutado"] ?? "Viajeros lo disfrutaron" ?></p>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="descrip-opinion text-white">
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
      <div class="col-lg-12 text-center mt-4">
        <button id="btn-ver-menos-VerMasActividades" class="btn btn-white btn-block fade-toggle" type="button" style="display:none;" onclick="toggleDiv('VerMasActividades', null)">
          <?= $labelVerMenos ?>
        </button>
      </div>
    </div>
  </div>
</section>
<section class="py-5">
  <div class="container">
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
          $OpinionesServicio = GetOpinionesServicio($idServicio);
          $estrellasServicio = GetEstrellasServicio($idServicio);
          $textoMiniaturaData = getTextoMiniatura($servicios[$i]["idTextoMiniaturas"] ?? 0);
          $textoMiniatura = (!empty($textoMiniaturaData)) ? $textoMiniaturaData[0]["texto"] : "";
          $fotos = getFotoMiniaturaServicio($idServicio);
      ?>
      <div class="col-lg-4 col-md-6 mb-4">
        <a href="servicio?id=<?= $idServicio ?>" class="destacados">
          <div class="d-destacado d-md-block d-none">
            <div class="row">
              <div class="col-lg-12 texto-destacado">
                <p class="title-big mb-0"><?= $servicios[$i]["nombre_servicio"] ?></p>
                <?php if (count($OpinionesServicio) > 0) { ?>
                <p class="mb-2"><?= $estrellasServicio ?>/10 - <?= count($OpinionesServicio) ?> <?= $lang["opiniones"] ?? "Opiniones" ?></p>
                <?php } ?>
                <p class="mb-0"><?= $descripcionCorta ?></p>
              </div>
            </div>
          </div>
          <div class="card card-destacadas">
            <img src="admin/classes/imgServicio/<?= (!empty($fotos) && isset($fotos[0]['ruta'])) ? $fotos[0]['ruta'] : 'placeholder.jpg' ?>" class="img-fluid img-card-top img-destacada">
            <div class="destacado">
              <h5 class="text-uppercase text-white"><?= $textoMiniatura ?></h5>
            </div>
            <div class="card-body card-body-10">
              <div class="d-flex justify-content-between align-items-start">
                <h5 class="flex-grow-1 pe-2 mb-0"><?= $servicios[$i]["nombre_servicio"] ?></h5>
                <h5 class="precio-card mb-0"><?= $precioSugerido ?></h5>
              </div>
              <?php if (count($OpinionesServicio) > 0) { ?>
              <p class="mb-2"><?= $estrellasServicio ?>/10 - <?= count($OpinionesServicio) ?> <?= $lang["opiniones"] ?? "Opiniones" ?></p>
              <?php } ?>
            </div>
          </div>
        </a>
      </div>
      <?php } ?>
    </div>
    <?php if (count($servicios_restantes) > 0) { ?>
    <div class="row mt-4">
      <div class="col-lg-12 text-center">
        <button id="btn-ver-mas-VerMasActividades-d" class="btn btn-white" type="button" onclick="toggleDiv('VerMasActividades-d', this)">
          <?= $labelVerMas ?>
        </button>
      </div>
    </div>
    <?php } ?>
    <div class="row toggle-section" id="VerMasActividades-d" style="max-height:0; overflow:hidden;">
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
          $OpinionesServicio = GetOpinionesServicio($idServicio);
          $estrellasServicio = GetEstrellasServicio($idServicio);
          $textoMiniaturaData = getTextoMiniatura($servicios_restantes[$i]["idTextoMiniaturas"] ?? 0);
          $textoMiniatura = (!empty($textoMiniaturaData)) ? $textoMiniaturaData[0]["texto"] : "";
          $fotos = getFotoMiniaturaServicio($idServicio);
      ?>
      <div class="col-lg-4 col-md-6 mb-4">
        <a href="servicio?id=<?= $idServicio ?>" class="destacados">
          <div class="d-destacado d-md-block d-none">
            <div class="row">
              <div class="col-lg-12 texto-destacado">
                <p class="title-big mb-0"><?= $servicios_restantes[$i]["nombre_servicio"] ?></p>
                <?php if (count($OpinionesServicio) > 0) { ?>
                <p class="mb-2"><?= $estrellasServicio ?>/10 - <?= count($OpinionesServicio) ?> <?= $lang["opiniones"] ?? "Opiniones" ?></p>
                <?php } ?>
                <p class="mb-0"><?= $descripcionCorta ?></p>
              </div>
            </div>
          </div>
          <div class="card card-destacadas">
            <img src="admin/classes/imgServicio/<?= (!empty($fotos) && isset($fotos[0]['ruta'])) ? $fotos[0]['ruta'] : 'placeholder.jpg' ?>" class="img-fluid img-card-top img-destacada">
            <div class="destacado">
              <h5 class="text-uppercase text-white"><?= $textoMiniatura ?></h5>
            </div>
            <div class="card-body card-body-10">
              <div class="d-flex justify-content-between align-items-start">
                <h5 class="flex-grow-1 pe-2 mb-0"><?= $servicios_restantes[$i]["nombre_servicio"] ?></h5>
                <h5 class="precio-card mb-0"><?= $precioSugerido ?></h5>
              </div>
              <?php if (count($OpinionesServicio) > 0) { ?>
              <p class="mb-2"><?= $estrellasServicio ?>/10 - <?= count($OpinionesServicio) ?> <?= $lang["opiniones"] ?? "Opiniones" ?></p>
              <?php } ?>
            </div>
          </div>
        </a>
      </div>
      <?php } ?>
      <div class="col-lg-12 text-center mt-4">
        <button id="btn-ver-menos-VerMasActividades-d" class="btn btn-white btn-block fade-toggle" type="button" style="display:none;" onclick="toggleDiv('VerMasActividades-d', null)">
          <?= $labelVerMenos ?>
        </button>
      </div>
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
          <label class="sr-only" for="buscar"><?= $lang["donde_vamos"] ?? "¿Dónde vamos?" ?></label>
          <div class="input-group">
            <input class="field form-control" id="buscar" name="buscar" type="text" placeholder="<?= $lang["donde_vamos"] ?? "¿Dónde vamos?" ?>" value="">
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
          <a href="cookies.php"><?= $lang["politicas_de_cookies"] ?? "Política de cookies" ?></a>
        </p>
      </div>
      <div class="col-4">
        <a style="cursor: pointer;" id="cerrar-cookies"><p class="text-center"><button type="button" class="btn btn-info btn-circle"><i class="fa fa-check"></i></button></p></a>
      </div>
    </div>
  </div>
</div>
<?php } ?>
<!-- jQuery ya está cargado en navbar.php; remover duplicado para evitar conflictos con Bootstrap modal -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment.min.js"></script>
<script src="js/wow.min.js?v=<?php echo $version ?? '1.0'; ?>"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js?v=<?php echo $version ?? '1.0'; ?>"></script>
<script src="vendor/jquery-easing/jquery.easing.min.js?v=<?php echo $version ?? '1.0'; ?>"></script>
<script src="js/script.js?v=<?php echo $version ?? '1.0'; ?>"></script>
<script type="text/javascript">
// Reinitializar dropdowns de Bootstrap después de cargar DOM
$(document).ready(function(){
  // Asegurar que Bootstrap dropdowns funcionen correctamente
  $('[data-toggle="dropdown"]').dropdown();
});

// Animaciones vivas para expansión/colapso
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
})();
</script>

