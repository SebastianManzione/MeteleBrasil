

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
require("classes/destinos.php");
require("classes/convierte_monedas.php");


if (!$_SESSION["login"]["rol"]==1) {

  alertar("Usted no tiene acceso a esta seccion del software", "error");

  redireccionarLento("index");

}




if ($_SERVER["REQUEST_METHOD"]=="POST") {

  if (isset($_POST["addDestino"])) {
    $textoDestino=$_POST["textoDestino"];
    $resuDestino=setDestino($textoDestino);

    if ($resuDestino>0) {
  alertar("Destino guardado con éxito","success");
}
  }


  if (isset($_POST["eliminarDestino"])) {
   

$idDestino=$_POST["eliminarDestino"];


$getAllDestinos=getAllDestinosBlog($idDestino);


if (count($getAllDestinos)>0 ) {
    alertar("El destino que desea eliminar esta asignado a algun post del blog","error");

}

else{
  $destinoResu=borraDestino($idDestino);
if ($destinoResu>0) {
  alertar("Destino eliminado con éxito","success");
}
}

  }







}

$destinos=getDestinos();
 ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Destinos</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Administración</a></li>
              <li class="breadcrumb-item active">Destinos</li>
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
                


<button type="button" class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#exampleModal" data-whatever="agregarNota">Agregar destino</button>          

                                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                      <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                          <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Nuevo destino</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                              <span aria-hidden="true">&times;</span></button>
                                          </div>

                                          <div class="modal-body">
                                            <form method="post">
                                              <div class="form-group">
                                                
                                                  <label for="recipient-name" class="col-form-label">Destino:</label>
                                                  <input type="text" class="form-control" name="textoDestino">
                                             </div>
                                                  
                                            
                                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                            <button type="submit" name="addDestino" class= "btn btn-primary">Agregar</button>
                                            </form>
                                          </div>
                                          
                                        </div>
                                      </div>
                                    </div>

        <button type="button" class="btn btn-secondary btn-lg btn-block" data-card-widget="collapse">Lista de Textos</button>

</div>
<!-- /.card-header -->


        <div class="card-body" >
            <div class="row">
                  <div class="table-responsive">   
                            
                            <table class="table" id="tablaCarrito">
                              <thead>
                                <tr>
                       
                                   <th scope="col">Destino</th>              
                                   <th scope="col">Accion</th>

                                </tr>
                              </thead>
                  
                           <tbody>
<?php for ($i=0; $i < count($destinos); $i++) { 
  $idDestino=$destinos[$i]["idDestino"];
?>


                             <tr>
                      
                                <td><?=$destinos[$i]["nombre"];?></td>
                                  
                                <td>
                                 <form method="post" id="borra<?=$idDestino;?>">
  <input type="hidden" name="eliminarDestino" value="<?=$idDestino;?>" >
       <a onclick="borrar(<?=$idDestino;?>)" class="btn btn-xs btn-danger">
        Eliminar
      </a>
      </form></td>
                             </tr>                    
<?php
} ?>


                                           
                         </tbody>
                         </table>
                                    </div>
                                    </div>
                                    </div><!-- /.card-body -->
             



                          
                          <script type="text/javascript">



            function borrar(idDestino){
           


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
  var formulario="#borra"+idDestino
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