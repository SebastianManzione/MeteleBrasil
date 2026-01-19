<?php
/**
 * SERVICIO DE TRANSPORTE - Viajes (Bus, Avión, Tren, Barco)
 * Misma estructura visual que servicio.php
 */

include("includes/navbar.php");
require_once("admin/classes/transporte.php");

$viaje = null;
$ruta = null;
$paradas = [];
$tipoTarifa = 'clases'; // Por defecto
$viajeNoEncontrado = false;

if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
    $idViaje = (int)$_GET['id'];
    $viaje = getViaje($idViaje);

    if (!empty($viaje)) {
        $idRuta = $viaje['idRuta'];
        $ruta = getRuta($idRuta);
        $paradas = getParadasRuta($idRuta);
        
        // Detectar tipo de tarifa (clases o segmentado)
        $tipoTarifa = isset($viaje['tipo_tarifa']) ? $viaje['tipo_tarifa'] : 'clases';
    } else {
        $viajeNoEncontrado = true;
    }
} else {
    $viajeNoEncontrado = true;
}

// Determinar si es administrador
$isAdmin = isset($_SESSION['login']['idUsuario']) && $_SESSION['login']['idUsuario'] == 1;

?>

<?php if ($viajeNoEncontrado): ?>
  <section class="py-5">
    <div class="container">
      <div class="alert alert-danger" role="alert">
        <h4 class="alert-heading">❌ <?= $lang["servicio_no_encontrado"] ?? "Viaje no encontrado" ?></h4>
        <p><?= $lang["intenta_con_otro"] ?? "El viaje que buscas no existe. Intenta con otro." ?></p>
        <hr>
        <p class="mb-0"><a href="javascript:window.history.back();" class="btn btn-primary">← Volver atrás</a></p>
      </div>
    </div>
  </section>
  <?php include('footer.php'); exit; ?>
<?php endif; ?>

<!-- ACORDEONES MÓVILES -->
<section class="d-md-none bg-white">
  <div class="container py-3">
    <div class="row">
      <div class="col-12">
        <h4 class="text-primary mb-3"><?= htmlspecialchars($viaje['ruta_nombre']) ?></h4>
        <div class="row text-center mb-3">
          <div class="col-4">
            <p class="mb-0"><i class="fa fa-bus text-primary"></i></p>
            <small class="bold"><?= htmlspecialchars($viaje['tipo_transporte_nombre'] ?? 'Transporte') ?></small>
          </div>
          <div class="col-4">
            <p class="mb-0"><i class="fa fa-calendar text-primary"></i></p>
            <small class="bold"><?= !empty($viaje['fecha']) ? date('d/m/Y', strtotime($viaje['fecha'])) : 'N/A' ?></small>
          </div>
          <div class="col-4">
            <p class="mb-0"><i class="fa fa-clock text-primary"></i></p>
            <small class="bold"><?= !empty($viaje['hora_salida']) ? substr($viaje['hora_salida'], 0, 5) : 'N/A' ?></small>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="container">
    <div class="row">
      <div class="col-12 px-0">
        
        <!-- ACORDEÓN DESCRIPCIÓN -->
        <div class="accordion" id="accordionMovil">
          <div class="card card-accordion">
            <div class="card-header bg-white" id="headingDescripcion">
              <h5 class="mb-0">
                <button class="btn btn-accordion btn-block text-left" data-toggle="collapse" data-target="#collapseDescripcion" aria-expanded="true">
                  <?= $lang["descripcion"] ?? "Descripción" ?> <i class="fa fa-chevron-down float-right"></i>
                </button>
              </h5>
            </div>
            <div id="collapseDescripcion" class="collapse show">
              <div class="card-body">
                <h6 class="semibold"><i class="fa fa-ruler"></i> <?= $lang["distancia"] ?? "Distancia" ?></h6>
                <p class="mx-3"><?= $ruta['distancia_km'] ?? '---' ?> km</p>

                <h6 class="semibold"><i class="fa fa-hourglass-half"></i> <?= $lang["duracion"] ?? "Duración" ?></h6>
                <p class="mx-3"><?= $ruta['duracion_estimada'] ?? '---' ?></p>

                <hr>

                <p><?= htmlspecialchars($ruta['descripcion_es'] ?? $ruta['nombre']) ?></p>
              </div>
            </div>
          </div>

          <!-- ACORDEÓN PARADAS -->
          <div class="card card-accordion">
            <div class="card-header bg-white" id="headingParadas">
              <h5 class="mb-0">
                <button class="btn btn-accordion btn-block text-left collapsed" data-toggle="collapse" data-target="#collapseParadas" aria-expanded="false">
                  <i class="fa fa-map-marker-alt"></i> <?= $lang["paradas"] ?? "Paradas" ?> (<?= count($paradas) ?>) <i class="fa fa-chevron-down float-right"></i>
                </button>
              </h5>
            </div>
            <div id="collapseParadas" class="collapse">
              <div class="card-body">
                <?php if (!empty($paradas)): ?>
                  <div class="list-group">
                    <?php foreach ($paradas as $p): ?>
                      <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-start">
                          <div>
                            <h6 class="mb-1">
                              <span class="badge badge-secondary"><?= $p['orden'] ?></span>
                              <?= htmlspecialchars($p['terminal_nombre']) ?>
                            </h6>
                            <small class="text-muted">📍 <?= htmlspecialchars($p['ciudad']) ?></small>
                          </div>
                          <div>
                            <?php if ($p['es_origen']): ?><span class="badge badge-success">O</span><?php endif; ?>
                            <?php if ($p['es_destino']): ?><span class="badge badge-danger">D</span><?php endif; ?>
                          </div>
                        </div>
                      </div>
                    <?php endforeach; ?>
                  </div>
                <?php endif; ?>
              </div>
            </div>
          </div>

          <!-- ACORDEÓN MAPA -->
          <div class="card card-accordion">
            <div class="card-header bg-white" id="headingMapa">
              <h5 class="mb-0">
                <button class="btn btn-accordion btn-block text-left collapsed" data-toggle="collapse" data-target="#collapseMapa" aria-expanded="false">
                  <i class="fa fa-map"></i> <?= $lang["mapa"] ?? "Mapa de recorrido" ?> <i class="fa fa-chevron-down float-right"></i>
                </button>
              </h5>
            </div>
            <div id="collapseMapa" class="collapse">
              <div class="card-body p-0">
                <div id="mapRecorridoMovil" style="width: 100%; height: 300px;"></div>
              </div>
            </div>
          </div>

          <!-- ACORDEÓN ORIGEN (Solo para modo segmentado) -->
          <?php if ($tipoTarifa === 'segmentado'): ?>
          <div class="card card-accordion">
            <div class="card-header bg-white" id="headingOrigen">
              <h5 class="mb-0">
                <button class="btn btn-accordion btn-block text-left collapsed" data-toggle="collapse" data-target="#collapseOrigen" aria-expanded="false">
                  <i class="fa fa-map-marker-alt text-success"></i> <?= $lang["origen"] ?? "Origen" ?> <i class="fa fa-chevron-down float-right"></i>
                </button>
              </h5>
            </div>
            <div id="collapseOrigen" class="collapse">
              <div class="card-body">
                <select id="selectOrigenMovil" class="form-control">
                  <option value="">-- Seleccione origen --</option>
                  <?php foreach ($paradas as $p): ?>
                    <?php if ($p['es_origen']): ?>
                      <option value="<?= $p['idRutaParada'] ?>"><?= htmlspecialchars($p['terminal_nombre']) ?> (<?= htmlspecialchars($p['ciudad']) ?>)</option>
                    <?php endif; ?>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>

          <!-- ACORDEÓN DESTINO (Solo para modo segmentado) -->
          <div class="card card-accordion">
            <div class="card-header bg-white" id="headingDestino">
              <h5 class="mb-0">
                <button class="btn btn-accordion btn-block text-left collapsed" data-toggle="collapse" data-target="#collapseDestino" aria-expanded="false">
                  <i class="fa fa-map-marker-alt text-danger"></i> <?= $lang["destino"] ?? "Destino" ?> <i class="fa fa-chevron-down float-right"></i>
                </button>
              </h5>
            </div>
            <div id="collapseDestino" class="collapse">
              <div class="card-body">
                <select id="selectDestinoMovil" class="form-control">
                  <option value="">-- Seleccione destino --</option>
                  <?php foreach ($paradas as $p): ?>
                    <?php if ($p['es_destino']): ?>
                      <option value="<?= $p['idRutaParada'] ?>"><?= htmlspecialchars($p['terminal_nombre']) ?> (<?= htmlspecialchars($p['ciudad']) ?>)</option>
                    <?php endif; ?>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>
          <?php endif; ?>

          <!-- ACORDEÓN CLASE (Solo para modo clases) -->
          <?php if ($tipoTarifa === 'clases'): ?>
          <div class="card card-accordion">
            <div class="card-header bg-white" id="headingClase">
              <h5 class="mb-0">
                <button class="btn btn-accordion btn-block text-left collapsed" data-toggle="collapse" data-target="#collapseClase" aria-expanded="false">
                  <i class="fa fa-chair"></i> <?= $lang["tipo_butaca"] ?? "Tipo de butaca" ?> <i class="fa fa-chevron-down float-right"></i>
                </button>
              </h5>
            </div>
            <div id="collapseClase" class="collapse">
              <div class="card-body">
                <select id="selectClaseMovil" class="form-control">
                  <option value="">-- Seleccione tipo de butaca --</option>
                  <option value="1">Economy</option>
                  <option value="2">Business</option>
                  <option value="3">First</option>
                </select>
              </div>
            </div>
          </div>
          <?php endif; ?>

          <!-- ACORDEÓN PASAJEROS -->
          <div class="card card-accordion">
            <div class="card-header bg-white" id="headingPasajeros">
              <h5 class="mb-0">
                <button class="btn btn-accordion btn-block text-left collapsed" data-toggle="collapse" data-target="#collapsePasajeros" aria-expanded="false">
                  <i class="fa fa-users"></i> <?= $lang["pasajeros"] ?? "Pasajeros" ?> <i class="fa fa-chevron-down float-right"></i>
                </button>
              </h5>
            </div>
            <div id="collapsePasajeros" class="collapse">
              <div class="card-body">
                <div id="selectoresPasajerosMovil"></div>
              </div>
            </div>
          </div>

          <!-- ACORDEÓN DISPONIBILIDAD -->
          <div class="card card-accordion">
            <div class="card-header bg-white" id="headingDisponibilidad">
              <h5 class="mb-0">
                <button class="btn btn-accordion btn-block text-left collapsed" data-toggle="collapse" data-target="#collapseDisponibilidad" aria-expanded="false">
                  <i class="fa fa-chair"></i> <?= $lang["disponibilidad"] ?? "Disponibilidad" ?> <i class="fa fa-chevron-down float-right"></i>
                </button>
              </h5>
            </div>
            <div id="collapseDisponibilidad" class="collapse">
              <div class="card-body">
                <div class="alert alert-info mb-0">
                  <strong><?= $viaje['asientos_disponibles'] ?></strong> asientos disponibles de <strong><?= $viaje['asientos_totales'] ?></strong>
                </div>
              </div>
            </div>
          </div>

        </div>

        <!-- PRECIO Y BOTÓN RESERVAR MÓVIL -->
        <div class="p-3">
          <div class="alert alert-info text-center mb-3" id="precioTotalMovil" style="display: none;">
            <h4 class="mb-0">Total: <strong id="precioValorMovil">$0</strong></h4>
          </div>
          <button id="btnReservarMovil" class="btn btn-primary btn-block btn-lg" disabled>
            <i class="fa fa-shopping-cart"></i> <?= $lang["reservar"] ?? "Reservar" ?>
          </button>
        </div>

      </div>
    </div>
  </div>
</section>

<!-- CONTENEDOR PRINCIPAL UNIFICADO (Desktop) -->
<section class="py-5 d-none d-md-block">
  <div class="container container_r clearfix">
      <div class="row">
         <!-- COLUMNA INFORMACIÓN PRINCIPAL -->
          <div class="col-lg-8 col-12">
             <div id="content">
      
              <!-- TÍTULO Y TIPO -->
              <h2 class="mb-1 text-primary"><?= htmlspecialchars($viaje['ruta_nombre']) ?></h2>
              <p class="text-muted mb-4">
                <i class="fas fa-<?= $viaje['tipo_transporte_nombre'] == 'Bus' ? 'bus' : ($viaje['tipo_transporte_nombre'] == 'Avión' ? 'plane' : 'train') ?>"></i>
                <?= htmlspecialchars($viaje['tipo_transporte_nombre'] ?? 'Transporte') ?> · 
                <?= !empty($viaje['fecha']) ? date('d/m/Y', strtotime($viaje['fecha'])) : 'N/A' ?> a las 
                <?= !empty($viaje['hora_salida']) ? substr($viaje['hora_salida'], 0, 5) : 'N/A' ?>
              </p>
              
              <!-- DESCRIPCIÓN -->
              <div class="descripcion" id="descripcion">
                <p><?= htmlspecialchars($ruta['descripcion_es'] ?? $ruta['nombre']) ?></p>
              </div>

              <!-- INFO RÁPIDA -->
              <div class="row mb-4">
                <div class="col-md-4">
                  <div class="card">
                    <div class="card-body text-center">
                      <h5 class="card-title"><i class="fa fa-ruler text-primary"></i></h5>
                      <p class="mb-0"><?= $ruta['distancia_km'] ?? '---' ?> km</p>
                      <small class="text-muted"><?= $lang["distancia"] ?? "Distancia" ?></small>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="card">
                    <div class="card-body text-center">
                      <h5 class="card-title"><i class="fa fa-hourglass-half text-primary"></i></h5>
                      <p class="mb-0"><?= $ruta['duracion_estimada'] ?? '---' ?></p>
                      <small class="text-muted"><?= $lang["duracion"] ?? "Duración" ?></small>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="card">
                    <div class="card-body text-center">
                      <h5 class="card-title"><i class="fa fa-chair text-primary"></i></h5>
                      <p class="mb-0"><?= $viaje['asientos_disponibles'] ?>/<?= $viaje['asientos_totales'] ?></p>
                      <small class="text-muted"><?= $lang["disponibilidad"] ?? "Disponibilidad" ?></small>
                    </div>
                  </div>
                </div>
              </div>

              <!-- PARADAS -->
              <div id="paradas">
                <h2 class="py-4 text-primary"><i class="fa fa-map-marker-alt"></i> <?= $lang["paradas"] ?? "Paradas" ?> (<?= count($paradas) ?>)</h2>
                
                <?php if (!empty($paradas)): ?>
                  <div class="list-group mb-4">
                    <?php foreach ($paradas as $p): ?>
                      <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-start">
                          <div>
                            <h6 class="mb-1">
                              <span class="badge badge-secondary"><?= $p['orden'] ?></span>
                              <?= htmlspecialchars($p['terminal_nombre']) ?>
                            </h6>
                            <small class="text-muted">
                              📍 <?= htmlspecialchars($p['ciudad']) ?>, <?= htmlspecialchars($p['estado'] ?? '') ?>
                              <?php if (!empty($p['tiempo_desde_inicio'])): ?>
                                <br><em><?= $lang["tiempo"] ?? "Tiempo" ?>: <?= $p['tiempo_desde_inicio'] ?></em>
                              <?php endif; ?>
                            </small>
                          </div>
                          <div>
                            <?php if ($p['es_origen']): ?><span class="badge badge-success"><?= $lang["origen"] ?? "ORIGEN" ?></span><?php endif; ?>
                            <?php if ($p['es_destino']): ?><span class="badge badge-danger"><?= $lang["destino"] ?? "DESTINO" ?></span><?php endif; ?>
                          </div>
                        </div>
                      </div>
                    <?php endforeach; ?>
                  </div>
                <?php endif; ?>
              </div>

              <!-- MAPA -->
              <div id="mapa">
                <h2 class="py-4 text-primary"><i class="fa fa-map"></i> <?= $lang["recorrido"] ?? "Recorrido" ?></h2>
                <div id="mapRecorridoDesktop" style="width: 100%; height: 450px; border: 2px solid #17a2b8; border-radius: 8px; margin-bottom: 30px;"></div>
              </div>

             </div>
          </div>

          <!-- COLUMNA LATERAL / PANEL DE RESERVA -->
          <div class="col-lg-4 col-12">
            
            <!-- PRECIO FLOTANTE (Desktop) -->
            <div class="div-precios text-right d-none d-md-block">
              <h6 class="mb-1" style="color: #029ce2;"><strong><?= $lang["total"] ?? "Total" ?></strong></h6>
              <h2 class="text-primary mb-2"><span id="precioTotal">--</span></h2>
              <small><?= $lang["sin_sobreprecios"] ?? "Sin sobreprecios" ?></small>
            </div>

            <!-- SIDEBAR FIJO / PANEL DE RESERVA -->
            <div id="sidebar">
              <div class="sidebar__inner">
                <div id="calendario-fijo">

                  <!-- ACORDEÓN DE CLASE (solo si tipoTarifa='clases') -->
                  <?php if ($tipoTarifa === 'clases'): 
                    $clasesDisponibles = getViajeClasesServicio($idViaje);
                  ?>
                  <div class="accordion mb-2" id="acordeonClaseDesktop">
                    <div class="card card-accordion">
                      <div id="headingClaseDesktop">
                        <h5 class="mb-0">
                          <a class="btn btn-accordion btn-calendar text-white" data-toggle="collapse" data-target="#collapseClaseDesktop" aria-expanded="true">
                            <i class="fa fa-star"></i> <?= $lang["tipo_butaca"] ?? "Tipo de butaca" ?><i class="fa fa-sort-down float-right"></i>
                          </a>
                        </h5>
                      </div>
                      <div id="collapseClaseDesktop" class="collapse show" aria-labelledby="headingClaseDesktop" data-parent="#acordeonClaseDesktop">
                        <div class="card-body p-2">
                          <select id="selectClaseDesktop" class="form-control" onchange="cargarTarifas()">
                            <option value="">-- Seleccione tipo de butaca --</option>
                            <?php if (!empty($clasesDisponibles)): ?>
                              <?php foreach ($clasesDisponibles as $c): ?>
                                <option value="<?= $c['idViajeClase'] ?>"><?= htmlspecialchars($c['nombre_clase'] ?? 'Clase') ?></option>
                              <?php endforeach; ?>
                            <?php endif; ?>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                  <?php endif; ?>

                  <!-- ACORDEÓN DE ORIGEN (solo si tipoTarifa='segmentado') -->
                  <?php if ($tipoTarifa === 'segmentado'): ?>
                  <div class="accordion mb-2" id="acordeonOrigen">
                    <div class="card card-accordion">
                      <div id="headingOrigenDesktop">
                        <h5 class="mb-0">
                          <a class="btn btn-accordion btn-calendar text-white" data-toggle="collapse" data-target="#collapseOrigenDesktop" aria-expanded="true">
                            <i class="fa fa-map-marker-alt"></i> <?= $lang["origen"] ?? "Origen" ?><i class="fa fa-sort-down float-right"></i>
                          </a>
                        </h5>
                      </div>
                      <div id="collapseOrigenDesktop" class="collapse show" aria-labelledby="headingOrigenDesktop" data-parent="#acordeonOrigen">
                        <div class="card-body p-2">
                          <select id="selectOrigen" class="form-control" onchange="cargarTarifas()">
                            <option value="">-- Seleccione origen --</option>
                            <?php foreach ($paradas as $p): ?>
                              <?php if ($p['es_origen']): ?>
                                <option value="<?= $p['idRutaParada'] ?>"><?= htmlspecialchars($p['terminal_nombre']) ?></option>
                              <?php endif; ?>
                            <?php endforeach; ?>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- ACORDEÓN DE DESTINO -->
                  <div class="accordion mb-2" id="acordeonDestino">
                    <div class="card card-accordion">
                      <div id="headingDestinoDesktop">
                        <h5 class="mb-0">
                          <a class="btn btn-accordion btn-calendar text-white" data-toggle="collapse" data-target="#collapseDestinoDesktop" aria-expanded="true">
                            <i class="fa fa-map-marker-alt"></i> <?= $lang["destino"] ?? "Destino" ?><i class="fa fa-sort-down float-right"></i>
                          </a>
                        </h5>
                      </div>
                      <div id="collapseDestinoDesktop" class="collapse show" aria-labelledby="headingDestinoDesktop" data-parent="#acordeonDestino">
                        <div class="card-body p-2">
                          <select id="selectDestino" class="form-control" onchange="cargarTarifas()">
                            <option value="">-- Seleccione destino --</option>
                            <?php foreach ($paradas as $p): ?>
                              <?php if ($p['es_destino']): ?>
                                <option value="<?= $p['idRutaParada'] ?>"><?= htmlspecialchars($p['terminal_nombre']) ?></option>
                              <?php endif; ?>
                            <?php endforeach; ?>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                  <?php endif; ?>

                  <!-- ACORDEÓN DE PASAJEROS -->
                  <div class="accordion mb-2" id="acordeonPasajeros">
                    <div class="card card-accordion">
                      <div id="headingPasajerosDesktop">
                        <h5 class="mb-0">
                          <a class="btn btn-accordion btn-calendar text-white" data-toggle="collapse" data-target="#collapsePasajerosDesktop" aria-expanded="false">
                            <i class="fa fa-users"></i> <?= $lang["pasajeros"] ?? "Pasajeros" ?><i class="fa fa-sort-down float-right"></i>
                          </a>
                        </h5>
                      </div>
                      <div id="collapsePasajerosDesktop" class="collapse" aria-labelledby="headingPasajerosDesktop" data-parent="#acordeonPasajeros">
                        <div class="card-body p-2" id="selectoresPasajeros">
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- ACORDEÓN DE INFORMACIÓN -->
                  <div class="accordion mb-2" id="acordeonInfo">
                    <div class="card card-accordion">
                      <div id="headingInfo">
                        <h5 class="mb-0">
                          <a class="btn btn-accordion btn-calendar text-white" data-toggle="collapse" data-target="#collapseInfo" aria-expanded="false">
                            <i class="fa fa-info-circle"></i> <?= $lang["informacion"] ?? "Información" ?><i class="fa fa-sort-down float-right"></i>
                          </a>
                        </h5>
                      </div>
                      <div id="collapseInfo" class="collapse" aria-labelledby="headingInfo" data-parent="#acordeonInfo">
                        <div class="card-body p-2">
                          <p class="mb-1"><strong><?= $lang["tipo"] ?? "Tipo" ?>:</strong></p>
                          <p class="mx-2 mb-3"><?= htmlspecialchars($viaje['tipo_transporte_nombre'] ?? 'Transporte') ?></p>

                          <p class="mb-1"><strong><?= $lang["salida"] ?? "Salida" ?>:</strong></p>
                          <p class="mx-2 mb-3">
                            <?= !empty($viaje['fecha']) ? date('d/m/Y', strtotime($viaje['fecha'])) : 'N/A' ?><br>
                            <?= !empty($viaje['hora_salida']) ? substr($viaje['hora_salida'], 0, 5) : 'N/A' ?>
                          </p>

                          <p class="mb-1"><strong><?= $lang["disponibilidad"] ?? "Disponibilidad" ?>:</strong></p>
                          <p class="mx-2"><?= $viaje['asientos_disponibles'] ?> / <?= $viaje['asientos_totales'] ?> asientos</p>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- BOTÓN RESERVAR -->
                  <button id="btnReservar" class="btn btn-primary btn-block btn-lg mt-3" disabled>
                    <i class="fa fa-shopping-cart"></i> <?= $lang["reservar"] ?? "Reservar" ?>
                  </button>

                </div>
              </div>
            </div>
          </div>
      </div>
  </div>
</section>

<!-- SCRIPTS -->
<script>
// Paradas JSON
var paradasViaje = <?php echo json_encode(array_map(function($p) {
    return [
        'orden' => (int)$p['orden'],
        'nombre' => $p['terminal_nombre'],
        'ciudad' => $p['ciudad'],
        'latitud' => (float)$p['latitud'],
        'longitud' => (float)$p['longitud'],
        'es_origen' => (int)$p['es_origen'],
        'es_destino' => (int)$p['es_destino'],
        'idRutaParada' => (int)$p['idRutaParada']
    ];
}, $paradas), JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK); ?>;

var idViaje = <?= $idViaje ?>;
var tipoTarifa = '<?= $tipoTarifa ?>';
var tarifasActuales = [];
var cantidadesPasajeros = {
    1: 0, // Adulto
    2: 0, // Niño
    3: 0, // Senior
    4: 0  // Estudiante
};

console.log('✓ Paradas cargadas:', paradasViaje.length);
console.log('✓ ID Viaje:', idViaje);
console.log('✓ Tipo Tarifa:', tipoTarifa);

// Cuando seleccionan origen/destino O clase, cargar tarifas
function cargarTarifas() {
    console.log('cargarTarifas() - Tipo:', tipoTarifa);
    
    if (tipoTarifa === 'clases') {
        // MÉTODO: CLASES
        var idViajeClase = $('#selectClaseDesktop').val() || $('#selectClaseMovil').val();
        
        console.log('Método CLASES - idViajeClase:', idViajeClase);
        
        if (!idViajeClase) {
            console.log('Falta seleccionar clase');
            return;
        }
        
        console.log('Haciendo petición AJAX para CLASES...');
        
        $.ajax({
            url: 'admin/ctrl/ctrlTarifasViaje.php',
            method: 'POST',
            data: {
              action: 'getClasesTarifas',
                idViaje: idViaje,
                idViajeClase: idViajeClase
            },
            dataType: 'json',
            success: function(response) {
                console.log('Respuesta del servidor (CLASES):', response);
                
                if (response.success) {
                    tarifasActuales = response.tarifas;
                    console.log('✓ Tarifas de clase cargadas:', tarifasActuales);
                    generarSelectoresPasajeros();
                    calcularPrecioTotal();
                } else {
                    console.error('Error:', response.message);
                    alert('No hay tarifas disponibles para esta clase\n\n' + (response.message || ''));
                    tarifasActuales = [];
                }
            },
            error: function(xhr, status, error) {
                console.error('Error AJAX:', status, error);
                console.log('Response:', xhr.responseText);
                alert('Error al cargar tarifas. Ver consola para detalles.');
            }
        });
        
    } else if (tipoTarifa === 'segmentado') {
        // MÉTODO: SEGMENTADO (por origen-destino)
        var origen = $('#selectOrigen').val() || $('#selectOrigenMovil').val();
        var destino = $('#selectDestino').val() || $('#selectDestinoMovil').val();
        
        console.log('Método SEGMENTADO - Origen:', origen, 'Destino:', destino);
        
        if (!origen || !destino) {
            console.log('Falta origen o destino');
            return;
        }
        
        if (origen === destino) {
            alert('El origen y destino no pueden ser iguales');
            return;
        }
        
        console.log('Haciendo petición AJAX para SEGMENTADO...');
        
        $.ajax({
            url: 'admin/ctrl/ctrlTarifasViaje.php',
            method: 'POST',
            data: {
              action: 'getTarifasViaje',
                idViaje: idViaje,
                idOrigen: origen,
                idDestino: destino
            },
            dataType: 'json',
            success: function(response) {
                console.log('Respuesta del servidor (SEGMENTADO):', response);
                
                if (response.success) {
                    tarifasActuales = response.tarifas;
                    console.log('✓ Tarifas de segmento cargadas:', tarifasActuales);
                    generarSelectoresPasajeros();
                    calcularPrecioTotal();
                } else {
                    console.error('Error:', response.message);
                    alert('No hay tarifas disponibles para este segmento\n\n' + (response.message || ''));
                    tarifasActuales = [];
                }
            },
            error: function(xhr, status, error) {
                console.error('Error AJAX:', status, error);
                console.log('Response:', xhr.responseText);
                alert('Error al cargar tarifas. Ver consola para detalles.');
            }
        });
    } else {
        console.error('Tipo de tarifa desconocido:', tipoTarifa);
        alert('Error: tipo de tarifa desconocido');
    }
}

// Generar selectores de cantidad de pasajeros
function generarSelectoresPasajeros() {
    if (tarifasActuales.length === 0) {
        return;
    }
    
    var html = '';
    
    tarifasActuales.forEach(function(tarifa) {
        var tipoPasajero = tarifa.nombre_tipo_tarifa || 'Pasajero';
        var idTipoTarifa = tarifa.idTipoTarifa;
        var precio = parseFloat(tarifa.precio_convertido).toFixed(2);
        var simbolo = tarifa.moneda_usuario || 'ARS';
        
        html += '<div class="form-group mb-3">';
        html += '  <label class="font-weight-bold">' + tipoPasajero + ' <small class="text-muted">(' + simbolo + ' ' + precio + ')</small></label>';
        html += '  <div class="input-group">';
        html += '    <div class="input-group-prepend">';
        html += '      <button class="btn btn-outline-secondary" type="button" onclick="cambiarCantidad(' + idTipoTarifa + ', -1)">-</button>';
        html += '    </div>';
        html += '    <input type="text" class="form-control text-center" id="cant_' + idTipoTarifa + '" value="0" readonly>';
        html += '    <div class="input-group-append">';
        html += '      <button class="btn btn-outline-secondary" type="button" onclick="cambiarCantidad(' + idTipoTarifa + ', 1)">+</button>';
        html += '    </div>';
        html += '  </div>';
        html += '</div>';
    });
    
    $('#selectoresPasajeros').html(html);
    $('#selectoresPasajerosMovil').html(html);
}

// Cambiar cantidad de pasajeros
function cambiarCantidad(idTipoTarifa, cambio) {
    var actual = cantidadesPasajeros[idTipoTarifa] || 0;
    var nuevo = actual + cambio;
    
    if (nuevo < 0) nuevo = 0;
    if (nuevo > 10) nuevo = 10; // Máximo 10 por tipo
    
    cantidadesPasajeros[idTipoTarifa] = nuevo;
    $('#cant_' + idTipoTarifa).val(nuevo);
    
    calcularPrecioTotal();
}

// Calcular precio total
function calcularPrecioTotal() {
    var total = 0;
    var totalPasajeros = 0;
    
    tarifasActuales.forEach(function(tarifa) {
        var cantidad = cantidadesPasajeros[tarifa.idTipoTarifa] || 0;
        var precio = parseFloat(tarifa.precio_convertido);
        total += cantidad * precio;
        totalPasajeros += cantidad;
    });
    
    if (totalPasajeros > 0 && tarifasActuales.length > 0) {
        var simbolo = tarifasActuales[0].moneda_usuario || 'ARS';
        $('#precioTotal').text(simbolo + ' ' + total.toFixed(2));
        $('#precioValorMovil').text(simbolo + ' ' + total.toFixed(2));
        $('#precioTotalMovil').show();
        $('#btnReservar, #btnReservarMovil').prop('disabled', false);
    } else {
        $('#precioTotal').text('--');
        $('#precioTotalMovil').hide();
        $('#btnReservar, #btnReservarMovil').prop('disabled', true);
    }
}

// Reservar
function reservarPasaje() {
    var totalPasajeros = 0;
    for (var key in cantidadesPasajeros) {
        totalPasajeros += cantidadesPasajeros[key];
    }
    
    if (totalPasajeros === 0) {
        alert('Debe seleccionar al menos un pasajero');
        return;
    }
    
    // Validar según tipo de tarifa
    if (tipoTarifa === 'clases') {
        var clase = $('#selectClaseDesktop').val() || $('#selectClaseMovil').val();
        if (!clase) {
            alert('Debe seleccionar una clase');
            return;
        }
        // TODO: Agregar al carrito
        alert('Reserva en desarrollo\n\nViaje ID: ' + idViaje + '\nClase: ' + clase + '\nPasajeros: ' + totalPasajeros);
    } else if (tipoTarifa === 'segmentado') {
        var origen = $('#selectOrigen').val() || $('#selectOrigenMovil').val();
        var destino = $('#selectDestino').val() || $('#selectDestinoMovil').val();
        
        if (!origen || !destino) {
            alert('Debe seleccionar origen y destino');
            return;
        }
        // TODO: Agregar al carrito
        alert('Reserva en desarrollo\n\nViaje ID: ' + idViaje + '\nOrigen: ' + origen + '\nDestino: ' + destino + '\nPasajeros: ' + totalPasajeros);
    }
}

// Events
$(document).ready(function() {
    // Para método SEGMENTADO (origen/destino)
    $('#selectOrigen, #selectOrigenMovil').on('change', function() {
        var valor = $(this).val();
        $('#selectOrigen, #selectOrigenMovil').val(valor);
        cargarTarifas();
    });
    
    $('#selectDestino, #selectDestinoMovil').on('change', function() {
        var valor = $(this).val();
        $('#selectDestino, #selectDestinoMovil').val(valor);
        cargarTarifas();
    });
    
    // Para método CLASES (clase de servicio)
    $('#selectClaseDesktop, #selectClaseMovil').on('change', function() {
        var valor = $(this).val();
        $('#selectClaseDesktop, #selectClaseMovil').val(valor);
        cargarTarifas();
    });
    
    $('#btnReservar, #btnReservarMovil').on('click', reservarPasaje);
});

console.log('✓ Sistema de reserva inicializado');
</script>

<!-- Función mapa (scope global) -->
<script>
function inicializarMapa() {
    console.log('=== Google Maps Callback ===');
    
    if (!paradasViaje || paradasViaje.length === 0) {
        console.error('No hay paradas');
        return;
    }
    
    // Desktop map
    var containerDesktop = document.getElementById('mapRecorridoDesktop');
    if (containerDesktop) {
        renderMap(containerDesktop);
    }
    
    // Mobile map
    var containerMovil = document.getElementById('mapRecorridoMovil');
    if (containerMovil) {
        renderMap(containerMovil);
    }
    
    console.log('✓ Mapas renderizados');
}

function renderMap(container) {
    var map = new google.maps.Map(container, {
        zoom: 6,
        center: {lat: paradasViaje[0].latitud, lng: paradasViaje[0].longitud}
    });
    
    var bounds = new google.maps.LatLngBounds();
    var path = [];
    
    paradasViaje.forEach(function(p) {
        var pos = {lat: p.latitud, lng: p.longitud};
        bounds.extend(pos);
        path.push(pos);
        
        var color = '#6c757d';
        if (p.es_origen && p.es_destino) {
            color = '#ffc107';
        } else if (p.es_origen) {
            color = '#28a745';
        } else if (p.es_destino) {
            color = '#dc3545';
        }
        
        new google.maps.Marker({
            position: pos,
            map: map,
            label: {text: p.orden.toString(), color: 'white', fontWeight: 'bold'},
            icon: {
                path: google.maps.SymbolPath.CIRCLE,
                fillColor: color,
                fillOpacity: 1,
                strokeColor: 'white',
                strokeWeight: 2,
                scale: 12
            },
            title: p.nombre
        });
    });
    
    new google.maps.Polyline({
        path: path,
        geodesic: true,
        strokeColor: '#17a2b8',
        strokeOpacity: 0.8,
        strokeWeight: 4,
        map: map
    });
    
    map.fitBounds(bounds);
}
</script>

<!-- Google Maps -->
<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_MAPS_API_KEY; ?>&callback=inicializarMapa"></script>

<?php include('footer.php'); ?>
