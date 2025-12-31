<?php
/**
 * SERVICIO UNIFICADO - Versión Responsiva
 * Un solo código para PC y Móvil
 * Sin duplicación de componentes
 */

include("includes/navbar.php");
require_once("admin/classes/categoria.php");
require_once("admin/classes/opiniones_categoria.php");
require_once("admin/classes/servicio_opiniones.php");
require_once("admin/classes/texto_miniaturas.php"); 
require_once("admin/classes/destinos.php"); 
require_once("admin/classes/paises.php"); 
require_once("admin/classes/accesibilidad.php"); 
require_once("admin/classes/texto_viajeros.php");
require_once("admin/classes/tarifas.php");
require_once("admin/classes/cancelaciones.php");
require_once("admin/classes/fotos_servicio.php");
require_once("admin/classes/salidas.php");

if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
  $idServicio = (int)$_GET['id'];
  $serviciosData = getServicio($idServicio);
  if (empty($serviciosData) || !isset($serviciosData[0])) {
    header("HTTP/1.1 404 Not Found");
    $titulo404 = $lang["servicio_no_encontrado"] ?? "Servicio no encontrado";
    $ctaTexto = $lang["volver_a_servicios"] ?? "Volver a servicios";
    echo "<section class='py-5'><div class='container text-center'>"
       . "<h2 class='mb-3'>" . $titulo404 . "</h2>"
       . "<p class='text-muted mb-4'>" . ($lang["no_encontramos_este_servicio"] ?? "No encontramos este servicio o ya no está disponible.") . "</p>"
       . "<a href='servicios.php' class='btn btn-primary'>" . $ctaTexto . "</a>"
       . "</div></section>";
    include 'footer.php';
    exit;
  }
  $servicio = $serviciosData[0];
  $idCategoria_servicio = $servicio['idCategoria_servicio'] ?? null;
  $categoria_servicio = $idCategoria_servicio ? getCategoria($idCategoria_servicio) : [];
  $OpinionesServicio = GetOpinionesServicio($idServicio);
  $CantOpinionesServicio = count($OpinionesServicio);
  $estrellasServicio = GetEstrellasServicio($idServicio);
  $textoMiniaturaData = getTextoMiniatura($servicio["idTextoMiniaturas"] ?? 0);
  $textoMiniatura = (!empty($textoMiniaturaData) && isset($textoMiniaturaData[0]["texto"])) ? $textoMiniaturaData[0]["texto"] : "";
  $fotos = getFotosServicio($idServicio);
  $fotoPortada = getFotoPortadaServicio($idServicio);
  $duracion = getDuracionServicio($idServicio);
  $salidas = getSalidasServicio($idServicio);
  // Recolectar políticas de cancelación únicas desde las tarifas de las salidas del servicio
  $cancelacionesArr = [];
  if (!empty($salidas)) {
    foreach ($salidas as $s) {
      if (!isset($s['idServicioSalidas'])) continue;
      $tarifasSalida = getTarifas($s['idServicioSalidas']);
      if (!empty($tarifasSalida)) {
        foreach ($tarifasSalida as $t) {
          if (isset($t['idCancelaciones']) && !empty($t['idCancelaciones'])) {
            $c = getTipoCancelaciones($t['idCancelaciones']);
            if (!empty($c) && isset($c[0]['texto'])) {
              $textoC = $c[0]['texto'];
              if (!in_array($textoC, $cancelacionesArr)) $cancelacionesArr[] = $textoC;
            }
          }
        }
      }
    }
  }
  $idAccesibilidad = (!empty($salidas) && isset($salidas[0]['idAccesibilidad'])) ? $salidas[0]['idAccesibilidad'] : null;
  $accesibilidad = $idAccesibilidad ? getAccesibilidad($idAccesibilidad) : [];
  $eventArray = GetEventosArray($idServicio);
  $idDestino = $servicio["idDestino"] ?? null;
  $destino = $idDestino ? getDestino($idDestino) : [];
  $idPais = (!empty($destino) && isset($destino[0]["idPais"])) ? $destino[0]["idPais"] : null;
  $pais = $idPais ? getPais($idPais) : [];
  
  // Calcular precio mínimo del servicio
  $precioMinimo = 0;
  if (!empty($salidas)) {
    $preciosArray = [];
    foreach ($salidas as $salida) {
      if (isset($salida['idServicioSalidas'])) {
        $tarifasSalida = getTarifas($salida['idServicioSalidas']);
        if (!empty($tarifasSalida)) {
          foreach ($tarifasSalida as $tarifa) {
            if (isset($tarifa['valor']) && $tarifa['valor'] > 0) {
              $preciosArray[] = floatval($tarifa['valor']);
            }
          }
        }
      }
    }
    if (!empty($preciosArray)) {
      $precioMinimo = min($preciosArray);
    }
  }
    
  // Determinar si es administrador (idUsuario == 1)
  $isAdmin = isset($_SESSION['login']['idUsuario']) && $_SESSION['login']['idUsuario'] == 1;
}

include('servicioHead.php');
?>

<!-- CONTENEDOR PRINCIPAL UNIFICADO -->
<section class="py-5">
  <div class="container container_r clearfix">
      <div class="row">
         <!-- COLUMNA INFORMACIÓN PRINCIPAL -->
          <div class="col-lg-8 col-12">
             <div id="content">
      
              <!-- DESCRIPCIÓN -->
              <div class="descripcion" id="descripcion">
                <p><?=$servicio["descripcion_corta"];?></p>
                
                <!-- SLIDER DE FOTOS UNIFICADO -->
                <div id="carouselServicio" class="carousel slide" data-ride="carousel">
                  <ol class="carousel-indicators">
                    <?php 
                    for ($i=0; $i < count($fotos); $i++) { 
                        $active = ($i==0) ? 'class="active"' : '';
                        echo '<li data-target="#carouselServicio" data-slide-to="'.$i.'" '.$active.'></li>';
                    }
                    ?>
                  </ol>
                  <div class="carousel-inner">
                    <?php
                    for ($i=0; $i < count($fotos); $i++) { 
                        $classe = ($i==0) ? "carousel-item active" : "carousel-item";
                        echo '<div class="'.$classe.'">';
                        echo '<img class="d-block w-100 img-slider-servicio" src="admin/classes/imgServicio/'.$fotos[$i]['ruta'].'" alt="Foto '.$i.'">';
                        echo '</div>';
                    }
                    ?>
                  </div>
                  <a class="carousel-control-prev" href="#carouselServicio" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only"><?=$lang["anterior"]?></span>
                  </a>
                  <a class="carousel-control-next" href="#carouselServicio" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only"><?=$lang["Proxima"]?></span>
                  </a>
                </div>
                
                <!-- QUÉ SE VISITA -->
                <h2 class="py-4 text-primary"><?=$lang["que_se_visita"]?></h2>
                <?=$servicio["descripcion_servicio"];?>
                
                <!-- IMPORTANTE -->
                <h2 class="py-4 text-primary"><?=$lang["importante"]?></h2>
                <?=$servicio["observaciones"];?>
              </div>
              
              <!-- PRECIO -->
              <div id="precio">
                <h2 class="py-4 text-primary"><?=$lang["precio"]?></h2>
                <div class="container">
                  <div class="row" id="rowCirculosPrecios">
                    <div class="col-lg-3 col-12 my-auto">
                      <p class="popular text-center mb-0"><i class="fa fa-star"></i> <?=$lang["mas_popular"]?></p>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- DETALLES -->
              <div id="detalles">
                <h2 class="py-4 text-primary"><?=$lang["detalles_"]?></h2>
                
                <?php if($servicio["idCategoria_servicio"] != 5){ ?>
                <h5 class="semibold"><i class="fa fa-hourglass-half"></i> <?=$lang["duracion_"]?></h5>
                <p class="mx-4" id="txtDuracion"></p>
                <?php } ?>
                
                <h5 class="semibold"><i class="fa fa-language"></i> <?=$lang["idioma_"]?></h5>
                <p class="mx-4" id="pIdiomas"></p>
                
                <h5 class="semibold"><i class="fa fa-exclamation-triangle"></i><?=$lang["incluido_"]?></h5>
                <ul id="ulIncluidos"></ul>
                
                <div id="divNoIncluidosCuerpo">
                  <h5 class="semibold"><i class="fa fa-exclamation-triangle"></i> <?=$lang["no_incluido"]?></h5>
                  <ul id="ulNoIncluidos"></ul>
                </div>
                
                <h5 class="semibold"><i class="fas fa-passport"></i> <?=$lang["documentacion_para_el_viajero"]?></h5>
                <ul><?= $servicio['documentacionViajero']; ?></ul>
                
                <h5 class="semibold"><i class="fa fa-calendar-alt"></i><?=$lang["cuando_reservar"]?></h5>
                <p class="mx-4"><?=$lang["reserva_cuanto_antes_para"]?></p>
                <p class="mx-4" id="cuandoReservar"><?=$lang["se_permiten_reservas_hasta_las_23"]?></p>
                
                <h5 class="semibold"><i class="fa fa-file"></i><?=$lang["justificante"]?></h5>
                <p class="mx-4"><?=$lang["te_enviaremos_un_email"]?></p>
                
                <h5 class="semibold"><i class="fa fa-wheelchair"></i> <?=$lang["accesibilidad_"]?></h5>
                <p class="mx-4"><?= isset($accesibilidad[0]['texto']) ? $accesibilidad[0]['texto'] : ""; ?></p>
                
                <!-- CANCELACIONES -->
                <h2 class="py-4 text-primary"><?=$lang["cancelaciones_"]?></h2>
                <div id="divCancelaciones">
                  <?php 
                  // Debug temporal
                  if ($isAdmin && !empty($salidas)) {
                    echo "<!-- DEBUG: Total salidas: " . count($salidas) . " -->";
                    echo "<!-- DEBUG: Total cancelaciones encontradas: " . count($cancelacionesArr) . " -->";
                  }
                  
                  if (!empty($cancelacionesArr)) { ?>
                    <ul class="mb-0">
                      <?php foreach ($cancelacionesArr as $txt) { echo '<li>'. $txt .'</li>'; } ?>
                    </ul>
                  <?php } else { ?>
                    <p class="mx-4"><?=$lang["consultar_politica_cancelacion"] ?? "Consultar política de cancelación al momento de reservar.";?></p>
                  <?php } ?>
                </div>
              </div>
             </div>
          </div>

          <!-- COLUMNA LATERAL / PANEL DE RESERVA -->
          <div class="col-lg-4 col-12">
            
            <!-- PRECIO FLOTANTE (Desktop) -->
            <div class="div-precios text-right d-none d-md-block">
              <h6 class="mb-1" style="color: #ff0000;"><strong><?=$lang["antes_precio"]?></strong></h6>
              <h5 class="mb-2" style="text-decoration: line-through; color: #ff0000;" id="precioTotalSinDescuento"></h5>
              <h6 class="mb-1" style="color: #008000;"><strong><?=$lang["ahora_precio"]?></strong></h6>
              <h2 class="text-primary mb-2"><span id="precioTotal0"></span></h2>
              <small><?=$lang["sin_sobreprecios"]?></small>
              <p class="text-success mb-0" id="textoCancelacionGratuita" style="display: none;"><b><?=$lang["cancelacion_gratuita_"]?></b></p>
            </div>

            <!-- SIDEBAR FIJO / PANEL DE RESERVA -->
            <div id="sidebar">
              <div class="sidebar__inner">
                <div id="calendario-fijo">

                  <!-- ACORDEÓN DE CALENDARIO UNIFICADO -->
                  <div class="accordion mb-2" id="acordeonCalendario">
                    <div class="card card-accordion">
                      <div id="headingCalendario">
                        <h5 class="mb-0">
                          <a class="btn btn-accordion btn-calendar text-white" data-toggle="collapse" data-target="#collapseCalendario" aria-expanded="true">
                            <i class="fa fa-calendar"></i> <?=isset($lang["selecciona_fecha"]) ? $lang["selecciona_fecha"] : "Selecciona Fecha"?><i class="fa fa-sort-down float-right"></i>
                          </a>
                        </h5>
                      </div>
                      <div id="collapseCalendario" class="collapse show" aria-labelledby="headingCalendario" data-parent="#acordeonCalendario">
                        <div class="card-body p-0">
                          <div id="calendar" class="calendario-visitas" translate="no"></div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- ACORDEÓN DE HORARIOS UNIFICADO -->
                  <div class="accordion mb-2" id="Seleccionar_hora">
                    <div class="card card-accordion">
                      <div id="headingHorarios">
                        <h5 class="mb-0">
                          <a class="btn btn-accordion btn-calendar text-white" data-toggle="collapse" data-target="#divhora" aria-expanded="false">
                            <i class="fa fa-clock"></i> <?=isset($lang["hora"]) ? $lang["hora"] : "Hora y punto de embarque"?><i class="fa fa-sort-down float-right"></i>
                          </a>
                        </h5>
                      </div>
                      <div id="divhora" class="collapse" aria-labelledby="headingHorarios" data-parent="#Seleccionar_hora">
                        <div class="card-body p-2" id="divhoraBody"></div>
                      </div>
                    </div>
                  </div>
                  
                  <div id="divLugares"></div>

                  <!-- ACORDEÓN DE CUPÓN DESCUENTO UNIFICADO -->
                  <div class="accordion mb-2" id="Seleccionar_descuento">
                    <div class="card card-accordion">
                      <div id="headingDescuento">
                        <h5 class="mb-0">
                          <a class="btn btn-accordion btn-calendar text-white" data-toggle="collapse" data-target="#seleccionar_descuentoDiv" aria-expanded="false">
                            <i class="fa fa-tag"></i> <?=isset($lang["cupon_de_descuento"]) ? $lang["cupon_de_descuento"] : "Cupón de descuento"?><i class="fa fa-sort-down float-right"></i>
                          </a>
                        </h5>
                      </div>
                      <div id="seleccionar_descuentoDiv" class="collapse">
                        <div class="p-3" id="cuponContent">
                          <div class="input-group mb-2">
                            <input type="text" onkeyup="cupon(this.value)" id="txtCuponDescuento" class="form-control" placeholder="Ingresa tu código aquí...">
                            <div class="input-group-append">
                              <span class="input-group-text"><i class="fa fa-ticket"></i></span>
                            </div>
                          </div>
                          <div id="cuponOk" class="px-2"></div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- ACORDEÓN DE PERSONAS UNIFICADO -->
                  <div class="accordion mb-2" id="Seleccionar_personas_div">
                    <div class="card card-accordion">
                      <div id="headingPersonas">
                        <h5 class="mb-0">
                          <a class="btn btn-accordion btn-calendar text-white" data-toggle="collapse" data-target="#seleccionar_personas" aria-expanded="false">
                            <i class="fa fa-male"></i> <?=isset($lang["personas"]) ? $lang["personas"] : "Personas"?><i class="fa fa-sort-down float-right"></i>
                          </a>
                        </h5>
                      </div>
                      <div id="seleccionar_personas" class="collapse" aria-labelledby="headingPersonas" data-parent="#Seleccionar_personas_div" style="padding:15px;"></div>
                    </div>
                  </div>

                  <!-- ACORDEÓN DE SERVICIOS ADICIONALES UNIFICADO -->
                  <div class="accordion mb-2" id="Seleccionar_adicionales_b">
                    <div class="card card-accordion">
                      <div id="headingAdicionales">
                        <h5 class="mb-0">
                          <a class="btn btn-accordion btn-calendar text-white" data-toggle="collapse" data-target="#seleccionar_adicionales_a" aria-expanded="false">
                            <i class="fa fa-plus-circle"></i> <?=isset($lang["servicios_adicionales"]) ? $lang["servicios_adicionales"] : "Servicios adicionales"?><i class="fa fa-sort-down float-right"></i>
                          </a>
                        </h5>
                      </div>
                      <div id="seleccionar_adicionales_a" class="collapse" aria-labelledby="headingAdicionales" data-parent="#Seleccionar_adicionales_b" style="padding:15px;">
                        <div id="divAdicionalesNoIncluidos"></div>
                      </div>
                    </div>
                  </div>

                  <!-- BOTÓN RESERVAR -->
                  <button onclick="enviar()" class="btn btn-primary btn-block btn-lg mt-3">
                    <i class="fa fa-shopping-cart"></i> <?=isset($lang["reservar"]) ? $lang["reservar"] : "Reservar"?>
                  </button>

                </div>
              </div>
            </div>
          </div>
      </div>
  </div>
</section>

<!-- PRECIO FIJO INFERIOR (Móvil) - OCULTO -->
<!-- <section class="d-md-none scroll-to-top2 position-fixed" style="bottom:0; left:0; right:0; background:#fff; z-index:1000; box-shadow:0 -2px 10px rgba(0,0,0,0.1);">
  <div class="container-fluid py-2">
    <div class="row align-items-center">
      <div class="col-6">
        <small class="text-muted">Total</small>
        <h4 class="mb-0 text-primary" id="precio-nav"></h4>
      </div>
      <div class="col-6 text-right">
        <button onclick="enviar()" class="btn btn-primary">
          <i class="fa fa-shopping-cart"></i> Reservar
        </button>
      </div>
    </div>
  </div>
</section> -->

<!-- SCRIPTS UNIFICADOS -->
<script src="admin/js/underscore-min.js"></script>
<script src="admin/js/moment.min.js"></script>
<script src="admin/js/clndr.js"></script>
<script src="admin/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<script type="text/javascript">
var idServicioSeleccionado = <?=$idServicio?>;
var symMoneda = '<?=$_SESSION['moneda_sel_sym']?>';
var idiomaSistema = '<?= isset($_SESSION['idioma']) ? $_SESSION['idioma'] : 'ES' ?>';
var disponibilidad = 0;
var cantidadPersonas = 0;
var precioTotal = 0;
var reserva = [];
var reservaAdicionales = [];
var eventArray = <?php echo(!empty($eventArray) ? $eventArray : '[]'); ?>;
var isAdmin = <?php echo isset($isAdmin) && $isAdmin ? 'true' : 'false'; ?>;

// Etiquetas de idioma para JavaScript
var langLabels = {
  mas_popular: '<?=$lang["mas_popular"]?>',
  incluido_: '<?=$lang["incluido_"]?>',
  no_incluido: '<?=$lang["no_incluido"]?>'
};
</script>

<!-- SCRIPT CUPÓN -->
<script>
var codCupon = <?=isset($_SESSION['codCupon']) ? '"'.$_SESSION['codCupon'].'"' : 'null'?>;

function cupon(texto){
  if (!texto || texto.trim() === '') {
    $("#cuponOk").removeClass("text-success text-danger").text("");
    codCupon = null;
    return;
  }
  
  console.log('Validando cupón:', texto);
  
  $.post('admin/ctrl/ctrlCupon.php', {
    data: { 'cupon' : JSON.stringify(texto) }
  }, function(response) {
    console.log('Respuesta servidor:', response);
    console.log('Longitud respuesta:', response.length);
    
    // Extraer solo el JSON, ignorando warnings de PHP
    var jsonMatch = response.match(/\{.*\}/);
    if (jsonMatch) {
      try {
        var responser = JSON.parse(jsonMatch[0]);
        console.log('JSON parseado:', responser);
        
        codCupon = texto;
        $("#cuponOk").removeClass("text-danger").addClass("text-success");
        
        // Mostrar mensaje con o sin anfitrion
        if (responser['anfitrion'] && responser['anfitrion'] !== '') {
          $("#cuponOk").html("<div class='alert alert-success mb-0'><i class='fa fa-check-circle'></i> <strong>¡Cupón aceptado!</strong><br>Tu anfitrión es <strong>" + responser['anfitrion'] + "</strong><br>Descuento: <strong>" + responser['descuentoPorcentual'] + "%</strong></div>");
        } else {
          $("#cuponOk").html("<div class='alert alert-success mb-0'><i class='fa fa-check-circle'></i> <strong>¡Cupón aceptado!</strong><br>Descuento aplicado: <strong>" + responser['descuentoPorcentual'] + "%</strong></div>");
        }
        console.log('Cupón válido aplicado');
      } catch(e) {
        console.error('Error parseando JSON:', e);
        $("#cuponOk").removeClass("text-success").addClass("text-danger");
        $("#cuponOk").html("<div class='alert alert-danger mb-0'><i class='fa fa-exclamation-circle'></i> Error procesando cupón.</div>");
      }
    } else {
      console.log('Cupón inválido');
      codCupon = null;
      $("#cuponOk").removeClass("text-success").addClass("text-danger");
      $("#cuponOk").html("<div class='alert alert-danger mb-0'><i class='fa fa-times-circle'></i> Cupón no válido.</div>");
    }
  }).fail(function(xhr, status, error) {
    console.error('Error AJAX:', status, error);
    $("#cuponOk").removeClass("text-success").addClass("text-danger");
    $("#cuponOk").html("<div class='alert alert-danger mb-0'><i class='fa fa-exclamation-circle'></i> Error al validar cupón.</div>");
  });
}

// Cargar cupón desde sesión al inicio (después de que Bootstrap esté listo)
setTimeout(function(){
  <?php if(isset($_SESSION['codCupon']) && !empty($_SESSION['codCupon'])): ?>
    $('#txtCuponDescuento').val('<?=$_SESSION['codCupon']?>');
    cupon('<?=$_SESSION['codCupon']?>');
    // Expandir el acordeón de cupón
    $('#seleccionar_descuentoDiv').addClass('show');
    $('[data-target="#seleccionar_descuentoDiv"]').attr('aria-expanded', 'true');
    // Ocultar el input si hay cupón válido
    $('#txtCuponDescuento').closest('.input-group').hide();
  <?php endif; ?>
}, 500);
</script>

<script type="text/javascript" src="js/traeHorarios_unificado.js?v=<?=time()?>" charset="UTF-8"></script>

<!-- JavaScript para barra flotante móvil -->
<script>
$(document).ready(function() {
  // Botón reservar móvil - hacer scroll al calendario
  $('#btnReservarMovil').on('click', function() {
    var calendarioPos = $('#calendario-fijo').offset().top - 80; // 80px para el header
    $('html, body').animate({
      scrollTop: calendarioPos
    }, 800);
  });
  
  // Función para actualizar precio en barra móvil (se llamará desde traeHorarios_unificado.js)
  window.actualizarPrecioMovil = function(precio) {
    $('#precioMovilSticky').html(precio);
  };
});
</script>

<!--CARDS DE INTERES - TAMBIÉN TE PUEDE INTERESAR-->
<?php
// Preparar servicios relacionados
$serviciosRelacionados = [];
$mostrados = 0;
$maxMostrar = 3;

// 1. Primero intentar por categoría
if (!empty($idCategoria_servicio)) {
  $serviciosRelacionados = getServiciosidCategoria_servicio($idCategoria_servicio);
}

// 2. Si no hay suficientes, intentar por destino
if (count($serviciosRelacionados) < 4 && !empty($idDestino)) {
  $serviciosPorDestino = getServiciosidDestino($idDestino);
  // Combinar sin duplicados
  foreach ($serviciosPorDestino as $servDest) {
    $yaExiste = false;
    foreach ($serviciosRelacionados as $servRel) {
      if ($servRel['idServicio'] == $servDest['idServicio']) {
        $yaExiste = true;
        break;
      }
    }
    if (!$yaExiste) {
      $serviciosRelacionados[] = $servDest;
    }
  }
}

// 3. Si aún no hay suficientes, obtener servicios aleatorios
if (count($serviciosRelacionados) < 4) {
  require_once("admin/classes/conexion.php");
  $consultaRandom = "SELECT * FROM servicio WHERE habilitado=1 ORDER BY RAND() LIMIT 10";
  $comandoRandom = $pdo->prepare($consultaRandom);
  $comandoRandom->execute();
  $serviciosAleatorios = $comandoRandom->fetchAll(PDO::FETCH_ASSOC);
  
  foreach ($serviciosAleatorios as $servAle) {
    $yaExiste = false;
    foreach ($serviciosRelacionados as $servRel) {
      if ($servRel['idServicio'] == $servAle['idServicio']) {
        $yaExiste = true;
        break;
      }
    }
    if (!$yaExiste) {
      $serviciosRelacionados[] = $servAle;
    }
  }
}

// Mostrar sección solo si hay servicios disponibles
if (!empty($serviciosRelacionados) && is_array($serviciosRelacionados)):
?>
<section class="py-5" style="clear: both; position: relative; z-index: 1;">
  <div class="container mb-5">
    <h2 class="text-center mb-4"><?=$lang["tambien_te_puede_interesar"]?></h2>
    <div class="row">
      <?php 
      foreach ($serviciosRelacionados as $servicioRel) {
        if ($mostrados >= $maxMostrar) break;
        if (!isset($servicioRel["idServicio"])) continue;
        
        $idServicioRelacionado = $servicioRel["idServicio"];
        
        // Evitar mostrar el servicio actual
        if ($idServicioRelacionado == $idServicio) continue;
        
        // Obtener salidas desde hoy en adelante
        require_once("admin/classes/conexion.php");
        $fechaHoy = date("Y-m-d");
        $consultaSalidas = "SELECT * FROM servicio_salidas WHERE idServicio = :idServicio AND fecha >= :fechaHoy ORDER BY fecha ASC LIMIT 1";
        $cmdSalidas = $pdo->prepare($consultaSalidas);
        $cmdSalidas->execute(['idServicio' => $idServicioRelacionado, 'fechaHoy' => $fechaHoy]);
        $salidasFuturas = $cmdSalidas->fetchAll(PDO::FETCH_ASSOC);
        
        // Solo mostrar si tiene salidas futuras
        if (empty($salidasFuturas)) continue;
        
        // Calcular precio desde la primera salida disponible
        $precioSugerido = $lang["consultar"] ?? "Consultar";
        $idServicioSalidas = $salidasFuturas[0]['idServicioSalidas'];
        $tarifas = getTarifas($idServicioSalidas);
        if (!empty($tarifas)) {
          $tarifa = calculaTarifa($tarifas[0]['idServicioSalidasTarifas'], 1);
          if (!empty($tarifa) && isset($tarifa[0]["valorSym"])) {
            $precioSugerido = $tarifa[0]["valorSym"];
          }
        }
        
        $textoMiniaturaData = getTextoMiniatura($servicioRel["idTextoMiniaturas"] ?? 0);
        $textoMiniatura = (!empty($textoMiniaturaData) && isset($textoMiniaturaData[0]["texto"])) ? $textoMiniaturaData[0]["texto"] : "";
        
        $OpinionesServicio = GetOpinionesServicio($idServicioRelacionado);
        $estrellasServicio = getEstrellasServicio($idServicioRelacionado);
        $fotos = getFotoMiniaturaServicio($idServicioRelacionado);
        
        if (!empty($fotos) && isset($fotos[0]['ruta'])) {
          $mostrados++;
      ?>
      <div class="col-lg-4 col-md-6 mb-4">
        <div class="card card-destacadas shadow" style="height: auto; min-height: 450px;">
          <img src="admin/classes/imgServicio/<?=$fotos[0]['ruta'];?>" class="img-fluid img-card-top img-destacada" alt="<?=$servicioRel["nombre_servicio"] ?? '';?>">
          <?php if (!empty($textoMiniatura)): ?>
          <div class="destacado">
            <h5 class="text-uppercase text-white"><?=$textoMiniatura;?></h5>
          </div>
          <?php endif; ?>
          <div class="card-body">
            <h3><a href="servicio?id=<?=$idServicioRelacionado?>"><?=$servicioRel["nombre_servicio"] ?? 'Servicio';?></a></h3>
            <p class="text-primary mb-2"><strong><?=$estrellasServicio;?>/10</strong> <span class="text-gris"><?= count($OpinionesServicio);?> <?=$lang["opiniones"];?></span></p>
            <p class="mb-3"><?=$servicioRel["descripcion_corta"] ?? '';?></p>
            <h3 class="text-primary mb-0"><?=$precioSugerido;?></h3>
          </div>
          <a href="servicio?id=<?=$idServicioRelacionado?>" class="btn-reserva-destacada"><?=$lang["reservar"]?></a>
        </div>
      </div>
      <?php
        }
      }
      ?>
    </div>
  </div>
</section>
<?php endif; ?>
<!--FIN CARDS DE INTERES-->

<!-- Barra flotante móvil precio y reservar -->
<div class="barra-precio-movil d-md-none">
  <div class="container-fluid">
    <div class="row align-items-center">
      <div class="col-6">
        <p class="mb-0 text-muted" style="font-size: 14px;"><?=$lang["desde"] ?? "Desde";?></p>
        <h4 class="mb-0 text-primary font-weight-bold" id="precioMovilSticky">
          <?php if ($precioMinimo > 0): ?>
            <?=$_SESSION['moneda_sel_sym']?><?=number_format($precioMinimo, 2, ',', '.');?>
          <?php else: ?>
            <?=$lang["consultar"] ?? "Consultar";?>
          <?php endif; ?>
        </h4>
      </div>
      <div class="col-6 text-right">
        <button class="btn btn-primary btn-block" id="btnReservarMovil" style="font-size: 18px; padding: 12px;">
          <?=$lang["reservar"] ?? "Reservar";?>
        </button>
      </div>
    </div>
  </div>
</div>

<?php include("footer.php"); ?>
