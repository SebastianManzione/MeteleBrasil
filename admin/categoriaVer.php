<?php
include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");
require("classes/functions.php");
require("classes/categoria.php");

if ($_SESSION["login"]["rol"] != 1) {
  alertar("Usted no tiene acceso a esta seccion del software", "error");
  redireccionarLento("index");
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $idCategoria = $_GET['idCategoria'];
    $categoria = getCategoria($idCategoria);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["desHabilitarCategoria"])) {
    desHabilitarCategoria($_POST["desHabilitarCategoria"]);
    $idCategoria = $_POST['desHabilitarCategoria'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["habilitarCategoria"])) {
    habilitarCategoria($_POST["habilitarCategoria"]);
    $idCategoria = $_POST['habilitarCategoria'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["editarGuia"]) && strlen($_FILES["file"]["name"][0]) > 0) {
    $idCategoria = $_POST['editarGuia'];
    $editarGuia = $_POST['editarGuia'];
    $categoria = getCategoria($idCategoria);
    $nombre_categoria = $categoria[0]["nombre_categoria_servicio"];
    $resul = updateGuiaCategoria($_FILES, $editarGuia);
    alertar("Guia de categoria atualizado com sucesso", "success");
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["editarCategoria"])) {
    $idCategoria = $_POST['editarCategoria'];
    $nombre_es = $_POST['nombre_categoria_es'];
    $nombre_en = $_POST['nombre_categoria_en'];
    $nombre_pt = $_POST['nombre_categoria_pt'];
    $descripcion_es = $_POST['descripcion_categoria_servicio_es'];
    $descripcion_en = $_POST['descripcion_categoria_servicio_en'];
    $descripcion_pt = $_POST['descripcion_categoria_servicio_pt'];
    $descripcionCorta_es = $_POST['descripcionCorta_categoria_servicio_es'];
    $descripcionCorta_en = $_POST['descripcionCorta_categoria_servicio_en'];
    $descripcionCorta_pt = $_POST['descripcionCorta_categoria_servicio_pt'];
    $nViajeros = $_POST['nViajeros'];

    // Processa o upload da imagem
    $img_categoria = $_FILES['img_categoria'] ?? null;
    $img_categoria_name = "";

    if ($img_categoria && $img_categoria['error'] === UPLOAD_ERR_OK) {
        $img_categoria_name = uploadImagem($img_categoria, $idCategoria);
    }

    // Atualiza a categoria
    $resul = updateCategoria(
      $idCategoria,
      $nombre_es, $nombre_en, $nombre_pt, $_POST['nombre_categoria_it'],
      $descripcion_es, $descripcion_en, $descripcion_pt, $_POST['descripcion_categoria_servicio_it'],
      $descripcionCorta_es, $descripcionCorta_en, $descripcionCorta_pt, $_POST['descripcionCorta_categoria_servicio_it'],
      $nViajeros
    );

    if ($resul && !empty($img_categoria_name)) {
        updateImagemCategoria($idCategoria, $img_categoria_name);
    }

    if ($resul) {
        alertar("Categoria atualizada com sucesso", "success");
    }
}

$categoria = getCategoria($idCategoria);
$nombre_categoria_es = $categoria[0]["nombre_categoria_servicio"];
$nombre_categoria_en = $categoria[0]["nombre_categoria_servicio_en"];
$nombre_categoria_pt = $categoria[0]["nombre_categoria_servicio_pt"];
$descripcion_categoria_servicio_es = $categoria[0]["descripcion_categoria_servicio"];
$descripcion_categoria_servicio_en = $categoria[0]["descripcion_categoria_servicio_en"];
$descripcion_categoria_servicio_pt = $categoria[0]["descripcion_categoria_servicio_pt"];
$nViajeros = $categoria[0]["nViajeros"];
$guia = $categoria[0]["guia"];
$descripcionCorta_categoria_servicio_es = $categoria[0]["descripcionCorta_categoria_servicio"];
$descripcionCorta_categoria_servicio_en = $categoria[0]["descripcionCorta_categoria_servicio_en"];
$descripcionCorta_categoria_servicio_pt = $categoria[0]["descripcionCorta_categoria_servicio_pt"];


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["editarGuia"])) {
    $idCategoria = $_POST['editarGuia'];

    // Processa o upload das guias
    $guia_es = $_FILES['guia_es'] ?? null;
    $guia_en = $_FILES['guia_en'] ?? null;
    $guia_pt = $_FILES['guia_pt'] ?? null;

    if ($guia_es && $guia_es['error'] === UPLOAD_ERR_OK) {
        $guia_es_name = uploadGuia($guia_es, $idCategoria, 'es');
        updateGuiaCategoria($idCategoria, $guia_es_name, 'es');
    }

    if ($guia_en && $guia_en['error'] === UPLOAD_ERR_OK) {
        $guia_en_name = uploadGuia($guia_en, $idCategoria, 'en');
        updateGuiaCategoria($idCategoria, $guia_en_name, 'en');
    }

    if ($guia_pt && $guia_pt['error'] === UPLOAD_ERR_OK) {
        $guia_pt_name = uploadGuia($guia_pt, $idCategoria, 'pt');
        updateGuiaCategoria($idCategoria, $guia_pt_name, 'pt');
    }

    alertar("Guias atualizadas com sucesso", "success");
    redireccionarLento("categoriaVer.php?idCategoria=" . $idCategoria);
}
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Editor de categoria <?= $nombre_categoria_es ?></h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Editor</a></li>
                        <li class="breadcrumb-item active">Editor de categoria <?= $nombre_categoria_es ?></li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="form-inline">
                    <div class="form-group">
                        <!-- Botões de habilitar/desabilitar categoria -->
                        <form method="post">
                            <?php if ($categoria[0]["habilitado"] == 0) { ?>
                                <button class="btn btn-sm btn-info" name="habilitarCategoria" value="<?= $idCategoria ?>">Habilitar categoria</button>
                            <?php } else { ?>
                                <button class="btn btn-sm btn-danger" name="desHabilitarCategoria" value="<?= $idCategoria ?>">Deshabilitar Categoria</button>
                            <?php } ?>
                        </form>
                    </div>
                    <div class="form-group">
                        <!-- Botão para editar opiniões -->
                        <form method="post" action="categoriaOpiniones">
                            <button type="submit" name="idCategoria_servicio" value="<?= $idCategoria ?>" class="btn-sm btn-primary">Editar Opiniones</button>
                        </form>
                    </div>
                    <div class="form-group">
                        <!-- Botão para serviços adicionais -->
                        <form method="post" action="categoriaServiciosAdicionales">
                            <button type="submit" name="idCategoria_servicio" value="<?= $idCategoria ?>" class="btn-sm btn-warning" target="_blank">Serviços adicionais</button>
                        </form>
                    </div>
                </div>
                <div class="form-inline float-right">
                    <div class="form-group">
                        <a href="categoriasLista" class="btn btn-success float-right"><i class="fas fa-arrow-left"></i> Voltar</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Em geral</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post">
                            <!-- Nome da Categoria -->
                            <div class="form-group">
                                <label>Nombre (ES)</label>
                                <input type="text" name="nombre_categoria_es" class="form-control" value="<?= $nombre_categoria_es ?>">
                            </div>
                            <div class="form-group">
                                <label>Nombre (EN)</label>
                                <input type="text" name="nombre_categoria_en" class="form-control" value="<?= $nombre_categoria_en ?>">
                            </div>
                            <div class="form-group">
                                <label>Nombre (PT)</label>
                                <input type="text" name="nombre_categoria_pt" class="form-control" value="<?= $nombre_categoria_pt ?>">
                            </div>
                            <div class="form-group">
                                <label>Nome (IT)</label>
                                <input type="text" name="nombre_categoria_it" class="form-control" value="<?= $categoria[0]["nombre_categoria_servicio_it"] ?? '' ?>">
                            </div>

                            <!-- Descrição da Categoria -->
                            <div class="form-group">
                                <label>Descripción (ES)</label>
                                <textarea name="descripcion_categoria_servicio_es" class="form-control" rows="4"><?= $descripcion_categoria_servicio_es ?></textarea>
                            </div>
                            <div class="form-group">
                                <label>Descripción (EN)</label>
                                <textarea name="descripcion_categoria_servicio_en" class="form-control" rows="4"><?= $descripcion_categoria_servicio_en ?></textarea>
                            </div>
                            <div class="form-group">
                                <label>Descripción (PT)</label>
                                <textarea name="descripcion_categoria_servicio_pt" class="form-control" rows="4"><?= $descripcion_categoria_servicio_pt ?></textarea>
                            </div>
                            <div class="form-group">
                              <label>Descrizione (IT)</label>
                              <textarea name="descripcion_categoria_servicio_it" class="form-control" rows="4"><?= $categoria[0]["descripcion_categoria_servicio_it"] ?? '' ?></textarea>
                            </div>


                          <!-- Descrição Curta da Categoria -->
                            <div class="form-group">
                                <label>Descripción Corta (ES)</label>
                                <textarea name="descripcionCorta_categoria_servicio_es" class="form-control" rows="4"><?= $descripcionCorta_categoria_servicio_es ?></textarea>
                            </div>
                            <div class="form-group">
                                <label>Descripción Corta (EN)</label>
                                <textarea name="descripcionCorta_categoria_servicio_en" class="form-control" rows="4"><?= $descripcionCorta_categoria_servicio_en ?></textarea>
                            </div>
                            <div class="form-group">
                                <label>Descripción Corta (PT)</label>
                                <textarea name="descripcionCorta_categoria_servicio_pt" class="form-control" rows="4"><?= $descripcionCorta_categoria_servicio_pt ?></textarea>
                            </div>
                            <div class="form-group">
                              <label>Descrizione Corta (IT)</label>
                              <textarea name="descripcionCorta_categoria_servicio_it" class="form-control" rows="4"><?= $categoria[0]["descripcionCorta_categoria_servicio_it"] ?? '' ?></textarea>
                            </div>

                            <!-- Número de Viajeros -->
                            <div class="form-group">
                                <label>Cantidad de viajeros</label>
                                <input type="number" name="nViajeros" class="form-control" value="<?= $nViajeros ?>" step="1">
                            </div>

                          <div class="form-group">
                              <label>Imagem da Categoria</label>
                              <input type="file" name="img_categoria" class="form-control" accept="image/*">
                              <?php if (!empty($categoria[0]["img_categoria_servicio"])) { ?>
                                  <img src="img/categoria_servicio/<?= $categoria[0]["img_categoria_servicio"] ?>" alt="Imagem da Categoria" style="max-width: 300px; margin-top: 10px;">
                              <?php } ?>
                          </div>

                            <button type="submit" name="editarCategoria" value="<?= $idCategoria ?>" class="btn btn-info">Salvar</button>
                        </form>
                    </div>
                </div>
            </div>
          <div class="col-md-6">
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Guia</h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <table class="table">
                  <thead>
                  <tr>
                    <th>Idioma</th>
                    <th>Nome do arquivo</th>
                    <th>Ações</th>
                  </tr>
                  </thead>
                  <tbody>
                  <!-- Guia em Espanhol (ES) -->
                  <tr>
                    <td>Espanhol (ES)</td>
                    <td><?= $categoria[0]["guia"] ?></td>
                    <td>
                      <?php if (!empty($categoria[0]["guia"])) { ?>
                        <a href="classes/guias/<?= $categoria[0]["guia"] ?>" target="_blank" class="btn btn-sm btn-primary">
                          <i class="fas fa-eye"></i> Ver
                        </a>
                      <?php } else { ?>
                        <span class="text-muted">Nenhum arquivo</span>
                      <?php } ?>
                    </td>
                  </tr>
                  <!-- Guia em Inglês (EN) -->
                  <tr>
                    <td>Inglês (EN)</td>
                    <td><?= $categoria[0]["guia_en"] ?></td>
                    <td>
                      <?php if (!empty($categoria[0]["guia_en"])) { ?>
                        <a href="classes/guias/<?= $categoria[0]["guia_en"] ?>" target="_blank" class="btn btn-sm btn-primary">
                          <i class="fas fa-eye"></i> Ver
                        </a>
                      <?php } else { ?>
                        <span class="text-muted">Nenhum arquivo</span>
                      <?php } ?>
                    </td>
                  </tr>
                  <!-- Guia em Português (PT) -->
                  <tr>
                    <td>Português (PT)</td>
                    <td><?= $categoria[0]["guia_pt"] ?></td>
                    <td>
                      <?php if (!empty($categoria[0]["guia_pt"])) { ?>
                        <a href="classes/guias/<?= $categoria[0]["guia_pt"] ?>" target="_blank" class="btn btn-sm btn-primary">
                          <i class="fas fa-eye"></i> Ver
                        </a>
                      <?php } else { ?>
                        <span class="text-muted">Nenhum arquivo</span>
                      <?php } ?>
                    </td>
                  </tr>
                  <tr>
                    <td>Português (IT)</td>
                    <td><?= $categoria[0]["guia_it"] ?></td>
                    <td>
                      <?php if (!empty($categoria[0]["guia_it"])) { ?>
                        <a href="classes/guias/<?= $categoria[0]["guia_it"] ?>" target="_blank" class="btn btn-sm btn-primary">
                          <i class="fas fa-eye"></i> Ver
                        </a>
                      <?php } else { ?>
                        <span class="text-muted">Nenhum arquivo</span>
                      <?php } ?>
                    </td>
                  </tr>
                  </tbody>
                </table>

                <!-- Formulário para upload de novas guias -->
                <form method="post" enctype="multipart/form-data">
                  <div class="form-group">
                    <label>Guia em Espanhol (ES)</label>
                    <input type="file" name="guia_es" class="form-control" accept="application/pdf">
                  </div>
                  <div class="form-group">
                    <label>Guia em Inglês (EN)</label>
                    <input type="file" name="guia_en" class="form-control" accept="application/pdf">
                  </div>
                  <div class="form-group">
                    <label>Guia em Português (PT)</label>
                    <input type="file" name="guia_pt" class="form-control" accept="application/pdf">
                  </div>
                  <div class="form-group">
                    <label>Guida in Italiano (IT)</label>
                    <input type="file" name="guia_it" class="form-control" accept="application/pdf">
                  </div>
                  <button type="submit" name="editarGuia" class="btn btn-success" value="<?= $idCategoria ?>">
                    <i class="fas fa-upload"></i> Salvar novas guias
                  </button>
                </form>
              </div>
            </div>
          </div>

<?php include("includes/footer.php"); ?>
