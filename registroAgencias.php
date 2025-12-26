
<?php
include("includes/navbar.php");
include("admin/classes/paises.php");
include("admin/classes/solicitudes.php");
include("admin/classes/antibot.php");

if ($_SERVER["REQUEST_METHOD"] == "POST"  ) {
    // Validación anti-bot
    $validacion = validarAntiBot('register_agency_form', $_POST['recaptcha_token'] ?? null);
    
    if (!$validacion['success']) {
        alertar($validacion['error'], "error");
    } else {
        $idTipoSolicitud=2;
        $email=$_POST["email"];
        $password=$_POST["contrasena"];
        $nombre_agencia=$_POST["nombre_agencia"];
        $tipo_de_agencia=$_POST["tipo_agencia"];
        $nombre=$_POST["nombre"];
        $cargo_empresa=$_POST["cargo_empresa"];
        $whatsapp=$_POST["whatsapp"];
        $estado=$_POST["estado"];
        $ciudad=$_POST["ciudad"];
        $destino_que_opera=$_POST["destino_que_opera"];
        $descripcion_agencia="";

        $resu=setSolicitud($idTipoSolicitud, $email, $password, $nombre_agencia, $descripcion_agencia ,$tipo_de_agencia, $nombre, $cargo_empresa, $whatsapp, $estado, $ciudad, $destino_que_opera);
        if($resu>0){
            alertar("Su solicitud se envio correctamente, nos comunicaremos con usted por email", "success");
            redireccionarLento("index");
        }
    }
}



?>



 <!--SECCION HEADER-->
<section id="header-visitas" class="menu-h" style="background-image: url('img/slider1.jpg');">
  <div class="container d-md-block">
    <div class="row">
      <div class="col-lg-12">
          
          <!--BUCLE DE LOS RESULTADOS AQUI--> 
        <div class="badge badge-primary badge-ciudad"></div>
         <!--FIN BUCLE DE LOS RESULTADOS AQUI-->
         
          <!--TITULO-->
        <h1 class="text-white texto-shadow py-2 bold" style=" text-shadow: -1px 0px 6px #000000; text-align:center;"><?=$lang["registre_agencia"];?></h1>
         <!--TITULO-->
        
      
      </div>
    </div>
  </div>
 <!--header-->
  
 <!-- CONTENEDOR DE CARACTERISTICAS-->



  <br>
  
  <!-- CONTENEDOR DE FRANJA TRANSPARENTE-->
  <div class="container  d-md-block  ">
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
  <div class="container-fluid ">
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



 <section class="py-5 d-md-block">
  <div class="container container_r clearfix">
      <div class="row">
           <!--COL INFORMACION IZQUIERDA-->
          <div class="col-lg-12">

            <div style="margin-left: 5%;margin-right: 5%;">

            <h1 class="a-title-empleo afiliados" style="text-align: center;"><?=$lang["activa_tu_cuenta_ya"];?></h1>

<h5 class="a-title-empleo afiliados" style="text-align: center;"><?=$lang["completa_datos_para_activar_agencia"];?></h5>

<p class="a-title-empleo afiliados" style="text-align: center;"><?=$lang["nuestros_partners_se_comunicaran"];?></p>
</div>

        <form class="form-buscar" method="post" onsubmit="return validar()"style="padding-top: 30px;">

    <div class="card card-primary" style="margin-left: 5%;margin-right: 5%;">
              <div class="card-header">   
               <h3 class="card-title"><i class="fas fa-key fa x3"></i> <?=$lang["datos_de_acceso"];?></h3>
              </div>   
<div class="card-body register-card-body">
      <p class="login-box-msg"></p>

        
        <div class="input-group mb-3">
          <input type="email" name="email" class="form-control" placeholder="<?=$lang["digite_su_email"];?>">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" name="contrasena" id="contrasena" class="form-control" placeholder="<?=$lang["senha"];?>">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" name="confirmaContrasena" id="confirmaContrasena" class="form-control" placeholder="<?=$lang["repetir_contrasena"];?>">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        
      
    </div>
            </div>



<div class="card card-primary" style="margin-left: 5%;margin-right: 5%;">
              <div class="card-header">

                <h3 class="card-title"><i class="fas fa-briefcase"></i> <?=$lang["datos_de_agencia"];?></h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
         
                <div class="card-body">
                  <div class="form-group">
                    <label for="exampleInputEmail1"><?=$lang["nombre_agencia"];?></label>
                    <input type="text" class="form-control" id="nombre_agencia" name="nombre_agencia" placeholder="<?=$lang["complete_nombre_fantasia"];?>">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1"><?=$lang["tipo_de_agencia"];?></label>
                    <input type="text" class="form-control" id="tipo_de_agencia" name="tipo_agencia" placeholder="<?=$lang["agencia_receptiva_emisiva"];?>">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1"><?=$lang["nombre_"];?></label>
                    <input type="text" class="form-control" id="nombre_" name="nombre" placeholder="<?=$lang["complete_con_su_nombre"];?>">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1"><?=$lang["cargo_empresa"];?></label>
                    <input type="text" class="form-control" id="cargo_empresa" name="cargo_empresa" placeholder="<?=$lang["indique_cargo_en_empresa_"];?>">
                  </div>               
                  <div class="form-group">
                    <label for="exampleInputPassword1"><?=$lang["whatsapp_"];?></label>
                    <input type="text" class="form-control" id="whatsapp_" name="whatsapp" placeholder="<?=$lang["indique_whatsapp_empresa"];?>">
                  </div>
                                  
<div class="row">
          <div class="col">
            <div class="form-group">
                    <label for="exampleInputPassword1"><?=$lang["estado_"];?></label>
                    <input type="text" class="form-control" id="estado_" name="estado" placeholder="<?=$lang["indique_estado_"];?>">
          </div>
          </div>
           <div class="col">
            <div class="form-group">
                    <label for="exampleInputPassword1"><?=$lang["ciudad_"];?></label>
                    <input type="text" class="form-control" id="ciudad_" name="ciudad" placeholder="<?=$lang["indique_ciudad_"];?>">
          </div>
            
          </div>
           <div class="col">
            <div class="form-group">
                    <label for="exampleInputPassword1"><?=$lang["destino_que_opera"];?></label>
                    <input type="text" class="form-control" id="destino_que_opera" name="destino_que_opera" placeholder="<?=$lang["indique_destino_que_opera"];?>">
            
          </div>
       
        </div>

          </div>
       
 
<div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="agreeTerms" name="terms">
              <label for="agreeTerms">
              <?=$lang["acepto_los"];?><a href="https://www.metelebrasil.com/privacy"> <?=$lang["terminos_y_condiciones_"];?></a>
              </label>
            </div>
          </div>
          <!-- /.col -->
          
          <!-- /.col -->
        </div>

        <!-- Campos Anti-Bot (invisibles) -->
        <?php echo generarCamposAntiBot(); ?>

      </div>

<div class="col">
            <button type="submit" class="btn btn-primary btn-block" ><?=$lang["activar_cuenta"];?></button>
          </div>

          <br>
                  </div>


                  
                <!-- /.card-body -->
              </form>
              

            </div>
<script type="text/javascript">
  function validar(){
      var agreeTerms=$('#agreeTerms').prop('checked');

if (agreeTerms) {
  var pass1=$('#contrasena').val();
  var pass2=$('#confirmaContrasena').val();
if (pass1!=pass2) {
 Swal.fire("Error", "Las Contraseñas no coinciden", "warning");

  return false;
} else{
  return true;
} 
 } //if (agreeTerms) {
  else{
     Swal.fire("Error", "Debe aceptar los terminos y condiciones para poder continuar", "warning");
     return false;
  }
  }
</script>
</div>
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

            <label class="sr-only" for="s"><?=$lang["donde_vamos"]?></label>
          <div class="input-group ">
            <input class="field form-control" id="buscar" name="buscar" type="text" valeu="¿Dónde vamos?" value="">
            <span class="input-group-append">
              <button class="submit btn btn-primary" id="searchsubmit2" name="submit" type="submit"><?=$lang["nombre"]?><?=$lang["buscar"]?><i class="fa fa-arrow-right"></i></button>
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
                            <h3 class="text-center text-primary"><?=$lang["top_actividades"]?></h3>
                          </div>
                        </div>
                        <div class="row  mb-4">
                            
                            <!--EL BUCLE DE LOS RESULTADOS DEBE IR ACA-->   
                            <div class="col-md-3 col-6 mb-3">
                            <h4 class=" mb-0"><a href="#" class="text-destinos">Rio de Janeiro</a></h4>
                            <small>Salvador Bahia</small>
                            </div>
                            <!--FIN BUCLE DE LOS RESULTADOS DEBE IR ACA-->   
                  
                         </div>
                         <div class="row py-4">
                          <div class="col-lg-12">
                            <h3 class="text-center"><a href="" class="btn btn-outline-primary btn-white" style="border-radius:25px;"><?=$lang["ver_todos_los_destinos"]?></a></h3>
                          </div>
                        </div>
                        </div>
                     </div>
                  </div>
                </div>
                </div>
              </div>
               <!--EMPIEZA DESPLEGABLE DEL BANNER-->  
              
        
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

<!-- Calendly CSS + widget -->
<link href="https://assets.calendly.com/assets/external/widget.css" rel="stylesheet">
<script src="https://assets.calendly.com/assets/external/widget.js" async></script>

<!-- Botón flotante izquierdo (simula badge) -->
<div id="cb-left-badge">
  <button id="cb-left-btn">Agende um horário comigo</button>
</div>

<style>
#cb-left-badge {
  position: fixed;
  left: 18px; /* a la izquierda */
  bottom: 28px;
  z-index: 99999;
}

#cb-left-btn {
  background-color: #0069ff;
  color: #fff;
  border: none;
  border-radius: 8px;
  padding: 12px 18px;
  font-size: 14px;
  cursor: pointer;
  box-shadow: 0 6px 18px rgba(0,0,0,0.12);
  display: flex;
  align-items: center;
  gap: 6px;
  font-family: Arial, sans-serif;
  animation: gentleTick 3s infinite ease-in-out;
}

#cb-left-btn:hover {
  transform: scale(1.05);
  box-shadow: 0 10px 24px rgba(0,0,0,0.14);
  transition: all 0.12s ease;
}

/* Animación tipo “tic-tac leve” */
@keyframes gentleTick {
  0% { transform: rotate(0deg); }
  10% { transform: rotate(3deg); }
  20% { transform: rotate(-3deg); }
  30% { transform: rotate(2deg); }
  40% { transform: rotate(-2deg); }
  50% { transform: rotate(1deg); }
  60% { transform: rotate(-1deg); }
  70% { transform: rotate(0.5deg); }
  80% { transform: rotate(-0.5deg); }
  100% { transform: rotate(0deg); }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
  var btn = document.getElementById('cb-left-btn');

  btn.addEventListener('click', function() {
    if (window.Calendly && typeof window.Calendly.initPopupWidget === 'function') {
      // Abre popup oficial de Calendly
      Calendly.initPopupWidget({ url: 'https://calendly.com/metelebrasil/30min' });
    } else {
      // Fallback: abrir en nueva pestaña si algo falla
      window.open('https://calendly.com/metelebrasil/30min', '_blank', 'noopener');
    }
  });
});
</script>












</body>
</html>