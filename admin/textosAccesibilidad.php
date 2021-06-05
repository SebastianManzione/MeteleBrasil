

<?php 


include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");
require("classes/functions.php");
require("classes/prestador.php");
require("classes/usuario.php");
require("classes/reserva.php");
require("classes/salidas.php");
require("classes/servicio.php");
require("classes/comprobantes.php");
require("classes/convierte_monedas.php");



if ($_SERVER["REQUEST_METHOD"]=="POST") {
$idReserva=$_POST["detallesCarrito"];
$reserva=getReservaId($idReserva);
$codigoAmigable=$reserva[0]["codigoAmigable"];
$fechaReserva=date("d-m-Y H:i:s", strtotime($reserva[0]['fechaAlta']));
$idUsuario=$reserva[0]['idUsuario'];
$usuario=getUsuario($idUsuario);
$nombre_usuario=$usuario[0]["usuario"];
$nombreResponsable=$reserva[0]["nombreResponsable"]." ".$reserva[0]["apellidoResponsable"];
$telefonoResponsable=$reserva[0]["telefonoResponsable"];
$emailResponsable=$reserva[0]["emailResponsable"];



$horarios=getReservaHorarios($idReserva);

}

 ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Textos accesibilidad</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Administración</a></li>
              <li class="breadcrumb-item active">Textos Accesibilidad</li>
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
                


<button type="button" class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#exampleModal" data-whatever="agregarNota">Agregar texto de Accesibilidad</button>          

                                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                      <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                          <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Nuevo texto de accesibilidad</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                              <span aria-hidden="true">&times;</span></button>
                                          </div>

                                          <div class="modal-body">
                                            <form>
                                              <div class="form-group">
                                                
                                                  <label for="recipient-name" class="col-form-label">Texto de accesibilidad:</label>
                                                  <input type="text" class="form-control" id="recipient-name">
                                             </div>
                                                  
                                            
                                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                            <button type="button" class= "btn btn-primary">Agregar</button>
                                            </form>
                                          </div>
                                          
                                        </div>
                                      </div>
                                    </div>

        <button type="button" class="btn btn-secondary btn-lg btn-block" data-card-widget="collapse">Lista de Textos</button>

</div>
<!-- /.card-header -->


        <div class="card-body" style="display: none;">
            <div class="row">
                  <div class="table-responsive">   
                            
                            <table class="table" id="tablaCarrito">
                              <thead>
                                <tr>
                                   <th scope="col">Operador</th>
                                   <th scope="col">Texto</th>              
                                   <th scope="col">Accion</th>

                                </tr>
                              </thead>
                  
                           <tbody>

                             <tr>
                                <td>Admin</td>
                                <td>Solo Rampa en algunas areas</td>
                                <td><button type="button" class="btn btn-danger">Eliminar</button>
<button type="button" class="btn btn-primary">Editar</button></td>
                             </tr>                    



                                           
                         </tbody>
                         </table>
                                    </div>
                                    </div>
                                    </div><!-- /.card-body -->
             



                                        
                                  
                                   

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