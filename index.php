
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
          <div class="col-lg-12 text-center text-white div-bottom">
              <div class="container">
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
  
</div>
  <!-- FIN HEADER -->


<!--EMPIEZA SECCION PRINCIPALES DESTINOS -->
 <section class="py-5">
   <div class="container">
     <div class="row mb-4">
       <div class="col-lg-12 ">
         <h2 class="page-section-heading text-center text-uppercase text-secondary mb-0 ">
          <?=$lang["principales_actividades"];?></h2>
       </div>
     </div>
     <div class="row ">
         
       <!--EL BUCLE DEBES EMPEZARLO ACA-->        
       <!--EMPIEZA CARD DESTINOS-->    
  <?php 

$categorias=getCategoriasLimit6();

 

for ($i=0; $i < count($categorias); $i++) { 
	
	$idCategoria_servicio=$categorias[$i]['idCategoria_servicio'];

$OpinionesCategoria=OpinionesCategoria($idCategoria_servicio);

$CantOpinionesCategoria=count($OpinionesCategoria);
$estrellas=getEstrellasCategoria($idCategoria_servicio);



?>
 
       <div class="col-lg-4 col-md-6 col-12 mb-4">

         <a href="categorias?idCategoria=<?=$categorias[$i]['idCategoria_servicio']?>" class="imagen">
             
           <div class="img-c" style="background-image: url(admin/img/categoria_servicio/<?=$categorias[$i]['img_categoria_servicio']?>)">
              
             <!-- EMPIEZA LA INFO AL PASAR EL HOVER -->   
             <div class="info d-md-block d-none">
             <h3 class="headline text-uppercase semibold"><?=$lang[$categorias[$i]['nombre_categoria_servicio']];?></h3>
               <div class="container">
                 <div class="row">
                   <div class="col-md-6">
                   <div class="descripcion text-white">
                     <p class=" mb-0">
                        <strong style="font-size:30px;"><?=$categorias[$i]['nViajeros']?></strong> 
                     </p>
                      <p class="mb-0 p"><?= $lang["viajeros_ya_lo_han_disfrutado"]?></p>
                   </div>
                   </div>
                    <div class="col-md-6">
                    <div class="descrip-opinion text-white">
                    <p class=" mb-0">
                       <strong style="font-size:30px;"><?=$estrellas?>*****</strong> 
                    </p>
                    <p class="mb-0 p">
                        <?=$CantOpinionesCategoria;?> <?=$lang["opiniones"];?>
                    </p>
                    </div>
                   </div>
                 </div>
               </div>
             </div>
             <!-- FIN LA INFO AL PASAR EL HOVER -->   
             
           </div>
           
           <h3 class="title-categoria text-uppercase texto-shadow text-white"><?=$lang[$categorias[$i]['nombre_categoria_servicio']];?></h3>
         </a>

       </div>
       <!--FIN CARD DESTINOS-->


       <?php

  }


 ?>
       <!--FIN CARD DESTINOS-->
       <!--EL BUCLE DEBES TERMINARLO ACA--> 

       
     </div>
     <!--COLUMNA VER MAS-->
     <div class="row collapse" id="VerMasActividades">
      <!--BUCLE MAS-->
       <!--EL BUCLE DEBES EMPEZARLO ACA-->        
       <!--EMPIEZA CARD DESTINOS-->    
  <?php 

$categorias=getCategoriasLimit612();

 

for ($i=0; $i < count($categorias); $i++) { 

	$idCategoria_servicio=$categorias[$i]['idCategoria_servicio'];

$OpinionesCategoria=OpinionesCategoria($idCategoria_servicio);

$CantOpinionesCategoria=count($OpinionesCategoria);
$estrellas=getEstrellasCategoria($idCategoria_servicio);


?>
 
       <div class="col-lg-4 col-md-6 col-12 mb-4">

         <a href="categorias?idCategoria=<?=$categorias[$i]['idCategoria_servicio']?>" class="imagen">
             
           <div class="img-c" style="background-image: url(admin/img/categoria_servicio/<?=$categorias[$i]['img_categoria_servicio']?>)">
              
             <!-- EMPIEZA LA INFO AL PASAR EL HOVER -->   
             <div class="info d-md-block d-none">
             <h3 class="headline text-uppercase semibold"><?=$lang[$categorias[$i]['nombre_categoria_servicio']];?></h3>
               <div class="container">
                 <div class="row">
                   <div class="col-md-6">
                   <div class="descripcion text-white">
                     <p class=" mb-0">
                        <strong style="font-size:30px;"><?=$categorias[$i]['nViajeros']?></strong> 
                     </p>
                      <p class="mb-0 p"><?= $lang["viajeros_ya_lo_han_disfrutado"]?></p>
                   </div>
                   </div>
                    <div class="col-md-6">
                    <div class="descrip-opinion text-white">
                    <p class=" mb-0">
                       <strong style="font-size:30px;"><?=$estrellas?>*****</strong> 
                    </p>
                    <p class="mb-0 p">
                        <?=$CantOpinionesCategoria;?> <?=$lang["opiniones"];?>
                    </p>
                    </div>
                   </div>
                 </div>
               </div>
             </div>
             <!-- FIN LA INFO AL PASAR EL HOVER -->   
             
           </div>
           
           <h3 class="title-categoria text-uppercase texto-shadow text-white"><?=$lang[$categorias[$i]['nombre_categoria_servicio']];?></h3>
         </a>

       </div>
       <!--FIN CARD DESTINOS-->


       <?php

  }


 ?>
       <!--FIN CARD DESTINOS-->
       <!--EL BUCLE DEBES TERMINARLO ACA--> 

       


     </div>
<?php if (count(getCategoriasLimit6())>6) {
  ?>


            <!--BUCLE MAS-->
            <div class="col-lg-12 text-center ">
          <button class="btn  btn-white" type="button" id="alternar-panel-oculto-1" data-toggle="collapse" data-target="#VerMasActividades" aria-expanded="false" aria-controls="VerMasActividades">
           <?=$lang["ver_mas"];?>
          </button>
       </div>
    <!--FIN COLUMNA VER MAS-->
      <?php
  // code...
} ?>

   </div>
 </section>
<!--EMPIEZA SECCION PRINCIPALES DESTINOS -->


<!--EMPIEZA SECCION Actividades destacadas-->
 <section class="">
   <div class="container">
     <div class="row mb-4">
       <div class="col-lg-12 ">
         <h2 class="page-section-heading text-center text-uppercase text-secondary mb-0 "><?=$lang["actividades_destacadas"]?></h2>
       </div>
     </div>
     <div class="row ">
         
         
    <!--EL BUCLE DEBES EMPEZARLO ACA-->        
    <!--EMPIEZA CARD DE ACTIVIDADES DESTACADAS-->     
   
<?php

$servicios=getServiciosLimit6();
   /* $query=mysqli_query($conection,"SELECT * FROM servicio
     inner JOIN categoria_servicio ON servicio.idCategoria_servicio = categoria_servicio.idCatSrv 
    LEFT JOIN usuario us ON servicio.operador_alta = us.idUsuario
      ORDER BY idServicio DESC LIMIT 6");
    $result=mysqli_num_rows($query);
    if ($result > 0) {
      while ($data = mysqli_fetch_array($query)) {
$idServicio=$data['idServicio'];
$idcatsrv=$data['idCatSrv'];

if($idcatsrv==4){
    $link='href="verServicioPaquete.php?id='.$idServicio.'"';
}
else{
  $link='href="verServicio.php?id='.$idServicio.'"';
}
*/
/*$OpinionesCategoria=OpinionesCategoria($idcatsrv);
$CantOpinionesCategoria=count($OpinionesCategoria);
$textoMiniatura=TextoMiniaturas($idServicio)[0][1];

$OpinionesServicio=OpinionesServicio($idServicio);
$CantOpinionesServicio=count($OpinionesServicio);
$acum=$OpinionesServicio[$CantOpinionesServicio-1][2];
if(!$CantOpinionesServicio==0){
$puntuacion=round($acum/$CantOpinionesServicio, 1);
}




      
        $nroFotos=count(DevuelveFotosServicio($data['idServicio']));
        $idServicio=$data['idServicio'];
    $fotos=DevuelveFotosServicio($idServicio);
    $monedaServicio=MonedaServicio($idServicio);*/
    for ($i=0; $i < count($servicios); $i++) { 
    	$idServicio=$servicios[$i]["idServicio"];
      $fecha=date("Y-m-d");
  
    	$salidas=getSalidasFechaLuegoIdServicio($fecha,$idServicio);
    if (count($salidas )>0) {
      # code...
   
  $idMoneda=$salidas[0]['idMoneda'];
      $idServicioSalidas=$salidas[0]['idServicioSalidas'];
      $tarifas=getTarifas($idServicioSalidas);

   $tarifa=calculaTarifa($tarifas[0]['idServicioSalidasTarifas'],
1);
      $precioSugerido=($tarifa[0]["valorSym"]);

       }
       else{
        $precioSugerido="AGOTADO!!!";
       }

    	$OpinionesServicio=GetOpinionesServicio($idServicio);
    	$estrellasServicio=GetEstrellasServicio($idServicio);
    	$textoMiniatura=getTextoMiniatura($servicios[$i]["idTextoMiniaturas"])[0]["texto"];
	  $fotos=getFotosServicio($idServicio);

   
    	?>

    <!--EMPIEZA CARD DE ACTIVIDADES DESTACADAS-->     
       <div class="col-lg-4 col-md-6 mb-4 no-padding">
         <a href="servicio?id=<?=$idServicio;?>"class="destacados">
             
             <!--EMPIEZA INFO AL PASAR HOVER-->
              <div class="d-destacado d-md-block d-none ">
                 <div class="row wow animated bounceInUp" data-wow-duration="2s">
                  <div class="col-lg-12 texto-destacado">
                      <p class="title-big mb-0" style="margin-bottom:-15px !important;;margin-top: 20px;line-height: 24px;"><?=$servicios[$i]["nombre_servicio"];?></p>
                      <div class="d-flex flex-row">
                        <div class="">
                        <p class="title-number mb-0"><?=$estrellasServicio;?></p>
                        </div>
                        <div class="p-1 my-auto">
                           <p class="mb-0" style="margin-top:20px;">
                           <i class="fa fa-star"></i>
                           <i class="fa fa-star"></i>
                           <i class="fa fa-star"></i>
                           <i class="fa fa-star"></i>
                           <i class="fa fa-star"></i>
                           </p>
                           <p><small class="text--rating-total "><?=count($OpinionesServicio);?> <?=$lang["opiniones"];?></small></p>
                        </div>
                      </div>
                      <p class="p-text" style="margin-top:-5px;"><?=$servicios[$i]["descripcion_corta"];?></b></p>
                  </div>
                </div>
              </div>
              <!-- FIN INFO AL PASAR HOVER -->
              
              <!--EMPIEZA CARD POR DEFECTO SIN EL HOVER-->
             <div class="card card-destacadas ">
             <img src="admin/classes/imgServicio/<?=$fotos[0]['ruta'];?>" class="img-fluid img-card-top img-destacada " >
             <div class="destacado">
               <h5 class="text-uppercase text-white"><?=$textoMiniatura;?></h5>
             </div>
             <div class="card-body card-body-10">
               <div class="row" style="position:relative">
                 <div class="col-lg-9 col-md-9 col-8">
                 <h5 ><?=$servicios[$i]["nombre_servicio"];?></h5>
                 </div>
                 <div class="col-lg-3 col-md-3 col-4">
                 <h5 class="text-right precio-card"><?=$precioSugerido;?></h5>
                 </div>
               </div>
               <p class="mb-0"><?=$estrellasServicio;?>/10 <span class="text-gris"><?=count($OpinionesServicio);?> <?=$lang["opiniones"];?></span></p>
             </div>
           </div>
           <!--FIN CARD POR DEFECTO SIN EL HOVER-->
           
         </a>
       </div>
       <!--FIN CARD DE ACTIVIDADES DESTACADAS-->
       <!--EL BUCLE DEBES TERMINARLO ACA--> 

    	<?php
    }

 ?>

</div>

       <!--EL BUCLE DEBES TERMINARLO ACA--> 
       
        <!--EL BUCLE VER MAS--> 
  <div class="row collapse" id="VerMasActividades-d">
   

<?php

$servicios=getServiciosLimit612();
 
    for ($i=0; $i < count($servicios); $i++) { 
      if ($i<12) {
        
    $idServicio=$servicios[$i]["idServicio"];
      $fecha=date("Y-m-d");
  
      $salidas=getSalidasFechaLuegoIdServicio($fecha,$idServicio);
      if (count($salidas)>0) {
  $idMoneda=$salidas[0]['idMoneda'];
      $idServicioSalidas=$salidas[0]['idServicioSalidas'];
      $tarifas=getTarifas($idServicioSalidas);

   $tarifa=calculaTarifa($tarifas[0]['idServicioSalidasTarifas'],
1);
      $precioSugerido=($tarifa[0]["valorSym"]);

      

      $OpinionesServicio=GetOpinionesServicio($idServicio);
      $estrellasServicio=GetEstrellasServicio($idServicio);
      $textoMiniatura=getTextoMiniatura($servicios[$i]["idTextoMiniaturas"])[0]["texto"];
    $fotos=getFotosServicio($idServicio);
   
    	?>

    <!--EMPIEZA CARD DE ACTIVIDADES DESTACADAS-->     
       <div class="col-lg-4 col-md-6 mb-4 no-padding">
         <a href="servicio.php?id=<?=$idServicio;?>"class="destacados">
             
             <!--EMPIEZA INFO AL PASAR HOVER-->
              <div class="d-destacado d-md-block d-none ">
                 <div class="row wow animated bounceInUp" data-wow-duration="2s">
                  <div class="col-lg-12 texto-destacado">
                      <p class="title-big mb-0" style="margin-bottom:-15px !important;;margin-top: 20px;line-height: 24px;"><?=$servicios[$i]["nombre_servicio"];?></p>
                      <div class="d-flex flex-row">
                        <div class="">
                        <p class="title-number mb-0"><?=$estrellasServicio;?></p>
                        </div>
                        <div class="p-1 my-auto">
                           <p class="mb-0" style="margin-top:20px;">
                           <i class="fa fa-star"></i>
                           <i class="fa fa-star"></i>
                           <i class="fa fa-star"></i>
                           <i class="fa fa-star"></i>
                           <i class="fa fa-star"></i>
                           </p>
                           <p><small class="text--rating-total "><?=count($OpinionesServicio);?> opiniones</small></p>
                        </div>
                      </div>
                      <p class="p-text" style="margin-top:-5px;"><?=$servicios[$i]["descripcion_corta"];?></b></p>
                  </div>
                </div>
              </div>
              <!-- FIN INFO AL PASAR HOVER -->
              
              <!--EMPIEZA CARD POR DEFECTO SIN EL HOVER-->
             <div class="card card-destacadas ">
             <img src="admin/classes/imgServicio/<?=$fotos[0]['ruta'];?>" class="img-fluid img-card-top img-destacada " >
             <div class="destacado">
               <h5 class="text-uppercase text-white"><?=$textoMiniatura;?></h5>
             </div>
             <div class="card-body card-body-10">
               <div class="row" style="position:relative">
                 <div class="col-lg-9 col-md-9 col-8">
                 <h5 ><?=$servicios[$i]["nombre_servicio"];?></h5>
                 </div>
                 <div class="col-lg-3 col-md-3 col-4">
                 <h5 class="text-right precio-card"><?=$precioSugerido;?></h5>
                 </div>
               </div>
               <p class="mb-0"><?=$estrellasServicio;?>/10 <span class="text-gris"><?=count($OpinionesServicio);?> <?=$lang["opiniones"];?></span></p>
             </div>
           </div>
           <!--FIN CARD POR DEFECTO SIN EL HOVER-->
           
         </a>
       </div>
       <!--FIN CARD DE ACTIVIDADES DESTACADAS-->
       <!--EL BUCLE DEBES TERMINARLO ACA--> 

    	<?php
    }}}

 ?>



  
  </div>

  <?php   if (count(getServiciosLimit612())>0) {
   ?>


<!-- BUCLE VER MAS--> 

       <div class="col-lg-12 text-center py-5">
         <button class="btn  btn-white" type="button" id="alternar-panel-oculto-2" data-toggle="collapse" data-target="#VerMasActividades-d" aria-expanded="false" aria-controls="VerMasActividades-d">
            <?=$lang["ver_mas"];?>
          </button>
       </div>
          <?php
  } ?>   <!--FIN BUCLE VER MAS--> 
     </div>
   </div>
 </section>
 <!--EMPIEZA SECCION Actividades destacadas-->



  




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
            <label class="sr-only" for="s"><?=$lang["donde_vamos"]?>?</label>
          <div class="input-group ">
            <input class="field form-control" id="buscar" name="buscar" type="text" placeholder="¿Dónde vamos?" value="">
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
