<?php
 include('includes/navbar.php'); 

 include('admin/classes/categoria.php'); 
  include('admin/classes/fotos_categoria.php'); 
 
 include('admin/classes/opiniones_categoria.php'); 


if (isset($_GET["idCategoria"])) {
$idCategoria=$_GET["idCategoria"];
$categorias=getCategoria($idCategoria);
$servicios=getServiciosidCategoria_servicio($idCategoria);
 $cantidad_servicios_categoria=count($servicios);
  $nViajeros=$categorias[0]["nViajeros"];
   $id=$categorias[0]["idCategoria_servicio"];
   $nombre_categoria=$categorias[0]["nombre_categoria_servicio"];
   $opiniones_categoria=OpinionesCategoria($id);
   $cantidad_opiniones_categoria=count($opiniones_categoria);
   $fotos=$categorias[0]["img_categoria_servicio"];
}
else{
  $servicios=getServicios();
 $cantidad_servicios_categoria=count($servicios);

 $categorias=getCategorias();
 $nViajeros=rand(690,1200); 
$nombre_categoria=" Todas Las Categorías";
$opiniones_categoria=array();
 $cantidad_opiniones_categoria=rand(100,500); ;
    $fotos="sinCategoria.jpg";
}


?>



 <!--SECCION HEADER-->
<section id="header-visitas" class="menu-h" style="background-image: url('sistema/img/categoria_servicio/<?=$fotos;?>');">
  <div class="container d-md-block d-none">
    <div class="row">
      <div class="col-lg-12">
          
          <!--BUCLE DE LOS RESULTADOS AQUI--> 
        <div class="badge badge-primary badge-ciudad"></div>
         <!--FIN BUCLE DE LOS RESULTADOS AQUI-->
         
          <!--TITULO-->
        <h1 class="text-white texto-shadow py-2 bold" style=" text-shadow: -1px 0px 6px #000000;"></h1>
         <!--TITULO-->
        
      
      </div>
    </div>
  </div>
 <!--header-->
  
 <!-- CONTENEDOR DE CARACTERISTICAS-->



  <br>
  
  <!-- CONTENEDOR DE FRANJA TRANSPARENTE-->
  <div class="container  d-md-block d-none ">
     <div class="row">
      <div class="col-lg-12">
        <div class="div-fondo-visita-">
          <ul class="lista-visitas text-white">
          </ul>
        </div>
      </div>
    </div>
  </div>
    <!-- FIN CONTENEDOR DE FRANJA TRANSPARENTE-->
    
    
    <!-- CONTENEDOR DE BOTONES EN MOVIL-->
  <div class="container-fluid  d-md-none">
     <div class="row">
      <div class="col-6">
      </div>
      <div class="col-6">
          <a  data-toggle="collapse" class="btn-reservar" href="#compartir" role="button" aria-expanded="false" aria-controls="collapseExample" ><i class="text-dark fa fa-share-alt"></i></a>
                 <div class="collapse" id="compartir">
                  <div class="card card-body">
                   <a class="btn btn-outline-light btn-social facebook mx-1 mb-2" href="#">
                    <i class="fab fa-fw fa-facebook-f"></i>
                  </a>
                  <a class="btn btn-outline-light btn-social linkedin mx-1 mb-2" href="#">
                    <i class="fab fa-fw fa-instagram"></i>
                  </a>
                  </div>
                </div>
      </div>
    </div>
  </div>
    <!-- FIN CONTENEDOR DE BOTONES EN MOVIL-->
  
</section>
 <!--FIN SECCION HEADER-->




<!--FIN MENU FIJO-->


<!--SECCION INFORMACION DE VISITA MOVIL-->


<!--FIN SECCION INFORMACION DE VISITA MOVIL-->


<section class="py-5 d-md-block d-none">
  <div class="container container_r clearfix">
      <div class="row">
           <!--COL INFORMACION IZQUIERDA-->
          <div class="col-lg-12">
             <div id="content">
                 
            <div class="o-container-work-us">
<h1 class="a-title-empleo afiliados" style="text-align: center;">Guia de <?=$nombre_categoria; ?></h1>
<span class="py-4 text-primary">Envienos sus datos y le enviaremos la guia de <?=$nombre_categoria; ?></span>

       
        <form class="form-buscar" style="padding-top: 30px;">
        <div class="col-lg-12 py-2 d-md-block d-none">
          <h5 class="text-uppercase mb-4" style="text-align: center;">Nombre</h5>
          
                  <div class="input-group">
                <input class="field form-control" id="nombre" name="nombre" type="text" placeholder="Escribe tu nombre" value="">
              </div>
            
        </div>
        <div class="col-lg-12 py-2 d-md-block d-none">
          <h5 class="text-uppercase mb-4" style="text-align: center;">Email</h5>
         
                  <div class="input-group">
                <input class="field form-control" id="email" name="email" type="text" placeholder="Escribe tu mail" value="">
              </div>
           
        </div>
        <div class="col-lg-12 py-2 d-md-block d-none">
          <h5 class="text-uppercase mb-4" style="text-align: center;">Teléfono</h5>
         
                  <div class="input-group">
                <input class="field form-control" id="phone" name="phone" type="text" placeholder="Escribe tu teléfono" value="">
              </div>
            
        </div>
        <div class="col-lg-12 py-2 d-md-block d-none">
          <h5 class="text-uppercase mb-4" style="text-align: center;">Mensaje</h5>
         
              <div class="input-group">
                <textarea class="field form-control" id="mensaje" name="mensaje">
                
                </textarea>
              </div>
            
        </div>
        <button class="submit btn btn-primary" id="searchsubmit" name="submit" type="submit">Enviar <i class="fa fa-arrow-right"></i></button>
        </form>
       

            </div>

        </div>
        
            </div>
        <!--FIN PRIVACIDAD-->
        </div> 
           </div>
          </section>
<div class="container py-2">
                          <div class="row">
                              <div class="col-12">
<h1 class="a-title-empleo afiliados" style="text-align: center;">Guia de <?= NombreCategoria($id); ?></h1>
<span class="py-4 text-primary">Envienos sus datos y le enviaremos la guia de <?= NombreCategoria($id); ?></span>

       
        <form class="form-buscar" style="padding-top: 30px;">
        
          <h5 class="text-uppercase mb-4" style="text-align: center; padding-top: 10px;">Nombre</h5>
          
                  <div class="input-group">
                <input class="field form-control" id="nombre" name="nombre" type="text" placeholder="Escribe tu nombre" value="">
              </div>
            
        
       
          <h5 class="text-uppercase mb-4" style="text-align: center; padding-top: 10px;">Email</h5>
         
                  <div class="input-group">
                <input class="field form-control" id="email" name="email" type="text" placeholder="Escribe tu mail" value="">
              </div>
           
        
        
          <h5 class="text-uppercase mb-4" style="text-align: center; padding-top: 10px;">Teléfono</h5>
         
                  <div class="input-group">
                <input class="field form-control" id="phone" name="phone" type="text" placeholder="Escribe tu teléfono" value="">
              </div>
            
        
        
          <h5 class="text-uppercase mb-4" style="text-align: center; padding-top: 10px;">Mensaje</h5>
         
              <div class="input-group">
                <textarea class="field form-control" id="mensaje" name="mensaje">
                
                </textarea>
              </div>
            
        
        </form>
                              </div>
                          </div>
</div>
  
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
                            <h4 class=" mb-0"><a href="#" class="text-destinos">Nueva York</a></h4>
                            <small>Estados Unidos</small>
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
  <script src="js/wow.min.js"></script>
  <!-- WOW ANIMACION -->
  

  
  <!-- BOOTSTRAP BUNDLE -->
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- BOOTSTRAP BUNDLE -->
  
  <!-- JQUERY EASING -->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
  <!-- JQUERY EASING -->

  <!-- CUSTOM -->
  <script src="js/script.js"></script>
  <!-- CUSTOM -->
  
  <script type="text/javascript" src="js/rAF.js"></script>
  <script type="text/javascript" src="js/ResizeSensor.js"></script>
  <script type="text/javascript" src="js/sticky-sidebar.js"></script>
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