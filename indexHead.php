<section class="div-absolute">
      <div class="container"> 
        <div class="row" >
          
          <div class="col-lg-6 offset-lg-3 mb-5 mb-15">
             <h1 class="text-white text-uppercase titulo" >
      
              <span class="semibold"><?= $lang["crea_tu_viaje"]; ?></span> <br>
              <?=$lang["excursiones_en_brasil"]; ?>
            </h1>
            <form class="form-buscar mb-5 " action="categorias" method="get">
            <label class="sr-only" for="s"><?= $lang["que_hacemos"]; ?></label>
          <div class="input-group">
            <input class="field form-control form-control-search" id="buscar" name="buscar" type="text" placeholder="<?= $lang["que_hacemos"]; ?>" value="">
            <span class="input-group-append">
              <button class="submit btn btn-primary" id="searchsubmit" name="submit" type="submit"><?= $lang["buscar"]; ?> <i class="fa fa-arrow-right"></i></button>
            </span>
          </div>
              <!--EMPIEZA DESPLEGABLE DEL BANNER-
              <div class="form-group">
               <div class="container">
                <div class="row">
                  <div class="col-lg-12">
                     <div id="destinos" class="d-none">
                         <div class="container">
                        <div class="row mb-4">
                          <div class="col-lg-12">
                            <h3 class="text-center">¿Que hacemos?</h3>
                          </div>
                        </div>
                        <div class="row  mb-4">
                            -->
                            <!--EL BUCLE DE LOS RESULTADOS DEBE IR ACA-->  <?php /*
$categorias=DevuelveCategorias();
for ($i=0; $i < count($categorias); $i++) { 
  echo '    <div class="col-md-3 mb-3" ">
<a href="./categorias.php?id='.$categorias[$i][0].'">
  <img src="sistema/img/categoria_servicio/'.$categorias[$i][2].'" class="img-fluid img-card-top img-destacada">
                            <h4 class=" mb-0"><a  href="./categorias.php?id='.$categorias[$i][0].'" class="text-destinos">'.$categorias[$i][1].'</a></h4>
                            <small></small>
                          </a>  </div>';
}*/
                             ?>         
                        
                            <!--FIN BUCLE DE LOS RESULTADOS DEBE IR ACA-->   
                  <!--
                         </div>
                         <div class="row py-4">
                          <div class="col-lg-12">
                            <h3 class="text-center"><a href="" class="btn btn-outline-primary btn-white" style="border-radius:25px;">Ver todos los destinos</a></h3>
                          </div>
                        </div>
                        </div>
                     </div>
                  </div>
                </div>
                </div>
              </div> -->
               <!--EMPIEZA DESPLEGABLE DEL BANNER-->  
              
            </form>


          </div>  
          <!--EMPIEZA FOOTER DEL BANNER-->
           <div class="col-lg-12 text-center text-white div-bottom" >
              <div class="container" >
              <div class="row">
                <div class="col-lg-3 col-md-3 col-3">
                 <i class="text-white fa fa-calendar-check fa-2x "></i>
                  <p class="texto-bottom"><?=$lang["las_mejores_actividades"];?></p>
                </div>
                <div class="col-lg-3 col-md-3 col-3">
                  <i class="text-white fa fa-headset fa-2x "></i>
                  <p class="texto-bottom"><?=$lang["atencion_al_cliente_247"];?></p>
                </div>
                <div class="col-lg-3 col-md-3 col-3">
                  <i class="text-white fa fa-comment-dots fa-2x "></i>
                  <p class="texto-bottom"><?=$lang["miles_de_opiniones"];?></p>
                </div>
                <div class="col-lg-3 col-md-3 col-3">
                  <i class="text-white fa fa-hand-holding-usd fa-2x "></i>
                  <p class="texto-bottom"><?=$lang["sin_sobreprecios"];?></p>
                </div>
              </div>
            </div>
          </div>

          <!--FIN FOOTER DEL BANNER-->
          
        </div>
    </div>
  </section>