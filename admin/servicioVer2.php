<?php
// Verificar permisos de acceso ANTES de cualquier salida
require_once(__DIR__ . "/classes/permisos.php");
require_once(__DIR__ . "/includes/permisos_helper.php");
$permisos = new PermisosManager($GLOBALS['pdo'], $_SESSION['login'] ?? []);
$permisos->verificarAcceso('servicioVer2');

setlocale(LC_TIME, "es_ES");


$fecha_actual = date("d-m-Y");
include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");
require_once("classes/functions.php");
require_once("classes/categoria.php");
require_once("classes/texto_miniaturas.php");
require_once("classes/tipos_tarifa.php");
require_once("classes/accesibilidad.php");
require_once("classes/idiomas.php");
require_once("classes/reserva.php");
require_once("classes/edades.php");
require_once("classes/salidas.php");
require_once("classes/tarifas.php");
require_once("classes/tarifas_ubicacion.php");
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

if (isset($_GET["idServicio"])) {

  $idServicio = $_GET["idServicio"];

  $servicio = getServicio($idServicio)[0];

  $fotos = getFotoMiniaturaServicio($idServicio);

  $categoria = getCategoria($servicio["idCategoria_servicio"]);
}

?>









<div class="content-wrapper">
  <!-- Content Wrapper. Contains page content -->



  <div class="content-header">
    <!-- Content Header (Page header) -->

    <div class="container-fluid">

      <div class="row mb-2">

        <div class="col-sm-6">

          <h1 class="m-0 text-dark"></h1>



        </div><!-- /.col -->

        <div class="col-sm-6">

          <ol class="breadcrumb float-sm-right">

            <li class="breadcrumb-item"><a href="#"><?= $lang["servicio"]; ?> <?= $servicio["nombre_servicio"]; ?></a></li>

            <li class="breadcrumb-item active"></li>

          </ol>

        </div><!-- /.col -->

      </div><!-- /.row -->

    </div><!-- /.container-fluid -->

  </div>













  <section class="content">



    <!-- Default box -->

    <div class="card">

      <div class="card-header">

        <h3 class="card-title"><?= $lang["servicio"]; ?> <?= $servicio["nombre_servicio"]; ?></h3>



        <div class="card-tools">

          <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">

            <i class="fas fa-minus"></i></button>



        </div>

      </div>

      <div class="card-body">

        <div class="row">

          <div class="col-12 col-md-12 col-lg-8 order-2 order-md-1">

            <div class="row">

              <?php



              for ($i = 0; $i <  count($fotos); $i++) {

              ?>

                <div class="col-12 col-sm-4">

                  <img class="info-box bg-light" src="classes/imgServicio/<?= $fotos[$i]["ruta"] ?>" style="width: 150px;">





                </div>

              <?php

              } ?>

              <!--Fotos -->

            </div>





            <div class="row">

              <div class="col-12">



                <div class="post">

                  <div class="user-block">

                    <span class="description"><?= $lang["fecha_alta"]; ?> <?= date("d-m-Y", strtotime($servicio["fechaAlta"])) ?></span>

                  </div>

                  <!-- /.user-block -->

                  <p>
                   <?= $lang["descripcion_del_servicio"]; ?>
                 </p>



                </div>



              </div>

            </div>

          </div>

          <div class="col-12 col-md-12 col-lg-4 order-1 order-md-2">

            <h3 class="text-primary"><i class="fas fa-paint-brush"></i> <?= $servicio["nombre_servicio"]; ?></h3>

            <p class="text-muted"><?= $servicio["descripcion_corta"]; ?></p>

            <br>

            <div class="text-muted">
              <p class="text-sm"><?= $lang["categoria_del_servicio"]; ?>
                <b class="d-block"><?= $categoria[0]["nombre_categoria_servicio"] ?></b>
              </p>
              <p class="text-sm"><?= $lang["observaciones"]; ?>
                <b class="d-block"><?= $servicio["observaciones"]; ?></b>
              </p>
            </div>
          </div>

        </div> <!-- /.row -->

      </div> <!-- /.card-body -->

      <div class="col-12 form-inline">

        <form method="post" action="altaSalidas" style="padding: 3px;"><button name="idServicio" value="<?= $idServicio; ?>" class="btn btn-info"><?= $lang["agregar_salida"]; ?> </button></form>
        <?php if ($_SESSION['login']["idUsuario"] == 1) { ?>
          <form method="post" action="servicioOpiniones" style="padding: 3px;">
            <button name="idServicio" value="<?= $idServicio; ?>" class="btn btn-success"><?= $lang["opiniones"]; ?></button>
          </form>
          <form method="get" action="altaServicio.php" style="padding: 3px;">
            <input type="hidden" name="idServicio" value="<?= $idServicio; ?>">
            <button class="btn btn-primary" type="submit"><?= $lang["editar_servicio"]; ?></button>
          </form>


        <?php } ?>

        <?php



         if ($_SESSION['login']["idUsuario"] == 1) {
        ?>
          <form method="post" action="servicioFotos" style="padding: 3px;">
            <button type="submit" class="btn btn-success" name="idServicio" value="<?= $idServicio ?>">Editor de fotos</button>
          </form>

          <form method="post" action="servicioComisionPrestador" style="padding: 3px;">
            <button type="submit" class="btn btn-info" name="idServicio" value="<?= $idServicio ?>"><?= $lang["comision_inicial_prestador"]; ?></button>
          </form>

          <form method="post" id="borraServicio" style="padding: 3px;">
            <input type="hidden" name="eliminarServicio" value="<?= $idServicio; ?>">
            <a class="btn btn-danger" name="eliminarServicio" value="<?= $idServicio; ?>" onclick="confirm1()"><?= $lang["eliminar_servicio"]; ?></a>
          </form>
        <?php
        } ?>



        <script type="text/javascript">
          function confirm1() {

            Swal.fire({
              title: 'Esta seguro que desea borrar el servicio, salidas y tarifas?',

              showCancelButton: true,
              confirmButtonText: `Si, Borrar`,
              denyButtonText: `No`,
            }).then((result) => {
              /* Read more about isConfirmed, isDenied below */
              if (result.isConfirmed) {
                $("#borraServicio").submit();
              } else if (result.isDenied) {
                return false
              }
            })
            return false
          }
        </script>



      </div>

    </div> <!-- /.card. -->





  </section> <!-- Main content -->






    <section class="content">
     <div class="" >

<table  id="tabla_servicios" class="table table-bordered table-striped" style="width: 100%;">
  <thead>
    <div class="input-group input-daterange">

      <input type="date" id="min-date" class="form-control date-range-filter" data-date-format="yyyy-mm-dd" placeholder="From:" value="<?=date('Y-m-d')?>">

      <div class="input-group-addon">to</div>

      <input type="date" id="max-date" class="form-control date-range-filter" data-date-format="yyyy-mm-dd" placeholder="To:" value="<?=date('Y-m-d',strtotime($fecha_actual.'+ 1 days')); ?>">

    </div>
    <tr>
      <th>idSalida</th>
       <th>Nombre</th>
        <th>Fecha</th>
         <th>Accion</th>
    </tr>
  </thead>
  <tbody>

  <!-- Default box -->

  <?php

  if ($_SESSION['login']["idUsuario"] == 1) {
    $salidas = getAllSalidasServicio($idServicio);
  } else {
    $salidas = getSalidasServicioIdPrestador($idServicio);
  }




  for ($i = 0; $i < count($salidas); $i++) {

    $idMoneda = ($salidas[$i]["idMoneda"]);

    //print_r($salidas[$i]);

    $moneda = getMoneda($idMoneda);

    $idServiciosSalidasPack = $salidas[$i]["idServiciosSalidasPack"];

    $idServicioSalidas = $salidas[$i]["idServicioSalidas"];

    $idiomas = getIdiomasSalida($idServicioSalidas);

    if ($salidas[$i]["idPrestador"] > 0) {

      $prestador = getPrestador($salidas[$i]["idPrestador"])[0];
    }



$idServicioSalidas=$salidas[$i]["idServicioSalidas"];

  ?>


    <tr>
              <td><?= $idServicioSalidas; ?></td>
      <td><?= $salidas[$i]["nombre"] ?></td>

          <td data-filter="<?= date('Y-m-d', strtotime($salidas[$i]['fecha'])) ?> "><?= strftime("%A", strtotime($salidas[$i]["fecha"])) ?> <?= date("d-m-Y", strtotime($salidas[$i]["fecha"])) ?> <?= $salidas[$i]["horaSalida"] ?></td>
            <td>
              <form method="post" action="salidaVer.php">
                <button class="btn btn-primary" data-toggle="collapse" data-target="#collapseSalidas<?=$idServicioSalidas?>" aria-expanded="false" aria-controls="collapseSalidas<?=$idServicioSalidas?>" name="idServicioSalidas" value="<?=$idServicioSalidas;?>"><?= $lang["ver"]; ?></button>


              </form>
                    
            </td>
    </tr>



      <?php } ?>

  </tbody>
</table>



<script type="text/javascript">
  
 var table = $('#tabla_servicios').DataTable({
      "paging": true,
      "stateSave": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true
    });

$.fn.dataTable.ext.search.push(
  function(settings, data, dataIndex) {
    var min = $('#min-date').val();
    var max = $('#max-date').val();

    var createdAt = data[2] || 0; // Our date column in the table

    if (
      (min == "" || max == "") ||
      (moment(createdAt).isSameOrAfter(min) && moment(createdAt).isSameOrBefore(max))
    ) {
      return true;
    }
    return false;
  }
);

// Re-draw the table when the a date range filter changes
$('.date-range-filter').change(function() {
  table.draw();

});
  table.draw();
$('#my-table_filter').hide();
</script>
      <!-- Content Header (Page header) -->






</div>
</section>














      <!-- SELECT2 EXAMPLE -->

      <!-- SELECT2 EXAMPLE -->