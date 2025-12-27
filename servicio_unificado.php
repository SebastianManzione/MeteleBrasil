<?php
/**
 * SERVICIO UNIFICADO - Versión Responsiva
 * Un solo código para PC y Móvil
 * Sin duplicación de componentes
 */

include("includes/navbar.php");
require("admin/classes/categoria.php");
require("admin/classes/opiniones_categoria.php");
require("admin/classes/servicio_opiniones.php");
require("admin/classes/texto_miniaturas.php"); 
require("admin/classes/destinos.php"); 
require("admin/classes/paises.php"); 
require("admin/classes/accesibilidad.php"); 
require("admin/classes/texto_viajeros.php");

if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
    $idServicio=$_GET['id'];
    $servicio=getServicio($idServicio)[0];
    $idCategoria_servicio=$servicio['idCategoria_servicio'];
    $categoria_servicio=getCategoria($idCategoria_servicio);
    $OpinionesServicio=GetOpinionesServicio($idServicio);
    $CantOpinionesServicio=count($OpinionesServicio);
    $estrellasServicio=GetEstrellasServicio($idServicio);
    $textoMiniatura=getTextoMiniatura($servicio["idTextoMiniaturas"])[0]["texto"];
    $fotos=getFotosServicio($idServicio);
    $fotoPortada=getFotoPortadaServicio($idServicio);
    $duracion=getDuracionServicio($idServicio);
    $salidas=getSalidasServicio($idServicio);
    $idAccesibilidad=$salidas[0]['idAccesibilidad'];
    $accesibilidad=getAccesibilidad($idAccesibilidad);
    $eventArray=GetEventosArray($idServicio);
    $idDestino=$servicio["idDestino"];
    $destino=getDestino($idDestino);
    $pais=getPais($destino[0]["idPais"]);
    
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
                <p class="mx-4"><?=$accesibilidad[0]['texto'];?></p>
                
                <!-- CANCELACIONES -->
                <h2 class="py-4 text-primary"><?=$lang["cancelaciones_"]?></h2>
                <div id="divCancelaciones"></div>
              </div>
             </div>
          </div>

          <!-- COLUMNA LATERAL / PANEL DE RESERVA -->
          <div class="col-lg-4 col-12">
            
            <!-- PRECIO FLOTANTE (Desktop) -->
            <div class="div-precios text-right d-none d-md-block">
              <h4 style="font-size: 20px; color: #ff0000; font-weight: bold;"><?=$lang["antes_precio"]?></h4>
              <h4 style="font-size: 30px; text-decoration: line-through; color: #ff0000; font-weight: bold;" id="precioTotalSinDescuento"></h4>
              <h4 style="font-size: 20px; color: #008000; font-weight: bold;"><?=$lang["ahora_precio"]?></h4>
              <h2 class="text-primary"><span style="font-size:60px;" id="precioTotal0"></span></h2>
              <p><?=$lang["sin_sobreprecios"]?></p>
              <p class="text-success" id="textoCancelacionGratuita" style="display: none;"><b><?=$lang["cancelacion_gratuita_"]?></b></p>
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
                        <div class="form-group p-3">
                          <label><?=isset($lang["ingrese_un_cupon"]) ? $lang["ingrese_un_cupon"] : "Ingrese un cupón de descuento"?></label>
                          <input type="text" onkeyup="cupon(this.value)" id="txtCuponDescuento" class="form-control">
                        </div>
                        <div id="cuponOk" class="px-3 pb-3"></div>
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
          $("#cuponOk").html("<i class='fa fa-check-circle'></i> Cupón aceptado, Tu anfitrion es <strong>" + responser['anfitrion'] + "</strong> y brinda un descuento del <strong>" + responser['descuentoPorcentual'] + "%</strong>");
        } else {
          $("#cuponOk").html("<i class='fa fa-check-circle'></i> Cupón aceptado con un descuento del <strong>" + responser['descuentoPorcentual'] + "%</strong>");
        }
        console.log('Cupón válido aplicado');
      } catch(e) {
        console.error('Error parseando JSON:', e);
        $("#cuponOk").removeClass("text-success").addClass("text-danger");
        $("#cuponOk").text("Error procesando cupón.");
      }
    } else {
      console.log('Cupón inválido');
      codCupon = null;
      $("#cuponOk").removeClass("text-success").addClass("text-danger");
      $("#cuponOk").html("<i class='fa fa-times-circle'></i> Cupón no válido.");
    }
  }).fail(function(xhr, status, error) {
    console.error('Error AJAX:', status, error);
    $("#cuponOk").removeClass("text-success").addClass("text-danger");
    $("#cuponOk").text("Error al validar cupón.");
  });
}

// Cargar cupón desde sesión al inicio (después de que Bootstrap esté listo)
setTimeout(function(){
  <?php if(isset($_SESSION['codCupon']) && !empty($_SESSION['codCupon'])): ?>
    $('#txtCuponDescuento').val('<?=$_SESSION['codCupon']?>');
    cupon('<?=$_SESSION['codCupon']?>');
  <?php endif; ?>
}, 500);
</script>

<script type="text/javascript" src="js/traeHorarios_unificado.js?v=<?=time()?>" charset="UTF-8"></script>

<?php include("footer.php"); ?>
