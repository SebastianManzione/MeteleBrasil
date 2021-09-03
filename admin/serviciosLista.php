<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



include("includes/header.php");

include("includes/navbar.php");

include("includes/sidebar.php");

require("classes/functions.php");

require("classes/categoria.php");

require("classes/texto_miniaturas.php");

require("classes/tipos_tarifa.php");

require("classes/accesibilidad.php");
require("classes/comision_prestador.php");
require("classes/idiomas.php");

require("classes/edades.php");
require("classes/destinos.php");
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

                    <h1 class="m-0 text-dark">Lista de Servicios</h1>



                </div><!-- /.col -->

                <div class="col-sm-6">

                    <ol class="breadcrumb float-sm-right">

                        <li class="breadcrumb-item"><a href="#">Servicios</a></li>

                        <li class="breadcrumb-item active">Lista de Servicios</li>

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

                <h3 class="card-title">Lista de Servicios</h3>



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

                <table id="tabla_servicios" class="table table-bordered table-striped">

                  <thead>

                  <tr>

                  
                      <th>Nombre</th>

                      <th>Fecha Alta</th>
                      <th>Destino</th>
                      <th>Foto</th>

                           <?php  if($_SESSION["login"]["rol"]==1){ ?> <th scope="col">¿Habilitado?</th>       <?php  } ?>

                      <th>Acciones</th>

                       <!-- /.Descripcion corta -->

                  </tr>

                  </thead>

                  <tbody>

                    <?php 



                   if($_SESSION["login"]["rol"]==1){
                    $servicios=getAllServicios();}
                    else{
                   
                   
                      $servicios=getAllServiciosPrestador($_SESSION["login"]["idPrestador"]);
                    }

                    for ($i=0; $i < count($servicios); $i++) { 

                      $idServicio=$servicios[$i]["idServicio"];

                      $fotos=getFotosServicio($idServicio);

                              $destino=getDestino($servicios[$i]["idDestino"]);        

                      $fechaAlta=$servicios[$i]["fechaAlta"];

                    ?>

    <tr><a ></a>

               <td><?=$servicios[$i]["nombre_servicio"]?></td>

                    

                     <td><?=date("d-m-Y", strtotime($fechaAlta))?></td>
<td><?=$destino[0]["nombre"]?></td>
                      

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

<script type="text/javascript">
  
   $('#tabla_servicios').DataTable({
      "paging": true,
      "stateSave": true,
      "lengthChange": false,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": true,
    });
</script>



                                

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




              <!-- /.card-body -->


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





  <?php 

  include("includes/footer.php"); ?>