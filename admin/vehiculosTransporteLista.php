<?php
session_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$archivo_actual = basename($_SERVER['PHP_SELF']);

require_once "includes/header.php";
require_once "includes/navbar.php";
require_once "includes/sidebar.php";
require_once "classes/transporte.php";

$vehiculos = getAllVehiculos();
$modelos = getAllModelos();
// Mapa por idModelo para consultar distribucion_json y otros datos
$modelosMap = [];
foreach ($modelos as $m) { $modelosMap[$m['idModelo']] = $m; }
?>

<!-- Content Wrapper -->
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark"><i class="fas fa-bus"></i> Gestión de Vehículos</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Transporte</a></li>
                        <li class="breadcrumb-item active">Vehículos</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                            <h4 class="mb-0"><i class="fas fa-list"></i> Flota de Vehículos</h4>
                            <button class="btn btn-success btn-sm" onclick="mostrarFormularioNuevo()">
                                <i class="fas fa-plus"></i> Nuevo Vehículo
                            </button>
                        </div>
                        <div class="card-body">
                            <!-- Info: Edición en desarrollo -->
                            <div class="alert alert-info" role="alert">
                                <i class="fas fa-info-circle"></i>
                                Edición en desarrollo: la edición/eliminación de vehículos se está implementando. Para configurar el mapa de asientos, use el editor en <strong>Modelos de Vehículos</strong>.
                            </div>
                            <!-- Filtros -->
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label>Filtrar por modelo:</label>
                                    <select id="filtroModelo" class="form-control">
                                        <option value="">Todos</option>
                                        <?php foreach ($modelos as $modelo): ?>
                                            <option value="<?=$modelo['idModelo']?>"><?=$modelo['nombre']?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Filtrar por estado:</label>
                                    <select id="filtroEstado" class="form-control">
                                        <option value="">Todos</option>
                                        <option value="activo">Activo</option>
                                        <option value="mantenimiento">Mantenimiento</option>
                                        <option value="retirado">Retirado</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Búsqueda (Patente):</label>
                                    <input type="text" id="busqueda" class="form-control" placeholder="Patente...">
                                </div>
                                <div class="col-md-3">
                                    <label>&nbsp;</label>
                                    <button class="btn btn-secondary btn-sm btn-block" onclick="limpiarFiltros()">
                                        <i class="fas fa-eraser"></i> Limpiar
                                    </button>
                                </div>
                            </div>

                            <!-- Tabla de Vehículos -->
                            <div class="table-responsive">
                                <table id="tablaVehiculos" class="table table-bordered table-striped table-hover">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>ID</th>
                                            <th>Patente</th>
                                            <th>Modelo</th>
                                            <th>Capacidad</th>
                                            <th>Estado</th>
                                            <th>Info</th>
                                            <th>Fecha Alta</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($vehiculos as $vehiculo): ?>
                                            <tr id="fila-<?=$vehiculo['idVehiculo']?>">
                                                <td><?=$vehiculo['idVehiculo']?></td>
                                                <td>
                                                    <strong><?=$vehiculo['patente']?></strong>
                                                </td>
                                                <td>
                                                    <span class="badge badge-info"><?=$vehiculo['modelo_nombre']?></span>
                                                </td>
                                                <td>
                                                    <?=$vehiculo['capacidad_total']?> pasajeros
                                                </td>
                                                <td>
                                                    <?php 
                                                    $badge = 'secondary';
                                                    if ($vehiculo['estado'] === 'activo') $badge = 'success';
                                                    if ($vehiculo['estado'] === 'mantenimiento') $badge = 'warning';
                                                    if ($vehiculo['estado'] === 'retirado') $badge = 'danger';
                                                    ?>
                                                    <span class="badge badge-<?=$badge?>">
                                                        <?=ucfirst($vehiculo['estado'])?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php
                                                    $modeloId = $vehiculo['idModelo'] ?? null;
                                                    $distJson = $modeloId && isset($modelosMap[$modeloId]) ? ($modelosMap[$modeloId]['distribucion_json'] ?? '') : '';
                                                    $hasMapa = !empty($distJson) && trim($distJson) !== '{}' && trim($distJson) !== '[]';
                                                    ?>
                                                    <?php if ($hasMapa): ?>
                                                        <span class="badge badge-primary">Mapa OK</span>
                                                    <?php else: ?>
                                                        <span class="badge badge-secondary">Mapa pendiente</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?=date('d/m/Y', strtotime($vehiculo['fecha_alta']))?>
                                                </td>
                                                <td>
                                                    <button class="btn btn-xs btn-primary" onclick="editarVehiculo(<?=$vehiculo['idVehiculo']?>)" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-xs btn-info" onclick="verObservaciones(<?=$vehiculo['idVehiculo']?>)" title="Observaciones">
                                                        <i class="fas fa-sticky-note"></i>
                                                    </button>
                                                    <button class="btn btn-xs btn-danger" onclick="deleteVehiculo(<?=$vehiculo['idVehiculo']?>)" title="Eliminar">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- Modal: Nuevo/Editar Vehículo -->
<div class="modal fade" id="modalVehiculo" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="tituloModal">Nuevo Vehículo</h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formVehiculo">
                    <input type="hidden" id="idVehiculo" name="idVehiculo" value="">
                    
                    <div class="form-group">
                        <label>Modelo:</label>
                        <select id="idModelo" name="idModelo" class="form-control" required>
                            <option value="">Seleccionar...</option>
                            <?php foreach ($modelos as $modelo): ?>
                                <option value="<?=$modelo['idModelo']?>"><?=$modelo['nombre']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Patente:</label>
                        <input type="text" id="patente" name="patente" class="form-control" placeholder="Ej: AH 001 ER" required>
                    </div>

                    <div class="form-group">
                        <label>Estado:</label>
                        <select id="estado" name="estado" class="form-control">
                            <option value="activo">Activo</option>
                            <option value="mantenimiento">Mantenimiento</option>
                            <option value="retirado">Retirado</option>
                            <option value="inactivo">Inactivo</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Observaciones:</label>
                        <textarea id="observaciones" name="observaciones" class="form-control" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardarVehiculo()">
                    <i class="fas fa-save"></i> Guardar
                </button>
            </div>
        </div>
    </div>
</div>

<?php
require_once "includes/footer.php";
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>

function mostrarFormularioNuevo() {
    $('#idVehiculo').val('');
    $('#formVehiculo')[0].reset();
    $('#tituloModal').text('Nuevo Vehículo');
    $('#modalVehiculo').modal('show');
}

function editarVehiculo(idVehiculo) {
    Swal.fire('Info', 'Edición en desarrollo', 'info');
}

function guardarVehiculo() {
    const idVehiculo = $('#idVehiculo').val();
    const datos = {
        action: idVehiculo ? 'updateVehiculo' : 'insertVehiculo',
        idVehiculo: idVehiculo,
        idModelo: $('#idModelo').val(),
        patente: $('#patente').val().toUpperCase(),
        estado: $('#estado').val(),
        observaciones: $('#observaciones').val()
    };

    $.post('ctrl/ctrlModelosVehiculos.php', datos, function(response) {
        if (response.success) {
            Swal.fire('Éxito', response.message, 'success').then(() => {
                location.reload();
            });
        } else {
            Swal.fire('Error', response.error || 'Error desconocido', 'error');
        }
    }, 'json');
}

function verObservaciones(idVehiculo) {
    Swal.fire('Info', 'Función en desarrollo', 'info');
}

function deleteVehiculo(idVehiculo) {
    Swal.fire({
        title: 'Eliminar vehículo',
        text: '¿Estás seguro?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí',
        cancelButtonText: 'Cancelar'
    }).then(result => {
        if (result.isConfirmed) {
            Swal.fire('Info', 'Eliminación en desarrollo', 'info');
        }
    });
}

function limpiarFiltros() {
    $('#filtroModelo').val('');
    $('#filtroEstado').val('');
    $('#busqueda').val('');
    location.reload();
}

// Inicializar DataTable
$(document).ready(function() {
    $('#tablaVehiculos').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
        },
        "pageLength": 25
    });
});
</script>
