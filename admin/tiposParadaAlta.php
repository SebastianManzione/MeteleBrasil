<?php
// Verificar permisos de acceso
require_once(__DIR__ . "/classes/permisos.php");
require_once(__DIR__ . "/includes/permisos_helper.php");
$permisos = new PermisosManager($GLOBALS['pdo'], $_SESSION['login'] ?? []);
$permisos->verificarAcceso('tiposParadaAlta');

include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");

require_once("classes/transporte.php");

// Obtener ID si es edición
$idTipoPrada = isset($_GET['id']) ? intval($_GET['id']) : 0;
$tipo = null;

if ($idTipoPrada > 0) {
    $stmt = $GLOBALS['pdo']->prepare("SELECT * FROM tipo_parada WHERE idTipoPrada = :id");
    $stmt->execute(['id' => $idTipoPrada]);
    $tipo = $stmt->fetch(PDO::FETCH_ASSOC);
}

$modo = $tipo ? 'edicion' : 'alta';
$titulo = $modo === 'edicion' ? 'Editar Tipo' : 'Nuevo Tipo de Parada';
?>

<style>
    .icon-preview {
        font-size: 32px;
        margin: 10px 0;
    }
    .color-preview {
        width: 50px;
        height: 50px;
        border: 2px solid #ddd;
        border-radius: 4px;
    }
</style>

<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">
                        <i class="fas fa-tag"></i> <?= htmlspecialchars($titulo) ?>
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Transporte</a></li>
                        <li class="breadcrumb-item"><a href="tiposParadaLista.php">Tipos</a></li>
                        <li class="breadcrumb-item active"><?= htmlspecialchars($modo === 'edicion' ? 'Editar' : 'Nuevo') ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Datos del Tipo</h5>
                        </div>
                        <form method="POST" action="ctrl/ctrlTiposParada.php" id="formTipo">
                            <input type="hidden" name="action" value="<?= $modo === 'edicion' ? 'update' : 'insert' ?>">
                            <?php if ($modo === 'edicion'): ?>
                                <input type="hidden" name="idTipoPrada" value="<?= htmlspecialchars($tipo['idTipoPrada']) ?>">
                            <?php endif; ?>

                            <div class="card-body">
                                <!-- Nombre -->
                                <div class="form-group">
                                    <label for="nombre"><i class="fas fa-heading"></i> Nombre <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" required 
                                           placeholder="Ej: terminal, hotel, estación"
                                           value="<?= $tipo ? htmlspecialchars($tipo['nombre']) : '' ?>">
                                    <small class="form-text text-muted">Nombre descriptivo del tipo de parada</small>
                                </div>

                                <!-- Icono Font Awesome -->
                                <div class="form-group">
                                    <label for="icono"><i class="fas fa-icons"></i> Icono <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="icono" name="icono" required 
                                               placeholder="Ej: fa-map-marker-alt"
                                               value="<?= $tipo ? htmlspecialchars($tipo['icono']) : 'fa-map-marker-alt' ?>">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="button" onclick="abrirSelectorIconos()">
                                                <i class="fas fa-search"></i> Selector
                                            </button>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">
                                        Clase de Font Awesome 
                                        <a href="https://fontawesome.com/icons" target="_blank">Ver iconos</a>
                                    </small>
                                    <div class="mt-2">
                                        <label>Vista previa:</label>
                                        <i class="fas icon-preview" id="iconPreview" style="color: <?= $tipo ? $tipo['color'] : '#6c757d' ?>"></i>
                                    </div>
                                </div>

                                <!-- Color -->
                                <div class="form-group">
                                    <label for="color"><i class="fas fa-palette"></i> Color <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="color" class="form-control" id="color" name="color" required 
                                               style="max-width: 100px;"
                                               value="<?= $tipo ? htmlspecialchars($tipo['color']) : '#6c757d' ?>">
                                        <input type="text" class="form-control ml-2" id="colorText" 
                                               readonly value="<?= $tipo ? htmlspecialchars($tipo['color']) : '#6c757d' ?>">
                                    </div>
                                    <small class="form-text text-muted">Código hexadecimal para el badge</small>
                                    <div class="mt-2">
                                        <label>Vista previa:</label>
                                        <div class="color-preview" id="colorPreview" style="background-color: <?= $tipo ? $tipo['color'] : '#6c757d' ?>"></div>
                                    </div>
                                </div>

                                <!-- Estado -->
                                <div class="form-group">
                                    <label>
                                        <input type="checkbox" name="habilitado" value="1" 
                                               <?= (!$tipo || $tipo['habilitado']) ? 'checked' : '' ?>>
                                        <i class="fas fa-toggle-on"></i> Habilitado
                                    </label>
                                    <small class="form-text text-muted">Marcar para que este tipo esté disponible</small>
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check-circle"></i> <?= $modo === 'edicion' ? 'Actualizar' : 'Crear Tipo' ?>
                                </button>
                                <a href="tiposParadaLista.php" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancelar
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Sidebar: Colores predefinidos -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">Colores Sugeridos</h5>
                        </div>
                        <div class="card-body">
                            <div class="list-group">
                                <button type="button" class="list-group-item list-group-item-action" 
                                        onclick="seleccionarColor('#007bff', 'fa-map-marker-alt')">
                                    <span style="background-color: #007bff; width: 20px; height: 20px; display: inline-block; border-radius: 3px;"></span>
                                    Terminal (Azul)
                                </button>
                                <button type="button" class="list-group-item list-group-item-action" 
                                        onclick="seleccionarColor('#ff6b6b', 'fa-hotel')">
                                    <span style="background-color: #ff6b6b; width: 20px; height: 20px; display: inline-block; border-radius: 3px;"></span>
                                    Hotel (Rojo)
                                </button>
                                <button type="button" class="list-group-item list-group-item-action" 
                                        onclick="seleccionarColor('#ffc107', 'fa-train')">
                                    <span style="background-color: #ffc107; width: 20px; height: 20px; display: inline-block; border-radius: 3px;"></span>
                                    Estación (Amarillo)
                                </button>
                                <button type="button" class="list-group-item list-group-item-action" 
                                        onclick="seleccionarColor('#17a2b8', 'fa-anchor')">
                                    <span style="background-color: #17a2b8; width: 20px; height: 20px; display: inline-block; border-radius: 3px;"></span>
                                    Puerto (Cian)
                                </button>
                                <button type="button" class="list-group-item list-group-item-action" 
                                        onclick="seleccionarColor('#28a745', 'fa-plane')">
                                    <span style="background-color: #28a745; width: 20px; height: 20px; display: inline-block; border-radius: 3px;"></span>
                                    Aeropuerto (Verde)
                                </button>
                                <button type="button" class="list-group-item list-group-item-action" 
                                        onclick="seleccionarColor('#6c757d', 'fa-home')">
                                    <span style="background-color: #6c757d; width: 20px; height: 20px; display: inline-block; border-radius: 3px;"></span>
                                    Casa (Gris)
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Iconos populares -->
                    <div class="card mt-3">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">Iconos Populares</h5>
                        </div>
                        <div class="card-body">
                            <div class="btn-group-vertical btn-block" role="group">
                                <button type="button" class="btn btn-outline-secondary text-left" 
                                        onclick="seleccionarIcono('fa-map-marker-alt')">
                                    <i class="fas fa-map-marker-alt"></i> fa-map-marker-alt
                                </button>
                                <button type="button" class="btn btn-outline-secondary text-left" 
                                        onclick="seleccionarIcono('fa-hotel')">
                                    <i class="fas fa-hotel"></i> fa-hotel
                                </button>
                                <button type="button" class="btn btn-outline-secondary text-left" 
                                        onclick="seleccionarIcono('fa-train')">
                                    <i class="fas fa-train"></i> fa-train
                                </button>
                                <button type="button" class="btn btn-outline-secondary text-left" 
                                        onclick="seleccionarIcono('fa-anchor')">
                                    <i class="fas fa-anchor"></i> fa-anchor
                                </button>
                                <button type="button" class="btn btn-outline-secondary text-left" 
                                        onclick="seleccionarIcono('fa-plane')">
                                    <i class="fas fa-plane"></i> fa-plane
                                </button>
                                <button type="button" class="btn btn-outline-secondary text-left" 
                                        onclick="seleccionarIcono('fa-home')">
                                    <i class="fas fa-home"></i> fa-home
                                </button>
                                <button type="button" class="btn btn-outline-secondary text-left" 
                                        onclick="seleccionarIcono('fa-building')">
                                    <i class="fas fa-building"></i> fa-building
                                </button>
                                <button type="button" class="btn btn-outline-secondary text-left" 
                                        onclick="seleccionarIcono('fa-compass')">
                                    <i class="fas fa-compass"></i> fa-compass
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
function seleccionarIcono(icono) {
    $('#icono').val(icono);
    updateIconPreview();
}

function seleccionarColor(color, icono) {
    $('#color').val(color);
    $('#colorText').val(color);
    $('#colorPreview').css('background-color', color);
    $('#iconPreview').css('color', color);
    if (icono) seleccionarIcono(icono);
}

function updateIconPreview() {
    var icono = $('#icono').val();
    var color = $('#color').val();
    $('#iconPreview').attr('class', 'fas icon-preview ' + icono);
    $('#iconPreview').css('color', color);
}

$('#icono').on('change', updateIconPreview);
$('#color').on('change', function() {
    var color = $(this).val();
    $('#colorText').val(color);
    $('#colorPreview').css('background-color', color);
    $('#iconPreview').css('color', color);
});

$(document).ready(function() {
    updateIconPreview();
});
</script>

<?php include("includes/footer.php"); ?>
