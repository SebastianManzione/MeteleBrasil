  

 <!--SECCION HEADER-->

<section id="header-visitas" class="menu-h" style="background-image: url('admin/classes/imgServicio/<?=$fotoPortada[0]["ruta"];?>'); " >

  <div class="container d-md-block d-none">

    <div class="row">

      <div class="col-lg-12">

          

          <!--BUCLE DE LOS RESULTADOS AQUI--> 
<?php if($servicio["idCategoria_servicio"] == 4){ //si es paquete
  $origen=getDestino($servicio["idDestino"]);
     $paisOrigen=getPais($origen[0]["idPais"]); 
?>
  <div class="badge badge-primary badge-ciudad"><?=$origen[0]["nombre"]?>, <?=$origen[0]["estado"]?>, <?=$paisOrigen[0]["nombre"]?> | <?=$destino[0]["nombre"]?>, <?=$pais[0]["nombre"]?></div>
<?php
} else{
  ?>
  <div class="badge badge-primary badge-ciudad"><?=$destino[0]["nombre"]?>, <?=$destino[0]["estado"]?>, <?=$pais[0]["nombre"]?></div>
  <?php
} ?>
      

         <!--FIN BUCLE DE LOS RESULTADOS AQUI-->

         

          <!--TITULO-->

        <h1 class="text-white texto-shadow py-2 bold" style=" text-shadow: -1px 0px 6px #000000;"><?=$servicio["nombre_servicio"];?></h1>

         <!--TITULO-->

        

      

      </div>

    </div>

  </div>

 <!--header-->

  <!-- CONTENEDOR DE OPINIONES IDIOMA Y DURACION-->

 <div class="container-fluid d-md-block d-none">

    <div class="row">

      <div class="col-lg-8">

        <div class="img-azul"></div>

      </div>

       <div class="col-lg-8">

      

      </div>


    </div>

 </div>



 <?php 

 $opinionAleatoria=GetOpinionAleatoria($idServicio);

?>



  <div class="container z-index-b d-md-block d-none ">

    <div class="row">

      <div class="col-lg-4 col-md-6"> 

        <div class="resumen-visitas text-white">



          <h2 class="semibold"><?=$estrellasServicio;?>/10 <small><?=count($OpinionesServicio);?><?=$lang["opiniones"]?></small></h2>

          <p class="mb-0"> <?= substr($opinionAleatoria[0]['opinion'], 0, 150);?>...</p>

          <p>





            <?php for ($i=0; $i < ($opinionAleatoria[0]['estrellas']/2); $i++) { ?>

                      <i class="fa fa-star"></i> 

    <?php     } ?>

          

            



</p>

        </div>

      </div>

    </div>

  </div>

  <br>

  <div class="container-fluid d-md-block d-none">

    <div class="row">

      <div class="col-lg-12">

        <div class="img-gris"></div>

      </div>

    </div>

</div>

  <!-- CONTENEDOR DE FRANJA TRANSPARENTE-->

  <div class="container  d-md-block d-none ">

     <div class="row">

      <div class="col-lg-12">

        <div class="div-fondo-visita-">

          <ul class="lista-visitas text-white">
 <ul id="LiIdiomasNav"><i class="fa fa-comment"></i><small id="pIdiomasNav">  </small> 
           
<?php if($servicio["idCategoria_servicio"] != 5){ //si no es transporte sacamos el horario en el header?>
  
            <i class="fa fa-hourglass-half"></i>
             <?= $duracion["duracionMinima"];?> - <?= $duracion["duracionMaxima"];?>


<?php } ?>
   </ul>
</ul>

        </div>

      </div>

    </div>

  </div>

    <!-- FIN CONTENEDOR DE FRANJA TRANSPARENTE-->

   <!-- FIN CONTENEDOR DE OPINIONES IDIOMA Y DURACION-->



    <!-- CONTENEDOR DE BOTONES EN MOVIL-->

  <div class="container-fluid  d-md-none">

     <div class="row">

      <div class="col-6">
<?php if($servicio["idCategoria_servicio"] == 4){ //si es paquete
  $origen=getDestino($servicio["idDestino"]);
     $paisOrigen=getPais($origen[0]["idPais"]); 
?>
  <a  class="btn btn-movil-header  text-primary "><?=$origen[0]["nombre"]?>, <?=$origen[0]["estado"]?>, <?=$paisOrigen[0]["nombre"]?> | <?=$destino[0]["nombre"]?>, <?=$pais[0]["nombre"]?></a>
<?php
} else{
  ?>
  <a  class="btn btn-movil-header  text-primary "><?=$destino[0]["nombre"]?>, <?=$destino[0]["estado"]?>, <?=$pais[0]["nombre"]?></a>
  <?php
} ?>

       

      </div>

      <div class="col-6">

      

                 <div class="collapse" id="compartir">

                  <div class="card card-body">

    <a  data-toggle="collapse" class="btn-reservar" href="#compartir" role="button" aria-expanded="false" aria-controls="collapseExample" ><i class="text-dark fa fa-share-alt"></i></a>

                      <a class="btn btn-outline-light btn-social facebook mx-1 mb-2" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fwww.metelebrasil.com%2Fservicio%3Fid%3D<?=$idServicio?>&amp;src=sdkpreparse">

                    <i class="fab fa-fw fa-facebook-f"></i>

                  </a>

                

                  </div>

                </div>

      </div>

    </div>

  </div>

    <!-- FIN CONTENEDOR DE BOTONES EN MOVIL-->

  

</section>

 <!--FIN SECCION HEADER-->



 





<!--MENU FIJO-->



 <nav class="navbar navbar-expand-lg bg-white menu-fixed " id="mainNav" style="z-index: 2;">

    <div class="container">

         <div class="collapse navbar-collapse" id="navbarResponsive">

        <ul class="navbar-nav mr-auto">

          <li class="nav-item nav-visita px-2 ">

            <a class="nav-link  text-gris  px-0   js-scroll-trigger" href="#desc"><?=$lang["descripcion_"]?></a>

          </li>

          <li class="nav-item nav-visita px-2 ">

            <a class="nav-link  text-gris  px-0   js-scroll-trigger" href="#precio"><?=$lang["precio"]?></a>

          </li>

          <li class="nav-item nav-visita px-2 ">

            <a class="nav-link  text-gris  px-0   js-scroll-trigger" href="#det"><?=$lang["detalles_"]?></a>

          </li>

          <li class="nav-item nav-visita px-2 ">

            <a class="nav-link  text-gris  px-0   js-scroll-trigger" href="#documentacionViajero"><?=$lang["documentacion_para_el_viajero"]?></a>

          </li>

          <li class="nav-item nav-visita px-2 ">

            <a class="nav-link  text-gris  px-0   js-scroll-trigger" href="#cancelaciones"><?=$lang["cancelaciones_"]?></a>

          </li>

          <li class="nav-item nav-visita px-2 mx-4">

            <a class="nav-link  text-gris  px-0   js-scroll-trigger" href="#opiniones"><?=$lang["opiniones"]?></a>

          </li>



        </ul>

                 <a class="btn btn-primary btn-nav-visita btn-lg" id="btn-reservar-nav" onclick="enviar()"><?=$lang["reservar"]?></a>  
                 <div>

                 <a  data-toggle="collapse" id="btn-share" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample" > <?=$lang["compartir"]?> <i class=" fa fa-share-alt text-gris"> </i> </a></div>

        <form class="form-inline ">

         <div class="form-group row">

             <div class="col-lg-6">

 
             <p id="precio-nav" class="h2 text-primary bold mb-0" style="display: none;" ><?=$lang["precio"]?></p>

             </div>

             <div class="col-lg-6 text-center">

               

                 <a  data-toggle="collapse" style="display: none;" id="btn-share-nav" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample" ><i class=" fa fa-share-alt text-gris"></i></a>

                 <div class="collapse" id="collapseExample">

                  <div class="card card-body">

                     <a class="btn btn-outline-light btn-social facebook mx-1 mb-2" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fwww.metelebrasil.com%2Fservicio%3Fid%3D<?=$idServicio?>&amp;src=sdkpreparse">

                    <i class="fab fa-fw fa-facebook-f"></i>

                  </a>

                 

                  </div>

                </div>

             </div>

         </div>

        </form>

      </div>

    </div>

  </nav>



<!--FIN MENU FIJO-->