<?php
// Verificar permisos de acceso ANTES de cualquier salida
require_once(__DIR__ . "/classes/permisos.php");
require_once(__DIR__ . "/includes/permisos_helper.php");
$permisos = new PermisosManager($GLOBALS['pdo'], $_SESSION['login'] ?? []);
$permisos->verificarAcceso('viajeTransporteAdicionales');

include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");

require_once("classes/transporte.php");
require_once("classes/servicios_adicionales.php");
require_once("classes/moneda.php");

// Obtener ID del viaje desde parámetro
$idViaje = $_GET['id'] ?? null;

if (!$idViaje) {
    header("Location: viajesTransporteLista.php");
    exit();
}

// Obtener datos del viaje
$viaje = getViaje($idViaje);
if (!$viaje) {
    header("Location: viajesTransporteLista.php");
    exit();
}

// Obtener ruta para mostrar información
$ruta = getRuta($viaje['idRuta']);

// Obtener servicios adicionales asignados
$serviciosAsignados = getServiciosAdicionalesViaje($idViaje);

// Obtener servicios disponibles
$serviciosDisponibles = getServiciosAdicionalesDisponiblesParaViaje($idViaje);

// Obtener lista de monedas usando función existente
$monedas = getMonedas();
?>

<!-- Content Wrapper -->
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">
                        <i class="fas fa-gift"></i>
                        Servicios Adicionales del Viaje
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="viajesTransporteLista.php">Viajes</a></li>
                        <li class="breadcrumb-item active">Servicios Adicionales</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Info del viaje -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="alert alert-info">
                        <strong>Viaje:</strong> <?= htmlspecialchars($ruta['nombre']) ?> 
                        | <strong>Fecha:</strong> <?= date('d/m/Y H:i', strtotime(($viaje['fecha'] ?? '') . ' ' . ($viaje['hora_salida'] ?? '00:00'))) ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Servicios asignados -->
                <div class="col-lg-6">
                    <div class="card shadow">
                        <div class="card-header bg-primary">
                            <h3 class="card-title">
                                <i class="fas fa-check-circle"></i>
                                Servicios Asignados (<?= count($serviciosAsignados) ?>)
                            </h3>
                        </div>
                        <div class="card-body">
                            <?php if (count($serviciosAsignados) > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Servicio</th>
                                                <th>Precio</th>
                                                <th>Moneda</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($serviciosAsignados as $sa): ?>
                                                <tr id="row-<?= $sa['idViajeAdicional'] ?>">
                                                    <td>
                                                        <strong><?= htmlspecialchars($sa['nombre']) ?></strong>
                                                        <br>
                                                        <small class="text-muted">ID: <?= $sa['idServiciosAdicionales'] ?></small>
                                                    </td>
                                                    <td>
                                                        <span id="precio-<?= $sa['idViajeAdicional'] ?>">
                                                            <?= number_format($sa['precio'], 2) ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span id="moneda-<?= $sa['idViajeAdicional'] ?>">
                                                            <?= $sa['moneda_simbolo'] ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-sm btn-warning" onclick="editarServicio(<?= $sa['idViajeAdicional'] ?>, '<?= htmlspecialchars($sa['nombre']) ?>', <?= $sa['precio'] ?>, <?= $sa['idMoneda'] ?>)">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-danger" onclick="eliminarServicio(<?= $sa['idViajeAdicional'] ?>)">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    No hay servicios adicionales asignados
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Agregar nuevos servicios -->
                <div class="col-lg-6">
                    <div class="card shadow">
                        <div class="card-header bg-success">
                            <h3 class="card-title">
                                <i class="fas fa-plus-circle"></i>
                                Agregar Servicio
                            </h3>
                        </div>
                        <div class="card-body">
                            <?php if (count($serviciosDisponibles) > 0): ?>
                                <form id="formAgregarServicio">
                                    <input type="hidden" name="idViaje" value="<?= $idViaje ?>">
                                    
                                    <div class="form-group">
                                        <label for="selectServicio">Servicio *</label>
                                        <select class="form-control" id="selectServicio" name="idServiciosAdicionales" required>
                                            <option value="">-- Seleccionar servicio --</option>
                                            <?php foreach ($serviciosDisponibles as $s): ?>
                                                <option value="<?= $s['idServiciosAdicionales'] ?>">
                                                    <?= htmlspecialchars($s['nombre']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="precio">Precio *</label>
                                                <input type="number" class="form-control" id="precio" name="precio" step="0.01" min="0" required placeholder="0.00">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="idMoneda">Moneda *</label>
                                                <select class="form-control" id="idMoneda" name="idMoneda" required>
                                                    <option value="">-- Seleccionar moneda --</option>
                                                    <?php foreach ($monedas as $m): ?>
                                                        <option value="<?= $m['idMoneda'] ?>">
                                                            <?= htmlspecialchars($m['CurrencyName'] ?? '') ?> (<?= htmlspecialchars($m['Symbol'] ?? '') ?>)
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-success btn-block">
                                        <i class="fas fa-save"></i>
                                        Agregar Servicio
                                    </button>
                                </form>
                            <?php else: ?>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    Todos los servicios disponibles ya están asignados.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal para editar -->
            <div class="modal fade" id="modalEditarServicio" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Editar Servicio Adicional</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="formEditarServicio">
                                <input type="hidden" id="editIdViajeAdicional" name="idViajeAdicional">
                                
                                <div class="form-group">
                                    <label>Servicio</label>
                                    <input type="text" class="form-control" id="editNombreServicio" readonly>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="editPrecio">Precio *</label>
                                            <input type="number" class="form-control" id="editPrecio" name="precio" step="0.01" min="0" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="editIdMoneda">Moneda *</label>
                                            <select class="form-control" id="editIdMoneda" name="idMoneda" required>
                                                <?php foreach ($monedas as $m): ?>
                                                    <option value="<?= $m['idMoneda'] ?>">
                                                        <?= htmlspecialchars($m['CurrencyName'] ?? '') ?> (<?= htmlspecialchars($m['Symbol'] ?? '') ?>)
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-primary" onclick="guardarEdicion()">Guardar Cambios</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
// Agregar nuevo servicio
document.getElementById('formAgregarServicio').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);
    
    $.ajax({
        url: 'ctrl/ctrlViajeTransporteAdicionales.php',
        method: 'POST',
        data: {
            accion: 'agregar',
            idViaje: data.idViaje,
            idServiciosAdicionales: data.idServiciosAdicionales,
            precio: data.precio,
            idMoneda: data.idMoneda
        },
        success: function(response) {
            const result = JSON.parse(response);
            if (result.success) {
                Swal.fire('Éxito', 'Servicio agregado correctamente', 'success').then(() => {
                    location.reload();
                });
            } else {
                Swal.fire('Error', result.message || 'Error al agregar servicio', 'error');
            }
        },
        error: function() {
            Swal.fire('Error', 'Error en la conexión', 'error');
        }
    });
});

// Editar servicio
function editarServicio(idViajeAdicional, nombre, precio, idMoneda) {
    document.getElementById('editIdViajeAdicional').value = idViajeAdicional;
    document.getElementById('editNombreServicio').value = nombre;
    document.getElementById('editPrecio').value = precio;
    document.getElementById('editIdMoneda').value = idMoneda;
    
    $('#modalEditarServicio').modal('show');
}

// Guardar edición
function guardarEdicion() {
    const formData = {
        accion: 'actualizar',
        idViajeAdicional: document.getElementById('editIdViajeAdicional').value,
        precio: document.getElementById('editPrecio').value,
        idMoneda: document.getElementById('editIdMoneda').value
    };
    
    $.ajax({
        url: 'ctrl/ctrlViajeTransporteAdicionales.php',
        method: 'POST',
        data: formData,
        success: function(response) {
            const result = JSON.parse(response);
            if (result.success) {
                $('#modalEditarServicio').modal('hide');
                Swal.fire('Éxito', 'Cambios guardados', 'success').then(() => {
                    location.reload();
                });
            } else {
                Swal.fire('Error', result.message || 'Error al guardar cambios', 'error');
            }
        },
        error: function() {
            Swal.fire('Error', 'Error en la conexión', 'error');
        }
    });
}

// Eliminar servicio
function eliminarServicio(idViajeAdicional) {
    Swal.fire({
        title: '¿Eliminar servicio?',
        text: 'Esta acción no se puede deshacer',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'ctrl/ctrlViajeTransporteAdicionales.php',
                method: 'POST',
                data: {
                    accion: 'eliminar',
                    idViajeAdicional: idViajeAdicional
                },
                success: function(response) {
                    const result = JSON.parse(response);
                    if (result.success) {
                        Swal.fire('Eliminado', 'Servicio eliminado correctamente', 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error', result.message || 'Error al eliminar', 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Error en la conexión', 'error');
                }
            });
        }
    });
}
</script>

<?php include("includes/footer.php"); ?>
