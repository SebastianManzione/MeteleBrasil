
<?php 



include("includes/navbar.php"); 
require("admin/classes/opiniones_categoria.php");

require_once("admin/classes/salidas.php");

require("admin/classes/categoria.php");
require("admin/classes/servicio_opiniones.php");
require("admin/classes/texto_miniaturas.php"); 

  

/*
if (!isset($_SESSION["visitante"])) {

 $_SESSION["visitante"]=Array();
$visitante=array();

$visitante[0]=$_SESSION["realIP"];
$visitante[1]=$_SESSION["paisConexion"];
$visitante[2]="index.php";
Visitante($visitante);
}
*/
?>


 <!-- header -->
 <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
  <ol class="carousel-indicators">
    <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
    <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
    <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
    <li data-target="#carouselExampleIndicators" data-slide-to="3"></li>

  </ol>
  <div class="carousel-inner">
     <div class="carousel-item active">
      <img class="img-fluid img-slider" src="img/slider4.jpg">
    </div>
    <div class="carousel-item">
      <img class="img-fluid img-slider" src="img/slider1.jpg" >
    </div>
    <div class="carousel-item">
      <img class="img-fluid img-slider" src="img/slider2.jpg">
    </div>
    <div class="carousel-item">
      <img class="img-fluid img-slider" src="img/slider6.jpg">
    </div>
  </div>
  <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
  
    <section class="div-absolute">
      <div class="container"> 
        <div class="row">
          
          <div class="col-lg-6 offset-lg-3 mb-5 mb-15">
             <h1 class="text-white text-uppercase titulo" >
      
              <span class="semibold"><?= $lang["crea_tu_viaje"]; ?></span> <br>
              <?=$lang["excursiones_en_brasil"]; ?>
            </h1>
            <form class="form-buscar mb-5 " action="categorias" method="get">

<div class="input-group">
                <input class="field form-control form-control-search"  name="buscar" type="text" placeholder="Escribe tu mail" value="">
                <span class="input-group-append">
                <button class="submit btn btn-primary" id="searchsubmit" name="submit" type="submit"><i class="fa fa-arrow-right"></i></button>
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
          
          <!--FIN FOOTER DEL BANNER-->
          
        </div>
    </div>
  </section>
  
</div>
  <!-- FIN HEADER -->


<!--EMPIEZA SECCION PRINCIPALES DESTINOS -->
 


<!--EMPIEZA SECCION Actividades destacadas-->




 <!-- Footer -->

  
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
            <label class="sr-only" for="s"><?=$lang["donde_vamos"]?>?</label>
          <div class="input-group ">
            <input class="field form-control" id="buscar" name="buscar" type="text" placeholder="" value="">
            <span class="input-group-append">
              <button class="submit btn btn-primary" id="searchsubmit" name="submit" type="submit"><?=$lang["buscar"]?><i class="fa fa-arrow-right"></i></button>
              
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
                            <h3 class="text-center text-primary"><?=$lang["ver_todos_los_destinos"]?></h3>
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


<!-- BOTON AYUDA-
   <a class="btn-ayuda d-md-block d-none" href="#">
  <div class="boton-ayuda">
   
      <i class="fa fa-question-circle"></i> Ayuda
  </div>
     </a>


  -- FIN BOTON AYUDA-->

  <!--AVISO DE COOKIES-->
  
  <?php if (!isset($_COOKIE['politicaCookies'])){?>
     <div class="cookies" id="cookies">
      
          <div class="container">
             <div class="row">
               <div class="col-8"><p class="text-white mb-0 text-justify cookies-sm " style="font-size:14px;"><?=$lang["utilizamos_cookies_propias"]?><a href="cookies.php"><?=$lang["politicas_de_cookies"]?></a> 
               </p> 
              </div>
              <div class="col-4"><a style="cursor: pointer;" id="cerrar-cookies"><p class="text-center"><input type="hidden" value="geo"><button type="button" class="btn btn-info btn-circle"><i class="fa fa-check"></i></button></p></a>
              </div>
            </div>
          </div>

</div>
  <?php } ?>
  

  
  <!--FIN AVISO DE COOKIES-->

  <!-- BOTON SUBIR
  <div class="scroll-to-top  position-fixed ">
    <a class="js-scroll-trigger d-block text-center text-white rounded" href="#page-top">
      <i class="fa fa-chevron-up"></i>
    </a>
  </div>
  FIN BOTON SUBIR-->


 <!-- SCRIPTS NECESARIOS-->
 
  <!-- JQUERY-->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <!-- JQUERY-->
  
  <!-- UNDERSCORE-->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js"></script>
  <!-- UNDERSCORE-->
  
  <!-- MOMENT -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment.min.js"></script>
  <!-- MOMENT -->
  
  <!-- WOW ANIMACION -->
  <script src="js/wow.min.js?v=<?php echo $version?>"></script>
  <!-- WOW ANIMACION -->

  
  <!-- BOOTSTRAP BUNDLE -->
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js?v=<?php echo $version?>"></script>
  <!-- BOOTSTRAP BUNDLE -->
  
  <!-- JQUERY EASING -->
  <script src="vendor/jquery-easing/jquery.easing.min.js?v=<?php echo $version?>"></script>
  <!-- JQUERY EASING -->
<script type="text/javascript">
$(document).ready(function(){  
  var i = 1;
  $('#alternar-panel-oculto-1').click(function() {

  
      if(i==1){
        $('#alternar-panel-oculto-1').html('Ver menos');
        i = 0;
      }else{
        $('#alternar-panel-oculto-1').html('Ver más');
        i = 1;
      }                
});
});
</script>
<script type="text/javascript">
$(document).ready(function(){  
  var k = 1;
  $('#alternar-panel-oculto-2').click(function() {

  
      if(k==1){
        $('#alternar-panel-oculto-2').html('Ver menos');
        k = 0;
      }else{
        $('#alternar-panel-oculto-2').html('Ver más');
        k = 1;
      }                
});
});
</script>
 
  <!-- CUSTOM -->
  <script src="js/script.js?v=<?php echo $version?>"></script>
  <!-- CUSTOM -->
  
<!-- FIN SCRIPTS NECESARIOS-->

</body>

</html>
