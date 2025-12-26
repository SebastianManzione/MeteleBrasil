<?php
// Verificar permisos de acceso ANTES de cualquier salida
require_once(__DIR__ . "/classes/permisos.php");
require_once(__DIR__ . "/includes/permisos_helper.php");
$permisos = new PermisosManager($GLOBALS['pdo'], $_SESSION['login'] ?? []);
$permisos->verificarAcceso('altaServicio'); // Redirige si no tiene permiso

include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");

require("classes/functions.php");
require("classes/categoria.php");
require("classes/paises.php");
require("classes/destinos.php");
require("classes/texto_miniaturas.php");
require("classes/servicio.php");
require("classes/fotos_servicio.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['actualizar'])) {
    $idServicio = $_GET['idServicio'];
    $idUsuario = $_SESSION['login']['idUsuario'];

    $idServiciodd = updateServicio(
        $_POST['txtNomEvt'], $_POST['txtNomEvt_en'], $_POST['txtNomEvt_pt'], $_POST['txtNomEvt_it'],
        $_POST['selCategoria'],
        $_POST['txtDescripcion'], $_POST['txtDescripcion_en'], $_POST['txtDescripcion_pt'], $_POST['txtDescripcion_it'],
        $_POST['txtDescripcionCorta'], $_POST['txtDescripcionCorta_en'], $_POST['txtDescripcionCorta_pt'], $_POST['txtDescripcionCorta_it'],
        $_POST['txtDocumentacionViajero'], $_POST['txtDocumentacionViajero_en'], $_POST['txtDocumentacionViajero_pt'], $_POST['txtDocumentacionViajero_it'],
        $_POST['txtObservaciones'], $_POST['txtObservaciones_en'], $_POST['txtObservaciones_pt'], $_POST['txtObservaciones_it'],
        $_POST['idTextoMiniatura'], $idUsuario, $_POST['idOrigen'], $_POST['idDestino'], $_POST['idServicio']
    );

    if (count($_FILES) > 1) {
        $fotos = altaFotosServicio($_FILES, $idServicio);
    }
    if ($idServiciodd > 0) {
        alertar($lang["servicio_actualizado_correctamente"], "success");
        redireccionar("servicioVer?idServicio=" . $idServicio);
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['guardar'])) {
    $idUsuario = $_SESSION['login']['idUsuario'];

    $idServicio = altaServicio(
        $_POST['txtNomEvt'], $_POST['txtNomEvt_en'], $_POST['txtNomEvt_pt'], $_POST['txtNomEvt_it'],
        $_POST['selCategoria'],
        $_POST['txtDescripcion'], $_POST['txtDescripcion_en'], $_POST['txtDescripcion_pt'], $_POST['txtDescripcion_it'],
        $_POST['txtDescripcionCorta'], $_POST['txtDescripcionCorta_en'], $_POST['txtDescripcionCorta_pt'], $_POST['txtDescripcionCorta_it'],
        $_POST['txtDocumentacionViajero'], $_POST['txtDocumentacionViajero_en'], $_POST['txtDocumentacionViajero_pt'], $_POST['txtDocumentacionViajero_it'],
        $_POST['txtObservaciones'], $_POST['txtObservaciones_en'], $_POST['txtObservaciones_pt'], $_POST['txtObservaciones_it'],
        $_POST['idTextoMiniatura'], $idUsuario, $_POST['idOrigen'], $_POST['idDestino']
    );

    $_SESSION["altaServicio"] = $idServicio;

    if (count($_FILES) > 0) {
        $fotos = altaFotosServicio($_FILES, $idServicio);
    }
    alertar($lang["servicio_cargado_correctamente"], "success");
    redireccionar("altaSalidas.php");
    exit();
}

// Inicializa variáveis
$textoNuevoOEditar = "Detalhes do serviço";
$nombre_servicio = $nombre_servicio_en = $nombre_servicio_pt = $nombre_servicio_it = '';
$idCategoria_servicio = '';
$descripcion_corta = $descripcion_corta_en = $descripcion_corta_pt = $descripcion_corta_it = '';
$descripcion_servicio = $descripcion_servicio_en = $descripcion_servicio_pt = $descripcion_servicio_it = '';
$documentacionViajero = $documentacionViajero_en = $documentacionViajero_pt = $documentacionViajero_it = '';
$idTextoMiniaturas = '';
$observaciones = $observaciones_en = $observaciones_pt = $observaciones_it = '';
$idOrigen = $idDestino = "";
$idServicio = 0;

if (isset($_GET['idServicio'])) {
    $idServicio = $_GET['idServicio'];
    $textoNuevoOEditar = "EDITANDO SERVICIO " . $idServicio;
    $servicio = getServicio($idServicio);
    $nombre_servicio = $servicio[0]['nombre_servicio'];
    $nombre_servicio_en = $servicio[0]['nombre_servicio_en'];
    $nombre_servicio_pt = $servicio[0]['nombre_servicio_pt'];
    $nombre_servicio_it = $servicio[0]['nombre_servicio_it'];
    $idCategoria_servicio = $servicio[0]['idCategoria_servicio'];
    $descripcion_corta = $servicio[0]['descripcion_corta'];
    $descripcion_corta_en = $servicio[0]['descripcion_corta_en'];
    $descripcion_corta_pt = $servicio[0]['descripcion_corta_pt'];
    $descripcion_corta_it = $servicio[0]['descripcion_corta_it'];
    $descripcion_servicio = $servicio[0]['descripcion_servicio'];
    $descripcion_servicio_en = $servicio[0]['descripcion_servicio_en'];
    $descripcion_servicio_pt = $servicio[0]['descripcion_servicio_pt'];
    $descripcion_servicio_it = $servicio[0]['descripcion_servicio_it'];
    $documentacionViajero = $servicio[0]['documentacionViajero'];
    $documentacionViajero_en = $servicio[0]['documentacionViajero_en'];
    $documentacionViajero_pt = $servicio[0]['documentacionViajero_pt'];
    $documentacionViajero_it = $servicio[0]['documentacionViajero_it'];
    $idTextoMiniaturas = $servicio[0]['idTextoMiniaturas'];
    $observaciones = $servicio[0]['observaciones'];
    $observaciones_en = $servicio[0]['observaciones_en'];
    $observaciones_pt = $servicio[0]['observaciones_pt'];
    $observaciones_it = $servicio[0]['observaciones_it'];
    $idOrigen = $servicio[0]['idOrigen'];
    $idDestino = $servicio[0]['idDestino'];
}
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark"><?= $textoNuevoOEditar; ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#"><?= $lang["inicio"]; ?></a></li>
                        <li class="breadcrumb-item active"><?= $textoNuevoOEditar; ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
      <div class="container-fluid">
        <div class="card card-default">
          <div class="card-header">
            <h3 class="card-title"><?= $textoNuevoOEditar ?></h3>
          </div>
          <form method="post" enctype="multipart/form-data">
            <div class="card-body">
              <!-- Abas de Idiomas -->
              <ul class="nav nav-tabs" id="languageTabs" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" id="spanish-tab" data-toggle="tab" href="#spanish" role="tab" aria-controls="spanish" aria-selected="true">
                    <img src="img/countries/Spain-icon.png" alt="Español" width="24"> Español
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="english-tab" data-toggle="tab" href="#english" role="tab" aria-controls="english" aria-selected="false">
                    <img src="img/countries/United-States-of-Americ-icon.png" alt="English" width="24"> English
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="portuguese-tab" data-toggle="tab" href="#portuguese" role="tab" aria-controls="portuguese" aria-selected="false">
                    <img src="img/countries/Brazil-icon.png" alt="Português" width="24"> Português
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="italian-tab" data-toggle="tab" href="#italian" role="tab" aria-controls="italian" aria-selected="false">
                    <img src="img/countries/italy-icon.png" alt="Italiano" width="24"> Italiano
                  </a>
                </li>
              </ul>

              <!-- Conteúdo das Abas -->
              <div class="tab-content p-3" id="languageTabsContent">
                <!-- Conteúdo em Espanhol -->
                <div class="tab-pane fade show active" id="spanish" role="tabpanel" aria-labelledby="spanish-tab">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label>Nome da Atividade (ES)</label>
                        <input name="txtNomEvt" class="form-control" value="<?= $nombre_servicio ?>" required>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label>Descrição Curta (ES)</label>
                        <textarea name="txtDescripcionCorta" class="form-control"><?= $descripcion_corta ?></textarea>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label>Descrição Completa (ES)</label>
                        <textarea name="txtDescripcion" class="form-control"><?= $descripcion_servicio ?></textarea>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label>Documentação para o Viajante (ES)</label>
                        <textarea name="txtDocumentacionViajero" class="form-control"><?= $documentacionViajero ?></textarea>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label>Observações (ES)</label>
                        <textarea name="txtObservaciones" class="form-control"><?= $observaciones ?></textarea>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Conteúdo em Inglês -->
                <div class="tab-pane fade" id="english" role="tabpanel" aria-labelledby="english-tab">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label>Activity Name (EN)</label>
                        <input name="txtNomEvt_en" class="form-control" value="<?= $nombre_servicio_en ?>" required>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label>Short Description (EN)</label>
                        <textarea name="txtDescripcionCorta_en" class="form-control"><?= $descripcion_corta_en ?></textarea>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label>Full Description (EN)</label>
                        <textarea name="txtDescripcion_en" class="form-control"><?= $descripcion_servicio_en ?></textarea>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label>Traveler Documentation (EN)</label>
                        <textarea name="txtDocumentacionViajero_en" class="form-control"><?= $documentacionViajero_en ?></textarea>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label>Observations (EN)</label>
                        <textarea name="txtObservaciones_en" class="form-control"><?= $observaciones_en ?></textarea>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Conteúdo em Português -->
                <div class="tab-pane fade" id="portuguese" role="tabpanel" aria-labelledby="portuguese-tab">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label>Nome da Atividade (PT)</label>
                        <input name="txtNomEvt_pt" class="form-control" value="<?= $nombre_servicio_pt ?>" required>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label>Descrição Curta (PT)</label>
                        <textarea name="txtDescripcionCorta_pt" class="form-control"><?= $descripcion_corta_pt ?></textarea>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label>Descrição Completa (PT)</label>
                        <textarea name="txtDescripcion_pt" class="form-control"><?= $descripcion_servicio_pt ?></textarea>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label>Documentação do Viajante (PT)</label>
                        <textarea name="txtDocumentacionViajero_pt" class="form-control"><?= $documentacionViajero_pt ?></textarea>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label>Observações (PT)</label>
                        <textarea name="txtObservaciones_pt" class="form-control"><?= $observaciones_pt ?></textarea>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Conteúdo em Italiano -->
                <div class="tab-pane fade" id="italian" role="tabpanel" aria-labelledby="italian-tab">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label>Nome dell'Attività (IT)</label>
                        <input name="txtNomEvt_it" class="form-control" value="<?= $nombre_servicio_it ?>" required>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label>Descrizione Breve (IT)</label>
                        <textarea name="txtDescripcionCorta_it" class="form-control"><?= $descripcion_corta_it ?></textarea>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label>Descrizione Completa (IT)</label>
                        <textarea name="txtDescripcion_it" class="form-control"><?= $descripcion_servicio_it ?></textarea>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label>Documentazione per il Viaggiatore (IT)</label>
                        <textarea name="txtDocumentacionViajero_it" class="form-control"><?= $documentacionViajero_it ?></textarea>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label>Osservazioni (IT)</label>
                        <textarea name="txtObservaciones_it" class="form-control"><?= $observaciones_it ?></textarea>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Campos comuns a todos os idiomas -->
              <div class="mt-4">
                <h4>Informações Gerais</h4>

                <!-- Categoria -->
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label><?= $lang["categoria_"]; ?></label>
                      <p><?= $lang["indique_a_que_tipo_de_categoria"]; ?></p>
                      <select name="selCategoria" class="form-control" required>
                        <?php
                        $categorias = getAllCategorias();
                        foreach ($categorias as $categoria) {
                          $selected = ($idCategoria_servicio == $categoria['idCategoria_servicio']) ? 'selected' : '';
                          echo "<option value='{$categoria["idCategoria_servicio"]}' {$selected}>{$categoria["nombre_categoria_servicio"]}</option>";
                        }
                        ?>
                      </select>
                    </div>
                  </div>
                </div>

                <!-- Upload de Imagens -->
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label><?= $lang["imagenes"]; ?></label>
                      <input type="file" name="file[]" class="file-input form-control-file" multiple id="gallery-photo-add" />
                    </div>
                  </div>
                </div>

                <!-- Texto Miniatura -->
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label><?= $lang["texto_destacado_miniatura"]; ?></label>
                      <p><?= $lang["elija_el_texto_que_destaca"]; ?></p>
                      <select name="idTextoMiniatura" class="form-control" required>
                        <?php
                        $textMiniaturas = getTextosMiniaturas();
                        foreach ($textMiniaturas as $texto) {
                          $selected = ($idTextoMiniaturas == $texto['idTextoMiniaturas']) ? 'selected' : '';
                          echo "<option value='{$texto["idTextoMiniaturas"]}' {$selected}>{$texto["texto"]}</option>";
                        }
                        ?>
                      </select>
                    </div>
                  </div>
                </div>

                <!-- Origen y Destino -->
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label><?= $lang["origen"]; ?></label>
                      <p><?= $lang["indique_el_origen"]; ?></p>
                      <select name="idOrigen" class="form-control" required>
                        <?php
                        $destinos = getDestinos();
                        foreach ($destinos as $destino) {
                          $selected = ($idOrigen == $destino["idDestino"]) ? 'selected' : '';
                          $pais = getPais($destino["idPais"]);
                          echo "<option value='{$destino["idDestino"]}' {$selected}>{$destino["nombre"]}, {$destino["estado"]}, {$pais[0]["nombre"]}</option>";
                        }
                        ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label><?= $lang["destino"]; ?></label>
                      <p><?= $lang["indique_el_destino"]; ?></p>
                      <select name="idDestino" class="form-control" required>
                        <?php
                        foreach ($destinos as $destino) {
                          $selected = ($idDestino == $destino["idDestino"]) ? 'selected' : '';
                          $pais = getPais($destino["idPais"]);
                          echo "<option value='{$destino["idDestino"]}' {$selected}>{$destino["nombre"]}, {$destino["estado"]}, {$pais[0]["nombre"]}</option>";
                        }
                        ?>
                      </select>
                    </div>
                  </div>
                </div>

                <input type="hidden" name="idServicio" value="<?=$idServicio;?>">
              </div>
            </div>

            <!-- Botões -->
            <div class="card-footer">
              <div align="center">
                <?php if (isset($_GET['idServicio']) || isset($_POST['idServicio'])) { ?>
                  <button type="submit" class="btn btn-info" name="actualizar">
                    <i class="fa fa-floppy-o"></i> <?= $lang["actualizar_cambios"]; ?>
                  </button>
                <?php } else { ?>
                  <button type="submit" class="btn btn-success" name="guardar">
                    <i class="fa fa-floppy-o"></i> <?= $lang["continuar_"]; ?>
                  </button>
                <?php } ?>
                <a href="serviciosLista.php" class="btn btn-danger">
                  <i class="fa fa-times"></i> <?= $lang["salir_sin_guardar"]; ?>
                </a>
                <?php if (isset($_GET['idServicio']) || isset($_POST['idServicio'])) { ?>
                  <a href="servicioVer.php?idServicio=<?= $idServicio; ?>" class="btn btn-primary">
                    <?= $lang["volver"]; ?>
                  </a>
                <?php } ?>
              </div>
            </div>
          </form>
        </div>
      </div>
    </section>
</div>

<!-- TinyMCE Script
<script src="https://cdn.tiny.cloud/1/tmziljuhbvkgvh6s3nraitzg8kqwidrdhvdwhhna089a987b/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: 'textarea',
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
        tinycomments_mode: 'embedded',
        tinycomments_author: 'Author name',
        mergetags_list: [
            { value: 'First.Name', title: 'First Name' },
            { value: 'Email', title: 'Email' },
        ],
        language: 'pt_BR'
    });
</script>
 -->
<?php include "includes/footer.php"; ?>
