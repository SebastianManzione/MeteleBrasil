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
require("classes/idiomas.php");
require("classes/edades.php");
require("classes/destinos.php");
require("classes/blog.php"); 
require("classes/fotos_blog.php"); 
require("classes/guias.php");

if (!$_SESSION["login"]["rol"]==1) {
  alertar("Usted no tiene acceso a esta seccion del software", "error");
  redireccionarLento("index");
exit();
}





 ?>

 <!-- Content Wrapper. Contains page content -->

<div class="content-wrapper">

    <!-- Content Header (Page header) -->

    <div class="content-header">

        <div class="container-fluid">

            <div class="row mb-2">

                <div class="col-sm-6">

                    <h1 class="m-0 text-dark">Lista de emails Guias</h1>



                </div><!-- /.col -->

                <div class="col-sm-6">

                    <ol class="breadcrumb float-sm-right">

                        <li class="breadcrumb-item"><a href="#">Blog</a></li>

                        <li class="breadcrumb-item active">Lista de emails Guias</li>

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

                <h3 class="card-title">Lista de emails Guias</h3>

              </div>

   <!-- /.card -->



      

              <!-- /.card-header -->

      <div class="card-body">

                <table id="example2" class="table table-bordered table-striped">

                  <thead>

                  <tr>

                      <th>idEmail</th>
                      <th>Nombre</th>
                      <th>Email</th>
                      <th>Telefono</th>
                      <th>Guia de</th> 
                        <th>Mensaje</th> 
                      <th>Fecha Hora</th>
                    
                      
                       <!-- /.Descripcion corta -->

                  </tr>

                  </thead>

                  <tbody>

                    <?php 
              $mails=getEmailsGuias();
                    for ($i=0; $i < count($mails); $i++) { 

                      $idEmailGuia=$mails[$i]["idEmailGuia"];
                      $nombre=$mails[$i]["nombre"];
                      $email=$mails[$i]["email"];
                      $idCategoria_servicio=$mails[$i]["idCategoria_servicio"];
                      $categoria=getCategoria($idCategoria_servicio);
                      $telefono=$mails[$i]["telefono"];
                      $mensaje=$mails[$i]["mensaje"];
                      $fecha_alta=date("d-m-Y H:i", strtotime($mails[$i]["fecha_alta"]));
                    ?>

                  <tr>
                   <td><?=$idEmailGuia?></td>
                    <td><?=$nombre;?></td>
                    <td><?=$email;?></td>
                    <td><?=$telefono;?></td>
                    <td><?=$categoria[0]['nombre_categoria_servicio'];?></td>
                    <td><?=$mensaje;?></td>
                     <td><?=$fecha_alta;?></td>
   
           

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



