

<?php 


include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");
require("classes/functions.php");

require("classes/servicio.php");
require("classes/categoria.php");

require("classes/opiniones_categoria.php");


if ($_SERVER["REQUEST_METHOD"]=="GET" && isset($_GET["idCategoria_servicio"])) {
    $idCategoria_servicio=$_GET["idCategoria_servicio"];
}

if ($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST["idCategoria_servicio"]) && is_numeric($_POST["idCategoria_servicio"])) {

  $idCategoria_servicio=$_POST["idCategoria_servicio"];
  if (isset($_POST["setOpinionCategoria"])) {

$opinionResu=setOpinionCategoria($idCategoria_servicio, $_POST["nombre"],$_POST["opinion"], $_POST["estrellasServicio"],$_POST["pais"]);
if ($opinionResu>0) {
  alertar("Opinión guardada con éxito","success");
}
  }




}

   $categoria=getCategoria($idCategoria_servicio)[0];
  
  $opiniones=OpinionesCategoria($idCategoria_servicio);
 ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h3 class="m-0 text-dark">Editor de opiniones de categoria <?=$categoria["nombre_categoria_servicio"]?></h3>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Editor</a></li>
              <li class="breadcrumb-item active">Editor de opiniones de categoria</li>
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
                


<button type="button" class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#exampleModal" data-whatever="agregarNota">Agregar comentario de Categoria</button>          

                                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                      <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                          <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Nuevo Comentario</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                              <span aria-hidden="true">&times;</span></button>
                                          </div>

                                          <div class="modal-body">
                                            <form method="post">
                                              <div class="form-group">
                                            
                                               <input type="hidden" name="idCategoria_servicio" value="<?=$idCategoria_servicio;?>">
                                                  <label for="recipient-name" class="col-form-label">Nombre:</label>
                                                  <input type="text" name="nombre" class="form-control" id="recipient-name">
                                             </div>
                                                  <div class="form-group">
                                                     <label for="message-text" class="col-form-label">Comentario:</label>
                                                        <textarea name="opinion" class="form-control" id="message-text"></textarea>
                                                  </div>
                                                   <div class="form-group">
                                                     <label for="message-text" class="col-form-label">Pais:</label>
                                                        <input name="pais" class="form-control" type="text">
                                                  </div>
                                                  <div class="form-group">
                                                    
                                             
                                                     <label class="col-form-label">Estrellas 1-5</label>
                                                       <select name="estrellasServicio" class="form-control"><option>1</option><option>2</option><option>3</option><option>4</option><option selected>5</option></select>
                                                            </div>
                                            <div class="form-group">
                                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                            <button type="submit" name="setOpinionCategoria" class= "btn btn-primary">Agregar</button>
                                          </div>
                                            </form>
                                          </div>
                                          
                                        </div>
                                      </div>
                                    </div>

        <button type="button" class= "btn btn-secondary btn-lg btn-block" data-card-widget="collapse">Lista de comentarios</button>

</div>
<!-- /.card-header -->


        <div class="card-body">
            <div class="row">
                  <div class="table-responsive">   
                            
                            <table class="table" id="tablaCarrito">
                              <thead>
                                <tr>
                        
                                   <th scope="col">Nombre</th>
                                   <th scope="col">Comentario</th>
                                   <th scope="col">Estrellas</th>
                                    <th scope="col">País</th>      
                                    <th scope="col">Fecha</th> 
                                   <th scope="col">Acción</th>

                                </tr>
                              </thead>
                  

                           <tbody>
<?php for ($i=0; $i < count($opiniones); $i++) { 
  $idOpinion=$opiniones[$i]["idOpinionCategoria"];
$fechaAlta=date("d-m-Y H:i:s", strtotime($opiniones[$i]['fechaAlta']));
 ?>
   <tr>
                           
                                <td><?=$opiniones[$i]["nombre"];?></td>
                                <td><?=$opiniones[$i]["opinion"];?></td>
                                <td><?=$opiniones[$i]["estrellas"];?></td>
                                <td><?=$opiniones[$i]["pais"];?></td>
                                <td><?=$fechaAlta;?></td>
                                <td><button type="button" onclick="borrar(<?=$idOpinion;?>)"class="btn btn-danger">Eliminar</button>
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



function uploadForm(){

$("#formulario").submit();
}
            function borrar(idABorrar){
           

var parametros={"borraComentarioCategoria" : idABorrar};   
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
 $.post("./ctrl/ctrl_comentarios_categoria.php",
parametros,
function(data, status){
console.log(data);
 if (data>0) {
  location.href = 'categoriaOpiniones?idCategoria_servicio=<?=$idCategoria_servicio?>';
 }
});
 Swal.fire(
   'Eliminado!',
   'El comentario se elimino.',
   'success'
 )
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