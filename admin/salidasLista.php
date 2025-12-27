
<?php 
// Verificar permisos de acceso ANTES de cualquier salida
require_once(__DIR__ . "/classes/permisos.php");
require_once(__DIR__ . "/includes/permisos_helper.php");
$permisos = new PermisosManager($GLOBALS['pdo'], $_SESSION['login'] ?? []);
$permisos->verificarAcceso('salidasLista');

include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");
require_once("classes/functions.php");
require_once("classes/prestador.php");
require_once("classes/usuario.php");
require_once("classes/reserva.php");
require_once("classes/salidas.php");
require_once("classes/categoria.php");
require_once("classes/servicio.php");
if ($_SERVER["REQUEST_METHOD"]=="POST") {

 if($prestador>1){
alertar($lang["prestador_guardado_con_exito"], "success");
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
            <h1 class="m-0 text-dark"><?=$lang["salidas"];?></h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#"><?=$lang["administracion"];?></a></li>
              <li class="breadcrumb-item active"><?=$lang["salidas"];?></li>
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
            <h3 class="card-title"><?=$lang["salidas"];?></h3>

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

                    <th scope="col"><?=$lang["fecha_de_contratacion"];?></th>
                    <th scope="col"><?=$lang["servicios"];?></th>
                    <th scope="col"><?=$lang["cod-servicio"];?></th>
                    <th scope="col"><?=$lang["fecha_de_salida"];?></th>
                    <th scope="col"><?=$lang["horario_de_salida"];?></th>
                    <th scope="col"><?=$lang["horario_de_check_in"];?></th>
                    <th scope="col"><?=$lang["resta_pagar"];?></th>
                    <th scope="col"><?=$lang["estado_"];?></th>
                    <th scope="col"><?=$lang["detalles"];?></th>

      </tr>
    </thead>
    <tbody>
      <?php 
$reservas=getReservas();
for ($i=0; $i < count($reservas); $i++) { 
	$idReserva=$reservas[$i]["idReserva"];
getMoneda($reservas[$i]["monedaSel"]);
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
                                                    <td>'.$nombre_categoria_servicio.'</td>
                                                  	<td class="details-control"><a class="btn btn-success" href="pasajerosLista">IR</a></td>

                                                    </tr> ';
	
}
$dataChildValue=' <div class="table-responsive">                                      
                                                    <h5 class="m-0 text-dark">>Lista de Pax</h5> <table class="table">
                                                      <tr>
                                                        <th scope="col">Nombre</th>
                                                        <th scope="col">Apellido</th>
                                                        <th scope="col">Resposable de Reserva</th>
                                                      <th scope="col">Lista de Embarque</th>

                                                      </tr>
                                          '.$trs.'                                                
                            </div>';

?>

      <tr class="accordion-toggle collapsed" id="accordion1" data-toggle="collapse" data-parent="#accordion1" href="#collapseOne"  
                          data-child-name="row0"
                            data-child-value='<?=$dataChildValue;?>'>

                                 <td>22/12/2021</td>
                                 <td><?=$reservas[$i]["codigoAmigable"]?></td>
                                 <td><?=$reservas[$i]["nombreResponsable"]." ".$reservas[$i]["apellidoResponsable"]?></td>
                                 <td> <?=date("d-m-Y", strtotime($reservas[$i]['fecha']));?></td>
                                 <td>.....</td>
                                 <td>.....</td>
                                 <td>.....</td>
                                 <td> <button type="button" class="btn btn-warning"><?=$lang["pendientes"];?></button></td> 
                                 <td class="details-control"><input id="boton" type="submit" name="proceso" class="btn btn-info" value="VER"></td>                              

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
