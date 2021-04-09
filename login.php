


                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       <?php 
 
include("navbar.php");
include($GLOBALS['path'].'/conectar.php');




?>



 <!--SECCION HEADER-->
<section id="header-visitas" class="menu-h" style="background-image: url('img/slider4.jpg');">
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
<h1 class="a-title-empleo afiliados" style="text-align: center;">Inicia sesion</h1>


        <form class="form-buscar" style="padding-top: 30px;">
  
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
<h1 class="a-title-empleo afiliados" style="text-align: center;">Login</h1>


           <!-- NAV-ITEM-->
          <li class="nav-item mx-0 mx-lg-1 user">
            <a class="nav-link py-3 px-0 px-lg-3 rounded-sm " href id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fa fa-user-alt"></i> <?= $_SESSION["usuario"];?>
            </a>
             <!-- CONTENEDOR USUARIO LOGIN -->
             <div class="container" id="usuario" style="margin-top: -10px;">
            <div class="row">
                <div class="col-lg-2"></div>
                 <!-- CONTENEDOR LOGIN -->
           
               <?php if (isset($_SESSION["active"])) {
                 echo '
      <div class="dropdown-menu menu-civa" aria-labelledby="navbarDropdownMenuLink" id="divMonedaSel">
                      <form action="ctrlLogin.php" method="post" class="">
                      <input type="hidden" name="login" value="0">
                    <button class="dropdown-item" >Cerrar sesión</a>
                      </form>
                     
                      
                   
                    </div>
                    ';
               } 
else{
  echo '   <div class="col-lg-5 bg-white ">
             <div class="card card-usuario ">
                  <div class="card-body">
                    <h3 class="text-primary">Mi cuenta</h3>
                <p>¿Ya tienes cuenta? Accede a tu panel de usuario</p>
                <form action="ctrlLogin.php" method="post" class="">
                  <div class="form-group">
                    <input type="email" name="email" class="form-control" placeholder="Email">
                  </div>
                   <div class="form-group">
                   <input type="hidden" name="login" value="1">
                    <input type="password" name="clave" class="form-control" placeholder="Contraseña">
                    <small class="float-right text-primary py-2"><a href="">He olvidado mi contraseña</a></small>
                  </div>
                  <button class="btn btn-primary bd-highlight">Iniciar sesion</button>
                </form>
                <div class="row mb-4">
                  <div class="col-lg-6">
                    <div class="fb-login-button" style="padding-top: 38px; " data-width="" data-size="medium" data-button-type="login_with" data-auto-logout-link="false" data-use-continue-as="false"></div>

                  </div>
                  <div class="col-lg-6">
                     <div class="g-signin2" style="padding-top: 38px; width: 200%; height: 30%; font-size: 13px;" data-onsuccess="onSignIn"></div>

                   
                  </div>
                </div>
                <h5>¿No tienes cuenta? <span>Regístrate aquí</span></h5>
                  </div>
                </div>
                    </div>
                             <!-- FIN CONTENEDOR LOGIN -->
               
               <!-- CONTENEDOR RESERVAS -->
              <div class="col-lg-5 bg-light">
               <div class="card card-usuario ">
                 <div class="card-body">
                    <h3 class="text-primary">Mis reservas</h3>
                <p>Puedes gestionar tu reserva sin estar registrado</p>
                <form action="" class="">
                  <div class="form-group">
                    <input type="email" name="" class="form-control" placeholder="Email">
                  </div>
                   <div class="form-group">
                    <input type="password" name="" class="form-control" placeholder="Identificador de la reserva">
                  </div>
                  <button class="btn btn-primary">Ir a reserva</button>
                </form>
                 </div>
               </div>
              </div>
              <!-- FIN CONTENEDOR RESERVAS -->
  ';
}


               ?>
   
          
      
              
            </div>
          </div>
           <!-- FIN CONTENEDOR USUARIO LOGIN -->
          </li>
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