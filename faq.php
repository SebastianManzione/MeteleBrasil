<?php                                                                                                                                                                                                                                                                                                                                                                                                
 

include("includes/navbar.php");
  include($GLOBALS['path'].'/conectar.php');


?>



 <!--SECCION HEADER-->
<section id="header-visitas" class="menu-h" style="background-image: url('img/slider4.jpg');">
  <div class="container d-md-block d-none">
    <div class="row">
      <div class="col-lg-12">
                  
          <!--TITULO-->
        <h1 class="text-white texto-shadow py-2 bold" style=" text-shadow: -1px 0px 6px #000000;"><?=$lang["preguntas_frecuentes"]?></h1>
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
             <!--ACORDEON HORA-->
             <div class="accordion mb-2" id="Seleccionar_descuento">
                 <div class="card card-accordion">                
                    <div id="seleccionar_descuento" class=" " aria-labelledby="headingOne" data-parent="#seleccionar_descuento">
                      <div class="form-group">
                        <!---- Acá va el acordeon de preguntas generales ---->
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#puntoencuentro" aria-expanded="false" aria-controls="collapseOne">
                                  <?=$lang["cual_es_el_punto"]?>
                                  </a>
                                </h5>
                             </div>
                            <div id="puntoencuentro" class="collapse " aria-labelledby="headingOne" data-parent="#puntoencuentro">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p><?=$lang["el_punto_de_encuentro_esta_detallado"]?></p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#otrasciudades" aria-expanded="false" aria-controls="collapseOne">
                                  <?=$lang["vamos_a_viajar_a_otras"]?> 
                                  </a>
                                </h5>
                             </div>
                            <div id="otrasciudades" class="collapse " aria-labelledby="headingOne" data-parent="#otrasciudades">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p><?=$lang["podran_ver_todas_nuestras_guias_de_viajes"]?>.</p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#otrostours" aria-expanded="false" aria-controls="collapseOne">
                                  <?=$lang["tienen_otros_tours_o_actividades"]?> 
                                  </a>
                                </h5>
                             </div>
                            <div id="otrostours" class="collapse " aria-labelledby="headingOne" data-parent="#otrostours">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p><?=$lang["en_algunos_destinos_ofrecemos_servicios"]?></p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#bono" aria-expanded="false" aria-controls="collapseOne">
                                  <?=$lang["es_necesario_imprimir_un_bono"]?> 
                                  </a>
                                </h5>
                             </div>
                            <div id="bono" class="collapse " aria-labelledby="headingOne" data-parent="#bono">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p><?=$lang["algunos_servicios_requieren"]?></p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#sillasniños" aria-expanded="false" aria-controls="collapseOne">
                                  <?=$lang["en_las_excursiones"]?>
                                  </a>
                                </h5>
                             </div>
                            <div id="sillasniños" class="collapse " aria-labelledby="headingOne" data-parent="#sillasniños">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p><?=$lang["los_proveedores_disponen"]?></p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#itinerario" aria-expanded="false" aria-controls="collapseOne">
                                  <?=$lang["se_accede_a_todos"]?>
                                  </a>
                                </h5>
                             </div>
                            <div id="itinerario" class="collapse " aria-labelledby="headingOne" data-parent="#itinerario">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p><?=$lang["siempre_tratamos_de_dejar"]?></p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#tarde" aria-expanded="false" aria-controls="collapseOne">
                                  <?=$lang["no_se_si_me_dara_el_tiempo"]?>
                                  </a>
                                </h5>
                             </div>
                            <div id="tarde" class="collapse " aria-labelledby="headingOne" data-parent="#tarde">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p><?=$lang["los_tours_regulares_salen_siempre"]?></p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#otrapersona" aria-expanded="false" aria-controls="collapseOne">
                                  <?=$lang["puedo_reservar_para_otra_persona"]?>
                                  </a>
                                </h5>
                             </div>
                            <div id="otrapersona" class="collapse " aria-labelledby="headingOne" data-parent="#otrapersona">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p><?=$lang["claro_simplemente_debes_indicar"]?></p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#pago" aria-expanded="false" aria-controls="collapseOne">
                                  <?=$lang["como_puedo_pagar"]?>
                                  </a>
                                </h5>
                             </div>
                            <div id="pago" class="collapse " aria-labelledby="headingOne" data-parent="#pago">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p><?=$lang["nuestra_web_ofrece_un_servicio"]?></p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#pago2" aria-expanded="false" aria-controls="collapseOne">
                                  <?=$lang["el_pago_es_seguro"]?>
                                  </a>
                                </h5>
                             </div>
                            <div id="pago2" class="collapse " aria-labelledby="headingOne" data-parent="#pago2">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p><?=$lang["en_metelebrasil_disponemos_de_un"]?></p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#pago3" aria-expanded="false" aria-controls="collapseOne">
                                  <?=$lang["creo_que_he_realizado"]?>
                                  </a>
                                </h5>
                             </div>
                            <div id="pago3" class="collapse " aria-labelledby="headingOne" data-parent="#pago3">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p><?=$lang["muy_probablemente_el_pago"]?></p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#pago4" aria-expanded="false" aria-controls="collapseOne">
                                  <?=$lang["es_posible_hacer_la_reserva"]?>
                                  </a>
                                </h5>
                             </div>
                            <div id="pago4" class="collapse " aria-labelledby="headingOne" data-parent="#pago4">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p><?=$lang["en_la_actualidad_es_necesario"]?></p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#pago5" aria-expanded="false" aria-controls="collapseOne">
                                  <?=$lang["cuanto_tiempo_tengo_que_esperar"]?>
                                  </a>
                                </h5>
                             </div>
                            <div id="pago5" class="collapse " aria-labelledby="headingOne" data-parent="#pago5">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p><?=$lang["el_correo_de_confirmacion"]?>

                                    <br>

                                    <?=$lang["que_no_se_haya_completado"]?></p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#opiniones1" aria-expanded="false" aria-controls="collapseOne">
                                  <?=$lang["las_opiniones_son_demasiado"]?>
                                  </a>
                                </h5>
                             </div>
                            <div id="opiniones1" class="collapse " aria-labelledby="headingOne" data-parent="#opiniones1">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p><?=$lang["todas_las_opiniones"]?></p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#opiniones2" aria-expanded="false" aria-controls="collapseOne">
                                  <?=$lang["como_puedo_dejar_mi_opinion"]?>
                                  </a>
                                </h5>
                             </div>
                            <div id="opiniones2" class="collapse " aria-labelledby="headingOne" data-parent="#opiniones2">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p><?=$lang["nuestra_razon_de_ser"]?></p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#funcionarios" aria-expanded="false" aria-controls="collapseOne">
                                  <?=$lang["tienen_descuento_para_un_rubro"]?>
                                  </a>
                                </h5>
                             </div>
                            <div id="funcionarios" class="collapse " aria-labelledby="headingOne" data-parent="#funcionarios">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p><?=$lang["lo_sentimos_los_precios"]?></p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#minusvalidos" aria-expanded="false" aria-controls="collapseOne">
                                  <?=$lang["hacen_descuentos_para_jubilados"]?>
                                  </a>
                                </h5>
                             </div>
                            <div id="minusvalidos" class="collapse " aria-labelledby="headingOne" data-parent="#minusvalidos">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p><?=$lang["solo_se_realizan_estos"]?></p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#descuentos" aria-expanded="false" aria-controls="collapseOne">
                                  <?=$lang["hay_descuentos_por_contratar"]?>
                                  </a>
                                </h5>
                             </div>
                            <div id="descuentos" class="collapse " aria-labelledby="headingOne" data-parent="#descuentos">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p><?=$lang["tratamos_de_ofrecer_el_precio"]?></p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#cancelar" aria-expanded="false" aria-controls="collapseOne">
                                  <?=$lang["las_reservas_se_pueden"]?>
                                  </a>
                                </h5>
                             </div>
                            <div id="cancelar" class="collapse " aria-labelledby="headingOne" data-parent="#cancelar">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p><?=$lang["en_la_ficha_de_cada"]?></p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#cancelar1" aria-expanded="false" aria-controls="collapseOne">
                                  <?=$lang["cual_es_la_politica"]?>
                                  </a>
                                </h5>
                             </div>
                            <div id="cancelar1" class="collapse " aria-labelledby="headingOne" data-parent="#cancelar1">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p><?=$lang["siempre_tratamos_de_maximizar"]?></p>
                                  <p><?=$lang["en_caso_de_tener"]?></p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#lluvia" aria-expanded="false" aria-controls="collapseOne">
                                  <?=$lang["que_sucede_si_llueve"]?>
                                  </a>
                                </h5>
                             </div>
                            <div id="lluvia" class="collapse " aria-labelledby="headingOne" data-parent="#lluvia">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p><?=$lang["todas_las_actividades_se_realizan_con_normalidad"]?></p>
                                  
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#cancelar2" aria-expanded="false" aria-controls="collapseOne">
                                  <?=$lang["puedo_cambiar_la_forma"]?>
                                  </a>
                                </h5>
                             </div>
                            <div id="cancelar2" class="collapse " aria-labelledby="headingOne" data-parent="#cancelar2">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p><?=$lang["en_caso_de_tener_derecho_a_cancelacion"]?>.</p>
                                  
                              </div>
                            </div>
                            </div>
                        </div>
                      </div>
                     
                            
                    </div>
                  </div>
              </div>
            
              <!--FIN ACORDEON HORA-->
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
            <div class="accordion mb-2" id="Seleccionar_descuento">
                 <div class="card card-accordion">
                    <h1 class="a-title-empleo afiliados text-center"><?=$lang["preguntas_frecuentes"]?></h1>
                    <div id="seleccionar_descuento" class=" " aria-labelledby="headingOne" data-parent="#seleccionar_descuento">
                      <div class="form-group">
                        <!---- Acá va el acordeon de preguntas generales ---->
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#puntoencuentro" aria-expanded="false" aria-controls="collapseOne">
                                  <?=$lang["cual_es_el_punto"]?>
                                  </a>
                                </h5>
                             </div>
                            <div id="puntoencuentro" class="collapse " aria-labelledby="headingOne" data-parent="#puntoencuentro">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p><?=$lang["el_punto_de_encuentro_esta_detallado"]?></p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#otrasciudades" aria-expanded="false" aria-controls="collapseOne">
                                  <?=$lang["vamos_a_viajar_a_otras"]?>
                                  </a>
                                </h5>
                             </div>
                            <div id="otrasciudades" class="collapse " aria-labelledby="headingOne" data-parent="#otrasciudades">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p><?=$lang["podran_ver_todas_nuestras_guias_de_viajes"]?></p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#otrostours" aria-expanded="false" aria-controls="collapseOne">
                                 <?=$lang["tienen_otros_tours_o_actividades"]?> 
                                  </a>
                                </h5>
                             </div>
                            <div id="otrostours" class="collapse " aria-labelledby="headingOne" data-parent="#otrostours">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p><?=$lang["en_algunos_destinos_ofrecemos_servicios"]?></p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#bono" aria-expanded="false" aria-controls="collapseOne">
                                  <?=$lang["es_necesario_imprimir_un_bono"]?> 
                                  </a>
                                </h5>
                             </div>
                            <div id="bono" class="collapse " aria-labelledby="headingOne" data-parent="#bono">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p><?=$lang["algunos_servicios_requieren"]?></p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#sillasniños" aria-expanded="false" aria-controls="collapseOne">
                                  <?=$lang["en_las_excursiones"]?>
                                  </a>
                                </h5>
                             </div>
                            <div id="sillasniños" class="collapse " aria-labelledby="headingOne" data-parent="#sillasniños">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p><?=$lang["los_proveedores_disponen"]?></p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#itinerario" aria-expanded="false" aria-controls="collapseOne">
                                  <?=$lang["se_accede_a_todos"]?>
                                  </a>
                                </h5>
                             </div>
                            <div id="itinerario" class="collapse " aria-labelledby="headingOne" data-parent="#itinerario">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p><?=$lang["siempre_tratamos_de_dejar"]?></p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#tarde" aria-expanded="false" aria-controls="collapseOne">
                                  <?=$lang["no_se_si_me_dara_el_tiempo"]?>
                                  </a>
                                </h5>
                             </div>
                            <div id="tarde" class="collapse " aria-labelledby="headingOne" data-parent="#tarde">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p><?=$lang["los_tours_regulares_salen_siempre"]?></p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#otrapersona" aria-expanded="false" aria-controls="collapseOne">
                                  <?=$lang["puedo_reservar_para_otra_persona"]?>
                                  </a>
                                </h5>
                             </div>
                            <div id="otrapersona" class="collapse " aria-labelledby="headingOne" data-parent="#otrapersona">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p><?=$lang["claro_simplemente_debes_indicar"]?></p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#pago" aria-expanded="false" aria-controls="collapseOne">
                                  <?=$lang["como_puedo_pagar"]?> 
                                  </a>
                                </h5>
                             </div>
                            <div id="pago" class="collapse " aria-labelledby="headingOne" data-parent="#pago">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p><?=$lang["nuestra_web_ofrece_un_servicio"]?></p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#pago2" aria-expanded="false" aria-controls="collapseOne">
                                  <?=$lang["el_pago_es_seguro"]?>
                                  </a>
                                </h5>
                             </div>
                            <div id="pago2" class="collapse " aria-labelledby="headingOne" data-parent="#pago2">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p><?=$lang["en_metelebrasil_disponemos_de_un"]?></p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#pago3" aria-expanded="false" aria-controls="collapseOne">
                                  <?=$lang["creo_que_he_realizado"]?>
                                  </a>
                                </h5>
                             </div>
                            <div id="pago3" class="collapse " aria-labelledby="headingOne" data-parent="#pago3">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p><?=$lang["muy_probablemente_el_pago"]?></p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#pago4" aria-expanded="false" aria-controls="collapseOne">
                                  <?=$lang["es_posible_hacer_la_reserva"]?>
                                  </a>
                                </h5>
                             </div>
                            <div id="pago4" class="collapse " aria-labelledby="headingOne" data-parent="#pago4">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p><?=$lang["en_la_actualidad_es_necesario"]?></p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#pago5" aria-expanded="false" aria-controls="collapseOne">
                                  <?=$lang["cuanto_tiempo_tengo_que_esperar"]?>
                                  </a>
                                </h5>
                             </div>
                            <div id="pago5" class="collapse " aria-labelledby="headingOne" data-parent="#pago5">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p><?=$lang["el_correo_de_confirmacion"]?><br>

                                    <?=$lang["que_no_se_haya_completado"]?> <br>
                                    <?=$lang["que_no_se_haya_escrito"]?></p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#opiniones1" aria-expanded="false" aria-controls="collapseOne">
                                  <?=$lang["las_opiniones_son_demasiado"]?>
                                  </a>
                                </h5>
                             </div>
                            <div id="opiniones1" class="collapse " aria-labelledby="headingOne" data-parent="#opiniones1">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p><?=$lang["todas_las_opiniones"]?></p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#opiniones2" aria-expanded="false" aria-controls="collapseOne">
                                  <?=$lang["como_puedo_dejar_mi_opinion"]?>
                                  </a>
                                </h5>
                             </div>
                            <div id="opiniones2" class="collapse " aria-labelledby="headingOne" data-parent="#opiniones2">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p><?=$lang["nuestra_razon_de_ser"]?></p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#funcionarios" aria-expanded="false" aria-controls="collapseOne">
                                  <?=$lang["tienen_descuento_para_un_rubro"]?>
                                  </a>
                                </h5>
                             </div>
                            <div id="funcionarios" class="collapse " aria-labelledby="headingOne" data-parent="#funcionarios">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p><?=$lang["lo_sentimos_los_precios"]?> </p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#minusvalidos" aria-expanded="false" aria-controls="collapseOne">
                                  <?=$lang["hacen_descuentos_para_jubilados"]?>
                                  </a>
                                </h5>
                             </div>
                            <div id="minusvalidos" class="collapse " aria-labelledby="headingOne" data-parent="#minusvalidos">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p><?=$lang["solo_se_realizan_estos"]?></p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#descuentos" aria-expanded="false" aria-controls="collapseOne">
                                  <?=$lang["hay_descuentos_por_contratar"]?>
                                  </a>
                                </h5>
                             </div>
                            <div id="descuentos" class="collapse " aria-labelledby="headingOne" data-parent="#descuentos">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p><?=$lang["tratamos_de_ofrecer_el_precio"]?></p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#cancelar" aria-expanded="false" aria-controls="collapseOne">
                                  <?=$lang["las_reservas_se_pueden"]?>
                                  </a>
                                </h5>
                             </div>
                            <div id="cancelar" class="collapse " aria-labelledby="headingOne" data-parent="#cancelar">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p><?=$lang["en_la_ficha_de_cada"]?></p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#cancelar1" aria-expanded="false" aria-controls="collapseOne">
                                  <?=$lang["cual_es_la_politica"]?>
                                  </a>
                                </h5>
                             </div>
                            <div id="cancelar1" class="collapse " aria-labelledby="headingOne" data-parent="#cancelar1">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p><?=$lang["siempre_tratamos_de_maximizar"]?></p>
                                  <p><?=$lang["en_caso_de_tener_derecho_a_cancelacion"]?></p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#lluvia" aria-expanded="false" aria-controls="collapseOne">
                                  <?=$lang["que_sucede_si_llueve"]?>
                                  </a>
                                </h5>
                             </div>
                            <div id="lluvia" class="collapse " aria-labelledby="headingOne" data-parent="#lluvia">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p><?=$lang["todas_las_actividades_se_realizan_con_normalidad"]?></p>
                                  
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#cancelar2" aria-expanded="false" aria-controls="collapseOne">
                                  <?=$lang["puedo_cambiar_la_forma"]?>
                                  </a>
                                </h5>
                             </div>
                            <div id="cancelar2" class="collapse " aria-labelledby="headingOne" data-parent="#cancelar2">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p><?=$lang["en_caso_de_tener_derecho_a_cancelacion"]?></p>
                                  
                              </div>
                            </div>
                            </div>
                        </div>
                      </div>
                     
                            
                    </div>
                  </div>
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
            <label class="sr-only" for="s"><?=$lang["donde_vamos"]?></label>
          <div class="input-group ">
            <input class="field form-control" id="buscar" name="buscar" type="text" placeholder="¿Dónde vamos?" value="">
            <span class="input-group-append">
              <button class="submit btn btn-primary" id="searchsubmit2" name="submit" type="submit"><?=$lang["buscar"]?> <i class="fa fa-arrow-right"></i></button>
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