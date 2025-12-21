<?php

setlocale(LC_TIME, "es_ES");


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
require("classes/salidas_comisiones.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["borrarSalida"])) {

$idServicioSalidas=$_POST['idServicioSalidas'];
$salida=getSalida($idServicioSalidas);
$salidas=getSalida($idServicioSalidas);
$idServicio=$salida[0]["idServicio"];
$servicio=getServicio($idServicio);
$nombre_servicio=$servicio[0]['nombre_servicio'];
$resu=borraSalida($idServicioSalidas);

if ($resu) {
  alertar("salida eliminada con exito, espere por favor", "success");
  redireccionarLento("servicioVer?idServicio=".$idServicio);
}
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["idServicioSalidas"])) {

$idServicioSalidas=$_POST['idServicioSalidas'];
$salida=getSalida($idServicioSalidas);
$salidas=getSalida($idServicioSalidas);
$idServicio=$salida[0]["idServicio"];
$servicio=getServicio($idServicio);
$nombre_servicio=$servicio[0]['nombre_servicio'];

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

            <li class="breadcrumb-item"><a href="#"><?= $lang["servicio"]; ?> <?= $nombre_servicio; ?></a></li>

            <li class="breadcrumb-item active">Salida #<?=$idServicioSalidas?></li>

          </ol>

        </div><!-- /.col -->

      </div><!-- /.row -->

    </div><!-- /.container-fluid -->

  </div>













  <section class="content">



    <!-- Default box -->

    <div class="card">

      <div class="card-header">

        <h3 class="card-title"><?= $lang["salidas"]; ?> #<?=$idServicioSalidas;?> <?= $nombre_servicio; ?></h3>



        <div class="card-tools">

          <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">

            <i class="fas fa-minus"></i></button>



        </div>

      </div>

      <div class="card-body" >

        <div class="row">



          <div class="col-12 col-md-12 col-lg-4 order-1 order-md-2">

            <h3 class="text-primary"><?= $lang["salidas"]; ?> #<?=$idServicioSalidas;?> <?= $nombre_servicio; ?></h3>


          </div>

        </div> <!-- /.row -->

      </div> <!-- /.card-body -->

      <div class="col-12 form-inline">
<div class="col-1">
     <form method="post" action="pasajerosLista" target="_blank">

              <button name="idServicioSalidas" value="<?= $idServicioSalidas; ?>" class="btn btn-secondary"><?= $lang["lista_de_pasajeros"]; ?></button>

            </form>
</div>
 
         
<div class="col-1">
   <form method="post" action="salidasEditar">

              <button name="idServicioSalidas" value="<?= $idServicioSalidas; ?>" class="btn btn-info"><?= $lang["editar_salida"]; ?></button>

            </form>

</div>
           
          <div class="col-1">
              <form method="post" action="salidasEditaXPack">

              <button name="idServicioSalidas" value="<?= $idServicioSalidas; ?>" class="btn btn-info">Edita X Pack</button>

            </form>
          </div>
           


        



        <script type="text/javascript">
          function confirm1() {

            Swal.fire({
              title: 'Esta seguro que desea borrar la salida?',

              showCancelButton: true,
              confirmButtonText: `Si, Borrar`,
              denyButtonText: `No`,
            }).then((result) => {
              /* Read more about isConfirmed, isDenied below */
              if (result.isConfirmed) {
                $("#borraSalida").submit();
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







  <!-- Default box -->

  <?php


  for ($i = 0; $i < count($salidas); $i++) {

    $idMoneda = ($salidas[0]["idMoneda"]);

    //print_r($salidas[0]);

    $moneda = getMoneda($idMoneda);

    $idServiciosSalidasPack = $salidas[0]["idServiciosSalidasPack"];

    $idServicioSalidas = $salidas[0]["idServicioSalidas"];

    $idiomas = getIdiomasSalida($idServicioSalidas);

    if ($salidas[0]["idPrestador"] > 0) {

      $prestador = getPrestador($salidas[0]["idPrestador"])[0];
    }



$cantidadPasajeros=getCantidadPasajerosIdReservaTarifas($idServicioSalidas);

  ?>



    <section class="content">

      <div class="card" >
        <!--collapsed-card_-->

        <div class="card-header">

          <h3 class="card-title"><?= $salidas[0]["nombre"] ?> <?= strftime("%A", strtotime($salidas[0]["fecha"])) ?> <?= date("d-m-Y", strtotime($salidas[0]["fecha"])) ?> <?= $salidas[0]["horaSalida"] ?></h3>

          <div class="card-tools">
<form method="post" id="borraSalida" onsubmit="return confirm()">
  <input type="hidden" name="idServicioSalidas" value="<?=$idServicioSalidas?>">
  <button class="btn btn-danger" name="borrarSalida"><?= $lang["eliminar_salida"]; ?></button>
</form>
            



          </div>

        </div>



        <div class="card-body" id="collapseSalidas<?=$idServicioSalidas?>">
          <!-- /.card-body -->

          <div class="row">

            <div class="col-12">

              <div class="row">
                <table style="width:100%">
                  <tr>
                    <th><?= $lang["dia_de_la_salida"]; ?></th>
                    <th><?= $lang["fecha_de_salida"]; ?></th>
                    <th><?= $lang["disponibilidad"]; ?></th>
                  </tr>
                  <tr>
                    <td><?= strftime("%A", strtotime($salidas[0]["fecha"])) ?></td>
                    <td><?= date('d-m-Y', strtotime($salidas[0]["fecha"])) ?></td>
                    <td><?= $salidas[0]["disponibilidad"] - $cantidadPasajeros; ?></td>
                  </tr>
                  <tr>
                    <th><?= $lang["horario_de_salida"]; ?></th>
                    <th><?= $lang["horario_de_check_in"]; ?></th>
                    <th><?= $lang["nota_de_salida"]; ?> (ES)</th>
                    <th><?= $lang["nota_de_salida"]; ?> (EN)</th>
                    <th><?= $lang["nota_de_salida"]; ?> (PT)</th>
                    <th><?= $lang["nota_de_salida"]; ?> (IT)</th>
                  </tr>
                  <tr>
                    <td><?= $salidas[0]["horaSalida"] ?></td>
                    <td><?= $salidas[0]["horaCheckIn"] ?></td>
                    <td><?= $salidas[0]["nota_salida"] ?></td>
                    <td><?= $salidas[0]["nota_salida_en"] ?></td>
                    <td><?= $salidas[0]["nota_salida_pt"] ?></td>
                    <td><?= $salidas[0]["nota_salida_it"] ?></td>
                  </tr>
                </table>


                <button class="btn btn-link collapsed <?= $alerta; ?>" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                </button>

                <button class="btn btn-secondary" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                  Tarifas de Salida
                </button>

                <div class="accordion" id="accordionExample" style="width: 100%;">

<!--arranca adicionales-->
                  <div id="collapseOne" class="" aria-labelledby="headingOne" data-parent="#accordionExample">

                    <div class="card-body">

                      <table class="table table-bordered table-striped" id="tipos_pax_table" style="width:100%">

                        <thead>

                          <tr>

                            <th style="width: 20%;"><?= $lang["origen"]; ?></th>
                            <th> <?= $lang["nombre_tarifa"]; ?></th>
                            <th><?= $lang["desde_anos"]; ?></th>
                            <th><?= $lang["hasta_anos"]; ?></th>
                            <th style="width: 20%;"> <?= $lang["tipo_de_tarifa"]; ?></th>
                            <th> <?= $lang["precio_"]; ?></th>
                            <th> <?= $lang["pago_minimo"]; ?></th>
                            <th> <?= $lang["tipo_de_cancelacion"]; ?></th>
                                  <th> Comision</th>
                            <th></th>

                          </tr>

                        </thead>

                        <tbody>

                          <?php

                          $tarifas = getTarifas($idServicioSalidas);

                          for ($j = 0; $j < count($tarifas); $j++) {

                            $edadFrom = getEdad($tarifas[$j]["idFromEdad"])[0];
                            $edadTo = getEdad($tarifas[$j]["idToEdad"])[0];
                            $tipoTarifa = getTipoTarifa($tarifas[$j]["idTipoTarifa"])[0];
                            $idServicioSalidasTarifas = $tarifas[$j]["idServicioSalidasTarifas"];
                            $ubicacion = getUbicacionIdTarifa($idServicioSalidasTarifas);
                            $idCancelacion = $tarifas[$j]["idCancelaciones"];
                            $cancelacion = getTipoCancelaciones($idCancelacion);
$comisiones=getComisionSalidaTarifa($idServicioSalidasTarifas);

                          ?>

                            <tr>
                              <td><?= $ubicacion[0]["direccion"]; ?></td>
                              <td><?= $tarifas[$j]['nombre']; ?></td>
                              <td><?= $edadFrom['valor']; ?></td>
                              <td><?= $edadTo['valor']; ?></td>
                              <td><?= $tipoTarifa["nombre"]; ?></td>
                              <td><?= $moneda[0]["Symbol"] . $tarifas[$j]["valor"]; ?></td>
                              <td><?= $moneda[0]["Symbol"] . $tarifas[$j]["minimo"]; ?></td>
                              <td><?= $cancelacion[0]["texto"] ?></td>
                                 <td>Vendedor: <?= $comisiones[0]["valor"] ?></td>
                              <td>
    <a href="tarifasEditor?idServicioSalidasTarifas=<?=$idServicioSalidasTarifas;?>" class="btn-sm btn-info">Editar</a>
                                  <?php //print_r($adicionales[$j]) ?></td>
                            </tr>
                          <?php

                          } ?>

                        </tbody>
                      </table>
                    </div>
                  </div>

                  <?php $adicionales = getServiciosAdicionalesSalidaNoIncluidos($idServicioSalidas);
                  $adicionalesIncluidos = getServiciosAdicionalesSalidaIncluidos($idServicioSalidas);
                  $cantidadServiciosAdicionales = count($adicionales) + count($adicionalesIncluidos);
                  $alerta = "";
                  $msj = " Servicios Adicionales Salida (" . $cantidadServiciosAdicionales . ")";
                  if ($cantidadServiciosAdicionales == 0) {
                    $alerta = "alert alert-danger";
                    $msj = " SIN SERVICIOS ADICIONALES CARGADOS!!!";
                  }
                  ?>

            





                

                  <div class="card-header">
                    <h5 class="mb-0">
    
                    </h5>

                  </div>  
                    <div id="collapseAdicionales<?= $idServicioSalidas; ?>" class="card">
                      <div class="card-body">
                        <table class="table table-bordered table-striped table-hover"  style="width:100%">
                          <thead>
                            <tr colspan="2">      <th>
                              <?=$msj;?>
                            </th >
                             <th colspan="3"> <!-- <form method="post" action="altaServiciosAdicionales"><button name="idServicioSalidas" value="<?= $idServicioSalidas; ?>" class="btn-sm btn-success"><?= $lang["agregar_servicios_adicionales"]; ?></button></form>-->

                              
                          <form method="post" action="serviciosAdicionalesSalidaEdita">

                <button name="idServicioSalidas" value="<?= $idServicioSalidas; ?>" class="btn btn-warning">Editar Adicionales</button>

              </form>
                                    </th>

                            </tr>
                            <tr>
                              <th style="width: 20%;"><?= $lang["nombre"]; ?></th>
                              <th><?= $lang["descripcion"]; ?></th>
                              <th><?= $lang["valor"]; ?></th>
                              <th> </th>
                            </tr>
                          </thead>

                          <?php if ($cantidadServiciosAdicionales > 0) {

                  ?>
                          <tbody>
                            <?php
                            for ($j = 0; $j < count($adicionales); $j++) {
                            ?>
                              <tr>
                                <td><?= $adicionales[$j]["nombre"]; ?></td>
                                <td><?= $adicionales[$j]["descripcion"]; ?></td>
                                <td><?= $adicionales[$j]["valor"]; ?></td>
               
                              </tr>
                            <?php
                            } ?>
                            <?php
                            for ($j = 0; $j < count($adicionalesIncluidos); $j++) {
                            ?>
                              <tr>
                                <td><?= $adicionalesIncluidos[$j]["nombre"]; ?></td>
                                <td colspan="3"><?= $adicionalesIncluidos[$j]["descripcion"]; ?></td>
                              </tr>
                            <?php
                            } ?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  <?php } ?>



                </div>





              </div>

            </div>









          </div> <!-- /.card-body -->


        </div><!-- /.card collapsed-card del periodo-->

      <?php } ?>
<form method="get" ><a class="btn-lg btn-info" href="servicioVer.php?idServicio=<?=$idServicio;?>">Voltar </a></form>
</section>
</div>


      <!-- Content Header (Page header) -->





















      <!-- SELECT2 EXAMPLE -->

      <!-- SELECT2 EXAMPLE -->
