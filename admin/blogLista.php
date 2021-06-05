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
 require("classes/fotos_servicio.php"); 



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
                   

                    <div class="input-group-append">
                      <a href="blogAlta" class="btn btn-info">
                        Nuevo Post
                      </a>
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
                      <th>idPost</th>
                      <th>Titulo</th>
                      <th>Destino</th>
                      <th>Desc.Corta</th>  
                      <th>Fecha</th>
                      <th>Acciones</th>
                       <!-- /.Descripcion corta -->
                  </tr>
                  </thead>
                  <tbody>
                    <?php $blog=getArticulosBlog();
                    for ($i=0; $i < count($blog); $i++) { 
                      $idPost=$blog[$i]["idPost"];
                      $titulo=$blog[$i]["titulo"];
                       $idDestino=$blog[$i]["idDestino"];
                       $destino=getDestino($idDestino);
                        $descripcionCorta=$blog[$i]["descripcionCorta"];
                         $contenido=$blog[$i]["contenido"];
                          $tipsYConsejos=$blog[$i]["tipsYConsejos"];
                           $observaciones=$blog[$i]["observaciones"];
                            $idTextoMiniaturasBlog=$blog[$i]["idTextoMiniaturasBlog"];
                            $fecha_alta=date("d-m-Y", strtotime($blog[$i]["fecha_alta"]));
                     
                           

                  
                    ?>
    <tr><a ></a>
                    <td><?=$idPost?></td>
                    <td><?=$titulo;?></td>
                    <td><?=$destino[0]["nombre"];?></td>
                    <td><?=$descripcionCorta;?></td>
                     <td><?=$fecha_alta;?></td>
                      
          <!--  <td><img style="width: 100px;"src="classes/imgServicio/<?=$fotos[0]['ruta']?>"></td>-->

                    <td><a href="../articuloBlog.php?post=<?=$idPost;?>" class="btn btn-success">Ver</a></td> 

                  
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

