                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     
<?php
include("includes/navbar.php");





?>



 <!--SECCION HEADER-->
<section id="header-visitas" class="menu-h" style="background-image: url('img/slider4.jpg');">
  <div class="container d-md-block d-none">
    <div class="row">
      <div class="col-lg-12">
          
          <!--BUCLE DE LOS RESULTADOS AQUI--> 
        <div class="badge badge-primary badge-ciudad"><?=$lang["sostenibilidad"]?></div>
         <!--FIN BUCLE DE LOS RESULTADOS AQUI-->
         
          <!--TITULO-->
        <h1 class="text-white texto-shadow py-2 bold" style=" text-shadow: -1px 0px 6px #000000;"><?=$lang["sostenibilidad"]?></h1>
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





<section class="d-md-block">
  <div class="container container_r clearfix">
      <div class="row">
           <!--COL INFORMACION IZQUIERDA-->
          <div class="col-lg-12">
             <div id="content">
                 
            <div class="o-container-work-us">
        
         <br>

            <span class="py-4 text-primary"></span>

            <p><b>METELEBRASIL </b><?=$lang["es_una_plataforma_de_reservas"]?></p>

            
            <p><?=$lang["integramos_en_una_plataforma"]?></p>

       

            <span class="py-4 text-primary"></span>

            <p><?=$lang["resolvemos_todos_los_esquemas"]?></p>


     <!-- ---------------------------------------------------------------------------------------------------------- -->

           


            <!-- ---------------------------------------------------------------------------------------------------------- -->

           <h3><span class="py-4 text-primary"><b><?=$lang["impacto_socio_ambiental"]?></b></span></h3>

            <p><?=$lang["nuestro_sistema_integrado"]?>


           <br>
            <li><?=$lang["nuestro_sistema_soluciona"]?>

<br><br>

            <h3><span class="py-4 text-primary"><b><?=$lang["impacto_economico"]?></b></span></h3>



            <?=$lang["nuestro_impacto_en_el_turismo"]?></p>

            <p></p>

                        <p><?=$lang["en_nuestro_ecosistema"]?>

            <i>“<b></b>”</i><?=$lang["cualquier_turista"]?></p>

            <!-- ---------------------------------------------------------------------------------------------------------- -->

            <h3><span class="py-4 text-primary"><b><?=$lang["metas_objetivos"]?></b></span></h3>

            <?=$lang["podemos_generar_mas_trabajo"]?>
            <br><br>

            <li><?=$lang["los_operadores_tendran_la_posibilidad"]?><?=$lang["ejemplo_una_agencia"]?>
            <br> <br>
            <li><?=$lang["nuestro_principal_objetivo_es_vender"]?>
            <br><br>
             <p><b><?=$lang["creemos_que_de_esta_manera"]?></b></p>

             <?=$lang["este_sistema_esta_implementado"]?>
<br><br>
             <b><?=$lang["si_estas_interesado"]?></b>



            

           

       <a href="mail : COMERCIAL@METELEBRASIL.com">comercial@METELEBRASIL.com</a>

           
            <!-- ---------------------------------------------------------------------------------------------------------- -->

            <br><br><br>




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
                            <h4 class=" mb-0"><a href="#" class="text-destinos">Rio de Janeiro</a></h4>
                            <small>Brasil</small>
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