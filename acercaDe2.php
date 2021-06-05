<?php
 

include("includes/navbar.php");
  include($GLOBALS['path'].'/conectar.php');



?>



 <!--SECCION HEADER-->
<section id="header-visitas" class="menu-h" style="background-image: url('img/slider4.jpg');">

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





<section class="py-5 d-md-block d-none">
  <div class="container container_r clearfix">
      <div class="row">
           <!--COL INFORMACION IZQUIERDA-->
          <div class="col-lg-12">
             <div id="content">
                 
            <div class="o-container-work-us">
<h1 class="a-title-empleo afiliados">Acerca de nosotros</h1>


        <span class="py-4 text-primary">¿Quienes somos?:</span>

            <span class="">METELEBRASIL es una empresa especializada en brindar alto valor profesional en soluciones integrales de servicios de viajes.
            <br><br>
            </span>

        <span class="py-4 text-primary">¿Que le aportamos a su experiencia?</span>

        <span class="">Sumamos conocimiento, experiencia y estrategia para ayudar a nuestros clientes a tomar las decisiones que necesitan para optimizar sus gastos de viajes y representación.</span>
        <br><br>

               


        <span class="py-4 text-primary">Valor agregado</span>

        <span class="">Flexible y de respuesta en tiempo real, nuestros especialistas colaboran para responder a la demanda de un mercado cada vez más exigente, combinando el trato altamente personalizado y los beneficios de la economía de escala que se traducen en ahorros.</span>

        <span class="py-4 text-primary">Soporte permanente</span>

        <span class="">Nuestras unidades de negocios comprenden:
Business Travel<br>
Groups, Events & Meetings<br>
Incoming Services<br>
</span>
        <br><br>


       
       
        <span class="py-4 text-primary">Nuestro propósito</span>

        <span class="">Nuestro propósito es que la experiencia de los viajes resulte personalmente agradable y lo más beneficiosa posible.

En síntesis, nosotros nos preocupamos para que ustedes no se preocupen, porque en definitiva cuidar su bienestar e interés es nuestro negocio, ya que de ello también depende nuestro bienestar.</span>

                            </div>

        </div>
        
            </div>
        <!--FIN PRIVACIDAD-->
        </div> 
           </div>
          </section>
<div id="escondermobil">
 <div class="container py-2">
                          <div class="row">
                              <div class="col-12">
                                  <!--CONTENEDOR DESCRIPCION-->
       <h1 class="a-title-empleo afiliados">Acerca de nosotros</h1>


        <span class="py-4 text-primary">¿Quienes somos?:</span>

            <span class="">METELEBRASIL es una empresa especializada en brindar alto valor profesional en soluciones integrales de servicios de viajes.
            <br><br>
            </span>

        <span class="py-4 text-primary">¿Que le aportamos a su experiencia?</span>

        <span class="">Sumamos conocimiento, experiencia y estrategia para ayudar a nuestros clientes a tomar las decisiones que necesitan para optimizar sus gastos de viajes y representación.</span>
        <br><br>

               


        <span class="py-4 text-primary">Valor agregado</span>

        <span class="">Flexible y de respuesta en tiempo real, nuestros especialistas colaboran para responder a la demanda de un mercado cada vez más exigente, combinando el trato altamente personalizado y los beneficios de la economía de escala que se traducen en ahorros.</span>

        <span class="py-4 text-primary">Soporte permanente</span>

        <span class="">Nuestras unidades de negocios comprenden:
Business Travel<br>
Groups, Events & Meetings<br>
Incoming Services<br>
</span>
        <br><br>


      
       
        <span class="py-4 text-primary">Nuestro propósito</span>

        <span class="">Nuestro propósito es que la experiencia de los viajes resulte personalmente agradable y lo más beneficiosa posible.

En síntesis, nosotros nos preocupamos para que ustedes no se preocupen, porque en definitiva cuidar su bienestar e interés es nuestro negocio, ya que de ello también depende nuestro bienestar.</span>

        <!--FIN CONTENEDOR DESCRIPCION-->
                              </div>
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