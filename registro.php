<?php
include("includes/navbar.php"); 
include("admin/classes/usuario.php"); 




if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["registro"]) ) {

$email=$_POST['email'];
$password=md5($_POST['pass1']);


$resu=altaUsuario($_POST["nombre"], $email,  $password, 1,0,0,0);

if ($resu>0) {
$nombre=$_POST["nombre"];
include("admin/classes/email_registro_correcto.php");

include("admin/classes/reservaEmail.php");


$resumail=enviaMail($email," Bem-vindo à Metele Brasil", $email_registro_correcto, $parametros[0]["site"]);




  alertar("registrado con exito", "success");
  $login=login($email, $password);
  redireccionarLento("index");
}
else{
  alertar("Error, el email ya esta registrado", "warning");
}
}

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


<br>

<!--FIN MENU FIJO-->


<!--SECCION INFORMACION DE VISITA MOVIL-->


<!--FIN SECCION INFORMACION DE VISITA MOVIL-->


<body class="hold-transition register-page">
<div class="register-box" style="margin-left: 20%;margin-right: 20%;">
  <div class="register-logo">

     <h3 class="py-2 bold" style="text-align:center;">Registrate Ahora!</h3>
<br>
  </div>

  <div class="card">
    <div class="card-body register-card-body">
      <p class="login-box-msg">Se parte de nuestra comunidad! Brasil te espera!</p>

      <form action="../../index.html" method="post">
        <div class="input-group mb-3">
          <input type="text" class="form-control" placeholder="Full name">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="email" class="form-control" placeholder="Email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Retype password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="agreeTerms" name="terms" value="agree">
              <label for="agreeTerms">
               I agree to the <a href="#">terms</a>
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Register</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

      <div class="social-auth-links text-center" style="display:none;">
        <p>- OR -</p>
        <a href="#" class="btn btn-block btn-primary">
          <i class="fab fa-facebook mr-2"></i>
          Sign up using Facebook
        </a>
        <a href="#" class="btn btn-block btn-danger">
          <i class="fab fa-google-plus mr-2"></i>
          Sign up using Google+
        </a>
      </div>

      <a data-toggle="modal" data-target="#modalLoginForm" class="text-center">I already have a membership</a>
    </div>
    <!-- /.form-box -->
  </div><!-- /.card -->
</div>
<!-- /.register-box -->

<!-- jQuery -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js"></script>
</body>







<section class="py-5 d-md-block d-none">
  <div class="container container_r clearfix">
      <div class="row">
           <!--COL INFORMACION IZQUIERDA-->
          <div class="col-lg-12">
             <div id="content">
                 
            <div class="o-container-work-us">
<h1 class="a-title-empleo afiliados" style="text-align: center;">Registrate</h1>


        <form class="form-buscar" method="post" onsubmit="return validarRegistro()"style="padding-top: 30px;">
  
        <div class="col-lg-12 py-2 d-md-block d-none">
          <h5 class="text-uppercase mb-4" style="text-align: center;">Email</h5>
         
                  <div class="input-group">
                <input class="field form-control" id="email" name="email" type="text" value="" required>
                 </div>
           
        </div>
  
        <div class="col-lg-12 py-2 d-md-block d-none">
          <h5 class="text-uppercase mb-4" style="text-align: center;">Nombre Y Apellido</h5>
         
                  <div class="input-group">
                <input class="field form-control" id="nombre" name="nombre" type="text" value="" required>
                 </div>
           
        </div>

        <div class="col-lg-12 py-2 d-md-block d-none">
          <h5 class="text-uppercase mb-4" style="text-align: center;">Contraseña</h5>
         
                  <div class="input-group">
                <input class="field form-control" id="pass1" name="pass1" type="password"  value="" required>
                 </div>
           
        </div>
    <div class="col-lg-12 py-2 d-md-block d-none">
          <h5 class="text-uppercase mb-4" style="text-align: center;">Repetir Contraseña</h5>
         
                  <div class="input-group">
                <input class="field form-control" id="pass2" name="pass2" type="password"  value="" required>
                 </div>
           
        </div>
         <div class="col-lg-12 py-2 d-md-block d-none">
        <button class="btn btn-info" name="registro">Registrarme</button>
        </div>
        </form>
       <script type="text/javascript">
         function validarRegistro(){
         
 var pass1 = $("#pass1").val();
 var pass2 = $("#pass2").val();
 if (pass1 != pass2) {
 Swal.fire("Error, las contraseñas no coinciden", "" ,"warning");
  return false;
 }
 else{
  return true;
 }


         }
       </script>

            </div>

        </div>
        
            </div>
        <!--FIN PRIVACIDAD-->
        </div> 
           </div>
          </section>

  
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