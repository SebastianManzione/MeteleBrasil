<?php
// Verificar permisos de acceso ANTES de cualquier salida
require_once(__DIR__ . "/classes/permisos.php");
require_once(__DIR__ . "/includes/permisos_helper.php");
$permisos = new PermisosManager($GLOBALS['pdo'], $_SESSION['login'] ?? []);
$permisos->verificarAcceso('textoMiniaturaLista');

include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");

require("classes/functions.php");
require("classes/categoria.php");
require("classes/texto_miniaturas.php");

if (!$_SESSION["login"]["rol"]==1) {

  alertar("Usted no tiene acceso a esta seccion del software", "error");

  redireccionarLento("index");

}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["texto_es"])) {
        $texto_es = $_POST["texto_es"];
        $texto_en = $_POST["texto_en"];
        $texto_pt = $_POST["texto_pt"];
        $texto_it = $_POST["texto_it"];

        $resu = setTextoMiniatura($texto_es, $texto_en, $texto_pt, $texto_it);
        if ($resu > 0) {
            alertar("Texto guardado correctamente", "success");
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST["eliminarTextoMiniaturas"])) {
    $eliminarTextoMiniaturas = $_POST["eliminarTextoMiniaturas"];
    $resu = borraTextoMiniatura($eliminarTextoMiniaturas);

    if ($resu == 1) {
      alertar("Texto borrado correctamente", "warning");
    }
    if ($resu == -5) {
      alertar("No se puede borrar un texto de miniatura mientras existan servicios que utilicen ese texto", "error");
    }
  }


  // code...
}


$textos_miniaturas = getTextosMiniaturas();

?>

<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper">

  <!-- Content Header (Page header) -->

  <div class="content-header">

    <div class="container-fluid">

      <div class="row mb-2">

        <div class="col-sm-6">

          <h1 class="m-0 text-dark">Textos Miniaturas</h1>

        </div><!-- /.col -->

        <div class="col-sm-6">

          <ol class="breadcrumb float-sm-right">

            <li class="breadcrumb-item"><a href="#">Editor Textos Miniaturas</a></li>

            <li class="breadcrumb-item active">Editor Textos Miniaturas</li>

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

          <button type="button" class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#exampleModal"
                  data-whatever="agregarNota">Agregar nuevo texto miniaturas
          </button>


          <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Agregar nuevo texto miniaturas</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <form method="post">
                    <label class="col-form-label">Texto (Español)</label>
                    <input class="form-control" type="text" name="texto_es" required>

                    <label class="col-form-label">Texto (Inglés)</label>
                    <input class="form-control" type="text" name="texto_en" required>

                    <label class="col-form-label">Texto (Português)</label>
                    <input class="form-control" type="text" name="texto_pt" required>

                    <label class="col-form-label">Texto (Italiano)</label>
                    <input class="form-control" type="text" name="texto_it" required>

                    <button type="submit" class="btn btn-primary">Agregar</button>
                  </form>
                </div>
              </div>
            </div>
          </div>

          <button type="button" class="btn btn-secondary btn-lg btn-block" data-card-widget="collapse">Lista de textos
            miniaturas
          </button>


        </div>

        <!-- /.card-header -->


        <div class="card-body">

          <div class="row">

            <div class="table-responsive">


              <table class="table" id="tablaCarrito">

                <thead>

                <tr>

                  <th scope="col">IdTexto</th>

                  <th scope="col">Texto</th>

                  <th scope="col">Acción</th>


                </tr>

                </thead>


                <tbody>

                <?php


                for ($i = 0; $i < count($textos_miniaturas); $i++) {
                  $idTextoMiniaturas = $textos_miniaturas[$i]["idTextoMiniaturas"];
                  $texto = $textos_miniaturas[$i]["texto"];
                  echo "<tr>
                    <td>$idTextoMiniaturas</td>
                    <td>$texto</td>
                    <td>
                        <form method='post'>
                            <button type='submit' name='eliminarTextoMiniaturas' value='$idTextoMiniaturas' class='btn-sm btn-warning'>Eliminar</button>
                        </form>
                    </td>
                  </tr>";
                }

                ?>


                </tbody>


              </table>

            </div>

          </div>


        </div><!-- /.card-body -->


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
