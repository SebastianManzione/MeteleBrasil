
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

if ($_SESSION['login']["idUsuario"]==1) {
$idPrestador=(-5);

}else{
  $idPrestador=  $_SESSION['login']['idPrestador'];
}


  $hoy=strtotime(date('Y-m-d'));
$fecha_actual = date("d-m-Y");
$desde=date("Y-m-d",strtotime($fecha_actual."- 1 month")); 
$hasta=date("Y-m-d");
$prestadores=getPrestadores();
$totalComisionesVendedor=0;
$totalComisionesSistema=0;

$reservas=getReservasConfirmadas($idPrestador);
 if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["verComoPrestador"]) ) {
$idPrestador=$_POST['idPrestador'];
$desde=$_POST['desde'];
$hasta=$_POST['hasta'];
$reservas=getReservasConfirmadasFechasPrestador($_POST['idPrestador'],$_POST['desde'],$_POST['hasta']);
} 

$idMoneda="";

 ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Financiero Prestador</h1>
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
              <?php if ($_SESSION['login']["idUsuario"]==1) {
?>
<form method="post">
  <select name="idPrestador">

    <?php 
 
    foreach ($prestadores as $key => $value) {
      $selected= '';
   if ($value['idPrestador'] == $idPrestador) {
    $selected= 'selected';
   }
      ?>

 <option value="<?=$value['idPrestador']?>" <?=$selected;?>><?=$value["nombre"];?> </option>
      <?php
    } ?>
    
  </select>
 <input type="date" name="desde" value="<?=$desde;?>">-<input type="date" name="hasta" value="<?=$hasta?>">-
  <button class="btn btn-success" name="verComoPrestador">Ver como...</button>
</form>

<?php
} ?>
</div>

            <div class="row">
     
            
<div class="table-responsive">
  <table class="table" id="tablaCarrito">
       <thead>
      <tr>
        <th></th>
        <th>Periodo</th>
        <th> <?=date("d/m/Y",strtotime($desde));?></th>
        <th> <?=date("d/m/Y",strtotime($hasta));?></th>
        <th></th>
        <th></th>
      </tr>
    </thead>
    <thead>


      <tr>

                    <th scope="col"><?=$lang["cod-carrito"];?></th>
                    <th scope="col"><?=$lang["fecha"];?></th>
                    <th scope="col">Responsavél</th>
                    <th scope="col">Periodo</th>
                    <th scope="col">Serviço</th>
                    <th scope="col">Data compra</th>

                    <th scope="col">Valor referente de Comissão</th>
                    <th scope="col">Valor pagar Fornecedor</th>
                    <th scope="col"><?=$lang["detalles"];?></th>

      </tr>
    </thead>
    <tbody>
<?php
$totalAPAgarPrestador=0;
 for ($i=0; $i < count($reservas); $i++) { 

  $idReserva=$reservas[$i]['idReserva'];
  $horarios=getReservaHorarios($idReserva);
  for ($j=0; $j < count($horarios); $j++) { 
 
              $idReservaHorarios=$horarios[$j]["idReservaHorarios"];
              $salida=getSalida($horarios[$j]["idServicioSalidas"]);
              $servicio=getServicio($salida[0]["idServicio"]);
                 $fechaEvento=strtotime($salida[0]['fecha']);
                   $tarifas=getReservaTarifas($idReservaHorarios);
                   for ($k=0; $k < count($tarifas); $k++) { 
                
                        $nombre_tarifa=($tarifas[0]["nombre"]);
                        $monedaSel=$tarifas[0]["monedaSel"];
                        $valorSinIva=$tarifas[0]["valorSinIva"];
                       $cantidad=($tarifas[0]["cantidad"]);
                        $totalTarifa=$valorSinIva*$cantidad;
$total=ConvierteMoneda($tarifas[0]["monedaSel"],$_SESSION["moneda_sel"], $reservas[$i]["total"]);
                     $comisionIndividual=ConvierteMoneda($monedaSel,$_SESSION["moneda_sel"], $tarifas[0]['comisionVendedor']); 
                          $valorReferente=ConvierteMoneda($monedaSel,$_SESSION["moneda_sel"], $tarifas[0]['valorSinIva']);
                          $valorPagarPrestador=$tarifas[0]['valorSinIva']-$tarifas[0]['comisionSistema']-$tarifas[0]['comisionVendedor'];


 }
                    if ($salida[0]["idPrestador"]==$idPrestador  ) {
                    $totalAPAgarPrestador+=$valorPagarPrestador;
                   

?>



                                 <tr> 
                                  <td><?=$reservas[$i]["codigoAmigable"]?></td>
                                  <td> <?=date("d/m/Y H:i", strtotime($reservas[$i]['fechaAlta']));?></td>
                                    <td><?=$reservas[$i]["nombreResponsable"]." ".$reservas[$i]["apellidoResponsable"]?></td>
                                    <td><?=$salida[0]['nombre'];?></td>
                                    <td><?=$servicio[0]["nombre_servicio"];?></td>
                                    <td><?=date("d/m/Y", strtotime($salida[0]['fecha']))?></td>
                                    <td><?=$_SESSION["moneda_sel_sym"].$valorReferente;?></td> 
                                   <td><?=$_SESSION["moneda_sel_sym"].$valorPagarPrestador;?></td> 
                                    <td><form method="post" action="voucherPrestador"><button type="submit" class="btn btn-info" name="idReservaHorarios" value="<?=$idReservaHorarios;?>"><?=$lang["voucher_prestador"];?></button></form></td>
                             

                             </tr>

                             <?php
                                          }    
  }

} ?>



  
                  
                  </tbody>
                                   <tfoot>
    <tr>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
       <td></td>
          <td></td>
      <td>Valor a Pagar Prestador: </td>
      <td><?=$_SESSION["moneda_sel_sym"].$totalAPAgarPrestador;?></td>
    </tr>
  </tfoot>
                </table>


              </div>
              <!-- /.card-body -->
        
        

<script type="text/javascript">
    function format(value) {
      return value  ;
  }
  $(document).ready(function () {
      var table = $('#tablaCarrito3').DataTable({});

      // Add event listener for opening and closing details
      $('#tablaCarrito3').on('click', 'td.details-control', function () {

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



<script type="text/javascript">
var tableToExcel = (function() {
  var uri = 'data:application/vnd.ms-excel;base64,'
    , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>'
    , base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
    , format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }
  return function(table, name) {
    if (!table.nodeType) table = document.getElementById(table)
    var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
    window.location.href = uri + base64(format(template, ctx))
  }
})()
</script>

<input type="button" onclick="tableToExcel('tablaCarrito', 'Comisiones Vendedor')" value="Exportar a Excel">
              <!-- /.col -->
            </div>

            <!-- /.row -->
          </div>
          <!-- /.card-body -->


          <div class="card-footer">
         <!-- <div align="center"> <button type="submit" id="uploadfiles" value="Crear servicio" class="btn btn-success"><i class="fa fa-floppy-o" aria-hidden="true"></i> Continuar</button>   <a href="servicios.php" class="btn btn-danger" ><i class="fa fa-times" aria-hidden="true"></i> Salir sin guardar</a>
