<?php
// Verificar permisos de acceso ANTES de cualquier salida
require_once(__DIR__ . "/classes/permisos.php");
require_once(__DIR__ . "/includes/permisos_helper.php");
$permisos = new PermisosManager($GLOBALS['pdo'], $_SESSION['login'] ?? []);
$permisos->verificarAcceso('viajesTransporteLista');

include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");

require_once("classes/transporte.php");

$viajes = getAllViajes();

$success = isset($_GET['success']) ? $_GET['success'] : '';
?>

<style>
    .badge-activo { background-color: #28a745; }
    .badge-cancelado { background-color: #dc3545; }
    .badge-completo { background-color: #ffc107; color: #000; }
    .disponibilidad-baja { color: #dc3545; font-weight: bold; }
    .disponibilidad-media { color: #ffc107; font-weight: bold; }
    .disponibilidad-alta { color: #28a745; font-weight: bold; }
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark"><i class="fas fa-calendar-alt"></i> Gestión de Viajes</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Transporte</a></li>
                        <li class="breadcrumb-item active">Viajes</li>
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
                        <h4 class="mb-0"><i class="fas fa-calendar-alt"></i> Lista de Viajes Programados</h4>
                        <a href="viajeTransporteAlta.php" class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> <span class="d-none d-sm-inline">Nuevo Viaje</span>
                        </a>
                    </div>
                    <div class="card-body">
                        <?php if ($success == '1') { ?>
                            <div class="alert alert-success alert-dismissible fade show">
                                <i class="fas fa-check-circle"></i> Viaje guardado correctamente
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                            </div>
                        <?php } elseif ($success == '2') { ?>
                            <div class="alert alert-success alert-dismissible fade show">
                                <i class="fas fa-trash-alt"></i> Viaje eliminado correctamente
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                            </div>
                        <?php } ?>
                        
                        <!-- Filtros -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label>Filtrar por Tipo:</label>
                                <select id="filtroTipo" class="form-control">
                                    <option value="">Todos</option>
                                    <option value="Bus">Bus</option>
                                    <option value="Avión">Avión</option>
                                    <option value="Tren">Tren</option>
                                    <option value="Barco">Barco</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Filtrar por Estado:</label>
                                <select id="filtroEstado" class="form-control">
                                    <option value="">Todos</option>
                                    <option value="activo">Activo</option>
                                    <option value="cancelado">Cancelado</option>
                                    <option value="completo">Completo</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Desde Fecha:</label>
                                <input type="date" id="filtroFecha" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label>&nbsp;</label>
                                <button id="btnLimpiarFiltros" class="btn btn-secondary btn-sm btn-block">
                                    <i class="fas fa-eraser"></i> Limpiar Filtros
                                </button>
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                            <table id="tablaViajes" class="table table-bordered table-striped table-hover">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Ruta</th>
                                        <th>Tipo</th>
                                        <th>Empresa</th>
                                        <th>Fecha Salida</th>
                                        <th>Hora</th>
                                        <th>Disponibilidad</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($viajes as $viaje): 
                                        $porcentaje = ($viaje['asientos_disponibles'] / $viaje['asientos_totales']) * 100;
                                        $claseDisp = '';
                                        if ($porcentaje <= 20) {
                                            $claseDisp = 'disponibilidad-baja';
                                        } elseif ($porcentaje <= 50) {
                                            $claseDisp = 'disponibilidad-media';
                                        } else {
                                            $claseDisp = 'disponibilidad-alta';
                                        }
                                        
                                        $fechaFormateada = date('d/m/Y', strtotime($viaje['fecha']));
                                        $horaFormateada = date('H:i', strtotime($viaje['hora_salida']));
                                    ?>
                                    <tr>
                                        <td><?= $viaje['idViaje'] ?></td>
                                        <td><?= htmlspecialchars($viaje['ruta_nombre']) ?></td>
                                        <td>
                                            <i class="fas fa-<?= 
                                                $viaje['tipo_transporte'] == 'Bus' ? 'bus' : 
                                                ($viaje['tipo_transporte'] == 'Avión' ? 'plane' : 
                                                ($viaje['tipo_transporte'] == 'Tren' ? 'train' : 'ship'))
                                            ?>"></i>
                                            <?= $viaje['tipo_transporte'] ?>
                                        </td>
                                        <td><?= htmlspecialchars($viaje['empresa_nombre'] ?? 'N/A') ?></td>
                                        <td><?= $fechaFormateada ?></td>
                                        <td><?= $horaFormateada ?>hs</td>
                                        <td class="<?= $claseDisp ?>">
                                            <?= $viaje['asientos_disponibles'] ?> / <?= $viaje['asientos_totales'] ?>
                                            <small>(<?= number_format($porcentaje, 0) ?>%)</small>
                                        </td>
                                        <td>
                                            <?php 
                                            $estado = $viaje['habilitado'] ? 'activo' : 'deshabilitado';
                                            $badgeClass = $viaje['habilitado'] ? 'success' : 'secondary';
                                            ?>
                                            <span class="badge badge-<?= $badgeClass ?>">
                                                <?= ucfirst($estado) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="viajeTransporteAlta.php?id=<?= $viaje['idViaje'] ?>" 
                                               class="btn btn-sm btn-warning" 
                                               title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="viajeSegmentosPreciosEditor.php?id=<?= $viaje['idViaje'] ?>" 
                                               class="btn btn-sm btn-success" 
                                               title="Precios por Segmento">
                                                <i class="fas fa-dollar-sign"></i>
                                            </a>
                                            <a href="viajeTransporteAdicionales.php?id=<?= $viaje['idViaje'] ?>" 
                                               class="btn btn-sm btn-info" 
                                               title="Servicios Adicionales">
                                                <i class="fas fa-gift"></i>
                                            </a>
                                            <button class="btn btn-sm btn-danger btnEliminar" 
                                                    data-id="<?= $viaje['idViaje'] ?>"
                                                    title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                                          <a href="tipoButacaTransporte.php?idViaje=<?= $viaje['idViaje'] ?>" 
                                                              class="btn btn-sm btn-info" 
                                                              title="Tipos de Butaca" style="display:none;">
                                                <i class="fas fa-chair"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    
    <?php include("includes/footer.php"); ?>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<!-- DataTables -->
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
<!-- AdminLTE -->
<script src="dist/js/adminlte.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    // Inicializar DataTable
    var table = $('#tablaViajes').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
        },
        "order": [[4, "asc"], [5, "asc"]], // Ordenar por fecha y hora
        "pageLength": 25,
        "columnDefs": [
            { "orderable": false, "targets": 8 } // Columna de acciones no ordenable
        ]
    });
    
    // Filtros personalizados
    $('#filtroTipo').on('change', function() {
        table.column(2).search(this.value).draw();
    });
    
    $('#filtroEstado').on('change', function() {
        table.column(7).search(this.value).draw();
    });
    
    $('#filtroFecha').on('change', function() {
        var fecha = this.value;
        if (fecha) {
            // Convertir a formato dd/mm/yyyy para búsqueda
            var partes = fecha.split('-');
            var fechaBusqueda = partes[2] + '/' + partes[1] + '/' + partes[0];
            table.column(4).search(fechaBusqueda).draw();
        } else {
            table.column(4).search('').draw();
        }
    });
    
    $('#btnLimpiarFiltros').on('click', function() {
        $('#filtroTipo').val('');
        $('#filtroEstado').val('');
        $('#filtroFecha').val('');
        table.search('').columns().search('').draw();
    });
    
    // Eliminar viaje
    $(document).on('click', '.btnEliminar', function() {
        var idViaje = $(this).data('id');
        
        Swal.fire({
            title: '¿Estás seguro?',
            text: "Si el viaje tiene reservas, solo se deshabilitará",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'ctrl/ctrlViajesTransporte.php?action=delete&id=' + idViaje,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire(
                                'Eliminado!',
                                'El viaje ha sido eliminado.',
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                response.error || 'No se pudo eliminar el viaje',
                                'error'
                            );
                        }
                    },
                    error: function() {
                        Swal.fire(
                            'Error!',
                            'Error de conexión',
                            'error'
                        );
                    }
                });
            }
        });
    });
});
</script>

                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php include("includes/footer.php"); ?>
