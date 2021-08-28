<?php ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");
require("classes/servicio.php");
require("classes/salidas.php");
require("classes/usuario.php");
require("classes/visitas.php");
require("classes/reserva.php");
require("classes/edades.php");
require("classes/tarifas.php");
require("classes/convierte_monedas.php");require("classes/comprobantes.php");
$idPrestador=($_SESSION['login']['idPrestador']);
 ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Reservate</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Reservate</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3><?= count(getServicios());?></h3>

                <p>Servicios</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
              <a class="small-box-footer">Mas info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3><?=count(getSalidas());?></h3>

                <p>Salidas</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
              <a class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3><?=count(getUsuarios());?></h3>

                <p>Usuarios</p>
              </div>
              <div class="icon">
                <i class="ion ion-person-add"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3><?=count(getVisitas());?></h3>

                <p>Visitas</p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
        </div>
        <!-- /.row -->
        <!-- Main row -->
        <div class="row">
          <!-- Left col -->
          <section class="col-lg-7 connectedSortable">
            <!-- Custom tabs (Charts with tabs)-->
         <!-- TO DO List -->
         <?php $salidas= getSalidasIdPrestadorHoy();

for ($i=0; $i < count($salidas); $i++) { 
  $servicio=getServicio($salidas[$i]['idServicio']);
  $nombre_servicio=$servicio[0]['nombre_servicio'];
  $idServicioSalidas=$salidas[$i]['idServicioSalidas'];
 
 $tarifas=getTarifasReservadas($idServicioSalidas);





 ?>
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="ion ion-clipboard mr-1"></i>
                  Lista de pasajeros salida #<?=$idServicioSalidas?> <?=$nombre_servicio;?> <?=date("d/m/Y", strtotime($salidas[$i]['fecha']))?> <?=$salidas[$i]['horaSalida'];?>
                </h3>

      
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <ul class="todo-list" data-widget="todo-list">
              <table class="table" id="tablaCarrito">
                              <thead>
                                <tr>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Cod-Carrito</th>
                                    <th scope="col">Fecha de contratacion</th>
                                    <th scope="col">Dia del Evento</th>
                                    <th scope="col">C.ServContratados</th>
                                    <th scope="col">Valor Total</th>
                      
                                 </tr>
                              </thead>
                    <tbody>

<?php  
if (count($tarifas)==0) {
  echo "SIN PASAJEROS";
}
 for ($j=0; $j < count($tarifas); $j++) { 
    $fromEdad=getEdad($tarifas[$j]['idFromEdad'])[0]["valor"];

                            $toEdad=getEdad($tarifas[$j]['idToEdad'])[0]["valor"];

                            $idReservaTarifas=$tarifas[$j]['idReservaTarifas'];
                            $idReserva=$tarifas[$j]['idReserva'];
$reserva=getReservaId($idReserva);
$totalDolares=($reserva[0]["total_dolares"]);

$totalComprobantes=getComprobantesIdReservaDolar($idReserva);

      $horario=getReservaHorariosId($tarifas[$j]['idReservaHorarios']);

            $tarifa2=getTarifasReservadasIdTarifa( $idReservaTarifas);
            $valorSinIva=($tarifa2[0]['valorSinIva']);
            $cantidad=($tarifa2[0]['cantidad']); 
            $monedaSel=($tarifa2[0]['monedaSel']);
            $total=$valorSinIva*$cantidad;
            $precio=ConvierteMoneda($monedaSel,$_SESSION["moneda_sel"], $total);
            $diferenciaComprobantesPrecio=$totalDolares-$totalComprobantes;
       
                  $tarifaOriginal=getTarifa($tarifa2[0]['idServicioSalidasTarifas']);
                       $pasajeros=getPasajeros($idReservaTarifas);
                       if ($diferenciaComprobantesPrecio<=0) {
                         // code...
                      
               for ($k=0; $k < count($pasajeros); $k++) { 
                 // code...
               
 

   ?>
                                 <tr>
                                    <td><?=$pasajeros[$k]["nombrePasajero"]." ".$pasajeros[$k]["apellidoPasajero"]?></td>
                                    <td><?=$reserva[0]['codigoAmigable'];?></td>
                                    <td> <?=date("d-m-Y H:i", strtotime($reserva[0]['fechaAlta']));?></td>
                                    <td><?=date("d/m/Y", strtotime($salidas[$i]['fecha']))?></td>
                                    <td><?=count($tarifas);?></td>
                                    <td><?=$_SESSION["moneda_sel_sym"].$total;?></td>
                             

                             </tr>
<?php } } }?>
                        </tbody>
                      </table>
      
                </ul>
              </div>
              <!-- /.card-body -->
              <div class="card-footer clearfix">
            
              </div>
            </div>
            <?php }
 ?>
            <!-- /.card -->
            <!-- /.card -->

 
    
          </section>
          <!-- /.Left col -->
          <!-- right col (We are only adding the ID to make the widgets sortable)-->
          <section class="col-lg-5 connectedSortable">

            <!-- Map card -->
             <!-- /.card -->
          </section>
          <!-- right col -->
        </div>
        <!-- /.row (main row) -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <?php 
  include("includes/footer.php"); ?>