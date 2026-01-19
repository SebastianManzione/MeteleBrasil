<?php
// Verificar permisos de acceso ANTES de cualquier salida
require_once(__DIR__ . "/classes/permisos.php");
require_once(__DIR__ . "/includes/permisos_helper.php");
$permisos = new PermisosManager($GLOBALS['pdo'], $_SESSION['login'] ?? []);
$permisos->verificarAcceso('terminalesLista');

include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");

require_once("classes/transporte.php");

// Conectar a BD experimental si no está disponible
if (!isset($GLOBALS['pdo_experimental'])) {
    $GLOBALS['pdo_experimental'] = new PDO(
        'mysql:host=localhost;dbname=metelebrasil_experimental;charset=utf8mb4',
        'root',
        ''
    );
    $GLOBALS['pdo_experimental']->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}

// Obtener todas las terminales desde terminal_transporte (experimental)
$stmt = $GLOBALS['pdo_experimental']->prepare(
    "SELECT tt.*, tp.nombre AS tipo_nombre
     FROM terminal_transporte tt
     LEFT JOIN tipo_transporte tp ON tt.idTipoTransporte = tp.idTipoTransporte
     ORDER BY tt.ciudad, tt.nombre"
);
$stmt->execute();
$terminales = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Tipos de transporte (para filtro)
$tiposStmt = $GLOBALS['pdo_experimental']->query("SELECT * FROM tipo_transporte WHERE habilitado=1 ORDER BY nombre");
$tipos = $tiposStmt->fetchAll(PDO::FETCH_ASSOC);

$success = isset($_GET['success']) ? $_GET['success'] : '';
?>

<style>
    .badge-bus { background-color: #007bff; }
    .badge-plane { background-color: #28a745; }
    .badge-train { background-color: #ffc107; color: #000; }
    .badge-ship { background-color: #17a2b8; }
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark"><i class="fas fa-map-marker-alt"></i> Terminales de Transporte</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Transporte</a></li>
                        <li class="breadcrumb-item active">Terminales</li>
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
                        <h4 class="mb-0"><i class="fas fa-map-marker-alt"></i> Terminales de Transporte</h4>
                        <a href="terminalAlta.php" class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> <span class="d-none d-sm-inline">Nueva Terminal</span>
                        </a>
                    </div>
                    <div class="card-body">
                        <?php if ($success == '1') { ?>
                            <div class="alert alert-success alert-dismissible fade show">
                                <i class="fas fa-check-circle"></i> Terminal guardada correctamente
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                            </div>
                        <?php } ?>
                        
                        <?php if ($success == 'deleted') { ?>
                            <div class="alert alert-info alert-dismissible fade show">
                                <i class="fas fa-trash"></i> Terminal eliminada
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                            </div>
                        <?php } ?>
                        
                        <!-- Filtros -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Filtrar por tipo:</label>
                                <select id="filtroTipo" class="form-control">
                                    <option value="">Todos los tipos</option>
                                    <?php foreach ($tipos as $tipo) { ?>
                                        <option value="<?=$tipo['idTipoTransporte']?>"><?=$tipo['nombre']?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Tabla -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="tablaTerminales">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Ciudad</th>
                                        <th>Tipo</th>
                                        <th>Código IATA</th>
                                        <th>Dirección</th>
                                        <th>Coordenadas</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($terminales as $terminal) { ?>
                                        <tr data-tipo="terminal" data-ciudad="<?=strtolower($terminal['ciudad'])?>">
                                            <td><?=$terminal['idTerminal']?></td>
                                            <td data-order="<?=htmlspecialchars($terminal['nombre'])?>"><strong><?=$terminal['nombre']?></strong></td>
                                            <td><?=$terminal['ciudad']?></td>
                                            <td>
                                                <span class="badge badge-warning">
                                                    <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($terminal['tipo_nombre'] ?? 'Terminal')?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if (!empty($terminal['codigo_iata'])) { ?>
                                                    <span class="badge badge-info"><?=$terminal['codigo_iata']?></span>
                                                <?php } else { ?>
                                                    <span class="text-muted">-</span>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <?=!empty($terminal['direccion']) ? htmlspecialchars($terminal['direccion']) : '-'?>
                                                </small>
                                            </td>
                                            <td>
                                                <?php if (!empty($terminal['latitud']) && !empty($terminal['longitud'])) { ?>
                                                    <a href="https://www.google.com/maps?q=<?=$terminal['latitud']?>,<?=$terminal['longitud']?>" 
                                                       target="_blank" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-map-marked-alt"></i> Ver mapa
                                                    </a>
                                                <?php } else { ?>
                                                    <span class="text-muted">-</span>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <?php if ($terminal['habilitado']) { ?>
                                                    <span class="badge badge-success">Activo</span>
                                                <?php } else { ?>
                                                    <span class="badge badge-secondary">Inactivo</span>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <a href="terminalAlta.php?id=<?=$terminal['idTerminal']?>" 
                                                   class="btn btn-sm btn-warning" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button onclick="eliminarTerminal(<?=$terminal['idTerminal']?>, '<?=htmlspecialchars($terminal['nombre'], ENT_QUOTES)?>')" 
                                                        class="btn btn-sm btn-danger" title="Eliminar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <?php if (empty($terminales)) { ?>
                            <div class="alert alert-info text-center">
                                <i class="fas fa-info-circle"></i> No hay terminales registradas. 
                                <a href="terminalAlta.php">Crear primera terminal</a>
                            </div>
                        <?php } ?>
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

<script>
    // Plugin para usar data-order en columnas con HTML
    jQuery.extend(jQuery.fn.dataTable.ext.order, {
        "dom-data-order": function ( settings, col ) {
            return this.api().column( col, {order:'index'} ).nodes().map( function (td, i) {
                return td.getAttribute('data-order') || td.innerText;
            } );
        }
    });

    // Filtro por tipo
    // Inicializar DataTables
    var table = $('#tablaTerminales').DataTable({
        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json"
        },
        pageLength: 25,
        order: [[2, 'asc'], [1, 'asc']], // Primero ciudad, luego nombre
        columnDefs: [
            { orderable: false, targets: [8] }, // Acciones no ordenable
            { targets: 1, orderDataType: 'dom-data-order' } // usar data-order en nombre
        ]
    });
    
    // Integrar filtro de tipo con DataTables
    $('#filtroTipo').on('change', function() {
        var valorFiltro = $(this).val();
        
        if (valorFiltro === '') {
            // Si está vacío, mostrar todos
            table.search('').draw();
        } else {
            // Buscar por el texto visible del tipo (Bus, Avión, etc)
            var textoTipo = $(this).find('option:selected').text();
            table.column(3).search(textoTipo).draw();
        }
    });
    
    // Eliminar terminal
    function eliminarTerminal(id, nombre) {
        if (!window.Swal) {
            if (confirm('¿Eliminar la terminal "' + nombre + '"?')) {
                fetch('ctrl/ctrlTerminalesNuevo.php', {
                    method: 'POST',
                    body: new URLSearchParams({ action: 'delete', idTerminal: id })
                }).then(r => r.json()).then(data => {
                    if (data && data.success) {
                        table.rows(function(idx, data, node) {
                            return $(node).find('td:first').text() == id;
                        }).remove().draw();
                        alert('Terminal eliminada');
                    } else {
                        alert(data.message || 'No se pudo eliminar');
                    }
                });
            }
            return;
        }
        Swal.fire({
            title: '¿Eliminar terminal?',
            text: nombre,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then(function(result) {
            if (result.isConfirmed) {
                fetch('ctrl/ctrlTerminalesNuevo.php', {
                    method: 'POST',
                    body: new URLSearchParams({ action: 'delete', idTerminal: id })
                }).then(r => r.json()).then(data => {
                    if (data && data.success) {
                        table.rows(function(idx, data, node) {
                            return $(node).find('td:first').text() == id;
                        }).remove().draw();
                        Swal.fire({ icon: 'success', title: 'Eliminada', timer: 1200, showConfirmButton: false });
                    } else {
                        Swal.fire({ icon: 'error', title: 'Error', text: data.message || 'No se pudo eliminar' });
                    }
                }).catch(err => {
                    Swal.fire({ icon: 'error', title: 'Error', text: err.message });
                });
            }
        });
    }
    </script>
