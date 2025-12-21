<?php
include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");
require("classes/functions.php");
require("classes/servicio.php");
require("classes/categoria.php");
require("classes/servicios_adicionales.php");

// Get current language from session
session_start();
$idioma = isset($_SESSION["idioma"]) ? $_SESSION["idioma"] : "ES";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST["setServicioAdicional"])) {
    $nombre = [
      'ES' => $_POST["nombre"],
      'EN' => $_POST["nombre_en"],
      'PT' => $_POST["nombre_pt"],
      'IT' => $_POST["nombre_it"]
    ];

    $descripcion = [
      'ES' => $_POST["descripcion_servicio_adicional"],
      'EN' => $_POST["descripcion_servicio_adicional_en"],
      'PT' => $_POST["descripcion_servicio_adicional_pt"],
      'IT' => $_POST["descripcion_servicio_adicional_it"]
    ];

    $servicio_adicional_resu = setServicioAdicional($nombre, $descripcion);
    if ($servicio_adicional_resu > 0) {
      alertar("Servicio Adicional guardado con éxito", "success");
    }
  }

  if (isset($_POST["eliminarServicioAdicional"])) {
    $idServiciosAdicionales = $_POST["eliminarServicioAdicional"];
    $getServiciosAdicionalesAllSalidas = getServiciosAdicionalesAllSalidas($idServiciosAdicionales);
    $getServiciosAdicionalesAllCategorias = getServiciosAdicionalesAllCategorias($idServiciosAdicionales);

    if (count($getServiciosAdicionalesAllSalidas) > 0) {
      alertar("El servicio adicional que desea eliminar esta asignado a algunas salidas de servicio", "error");
    } elseif (count($getServiciosAdicionalesAllCategorias) > 0) {
      alertar("El servicio adicional que desea eliminar esta asignado a alguna categoria", "error");
    } else {
      $servicio_adicional_resu = borraServicioAdicional($idServiciosAdicionales);
      if ($servicio_adicional_resu > 0) {
        alertar("Servicio Adicional eliminado con éxito", "success");
      }
    }
  }
}

$servicios_adicionales = getServiciosAdicionales();
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h3 class="m-0 text-dark">Servicios adicionales</h3>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Editor</a></li>
              <li class="breadcrumb-item active">Servicios adicionales</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        <div class="card card-default">
          <div class="card-header">
            <button type="button" class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#exampleModal" data-whatever="agregarNota">
              Agregar servicio adicional
            </button>

            <button type="button" class="btn btn-secondary btn-lg btn-block" data-card-widget="collapse">
              Lista de servicios adicionales
            </button>
          </div>

  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Nuevo Servicio Adicional</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <form method="post">
            <!-- Spanish Fields -->
            <div class="form-group">
              <label for="recipient-name">Nombre (Español):</label>
              <input type="text" name="nombre" class="form-control" id="recipient-name" required>
            </div>
            <div class="form-group">
              <label for="message-text">Descripción (Español):</label>
              <textarea name="descripcion_servicio_adicional" class="form-control" id="message-text"></textarea>
            </div>

            <!-- English Fields -->
            <div class="form-group">
              <label for="recipient-name-en">Name (English):</label>
              <input type="text" name="nombre_en" class="form-control" id="recipient-name-en">
            </div>
            <div class="form-group">
              <label for="message-text-en">Description (English):</label>
              <textarea name="descripcion_servicio_adicional_en" class="form-control" id="message-text-en"></textarea>
            </div>

            <!-- Portuguese Fields -->
            <div class="form-group">
              <label for="recipient-name-pt">Nome (Português):</label>
              <input type="text" name="nombre_pt" class="form-control" id="recipient-name-pt">
            </div>
            <div class="form-group">
              <label for="message-text-pt">Descrição (Português):</label>
              <textarea name="descripcion_servicio_adicional_pt" class="form-control" id="message-text-pt"></textarea>
            </div>

            <!-- Italian Fields -->
            <div class="form-group">
              <label for="recipient-name-it">Nome (Italiano):</label>
              <input type="text" name="nombre_it" class="form-control" id="recipient-name-it">
            </div>
            <div class="form-group">
              <label for="message-text-it">Descrizione (Italiano):</label>
              <textarea name="descripcion_servicio_adicional_it" class="form-control" id="message-text-it"></textarea>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
              <button type="submit" name="setServicioAdicional" class="btn btn-primary">Agregar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Table section -->
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
            <?php foreach ($servicios_adicionales as $servicio) {
              $idServiciosAdicionales = $servicio["idServiciosAdicionales"];
              // Get name based on current language
              $nombre = $servicio["nombre_" . strtolower($idioma)] ?? $servicio["nombre"];
            ?>
              <tr>
                <td><?= htmlspecialchars($nombre) ?></td>
                <td>
                  <form method="post" id="borra<?= $idServiciosAdicionales; ?>">
                    <input type="hidden" name="eliminarServicioAdicional" value="<?= $idServiciosAdicionales; ?>">
                    <a onclick="borrar(<?= $idServiciosAdicionales; ?>)" class="btn btn-xs btn-danger">
                      Eliminar
                    </a>
                  </form>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Scripts section -->
  <script type="text/javascript">
    function borrar(idServiciosAdicionales) {
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
          var formulario = "#borra" + idServiciosAdicionales;
          $(formulario).submit();
        } else {
          return false;
        }
      });
    }

    $(document).ready(function() {
      var table = $('#tablaCarrito').DataTable({});

      $('#tablaCarrito').on('click', 'td.details-control', function() {
        var tr = $(this).closest('tr');
        var row = table.row(tr);

        if (row.child.isShown()) {
          row.child.hide();
          tr.removeClass('shown');
        } else {
          row.child(format(tr.data('child-value'))).show();
          tr.addClass('shown');
        }
      });
    });
  </script>
</div>

<?php include("includes/footer.php"); ?>
