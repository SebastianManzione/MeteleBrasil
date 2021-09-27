 <?php


include("includes/navbar.php");

require("admin/classes/categoria.php");
require("admin/classes/opiniones_categoria.php");
require("admin/classes/servicio_opiniones.php");
require("admin/classes/texto_miniaturas_blog.php"); 
require("admin/classes/texto_miniaturas.php"); 

require("admin/classes/accesibilidad.php"); 
require("admin/classes/texto_viajeros.php");
require("admin/classes/paises.php");
require("admin/classes/destinos.php");




     if (isset($_GET["post"]) ) {
                    $idPost=$_GET['post'];
                    $articulo=getArticuloBlog($idPost);
                     $titulo=$articulo[0]["titulo"];
                    $descripcionCorta=$articulo[0]["descripcionCorta"];
                    $contenido=$articulo[0]["contenido"];
                   $tipsYConsejos=$articulo[0]["tipsYConsejos"];
                    $observaciones=$articulo[0]["observaciones"];
                  $idDestino=$articulo[0]["idDestino"];
                  $destino=getDestino($idDestino);
                  $pais=getPais($destino[0]["idPais"]);
                  $fotos=getFotosBlogIdPost($idPost);  

if (count($fotos)<1) {

 $fotos[0]["ruta"]="default.jpg";

}

        



            if (count($articulo)> 1 || count($articulo )<1) {



              alertar($lang["el_post_no_existe"],$lang["error"]);



            }







             



                



     }







     ?>







     <?php

include('blogHead.php');

?>

<?php include("articuloMovil.php"); ?>





 <!--botones de compartir whatsapp y facebook-->



<script type="text/javascript" src="https://platform-api.sharethis.com/js/sharethis.js#property=60f5f984a5ae4f00192544a9&product=inline-share-buttons" async="async"></script>













<section class="py-5 d-md-block d-none">



  <div class="container container_r clearfix">



      <div class="row">



           <!--COL INFORMACION IZQUIERDA-->



          <div class="col-lg-12">



             <div id="content">



                 



        



        <!--CONTENEDOR DESCRIPCION-->



        <div class="descripcion" id="descripcion">







          <!--TEXTO DESTACADO-->



      <p>   <?=$descripcionCorta;?></p>



          <!--FIN TEXTO DESTACADO-->







                   <!--SLIDER-->





        <h2 class="py-4 text-primary" name="desc"><?=$titulo;?></h2>

 <?=$contenido;?>



          <div id="carrouseldivPc" class="carousel slide" data-ride="carousel">



            <ol class="carousel-indicators">



              



      <!--CARGA DE IMAGENES-->



                                     <?php //********************arranca fotos*******************************************



      # code...  



                                    



for ($i=0; $i < count($fotos); $i++) { 



    $active='';



  if ($i==0) {



   $active='class="active"';



  }?>



  <li data-target="#carrouseldivPc" data-slide-to="<?=$i;?>" <?= $active;?>></li>



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
                  <span class="sr-only"><?=$lang["anterior"];?></span>
                </a>


                <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                  <span class="sr-only"><?=$lang["Proxima"];?></span>
                </a>
           </div>


         <h2 class="py-4 text-primary"><?=$lang["tips_y_concejos"];?></h2>
                <?=$tipsYConsejos;?>
         <h2 class="py-4 text-primary"><?=$lang["observaciones"];?></h2>
                <?=$observaciones;?>
           <!--BARRA DESPLAZADA-->
          </div>
      </div>
            <!--CAJA DE COMENTARIOS-->


<div class="card direct-chat direct-chat-primary" >
              <div class="card-header">
                <h3 class="card-title">Comentarios</h3>           
              </div>

              <!-- /.card-header -->
<!--botones de compartir whatsapp y facebook-->

<div class="row" style="margin-right: 3%; margin-left: 3%;">
    <div class="col fb-share-button" data-href="https://www.metelebrasil.com/articuloBlog?post=<?=$idPost?>" data-layout="button_count" data-size="small"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fwww.metelebrasil.com%2FarticuloBlog%3Fpost%3D<?=$idPost?>&amp;src=sdkpreparse" class="btn btn-primary btn-lg active btn-block"><i class="fab fa-facebook-f"></i>  <?=$lang["compartir_en_facebook"];?></a>
    </div>
    <div class="col">
     <a class="btn btn-success btn-lg active btn-block" href="whatsapp://send?text=Metelebrasil%20https://www.metelebrasil.com/articuloBlog?post=<?=$idPost;?>"><i class="fab fa-whatsapp"></i>  <?=$lang["compartir_en_whatsapp"];?></a>
  </div>

</div>


<div class="row" style="margin-right: 3%; margin-left: 3%;">

<div class="fb-comments" data-href="https://metelebrasil.com/articuloBlog?post=<?=$idPost;?>" data-width="100%" data-numposts="5"></div>


</div>



<!--FINALIZA CAJA DE COMENTARIOS-->

</section>



<section>
 <div class="container" >
         <div class="row">
           <div class="col-lg-12">
            <div class="container mb-5" >
              <h2 class="text-center mb-4"><?=$lang["tambien_te_puede_interesar"];?></h2>
             <div class="row">





<?php $serviciosRelacionados=getServiciosidDestino($idDestino); 

if (count($serviciosRelacionados)>=3) {
for ($i=0; $i < 2; $i++) { 
      $idServicioRelacionado=$serviciosRelacionados[$i]["idServicio"];
    $fecha=date("Y-m-d");
    $textoMiniatura=getTextoMiniatura($serviciosRelacionados[$i]["idTextoMiniaturas"])[0]["texto"];
    $salidas=getSalidasFechaIdServicio($fecha,$idServicioRelacionado);
  if (count($salidas)>0) {
       $idMoneda=$salidas[0]['idMoneda'];
      $idServicioSalidas=$salidas[0]['idServicioSalidas'];
      $tarifas=getTarifas($idServicioSalidas);
   $tarifa=calculaTarifa($tarifas[0]['idServicioSalidasTarifas'],1);
      $precioSugerido=($tarifa[0]["valorSym"]);
      $OpinionesServicio=GetOpinionesServicio($idServicioRelacionado);
      $estrellasServicio=GetEstrellasServicio($idServicioRelacionado);
      $fotos=getFotosServicio($idServicioRelacionado);
?>

  <div class="col-lg-4">
                   <div class="card card-destacadas mb-5 shadow ">
                   <img src="admin/classes/imgServicio/<?=$fotos[0]['ruta'];?>" class="img-fluid img-card-top img-destacada">
                   <div class="destacado">
                     <h5 class="text-uppercase text-white"><?=$textoMiniatura;?></h5>
                   </div>
                   <div class="card-body">
                     <h3><a href="servicio?id=<?=$idServicioRelacionado?>"><?=$serviciosRelacionados[$i]["nombre_servicio"];?></a></h3>
                     <p class="text-primary"><strong><?=$estrellasServicio;?>/10</strong> <span class="text-gris"><?= count($OpinionesServicio);?><?=$lang["opiniones"];?></span></p>
                     <p><?=$serviciosRelacionados[$i]["descripcion_corta"];?></p>
                    <h3 class="text-primary"><?=$precioSugerido;?></h3>
                  </div>
                   <a href="servicio?id=<?=$idServicioRelacionado?>" class="btn-reserva-destacada"><?=$lang["reservar"]?></a>
                 </div>
                </div>
<?php
} }}
?>




<?php 

$articulosRecomendados=getArticulosBlogIdDestino($idDestino);

for ($i=0; $i < count($articulosRecomendados); $i++) { 

if (count($serviciosRelacionados)>1) {
  $cantidadRelleno=1;
}
else{
  $cantidadRelleno=2;
}

  if ($i<=$cantidadRelleno) {

  $idPost=$articulosRecomendados[$i]["idPost"];
  $titulo=$articulosRecomendados[$i]["titulo"];
$descripcionCorta=$articulosRecomendados[$i]["descripcionCorta"];
$img=getImgArticulo($idPost);
$idTextoMiniaturas=$articulosRecomendados[$i]["idTextoMiniaturasBlog"];
$textoMiniatura=getTextoMiniaturaBlog($idTextoMiniaturas)[0]["texto"];
$foto='default.jpg';
if (count($img)>0) {
  $foto=$img[0]['ruta'];
}
  ?>


  <div class="col-lg-4">
                   <div class="card card-destacadas mb-5 shadow ">
                   <img src="admin/classes/imgBlog/<?=$foto;?>" class="img-fluid img-card-top img-destacada " >
                   <div class="destacado">
                     <h5 class="text-uppercase text-white"><?=$textoMiniatura;?></h5>
                   </div>
                   <div class="card-body">
                     <h3><a href="articuloBlog?post=<?=$idPost?>"><?=$titulo;?></a></h3>
                     <p class="text-primary"><strong></strong> <span class="text-gris"><?=$descripcionCorta;?></span></p>
                     <p></p>
                     <h3 class="text-primary"></h3>
                   </div>
                 </div>
                </div>
  <?php

}  } ?>
             </div>
            </div>
         </div>
   </div>
</div>
  </section>



<!--FIN BOTON RESERVA MOVIL-->




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



            <label class="sr-only" for="s"><?=$lang["donde_vamos"];?></label>



          <div class="input-group ">



            <input class="field form-control" id="buscar" name="buscar" type="text" placeholder="¿Dónde vamos?" value="">



            <span class="input-group-append">



              <button class="submit btn btn-primary" id="searchsubmit2" name="submit" type="submit"><?=$lang["buscar"];?><i class="fa fa-arrow-right"></i></button>



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



                            <h3 class="text-center text-primary"><?=$lang["top_actividades"];?></h3>



                          </div>



                        </div>



                        <div class="row  mb-4">



                            



                            <!--EL BUCLE DE LOS RESULTADOS DEBE IR ACA-->   



                            <div class="col-md-3 col-6 mb-3">



                            <h4 class=" mb-0"><a  class="text-destinos">Florianópolis</a></h4>



                            <small>Santa Catarina</small>



                            </div>



                            <!--FIN BUCLE DE LOS RESULTADOS DEBE IR ACA-->   



                  



                         </div>



                         <div class="row py-4">



                          <div class="col-lg-12">



                            <h3 class="text-center"><a href="" class="btn btn-outline-primary btn-white" style="border-radius:25px;"><?=$lang["ver_todos_los_destinos"];?></a></h3>



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



  <script src="js/wow.min.js?v=<?php echo $version?>"></script>



  <!-- WOW ANIMACION -->



  







  



  <!-- BOOTSTRAP BUNDLE -->



  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js?v=<?php echo $version?>"></script>



  <!-- BOOTSTRAP BUNDLE -->



  



  <!-- JQUERY EASING -->



  <script src="vendor/jquery-easing/jquery.easing.min.js?v=<?php echo $version?>"></script>



  <!-- JQUERY EASING -->







  <!-- CUSTOM -->



  <script src="js/script.js?v=<?php echo $version?>"></script>



  <!-- CUSTOM -->



  



  <script type="text/javascript" src="js/rAF.js?v=<?php echo $version?>"></script>



  <script type="text/javascript" src="js/ResizeSensor.js?v=<?php echo $version?>"></script>



  <script type="text/javascript" src="js/sticky-sidebar.js?v=<?php echo $version?>"></script>



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