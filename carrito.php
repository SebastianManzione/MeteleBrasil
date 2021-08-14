<?php 

session_start();



include("admin/classes/salidas.php");

include("admin/classes/tarifas.php");

include("admin/classes/tarifas_ubicacion.php");

include("admin/classes/idiomas.php");

include("admin/classes/servicio.php");

  include("admin/classes/comisiones.php");

    include("admin/classes/edades.php");

    include("admin/classes/cancelaciones.php");

    include("admin/classes/servicios_adicionales.php");

        include("admin/classes/convierte_monedas.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST'){



  if (isset($_POST["eliminarPaquete"])) {

  $posicion=$_POST["eliminarPaquete"];

  unset ($_SESSION['reserva'][$posicion]); 



$_SESSION['reserva'] = array_values($_SESSION['reserva']);





  }





    if (isset($_POST["eliminarActividad"])) {

  $posicion=$_POST["eliminarActividad"];

  unset ($_SESSION['reserva'][$posicion]); 



$_SESSION['reserva'] = array_values($_SESSION['reserva']);





  }

}



$totalCarrito=0;



include ("admin/classes/functions.php");







$carrito=$_SESSION['reserva'];



//print_r($carrito);

$cantCarrito=count($carrito);



// unset($_SESSION['reserva']);





if (false) {//$cantCarrito<1

echo '

<script>

location.href="index.php";

</script>

';

}


include("includes/headerPagos.php");
?>






<!--PASOS PARA RESERVA-->

<section class="py-2 bg-white">

  <div class="container">

    <div class="row">

      <div class="col-lg-12  ">

        <ul class="lista-pasos-form">

          <li class="active"><a href="carrito" class="btn-pasos"><strong><span>1</span> 

            <b><?=$lang["revisa_tus_reservas"]?></b></strong></a></li>

          <li><span>2</span><?=$lang["datos_personales"]?></li>

          <li><span>3</span> <strong><?=$lang["metodo_de_pago"]?></strong></li>

        </ul>

      </div>

    </div>

  </div>

</section>

<!--FIN PASOS PARA RESERVA-->





<!--SECCION DATOS PERSONALES-->

<section>

  <div class="container">

    <div class="row">



      <!--RESUMEN DE PEDIDO-->

      <div class="col-lg-4 col-md-4">

        <div class=" py-2">

             <div class="card card-visitas">

              <div class="card-body">

                 <h5><?=$lang["resumen_de_compra"]?></h5>

                 <!--ACORDEON CARACTERISTICAS-->

                  <div class="accordion" id="faq1">

                 <div class="card card-accordion">

                    <div class="" id="headingOne">

                      <h5 class="mb-0">

                        <a class="btn btn-accordion text-primary bg-white " href="#" data-toggle="collapse" data-target="#collapseOne2" aria-expanded="true" aria-controls="collapseOne">

                   

                          <p style="font-size: 20px;"> <?= $cantCarrito; ?> <?=$lang["servicios"]?></p>

                        

                        </a>

                      </h5>

                    </div>



                  



<div id="collapseOne2" class="collapse show" aria-labelledby="headingOne" data-parent="#faq1">  

  <ul class="lista-caracteristicas-r mx-4">

      <?php 

         

$precioTotalCarrito=0;

$totalDescuentos=0;

    for ($i=0; $i < $cantCarrito; $i++) {  

     echo "RESERVA nro: ".($i+1);

      $reserva=$carrito[$i][0];

      $reservaAdicionales=$carrito[$i][1];

       $precioReserva=0;

       $cantidadPasajeros=0;

      

      for ($j=0; $j < count($reserva); $j++) { 

       

      $servicio=getServicio($reserva[$j]['idServicioSeleccionado']);

      $idServicioSalidasTarifas=$reserva[$j]['idServicioSalidasTarifas'];

      $cantidad=$reserva[$j]['cantidad'];



      $cantidadPasajeros+=$reserva[$j]['cantidad']; 

  

        $tarifa=calculaTarifa($reserva[$j]["idServicioSalidasTarifas"],$reserva[$j]["cantidad"]);



          $totalDescuentos+=$tarifa[0]["totalDescuentos"];

          $precioReserva+=$tarifa[0]["valor"];

          if ($j==0) {

            ?>

            <li><?=$servicio[0]["nombre_servicio"];?><li>

            <?php

          }

      ?>

    <li><?= $cantidad.' '.$tarifa[0]["nombre"].' ('.$tarifa[0]["edadFrom"].' a '.$tarifa[0]["edadTo"].' Anos)'?><li>

    <li>Subtotal <?= $tarifa[0]["valorSinIvaSym"]; ?><li>

      <li>ICMS <?= $tarifa[0]["valorDeIvaSym"]; ?><li>

      <?php

$precioTotalCarrito+=$tarifa[0]["valor"];



      }

      

if ($_SESSION["login"]["idVendedor"]>0) {

  echo("<li>comision Vendedor: ". $_SESSION['moneda_sel_sym']."".$tarifa[0]["comisionVendedor"].'</li>');

echo("<li>comision Sistema: ". $_SESSION['moneda_sel_sym']."".$tarifa[0]["comisionSistema"].'</li>');

}

?>

   <li>Subtotal Reserva <?= $_SESSION['moneda_sel_sym']."".$precioReserva;?></li>

   

<?php



if (isset($reservaAdicionales) && count($reservaAdicionales)>0) {



 ?>

 <li>Adicionales: </li>

 <?php



for ($j=0; $j < count($reservaAdicionales); $j++) { 

  $idServicioSalidasAdicionales=$reservaAdicionales[$j]["idServicioSalidasAdicionales"];

 $servicioAdicional= getServicioAdicionalSalida($idServicioSalidasAdicionales);

 $cantidad=$reservaAdicionales[$j]["cantidad"];

 

 $valor=getValorServiciosAdicionalesSalida($idServicioSalidasAdicionales,$cantidad);



 $precioTotalCarrito+=$valor[0]["valor"];

  ?>

    <li><?=$cantidad;?> <?=$servicioAdicional[0]["nombre"]?> (X PAX <?=$valor[0]["valorSymUnitario"];?>)</li>

  <?php

 }}

 ?>

  <hr>

 <?php





} 

if ($totalDescuentos>0) {

  ?>

 <li><?=$lang["su_descuento"]?><?=$_SESSION['moneda_sel_sym'].$totalDescuentos;?></li>

   <li><?=$lang["anfitrion"]?><?=$_SESSION['cupon_descuento']['anfitrion'];?></li>

  <?php

}



?>





     



    <!--  <li> Total Adicionales: ".$sym." ".$subTotalAdicionales."</li>

      Total Reserva: ".$sym." ".$parcialReserva."-->



                           </div>

                           

                  

                  </div>

              </div>

                 <!--FIN ACORDEON CARACTERISTICAS-->

                <hr class="hr-puntuada">

                 <!--PRECIO TOTAL-->



                 <div class="div-precio-t">

                   <p class="mb-0 d-inline-block"><strong><?=$lang["total_carrito"]?></strong></p>

                  <h5 class="mb-0 bold d-inline-block float-right"><trong><?=  $_SESSION['moneda_sel_sym']."".($precioTotalCarrito); ?></trong></h5>

                 </div>



                 <!--FIN PRECIO TOTAL-->



              </div>

            </div>

          </div>

      </div>

      <!--FIN RESUMEN DE PEDIDO-->



      <!--DATOS PERSONALES-->

      <div class="col-lg-8 col-md-8">





      <?php 

      require("admin/classes/fotos_servicio.php");   

$precioTotalCarrito=0;

    for ($i=0; $i < $cantCarrito; $i++) {  

   

      $reserva=$carrito[$i][0];

      $idServicio=$reserva[0]['idServicioSeleccionado'];

            $servicio=getServicio($reserva[0]['idServicioSeleccionado']);

           $fotos=getFotosServicio($idServicio);

      ?>



       <div class=" py-2">

             <div class="card card-visitas">

                <div class="card-body">

                   <div class="row ">

                      <div class="col-md-4  col-4">

                      <img   src="admin/classes/imgServicio/<?=$fotos[0]['ruta'];?>" class=" img-fluid img-card-destinos">

        

  

                      </div>

                       <div class="col-md-8 col-8">

                         <div class="card-block ">

                          <h4 class="text-left titulo-card-destinos text-primary  mb-4"><?=$servicio[0]["nombre_servicio"];?>  </h4>

                          <div class="d-md-block d-none">

                            <div class="row no-gutters text-center ">

                            <div class="col-lg-5 col-md-5">

                             <p class="mb-0"><label class="h3 text-gris">

                             <?php 

                               $cantidadPasajeros=0;

                              for ($j=0; $j < count($reserva); $j++) { 

                                $cantidadPasajeros+=$reserva[$j]['cantidad'];

                              ?>

                              

                              <?php

                              

                              }

                             ?>

                             <?=  $cantidadPasajeros;?>  

 <i class="fas fa-users text-gris"></i> 

                              </label></p>





      <?php



       $precioReserva=0;

       $cantidadPasajeros=0;



      for ($j=0; $j < count($reserva); $j++) { 

  

      $idServicioSalidasTarifas=$reserva[$j]['idServicioSalidasTarifas'];

      $cantidad=$reserva[$j]['cantidad'];

      $cantidadPasajeros+=$reserva[$j]['cantidad'];

        $tarifa=calculaTarifa($reserva[$j]["idServicioSalidasTarifas"],

$reserva[$j]["cantidad"]);   

        $salida=getSalida($tarifa[0]['idServicioSalidas'] );

        $idiomas= getIdiomaSalida($salida[0]['idServicioSalidas']);

          $precioReserva+=$tarifa[0]["valor"];
          $fecha=strtotime($salida[0]['fecha']);
          require("admin/classes/locale.php");


      

         if($j==0){



         }

      ?>

     <li> <?= $cantidad.' '.$tarifa[0]["nombre"].' ('.$tarifa[0]["edadFrom"].' a '.$tarifa[0]["edadTo"].' Anos)'?></li> 

  





     <?php

$precioTotalCarrito+=$tarifa[0]["valor"];



      } //     for ($j=0; $j < count($reserva); $j++) { 





?>        

        </div>           

                           

                            <div class="col-lg-3 col-md-3 ">

                              <p class="mb-0 h3 text-gris"><i class="fa fa-language "></i></p>

                              <?php for ($m=0; $m < count($idiomas); $m++) { 

                                ?>

<li><?=$idiomas[$m];?></li>

                                <?php

                              } ?>

                           

                            

                            </div>

                            <div class="col-lg-2 col-md-2">

                               <p class="mb-0 h3 text-gris"> <i class="fa fa-calendar-alt"></i>

                                <?=date("d", strtotime($salida[0]['fecha']));?>

                               </p>

                             <!--<p><?php //echo DevuelveFechaHorario($horarioId)[0][2]; ?></p>-->

                             <p class="mb-0">

                              

                              <?php echo (strftime("%B", $fecha));?>

                                                   <?=date("Y", strtotime($salida[0]['fecha']));?> 

                             </p>

                            </div>

                            <div class="col-lg-2 col-md-2">

                               <p class="mb-1  h3 text-gris"><i class="fa fa-clock"></i></p>

                                 <p class="mb-0">

                              <?=$salida[0]['horaCheckIn'];?>

                              

                               

                             </p>

                            </div>

                          </div>

                          </div>

                       

     





    </div>

                  </div>

                </div>   <div class="row">

                  <div class="col-md-12">

                    <form method="post" action="carrito">

                      <input type="hidden" name="eliminarActividad" value="<?= $i ?>">

                    <div class="custom-control custom-checkbox mr-sm-2">

                   <button onclick="" class="btn btn-danger btn-lg btn-radius"><?=$lang["eliminar"]?></button>

                    </div>

                 

              </form>

                   

                  </div>

                </div>  </div>

             </div>

            </div>

<?php



} ?>

     



            



            

           <!--DATOS DE ACTIVIDADES -->

          



           <!--FIN DATOS DE ACTIVIDADES-->





      <!--FIN DATOS PERSONALES-->

   



 



   



<script>

  function carga(){

      $('#body').css('display','none');

  $('#bodyCarga').html('<div class="d-flex justify-content-center" style="margin-top: 15em;">  <div class="spinner-border" role="status">    <span class="sr-only">Loading...</span>  </div></div>');

  }

  function enviaa(){

 





       chkCondiciones=$('#customControlAutosizing').prop('checked');

      datos=Array();

      datos[0]='<?= $horarioId; ?>';

      datos[1]='<?= $cantidadAdul; ?>';

      datos[2]='<?= $cantidad12; ?>';

      datos[3]='<?= $cantidad5; ?>';

      datos[4]='<?= $cantidad3; ?>';      

      datos[5]='<?= $codCupon; ?>';      

       datos[6]='<?= $tmp; ?>';   



   datos[7]=Array();

    datos[7][0]=document.getElementById("txtNombre").value;

    datos[7][1]=document.getElementById("txtApellido").value;

    datos[7][2]=document.getElementById("txtPrefijo").value;

       datos[7][3]=document.getElementById("txtTelefono").value;

       datos[7][4]=document.getElementById("txtEmail").value;

       datos[8]='<?= $money?>';





       

       if (chkCondiciones) {

        if(datos[7][0]=="" ){

          alert("Todos los campos deben estar completos");

        } else {

			contador=0;

			if(contador==0){

			  contador+=1;

			   $.post('ctrlReserva.php', {

				data:{    'datos' : JSON.stringify(datos) }

			  }, function(response) {

			  if (response>1) {

			    alert("Reserva guardada con exito con el id: "+response);

			    window.location="metodo-pago.php?idReserva="+response;

			  }

			 });

			}

        }



       } else{

        alert("Aceptar la política de privacidad  y las condiciones generales es una obligación legal.");

       }



  }





</script>

<!--BOTON SIGUIENTE-->

    <div class="container mb-4">

      <div class="row">

        <div class="col-lg-8 col-md-8"></div>

        <div class="col-lg-4 col-md-4 col-12 text-right">

          <a href="datosPersonales.php" class="btn btn-primary btn-lg btn-radius" style="width: 100% !important;" onclick="carga();"><?=$lang["continuar"]?></a>

        </div>

      </div>

    </div>

<!--FIN BOTON SIGUIENTE-->



</section>

<!--FIN SECCION DATOS PERSONALES-->







  <!-- Footer -->

  <footer class="footer footer-reserva ">

    <div class="container">

      <div class="row">

        <div class="col-lg-4"></div>

        <div class="col-lg-2">

         <p class="text-gris text-pagos"> <i class="fa fa-lock mx-2 "></i> PAGO SEGURO</p>

        </div>

        <div class="col-lg-2">

          <img src="img/paypal-2.png" class="img-fluid img-foter">

        </div>

        <div class="col-lg-2">

          <img src="img/mastercard-2.png" class="img-fluid img-foter">

        </div>

        <div class="col-lg-2">

          <img src="img/visa-2.png" class="img-fluid img-foter">

        </div>

      </div>

    </div>

  </footer>



  <!-- Copyright Section -->

  <section class="copyright py-4 text-center text-white">

    <div class="container">

      <div class="row">

        <div class="col-lg-12">

           <h4 class="text-left"><small><span>METELE BRASIL</span><?=$lang["es_una_marca_registrada_de_reservate_sl"]?></small></h4>

        </div>

      </div>

    </div>

  </section>







<!-- MODAL POLITICA DE PRIVACIDAD-->

<div class="modal fade" id="politicas" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

  <div class="modal-dialog" role="document">

    <div class="modal-content">

      <div class="modal-header">

         <h4 class="text-center"><?=$lang["politica_de_privacidad"]?></h4>

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">

          <span aria-hidden="true">&times;</span>

        </button>

      </div>

      <div class="modal-body">

         <div class="container">

           <div class="row">

             <div class="col-lg-12">

              <p><?=$lang["en_metelebrasil_queremos_ser_transparente"]?></p>

              <p><?=$lang["les_informamos_que_todos_los_datos"]?></p>

              <p><?=$lang["asimismo_para_ofrecerles"]?></p>

              <p><?=$lang["tenes_derecho_a_presentar_una_reclamacion_ante"]?></p>

              <p class="text-center"><a href="#" class="btn btn-primary btn-radius"><?=$lang["leer_mas"]?></a></p>

             </div>

           </div>

         </div>

      </div>

    </div>

  </div>

</div>

<!-- FIN MODAL POLITICA DE PRIVACIDAD-->





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

  <script src="js/scriptcarrito.js"></script>

  <!-- CUSTOM -->

  

<!-- FIN SCRIPTS NECESARIOS-->

</div>

</body>



</html>

