 <?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("includes/navbar.php");

 require("admin/classes/blog.php"); 
require("admin/classes/categoria.php");
require("admin/classes/opiniones_categoria.php");
require("admin/classes/servicio_opiniones.php");
require("admin/classes/texto_miniaturas.php"); 
require("admin/classes/fotos_blog.php");
require("admin/classes/destinos.php");

require("admin/classes/texto_viajeros.php");
     if (isset($_GET["post"]) ) {
                    $idPost=$_GET['post'];
                    $articulo=getArticuloBlog($idPost);
         
             $titulo=$articulo[0]["titulo"];
            $descripcionCorta=$articulo[0]["descripcionCorta"];
              $contenido=$articulo[0]["contenido"];
               $tipsYConsejos=$articulo[0]["tipsYConsejos"];
$observaciones=$articulo[0]["observaciones"];
$idDestino=$articulo[0]["idDestino"];
$destino=getDestino($idDestino);

            $fotos=getFotosBlogIdPost($idPost);  
        
            if (count($articulo)> 1 || count($articulo )<1) {
              alertar("el post no existe", "error");
            }

             
                
     }

     ?>

     <?php
include('blogHead.php');
?>

 

<?php include("articuloMovil.php"); ?>




<section class="py-5 d-md-block d-none">
  <div class="container container_r clearfix">
      <div class="row">
           <!--COL INFORMACION IZQUIERDA-->
          <div class="col-lg-8">
             <div id="content">
                 
        
        <!--CONTENEDOR DESCRIPCION-->
        <div class="descripcion" id="descripcion">

          <!--TEXTO DESTACADO-->
      <p>   <?=$descripcionCorta;?></p>
          <!--FIN TEXTO DESTACADO-->

                   <!--SLIDER-->

          <div id="carrouseldivPc" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
              
      <!--CARGA DE IMAGENES-->
                                     <?php //********************arranca fotos*******************************************
      # code...  
                                    
for ($i=0; $i < count($fotos); $i++) { 
    $active='';
  if ($i==0) {
   $active='class="active"';
  }?>
  <li data-target="#carrouseldivPc" data-slide-to="<?=$i;?>" <?= $active;?>></li>
  <?php
}
?>
    </ol>
              <div class="carousel-inner">
<?php
    for ($i=0; $i < count($fotos) ; $i++) { 
      if ($i==0) {
        $classe="carousel-item active";
      }
      else{
        $classe="carousel-item";
      }?>
    
       <div class="<?= $classe ?>">
                  <img class="d-block w-100 img-slider-servicio" src="admin/classes/imgBlog/<?=$fotos[$i]['ruta'];?>" alt="First slide">
                  <div class="carousel-caption d-none d-md-block">
                    <h5></h5>
                  </div>
                </div>
   
   
<?php
    }
    //********************fin fotos*******************************************?>
            
                <!--FIN CARGA DE IMAGENES-->
         
              </div>
                <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                  <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                  <span class="sr-only">Next</span>
                </a>
           </div>
        <!--FIN SLIDER-->
        <!--TEXTO VISITA-->


        <h2 class="py-4 text-primary" name="desc"><?=$titulo;?></h2>

   

       
 <?=$contenido;?>
         
          
     

 



        <!--FIN TEXTO VISITA-->

        <!--TEXTO IMPORTANTE-->

         <h2 class="py-4 text-primary"> Tips Y Consejos </h2>

        <?=$tipsYConsejos;?>


         <h2 class="py-4 text-primary"> Observaciones </h2>

        <?=$observaciones;?>
           <!--BARRA DESPLAZADA-->
          </div>
          
      </div>
  </div>

  
</section>



<section>
  




             <!--FIN MAPA-->
  
  <div class="container" >
         <div class="row">
           <div class="col-lg-12">
          
           <!-- CONTENEDOR CANCELACION-->
          
           <!--FIN CONTENEDOR CANCELACION-->

           <!--FIN CONTENEDOR OPINIONES-->


            <!--CARDS DE INTERES-->

            
            <div class="container mb-5" style="display:none;">
              <h2 class="text-center mb-4">También te puede interesar</h2>
              <div class="row">




  <div class="col-lg-4">
                   <div class="card card-destacadas mb-5 shadow ">
                   <img src="admin/classes/imgServicio/<?=$fotos[0]['ruta'];?>" class="img-fluid img-card-top img-destacada " >
                   <div class="destacado">
                     <h5 class="text-uppercase text-white"></h5>
                   </div>
                   <div class="card-body">
                     <h3><a href=""></a></h3>
                     <p class="text-primary"><strong><</strong> <span class="text-gris"></span></p>
                     <p></p>
                     <h3 class="text-primary"></h3>
                   </div>
                 </div>
                </div>



<!--CARGA DE CARD DE INTERES-->
              
                  <!--CARGA DE CARD DE INTERES-->
        
 
     
         <!--BUCLE DE RESULTADOS -->
                
                    <!--CARGA DE CARD DE INTERES-->
         
              </div>
            </div>

       
            <!--FIN CARDS DE INTERES-->


       </div>
   </div>
</div>
  </section>



<!--SECCION INFORMACION MOVIL-->


<!--CONTENEDOR DE ACORDEON-->




<!--CONTENEDOR DE ACORDEON-->


<!--MODAL HORARIO-->


          
</div>

<!--MODAL HORARIO-->

<!--FIN SECCION INFORMACION MOVIL-->


<!--BOTON RESERVA MOVIL-->

<section class="d-md-none scroll-to-top2  position-fixed">
    <div class="container-fluid d-md-none">
        
   </div>

</section>

<!--FIN BOTON RESERVA MOVIL-->
  








 <!-- Footer -->
<?php include "footer.php"; ?>

  
  
  <!-- MODAL BUSCAR MOVIL-->
<div class="modal fade" id="modalbuscar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="form-buscar">
            <label class="sr-only" for="s">¿Dónde vamos?</label>
          <div class="input-group ">
            <input class="field form-control" id="buscar" name="buscar" type="text" placeholder="¿Dónde vamos?" value="">
            <span class="input-group-append">
              <button class="submit btn btn-primary" id="searchsubmit2" name="submit" type="submit">Buscar <i class="fa fa-arrow-right"></i></button>
            </span>
          </div>
              <!--EMPIEZA DESPLEGABLE DEL BANNER--> 
              <div class="form-group">
               <div class="container">
                <div class="row">
                  <div class="col-lg-12">
                     <div class="top-destinos-movil" >
                         <div class="container">
                        <div class="row mb-4">
                          <div class="col-lg-12">
                            <h3 class="text-center text-primary">Top Destinos</h3>
                          </div>
                        </div>
                        <div class="row  mb-4">
                            
                            <!--EL BUCLE DE LOS RESULTADOS DEBE IR ACA-->   
                            <div class="col-md-3 col-6 mb-3">
                            <h4 class=" mb-0"><a  class="text-destinos">Florianopolis</a></h4>
                            <small>Santa Catarina</small>
                            </div>
                            <!--FIN BUCLE DE LOS RESULTADOS DEBE IR ACA-->   
                  
                         </div>
                         <div class="row py-4">
                          <div class="col-lg-12">
                            <h3 class="text-center"><a href="" class="btn btn-outline-primary btn-white" style="border-radius:25px;">Ver todos los destinos</a></h3>
                          </div>
                        </div>
                        </div>
                     </div>
                  </div>
                </div>
                </div>
              </div>
               <!--EMPIEZA DESPLEGABLE DEL BANNER-->  
              
            </form>
      </div>
    </div>
  </div>
</div>
<!-- FIN MODAL BUSCAR MOVIL-->



 <!-- SCRIPTS NECESARIOS-->

  
  <!-- WOW ANIMACION -->
  <script src="js/wow.min.js?v=<?php echo $version?>"></script>
  <!-- WOW ANIMACION -->
  

  
  <!-- BOOTSTRAP BUNDLE -->
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js?v=<?php echo $version?>"></script>
  <!-- BOOTSTRAP BUNDLE -->
  
  <!-- JQUERY EASING -->
  <script src="vendor/jquery-easing/jquery.easing.min.js?v=<?php echo $version?>"></script>
  <!-- JQUERY EASING -->

  <!-- CUSTOM -->
  <script src="js/script.js?v=<?php echo $version?>"></script>
  <!-- CUSTOM -->
  
  <script type="text/javascript" src="js/rAF.js?v=<?php echo $version?>"></script>
  <script type="text/javascript" src="js/ResizeSensor.js?v=<?php echo $version?>"></script>
  <script type="text/javascript" src="js/sticky-sidebar.js?v=<?php echo $version?>"></script>
  <script type="text/javascript">

    var stickySidebar = new StickySidebar('#sidebar', {
      topSpacing: 90,
      bottomSpacing: 100,
      containerSelector: '.container_r',
      innerWrapperSelector: '.sidebar__inner'
    });
</script>
</body>
</html>