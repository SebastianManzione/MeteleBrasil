<?php





include("includes/navbar.php");





require("admin/classes/categoria.php");

require("admin/classes/opiniones_categoria.php");

require("admin/classes/servicio_opiniones.php");

require("admin/classes/texto_miniaturas.php"); 


require("admin/classes/destinos.php"); 

require("admin/classes/paises.php"); 



require("admin/classes/texto_viajeros.php");

     if (isset($_GET["id"]) && is_numeric($_GET["id"])) {

                    $idServicio=$_GET['id'];

                    $servicio=getServicio($idServicio)[0];

                  $idCategoria_servicio=$servicio['idCategoria_servicio'];

                  $categoria_servicio=getCategoria($idCategoria_servicio);

                    $OpinionesServicio=GetOpinionesServicio($idServicio);

            

                      $CantOpinionesServicio=count($OpinionesServicio);

                    $estrellasServicio=GetEstrellasServicio($idServicio);

                    $textoMiniatura=getTextoMiniatura($servicio["idTextoMiniaturas"])[0]["texto"];

                    $fotos=getFotosServicio($idServicio);

                  $duracion=getDuracionServicio($idServicio);

                 $salidas=getSalidasServicio($idServicio);

                 $eventArray=GetEventosArray($idServicio);

                 $CantOpinionesServicio=count($OpinionesServicio);

$destino=getDestino($servicio["idDestino"]);
     $pais=getPais($destino[0]["idPais"]);           

     }



     ?>



     <?php

include('servicioHead.php');

?>



 



<?php include("servicioMovil.php"); ?>









<section class="py-5 d-md-block d-none">

  <div class="container container_r clearfix">

      <div class="row">

           <!--COL INFORMACION IZQUIERDA-->

          <div class="col-lg-8">

             <div id="content">

                 

        

        <!--CONTENEDOR DESCRIPCION-->

        <div class="descripcion" id="descripcion">



          <!--TEXTO DESTACADO-->

          <p><?=$servicio["descripcion_corta"];?></p>

          <!--FIN TEXTO DESTACADO-->



                   <!--SLIDER-->



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

                  <span class="sr-only"><?=$lang["anterior"]?></span>

                </a>

                <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">

                  <span class="carousel-control-next-icon" aria-hidden="true"></span>

                  <span class="sr-only"><?=$lang["Proxima"]?></span>

                </a>

           </div>

        <!--FIN SLIDER-->

        <!--TEXTO VISITA-->





        <h2 class="py-4 text-primary" name="desc"><?=$lang["que_se_visita"]?></h2>



   



       



         <?=$servicio["descripcion_servicio"];?>

          

     



 







        <!--FIN TEXTO VISITA-->



        <!--TEXTO IMPORTANTE-->



         <h2 class="py-4 text-primary"> <?=$lang["importante"]?></h2>



       <?=$servicio["observaciones"];?>

        <!--FIN TEXTO IMPORTANTE-->



        <!--TEXTO RECOGIDA--



        <h2 class="py-4 text-primary">Recogida en el hotel</h2>



        <p>Opcionalmente, podes reservar la recogida en por el hotel. La recogida es una hora antes de la hora de inicio del tour e incluye todos los alojamientos en un radio de 8 kilómetros desde el punto de encuentro.</p>



       

  

        --FIN TEXTO RECOGIDA-->  



        </div>

        <!--FIN CONTENEDOR DESCRIPCION-->



         <!-- CONTENEDOR PRECIO-->

        <div id="precio">

          <!--TEXTO PRECIO-->



         <h2 class="py-4 text-primary"> <?=$lang["precio"]?></h2>



        <div class="container">

           <div class="row" id="rowCirculosPrecios">

             <div class="col-lg-3 col-12 my-auto">

               <p class="popular text-center mb-0 "><i class="fa fa-star"></i> <?=$lang["mas_populas"]?></p>

               <p><strong></strong></p>

             </div>

          

        

                  

       

           </div>

           





         </div>



        <!--FIN CONTENEDOR PRECIO-->

        </div>





        <!-- CONTENEDOR DETALLES-->



        <div id="detalles" name="det">

          <!--TEXTO DETALLE-->



        <h2 class="py-4 text-primary"> <?=$lang["detalles_"]?></h2>



        <h5 class="semibold"><i class="fa fa-hourglass-half"> </i> <?=$lang["duracion_"]?></h5>

        <p class="mx-4" id="txtDuracion">  </p>



        <h5 class="semibold"><i class="fa fa-language"></i> <?=$lang["idioma_"]?></h5>



<p class="mx-4" id="pIdiomas"> </p>

 



        <h5 class="semibold"><i class="fa fa-exclamation-triangle"> </i><?=$lang["incluido_"]?></h5>

        <ul class="" id="ulIncluidos">









        </ul>




<div id="divNoIncluidosCuerpo">
        <h5 class="semibold"><i class="fa fa-exclamation-triangle"></i> <?=$lang["no_incluido"]?></h5>

        <ul class="" id="ulNoIncluidos">









        </ul>


</div>
        <h5 class="semibold" name="documentacionViajero"><i class="fas fa-passport"></i> <?=$lang["documentacion_para_el_viajero"]?></h5>

        <ul class="" > <?= $servicio['documentacionViajero']; ?>

        </ul>

        <h5 class="semibold"><i class="fa fa-calendar-alt"> </i><?=$lang["cuando_reservar"]?></h5>

        <p class="mx-4"> <?=$lang["reserva_cuanto_antes_para"]?></p>

        <p  class="mx-4"> <?=$lang["se_permiten_reservas_hasta_las_23"]?></p>



        <h5 class="semibold"> <i class="fa fa-file"></i><?=$lang["justificante"]?></h5>

        <p class="mx-4"> <?=$lang["te_enviaremos_un_email"]?></p>



        <h5 class="semibold"> <i class="fa fa-question-circle mb-4"></i><?=$lang["preguntas_frecuentes"]?></h5>



        <!--PREGUNTAS FRECUENTES-->



        <div id="accordion" class="mb-4">

          <div class="card card-faq">

            <div class="card-header" id="headingOne">

              <h5 class="mb-0">

                <a  class=" btn-faq" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">

                 <strong> <?=$lang["viajeros"]?></strong>

                 <?=$lang["es_posible_organizar"]?>

                </a>

              </h5>

            </div>

            <div id="collapseOne" class="collapse " aria-labelledby="headingOne" data-parent="#accordion">

              <div class="card-body">

                <p class="mx-4"> <strong>Metelebrasil</strong><?=$lang["si_la_visita_puede_ser"]?></p>

              </div>

            </div>

          </div>

       </div>

        <!--FIN PREGUNTAS FRECUENTES-->



        <h5><i class="fa fa-wheelchair"></i> <?=$lang["accesibilidad_"]?></h5>

        <p class="mx-4"> <?=$lang["si_nuestras_actividades"]?></p>


 <!-- CONTENEDOR CANCELACION-->

          <div> 

            <h2 class="py-4 text-primary"><?=$lang["cancelaciones_"]?></h2>

<div id="divCancelaciones" name="cancelaciones"></div>

                          

          </div>

           <!--FIN CONTENEDOR CANCELACION-->


        <!--FIN TEXTO DETALLE-->

        </div>





        <!--FIN CONTENEDOR DETALLES-->



     

             </div>

          </div>








           <!--FIN COL INFORMACION-->

          <div class="col-lg-4">

    

               <div class="div-precios text-right d-md-block d-none">

                     <small id="precioTotalSinDescuento"></small>

                    <h2 class="text-primary" style="font-size:60px;"><span style="font-size:60px;"  id="precioTotal0"></span> </h2>

                    <p> <?=$lang["sin_sobreprecios"]?></p>

                    <p class="text-success" id="textoCancelacionGratuita" style="display: none;"><b> <?=$lang["cancelacion_gratuita_"]?></b></p>


                 </div>

              

              <!--BARRA DESPLAZADA-->

             <div id="sidebar">

           <div class="sidebar__inner" style="bottom:50px !important">

               <div id="calendario-fijo">

        

          <!--CALENDARIO PHP-->





<script>

	var codCupon;

  const empty = {};

function cupon(texto){



	$.post('admin/ctrl/ctrlCupon.php', {

    data:{    'cupon' : JSON.stringify(texto) }

  }, function(response) {



   

  

  	if (response.length>10) { 

          responser=JSON.parse(response);

  		codCupon=texto;

      $("#cuponOk").removeClass("text-danger");

      $("#cuponOkMovil").removeClass("text-danger");



  		$("#cuponOk").text("Cupón aceptado, Tu anfitrion es "+responser['anfitrion']+" y brinda un descuento del "+responser['descuentoPorcentual']+"%");

		  $("#cuponOk").addClass("text-success");

  		$("#cuponOkMovil").text("Cupón aceptado,Tu anfitrion es "+responser['anfitrion']+" y brinda un descuento del "+responser['descuentoPorcentual']+"%");

      $("#cuponOkMovil").addClass("text-success");



  	}

  	else{

	   $("#cuponOk").text("Cupón no válido.");

		$("#cuponOk").removeClass("text-success");



 $("#cuponOkMovil").text("Cupón no válido.");

    $("#cuponOkMovil").removeClass("text-success");



    $("#cuponOkMovil").addClass("text-danger");

     $("#cuponOk").addClass("text-danger");



   



  	}









   });



}







</script>





          <div id="sidebar-calendar" class="d-md-block d-none">



                    <div id="calendar" class="calendario-visitas cal1"></div>

             



          <!--CALENDARIO PHP-->



            <hr class="hr-divider d-md-block d-none"> 

            <div id="sidebar-calendar" class="text-center d-md-block d-none">

                

            <!--<div class="dropdown">

              <a class="btn btn-secondary btn-block btn-calendar dropdown-toggle"  role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                Dropdown link

              </a>

            

              <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">

                <a class="dropdown-item" >Action</a>

                <a class="dropdown-item" >Another action</a>

                <a class="dropdown-item" >Something else here</a>

              </div>

            </div>-->

            

             <!--ACORDEON HORA-->

             <div class="accordion mb-2" id="Seleccionar_hora">

                 <div class="card card-accordion">

                    <div class="" id="headingOne">

                      <h5 class="mb-0">

                        <a class="btn btn-accordion btn-calendar text-white "  data-toggle="collapse" data-target="#divhora" aria-expanded="true" aria-controls="collapseOne">

                         <i class="fa fa-clock"></i><?=$lang["hora"]?><i class="fa fa-sort-down float-right"></i>

                        </a>

                      </h5>

                    </div>



                    <div id="divhora"  aria-labelledby="headingOne" data-parent="#divhora">

                         

                            <!--Aca el js trae los horarios al clickear en el calendario-->

                    </div>

                  </div>

              </div>

              <!--FIN ACORDEON HORA-->



   <div id="divLugares" >

                         

                            <!--Aca el js trae los horarios al clickear en el calendario-->

                    </div>

            

            





      <!--ACORDEON HORA-->

             <div class="accordion mb-2" id="Seleccionar_descuento">

                 <div class="card card-accordion">

                    <div class="" id="headingOne">   

                      <h5 class="mb-0">

                     <a class="btn btn-accordion btn-calendar text-white" data-toggle="collapse" data-target="#seleccionar_descuentoDiv" aria-expanded="false" aria-controls="collapseOne">

                         <i class="fa fa-clock"></i><?=$lang["cupon_de_descuento"]?><i class="fa fa-sort-down float-right"></i>

                     </a>

                      </h5>   

                    </div>



                    <div id="seleccionar_descuentoDiv" class="collapse" aria-labelledby="headingOne" data-parent="#seleccionar_descuentoDiv">

                      <div class="form-group">

                        <label for="">

                          <?=$lang["ingrese_un_cupon"]?>

                        </label>

                        <input type="text" onkeyup="cupon(this.value)" name="" id="txtCuponDescuento" class="form-control" value="">

                      </div>

                      <div id="cuponOk"></div>

                            <!--Aca el js trae los horarios al clickear en el calendario-->

                    </div>

                  </div>



              </div>

             

              <!--FIN ACORDEON HORA-->







             <!--ACORDEON PERSONA-->

             <div class="accordion mb-2" id="Seleccionar_personas_div">

                 <div class="card card-accordion">

                    <div class="" id="headingOne">

                      <h5 class="mb-0">

                        <a class="btn btn-accordion btn-calendar text-white "  data-toggle="collapse" data-target="#seleccionar_personas" aria-expanded="false" aria-controls="collapseOne">

                         <i class="fa fa-male"></i><?=$lang["personas"]?><i class="fa fa-sort-down float-right"></i>

                        </a>

                      </h5>

                    </div>



                    <div id="seleccionar_personas" class="collapse " aria-labelledby="headingOne" data-parent="#seleccionar_personas" style="padding-right: 35px;">

                          

                          <!--GRUPO ADULTOS-->

                          

                       

				       </div>

                  </div>

              



              </div>

              <!--FIN ACORDEON PERSONA-->

              













               <!--ACORDEON TOURS-->







              <div class="accordion mb-2" id="Seleccionar_adicionales_b">

                 <div class="card card-accordion">

                    <div class="" id="headingOne">

                      <h5 class="mb-0">

                        <a class="btn btn-accordion btn-calendar text-white"  data-toggle="collapse" data-target="#seleccionar_adicionales_a" aria-expanded="false" aria-controls="collapseOne">

                         <i class="fa fa-hiking"></i><?=$lang["adicionales"]?><i class="fa fa-sort-down float-right"></i> </a>

                      </h5>

                    </div>

                    <div id="seleccionar_adicionales_a" class="collapse" aria-labelledby="headingOne" data-parent="#seleccionar_adicionales_a">









<!--ADICIONALES NO INCLUIDOS LOOP-->



                            <div class="container py-3" id="divAdicionalesNoIncluidos">

                 

                          </div>





<!--FIN ADICIONALES NO INCLUIDOS LOOP-->



                     </div>

                  </div>

              </div>

              <!--ACORDEON TOURS-->







   <button class="btn btn-primary btn-reservar-calendar btn-lg h-100" onclick="enviar()"><?=$lang["reservar"]?></button>



          
   
            </div>

        <!--FIN DE CALENDARIO-->
 
        </div> 

           </div>

          </div>

           <!--BARRA DESPLAZADA-->

          </div>

   

      </div>


</section> 


<section>


  <div class="container" style="display: none;">

    <div class="row">

      <div class="col-md-6">

           <!--CONTENEDOR PUNTO DE ENCUENTRO-->

      <div id="punto-encuentro">



      <!--TEXTO PUNTO DE ENCUENTRO-->



        <h2 class="py-4 text-primary"><?=$lang["punto_de_encuentro"]?></h2>



         <!--TEXTO DESTACADO-->

         <p><?php// DevuelveDireccionServicio($id); ?>.</p>

         <!--FIN TEXTO DESTACADO-->





      



            <!--FIN TEXTO PUNTO DE ENCUENTRO-->

            </div>

          <!--FIN CONTENEDOR PUNTO DE ENCUENTRO-->

      </div>

    </div>

  </div>



     <!--MAPA-->

<div class="google-maps" style="display: none;">

              <iframe src = "https://maps.google.com/maps?q=<?php//DevuelveCoordenadasServicio($id)["latitud"]?>,<?php//DevuelveCoordenadasServicio($id)["longitud"]?>&hl=es;z=14&amp;output=embed"></iframe>

                  </div>



             <!--FIN MAPA-->

  
          
  <div class="container" >

         <div class="row">

           <div class="col-lg-12">

          




<?php 

$CantOpinionesServicio=count($OpinionesServicio);

if ($CantOpinionesServicio>0) {

?>



     <div class="mb-5" id="opiniones" >

              <div class="row no-gutters">

                <div class="col-lg-8">

                  <h2 class="py-4 text-primary"><?=$lang["opiniones_de_nuestros"]?></h2>

                  <p><?=$lang["todas_las_opiniones"]?></p>

                </div>

           

              </div>

         



<?php





for ($i=0; $i < 1; $i++) { 

  $fecha_comentario=date("d-m-Y", strtotime($OpinionesServicio[$i]["fechaAlta"]));

  $estrellas=intval($OpinionesServicio[$i]["estrellas"]);   

  $texto_viajeros=getTextoViajeros($OpinionesServicio[$i]["selPasajeros"])[0];

?>

          

              <div class="row no-gutters">

                <div class="col-lg-12">

                  <p class="mb-4"><?=$lang["mas_populas"]?>Mostrando 1 de <?= $CantOpinionesServicio; ?><?=$lang["mas_populas"]?> opiniones</p>

                 <!-- CARGA BUCLE OPINIONES-->



 <div class="card card-opiniones mb-5">

                    <div class="card-body">

                      <div class="container">

                        <div class="row">

                          <div class="col-lg-2 col-12">

                            <?php for ($j=0; $j < $estrellas; $j++) { 

                              ?> <i class="fa fa-star text-primary"></i> <?php

                            } ?>

                            

                           

                            <p><?=$fecha_comentario;?></p>

                          </div>

                          <div class="col-lg-6 col-4 text-center">

                        <p class="mb-0 title-nombre"> <?=$OpinionesServicio[$i]["opinion"];?></p>

                          </div> 

                          <div class="col-lg-2 col-4">

      <p class="mb-0 title-nombre"><?=$OpinionesServicio[$i]["nombre"];?></p>

                        

                          </div>

                          <div class="col-lg-2 col-4">

                                <p class="mb-0 title-nombre"><?=$texto_viajeros['texto_viajeros'];?></p>

                          </div>

                        </div>

                      </div>

                    </div>

                  </div>



<?php if ($CantOpinionesServicio>1) {

  # code...

?>





   <!-- FIN CARGA BUCLE OPINIONES-->

                      <div class="row text-center">

                <div class="col-md-12">

                    <a class="btn btn-white-destinos"  id="alternar-panel-oculto-1" data-toggle="collapse" data-target="#Ver-mas-opinones" aria-expanded="false" aria-controls="Ver-mas-opinones">

                      <?=$lang["ver_todas_las_opiniones"]?>

                    </a>

                </div>

              </div>







                </div>

              </div>



<?php

}}  ?>

            <!-- CONTENEDOR OPINIONES-->

     

  

      <?php



for ($i=1; $i < $CantOpinionesServicio; $i++) { 

  $fecha_comentario=date("d-m-Y", strtotime($OpinionesServicio[$i]["fechaAlta"]));

  $estrellas=intval($OpinionesServicio[$i]["estrellas"]);   

  $texto_viajeros=getTextoViajeros($OpinionesServicio[$i]["selPasajeros"])[0];

?>

              <!--Mas opiniones-->

              <div class="row collapse" id="Ver-mas-opinones">

                 <div class="col-lg-12">

                  <p class="mb-4">

                    <?=$lang["mostrando"]?>

                  <?=($i+1)?><?=$lang["de_"]?><?= count($OpinionesServicio);?><?=$lang["opiniones"]?></p>

                 <!-- CARGA BUCLE OPINIONES-->





 <div class="card card-opiniones mb-5">

                    <div class="card-body">

                      <div class="container">

                        <div class="row">

                          <div class="col-lg-2 col-12">

                            <?php for ($j=0; $j < $estrellas; $j++) { 

                              ?> <i class="fa fa-star text-primary"></i> <?php

                            } ?>

                            

                           

                            <p><?=$fecha_comentario;?></p>

                          </div>

                          <div class="col-lg-6 col-4 text-center">

                        <p class="mb-0 title-nombre"> <?=$OpinionesServicio[$i]["opinion"];?></p>

                          </div> 

                          <div class="col-lg-2 col-4">

      <p class="mb-0 title-nombre"><?=$OpinionesServicio[$i]["nombre"];?></p>

                        

                          </div>

                          <div class="col-lg-2 col-4">

                                <p class="mb-0 title-nombre"><?=$texto_viajeros['texto_viajeros'];?></p>

                          </div>

                        </div>

                      </div>

                    </div>

                  </div>









              

                   <!-- FIN CARGA BUCLE OPINIONES-->

                 <?php

} } ?>

                </div>

              </div>



        

        

          </div>

           <!--FIN CONTENEDOR OPINIONES-->
<br><br>

<!--botones de compartir whatsapp y facebook-->

<div>

<div class="row" style="margin-right: 3%; margin-left: 3%;">
    <div class="col fb-share-button" data-href="https://www.metelebrasil.com/servicio?id=<?=$idServicio?>" data-layout="button_count" data-size="small"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fwww.metelebrasil.com%2Fservicio%3Fid%3D<?=$idServicio?>&amp;src=sdkpreparse" class="btn btn-primary btn-lg active btn-block"><i class="fab fa-facebook-f"></i>  <?=$lang["compartir_en_facebook"];?></a>
    </div>
    <div class="col">
     <a class="btn btn-success btn-lg active btn-block" href="whatsapp://send?text=Metelebrasil%20https://www.metelebrasil.com/servicio?id=<?=$idServicio;?>"><i class="fab fa-whatsapp"></i>  <?=$lang["compartir_en_whatsapp"];?></a>
  </div>
         </div>


</div>

            <!--CARDS DE INTERES-->

<br><br>

      


            <div class="container mb-5">

               <h2 class="text-center mb-4"><?=$lang["tambien_te_puede_interesar"]?></h2>

              <div class="row">


     




<?php $serviciosRelacionados=getServiciosidCategoria_servicio($idCategoria_servicio); 

if (count($serviciosRelacionados)>=3) {

  



for ($i=0; $i < 3; $i++) { 

      $idServicioRelacionado=$serviciosRelacionados[$i]["idServicio"];

    $fecha=date("Y-m-d");

    $textoMiniatura=getTextoMiniatura($serviciosRelacionados[$i]["idTextoMiniaturas"])[0]["texto"];

    $salidas=getSalidasFechaIdServicio($fecha,$idServicioRelacionado);

  if (count($salidas)>0) {

    # code...

 

      

       $idMoneda=$salidas[0]['idMoneda'];

      $idServicioSalidas=$salidas[0]['idServicioSalidas'];

      $tarifas=getTarifas($idServicioSalidas);



   $tarifa=calculaTarifa($tarifas[0]['idServicioSalidasTarifas'],

1);

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

<!--CARGA DE CARD DE INTERES-->

              

                  <!--CARGA DE CARD DE INTERES-->

        

 

     

         <!--BUCLE DE RESULTADOS -->

                

                    <!--CARGA DE CARD DE INTERES-->

         

              </div>

            </div>



       

            <!--FIN CARDS DE INTERES-->






   </div>

</div>

</div>










<!--SECCION INFORMACION MOVIL-->



<?php  //include("movil-acordeon.php");

//no se usa mas, esa pegado aca abajo por que sino el javascript traia problemas al querer levantar una fecha por default al calendario del movil

 ?>

<!--CONTENEDOR DE ACORDEON-->









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

           



<!--NOSE QUE ES ESTO lo iba a borrar pero te puede servir si no lo encontra para sebi de lucas

            <small>55 US$</small>

            <h2 class="text-primary"><span>49</span> US$</h2>-->

<!--Finaliza esto -->





            <p><?=$lang["sin_sobreprecios"]?></p>

            <p class="text-success"><b><?=$lang["cancelacion_gratuita_"]?></b></p>

        </div>

        <div id="sidebar-calendar-movil">

                    <div id="mini-clndr-movil" class="calendario-visitas-movil cal2"></div> 

            </div>





          





    <script src="admin/js/underscore-min.js"></script>

    <script src="admin/js/moment.min.js"></script>

    <script src="admin/js/clndr.js"></script>

  

 <script type="text/javascript" src="js/traeHorarios.js"></script>     



<script type="text/javascript" >



$( document ).ready(function() {

 

});

/* CALENDARIO CAL1 */

var eventArray=<?php echo($eventArray); ?>;

var targetOld="";

var eventoProximo;

var claseDiaSel;

var traigoDiaPorClase;

  $('.cal1').clndr({

        events: eventArray,

          startWithMonth:eventArray[0]["date"],

            

        clickEvents: {

            click: function (target) {

                        

          

                if (target['events'].length>0) {

              

 //************colorea dias    ***********************

                   if(targetOld!==target["events"][0]["title"]){

        

             targetOld=target["events"][0]["title"];



if (true) {

/*DESPINTAMOS EL PRIMER DIA QUE SE SELECCIONA SOLO */

traigoDiaPorClase = document.getElementsByClassName('calendar-day-'+eventArray[0]["date"])[0];

traigoDiaPorClase.style.color = "black";

traigoDiaPorClase.style.background = "#FFF";

traigoDiaPorClase.style.borderRadius = "0px";

/*       FIN DESPINTAMOS EL PRIMER DIA QUE SE SELECCIONA SOLO  */



for (var i = 0; i < document.getElementsByClassName(claseDiaSel).length; i++) {

traigoDiaPorClase = document.getElementsByClassName(claseDiaSel)[i]

traigoDiaPorClase.style.color = "black";

traigoDiaPorClase.style.background = "#FFF";

traigoDiaPorClase.style.borderRadius = "0px";



} }



claseDiaSel = (target["element"].getAttribute('class'));



for (var i = 0; i < document.getElementsByClassName(claseDiaSel).length; i++) {

traigoDiaPorClase = document.getElementsByClassName(claseDiaSel)[i];



traigoDiaPorClase.style.color = "#FFF";

traigoDiaPorClase.style.background = "#029ce2";

traigoDiaPorClase.style.borderRadius = "50%";

}   }  



// fin  colorea dia clickeado************************* 





traeHorarios(target["events"][0]["date"],<?=$idServicio;?>);

 

        }

     },

  },

        multiDayEvents: {

            singleDay: 'date',

            endDate: 'endDate',

            startDate: 'startDate'

        },  showAdjacentMonths: false,

        adjacentDaysChangeMonth: false

 });





traeHorarios(eventArray[0]["date"],<?=$idServicio;?>);







/*Pintamos el dia al cargar el calendario */

traigoDiaPorClase = document.getElementsByClassName('calendar-day-'+eventArray[0]["date"])[0];

traigoDiaPorClase.style.color = "#FFF";

traigoDiaPorClase.style.background = "#029ce2";

traigoDiaPorClase.style.borderRadius = "50%";

/*FIN  Pintamos el dia al cargar el calendario */



/* FIN CALENDARIO CAL1 */

var claseDiaSel;

var traigoDiaPorClase;



  $('.cal2').clndr({

        events: eventArray,

          startWithMonth:eventArray[0]["date"],

        clickEvents: {

            click: function (target) {

                        

          

                if (target['events'].length>0) {

 //************colorea dias    ***********************

                   if(targetOld!==target["events"][0]["title"]){

        

             targetOld=target["events"][0]["title"];



if (true) {



  /*DESPINTAMOS EL PRIMER DIA QUE SE SELECCIONA SOLO */

traigoDiaPorClase = document.getElementsByClassName('calendar-day-'+eventArray[0]["date"])[1];

traigoDiaPorClase.style.color = "black";

traigoDiaPorClase.style.background = "#FFF";

traigoDiaPorClase.style.borderRadius = "0px";

/*       FIN DESPINTAMOS EL PRIMER DIA QUE SE SELECCIONA SOLO  */





for (var i = 0; i < document.getElementsByClassName(claseDiaSel).length; i++) {

traigoDiaPorClase = document.getElementsByClassName(claseDiaSel)[i]

traigoDiaPorClase.style.color = "black";

traigoDiaPorClase.style.background = "#FFF";

traigoDiaPorClase.style.borderRadius = "0px";



} }



claseDiaSel = (target["element"].getAttribute('class'));



for (var i = 0; i < document.getElementsByClassName(claseDiaSel).length; i++) {

traigoDiaPorClase = document.getElementsByClassName(claseDiaSel)[i];



traigoDiaPorClase.style.color = "#FFF";

traigoDiaPorClase.style.background = "#029ce2";

traigoDiaPorClase.style.borderRadius = "50%";

}   }  



// fin  colorea dia clickeado************************* 





traeHorarios(target["events"][0]["date"],<?=$idServicio;?>);



        }

     },

  },

        multiDayEvents: {

            singleDay: 'date',

            endDate: 'endDate',

            startDate: 'startDate'

        },  showAdjacentMonths: false,

        adjacentDaysChangeMonth: false

 });



  /*Pintamos el dia al cargar el calendario */

traigoDiaPorClase = document.getElementsByClassName('calendar-day-'+eventArray[0]["date"])[1];

traigoDiaPorClase.style.color = "#FFF";

traigoDiaPorClase.style.background = "#029ce2";

traigoDiaPorClase.style.borderRadius = "50%";

/*FIN  Pintamos el dia al cargar el calendario */

/* FIN CALENDARIO CAL2 */



</script>



             <hr class="hr-divider "> 

             <div id="sidebar-calendar-movil-copia" class="text-center">

               <!--ACORDEON HORA-->

              <div class="accordion mb-2" id="hora-movil">

                 <div class="card card-accordion">

                    <div class="" id="headingOne">

                      

                        <button class="btn btn-accordion btn-calendar text-white " aria-expanded="false"  data-toggle="collapse" data-target="#divhora-movil" aria-controls="collapseOne" id="acordeonHoraMovil">

                        <h5 class="mb-0">

                         <i class="fa fa-clock"></i><?=$lang["elegi_la_hora"]?><i class="fa fa-sort-down text-primary float-right"></i>

                        </h5>



                        </button>

                      

                    </div>



                    <div id="divhora-movil" class="" aria-labelledby="headingOne" data-parent="#divhora-movil">

                        

                            

                    </div>

                  </div>

              </div>

                   <div id="divLugares-movil" ></div>

              <!--FIN ACORDEON HORA-->





               <!--ACORDEON PERSONA-->

             <div class="accordion mb-2" >

                 <div class="card card-accordion">

                    <div class="" id="headingOne">

                      <h5 class="mb-0">

                        <a class="btn btn-accordion btn-calendar text-white "  data-toggle="collapse" data-target="#seleccionar_personasMovil" aria-expanded="false" aria-controls="collapseOne">

                         <i class="fa fa-male"></i><?=$lang["cuantas_personas"]?><i class="fa fa-sort-down text-primary float-right"></i>

                        </a>

                      </h5>

                    </div>



                    <div id="seleccionar_personasMovil" class="collapse" aria-labelledby="headingOne" data-parent="#seleccionar_personasMovil">

                          

                          <!--GRUPO ADULTOS-->

                   

                            <!--FIN GRUPO ADULTOS-->

                            

           

                            

              



                    </div>

                  </div>

              </div>

              <!--FIN ACORDEON PERSONA-->



 <!--ACORDEON PERSONA-->

            

             <div class="accordion mb-2" id="Seleccionar_adicionales_b_celular">

                 <div class="card card-accordion">

                    <div class="" id="headingOne">

                      <h5 class="mb-0">

                        <a class="btn btn-accordion btn-calendar text-white"  data-toggle="collapse" data-target="#divpersona-movil-adicional" aria-expanded="false" aria-controls="collapseOne">

                         <i class="fa fa-male"></i><?=$lang["adicionales"]?><i class="fa fa-sort-down text-primary float-right"></i>

                        </a>

                      </h5>

                    </div>

<!-- si pongo esto los adicionales en el celular no se pueden cerrar

  y el mas a la derecha y el menos a la izquierda

  

                    <div id="divpersona-movil-adicional" class="collapse " aria-labelledby="headingOne" data-parent="#personas-movil-adicional">-->





                     

                         

                          <!--GRUPO ADULTOS-->

                          

                          <div class="container" id="divAdicionalesNoIncluidosCelular" class="collapse " aria-labelledby="headingOne" data-parent="#divpersona-movil-adicional">



<!--ADICIONALES NO INCLUIDOS LOOP-->

<!--<input type="hidden" value="'.$pre.'" id="precio'.$registros[$i][2].'">

                            <div class="container py-3">

                              <div class="row">

                                  <div class="col-md-12">

                                      <p class="counter-label mb-2 text-left">'.$registros[$i][0].'</p>

                                  </div>

                                  <div class="col-md-3 col-3">

   <span class="counter-label_span"><label id="txtPrecioMovil'.$registros[$i][2].'">'.$sym." ".$pre.'</label></span>

                                  </div>

                                 <a onclick="DecrementaAdicional('.$registros[$i][2].')" > 

                                 <div class="col-md-1 col-1" style=" padding-left: 0px !important;padding-right: 0px !important;" >

  

  <i class="fa fa-minus-circle fa-2x"></i>

                                   </div></a>

                                   <div class="col-md-4 col-3">

<input type="number" class="form-control" 

        value=0 id="cantPersAdcMovil'.$registros[$i][2].'"></input>

                                   </div>



  

 <div class="col-md-1 col-1" style=" padding-left: 0px !important;padding-right: 0px !important;" >

  <a  onclick="IncrementaAdicional('.$registros[$i][2].')">



 <i class="fa fa-plus-circle fa-2x"></i>

</a>

                                   </div>

       <div class="col-md-3 col-3">

       <small class="counter-label_span_precio"><label id="lblTotalMovil'.$registros[$i][2].'"></label></small>

                                   </div>

                              </div>

                          </div>-->













          

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

                        <a class="btn btn-accordion btn-calendar text-white "  data-toggle="collapse" data-target="#seleccionar-descuento-movil" aria-expanded="true" aria-controls="collapseOne">

                         <i class="fa fa-clock"></i><?=$lang["cupon_de_descuento"]?><i class="fa fa-sort-down text-primary float-right"></i>

                        </a>

                      </h5>

                    </div>



                    <div id="seleccionar-descuento-movil" class="collapse " aria-labelledby="headingOne" data-parent="#seleccionar-descuento-movil">

                      <div class="form-group">

                        <label for="">

                          <?=$lang["ingrese_un_cupon"]?>

                        </label>

                        <input type="text"  onkeyup="cupon(this.value)" name="" id="txtCuponDescuentoMovil" class="form-control">

                      </div>

                      <div id="cuponOkMovil"></div>

                            <!--Aca el js trae los horarios al clickear en el calendario-->

                    </div>

                  </div>

              </div>

<!---DESCUENTO-->

<br>

 <button onclick="enviar()" class="btn btn-primary btn-radius btn-lg h-100"><?=$lang["reservar"]?></button>

<br>

             </div>

              </div>

            </div>

          </div>

      </div>

    </div>

  </div>

</div>

</div>




<!--MODAL HORARIO-->



<!--FIN SECCION INFORMACION MOVIL-->

<?php if (isset($_GET["cupon"])) {

  echo '<script>

cupon("'.$_GET["cupon"].'");



  </script>';

} ?>

</section>

<!--BOTON RESERVA MOVIL-->



<section class="d-md-none scroll-to-top2  position-fixed">

    <div class="container-fluid d-md-none">

        <div class="row">

            <div class="col-6 py-2 bg-white">

              <p class="mb-0 text-center mb-0 text-primary" style="font-size:25px;"><small class="text-gris"><?=$lang["desde"]?></small> <span class="bold" id="precioTotalFooterNavCelular"></span></p>

            </div>

            <div class="col-6 bg-primary py-2">

              <p  class="mb-0 text-center" style="font-size:28px;">

                <button class= "bnt btn-primary" data-toggle="modal" data-target="#modalhorario-movil" class="text-white bold">

                <?=$lang["reservar"]?>
               </button>

             </p>

           </div>

            

       </div>

   </div>



</section>



<!--FIN BOTON RESERVA MOVIL-->

  









<?php 

$rol=0;

if (isset($_SESSION["rol"])){

$rol=$_SESSION["rol"];

}





 ?>





<script >

 

</script>





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

            <label class="sr-only" for="s"><?=$lang["donde_vamos"]?></label>

          <div class="input-group ">

            <input class="field form-control" id="buscar" name="buscar" type="text" placeholder="¿Dónde vamos?" value="">

            <span class="input-group-append">

              <button class="submit btn btn-primary" id="searchsubmit2" name="submit" type="submit"><?=$lang["buscar"]?><i class="fa fa-arrow-right"></i></button>

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

                            <h4 class=" mb-0"><a  class="text-destinos">Florianopolis</a></h4>

                            <small>Santa Catarina</small>

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