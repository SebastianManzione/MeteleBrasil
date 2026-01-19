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
$todasLasParadas = getAllParadas();
$success = isset($_GET['success']) ? $_GET['success'] : '';

// Preparar datos de paradas para JavaScript ANTES de cargar Google Maps
$paradasJSON = [];
foreach ($paradas as $parada) {
    $paradasJSON[] = [
        'orden' => $parada['orden'],
        'nombre' => $parada['terminal_nombre'],
        'ciudad' => $parada['ciudad'],
        'latitud' => floatval($parada['latitud'] ?? 0),
        'longitud' => floatval($parada['longitud'] ?? 0),
        'es_origen' => $parada['es_origen'],
        'es_destino' => $parada['es_destino'],
        'tiempo' => $parada['tiempo_desde_inicio']
    ];
}
?>

<script>
// Definir datos de paradas ANTES de cargar Google Maps
var paradasRecorrido = <?php echo json_encode($paradasJSON, JSON_UNESCAPED_UNICODE); ?>;
console.log('Paradas cargadas:', paradasRecorrido.length);
</script>



<style>
#suggestionsParada {
    background: white;
    border: 1px solid #ddd;
    border-top: none;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    margin-top: -1px;
}

#suggestionsParada .suggestion-item {
    cursor: pointer;
    border-left: none;
    border-right: none;
}

#suggestionsParada .suggestion-item:hover,
#suggestionsParada .suggestion-item.active {
    background-color: #f0f8ff;
    border-left: 3px solid #007bff;
}

#suggestionsParada .suggestion-item h6 {
    font-size: 14px;
    margin-bottom: 2px;
}

#suggestionsParada .suggestion-item small {
    font-size: 12px;
}

#inputTerminalAutocomplete:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
}

/* Estilos para Drag and Drop */
.parada-item {
    transition: background-color 0.2s ease, transform 0.1s ease;
}

.parada-item.drag-over {
    background-color: #e3f2fd;
    border-top: 2px solid #2196F3;
}

.parada-item:hover {
    background-color: #f5f5f5;
}

.drag-handle {
    cursor: move;
    font-size: 18px;
}

.drag-handle:hover {
    color: #007bff !important;
}

/* Efecto visual al arrastrar */
.parada-item[draggable="true"]:active {
    cursor: grabbing;
}
</style>

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
                            <!-- Tabs: Existente vs Nueva -->
                            <ul class="nav nav-tabs mb-3" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="tab-existente" data-toggle="tab" href="#form-existente" role="tab">
                                        <i class="fas fa-list"></i> Existente
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="tab-nueva" data-toggle="tab" href="#form-nueva" role="tab">
                                        <i class="fas fa-plus"></i> Nueva
                                    </a>
                                </li>
                            </ul>
                            
                            <div class="tab-content">
                                <!-- TAB 1: Seleccionar Parada Existente -->
                                <div class="tab-pane fade show active" id="form-existente" role="tabpanel">
                                    <form action="ctrl/ctrlParadasRuta.php" method="POST" id="formAgregarExistente">
                                        <input type="hidden" name="action" value="insert">
                                        <input type="hidden" name="idRuta" value="<?=$idRuta?>">
                                        
                                        <div class="form-group">
                                            <label><i class="fas fa-map-marker-alt"></i> Terminal/Parada <span class="text-danger">*</span></label>
                                            <input type="text" 
                                                   id="inputTerminalAutocomplete" 
                                                   class="form-control" 
                                                   placeholder="Escriba para buscar terminal..."
                                                   autocomplete="off"
                                                   required>
                                            <input type="hidden" name="idParada" id="hiddenIdParada" required>
                                            
                                            <!-- Contenedor de sugerencias -->
                                            <div id="suggestionsParada" class="list-group" style="position: absolute; z-index: 1000; max-height: 300px; overflow-y: auto; display: none; width: calc(100% - 30px);"></div>
                                            
                                            <small class="form-text text-muted">71 terminales disponibles - Escriba nombre, ciudad o país</small>
                                            
                                            <?php 
                                            // Preparar datos JSON para JavaScript
                                            $terminalesJSON = [];
                                            foreach ($todasLasParadas as $parada) {
                                                $terminalesJSON[] = [
                                                    'id' => $parada['idParada'],
                                                    'nombre' => $parada['nombre'],
                                                    'ciudad' => $parada['ciudad'] ?? '',
                                                    'estado' => $parada['estado'] ?? '',
                                                    'pais' => $parada['pais'] ?? '',
                                                    'tipo' => $parada['tipo_nombre'] ?? 'Terminal',
                                                    'searchText' => strtolower(
                                                        ($parada['nombre'] ?? '') . ' ' . 
                                                        ($parada['ciudad'] ?? '') . ' ' . 
                                                        ($parada['estado'] ?? '') . ' ' . 
                                                        ($parada['pais'] ?? '')
                                                    )
                                                ];
                                            }
                                            ?>
                                            <script>
                                            var terminalesData = <?php echo json_encode($terminalesJSON, JSON_UNESCAPED_UNICODE); ?>;
                                            </script>
                                        </div>
                                        
                                        <!-- Orden automático (oculto) -->
                                        <input type="hidden" name="orden" value="<?=count($paradas) + 1?>">
                                        
                                        <div class="form-group">
                                            <label><i class="far fa-clock"></i> Tiempo desde inicio (opcional)</label>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <input type="number" id="diasParada" name="dias" class="form-control" min="0" max="99" value="0" placeholder="0">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">días</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <input type="number" id="horasParada" name="horas" class="form-control" min="0" max="23" value="0" placeholder="0">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">h</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <input type="number" id="minutosParada" name="minutos" class="form-control" min="0" max="59" value="0" placeholder="0">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">min</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="hidden" name="tiempo_desde_inicio" id="tiempoCompuesto">
                                            <small class="form-text text-muted">Se calculará automáticamente al enviar (puede dejar todo en 0)</small>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label><strong>Tipo de parada:</strong></label>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="es_origen" name="es_origen" value="1">
                                                <label class="custom-control-label" for="es_origen">
                                                    <span class="badge badge-origen">ORIGEN</span>
                                                </label>
                                            </div>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="es_destino" name="es_destino" value="1">
                                                <label class="custom-control-label" for="es_destino">
                                                    <span class="badge badge-destino">DESTINO</span>
                                                </label>
                                            </div>
                                            <small class="form-text text-muted">Puede ser origen, destino, ambos o intermedia</small>
                                        </div>
                                        
                                        <button type="submit" class="btn btn-success btn-block">
                                            <i class="fas fa-check-circle"></i> Agregar Parada
                                        </button>
                                    </form>
                                </div>
                                
                                <!-- TAB 2: Crear Nueva Parada -->
                                <div class="tab-pane fade" id="form-nueva" role="tabpanel">
                                    <form action="ctrl/ctrlParadasRuta.php" method="POST" id="formNuevaParada">
                                        <input type="hidden" name="action" value="insert_nueva">
                                        <input type="hidden" name="idRuta" value="<?=$idRuta?>">
                                        <input type="hidden" name="tipo" value="customizada">
                                        <input type="hidden" name="latitud" id="latitudNueva" value="">
                                        <input type="hidden" name="longitud" id="longitudNueva" value="">
                                        
                                        <div class="form-group">
                                            <label><i class="fas fa-heading"></i> Nombre de la parada <span class="text-danger">*</span></label>
                                            <input type="text" name="nombre" id="nombreNueva" class="form-control" required placeholder="Ej: Terminal de Ómnibus Sur">
                                            <small class="form-text text-muted">Este nombre NO se autocompletará con el mapa, escríbalo manualmente</small>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label><i class="fas fa-map-marked-alt"></i> Buscar ubicación en el mapa</label>
                                            <input type="text" id="searchInputNueva" class="form-control mb-2" placeholder="Buscar dirección...">
                                            <div id="mapNueva" style="width: 100%; height: 400px; border: 1px solid #ddd; border-radius: 4px;"></div>
                                            <small class="form-text text-muted">Haga click en el mapa o arrastre el marcador para autocompletar dirección, ciudad, estado y país</small>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label><i class="fas fa-map-marker-alt"></i> Dirección <span class="text-danger">*</span></label>
                                            <input type="text" name="direccion" id="direccionNueva" class="form-control" required placeholder="Se completa automáticamente" readonly>
                                        </div>
                                        
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label><i class="fas fa-city"></i> Ciudad <span class="text-danger">*</span></label>
                                                <input type="text" name="ciudad" id="ciudadNueva" class="form-control" required placeholder="Se completa automáticamente" readonly>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label><i class="fas fa-map"></i> Estado</label>
                                                <input type="text" name="estado" id="estadoNueva" class="form-control" placeholder="Se completa automáticamente" readonly>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label><i class="fas fa-globe"></i> País <span class="text-danger">*</span></label>
                                            <input type="text" name="pais" id="paisNueva" class="form-control" required placeholder="Se completa automáticamente" readonly>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label><strong>Tipo:</strong></label>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="es_origen_n" name="es_origen" value="1">
                                                <label class="custom-control-label" for="es_origen_n">
                                                    <span class="badge badge-origen">ORIGEN</span>
                                                </label>
                                            </div>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="es_destino_n" name="es_destino" value="1">
                                                <label class="custom-control-label" for="es_destino_n">
                                                    <span class="badge badge-destino">DESTINO</span>
                                                </label>
                                            </div>
                                        </div>
                                        
                                        <!-- Orden automático (oculto) -->
                                        <input type="hidden" name="orden" value="<?=count($paradas) + 1?>">
                                        
                                        <div class="form-group">
                                            <label><i class="far fa-clock"></i> Tiempo desde inicio (opcional)</label>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <input type="number" id="diasNueva" name="dias" class="form-control" min="0" max="99" value="0" placeholder="0">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">días</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <input type="number" id="horasNueva" name="horas" class="form-control" min="0" max="23" value="0" placeholder="0">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">h</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <input type="number" id="minutosNueva" name="minutos" class="form-control" min="0" max="59" value="0" placeholder="0">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">min</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="hidden" name="tiempo_desde_inicio" id="tiempoCompuestoNueva">
                                            <small class="form-text text-muted">Se calculará automáticamente al enviar (puede dejar todo en 0)</small>
                                        </div>
                                        
                                        <button type="submit" class="btn btn-primary btn-block">
                                            <i class="fas fa-plus-circle"></i> Crear y Agregar Parada
                                        </button>
                                    </form>
                                </div>
                            </div>
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
                                                <tr class="parada-item" draggable="true" data-id="<?=$parada['idRutaParada']?>" data-orden="<?=$parada['orden']?>">
                                                    <td class="text-center" style="cursor: move;">
                                                        <i class="fas fa-grip-vertical text-muted drag-handle"></i>
                                                        <span class="badge badge-secondary ml-1"><?=$parada['orden']?></span>
                                                    </td>
                                                    <td><strong><?=$parada['terminal_nombre']?></strong></td>
                                                    <td><?=$parada['ciudad']?></td>
                                                    <td><?=$tipoParada?></td>
                                                    <td>
                                                        <?php 
                                                        $tiempo = trim($parada['tiempo_desde_inicio']);
                                                        // Detectar valores vacíos o default "0 días 0h 00min"
                                                        if (!empty($tiempo) && $tiempo !== '0 días 0h 00min' && $tiempo !== '0h' && $tiempo !== '0 días') { ?>
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
                                
                                <?php if (!empty($paradas)) { ?>
                                <!-- Mapa de Recorrido -->
                                <hr class="my-4">
                                <h5 class="mb-3">
                                    <i class="fas fa-route text-info"></i> Mapa de Recorrido
                                </h5>
                                <div id="mapRecorrido" style="width: 100%; height: 500px; border-radius: 4px; border: 1px solid #ddd;"></div>
                                <div class="mt-2">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle"></i> 
                                        Este mapa muestra el recorrido completo conectando todas las paradas en orden.
                                        <span class="badge badge-origen ml-2">Verde</span> = Origen | 
                                        <span class="badge badge-destino ml-1">Azul</span> = Destino | 
                                        <span class="badge badge-intermedia ml-1">Gris</span> = Intermedia
                                    </small>
                                </div>
                                <?php } ?>
                                
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

// Filtro con listado clickeable (sin dependencias externas)
document.addEventListener('DOMContentLoaded', function() {
    var inputFiltro = document.getElementById('filtroParada');
    var selectParada = document.getElementById('selectParada');
    var resultado = document.getElementById('resultadoParadas');
    if (!inputFiltro || !selectParada || !resultado) return;

    var normalizar = function(txt) {
        return (txt || '')
            .toString()
            .toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, ''); // quitar acentos
    };

    // Construir índice plano de opciones (mantiene optgroups)
    var opciones = [];
    var optgroups = selectParada.getElementsByTagName('optgroup');
    Array.prototype.forEach.call(optgroups, function(group) {
        var groupLabel = group.label || '';
        Array.prototype.forEach.call(group.children, function(opt) {
            if (opt.tagName !== 'OPTION') return;
            opciones.push({
                value: opt.value,
                text: opt.textContent || '',
                group: groupLabel
            });
        });
    });

    // También opciones sueltas fuera de optgroup (sin placeholder)
    Array.prototype.forEach.call(selectParada.children, function(node) {
        if (node.tagName === 'OPTION' && node.value !== '') {
            opciones.push({
                value: node.value,
                text: node.textContent || '',
                group: ''
            });
        }
    });

    var renderResultados = function(term) {
        var termNorm = normalizar(term);
        var html = '';
        var count = 0;
        opciones.forEach(function(op) {
            var textNorm = normalizar(op.text);
            if (termNorm === '' || textNorm.indexOf(termNorm) !== -1) {
                count++;
                html += '<button type="button" class="list-group-item list-group-item-action" data-value="' + op.value + '">' + op.text + '</button>';
            }
        });
        if (count === 0) {
            html = '<div class="list-group-item text-muted">Sin resultados</div>';
        }
        resultado.innerHTML = html;
        resultado.style.display = 'block';
    };

    inputFiltro.addEventListener('input', function() {
        renderResultados(this.value.trim());
    });

    resultado.addEventListener('click', function(e) {
        if (e.target && e.target.dataset && e.target.dataset.value) {
            var val = e.target.dataset.value;
            selectParada.value = val;
            inputFiltro.value = e.target.textContent.trim();
            resultado.style.display = 'none';
        }
    });

    // Render inicial (muestra lista completa al enfocar)
    inputFiltro.addEventListener('focus', function() {
        if (!resultado.innerHTML) renderResultados('');
        resultado.style.display = 'block';
    });

    document.addEventListener('click', function(e) {
        if (!resultado.contains(e.target) && e.target !== inputFiltro) {
            resultado.style.display = 'none';
        }
    });
});

// Calcular tiempo compuesto antes de enviar el formulario
$('#formAgregarExistente').on('submit', function(e) {
    var dias = parseInt($('#diasParada').val()) || 0;
    var horas = parseInt($('#horasParada').val()) || 0;
    var minutos = parseInt($('#minutosParada').val()) || 0;
    
    var tiempoStr = '';
    if (dias > 0) tiempoStr += dias + ' día' + (dias > 1 ? 's' : '') + ' ';
    if (horas > 0) tiempoStr += horas + 'h ';
    if (minutos > 0) tiempoStr += minutos.toString().padStart(2, '0') + 'min';
    
    $('#tiempoCompuesto').val(tiempoStr.trim());
});

// Calcular tiempo compuesto para formulario de nueva parada
$('#formNuevaParada').on('submit', function(e) {
    var dias = parseInt($('#diasNueva').val()) || 0;
    var horas = parseInt($('#horasNueva').val()) || 0;
    var minutos = parseInt($('#minutosNueva').val()) || 0;
    
    var tiempoStr = '';
    if (dias > 0) tiempoStr += dias + ' día' + (dias > 1 ? 's' : '') + ' ';
    if (horas > 0) tiempoStr += horas + 'h ';
    if (minutos > 0) tiempoStr += minutos.toString().padStart(2, '0') + 'min';
    
    $('#tiempoCompuestoNueva').val(tiempoStr.trim());
    
    // Validar que haya coordenadas
    var lat = $('#latitudNueva').val();
    var lng = $('#longitudNueva').val();
    
    if (!lat || !lng || lat === '' || lng === '') {
        e.preventDefault();
        Swal.fire({
            icon: 'warning',
            title: 'Ubicación requerida',
            text: 'Por favor, haga click en el mapa para seleccionar la ubicación de la parada',
            confirmButtonText: 'OK'
        });
        return false;
    }
    
    // Validar que el nombre no esté vacío (el usuario debe escribirlo manualmente)
    var nombre = $('#nombreNueva').val().trim();
    if (!nombre || nombre === '') {
        e.preventDefault();
        Swal.fire({
            icon: 'warning',
            title: 'Nombre requerido',
            text: 'Por favor, escriba el nombre de la parada en el primer campo',
            confirmButtonText: 'OK'
        });
        return false;
    }
    
    return true;
});

// ===============================================
// SISTEMA DE DRAG AND DROP PARA REORDENAR PARADAS
// ===============================================
var draggedRow = null;
var draggedId = null;
var targetRow = null;

document.querySelectorAll('.parada-item').forEach(function(row) {
    // Evento: inicio del arrastre
    row.addEventListener('dragstart', function(e) {
        draggedRow = this;
        draggedId = this.getAttribute('data-id');
        this.style.opacity = '0.4';
        e.dataTransfer.effectAllowed = 'move';
        e.dataTransfer.setData('text/html', this.innerHTML);
    });
    
    // Evento: fin del arrastre
    row.addEventListener('dragend', function(e) {
        this.style.opacity = '1';
        
        // Remover clases visuales de todas las filas
        document.querySelectorAll('.parada-item').forEach(function(item) {
            item.classList.remove('drag-over');
        });
    });
    
    // Evento: sobre qué fila está pasando
    row.addEventListener('dragover', function(e) {
        if (e.preventDefault) {
            e.preventDefault();
        }
        e.dataTransfer.dropEffect = 'move';
        
        if (draggedRow !== this) {
            this.classList.add('drag-over');
        }
        return false;
    });
    
    // Evento: entró a una fila
    row.addEventListener('dragenter', function(e) {
        if (draggedRow !== this) {
            this.classList.add('drag-over');
        }
    });
    
    // Evento: salió de una fila
    row.addEventListener('dragleave', function(e) {
        this.classList.remove('drag-over');
    });
    
    // Evento: soltó en una fila
    row.addEventListener('drop', function(e) {
        if (e.stopPropagation) {
            e.stopPropagation();
        }
        
        if (draggedRow !== this) {
            var targetId = this.getAttribute('data-id');
            var draggedOrden = parseInt(draggedRow.getAttribute('data-orden'));
            var targetOrden = parseInt(this.getAttribute('data-orden'));
            
            // Determinar dirección
            var direction = (draggedOrden < targetOrden) ? 'down' : 'up';
            
            // Enviar solicitud de reordenamiento múltiple
            reordenarParadas(draggedId, targetId, draggedOrden, targetOrden);
        }
        
        this.classList.remove('drag-over');
        return false;
    });
});

// Función para reordenar paradas mediante AJAX
function reordenarParadas(draggedId, targetId, draggedOrden, targetOrden) {
    $.ajax({
        url: 'ctrl/ctrlParadasRuta.php',
        type: 'POST',
        data: {
            action: 'reorder_drag',
            idParadaDragged: draggedId,
            idParadaTarget: targetId,
            ordenDragged: draggedOrden,
            ordenTarget: targetOrden,
            idRuta: <?=$idRuta?>
        },
        success: function(response) {
            // Recargar página para mostrar nuevo orden
            window.location.reload();
        },
        error: function() {
            alert('Error al reordenar las paradas');
        }
    });
}

// Sistema de autocomplete para terminales
$(document).ready(function() {
    var $input = $('#inputTerminalAutocomplete');
    var $hidden = $('#hiddenIdParada');
    var $suggestions = $('#suggestionsParada');
    var selectedIndex = -1;
    
    // Función para normalizar texto (sin acentos, minúsculas)
    function normalizar(texto) {
        return texto.toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '');
    }
    
    // Función para filtrar y mostrar sugerencias
    function mostrarSugerencias(query) {
        var queryNorm = normalizar(query);
        var resultados = [];
        
        if (queryNorm.length < 2) {
            $suggestions.hide().empty();
            return;
        }
        
        // Filtrar terminales que coincidan
        terminalesData.forEach(function(terminal) {
            if (terminal.searchText.includes(queryNorm)) {
                resultados.push(terminal);
            }
        });
        
        // Mostrar resultados
        if (resultados.length > 0) {
            var html = '';
            resultados.slice(0, 10).forEach(function(terminal) { // Máximo 10 sugerencias
                html += '<a href="#" class="list-group-item list-group-item-action suggestion-item" data-id="' + terminal.id + '" data-nombre="' + terminal.nombre + '">';
                html += '<div class="d-flex w-100 justify-content-between">';
                html += '<h6 class="mb-1"><i class="fas fa-map-marker-alt text-primary"></i> ' + terminal.nombre + '</h6>';
                html += '<small class="badge badge-secondary">' + terminal.tipo + '</small>';
                html += '</div>';
                html += '<small class="text-muted">' + terminal.ciudad + (terminal.estado ? ', ' + terminal.estado : '') + ', ' + terminal.pais + '</small>';
                html += '</a>';
            });
            $suggestions.html(html).show();
            selectedIndex = -1;
        } else {
            $suggestions.html('<div class="list-group-item text-muted">No se encontraron terminales</div>').show();
        }
    }
    
    // Event: input text change
    $input.on('input', function() {
        var query = $(this).val().trim();
        mostrarSugerencias(query);
        $hidden.val(''); // Limpiar selección anterior
    });
    
    // Event: click en sugerencia
    $suggestions.on('click', '.suggestion-item', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var nombre = $(this).data('nombre');
        
        $input.val(nombre);
        $hidden.val(id);
        $suggestions.hide();
    });
    
    // Event: navegación con teclado
    $input.on('keydown', function(e) {
        var $items = $suggestions.find('.suggestion-item');
        
        if (e.keyCode === 40) { // Flecha abajo
            e.preventDefault();
            if (selectedIndex < $items.length - 1) {
                selectedIndex++;
                $items.removeClass('active').eq(selectedIndex).addClass('active');
            }
        } else if (e.keyCode === 38) { // Flecha arriba
            e.preventDefault();
            if (selectedIndex > 0) {
                selectedIndex--;
                $items.removeClass('active').eq(selectedIndex).addClass('active');
            }
        } else if (e.keyCode === 13) { // Enter
            if (selectedIndex >= 0) {
                e.preventDefault();
                $items.eq(selectedIndex).click();
            }
        } else if (e.keyCode === 27) { // Escape
            $suggestions.hide();
            selectedIndex = -1;
        }
    });
    
    // Event: click fuera del autocomplete
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#inputTerminalAutocomplete, #suggestionsParada').length) {
            $suggestions.hide();
        }
    });
    
    // Event: focus en input (mostrar últimas sugerencias si hay texto)
    $input.on('focus', function() {
        var query = $(this).val().trim();
        if (query.length >= 2) {
            mostrarSugerencias(query);
        }
    });
});

// ===============================================
// GOOGLE MAPS - CREAR NUEVA PARADA
// ===============================================
var mapNueva, markerNueva, geocoder, autocompleteNueva;
var mapNuevaInicializado = false;

function initMapNueva() {
    if (mapNuevaInicializado) return; // Evitar reinicializar
    
    // Verificar que Google Maps esté disponible
    if (typeof google === 'undefined' || typeof google.maps === 'undefined') {
        $('#mapNueva').html('<div class="alert alert-danger m-3"><i class="fas fa-times-circle"></i> Google Maps no se pudo cargar. Verifique su conexión o desactive extensiones que bloqueen scripts.</div>');
        return;
    }
    
    // Inicializar geocoder
    geocoder = new google.maps.Geocoder();
    
    // Crear mapa centrado en Buenos Aires
    var centerLat = -34.603684;
    var centerLng = -58.381559;
    
    mapNueva = new google.maps.Map(document.getElementById('mapNueva'), {
        center: { lat: centerLat, lng: centerLng },
        zoom: 12,
        mapTypeControl: true,
        streetViewControl: false
    });
    
    // Crear marcador draggable
    markerNueva = new google.maps.Marker({
        map: mapNueva,
        position: { lat: centerLat, lng: centerLng },
        draggable: true,
        title: 'Arrastre para posicionar'
    });
    
    // Autocomplete en el input de búsqueda
    var searchInput = document.getElementById('searchInputNueva');
    autocompleteNueva = new google.maps.places.Autocomplete(searchInput);
    autocompleteNueva.bindTo('bounds', mapNueva);
    
    // Cuando selecciona lugar del autocomplete
    autocompleteNueva.addListener('place_changed', function() {
        var place = autocompleteNueva.getPlace();
        
        if (!place.geometry) {
            return;
        }
        
        // Centrar mapa y mover marcador
        if (place.geometry.viewport) {
            mapNueva.fitBounds(place.geometry.viewport);
        } else {
            mapNueva.setCenter(place.geometry.location);
            mapNueva.setZoom(17);
        }
        
        markerNueva.setPosition(place.geometry.location);
        
        // Autocompletar campos con reverse geocoding
        reverseGeocodeNueva(place.geometry.location.lat(), place.geometry.location.lng());
    });
    
    // Click en el mapa
    mapNueva.addListener('click', function(event) {
        markerNueva.setPosition(event.latLng);
        reverseGeocodeNueva(event.latLng.lat(), event.latLng.lng());
    });
    
    // Drag del marcador
    markerNueva.addListener('dragend', function(event) {
        reverseGeocodeNueva(event.latLng.lat(), event.latLng.lng());
    });
    
    mapNuevaInicializado = true;
}

// Reverse Geocoding - Autocompletar campos
function reverseGeocodeNueva(lat, lng) {
    var latlng = { lat: parseFloat(lat), lng: parseFloat(lng) };
    
    geocoder.geocode({ location: latlng }, function(results, status) {
        if (status === 'OK' && results[0]) {
            var addressComponents = results[0].address_components;
            var formattedAddress = results[0].formatted_address;
            
            // Autocompletar campos (NO el nombre)
            var direccion = '';
            var ciudad = '';
            var estado = '';
            var pais = '';
            
            for (var i = 0; i < addressComponents.length; i++) {
                var component = addressComponents[i];
                var types = component.types;
                
                if (types.includes('street_number') || types.includes('route')) {
                    direccion += component.long_name + ' ';
                }
                if (types.includes('locality') || types.includes('administrative_area_level_2')) {
                    ciudad = component.long_name;
                }
                if (types.includes('administrative_area_level_1')) {
                    estado = component.long_name;
                }
                if (types.includes('country')) {
                    pais = component.long_name;
                }
            }
            
            // Actualizar campos (readonly)
            document.getElementById('direccionNueva').value = direccion.trim() || formattedAddress;
            document.getElementById('ciudadNueva').value = ciudad;
            document.getElementById('estadoNueva').value = estado;
            document.getElementById('paisNueva').value = pais;
            
            // Guardar coordenadas
            document.getElementById('latitudNueva').value = lat;
            document.getElementById('longitudNueva').value = lng;
            
            // Notificación visual
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Ubicación actualizada',
                showConfirmButton: false,
                timer: 2000
            });
        } else {
            console.error('Geocoder failed:', status);
        }
    });
}

// Inicializar mapa cuando se hace click en el tab de crear nueva parada
// ===============================================
// CALLBACK - Inicializar todos los mapas
// ===============================================
function initMapasRuta() {
    console.log('initMapasRuta callback ejecutado');
    
    // Esperar a que el DOM esté completamente cargado
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            inicializarMapas();
        });
    } else {
        inicializarMapas();
    }
}

function inicializarMapas() {
    // Inicializar mapa de recorrido si hay paradas
    if (typeof paradasRecorrido !== 'undefined' && paradasRecorrido.length > 0) {
        // Pequeño delay para asegurar que el DOM esté renderizado
        setTimeout(function() {
            initMapRecorrido();
        }, 100);
    }
    
    // Listener para tab de crear nueva parada
    $(document).ready(function() {
        $('a[href="#form-nueva"]').on('shown.bs.tab', function (e) {
            if (!mapNuevaInicializado) {
                initMapNueva();
            }
        });
    });
}

// ===============================================
// GOOGLE MAPS - MAPA DE RECORRIDO COMPLETO
// ===============================================
function initMapRecorrido() {
    console.log('=== Inicializando mapa de recorrido ===');
    
    if (typeof paradasRecorrido === 'undefined' || paradasRecorrido.length === 0) {
        console.error('ERROR: No hay paradas para mostrar en el mapa');
        return;
    }
    
    console.log('Paradas disponibles:', paradasRecorrido.length, paradasRecorrido);
    
    // Verificar que el contenedor exista
    var mapContainer = document.getElementById('mapRecorrido');
    if (!mapContainer) {
        console.error('ERROR: Contenedor mapRecorrido no encontrado');
        return;
    }
    
    console.log('Contenedor encontrado:', mapContainer);
    console.log('Dimensiones contenedor:', mapContainer.offsetWidth, 'x', mapContainer.offsetHeight);
    
    // Verificar que Google Maps esté cargado
    if (typeof google === 'undefined' || typeof google.maps === 'undefined') {
        console.error('ERROR: Google Maps no está cargado');
        return;
    }
    
    console.log('Google Maps API cargada correctamente');
    
    var initialLocation = { lat: -34.6037, lng: -58.3816 };
    
    console.log('Creando mapa con centro:', initialLocation);
    
    // Crear mapa
    try {
        var mapRecorrido = new google.maps.Map(document.getElementById('mapRecorrido'), {
            zoom: 6,
            center: initialLocation,
            mapTypeControl: true,
            streetViewControl: false
        });
        
        console.log('Mapa creado exitosamente');
    } catch (error) {
        console.error('ERROR al crear mapa:', error);
        return;
    }
    
    var bounds = new google.maps.LatLngBounds();
    var markers = [];
    var path = [];
    
    // Crear marcadores para cada parada
    paradasRecorrido.forEach(function(parada, index) {
        var lat = parada.latitud;
        var lng = parada.longitud;
        var position = { lat: lat, lng: lng };
        
        path.push(position);
        bounds.extend(position);
        
        // Determinar color del marcador según tipo
        var iconColor = '#6c757d'; // gris (intermedia)
        if (parada.es_origen && parada.es_destino) {
            iconColor = '#ffc107'; // amarillo (ambos)
        } else if (parada.es_origen) {
            iconColor = '#28a745'; // verde (origen)
        } else if (parada.es_destino) {
            iconColor = '#007bff'; // azul (destino)
        }
        
        // Crear marcador
        var marker = new google.maps.Marker({
            position: position,
            map: mapRecorrido,
            title: parada.nombre,
            label: {
                text: parada.orden.toString(),
                color: 'white',
                fontWeight: 'bold'
            },
            icon: {
                path: google.maps.SymbolPath.CIRCLE,
                fillColor: iconColor,
                fillOpacity: 1,
                strokeColor: 'white',
                strokeWeight: 2,
                scale: 12
            }
        });
        
        // InfoWindow con información de la parada
        var infoContent = '<div style="padding: 5px;">' +
            '<strong>' + parada.orden + '. ' + parada.nombre + '</strong><br>' +
            '<small>' + parada.ciudad + '</small><br>';
        
        if (parada.tiempo && parada.tiempo !== '0 días 0h 00min') {
            infoContent += '<small><i class="far fa-clock"></i> ' + parada.tiempo + '</small><br>';
        }
        
        var tipo = [];
        if (parada.es_origen) tipo.push('ORIGEN');
        if (parada.es_destino) tipo.push('DESTINO');
        if (tipo.length === 0) tipo.push('INTERMEDIA');
        
        infoContent += '<small><strong>Tipo:</strong> ' + tipo.join(' / ') + '</small>';
        infoContent += '</div>';
        
        var infoWindow = new google.maps.InfoWindow({
            content: infoContent
        });
        
        marker.addListener('click', function() {
            infoWindow.open(mapRecorrido, marker);
        });
        
        markers.push(marker);
    });
    
    // Dibujar línea de recorrido
    var routePath = new google.maps.Polyline({
        path: path,
        geodesic: true,
        strokeColor: '#FF0000',
        strokeOpacity: 0.7,
        strokeWeight: 3
    });
    
    routePath.setMap(mapRecorrido);
    
    // Ajustar zoom y centrado para mostrar todas las paradas
    mapRecorrido.fitBounds(bounds);
}
</script>

<!-- Google Maps Script - Cargar API DESPUÉS de definir todas las funciones -->
<?php
if (!defined('GOOGLE_MAPS_API_KEY')) {
    require_once(__DIR__ . '/../config/config.php');
}
?>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_MAPS_API_KEY; ?>&libraries=places&callback=initMapasRuta"></script>
