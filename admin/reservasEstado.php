

<?php 


include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");
require("classes/functions.php");
require("classes/categoria.php");



$categorias=getCategorias();


 ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Reservas</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Reservas</a></li>
              <li class="breadcrumb-item active">Estado de reservas</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <section class="content">
      <div class="container-fluid">
        <!-- SELECT2 EXAMPLE -->
      
<button type="button" class= "btn btn-secondary btn-lg btn-block">Reservas</button>
        

<div id="accordion">
  <div class="card">
    <div class="card-header" id="headingOne">
      <h5 class="mb-0">
        <button class="btn btn-success btn-lg btn-block" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne"><i class="fas fa-check-double"></i> 
          Confirmadas
        </button>
      </h5>
    </div>

    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
      <div class="card-body">
        <h3>Lista de reservas confirmadas</h3> 
            <div class="row">
                  <div class="table-responsive">   
                            
                            <table class="table" id="tablaCarrito">
                              <thead>
                                <tr>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Cod-Carrito</th>
                                    <th scope="col">Fecha de contratacion</th>
                                    <th scope="col">Dia del Evento</th>
                                    <th scope="col">C.ServContratados</th>
                                    <th scope="col">Valor Total</th>
                                    <th scope="col">Acción</th>
                                 </tr>
                              </thead>
                    <tbody>

                                 <tr>
                                    <td>Nombre</td>
                                    <td>GVR298</td>
                                    <td>12/10/2019</td>
                                    <td>21/12/2020</td>
                                    <td>2</td>
                                    <td>R$ 1450</td>
                                    <td><form method="post"><input type="hidden" name="idCategoria_servicio" value="<?=$idCategoria_servicio;?>"><button type="submit" class="btn btn-danger" name="eliminarAdicional" value="<?=$habilitados[$i]['idServiciosAdicionalesCategoria'];?>">Quitar</button></form>
                                    </td>

                             </tr>
                        </tbody>
                      </table>
                    </div>
                 </div>
             </div>
          </div>
     </div>



  <div class="card">
    <div class="card-header" id="headingTwo">
      <h5 class="mb-0">
        <button class="btn btn-warning btn-lg btn-block collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo"><i class="fas fa-exclamation-circle"></i> 
          Pendientes
        </button>
      </h5>
    </div>
    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
      <div class="card-body">
        <h3>Lista de reservas pendientes</h3> 
            <div class="row">
                  <div class="table-responsive">   
                            
                            <table class="table" id="tablaCarrito">
                              <thead>
                                <tr>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Cod-Carrito</th>
                                    <th scope="col">Fecha de contratacion</th>
                                    <th scope="col">Dia del Evento</th>
                                    <th scope="col">C.ServContratados</th>
                                    <th scope="col">Valor Total</th>
                                    <th scope="col">Acción</th>
                                 </tr>
                              </thead>
                    <tbody>

                                 <tr>
                                    <td>Nombre</td>
                                    <td>GVR298</td>
                                    <td>12/10/2019</td>
                                    <td>21/12/2020</td>
                                    <td>2</td>
                                    <td>R$ 1450</td>
                                    <td><form method="post"><input type="hidden" name="idCategoria_servicio" value="<?=$idCategoria_servicio;?>"><button type="submit" class="btn btn-danger" name="eliminarAdicional" value="<?=$habilitados[$i]['idServiciosAdicionalesCategoria'];?>">Quitar</button></form>
                                    </td>

                             </tr>
                        </tbody>
                      </table>
                    </div>
                 </div>
             </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="headingThree">
      <h5 class="mb-0">
        <button class="btn btn-danger btn-lg btn-block collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree"><i class="fas fa-calendar-times"></i> 
          Canceladas
        </button>
      </h5>
    </div>
    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
      <div class="card-body">
        <h3>Lista de reservas canceladas</h3> 
            <div class="row">
                  <div class="table-responsive">   
                            
                            <table class="table" id="tablaCarrito">
                              <thead>
                                <tr>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Cod-Carrito</th>
                                    <th scope="col">Fecha de contratacion</th>
                                    <th scope="col">Dia del Evento</th>
                                    <th scope="col">C.ServContratados</th>
                                    <th scope="col">Valor Total</th>
                                    <th scope="col">Acción</th>
                                 </tr>
                              </thead>
                    <tbody>

                                 <tr>
                                    <td>Nombre</td>
                                    <td>GVR298</td>
                                    <td>12/10/2019</td>
                                    <td>21/12/2020</td>
                                    <td>2</td>
                                    <td>R$ 1450</td>
                                    <td><form method="post"><input type="hidden" name="idCategoria_servicio" value="<?=$idCategoria_servicio;?>"><button type="submit" class="btn btn-danger" name="eliminarAdicional" value="<?=$habilitados[$i]['idServiciosAdicionalesCategoria'];?>">Quitar</button></form>
                                    </td>

                             </tr>
                        </tbody>
                      </table>
                    </div>
                 </div>
             </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="headingThree">
      <h5 class="mb-0">
        <button class="btn btn-secondary btn-lg btn-block collapsed" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseThree"><i class="fas fa-clock"></i> 
          Pasadas
        </button>
      </h5>
    </div>
    <div id="collapseFour" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
      <div class="card-body">
        <h3>Lista de reservas pasadas</h3> 
            <div class="row">
                  <div class="table-responsive">   
                            
                            <table class="table" id="tablaCarrito">
                              <thead>
                                <tr>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Cod-Carrito</th>
                                    <th scope="col">Fecha de contratacion</th>
                                    <th scope="col">Dia del Evento</th>
                                    <th scope="col">C.ServContratados</th>
                                    <th scope="col">Valor Total</th>
                                    <th scope="col">Acción</th>
                                 </tr>
                              </thead>
                    <tbody>

                                 <tr>
                                    <td>Nombre</td>
                                    <td>GVR298</td>
                                    <td>12/10/2019</td>
                                    <td>21/12/2020</td>
                                    <td>2</td>
                                    <td>R$ 1450</td>
                                    <td><form method="post"><input type="hidden" name="idCategoria_servicio" value="<?=$idCategoria_servicio;?>"><button type="submit" class="btn btn-danger" name="eliminarAdicional" value="<?=$habilitados[$i]['idServiciosAdicionalesCategoria'];?>">Quitar</button></form>
                                    </td>

                             </tr>
                        </tbody>
                      </table>
                    </div>
                 </div>
             </div>
    </div>
  </div>
</div>

                                        
                                  
                                   

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