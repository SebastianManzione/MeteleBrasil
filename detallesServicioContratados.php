  

<?php 




include("includes/headPagos.php");
include("admin/classes/salidas.php");

include("admin/classes/tarifas.php");

include("admin/classes/idiomas.php");

include("admin/classes/servicio.php");

include("admin/classes/functions.php");
  include("admin/classes/comisiones.php");

    include("admin/classes/edades.php");

    include("admin/classes/cancelaciones.php");

    include("admin/classes/servicios_adicionales.php");

include("admin/classes/reserva.php");

include("admin/classes/comprobantes.php");

include("admin/classes/moneda.php");

include("admin/classes/convierte_monedas.php");



if ($_SERVER["REQUEST_METHOD"]=="GET") {



$codigoAmigable=$_GET["reserva"];



$reserva=getReserva($codigoAmigable)[0];

$idReserva=$reserva["idReserva"];

 $moneda=getMoneda($reserva["monedaSel"])[0]["Symbol"];



}
if (count($reserva)<1) {
  alertar("La Reserva con el codigo ".$codigoAmigable." no existe","error");
  redireccionarLento("index");
}


 ?>










<!--HEADER PAGO SEGURO-->



<section class="py-2 bg-primary">



  <div class="container">

    <div class="row">

      <div class="col-lg-2 col-6">

         <a href="index" class="navbar-brand text-white" ><h3>METELE BRASIL</h3></a>

      </div>

      <div class="col-lg-10 col-6">

        <p class="text-white text-pagos mb-0"> <i class="fa fa-lock mx-2 "></i><?=$lang["pago_seguro"]?></p>

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

      <li class="active"> <strong></i><?=$lang["consulta_de_reserva"]?><?= $codigoAmigable;?></strong></li>

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



                  <!--de aca le saque class="collapse"-->  <div id="collapseOne2" class=" " aria-labelledby="headingOne" data-parent="#faq1">

                    <ul  class="lista-caracteristicas-r mx-4">

         

<?php  



$horarios=getReservaHorarios($idReserva);

for ($i=0; $i < count($horarios); $i++) { 



 $servicio= getServicio($horarios[$i]["idServicioSeleccionado"]);

 $idReservaHorarios=$horarios[$i]["idReservaHorarios"];

 $reservaTarifas=getReservaTarifas($idReservaHorarios);

 $adicionales=getReservaAdicionalesNoIncluidos($idReservaHorarios);

 //print_r($adicionales);

 //print_r($horarios[$i]);

 ?>

<li><?=$servicio[0]["nombre_servicio"];?></li>

<li><?=date("d/m/Y", strtotime($horarios[$i]['fecha']))." Check IN: ".substr($horarios[$i]["horaCheckIn"], 0,5);?></li>



 <?php

 for ($j=0; $j < count($reservaTarifas); $j++) { 

   # code...

 

 $edadFrom=getEdad($reservaTarifas[$j]["idFromEdad"]);

 $edadTo=getEdad($reservaTarifas[$j]["idToEdad"]);

 $idMonedaSel=$reservaTarifas[$j]["monedaSel"];

  $moneda=getMoneda($idMonedaSel)[0]["Symbol"];





 ?>





<li><?=$reservaTarifas[$j]["cantidad"];?> <?=$reservaTarifas[$j]["nombre"];?> ( <?= $edadFrom[0]["valor"]?> A <?= $edadTo[0]["valor"]?> Anos)</li>

  <li>Subtotal <?= $moneda. $reservaTarifas[$j]["valorSinIva"]; ?></li>

<li>IVA <?= $moneda. $reservaTarifas[$j]["valorDeIva"]; ?><li>



 <?php

}?>





<?php

for ($j=0; $j < count($adicionales); $j++) { 

  ?> 

<li><?= $adicionales[$j]["cantidad"];?> <?= $adicionales[$j]["nombre"];?> <?= $moneda.$adicionales[$j]["precioIva"];?></li>



  <?php

}?>

<hr>

<?php

}

 ?>







                      

                            </ul>

        

                    </div>

                  </div>

              </div>

                 <!--FIN ACORDEON CARACTERISTICAS-->

                <hr class="hr-puntuada">

                 <!--PRECIO TOTAL-->



                 <div class="div-precio-t">

                   <p class="mb-0 float-left"><strong><?=$lang["precio_total"]?></strong></p>

<p class="mb-0 float-right"><strong><?= $moneda." ".$reserva["total"];?></strong></p>

                 </div>



                 <!--FIN PRECIO TOTAL-->



              </div>

            </div>

          </div>

      </div>

      <!--FIN RESUMEN DE PEDIDO-->



      <!--DATOS DE PAGO-->

      <?php 

$comprobantes= getComprobantesIdReservaDolar($idReserva);

$total_dolares=$reserva["total_dolares"];



if ($comprobantes<$total_dolares) {

     ?>

      <div class="col-lg-8 col-md-8">



        <!--METODOS DE PAGO-->

        <div class=" py-3">





             <div class="card card-visitas" id="cardVisitas">



              <div class="card-body">





                 <h5 class="mb-4" id="textoMetodoDePago">Divisa</h5>

                 <!--FORM DE PAGO-->

                <form class="datos-p" id="divMetodosDePago">

                  <div class="container paymentCont mb-4">

                      <div class="row paymentWrap">

                   <div class="btn-group col-lg-12">

                    <!--AQUI VA LA CARGA DE DIVISA-->

                    <?php 

$reales=convierteMoneda($idMonedaSel,283,$reserva["total"]);

$pesos_argentinos=convierteMoneda($idMonedaSel,270,$reserva["total"]);

$dolares=convierteMoneda($idMonedaSel,188,$reserva["total"]);

                     ?>

                      <label class="btn btn-primary paymentMethod texto-moneda  " id="reales">

                      <i class="fa fa-dollar-sign"> </i>

                            <small> Reales</small>

                          <?=$reales;?>

                      </label>

                      <label class="btn btn-primary paymentMethod texto-moneda" id="pesos">

                        

                        <i class="fa fa-dollar-sign"></i>

                        <small> Pesos ARG</small>

                          <?=$pesos_argentinos?>

                      </label>

                      <label class="btn btn-primary paymentMethod texto-moneda" id="dolar">

                       <i class="fa fa-dollar-sign"></i>

                         <small> Dolares</small>

                          <?=$dolares;?>

                      </label>

                      <!--FIN AQUI VA LA CARGA DE DIVISA-->

                      </div>        

                </div>

              </div>                    

                       <?php 

$total=0;//0ConvierteMoneda($monedaNativa,270, $totalAPagar);

$totalMercadopagoArgentina=convierteMoneda($idMonedaSel,270,$reserva["total"]);

$totalMercadopagoBrasil=convierteMoneda($idMonedaSel,283,$reserva["total"]);

$totalPayPal=convierteMoneda($idMonedaSel,188,$reserva["total"]);

include("./admin/pasarelas/mercadopagoArgentina/procesaPago.php");

include("./admin/pasarelas/mercadopagoBrasil/procesaPago.php");

//include("../query.php");

?>



  <script

    src="https://www.paypal.com/sdk/js?client-id=AbVzfexjVQ8bKuBQEkVem15h-IbQI4liLK_L9COokXJJvBdmj_JFReUBSOROn2DTxauOqRpmyvJVm6l9"> // Required. Replace SB_CLIENT_ID with your sandbox client ID.

  </script>



  <script>

    var totalPayPal = '<?= $totalPayPal ?>';

   var idReserva = '<?= $idReserva ?>';

    var codigoAmigable = '<?=$codigoAmigable ?>';

    



    $("#textoMetodoDePago").html("Divisa");





    paypal.Buttons({

    createOrder: function(data, actions) {

      // This function sets up the details of the transaction, including the amount and line item details.

      return actions.order.create({

        purchase_units: [{

           "reference_id": idReserva,

        "custom_id": codigoAmigable,

          amount: { value: totalPayPal, currency: 'USD'},

          description: "Reserva en metelebrasil.com",

          notify_url : "https://metelebrasil.com/admin/pasarelas/PayPal/notificaciones.php"

        }]

      });

    },

    onApprove: function(data, actions) {

      // This function captures the funds from the transaction.

      return actions.order.capture().then(function(details) {

        // This function shows a transaction success message to your buyer.

             alert('Gracias por pagar en metelebrasil '+ details.payer.name.given_name+' el pago de paypal puede demorar unos segundos en impactar en el sistema, Gracias');

        window.location='./consultaReserva.php?reserva='+codigoAmigable;

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

              <div class="method mercadopagoArgentina" >





            </div>



       





                        </div>

                      </label>

                   





        <label class="btn btn-primary paymentMethod" id="mercadopagoBrasil" style="display:none">

                     <div class="method paypal">

              <div class="method mercadopagoArgentina" >





            </div>



       





                        </div>

                      </label>





        <label class="btn btn-primary paymentMethod" id="paypal"  style="display:none">

                       



                          

          <div class="method paypal">

 <div id="paypal-button-container"></div>  







      

                    

                      <!--FIN AQUI VA LA CARGA DE METODOS DE PAGO-->













          </div> 

                      </label>

                      <!--FIN AQUI VA LA CARGA DE METODOS DE PAGO-->

 

                  </div>        

            </div>

          </div>

                </form>

                 <!--FIN FORM DE PAGO-->

              </div>

            </div>



 <?php  if (isset($_SESSION["login"]["idCobrador"])) {

  



         if($_SESSION["login"]["idCobrador"]>0)

      {

?>  







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

<br><div class="card card-visitas method vendedor" >





 <div class="card-body" id="divVendedor" >      

     <h5 class="mb-4" id="textoMetodoDePago"><?=$lang["cobro_en_mano"]?></h5>             

<form method="post" action="recibePago.php" onsubmit="return validaRecibo();">

<input type="number" step="0.01" name="dinero" width="5"/>

<input type="hidden" name="idReserva" value="<?=$idReserva?>"/>

<select name="moneda">

<option value="283">Reales</option>

<option value="270">Peso Arg</option>

<option value="188">Dolares</option>

</select><br><br>

<button type="submit" class="btn btn-primary btn-lg btn-radius"><?=$lang["cobro_de_sena"]?></button>

</form>

 </div>

  </div> 

  <br>

 <?php

      }

}

          ;?>   





          </div>

           <!--FIN METODOS DE PAGO-->



          

      </div>



    <?php } else{ // ($comprobantes<$total_dolares) {

      ?>

    <div class="col-lg-8 col-md-8">



        <!--METODOS DE PAGO-->

        <div class=" py-3">

                  <div class="card card-visitas" id="cardVisitas">



              <div class="card-body">





                 <h3 class="mb-4" id="textoMetodoDePago">Servicios Contratados</h5>

<h5 class="success">Paseo de Barco R$100</h3>

<h5 class="success">Paseo de Barco R$100</h3>

<h5 class="success">Paseo de Barco R$100</h3>

<h5 class="success">Paseo de Barco R$100</h3>
S



        




</div></div></div>


<br>


<div class="col">
          <a href="#" class="btn btn-primary btn-lg btn-radius" id="btnPagar" style="width: 100% !important;"><?=$lang["volver_al_site"];?></a>
       


</div>


<br>


 <?php   }?>

      <!--FIN DATOS DE PAGO-->

    </div>



  </div>



   <!--BOTON SIGUIENTE-->




<?php 



if ($comprobantes<$total_dolares) {

?>





    <div class="container py-4">

      <div class="row">

        <div class="col-lg-8 col-md-8"></div>

        <div class="col-lg-4 col-md-4 col-12 text-right">

          <a href="#" class="btn btn-primary btn-lg btn-radius" id="btnPagar" style="display: none; width: 100% !important;"><?=$lang["pagar"]?></a>

        </div>

      </div>

    </div>







<?php

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

           <h4 class="text-left"><small><span>METELE BRASIL</span><?=$lang["es_una_marca_registrada_de_reservate_sl"]?></small></h4>

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

     $("#reales").css('background',' #029ce2'); //pinta

      $("#reales").css('color',' #fff '); //pinta

      $("#paypal").css('display','none');

      $("#mercadopago").css('display','none');

        $("#mercadopagoBrasil").css('display','block');

$("#btnPagar").attr("href",mercadoPagoLinkBrasil);

$("#btnPagar").css("display","block");





    $("#reales").click(function(){

      $("#paypal").css('display','none');

      $("#mercadopago").css('display','none');

      $("#mercadopagoBrasil").css('display','block');

      $("#reales").css('background',' #029ce2'); //pinta

      $("#reales").css('color',' #fff '); //pinta

      $("#pesos").css('background',' #fff'); //despinta

      $("#pesos").css('color',' #929292 ');  //despinta

      $("#dolar").css('background',' #fff'); //despinta

      $("#dolar").css('color',' #929292 ');  //despinta

      $("#btnPagar").attr("href",mercadoPagoLinkBrasil);

$("#btnPagar").css("display","block");

    });



     $("#dolar").click(function(){

      $("#paypal").css('display','block');

      $("#mercadopago").css('display','none');

      $("#mercadopagoBrasil").css('display','none');

      $("#dolar").css('background',' #029ce2'); //pinta

      $("#dolar").css('color',' #fff '); //pinta

      $("#pesos").css('background',' #fff'); //despinta

      $("#pesos").css('color',' #929292 ');  //despinta

      $("#reales").css('background',' #fff'); //despinta

      $("#reales").css('color',' #929292 ');  //despinta

$("#btnPagar").css("display","none");



    });

var mercadoPagoLink= '<?= $preference->init_point;?>';

var mercadoPagoLinkBrasil= '<?= $preferenceBr->init_point;?>';

    $("#pesos").click(function(){



      $("#mercadopago").css('display','block');

            $("#mercadopagoBrasil").css('display','none');

      $("#paypal").css('display','none');



      $("#pesos").css('background',' #029ce2'); //pinta

      $("#pesos").css('color',' #fff '); //pinta

      $("#dolar").css('background',' #fff'); //despinta

      $("#dolar").css('color',' #929292 ');  //despinta

       $("#reales").css('background',' #fff'); //despinta

      $("#reales").css('color',' #929292 ');  //despinta

      $("#btnPagar").css("display","block");

      $("#btnPagar").attr("href",mercadoPagoLink);

    });





          $("#paypal").click(function(){

 $("#mercadopago").css('border','  none '); //pinta

 $("#paypal").css('border','  4px solid  #029ce2 '); //pinta

       $("#mercadopagoBrasil").css('display','none');

$("#btnPagar").css("display","none");

   

           });





      $("#mercadopago").click(function(){

 $("#paypal").css('border','  none '); //pinta

 $("#mercadopago").css('border','  4px solid  #029ce2 '); //pinta

       $("#mercadopagoBrasil").css('display','none');

$("#btnPagar").attr("href",mercadoPagoLink);



      });



           $("#mercadopagoBrasil").click(function(){

         

 $("#paypal").css('border','  none '); //pinta

 $("#mercadopago").css('border','  4px solid  #029ce2 '); //pinta

$("#btnPagar").attr("href",mercadoPagoLinkBrasil);



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

