

<?php 


include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");
require("classes/functions.php");
require("classes/categoria.php");


if (!$_SESSION["login"]["rol"]==1) {
  alertar("Usted no tiene acceso a esta seccion del software", "error");
  redireccionarLento("index");
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["desHabilitarCategoria"]) ) {
desHabilitarCategoria($_POST["desHabilitarCategoria"]);

}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["habilitarCategoria"]) ) {

habilitarCategoria($_POST["habilitarCategoria"]);

}

$categorias=getAllCategorias();
 ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Categorias</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Editor</a></li>
              <li class="breadcrumb-item active">Editor y lista de Categorias</li>
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
               <button type="button" class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#exampleModal" data-whatever="agregarNota">Agregar categoria</button>          

                                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                      <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                          <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Agregar nueva categoria</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                              <span aria-hidden="true">&times;</span></button>
                                          </div>

                                          <div class="modal-body">
                                            <form>
                                              <div class="form-group">
                                               
                                                <td><label>Nombre</label>
                                                     <input name="txtNombre" id="txtNombre" class="form-control select2bs4" style="width: 100%;" placeholder="Escriba el nombre de la categoria" required>
                                                </td>
                                        
                                             </div>
                                                  <div class="form-group">
                                                     <label for="message-text" class="col-form-label">Descripción:</label>
                                                        <textarea placeholder="Describa brevemente la categoria" required class="form-control" id="message-text"></textarea> 
                                                  </div>
                                                   <div class="btn btn-mdb-color btn-rounded float-left">
                                                          <span>Choose file</span>
                                                          <input type="file">
                                                        </div>
                                                  <label class="col-form-label"># Viajes realizados</label>
                                              <input class="form-control" type="number" name="puntuacion" min="0" max="10000" step="1"></input>
                                                  <label class="col-form-label"># Pasajeros</label>
                                              <input class="form-control" type="number" name="puntuacion" min="0" max="10000" step="1"></input>
                                                      <label class="col-form-label"># Opiniones</label>
                                              <input class="form-control" type="number" name="puntuacion" min="0" max="10000" step="1"></input>
                                                   <label class="col-form-label">Puntuación</label>
                                              <input class="form-control" type="number" name="puntuacion" min="1" max="10" step="0.5"></input>
                                            

                                            <button type="button" class= "btn btn-primary">Agregar</button>
                                            </form>
                                          </div>
                                          
                                        </div>
                                      </div>
                                    </div>

        <button type="button" class= "btn btn-secondary btn-lg btn-block" data-card-widget="collapse">Lista de categorias</button>

</div>
<!-- /.card-header -->


        <div class="card-body">
            <div class="row">
                  <div class="table-responsive">   
                            
                            <table class="table" id="tablaCarrito">
                              <thead>
                                <tr>
                                  <th scope="col">Nombre</th>
                                   <th scope="col">Descripción</th>
                                   <th scope="col">Desc. Corta</th>
                                   <th scope="col">Foto</th>
                                   <th scope="col"># de viajeros</th>
                                   <th scope="col"># de viajes realizados</th>
                                   <th scope="col"># de orden</th>
                                   <th scope="col">habilitado</th>                
                                   <th scope="col">Opiniones</th>
                                  <th scope="col">Sv.Adicionales</th>

                                </tr>
                              </thead>
                  

                           <tbody>
<?php 

for ($i=0; $i < count($categorias); $i++) { 
  $idCategoria_servicioTMP=$categorias[$i]["idCategoria_servicio"];

?>
    <tr>
                                <td><?=$categorias[$i]["nombre_categoria_servicio"];?></td>
                                <td><?=substr($categorias[$i]["descripcion_categoria_servicio"], 0,70);?><?php if (strlen($categorias[$i]["descripcion_categoria_servicio"])>70) {
                                  echo "...";
                                } ?></td>
                                 <td><?=$categorias[$i]["descripcionCorta_categoria_servicio"];?></td>
                                <td>foto</td>
                                <td>foto</td>
                                <td><?=$categorias[$i]["nViajeros"];?></td>
                                <td><?=$categorias[$i]["orden"];?></td>
                                <td><form method="post"><?php 
                                if($categorias[$i]["habilitado"]==0){ ?>
                                <button class="btn btn-sm btn-success" name="habilitarCategoria" value="<?=$idCategoria_servicioTMP?>">Habilitar</button>
                                <?php }
                                else{
                                  ?><button class="btn btn-sm btn-warning" name="desHabilitarCategoria" 
                                   value="<?=$idCategoria_servicioTMP;?>">Deshabilitar</button>
                               <?php }?>    </form></td>
                                <td><form method="post" action="categoriaOpiniones">
<button type="submit" name="idCategoria_servicio" value="<?=$categorias[$i]["idCategoria_servicio"];?>" class="btn-sm btn-primary">Opiniones</button></form>
</td>
<td>
<form method="post" action="categoriaServiciosAdicionales">
<button type="submit" name="idCategoria_servicio" value="<?=$categorias[$i]["idCategoria_servicio"];?>" class="btn-sm btn-warning">Adicionales</button></form>
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