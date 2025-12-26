

<?php 
// Verificar permisos de acceso ANTES de cualquier salida
require_once(__DIR__ . "/classes/permisos.php");
require_once(__DIR__ . "/includes/permisos_helper.php");
$permisos = new PermisosManager($GLOBALS['pdo'], $_SESSION['login'] ?? []);
$permisos->verificarAcceso('cupones');

include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");

require("classes/functions.php");
require("classes/prestador.php");
require("classes/usuario.php");
require("classes/reserva.php");
require("classes/salidas.php");
require("classes/servicio.php");
require("classes/accesibilidad.php");
require("classes/convierte_monedas.php");
require("classes/cupones_descuento.php");
require("classes/generador_aleatorio.php");
if (!$_SESSION["login"]["rol"]==1) {

  alertar("Usted no tiene acceso a esta seccion del software", "error");

  redireccionarLento("index");

}


if ($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST["addCupon"])) {
  $idUsuario=$_POST["idUsuario"];
  $descuentoPorcentual=$_POST["descuentoPorcentual"];
  
  $cupones=GeneraCupones(1, $idUsuario, $descuentoPorcentual);

  for ($i=0; $i < count($cupones); $i++) { 
    $cupon=getCupon($cupones[$i]);
    $codigo = !empty($cupon) && isset($cupon[0]["CodigoAmigable"]) ? $cupon[0]["CodigoAmigable"] : "";
    $cuponUsuarioId = !empty($cupon) && isset($cupon[0]["idUsuario"]) ? (int)$cupon[0]["idUsuario"] : 0;
    $usuario = $cuponUsuarioId > 0 ? getUsuario($cuponUsuarioId) : [];
    $emailUsuario = !empty($usuario) && isset($usuario[0]["email"]) ? $usuario[0]["email"] : "";
    $descuento = !empty($cupon) && isset($cupon[0]["descuentoPorcentual"]) ? $cupon[0]["descuentoPorcentual"] : 0;
    ?>

Cupon generado <?=$i+1?> <br>
Codigo: <?=$codigo;?> <br>
Usuario: <?=$emailUsuario;?> <br>
Descuento: <?=$descuento;?>% <br>

    <?php 
  }
}


$cupones= getCuponesDescuento();
 ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Cupones</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Administración</a></li>
              <li class="breadcrumb-item active">Cupones</li>
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
                


<button type="button" class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#exampleModal" data-whatever="agregarNota">Generar Cupones</button>          

                                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                      <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                          <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Generador de Cupones</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                              <span aria-hidden="true">&times;</span></button>
                                          </div>
                                          <h1>Generador de cupones </h1>
                                   

                                          <div class="modal-body">
                                            <form method="post">
                                              <div class="form-group">
                                                
                                                  <label for="recipient-name" class="col-form-label">Usuario:</label>
                                                   <select name="idUsuario">
                                                  <?php $usuarios=getUsuarios();
                                                  for ($i=0; $i < count($usuarios); $i++) { 
                                                    $idUsuario=$usuarios[$i]["idUsuario"];
                                                    $email=$usuarios[$i]["email"];
                                                    ?>  <option value="<?=$idUsuario?>"><?=$email;?></option><?php
                                                  } ?>
                                                
                                                  
                                                 </select>
                                             </div>
                                                   <div class="form-group">
                                                
                                                  <label for="recipient-name" class="col-form-label">Descuento %</label>
                                                   <input name="descuentoPorcentual" type="number" step="1" min="0" max="25">
                                                                                                   
                                                
                                             </div>
                                            
                                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                            <button type="submit" name="addCupon" class= "btn btn-primary">Agregar</button>
                                            </form>
                                          </div>
                                    
                                        </div>
                                      </div>
                                    </div>

        <button type="button" class="btn btn-secondary btn-lg btn-block" data-card-widget="collapse">Lista de edades</button>

</div>
<!-- /.card-header -->


        <div class="card-body">
            <div class="row">
                  <div class="table-responsive">   
                            
                            <table class="table" id="tablaCarrito">
                              <thead>
                                <tr>
                             
                                   <th scope="col">Codigo Amigable</th>
                                   <th scope="col">Usuario</th>
                                   <th scope="col">% de descuento</th>              
                              

                                </tr>
                              </thead>
                  
                           <tbody>
<?php 

for ($i=0; $i < count($cupones); $i++) { 
  $idCuponDescuento=$cupones[$i]["idCuponDescuento"];
  $CodigoAmigable=$cupones[$i]["CodigoAmigable"];
  $descuentoPorcentual=$cupones[$i]["descuentoPorcentual"];
  $idUsuario=$cupones[$i]["idUsuario"];
  $usuario=getUsuario($idUsuario);
  $emailUsuario = (!empty($usuario) && isset($usuario[0]["email"])) ? $usuario[0]["email"] : "";

?>



                             <tr>
                                <td ><?=$CodigoAmigable;?></td>
                                 <td ><?=$emailUsuario;?> </td>
                                  <td ><?=$descuentoPorcentual;?>%</td>
        
                             </tr>                    

<?php
} ?>

                                           
                         </tbody>
                         </table>
                                    </div>
                                    </div>
                                    </div><!-- /.card-body -->
             



                          <script type="text/javascript">



            function borrar(idAccesibilidad){
           


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
  var formulario="#borra"+idAccesibilidad
 $(formulario).submit();
}
else{
  return false;
}

})   
    
       }


           </script>                       
                                  
                                   

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


                                             </div>   
                                            </div>   
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



                     <!-- /.card -->
         </div><!-- /.container-fluid -->
    
  <?php 
   include("includes/footer.php"); ?>