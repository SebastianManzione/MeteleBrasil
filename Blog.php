<?php



 include('includes/navbar.php'); 



 include('admin/classes/blog.php'); 
 include('admin/classes/texto_miniaturas_blog.php');
 include('admin/classes/destinos.php'); 
 include('admin/classes/paises.php'); 
    

 $idDestinoSeleccionado=0;
if ($_SERVER["REQUEST_METHOD"] == "GET" ) {

  if (isset($_GET["idDestino"])) {
    $idDestinoSeleccionado=$_GET["idDestino"];
    $articulos=getArticulosBlogIdDestino($idDestinoSeleccionado);
  }
  else{
    $articulos=getArticulosBlog();
  }
}
else{
  $articulos=getArticulosBlog();
}
$destinos=getDestinos();

$nombre_categoria="Blog";
$cantidad_de_post=count($articulos);        
$busqueda="";
?>

 <!--SECCION HEADER-->

<section id="header-destinos"  style="background-image: url('admin/img/categoria_servicio/sinCategoria.jpg ');" >

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

          <h2 class="title-numeros mb-0 bold"><?= $cantidad_de_post; ?></h2>

          <p class="text-d-number">Posts</p>

      </div>

      <div class="col-lg-3 col-md-3">

          <h2 class="title-numeros mb-0 bold">22</h2>

          <p class="text-d-number">viajeros ya lo han disfrutado</p>

      </div>

      <div class="col-lg-3 col-md-3">

          <h2 class="title-numeros mb-0 bold">22</h2>

          <p class="text-d-number">opiniones reales</p>

      </div>

      <div class="col-lg-3 col-md-3">

          <h2 class="title-numeros mb-0 bold">9,2</h2>

          <p class="text-d-number">así nos puntúan</p>

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


              <!--ACORDEON PARA FILTRO DE BUSQUEDA EN PC-->

           
               <div class="accordion" id="accordionExample">

                 <div class="card card-accordion">

                    <div class="" id="headingOne">

                      <h5 class="mb-0">

                        <a class="btn btn-accordion " href="#" data-toggle="collapse" data-target="#categorias" aria-expanded="true" aria-controls="collapseOne">

                          Destinos <i class="fa fa-sort-down float-right"></i>

                        </a>

                      </h5>

                    </div>



                    <div id="categorias" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">

                      <div class="card-body">

         
    <?php for ($i=0; $i < count($destinos); $i++) { 
             $idDestino=$destinos[$i]["idDestino"];
             $checked="";
             $type="";
             if ($idDestino==$idDestinoSeleccionado) {
               $checked="checked";
               $type="radio";
             }
             $nombre_destino=$destinos[$i]["nombre"];
          ?>        
<a href="Blog?idDestino=<?=$idDestino;?>">

                         <div class="custom-control custom-checkbox mb-2">

                          <input type="<?=$type?>" class="custom-control-input" id="" <?=$checked;?>>

                          <label class="custom-control-label" for=""><?=$nombre_destino;?></label>

                        </div></a>
                       

                <?php  } ?>    

                      

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

               <div class="accordion" id="accordionExample">

                 <div class="card card-accordion">

                    <div class="" id="headingOne">

                      <h5 class="mb-0">

                        <a class="btn btn-accordion show" href="#" data-toggle="collapse" data-target="#precio" aria-expanded="true" aria-controls="collapseOne">

                          Ordenar <i class="fa fa-sort-down float-right"></i>

                        </a>

                      </h5>

                    </div>



                    <div id="precio" class="collapse show " aria-labelledby="headingOne" data-parent="#accordionExample">



                

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



        <!--FIN CARD ULTIMAS OPINIONES-->

        <br>

        <!--CARD GUIA PC-->


        <!--FIN CARD GUIA PC-->

         <div id="sidebar" class="mb-5" style="display: none;">

           <div class="sidebar__inner" style="bottom:50px !important">

              <!--CARD PRINCIPAL DE BUSQUEDA-->

        <div class="card card-seccion-right  ">

          <div class="card-body">

              

              <!--ACORDEON PARA FILTRO DE BUSQUEDA EN PC-->


               <div class="accordion" id="accordionExample">

                 <div class="card card-accordion">

                    <div class="" id="headingOne">

                      <h5 class="mb-0">

                        <a class="btn btn-accordion " href="#" data-toggle="collapse" data-target="#categorias" aria-expanded="true" aria-controls="collapseOne">

                          Categorias <i class="fa fa-sort-down float-right"></i>

                        </a>

                      </h5>

                    </div>



                    <div id="categorias" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">

                      <div class="card-body">







                       

                        <div class="custom-control custom-checkbox mb-2">

                          <input type="checkbox" class="custom-control-input" id="customControlInline">

                          <label class="custom-control-label" for="customControlInline">Visitas guiadas</label>

                        </div>

                        <div class="custom-control custom-checkbox mb-2">

                          <input type="checkbox" class="custom-control-input" id="customControlInline2">

                          <label class="custom-control-label" for="customControlInline2">Visitas guiadas</label>

                        </div>

                        <div class="custom-control custom-checkbox mb-2">

                          <input type="checkbox" class="custom-control-input" id="customControlInline3">

                          <label class="custom-control-label" for="customControlInline3">Visitas guiadas</label>

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

              </div>

              <br>

               <div class="accordion" id="accordionExample">

                 <div class="card card-accordion">

                    <div class="" id="headingOne">

                      <h5 class="mb-0">

                        <a class="btn btn-accordion show" href="#" data-toggle="collapse" data-target="#precio" aria-expanded="true" aria-controls="collapseOne">

                          Precio <i class="fa fa-sort-down float-right"></i>

                        </a>

                      </h5>

                    </div>



                    <div id="precio" class="collapse show " aria-labelledby="headingOne" data-parent="#accordionExample">

                      <form class="padding">

                      <div class="form-group">

                        <label for="formControlRange">gratis - 253 US$</label>

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

            <a href="#" style="font-size: 10px;" class="btn btn-dark btn-filtar d-md-none " data-toggle="modal" data-target="#exampleModal"><i class="fa fa-sliders-h "></i> Filtrar</a>

          </div>

           <div class="col-8 my-auto">

            

            <p class="text-left text-filtrar" ><?=$cantidad_de_post;?></p>

          </div>

        </div>

        </div>



 <p class="text-left d-md-block d-none " style="font-size: 30px;"><?=$cantidad_de_post;?> post</p>






<?php for ($i=0; $i < count($articulos); $i++) { 
$titulo=$articulos[$i]["titulo"];
$descripcionCorta=$articulos[$i]["descripcionCorta"];

$idPost=$articulos[$i]["idPost"];
$idTextoMiniaturas=$articulos[$i]["idTextoMiniaturasBlog"];
$textoMiniatura=getTextoMiniaturaBlog($idTextoMiniaturas)[0]["texto"];
$idDestino=$articulos[$i]["idDestino"];
$destino=getDestino($idDestino);
$pais=getPais($destino[0]["idPais"]);
$img=getImgArticulo($idPost);
$foto='default.jpg';
if (count($img)>0) {
  $foto=$img[0]['ruta'];
}

 ?>


<a href="articuloBlog?post=<?=$idPost?>">

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

            <img src="admin/classes/imgBlog/<?=$foto;?>" class="w-100 img-fluid img-card-destinos">

          </div>

          <div class="col-md-8 col-8" style="padding-left:0px !important;">

            <div class="card-block ">

       <h4 class="text-left titulo-card-destinos semibold"><?=$titulo;?></h4>

                   
                    <p class="text-gris d-md-block "><?=$descripcionCorta;?></p>

            </div>

             <ul class="lista-caracteristicas d-md-none">

                      <li> </li>

                  

                    </ul>

                   

                    <p class="float-right d-md-none semibold"></p>

          </div>



        </div>

        <div class=" d-md-block mt-2 d-none">

                <div class="row no-gutters">

                  <div class="col-lg-4 col-12">

                 
                  </div>

                  <div class="col-lg-4 col-12">


                  </div>

                  <div class="col-lg-4 col-12">

                   

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

<?php } ?>













        

   









     

         <!--BUCLE DE RESULTADOS -->

       

         <!--PAGINACION DE RESULTADOS -->

   

         

   

    <nav aria-label="Page navigation example">

            <ul class="pagination pagination-lg  justify-content-center">



   

    <li class="page-item flechas">

                <a class="page-link" href="Blog.php?id='.$id.'&pagina='.($pagina-1).'" aria-label="Previous">

                  <span aria-hidden="true">&laquo;</span>

                  <span class="sr-only">Previous</span>

                </a>

              </li>



 <li ><a class="page-link" href="Blog.php?id='.$id.'&pagina='.$contadorr.'">0</a></li>





       <li class="page-item flechas">

                <a class="page-link" href="Blog.php?id='.$id.'&pagina='.($pagina+1).'" aria-label="Next">

                  <span aria-hidden="true">&raquo;</span>

                  <span class="sr-only">Next</span>

                </a>

              </li>

              

  </ul>

        </nav>





             <!--FIN PAGINACION DE RESULTADOS -->



            

           

            

       

      

        

        <br>

         <!--CARD GUIA MOVIL -->

        <div class="card card-ultimas-o d-md-none">

          <div class="card-body">

            <form action="guias.php" method="post">

              <input type="hidden" name="idCategoria" value="<?= $id;?>">

  <?php 
   ?>

       <h4><i class="fa fa-map"></i> Conoce nuestra guia de <?= $nombre_categoria; ?></h4>

            <a href="#" class="text-white">



              <img src="admin/img/categoria_servicio/<?= $fotos; ?>" class="img-fluid img-guia mx-auto d-block">

              <h4 class="text-guia2"><?= $nombre_categoria; ?></h4>

            </a>

             <button class="submit btn btn-primary"><?= $nombre_categoria; ?></button>

          </div>

        </div></form>

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

            <label class="sr-only" for="s">¿Que hacemos?</label>

          <div class="input-group">

            <input class="field form-control form-control-search" name="buscar" type="text" placeholder="¿Que hacemos?" value="">

            <span class="input-group-append">

              <button class="submit btn btn-primary" id="searchsubmit" name="submit" type="submit">Buscar <i class="fa fa-arrow-right"></i></button>

            </span>

          </div>

</form>

        </div>



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

                          Precio <i class="fa fa-sort-down float-right"></i>

                        </a>

                      </h5>

                    </div>



                    <div id="precio" class="collapse show " aria-labelledby="headingOne" data-parent="#accordionExample">

                      <form class="padding">

                      <div class="btn-group" role="group" aria-label="Basic example">

                                <form action="categorias.php">

                            <input type="hidden" name="priceMin"></input>

                          <button type="submit" class="<?=$btnMenorPrecio;?>">Menor precio</button>

                        </form>

                                   <form action="categorias.php">

                            <input type="hidden" name="priceMax"></input>

                          <button type="submit" class="<?=$btnMayorPrecio;?>">Mayor precio</button>

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

            <label class="sr-only" for="s">¿Dónde vamos?</label>

        	<div class="input-group ">

        		<input class="field form-control"  name="buscar" type="text" placeholder="¿Dónde vamos?" value="">

        		<span class="input-group-append">

        			<button class="submit btn btn-primary" id="searchsubmit" name="submit" type="submit">Buscar <i class="fa fa-arrow-right"></i></button>

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

                            <h3 class="text-center text-primary">Top Destinos</h3>

                          </div>

                        </div>

                        <div class="row  mb-4">

                            

                            <!--EL BUCLE DE LOS RESULTADOS DEBE IR ACA-->	  

                            <div class="col-md-3 col-6 mb-3">

                            <h4 class=" mb-0"><a href="#" class="text-destinos">Nueva York</a></h4>

                            <small>Estados Unidos</small>

                            </div>

                            <!--FIN BUCLE DE LOS RESULTADOS DEBE IR ACA-->	 

                  

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

