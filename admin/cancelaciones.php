<?php
// Verificar permisos de acceso ANTES de cualquier salida
require_once(__DIR__ . "/classes/permisos.php");
require_once(__DIR__ . "/includes/permisos_helper.php");
$permisos = new PermisosManager($GLOBALS['pdo'], $_SESSION['login'] ?? []);
$permisos->verificarAcceso('cancelaciones');

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
require("classes/edades.php");
require("classes/cancelaciones.php");

if ($_SESSION["login"]["rol"] <> 1) {
    alertar("Usted no tiene acceso a esta seccion del software", "error");
    redireccionarLento("index");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["addTextoCancelacion"])) {
        $texto_es = $_POST["texto_es"];
        $texto_pt = $_POST["texto_pt"];
        $texto_en = $_POST["texto_en"];
        $texto_it = $_POST["texto_it"];
        $resuCancelacion = setTextoCancelacion($texto_es, $texto_pt, $texto_en, $texto_it);

        if ($resuCancelacion > 0) {
            alertar("Texto de cancelacion guardado con éxito", "success");
        }
    }
}

$cancelaciones = getTiposCancelaciones();
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Cancelaciones</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Administración</a></li>
                        <li class="breadcrumb-item active">Cancelaciones</li>
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
                    <button type="button" class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#exampleModal" data-whatever="agregarNota">Agregar Cancelación</button>

                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Nueva cancelación</h5>
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
                                      <button type="submit" name="addTextoCancelacion" class="btn btn-primary">Agregar</button>
                                  </form>
                              </div>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn btn-secondary btn-lg btn-block" data-card-widget="collapse">Lista de textos de cancelaciones</button>
                </div>
                <!-- /.card-header -->

                <div class="card-body">
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table" id="tablaCarrito">
                                <thead>
                                    <tr>
                                        <th scope="col">Texto</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    for ($i = 0; $i < count($cancelaciones); $i++) {
                                        $idCancelacion = $cancelaciones[$i]["idCancelacion"];
                                    ?>
                                        <tr>
                                            <td data-order="<?= $cancelaciones[$i]["texto"] ?>"><?= $cancelaciones[$i]["texto"] ?></td>
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

<?php
include("includes/footer.php");
?>
