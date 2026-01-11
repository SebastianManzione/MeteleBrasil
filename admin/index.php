<?php


include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");
// Verificar permisos de acceso ANTES de cualquier salida
require_once(__DIR__ . "/classes/permisos.php");
require_once(__DIR__ . "/includes/permisos_helper.php");
$permisos = new PermisosManager($GLOBALS['pdo'], $_SESSION['login'] ?? []);
$permisos->verificarAcceso('index');
require("classes/servicio.php");
require("classes/salidas.php");
require("classes/usuario.php");
require("classes/visitas.php");
require("classes/reserva.php");
require("classes/edades.php");
require("classes/tarifas.php");
require("classes/convierte_monedas.php");
require("classes/comprobantes.php");

$idPrestador = $_SESSION['login']['idPrestador'];
$idVendedor = $_SESSION['login']['idVendedor'] ?? 0;
$idUsuario = $_SESSION['login']['idUsuario'];
$isAdmin = ($idUsuario == 1);
$isPrestador = ($idPrestador > 0);
$isVendedor = ($idVendedor > 0);
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Dashboard</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
          </ol>
        </div>
      </div>
    </div>
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-3 col-6">
          <div class="small-box bg-info">
            <div class="inner">
              <h3><?= count(getServicios()); ?></h3>
              <p>Servicios Activos</p>
            </div>
            <div class="icon">
              <i class="fas fa-concierge-bell"></i>
            </div>
            <a class="small-box-footer">Ver más <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <div class="col-lg-3 col-6">
          <div class="small-box bg-success">
            <div class="inner">
              <h3><?= count(getSalidas()); ?></h3>
              <p>Salidas Programadas</p>
            </div>
            <div class="icon">
              <i class="fas fa-route"></i>
            </div>
            <a class="small-box-footer">Ver más <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <div class="col-lg-3 col-6">
          <div class="small-box bg-warning">
            <div class="inner">
              <h3><?= count(getUsuarios()); ?></h3>
              <p>Usuarios Registrados</p>
            </div>
            <div class="icon">
              <i class="fas fa-users"></i>
            </div>
            <a href="#" class="small-box-footer">Ver más <i class="fas fa-arrow-circle-right"></i></a> 
          </div>
        </div>

        <div class="col-lg-3 col-6">
          <div class="small-box bg-danger">
            <div class="inner">
              <h3><?= count(getVisitas()); ?></h3>
              <p>Visitas Totales</p>
            </div>
            <div class="icon">
              <i class="fas fa-chart-line"></i>
            </div>
            <a href="visitantesLista" class="small-box-footer">Ver más <i class="fas fa-arrow-circle-right"></i></a> 
          </div>
        </div>
      </div>
      <!-- /.row -->

      <!-- Main row -->
      <div class="row">
        <!-- Columna de Prestadores (Izquierda) -->
        <?php if ($isPrestador || $isAdmin): ?>
        <section class="<?= ($isVendedor || $isAdmin) ? 'col-lg-6' : 'col-lg-12' ?> connectedSortable">
          <h2 class="mb-3"><i class="fas fa-store text-primary"></i> Salidas de Prestadores</h2>
          <!-- Date Picker para seleccionar fecha de salidas -->
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Seleccionar Fecha de Salidas</h3>
            </div>
            <div class="card-body">
              <form method="get" class="form-inline">
                <?php if (isset($_GET['tipo']) && $_GET['tipo'] == 'comision' && isset($_GET['fechaComision'])): ?>
                  <input type="hidden" name="tipo" value="comision">
                  <input type="hidden" name="fechaComision" value="<?php echo $_GET['fechaComision']; ?>">
                <?php endif; ?>
                <div class="form-group mb-2">
                  <label for="fechaSalidas" class="mr-2">Fecha:</label>
                  <input type="date" class="form-control" id="fechaSalidas" name="fechaSalidas"        
                         value="<?php echo isset($_GET['fechaSalidas']) ? $_GET['fechaSalidas'] : date('Y-m-d'); ?>"                                                                                                                   min="<?php echo date('Y-m-d'); ?>">
                </div>
                <button type="submit" class="btn btn-primary mb-2 ml-2">Ver Salidas</button>
                <?php if (isset($_GET['fechaSalidas'])): ?>
                  <?php
                  $url = 'index.php';
                  $params = [];
                  if (isset($_GET['tipo']) && $_GET['tipo'] == 'comision' && isset($_GET['fechaComision'])) {
                    $params[] = 'tipo=comision';
                    $params[] = 'fechaComision=' . $_GET['fechaComision'];
                  }
                  if (!empty($params)) {
                    $url .= '?' . implode('&', $params);
                  }
                  ?>
                  <a href="<?php echo $url; ?>" class="btn btn-secondary mb-2 ml-2">Ver Hoy</a>        
                <?php endif; ?>
              </form>
              <div class="mt-3">
                <div class="custom-control custom-checkbox">
                  <input type="checkbox" class="custom-control-input" id="filtrarPasajerosPrestador" <?= (!isset($_GET['mostrarTodos']) || $_GET['mostrarTodos'] != 'prestador') ? 'checked' : '' ?> onchange="document.getElementById('formFiltrosPrestador').submit();">
                  <label class="custom-control-label" for="filtrarPasajerosPrestador">
                    Mostrar solo con pasajeros
                  </label>
                </div>
                <form id="formFiltrosPrestador" method="get" style="display:none;">
                  <input type="hidden" name="fechaSalidas" value="<?php echo isset($_GET['fechaSalidas']) ? $_GET['fechaSalidas'] : date('Y-m-d'); ?>">
                  <input type="hidden" name="mostrarTodos" value="<?= isset($_GET['mostrarTodos']) && $_GET['mostrarTodos'] == 'prestador' ? '' : 'prestador' ?>">
                </form>
              </div>
            </div>
          </div>

          <?php
          // Obtener fecha seleccionada o usar hoy por defecto
          $fechaSeleccionada = isset($_GET['fechaSalidas']) ? $_GET['fechaSalidas'] : date('Y-m-d');   
          $mostrarTodosPrestador = isset($_GET['mostrarTodos']) && $_GET['mostrarTodos'] == 'prestador';

          $salidas = getSalidasIdPrestadorFecha($fechaSeleccionada);

          if (empty($salidas)) {
          ?>
              <div class="card card-outline card-info">
                  <div class="card-body text-center">
                      <h4 class="card-title"><i class="fas fa-info-circle mr-2"></i>No hay salidas programadas para <?php echo date('d/m/Y', strtotime($fechaSeleccionada)); ?>.</h4>                                           </div>
              </div>
          <?php
          }

          foreach ($salidas as $salida) {
              $servicio = getServicio($salida['idServicio']);
              if (empty($servicio) || !isset($servicio[0])) {
                  continue; // Saltar si no se puede obtener el servicio
              }
              $nombre_servicio = $servicio[0]['nombre_servicio'];
              $idServicioSalidas = $salida['idServicioSalidas'];

              $tarifas = getTarifasReservadas($idServicioSalidas);

              $reservas_agrupadas = [];
              foreach ($tarifas as $tarifa) {
                  $idReserva = $tarifa['idReserva'];
                  $idReservaTarifas = $tarifa['idReservaTarifas'];

                  if (!isset($reservas_agrupadas[$idReserva])) {
                      $reserva_data = getReservaId($idReserva);
                      if (empty($reserva_data)) continue;

                      $reserva = $reserva_data[0];
                      $totalDolares = $reserva["total_dolares"];
                      $totalComprobantes = getComprobantesIdReservaDolar($idReserva);
                      $diferenciaComprobantesPrecio = $totalDolares - $totalComprobantes;

                      if ($diferenciaComprobantesPrecio <= 0) {
                          $reservas_agrupadas[$idReserva] = [
                              'detalles' => $reserva,
                              'pasajeros' => []
                          ];
                      }
                  }

                  if (isset($reservas_agrupadas[$idReserva])) {
                      $pasajeros = getPasajeros($idReservaTarifas);
                      foreach ($pasajeros as $pasajero) {
                          $reservas_agrupadas[$idReserva]['pasajeros'][] = $pasajero;
                      }
                  }
              }

              // Cantidad de pasajeros reales para esta salida
              $total_pasajeros = 0;
              foreach ($reservas_agrupadas as $reserva) {
                  $total_pasajeros += count($reserva['pasajeros']);
              }
              
              // Saltar si el filtro está activo y no hay pasajeros
              if (!$mostrarTodosPrestador && $total_pasajeros == 0) {
                  continue;
              }
              
              $cantidad_reservas = count($reservas_agrupadas);
          ?>
          <div class="card <?= $cantidad_reservas > 0 ? 'card-primary' : 'card-secondary' ?> card-outline shadow-sm">
            <div class="card-header">
              <h3 class="card-title d-flex align-items-center justify-content-between">
                <span>
                  <i class="fas fa-users mr-1"></i>
                  <strong><?= htmlspecialchars($nombre_servicio); ?></strong>  
                  - Salida #<?= htmlspecialchars($idServicioSalidas) ?> - <?= htmlspecialchars($salida['horaSalida']); ?>                                                                                                 </span>

                <span class="badge <?= $cantidad_reservas > 0 ? 'badge-success' : 'badge-secondary' ?> badge-reservas ml-3 p-2 animate__animated animate__fadeInRight" style="font-size: 1rem;">                                                                                                      <?= $cantidad_reservas > 0 ? '🏆 ' . $cantidad_reservas . ' reservas' : '⚪ Sin pasajeros' ?>
                </span>
              </h3>
            </div>

            <div class="card-body table-responsive p-0">
              <table class="table table-striped table-hover table-bordered">
                  <thead class="thead-light">
                      <tr class="text-center">
                          <th style="width: 35%;">Nombre Pasajero</th>
                          <th style="width: 20%;">Cod. Reserva</th>
                          <th style="width: 25%;">Fecha Contratación</th>
                          <th style="width: 20%;">Valor Reserva</th>
                      </tr>
                  </thead>
                  <tbody>
                  <?php
                  if (empty($reservas_agrupadas)) {
                      echo '<tr><td colspan="4" class="text-center font-italic p-4">SIN PASAJEROS CONFIRMADOS</td></tr>';                                                                                                       } else {
                      foreach ($reservas_agrupadas as $reserva) {
                          $num_pasajeros = count($reserva['pasajeros']);
                          if ($num_pasajeros === 0) continue;

                          foreach ($reserva['pasajeros'] as $k => $pasajero) {
                  ?>
                              <tr>
                                  <td><?= htmlspecialchars($pasajero["nombrePasajero"] . " " . $pasajero["apellidoPasajero"]) ?></td>                                                                                                           <?php if ($k === 0) { ?>
                                  <td rowspan="<?= $num_pasajeros ?>" class="align-middle text-center">
                                      <span class="badge badge-info p-2" style="font-size: 0.9rem;">Código: <?= htmlspecialchars($reserva['detalles']['codigoAmigable']); ?></span>                                                                     </td>
                                  <td rowspan="<?= $num_pasajeros ?>" class="align-middle text-center"><?= date("d-m-Y H:i", strtotime($reserva['detalles']['fechaAlta'])); ?></td>                                                             <td rowspan="<?= $num_pasajeros ?>" class="align-middle text-center font-weight-bold"><?= htmlspecialchars($_SESSION["moneda_sel_sym"]) . number_format(ConvierteMoneda(188, $_SESSION['moneda_sel'], $reserva['detalles']['total_dolares']), 2, ',', '.') ?></td>                                                                                                  <?php } ?>
                              </tr>
                  <?php
                          }
                      }
                  }
                  ?>
                  </tbody>
              </table>
            </div>
          </div>
          <?php } ?>
        </section>
        <?php endif; ?>

        <!-- Columna de Vendedores (Derecha) -->
        <?php if ($isAdmin): ?>
        <section class="<?= ($isPrestador || $isAdmin) ? 'col-lg-6' : 'col-lg-12' ?> connectedSortable">
          <h2 class="mb-3"><i class="fas fa-user-tie text-success"></i> Salidas de Vendedores</h2>
          <!-- Date Picker para seleccionar fecha de salidas vendedores -->
          <div class="card card-success">
            <div class="card-header">
              <h3 class="card-title">Seleccionar Fecha de Salidas</h3>
            </div>
            <div class="card-body">
              <form method="get" class="form-inline">
                <div class="form-group mb-2">
                  <label for="fechaSalidasVendedor" class="mr-2">Fecha:</label>
                  <input type="date" class="form-control" id="fechaSalidasVendedor" name="fechaSalidasVendedor"        
                         value="<?php echo isset($_GET['fechaSalidasVendedor']) ? $_GET['fechaSalidasVendedor'] : date('Y-m-d'); ?>"                                                                                                                   min="<?php echo date('Y-m-d'); ?>">
                </div>
                <button type="submit" class="btn btn-success mb-2 ml-2">Ver Salidas</button>
                <?php if (isset($_GET['fechaSalidasVendedor'])): ?>
                  <a href="index.php" class="btn btn-secondary mb-2 ml-2">Ver Hoy</a>        
                <?php endif; ?>
              </form>
              <div class="mt-3">
                <div class="custom-control custom-checkbox">
                  <input type="checkbox" class="custom-control-input" id="filtrarPasajerosVendedor" <?= (!isset($_GET['mostrarTodosVend']) || $_GET['mostrarTodosVend'] != 'vendedor') ? 'checked' : '' ?> onchange="document.getElementById('formFiltrosVendedor').submit();">
                  <label class="custom-control-label" for="filtrarPasajerosVendedor">
                    Mostrar solo con pasajeros
                  </label>
                </div>
                <form id="formFiltrosVendedor" method="get" style="display:none;">
                  <input type="hidden" name="fechaSalidas" value="<?php echo isset($_GET['fechaSalidas']) ? $_GET['fechaSalidas'] : date('Y-m-d'); ?>">
                  <input type="hidden" name="mostrarTodos" value="<?php echo isset($_GET['mostrarTodos']) ? $_GET['mostrarTodos'] : ''; ?>">
                  <input type="hidden" name="fechaSalidasVendedor" value="<?php echo isset($_GET['fechaSalidasVendedor']) ? $_GET['fechaSalidasVendedor'] : date('Y-m-d'); ?>">
                  <input type="hidden" name="mostrarTodosVend" value="<?= isset($_GET['mostrarTodosVend']) && $_GET['mostrarTodosVend'] == 'vendedor' ? '' : 'vendedor' ?>">
                </form>
              </div>
            </div>
          </div>

          <?php
          // Obtener fecha seleccionada o usar hoy por defecto
          $fechaSeleccionadaVendedor = isset($_GET['fechaSalidasVendedor']) ? $_GET['fechaSalidasVendedor'] : date('Y-m-d');   
          $mostrarTodosVendedor = isset($_GET['mostrarTodosVend']) && $_GET['mostrarTodosVend'] == 'vendedor';
          $salidasVendedor = getSalidasVendedorFecha($fechaSeleccionadaVendedor);

          if (empty($salidasVendedor)) {
          ?>
              <div class="card card-outline card-success">
                  <div class="card-body text-center">
                      <h4 class="card-title"><i class="fas fa-info-circle mr-2"></i>No hay salidas con reservas de vendedores para <?php echo date('d/m/Y', strtotime($fechaSeleccionadaVendedor)); ?>.</h4>                                           </div>
              </div>
          <?php
          }

          foreach ($salidasVendedor as $salidaVend) {
              $servicioVend = getServicio($salidaVend['idServicio']);
              if (empty($servicioVend) || !isset($servicioVend[0])) {
                  continue;
              }
              $nombre_servicio_vend = $servicioVend[0]['nombre_servicio'];
              $idServicioSalidasVend = $salidaVend['idServicioSalidas'];

              $tarifasVend = getTarifasReservadas($idServicioSalidasVend);
              
              // Si no hay tarifas y el filtro está activo, saltar
              if (empty($tarifasVend) && !$mostrarTodosVendedor) {
                  continue;
              }

              $reservas_agrupadas_vend = [];
              foreach ($tarifasVend as $tarifaVend) {
                  $idReservaVend = $tarifaVend['idReserva'];
                  $idReservaTarifasVend = $tarifaVend['idReservaTarifas'];

                  if (!isset($reservas_agrupadas_vend[$idReservaVend])) {
                      $reserva_data_vend = getReservaId($idReservaVend);
                      if (empty($reserva_data_vend)) {
                          continue;
                      }

                      $reservaVend = $reserva_data_vend[0];
                      
                      // Verificar que la reserva fue creada por un vendedor
                      $usuarioReserva = getUsuario($reservaVend['idUsuario']);
                      if (empty($usuarioReserva) || $usuarioReserva[0]['idVendedor'] == 0) {
                          continue;
                      }
                      
                      // Si es vendedor (no admin), solo mostrar sus propias reservas
                      if (!$isAdmin && $reservaVend['idUsuario'] != $idUsuario) {
                          continue;
                      }

                      // Agregar la reserva sin verificar si está pagada
                      $reservas_agrupadas_vend[$idReservaVend] = [
                          'detalles' => $reservaVend,
                          'pasajeros' => []
                      ];
                  }

                  if (isset($reservas_agrupadas_vend[$idReservaVend])) {
                      $pasajerosVend = getPasajeros($idReservaTarifasVend);
                      foreach ($pasajerosVend as $pasajeroVend) {
                          $reservas_agrupadas_vend[$idReservaVend]['pasajeros'][] = $pasajeroVend;
                      }
                  }
              }

              // Cantidad de pasajeros reales para esta salida
              $total_pasajerosVendedor = 0;
              foreach ($reservas_agrupadas_vend as $reserva) {
                  $total_pasajerosVendedor += count($reserva['pasajeros']);
              }
              
              // Mostrar badge según disponibilidad
              $cantidad_reservas_vend = count($reservas_agrupadas_vend);
              if ($cantidad_reservas_vend == 0) {
                  $cantidad_reservas_vend = 0; // Sin reservas
              }
          ?>
          <div class="card <?= $cantidad_reservas_vend > 0 ? 'card-success' : 'card-secondary' ?> card-outline shadow-sm">
            <div class="card-header">
              <h3 class="card-title d-flex align-items-center justify-content-between">
                <span>
                  <i class="fas fa-users mr-1"></i>
                  <strong><?= htmlspecialchars($nombre_servicio_vend); ?></strong>  
                  - Salida #<?= htmlspecialchars($idServicioSalidasVend) ?> - <?= htmlspecialchars($salidaVend['horaSalida']); ?>                                                                                                 </span>

                <span class="badge <?= $cantidad_reservas_vend > 0 ? 'badge-success' : 'badge-secondary' ?> badge-reservas ml-3 p-2 animate__animated animate__fadeInRight" style="font-size: 1rem;">                                                                                                      <?= $cantidad_reservas_vend > 0 ? '🎯 ' . $cantidad_reservas_vend . ' reservas' : '⚪ Sin pasajeros' ?>
                </span>
              </h3>
            </div>

            <div class="card-body table-responsive p-0">
              <table class="table table-striped table-hover table-bordered">
                  <thead class="thead-light">
                      <tr class="text-center">
                          <th style="width: 35%;">Nombre Pasajero</th>
                          <th style="width: 20%;">Cod. Reserva</th>
                          <th style="width: 25%;">Fecha Contratación</th>
                          <th style="width: 20%;">Valor Reserva</th>
                      </tr>
                  </thead>
                  <tbody>
                  <?php
                  if (empty($reservas_agrupadas_vend)) {
                      echo '<tr><td colspan="4" class="text-center font-italic p-4">SIN PASAJEROS CONFIRMADOS</td></tr>';                                                                                                       } else {
                      foreach ($reservas_agrupadas_vend as $reservaVend) {
                          $num_pasajeros_vend = count($reservaVend['pasajeros']);
                          if ($num_pasajeros_vend === 0) continue;

                          foreach ($reservaVend['pasajeros'] as $k => $pasajeroVend) {
                  ?>
                              <tr>
                                  <td><?= htmlspecialchars($pasajeroVend["nombrePasajero"] . " " . $pasajeroVend["apellidoPasajero"]) ?></td>                                                                                                           <?php if ($k === 0) { ?>
                                  <td rowspan="<?= $num_pasajeros_vend ?>" class="align-middle text-center">
                                      <span class="badge badge-success p-2" style="font-size: 0.9rem;">Código: <?= htmlspecialchars($reservaVend['detalles']['codigoAmigable']); ?></span>                                                                     </td>
                                  <td rowspan="<?= $num_pasajeros_vend ?>" class="align-middle text-center"><?= date("d-m-Y H:i", strtotime($reservaVend['detalles']['fechaAlta'])); ?></td>                                                             <td rowspan="<?= $num_pasajeros_vend ?>" class="align-middle text-center font-weight-bold"><?= htmlspecialchars($_SESSION["moneda_sel_sym"]) . number_format(ConvierteMoneda(188, $_SESSION['moneda_sel'], $reservaVend['detalles']['total_dolares']), 2, ',', '.') ?></td>                                                                                                  <?php } ?>
                              </tr>
                  <?php
                          }
                      }
                  }
                  ?>
                  </tbody>
              </table>
            </div>
          </div>
          <?php } ?>
        </section>
        <?php endif; ?>

        <!-- Sección de salidas con comisión ocultada temporalmente -->

      </div>
    </div>
  </section>
</div>

<?php
include("includes/footer.php");
?>

<!-- Animación CSS -->
<style>
.badge-reservas {
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  cursor: default;
}
.badge-reservas:hover {
  transform: scale(1.1) rotate(-2deg);
  box-shadow: 0 0 10px rgba(0, 128, 0, 0.4);
}
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
