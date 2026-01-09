<?php
header('Content-Type: text/html; charset=UTF-8');
$fecha_actual = date("d-m-Y");
include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");
require("classes/functions.php");
require("classes/categoria.php");
require("classes/texto_miniaturas.php");
require("classes/tipos_tarifa.php");
require("classes/accesibilidad.php");
require("classes/idiomas.php");
require("classes/reserva.php");
require("classes/edades.php");
require("classes/salidas.php");
require("classes/tarifas.php");
require("classes/tarifas_ubicacion.php");
require("classes/prestador.php");
require("classes/servicio.php");
require("classes/cancelaciones.php");
require("classes/fotos_servicio.php");
require("classes/servicios_adicionales.php");
require("classes/convierte_monedas.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["eliminarServicio"])) {

  alertar($lang["por_favor_espere_no_cierre"], "success");

  $idServicio = $_POST["eliminarServicio"];
  $reservas = getHorariosReservados_idServicioSeleccionado($idServicio);

  if (count($reservas) > 0) { //count($reservas)>0
    foreach ($reservas as $key => $value) {
      $idReserva = $value['idReserva'];
      $reserva = getReservaId($idReserva);
      echo "Reserva con horario en este servicio: " . $reserva[0]["codigoAmigable"] . "<br>";
    }


    alertar($lang["no_se_puede_eliminar_servicios"], "error");
  } else {

    $resEliminar = eliminarServicio($idServicio);
    alertar($lang["servicio_eliminado"], "success");
    redireccionarLento("serviciosLista");
    exit();
  }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["vaciarServicio"]) && $_POST['vaciarServicio']>0) {


  

  $idServicio = $_POST["vaciarServicio"];
  $reservas = getHorariosReservados_idServicioSeleccionado($idServicio);
$reservasOcupando="";
  if (count($reservas) > 0) { //count($reservas)>0
    foreach ($reservas as $key => $value) {
      $idReserva = $value['idReserva'];
      $reserva = getReservaId($idReserva);
      $reservasOcupando=$reservasOcupando."<br> ".$reserva[0]["codigoAmigable"];
  

    }


   alertar($lang["no_se_puede_eliminar_servicios"]. $reservasOcupando, "error");
    exit();  


  } else {

    $resEliminar = vaciarServicio($idServicio);
   alertar("Servicio desocupado con éxito", "success");
    redireccionarLento("servicioVer?idServicio=" . $idServicio);
    exit();
  }
}


if (isset($_GET["idServicio"])) {

  $idServicio = $_GET["idServicio"];

  $servicio = getServicio($idServicio)[0];

  $fotos = getFotoMiniaturaServicio($idServicio);

  $categoria = getCategoria($servicio["idCategoria_servicio"]);
}

?>

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark"><strong><?= $lang["servicio"]; ?></strong>: <?= $servicio["nombre_servicio"]; ?></h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="serviciosLista">Servicios</a></li>
            <li class="breadcrumb-item active"><?= $servicio["nombre_servicio"]; ?></li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <!-- Service Details Card -->
      <div class="card card-primary card-outline">
        <div class="card-header">
          <h3 class="card-title"><i class="fas fa-info-circle mr-2"></i><?= $lang["servicio"]; ?> <?= $servicio["nombre_servicio"]; ?></h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fas fa-minus"></i>
            </button>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-12 col-lg-8">
              <h4 class="font-weight-bold"><?= $lang["descripcion_del_servicio"]; ?></h4>
              <div class="post">
                <div class="user-block">
                  <span class="description text-muted"><strong><?= $lang["fecha_alta"]; ?></strong> - <?= date("d/m/Y", strtotime($servicio["fechaAlta"])) ?></span>
                </div>
                <p><?= $servicio["descripcion_corta"]; ?></p>
              </div>

              <h5 class="mt-4 text-muted"><?= $lang["galeria"] ?? "Galería"; ?></h5>
              <div class="row">
                <?php if (count($fotos) > 0) : ?>
                  <?php foreach ($fotos as $foto) : ?>
                    <div class="col-6 col-sm-4 col-md-3 mb-3">
                      <a href="classes/imgServicio/<?= $foto["ruta"] ?>" data-toggle="lightbox" data-gallery="gallery">
                        <img src="classes/imgServicio/<?= $foto["ruta"] ?>" class="img-fluid rounded shadow-sm" alt="Foto del servicio" style="width: 100%; height: 120px; object-fit: cover;">
                      </a>
                    </div>
                  <?php endforeach; ?>
                <?php else : ?>
                  <div class="col-12">
                    <p class="text-muted">No hay fotos disponibles para este servicio.</p>
                  </div>
                <?php endif; ?>
              </div>
            </div>
            <div class="col-12 col-lg-4">
              <div class="bg-light p-3 rounded">
                <h3 class="text-primary font-weight-bold"><i class="fas fa-list-alt"></i> Detalles</h3>
                <hr>
                <div class="text-muted mt-4">
                  <p class="text-sm"><strong><?= $lang["categoria_del_servicio"]; ?></strong>
                    <span class="d-block"><?= $categoria[0]["nombre_categoria_servicio"] ?></span>
                  </p>
                  <p class="text-sm"><strong><?= $lang["observaciones"]; ?></strong>
                    <span class="d-block"><?= $servicio["observaciones"] ?: 'N/A'; ?></span>
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /.card-body -->
        <div class="card-footer bg-light">
          <div class="d-flex flex-wrap justify-content-start">
            <form method="post" action="altaSalidas" class="mr-2 mb-2">
              <button name="idServicio" value="<?= $idServicio; ?>" class="btn btn-info"><i class="fas fa-plus-circle mr-1"></i><?= $lang["agregar_salida"]; ?></button>
            </form>
            <?php if ($_SESSION['login']["idUsuario"] == 1) : ?>
              <form method="post" action="servicioOpiniones" class="mr-2 mb-2">
                <button name="idServicio" value="<?= $idServicio; ?>" class="btn btn-success"><i class="fas fa-star mr-1"></i><?= $lang["opiniones"]; ?></button>
              </form>
              <form method="get" action="altaServicio.php" class="mr-2 mb-2">
                <input type="hidden" name="idServicio" value="<?= $idServicio; ?>">
                <button class="btn btn-primary" type="submit"><i class="fas fa-edit mr-1"></i><?= $lang["editar_servicio"]; ?></button>
              </form>
              <form method="post" action="servicioFotos" class="mr-2 mb-2">
                <button type="submit" class="btn btn-secondary" name="idServicio" value="<?= $idServicio ?>"><i class="fas fa-camera mr-1"></i>Editor de fotos</button>
              </form>
              <form method="get" action="servicioComisionPrestador.php" class="mr-2 mb-2">
                <input type="hidden" name="idServicio" value="<?= $idServicio ?>">
                <button type="submit" class="btn btn-info"><i class="fas fa-percent mr-1"></i><?= $lang["comision_inicial_prestador"]; ?></button>
              </form>
              <form method="post" id="borraServicio" class="mr-2 mb-2">
                <input type="hidden" name="eliminarServicio" value="<?= $idServicio; ?>">
                <button type="button" class="btn btn-danger" onclick="confirm1()"><i class="fas fa-trash mr-1"></i><?= $lang["eliminar_servicio"]; ?></button>
              </form>
              <form method="post" id="vaciarServicio" class="mr-2 mb-2">
                <input type="hidden" name="vaciarServicio" value="<?= $idServicio; ?>">
                <button type="button" class="btn btn-warning" onclick="confirm2()"><i class="fas fa-calendar-times mr-1"></i>Vaciar Salidas</button>
              </form>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <!-- /.card -->
    </div>
  </section>

  <!-- Departures Table -->
  <section class="content">
    <div class="container-fluid">
      <div class="card card-primary card-outline">
        <div class="card-header">
          <h3 class="card-title"><i class="fas fa-calendar-alt mr-2"></i>Salidas Programadas</h3>
        </div>
        <div class="card-body">
          <div class="row mb-3">
            <div class="col-md-6">
              <div class="input-group">
                <span class="input-group-text">Desde</span>
                <input type="date" id="min-date" class="form-control date-range-filter" value="<?= date('Y-m-d') ?>">
                <span class="input-group-text">Hasta</span>
                <input type="date" id="max-date" class="form-control date-range-filter" value="<?= date('Y-m-d', strtotime('+1 day')) ?>">
              </div>
            </div>
          </div>
          <div class="table-responsive">
            <table id="tabla_servicios" class="table table-bordered table-striped" style="width: 100%;">
              <thead>
                <tr>
                  <th>ID Salida</th>
                  <th>Nombre</th>
                  <th>Disponibles</th>
                  <th>Reservados</th>
                  <th>Periodo</th>
                  <th>Fecha y Hora</th>
                  <th>Acción</th>
                  <th class="d-none">Fecha Filtro</th>
                </tr>
              </thead>
              <tbody>
                <?php
                if ($_SESSION['login']["idUsuario"] == 1) {
                  $salidas = getAllSalidasServicio($idServicio);
                } else {
                  $salidas = getSalidasServicioIdPrestador($idServicio);
                }

                foreach ($salidas as $salida) {
                  $idServicioSalidas = $salida["idServicioSalidas"];
                  $cantPasajeros = getCantidadPasajerosIdReservaTarifas($idServicioSalidas);
                ?>
                  <tr>
                    <td><?= $idServicioSalidas; ?></td>
                    <td><?= htmlspecialchars($salida["nombre"]); ?></td>
                    <td><span class="badge bg-success"><?= $salida["disponibilidad"] - $cantPasajeros; ?></span></td>
                    <td><span class="badge bg-info"><?= $cantPasajeros; ?></span></td>
                    <td><?= htmlspecialchars($salida["nombre"]); ?></td>
                    <td data-sort="<?= strtotime($salida["fecha"] . " " . $salida["horaSalida"]) ?>">
                      <?= strftime("%A", strtotime($salida["fecha"])) ?>, <?= date("d/m/Y", strtotime($salida["fecha"])) ?> - <?= $salida["horaSalida"] ?>
                    </td>
                    <td>
                      <form method="post" action="salidaVer.php">
                        <button class="btn btn-primary btn-sm" name="idServicioSalidas" value="<?= $idServicioSalidas; ?>">
                          <i class="fas fa-eye mr-1"></i><?= $lang["ver"]; ?>
                        </button>
                      </form>
                    </td>
                    <td class="d-none"><?= date('Y-m-d', strtotime($salida['fecha'])) ?></td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<script type="text/javascript">
  function confirm1() {
    Swal.fire({
      title: '¿Está seguro?',
      text: "Esta acción borrará el servicio, sus salidas y tarifas. ¡No se puede revertir!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Sí, ¡Borrar!',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.isConfirmed) {
        $("#borraServicio").submit();
      }
    });
  }

  function confirm2() {
    Swal.fire({
      title: '¿Está seguro?',
      text: "Se borrarán todas las salidas y tarifas de este servicio, pero el servicio se conservará.",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#ffc107',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Sí, ¡Vaciar!',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.isConfirmed) {
        $("#vaciarServicio").submit();
      }
    });
  }

  $(document).ready(function() {
    // DataTable Initialization
    const table = $('#tabla_servicios').DataTable({
      "responsive": true,
      "autoWidth": false,
      "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json"
      },
      "order": [
        [5, "asc"]
      ] // Ordenar por fecha por defecto
    });

    // Ocultar columna de fecha para el filtro
    table.column(7).visible(false);

    // Custom search function for date range
    $.fn.dataTable.ext.search.push(
      function(settings, data, dataIndex) {
        let min = $('#min-date').val();
        let max = $('#max-date').val();
        let date = data[7] || 0; // Usar la columna oculta con formato YYYY-MM-DD

        if (
          (min === "" || max === "") ||
          (date >= min && date <= max)
        ) {
          return true;
        }
        return false;
      }
    );

    // Re-draw the table when the date range filter changes
    $('.date-range-filter').change(function() {
      table.draw();
    });

    // Trigger initial draw
    table.draw();
  });
</script>
