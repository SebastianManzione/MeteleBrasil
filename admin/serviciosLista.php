<?php 

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
 require("classes/servicio.php"); 
 require("classes/fotos_servicio.php"); 

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["desHabilitarServicio"]) ) {
$resul=quitarDestacarServicio($_POST["desHabilitarServicio"]);
$resul=desHabilitarServicio($_POST["desHabilitarServicio"]);

if ($resul) {
  alertar("Servicio destacado con exito", "success");
}


}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["habilitarServicio"]) ) {



$resul=habilitarServicio($_POST["habilitarServicio"]);
if ($resul) {
  alertar("Servicio destacado con exito", "success");
}


}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["destacarServicio"]) ) {



$resul=destacarServicio($_POST["destacarServicio"]);
if ($resul) {
  alertar("Servicio destacado con exito", "success");
}



}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["quitarDestacarServicio"]) ) {



$resul=quitarDestacarServicio($_POST["quitarDestacarServicio"]);
if ($resul) {
  alertar("Servicio destacado con exito", "success");
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

                    <h1 class="m-0 text-dark"><?=$lang["lista_de_servicios"];?></h1>



                </div><!-- /.col -->

                <div class="col-sm-6">

                    <ol class="breadcrumb float-sm-right">

                        <li class="breadcrumb-item"><a href="#"><?=$lang["servicios"];?></a></li>

                        <li class="breadcrumb-item active"><?=$lang["lista_de_servicios"];?></li>

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

                <h3 class="card-title"><?=$lang["lista_de_servicios"];?></h3>



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

                  
                      <th><?=$lang["nombre"];?></th>

                      <th><?=$lang["fecha_alta"];?></th>
                      <th><?=$lang["destino"];?></th>
                      <th><?=$lang["foto"];?></th>

                           <?php  if($_SESSION["login"]["rol"]==1){ ?> <th scope="col"><?=$lang["acción"];?></th><?php  } ?>

                      <th><?=$lang["detalles"];?></th>

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

                      $fotos=getFotoMiniaturaServicio($idServicio);

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

                                <button class="btn btn-sm btn-success" name="habilitarServicio" value="<?=$idServicio?>"><?=$lang["habilitar"];?></button>
                              

                                <?php }

                                else{

                                  ?><button class="btn btn-sm btn-warning" name="desHabilitarServicio" 

                                   value="<?=$idServicio;?>"><?=$lang["deshabilitar"];?></button>

                                     <?php if ($servicios[$i]["destacado"]==1) {
  ?> <button class="btn-sm btn-primary" name="quitarDestacarServicio"  value="<?=$idServicio;?>"  >Quitar Destacado</button> <?php
} else{ ?>

<button class="btn-sm btn-secondary" name="destacarServicio" value="<?=$idServicio;?>" >Destacar</button>
<?php } ?>

                               <?php }?>    </form><br>


                             </td>

<?php  } ?>

                    <td><a href="servicioVer.php?idServicio=<?=$idServicio;?>" class="btn btn-success"><?=$lang["ver"];?></a></td> 



                  

                  </tr>

                    <?php

                     } ?>

             

           

                  </tbody>

                </table>

<script type="text/javascript">
  
   $('#tabla_servicios').DataTable({
      "paging": true,
      "stateSave": true,
      "lengthChange": true,
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