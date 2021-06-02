<!--SECCION INFORMACION DE VISITA MOVIL-->
<section class="d-md-none bg-white seccion-arriba">
    <div class="container py-2">
        <div class="row">
            <div class="col-12">
                <h4 class="text-left titulo-card-destinos text-primary"><?=$servicio["nombre_servicio"];?> </h4>
            </div>
        </div>
    </div>
    <div class="container py-2">
        <div class="row text-center ">
            <div class="col-3">
                <h5 class="texto-opinion-desta">
                  <strong class="text-primary semibold"><?=$estrellasServicio;?>/10</strong>
                </h5>
                <small><?=count($OpinionesServicio);?> opiniones</small>
            </div>
            <div class="col-3">
               <p class="mb-0"> <i class="fa fa-hourglass-half text-primary"></i></p>
             
                <small><?= $duracion["duracionMinima"];?> - <?= $duracion["duracionMaxima"];?></small>
            </div>
            <div class="col-3">
                 <p class="mb-0"><i class="fa fa-comment text-primary"></i></p>
                <small> Español Ingles Portugués</small>
            </div>

            <div class="col-3 ">
                <p class="text-success mb-0 " style="font-size:12px;"><strong>Cancelación gratuita</strong></p>
            </div>
        </div>
    </div>
</section>

<!--FIN SECCION INFORMACION DE VISITA MOVIL-->


<div class="container d-md-none">
  <div class="row">
    <div class="col-12" style="padding-left: 0px;padding-right: 0px;">
       <!--ACORDEON DESCRIPCION-->
       <div class="accordion" id="accordionExample-movil">
                 <div class="card card-accordion">
                    <div class="" id="headingOne-Descripcion">
                      <h5 class="mb-0">
                        <a class="btn btn-accordion bg-white border-top btn-accordion-dark-show " id="btn-descripcion"  data-toggle="collapse" data-target="#descripcion-movil" aria-expanded="true" aria-controls="collapseOne">
                          Descripcion <i class="fa fa-sort-down text-primary float-right"></i>
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
          <p><?=$servicio["descripcion_corta"];?>.</p>
          <!--FIN TEXTO DESTACADO-->

 <!--SLIDER-->

          <div id="carrouselDivCelular" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
              
                    
                                <!--CARGA DE IMAGENES-->
                                     <?php //********************arranca fotos*******************************************
      # code...  
                                    
for ($i=0; $i < count($fotos); $i++) { 
     $active='';
  if ($i==0) {
   $active='class="active"';
  }?>
  <li data-target="#carrouselDivCelular" data-slide-to="<?=$i;?>" <?= $active;?>></li>
  <?php
}
?>
    </ol>
              <div class="carousel-inner">
<?php
    for ($i=0; $i < count($fotos) ; $i++) { 
      if ($i==0) {
        $classe="carousel-item active";
      }
      else{
        $classe="carousel-item";
      }?>
    
       <div class="<?= $classe ?>">
                  <img class="d-block w-100 img-slider-servicio" src="admin/classes/imgServicio/<?=$fotos[$i]['ruta'];?>" alt="First slide">
                  <div class="carousel-caption d-none d-md-block">
                    <h5></h5>
                  </div>
                </div>
   
   
<?php
    }
    //********************fin fotos*******************************************?>
            
                <!--FIN CARGA DE IMAGENES-->
         
              </div>
                <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                  <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                  <span class="sr-only">Next</span>
                </a>
           </div>
        <!--FIN SLIDER-->

        <!--TEXTO VISITA-->

        <h4 class="py-4 text-primary"> ¿Qué se visita?</h4>

        <p class=""><?=$servicio["descripcion_servicio"];?></p>

    
        <!--FIN TEXTO VISITA-->

        <!--TEXTO IMPORTANTE-->

         <h4 class="py-4 text-primary"> Importante</h4>

       
          <p class=""><?=$servicio["observaciones"];?></p>
       

        <!--FIN TEXTO IMPORTANTE-->

        <!--TEXTO RECOGIDA-

        <h4 class="py-4 text-primary">Recogida en el hotel</h4>

       <p>Opcionalmente, podes reservar la recogida en por el hotel. La recogida es una hora antes de la hora de inicio del tour e incluye todos los alojamientos en un radio de 8 kilómetros desde el punto de encuentro.</p>

  
        --FIN TEXTO RECOGIDA-->  

        </div>
        <!--FIN CONTENEDOR DESCRIPCION-->
                              </div>
                          </div>
                      </div>
                  </div>
              </div>

        <!--ACORDEON DESCRIPCION-->



<!--ACORDEON PRECIO-->
            
          
                 <div class="card card-accordion" id="divAcordionMovil">
                    <div class="" id="headingOne-Precio">
                      <h5 class="mb-0">
                        <a class="btn btn-accordion bg-white border-top  " id="btn-precio"  data-toggle="collapse" data-target="#precio-movil" aria-expanded="true" aria-controls="collapseOne">
                          Precio <i class="fa fa-sort-down text-primary float-right"></i>
                        </a>
                      </h5>
                    </div>

                    <div id="precio-movil" class="collapse " aria-labelledby="headingOne-Precio" data-parent="#divAcordionMovil">
                      <div class="container py-2">
                        <div class="row" >
                          <div class="col-12">
                               <!-- CONTENEDOR PRECIO-->
        <div id="rowCirculosPreciosMovil">
          <!--TEXTO PRECIO-->

         <h4 class="py-4 text-primary">Precio</h4>

         <div class="container">
        <div class="row">
             <div class="col-lg-3 col-12 my-auto">
               <p class="popular text-center mb-0 "><i class="fa fa-star"></i> MÁS POPULAR</p>
               <p><strong></strong></p>
             </div>
             <div class="col-lg-2 col-5">
               <p class="text-center ">Adultos</p>
               <div class="circulo-b">
                 <p class="text-center text-primary">precio</p>
               </div>
             </div>
            
            
           
           </div>


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
            <button class="btn btn-accordion bg-white border-top  "  data-toggle="modal" data-target="#modalhorario-movil">
              Elegí la Fecha <i class="fa fa-sort-down text-primary float-right"></i>
            </button>
          </h5>
        </div>
     </div>



  <!--FIN ACORDEON HORARIOS-->


  <!--ACORDEON DETALLES-->
            
         
                 <div class="card card-accordion">
                    <div class="" id="divDetallesMovil">
                      <h5 class="mb-0">
                        <a class="btn btn-accordion bg-white border-top" id="btn-detalles"  data-toggle="collapse" data-target="#detalles-movil" aria-expanded="true" aria-controls="collapseOne">
                          Detalles <i class="fa fa-sort-down text-primary float-right"></i>
                        </a>
                      </h5>
                    </div>

                    <div id="detalles-movil" class="collapse " aria-labelledby="headingOne-Detalles" data-parent="#divDetallesMovil">
                      <div class="container py-2">
                        <div class="row">
                           <div class="col-12">
                                <!-- CONTENEDOR DETALLES-->

        <div id="detalles">
          <!--TEXTO DETALLE-->

        <h4 class="py-4 text-primary">Detalles</h4>

        <h5 class="semibold"><i class="fa fa-hourglass"></i> Duración</h5>
        <p class="mx-4" id="txtDuracionMovil"> </p>

        <h5 class="semibold"><i class="fa fa-language"></i> Idioma </h5>
        <p class="mx-4" id="pIdiomasMovil"></p>

        <h5 class="semibold"><i class="fa fa-exclamation-circle"></i> Incluido </h5>
        <ul class=""  id="ulIncluidosMovil">
          
    


        </ul>


        <h5 class="semibold"><i class="fa fa-exclamation-triangle"></i> No incluido </h5>
        <ul class=""  id="ulNoIncluidosMovil">
        </ul>


        <h5 class="semibold"><i class="fas fa-passport"></i> Documentación para el Viajero </h5>
        <ul class="" >
                <?= $servicio['documentacionViajero']; ?>
        </ul>

        <h5 class="semibold"><i class="fa fa-calendar-alt"></i> ¿Cuándo reservar? </h5>
        <p class="mx-4">Reserva cuanto antes para garantizar la disponibilidad, especialmente en puentes y festivos.</p>
        <p  class="mx-4">Se permiten reservas hasta las 23:00 horas del día anterior (Horario de brasilia) siempre que queden plazas</p>

        <h5 class="semibold"><i class="fa fa-file"></i> Justificante</h5>
        <p class="mx-4">Te enviaremos un email con un bono que podrás imprimir o llevar en tu móvil a la actividad.</p>

        <h5 class="semibold"><i class="fa fa-question-circle mb-4"></i> Preguntas frecuentes</h5>

        <!--PREGUNTAS FRECUENTES-->

        <div id="accordion" class="mb-4">
          <div class="card card-faq">
            <div class="card-header" id="headingOne">
              <h5 class="mb-0">
                <a  class=" btn-faq" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                <strong>Viajero</strong> - ¿Es posible organizar una visita con grupos familiares de todas las edades?
                </a>
              </h5>
            </div>
            <div id="collapseOne" class="collapse " aria-labelledby="headingOne" data-parent="#accordion">
              <div class="card-body">
             <p class="mx-4"><strong>Metelebrasil</strong> - Sí. La visita puede ser realizada por grupos familiares y personas de todas las edades, incluyendo menores de 3 años.</p>
              </div>
            </div>
          </div>
       </div>
        <!--FIN PREGUNTAS FRECUENTES-->

         <?php /*if(Accesibilidad($id)){
          echo '<h5><i class="fa fa-wheelchair"></i> Accesibilidad </h5>
        <p class="mx-4">Si (nuestras actividades son aptas personas de movilidad reducida, y carritos de bebé).</p>';
        }
*/

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


             <!--ACORDEON PUNTOS DE ENCUENTRO-->
           
                 <div class="card card-accordion">
                    <div class="" id="divEncuentrosMovil">
                      <h5 class="mb-0">
                        <a class="btn btn-accordion bg-white border-top  " id="btn-encuentro"  data-toggle="collapse" data-target="#encuentros-movil" aria-expanded="true" aria-controls="collapseOne">
                          Encuentros <i class="fa fa-sort-down text-primary float-right"></i>
                        </a>
                      </h5>
                    </div>

                    <div id="encuentros-movil" class="collapse " aria-labelledby="headingOne-Encuentros" data-parent="#divEncuentrosMovil">
                      <div class="container py-2">
                        <div class="row" >
                          <div class="col-12">
                               <!--CONTENEDOR PUNTO DE ENCUENTRO-->
      <div id="punto-encuentro">

      <!--TEXTO PUNTO DE ENCUENTRO-->

        <h4 class="py-4 text-primary" >Punto de encuentro</h4>

         <!--TEXTO DESTACADO-->
          <p><?php /*DevuelveDireccionServicio($id);*/ ?>.</p>
         <!--FIN TEXTO DESTACADO-->


         <!--MAPA-->

         <div class="container-fluid">
           <div class="row">
             <div class="col-lg-12 py-2">
                 <div class="embed-responsive embed-responsive-16by9">
                    <iframe src = "https://maps.google.com/maps?q=<?php//DevuelveCoordenadasServicio($id)["latitud"]?>,<?php//DevuelveCoordenadasServicio($id)["longitud"]?>&hl=es;z=14&amp;output=embed"></iframe>
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
                    <div class="" id="divCancelacionesMovil">
                      <h5 class="mb-0">
                        <a class="btn btn-accordion bg-white border-top  " id="btn-cancelaciones"  data-toggle="collapse" data-target="#cancelaciones-movil" aria-expanded="true" aria-controls="collapseOne">
                          Cancelaciones <i class="fa fa-sort-down text-primary float-right"></i>
                        </a>
                      </h5>
                    </div>

                    <div id="cancelaciones-movil" class="collapse " aria-labelledby="headingOne-Cancelaciones" data-parent="#divCancelacionesMovil">
                      <div class="container py-2">
                        <div class="row">
                          <div class="col-12">
                                 <!-- CONTENEDOR CANCELACION-->
                          <div id="cancelaciones"> 
                            <h4 class="py-4 text-primary">Cancelaciones</h4>
                
                                  <?php 

//echo '   <p>'.Cancelaciones($id)[0][1].'</p> ';
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
            <!--
                 <div class="card card-accordion">
                    <div class="" id="headingOne-Cancelaciones">
                      <h5 class="mb-0">
                        <a class="btn btn-accordion bg-white border-top  " id="btn-opiniones"  data-toggle="collapse" data-target="#opiniones-movil" aria-expanded="true" aria-controls="collapseOne">
                          Opiniones <i class="fa fa-sort-down text-primary float-right"></i>
                        </a>
                      </h5>
                    </div>

                    <div id="opiniones-movil" class="collapse " aria-labelledby="headingOne-Cancelaciones" data-parent="#headingOne-Cancelaciones">
                      <div class="container py-2">
                        <div class="row">
                          <div class="col-12">
                              CONTENEDOR OPINIONES
          <div class="mb-5" id="opiniones">
              <div class="row no-gutters">
                <div class="col-lg-8">
                  <h4 class="py-4 text-primary">Opiniones de nuestros clientes</h4>
                  <p>Todas las opiniones han sido escritas por clientes reales que han reservado con nosotros.</p>
                </div>
                <div class="col-lg-4">
                  <div class="total-opinion">
                    <h2 class="text-primary semibold" ><?= $estrellasServicio?> <small class="text-gris"><?= $CantOpinionesServicio;?> opiniones</small></h2>
                  </div>
                </div>
              </div>
              <br>
              <div class="row no-gutters">
                <div class="col-lg-12">
                  <p class="mb-4">Mostrando 1 de <?= $CantOpinionesServicio;?> opiniones</p>
                 - CARGA BUCLE OPINIONES
         

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





              FIN CARGA BUCLE OPINIONES
                  
                </div>
              </div>
               -Mas opiniones-
              <div class="row collapse" id="Ver-mas-opinones-movil">
                 <div class="col-lg-12">
                  <p class="mb-4">Mostrando 2 de <?= $CantOpinionesServicio;?> opiniones</p>

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





               FIN CARGA BUCLE OPINIONES
                 
                </div>
              </div>
           Mas opiniones
               <div class="row text-center">
                <div class="col-md-12">
                    <a class="btn btn-white-destinos"  id="alternar-panel-oculto-1" data-toggle="collapse" data-target="#Ver-mas-opinones-movil" aria-expanded="false" aria-controls="Ver-mas-opinones-movil">
                      Ver todas las opiniones
                    </a>
                </div>
              </div>
          </div>
           --FIN CONTENEDOR OPINIONES-
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>-->
              </div>
            
            <!--FIN ACORDEON OPINIONES-->


    </div>
  </div>
</div>