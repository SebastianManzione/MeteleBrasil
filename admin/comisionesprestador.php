<?php

include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");
require("classes/functions.php");
require("classes/prestador.php");
require("classes/prestador_comision.php");
require("classes/comision_prestador.php");

if (!$_SESSION["login"]["rol"]==1) {
  alertar("Usted no tiene acceso a esta seccion del software", "error");
  redireccionarLento("index");
}

if ($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST["idPrestador"]) && is_numeric($_POST["idPrestador"])) {
  $idPrestador = $_POST["idPrestador"];
  $prestador = getPrestador($idPrestador);
  $nombre_prestador = $prestador[0]["nombre"];
} else {
  // Si no se recibe idPrestador, redirigir a prestadores.php
  redireccionarLento("prestadores.php");
}

$comisiones = getComisionesPrestador($idPrestador);

// Procesar operaciones POST solo si no es una petición AJAX
if ($_SERVER["REQUEST_METHOD"]=="POST" && !isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
  if (isset($_POST["setComisionPrestador"]) ) {
    $idPrestador = $_POST["idPrestador"];
    $nombre = $_POST["nombre"];
    $comisionVendedor = $_POST["comisionVendedor"];
    $comisionSistema = $_POST["comisionSistema"];

    $resul = setComisionPrestador($idPrestador, $nombre, $comisionVendedor, $comisionSistema);

    if ($resul > 0) {
      $success_message = "Comisión cargada correctamente";
      // Volver a obtener las comisiones después de guardar
      $comisiones = getComisionesPrestador($idPrestador);
    } else {
      $error_message = "Error al cargar la comisión";
    }
  }

  if (isset($_POST["updateComisionPrestador"]) ) {
    $idPrestadorComision = $_POST["idPrestadorComision"];
    $nombre = $_POST["nombre"];
    $comisionVendedor = $_POST["comisionVendedor"];
    $comisionSistema = $_POST["comisionSistema"];

    $resul = updateComisionPrestador($idPrestadorComision, $nombre, $comisionVendedor, $comisionSistema);

    if (is_array($resul) && $resul['total_actualizado'] > 0) {
      $success_message = "Comisión actualizada correctamente";
      if ($resul['asignaciones_actualizadas'] > 0) {
        $success_message .= " (" . $resul['asignaciones_actualizadas'] . " asignaciones actualizadas)";
      }
      // Volver a obtener las comisiones después de actualizar
      $comisiones = getComisionesPrestador($idPrestador);
    } elseif ($resul > 0) {
      // Fallback para respuesta antigua
      $success_message = "Comisión actualizada correctamente";
      $comisiones = getComisionesPrestador($idPrestador);
    } else {
      $error_message = "Error al actualizar la comisión";
    }
  }
}

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h3 class="m-0 text-dark">Comisiones del Prestador: <?=$nombre_prestador;?></h3>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="prestadores.php">Prestadores</a></li>
            <li class="breadcrumb-item active">Comisiones de <?=$nombre_prestador;?></li>
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
          <button type="button" class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#exampleModal" data-whatever="agregarComision">Agregar comisión al prestador</button>

          <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Comisión del Prestador: <?=$nombre_prestador;?></h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span></button>
                </div>

                <div class="modal-body">
                  <form id="formAgregarComision">
                    <input type="hidden" name="setComisionPrestador" value="1">
                    <div class="form-group">
                      <input type="hidden" name="idPrestador" value="<?=$idPrestador;?>">
                      <label for="nombre" class="col-form-label">Nombre de la Comisión</label>
                      <input class="form-control" type="text" name="nombre" id="nombre" placeholder="Ej: Comisión base, Comisión premium, etc." required>
                    </div>
                    <div class="form-group">
                      <label for="comisionVendedor" class="col-form-label">Comisión Vendedor (%)</label>
                      <input class="form-control" type="number" name="comisionVendedor" id="comisionVendedor" step="0.01" min="0" max="100" required>
                    </div>
                    <div class="form-group">
                      <label for="comisionSistema" class="col-form-label">Comisión Sistema (%)</label>
                      <input class="form-control" type="number" name="comisionSistema" id="comisionSistema" step="0.01" min="0" max="100" required>
                    </div>
                    <div class="form-group">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                      <button type="button" id="btnGuardarComision" class="btn btn-primary">Agregar</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>

          <button type="button" class="btn btn-secondary btn-lg btn-block" data-card-widget="collapse">Lista de comisiones del prestador <?=$nombre_prestador;?></button>
        </div>

        <!-- /.card-header -->
        <div class="card-body">
          <h3>Comisiones del Prestador</h3>
          <div class="row">
            <div class="table-responsive">
              <table class="table table-striped table-hover table-bordered table-modern w-100" id="tablaComisiones">
                <thead class="thead-dark">
                  <tr class="text-center">
                    <th scope="col">ID</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">% Vendedor</th>
                    <th scope="col">% Sistema</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <?php for ($i = 0; $i < count($comisiones); $i++) {
                    $idPrestadorComision = $comisiones[$i]["idPrestadorComision"];
                    $nombre = $comisiones[$i]["nombre"];
                    $comisionVendedor = $comisiones[$i]["comisionVendedor"];
                    $comisionSistema = $comisiones[$i]["comisionSistema"];

                    // Verificar si la comisión está en uso
                    $enUso = comisionPrestadorEnUso($idPrestadorComision);
                  ?>
                    <tr class="align-middle">
                      <td class="text-center"><?=$idPrestadorComision;?></td>
                      <td><?=$nombre;?></td>
                      <td class="text-center"><?=$comisionVendedor;?>%</td>
                      <td class="text-center"><?=$comisionSistema;?>%</td>
                      <td class="text-center">
                        <?php if ($enUso): ?>
                          <span class="badge badge-info" style="cursor: pointer;" onclick="verSalidasComision(<?=$idPrestadorComision;?>)">En uso</span>
                        <?php else: ?>
                          <span class="badge badge-success">Disponible</span>
                        <?php endif; ?>
                      </td>
                      <td class="text-center">
                        <?php if ($enUso): ?>
                          <button class="btn btn-sm btn-secondary" disabled title="No se puede editar porque está siendo usada en salidas">Editar</button>
                          <button class="btn btn-sm btn-secondary" disabled title="No se puede eliminar porque está siendo usada en salidas">Eliminar</button>
                        <?php else: ?>
                          <button class="btn btn-sm btn-warning" onclick="editarComision('<?=$idPrestadorComision;?>', '<?=$nombre;?>', '<?=$comisionVendedor;?>', '<?=$comisionSistema;?>')">Editar</button>
                          <button class="btn btn-sm btn-danger" onclick="borrarComision('<?=$idPrestadorComision;?>')">Eliminar</button>
                        <?php endif; ?>
                      </td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>

          <a class="btn btn-info float-right" href="prestadores.php"><i class="fa fa-arrow-left" aria-hidden="true"></i> Volver a Prestadores</a>
        </div><!-- /.card-body -->

        <!-- Modal para editar comisión -->
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Editar Comisión</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                  <span aria-hidden="true">&times;</span></button>
              </div>
              <div class="modal-body">
                <form id="formEditarComision">
                  <input type="hidden" name="updateComisionPrestador" value="1">
                  <input type="hidden" name="idPrestadorComision" id="editId">
                  <input type="hidden" name="idPrestador" value="<?=$idPrestador;?>">
                  <div class="form-group">
                    <label for="editNombre" class="col-form-label">Nombre de la Comisión</label>
                    <input class="form-control" type="text" name="nombre" id="editNombre" placeholder="Ej: Comisión base, Comisión premium, etc." required>
                  </div>
                  <div class="form-group">
                    <label for="editComisionVendedor" class="col-form-label">Comisión Vendedor (%)</label>
                    <input class="form-control" type="number" name="comisionVendedor" id="editComisionVendedor" step="0.01" min="0" max="100" required>
                  </div>
                  <div class="form-group">
                    <label for="editComisionSistema" class="col-form-label">Comisión Sistema (%)</label>
                    <input class="form-control" type="number" name="comisionSistema" id="editComisionSistema" step="0.01" min="0" max="100" required>
                  </div>
                  <div class="form-group">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" id="btnActualizarComision" class="btn btn-primary">Actualizar</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>

        <script type="text/javascript">
          function editarComision(id, nombre, vendedor, sistema) {
            document.getElementById('editId').value = id;
            document.getElementById('editNombre').value = nombre;
            document.getElementById('editComisionVendedor').value = vendedor;
            document.getElementById('editComisionSistema').value = sistema;
            $('#editModal').modal('show');
          }

          function borrarComision(idComision) {
            var parametros = {"deleteComisionPrestador": idComision};
            Swal.fire({
              title: '¿Está seguro?',
              text: 'Esta acción eliminará la comisión y todas sus asignaciones relacionadas. Esta acción no se puede revertir!',
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Sí, eliminar todo!'
            }).then((result) => {
              if (result.value) {
                $.post("./ctrl/ctrl_prestador_comision.php", parametros, function(data, status){
                  try {
                    var resultado = JSON.parse(data);
                    if (resultado.total_eliminado > 0) {
                      var mensaje = 'Comisión eliminada exitosamente.<br>';
                      mensaje += '• 1 comisión base eliminada<br>';
                      if (resultado.asignaciones_eliminadas > 0) {
                        mensaje += '• ' + resultado.asignaciones_eliminadas + ' asignaciones eliminadas';
                      }
                      Swal.fire('Eliminado!', mensaje, 'success')
                      .then(() => {
                        location.reload();
                      });
                    } else {
                      Swal.fire('Error', 'No se pudo eliminar la comisión.', 'error');
                    }
                  } catch (e) {
                    // Fallback para respuesta antigua (número)
                    if (parseInt(data) > 0) {
                      Swal.fire('Eliminado!', 'La comisión se eliminó.', 'success')
                      .then(() => {
                        location.reload();
                      });
                    } else {
                      Swal.fire('Error', 'No se pudo eliminar la comisión.', 'error');
                    }
                  }
                });
              }
            });
          }

          // Función para guardar nueva comisión
          function guardarComision() {
            var formData = new FormData(document.getElementById('formAgregarComision'));
            var nombreComision = document.getElementById('nombre').value.trim();
            var comisionVendedor = document.getElementById('comisionVendedor').value;
            var comisionSistema = document.getElementById('comisionSistema').value;
            var idPrestador = document.querySelector('input[name="idPrestador"]').value;

            // Validar que el nombre no esté vacío
            if (nombreComision === '') {
              Swal.fire({
                title: 'Campo requerido',
                text: 'El nombre de la comisión es obligatorio',
                icon: 'warning',
                confirmButtonText: 'OK'
              });
              return;
            }

            // Primero verificar si ya existe una comisión con los mismos porcentajes
            $.ajax({
              url: './ctrl/ctrl_prestador_comision.php',
              type: 'POST',
              data: {
                'checkComisionDuplicada': 1,
                'idPrestador': idPrestador,
                'comisionVendedor': comisionVendedor,
                'comisionSistema': comisionSistema
              },
              success: function(response) {
                try {
                  var resultado = JSON.parse(response);
                  if (resultado.duplicada) {
                    // Mostrar alerta de confirmación si ya existe
                    Swal.fire({
                      title: '¿Comisión duplicada?',
                      text: resultado.mensaje + ' ¿Desea agregar esta comisión de todas maneras?',
                      icon: 'warning',
                      showCancelButton: true,
                      confirmButtonColor: '#3085d6',
                      cancelButtonColor: '#6c757d',
                      confirmButtonText: 'Sí, agregar de todas maneras',
                      cancelButtonText: 'Cancelar'
                    }).then((result) => {
                      if (result.value) {
                        // Proceder con la creación a pesar del duplicado
                        procederConGuardado(formData);
                      }
                    });
                  } else {
                    // No hay duplicado, proceder normalmente
                    procederConGuardado(formData);
                  }
                } catch (e) {
                  console.error('Error parsing response:', e);
                  Swal.fire({
                    title: 'Error',
                    text: 'Error al verificar duplicados',
                    icon: 'error',
                    confirmButtonText: 'OK'
                  });
                }
              },
              error: function() {
                Swal.fire({
                  title: 'Error',
                  text: 'Error de conexión al verificar duplicados',
                  icon: 'error',
                  confirmButtonText: 'OK'
                });
              }
            });
          }

          function procederConGuardado(formData) {
            $.ajax({
              url: './ctrl/ctrl_prestador_comision.php',
              type: 'POST',
              data: formData,
              processData: false,
              contentType: false,
              success: function(response) {
                if (response > 0) {
                  $('#exampleModal').modal('hide');
                  document.getElementById('formAgregarComision').reset();
                  Swal.fire({
                    title: '¡Éxito!',
                    text: 'Comisión guardada correctamente',
                    icon: 'success',
                    confirmButtonText: 'OK'
                  }).then(() => {
                    location.reload();
                  });
                } else {
                  Swal.fire({
                    title: 'Error',
                    text: 'Error al guardar la comisión',
                    icon: 'error',
                    confirmButtonText: 'OK'
                  });
                }
              },
              error: function() {
                Swal.fire({
                  title: 'Error',
                  text: 'Error de conexión',
                  icon: 'error',
                  confirmButtonText: 'OK'
                });
              }
            });
          }

          // Función para actualizar comisión
          function actualizarComision() {
            var formData = new FormData(document.getElementById('formEditarComision'));

            // Mostrar confirmación antes de actualizar
            Swal.fire({
              title: '¿Actualizar comisión?',
              text: 'Esta acción también actualizará los porcentajes en todas las asignaciones relacionadas.',
              icon: 'question',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#6c757d',
              confirmButtonText: 'Sí, actualizar',
              cancelButtonText: 'Cancelar'
            }).then((result) => {
              if (result.value) {
                $.ajax({
                  url: './ctrl/ctrl_prestador_comision.php',
                  type: 'POST',
                  data: formData,
                  processData: false,
                  contentType: false,
                  success: function(response) {
                    try {
                      var resultado = JSON.parse(response);
                      if (resultado.total_actualizado > 0) {
                        $('#editModal').modal('hide');
                        var mensaje = 'Comisión actualizada exitosamente.<br>';
                        mensaje += '• 1 comisión base actualizada<br>';
                        if (resultado.asignaciones_actualizadas > 0) {
                          mensaje += '• ' + resultado.asignaciones_actualizadas + ' asignaciones actualizadas';
                        }
                        Swal.fire({
                          title: '¡Éxito!',
                          html: mensaje,
                          icon: 'success',
                          confirmButtonText: 'OK'
                        }).then(() => {
                          location.reload();
                        });
                      } else {
                        Swal.fire({
                          title: 'Error',
                          text: 'No se pudo actualizar la comisión',
                          icon: 'error',
                          confirmButtonText: 'OK'
                        });
                      }
                    } catch (e) {
                      // Fallback para respuesta antigua (número)
                      if (parseInt(response) > 0) {
                        $('#editModal').modal('hide');
                        Swal.fire({
                          title: '¡Éxito!',
                          text: 'Comisión actualizada correctamente',
                          icon: 'success',
                          confirmButtonText: 'OK'
                        }).then(() => {
                          location.reload();
                        });
                      } else {
                        Swal.fire({
                          title: 'Error',
                          text: 'Error al actualizar la comisión',
                          icon: 'error',
                          confirmButtonText: 'OK'
                        });
                      }
                    }
                  },
                  error: function() {
                    Swal.fire({
                      title: 'Error',
                      text: 'Error de conexión',
                      icon: 'error',
                      confirmButtonText: 'OK'
                    });
                  }
                });
              }
            });
          }

          // Función para ver las salidas que usan una comisión
          function verSalidasComision(idPrestadorComision) {
            $.ajax({
              url: 'ajax_get_salidas_comision.php',
              type: 'POST',
              dataType: 'json',
              data: { idPrestadorComision: idPrestadorComision },
              success: function(response) {
                if (response.success && response.salidas.length > 0) {
                  var mensaje = '<strong>Esta comisión se está usando en las siguientes salidas:</strong><br><br>';
                  mensaje += '<ul style="text-align: left; max-height: 200px; overflow-y: auto;">';
                  response.salidas.forEach(function(salida) {
                    mensaje += '<li>• ' + salida.nombre_servicio + ' - ' + salida.nombre + ' (' + salida.fecha + ')</li>';
                  });
                  mensaje += '</ul>';

                  if (response.total > 5) {
                    mensaje += '<br><small class="text-muted">Y ' + (response.total - 5) + ' salidas más...</small>';
                  }

                  Swal.fire({
                    title: 'Comisión en uso',
                    html: mensaje,
                    icon: 'info',
                    confirmButtonText: 'Entendido'
                  });
                } else {
                  Swal.fire({
                    title: 'Información',
                    text: 'No se encontraron salidas usando esta comisión.',
                    icon: 'question'
                  });
                }
              },
              error: function() {
                Swal.fire({
                  title: 'Error',
                  text: 'No se pudo cargar la información de las salidas.',
                  icon: 'error'
                });
              }
            });
          }

          $(document).ready(function() {
            var table = $('#tablaComisiones').DataTable({
              "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
              }
            });

            // Event listeners para los botones
            $('#btnGuardarComision').click(function(e) {
              e.preventDefault();
              guardarComision();
            });

            $('#btnActualizarComision').click(function(e) {
              e.preventDefault();
              actualizarComision();
            });
          });
        </script>
      </div>
    </div><!-- /.container-fluid -->
  </section>
</div>

<style>
/* Modernización y responsive */
.table-modern {
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 0 15px rgba(0,0,0,0.05);
}
.table-modern th {
  background: linear-gradient(90deg, #4a90e2, #357ab7);
  color: #fff;
  text-align: center;
}
.table-modern td {
  vertical-align: middle !important;
}
.table-modern tbody tr:hover {
  background-color: #f1f1f1;
  transition: 0.3s;
}
.card-header.bg-primary {
  background: linear-gradient(90deg, #4a90e2, #357ab7);
}
.btn-success, .btn-danger, .btn-primary, .btn-warning {
  border-radius: 6px;
  transition: transform 0.2s, box-shadow 0.2s;
}
.btn-success:hover, .btn-danger:hover, .btn-primary:hover, .btn-warning:hover {
  transform: scale(1.05);
  box-shadow: 0 0 10px rgba(0,0,0,0.2);
}
@media (max-width: 768px) {
  .table-modern th, .table-modern td {
    font-size: 0.85rem;
    padding: 0.4rem;
  }
  .btn-sm {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
  }
}
</style>

<?php include("includes/footer.php"); ?>
