<?php 
session_start();
print_r($_SESSION);
include("admin/classes/salidas.php");
include("admin/classes/tarifas.php");
include("admin/classes/idiomas.php");
include("admin/classes/servicio.php");
  include("admin/classes/comisiones.php");
    include("admin/classes/edades.php");
    include("admin/classes/cancelaciones.php");
    include("admin/classes/servicios_adicionales.php");
        include("admin/classes/convierte_monedas.php");
    include("admin/classes/codigos_telefonicos.php");
$totalCarrito=0;

include ("admin/classes/functions.php");



$carrito=$_SESSION['reserva'];

//print_r($carrito);
$cantCarrito=count($carrito);



/*
if ($cantCarrito<1) {
echo '
<script>
location.href="carrito";
</script>
';
}
*/
?>

<!DOCTYPE html>
<html lang="en">

<head>
 <title>MeteleBrasil.com</title>
  
    <meta name="title" content="MeteleBrasil.com" />
    <meta name="description" content="Actividades, traslados, entradas, visitas guiadas y excursiones en español en todo el mundo. Reserva online con precio mínimo garantizado." />
    <meta name="keywords" content="excursiones, visitas guiadas, tours, actividades, traslados, transfers, circuitos, guias turísticas, guias de viaje" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
     <link rel="icon" href="img/favicon.png" sizes="32x32">
   
  <!-- ESTILOS NECESARIOS -->
    
   <!-- FONT-AWESOME -->
   <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
   <!-- FONT-AWESOME -->
   
   <!-- ANIMATE -->
   <link rel="stylesheet" href="css/animate.min.css">
   <!-- ANIMATE -->
   
   <!-- BOOTSTRAP V4-->
   <link href="css/bootstrap.css" rel="stylesheet">
   <!-- BOOTSTRAP V4 -->
   
   <!-- STYLES GENERALES -->
   <link href="css/styles.css" rel="stylesheet">
   <!-- STYLES GENERALES -->
   
   <!-- RESPONSIVE DESING-->
   <link href="css/responsive.css" rel="stylesheet">
   <!-- RESPONSIVE DESING -->
   
   <!-- ESTILOS CALENDARIO-->
   <link href="css/clnr.css" rel="stylesheet">
   <!-- ESTILOS CALENDARIO-->
   
   <!-- FUENTES-->
   <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,600,700" rel="stylesheet">
   
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
   <script src="./js/jquery.redirect.js"></script>
   <!-- FUENTES-->
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

  <!-- ESTILOS NECESARIOS -->

<style type="text/css">
  .card-body{
    padding: 0.8rem !important;
  }
  .texto-opinion-desta{
    font-size: 12px !important;
  }
  .img-card-destinos {
    height: 120px !important;
  }
</style>

</head>

<body id="page-top">
  <div id="bodyCarga"></div>
<div id="body">
<!--HEADER PAGO SEGURO-->

<section class="py-2 bg-primary">
  <div class="container">
    <div class="row">
      <div class="col-lg-2 col-6">
         <a class="navbar-brand text-white" ><h3>METELEBRASIL</h3></a>
      </div>
      <div class="col-lg-10 col-6">
        <p class="text-white text-pagos mb-0"> <i class="fa fa-lock mx-2 "></i> PAGO SEGURO</p>
      </div>
    </div>
  </div>
</section>

<!--FIN HEADER PAGO SEGURO-->

<!--PASOS PARA RESERVA-->
<section class="py-2 bg-white">
  <div class="container">
    <div class="row">
      <div class="col-lg-12  ">
        <ul class="lista-pasos-form">
          <li><a href="carrito.php" class="btn-pasos"><span>1</span> Revisa tu Pedido </a></li>
          <li  class="active"><b><strong><span>2</span> Datos personales</strong></b></li>
          <li><span>3</span> <strong>Método de pago</strong></li>
        </ul>
      </div>
    </div>
  </div>
</section>
<!--FIN PASOS PARA RESERVA-->
<script type="text/javascript">
  function validateForm(){


    if($('#customControlAutosizing').prop('checked')){
  $('#body').css('display','none');
  $('#bodyCarga').html('<div class="d-flex justify-content-center" style="margin-top: 15em;">  <div class="spinner-border" role="status">    <span class="sr-only">Loading...</span>  </div></div>');

     return true;
    }
    else{
      Swal.fire("Reservate", "Aceptar la politica de privacidad y cookies es una obligacion legal", "error");
   // alert("Aceptar la politica de privacidad y cookies es una obligacion legal");
      return false;
    }
  }


</script>
<form method="post" action="guardaReservas.php"  onsubmit="return validateForm()" >
<!--SECCION DATOS PERSONALES-->

<section>
  <div class="container">
    <div class="row">

  <!--RESUMEN DE PEDIDO-->
  <div class="col-lg-4 col-md-4">
        <div class=" py-2">
             <div class="card card-visitas">
              <div class="card-body">
                 <h5>Resumen de Compra </h5>
                 <!--ACORDEON CARACTERISTICAS-->
                  <div class="accordion" id="faq1">
                 <div class="card card-accordion">
                    <div class="" id="headingOne">
                      <h5 class="mb-0">
                        <a class="btn btn-accordion text-primary bg-white " href="#" data-toggle="collapse" data-target="#collapseOne2" aria-expanded="true" aria-controls="collapseOne">
                   
                          <p style="font-size: 20px;"> <?= $cantCarrito ?> Actividades</p>
                        
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
    <li><?= $cantidad.' '.$tarifa[0]["nombre"].' ('.$tarifa[0]["edadFrom"].' a '.$tarifa[0]["edadTo"].' Años)'?><li>
    <li>Subtotal <?= $tarifa[0]["valorSinIvaSym"]; ?><li>
      <li>IVA <?= $tarifa[0]["valorDeIvaSym"]; ?><li>
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
 <li>SU DESCUENTO: <?=$_SESSION['moneda_sel_sym'].$totalDescuentos;?></li>
  <li>Anfitrión: <?=$_SESSION['cupon_descuento']['anfitrion'];?></li>
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
                   <p class="mb-0 d-inline-block"><strong>Total Carrito</strong></p>
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

<div class="card-body">
                <h5>Responsable de la Reserva</h5>

                  <div class="form-row ">
                    <div class="form-group col-6">
                      <input type="text" class="form-control" placeholder="Nombre" name="txtNombreResponsable" required>
                    </div>
                    <div class="form-group col-6">
                      <input type="text" class="form-control" placeholder="Apellido" name="txtApellidoResponsable" required>
                    </div>
                  </div>     
                    
                  <div class="form-row">
                    <div class="form-group col-6">
                      <input type="email" class="form-control" placeholder="Correo Electronico" name="txtEmailResponsable" required>
                    </div>
                    <div class="form-group col-3">
                      <select class="form-control" name="idCountry">
                        <?php $codigosTelefonicos=getCodigosTelefonicos();
                for ($i=0; $i < count($codigosTelefonicos); $i++) { 
                  $selected="";
                  if ($codigosTelefonicos[$i]["phonecode"]==55) {
                  $selected="selected";
                  }
               ?><option value="<?=$codigosTelefonicos[$i]['id'];?>"<?=$selected;?>> +<?=$codigosTelefonicos[$i]['phonecode'];?> <?=$codigosTelefonicos[$i]['nicename'];?> </option><?php
                 } ?>
                        
                      </select>
                    </div>
                    <div class="form-group col-3">
                      <input type="text" class="form-control" placeholder="Teléfono móvil" name="txtTelefonoResponsable" required>
                    </div>
                  </div>
              
              </div>

            
           <!--DATOS DE ACTIVIDADES -->
           
           <?php 
            require("admin/classes/fotos_servicio.php");  
           $carrito=$_SESSION['reserva'];

           //print_r($carrito);
           $cantCarrito=count($carrito);


           for ($i=0; $i < $cantCarrito; $i++) {  
            echo "RESERVA nro: ".($i+1);
            $reserva=$carrito[$i][0];

            $reservaAdicionales=$carrito[$i][1];
            $idServicio=$reserva[0]['idServicioSeleccionado'];
             $precioReserva=0;
             $cantidadPasajeros=0;
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
                          <h4 class="text-left titulo-card-destinos text-primary  mb-4"><?=$servicio[0]["nombre_servicio"];?> </h4>
                          <div class="d-md-block d-none">
                            <div class="row no-gutters text-center ">
                            <div class="col-lg-3 col-md-3">
                  <p class="mb-0"><label class="h3 text-gris">
                             <?php 
                               $cantidadPasajeros=0;
                              for ($j=0; $j < count($reserva); $j++) { 
                                $cantidadPasajeros+=$reserva[$j]['cantidad'];
                              
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
$reserva[$j ]["cantidad"]);   
        $salida=getSalida($tarifa[0]['idServicioSalidas'] );
        $idiomas= getIdiomaSalida($salida[0]['idServicioSalidas']);
          $precioReserva+=$tarifa[0]["valor"];
         if($j==0){

         }
      ?>
     <li> <?= $cantidad.' '.$tarifa[0]["nombre"].' ('.$tarifa[0]["edadFrom"].' a '.$tarifa[0]["edadTo"].' Años)'?></li> 
  


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
                            <div class="col-lg-3 col-md-3">
                               <p class="mb-0 h3 text-gris"> <i class="fa fa-calendar-alt"></i>
                     <?=date("d", strtotime($salida[0]['fecha']));?>
                               </p>
                             <!--<p><?php //echo DevuelveFechaHorario($horarioId)[0][2]; ?></p>-->
                             <p class="mb-0">
                              <?=date("F", strtotime($salida[0]['fecha']));?>
                              <?=date("Y", strtotime($salida[0]['fecha']));?>
                               
                             </p>
                            </div>
                            <div class="col-lg-3 col-md-3">
                               <p class="mb-1  h3 text-gris"><i class="fa fa-clock"></i></p>
                               <p>      <?=$salida[0]['horaCheckIn'];?></p>
                            </div>
                          </div>
                          </div>
                        </div>
                  </div>
                </div>
                <div class="row">
              
                </div>
                <?php for ($k=0; $k < count($reserva); $k++) { 

        $tarifa=calculaTarifa($reserva[$k]["idServicioSalidasTarifas"],$reserva[$k]["cantidad"]);

                  $cantidadPasajeros=($reserva[$k]["cantidad"]);
                  for ($l=0; $l < $cantidadPasajeros; $l++) { 
                    # code...
                  
                 ?>
               <div class="card-body">
                <h5>Pasajero  <?= $tarifa[0]["nombre"].' ('.$tarifa[0]["edadFrom"].' a '.$tarifa[0]["edadTo"].' Años)'?></h5>
           <input type="hidden" name='reservas[<?=$i?>][<?=$k?>][idServicioSalidasTarifas]' value='<?=$reserva[$k]["idServicioSalidasTarifas"]?>'>
                      <input type="hidden" name='reservas[<?=$i?>][<?=$k?>][idServicioSeleccionado]' value='<?=$reserva[$k]["idServicioSeleccionado"]?>'>

                  <div class="form-row ">
                    <div class="form-group col-6">
                      <input type="text" class="form-control" placeholder="Nombre"  name="pasajero[<?=$i?>][<?=$k?>][<?=$l?>][0]" required>
                    </div>
                    <div class="form-group col-6">
                      <input type="text" class="form-control" placeholder="Apellido" name="pasajero[<?=$i?>][<?=$k?>][<?=$l?>][1]" required>
                    </div>
                  </div>

     

      
              
              </div>
  
                 <?php 
                }?>

     
              <?php }?>
 

       <div class="row">
                  <div class="col-md-12">
                    <div class="custom-control custom-checkbox mr-sm-2">
                      <input type="checkbox" class="custom-control-input" id="example<?=$i?>Check<?=$k?>" data-toggle="collapse" href="#collapseExample<?=$i?>comentario<?=$k?>" role="button" aria-expanded="false" aria-controls="collapseExample<?=$i?>comentario<?=$k?>">
                      <label class="custom-control-label" for="example<?=$i?>Check<?=$k?>">Comentarios al proveedor</label>
                    </div>
                    <div class="collapse mt-2" id="collapseExample<?=$i?>comentario<?=$k?>">
                   
                        <div class="form-group">
                        <textarea class="form-control"
                        name='reservas[<?=$i?>][0][comentario]'  placeholder="
Comentarios (opcional) - 0/300" id="exampleFormControlTextarea1" rows="3"></textarea>
                      </div>
                     
                    </div>
                  </div>
                </div>




              </div>
             </div>
            </div>
      

<?php


}

            ?>

           <!--FIN DATOS DE ACTIVIDADES-->

          <!--CONTENEDOR POLITICAS DE PRIVACIDAD-->
            <div class="container">
              <div class="row">
                <div class="col-lg-12">
                  <div class="custom-control custom-checkbox mr-sm-2">
                    <input type="checkbox" class="custom-control-input" id="customControlAutosizing" >
                    <label class="custom-control-label" for="customControlAutosizing">Acepto la <a href="privacy" target="_BLANK">política de privacidad</a> <a data-toggle="modal" data-target="#politicas"><i class="fa fa-exclamation-circle"></i></a> y las condiciones generales.</label>
                  </div>
                </div>
              </div>
            </div>
        <!--CONTENEDOR POLITICAS DE PRIVACIDAD-->

      </div>
      <!--FIN DATOS PERSONALES-->
    </div>

  </div>



<!--BOTON SIGUIENTE-->
    <div class="container mb-4">
      <div class="row">
        <div class="col-lg-8 col-md-8"></div>
        <div class="col-lg-4 col-md-4 col-12 text-right">
          <button class="btn btn-primary btn-lg btn-radius" style="width: 100% !important;">Continuar</a>
        </div>
      </div>
    </div>
<!--FIN BOTON SIGUIENTE-->

</section>
<!--FIN SECCION DATOS PERSONALES-->

</form>

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
           <h4 class="text-left"><small><span>METELEBRASIL</span> es una marca de RESERVARTE SL.</small></h4>
        </div>
      </div>
    </div>
  </section>





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
