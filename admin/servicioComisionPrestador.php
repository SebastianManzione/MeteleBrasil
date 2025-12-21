<?php

// Validar que el parámetro idServicio esté presente en la URL
if (!isset($_GET["idServicio"]) || !is_numeric($_GET["idServicio"])) {
    alertar2("ID de servicio no válido o no especificado", "error");
    echo "<script>window.location.href='servicios.php';</script>";
    exit();
}

$idServicio = $_GET["idServicio"];

include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");
require("classes/functions.php");

require("classes/servicio.php");
require("classes/categoria.php");

require("classes/prestador.php");
require("classes/comision_prestador.php");
require("classes/prestador_comision.php");
require("classes/servicios_adicionales.php");
require("classes/opiniones_categoria.php");

// Obtener datos del servicio usando el idServicio del GET
$servicio=getServicio($idServicio);
$nombre_servicio=$servicio[0]["nombre_servicio"];
$prestadores=getPrestadores();

if ($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST["setComisionPrestador"]) ) {

  $idComision=$_POST["idComision"];
  $idPrestador=$_POST["idPrestador"];

  // Validar que se haya seleccionado un prestador
  if (empty($idPrestador)) {
    alertar2("Debe seleccionar un prestador", "error");
  }
  // Validar que la comisión existe y pertenece al prestador seleccionado
  elseif (empty($idComision)) {
    alertar2("Debe seleccionar una comisión válida", "error");
  } else {
    // Verificar que la comisión existe
    $comisionExiste = getComisionPrestador($idComision);
    if (!$comisionExiste) {
      alertar2("La comisión seleccionada no existe", "error");
    } else {
      // Asignar comisión existente al servicio
      $resul = asignarComisionPrestadorServicio($idServicio, $idComision);

      if ($resul>0) {
        alertar2("Comisión asignada correctamente al servicio", "success");
        // Redirigir para recargar la página y mostrar los cambios
        echo "<script>window.location.href='servicioComisionPrestador.php?idServicio=$idServicio';</script>";
        exit();
      } else {
        alertar2("Esta comisión ya está asignada a este servicio o error al asignar", "error");
      }
    }
  }
}

if ($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST["borraComisionServicio"]) ) {
  $idServicioComisionPrestador = $_POST["borraComisionServicio"];

  // Eliminar asignación de comisión al servicio
  $resul = deleteComisionPrestadorServicio($idServicioComisionPrestador);

  if ($resul>0) {
    alertar2("Asignación de comisión eliminada correctamente", "success");
  } else {
    alertar2("Error al eliminar la asignación", "error");
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
                


<button type="button" class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#exampleModal" data-whatever="asignarComision">Asignar comisión de prestador al servicio</button>          

                                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                      <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                          <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Asignar Comisión de Prestador al Servicio</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                              <span aria-hidden="true">&times;</span></button>
                                          </div>

                                          <div class="modal-body">
                                            <form method="post" id="formAsignarComision">
                                              <div class="form-group">
                                               <input type="hidden" name="idServicio" value="<?=$idServicio;?>">

                                                  <label for="idPrestador" class="col-form-label">Prestador:</label>
                                                         <select name="idPrestador" id="selPrestadorComision" class="form-control" required>
                                                         <option value="">Seleccione un prestador</option>
                                                         <?php for ($i=0; $i < count($prestadores) ; $i++) {
                                                          $idPrestador=$prestadores[$i]["idPrestador"];
                                                          $nombre=$prestadores[$i]["nombre"];
                                                         ?>
                                                        <option value="<?=$idPrestador?>"><?=$nombre?></option>
                                                         <?php } ?>
                                                      </select>
                                             </div>
                                             <div class="form-group">
                                                <label for="idComision" class="col-form-label">Comisión a asignar/reemplazar:</label>
                                                <select name="idComision" id="selComisionDisponible" class="form-control" required disabled>
                                                   <option value="">Primero seleccione un prestador</option>
                                                </select>
                                                <small class="form-text text-muted">Un prestador puede tener múltiples comisiones asignadas al mismo servicio.</small>
                                             </div>
                                             <div class="form-group">
                                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                            <button type="submit" name="setComisionPrestador" class="btn btn-primary" id="btnAsignarComision" disabled title="Seleccione un prestador y una comisión para habilitar esta opción">Asignar Comisión</button>
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
        <h3> Comisiones asignadas al servicio</h3>
        <p class="text-muted">Lista de prestadores con sus comisiones asignadas</p> 
            <div class="row">
                  <div class="table-responsive">   
                            
                            <table class="table" id="tablaCarrito">
                              <thead>
                                <tr>

                                   <th scope="col">Prestador</th>
                                   <th scope="col">Nombre Comisión</th>
                                   <th scope="col">% Vendedor</th>
                                  <th scope="col">% Sistema</th>
                                  <th scope="col">Estado</th>
                                  <th scope="col">Acciones</th>
                                </tr>
                              </thead>
                  

                           <tbody>
                  <?php for ($i=0; $i < count($comisiones); $i++) {
  $prestador=getPrestador($comisiones[$i]["idPrestador"]);
  $nombreComision=$comisiones[$i]["nombre_comision"] ?? "Comisión asignada";
  $comisionVendedor=$comisiones[$i]["comisionVendedor"];
  $comisionSistema=$comisiones[$i]["comisionSistema"];

  // Verificar si la comisión asignada está siendo usada en salidas
  $idPrestadorComision = $comisiones[$i]["idPrestadorComision"];
  $enUso = $idPrestadorComision ? comisionPrestadorEnUso($idPrestadorComision) : false;
 ?>
<tr>
  <td><?= $prestador[0]['nombre'];?></td>
  <td><?=$nombreComision;?></td>
  <td class="text-center"><?=$comisionVendedor;?>%</td>
  <td class="text-center"><?=$comisionSistema;?>%</td>
  <td class="text-center">
    <?php if ($enUso): ?>
      <span class="badge badge-info" style="cursor: pointer;" onclick="verSalidasComisionServicio('<?=$comisiones[$i]["idServicioComisionPrestador"];?>')">En uso</span>
    <?php else: ?>
      <span class="badge badge-success">Asignada</span>
    <?php endif; ?>
  </td>
  <td class="text-center">
    <?php if ($enUso): ?>
      <button class="btn btn-sm btn-secondary" disabled title="No se puede eliminar porque la comisión está siendo usada en salidas">
        <i class="fas fa-trash"></i> Eliminar
      </button>
    <?php else: ?>
      <button class="btn btn-sm btn-danger" onclick="borrarComisionServicio('<?=$comisiones[$i]["idServicioComisionPrestador"];?>')">
        <i class="fas fa-trash"></i> Eliminar
      </button>
    <?php endif; ?>
  </td>
</tr>
<?php } ?>

   



                                           
                         </tbody>

                         </table>

                                    </div>
                                    </div>
  <a  class="btn btn-info float-right" href="servicioVer?idServicio=<?=$idServicio?>"><i class="fa fa-arrow-left" aria-hidden="true"> <?=$lang["volver"];?></i>
</a>


                                    </div><!-- /.card-body -->
             



             <script type="text/javascript">



function uploadForm(){

$("#formulario").submit();
}

// Función para cargar comisiones disponibles del prestador
function cargarComisionesPrestador(idPrestador, idServicio) {
    if(idPrestador != "") {
            $.ajax({
                url: 'ajax_get_comisiones_prestador.php',
                type: 'POST',
                dataType: 'json',
                data: {
                    idPrestador: idPrestador,
                    idServicio: idServicio
                    // No enviamos solo_disponibles para que muestre comisiones disponibles para asignar
                },
            success: function(response) {
                var options = '<option value="">Seleccione una comisión disponible</option>';

                if (response.success && response.comisiones.length > 0) {
                    response.comisiones.forEach(function(comision) {
                        var nombreComision = comision.nombre + ' - Vendedor: ' + comision.vendedor + '% / Sistema: ' + comision.sistema + '%';
                        options += '<option value="' + comision.id + '">' + nombreComision + '</option>';
                    });
                    $("#selComisionDisponible").html(options).prop('disabled', false);
                    // Habilitar el botón cuando hay comisiones disponibles
                    $("#btnAsignarComision").prop('disabled', false).attr('title', 'Haga clic para asignar la comisión seleccionada');
                } else {
                    var mensaje = response.message || 'No hay comisiones disponibles para este prestador';
                    $("#selComisionDisponible").html('<option value="">' + mensaje + '</option>');
                    // Deshabilitar el botón cuando no hay comisiones disponibles
                    $("#btnAsignarComision").prop('disabled', true).attr('title', 'No hay comisiones disponibles para el prestador seleccionado');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error AJAX:', error);
                $("#selComisionDisponible").html('<option value="">Error al cargar comisiones</option>');
                // Deshabilitar el botón en caso de error
                $("#btnAsignarComision").prop('disabled', true).attr('title', 'Error al cargar las comisiones. Intente nuevamente.');
            }
        });
    } else {
        $("#selComisionDisponible").html('<option value="">Primero seleccione un prestador</option>').prop('disabled', true);
        // También deshabilitar el botón inicialmente
        $("#btnAsignarComision").prop('disabled', true).attr('title', 'Seleccione un prestador y una comisión para habilitar esta opción');
    }
}

function borrarComisionServicio(idServicioComisionPrestador){
  var parametros={"borraComisionServicio" : idServicioComisionPrestador};
  Swal.fire({
    title: 'Está seguro?',
    text: 'Esta acción quitará la asignación de esta comisión al servicio',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Sí, quitar asignación!'
  }).then((result) => {
    if (result.value) {
      $.post("./ctrl/ctrl_servicio_comision.php", parametros, function(data, status){
        if (data>0) location.reload();
      });
      Swal.fire('Eliminado!', 'La asignación se eliminó.', 'success')
    }
  })
}

// Event listeners
$(document).ready(function() {
    // Cuando cambia el prestador, cargar sus comisiones disponibles
    $("#selPrestadorComision").change(function(){
        var idPrestador = $(this).val();
        var idServicio = <?php echo $idServicio; ?>;
        cargarComisionesPrestador(idPrestador, idServicio);
    });
});

// Validación del formulario antes de enviar
$('#formAsignarComision').on('submit', function(e) {
    if ($('#btnAsignarComision').prop('disabled')) {
        e.preventDefault();
        Swal.fire({
            icon: 'warning',
            title: 'Acción no permitida',
            text: 'Debe seleccionar un prestador con comisiones disponibles antes de asignar.'
        });
        return false;
    }
});
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

        // Función para ver las salidas que usan una asignación específica (desde servicioComisionPrestador.php)
        function verSalidasComisionServicio(idServicioComisionPrestador) {
            $.ajax({
              url: 'ajax_get_salidas_asignacion.php',
              type: 'POST',
              dataType: 'json',
              data: { idServicioComisionPrestador: idServicioComisionPrestador },
              success: function(response) {
                if (response.success && response.salidas.length > 0) {
                  var mensaje = '<strong>Esta asignación de comisión se está usando en las siguientes salidas:</strong><br><br>';
                  mensaje += '<ul style="text-align: left; max-height: 200px; overflow-y: auto;">';
                  response.salidas.forEach(function(salida) {
                    mensaje += '<li>• ' + salida.nombre_servicio + ' - ' + salida.nombre + ' (' + salida.fecha + ')</li>';
                  });
                  mensaje += '</ul>';

                  if (response.total > 5) {
                    mensaje += '<br><small class="text-muted">Y ' + (response.total - 5) + ' salidas más...</small>';
                  }

                  Swal.fire({
                    title: 'Asignación en uso',
                    html: mensaje,
                    icon: 'info',
                    confirmButtonText: 'Entendido'
                  });
                } else {
                  Swal.fire({
                    title: 'Información',
                    text: 'Esta asignación aún no se ha usado en salidas.',
                    icon: 'question'
                  });
                }
              },
              error: function(xhr, status, error) {
                console.error('Error AJAX:', error);
                Swal.fire({
                  title: 'Error',
                  text: 'No se pudo cargar la información de las salidas.',
                  icon: 'error'
                });
              }
            });
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