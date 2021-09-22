

<?php 

include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");
require("classes/functions.php");

require("classes/servicio.php");
require("classes/categoria.php");

require("classes/prestador.php");
require("classes/comision_prestador.php");
require("classes/servicios_adicionales.php");
require("classes/opiniones_categoria.php");

if ($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST["idServicio"]) ) {

  $idServicio=$_POST["idServicio"];
  $servicio=getServicio($idServicio);
  $nombre_servicio=$servicio[0]["nombre_servicio"];
$prestadores=getPrestadores();








}

if ($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST["setComisionPrestador"]) ) {

  $idServicio=$_POST["idServicio"];
  $idPrestador=$_POST["idPrestador"];
  $comisionVendedor=$_POST["comisionVendedor"];
  $comisionSistema=$_POST["comisionSistema"];

$resul=setComisionPrestadorServicio($idServicio, $idPrestador, $comisionVendedor, $comisionSistema);

if ($resul>0) {
  alertar("Comision cargada correctamente y servicio habilitado al prestador", "success");
}


}

$comisiones=getComisionesPrestadorServicio($idServicio);


  
 ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h3 class="m-0 text-dark">Comision Inicial Prestadores servicio <?=$servicio[0]["nombre_servicio"];?></h3>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Editor</a></li>
              <li class="breadcrumb-item active">Comision Inicial servicio <?=$servicio[0]["nombre_servicio"];?></li>
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
                


<button type="button" class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#exampleModal" data-whatever="agregarNota">Agregar comision inicial de prestador al servicio</button>          

                                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                      <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                          <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Comision Prestador X Servicio</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                              <span aria-hidden="true">&times;</span></button>
                                          </div>

                                          <div class="modal-body">
                                            <form method="post">
                                              <div class="form-group">
                                            
                                               <input type="hidden" name="idServicio" value="<?=$idServicio;?>">
                                             
                                                  <label for="recipient-name" class="col-form-label">Nombre:</label>
                                                         <select name="idPrestador">
                                                      
                                                         <?php for ($i=0; $i < count($prestadores) ; $i++) { 
                                                          $idPrestador=$prestadores[$i]["idPrestador"];
                                                          $nombre=$prestadores[$i]["nombre"];
                                                           $getComision=getComisionesPrestadorServicioIdPrestadorIdServicio($idServicio, $idPrestador);
                                                           $disabled="";
                                                           if(count($getComision)>0){
                                                            $disabled="disabled";
}
                                                         ?>
                                                     
                                                        <option value="<?=$idPrestador?>" <?=$disabled?>><?=$nombre?></option>

                                                         <?php } ?>
                                                      
                                                      </select>
                                             </div>
                                                  <div class="form-group">
                                                     <label for="message-text" class="col-form-label">Comision Vendedor</label>
                                                        <input class="form-control" type="number" name="comisionVendedor" step="0.01"></input>
                                                  </div>
                                                 <div class="form-group">
                                                     <label for="message-text" class="col-form-label">Comision Sistema</label>
                                                     <input class="form-control" type="number" name="comisionSistema" step="0.01"></input>
                                                  </div>
                                            <div class="form-group">
                                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                            <button type="submit" name="setComisionPrestador" class= "btn btn-primary">Agregar</button>
                                          </div>
                                            </form>
                                          </div>
                                          
                                        </div>
                                      </div>
                                    </div>  

        <button type="button" class= "btn btn-secondary btn-lg btn-block" data-card-widget="collapse">Lista de comision inicial de prestadores <?=$servicio[0]["nombre_servicio"];?></button>

</div>
<!-- /.card-header -->


        <div class="card-body">
        <h3> Prestadores habilitados en el servicio</h3> 
            <div class="row">
                  <div class="table-responsive">   
                            
                            <table class="table" id="tablaCarrito">
                              <thead>
                                <tr>
                        
                                   <th scope="col">Nombre</th>
                           
                                   <th scope="col">% Vendedor</th>
                                  <th scope="col">% Sistema</th>
                                </tr>
                              </thead>
                  

                           <tbody>
<?php for ($i=0; $i < count($comisiones); $i++) { 
  $prestador=getPrestador($comisiones[$i]["idPrestador"]);
  $comisionVendedor=$comisiones[$i]["comisionVendedor"];
  $comisionSistema=$comisiones[$i]["comisionSistema"];
 ?>
<tr>
  <td><?= $prestador[0]['nombre'];?></td>
  <td><?= $comisionVendedor;?></td>
  <td><?= $comisionSistema;?></td>

</tr>
<?php } ?>

   



                                           
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