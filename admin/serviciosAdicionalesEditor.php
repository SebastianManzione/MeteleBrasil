<?php
// Verificar permisos de acceso ANTES de cualquier salida
require_once(__DIR__ . "/classes/permisos.php");
require_once(__DIR__ . "/includes/permisos_helper.php");
$permisos = new PermisosManager($GLOBALS['pdo'], $_SESSION['login'] ?? []);
$permisos->verificarAcceso('serviciosAdicionalesEditor');

include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");
require("classes/functions.php");
require("classes/servicios_adicionales.php");
require("classes/categoria.php");

// Verificar si es edición o creación
$modo = "crear";
$servicioAdicional = null;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["accion"])) {
    $accion = $_POST["accion"];
    
    if ($accion == "guardar") {
        $nombres = [
            "ES" => $_POST["nombre"] ?? "",
            "EN" => $_POST["nombre_en"] ?? "",
            "PT" => $_POST["nombre_pt"] ?? "",
            "IT" => $_POST["nombre_it"] ?? ""
        ];
        
        $descripciones = [
            "ES" => $_POST["descripcion"] ?? "",
            "EN" => $_POST["descripcion_en"] ?? "",
            "PT" => $_POST["descripcion_pt"] ?? "",
            "IT" => $_POST["descripcion_it"] ?? ""
        ];
        
        // Validación
        if (empty($nombres["ES"])) {
            alertar("El nombre en español es requerido", "danger");
        } else {
            if (isset($_POST["idServiciosAdicionales"]) && !empty($_POST["idServiciosAdicionales"])) {
                // Editar
                $resultado = editaServicioAdicional(
                    $_POST["idServiciosAdicionales"],
                    $nombres,
                    $descripciones
                );
                if ($resultado > 0) {
                    alertar("Servicio adicional actualizado con éxito", "success");
                    redireccionarLento("serviciosAdicionalesEditor.php");
                } else {
                    alertar("Error al actualizar el servicio adicional", "danger");
                }
            } else {
                // Crear
                $resultado = altaServicioAdicional($nombres, $descripciones);
                if ($resultado > 0) {
                    alertar("Servicio adicional creado con éxito", "success");
                    redireccionarLento("serviciosAdicionalesEditor.php");
                } else {
                    alertar("Error al crear el servicio adicional", "danger");
                }
            }
        }
    } elseif ($accion == "eliminar" && isset($_POST["idServiciosAdicionales"])) {
        $resultado = eliminaServicioAdicional($_POST["idServiciosAdicionales"]);
        if ($resultado > 0) {
            alertar("Servicio adicional eliminado con éxito", "success");
            redireccionarLento("serviciosAdicionalesEditor.php");
        } elseif ($resultado === -1) {
            alertar("No se puede eliminar: el servicio adicional está asignado a salidas/servicios", "warning");
        } else {
            alertar("Error al eliminar el servicio adicional", "danger");
        }
    }
}

// Verificar si se solicita editar
if (isset($_GET["editar"]) && is_numeric($_GET["editar"])) {
    $modo = "editar";
    $servicioAdicional = getServicioAdicionalParaEditar($_GET["editar"])[0];
}

// Obtener lista de servicios adicionales
$servicios = getServiciosAdicionales();
$categorias = getAllCategorias();
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">
                        <i class="fas fa-plus-circle"></i> 
                        <?= $modo == "crear" ? "Crear Servicio Adicional" : "Editar Servicio Adicional" ?>
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item active">Servicios Adicionales</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- FORMULARIO -->
                <div class="col-md-6">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">
                                <?= $modo == "crear" ? "Nuevo Servicio Adicional" : "Editar Servicio Adicional" ?>
                            </h3>
                        </div>
                        <form method="POST" id="formularioServicio">
                            <div class="card-body">
                                <input type="hidden" name="accion" value="guardar">
                                <?php if ($modo == "editar"): ?>
                                    <input type="hidden" name="idServiciosAdicionales" value="<?= $servicioAdicional['idServiciosAdicionales'] ?>">
                                <?php endif; ?>

                                <!-- PESTAÑA: IDIOMAS -->
                                <ul class="nav nav-tabs" id="idiomas-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="es-tab" data-toggle="tab" href="#es" role="tab">
                                            <span class="badge badge-danger">ES</span> Español
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="en-tab" data-toggle="tab" href="#en" role="tab">
                                            <span class="badge badge-info">EN</span> English
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="pt-tab" data-toggle="tab" href="#pt" role="tab">
                                            <span class="badge badge-success">PT</span> Português
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="it-tab" data-toggle="tab" href="#it" role="tab">
                                            <span class="badge badge-warning">IT</span> Italiano
                                        </a>
                                    </li>
                                </ul>

                                <div class="tab-content" id="idiomas-tabContent">
                                    <!-- ESPAÑOL -->
                                    <div class="tab-pane fade show active" id="es" role="tabpanel">
                                        <div class="form-group mt-3">
                                            <label for="nombre">Nombre <span class="text-danger">*</span></label>
                                            <input 
                                                type="text" 
                                                class="form-control" 
                                                id="nombre" 
                                                name="nombre" 
                                                placeholder="Ej: Transporte Hotel" 
                                                value="<?= $servicioAdicional['nombre'] ?? '' ?>"
                                                maxlength="100"
                                                required>
                                            <small class="form-text text-muted">Campo requerido</small>
                                        </div>
                                        <div class="form-group">
                                            <label for="descripcion">Descripción</label>
                                            <textarea 
                                                class="form-control" 
                                                id="descripcion" 
                                                name="descripcion" 
                                                rows="3"
                                                maxlength="500"
                                                placeholder="Describe este servicio adicional..."><?= $servicioAdicional['descripcion_servicio_adicional'] ?? '' ?></textarea>
                                            <small class="form-text text-muted">Máximo 500 caracteres</small>
                                        </div>
                                    </div>

                                    <!-- ENGLISH -->
                                    <div class="tab-pane fade" id="en" role="tabpanel">
                                        <div class="form-group mt-3">
                                            <label for="nombre_en">Nombre (English)</label>
                                            <input 
                                                type="text" 
                                                class="form-control" 
                                                id="nombre_en" 
                                                name="nombre_en" 
                                                placeholder="Ej: Hotel Transfer" 
                                                value="<?= $servicioAdicional['nombre_en'] ?? '' ?>"
                                                maxlength="100">
                                        </div>
                                        <div class="form-group">
                                            <label for="descripcion_en">Descripción (English)</label>
                                            <textarea 
                                                class="form-control" 
                                                id="descripcion_en" 
                                                name="descripcion_en" 
                                                rows="3"
                                                maxlength="500"
                                                placeholder="Describe this additional service..."><?= $servicioAdicional['descripcion_servicio_adicional_en'] ?? '' ?></textarea>
                                        </div>
                                    </div>

                                    <!-- PORTUGUÊS -->
                                    <div class="tab-pane fade" id="pt" role="tabpanel">
                                        <div class="form-group mt-3">
                                            <label for="nombre_pt">Nome (Português)</label>
                                            <input 
                                                type="text" 
                                                class="form-control" 
                                                id="nombre_pt" 
                                                name="nombre_pt" 
                                                placeholder="Ex: Transfer do Hotel" 
                                                value="<?= $servicioAdicional['nombre_pt'] ?? '' ?>"
                                                maxlength="100">
                                        </div>
                                        <div class="form-group">
                                            <label for="descripcion_pt">Descrição (Português)</label>
                                            <textarea 
                                                class="form-control" 
                                                id="descripcion_pt" 
                                                name="descripcion_pt" 
                                                rows="3"
                                                maxlength="500"
                                                placeholder="Descreva este serviço adicional..."><?= $servicioAdicional['descripcion_servicio_adicional_pt'] ?? '' ?></textarea>
                                        </div>
                                    </div>

                                    <!-- ITALIANO -->
                                    <div class="tab-pane fade" id="it" role="tabpanel">
                                        <div class="form-group mt-3">
                                            <label for="nombre_it">Nome (Italiano)</label>
                                            <input 
                                                type="text" 
                                                class="form-control" 
                                                id="nombre_it" 
                                                name="nombre_it" 
                                                placeholder="Es: Trasferimento da Hotel" 
                                                value="<?= $servicioAdicional['nombre_it'] ?? '' ?>"
                                                maxlength="100">
                                        </div>
                                        <div class="form-group">
                                            <label for="descripcion_it">Descrizione (Italiano)</label>
                                            <textarea 
                                                class="form-control" 
                                                id="descripcion_it" 
                                                name="descripcion_it" 
                                                rows="3"
                                                maxlength="500"
                                                placeholder="Descrivi questo servizio aggiuntivo..."><?= $servicioAdicional['descripcion_servicio_adicional_it'] ?? '' ?></textarea>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> 
                                    <?= $modo == "crear" ? "Crear Servicio" : "Guardar Cambios" ?>
                                </button>
                                <a href="serviciosAdicionalesEditor.php" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancelar
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- LISTA DE SERVICIOS -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-list"></i> Servicios Adicionales Registrados
                            </h3>
                        </div>
                        <div class="card-body">
                            <input 
                                type="text" 
                                class="form-control mb-3" 
                                id="buscar" 
                                placeholder="Buscar por nombre..."
                            >
                            <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                                <table class="table table-hover table-sm" id="tablaServicios">
                                    <thead class="bg-light sticky-top">
                                        <tr>
                                            <th width="40%">Nombre</th>
                                            <th width="20%">Idiomas</th>
                                            <th width="40%">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        foreach ($servicios as $sv) {
                                            $idiomas = [];
                                            if (!empty($sv['nombre_en'])) $idiomas[] = 'EN';
                                            if (!empty($sv['nombre_pt'])) $idiomas[] = 'PT';
                                            if (!empty($sv['nombre_it'])) $idiomas[] = 'IT';
                                            $conteo = count($idiomas);
                                        ?>
                                            <tr class="fila-servicio" data-nombre="<?= strtolower($sv['nombre']) ?>">
                                                <td>
                                                    <strong><?= substr($sv['nombre'], 0, 30) ?></strong>
                                                    <?php if (strlen($sv['nombre']) > 30): ?>
                                                        ...
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($conteo > 0): ?>
                                                        <span class="badge badge-info"><?= $conteo ?>/3</span>
                                                    <?php else: ?>
                                                        <span class="badge badge-warning">Solo ES</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <a 
                                                        href="serviciosAdicionalesEditor.php?editar=<?= $sv['idServiciosAdicionales'] ?>" 
                                                        class="btn btn-sm btn-info"
                                                        title="Editar"
                                                    >
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button 
                                                        type="button" 
                                                        class="btn btn-sm btn-danger"
                                                        onclick="confirmarEliminar(<?= $sv['idServiciosAdicionales'] ?>, '<?= addslashes($sv['nombre']) ?>')"
                                                        title="Eliminar"
                                                    >
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-muted text-center mt-3">
                                Total: <strong><?= count($servicios) ?></strong> servicios adicionales
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- MODAL CONFIRMAR ELIMINAR -->
<div class="modal fade" id="modalEliminar" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Eliminar Servicio Adicional</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro de que desea eliminar este servicio adicional?</p>
                <p><strong id="nombreServicio"></strong></p>
                <p class="text-muted small">Esta acción no se puede deshacer.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <form method="POST" style="display: inline;">
                    <input type="hidden" name="accion" value="eliminar">
                    <input type="hidden" name="idServiciosAdicionales" id="idAEliminar" value="">
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// BUSCAR EN TABLA
document.getElementById('buscar').addEventListener('keyup', function() {
    const termino = this.value.toLowerCase();
    document.querySelectorAll('.fila-servicio').forEach(fila => {
        const nombre = fila.dataset.nombre;
        fila.style.display = nombre.includes(termino) ? '' : 'none';
    });
});

// CONFIRMAR ELIMINACIÓN
function confirmarEliminar(id, nombre) {
    document.getElementById('nombreServicio').textContent = nombre;
    document.getElementById('idAEliminar').value = id;
    $('#modalEliminar').modal('show');
}

// VALIDACIÓN FORMULARIO
document.getElementById('formularioServicio').addEventListener('submit', function(e) {
    const nombre = document.getElementById('nombre').value.trim();
    if (!nombre) {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'El nombre en español es obligatorio'
        });
    }
});
</script>

<?php include("includes/footer.php"); ?>
