 <?php


include("includes/navbar.php");


require("admin/classes/categoria.php");
require("admin/classes/opiniones_categoria.php");
require("admin/classes/servicio_opiniones.php");
require("admin/classes/texto_miniaturas.php"); 



require("admin/classes/texto_viajeros.php");
     if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
                    $idServicio=$_GET['id'];
                    $servicio=getServicio($idServicio)[0];
                  $idCategoria_servicio=$servicio['idCategoria_servicio'];
                  $categoria_servicio=getCategoria($idCategoria_servicio);
                    $OpinionesServicio=GetOpinionesServicio($idServicio);
            
                      $CantOpinionesServicio=count($OpinionesServicio);
                    $estrellasServicio=GetEstrellasServicio($idServicio);
                    $textoMiniatura=getTextoMiniatura($servicio["idTextoMiniaturas"])[0]["texto"];
                    $fotos=getFotosServicio($idServicio);
                  $duracion=getDuracionServicio($idServicio);
                 $salidas=getSalidasServicio($idServicio);
                 $eventArray=GetEventosArray($idServicio);
                 $CantOpinionesServicio=count($OpinionesServicio);

                
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
          <p>Descripcion del breve del articulo</p>
          <!--FIN TEXTO DESTACADO-->

                   <!--SLIDER-->

          <div id="carrouseldivPc" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
              
                    
                                <!--CARGA DE IMAGENES-->
                                     
  <li data-target="#carrouseldivPc" data-slide-to=""></li>

    </ol>
              <div class="carousel-inner">

    
       <div class="<?= $classe ?>">
                  <img class="d-block w-100 img-slider-servicio" src="admin/classes/imgServicio/<?=$fotos[$i]['ruta'];?>" alt="First slide">
                  <div class="carousel-caption d-none d-md-block">
                    <h5></h5>
                  </div>
                </div>
   
   

            
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


        <h2 class="py-4 text-primary" name="desc">Titulo de la Descripcion</h2>

   

       
        Descripcion del Articulo
         
          
     

 



        <!--FIN TEXTO VISITA-->

        <!--TEXTO IMPORTANTE-->

         <h2 class="py-4 text-primary"> Importante </h2>

       
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

            <h2 class="text-center mb-4">También te puede interesar</h2>
            <div class="container mb-5">
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