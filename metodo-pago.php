<?php 
session_start();
include("admin/classes/salidas.php");
include("admin/classes/tarifas.php");
include("admin/classes/idiomas.php");
include("admin/classes/servicio.php");
  include("admin/classes/comisiones.php");
    include("admin/classes/edades.php");
    include("admin/classes/cancelaciones.php");
    include("admin/classes/servicios_adicionales.php");
if ($_SERVER['REQUEST_METHOD'] == 'POST'){




}

$totalCarrito=0;

include ("sistema/functions.php");



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
          <li><span>1</span> <b><?=$lang["revisa_tus_reservas"]?></b></li>
          <li><span>2</span> <strong><?=$lang["datos_personales"]?></strong></li>
          <li class="active"><span>3</span> <strong><?=$lang["metodo_de_pago"]?></strong></li>
        </ul>
      </div>
    </div>
  </div>
</section>
<!--FIN PASOS PARA RESERVA-->

<!--SECCION DATOS PERSONALES-->
<!--SECCION DATOS PERSONALES-->
<section>

  <div class="container">
    <div class="row">

        <!--RESUMEN DE PEDIDO-->
      <div class="col-lg-4 col-md-4">
        <div class=" py-3">
             <div class="card card-visitas ">
              <div class="card-body">
                 <h5><?=$lang["resumen"]?><a  class="float-right"><small> </small></a></h5>
                 <!--ACORDEON CARACTERISTICAS-->
                  <div class="accordion" id="faq1">
                 <div class="card card-accordion">
                    <div class="" id="headingOne">
                      <h5 class="mb-0">
                        <a class="btn btn-accordion text-primary bg-white " data-toggle="collapse" data-target="#collapseOne2" aria-expanded="true" aria-controls="collapseOne">
                          <p> <small class="float-right"></small></p>

                        </a>
                      </h5>
                    </div>

    <div id="collapseOne2" class="collapse show" aria-labelledby="headingOne" data-parent="#faq1">  
  <ul class="lista-caracteristicas-r mx-4">
      <?php 
         
$precioTotalCarrito=0;

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
           $salida=getSalida($tarifa[0]['idServicioSalidas']);
$monedaNativa=($salida[0]["idMoneda"]);
          $precioReserva+=$tarifa[0]["valor"];
          if ($j==0) {
            ?>
            <li><?=$servicio[0]["nombre_servicio"];?><li>
            <?php
          }
      ?>
    <li><?= $cantidad.' '.$tarifa[0]["nombre"].' ('.$tarifa[0]["edadFrom"].' a '.$tarifa[0]["edadTo"].' Anos)'?><li>
    <li>Subtotal <?= $tarifa[0]["valorSinIvaSym"]; ?><li>
      <li>ISS <?= $tarifa[0]["valorDeIvaSym"]; ?><li>
      <?php
$precioTotalCarrito+=$tarifa[0]["valor"];

      }
      

echo("<li>comision Vendedor". $_SESSION['moneda_sel_sym']."".$tarifa[0]["comisionVendedor"].'</li>');
echo("<li>comision Sistema". $_SESSION['moneda_sel_sym']."".$tarifa[0]["comisionSistema"].'</li>');
?>
   <li>Subtotal Reserva <?= $_SESSION['moneda_sel_sym']."".$precioReserva;?></li>
   
<?php

if (count($reservaAdicionales)>0) {
 ?>
 <li>Adicionales: </li>
 <?php
}
for ($j=0; $j < count($reservaAdicionales); $j++) { 
  $idServicioSalidasAdicionales=$reservaAdicionales[$j]["idServicioSalidasAdicionales"];
 $servicioAdicional= getServicioAdicionalSalida($idServicioSalidasAdicionales);
 $cantidad=$reservaAdicionales[$j]["cantidad"];
 
 $valor=getValorServiciosAdicionalesSalida($idServicioSalidasAdicionales,$cantidad);

 $precioTotalCarrito+=$valor[0]["valor"];
  ?>
    <li><?=$cantidad;?> <?=$servicioAdicional[0]["nombre"]?> (X PAX <?=$valor[0]["valorSymUnitario"];?>)</li>
  <?php
 }
 ?>
  <hr>
 <?php
} 


$totalAPagar=$precioTotalCarrito; ?>




     

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

      <!--DATOS DE PAGO-->
      <div class="col-lg-8 col-md-8">
        <!--METODOS DE PAGO-->
        <div class=" py-3">
             <div class="card card-visitas" >
              <div class="card-body">
                 <h5 class="mb-4" id="textoMetodoDePago">Divisa</h5>
                 <!--FORM DE PAGO-->
                <form class="datos-p" id="divMetodosDePago">
                  <div class="container paymentCont mb-4">
                      <div class="row paymentWrap">
                   <div class="btn-group col-lg-12">
                    <!--AQUI VA LA CARGA DE DIVISA-->
                      <label class="btn btn-primary paymentMethod texto-moneda  " id="euros">
                      <i class="fa fa-dollar-sign"> </i>
                            <small> Reales</small>
                          <?= ConvierteMoneda($monedaNativa,283, $precioTotalCarrito); ?>
                      </label>
                      <label class="btn btn-primary paymentMethod texto-moneda" id="pesos">

                        <i class="fa fa-dollar-sign"></i>
                        <small> Pesos ARG</small>
                          <?=  ConvierteMoneda($monedaNativa,270, $precioTotalCarrito); ?>
                      </label>
                      <label class="btn btn-primary paymentMethod texto-moneda" id="dolar">
                       <i class="fa fa-dollar-sign"></i>
                         <small> Dolares</small>
                            <?=  ConvierteMoneda($monedaNativa,188, $precioTotalCarrito); ?>
                      </label>
                      <!--FIN AQUI VA LA CARGA DE DIVISA-->
                      </div>
                </div>
              </div>
                       <?php
$total=ConvierteMoneda($monedaNativa,270, $precioTotalCarrito);
$totalPayPal=ConvierteMoneda($monedaNativa,188, $precioTotalCarrito);
//include("sistema/mercadopago/procesaPago.php");
//include("sistema/query.php");
?>

  <script
    src="https://www.paypal.com/sdk/js?client-id=AY_f6DGMcccB4MHNGbvKcJsDN-3V0jw_45N9PVuM3apECjAqy1GtrZF413qTZeLzYGusTC2Uc3wz7W2D"> // Required. Replace SB_CLIENT_ID with your sandbox client ID.
  </script>




  <script>
    var totalPayPal = '<?= $totalPayPal ?>';
   var total = '<?= $totalAPagar ?>';
      var idReserva = 001;
   if (total<=1) {
$("#divMetodosDePago").html("<h1>Felicitaciones, tu reserva esta confirmada!!!</h1>");


   }
   else{$(
    "#textoMetodoDePago").html("Divisa");
 }


paypal.Buttons({
    createOrder: function(data, actions) {
      // This function sets up the details of the transaction, including the amount and line item details.
      return actions.order.create({
        purchase_units: [{
           "reference_id": idReserva,
        "custom_id": idReserva,
          amount: { value: totalPayPal, currency: 'USD'},
          description: "Reserva en metelebrasil.com"
        }]
      });
    },
    onApprove: function(data, actions) {
      // This function captures the funds from the transaction.
      return actions.order.capture().then(function(details) {
        // This function shows a transaction success message to your buyer.
             alert('Gracias por pagar en metelebrasil '+ details.payer.name.given_name+' el pago de paypal puede demorar unos segundos en impactar en el sistema, Gracias');
        window.location='./consultaReserva.php?id='+ idReserva;
      });
    }
  }).render('#paypal-button-container');
  //This function displays Smart Payment Buttons on your web page.
</script>




                  <h5 id="textoMetodoDePago"class="mb-4"></h5>
                   <div class="container paymentCont mb-4">
                      <div class="row paymentWrap">
                   <div class="btn-group col-lg-12 paymentBtnGroup " data-toggle="">
                    <!--FIN AQUI VA LA CARGA DE METODOS DE PAGO-->

                      <label class="btn btn-primary paymentMethod" id="mercadopago" style="display:none">
                     <div class="method paypal">

              <div class="method mercadopagoArgentina" id="method mercadopagoArgentina">

               
            </div>




                        </div>


                      </label>
                       <label class="btn btn-primary paymentMethod " id="ebanxs" >
               <div class="method ebanxs" >


<?php //aainclude("sistema/ebanx/ebanx.php"); ?>


            </div>    </label>
        <label class="btn btn-primary paymentMethod" id="paypal"  style="display:none"><!--//****************comienza paypal-->
        <div class="method paypal">
 <div id="paypal-button-container"></div>

<script type="text/javascript">
  function validaRecibo() {
   if (confirm("Realmente desea cobrar??")) {
return true;

   }
   else
   {
    return false;
   }
  }

</script>
   </div>
        </label> <!--//****************termina paypal-->
                      <!--FIN AQUI VA LA CARGA DE METODOS DE PAGO-->


                      <!--FIN AQUI VA LA CARGA DE METODOS DE PAGO-->

                  </div>
            </div>
          </div>
                </form>
                 <!--FIN FORM DE PAGO-->
                  <?php

         if(isset($_SESSION["login"]["idCobrador"]) && $_SESSION["login"]["idCobrador"]>1 )
{
 ?>
            <hr></hr>
<form method="post" action="recibePago.php" onsubmit="return validaRecibo();">

          <div class="method vendedor">><?=$lang["cobro_en_mano"]?>
 <div id="divVendedor">
<input type="number" step="0.01" name="dinero" width="5">
<input type="hidden" name="idReserva" value="'.$idReserva.'">
<select name="moneda">
<option value="283">Reales</option>
<option value="270">Peso Arg</option>
<option value="188">Dolares</option>
</select><br><br>
<button type="submit" class="btn btn-primary btn-lg btn-radius"><?=$lang["cobro_de_sena"]?></button>
</form>
 </div>

          </div>
                      <?php
}


         ?>
              </div>
            </div>
          </div>
           <!--FIN METODOS DE PAGO-->


      </div>
      <!--FIN DATOS DE PAGO-->
    </div>

  </div>

   <!--BOTON SIGUIENTE-->
<?php
if ($totalAPagar>0) {
  echo '


    <div class="container py-4">
      <div class="row">
        <div class="col-lg-8 col-md-8"></div>
        <div class="col-lg-4 col-md-4 col-12 text-right">
          <a href="#" class="btn btn-primary btn-lg btn-radius" id="btnPagar" style="width: 100% !important;">Pagar</a>
        </div>
      </div>
    </div>



  ';
 } ?>
<!--FIN BOTON SIGUIENTE-->

</section>
<!--FIN SECCION DATOS PERSONALES-->



  <!-- Footer -->
    <!-- Footer -->
  <footer class="footer footer-reserva ">
    <div class="container">
      <div class="row">
        <div class="col-lg-4"></div>
        <div class="col-lg-2">
         <p class="text-gris text-pagos"> <i class="fa fa-lock mx-2 "></i><?=$lang["pago_seguro"]?></p>
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
           <h4 class="text-left"><small><span>METELE BRASIL</span></i><?=$lang["es_una_marca_registrada_de_reservate_sl"]?></small></h4>
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
  <script type="text/javascript">
    //por default arrancamos en reales
     $("#euros").css('background',' #029ce2'); //pinta
      $("#euros").css('color',' #fff '); //pinta
      $("#paypal").css('display','block');
      $("#mercadopago").css('display','none');
      $("#ebanxs").css('display','none');
         $("#ebanxs").css('color',' #929292 ');  //despinta

    $("#euros").click(function(){
      $("#paypal").css('display','block');
      $("#mercadopago").css('display','none');
      $("#euros").css('background',' #029ce2'); //pinta
      $("#euros").css('color',' #fff '); //pinta
      $("#pesos").css('background',' #fff'); //despinta
      $("#pesos").css('color',' #929292 ');  //despinta
      $("#dolar").css('background',' #fff'); //despinta
      $("#dolar").css('color',' #929292 ');  //despinta
         $("#ebanxs").css('color',' #929292 ');  //despinta
         $("#ebanxs").css('display','none');

    });

     $("#dolar").click(function(){
      $("#paypal").css('display','block');
      $("#mercadopago").css('display','none');

      $("#dolar").css('background',' #029ce2'); //pinta
      $("#dolar").css('color',' #fff '); //pinta
      $("#pesos").css('background',' #fff'); //despinta
      $("#pesos").css('color',' #929292 ');  //despinta
      $("#euros").css('background',' #fff'); //despinta
      $("#euros").css('color',' #929292 ');  //despinta
         $("#ebanxs").css('color',' #929292 ');  //despinta
         $("#ebanxs").css('display','block');


    });

    $("#pesos").click(function(){
      $("#mercadopago").css('display','block');
      $("#paypal").css('display','none');

      $("#pesos").css('background',' #029ce2'); //pinta
      $("#pesos").css('color',' #fff '); //pinta
      $("#dolar").css('background',' #fff'); //despinta
      $("#dolar").css('color',' #929292 ');  //despinta
       $("#euros").css('background',' #fff'); //despinta
      $("#euros").css('color',' #929292 ');  //despinta
          $("#ebanxs").css('color',' #929292 ');  //despinta
          $("#ebanxs").css('display','block');
    });
var mercadoPagoLink= '<?= $preference->init_point;
?>';
var ebanxsLink= '<?= $urlEbanxs;?>';


          $("#paypal").click(function(){
 $("#mercadopago").css('border','  none '); //pinta
 $("#paypal").css('border','  4px solid  #029ce2 '); //pinta
 $("#ebanxs").css('border','  none '); //pinta

           });


      $("#mercadopago").click(function(){
 $("#paypal").css('border','  none '); //pinta
 $("#mercadopago").css('border','  4px solid  #029ce2 '); //pinta
  $("#ebanxs").css('border','  none '); //pinta
$("#btnPagar").attr("href",mercadoPagoLink);

      });


      $("#ebanxs").click(function(){
 $("#mercadopago").css('border','  none '); //pinta
 $("#ebanxs").css('border','  4px solid  #029ce2 '); //pinta
$("#btnPagar").attr("href",ebanxsLink);

      });
  </script>
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

  <!-- CALENDARIO -->
  <script src="js/clndr.min.js"></script>
  <!-- CALENDARIO -->

  <!-- BOOTSTRAP BUNDLE -->
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- BOOTSTRAP BUNDLE -->

  <!-- JQUERY EASING -->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
  <!-- JQUERY EASING -->

  <!-- CUSTOM -->
  <script src="js/script.js"></script>
  <!-- CUSTOM -->

<!-- FIN SCRIPTS NECESARIOS-->

</body>

</html>
