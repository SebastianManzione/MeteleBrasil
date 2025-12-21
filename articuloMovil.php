<!--SECCION INFORMACION DE VISITA MOVIL-->





<!--FIN SECCION INFORMACION DE VISITA MOVIL-->





<div class="container d-md-none">

  <div class="row">

    <div class="col-12" style="padding-left: 0px;padding-right: 0px;">




       <!--ACORDEON DESCRIPCION-->

       <div class="accordion" id="accordionExample-movil">



<div class="card card-accordion">

    


                    <div id="descripcion-movil" class="collapse show" aria-labelledby="headingOne-Descripcion" data-parent="#accordionExample-movil">

                      <div class="container py-2">

                          <div class="row">

                              <div class="col-12">

                                  <!--CONTENEDOR DESCRIPCION-->

        <div class="descripcion" id="descripcion">
 <!--TEXTO DESTACADO-->

          <p><?=$descripcionCorta;?></p>

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

                  <img class="d-block w-100 img-slider-servicio" src="admin/classes/imgBlog/<?=$fotos[$i]['ruta'];?>" alt="First slide">

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

<p><?=$contenido;?></p>

     



    

        <!--FIN TEXTO VISITA-->



        <!--TEXTO IMPORTANTE-->



         <h4 class="py-4 text-primary"> Tips Y Consejos</h4>



       

          <p class=""> <?=$tipsYConsejos;?></p>

       

      <h4 class="py-4 text-primary"> Observaciones</h4>



       

          <p class=""> <?=$observaciones;?></p>

 


        </div>

        <!--FIN CONTENEDOR DESCRIPCION-->

                              </div>

                          </div>

                      </div>

                  </div>

              </div>
       </div>
         


    </div>

  </div>

</div>