
<?php 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");
require("classes/functions.php");
require("classes/categoria.php");
require("classes/texto_miniaturas.php");
require("classes/tipos_tarifa.php");
require("classes/accesibilidad.php");
require("classes/comision_prestador.php");
require("classes/idiomas.php");
require("classes/edades.php");
require("classes/destinos.php");
 require("classes/servicio.php"); 
 require("classes/fotos_servicio.php"); 
 require("classes/prestador.php"); 
 require("classes/usuario.php"); 
  require("classes/comisiones.php"); 
   require("classes/reserva.php");


$fecha_actual = date("d-m-Y");
$desde=date("Y-m-d",strtotime($fecha_actual."- 1 month")); 
$hasta=date("Y-m-d");
$usuarios=getUsuariosVendedores();
$totalComisionesVendedor=0;
$totalComisionesSistema=0;

if ($_SESSION["login"]["rol"]==1) {

  $comisiones=getReservasConfirmadasIdUsuarioCupon(-5);
}
else{
  $comisiones=getReservasConfirmadasIdUsuarioCupon($_SESSION["login"]["idUsuario"]);
}

 if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["verComoUsuario"]) ) {
$idUsuarioSeleccionado=$_POST['idUsuarioSeleccionado'];
$desde=$_POST['desde'];
$hasta=$_POST['hasta'];
$comisiones=getReservasConfirmadasIdUsuarioCupon($_POST['idUsuarioSeleccionado']);

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
            <h3 class="card-title"><?=$lang["comprobantes"];?> </h3> 

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
              <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">

            <?php if ($_SESSION['login']["idUsuario"]==1) {
?>
<form method="post">
  <select name="idUsuarioSeleccionado">
    <option value="-5">METELEBRASIL</option>
    <?php 
 
    foreach ($usuarios as $key => $value) {
      $selected= '';
   if ($value['idUsuario'] == $idUsuarioSeleccionado) {
    $selected= 'selected';
   }
      ?>

 <option value="<?=$value['idUsuario']?>" <?=$selected;?>><?=$value["usuario"];?> </option>
      <?php
    } ?>
    
  </select>
 <input type="date" name="desde" value="<?=$desde;?>">-<input type="date" name="hasta" value="<?=$hasta?>">-
  <button class="btn btn-success" name="verComoUsuario">Ver como...</button>
</form>

<?php
} ?>
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
               <th scope="col">Data compra</th>
                    <th scope="col">Responsavél</th>
                    <th scope="col">Periodo</th>
                    <th scope="col">Serviço</th>
                    <th scope="col"><?=$lang["fecha"];?></th>

                    <th scope="col">Valor referente de Comissão</th>
                    <th scope="col">Comissão </th>

                  

      </tr>
    </thead>
    <tbody>
     <?php 
 $comisionIndividual=0;
foreach ($comisiones as $key => $value) {

  $horarios=getReservaHorariosInnerHorariosFechas($value['idReserva'], $desde, $hasta);

  foreach ($horarios as $clave => $valor) {
   //print_r($valor);
 $monedaSel=$valor['idMoneda'];
   $reservaTarifas=(getReservaTarifas($valor['idReservaHorarios']));
   $servicio=getServicio($valor['idServicioSeleccionado']);
   foreach ($reservaTarifas as $claveTarifas => $valorTarifas) {

    $comisionIndividual=ConvierteMoneda($monedaSel,$_SESSION["moneda_sel"], $valorTarifas['comisionVendedor']);
     $totalComisionesVendedor+=$comisionIndividual;
   $fechaCompra=date("d/m/Y",strtotime($value["fechaAlta"]));
   $nombreResponsable=$value['nombreResponsable']." ".$value['apellidoResponsable'];
   $periodo=$valor['nombre'];
   //echo "<br>";
   $nombre_servicio=$servicio[0]["nombre_servicio"];
   $fecha_salida=date("d/m/Y",strtotime($valor["fecha"]));
   $idMonedaSeleccionada=$valor["idMoneda"];
    $comisionIndividual=ConvierteMoneda($monedaSel,$_SESSION["moneda_sel"], $valorTarifas['comisionVendedor']);   
     $valorReferente=ConvierteMoneda($monedaSel,$_SESSION["moneda_sel"], $valorTarifas['valorSinIva']);
$valorPagarPrestador=$valorTarifas['valorSinIva']-$valorTarifas['comisionSistema']-$valorTarifas['comisionVendedor'];
$valorPagarPrestador=ConvierteMoneda($monedaSel,$_SESSION["moneda_sel"], $valorPagarPrestador);
   //echo "<br><br>";
      ?>
   

    <tr><a ></a>

               <td><?=$value["codigoAmigable"]?></td>

                    <td><?=$fechaCompra;?></td>

                     <td><?= $nombreResponsable;?></td>

                      <td><?= $periodo;?></td>    
                      <td><?= $nombre_servicio;?></td>              
  <td><?= $fecha_salida;?></td>    
  <td><?=$_SESSION["moneda_sel_sym"].$valorReferente;?></td>  
    <td><?=$_SESSION["moneda_sel_sym"].$comisionIndividual;?></td>  
       
      

                  </tr>

      <?php
   }
  }

}


 ?>



  
                  
                  </tbody>
                 <tfoot>
    <tr>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
       <td></td>
          <td></td>
      <td>comisiones total: </td>
      <td><?=$_SESSION["moneda_sel_sym"].$totalComisionesVendedor;?></td>
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



         
                </tfoot>
              </table>
              <!-- /.col -->
            </div>

            <!-- /.row -->
          </div>
          <!-- /.card-body -->


          <div class="card-footer">
         <!-- <div align="center"> <button type="submit" id="uploadfiles" value="Crear servicio" class="btn btn-success"><i class="fa fa-floppy-o" aria-hidden="true"></i> Continuar</button>   <a href="servicios.php" class="btn btn-danger" ><i class="fa fa-times" aria-hidden="true"></i> Salir sin guardar</a>
