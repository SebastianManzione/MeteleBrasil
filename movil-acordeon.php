<!--CONTENEDOR DE ACORDEON-->

<div class="container d-md-none">
  <div class="row">
    <div class="col-12" style="padding-left: 0px;padding-right: 0px;">
       <!--ACORDEON DESCRIPCION-->
       <div class="accordion" id="accordionExample-movil">
                 <div class="card card-accordion">
                    <div class="" id="headingOne-Descripcion">
                      <h5 class="mb-0">
                        <a class="btn btn-accordion bg-white border-top btn-accordion-dark-show " id="btn-descripcion" href="#" data-toggle="collapse" data-target="#descripcion-movil" aria-expanded="true" aria-controls="collapseOne">
                          <?=$lang["descripcion"]?> <i class="fa fa-sort-down text-primary float-right"></i>
                        </a>
                      </h5>
                    </div>

                    <div id="descripcion-movil" class="collapse show" aria-labelledby="headingOne-Descripcion" data-parent="#accordionExample-movil">
                      <div class="container py-2">
                          <div class="row">
                              <div class="col-12">
                                  <!--CONTENEDOR DESCRIPCION-->
        <div class="descripcion" id="descripcion">

          <!--TEXTO DESTACADO-->
          <p><?php echo DescripcionCortaServicio($id); ?>.</p>
          <!--FIN TEXTO DESTACADO-->

          <!--SLIDER-->

          <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
              <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
              <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
              <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
            </ol>
              <div class="carousel-inner">
                <!--CARGA DE IMAGENES-->
                                                <?php //********************arranca fotos*******************************************
      # code...  
    $nroFotos=count(DevuelveFotosServicio($id));
    $fotos=DevuelveFotosServicio($id);
    for ($i=0; $i < $nroFotos ; $i++) { 
      if ($i==0) {
        $classe="carousel-item active";
      }
      else{
        $classe="carousel-item";
      }
      echo '
       <div class="'. $classe.'">
                  <img class="d-block w-100 img-slider-servicio" src="sistema/img/uploads/'.$fotos[$i].'" alt="First slide">
                  <div class="carousel-caption d-none d-md-block">
                    <h5>'.DescripcionCortaServicio($id).'</h5>
                  </div>
                </div>
   
   
 ';
    }
    //********************fin fotos*******************************************?>
                <!--FIN CARGA DE IMAGENES-->
         
              </div>
                <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                  <span class="sr-only"><?=$lang["anterior"]?></span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                  <span class="sr-only"><?=$lang["Proxima"]?></span>
                </a>
           </div>
        <!--FIN SLIDER-->

        <!--TEXTO VISITA-->

        <h4 class="py-4 text-primary"><?=$lang["que_se_visita"]?></h4>

        <p class=""><?php echo DescripcionServicio($id); ?>.</p>

        <ul>
    
        </ul>



        <!--FIN TEXTO VISITA-->

        <!--TEXTO IMPORTANTE-->

         <h4 class="py-4 text-primary"> Importante</h4>

         <ul>
           <li> <?php echo ObservacionesServicio($id); ?> </li>
         </ul>

        <!--FIN TEXTO IMPORTANTE-->

        <!--TEXTO RECOGIDA-->

        <h4 class="py-4 text-primary"><?=$lang["recogida_en_el_hotel"]?></h4>

       <p><?=$lang["opcionalmente_podes_reservar_la_recogida"]?></p>

  
        <!--FIN TEXTO RECOGIDA-->  

        </div>
        <!--FIN CONTENEDOR DESCRIPCION-->
                              </div>
                          </div>
                      </div>
                  </div>
              </div>

        <!--ACORDEON DESCRIPCION-->



<!--ACORDEON PRECIO-->
            
          
                 <div class="card card-accordion">
                    <div class="" id="headingOne-Precio">
                      <h5 class="mb-0">
                        <a class="btn btn-accordion bg-white border-top  " id="btn-precio" href="#" data-toggle="collapse" data-target="#precio-movil" aria-expanded="true" aria-controls="collapseOne">
                          <?=$lang["precio"]?> <i class="fa fa-sort-down text-primary float-right"></i>
                        </a>
                      </h5>
                    </div>

                    <div id="precio-movil" class="collapse " aria-labelledby="headingOne-Precio" data-parent="#accordionExample-movil">
                      <div class="container py-2">
                        <div class="row" >
                          <div class="col-12">
                               <!-- CONTENEDOR PRECIO-->
        <div id="precio-movil-p">
          <!--TEXTO PRECIO-->

         <h4 class="py-4 text-primary">Precio</h4>

         <div class="container">
        <div class="row">
             <div class="col-lg-3 col-12 my-auto">
               <p class="popular text-center mb-0 "><i class="fa fa-star"></i> <?=$lang["mas_popular"]?></p>
               <p><strong></strong></p>
             </div>
             <div class="col-lg-2 col-5">
               <p class="text-center "><?=$lang["adultos"]?></p>
               <div class="circulo-b">
                 <p class="text-center text-primary"><?= $sym." ".PrecioSugeridoServicio2($id, $money, $impuestosPais); ?></p>
               </div>
             </div>
              <div class="col-lg-2 col-5">
               <p class="text-center "><?=$lang["menores_12_anos"]?></p>
               <div class="circulo-b">
                 <p class="text-center text-primary"><?= $sym." ".PrecioSugeridoServicio12($id, $money, $impuestosPais); ?></p>
               </div>
             </div>
             <div class="col-lg-2 col-5">
               <p class="text-center "><?=$lang["menores_5_anos"]?></p>
               <div class="circulo-b">
                 <p class="text-center text-primary"><?= $sym." ".PrecioSugeridoServicio5($id, $money, $impuestosPais); ?></p>
               </div>
             </div>
                 <div class="col-lg-2 col-5">
               <p class="text-center "><?=$lang["menores_3_anos"]?></p>
               <div class="circulo-b">
                 <p class="text-center text-primary"><?= $sym." ".PrecioSugeridoServicio3($id, $money, $impuestosPais); ?></p>
               </div>
             </div>
           </div>
         <!--  <hr class="hr-puntuada">  <br>

          
           <div class="row">
             <div class="col-lg-3">
               <p><strong>Tour con recogida</strong></p>
             </div>
             <div class="col-lg-3 col-4">
               <p class="text-center ">Adultos</p>
               <div class="circulo-b">
                 <p class="text-center text-primary"><?= PrecioSugeridoServicio2($id, $money, $impuestosPais)." ".$sym; ?></p>
               </div>
             </div>
             <div class="col-lg-3 col-4">
               <p class="text-center ">Adultos</p>
               <div class="circulo-b">
                 <p class="text-center text-primary"><?= PrecioSugeridoServicio2($id, $money, $impuestosPais)." ".$sym; ?></p>
               </div>
             </div>
             <div class="col-lg-3 col-4">
               <p class="text-center ">Adultos</p>
               <div class="circulo-b">
                 <p class="text-center text-primary"><?= PrecioSugeridoServicio2($id, $money, $impuestosPais)." ".$sym; ?></p>
               </div>
             </div>
           </div>
-->

         </div>
        </div>
        <!--FIN CONTENEDOR PRECIO-->
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
            
            <!--FIN ACORDEON PRECIO-->


   <!--ACORDEON HORARIOS-->

 
     <div class="card card-accordion">
        <div class="" id="headingOne">
          <h5 class="mb-0">
            <a class="btn btn-accordion bg-white border-top  "  data-toggle="modal" data-target="#modalhorario-movil">
              <?=$lang["elegi_la_fecha"]?><i class="fa fa-sort-down text-primary float-right"></i>
            </a>
          </h5>
        </div>
     </div>



  <!--FIN ACORDEON HORARIOS-->


  <!--ACORDEON DETALLES-->
            
         
                 <div class="card card-accordion">
                    <div class="" id="headingOne-Detalles">
                      <h5 class="mb-0">
                        <a class="btn btn-accordion bg-white border-top" id="btn-detalles" href="#" data-toggle="collapse" data-target="#detalles-movil" aria-expanded="true" aria-controls="collapseOne">
                          <?=$lang["detalles_"]?><i class="fa fa-sort-down text-primary float-right"></i>
                        </a>
                      </h5>
                    </div>

                    <div id="detalles-movil" class="collapse " aria-labelledby="headingOne-Detalles" data-parent="#accordionExample-movil">
                      <div class="container py-2">
                        <div class="row">
                           <div class="col-12">
                                <!-- CONTENEDOR DETALLES-->

        <div id="detalles">
          <!--TEXTO DETALLE-->

        <h4 class="py-4 text-primary"><?=$lang["detalles_"]?></h4>

        <h5 class="semibold"><i class="fa fa-hourglass"></i><?=$lang["duracion_"]?></h5>
        <p class="mx-4"> <?= DuracionServicio($id)[0];?> - <?= DuracionServicio($id)[1];?></p>

        <h5 class="semibold"><i class="fa fa-language"></i><?=$lang["idioma"]?></h5>
        <p class="mx-4"><?=$lang["el_tour_se_realiza_en"]?></p>

        <h5 class="semibold"><i class="fa fa-exclamation-circle"></i><?=$lang["incluido_"]?></h5>
        <ul class="">
          
    
 <?php

//********************sv adicionales incluidos*******************************************

for ($i=0; $i < count(svAdicionalesIncluidos($id)); $i++) { 
 echo '<li>'.svAdicionalesIncluidos($id)[$i].'</li>'
 ;
}

//********************fin sv adicionales incluidos*******************************************
 ?>


        </ul>

        <h5 class="semibold"><i class="fa fa-exclamation-triangle"></i><?=$lang["no_incluido"]?></h5>
        <ul class="">
          
<?php
//*************************************sv no incluidos*********************
/*
$registros= count(svAdicionalesNoIncluidos($id)) ;
for ($i=0; $i < $registros ; $i++) { 
 $registrosZ= count(svAdicionalesNoIncluidos($id)[$i]);

 
  if (svAdicionalesNoIncluidos($id)[$i][5]=="false") {
    echo ('<li>'.svAdicionalesNoIncluidos($id)[$i][2].' '.svAdicionalesNoIncluidos($id)[$i][3].svAdicionalesNoIncluidos($id)[$i][4]." </li> ");


    }
    echo '</p> ';
}
//*************************************fin no incluidos*************
*/
 ?> 


        </ul>

        <h5 class="semibold"><i class="fa fa-calendar-alt"></i><?=$lang["cuando_reservar"]?></h5>
        <p class="mx-4"><?=$lang["reserva_cuanto_antes_para"]?></p>

        <p  class="mx-4"><?=$lang["se_permiten_reservas_hasta_las_23"]?></p>

        <h5 class="semibold"><i class="fa fa-file"></i><?=$lang["justificante"]?></h5>
        <p class="mx-4"><?=$lang["te_enviaremos_un_email"]?></p>

        <h5 class="semibold"><i class="fa fa-question-circle mb-4"></i> <?=$lang["preguntas_frecuentes"]?></h5>

        <!--PREGUNTAS FRECUENTES-->

        <div id="accordion" class="mb-4">
          <div class="card card-faq">
            <div class="card-header" id="headingOne">
              <h5 class="mb-0">
                <a href="#" class=" btn-faq" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                <strong><?=$lang["viajero"]?></strong><?=$lang["es_posible_organizar"]?>
                </a>
              </h5>
            </div>
            <div id="collapseOne" class="collapse " aria-labelledby="headingOne" data-parent="#accordion">
              <div class="card-body">
             <p class="mx-4"><strong>Metelebrasil</strong><?=$lang["si_la_visita_puede_ser"]?></p>
              </div>
            </div>
          </div>
       </div>
        <!--FIN PREGUNTAS FRECUENTES-->

         <?php if(Accesibilidad($id)){
          echo '<h5><i class="fa fa-wheelchair"></i> Accesibilidad </h5>
        <p class="mx-4">Si (nuestras actividades son aptas personas de movilidad reducida, y carritos de bebé).</p>';
        }


        ?>


         </div>
        <!--FIN TEXTO DETALLE-->
        


        <!--FIN CONTENEDOR DETALLES-->
                           </div>
                        </div>
                      </div>
                    </div>
                  </div>
            
            <!--FIN ACORDEON DETALLES-->


             <!--
           
                 <div class="card card-accordion">
                    



                    <div class="" id="headingOne-Encuentros">
                      <h5 class="mb-0">
                        <a class="btn btn-accordion bg-white border-top  " id="btn-encuentro" href="#" data-toggle="collapse" data-target="#encuentros-movil" aria-expanded="true" aria-controls="collapseOne">
                          Encuentros <i class="fa fa-sort-down text-primary float-right"></i>
                        </a>
                      </h5>
                    </div>

                    ACORDEON PUNTOS DE ENCUENTRO-->

                    <div id="encuentros-movil" class="collapse " aria-labelledby="headingOne-Encuentros" data-parent="#accordionExample-movil">
                      <div class="container py-2">
                        <div class="row" >
                          <div class="col-12">
                               <!--CONTENEDOR PUNTO DE ENCUENTRO-->
      <div id="punto-encuentro">

      <!--TEXTO PUNTO DE ENCUENTRO-->

        <h4 class="py-4 text-primary">Punto de encuentro</h4>

         <!--TEXTO DESTACADO-->
          <p><?= DevuelveDireccionServicio($id); ?>.</p>
         <!--FIN TEXTO DESTACADO-->


         <!--MAPA-->

         <div class="container-fluid">
           <div class="row">
             <div class="col-lg-12 py-2">
                 <div class="embed-responsive embed-responsive-16by9">
                    <iframe src = "https://maps.google.com/maps?q=<?=DevuelveCoordenadasServicio($id)["latitud"]?>,<?=DevuelveCoordenadasServicio($id)["longitud"]?>&hl=es;z=14&amp;output=embed"></iframe>
                 </div>
             </div>
           </div>
         </div>


             <!--FIN MAPA-->

            <!--FIN TEXTO PUNTO DE ENCUENTRO-->
            </div>
          <!--FIN CONTENEDOR PUNTO DE ENCUENTRO-->
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
            
            <!--FIN ACORDEON PUNTOS DE ENCUENTRO-->

              <!--ACORDEON CANCELACIONES-->
            
           
                 <div class="card card-accordion">
                    <div class="" id="headingOne-Cancelaciones">
                      <h5 class="mb-0">
                        <a class="btn btn-accordion bg-white border-top  " id="btn-cancelaciones" href="#" data-toggle="collapse" data-target="#cancelaciones-movil" aria-expanded="true" aria-controls="collapseOne">
                          Cancelaciones <i class="fa fa-sort-down text-primary float-right"></i>
                        </a>
                      </h5>
                    </div>

                    <div id="cancelaciones-movil" class="collapse " aria-labelledby="headingOne-Cancelaciones" data-parent="#accordionExample-movil">
                      <div class="container py-2">
                        <div class="row">
                          <div class="col-12">
                                 <!-- CONTENEDOR CANCELACION-->
                          <div id="cancelaciones"> 
                            <h4 class="py-4 text-primary">Cancelaciones</h4>
                
                                  <?php 

echo '   <p>'.Cancelaciones($id)[0][1].'</p> ';
 ?>
                          </div>
                           <!--FIN CONTENEDOR CANCELACION-->
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
        
            
            <!--FIN ACORDEON CANCELACIONES-->

             <!--ACORDEON OPINIONES-->
            
                 <div class="card card-accordion">
                    <div class="" id="headingOne-Cancelaciones">
                      <h5 class="mb-0">
                        <a class="btn btn-accordion bg-white border-top  " id="btn-opiniones" href="#" data-toggle="collapse" data-target="#opiniones-movil" aria-expanded="true" aria-controls="collapseOne">
                          Opiniones <i class="fa fa-sort-down text-primary float-right"></i>
                        </a>
                      </h5>
                    </div>

                    <div id="opiniones-movil" class="collapse " aria-labelledby="headingOne-Cancelaciones" data-parent="#accordionExample-movil">
                      <div class="container py-2">
                        <div class="row">
                          <div class="col-12">
                               <!-- CONTENEDOR OPINIONES-->
          <div class="mb-5" id="opiniones">
              <div class="row no-gutters">
                <div class="col-lg-8">
                  <h4 class="py-4 text-primary">Opiniones de nuestros clientes</h4>
                  <p>Todas las opiniones han sido escritas por clientes reales que han reservado con nosotros.</p>
                </div>
                <div class="col-lg-4">
                  <div class="total-opinion">
                    <h2 class="text-primary semibold" ><?= $puntuacion?> <small class="text-gris"><?= $CantOpinionesServicio;?> opiniones</small></h2>
                  </div>
                </div>
              </div>
              <br>
              <div class="row no-gutters">
                <div class="col-lg-12">
                  <p class="mb-4">Mostrando 1 de <?= $CantOpinionesServicio;?> opiniones</p>
                 <!-- CARGA BUCLE OPINIONES-->
                 <?php 


for ($i=0; $i < $CantOpinionesServicio; $i++) { 
if($i==0){
$texto=OpinionesDeServicioTexto($OpinionesServicio[$i][5]);
  echo '
 <div class="card card-opiniones mb-5">
                    <div class="card-body">
                      <div class="container">
                        <div class="row">
                          <div class="col-lg-6 col-12">
                            <i class="fa fa-star text-primary"></i>
                            <i class="fa fa-star text-primary"></i>
                            <i class="fa fa-star text-primary"></i>
                            <i class="fa fa-star text-primary"></i>
                            <i class="fa fa-star text-primary"></i>
                            <p>'.$OpinionesServicio[$i][1].'.</p>
                          </div>
                          <div class="col-lg-2 col-4 text-center">
                          .'.$texto.'.
                          </div> 
                          <div class="col-lg-2 col-4">
      <p class="mb-0 title-nombre">'.$OpinionesServicio[$i][0].'</p>
                            <p>'.$OpinionesServicio[$i][4].'</p>
                          </div>
                          <div class="col-lg-2 col-4">
                            <p class="t_fecha">'.$OpinionesServicio[$i][3].'</p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>



  ';
}



} ?>


                   <!-- FIN CARGA BUCLE OPINIONES-->
                  
                </div>
              </div>
               <!--Mas opiniones-->
              <div class="row collapse" id="Ver-mas-opinones-movil">
                 <div class="col-lg-12">
                  <p class="mb-4">Mostrando 2 de <?= $CantOpinionesServicio;?> opiniones</p>
                 <!-- CARGA BUCLE OPINIONES-->
           <?php 

$texto="";
for ($i=1; $i < $CantOpinionesServicio; $i++) { 

$texto=OpinionesDeServicioTexto($OpinionesServicio[$i][5]);
  echo '
  <div class="card card-opiniones mb-5">
                    <div class="card-body">
                      <div class="container">
                        <div class="row">
                          <div class="col-lg-6 col-12">
                              <i class="fa fa-star text-primary"></i>
                            <i class="fa fa-star text-primary"></i>
                            <i class="fa fa-star text-primary"></i>
                            <i class="fa fa-star text-primary"></i>
                            <i class="fa fa-star text-primary"></i>
                            <p>'.$OpinionesServicio[$i][1].'.</p>
                          </div>
                          <div class="col-lg-2 col-4 text-center">
                          .'.$texto.'.
                          </div> 
                          <div class="col-lg-2 col-4">
      <p class="mb-0 title-nombre">'.$OpinionesServicio[$i][0].'</p>
                            <p>'.$OpinionesServicio[$i][4].'</p>
                          </div>
                          <div class="col-lg-2 col-4">
                            <p class="t_fecha">'.$OpinionesServicio[$i][3].'</p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>



  ';




} ?>

                   <!-- FIN CARGA BUCLE OPINIONES-->
                 
                </div>
              </div>
              <!--Mas opiniones-->
               <div class="row text-center">
                <div class="col-md-12">
                    <a class="btn btn-white-destinos" href="#" id="alternar-panel-oculto-1" data-toggle="collapse" data-target="#Ver-mas-opinones-movil" aria-expanded="false" aria-controls="Ver-mas-opinones-movil">
                      Ver todas las opiniones
                    </a>
                </div>
              </div>
          </div>
           <!--FIN CONTENEDOR OPINIONES-->
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
              </div>
            
            <!--FIN ACORDEON OPINIONES-->


    </div>
  </div>
</div>



<!--CONTENEDOR DE ACORDEON-->


<!--MODAL HORARIO-->


          <div class="modal fade" id="modalhorario-movil" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document" style="margin-top: 0px;margin-left: 0px;margin-right: 0px;margin-bottom: 0px;">
    <div class="modal-content" style="border-radius:0!important;">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true" class="fa fa-arrow-left float-left text-white"></span>
        </button>
        <h5 class="text-white" style="position:absolute;margint-left:0;margin-right:0; left:70px;">Selecciona fecha y hora</h5>
      </div>
      <div class="modal-body">
            <div class="container py-2">
            <div class="row">
              <div class="col-12">
                <div class="div-precios text-right d-md-block d-none" >
            <small>55 US$</small>
            <h2 class="text-primary"><span>49</span> US$</h2>
            <p>Sin sobreprecios ni costes ocultos</p>
            <p class="text-success"><b>Cancelación gratuita hasta 48 horas antes</b></p>
        </div>
        <div id="sidebar-calendar-movil">
                    <div id="mini-clndr-movil" class="calendario-visitas-movil cal2"></div>
                         <div class="form-group">
                           <label>Fecha seleccionada:</label>
                         <input type="text" id="txtFechaSeleccionadaMovil" disabled></input>
                         </div>

                    <script id="calendar-template-movil" type="text/template">
 <div class='clndr-controls'>
                    <div class='clndr-control-button'>
                        <span class='clndr-previous-button'><i class='fas fa-arrow-circle-left'></i></span>
                    </div>
                    <div class='monthyear'>
                        <span class='month'><%= month %></span>
                        <span class='year'><%= year %></span>
                    </div>
                    <div class='clndr-control-button'>
                        <span class='clndr-next-button'><i class='fas fa-arrow-circle-right'></i></span>
                    </div>
                </div>
                <table class='clndr-table' border='0' cellspacing='0' cellpadding='0'>
                    <thead>
                    <tr class='header-days'>
                        <% for(var i = 0; i < daysOfTheWeek.length; i++) { %>
                        <td class='header-day'><%= daysOfTheWeek[i] %></td>
                        <% } %>
                    </tr>
                    </thead>
                    <tbody>
                    <% for(var i = 0; i < numberOfRows; i++){ %>
                    <tr>
                        <% for(var j = 0; j < 7; j++){ %>
                        <% var d = j + i * 7; %>
                        <td class='<%= days[d].classes %>
                                                        <% if (days[d].events){ %>
                                                          <% for(var k = 0; k < days[d].events.length; k++){ %>
                                                            <%= days[d].events[k].class %>
                                                          <% } %>
                                                        <% } %>
                                                        '>
                            <div class='day-contents'>
                                <%= days[d].day %>
                            </div>
                            <% if (days[d].events){ %>
                                <% for(var k = 0; k < days[d].events.length; k++){ %>
                                    <% if (days[d].events[k].hours){ %>
                                        <div class="tooltip top m-calendar-tooltip _booking_calendar _availability _civ-tooltip _length_<%= days[d].events[k].hours.length %><% if (days[d].events[k].hours.length == 0){ %> _length__small<% } %>">
                                            <div class="m-availability__tooltip _<%= days[d].events[k].hours.length %>">
                                            <% if (days[d].events[k].hours.length == 0){ %>
                                                <span class="_time_empty">Horario flexible</span>
                                                <%= civ.calendar.getNoHoursTooltipQuota(days[d]) %>
                                            <% } else { %>
                                                <% for(var l = 0; l < days[d].events[k].hours.length; l++){ %>
                                                    <span class="_time">
                                                        <%= days[d].events[k].hours[l] %>
                                                        <%= civ.calendar.getTooltipQuota(days[d], days[d].events[k].hours[l]) %>
                                                    </span>
                                                <% } %>
                                            <% } %>
                                            </div>
                                        </div>
                                    <% } %>
                                <% } %>
                            <% } %>
                            </div>
                        </td>
                        <% } %>
                    </tr>
                    <% } %>
                    </tbody>
                </table>

                    </script>
                   
                     
            </div>
             <hr class="hr-divider "> 
             <div id="sidebar-calendar-movil-copia" class="text-center">
               <!--ACORDEON HORA-->
              <div class="accordion mb-2" id="hora-movil">
                 <div class="card card-accordion">
                    <div class="" id="headingOne">
                      <h5 class="mb-0">
    <a class="btn btn-accordion btn-calendar text-white " href="#" data-toggle="collapse" data-target="#seleccionar_hora-movil" aria-controls="collapseOne"
    id="acordeonHoraMovil">
                         <i class="fa fa-clock"></i>Elegí la Hora <i class="fa fa-sort-down text-primary float-right"></i>
                        </a>
                      </h5>
                    </div>

                    <div id="divhora-movil" class="collapse " aria-labelledby="headingOne" data-parent="#seleccionar_hora-movil">
                            <p class="">Debe seleccionar una fecha!!!</p>
                            
                    </div>
                  </div>
              </div>
              <!--FIN ACORDEON HORA-->

               <!--
              <div class="accordion mb-2" id="tours-movil">
                 <div class="card card-accordion">
                    <div class="" id="headingOne">
                      <h5 class="mb-0">
                        <a class="btn btn-accordion btn-calendar text-white " href="#" data-toggle="collapse" data-target="#divtours-movil" aria-expanded="true" aria-controls="collapseOne">
                         <i class="fa fa-hiking"></i> Tour con recogida <i class="fa fa-sort-down text-primary float-right"></i>
                        </a>
                      </h5>
                    </div>

                    <div id="divtours-movil" class="collapse " aria-labelledby="headingOne" data-parent="#tours-movil">
                      <p class="py-2 text-left mb-0 mx-4">Tours sin recogida</p>
                      <p class="py-2 text-left mb-0 mx-4">Tours sin recogida</p>
                    </div>
                  </div>
              </div>
           -->

               <!--ACORDEON PERSONA-->
             <div class="accordion mb-2" id="personas-movil">
                 <div class="card card-accordion">
                    <div class="" id="headingOne">
                      <h5 class="mb-0">
                        <a class="btn btn-accordion btn-calendar text-white " href="#" data-toggle="collapse" data-target="#divpersona-movil" aria-expanded="true" aria-controls="collapseOne">
                         <i class="fa fa-male"></i>¿Cuantas personas? <i class="fa fa-sort-down text-primary float-right"></i>
                        </a>
                      </h5>
                    </div>

                    <div id="divpersona-movil" class="collapse " aria-labelledby="headingOne" data-parent="#personas-movil">
                          
                          <!--GRUPO ADULTOS-->
                          
                          <div class="container py-3">
                              <div class="row">
                                  <div class="col-md-12">
                                      <p class="counter-label mb-2 text-left">Adultos</p>
                                  </div>
                                  <div class="col-md-3 col-3">
  <span class="counter-label_span"><label id=pAdulMov></label></span>
                                  </div>
                                  <div class="col-md-1 col-1 " style=" padding-left: 0px !important;padding-right: 0px !important;"  >
       <a  onclick="decrementa(1)"><i class="fa fa-minus-circle fa-2x"></i></a>
                                   </div>
                                   <div class="col-md-4 col-3">
  <input class="form-control" type="text" id="cantAdulMov" onchange="calcula(this.value, 1)" min="0" value="0" >
                                   </div>
                                   <div class="col-md-1 col-1" style=" padding-left: 0px !important;padding-right: 0px !important;">
  <a  onclick="incrementa(1)"><i class="fa fa-plus-circle fa-2x"></i></a>
                                   </div>
                                   <div class="col-md-3 col-3">
     <small class="counter-label_span_precio"><label id=totalAdulMov></label></small>
                                   </div>
                              </div>
                          </div>
                            <!--FIN GRUPO ADULTOS-->
                            
                         <!--GRUPO NIÑOS MENORES DE 12 AÑOS-->
                             <div class="container py-3">
                              <div class="row">
                                  <div class="col-md-12">
                                      <p class="counter-label mb-2 text-left">Menores de 12 anos</p>
                                  </div>
                                  <div class="col-md-3 col-3">
                <span class="counter-label_span"><label id=pMen12Mov></label></span>
                                  </div>
                                  <div class="col-md-1 col-1" style=" padding-left: 0px !important;padding-right: 0px !important;" >
<a onclick="decrementa(2)"><i class="fa fa-minus-circle fa-2x"></i></a>
                                   </div>
                                   <div class="col-md-4 col-3">
<input  class="form-control" type="text" id="cant12Mov" onchange="calcula(this.value, 2)" min="0" value="0">
                                   </div>
                                   <div class="col-md-1 col-1" style=" padding-left: 0px !important;padding-right: 0px !important;" >
        <a  onclick="incrementa(2)"><i class="fa fa-plus-circle fa-2x"></i></a>
                                   </div>
                                   <div class="col-md-3 col-3">
 <small class="counter-label_span_precio"><label id=totalMen12Mov></label></small>
                                   </div>
                              </div>
                          </div>
                            <!--FIN GRUPO  MENORES DE 12 AÑOS-->
                            
                    <!--GRUPO MENORES DE 5 AÑOS-->
                            <div class="container py-3">
                              <div class="row">
                                  <div class="col-md-12">
                                      <p class="counter-label mb-2 text-left">Menores de 5 anos</p>
                                  </div>
                                  <div class="col-md-3 col-3">
                <span class="counter-label_span"><label id=pMen5Mov></label></span>
                                  </div>
                                  <div class="col-md-1 col-1" style=" padding-left: 0px !important;padding-right: 0px !important;" >
 <a  onclick="decrementa(3)"><i class="fa fa-minus-circle fa-2x"></i></a>
                                   </div>
                                   <div class="col-md-4 col-3">
<input class="form-control" type="text" id="cant5Mov" onchange="calcula(this.value, 3)" min="0" value="0" >
                                   </div>
                                   <div class="col-md-1 col-1" style=" padding-left: 0px !important;padding-right: 0px !important;" >
 <a  onclick="incrementa(3)"><i class="fa fa-plus-circle fa-2x"></i></a>
                                   </div>
                                   <div class="col-md-3 col-3">
           <small class="counter-label_span_precio"><label id=totalMen5Mov></label></small>
                                   </div>
                              </div>
                          </div>
                            <!--FIN GRUPO MENORES DE 5 AÑOS-->
<!--GRUPO MENORES DE 3 AÑOS-->
                            <div class="container py-3">
                              <div class="row">
                                  <div class="col-md-12">
                                      <p class="counter-label mb-2 text-left">Menores de 3 anos</p>
                                  </div>
                                  <div class="col-md-3 col-3">
                                     <span class="counter-label_span"><label id=pMen3Mov></label></span>
                                  </div>
                                  <div class="col-md-1 col-1" style=" padding-left: 0px !important;padding-right: 0px !important;" >
  <a  onclick="decrementa(4)"><i class="fa fa-minus-circle fa-2x"></i></a>
                                   </div>
                                   <div class="col-md-4 col-3">
<input class="form-control" type="text" id="cant3Mov" onchange="calcula(this.value, 4)" min="0" value="0"  >
                                   </div>
                                   <div class="col-md-1 col-1" style=" padding-left: 0px !important;padding-right: 0px !important;" >
 <a  onclick="incrementa(4)"><i class="fa fa-plus-circle fa-2x"></i></a>
                                   </div>
                                   <div class="col-md-3 col-3">
                   <small class="counter-label_span_precio"><label id=totalMen3Mov></label></small>
                                   </div>
                              </div>
                          </div>
                            <!--FIN GRUPO MENORES DE 3 AÑOS-->
                    </div>
                  </div>
              </div>
              <!--FIN ACORDEON PERSONA-->

 <!--ACORDEON PERSONA-->
             <div class="accordion mb-2" id="personas-movil">
                 <div class="card card-accordion">
                    <div class="" id="headingOne">
                      <h5 class="mb-0">
                        <a class="btn btn-accordion btn-calendar text-white " href="#" data-toggle="collapse" data-target="#divpersona-movil-adicional" aria-expanded="true" aria-controls="collapseOne">
                         <i class="fa fa-male"></i>Adicionales <i class="fa fa-sort-down text-primary float-right"></i>
                        </a>
                      </h5>
                    </div>
<!-- si pongo esto los adicionales en el celular no se pueden cerrar
  y el mas a la derecha y el menos a la izquierda
  
                    <div id="divpersona-movil-adicional" class="collapse " aria-labelledby="headingOne" data-parent="#personas-movil-adicional">-->
                          <div >
                          <!--GRUPO ADULTOS-->
                          
                          <div class="container py-3">
<?php
//****sv no incluidos**********************************************

$registros= count(svAdicionalesNoIncluidos($id,$money)) ;

for ($i=0; $i < $registros ; $i++) { 
 $registrosZ= count(svAdicionalesNoIncluidos($id,$money)[$i]);

 
  if (svAdicionalesNoIncluidos($id,$money)[$i][5]=="false") {

    echo ('
<!--ADICIONALES NO INCLUIDOS LOOP-->
                            <div class="container py-3">
                              <div class="row">
                                  <div class="col-md-12">
                                      <p class="counter-label mb-2 text-left">'.svAdicionalesNoIncluidos($id,$money)[$i][2].'</p>
                                  </div>
                                  <div class="col-md-3">
   <span class="counter-label_span"><label id="txtPrecioMovil'.svAdicionalesNoIncluidos($id,$money)[$i][1].'"></label></span>
                                  </div>
                                  <div class="col-md-1" style=" padding-left: 0px !important;padding-right: 0px !important;" >
  <a onclick="DecrementaAdicional('.svAdicionalesNoIncluidos($id,$money)[$i][1].')" ><i class="fa fa-minus-circle fa-2x"></i></a>
                                   </div>
                                   <div class="col-md-4">
<input type="number" class="form-control" 
         onchange="InsertarAdicional(this.value,
         '.svAdicionalesNoIncluidos($id,$money)[$i][1].','.
         svAdicionalesNoIncluidos($id,$money)[$i][4].')" id="txtSrvAdcMovil'.svAdicionalesNoIncluidos($id,$money)[$i][1].'"></input>
                                   </div>
                                   <div class="col-md-1" style=" padding-left: 0px !important;padding-right: 0px !important;" >
 <a  onclick="IncrementaAdicional('.svAdicionalesNoIncluidos($id,$money)[$i][1].')"><i class="fa fa-plus-circle fa-2x"></i></a>
                                   </div>
                                   <div class="col-md-3">
                   <small class="counter-label_span_precio"><label id="txtTotalAdcpMovil'.svAdicionalesNoIncluidos($id,$money)[$i][1].'"></label></small>
                                   </div>
                              </div>
                          </div>

  <script>

adicionales['.svAdicionalesNoIncluidos($id,$money)[$i][1].']=Array();
 
  InsertarAdicional(1,
         '.svAdicionalesNoIncluidos($id,$money)[$i][1].','.
         svAdicionalesNoIncluidos($id,$money)[$i][4].');
          </script>
<!--FIN ADICIONALES NO INCLUIDOS LOOP-->
  ');

/*

    echo ('<p class="alert-warning">'.svAdicionalesNoIncluidos($id)[$i][2]." ");
    echo svAdicionalesNoIncluidos($id)[$i][3].svAdicionalesNoIncluidos($id)[$i][4];
         echo ' <input type="number" 
         onchange="InsertarAdicional(this.value,
         '.svAdicionalesNoIncluidos($id)[$i][1].','.
         svAdicionalesNoIncluidos($id)[$i][4].')" id="txtSrvAdc'.svAdicionalesNoIncluidos($id)[$i][1].'"></input>';
*/
    }
  //  echo '</p> ';
}
//*************************************fin no incluidos**********************************************

 ?>


          
                          </div>
                            <!--FIN GRUPO ADULTOS-->
                            

                  </div>
              </div>
              <!--FIN ACORDEON PERSONA-->



            
            </div>

            <!--DESCUENTO-->
 <div class="accordion mb-2" id="DEscuento">
                 <div class="card card-accordion">
                    <div class="" id="headingOne">
                      <h5 class="mb-0">
                        <a class="btn btn-accordion btn-calendar text-white " href="#" data-toggle="collapse" data-target="#seleccionar-descuento-movil" aria-expanded="true" aria-controls="collapseOne">
                         <i class="fa fa-clock"></i> Cupon de descuento <i class="fa fa-sort-down text-primary float-right"></i>
                        </a>
                      </h5>
                    </div>

                    <div id="seleccionar-descuento-movil" class="collapse " aria-labelledby="headingOne" data-parent="#seleccionar-descuento-movil">
                      <div class="form-group">
                        <label for="">
                          Ingrese un cupon de descuento
                        </label>
                        <input type="text"  onkeyup="cupon(this.value)" name="" id="txtCuponDescuentoMovil" class="form-control">
                      </div>
                      <div id="cuponOkMovil"></div>
                            <!--Aca el js trae los horarios al clickear en el calendario-->
                    </div>
                  </div>
              </div>
<!---DESCUENTO-->

 <button onclick="enviar()" class="btn btn-primary btn-radius btn-lg h-100">Reservar</button>

             </div>
              </div>
            </div>
          </div>
      </div>
    </div>
  </div>
</div>

<!--MODAL HORARIO-->