

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
            <h1 class="m-0 text-dark">Administrador de monedas</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Moneda</a></li>
              <li class="breadcrumb-item active">Administrador de monedas</li>
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
                


        

                                    
                                            <h5 class="modal-title" id="exampleModalLabel">Cargue aqui una nueva moneda</h5>
                                          
                                          </div>

                                          <div class="modal-body">
                                            <form>
                                              <div class="form-group">
                                               
                                                <td><label>Tipo de moneda</label>
                                                     <input name="txtNombre" id="txtNombre" class="form-control select2bs4" style="width: 100%;" placeholder="Escriba el nombre de la moneda" required>
                                                </td>
                                                <td><label>Pais</label>
                                                     <input name="txtNombre" id="txtNombre" class="form-control select2bs4" style="width: 100%;" placeholder="Pais de origen de la moneda" required>
                                                </td>
                                                <td><label>Abreviacion</label>
                                                     <input name="txtNombre" id="txtNombre" class="form-control select2bs4" style="width: 100%;" placeholder="Pais de origen de la moneda" required>
                                                 </td>
                                                 </div>
                                                 <label class="col-form-label">Valor con respecto al dolar americano</label>
                                              <input class="form-control" type="number" name="puntuacion" min="1" max="10000" step="0.01"></input>

                                              <div class="form-group">
                                                     <label for="message-text" class="col-form-label">Descripción:</label>
                                                        <textarea placeholder="Describa brevemente la moneda sobre su posicion en el mercado" required class="form-control" id="message-text"></textarea> 
                                                  </div>
                                            

                                            <button type="button" class= "btn btn-primary">Agregar</button>
                                            
                                          
                                          </form>
                                       
                                     
                                  

        <button type="button" class= "btn btn-secondary btn-lg btn-block" data-card-widget="collapse">Monedas</button>

</div>
<!-- /.card-header -->


        <div class="card-body" style="display: none;">
            <div class="row">
                  <div class="table-responsive">   
                            
                            <table class="table" id="tablaCarrito">
                              <thead>
                                <tr>
                                  <th scope="col">Tipo de moneda</th>
                                  <th scope="col">Pais</th>
                                  <th scope="col">Abreviación</th>
                                  <th scope="col">Valor con respecto al dolar estadounidense</th>
                                  <th scope="col">Descripción</th>
                                  <th scope="col">Acciones</th>


                                </tr>
                              </thead>
                  

                           <tbody>


                             <tr>
                                <td>Reales</td>
                                <td>Brasil</td>
                                <td>R$</td>
                                <td>5.55</td>
                                <td>Moneda Brasilera fuerte en el mercado sudamericano</td>
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