<?php



 include('includes/navbar.php'); 



 include('admin/classes/categoria.php'); 

  include('admin/classes/fotos_categoria.php'); 



 include('admin/classes/opiniones_categoria.php'); 

 include('admin/classes/servicio_opiniones.php');

    

require("admin/classes/texto_miniaturas.php");

/*



  include($GLOBALS['path'].'/conectar.php');

$ordenar=" ";

if (isset($_GET["priceMin"])) {

  $ordenar="ORDER BY sv.precioSugerido ASC";

}

if (isset($_GET["priceMax"])) {

  $ordenar="ORDER BY sv.precioSugerido DESC";

}

if (isset($_GET['id'])&&is_numeric($_GET['id'])&&$_GET['id']>0) {

   $id=$_GET['id'];

$consulta="WHERE idCategoria_servicio=".$id ;

$fotoSrv=DevuelveFotosCategoria($id)[0];



$OpinionesCategoria=OpinionesCategoria($id);

$CantOpinionesCategoria=count($OpinionesCategoria);

  

// header("location: ./index.php");

}else if(isset($_GET["buscar"])&&is_string($_GET["buscar"])){

  $buscar=  $_GET["buscar"];

   $_GET["buscar"] = preg_replace('/\&(.)[^;]*;/', '\\1', $_GET["buscar"]);

$precioMin=10;

 $id="-1";

  $consulta=" WHERE sv.nombre_servicio LIKE '%".$_GET["buscar"]."%' 

    OR sv.descripcion_servicio LIKE '%".$_GET["buscar"]."%' OR sv.descripcion_corta LIKE '%".$_GET["buscar"]."%' OR csv.nombre_categoria_servicio LIKE '%".$_GET["buscar"]."%' ".$ordenar;



} 

else{

   $id="0";

  $consulta=" ".$ordenar;



}



$consultaQuery="SELECT * FROM servicio sv

            INNER JOIN categoria_servicio csv ON sv.idCategoria_servicio = csv.idCatSrv  

            INNER JOIN usuario us ON sv.operador_alta = us.idUsuario

           

            ".$consulta;



$hoy= date("Y-m-d");

if(isset($_GET["hoy"])){

$consultaQuery="SELECT * FROM servicio sv

            INNER JOIN categoria_servicio csv ON sv.idCategoria_servicio = csv.idCatSrv  

            INNER JOIN usuario us ON sv.operador_alta = us.idUsuario

            LEFT JOIN horarios hss ON sv.idServicio=hss.servicioId

            LEFT JOIN horarios_paquetes hsp ON sv.idServicio=hsp.servicioId



            ".$consulta." WHERE hss.fechaIn='".$hoy."'

           ".$ordenar;



}

if(isset($_GET["manana"])){



$manana= date("Y-m-d",strtotime($hoy."+ 1 days"));

  $ordenar=" sv.idServicio ";

 if(isset($_GET["price"])){

  $ordenar=" sv.pAdulto ";

 }  

$consultaQuery="SELECT * FROM servicio sv

            INNER JOIN categoria_servicio csv ON sv.idCategoria_servicio = csv.idCatSrv  

            INNER JOIN usuario us ON sv.operador_alta = us.idUsuario

            LEFT JOIN horarios hss ON sv.idServicio=hss.servicioId

            LEFT JOIN horarios_paquetes hsp ON sv.idServicio=hsp.servicioId



            ".$consulta." WHERE hss.fechaIn='".$manana."'

            ".$ordenar;



} */

$busqueda="";

if (isset($_GET["idCategoria"])) {



$idCategoria=$_GET["idCategoria"];

$categorias=getCategoria($idCategoria);

$servicios=getServiciosidCategoria_servicio($idCategoria);

 

  $nViajeros=$categorias[0]["nViajeros"];

   $id=$categorias[0]["idCategoria_servicio"];

   $nombre_categoria=$categorias[0]["nombre_categoria_servicio"];

   $opiniones_categoria=OpinionesCategoria($id);

   $cantidad_opiniones_categoria=count($opiniones_categoria);

   $fotos=$categorias[0]["img_categoria_servicio"];

}

else if (isset($_GET["buscar"])) {

$busqueda=$_GET["buscar"];

  $idCategoria=0;



  $servicios=getServiciosBusqueda($_GET["buscar"]);

 



 $categorias=getCategorias();

 $nViajeros=rand(690,1200); 

$nombre_categoria=" Todas Las Categorías";

$opiniones_categoria=array();

 $cantidad_opiniones_categoria=rand(100,500); ;

    $fotos="sinCategoria.jpg";







}

else{

  $idCategoria=0;

  $servicios=getServicios();

 



 $categorias=getCategorias();

 $nViajeros=rand(690,1200); 

$nombre_categoria=" Todas Las Categorías";

$opiniones_categoria=array();

 $cantidad_opiniones_categoria=rand(100,500); ;

    $fotos="sinCategoria.jpg";

}





$cantidad_servicios_categoria=count($servicios);



        

?>

 <!--SECCION HEADER-->

<section id="header-destinos"  style="background-image: url('admin/img/categoria_servicio/<?= $fotos; ?> ');" >

  <div class="container mb-5">

    <div class="row mb-4">

      <div class="col-lg-12">

          

          <!--BUCLE DE LOS RESULTADOS AQUI-->	

        <div class="badge badge-primary badge-ciudad"><?php //echo NombreCategoria($id); ?></div>

         <!--FIN BUCLE DE LOS RESULTADOS AQUI-->

         

          <!--TITULO-->

        <h1 class="text-white titulo-categoria py-2 bold texto-shadow"><?=$nombre_categoria; ?></h1>

         <!--TITULO-->

        

        

        <!--DESPLIEGUE DE LISTA

        <ul class="lista-ciudad d-md-block d-none">

          <li><a href="#" class="text-white texto-shadow">Actividades</a></li>

          <li><a href="#" class="text-white texto-shadow">Visitas guiadas</a></li>

          <li><a href="#" class="text-white texto-shadow">Excursiones</a></li>

          <li><a href="#" class="text-white texto-shadow">Traslados aeropuerto</a></li>

        </ul>

    FIN DESPLIEGUE DE LISTA-->

        

      </div>

    </div>

  </div>

 <!--header-->







 <!-- CONTENEDOR DE CARACTERISTICAS-->

  <div class="container z-index d-md-block d-none ">

    <div class="row text-white">

      <div class="col-lg-3 col-md-3">

          <h2 class="title-numeros mb-0 bold"><?= $cantidad_servicios_categoria; ?></h2>

          <p class="text-d-number"><?= $nombre_categoria ?></p>

      </div>

      <div class="col-lg-3 col-md-3">

          <h2 class="title-numeros mb-0 bold"><?= $nViajeros; ?></h2>

          <p class="text-d-number"><?=$lang["viajeros_lo_han_disfrutado"]?></p>

      </div>

      <div class="col-lg-3 col-md-3">

          <h2 class="title-numeros mb-0 bold"><?=$cantidad_opiniones_categoria;?></h2>

          <p class="text-d-number"><?=$lang["opiniones_reales"]?></p>

      </div>

      <div class="col-lg-3 col-md-3">

          <h2 class="title-numeros mb-0 bold">9,2</h2>

          <p class="text-d-number"><?=$lang["asi_nos_puntuan"]?></p>

      </div>

    </div>

  </div>

   <!-- FIN CONTENEDOR DE CARACTERISTICAS-->



<br>



  <!-- CONTENEDOR DE FRANJA TRANSPARENTE-->

  <div class="container-fluid d-md-block d-none">

     <div class="row">

      <div class="col-lg-12">

        <div class="div-fondo"></div>

      </div>

    </div>

  </div>

    <!-- FIN CONTENEDOR DE FRANJA TRANSPARENTE-->

  

</section>

 <!--FIN SECCION HEADER-->







<!--SECCION ACTIVIDADES-->



<section class="mt-4">

  <div class="container">

    <div class="row">

        

        <!--COLUMNA DERECHA DE BUSQUEDA-->

      <div class="col-lg-4 d-md-block d-none">

          

           <!--CARD PRINCIPAL DE BUSQUEDA-->

        <div class="card card-seccion-right  ">

          <div class="card-body">

              <div>

                <form class="form-buscar mb-5 " action="categorias.php" method="get">

            <label class="sr-only" for="s"><?= $lang["que_hacemos"]; ?></label>

          <div class="input-group">

            <input class="field form-control form-control-search"  name="buscar" type="text" placeholder="<?= $lang["que_hacemos"]; ?>" value="<?=$busqueda?>">

            <span class="input-group-append">

              <button class="submit btn btn-primary" id="searchsubmit" name="submit" type="submit"><?= $lang["buscar"]; ?> <i class="fa fa-arrow-right"></i></button>

            </span>

          </div>

</form>

        </div>

              <!--ACORDEON PARA FILTRO DE BUSQUEDA EN PC-->

            <div class="accordion" id="Disponibilidad" style="display: none;">

                 <div class="card card-accordion">

                    <div class="" id="headingOne">

                      <h5 class="mb-0">

                        <a class="btn btn-accordion " href="#" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">

                          <?=$lang["disponibilidad"]?><i class="fa fa-sort-down float-right"></i>

                        </a>

                      </h5>

                    </div>



                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#Disponibilidad">

                      <div class="card-body">

                        <div class="btn-group" role="group" aria-label="Basic example">

                          <?php 



$btnHoy="btn btn-primary btn-size";

$btnManana="btn btn-primary btn-size";           

if (isset($_GET["hoy"]))

 {

$btnHoy="btn btn-primary-selected btn-size";

   }

   if (isset($_GET["manana"]))

 {

$btnManana="btn btn-primary-selected btn-size";

   }







                           ?>

                          <form action="categorias.php" style="display: none;">

                            <input type="hidden" name="hoy">

                          <button type="submit" class="<?=$btnHoy;?>"><?= $lang["hoy"];?></button>

                        </form>

                         <form action="categorias.php" style="display: none;">

                          <input type="hidden" name="manana">

                          <button type="submit" class="<?= $btnManana;?>"><?= $lang["manana"];?></button>

                            </form>

                       

                        </div>

                      </div>

                    </div>

                  </div>

              </div>

              <br>

               <div class="accordion" id="accordionExample">

                 <div class="card card-accordion">

                    <div class="" id="headingOne">

                      <h5 class="mb-0">

                        <a class="btn btn-accordion " href="#" data-toggle="collapse" data-target="#categorias" aria-expanded="true" aria-controls="collapseOne"><?= $lang["categoria"];?><i class="fa fa-sort-down float-right"></i>

                        </a>

                      </h5>

                    </div>



                    <div id="categorias" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">

                      <div class="card-body">

                        <?php 

                       $todas_las_categorias=getCategorias();

                        for ($i=0; $i < count($todas_las_categorias); $i++) { 

                          $idCategoria_todas=$todas_las_categorias[$i]["idCategoria_servicio"];

                          $nombre_categoria_servicio_todas=$todas_las_categorias[$i]["nombre_categoria_servicio"];

$checked="";

$type="";



                          if($idCategoria==$idCategoria_todas){

                           $checked="checked";

                           $type="radio";

                          }

                         echo '    

<a href="categorias?idCategoria='.$idCategoria_todas.'">

                         <div class="custom-control custom-checkbox mb-2">

                          <input type="'.$type.'" class="custom-control-input" id="" '.$checked.'>

                          <label class="custom-control-label" for="">'.$nombre_categoria_servicio_todas.'</label>

                        </div></a>';

                        }

                      ?>

                       

                    

                      

                      </div>

                    </div>

                  </div>

              </div>

              <br>

              <!-- <div class="accordion" id="accordionExample">

                 <div class="card card-accordion">

                    <div class="" id="headingOne">

                      <h5 class="mb-0">

                        <a class="btn btn-accordion " href="#" data-toggle="collapse" data-target="#accesibiliad" aria-expanded="true" aria-controls="collapseOne">

                          Accesibilidad <i class="fa fa-sort-down float-right"></i>

                        </a>

                      </h5>

                    </div>



                    <div id="accesibiliad" class="collapse  show" aria-labelledby="headingOne" data-parent="#accordionExample">

                      <div class="card-body">

                        <div class="custom-control custom-checkbox mb-2">

                          <input type="checkbox" class="custom-control-input" id="customControlInlinea">

                          <label class="custom-control-label" for="customControlInlinea">Accesible</label>

                        </div>

                      </div>

                    </div>

                  </div>

              </div>-->

              <br>

               <div class="accordion" id="accordionExample" style="display: none;">

                 <div class="card card-accordion">

                    <div class="" id="headingOne">

                      <h5 class="mb-0">

                        <a class="btn btn-accordion show" href="#" data-toggle="collapse" data-target="#precio" aria-expanded="true" aria-controls="collapseOne"><?= $lang["ordenar"];?><i class="fa fa-sort-down float-right"></i>

                        </a>

                      </h5>

                    </div>



                    <div id="precio" class="collapse show " aria-labelledby="headingOne" data-parent="#accordionExample">



                      <form class="padding">

                  

                        <div class="btn-group" role="group" aria-label="Basic example">

             

                                   <?php 



$btnMenorPrecio="btn btn-primary btn-size";

$btnMayorPrecio="btn btn-primary btn-size";           

if (isset($_GET["priceMin"]))

 {

$btnMenorPrecio="btn btn-primary-selected btn-size";

   }

   if (isset($_GET["priceMax"]))

 {

$btnMayorPrecio="btn btn-primary-selected btn-size";

   }







                           ?>                      



 <form action="categorias.php" style="display: none;">

                            <input type="hidden" name="priceMin"></input>

                          <button type="submit" class="<?=$btnMenorPrecio;?>"><?= $lang["menor_precio"];?></button>

                        </form>

                                   <form action="categorias.php">

                            <input type="hidden" name="priceMax"></input>

                          <button type="submit" class="<?=$btnMayorPrecio;?>">"mayor_precio"];?></button>

                        </form>





 

                            

                        <!--input type="range" class="form-control-range custom-range"  id="formControlRange" href="#"-->

                      </div>

                    </form>

                    </div>

                  </div>

              </div>

              <br>

             <!--  <div class="accordion" id="accordionExample">

                 <div class="card card-accordion">

                    <div class="" id="headingOne">

                      <h5 class="mb-0">

                        <a class="btn btn-accordion show" href="#" data-toggle="collapse" data-target="#Duración" aria-expanded="true" aria-controls="collapseOne">

                          Duración <i class="fa fa-sort-down float-right"></i>

                        </a>

                      </h5>

                    </div>



                    <div id="Duración" class="collapse show " aria-labelledby="headingOne" data-parent="#accordionExample">

                       <form class="padding">

                      <div class="form-group">

                        <input type="range" class="form-control-range custom-range"  id="formControlRange2">

                      </div>

                    </form>

                    </div>

                  </div>

              </div>-->

               <!--FIN ACORDEON PARA FILTRO DE BUSQUEDA EN PC-->

          </div>

        </div>

        <br>

         <!--CARD ULTIMAS OPINIONES-->



        <div class="card card-ultimas-o d-md-block d-none">

<?php 

      for ($b=0; $b < count($opiniones_categoria) ; $b++) { 

        if ($b <=2) {

          # code...

        

  ?>

              <div class="card-body">

      <p class="text-primary"><?=$opiniones_categoria[$b]['opinion']?></p>

            <p>

            <i class="fa fa-star text-primary"></i>

            <i class="fa fa-star text-primary"></i>

            <i class="fa fa-star text-primary"></i>

            <i class="fa fa-star text-primary"></i>

            <i class="fa fa-star text-primary"></i>

             <?=$opiniones_categoria[$b]['nombre']?></p>

            <hr>

          

         

 </div>

      <?php

      }}



  



 ?>

         

        </div>

        <!--FIN CARD ULTIMAS OPINIONES-->

        <br>

        <!--CARD GUIA PC-->

         <div class="card card-ultimas-o d-md-block d-none">

          <div class="card-body" >

            <form action="guias.php" method="post">

              <input type="hidden" name="idCategoria" value="<?= $id;?>">

            <h4><i class="fa fa-map"></i><?=$lang["conoce_nuestra_guia_de"]?><?= $nombre_categoria; ?></h4>

            <a class="text-white">

              <img src="admin/img/categoria_servicio/<?=$fotos;?>" class="img-fluid img-guia">

              <button class="submit btn btn-primary"><?= $nombre_categoria; ?></button>

            </a>

          </form>

          </div>

        </div>

        <!--FIN CARD GUIA PC-->

         <div id="sidebar" class="mb-5" style="display: none;">

           <div class="sidebar__inner" style="bottom:50px !important">

              <!--CARD PRINCIPAL DE BUSQUEDA-->

        <div class="card card-seccion-right  ">

          <div class="card-body">

              

              <!--ACORDEON PARA FILTRO DE BUSQUEDA EN PC-->

            <div class="accordion" id="Disponibilidad" style="display: none;" >

                 <div class="card card-accordion" style="display: none;">

                    <div class="" id="headingOne">

                      <h5 class="mb-0">

                        <a class="btn btn-accordion " href="#" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne"><?=$lang["disponibilidad"]?> <i class="fa fa-sort-down float-right"></i>

                        </a>

                      </h5>

                    </div>



                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#Disponibilidad">

                      <div class="card-body">

                        <div class="btn-group" role="group" aria-label="Basic example">

                               <form action="categorias.php">

                            <input type="hidden" name="hoy"></input>

                          <button type="submit" class="<?=$btnHoy;?>"><?=$lang["Hoy"]?></button>

                        </form>

                         <form action="categorias.php">

                          <input type="hidden" name="manana"></input>

                          <button type="submit" class="<?= $btnManana;?>"><?=$lang["manana"]?></button>

                            </form>

                        </div>

                      </div>

                    </div>

                  </div>

              </div>

              <br>

               <div class="accordion" id="accordionExample">

                 <div class="card card-accordion">

                    <div class="" id="headingOne">

                      <h5 class="mb-0">

                        <a class="btn btn-accordion " href="#" data-toggle="collapse" data-target="#categorias" aria-expanded="true" aria-controls="collapseOne">

                          <?=$lang["categorias"]?><i class="fa fa-sort-down float-right"></i>

                        </a>

                      </h5>

                    </div>



                    <div id="categorias" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">

                      <div class="card-body">







                       

                        <div class="custom-control custom-checkbox mb-2">

                          <input type="checkbox" class="custom-control-input" id="customControlInline">

                          <label class="custom-control-label" for="customControlInline"><?=$lang["visitas_guiadas"]?></label>

                        </div>

                        <div class="custom-control custom-checkbox mb-2">

                          <input type="checkbox" class="custom-control-input" id="customControlInline2">

                          <label class="custom-control-label" for="customControlInline2"><?=$lang["visitas_guiadas"]?></label>

                        </div>

                        <div class="custom-control custom-checkbox mb-2">

                          <input type="checkbox" class="custom-control-input" id="customControlInline3">

                          <label class="custom-control-label" for="customControlInline3"><?=$lang["visitas_guiadas"]?></label>

                        </div>

                      </div>

                    </div>

                  </div>

              </div>

              <br>

               <div class="accordion" id="accordionExample">

                 <div class="card card-accordion">

                    <div class="" id="headingOne">

                      <h5 class="mb-0">

                        <a class="btn btn-accordion " href="#" data-toggle="collapse" data-target="#accesibiliad" aria-expanded="true" aria-controls="collapseOne">

                          <?=$lang["accesibiliad"]?><i class="fa fa-sort-down float-right"></i>

                        </a>

                      </h5>

                    </div>



                    <div id="accesibiliad" class="collapse  show" aria-labelledby="headingOne" data-parent="#accordionExample">

                      <div class="card-body">

                        <div class="custom-control custom-checkbox mb-2">

                          <input type="checkbox" class="custom-control-input" id="customControlInlinea">

                          <label class="custom-control-label" for="customControlInlinea"><?=$lang["accesible"]?></label>

                        </div>

                      </div>

                    </div>

                  </div>

              </div>

              <br>

               <div class="accordion" id="accordionExample">

                 <div class="card card-accordion">

                    <div class="" id="headingOne">

                      <h5 class="mb-0">

                        <a class="btn btn-accordion show" href="#" data-toggle="collapse" data-target="#precio" aria-expanded="true" aria-controls="collapseOne">

                          <?=$lang["precio"]?><i class="fa fa-sort-down float-right"></i>

                        </a>

                      </h5>

                    </div>



                    <div id="precio" class="collapse show " aria-labelledby="headingOne" data-parent="#accordionExample">

                      <form class="padding">

                      <div class="form-group">

                        <label for="formControlRange">gratis</label>

                        <input type="range" class="form-control-range custom-range"  id="formControlRange">

                      </div>

                    </form>

                    </div>

                  </div>

              </div>

              <br>

               <div class="accordion" id="accordionExample">

                 <div class="card card-accordion">

                    <div class="" id="headingOne">

                      <h5 class="mb-0">

                        <a class="btn btn-accordion show" href="#" data-toggle="collapse" data-target="#Duración" aria-expanded="true" aria-controls="collapseOne">

                          <?=$lang["duracion"]?><i class="fa fa-sort-down float-right"></i>

                        </a>

                      </h5>

                    </div>



                    <div id="Duración" class="collapse show " aria-labelledby="headingOne" data-parent="#accordionExample">

                       <form class="padding">

                      <div class="form-group">

                        <input type="range" class="form-control-range custom-range"  id="formControlRange2">

                      </div>

                    </form>

                    </div>

                  </div>

              </div>

               <!--FIN ACORDEON PARA FILTRO DE BUSQUEDA EN PC-->

          </div>

        </div>

           </div>

         </div>



      </div>

 <!--FIN COLUMNA DERECHA DE BUSQUEDA-->

 

 

 

  <!--COLUMNA IZQUIERDA RESULTADOS DE BUSQUEDA-->

      <div class="col-lg-8 padding-lr-0">

      









        <!--BUCLE DE RESULTADOS -->





            <div class="container d-md-none py-2">

          <div class="row ">

          <div class="col-4 ">

            <a href="#" style="font-size: 10px;" class="btn btn-dark btn-filtar d-md-none " data-toggle="modal" data-target="#exampleModal"><i class="fa fa-sliders-h "></i><?=$lang["filtrar"]?></a>

          </div>

           <div class="col-8 my-auto">

            

            <p class="text-left text-filtrar"><?=$cantidad_servicios_categoria;?> <?=$lang["actividades_en"]?><?= $nombre_categoria; ?></p>

          </div>

        </div>

        </div>



 <p class="text-left d-md-block d-none " style="font-size: 30px;"><?= $cantidad_servicios_categoria;?> <?=$lang["actividades_en"]?><?= $nombre_categoria; ?></p>









<?php  for ($i=0; $i < count($servicios) ; $i++) { 

 



 

  $idServicio=$servicios[$i]["idServicio"];

      $fecha=date("Y-m-d");

        $salidas=getSalidasFechaLuegoIdServicio($fecha,$idServicio);



      if (true) { 

  $idMoneda=$salidas[0]['idMoneda'];

      $idServicioSalidas=$salidas[0]['idServicioSalidas'];

      $tarifas=getTarifas($idServicioSalidas);



   $tarifa=calculaTarifa($tarifas[0]['idServicioSalidasTarifas'],

1);

      $precioSugerido=($tarifa[0]["valorSym"]);





  





$nombre_servicio=$servicios[$i]["nombre_servicio"];

$descripcion_corta=$servicios[$i]["descripcion_corta"];

$opiniones_servicio=getOpinionesServicio($idServicio);

$estrellas_servicio=getEstrellasServicio($idServicio);

$cantidad_opiniones_servicio=count($opiniones_servicio);

$duracion_servicio=getDuracionServicio($idServicio);

$fotos_servicio=getFotosServicio($idServicio);

$fotos_servicio=$fotos_servicio[0]["ruta"];

      $textoMiniatura=getTextoMiniatura($servicios[$i]["idTextoMiniaturas"])[0]["texto"];

?>



<a href="servicio?id=<?= $idServicio?>">

              <div class="mb-4">

    <div class="card card-visitas">

        <div class="row no-gutters d-md-none" style="position: absolute;z-index: 999;">

                    <div class="col-6">

                         <div class="badge badge-primary badge-destacado"><?=$textoMiniatura;?></div>

                    </div>

        </div>

      <div class="card-body padding-body">

          <div class="row ">

        <div class="col-md-4 col-4">

            <img src="admin/classes/imgServicio/<?=$fotos_servicio;?>" class="w-100 img-fluid img-card-destinos">

          </div>

          <div class="col-md-8 col-8" style="padding-left:0px !important;">

            <div class="card-block ">

       <h4 class="text-left titulo-card-destinos semibold"><?=$nombre_servicio?></h4>

                    <h5 class="texto-opinion-desta"><strong><?=$estrellas_servicio;?>/10</strong> <small class="text-gris"><?=$cantidad_opiniones_servicio;?> opiniones</small></h5>

                    <p class="text-gris d-md-block d-none"><?=$descripcion_corta;?></p>

            </div>

             <ul class="lista-caracteristicas d-md-none">

                      <li><i class="fa fa-hourglass-half"></i> <?=$duracion_servicio["duracionMinima"];?>  - <?=$duracion_servicio["duracionMaxima"];?></li>

                      

                    </ul>

                    <h4 class="text-success text-cancelacion  float-left d-md-none semibold"><?=$lang["cancelacion_gratuita"]?></h4>

                    <p class="float-right d-md-none semibold"><?=$precioSugerido;?></p>

          </div>



        </div>

        <div class=" d-md-block mt-2 d-none">

                <div class="row no-gutters">

                  <div class="col-lg-4 col-12">

                    <ul class="lista-caracteristicas">

                      <li><i class="fa fa-hourglass-half"></i> <?=$duracion_servicio["duracionMinima"];?>  - <?=$duracion_servicio["duracionMaxima"];?> </li>

                      

                    </ul>

                  </div>

                  <div class="col-lg-4 col-12">

                    <h4 class="text-success text-cancelacion semibold"><?=$lang["cancelacion_gratuita"]?></h4>

                  </div>

                  <div class="col-lg-4 col-12">

                    <h4 class="float-right semibold"><?=$precioSugerido;?></h4>

                  </div>

                </div>

              </div>

      </div>

       <div class="destacado d-md-block d-none">

               <h5 class="text-uppercase text-white"><?=$textoMiniatura;?></h5>

             </div>

     </div>

    </div>

         </a>











<?php

}   }?>





        

   









     

         <!--BUCLE DE RESULTADOS -->

       

         <!--PAGINACION DE RESULTADOS -->

   

         

   

    <nav aria-label="Page navigation example">

            <ul class="pagination pagination-lg  justify-content-center">



   

    <li class="page-item flechas">

                <a class="page-link" href="categorias.php?id='.$id.'&pagina='.($pagina-1).'" aria-label="Previous">

                  <span aria-hidden="true">&laquo;</span>

                  <span class="sr-only"><?=$lang["anterior"]?>Anterior</span>

                </a>

              </li>



 <li ><a class="page-link" href="categorias.php?id='.$id.'&pagina='.$contadorr.'">0</a></li>





       <li class="page-item flechas">

                <a class="page-link" href="categorias.php?id='.$id.'&pagina='.($pagina+1).'" aria-label="Next">

                  <span aria-hidden="true">&raquo;</span>

                  <span class="sr-only"><?=$lang["Proxima"]?></span>

                </a>

              </li>

              

  </ul>

        </nav>





             <!--FIN PAGINACION DE RESULTADOS -->



            

           

            

       

      

        

        <br>





        <div class="card card-ultimas-o d-md-block">

<?php 

      for ($b=0; $b < count($opiniones_categoria) ; $b++) { 

        if ($b <= 2) { // para maximo 2 resultados

          # code...

       

  ?>

              <div class="card-body">

      <p class="text-primary"><?=$opiniones_categoria[$b]['opinion']?></p>

            <p>

            <i class="fa fa-star text-primary"></i>

            <i class="fa fa-star text-primary"></i>

            <i class="fa fa-star text-primary"></i>

            <i class="fa fa-star text-primary"></i>

            <i class="fa fa-star text-primary"></i>

             <?=$opiniones_categoria[$b]['nombre']?></p>

            <hr>

          

         

 </div>

      <?php

      }

 }

  



 ?>

         

        </div>



         <!--CARD GUIA MOVIL -->

        <div class="card card-ultimas-o d-md-none">

          <div class="card-body">

            <form action="guias.php" method="post">

              <input type="hidden" name="idCategoria" value="<?= $id;?>">



       <h4><i class="fa fa-map"></i><?=$lang["conoce_nuestra_guia_de"]?><?= $nombre_categoria; ?></h4>

            <a href="#" class="text-white">



              <img src="admin/img/categoria_servicio/<?= $fotos; ?>" class="img-fluid img-guia mx-auto d-block">

              <h4 class="text-guia2"><?= $nombre_categoria; ?></h4>

            </a>

             <button class="submit btn btn-primary"><?= $nombre_categoria; ?></button>

           </form>

          </div>

        </div>

         <!--FIN CARD GUIA MOVIL -->

         <div class="container_r clearfix">

           

         </div>

      </div>

        <!--FIN COLUMNA IZQUIERDA RESULTADOS DE BUSQUEDA-->

    </div>

  </div>

</section>

<!--SECCION ACTIVIDADES-->





<!-- MODAL HERRAMIENTAS MOVIL-->

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

  <div class="modal-dialog" role="document">

    <div class="modal-content">

      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">

          <span aria-hidden="true">&times;</span>

        </button>

      </div>

      <div class="modal-body">

        <div class="card card-seccion-right  ">

          <div class="card-body">

              <!--ACORDEON PARA FILTRO DE BUSQUEDA EN MOVIL-->



              <div name="buscadorOPT">

                <form class="form-buscar mb-5 " action="categorias.php" method="get">

            <label class="sr-only" for="s"><?=$lang["que_hacemos"]?></label>

          <div class="input-group">

            <input class="field form-control form-control-search" name="buscar" type="text" placeholder="¿Que hacemos?" value="">

            <span class="input-group-append">

              <button class="submit btn btn-primary" id="searchsubmit" name="submit" type="submit"><?=$lang["buscar"]?><i class="fa fa-arrow-right"></i></button>

            </span>

          </div>

</form>

        </div>





           <div class="accordion" id="Disponibilidad">

                 <div class="card card-accordion">

                    <div class="" id="headingOne">

                      <h5 class="mb-0">

                        <a class="btn btn-accordion " href="#" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">

                          <?=$lang["disponibilidad"]?><i class="fa fa-sort-down float-right"></i>

                        </a>

                      </h5>

                    </div>



                    <div id="collapseOne" class="collapse show " aria-labelledby="headingOne" data-parent="#Disponibilidad">

                      <div class="card-body">

                        <div class="btn-group" role="group" aria-label="Basic example">

                                <form action="categorias.php">

                            <input type="hidden" name="hoy"></input>

                          <button type="submit" class="<?=$btnHoy;?>"><?=$lang["hoy"]?></button>

                        </form>

                         <form action="categorias.php">

                          <input type="hidden" name="manana"></input>

                          <button type="submit" class="<?= $btnManana;?>"><?=$lang["manana"]?></button>

                            </form>

                          

                        </div>

                      </div>

                    </div>

                  </div>

              </div> 

              <br>

             <!--  <div class="accordion" id="accordionExample">

                 <div class="card card-accordion">

                    <div class="" id="headingOne">

                      <h5 class="mb-0">

                        <a class="btn btn-accordion " href="#" data-toggle="collapse" data-target="#categorias" aria-expanded="true" aria-controls="collapseOne">

                          Categorias <i class="fa fa-sort-down float-right"></i>

                        </a>

                      </h5>

                    </div>



                    <div id="categorias" class="collapse show " aria-labelledby="headingOne" data-parent="#accordionExample">

                      <div class="card-body">

 <?php 



//$cant=count(DevuelveCategorias());

//$devuelveCat=DevuelveCategorias();



                        for ($i=0; $i < $cant; $i++) {

                        $checked="";

                        $type=""; 

                        if($id==$devuelveCat[$i][0]){

                           $checked="checked";

                           $type="radio";

                          }

  /*                       echo '    

<a href="categorias.php?id='.DevuelveCategorias()[$i][0].'">

               <div class="custom-control custom-checkbox mb-2" >

                      

    <input type="'.$type.'" class="custom-control-input" id="" '.$checked.'>

   <label class="custom-control-label" for="">'.DevuelveCategorias()[$i][1].'</label>

                    </div> </a>   ';*/

                        }

                      ?>

                    



                    

                        

                      </div>

                    </div>

                  </div>

              </div>-->

              <br>

              <!-- <div class="accordion" id="accordionExample">

                 <div class="card card-accordion">

                    <div class="" id="headingOne">

                      <h5 class="mb-0">

                        <a class="btn btn-accordion " href="#" data-toggle="collapse" data-target="#accesibiliad" aria-expanded="true" aria-controls="collapseOne">

                          Accesibilidad <i class="fa fa-sort-down float-right"></i>

                        </a>

                      </h5>

                    </div>



                    <div id="accesibiliad" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">

                      <div class="card-body">

                        <div class="custom-control custom-checkbox mb-2">

                          <input type="checkbox" class="custom-control-input" id="customControlInlinea">

                          <label class="custom-control-label" for="customControlInlinea">Accesible</label>

                        </div>

                      </div>

                    </div>

                  </div>

              </div>-->

              <br>

               <div class="accordion" id="accordionExample">

                 <div class="card card-accordion">

                    <div class="" id="headingOne">

                      <h5 class="mb-0">

                        <a class="btn btn-accordion " href="#" data-toggle="collapse" data-target="#precio" aria-expanded="true" aria-controls="collapseOne">

                          <?=$lang["precio"]?><i class="fa fa-sort-down float-right"></i>

                        </a>

                      </h5>

                    </div>



                    <div id="precio" class="collapse show " aria-labelledby="headingOne" data-parent="#accordionExample">

                      <form class="padding">

                      <div class="btn-group" role="group" aria-label="Basic example">

                                <form action="categorias.php">

                            <input type="hidden" name="priceMin"></input>

                          <button type="submit" class="<?=$btnMenorPrecio;?>"><?=$lang["menor_precio"]?></button>

                        </form>

                                   <form action="categorias.php">

                            <input type="hidden" name="priceMax"></input>

                          <button type="submit" class="<?=$btnMayorPrecio;?>"><?=$lang["mayor_precio"]?></button>

                        </form>

                      </div>

                    </form>

                    </div>

                  </div>

              </div>

              <br>

              <!-- <div class="accordion" id="accordionExample">

                 <div class="card card-accordion">

                    <div class="" id="headingOne">

                      <h5 class="mb-0">

                        <a class="btn btn-accordion " href="#" data-toggle="collapse" data-target="#Duración" aria-expanded="true" aria-controls="collapseOne">

                          Duración <i class="fa fa-sort-down float-right"></i>

                        </a>

                      </h5>

                    </div>



                    <div id="Duración" class="collapse show " aria-labelledby="headingOne" data-parent="#accordionExample">

                       <form class="padding">

                      <div class="form-group">

                        <input type="range" class="form-control-range custom-range"  id="formControlRange2">

                      </div>

                    </form>

                    </div>

                  </div>

              </div>-->

                  <!--ACORDEON PARA FILTRO DE BUSQUEDA EN MOVIL-->

          </div>

        </div>

      </div>

   

    </div>

  </div>

</div>

<!-- FIN MODAL HERRAMIENTAS MOVIL-->







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

        		<input class="field form-control"  name="buscar" type="text" placeholder="¿Dónde vamos?" value="">

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

                            <h3 class="text-center text-primary"><?=$lang["top_actividades"]?></h3>

                          </div>

                        </div>

                        <div class="row  mb-4">

                            

                            <!--EL BUCLE DE LOS RESULTADOS DEBE IR ACA-->	  

                            <div class="col-md-3 col-6 mb-3">

                            <h4 class=" mb-0"><a href="#" class="text-destinos">Rio de Janeiro</a></h4>

                            <small>Florianopolis</small>

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



 <!-- Footer -->

 <?php include "footer.php"; 

?>

 <!--- Fin del footer --->



 

  <!-- BOTON SUBIR-->

  <div class="scroll-to-top  position-fixed ">

    <a class="js-scroll-trigger d-block text-center text-white rounded" href="#page-top">

      <i class="fa fa-chevron-up"></i>

    </a>

  </div>

  <!-- FIN BOTON SUBIR-->





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

  

<!-- FIN SCRIPTS NECESARIOS-->

</body>



</html>

