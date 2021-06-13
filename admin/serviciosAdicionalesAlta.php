

<?php 


include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");
require("classes/functions.php");

require("classes/servicio.php");
require("classes/categoria.php");

require("classes/servicios_adicionales.php");

if (!$_SESSION["login"]["rol"]==1) {

  alertar("Usted no tiene acceso a esta seccion del software", "error");

  redireccionarLento("index");

}

if ($_SERVER["REQUEST_METHOD"]=="POST" ) {

  if (isset($_POST["setServicioAdicional"])) {

$nombre=$_POST["nombre"];
$descripcion_servicio_adicional=$_POST["descripcion_servicio_adicional"];
$servicio_adicional_resu=setServicioAdicional($nombre, $descripcion_servicio_adicional);
if ($servicio_adicional_resu>0) {
  alertar("Servicio Adicional guardado con éxito","success");
}
  }

  if (isset($_POST["eliminarServicioAdicional"])) {
   

$idServiciosAdicionales=$_POST["eliminarServicioAdicional"];


$getServiciosAdicionalesAllSalidas=getServiciosAdicionalesAllSalidas($idServiciosAdicionales);
$getServiciosAdicionalesAllCategorias=getServiciosAdicionalesAllCategorias($idServiciosAdicionales);

if (count($getServiciosAdicionalesAllSalidas)>0 ) {
    alertar("El servicio adicional que desea eliminar esta asignado a algunas salidas de servicio","error");

}
elseif (count($getServiciosAdicionalesAllCategorias)>0) {
  alertar("El servicio adicional que desea eliminar esta asignado a alguna categoria","error");
}
else{
  $servicio_adicional_resu=borraServicioAdicional($idServiciosAdicionales);
if ($servicio_adicional_resu>0) {
  alertar("Servicio Adicional eliminado con éxito","success");
}
}

  }


}


$servicios_adicionales=getServiciosAdicionales();


 ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h3 class="m-0 text-dark">Servicios adicionales</h3>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Editor</a></li>
              <li class="breadcrumb-item active">Servicios adicionales</li>
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
                


<button type="button" class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#exampleModal" data-whatever="agregarNota">Agregar servicio adicional</button>          

                                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                      <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                          <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Nuevo Servicio Adicional</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                              <span aria-hidden="true">&times;</span></button>
                                          </div>

                                          <div class="modal-body">
                                            <form method="post">
                                              <div class="form-group">
                                       
                                                  <label for="recipient-name" class="col-form-label">Nombre:</label>
                                                  <input type="text" name="nombre" class="form-control" id="recipient-name">
                                             </div>
                                                  <div class="form-group">
                                                     <label for="message-text" class="col-form-label">Descripcion del servicio adicional</label>
                                                        <textarea name="descripcion_servicio_adicional" class="form-control" id="message-text"></textarea>
                                                  </div>
                                             
                                            <div class="form-group">
                                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                            <button type="submit" name="setServicioAdicional" class= "btn btn-primary">Agregar</button>
                                          </div>
                                            </form>
                                          </div>
                                          
                                        </div>
                                      </div>
                                    </div>

        <button type="button" class= "btn btn-secondary btn-lg btn-block" data-card-widget="collapse">Lista de servicios adicionales</button>

</div>
<!-- /.card-header -->


        <div class="card-body">
            <div class="row">
                  <div class="table-responsive">   
                            
                            <table class="table" id="tablaCarrito">
                              <thead>
                                <tr>
                        
                                   <th scope="col">Nombre</th>
                             
                                   <th scope="col">Acción</th>

                                </tr>
                              </thead>
                  

                           <tbody>
                            <?php for ($i=0; $i < count($servicios_adicionales); $i++) { 
                              $idServiciosAdicionales=$servicios_adicionales[$i]["idServiciosAdicionales"];
                          ?>



                      

   <tr>
                           
                                <td><?= $servicios_adicionales[$i]["nombre"] ?></td>
                              
                                <td>
 <form method="post" id="borra<?=$idServiciosAdicionales;?>">
  <input type="hidden" name="eliminarServicioAdicional" value="<?=$idServiciosAdicionales;?>" >
       <a onclick="borrar(<?=$idServiciosAdicionales;?>)" name="eliminarServicioAdicional" class="btn btn-xs btn-danger">
        Eliminar
      </a>
      </form>
</td>
                             </tr>   
    <?php
                            } ?>


                                           
                         </tbody>

                         </table>
                                    </div>
                                    </div>

                                    </div><!-- /.card-body -->
             



             <script type="text/javascript">



            function borrar(idServiciosAdicionales){
           


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
  var formulario="#borra"+idServiciosAdicionales
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