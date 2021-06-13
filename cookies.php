<?php
include("includes/navbar.php");




?>




 <!--SECCION HEADER-->
<section id="header-visitas" class="menu-h" style="background-image: url('img/slider4.jpg');">
  <div class="container d-md-block d-none">
    <div class="row">
      <div class="col-lg-12">
          
          <!--BUCLE DE LOS RESULTADOS AQUI--> 
        <div class="badge badge-primary badge-ciudad"><?=$lang["politicas_de_cookies"]?></div>
         <!--FIN BUCLE DE LOS RESULTADOS AQUI-->
         
          <!--TITULO-->
        <h1 class="text-white texto-shadow py-2 bold" style=" text-shadow: -1px 0px 6px #000000;"><?=$lang["politicas_de_cookies"]?></h1>
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


<section class="py-5 d-md-block">
  <div class="container container_r clearfix">
      <div class="row">
           <!--COL INFORMACION IZQUIERDA-->
          <div class="col-lg-12">
             <div id="content">
                 
            <div class="o-container-work-us">
            <h1 class="a-title-empleo afiliados"><?=$lang["politicas_de_cookies"]?></h1>

        <span class="a-sub-empleo"><?=$lang["el_presente_sitio_web_en_adelante_el_sitio_web_utiliza"]?></span>

         <br><br>

        <span class="py-4 text-primary"><?=$lang["1_que_son_las_cookies"]?></span>

            <span class=""><?=$lang["las_cookies_son_pequenos"]?>
            <br><br>
            <?=$lang["la_informacion_recogida_por_las_cookies"]?></span>

             <br><br>

        <span class="py-4 text-primary"><?=$lang["2_tipos_de_cookies"]?></span>

        <span class=""><?=$lang["el_presente_sitio_web_utiliza_cookies"]?></span>
        <br><br>

        <h5><?=$lang["cookies_propias"]?></h5>

        <span class=""><?=$lang["cookies_de_metelebrasil"]?></span>
        <br><br>
        


        <h5><?=$lang["cookies_de_terceros"]?></h5>

        <span class=""><b><?=$lang["cookies_de_analitica"]?></b>


            <?=$lang["estas_cookies_permiten"]?></span>
        <br><br>
       


        <span class=""><b><?=$lang["cookies_de_publicidad"]?></b>

            <?=$lang["permiten_la_gestion"]?></span>
        <br><br>
       


        <span class="py-4 text-primary"><?=$lang["3_web_beacons"]?></span>

        <span class=""><?=$lang["este_sitio_web_tambien_utiliza_web_beacons"]?></span>

         <br><br>

        <span class="py-4 text-primary"><?=$lang["4_como_deshabilitar_las_cookies"]?></span>

        <span class=""><?=$lang["la_mayoria_de_los_navegadores"]?>
            <br><br>
            <?=$lang["los_siguientes_links_muestren"]?></span>
        <br><br>

        <h5><?=$lang["1_intetnet_explorer"]?></h5>

        <ul class="listado">

            <li><?=$lang["en_el_menu_de_herramientas"]?></li>

            <li><?=$lang["haga_clic_en_la_pestana"]?></li>

            <li><?=$lang["podra_configurar_la_privacidad"]?>
            </li>

        </ul>
        <br>

        <h5><?=$lang["2_mozilla"]?></h5>

        <ul class="listado">

            <li><?=$lang["en_la_parte_superior"]?></li>

            <li><?=$lang["seleccionar_opciones"]?></li>

            <li><?=$lang["seleccionar_el_panel_privacidad"]?></li>

            <li><?=$lang["en_la_opcion_firefox"]?>
            </li>

        </ul>
        <br>

        <h5><?=$lang["3_google_chrome"]?></h5>

        <ul class="listado">

            <li><?=$lang["hacer_clic_en_el_menu_situado"]?></li>

            <li><?=$lang["seleccionar_configuracion"]?></li>

            <li><?=$lang["hacer_clic_en_mostrar"]?></li>

            <li><?=$lang["en_la_seccion_privacidad"]?></li>

            <li><?=$lang["en_la_seccion_de_cookies"]?></li>

        </ul>

        <br>
        <h5><?=$lang["4_safari"]?></h5>

        <ul class="listado">

            <li><?=$lang["en_el_menu_de_configuracion"]?></li>

            <li><?=$lang["abra_la_pestana"]?></li>

            <li><?=$lang["seleccione_la_opcion"]?></li>

            <li><?=$lang["recuerde_que_ciertas"]?>
            </li>

        </ul>

         <br><br>

        <span class="py-4 text-primary"><?=$lang["5_cookies"]?></span>

        <span class=""><?=$lang["el_presente_sitio"]?>
            <br><br>
            <?=$lang["si_desea_modificar"]?>
            <br><br>
       
        <span class="py-4 text-primary"><?=$lang["6_aceptacion_de_cookies"]?></span>

        <span class=""><?=$lang["si_usted_continua"]?>
            web.
            <br><br>
            <?=$lang["le_informamos_que"]?></span>

        </div>

        </div>
        
            </div>
        <!--FIN PRIVACIDAD-->
        </div> 
           </div>
          </section>

 <div class="container py-2">
                          <div class="row">
                              <div class="col-12"></div>
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
              <button class="submit btn btn-primary" id="searchsubmit2" name="submit" type="submit"><?=$lang["buscar"]?><i class="fa fa-arrow-right"></i></button>
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
                            <h4 class=" mb-0"><a href="#" class="text-destinos">Rio Janeiro</a></h4>
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