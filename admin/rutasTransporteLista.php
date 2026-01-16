<?php
// Verificar permisos de acceso ANTES de cualquier salida
require_once(__DIR__ . "/classes/permisos.php");
require_once(__DIR__ . "/includes/permisos_helper.php");
$permisos = new PermisosManager($GLOBALS['pdo'], $_SESSION['login'] ?? []);
$permisos->verificarAcceso('rutasTransporteLista');

include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");

require_once("classes/transporte.php");

$rutas = getAllRutas();
$tipos = getAllTiposTransporte();

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
                    <h1 class="m-0 text-dark"><i class="fas fa-route"></i> Rutas de Transporte</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Transporte</a></li>
                        <li class="breadcrumb-item active">Rutas</li>
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
                        <h4 class="mb-0"><i class="fas fa-route"></i> Rutas de Transporte</h4>
                        <a href="rutaTransporteAlta.php" class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> <span class="d-none d-sm-inline">Nueva Ruta</span>
                        </a>
                    </div>
                    <div class="card-body">
                        <?php if ($success == '1') { ?>
                            <div class="alert alert-success alert-dismissible fade show">
                                <i class="fas fa-check-circle"></i> Ruta guardada correctamente
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                            </div>
                        <?php } elseif ($success == '2') { ?>
                            <div class="alert alert-success alert-dismissible fade show">
                                <i class="fas fa-trash-alt"></i> Ruta eliminada correctamente
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
                            <table class="table table-striped table-hover" id="tablaRutas">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Tipo</th>
                                        <th>Empresa</th>
                                        <th>Prestador</th>
                                        <th>Duración</th>
                                        <th>Distancia</th>
                                        <th>Paradas</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($rutas as $ruta) { 
                                        // Obtener paradas de esta ruta
                                        $paradas = getParadasRuta($ruta['idRuta']);
                                        $numParadas = count($paradas);
                                        
                                        // Determinar clase de badge según tipo
                                        $badgeClass = 'badge-secondary';
                                        $icono = 'fa-bus';
                                        switch($ruta['idTipoTransporte']) {
                                            case 1: $badgeClass = 'badge-bus'; $icono = 'fa-bus'; break;
                                            case 2: $badgeClass = 'badge-plane'; $icono = 'fa-plane'; break;
                                            case 3: $badgeClass = 'badge-train'; $icono = 'fa-train'; break;
                                            case 4: $badgeClass = 'badge-ship'; $icono = 'fa-ship'; break;
                                        }
                                    ?>
                                        <tr data-tipo="<?=$ruta['idTipoTransporte']?>">
                                            <td><?=$ruta['idRuta']?></td>
                                            <td><strong><?=$ruta['nombre']?></strong></td>
                                            <td>
                                                <span class="badge <?=$badgeClass?>">
                                                    <i class="fas <?=$icono?>"></i> <?=$ruta['tipo_transporte_nombre']?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if (!empty($ruta['empresa_nombre'])) { ?>
                                                    <span class="badge badge-info"><?=$ruta['empresa_nombre']?></span>
                                                <?php } else { ?>
                                                    <span class="text-muted">-</span>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <?php if (!empty($ruta['prestador_nombre'])) { ?>
                                                    <small><?=$ruta['prestador_nombre']?></small>
                                                <?php } else { ?>
                                                    <span class="text-muted">-</span>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <?php if (!empty($ruta['duracion_estimada'])) { ?>
                                                    <i class="far fa-clock"></i> <?=$ruta['duracion_estimada']?>
                                                <?php } else { ?>
                                                    <span class="text-muted">-</span>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <?php if (!empty($ruta['distancia_km'])) { ?>
                                                    <i class="fas fa-road"></i> <?=$ruta['distancia_km']?> km
                                                <?php } else { ?>
                                                    <span class="text-muted">-</span>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <span class="badge badge-secondary">
                                                    <i class="fas fa-map-marker-alt"></i> <?=$numParadas?> paradas
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ($ruta['habilitado']) { ?>
                                                    <span class="badge badge-success">Activa</span>
                                                <?php } else { ?>
                                                    <span class="badge badge-danger">Inactiva</span>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="rutaTransporteAlta.php?id=<?=$ruta['idRuta']?>" 
                                                       class="btn btn-sm btn-warning" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="rutaTransporteParadas.php?id=<?=$ruta['idRuta']?>" 
                                                       class="btn btn-sm btn-info" title="Gestionar paradas">
                                                        <i class="fas fa-map-marker-alt"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-danger" 
                                                            onclick="eliminarRuta(<?=$ruta['idRuta']?>, '<?=htmlspecialchars($ruta['nombre'], ENT_QUOTES)?>')" 
                                                            title="Eliminar">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php } ?>
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

<?php include("includes/footer.php"); ?>

<script>
    // Inicializar DataTables
    var table = $('#tablaRutas').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json"
        },
        "pageLength": 25,
        "order": [[1, 'asc']], // Ordenar por nombre
        "columnDefs": [
            { "orderable": false, "targets": [9] } // Columna de acciones no ordenable
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
            table.column(2).search(textoTipo).draw();
        }
    });
    
    // Eliminar ruta
    function eliminarRuta(id, nombre) {
        if (confirm('¿Estás seguro de eliminar la ruta "' + nombre + '"?\n\nEsto puede afectar viajes y reservas asociados.')) {
            window.location.href = 'ctrl/ctrlRutasTransporte.php?action=delete&id=' + id;
        }
    }
</script>
