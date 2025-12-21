
<?php 


include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");
require("classes/functions.php");
require("classes/prestador.php");
require("classes/usuario.php");
require("classes/reserva.php");
require("classes/salidas.php");
require("classes/categoria.php");

require("classes/servicio.php");
require("classes/comprobantes.php");
require("classes/convierte_monedas.php");
require("classes/origenes_comprobantes.php");
if (!$_SESSION["login"]["rol"]==1) {
  alertar($lang["usted_no_tiene_acceso"], "error");
  redireccionarLento("index");
}

 ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark"><?=$lang["comprobantes_de_pagos"];?></h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#"><?=$lang["administracion"];?></a></li>
              <li class="breadcrumb-item active"><?=$lang["comprobantes"];?></li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <section class="content">
      <div class="container-fluid">
        <!-- SELECT2 EXAMPLE -->


        <!-- /.Responsive -->
        <!-- SELECT2 EXAMPLE -->
        <div class="card card-default">
          <div class="card-header">
            <h3 class="card-title"><?=$lang["comprobantes"];?></h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
              <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <div class="row">
     
            
<div class="table-responsive">
  <table class="table" id="tablaCarrito">
    <thead>
      <tr>

                    <th scope="col"><?=$lang["fecha"];?></th>
                    <th scope="col"><?=$lang["valor_pago"];?></th>
                    <th scope="col"><?=$lang["cod-carrito"];?></th>
                    <th scope="col"><?=$lang["origen"];?></th>
                    <th scope="col"><?=$lang["codigo_pasarela"];?></th>
                    <th scope="col"><?=$lang["detalles"];?></th>

      </tr>
    </thead>
    <tbody>
      <?php 
$comprobantes=getComprobantes();

for ($i=0; $i < count($comprobantes); $i++) { 
 
 $idReserva=$comprobantes[$i]["idReserva"];
  $reserva=getReservaId($idReserva);


   $codigoAmigable="RESERVA ELIMINADA";
   $fecha_reserva="RESERVA ELIMINADA";
     $nombreResponsable="";
   $total=0;
   $precio=0;
  if (count($reserva) > 0) {
    $codigoAmigable=$reserva[0]["codigoAmigable"];
    $fecha_reserva=date("d-m-Y", strtotime($reserva[0]["fechaAlta"]));
    $total=$reserva[0]["total"];
    $precio=ConvierteMoneda($reserva[0]["monedaSel"],$_SESSION["moneda_sel"], $total);
    $nombreResponsable=$reserva[0]["nombreResponsable"]." ".$reserva[0]["apellidoResponsable"];
  }
$fechaIngreso=date("d-m-Y", strtotime($comprobantes[$i]["fechaIngreso"]));
$idMonedaOrigen=$comprobantes[$i]["monedaComprobante"];
$totalComprobanteMonedaOrigen=$comprobantes[$i]["total"];
 $totalComprobanteConvertido=ConvierteMoneda($idMonedaOrigen,$_SESSION["moneda_sel"], $totalComprobanteMonedaOrigen);	

 $reserva=getReservaId($idReserva);


 $monedaComprobante=getMoneda($idMonedaOrigen);
 $origenComprobante=getOrigenComprobante($comprobantes[$i]["origenComprobante"])[0]["nombre"];






  $totalComprobantes=getComprobantesIdReserva($idReserva);
  $diferenciaComprobantesPrecio=$precio-$totalComprobantes;


$claseBoton="btn btn-warning";
                                        $textoBoton="Pendiente";
                                        if ($diferenciaComprobantesPrecio<1 && $precio > 0) {
                                         $claseBoton="btn btn-success";
                                          $textoBoton="Confirmada";
                                        }


$trs='';

	$trs=$trs.' 
                                                    <tr class="table-secondary">
                                                    
                                                    <td>'.$nombreResponsable.'</td>
                                                    <td>'.$fecha_reserva.'</td>
                                                    <td>'.$_SESSION["moneda_sel_sym"].round($precio,2).'</td>
                                                    <td>'.$_SESSION["moneda_sel_sym"].round($diferenciaComprobantesPrecio,2).'</td>
                                                    <td> <button type="button" class="'.$claseBoton.'">'.$textoBoton.'</button></td>
                                                  	<td class="details-control"> <form method="post" action="carritoDetalles">
                                                    <button class="btn btn-success" name="detallesCarrito" value="'.$idReserva.'" >Ver carrito</button>
                                                    </form>
                                                    </td>

                                                    </tr> ';
	

$dataChildValue=' <div class="table-responsive">                                      
                                                    <h5 class="m-0 text-dark">Detalles de la reserva</h5> <table class="table">
                                                      <tr>
                                                        <th scope="col">Nombre responsable</th>
                                                        <th scope="col">Fecha de contratación</th>
                                                        <th scope="col">Total</th>
                                                        <th scope="col">Resta pagar</th>
                                                        <th scope="col">Estado</th>
                                                        <th scope="col">Accion</th>

                                                      </tr>
                                          '.$trs.'                                                
                            </div>';

?>

      <tr class="accordion-toggle collapsed" id="accordion1" data-toggle="collapse" data-parent="#accordion1" href="#collapseOne"  
                          data-child-name="row0"
                            data-child-value='<?=$dataChildValue;?>'>

                                 <td><?=$fechaIngreso;?></td>
                                 <td><?=$monedaComprobante[0]["Symbol"].round($totalComprobanteMonedaOrigen,2);?></td>
                                 <td><?=$codigoAmigable;?></td>
                                 <td><?= $origenComprobante;?></td>
                              <td> <?=$comprobantes[$i]["compOrigen"];?></td>
                                 <td class="details-control"><input id="boton" type="submit" name="proceso" class="btn btn-info" value="Ver"></td>                              

</tr>

<?php
}
//print_r($reservas);
       ?>



  
                  
                  </tbody>
                </table>


              </div>
              <!-- /.card-body -->
        
         

<script type="text/javascript">
    function format(value) {
      return value  ;
  }
  $(document).ready(function () {
      var table = $('#tablaCarrito').DataTable({});

      // Add event listener for opening and closing details
      $('#tablaCarrito').on('click', 'td.details-control', function () {

          var tr = $(this).closest('tr');
          var row = table.row(tr);

          if (row.child.isShown()) {
              // This row is already open - close it
              row.child.hide();
              tr.removeClass('shown');
          } else {
              // Open this row
              row.child(format(tr.data('child-value'))).show();
              tr.addClass('shown');
          }
      });
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
