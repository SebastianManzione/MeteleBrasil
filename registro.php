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


$resumail=enviaMail($email,$lang["bienvenido_a_metelebrasil"], $email_registro_correcto, $parametros[0]["site"]);




  alertar($lang["registro_con_exito"], "success");
  $login=login($email, $password);
  redireccionarLento("index");
  exit();
}
else{
  alertar($lang["error_el_mail"], "warning");
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







<section class="py-5 d-md-block">
  <div class="container container_r clearfix">
      <div class="row">
           <!--COL INFORMACION IZQUIERDA-->
          <div class="col-lg-12">
             <div id="content">
                 
            <div class="o-container-work-us">
<h1 class="a-title-empleo afiliados" style="text-align: center;"><?=$lang["registrate"]?></h1>


        <form class="form-buscar" method="post" onsubmit="return validarRegistro()"style="padding-top: 30px;">
  
        <div class="col-lg-12 py-2 d-md-block">
          <h5 class="text-uppercase mb-4" style="text-align: center;">Email</h5>
         
                  <div class="input-group">
                <input class="field form-control" id="email" name="email" type="text" value="" required>
                 </div>
           
        </div>
  
        <div class="col-lg-12 py-2 d-md-block ">
          <h5 class="text-uppercase mb-4" style="text-align: center;"><?=$lang["nombre_apellido"]?></h5>
         
                  <div class="input-group">
                <input class="field form-control" id="nombre" name="nombre" type="text" value="" required>
                 </div>
           
        </div>

        <div class="col-lg-12 py-2 d-md-block ">
          <h5 class="text-uppercase mb-4" style="text-align: center;"><?=$lang["senha"]?></h5>
         
                  <div class="input-group">
                <input class="field form-control" id="pass1" name="pass1" type="password"  value="" required>
                 </div>
           
        </div>
    <div class="col-lg-12 py-2 d-md-block ">
          <h5 class="text-uppercase mb-4" style="text-align: center;"><?=$lang["repetir_contrasena"]?></h5> 
         
                  <div class="input-group">
                <input class="field form-control" id="pass2" name="pass2" type="password"  value="" required>
                 </div>
           
        </div>
         <div class="col-lg-12 py-2 d-md-block ">
        <button class="btn btn-info" name="registro"><?=$lang["confirmar_registro"]?></button>
        </div>
        </form>
       <script type="text/javascript">
         function validarRegistro(){
         
 var pass1 = $("#pass1").val();
 var pass2 = $("#pass2").val();
 if (pass1 != pass2) {
 Swal.fire("Ups! deu erro, as senhas não correspondem", "" ,"warning");
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