<?php 

include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");
require("classes/functions.php");
require("classes/categoria.php");
require("classes/texto_miniaturas.php");
require("classes/tipos_tarifa.php");
require("classes/accesibilidad.php");
require("classes/idiomas.php");
require("classes/monedas.php");require("classes/edades.php");
 //$moneda=getMoneda($_SESSION["nuevoServicio"]["selMoneda"]);
//print_r($_POST);
if (isset($_SESSION['altaServicio'])) {
    require("classes/servicio.php");
        require("classes/fotos_servicio.php");
 $servicio=getServicio($_SESSION["altaServicio"]);
alertar("Seccion en construccion, regrese mas tarde...","warning");
//print_r($servicio);
$idServicio=$servicio[0]['idServicio'];
$fotos=getFotosServicio($idServicio);

}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  print_r($_POST);
  $cantDias=calculaDias($_POST["inicio_periodo"],$_POST["fin_periodo"]);
echo "Cant Salidas:".$cantDias. " || <br>";
for ($i=0; $i < $cantDias; $i++) { 
  $fechaSalida=SumaFecha($_POST["inicio_periodo"], $i);
  $diaSemana=retornaDiaSemana($fechaSalida);
$cantLugares=$_POST["cantLugares"];
$hSalida=$_POST["hSalida"];
$horaCheckIn=$_POST["horaCheckIn"];


  if (isset($_POST["dom"]) && $diaSemana==0) {
    echo "Salida Fecha: ".$fechaSalida. " dia domingo, Hora salida:  ".$hSalida. " hora check in: ".$horaCheckIn;
    echo("PÄX de la salida: ".$cantLugares." || <br>");
  }
  if (isset($_POST["lun"]) && $diaSemana==1) {
    echo "Salida Fecha: ".$fechaSalida. " dia lunes, Hora salida:  ".$hSalida. " hora check in: ".$horaCheckIn;
    echo("PÄX de la salida: ".$cantLugares." || <br>");
  }
  if (isset($_POST["mar"]) && $diaSemana==2) {
    echo "Salida Fecha: ".$fechaSalida. " dia martes, Hora salida:  ".$hSalida. " hora check in: ".$horaCheckIn;
    echo("PÄX de la salida: ".$cantLugares." || <br>");
  }
  if (isset($_POST["mie"]) && $diaSemana==3) {
    echo "Salida Fecha: ".$fechaSalida. " dia miercoles, Hora salida:  ".$hSalida. " hora check in: ".$horaCheckIn;
    echo("PÄX de la salida: ".$cantLugares." || <br>");
  }
    if (isset($_POST["jue"]) && $diaSemana==4) {
    echo "Salida Fecha: ".$fechaSalida. " dia jueves, Hora salida:  ".$hSalida. " hora check in: ".$horaCheckIn;
    echo("PÄX de la salida: ".$cantLugares." || <br>");
  }
    if (isset($_POST["vie"]) && $diaSemana==5) {
    echo "Salida Fecha: ".$fechaSalida. " dia viernes, Hora salida:  ".$hSalida. " hora check in: ".$horaCheckIn;
    echo("PÄX de la salida: ".$cantLugares." || <br>");
  }
    if (isset($_POST["sab"]) && $diaSemana==6) {
    echo "Salida Fecha: ".$fechaSalida. " dia sabado, Hora salida:  ".$hSalida. " hora check in: ".$horaCheckIn;
    echo("PÄX de la salida: ".$cantLugares." || <br>");
  }


}

}


 ?>
 <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Carga de Tarifas <?= $servicio[0]['nombre_servicio']?></h1>

                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Textos Destacados</a></li>
                        <li class="breadcrumb-item active">Lista Textos Destacados</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <section class="content">
        <div class="container-fluid">
            <!-- SELECT2 EXAMPLE -->
            <!-- SELECT2 EXAMPLE -->
           
                                    
 <hr color="green" size=0.5 width="100%"> 


 <div class="card-header">
                <h3 class="card-title">Lista de Texto en Miniatura</h3>

                <div class="card-tools">
                  <div class="input-group input-group-sm" style="width: 150px;">
                    <input type="text" name="table_search" class="form-control float-right" placeholder="Search">

                    <div class="input-group-append">
                      <button type="submit" class="btn btn-default">
                        <i class="fas fa-search"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
   <!-- /.card -->

      
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example2" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                      <th>Texto Destacado Miniatura</th>
                     
                  </tr>
                  </thead>
                  <tbody>
                  <tr>
                    <td>Promoción</td>
                    </tr>
                    
                  <tr>
                    <td>Ahorre</td>
                   
                  </tr>
                  <tr>
                    <td>Aproveche</td>
                
                  </tr>
                  <tr>
                    <td>50% Descuento</td>
                    
                  </tr>
                  <tr>
                    <td>Recomendable</td>
                    
                  </tr>
                  <tr>
                    <td>Destacado</td>
                    
                  </tr>
                  <tr>
                    <td>Promocion Especial</td>
                    
                  
                  </tbody>
                  <tfoot>
                  <tr>
                      <th>Idioma</th>
                     
                  </tr>
                  </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
              
<!-- /.card-body -->


<div id="seat-map">
  <div class="front-indicator">Front</div>
</div>

<div class="booking-details">
  <h2>Booking Details</h2>
  <h3> Selected Seats (<span id="counter">0</span>):</h3>
  <ul id="selected-seats">
  </ul>
  Total: <b>$<span id="total">0</span></b>
  <button class="checkout-button">Checkout &raquo;</button>
  <div id="legend"></div>
</div>






              <!-- /.card-body -->
              <div class="card-footer clearfix">
                <button type="button" class="btn btn-info float-right"><i class="fas fa-plus"></i> Adicionar Texto Miniatura</button>
              </div>
            </div>
            <!-- /.card -->
          </section>
          <!-- /.Left col -->
          <!-- right col (We are only adding the ID to make the widgets sortable)-->
          <section class="col-lg-5 connectedSortable">

            <!-- /.card -->
          </section>
          <!-- right col -->
        </div>
        <!-- /.row (main row) -->
      </div><!-- /.container-fluid -->
   
  <?php 
  include("includes/footer.php"); ?>
                </form>
            </div>
        </div>
          </div>
            </div>
    </section>
</div>
<script type="text/javascript">
  $("#formAltaSalidas").submit(function(e){
    e.preventDefault();
  });
  
</script>
    <footer class="main-footer">
      <strong>Copyright &copy; 2014-<?= date("Y");?> <a href="http://sistemanz.com.ar">SisteManz</a>.</strong>
      Todos los derechos reservados.
      <div class="float-right d-none d-sm-inline-block">
        <b>Version</b> 2.01
      </div>
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
  </div>
  <!-- ./wrapper -->

</body>
</html>
