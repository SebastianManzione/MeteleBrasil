
<?php
include("includes/navbar.php");
include("admin/classes/contacto.php");
if ($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST["contacto"])) {
$nombre=$_POST["nombre"];
$email=$_POST["email"];
$telefono=$_POST["telefono"];
$mensaje=$_POST["mensaje"];
$resul=setContacto($nombre, $email, $telefono, $mensaje);


if($resul>0){
  alertar("Su consulta sera respondida a la brevedad", "success");
}

}




?>



 <!--SECCION HEADER-->
<section id="header-visitas" class="menu-h" style="background-image: url('img/slider4.jpg');">
  <div class="container d-md-block">
    <div class="row">
      <div class="col-lg-12">
          
          <!--BUCLE DE LOS RESULTADOS AQUI--> 
        <div class="badge badge-primary badge-ciudad"></div>
         <!--FIN BUCLE DE LOS RESULTADOS AQUI-->
         
          <!--TITULO-->
        <h1 class="text-white texto-shadow py-2 bold" style=" text-align: center;text-shadow: -1px 0px 6px #000000;"><?=$lang["hablemos"];?></h1>
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
             <div id="content" style="margin-left: 10%;margin-right: 10%">
                 
            <div class="o-container-work-us">

              <h1 class="a-title-empleo afiliados" style="text-align: center;"><?=$lang["equipo_de_ayuda_al_usuario"];?></h1>

        <form method="post" class="form-buscar" style="padding-top: 30px;margin-left: 10%;margin-right: 10%">

          

        <p style="text-align: center;"><?=$lang["como_estas_necesitas_ayuda"];?> <?=$lang["responderemos_a_la_brevedad"]?>
           </p>

           <p class="py-4 text-primary" style="text-align: center;"><?=$lang["si_le_quedan_dudas_respecto"]?></p>

        <div class="col-lg-12 py-2 d-md-block d-none">




          <h5 class="text-uppercase mb-4" style="text-align: center;"><?=$lang["nombre"]?></h5>
          
                  <div class="input-group">
                <input class="field form-control" id="nombre" name="nombre" type="text" placeholder="<?=$lang["complete_con_su_nombre"];?>" value="">
              </div>
            
        </div>
        <div class="col-lg-12 py-2 d-md-block d-none">
          <h5 class="text-uppercase mb-4" style="text-align: center;"><?=$lang["email"]?></h5>
         
                  <div class="input-group">
                <input class="field form-control" id="email" name="email" type="text" placeholder="<?=$lang["completa_con_tu_correo_electronico"];?>" value="">
              </div>
           
        </div>
        <div class="col-lg-12 py-2 d-md-block d-none">
          <h5 class="text-uppercase mb-4" style="text-align: center;"><?=$lang["telefono"]?></h5>
         
                  <div class="input-group">
                <input class="field form-control" id="phone" name="telefono" type="text" placeholder="<?=$lang["tu_telefono_de_contacto"];?>" value="">
              </div>
            
        </div>
        <div class="col-lg-12 py-2 d-md-block d-none">
          <h5 class="text-uppercase mb-4" style="text-align: center;"><?=$lang["mensaje"]?></h5>
         
              <div class="input-group">
                <textarea class="field form-control" id="mensaje" name="mensaje" placeholder="<?=$lang["describa_su_consulta_aqui"];?>"></textarea>
              </div>
            
        </div>
<br>
      <div class="col-lg-12 py-2 d-md-block" style="text-align: center;">
        <button class="btn btn-info" name="contacto"><?=$lang["enviar"];?></button>
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
 <h1 class="a-title-empleo afiliados" style="text-align: center;"><?=$lang["equipo_de_ayuda_al_usuario"];?></h1>


<br>

        <span class=""><?=$lang["como_estas_necesitas_ayuda"];?> <?=$lang["responderemos_a_la_brevedad"]?>
           </span>

<br><br>
           <span class="py-4 text-primary"><?=$lang["si_le_quedan_dudas_respecto"]?></span>
        <form class="form-buscar" style="padding-top: 30px; margin-left: 5%;margin-right: 5%;">
        
          <h5 class="text-uppercase mb-4" style="text-align: center; padding-top: 10px;"><?=$lang["nombre"]?></h5>
          
                  <div class="input-group">
                <input class="field form-control" id="nombre" name="nombre" type="text" placeholder="<?=$lang["complete_con_su_nombre"];?>" value="">
              </div>
            
        
       
          <h5 class="text-uppercase mb-4" style="text-align: center; padding-top: 10px;"><?=$lang["email"]?></h5>
         
                  <div class="input-group">
                <input class="field form-control" id="email" name="email" type="text" placeholder="<?=$lang["completa_con_tu_correo_electronico"];?>" value="">
              </div>
           
        
        
          <h5 class="text-uppercase mb-4" style="text-align: center; padding-top: 10px;"><?=$lang["telefono"]?></h5>
         
                  <div class="input-group">
                <input class="field form-control" id="phone" name="phone" type="text" placeholder="<?=$lang["tu_telefono_de_contacto"];?>" value="">
              </div>
            
        
        
          <h5 class="text-uppercase mb-4" style="text-align: center; padding-top: 10px;"><?=$lang["mensaje"]?></h5>
         
              <div class="input-group">
                <textarea class="field form-control" id="mensaje" name="mensaje" placeholder="<?=$lang["describa_su_consulta_aqui"];?>">
                
                </textarea>
              </div>
            <br>
        <div class="col-lg-12 py-2 d-md-block" style="text-align: center;">
        <button class="btn btn-info" name="registro"><?=$lang["enviar"];?></button>
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
            <label class="sr-only" for="s"><?=$lang["donde_vamos"]?></label>
          <div class="input-group ">
            <input class="field form-control" id="buscar" name="buscar" type="text" placeholder="¿Dónde vamos?" value="">
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