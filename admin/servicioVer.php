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
              <div class="admin-galeria-container">
                <!-- Slider Principal -->
                <div class="admin-slider-principal mb-3">
                  <div id="carouselAdminServicio" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                      <?php if (count($fotos) > 0) : ?>
                        <?php foreach ($fotos as $index => $foto) : ?>
                          <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                            <img src="classes/imgServicio/<?= $foto["ruta"] ?>" class="d-block w-100" alt="Foto del servicio" style="max-height: 400px; object-fit: cover; border-radius: 6px;">
                          </div>
                        <?php endforeach; ?>
                      <?php else : ?>
                        <div class="carousel-item active">
                          <div class="bg-light d-flex align-items-center justify-content-center" style="height: 300px; border-radius: 6px;">
                            <p class="text-muted">No hay fotos disponibles</p>
                          </div>
                        </div>
                      <?php endif; ?>
                    </div>
                    
                    <?php if (count($fotos) > 1) : ?>
                    <a class="carousel-control-prev" href="#carouselAdminServicio" role="button" data-slide="prev">
                      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    </a>
                    <a class="carousel-control-next" href="#carouselAdminServicio" role="button" data-slide="next">
                      <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    </a>
                    <?php endif; ?>
                  </div>
                </div>

                <!-- Thumbnails/Previsualizaciones -->
                <?php if (count($fotos) > 1) : ?>
                <div class="admin-galeria-thumbnails">
                  <div class="row g-2">
                    <?php foreach ($fotos as $index => $foto) : ?>
                      <div class="col-auto">
                        <a href="#carouselAdminServicio" data-slide-to="<?= $index ?>" class="thumbnail-item-admin <?= $index === 0 ? 'active' : '' ?>" style="cursor: pointer;">
                          <img src="classes/imgServicio/<?= $foto["ruta"] ?>" alt="Miniatura <?= $index ?>" class="img-thumbnail" style="width: 90px; height: 90px; object-fit: cover; border: 2px solid #ddd; transition: all 0.3s; border-radius: 4px;">
                        </a>
                      </div>
                    <?php endforeach; ?>
                  </div>
                </div>
                <?php endif; ?>
              </div>

              <style>
                .admin-galeria-thumbnails .thumbnail-item-admin {
                  display: inline-block;
                  text-decoration: none;
                }
                .admin-galeria-thumbnails .thumbnail-item-admin.active img {
                  border-color: #007bff !important;
                  box-shadow: 0 0 8px rgba(0, 123, 255, 0.5);
                }
                .admin-galeria-thumbnails .thumbnail-item-admin img:hover {
                  border-color: #007bff !important;
                  opacity: 0.9;
                }
              </style>

              <script>
              document.addEventListener('DOMContentLoaded', function() {
                const carousel = document.querySelector('#carouselAdminServicio');
                const thumbnails = document.querySelectorAll('.thumbnail-item-admin');
                
                if (carousel && thumbnails.length > 0) {
                  carousel.addEventListener('slid.bs.carousel', function(e) {
                    thumbnails.forEach(t => t.classList.remove('active'));
                    if (thumbnails[e.to]) {
                      thumbnails[e.to].classList.add('active');
                    }
                  });
                  
                  thumbnails.forEach((thumb, index) => {
                    thumb.addEventListener('click', function(e) {
                      e.preventDefault();
                      $(carousel).carousel(index);
                    });
                  });
                }
              });
              </script>
            </div>
            <div class="col-12 col-lg-4">
              <div class="bg-light p-3 rounded">
                <div class="d-flex justify-content-between align-items-center">
                  <h3 class="text-primary font-weight-bold mb-0"><i class="fas fa-list-alt"></i> Detalles</h3>
                  <button class="btn btn-sm btn-outline-primary" type="button" data-toggle="collapse" data-target="#detallesServicio" aria-expanded="false" aria-controls="detallesServicio">
                    <i class="fas fa-chevron-down"></i> Ver
                  </button>
                </div>
                <hr>
                <div class="collapse" id="detallesServicio">
                  <div class="text-muted mt-4">
                    <p class="text-sm"><strong><?= $lang["categoria_del_servicio"]; ?></strong>
                      <span class="d-block"><?= $categoria[0]["nombre_categoria_servicio"] ?></span>
                    </p>
                        <p class="text-sm"><strong>Descripcion</strong>
                      <span class="d-block"><?= $servicio["descripcion_servicio"] ?></span>
                    </p>
                    <p class="text-sm"><strong><?= $lang["observaciones"]; ?></strong>
                      <span class="d-block"><?= $servicio["observaciones"] ?: 'N/A'; ?></span>
                    </p>
                  </div>
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
                <input type="date" id="max-date" class="form-control date-range-filter" value="<?= date('Y-m-d', strtotime('+90 days')) ?>">
              </div>
            </div>
          </div>
          <div class="table-responsive">
            <table id="tabla_servicios" class="table table-bordered table-striped" style="width: 100%;">
              <thead>
                <tr>
                  <th>Fecha</th>
                  <th>Disponibles</th>
                  <th>Reservados</th>
                  <th>Periodo</th>
                  <th>ID Salida</th>
                  <th>Acción</th>
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

                if (empty($salidas)) {
                  echo '<tr><td colspan="7" class="text-center text-muted py-3"><i class="fas fa-info-circle mr-2"></i>No hay salidas programadas para este servicio</td></tr>';
                } else {
                  foreach ($salidas as $salida) {
                    $idServicioSalidas = $salida["idServicioSalidas"];
                    $cantPasajeros = getCantidadPasajerosIdReservaTarifas($idServicioSalidas);
                  ?>
                    <tr>
                      <td data-sort="<?= strtotime($salida["fecha"] . " " . $salida["horaSalida"]) ?>">
                        <?php 
                          $diasSemana = array('Sunday' => 'Domingo', 'Monday' => 'Lunes', 'Tuesday' => 'Martes', 'Wednesday' => 'Miércoles', 'Thursday' => 'Jueves', 'Friday' => 'Viernes', 'Saturday' => 'Sábado');
                          $diaSemana = date('l', strtotime($salida["fecha"]));
                          echo $diasSemana[$diaSemana];
                        ?>, <?= date("d/m/Y", strtotime($salida["fecha"])) ?> - <?= $salida["horaSalida"] ?>
                      </td>
                      <td>
                        <input type="number" class="form-control disponibles-input" data-id="<?= $idServicioSalidas ?>" value="<?= $salida["disponibilidad"]; ?>" min="0" style="width: 80px; text-align: center;" data-original="<?= $salida["disponibilidad"]; ?>">
                      </td>
                      <td><span class="badge bg-info"><?= $cantPasajeros; ?></span></td>
                      <td><?= htmlspecialchars($salida["nombre"]); ?></td>
                      <td><?= $idServicioSalidas; ?></td>
                      <td>
                        <form method="post" action="salidaVer.php">
                          <button class="btn btn-primary btn-sm" name="idServicioSalidas" value="<?= $idServicioSalidas; ?>">
                            <i class="fas fa-eye mr-1"></i> <?= $lang["ver"]; ?>
                          </button>
                        </form>
                      </td>
                      <td>
                           <form method="post" action="pasajerosLista">

              <button name="idServicioSalidas" value="<?= $idServicioSalidas; ?>" class="btn btn-xs btn-success"> <i class="fas fa-eye mr-1"></i> <?= $lang["lista_de_pasajeros"]; ?></button>

            </form>
                      </td>
                      <td class="d-none"><?= date('Y-m-d', strtotime($salida['fecha'])) ?></td>
                    </tr>
                  <?php 
                  }
                }
                ?>
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

    // Manejo de edición de "Disponibles"
    let actualizandoDisponibilidad = false;
    
    $('.disponibles-input').on('change', function() {
      // Evitar múltiples llamadas simultáneas
      if (actualizandoDisponibilidad) {
        return;
      }

      const idSalida = $(this).data('id');
      const nuevoValor = parseInt($(this).val());
      const originalValue = parseInt($(this).data('original'));
      const $input = $(this);

      // Validación
      if (isNaN(nuevoValor) || nuevoValor < 0) {
        Swal.fire('Error', 'El valor no puede ser negativo', 'error');
        $input.val(originalValue);
        return;
      }

      // No hacer nada si no hay cambio
      if (nuevoValor === originalValue) {
        return;
      }

      actualizandoDisponibilidad = true;

      // AJAX para guardar
      $.ajax({
        type: 'POST',
        url: 'ajax/actualizar_disponibilidad.php',
        data: {
          idServicioSalidas: idSalida,
          disponibilidad: nuevoValor
        },
        dataType: 'json',
        success: function(response) {
          if (response.success) {
            $input.data('original', nuevoValor);
            
            // Mensaje personalizado usando la diferencia real del servidor
            let mensaje = '';
            const diferencia = response.diferencia || 0;
            
            if (diferencia > 0) {
              mensaje = `Se agregaron ${Math.abs(diferencia)} lugar${Math.abs(diferencia) > 1 ? 'es' : ''}`;
            } else if (diferencia < 0) {
              mensaje = `Se quitaron ${Math.abs(diferencia)} lugar${Math.abs(diferencia) > 1 ? 'es' : ''}`;
            } else {
              mensaje = 'Disponibilidad actualizada';
            }
            
            Swal.fire('Éxito', mensaje, 'success');
          } else {
            Swal.fire('Error', response.message || 'No se pudo actualizar', 'error');
            $input.val(originalValue);
          }
        },
        error: function(xhr, status, error) {
          let mensajeError = 'No se pudo actualizar la disponibilidad';
          
          if (xhr.responseJSON && xhr.responseJSON.message) {
            mensajeError = xhr.responseJSON.message;
          } else if (xhr.status === 401) {
            mensajeError = 'Sesión expirada. Por favor, recargue la página';
          } else if (xhr.status === 403) {
            mensajeError = 'No tiene permisos para hacer este cambio';
          } else if (xhr.status === 500) {
            mensajeError = 'Error del servidor. Intente nuevamente';
          }
          
          console.error('Error AJAX:', status, error, xhr.responseText);
          Swal.fire('Error', mensajeError, 'error');
          $input.val(originalValue);
        },
        complete: function() {
          actualizandoDisponibilidad = false;
        }
      });
    });

    // Toggle detalles button icon
    $('#detallesServicio').on('show.bs.collapse', function() {
      const btn = $('button[data-target="#detallesServicio"]');
      btn.find('i').removeClass('fa-chevron-down').addClass('fa-chevron-up');
      btn.find('span').text(' Ocultar') || btn.html('<i class="fas fa-chevron-up"></i> Ocultar');
    });

    $('#detallesServicio').on('hide.bs.collapse', function() {
      const btn = $('button[data-target="#detallesServicio"]');
      btn.find('i').removeClass('fa-chevron-up').addClass('fa-chevron-down');
      btn.find('span').text(' Ver') || btn.html('<i class="fas fa-chevron-down"></i> Ver');
    });

    // Trigger initial draw
    table.draw();
  });
</script>
