<?php
// Verificar permisos de acceso ANTES de cualquier salida
require_once(__DIR__ . "/classes/permisos.php");
require_once(__DIR__ . "/includes/permisos_helper.php");
$permisos = new PermisosManager($GLOBALS['pdo'], $_SESSION['login'] ?? []);
$permisos->verificarAcceso('rutaTransporteParadas');

include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");

require_once("classes/transporte.php");

// Validar ID de ruta
$idRuta = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($idRuta <= 0) {
    header("Location: rutasTransporteLista.php");
    exit();
}

$ruta = getRuta($idRuta);
if (!$ruta) {
    header("Location: rutasTransporteLista.php");
    exit();
}

$paradas = getParadasRuta($idRuta);
$terminales = getAllTerminales();
$success = isset($_GET['success']) ? $_GET['success'] : '';
?>

<style>
    .parada-item {
        transition: all 0.3s ease;
    }
    .parada-item:hover {
        background-color: #f8f9fa;
    }
    .badge-origen {
        background-color: #28a745;
    }
    .badge-destino {
        background-color: #007bff;
    }
    .badge-intermedia {
        background-color: #6c757d;
    }
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">
                        <i class="fas fa-map-marker-alt"></i> Paradas de la Ruta
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Transporte</a></li>
                        <li class="breadcrumb-item"><a href="rutasTransporteLista.php">Rutas</a></li>
                        <li class="breadcrumb-item active">Paradas</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            
            <!-- Info de la ruta -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="alert alert-info">
                        <h5><i class="fas fa-route"></i> Ruta: <strong><?=$ruta['nombre']?></strong></h5>
                        <p class="mb-0">
                            Tipo: <span class="badge badge-primary"><?=$ruta['tipo_transporte_nombre']?></span>
                            <?php if (!empty($ruta['duracion_estimada'])) { ?>
                                | Duración: <strong><?=$ruta['duracion_estimada']?></strong>
                            <?php } ?>
                            <?php if (!empty($ruta['distancia_km'])) { ?>
                                | Distancia: <strong><?=$ruta['distancia_km']?> km</strong>
                            <?php } ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Formulario para agregar parada -->
                <div class="col-md-4">
                    <div class="card shadow">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="fas fa-plus-circle"></i> Agregar Parada</h5>
                        </div>
                        <div class="card-body">
                            <form action="ctrl/ctrlParadasRuta.php" method="POST" id="formAgregarParada">
                                <input type="hidden" name="action" value="insert">
                                <input type="hidden" name="idRuta" value="<?=$idRuta?>">
                                
                                <div class="form-group">
                                    <label><i class="fas fa-map-marker-alt"></i> Terminal <span class="text-danger">*</span></label>
                                    <select name="idTerminal" class="form-control" required>
                                        <option value="">Seleccionar terminal...</option>
                                        <?php foreach ($terminales as $terminal) { ?>
                                            <option value="<?=$terminal['idTerminal']?>">
                                                <?=$terminal['nombre']?> (<?=$terminal['ciudad']?>)
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label><i class="fas fa-sort"></i> Orden <span class="text-danger">*</span></label>
                                    <input type="number" name="orden" class="form-control" required min="1" 
                                           value="<?=count($paradas) + 1?>"
                                           placeholder="1">
                                    <small class="form-text text-muted">Posición en la secuencia de paradas</small>
                                </div>
                                
                                <div class="form-group">
                                    <label><i class="far fa-clock"></i> Tiempo desde inicio</label>
                                    <input type="text" name="tiempo_desde_inicio" class="form-control" 
                                           placeholder="Ej: 2h 30min">
                                    <small class="form-text text-muted">Opcional: Tiempo acumulado desde origen</small>
                                </div>
                                
                                <div class="form-group">
                                    <label><strong>Tipo de parada:</strong></label>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="es_origen" name="es_origen" value="1">
                                        <label class="custom-control-label" for="es_origen">
                                            <span class="badge badge-origen">ORIGEN</span> Punto de partida
                                        </label>
                                    </div>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="es_destino" name="es_destino" value="1">
                                        <label class="custom-control-label" for="es_destino">
                                            <span class="badge badge-destino">DESTINO</span> Punto de llegada
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">Una parada puede ser origen, destino, ambos o intermedia</small>
                                </div>
                                
                                <hr>
                                <button type="submit" class="btn btn-success btn-block">
                                    <i class="fas fa-plus"></i> Agregar Parada
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Lista de paradas existentes -->
                <div class="col-md-8">
                    <div class="card shadow">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-list"></i> Paradas Configuradas (<?=count($paradas)?>)</h5>
                        </div>
                        <div class="card-body">
                            
                            <?php if ($success == '1') { ?>
                                <div class="alert alert-success alert-dismissible fade show">
                                    <i class="fas fa-check-circle"></i> Parada agregada correctamente
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                </div>
                            <?php } elseif ($success == '2') { ?>
                                <div class="alert alert-success alert-dismissible fade show">
                                    <i class="fas fa-trash-alt"></i> Parada eliminada correctamente
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                </div>
                            <?php } ?>
                            
                            <?php if (empty($paradas)) { ?>
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i> 
                                    <strong>No hay paradas configuradas.</strong>
                                    <p class="mb-0">Agrega al menos un origen y un destino para que los clientes puedan reservar esta ruta.</p>
                                </div>
                            <?php } else { ?>
                                
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> 
                                    <strong>Importante:</strong> Las paradas determinan qué combinaciones origen-destino estarán disponibles para los clientes.
                                </div>
                                
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="thead-light">
                                            <tr>
                                                <th style="width: 50px;">Orden</th>
                                                <th>Terminal</th>
                                                <th>Ciudad</th>
                                                <th>Tipo</th>
                                                <th>Tiempo</th>
                                                <th style="width: 100px;">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($paradas as $parada) { 
                                                // Determinar tipo de parada
                                                $esOrigen = $parada['es_origen'];
                                                $esDestino = $parada['es_destino'];
                                                $tipoParada = '';
                                                
                                                if ($esOrigen && $esDestino) {
                                                    $tipoParada = '<span class="badge badge-origen">ORIGEN</span> <span class="badge badge-destino">DESTINO</span>';
                                                } elseif ($esOrigen) {
                                                    $tipoParada = '<span class="badge badge-origen">ORIGEN</span>';
                                                } elseif ($esDestino) {
                                                    $tipoParada = '<span class="badge badge-destino">DESTINO</span>';
                                                } else {
                                                    $tipoParada = '<span class="badge badge-intermedia">INTERMEDIA</span>';
                                                }
                                            ?>
                                                <tr class="parada-item">
                                                    <td class="text-center">
                                                        <span class="badge badge-secondary"><?=$parada['orden']?></span>
                                                    </td>
                                                    <td><strong><?=$parada['terminal_nombre']?></strong></td>
                                                    <td><?=$parada['ciudad']?></td>
                                                    <td><?=$tipoParada?></td>
                                                    <td>
                                                        <?php if (!empty($parada['tiempo_desde_inicio'])) { ?>
                                                            <i class="far fa-clock text-muted"></i> <?=$parada['tiempo_desde_inicio']?>
                                                        <?php } else { ?>
                                                            <span class="text-muted">-</span>
                                                        <?php } ?>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-danger" 
                                                                onclick="eliminarParada(<?=$parada['idRutaParada']?>, '<?=htmlspecialchars($parada['terminal_nombre'], ENT_QUOTES)?>')"
                                                                title="Eliminar">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                                
                            <?php } ?>
                        </div>
                        
                        <div class="card-footer">
                            <a href="rutasTransporteLista.php" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Volver a Rutas
                            </a>
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
function eliminarParada(idParada, nombreTerminal) {
    if (confirm('¿Estás seguro de eliminar la parada "' + nombreTerminal + '"?\n\nEsto puede afectar los viajes y reservas existentes.')) {
        window.location.href = 'ctrl/ctrlParadasRuta.php?action=delete&idParada=' + idParada + '&idRuta=<?=$idRuta?>';
    }
}
</script>
