
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
if ($_SERVER["REQUEST_METHOD"]=="POST") {

 if($prestador>1){
alertar("Prestador guardado con exito", "success");
 }


}

 ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Lista de carritos</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Administración</a></li>
              <li class="breadcrumb-item active">Lista de carritos</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <section class="content">
      <div class="container-fluid">
        <!-- SELECT2 EXAMPLE -->

<div class="row">
          <div class="col-12">
            <div class="card"  style="display: none;">
              <div class="card-header">
                <h3 class="card-title">Seleccione el mes que desea explorar</h3>
              </div>
              <div class="table-responsive">
              <div class="card-body">
                <ul class="pagination pagination-month justify-content-center" style="display: none;">
                  <li class="page-item"><a class="page-link" href="#">«</a></li>
                  <li class="page-item">
                      <a class="page-link" href="#">
                          <p class="page-month">Jan</p>
                          <p class="page-year">2020</p>
                      </a>
                  </li>
                  <li class="page-item active">
                      <a class="page-link" href="#">
                          <p class="page-month">Feb</p>
                          <p class="page-year">2020</p>
                      </a>
                  </li>
                  <li class="page-item">
                      <a class="page-link" href="#">
                          <p class="page-month">Mar</p>
                          <p class="page-year">2020</p>
                      </a>
                  </li>
                  <li class="page-item">
                      <a class="page-link" href="#">
                          <p class="page-month">Apr</p>
                          <p class="page-year">2020</p>
                      </a>
                  </li>
                  <li class="page-item">
                      <a class="page-link" href="#">
                          <p class="page-month">May</p>
                          <p class="page-year">2020</p>
                      </a>
                  </li>
                  <li class="page-item">
                      <a class="page-link" href="#">
                          <p class="page-month">Jun</p>
                          <p class="page-year">2020</p>
                      </a>
                  </li>
                  <li class="page-item">
                      <a class="page-link" href="#">
                          <p class="page-month">Jul</p>
                          <p class="page-year">2020</p>
                      </a>
                  </li>
                  <li class="page-item">
                      <a class="page-link" href="#">
                          <p class="page-month">Aug</p>
                          <p class="page-year">2020</p>
                      </a>
                  </li>
                  <li class="page-item">
                      <a class="page-link" href="#">
                          <p class="page-month">Sep</p>
                          <p class="page-year">2020</p>
                      </a>
                  </li>
                  <li class="page-item">
                      <a class="page-link" href="#">
                          <p class="page-month">Oct</p>
                          <p class="page-year">2020</p>
                      </a>
                  </li>
                  <li class="page-item">
                      <a class="page-link" href="#">
                          <p class="page-month">Nov</p>
                          <p class="page-year">2020</p>
                      </a>
                  </li>
                  <li class="page-item">
                      <a class="page-link" href="#">
                          <p class="page-month">Dec</p>
                          <p class="page-year">2020</p>
                      </a>
                  </li>
                  <li class="page-item"><a class="page-link" href="#">»</a></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <!-- /.row -->
        </div>
        <!-- /.Responsive -->
        <!-- SELECT2 EXAMPLE -->
        <div class="card card-default">
          <div class="card-header">
            <h3 class="card-title">Carritos</h3>

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
                    <th scope="col">Servicios</th>
                    <th scope="col">COD-carrito</th>
                    <th scope="col">Responsable</th>
                    <th scope="col">Fecha de Compra                
                    <th scope="col">Total</th>
                    <th scope="col">RESTA PAGAR</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Accion</th>

      </tr>
    </thead>
    <tbody>
      <?php 
$reservas=getReservas(); 
for ($i=0; $i < count($reservas); $i++) { 

	$idReserva=$reservas[$i]["idReserva"];




 
$monedaSel=$reservas[$i]["monedaSel"];
$total=$reservas[$i]["total"];
$impuestos=$reservas[$i]["impuestos"];
$precio=ConvierteMoneda($monedaSel,$_SESSION["moneda_sel"], $total);
  $totalComprobantes=getComprobantesIdReserva($idReserva);
  $diferenciaComprobantesPrecio=$precio-$totalComprobantes;
$horariosReserva=getReservaHorarios($idReserva);

$trs='';
for ($j=0; $j < count($horariosReserva); $j++) { 

	$idServicioSalidas=$horariosReserva[$j]["idServicioSalidas"];
	$salida=getSalida($idServicioSalidas);
  $fecha_salida=date("d-m-Y", strtotime($salida[0]["fecha"]));
	$servicio=getServicio($salida[0]["idServicio"]);
  $idCategoria_servicio=$servicio[0]["idCategoria_servicio"];
  $categoria_servicio=getCategoria($idCategoria_servicio);
  $nombre_categoria_servicio=$categoria_servicio[0]["nombre_categoria_servicio"];
	$nombreServicio=$servicio[0]["nombre_servicio"];
	$trs=$trs.' 
          <tr class="table-secondary">
            <td>'.$nombreServicio.'</td>
            <td>'.$nombre_categoria_servicio.'</td>
            <td>'.$fecha_salida.'</td>
          	<td class="details-control"><a class="btn btn-success">Detalle Salida</a></td>

          </tr> ';
	
}
$dataChildValue=' <div class="table-responsive">                                      
                                                    <h5 class="m-0 text-dark">Servicios Contratados</h5> <table class="table">
                                                  <tr>
                                                    <th scope="col">Servicio</th>
                                                    <th scope="col">Categoria</th>
                                                    <th scope="col">Fecha del Evento</th>
                                                    <th scope="col">Acción</th>
                                                  </tr>
                                          '.$trs.'                                                
                            </div>';

?>

      <tr class="accordion-toggle collapsed" id="accordion1" data-toggle="collapse" data-parent="#accordion1" href="#collapseOne"  
                          data-child-name="row0"
                            data-child-value='<?=$dataChildValue;?>'>

                                 <td class="details-control"><input id="boton" type="submit" name="proceso" class="btn btn-info" value="Ver"></td>
                                 <td><?=$reservas[$i]["codigoAmigable"]?></td>
                                 <td><?=$reservas[$i]["nombreResponsable"]." ".$reservas[$i]["apellidoResponsable"]?></td>

                                 <td> <?=date("d-m-Y", strtotime($reservas[$i]['fechaAlta']));?></td>
                                 <td><?=$_SESSION["moneda_sel_sym"].$precio;?></td>
                                 <td><?=$_SESSION["moneda_sel_sym"].$diferenciaComprobantesPrecio;?></td>
                                 <?php $claseBoton="btn btn-warning";
                                        $textoBoton="Pendiente";
                                        if ($diferenciaComprobantesPrecio<1 && $precio > 0) {
                                         $claseBoton="btn btn-success";
                                          $textoBoton="Confirmada";
                                        }
                                  ?>
                                 <td> <button type="button" class="<?= $claseBoton;?>"><?= $textoBoton;?></button></td> 
                                 <td class="details-control"> <form method="post" action="carritoDetalles"> <button class="btn btn-primary" name="detallesCarrito" value="<?=$idReserva;?>">Detalles carrito</a></form> </td>
                              

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
</div>-->





          </div>
        </div>
        <!-- /.card -->

      </div><!-- /.container-fluid -->
    </section>
        </div>
        <!-- /.row (main row) -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <?php 
  include("includes/footer.php"); ?>




