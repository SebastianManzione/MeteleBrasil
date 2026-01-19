<?php
// Verificar permisos
require_once(__DIR__ . "/classes/permisos.php");
require_once(__DIR__ . "/includes/permisos_helper.php");
$permisos = new PermisosManager($GLOBALS['pdo'], $_SESSION['login'] ?? []);
// Mantener la clave de permiso existente para evitar romper ACL
$permisos->verificarAcceso('viajeClasesLista');

include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");

require_once("classes/transporte.php");

$viajes = getAllViajes();
$claseServicioDisponibles = [];

$stmt = $pdo->query("SELECT * FROM clase_servicio_transporte WHERE habilitado = 1 ORDER BY idTipoTransporte, orden");
$claseServicioDisponibles = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener viaje preseleccionado si viene en GET
$viajePreseleccionado = $_GET['idViaje'] ?? null;
?>

<style>
    .clase-card {
        border-left: 4px solid #007bff;
        margin-bottom: 15px;
        padding: 15px;
        background-color: #f8f9fa;
        border-radius: 4px;
    }
    .clase-card.disabled {
        opacity: 0.6;
        border-left-color: #ccc;
    }
    .tarifa-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px;
        border-bottom: 1px solid #ddd;
    }
    .btn-edit-inline {
        padding: 2px 8px;
        font-size: 0.85rem;
    }
    .precio-input {
        max-width: 150px;
    }
</style>

<!-- Content Wrapper -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark"><i class="fas fa-chair"></i> Tipos de Butaca por Viaje</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="viajesTransporteLista.php">Viajes</a></li>
                        <li class="breadcrumb-item active">Tipos de Butaca</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="fas fa-chair"></i> Gestión de Tipos de Butaca por Viaje</h4>
                    </div>
                    <div class="card-body">
                        
                        <!-- Selector de Viaje -->
                        <div class="form-group">
                            <label for="selectViaje"><strong>Selecciona un viaje:</strong></label>
                            <select id="selectViaje" class="form-control" style="max-width: 400px;">
                                <option value="">-- Selecciona un viaje --</option>
                                <?php foreach ($viajes as $v) {
                                    $fecha = date('d/m/Y', strtotime($v['fecha']));
                                    $selected = ($viajePreseleccionado && $viajePreseleccionado == $v['idViaje']) ? 'selected' : '';
                                    echo "<option value='{$v['idViaje']}' $selected>Viaje #{$v['idViaje']} - {$v['ruta']} ({$fecha} {$v['hora_salida']})</option>";
                                } ?>
                            </select>
                        </div>

                        <!-- Contenedor de Clases -->
                        <div id="clasesContainer" style="display:none;">
                            <hr>
                            <div class="row">
                                <div class="col-lg-8">
                                    <h5>Tipos de Butaca Configurados</h5>
                                    <div id="listaClases"></div>
                                </div>
                                <div class="col-lg-4">
                                    <h5>Agregar Tipo de Butaca</h5>
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label>Selecciona tipo de butaca:</label>
                                                <select id="selectClase" class="form-control" style="font-size: 0.9rem;">
                                                    <option value="">-- Tipos disponibles --</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Asientos:</label>
                                                <input type="number" id="newAsientos" class="form-control" value="20" min="1">
                                            </div>
                                            <div class="form-group">
                                                <label>Precio Base (ARS):</label>
                                                <input type="number" id="newPrecio" class="form-control" value="15000" step="100">
                                            </div>
                                            <button class="btn btn-success btn-block" onclick="agregarClase()">
                                                <i class="fas fa-plus"></i> Agregar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>
</div>

<!-- Modal para editar tarifas -->
<div class="modal fade" id="modalTarifas" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Tarifas por Tipo de Pasajero - <span id="modalClaseNombre"></span></h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div id="tarifasContainer"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="guardarTarifas()">Guardar Cambios</button>
            </div>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>

<script>
let viajeActual = null;
let clasesCache = {};

$(document).ready(function() {
    $('#selectViaje').change(function() {
        viajeActual = $(this).val();
        if (viajeActual) {
            cargarClases(viajeActual);
            cargarClasesDisponibles(viajeActual);
            $('#clasesContainer').show();
        } else {
            $('#clasesContainer').hide();
        }
    });
    
    // Si hay viaje preseleccionado en URL, cargarlo
    let viajePresel = $('#selectViaje').val();
    if (viajePresel) {
        $('#selectViaje').change();
    }
});

function cargarClases(idViaje) {
    $.ajax({
        url: 'ctrl/ctrlViajeClases.php?action=getClases&idViaje=' + idViaje,
        success: function(response) {
            if (response.success) {
                clasesCache = {};
                let html = '';
                
                response.data.forEach(function(clase) {
                    clasesCache[clase.idViajeClase] = clase;
                    
                    let porciento = clase.asientos_totales > 0 
                        ? Math.round((clase.asientos_disponibles / clase.asientos_totales) * 100)
                        : 0;
                    
                    let colorDisp = porciento <= 20 ? 'danger' : (porciento <= 50 ? 'warning' : 'success');
                    
                    html += `<div class="clase-card ${clase.habilitado === 0 ? 'disabled' : ''}">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h6 class="mb-1">${clase.nombre_clase}</h6>
                                <small class="text-muted">${clase.descripcion_clase}</small>
                                <p class="mb-0 mt-2">
                                    <strong>Precio Base:</strong> ${clase.moneda_simbolo} ${Number(clase.precio_base).toLocaleString('es-AR')}
                                </p>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <small class="text-muted d-block">Disponibilidad</small>
                                    <span class="badge badge-${colorDisp} badge-lg">
                                        ${clase.asientos_disponibles}/${clase.asientos_totales}
                                    </span>
                                    <div class="progress mt-2" style="height: 5px;">
                                        <div class="progress-bar bg-${colorDisp}" style="width: ${porciento}%"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 text-right">
                                <button class="btn btn-sm btn-info" onclick="editarTarifas(${clase.idViajeClase}, '${clase.nombre_clase}')">
                                    <i class="fas fa-dollar-sign"></i> Tarifas
                                </button>
                                <button class="btn btn-sm btn-warning" onclick="editarClase(${clase.idViajeClase})">
                                    <i class="fas fa-edit"></i> Editar
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="eliminarClase(${clase.idViajeClase})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>`;
                });
                
                $('#listaClases').html(html);
            } else {
                Swal.fire('Error', response.error, 'error');
            }
        }
    });
}

function cargarClasesDisponibles(idViaje) {
    $.ajax({
        url: 'ctrl/ctrlViajeClases.php?action=getClasesDisponibles&idViaje=' + idViaje,
        success: function(response) {
            if (response.success) {
                let options = '<option value="">-- Clases disponibles --</option>';
                response.data.forEach(function(clase) {
                    options += `<option value="${clase.idClaseServicio}">${clase.nombre}</option>`;
                });
                $('#selectClase').html(options);
            }
        }
    });
}

function agregarClase() {
    let idClase = $('#selectClase').val();
    let asientos = $('#newAsientos').val();
    let precio = $('#newPrecio').val();
    
    if (!idClase) {
        Swal.fire('Error', 'Selecciona una clase', 'warning');
        return;
    }
    
    $.ajax({
        url: 'ctrl/ctrlViajeClases.php?action=addClase',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
            idViaje: viajeActual,
            idClaseServicio: idClase,
            asientos_totales: asientos,
            asientos_disponibles: asientos,
            precio_base: precio,
            idMoneda: 270
        }),
        success: function(response) {
            if (response.success) {
                Swal.fire('Éxito', 'Clase agregada correctamente', 'success');
                cargarClases(viajeActual);
                cargarClasesDisponibles(viajeActual);
            }
        }
    });
}

function editarClase(idViajeClase) {
    let clase = clasesCache[idViajeClase];
    
    Swal.fire({
        title: 'Editar Clase: ' + clase.nombre_clase,
        html: `
            <div class="form-group text-left">
                <label>Asientos Totales:</label>
                <input type="number" id="editAsientosTot" class="form-control" value="${clase.asientos_totales}" min="1">
            </div>
            <div class="form-group text-left">
                <label>Asientos Disponibles:</label>
                <input type="number" id="editAsientosDisp" class="form-control" value="${clase.asientos_disponibles}" min="0">
            </div>
            <div class="form-group text-left">
                <label>Precio Base:</label>
                <input type="number" id="editPrecioBase" class="form-control" value="${clase.precio_base}" step="100">
            </div>
        `,
        confirmButtonText: 'Guardar',
        showCancelButton: true,
        cancelButtonText: 'Cancelar'
    }).then(function(res) {
        if (res.isConfirmed) {
            let datos = {
                idViajeClase: idViajeClase,
                asientos_totales: $('#editAsientosTot').val(),
                asientos_disponibles: $('#editAsientosDisp').val(),
                precio_base: $('#editPrecioBase').val()
            };
            $.ajax({
                url: 'ctrl/ctrlViajeClases.php?action=updateClase',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(datos),
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Guardado', 'Clase actualizada', 'success');
                        cargarClases(viajeActual);
                    } else {
                        Swal.fire('Error', response.error || 'No se pudo actualizar', 'error');
                    }
                }
            });
        }
    });
}

function eliminarClase(idViajeClase) {
    Swal.fire({
        title: 'Eliminar Tipo de Butaca',
        text: 'Esta acción no se puede deshacer. ¿Deseas continuar?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Eliminar',
        cancelButtonText: 'Cancelar'
    }).then(function(res) {
        if (res.isConfirmed) {
            $.ajax({
                url: 'ctrl/ctrlViajeClases.php?action=deleteClase',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({ idViajeClase }),
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Eliminado', 'Tipo de butaca eliminado', 'success');
                        cargarClases(viajeActual);
                    }
                }
            });
        }
    });
}

function editarTarifas(idViajeClase, nombreClase) {
    $('#modalClaseNombre').text(nombreClase);
    $('#modalTarifas').modal('show');
    
    $.ajax({
        url: 'ctrl/ctrlTarifasViaje.php?action=getClasesTarifas&idViajeClase=' + idViajeClase,
        success: function(response) {
            if (response.success) {
                let html = '';
                response.data.forEach(function(tarifa) {
                    html += `
                        <div class="tarifa-row">
                            <div>
                                <strong>${tarifa.nombre_tipo_tarifa}</strong>
                                <small class="text-muted d-block">${tarifa.moneda_usuario} ${Number(tarifa.precio_convertido).toLocaleString('es-AR')}</small>
                            </div>
                            <div>
                                <input type="number" class="form-control precio-input" value="${tarifa.precio}" step="100" data-id-tarifa="${tarifa.idViajeClaseTarifa}">
                            </div>
                        </div>
                    `;
                });
                $('#tarifasContainer').html(html);
            }
        }
    });
}

function guardarTarifas() {
    let cambios = [];
    $('#tarifasContainer .precio-input').each(function() {
        cambios.push({
            idViajeClaseTarifa: $(this).data('id-tarifa'),
            precio: $(this).val()
        });
    });
    
    $.ajax({
        url: 'ctrl/ctrlTarifasViaje.php?action=updateClasesTarifas',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({ cambios }),
        success: function(response) {
            if (response.success) {
                Swal.fire('Guardado', 'Tarifas actualizadas', 'success');
                $('#modalTarifas').modal('hide');
            }
        }
    });
}
</script>