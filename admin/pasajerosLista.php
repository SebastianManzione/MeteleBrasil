
<?php 


include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");
require("classes/functions.php");
require("classes/prestador.php");
require("classes/usuario.php");
require("classes/edades.php");
require("classes/servicio.php");
require("classes/reserva.php");
require("classes/salidas.php");
require("classes/tarifas.php");
require("classes/comprobantes.php");
require("classes/cancelaciones.php");
require("classes/convierte_monedas.php");
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
            <h1 class="m-0 text-dark">Lista de pasajeros</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Reservas</a></li>
              <li class="breadcrumb-item active">Lista de pasajeros</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <section class="content">
      <div class="container-fluid">
        <!-- SELECT2 EXAMPLE -->


        <!-- SELECT2 EXAMPLE -->
        <div class="card card-default">
          <div class="card-header">
            <h3 class="card-title">Fecha del Servicio <?=$fechaSalida;?></h3>

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
                                                    
                                                    <th>Servicio</th>
                                                    <th>Fecha de Salida</th>
                                                    <th>Horario de Salida</th>
                                                    <th>Horario de Check In</th>
                                                    <th>COBRO PENDIENTE</th>
                                                     
                                                  </tr>
             
                                                  <tr align="center">
                                                    <td><?=$servicio[0]["nombre_servicio"]?></td>
                                                    <td><?= $fechaSalida ?></td>
                                                    <td><?=$horaSalida;?></td>
                                                    <td><?= $horaCheckIn ?></td>
                                                    <td>2785,00</td>
                                                
                                                   </tr>
                                                
                                        </table>

                      </div>



 
                                               <table style="width:100%">

                                                  <tr align="center">
                                                       <th>Notas de salida:</th>
                                                  </tr>

                                                  <tr class="bg-secondary" align="center" style="width:80%">
                                                       <td><?=$salida[0]["nota_salida"];?></td>
                                                  </tr>

                                               </table>
            
                  </div>





                  <table id="tabla_pasajeros" class="table table-bordered table-striped datatable">
                                    <thead>
                                                  <tr>

                  	                                <th>Nombre pax</th>
                                                    <th>Tipo de pax</th>
                                                    <th>Codigo carrito</th>
                                                    <th>Telefono</th>
                                                    <th>TOTAL</th>
                                                    <th>PAGO</th>
                                                    <th>Resta pagar</th>
                                                    <th>Tipo de tarifa</th>
                                                    <th>Estado</th>
                                                    <th>Comentario</th>
                                                  
                                                  </tr>
                                       </thead>


     <tbody>


          	<?php $tarifas=getTarifasReservadas($idServicioSalidas) ;
         //   $tarifas=getTarifasReservadas(1455);//788 tiene registros
                        	for ($i=0; $i < count($tarifas); $i++) { 

                            $fromEdad=getEdad($tarifas[$i]['idFromEdad'])[0]["valor"];
                            $toEdad=getEdad($tarifas[$i]['idToEdad'])[0]["valor"];
                            $idReservaTarifas=$tarifas[$i]['idReservaTarifas'];
      $horario=getReservaHorariosId($tarifas[$i]['idReservaHorarios']);
  
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
        $totalComprobantes=getComprobantesIdReserva($idReserva);
$totalReserva=ConvierteMoneda($reserva[0]["monedaSel"],$_SESSION["moneda_sel"], $reserva[0]["total"]);
$diferenciaComprobantesPrecio=$totalReserva-$totalComprobantes;

                                       $claseBoton="btn btn-warning";
                                        $textoBoton="Pendiente";
                                        if ($diferenciaComprobantesPrecio<1 && $totalReserva > 0) {
                                         $claseBoton="btn btn-success";
                                          $textoBoton="Confirmada";
                                        }


      $fechaAlta= date( "d-m-Y", strtotime( $reserva[0]["fechaAlta"] ) );
                     for ($j=0; $j < count($pasajeros); $j++) { 
                     


                        	 	?>



                  <tr data-child-name="row0" data-child-value="<table>


                            <tr>
                              <td>Fecha De Contratación</td> 
                              
                              <td>Pax Responsable</td> 
                               <td>Pais</td> 
                                <td>Idioma</td> 
                            </tr>
                             <tr>    
                              <td><?=$fechaAlta?></td>
                                <td><?=$reserva[0]["nombreResponsable"]?> <?=$reserva[0]["apellidoResponsable"]?> </td>
                                 <td>?????no tenemos este dato todavia </td>
                                  <td>????no tenemos este dato todavia</td>
                      
                              
                            </tr>
                            <tr>
                              <td>Total</td>
                              <td>Pago</td>
                          
                              <td>A Pagar</td> 
                            </tr>
                            <tr>
                              <td><?=$_SESSION["moneda_sel_sym"].$totalReserva;?></td>
                              <td><?=$_SESSION["moneda_sel_sym"].$totalComprobantes;?></td>
                             
                              <td><?=$_SESSION["moneda_sel_sym"].$diferenciaComprobantesPrecio;?></td>
                            </tr>
                            <tr>
                              <td>Tipo De Tarifa</td>
                              <td>Valor Comisionable</td>
                            
                              <td>Canal de Venta</td> 
                            </tr>
                            <tr>
                              <td><?=$tarifa2[0]["nombre"]?> (<?=$fromEdad?> a <?=$toEdad?> Años)</td>
                              <td><?=$_SESSION["moneda_sel_sym"].$comisionVendedor;?></td>
                         
                              <td></td>
                            </tr>
                          </table>">
                  	<td class="details-control"><?=$pasajeros[$j]["nombrePasajero"]?> <?=$pasajeros[$j]["apellidoPasajero"]?> </td>
                    <td class="details-control"><?=$tarifa2[0]["nombre"]?> (<?=$fromEdad?> a <?=$toEdad?> Años)</td>                
                  	<td class="details-control"><?=$reserva[0]["codigoAmigable"]?></td>
                    <td class="details-control"><?=$reserva[0]["telefonoResponsable"]?></td>
                    <td class="details-control"><?=$_SESSION["moneda_sel_sym"].$totalReserva;?></td>
                    <td class="details-control"><?=$_SESSION["moneda_sel_sym"].$totalComprobantes;?></td>
                    <td class="details-control"><?=$_SESSION["moneda_sel_sym"].$diferenciaComprobantesPrecio;?></td>
                    <td class="details-control"><?=$cancelacion;?></td>
                    <td> <button type="button" class="<?= $claseBoton;?>"><?= $textoBoton;?></button></td> 
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

              <div class="row no-print">
                <div class="col-12">
                 <!-- tengo este archivo de invoice-print.html --> 
                 <a href="invoice-print.html" rel="noopener" target="_blank" class="btn btn-success"><i class="fas fa-print"></i> Imprimir</a>
                  <button type="button" class="btn btn-primary float-right" style="margin-right: 5px;">
                    <i class="fas fa-download"></i> Generar PDF </button>
                </div>
               </div>
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




<!--<?php
$prestadores=getPrestadores();
for($i=0;$i < count($prestadores); $i++){
 $idPrestador=$prestadores[$i]["idPrestador"];
 $idUsuario=$prestadores[$i]["idUsuario"];
 $usuario=getUsuario($idUsuario);
    ?>
    
    <tr> 
   <td> <?= $prestadores[$i]["nombre"]; ?> </td>
   <td> <?= $prestadores[$i]["razonSocial"]; ?> </td>

   <td> <?= $prestadores[$i]["documento"]; ?> </td>
   <td> <?= $prestadores[$i]["telefono"]; ?> </td> 
   <td> <?= $prestadores[$i]["email"]; ?> </td>
   <td> <?= $prestadores[$i]["observaciones"]; ?> </td>
  
   <td> <?=  $usuario[0]["email"]; ?> </td>
      <td> <?= $prestadores[$i]["celular"]; ?> </td>
         <td> <?= $prestadores[$i]["facebook"]; ?> </td>
            <td> <?= $prestadores[$i]["instagram"]; ?> </td>
               <td> <?= $prestadores[$i]["web"]; ?> </td>
      <td>
          <a class="btn-sm btn-danger"onclick="borraPrestador('<?=$idPrestador;?>')"><i class="fas fa-trash"></i> Eliminar</a>
      </td>
</tr>   
    
    <?php
}
?>



    
<script type="text/javascript">



function uploadForm(){

$("#formulario").submit();
}
            function borraPrestador(idPrestador){
                      
var parametros={"borraPrestador" : idPrestador};   
Swal.fire({
title: 'Esta seguro?',
text: 'Esta accion no se puede revertir!',
icon: 'warning',
showCancelButton: true,
confirmButtonColor: '#3085d6',
cancelButtonColor: '#d33',
confirmButtonText: 'Sí, borrar!'
}).then((result) => {

if (result.value) {
 $.post("./ctrl/ctrl_prestador.php",
parametros,
function(data, status){
console.log(data);
 if (data>0) {
  location.href = 'prestadores.php';
 }
});
 Swal.fire(
   'Eliminado!',
   'El prestador se elimino.',
   'success'
 )
}
})   
     
       }


           </script>
         
                </tfoot>
              </table>
              <!-- /.col -->
            </div>

            <!-- /.row -->
          </div>
          <!-- /.card-body -->
          <div class="card-footer">
         <!-- <div align="center"> <button type="submit" id="uploadfiles" value="Crear servicio" class="btn btn-success"><i class="fa fa-floppy-o" aria-hidden="true"></i> Continuar</button>   <a href="servicios.php" class="btn btn-danger" ><i class="fa fa-times" aria-hidden="true"></i> Salir sin guardar</a>
</div>-->
          </div>
        </div>
        <!-- /.card -->

        <!-- /.row -->
      


  <?php 
  include("includes/footer.php"); ?>




}