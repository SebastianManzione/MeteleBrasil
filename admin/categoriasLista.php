<?php
ob_start(); // Inicia o buffer de saída
// Verificar permisos de acceso ANTES de cualquier salida
require_once(__DIR__ . "/classes/permisos.php");
require_once(__DIR__ . "/includes/permisos_helper.php");
$permisos = new PermisosManager($GLOBALS['pdo'], $_SESSION['login'] ?? []);
$permisos->verificarAcceso('categoriasLista');

include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");

require("classes/functions.php");
require("classes/categoria.php");

if ($_SESSION["login"]["rol"] != 1) {
  alertar("Usted no tiene acceso a esta seccion del software", "error");
  redireccionarLento("index");
}

$categorias = getAllCategorias();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_es = $_POST['nombre_es'] ?? '';
    $nombre_en = $_POST['nombre_en'] ?? '';
    $nombre_pt = $_POST['nombre_pt'] ?? '';
    $descripcion_es = $_POST['descripcion_es'] ?? '';
    $descripcion_en = $_POST['descripcion_en'] ?? '';
    $descripcion_pt = $_POST['descripcion_pt'] ?? '';
    $descripcionCorta_es = $_POST['descripcionCorta_es'] ?? '';
    $descripcionCorta_en = $_POST['descripcionCorta_en'] ?? '';
    $descripcionCorta_pt = $_POST['descripcionCorta_pt'] ?? '';
    $nombre_it = $_POST['nombre_it'] ?? '';
    $descripcion_it = $_POST['descripcion_it'] ?? '';
    $descripcionCorta_it = $_POST['descripcionCorta_it'] ?? '';
    $nViajeros = $_POST['nViajeros'] ?? 0;

    try {
        // Salva a categoria e obtém o ID
        $categoria_id = saveCategoria(
            $nombre_es, $nombre_en, $nombre_pt, $nombre_it, // Nomes
            $descripcion_es, $descripcion_en, $descripcion_pt, $descripcion_it, // Descrições
            $descripcionCorta_es, $descripcionCorta_en, $descripcionCorta_pt, $descripcionCorta_it, // Descrições curtas
            $nViajeros // Número de viajantes
        );

        if ($categoria_id) {
            $img_categoria = $_FILES['img_categoria'] ?? null;
            if ($img_categoria && $img_categoria['error'] === UPLOAD_ERR_OK) {
                $img_categoria_name = uploadImagem($img_categoria, $categoria_id);
                updateImagemCategoria($categoria_id, $img_categoria_name);
            }

            // Processa o upload das guias
            $guia_es = $_FILES['guia_es'] ?? null;
            $guia_en = $_FILES['guia_en'] ?? null;
            $guia_pt = $_FILES['guia_pt'] ?? null;
            $guia_it = $_FILES['guia_it'] ?? null;

            if ($guia_es && $guia_es['error'] === UPLOAD_ERR_OK) {
                $guia_es_name = uploadGuia($guia_es, $categoria_id, 'es');
                updateGuiaCategoria($categoria_id, $guia_es_name, 'es');
            }

            if ($guia_en && $guia_en['error'] === UPLOAD_ERR_OK) {
                $guia_en_name = uploadGuia($guia_en, $categoria_id, 'en');
                updateGuiaCategoria($categoria_id, $guia_en_name, 'en');
            }

            if ($guia_pt && $guia_pt['error'] === UPLOAD_ERR_OK) {
                $guia_pt_name = uploadGuia($guia_pt, $categoria_id, 'pt');
                updateGuiaCategoria($categoria_id, $guia_pt_name, 'pt');
            }

            if ($guia_it && $guia_it['error'] === UPLOAD_ERR_OK) {
                $guia_it_name = uploadGuia($guia_it, $categoria_id, 'it');
                updateGuiaCategoria($categoria_id, $guia_it_name, 'it');
            }

            ob_end_clean(); // Limpa o buffer
            header("Location: ".$_SERVER['PHP_SELF']);
            exit();
        }
    } catch (Exception $e) {
        echo "<script>alert('Error: ".$e->getMessage()."');</script>";
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

          <button type="button" class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#exampleModal"
                  data-whatever="agregarNota">Agregar categoria
          </button>


<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar nueva categoría</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="categoriaForm" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Nombre (ES)</label>
                        <input name="nombre_es" id="nombre_es" class="form-control" placeholder="Escriba el nombre de la categoría" required>
                    </div>
                    <div class="form-group">
                        <label>Nombre (EN)</label>
                        <input name="nombre_en" id="nombre_en" class="form-control" placeholder="Enter category name" required>
                    </div>
                    <div class="form-group">
                        <label>Nombre (PT)</label>
                        <input name="nombre_pt" id="nombre_pt" class="form-control" placeholder="Digite o nome da categoria" required>
                    </div>
                    <div class="form-group">
                        <label>Nome (IT)</label>
                        <input name="nombre_it" id="nombre_it" class="form-control" placeholder="Inserisci il nome della categoria" required>
                    </div>
                    <div class="form-group">
                        <label>Descripción (ES)</label>
                        <textarea name="descripcion_es" id="descripcion_es" class="form-control" placeholder="Describa brevemente la categoría" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Descripción (EN)</label>
                        <textarea name="descripcion_en" id="descripcion_en" class="form-control" placeholder="Briefly describe the category" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Descripción (PT)</label>
                        <textarea name="descripcion_pt" id="descripcion_pt" class="form-control" placeholder="Descreva brevemente a categoria" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Descrizione (IT)</label>
                        <textarea name="descripcion_it" id="descripcion_it" class="form-control" placeholder="Descrivi brevemente la categoria" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Descripción Corta (ES)</label>
                        <input name="descripcionCorta_es" id="descripcionCorta_es" class="form-control" placeholder="Breve descripción" required>
                    </div>
                    <div class="form-group">
                        <label>Descripción Corta (EN)</label>
                        <input name="descripcionCorta_en" id="descripcionCorta_en" class="form-control" placeholder="Short description" required>
                    </div>
                    <div class="form-group">
                        <label>Descripción Corta (PT)</label>
                        <input name="descripcionCorta_pt" id="descripcionCorta_pt" class="form-control" placeholder="Breve descrição" required>
                    </div>
                    <div class="form-group">
                      <label>Descrizione Corta (IT)</label>
                      <input name="descripcionCorta_it" id="descripcionCorta_it" class="form-control" placeholder="Breve descrizione" required>
                    </div>
                    <div class="form-group">
                        <label># Viajeros</label>
                        <input class="form-control" type="number" name="nViajeros" id="nViajeros" min="0" max="10000" step="1" required>
                    </div>
                    <div class="form-group">
                      <label># Pasajeros</label>
                      <input class="form-control" type="number" name="nPasajeros" id="nPasajeros" min="0" max="10000" step="1" required>
                    </div>
                    <div class="form-group">
                      <label># Opiniones</label>
                      <input class="form-control" type="number" name="nOpiniones" id="nOpiniones" min="0" max="10000" step="1" required>
                    </div>
                    <div class="form-group">
                      <label>Puntuación</label>
                      <input class="form-control" type="number" name="puntuacion" id="puntuacion" min="1" max="10" step="0.5" required>
                    </div>
                    <hr>
                    <div class="form-group">
                      <label>Imagem da Categoria</label>
                      <input type="file" name="img_categoria" id="img_categoria" class="form-control" accept="image/*" required>
                    </div>
                    <hr>
                    <div class="form-group">
                        <label>Guía (ES)</label>
                        <input type="file" name="guia_es" id="guia_es" class="form-control" accept="application/pdf">
                    </div>
                    <div class="form-group">
                        <label>Guía (EN)</label>
                        <input type="file" name="guia_en" id="guia_en" class="form-control" accept="application/pdf">
                    </div>
                    <div class="form-group">
                        <label>Guía (PT)</label>
                        <input type="file" name="guia_pt" id="guia_pt" class="form-control" accept="application/pdf">
                    </div>
                    <div class="form-group">
                      <label>Guida (IT)</label>
                      <input type="file" name="guia_it" id="guia_it" class="form-control" accept="application/pdf">
                    </div>
                    <button type="submit" class="btn btn-primary">Agregar</button>
                </form>
            </div>
        </div>
    </div>
</div>

          <button type="button" class="btn btn-secondary btn-lg btn-block" data-card-widget="collapse">Lista de
            categorias
          </button>


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


                  <th scope="col"># de viajeros</th>

                  <th scope="col">ver..</th>


                </tr>

                </thead>


                <tbody>
                <?php
                for ($i = 0; $i < count($categorias); $i++) {
                  $idCategoria_servicioTMP = $categorias[$i]["idCategoria_servicio"];
                  ?>
                  <tr>
                    <td><?= $categorias[$i]["nombre_categoria_servicio"]; ?></td>
                    <td><?= substr($categorias[$i]["descripcion_categoria_servicio"], 0, 70); ?><?php if (strlen($categorias[$i]["descripcion_categoria_servicio"]) > 70) {
                        echo "...";
                      } ?></td>
                    <td><?= $categorias[$i]["descripcionCorta_categoria_servicio"]; ?></td>
                    <td><?= $categorias[$i]["nViajeros"]; ?></td>
                    <td><a href="categoriaVer.php?idCategoria=<?= $idCategoria_servicioTMP ?>"
                           class="btn-sm btn-success">Ver</a></td>
                  </tr>
                  <?php
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
