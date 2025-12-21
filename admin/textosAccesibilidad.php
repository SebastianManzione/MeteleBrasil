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
require("classes/accesibilidad.php");
require("classes/convierte_monedas.php");

if (!$_SESSION["login"]["rol"] = 1) {
    alertar("Usted no tiene acceso a esta seccion del software", "error");
    redireccionarLento("index");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["addTextoAccesiblidad"])) {
      $texto_es = $_POST["texto_es"];
      $texto_en = $_POST["texto_en"];
      $texto_pt = $_POST["texto_pt"];
      $texto_it = $_POST["texto_it"];

      $resuAccesibilidad = setTextoAccesibilidad($texto_es, $texto_en, $texto_pt, $texto_it);
      if ($resuAccesibilidad > 0) {
        alertar("Texto de accesibilidad guardado con éxito", "success");
      }
    }

    if (isset($_POST["eliminarAccesibilidad"])) {
        $idAccesibilidad = $_POST["eliminarAccesibilidad"];
        $getAllAccesiblidadesSalidas = getAllAccesiblidadesSalidas($idAccesibilidad);

        if (count($getAllAccesiblidadesSalidas) > 0) {
            alertar("El texto accesibilidad que desea eliminar esta asignado a algunas salidas de servicio", "error");
        } else {
            $servicio_adicional_resu = borraTextoAccesibilidad($idAccesibilidad);
            if ($servicio_adicional_resu > 0) {
                alertar("Texto Accesiblidad eliminado con éxito", "success");
            }
        }
    }
}

$accesibilidades = getAccesibilidades();
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
            <div class="card card-default">
                <div class="card-header">
                    <button type="button" class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#exampleModal" data-whatever="agregarNota">Agregar texto de Accesibilidad</button>

                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Nuevo texto de accesibilidad</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                              <div class="modal-body">
                                <form method="post">
                                  <div class="form-group">
                                    <label for="texto_es" class="col-form-label">Texto (ES):</label>
                                    <input type="text" class="form-control" name="texto_es">
                                  </div>
                                  <div class="form-group">
                                    <label for="texto_pt" class="col-form-label">Texto (PT):</label>
                                    <input type="text" class="form-control" name="texto_pt">
                                  </div>
                                  <div class="form-group">
                                    <label for="texto_en" class="col-form-label">Texto (EN):</label>
                                    <input type="text" class="form-control" name="texto_en">
                                  </div>
                                  <div class="form-group">
                                    <label for="texto_it" class="col-form-label">Texto (IT):</label>
                                    <input type="text" class="form-control" name="texto_it">
                                  </div>
                                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                  <button type="submit" name="addTextoAccesiblidad" class="btn btn-primary">Agregar</button>
                                </form>
                              </div>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn btn-secondary btn-lg btn-block" data-card-widget="collapse">Lista de Textos</button>
                </div>
                <!-- /.card-header -->

                <div class="card-body">
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table" id="tablaCarrito">
                                <thead>
                                    <tr>
                                        <th scope="col">Texto</th>
                                        <th scope="col">Accion</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    for ($i = 0; $i < count($accesibilidades); $i++) {
                                        $idAccesibilidad = $accesibilidades[$i]["idAccesibilidad"];
                                    ?>
                                        <tr>
                                            <td><?= $accesibilidades[$i]["texto"] ?></td>
                                            <td>
                                                <form method="post" id="borra<?= $idAccesibilidad; ?>">
                                                    <input type="hidden" name="eliminarAccesibilidad" value="<?= $idAccesibilidad; ?>">
                                                    <a onclick="borrar(<?= $idAccesibilidad; ?>)" class="btn btn-xs btn-danger">
                                                        Eliminar
                                                    </a>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div><!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div><!-- /.container-fluid -->
    </section>
</div>
<!-- /.content-wrapper -->

<script type="text/javascript">


  function borrar(idAccesibilidad) {


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
        var formulario = "#borra" + idAccesibilidad
        $(formulario).submit();
      } else {
        return false;
      }

    })

  }


</script>


<script type="text/javascript">
  function format(value) {
    return value;
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

<?php
include("includes/footer.php");
?>
