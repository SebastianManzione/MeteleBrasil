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
require("classes/edades.php");
 //$moneda=getMoneda($_SESSION["nuevoServicio"]["selMoneda"]);
//print_r($_POST); 
 require("classes/servicio.php"); 
 require("classes/fotos_servicio.php"); 
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["desHabilitarServicio"]) ) {
desHabilitarServicio($_POST["desHabilitarServicio"]);

}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["habilitarServicio"]) ) {

habilitarServicio($_POST["habilitarServicio"]);

}

 ?>
 <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Lista de Articulos</h1>

                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Blog</a></li>
                        <li class="breadcrumb-item active">Lista de Articulos</li>
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
                <h3 class="card-title">Lista de Articulos</h3>

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
                      <th>id Servicio</th>
                      <th>Nombre</th>
                      <th>Fecha Alta</th>
                      <th>Foto</th>
                           <?php  if($_SESSION["login"]["rol"]==1){ ?> <th scope="col">habilitado</th>       <?php  } ?>
                      <th>Acciones</th>
                       <!-- /.Descripcion corta -->
                  </tr>
                  </thead>
                  <tbody>
                    <?php $servicios=getAllServicios();
                    for ($i=0; $i < count($servicios); $i++) { 
                      $idServicio=$servicios[$i]["idServicio"];
                      $fotos=getFotosServicio($idServicio);
                                      
                      $fechaAlta=$servicios[$i]["fechaAlta"];
                    ?>
    <tr><a ></a>
                    <td><?=$idServicio?></td>
                    <td><?=$servicios[$i]["nombre_servicio"]?></td>
                     <td><?=date("d-m-Y", strtotime($fechaAlta))?></td>
                      
            <td><img style="width: 100px;"src="classes/imgServicio/<?=$fotos[0]['ruta']?>"></td>
            <?php  if($_SESSION["login"]["rol"]==1){ ?>
   <td><form method="post"><?php 
                                if($servicios[$i]["habilitado"]==0){ ?>
                                <button class="btn btn-sm btn-success" name="habilitarServicio" value="<?=$idServicio?>">Habilitar</button>
                                <?php }
                                else{
                                  ?><button class="btn btn-sm btn-warning" name="desHabilitarServicio" 
                                   value="<?=$idServicio;?>">Deshabilitar</button>
                               <?php }?>    </form></td>
<?php  } ?>
                    <td><a href="servicioVer.php?idServicio=<?=$idServicio;?>" class="btn btn-success">Ver</a></td> 

                  
                  </tr>
                    <?php
                     } ?>
             
           
                  </tbody>
                </table>


                                
                            </div>              <!-- /.card-body -->
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
                <button type="button" class="btn btn-info float-right"><i class="fas fa-plus"></i> Adicionar Salida</button>
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
