  



<?php 





include("includes/headPagos.php");

include("admin/classes/salidas.php");

include("admin/classes/tarifas.php");

include("admin/classes/idiomas.php");

include("admin/classes/servicio.php");

include("admin/classes/comisiones.php");

include("admin/classes/edades.php");

include("admin/classes/cancelaciones.php");

include("admin/classes/servicios_adicionales.php");

include("admin/classes/reserva.php");

include("admin/classes/comprobantes.php");

include("admin/classes/moneda.php");

include("admin/classes/convierte_monedas.php");

if ($_SERVER["REQUEST_METHOD"]=="GET" && isset($_GET["merchant_payment_code"])) {

$codigoAmigable=$_GET["merchant_payment_code"];

$reserva=getReserva($codigoAmigable)[0];

$idReserva=$reserva["idReserva"];

 $moneda=getMoneda($reserva["monedaSel"])[0]["Symbol"];



}

if ($_SERVER["REQUEST_METHOD"]=="GET" && isset($_GET["reserva"])) {







$codigoAmigable=$_GET["reserva"];



$reserva=getReserva($codigoAmigable);

if (count($reserva)<1) {

  alertar($lang["la_reserva_con_el_codigo"]." ".$codigoAmigable." ".$lang["no_existe"],"error");

  redireccionarLento("index");

  exit();

}





$reserva=getReserva($codigoAmigable)[0];



$idReserva=$reserva["idReserva"];



 $moneda=getMoneda($reserva["monedaSel"])[0]["Symbol"];







}







$comprobantes= getComprobantesIdReservaDolar($idReserva);



$total_dolares=$reserva["total_dolares"];

$comprobantes225=convierteMoneda(188,225,$comprobantes);

$comprobantes270=convierteMoneda(188,270,$comprobantes);

$comprobantes271=convierteMoneda(188,271,$comprobantes);

$comprobantes283=convierteMoneda(188,283,$comprobantes);



 ?>





<!--PASOS PARA RESERVA-->



<section class="py-2 bg-white">



  <div class="container">



    <div class="row">



      <div class="col-lg-12  ">



        <ul class="lista-pasos-form">



      <li class="active"> <strong></i><?=$lang["consulta_de_reserva"]?> <?= $codigoAmigable;?></strong></li>



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

 ?>

<li><?=$servicio[0]["nombre_servicio"];?></li>

<li><?=date("d/m/Y", strtotime($horarios[$i]['fecha']))." Check IN: ".substr($horarios[$i]["horaCheckIn"], 0,5);?></li>

 <?php



 for ($j=0; $j < count($reservaTarifas); $j++) { 

 $edadFrom=getEdad($reservaTarifas[$j]["idFromEdad"]);

 $edadTo=getEdad($reservaTarifas[$j]["idToEdad"]);

 $idMonedaSel=$reservaTarifas[$j]["monedaSel"];

  $moneda=getMoneda($idMonedaSel)[0]["Symbol"];



 ?>



<li><?=$reservaTarifas[$j]["cantidad"];?> <?=$reservaTarifas[$j]["nombre"];?> ( <?= $edadFrom[0]["valor"]?> A <?= $edadTo[0]["valor"]?> Anos)</li>

<li>ISS <?= $moneda. $reservaTarifas[$j]["valorDeIva"]; ?><li>

  <li>Subtotal <?= $moneda. ($reservaTarifas[$j]["valorSinIva"]+$reservaTarifas[$j]["valorDeIva"]); ?></li>



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



 <?php if ($comprobantes>0) {

  ?>

<li>Total <?=  $moneda.convierteMoneda(188,$idMonedaSel,$reserva["total_dolares"]);?></li>

  <?php

  $totalComprobantesAMostrar=$comprobantes;

  $diferenciaAPagar=$reserva["total_dolares"]-$totalComprobantesAMostrar;

if ($diferenciaAPagar< 1) {

  $diferenciaAPagar=0;

}



   ?>



<li>Pagamento realizado <?=  $moneda.convierteMoneda(188,$idMonedaSel,$totalComprobantesAMostrar)?></li>

   <?php

 } else{

    $diferenciaAPagar=$reserva["total_dolares"]-$totalComprobantesAMostrar;

  ?>

<li>Total <?=  $moneda.convierteMoneda(188,$idMonedaSel,$reserva["total_dolares"]);?></li>



  <?php

 }?>

                          </ul>



        



                    </div>



                  </div>



              </div>



                 <!--FIN ACORDEON CARACTERISTICAS-->



                <hr class="hr-puntuada">



                 <!--PRECIO TOTAL-->







                 <div class="div-precio-t">



                   <p class="mb-0 float-left"><strong>Resta pagar</strong></p>



<p class="mb-0 float-right"><strong><?= $moneda." ".convierteMoneda(188,$idMonedaSel,$diferenciaAPagar);?></strong></p>



                 </div>







                 <!--FIN PRECIO TOTAL-->







              </div>



            </div>



          </div>



      </div>



      <!--FIN RESUMEN DE PEDIDO-->







      <!--DATOS DE PAGO-->



      <?php 







if ($comprobantes<$total_dolares) {



     ?>



      <div class="col-lg-8 col-md-8">







        <!--METODOS DE PAGO-->







<?php 

   $countryEbanx='';

   $currencyEbanx='';

   $habilita_pesos_arg='';

   $habilita_reales='';

   $habilita_pesos_ch='';

  $habilita_guaranies='';

  $habilita_dolares='';

    $habilita_soles_peruanos='';





     $habilita_ebanx='';

switch ($_SESSION['geo']['countryCode']) {

  case 'AR':

   $habilita_reales=false;

   $habilita_pesos_arg=true;

   $habilita_pesos_ch=false;

   $countryEbanx='AR';

   $currencyEbanx='ARS';

   $habilita_ebanx=true;

     $_SESSION['geo']['nombre_pais']="Argentina";

   $totalEbanx=convierteMoneda(188,270,$reserva["total_dolares"])-$comprobantes;





    break;

    case 'BR':

   $habilita_reales=true;

   $habilita_pesos_arg=false;

   $habilita_pesos_ch=false;

   $countryEbanx='BR';

   $currencyEbanx='BRL';



   $totalEbanx=convierteMoneda(188,283,$reserva["total_dolares"])-$comprobantes;

   $habilita_ebanx=false;

  $_SESSION['geo']['nombre_pais']='Brasil';



    break;

    case 'CL':

   $habilita_reales=false;

   $habilita_pesos_arg=false;

   $habilita_pesos_ch=true;

   $countryEbanx='CL';

   $currencyEbanx='CLP';

   $totalEbanx=convierteMoneda(188,271,$reserva["total_dolares"])-$comprobantes;

   $habilita_ebanx=true;

  $_SESSION['geo']['nombre_pais']='Chile';



    break;



  case 'UY':

   $habilita_dolares=true;

   $countryEbanx='UY';

   $currencyEbanx='USD';

   $totalEbanx=convierteMoneda(188,188,$reserva["total_dolares"])-$comprobantes;

   $habilita_ebanx=true;

  $_SESSION['geo']['nombre_pais']='Uruguay';



    break;

  case 'PE':

 $habilita_dolares=true;

   $countryEbanx='PE';

   $currencyEbanx='USD';

   $totalEbanx=convierteMoneda(188,188,$reserva["total_dolares"])-$comprobantes;

   $habilita_ebanx=true;

  $_SESSION['geo']['nombre_pais']='Peru';



    break;  



    case 'PY':

  

   $habilita_guaranies=true;

   $countryEbanx='PY';

   $currencyEbanx='USD';

   $totalEbanx=convierteMoneda(188,225,$reserva["total"])-$comprobantes;

   $habilita_ebanx=true;

  $_SESSION['geo']['nombre_pais']='Paraguay';



    break;

  default:

    // code...

    break; }?>





             



        <div class=" py-3">

    <b class="mb-4">Pago desde <?=$_SESSION['geo']['nombre_pais']?> <a href="monedasPago?reserva=<?=$codigoAmigable?>" class="btn">Cambiar Pais</a></b>









             <div class="card card-visitas" id="cardVisitas">







              <div class="card-body">

                 <!--FORM DE PAGO-->



                <form class="datos-p" id="divMetodosDePago">



                  <div class="container paymentCont mb-4">



                      <div class="row paymentWrap">



                   <div class="btn-group col-lg-12">



                    <!--AQUI VA LA CARGA DE DIVISA-->



                    <?php 

                     

$reales=convierteMoneda(188,283,$diferenciaAPagar);

$pesos_argentinos=convierteMoneda(188,270,$diferenciaAPagar);

$pesos_chilenos=convierteMoneda(188,271,$diferenciaAPagar);

$dolares=$diferenciaAPagar;

$guaranies=convierteMoneda(188,225,$reserva["total_dolares"])-$comprobantes;

                     ?>

<?php if($habilita_reales){ ?>



         <label class="btn btn-primary paymentMethod texto-moneda  " id="reales">


R$



                          <?=$reales;?>



                      </label>



  <?php } ?>

       <?php if($habilita_soles_peruanos){ ?>

                      <label class="btn btn-primary paymentMethod texto-moneda" id="pesos">



                        



                        <i class="fa fa-dollar-sign"></i>



                        <small> Soles Peruanos</small>



                          <?=$soles_peruanos;?>



                      </label>

<?php } ?>  

     <?php if($habilita_pesos_ch){ ?>

                      <label class="btn btn-primary paymentMethod texto-moneda" id="pesos">



                        



                        <i class="fa fa-dollar-sign"></i>



                        <small> Pesos CH</small>



                          <?=$pesos_chilenos;?>



                      </label>

<?php } ?>        

<?php if($habilita_pesos_arg){ ?>

                      <label class="btn btn-primary paymentMethod texto-moneda" id="pesos">



                        



                        <i class="fa fa-dollar-sign"></i>






                         <?=convierteMoneda(188,270,$diferenciaAPagar);?>



                      </label>

<?php } ?>



<?php if($habilita_guaranies){ ?>

                      <label class="btn btn-primary paymentMethod texto-moneda" id="pesos">



                        



                        <i class="fa fa-dollar-sign"></i>



                        <small> Guaranies</small>



                          <?=$guaranies?>



                      </label>

<?php } ?>

<?php if($habilita_dolares){ ?>

                      <label class="btn btn-primary paymentMethod texto-moneda" id="dolar">



                       <i class="fa fa-dollar-sign"></i>



                         <small> Dolares</small>



                          <?=$dolares;?>



                      </label>

<?php } ?>

                      <!--FIN AQUI VA LA CARGA DE DIVISA-->



                      </div>        



                </div>



              </div>                    



                       <?php 

 

$total=0;//0ConvierteMoneda($monedaNativa,270, $totalAPagar);

$totalMercadopagoArgentina=convierteMoneda(188,270,$diferenciaAPagar);



$totalMercadopagoBrasil=convierteMoneda($idMonedaSel,283,$reserva["total"])-$comprobantes283;

$totalReales=convierteMoneda(188,283,$diferenciaAPagar);

$totalPayPal=convierteMoneda($idMonedaSel,188,$reserva["total"])-$comprobantes;



include("./admin/pasarelas/mercadopagoArgentina/procesaPago.php");



include("./admin/pasarelas/mercadopagoBrasil/procesaPago.php");



//include("../query.php");



?>







  <script



    src="https://www.paypal.com/sdk/js?client-id=AcfLam9LvePwGz5ICPiLrSw-s3gdr5BVbq-YpwoYGQwTKOuu8Ai8llIdY5LAl0jnUULO85QkJ4rVGqcZ"> // Required. Replace SB_CLIENT_ID with your sandbox client ID.



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



             alert('<?$lang["gracias_por_confiar_en_metele_brasil"]?>'+ details.payer.name.given_name+'<?$lang["el_pago_de_paypal"]?>');



        window.location='./consultaReserva.php?reserva='+codigoAmigable;



      });



    }



  }).render('#paypal-button-container');



  //This function displays Smart Payment Buttons on your web page.



 



  



</script>

                <h5 id="textoMetodoDePago"class="mb-4"></h5>
                   <div class="container paymentCont ">
                    <div class="row paymentWrap">
                   <div class="list-group col-lg-12 paymentBtnGroup " data-toggle="">
                    <!--FIN AQUI VA LA CARGA DE METODOS DE PAGO-->
                <!-- openpix -->
<label class="btn btn-primary paymentMethod" >
<a onclick="displayOpenPixModal()">
                   <div class="method paypal"> </div>
              <div class="method pix" ></div>
</a>
                      </label>

<?php 
$totalOpenpixRs=sprintf('%.2f',$totalReales);
$totalOpenpixRs=str_replace(".", "", $totalOpenpixRs);
 ?>

    <script src="https://plugin.openpix.com.br/v1/openpix.js" async></script>
    <script>
      totalOpenPix=parseInt('<?=$totalOpenpixRs;?>')
      codigoAmigable='<?=$codigoAmigable;?>'
    

      function displayOpenPixModal() {

        window.$openpix = window.$openpix || []; // priorize o objeto já instanciado



        window.$openpix.push(['config', {

          appID: 'Q2xpZW50X0lkXzliNTY2N2Q2LTk2MzAtNGIzMi1hZWIzLTc4YTY2ZWQxZWVkMzpDbGllbnRfU2VjcmV0X3hyUmk4aDhGSWp0ZnRROUx3R2daNXZ3NGJva0ViNUVuc3ZGcnR0L29JdUE9'

        }]);



        window.$openpix.push([

          'pix',

          {

            value: totalOpenPix, // R$ 10,00

            correlationID: codigoAmigable,

             

          },

        ]);



        const logEvents = (e) => {

          if (e.type === 'CHARGE_COMPLETED') {

            

            location.reload();



          }



          if (e.type === 'CHARGE_EXPIRED') {

           

          }



          if (e.type === 'ON_CLOSE') {

            

          }

        }

        

      // only register event listener when plugin is already loaded

        if(!!window.$openpix?.addEventListener) {

          const unsubscribe = window.$openpix.addEventListener(logEvents);



          // parar de escutar os eventos

          // unsubscribe();

        }

      }

    </script>







<!--fin openpix-->





<?php if($habilita_pesos_arg){ ?>

        <label class="btn btn-primary paymentMethod"  style="">

          <a href="<?= $preference->init_point; ?>">

                     <div class="method paypal">

              <div class="method mercadopagoArgentina" >

              </div>

                    </div>

            </a>

         </label>

<?php } ?>

                   









<?php if($habilita_reales){ ?>

        <label class="btn btn-primary paymentMethod" id="mercadopagoBrasil" style="">

<a href="<?= $preferenceBr->init_point;?>">

                     <div class="method paypal">



              <div class="method mercadopagoArgentina" >





            </div>  

          </div>

       </a>

        </label>

        <?php } ?>

<?php 



if (0) {//$habilita_ebanx //$_SESSION['geo']['countryCode']!='BR' && $url_ebanx!=(-5)



include("admin/pasarelas/ebanx/ebanx.php");

 $url_ebanx=url_ebanx($codigoAmigable, $currencyEbanx, $countryEbanx, $totalEbanx);

 if ($url_ebanx==(-5)) {

   alertar($lang["el_metodo_seleccionado_no_puede_cobrar"],$lang["error"]);

 }

 $url_ebanx_dolares=url_ebanx($codigoAmigable, 'USD', $countryEbanx, $totalPayPal);

 

 ?>

 <label class="btn btn-primary paymentMethod" id="ebanx" style="display:none">

  

<a href="<?=$url_ebanx;?>">

                     <div class="method paypal"> </div>



              <div class="method ebanxs" ></div>

                        

</a>

                      </label>



<?php } ?>



 <label class="btn btn-primary paymentMethod" id="ebanxDolares" style="display:none">

  

<a href="<?=$url_ebanx_dolares;?>">

                     <div class="method paypal"> </div>



              <div class="method ebanxs" ></div>

                        

</a>

                      </label>







        <label class="btn btn-primary paymentMethod" id=""  style="">

   <div class="method paypal">



 <div id="paypal-button-container"></div>  



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


<div class="card card-visitas" >
  <h5 class="mb-4" id="textoMetodoDePago"><?=$lang["cobro_en_mano"]?></h5> 
   <div class="container paymentCont ">
                    
<a href="admin/cobroSignal?reserva=<?=$codigoAmigable?>">
         <label class="btn btn-primary paymentMethod metele-pago"  >
           <div class="method paypal">
              <div class="method " >
              </div>  
           </div>
        </label> 
</a>


  </div>
</div> 

 <?php
      }
}
?>   
     <!--FIN METODOS DE PAGO-->
    <?php } 
    if($comprobantes>0){ // ($comprobantes<$total_dolares) {

      ?>

        <!--METODOS DE PAGO-->
        <div class="col-md-6 py-3">
                  <div class="card card-visitas" id="cardVisitas">
              <div class="card-body">
                 <h3 class="mb-4" id="textoMetodoDePago"><?=$lang["Felicidades"]?></h5>
<h5 class="success">Reserva confirmada</h3>
  <form method="post" action="voucherCarrito">
 <button type="submit" name="codigoAmigable" value="<?=$codigoAmigable;?>" class="btn btn-secondary btn-lg btn-radius" style="width: 100% !important;"><?=$lang["detalles_reserva"];?></button>
  </form>
   <a href="https://metelebrasil.com" class="btn btn-primary btn-lg btn-radius" id="btnPagar" style="width: 100% !important;"><?=$lang["volver_al_site"];?></a>
</div></div></div>

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

     $("#ebanx").css('display','block');













    $("#reales").click(function(){



      $("#paypal").css('display','none');



      $("#mercadopago").css('display','none');



      $("#mercadopagoBrasil").css('display','block');

      $("#ebanx").css('display','none');

      



      $("#reales").css('background',' #029ce2'); //pinta



      $("#reales").css('color',' #fff '); //pinta



      $("#pesos").css('background',' #fff'); //despinta



      $("#pesos").css('color',' #929292 ');  //despinta



      $("#dolar").css('background',' #fff'); //despinta



      $("#dolar").css('color',' #929292 ');  //despinta











    });







     $("#dolar").click(function(){



      $("#paypal").css('display','block');



      $("#mercadopago").css('display','none');



      $("#mercadopagoBrasil").css('display','none');

       $("#ebanxDolares").css('display','block');

  $("#ebanx").css('display','none');

      $("#dolar").css('background',' #029ce2'); //pinta



      $("#dolar").css('color',' #fff '); //pinta



      $("#pesos").css('background',' #fff'); //despinta



      $("#pesos").css('color',' #929292 ');  //despinta



      $("#reales").css('background',' #fff'); //despinta



      $("#reales").css('color',' #929292 ');  //despinta









    });



var mercadoPagoLink= '<?= $preference->init_point;?>';



var mercadoPagoLinkBrasil= '<?= $preferenceBr->init_point;?>';



    $("#pesos").click(function(){

      $("#mercadopago").css('display','block');
      $("#mercadopagoBrasil").css('display','none');
      $("#ebanx").css('display','block');
      $("#ebanxDolares").css('display','none');
      $("#paypal").css('display','none');
      $("#pesos").css('background',' #029ce2'); //pinta
      $("#pesos").css('color',' #fff '); //pinta
      $("#dolar").css('background',' #fff'); //despinta
      $("#dolar").css('color',' #929292 ');  //despinta
       $("#reales").css('background',' #fff'); //despinta
      $("#reales").css('color',' #929292 ');  //despinta
    });


          $("#paypal").click(function(){

 $("#mercadopago").css('border','  none '); //pinta
 $("#paypal").css('border','  4px solid  #029ce2 '); //pinta
       $("#mercadopagoBrasil").css('display','none');
           });


      $("#mercadopago").click(function(){
 $("#paypal").css('border','  none '); //pinta
 $("#mercadopago").css('border','  4px solid  #029ce2 '); //pinta
       $("#mercadopagoBrasil").css('display','none');
      });

           $("#mercadopagoBrasil").click(function(){
 $("#paypal").css('border','  none '); //pinta
 $("#mercadopago").css('border','  4px solid  #029ce2 '); //pinta

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



