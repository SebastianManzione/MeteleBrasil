

<?php 
// Verificar permisos de acceso ANTES de cualquier salida
require_once(__DIR__ . "/classes/permisos.php");
require_once(__DIR__ . "/includes/permisos_helper.php");
$permisos = new PermisosManager($GLOBALS['pdo'], $_SESSION['login'] ?? []);
$permisos->verificarAcceso('pasajerosLista');

include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");
require_once("classes/functions.php");
require_once("classes/prestador.php");
require_once("classes/usuario.php");
require_once("classes/edades.php");
require_once("classes/servicio.php");
require_once("classes/reserva.php");
require_once("classes/salidas.php");
require_once("classes/tarifas.php");
require_once("classes/comprobantes.php");
require_once("classes/cancelaciones.php");
require_once("classes/convierte_monedas.php");
require_once("classes/codigos_telefonicos.php");
if ($_SERVER["REQUEST_METHOD"]=="POST") {


$idServicioSalidas=($_POST["idServicioSalidas"]);
$salida=getSalida($idServicioSalidas);
$servicio=getServicio($salida[0]["idServicio"]);
$fechaSalida=date("d-m-Y", strtotime($salida[0]["fecha"]));
$horaSalida=$salida[0]["horaSalida"];
$horaCheckIn=$salida[0]["horaCheckIn"];

}



 ?>

  <!-- Content Wrapper. Contains page content -->

  <div class="content-wrapper">

    <!-- Content Header (Page header) -->

    <div class="content-header">

      <div class="container-fluid">

        <div class="row mb-2">

          <div class="col-sm-6">

            <h1 class="m-0 text-dark"><?=$lang["lista_de_pasajeros"];?></h1>

          </div><!-- /.col -->

          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right">

              <li class="breadcrumb-item"><a href="#"><?=$lang["reservas"];?></a></li>

              <li class="breadcrumb-item active"><?=$lang["lista_de_pasajeros"];?></li>

            </ol>

          </div><!-- /.col -->

        </div><!-- /.row -->

      </div><!-- /.container-fluid -->

    </div>

    <section class="content">

      <div class="container-fluid">

        <!-- SELECT2 EXAMPLE -->





        <!-- SELECT2 EXAMPLE -->

        <div class="card card-default" id="imprimible">

          <div class="card-header">

            <h3 class="card-title"><?=$lang["fecha_del_servicio"];?><?=$fechaSalida;?></h3>



            <div class="card-tools">

              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>

              <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>

            </div>

          </div>

          <!-- /.card-header -->

          <div class="card-body">

            



<div class="table-responsive">

              <!-- /.card-header -->

              <div class="card-body">

                 <div class="row">

                     <div class="card bg-secondary" style="width:100%">

         

                                        <table class="align-middle" style="width:100%">



                                                  <tr align="center">

                                                    

                                                    <th><?=$lang["servicio"];?></th>

                                                    <th><?=$lang["fecha_de_salida"];?></th>

                                                    <th><?=$lang["horario_de_salida"];?></th>

                                                    <th><?=$lang["horario_de_check_in"];?></th>

                                                    <th><?=$lang["cobro_pendiente"];?></th>

                                                     

                                                  </tr>

             

                                                  <tr align="center">

                                                    <td><?=$servicio[0]["nombre_servicio"]?></td>

                                                    <td><?= $fechaSalida ?></td>

                                                    <td><?=$horaSalida;?></td>

                                                    <td><?= $horaCheckIn ?></td>

                                                    <td></td>

                                                

                                                   </tr>

                                                

                                        </table>



                      </div>







 

                                               <table style="width:100%">



                                                  <tr align="center">

                                                       <th><?=$lang["notas_de_salida"];?></th>

                                                  </tr>



                                                  <tr class="bg-secondary" align="center" style="width:80%">

                                                       <td><?=$salida[0]["nota_salida"];?></td>

                                                  </tr>



                                               </table>

            

                  </div>











                  <table class="table table-bordered table-striped datatable">

                                    <thead>

                                                  <tr>



                  	                                <th><?=$lang["nombre_de_pasajeiro"];?></th>

                                                    <th><?=$lang["tipo_de_pasajegeiro"];?></th>

                                                    <th><?=$lang["codigo"];?></th>

                                                    <th><?=$lang["telefono"];?></th>

                                                    <th><?=$lang["total"];?></th>

                                                    <th><?=$lang["valor_ya_pago"];?></th>

                                                    <th><?=$lang["resta_pagar"];?></th>

                                                    <th><?=$lang["tipo_de_tarifa"];?></th>

                                                    <th><?=$lang["estado_"];?></th>
  <th>Voucher</th>
                                                    <th><?=$lang["comentario_del_pasajero"];?></th>

                                                  

                                                  </tr>

                                       </thead>





     <tbody>





          	<?php $tarifas=getTarifasReservadas($idServicioSalidas) ;

         //   $tarifas=getTarifasReservadas(1455);//788 tiene registros

                        	for ($i=0; $i < count($tarifas); $i++) { 



                            $fromEdad=getEdad($tarifas[$i]['idFromEdad'])[0]["valor"];

                            $toEdad=getEdad($tarifas[$i]['idToEdad'])[0]["valor"];

                            $idReservaTarifas=$tarifas[$i]['idReservaTarifas'];
$idReservahorarios=$tarifas[$i]['idReservaHorarios'];
      $horario=getReservaHorariosId($idReservahorarios);

  

      $idReserva=$horario[0]["idReserva"];

      $tarifa2=getTarifasReservadasIdTarifa( $idReservaTarifas);



      $tarifaOriginal=getTarifa($tarifa2[0]['idServicioSalidasTarifas']);

      $cancelacion=getTipoCancelaciones($tarifaOriginal[0]['idCancelaciones'])[0]["texto"];

     

       $pasajeros=getPasajeros($idReservaTarifas);

      $moneda=getMoneda($tarifa2[0]["monedaSel"]);

      $symMoneda=$_SESSION["moneda_sel_sym"];



      $valorSinIva=ConvierteMoneda($tarifa2[0]["monedaSel"],$_SESSION["moneda_sel"], $tarifa2[0]["valorSinIva"]);

      $valor=ConvierteMoneda($tarifa2[0]["monedaSel"],$_SESSION["moneda_sel"], $tarifa2[0]["valor"]);

      $comisionVendedor=ConvierteMoneda($tarifa2[0]["monedaSel"],$_SESSION["moneda_sel"], $tarifa2[0]["comisionVendedor"]);



      $reserva=getReservaId($idReserva);

$codigoTelefonico=getCodigoTelefonico($reserva[0]["idCountry"]);
  
        $totalComprobantes=getComprobantesIdReserva($idReserva);

$totalReserva=ConvierteMoneda($reserva[0]["monedaSel"],$_SESSION["moneda_sel"], $reserva[0]["total"]);

$diferenciaComprobantesPrecio=$totalReserva-$totalComprobantes;



                                       $claseBoton="btn btn-warning";

                                        $textoBoton="Pendiente";
                                          $confirmada=false;
                                        if ($diferenciaComprobantesPrecio<1 && $totalReserva > 0) {
                                          $confirmada=true;
                                         $claseBoton="btn btn-success";

                                          $textoBoton="Confirmada";

                                        }





      $fechaAlta= date( "d-m-Y", strtotime( $reserva[0]["fechaAlta"] ) );

                     for ($j=0; $j < count($pasajeros); $j++) { 

                     





                        	 	?>







        <tr>
                  	<td class="details-control"><?=$pasajeros[$j]["nombrePasajero"]?> <?=$pasajeros[$j]["apellidoPasajero"]?> </td>

                    <td class="details-control"><?=$tarifa2[0]["nombre"]?> (<?=$fromEdad?> a <?=$toEdad?> Años)</td>                

                  	<td class="details-control"><?=$reserva[0]["codigoAmigable"]?></td>

                    <td class="details-control">+<?=$codigoTelefonico;?><?=$reserva[0]["telefonoResponsable"]?></td>

                    <td class="details-control"><?=$_SESSION["moneda_sel_sym"].round($totalReserva,2);?></td>

                    <td class="details-control"><?=$_SESSION["moneda_sel_sym"].round($totalComprobantes,2);?></td>

                    <td class="details-control"><?=$_SESSION["moneda_sel_sym"].round($diferenciaComprobantesPrecio,2);?></td>

                    <td class="details-control"><?= substr($cancelacion, 0,50);?></td>

                    <td> <button type="button" class="<?= $claseBoton;?>"><?= $textoBoton;?></button></td> 
                    <td><?php if ($confirmada) {
                     ?>
<form method="post" action="voucherPrestador" ><button type="submit" name="idReservaHorarios" value="<?=$idReservahorarios;?>"><?=$lang["voucher"];?></button></form>
                     <?php
                    } ?> </td>

                    <td class="details-control"><?= $horario[0]['comentario']?></td>



                  </tr>





              <?php

             }  }?>

          

                  </tbody>

                </table>

              </div>

              <!-- /.card-body -->

          </div> 

          <!-- /.Responsive -->







              <!-- botones de impresion y de generar pdf -->



              <!-- /.card-body -->

          </div>



         

<script>

  

    

                    var table =  $("#tabla_pasajeros").DataTable({

                         "paging": true,

                    "lengthChange": false,

                    "searching": true,

                    "ordering": true,

                    "info": true,

                    "autoWidth": true,

                      });

              	function format(value) {

              		

              	    return '<div>' + value + '</div>';

              	}

              // Add event listener for opening and closing details

              $('#tabla_pasajeros tbody').on('click', 'td.details-control', function () {

              	

                var tr = $(this).closest('tr');

                var row = table.row( tr );





                if (row.child.isShown()) {

                     // This row is already open - close it

                     row.child( format( tr.data('child-value') ) ).hide();

                     tr.removeClass('shown');

                 }

                 else {

                     // Open this row

                     row.child( format(  tr.data('child-value') ) ).show();

                     tr.addClass('shown');

                 }

              });

  

</script>
                </tfoot>

              </table>

              <!-- /.col -->

            </div>



            <!-- /.row -->

          </div>

          <!-- /.card-body -->

          <div class="card-footer">
          <div class="row no-print">
                <div class="col-12">
               
                  <form method="post" action="pasajerosLista">
                       <a id="btnImprimir" class="btn btn-default"><i class="fas fa-print"></i>  <?=$lang["imprimir"];?></a>
                    <!--<button class="btn-sm btn-default" type="submit" name="idServicioSalidas" value="<?=$idServicioSalidas;?>">Voltar</button>-->
                  </form>
                 
      

                </div>
              </div>

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
         <!-- <div align="center"> <button type="submit" id="uploadfiles" value="Crear servicio" class="btn btn-success"><i class="fa fa-floppy-o" aria-hidden="true"></i> Continuar</button>   <a href="servicios.php" class="btn btn-danger" ><i class="fa fa-times" aria-hidden="true"></i> Salir sin guardar</a>

</div>-->

          </div>

        </div>

        <!-- /.card -->



        <!-- /.row -->

      





  <?php 

  include("includes/footer.php"); ?>









}