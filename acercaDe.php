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

<h1 class="a-title-empleo afiliados"><?=$lang["acerca_de_nosotros"]?></h1>


        <span class="py-4 text-primary"><?=$lang["quienes_somos"]?></span>

            <span class=""><?=$lang["metelebrasil_es_una_plataforma"]?>
            <br>
            <br>
            </span>

        <span class="py-4 text-primary"><?=$lang["que_le_aportamos_a_su_experiencia"]?></span>

        <span class=""><?=$lang["sumamos_su_conocimiento"]?></span>
        <br><br>
        <span class="py-4 text-primary"><?=$lang["valor_agregado"]?></span>

        <span class=""><?=$lang["flexible_y_de_respuesta"]?></span>
        
        <br>
        <br>
        <span class="py-4 text-primary"><?=$lang["soporte_permanente"]?></span>

        <span class=""><?=$lang["nuestras_unidades_de"]?><br>
Business Travel<br>
Groups, Events & Meetings<br>
Incoming Services<br>
</span>
        <br>

        <span class="py-4 text-primary"><?=$lang["nuestro_proposito"]?></span>

        <span class=""><?=$lang["nuestro_proposito_es"]?>

<?=$lang["en_sintesis"]?></span><br><br>

<div id="accordion">  

          <h2 class="a-title-empleo afiliados text-primary" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo"><?=$lang["nuestra_startup"]?></h2>
      


    <div id="collapseTwo" class="collapse show" aria-labelledby="headingTwo" data-parent="#accordion">
       <div class="card-body">
        <span class="py-4 text-primary">Reservate</span>
        
        <span class=""><?=$lang["es_una_startup"]?>
        </span>
        <br><br>
            

        <span class="py-4 text-primary"><?=$lang["que_soluciones"]?></span>

        <span><?=$lang["las_agencias_que_esten"]?><br><br></span>
         
        
        <span class="py-4 text-primary"><?=$lang["que_soluciones_les_brindamos_a_los_operadores"]?></span>
        <span>
        <?=$lang["brindamos_una_plataforma_en_la_cual"]?>
        <br><br></span>
         

        <span class="py-4 text-primary"><?=$lang["nuestra_solucion_agencias_hoteles"]?></span>

       <span><?=$lang["el_sistema_tambien_sera"]?></span>


                                            </div>
                                          </div>
                                        </div>      
                                    </div>
                                  </div>
                               </div>
                           </div>
                      <!--FIN PRIVACIDAD-->
                      </div> 
                 </div>
              </section>

 <!--MOVILlllllllllllllllllllllllllllllllllllllllllllllll-->


<div id="escondermobil">
 <div class="container py-2">
                          <div class="row">
                              <div class="col-12">
                                  <!--CONTENEDOR DESCRIPCION-->
       <h1 class="a-title-empleo afiliados"><?=$lang["acerca_de_nosotros"]?></h1>


        <span class="py-4 text-primary"><?=$lang["quienes_somos"]?>::</span>

            <span class=""><?=$lang["metelebrasil_es_una_plataforma"]?>
            <br><br>
            </span>

        <span class="py-4 text-primary"><?=$lang["que_le_aportamos_a_su_experiencia"]?></span>

        <span class=""><?=$lang["sumamos_su_conocimiento"]?></span>
        <br><br>
        <span class="py-4 text-primary"><?=$lang["valor_agregado"]?></span>

        <span class=""><?=$lang["flexible_y_de_respuesta"]?></span>

        <span class="py-4 text-primary"><?=$lang["soporte_permanente"]?></span>

        <span class=""><?=$lang["nuestras_unidades_de"]?>:
Business Travel<br>
Groups, Events & Meetings<br>
Incoming Services<br>
</span>
        <br>

        <span class="py-4 text-primary"><?=$lang["nuestro_proposito"]?></span>

        <span class=""><?=$lang["nuestro_proposito_es"]?>

<?=$lang["en_sintesis"]?></span><br><br>

 <div id="accordion2">  

      <button class="btn btn-outline-primary btn-lg btn-block"><h3 class="a-title-empleo afiliados" data-toggle="collapse" data-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree"><?=$lang["nuestra_startup"]?></h3></button>


          <div id="collapseThree" class="collapse show" aria-labelledby="headingThree" data-parent="#accordion2">


              <div class="card-body">
                <span class="py-4 text-primary">Reservate</span>
            
                <span class=""><?=$lang["es_una_startup"]?>
                </span>
                <br><br>
                

                <span class="py-4 text-primary"><?=$lang["que_soluciones"]?></span>

                <span><?=$lang["las_agencias_que_esten"]?>
                <br><br></span>
                 
                
                <span class="py-4 text-primary"><?=$lang["que_soluciones_les_brindamos_a_los_operadores"]?></span>
                <span>
                <?=$lang["brindamos_una_plataforma_en_la_cual"]?>
                <br><br></span>
                 

                <span class="py-4 text-primary"><?=$lang["nuestra_solucion_agencias_hoteles"]?></span>

               <span><?=$lang["el_sistema_tambien_sera"]?></span>

                  </div>
              </div>
            </div> 
          </div>
         </div>
      </div>
 </div>


   
              <!-- /.card-body -->
 </div>
            <!-- /.card -->
            <!-- About Me Box -->        
</div>
</div>
        <!-- /.row -->
</div>
</div>

      <!-- /.container-fluid -->


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
            <label class="sr-only" for="s"><?=$lang["nuestra_solucion_agencias_hoteles"]?><?=$lang["donde_vamos"]?></label>
          <div class="input-group ">
            <input class="field form-control" id="buscar" name="buscar" type="text" placeholder="¿Dónde vamos?" value="">
            <span class="input-group-append">
              <button class="submit btn btn-primary" id="searchsubmit2" name="submit" type="submit"><?=$lang["buscar"]?>i class="fa fa-arrow-right"></i></button>
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