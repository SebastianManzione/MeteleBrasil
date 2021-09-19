  

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
include("admin/classes/accesibilidad.php");
include("admin/classes/convierte_monedas.php");


if ($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST["codigoAmigable"])) {

$codigoAmigable=$_POST["codigoAmigable"];

$reserva=getReserva($codigoAmigable)[0];
$idReserva=$reserva["idReserva"];
$monedaSel=$reserva["monedaSel"];
$moneda=getMoneda($reserva["monedaSel"])[0]["Symbol"];
$idReserva=$reserva['idReserva'];
$fechaAlta=date("d/m/Y",strtotime($reserva["fechaAlta"]));
$nombreResponsable=$reserva['nombreResponsable']." ".$reserva['apellidoResponsable'];
$emailResponsable=$reserva['emailResponsable'];
$totalReserva=$reserva["total"];
$impuestos=$reserva["impuestos"];
$precio=ConvierteMoneda($monedaSel,$_SESSION["moneda_sel"], $totalReserva);

}
if (count($reserva)<1) {
  alertar("La Reserva con el codigo ".$codigoAmigable." no existe","error");
  redireccionarLento("index");
  exit();
}


$comprobantes= getComprobantesIdReservaDolar($idReserva);

$total_dolares=$reserva["total_dolares"];

if ($comprobantes<$total_dolares){
  alertar("La Reserva con el codigo ".$codigoAmigable." no tiene su pago completo, no se puede generar el comprobante","error");
  redireccionarLento("consultaReserva?reserva=".$codigoAmigable);
  exit();
}

 ?>



<!--PASOS PARA RESERVA-->

<section>

 

<div class="table-responsive">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>VOUCHER</h1>
            <hr>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Financiero</a></li>
              <li class="breadcrumb-item active">Voucher de Carrito</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <!-- Main content -->
            <div class="invoice p-3 mb-3"  id="imprimible">
              <!-- title row -->
              <div class="row">
                <div class="col-12">
                  <h4>
                    <h3 class="page-header">
           <strong>  DETALHES</strong>
        </h3>
                    <small class="float-right">Date: <?= date('d/m/Y');?></small>
                  </h4>
                </div>
                <!-- /.col -->
              </div>
              <!-- info row -->



              <div class="row invoice-info">
                <div class="col-sm-6 invoice-col">
                   <strong>Responsavél da reserva:</strong>
                  
                    <?= $nombreResponsable;?><br>
                    <address><b>Email:</b> <?=$emailResponsable; ?><br>
                       <b>Responsavél do pagamento:</b> <?= $nombreResponsable;?><br>
                   </address><br>
                 
                </div>
                <!-- /.col -->
               
                <!-- /.col -->
                <div class="col-sm-6 invoice-col">  
                  <b>Fecha de compra:</b> <?=$fechaAlta;?><br>
                  <b>Numero de comprovante</b><b> #<?= $codigoAmigable;?></b><br>
                  <b>Numero de ordem ID:</b> <?= $codigoAmigable;?><br>

                  
                  
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->


<div class="callout callout-info">
   
      
            </div>

              <!-- Table row -->
              <div class="row">
                <div class="col-12 table-responsive">
                  <table class="table table-striped">
                    <thead>
                    <tr>
                      <th>Servicio</th>
                    <th>Cantidad</th>
                      <th>Tipo de tarifa</th>
                      <th>Check in</th>
                      <th>Subtotal</th>
                   
                      <th></th>
                    </tr>
                    </thead>
                    <tbody>
                      <?php 
                      $cancelacionesArr=Array();
          $horarios=getReservaHorarios($idReserva); 
          $totalIva=0;
          $totalCarrito=0;
        //  print_r($horarios);
for ($i=0; $i < count($horarios); $i++) { 

  $idReservaHorarios=$horarios[$i]['idReservaHorarios'];
          $servicio= getServicio($horarios[$i]["idServicioSeleccionado"]);
          $idServicioSalidas=$horarios[$i]["idServicioSalidas"];



         $idReservaHorarios=$horarios[$i]["idReservaHorarios"];
         $adicionales=getReservaAdicionalesNoIncluidos($idReservaHorarios);
        
         $reservaTarifas=getReservaTarifas($idReservaHorarios);
      

          for ($j=0; $j < count($reservaTarifas); $j++) { 
     
            $idServicioSalidasTarifas=$reservaTarifas[$j]['idServicioSalidasTarifas'];
            $tarifaOrigi=getTarifa($idServicioSalidasTarifas);
            $cancelacion=getTipoCancelaciones($tarifaOrigi[0]["idCancelaciones"]);

if (in_array($cancelacion[0]['texto'],$cancelacionesArr)==0) {
   array_push( $cancelacionesArr, $cancelacion[0]['texto']);
}
        
                     $monedaSel=$reservaTarifas[$j]["monedaSel"];
                $valorSinIva=$reservaTarifas[$j]["valorSinIva"];
                       $cantidad=($reservaTarifas[$j]["cantidad"]);
                       $valorDelIva=$reservaTarifas[$j]['valorDeIva'];      
                   
                       $valorDelIva=ConvierteMoneda($reservaTarifas[0]["monedaSel"],$_SESSION["moneda_sel"], $valorDelIva);
                      
                        $totalTarifa=$valorSinIva;
         
                        $totalIva+= $valorDelIva;
                  
            $total=ConvierteMoneda($reservaTarifas[$j]["monedaSel"],$_SESSION["moneda_sel"], $totalTarifa);
                   $totalCarrito+=$total;
             $edadFrom=getEdad($reservaTarifas[$j]["idFromEdad"]);
             $edadTo=getEdad($reservaTarifas[$j]["idToEdad"]);
             $idMonedaSel=$reservaTarifas[$j]["monedaSel"];
              $moneda=getMoneda($idMonedaSel)[0]["Symbol"];

?>



                   <tr>
                      <td> <?=$servicio[0]["nombre_servicio"];?></td>
                      <td><?=$cantidad?> <?=$reservaTarifas[$j]["nombre"];?> </td>
                      <td><?= $edadFrom[0]["valor"]?> A <?= $edadTo[0]["valor"]?> Anos</td>
                      <td><?= date("d/m/Y",strtotime($horarios[0]['fecha']))?> <?=$horarios[0]['horaCheckIn']?></td>
                      <td><?=$_SESSION["moneda_sel_sym"].$total;?></td>
            
                 
                      <td>
                        <?php if ($j==0) { ?>
                        <form method="post" action="voucherSalida">
                          <button type="submit" name="idReservaHorarios" value="<?=$idReservaHorarios;?>" class="btn-sm btn-primary float-right">Detalles Servicio</button>    
                           </form> 
                        <?php } ?>
                            
                     
                      </td>
                   </tr>


                    <?php
          } for ($k=0; $k < count($adicionales); $k++) { 
        
              $precioAdicional=ConvierteMoneda($reservaTarifas[0]["monedaSel"],$_SESSION["moneda_sel"],$adicionales[$k]['precio']);
              $cantidad=$adicionales[$k]['cantidad'];
                $totalCarrito+=$precioAdicional;

              $valorIva=ConvierteMoneda($reservaTarifas[0]["monedaSel"],$_SESSION["moneda_sel"],$adicionales[$k]['valorIva']);
             $totalIva+=$valorIva;
           ?>

<tr>
  <td colspan="4"><?=$adicionales[$k]['cantidad'];?> <?=$adicionales[$k]['nombre'];?></td>
  <td ><?= $_SESSION["moneda_sel_sym"].$precioAdicional?></td>

</tr>

            <?php
          } ?>


          <?php

                    }               
?>
              
     
                    </tbody>
                  </table>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
     
              <div class="row">
                <!-- accepted payments column -->
                <div class="col-6">
                  <p class="lead">Metodos de Pagamento:</p>
                  <img src="img/credit/visa.png" alt="Visa">
                  <img src="img/credit/mastercard.png" alt="Mastercard">
                  <img src="img/credit/american-express.png" alt="American Express">
                  <img src="img/credit/paypal2.png" alt="Paypal">

                  <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
                   Para sua segurança, caso haja qualquer divergência entre as informações cadastrais e de pagamento, nos reservamos o direito de não aprovar o seu pedido, ou, de entrar em contato para confirmar seus dados.
                  </p>
                </div>
                <!-- /.col -->
                <div class="col-6">
                  <p class="lead">Data de Validade 2/22/2014</p>

                  <div class="table-responsive">
                    <h4>RESUMO</h4>
                    <table class="table">
                      <tr>
                        <th style="width:50%">Subtotal:</th>
                        <td><?=$_SESSION["moneda_sel_sym"].$totalCarrito;?></td>
                      </tr>
                      <tr>
                        <th>Impostos</th>
                        <td><?=$_SESSION["moneda_sel_sym"].$totalIva;?></td>
                      </tr>

                      <?php if(isset($_SESSION['login']['idVendedor']) &&  $_SESSION['login']['idVendedor']>0 || isset($_SESSION['login']['idPrestador']) &&  $_SESSION['login']['idPrestador']>0 ){ ?>
                      <tr>
                        <th>Comissão/(nao mostrar para cliente final) :</th>
                        <td>---</td>
                      </tr> 
                        <?php } ?>
                      <tr>
                        <th>Total:</th>
                        <td><?= $moneda." ".$reserva["total"];?></td>
                      </tr>
                    </table>
                  </div>
                </div>
                <!-- /.col -->
              </div>

     <hr>
              <!-- /.row -->
                    <div class="callout callout-info">
                        <?php 

                        for ($i=0; $i < count($cancelacionesArr); $i++) { 
                      ?>
      <h5><i class="fas fa-info"></i> Politicas do voucher:</h5>
              <?=$cancelacionesArr[$i]?><br>
                      <?php
                        } ?>
        
        <br>
            </div>

             <hr>
              <!-- this row will not appear when printing -->
              <div class="row no-print">
                <div class="col-12">
              <a id="btnImprimir" class="btn btn-default"><i class="fas fa-print"></i> Imprimir</a>
              
          
               
                </div>
              </div>
            </div>

   

            <!-- /.invoice -->
          </div><!-- /.col -->
        </div><!-- /.row -->

      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  </div>






</section>



<script type="text/javascript">
  
function imprimirElemento(elemento){
  var ventana = window.open('', 'PRINT', 'height=400,width=600');
  ventana.document.write('<html><head><title>' + document.title + '</title>');
  ventana.document.write('</head><body >');
  ventana.document.write(elemento.innerHTML);
  ventana.document.write('</body></html>');
  ventana.document.close();
  ventana.focus();
  ventana.print();

  return true;
}
  document.querySelector("#btnImprimir").addEventListener("click", function() {
    alert();
  var div = document.querySelector("#imprimible");
  imprimirElemento(div);
});
</script>
  <!-- Footer -->

  

  <!-- Footer -->

  <footer class="footer footer-reserva ">

    <div class="container">

      <div class="row"></div>

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

