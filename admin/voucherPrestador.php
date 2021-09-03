

<?php 





include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");
require("classes/functions.php");
include("classes/salidas.php");
include("classes/tarifas.php");
include("classes/idiomas.php");
include("classes/servicio.php");
include("classes/comisiones.php");
include("classes/edades.php");
include("classes/cancelaciones.php");
include("classes/tarifas_ubicacion.php");
include("classes/servicios_adicionales.php");
include("classes/reserva.php");
include("classes/comprobantes.php");
include("classes/prestador.php");
include("classes/accesibilidad.php");
include("classes/convierte_monedas.php");
include("classes/destinos.php");



/*

if (!$_SESSION["login"]["rol"]==1) {

  alertar("Você não tem acesso a esta seção do software", "error");

  redireccionarLento("index");
  exit();

}*/
      $totalIva=0;
          $totalCarrito=0;
if ($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST["idReservaHorarios"])) {
$idReservaHorarios=$_POST['idReservaHorarios'];
$horarios=getReservaHorariosId($idReservaHorarios);
$reserva=getReservaId($horarios[0]['idReserva'])[0];
$codigoAmigable=$reserva['codigoAmigable'];
$idReserva=$reserva['idReserva'];
$fechaAlta=date("d/m/Y",strtotime($reserva["fechaAlta"]));
$nombreResponsable=$reserva['nombreResponsable']." ".$reserva['apellidoResponsable'];
$emailResponsable=$reserva['emailResponsable'];
$totalReserva=$reserva["total"];
$impuestos=$reserva["impuestos"];
$monedaSel=$reserva["monedaSel"];

$precio=ConvierteMoneda($monedaSel,$_SESSION["moneda_sel"], $totalReserva);
     $servicio= getServicio($horarios[0]["idServicioSeleccionado"]);
$idServicioSalidas=($horarios[0]['idServicioSalidas']);

$salida=getSalida($idServicioSalidas)[0];
$idiomasSalida=getIdiomasSalida($idServicioSalidas);
$prestador=getPrestador($salida["idPrestador"])[0];

if (  $salida["idPrestador"]!=$_SESSION['login']['idPrestador']) {
  if ($_SESSION["login"]["rol"]!=1) {
    alertar("Você não tem acesso a esta seção do software", "error");

  redireccionarLento("index");
  exit();
  }
    
}
$horaSalida=$salida['horaSalida'];
$horaCheckIn=$salida['horaCheckIn'];
$destino=getDestino($servicio[0]['idDestino']);

$idiomasSalidaTxt='';
foreach ($idiomasSalida as $key => $value) {
$idiomasSalidaTxt=$value.' ';
}
$fechaCheckIn= date("d/m/Y",strtotime($salida['fecha']));
         $idReservaHorarios=$horarios[0]["idReservaHorarios"];
          $adicionales=getReservaAdicionalesNoIncluidos($idReservaHorarios);
         $adicionales=getReservaAdicionalesNoIncluidos($idReservaHorarios);
        
         $reservaTarifas=getReservaTarifas($idReservaHorarios);
         $adicionales=getReservaAdicionalesNoIncluidos($idReservaHorarios);
}



 ?>

  <!-- Content Wrapper. Contains page content -->


    <!-- Content Header (Page header) -->




        <!-- SELECT2 EXAMPLE -->





        <!-- /.Responsive -->

        <!-- SELECT2 EXAMPLE -->


<div class="table-responsive">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->


    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>VOUCHER DE <?=$servicio[0]["nombre_servicio"];?></h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Financiero</a></li>
              <li class="breadcrumb-item active">Voucher de Servicio</li>
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
            <div class="invoice p-3 mb-3" id="imprimible">
              <!-- title row -->
              <div class="row">
                <div class="col-12">
                  <h4>
                    <h3 class="page-header">
           <strong>  DETALHES DA COMPRA</strong>
        </h3>
                    <small class="float-right">Date: <?=$fechaCheckIn;?></small>
                  </h4>
                </div>
                <!-- /.col -->
              </div>
              <!-- info row -->
              <div class="row invoice-info">
                <div class="col-sm-4 invoice-col">
                  Responsavél da reserva
                  <address>
               <strong><?=$nombreResponsable;?></strong><br>
                    <b>Usuario:</b> <?=$emailResponsable; ?><br>
                    <b>Responsavél do pagamento:</b><?= $nombreResponsable;?><br>
                    <b>Fecha de compra:</b> <?=$fechaAlta;?><br>
                    <b>Validade do Voucher:</b> <?=$fechaCheckIn;?><br>
                    
                    
                  </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                  Pais
                  <address>
               <strong><?=$destino[0]['nombre'];?></strong><br>
                    <b>Idioma:</b> <?= $idiomasSalidaTxt;?><br>
                    <b>Data do Evento:</b> <?=$fechaCheckIn;?><br>
                    <b>Horario de check in:</b> <?=$horaCheckIn;?><br>
                    <b>Horario de saída:</b><?= $horaSalida ?><br>
                    

                  </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                  <b>Numero de comprovante</b><b> #<?= $codigoAmigable;?></b><br>
                  <b>Numero de ordem ID:</b> <?= $codigoAmigable;?><br>
                  <b>Telefone do prestador:</b><b> <?=$prestador['telefono'];?></b><br>
                  <b>Email do prestador:</b><b> <?=$prestador['email'];?></b>
                  
                  
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->



<h4><i class="fas fa-info"></i> Servicio 1: <?=$servicio[0]["nombre_servicio"];?></h4>
              <!-- Table row -->
              <div class="row">
                <div class="col-12 table-responsive">
                  <table class="table table-striped">
                    <thead>
                    <tr>
                      <th>Nombre y apellido</th>
                      <th>Tipo de tarifa</th>
                      <th>Edad</th>
                      <th>Fecha y hora Check In</th>
                      <th>Subtotal</th>
                       <th>ICMS</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php     $cancelacionesArr=Array();    

                     for ($j=0; $j < count($reservaTarifas); $j++) { 

            $idServicioSalidasTarifas=$reservaTarifas[$j]['idServicioSalidasTarifas'];
                $tarifaOrigi=getTarifa($idServicioSalidasTarifas);
                  $cancelacion=getTipoCancelaciones($tarifaOrigi[0]["idCancelaciones"]);
            $idReservaTarifas=$reservaTarifas[$j]["idReservaTarifas"];
            $tarifaOrigi=getTarifa($idServicioSalidasTarifas);
            $ubicacion=getUbicacionIdTarifa($idServicioSalidasTarifas);
            $cancelacion=getTipoCancelaciones($tarifaOrigi[0]["idCancelaciones"]);

if (in_array($cancelacion[0]['texto'],$cancelacionesArr)==0) {
   array_push( $cancelacionesArr, $cancelacion[0]['texto']);
}
        
                     $monedaSel=$reservaTarifas[$j]["monedaSel"];
                $valorSinIva=$reservaTarifas[$j]["valorSinIva"];
                       $cantidad=($reservaTarifas[$j]["cantidad"]);
                       $valorDelIva=$reservaTarifas[$j]['valorDeIva'];      
                   
                       $valorDelIva=ConvierteMoneda($reservaTarifas[0]["monedaSel"],$_SESSION["moneda_sel"], $valorDelIva);
                      
                        $totalTarifa=$valorSinIva/$cantidad;
      
                        $totalIva+= $valorDelIva;
                  
            $total=ConvierteMoneda($reservaTarifas[$j]["monedaSel"],$_SESSION["moneda_sel"], $totalTarifa);
                   $totalCarrito+=$total;
             $edadFrom=getEdad($reservaTarifas[$j]["idFromEdad"]);
             $edadTo=getEdad($reservaTarifas[$j]["idToEdad"]);
             $idMonedaSel=$reservaTarifas[$j]["monedaSel"];
              $moneda=getMoneda($idMonedaSel)[0]["Symbol"];
              $pasajeros=getPasajeros($idReservaTarifas);
              
              for ($k=0; $k < count($pasajeros); $k++) { 
?>
                    <tr>
                      <td> <?=$pasajeros[$k]["nombrePasajero"];?> <?=$pasajeros[$k]["apellidoPasajero"];?></td>
                      <td><?=$cantidad?> <?=$reservaTarifas[$j]["nombre"];?> </td>
                      <td><?= $edadFrom[0]["valor"]?> A <?= $edadTo[0]["valor"]?> Anos</td>
                      <td><?= date("d/m/Y",strtotime($horarios[0]['fecha']))?> <?=$horarios[0]['horaCheckIn']?></td>
                      <td><?=$_SESSION["moneda_sel_sym"].$total;?></td>
                      <td><?=$_SESSION["moneda_sel_sym"].$valorDelIva;?></td> 
                    </tr>
                <?php } 

                        ?>
                    
                  
            
                    <?php
          } 
               
              ?>

                    <?php
           for ($k=0; $k < count($adicionales); $k++) { 
        
              $precioAdicional=ConvierteMoneda($reservaTarifas[0]["monedaSel"],$_SESSION["moneda_sel"],$adicionales[$k]['precio']);
              $cantidad=$adicionales[$k]['cantidad'];
                $totalCarrito+=$precioAdicional;

              $valorIva=ConvierteMoneda($reservaTarifas[0]["monedaSel"],$_SESSION["moneda_sel"],$adicionales[$k]['valorIva']);
             $totalIva+=$valorIva;
           ?>

<tr>
  <td colspan="4"><?=$adicionales[$k]['cantidad'];?> <?=$adicionales[$k]['nombre'];?></td>
  <td ><?= $_SESSION["moneda_sel_sym"].$precioAdicional?></td>
    <td ><?= $_SESSION["moneda_sel_sym"].$valorIva?></td> 
</tr>

            <?php
          } ?>


                  <tr>
                      <th colspan="3"></th>
                      <th >TOTAL</th>
                  <th><?=$_SESSION["moneda_sel_sym"].$totalCarrito;?></th>
                  <th></th>
                    </tr>
              
                    </tbody>
                  </table>
                </div>
  
                <!-- /.col -->
              </div>
          <div class="callout callout-info" >
              <h3><i class="fas fa-info"></i> Ponto de sáida:</h3>
             
             <div class="google-maps" style="width: 100%;">

              <iframe style="width: 100%;" src = "https://maps.google.com/maps?q=<?=$ubicacion[0]['latitud']?>,<?=$ubicacion[0]['longitud']?>&hl=es;z=14&amp;output=embed"></iframe>

                  </div>
            
            </div>
              <!-- /.row -->
     
              <div class="row">




                <!-- accepted payments column -->
                <div class="col-6">
                  <p class="lead">Metodos de Pagamento:</p>
                  <img src="../img/credit/visa.png" alt="Visa">
                  <img src="../img/credit/mastercard.png" alt="Mastercard">
                  <img src="../img/credit/american-express.png" alt="American Express">
                  <img src="../img/credit/paypal2.png" alt="Paypal">

                  <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
                   Para sua segurança, caso haja qualquer divergência entre as informações cadastrais e de pagamento, nos reservamos o direito de não aprovar o seu pedido, ou, de entrar em contato para confirmar seus dados.
                  </p>
                </div>
                <!-- /.col -->
                <div class="col-6">
                  

                  <div class="table-responsive">
                    
                    <table class="table">
                   <h5> RESUMO</h5>
               
                   
                         <tr>
                        <th>ICMS:</th>
                        <td><?=$_SESSION["moneda_sel_sym"].$totalIva;?></td>
                      </tr>  
                       <tr>
                        <th>Total:</th>
                        <td><?=$_SESSION["moneda_sel_sym"].($totalCarrito+$totalIva);?></td>
                      </tr>
                    </table>


                  </div>

                </div>
                                                <div class="callout callout-info">
                                                     <h5>   <i class="fas fa-info"></i> Politicas das tarifas:</h5>
                                                  <?php for ($i=0; $i < count($cancelacionesArr); $i++) { 
                                                 
                                                    ?><?=$cancelacionesArr[$i];?><br>
                                                    <?php
                                                  } ?>
        
            </div>
                <!-- /.col -->

              </div>
              <!-- /.row -->

              <!-- this row will not appear when printing -->

            </div>
            <!-- /.invoice -->
          </div><!-- /.col -->
        </div><!-- /.row -->
             <div class="row no-print">
                <div class="col-12">
               
                  <form method="post" action="pasajerosLista">
                       <a id="btnImprimir" class="btn btn-default"><i class="fas fa-print"></i> Imprimir</a>
                    <button class="btn-sm btn-default" type="submit" name="idServicioSalidas" value="<?=$idServicioSalidas;?>">Volver</button>
                  </form>
                 
                
                </div>
              </div>
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
    <!-- /.content -->
  </div>

          <!-- /.card-body -->





          <div class="card-footer">

         <!-- <div align="center"> <button type="submit" id="uploadfiles" value="Crear servicio" class="btn btn-success"><i class="fa fa-floppy-o" aria-hidden="true"></i> Continuar</button>   <a href="servicios.php" class="btn btn-danger" ><i class="fa fa-times" aria-hidden="true"></i> Salir sin guardar</a>

</div>-->





      
  <?php 

  include("includes/footer.php"); ?>







          </div>









