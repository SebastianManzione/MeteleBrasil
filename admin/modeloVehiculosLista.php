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

$modelos = getAllModelos();
$tiposTransporte = getAllTiposTransporte();
?>

<!-- Content Wrapper -->
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark"><i class="fas fa-car-side"></i> Modelos de Vehículos</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Transporte</a></li>
                        <li class="breadcrumb-item active">Modelos</li>
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
                            <h4 class="mb-0"><i class="fas fa-list"></i> Catálogo de Modelos Preconfigurados</h4>
                            <button class="btn btn-success btn-sm" onclick="mostrarFormularioNuevo()">
                                <i class="fas fa-plus"></i> Nuevo Modelo
                            </button>
                        </div>
                        <div class="card-body">
                            <!-- Filtros -->
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label>Filtrar por tipo:</label>
                                    <select id="filtroTipo" class="form-control">
                                        <option value="">Todos</option>
                                        <?php foreach ($tiposTransporte as $tipo): ?>
                                            <option value="<?=$tipo['idTipoTransporte']?>"><?=$tipo['nombre']?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label>Búsqueda:</label>
                                    <input type="text" id="busqueda" class="form-control" placeholder="Nombre del modelo...">
                                </div>
                                <div class="col-md-4">
                                    <label>&nbsp;</label>
                                    <button class="btn btn-secondary btn-sm btn-block" onclick="limpiarFiltros()">
                                        <i class="fas fa-eraser"></i> Limpiar
                                    </button>
                                </div>
                            </div>

                            <!-- Tabla de Modelos -->
                            <div class="table-responsive">
                                <table id="tablaModelos" class="table table-bordered table-striped table-hover">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>ID</th>
                                            <th>Nombre</th>
                                            <th>Tipo</th>
                                            <th>Capacidad</th>
                                            <th>Filas x Columnas</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($modelos as $modelo): ?>
                                            <tr id="fila-<?=$modelo['idModelo']?>">
                                                <td><?=$modelo['idModelo']?></td>
                                                <td>
                                                    <strong><?=$modelo['nombre']?></strong>
                                                    <br><small class="text-muted"><?=$modelo['descripcion']?></small>
                                                </td>
                                                <td>
                                                    <span class="badge badge-info"><?=$modelo['tipo_nombre']?></span>
                                                </td>
                                                <td>
                                                    <?php
                                                        // Calcular capacidad real desde el mapa de asientos
                                                        $capacidadBD = intval($modelo['capacidad_total']);
                                                        $distribucion = json_decode($modelo['distribucion_json'], true);
                                                        $capacidadReal = 0;
                                                        
                                                        if (is_array($distribucion) && isset($distribucion['pisos'])) {
                                                            foreach ($distribucion['pisos'] as $piso) {
                                                                if (isset($piso['asientos']) && is_array($piso['asientos'])) {
                                                                    foreach ($piso['asientos'] as $fila) {
                                                                        if (is_array($fila)) {
                                                                            foreach ($fila as $celda) {
                                                                                if ($celda === 1) {
                                                                                    $capacidadReal++;
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                        
                                                        $coincide = ($capacidadBD === $capacidadReal);
                                                    ?>
                                                    <strong><?=$capacidadReal?></strong> pasajeros
                                                    <?php if (!$coincide && $capacidadReal > 0): ?>
                                                        <br><small class="badge badge-warning" title="BD tiene: <?=$capacidadBD?>, Mapa tiene: <?=$capacidadReal?>">
                                                            <i class="fas fa-exclamation-triangle"></i> Desincronizado (BD: <?=$capacidadBD?>)
                                                        </small>
                                                    <?php elseif ($capacidadReal === 0 && $capacidadBD > 0): ?>
                                                        <br><small class="badge badge-danger" title="Sin mapa de asientos">
                                                            <i class="fas fa-map"></i> Sin mapa
                                                        </small>
                                                    <?php else: ?>
                                                        <br><small class="badge badge-success">
                                                            <i class="fas fa-check"></i> Sincronizado
                                                        </small>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?=$modelo['filas']?> filas × <?=$modelo['columnas']?> columnas
                                                    <?php if (intval($modelo['filas']) * intval($modelo['columnas']) !== $capacidadReal && $capacidadReal > 0): ?>
                                                        <br><small class="text-muted">(Teórica: <?=intval($modelo['filas']) * intval($modelo['columnas'])?> | Real: <?=$capacidadReal?>)</small>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($modelo['habilitado']): ?>
                                                        <span class="badge badge-success">Activo</span>
                                                    <?php else: ?>
                                                        <span class="badge badge-danger">Inactivo</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <button class="btn btn-xs btn-primary" onclick="editarModelo(<?=$modelo['idModelo']?>)" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-xs btn-info" onclick="verMapa(<?=$modelo['idModelo']?>)" title="Ver Mapa">
                                                        <i class="fas fa-th"></i>
                                                    </button>
                                                    <button class="btn btn-xs btn-warning" onclick="toggleEstado(<?=$modelo['idModelo']?>, <?=$modelo['habilitado']?>)" title="Toggle">
                                                        <i class="fas fa-toggle-<?=$modelo['habilitado'] ? 'on' : 'off'?>"></i>
                                                    </button>
                                                    <?php if (!$coincide && $capacidadReal > 0): ?>
                                                        <button class="btn btn-xs btn-success" onclick="sincronizarCapacidad(<?=$modelo['idModelo']?>, <?=$capacidadReal?>)" title="Sincronizar capacidad desde mapa">
                                                            <i class="fas fa-sync-alt"></i> Sync
                                                        </button>
                                                    <?php endif; ?>
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

<!-- Modal: Nuevo/Editar Modelo -->
<div class="modal fade" id="modalModelo" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="tituloModal">Nuevo Modelo</h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formModelo">
                    <input type="hidden" id="idModelo" name="idModelo" value="">
                    
                    <div class="form-row">
                        <div class="form-group col-md-8">
                            <label>Nombre del modelo:</label>
                            <input type="text" id="nombre" name="nombre" class="form-control" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Tipo:</label>
                            <select id="tipo_transporte" name="tipo_transporte" class="form-control" required>
                                <option value="">Seleccionar...</option>
                                <?php foreach ($tiposTransporte as $tipo): ?>
                                    <option value="<?=$tipo['idTipoTransporte']?>"><?=$tipo['nombre']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Filas:</label>
                            <input type="number" id="filas" name="filas" class="form-control" min="1" max="50" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Columnas:</label>
                            <input type="number" id="columnas" name="columnas" class="form-control" min="1" max="20" required>
                        </div>
                    </div>

                    <div class="form-group d-none">
                        <label>Distribución (JSON):</label>
                        <textarea id="distribucion_json" name="distribucion_json" class="form-control" rows="3" placeholder='{"filas":10,"columnas":4}'></textarea>
                    </div>

                    <div class="form-group">
                        <label>Descripción:</label>
                        <textarea id="descripcion" name="descripcion" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" id="habilitado" name="habilitado" class="form-check-input" value="1" checked>
                        <label class="form-check-label" for="habilitado">Habilitado</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardarModelo()">
                    <i class="fas fa-save"></i> Guardar
                </button>
                <button type="button" class="btn btn-info" onclick="previewMapaFormulario()">
                    <i class="fas fa-th"></i> Previsualizar mapa
                </button>
                <button type="button" class="btn btn-warning" onclick="abrirEditorVisual()">
                    <i class="fas fa-th-large"></i> Editor visual
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Editor Visual de Asientos -->
<div class="modal fade" id="modalEditorVisual" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title"><i class="fas fa-th-large"></i> Editor visual de asientos</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="form-check mr-3 mb-0">
                        <input type="checkbox" class="form-check-input" id="editor_doblepiso">
                        <label class="form-check-label" for="editor_doblepiso">Doble piso</label>
                    </div>
                    <button class="btn btn-sm btn-secondary" onclick="generarGridEditor(); return false;">
                        <i class="fas fa-sync"></i> Regenerar todo
                    </button>
                </div>
                <ul class="nav nav-tabs" id="tabsPisos" role="tablist" style="display:none;">
                    <li class="nav-item">
                        <a class="nav-link active" id="piso1-tab" data-toggle="tab" href="#piso1" role="tab">Piso 1</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="piso2-tab" data-toggle="tab" href="#piso2" role="tab">Piso 2</a>
                    </li>
                </ul>
                <div class="tab-content mt-3" id="contenidoPisos">
                    <div class="tab-pane fade show active" id="piso1" role="tabpanel">
                        <div class="d-flex align-items-end mb-2 flex-wrap">
                            <div class="mr-3">
                                <label class="mb-0">Filas piso 1</label>
                                <input type="number" id="editor_filas1" class="form-control" style="width:110px" min="1" value="10">
                            </div>
                            <div class="mr-3">
                                <label class="mb-0">Columnas piso 1</label>
                                <input type="number" id="editor_columnas1" class="form-control" style="width:110px" min="1" value="4">
                            </div>
                            <div class="mr-3">
                                <label class="mb-0">Letra inicial 1</label>
                                <input type="text" id="editor_letra1" class="form-control" style="width:80px" maxlength="1" value="A" placeholder="A">
                            </div>
                            <div class="mr-3">
                                <label class="mb-0">Número inicial 1</label>
                                <input type="number" id="editor_numero1" class="form-control" style="width:100px" value="1" min="1">
                            </div>
                            <div class="mr-3">
                                <label class="mb-0">Columnas pasillo 1</label>
                                <select id="editor_pasillo_col1" class="form-control" style="width:180px" multiple></select>
                            </div>
                            <button class="btn btn-sm btn-outline-secondary mt-4" onclick="regenerarPiso(0); return false;">
                                <i class="fas fa-sync"></i> Aplicar piso 1
                            </button>
                            <button class="btn btn-sm btn-outline-dark mt-4 ml-2" onclick="marcarPasilloColumna(0); return false;">
                                <i class="fas fa-grip-lines-vertical"></i> Marcar pasillo
                            </button>
                            <button class="btn btn-sm btn-outline-dark mt-4 ml-2" onclick="limpiarPasilloColumna(0); return false;">
                                <i class="fas fa-times"></i> Limpiar pasillo
                            </button>
                        </div>
                        <div id="gridPiso1" class="grid-container"></div>
                    </div>
                    <div class="tab-pane fade" id="piso2" role="tabpanel">
                        <div class="d-flex align-items-end mb-2 flex-wrap">
                            <div class="mr-3">
                                <label class="mb-0">Filas piso 2</label>
                                <input type="number" id="editor_filas2" class="form-control" style="width:110px" min="1" value="10">
                            </div>
                            <div class="mr-3">
                                <label class="mb-0">Columnas piso 2</label>
                                <input type="number" id="editor_columnas2" class="form-control" style="width:110px" min="1" value="4">
                            </div>
                            <div class="mr-3">
                                <label class="mb-0">Letra inicial 2</label>
                                <input type="text" id="editor_letra2" class="form-control" style="width:80px" maxlength="1" value="A" placeholder="A">
                            </div>
                            <div class="mr-3">
                                <label class="mb-0">Número inicial 2</label>
                                <input type="number" id="editor_numero2" class="form-control" style="width:100px" value="1" min="1">
                            </div>
                            <div class="mr-3">
                                <label class="mb-0">Columnas pasillo 2</label>
                                <select id="editor_pasillo_col2" class="form-control" style="width:180px" multiple></select>
                            </div>
                            <button class="btn btn-sm btn-outline-secondary mt-4" onclick="regenerarPiso(1); return false;">
                                <i class="fas fa-sync"></i> Aplicar piso 2
                            </button>
                            <button class="btn btn-sm btn-outline-dark mt-4 ml-2" onclick="marcarPasilloColumna(1); return false;">
                                <i class="fas fa-grip-lines-vertical"></i> Marcar pasillo
                            </button>
                            <button class="btn btn-sm btn-outline-dark mt-4 ml-2" onclick="limpiarPasilloColumna(1); return false;">
                                <i class="fas fa-times"></i> Limpiar pasillo
                            </button>
                        </div>
                        <div id="gridPiso2" class="grid-container"></div>
                    </div>
                </div>
                <div class="mt-3 mb-3">
                    <label><strong>Tipo de celda a asignar:</strong></label>
                    <div class="btn-group btn-group-sm mb-2 d-flex flex-wrap" role="group">
                        <button type="button" class="btn btn-outline-success active" onclick="setTipoCelda(1)" title="Asiento">✓ Asiento</button>
                        <button type="button" class="btn btn-outline-secondary" onclick="setTipoCelda(0)" title="Vacío">∅ Vacío</button>
                        <button type="button" class="btn btn-outline-warning" onclick="setTipoCelda('B')" title="Baño">🚻 Baño</button>
                        <button type="button" class="btn btn-outline-dark" onclick="setTipoCelda('P')" title="Pasillo">≡ Pasillo</button>
                        <button type="button" class="btn btn-outline-info" onclick="setTipoCelda('C')" title="Cafetera">☕ Cafetera</button>
                        <button type="button" class="btn btn-outline-danger" onclick="setTipoCelda('E')" title="Escalera">⬆ Escalera</button>
                        <button type="button" class="btn btn-outline-warning" onclick="setTipoCelda('W')" title="Panorámico">🪟 Panorámico</button>
                        <button type="button" class="btn btn-outline-success" onclick="setTipoCelda('D')" title="Cama">🛏 Cama</button>
                        <button type="button" class="btn btn-outline-success" onclick="setTipoCelda('S')" title="Semicama">⊔ Semicama</button>
                        <button type="button" class="btn btn-outline-brown" onclick="setTipoCelda('F')" title="Cerca cafetera">F Cerca cafetera</button>
                        <button type="button" class="btn btn-outline-primary" onclick="setTipoCelda('T')" title="TV">📺 TV</button>
                        <button type="button" class="btn btn-outline-danger" onclick="setTipoCelda('X')" title="Puerta">🚪 Puerta</button>
                        <button type="button" class="btn btn-outline-warning" onclick="setTipoCelda('K')" title="Cocina">🍳 Cocina</button>
                        <button type="button" class="btn btn-outline-dark" onclick="setTipoCelda('Y')" title="Volante">🎡 Volante</button>
                        <button type="button" class="btn btn-outline-secondary" onclick="setTipoCelda('G')" title="Parabrisas">🪟 Parabrisas</button>
                    </div>
                </div>
                <div class="mt-2">
                    <span class="badge badge-success">✓ Asiento</span>
                    <span class="badge badge-secondary">∅ Vacío</span>
                    <span class="badge badge-warning text-dark">🚻 Baño</span>
                    <span class="badge badge-dark">≡ Pasillo</span>
                    <span class="badge" style="background-color:#8b6f47; color:#fff;">☕ Cafetera</span>
                    <span class="badge badge-danger">⬆ Escalera</span>
                    <span class="badge badge-warning">🪟 Panorámico</span>
                    <span class="badge badge-success">🛏 Cama</span>
                    <span class="badge badge-success">⊔ Semicama</span>
                    <span class="badge" style="background-color:#8b6f47; color:#fff;">F Cerca cafetera</span>
                    <span class="badge badge-primary">📺 TV</span>
                    <span class="badge badge-danger">🚪 Puerta</span>
                    <span class="badge badge-warning">🍳 Cocina</span>
                    <span class="badge badge-dark">🎡 Volante</span>
                    <span class="badge badge-secondary">🪟 Parabrisas</span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="guardarEditorVisual()">
                    <i class="fas fa-save"></i> Usar en el modelo
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Mapa de Asientos (Preview) -->
<div class="modal fade" id="modalMapa" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Mapa de Asientos</h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="contenidoMapa" class="text-center">
                    <!-- Se genera dinámicamente -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<?php
require_once "includes/footer.php";
?>

<style>
.grid-container {
    display: grid;
    grid-gap: 6px;
    grid-auto-rows: 44px;
    justify-content: start;
}

.grid-seat {
    border: 1px solid #ced4da;
    border-radius: 4px;
    background: #f8f9fa;
    color: #6c757d;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.15s ease;
    user-select: none;
}

.grid-seat.active {
    background: #17a2b8;
    color: #fff;
}

.grid-seat.inactive {
    background: #e9ecef;
    color: #6c757d;
    text-decoration: line-through;
}

.grid-seat.bathroom {
    background: #ffc107;
    color: #212529;
    font-weight: 700;
}

.grid-seat.aisle {
    background: #6c757d;
    color: #ffffff;
    font-weight: 700;
}

.grid-seat.cafeteria {
    background: #8b6f47;
    color: #ffffff;
    font-weight: 700;
}

.grid-seat.stairs {
    background: #dc3545;
    color: #ffffff;
    font-weight: 700;
}

.grid-seat.window {
    background: #fd7e14;
    color: #ffffff;
    font-weight: 700;
}

.grid-seat.bed {
    background: #28a745;
    color: #ffffff;
    font-weight: 700;
}

.grid-seat.semibed {
    background: #5cb85c;
    color: #ffffff;
    font-weight: 700;
}

.grid-seat.near-cafe {
    background: #a68873;
    color: #ffffff;
    font-weight: 700;
}

.grid-seat.tv {
    background: #0275d8;
    color: #ffffff;
    font-weight: 700;
}

.grid-seat.door {
    background: #e83e8c;
    color: #ffffff;
    font-weight: 700;
}

.grid-seat.kitchen {
    background: #fd7e14;
    color: #ffffff;
    font-weight: 700;
}

.grid-seat.steering {
    background: #212529;
    color: #ffcc00;
    font-weight: 700;
}

.grid-seat.windshield {
    background: #87ceeb;
    color: #ffffff;
    font-weight: 700;
}

.grid-seat:hover {
    transform: scale(1.05);
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>

// ============================================
// FUNCIONES
// ============================================

const letras = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'.split('');
let editorState = null;

function safeParseDistribucion(str) {
    if (!str) return null;
    try {
        return typeof str === 'string' ? JSON.parse(str) : str;
    } catch (e) {
        return null;
    }
}

function normalizarValorCelda(valorPrevio) {
    if (valorPrevio === 'B' || valorPrevio === 'b') return 'B';
    if (valorPrevio === 'P' || valorPrevio === 'p' || valorPrevio === 'A' || valorPrevio === 'a') return 'P';
    if (valorPrevio === 'C' || valorPrevio === 'c') return 'C';
    if (valorPrevio === 'E' || valorPrevio === 'e') return 'E';
    if (valorPrevio === 'W' || valorPrevio === 'w') return 'W';
    if (valorPrevio === 'D' || valorPrevio === 'd') return 'D';
    if (valorPrevio === 'S' || valorPrevio === 's') return 'S';
    if (valorPrevio === 'F' || valorPrevio === 'f') return 'F';
    if (valorPrevio === 'T' || valorPrevio === 't') return 'T';
    if (valorPrevio === 'X' || valorPrevio === 'x') return 'X';
    if (valorPrevio === 'K' || valorPrevio === 'k') return 'K';
    if (valorPrevio === 'Y' || valorPrevio === 'y') return 'Y';
    if (valorPrevio === 'G' || valorPrevio === 'g') return 'G';
    if (valorPrevio === 0 || valorPrevio === '0') return 0;
    return 1; // default asiento activo
}

function crearMatrizFilas(rows, cols, original) {
    const filas = Math.max(1, rows);
    const columnas = Math.max(1, cols);
    const matriz = [];
    for (let r = 0; r < filas; r++) {
        const fila = [];
        for (let c = 0; c < columnas; c++) {
            const valorPrevio = original && original[r] && typeof original[r][c] !== 'undefined' ? original[r][c] : 1;
            fila.push(normalizarValorCelda(valorPrevio));
        }
        matriz.push(fila);
    }
    return matriz;
}

function normalizarState(state) {
    // Soporta formato viejo (filas/columnas globales) y nuevo (por piso)
    const tienePisos = Array.isArray(state?.pisos) && state.pisos.length > 0;
    const doble = !!state?.doblePiso || (tienePisos && state.pisos.length > 1);

    // Nuevo: siempre intentar normalizar por piso.
    if (tienePisos) {
        const pisosNormalizados = state.pisos.map((p, idx) => {
            // Si no vienen filas/columnas explícitas, inferir desde la matriz de asientos
            const inferFilas = Array.isArray(p?.asientos) ? p.asientos.length : 0;
            const inferCols = Array.isArray(p?.asientos) && Array.isArray(p.asientos[0]) ? p.asientos[0].length : 0;
            const filasP = Math.max(1, parseInt(p?.filas, 10) || inferFilas || 0);
            const colsP = Math.max(1, parseInt(p?.columnas, 10) || inferCols || 0);
            return {
                nombre: p?.nombre || `Piso ${idx + 1}`,
                filas: filasP,
                columnas: colsP,
                letraInicial: p?.letraInicial || 'A',
                numeroInicial: p?.numeroInicial || 1,
                asientos: crearMatrizFilas(filasP, colsP, p?.asientos)
            };
        });
        const totalFilas = pisosNormalizados.reduce((acc, p) => acc + p.filas, 0);
        const maxCols = pisosNormalizados.reduce((acc, p) => Math.max(acc, p.columnas), 0);
        return {
            filas: totalFilas,
            columnas: maxCols,
            doblePiso: doble,
            pisos: pisosNormalizados
        };
    }

    const filasGlobal = Math.max(1, parseInt(state?.filas, 10) || 0);
    const columnasGlobal = Math.max(1, parseInt(state?.columnas, 10) || 0);
    const filasPiso1 = doble ? Math.max(1, Math.ceil(filasGlobal / 2)) : filasGlobal;
    const filasPiso2 = doble ? Math.max(1, filasGlobal - filasPiso1) : 0;
    const matriz1 = tienePisos && state.pisos[0] ? state.pisos[0].asientos : null;
    const matriz2 = tienePisos && state.pisos[1] ? state.pisos[1].asientos : null;
    const pisos = [];
    pisos.push({ nombre: 'Piso 1', filas: filasPiso1, columnas: columnasGlobal, letraInicial: state?.pisos?.[0]?.letraInicial || 'A', numeroInicial: state?.pisos?.[0]?.numeroInicial || 1, asientos: crearMatrizFilas(filasPiso1, columnasGlobal, matriz1) });
    if (doble) {
        pisos.push({ nombre: 'Piso 2', filas: filasPiso2, columnas: columnasGlobal, letraInicial: state?.pisos?.[1]?.letraInicial || 'A', numeroInicial: state?.pisos?.[1]?.numeroInicial || 1, asientos: crearMatrizFilas(filasPiso2, columnasGlobal, matriz2) });
    }
    return {
        filas: filasGlobal,
        columnas: columnasGlobal,
        doblePiso: doble,
        pisos
    };
}

function crearStateBase(filas1, columnas1, doble, filas2 = null, columnas2 = null) {
    const f1 = Math.max(1, parseInt(filas1, 10) || 0);
    const c1 = Math.max(1, parseInt(columnas1, 10) || 0);
    const f2 = doble ? Math.max(1, parseInt(filas2 ?? filas1, 10) || 0) : 0;
    const c2 = doble ? Math.max(1, parseInt(columnas2 ?? columnas1, 10) || 0) : 0;
    const pisos = [];
    pisos.push({ nombre: 'Piso 1', filas: f1, columnas: c1, letraInicial: 'A', numeroInicial: 1, asientos: crearMatrizFilas(f1, c1) });
    if (doble) {
        pisos.push({ nombre: 'Piso 2', filas: f2, columnas: c2, letraInicial: 'A', numeroInicial: 1, asientos: crearMatrizFilas(f2, c2) });
    }
    return {
        filas: f1 + (doble ? f2 : 0),
        columnas: Math.max(c1, doble ? c2 : c1),
        doblePiso: !!doble,
        pisos
    };
}

function buildStateFromModelo(modeloBase) {
    const dist = safeParseDistribucion(modeloBase.distribucion_json || modeloBase.distribucion);
    if (dist) {
        const normalizada = normalizarState(dist);
        if (normalizada) return normalizada;
    }
    const filas = parseInt(modeloBase.filas, 10) || 0;
    const columnas = parseInt(modeloBase.columnas, 10) || 0;
    if (!filas || !columnas) return null;
    const heuristicaDoble = (modeloBase.nombre || '').toLowerCase().includes('doble') || (parseInt(modeloBase.capacidad_total, 10) || 0) > 45 || filas >= 10;
    const filas2 = heuristicaDoble ? Math.max(1, Math.floor(filas / 2)) : null;
    return crearStateBase(filas, columnas, heuristicaDoble, filas2, columnas);
}

function contarAsientosActivos(state) {
    if (!state || !Array.isArray(state.pisos)) return 0;
    let total = 0;
    state.pisos.forEach(piso => {
        if (piso && Array.isArray(piso.asientos)) {
            piso.asientos.forEach(fila => fila.forEach(celda => { if (celda === 1) total++; }));
        }
    });
    return total;
}

function renderGrid(containerId, matrix, columnas, startRowIndex, pisoIndex, letraInicial = 'A', numeroInicial = 1) {
    const contenedor = $('#' + containerId);
    contenedor.empty();
        contenedor.css('grid-template-columns', `repeat(${columnas}, 48px)`);
    const letraCode = letraInicial.toUpperCase().charCodeAt(0) - 65; // A=0, B=1, etc.
    matrix.forEach((fila, idxFila) => {
        fila.forEach((celda, idxCol) => {
            const letraIdx = (letraCode + startRowIndex + idxFila) % letras.length;
            const label = letras[letraIdx] + (numeroInicial + idxCol);
            const seat = $('<div class="grid-seat"></div>');
            if (celda === 0) {
                seat.addClass('inactive');
                seat.text(label);
            } else if (celda === 'B') {
                seat.addClass('bathroom');
                seat.html('<i class="fas fa-restroom"></i>');
            } else if (celda === 'P') {
                seat.addClass('aisle');
                seat.html('<i class="fas fa-grip-lines-vertical"></i>');
            } else if (celda === 'C') {
                seat.addClass('cafeteria');
                seat.html('<i class="fas fa-coffee"></i>');
            } else if (celda === 'E') {
                seat.addClass('stairs');
                seat.html('<i class="fas fa-arrow-up"></i>');
            } else if (celda === 'W') {
                seat.addClass('window');
                seat.html('<i class="fas fa-window-maximize"></i>');
            } else if (celda === 'T') {
                seat.addClass('tv');
                seat.html('<i class="fas fa-tv"></i>');
            } else if (celda === 'X') {
                seat.addClass('door');
                seat.html('<i class="fas fa-door-open"></i>');
            } else if (celda === 'K') {
                seat.addClass('kitchen');
                seat.html('<i class="fas fa-utensils"></i>');
            } else if (celda === 'Y') {
                seat.addClass('steering');
                seat.html('<i class="fas fa-steering-wheel"></i>');
            } else if (celda === 'G') {
                seat.addClass('windshield');
                seat.html('<i class="fas fa-eye"></i>');
            } else if (celda === 'D') {
                seat.addClass('bed');
                seat.html('<i class="fas fa-bed"></i>');
            } else if (celda === 'S') {
                seat.addClass('semibed');
                seat.html('<i class="fas fa-bed"></i>');
            } else if (celda === 'F') {
                seat.addClass('near-cafe');
                seat.text('F');
            } else if (celda === 1) {
                seat.addClass('active');
                seat.text(label);
            } else {
                seat.text(label);
            }
            seat.on('click', () => toggleSeat(pisoIndex, idxFila, idxCol));
            contenedor.append(seat);
        });
    });
}

function renderEditorFromState() {
    if (!editorState) return;
    // Preservar la pestaña activa antes de modificar clases
    const wasPiso2Active = $('#piso2-tab').hasClass('active') || $('#piso2').hasClass('show active');

    $('#editor_filas').val(editorState.filas);
    $('#editor_columnas').val(editorState.columnas);
    $('#editor_doblepiso').prop('checked', editorState.doblePiso);

    if (editorState.doblePiso && editorState.pisos[1]) {
        $('#tabsPisos').show();
        $('#piso2-tab').show().removeClass('d-none');
        // Restaurar pestaña activa según estado anterior
        if (wasPiso2Active) {
            $('#piso1-tab').removeClass('active');
            $('#piso2-tab').addClass('active');
            $('#piso1').removeClass('show active');
            $('#piso2').addClass('show active');
        } else {
            $('#piso1-tab').addClass('active');
            $('#piso2-tab').removeClass('active');
            $('#piso1').addClass('show active');
            $('#piso2').removeClass('show active');
        }
    } else {
        $('#tabsPisos').hide();
        $('#piso1-tab').addClass('active');
        $('#piso2-tab').removeClass('active');
        $('#piso1').addClass('show active');
        $('#piso2').removeClass('show active');
    }

    let inicio = 0;
    // Usar columnas del piso específico para el grid
    const colsPiso1 = editorState.pisos[0].columnas || (editorState.pisos[0].asientos[0] ? editorState.pisos[0].asientos[0].length : editorState.columnas);
    const letraPiso1 = ($('#editor_letra1').val() || 'A').toUpperCase();
    const numeroPiso1 = parseInt($('#editor_numero1').val() || 1, 10);
    renderGrid('gridPiso1', editorState.pisos[0].asientos, colsPiso1, inicio, 0, letraPiso1, numeroPiso1);
    // Rellenar selects de pasillo con cantidad de columnas
    const cols1 = editorState.pisos[0].columnas || (editorState.pisos[0].asientos[0] ? editorState.pisos[0].asientos[0].length : editorState.columnas);
    const sel1 = $('#editor_pasillo_col1');
    sel1.empty();
    for (let c = 1; c <= cols1; c++) sel1.append('<option value="'+c+'">Col '+c+'</option>');
    // Preseleccionar columnas que sean completamente pasillo
    const pasillos1 = [];
    for (let c = 0; c < cols1; c++) {
        let esPasillo = true;
        for (let r = 0; r < editorState.pisos[0].asientos.length; r++) {
            if (editorState.pisos[0].asientos[r][c] !== 'P') { esPasillo = false; break; }
        }
        if (esPasillo) pasillos1.push(String(c+1));
    }
    sel1.val(pasillos1);

    inicio += editorState.pisos[0].asientos.length;
    if (editorState.doblePiso && editorState.pisos[1]) {
        const colsPiso2 = editorState.pisos[1].columnas || (editorState.pisos[1].asientos[0] ? editorState.pisos[1].asientos[0].length : editorState.columnas);
        const letraPiso2 = ($('#editor_letra2').val() || 'A').toUpperCase();
        const numeroPiso2 = parseInt($('#editor_numero2').val() || 1, 10);
        renderGrid('gridPiso2', editorState.pisos[1].asientos, colsPiso2, inicio, 1, letraPiso2, numeroPiso2);
        const cols2 = colsPiso2;
        const sel2 = $('#editor_pasillo_col2');
        sel2.empty();
        for (let c = 1; c <= cols2; c++) sel2.append('<option value="'+c+'">Col '+c+'</option>');
        const pasillos2 = [];
        for (let c = 0; c < cols2; c++) {
            let esPasillo = true;
            for (let r = 0; r < editorState.pisos[1].asientos.length; r++) {
                if (editorState.pisos[1].asientos[r][c] !== 'P') { esPasillo = false; break; }
            }
            if (esPasillo) pasillos2.push(String(c+1));
        }
        sel2.val(pasillos2);
    } else {
        $('#gridPiso2').empty();
        $('#editor_pasillo_col2').empty();
    }
}

let tipoCeldaSeleccionado = 1;

function setTipoCelda(tipo) {
    tipoCeldaSeleccionado = tipo;
    $('.btn-group button').removeClass('active');
    $('button[onclick="setTipoCelda(' + (typeof tipo === 'string' ? "'" + tipo + "'" : tipo) + ')"]').addClass('active');
}

function toggleSeat(pisoIndex, filaIndex, colIndex) {
    if (!editorState || !editorState.pisos[pisoIndex]) return;
    const matriz = editorState.pisos[pisoIndex].asientos;
    matriz[filaIndex] = Array.isArray(matriz[filaIndex]) ? matriz[filaIndex].slice() : [];
    matriz[filaIndex][colIndex] = tipoCeldaSeleccionado;
    renderEditorFromState();
}

function generarGridEditor() {
    const doble = $('#editor_doblepiso').is(':checked');
    const filas1 = Math.max(1, parseInt($('#editor_filas1').val(), 10) || 1);
    const cols1 = Math.max(1, parseInt($('#editor_columnas1').val(), 10) || 1);
    const filas2 = doble ? Math.max(1, parseInt($('#editor_filas2').val(), 10) || filas1) : 0;
    const cols2 = doble ? Math.max(1, parseInt($('#editor_columnas2').val(), 10) || cols1) : 0;

    const prevPiso1 = editorState && editorState.pisos && editorState.pisos[0] ? editorState.pisos[0].asientos : null;
    const prevPiso2 = editorState && editorState.pisos && editorState.pisos[1] ? editorState.pisos[1].asientos : null;

    editorState = {
        filas: filas1 + (doble ? filas2 : 0),
        columnas: Math.max(cols1, doble ? cols2 : cols1),
        doblePiso: doble,
        pisos: []
    };
    editorState.pisos.push({ nombre: 'Piso 1', filas: filas1, columnas: cols1, asientos: crearMatrizFilas(filas1, cols1, prevPiso1) });
    if (doble) {
        editorState.pisos.push({ nombre: 'Piso 2', filas: filas2, columnas: cols2, asientos: crearMatrizFilas(filas2, cols2, prevPiso2) });
    }

    renderEditorFromState();
}

function regenerarPiso(idx) {
    if (!editorState) {
        generarGridEditor();
        return;
    }
    const isPiso2 = idx === 1;
    const doble = $('#editor_doblepiso').is(':checked');
    if (isPiso2 && !doble) {
        $('#editor_doblepiso').prop('checked', true);
    }
    const filas = Math.max(1, parseInt($('#editor_filas' + (idx + 1)).val(), 10) || 1);
    const cols = Math.max(1, parseInt($('#editor_columnas' + (idx + 1)).val(), 10) || 1);
    const prev = editorState.pisos[idx] ? editorState.pisos[idx].asientos : null;
    if (!editorState.pisos[idx]) {
        editorState.pisos[idx] = { nombre: 'Piso ' + (idx + 1), filas, columnas: cols, asientos: crearMatrizFilas(filas, cols, prev) };
    } else {
        editorState.pisos[idx].filas = filas;
        editorState.pisos[idx].columnas = cols;
        editorState.pisos[idx].asientos = crearMatrizFilas(filas, cols, prev);
    }
    if (idx === 1 && !editorState.doblePiso) {
        editorState.doblePiso = true;
    }
    editorState.filas = editorState.pisos.reduce((acc, p) => acc + p.filas, 0);
    editorState.columnas = editorState.pisos.reduce((acc, p) => Math.max(acc, p.columnas), 0);
    renderEditorFromState();
}

function abrirEditorVisual() {
    const dist = editorState || safeParseDistribucion($('#distribucion_json').val());
    const filasForm = parseInt($('#filas').val(), 10) || 10;
    const columnasForm = parseInt($('#columnas').val(), 10) || 4;
    const heuristicaDoble = ($('#nombre').val() || '').toLowerCase().includes('doble');
    editorState = dist ? normalizarState(dist) : crearStateBase(filasForm, columnasForm, heuristicaDoble);
    // Inicializar letra y número en campos de entrada
    $('#editor_letra1').val('A');
    $('#editor_numero1').val('1');
    $('#editor_letra2').val('A');
    $('#editor_numero2').val('1');
    renderEditorFromState();
    $('#modalEditorVisual').modal('show');
}

function guardarEditorVisual() {
    if (!editorState) return;
    
    // Guardar letra y número iniciales en el estado
    editorState.pisos[0].letraInicial = ($('#editor_letra1').val() || 'A').toUpperCase();
    editorState.pisos[0].numeroInicial = parseInt($('#editor_numero1').val() || 1, 10);
    if (editorState.pisos[1]) {
        editorState.pisos[1].letraInicial = ($('#editor_letra2').val() || 'A').toUpperCase();
        editorState.pisos[1].numeroInicial = parseInt($('#editor_numero2').val() || 1, 10);
    }
    
    $('#distribucion_json').val(JSON.stringify(editorState));
    $('#filas').val(editorState.pisos.reduce((acc, p) => acc + (p.filas || p.asientos.length), 0));
    $('#columnas').val(editorState.pisos.reduce((acc, p) => Math.max(acc, p.columnas || (p.asientos[0] ? p.asientos[0].length : 0)), 0));
    $('#modalEditorVisual').modal('hide');
    
    // Guardar directamente en BD
    guardarModelo();
}

function mostrarFormularioNuevo() {
    editorState = null;
    $('#idModelo').val('');
    $('#formModelo')[0].reset();
    $('#distribucion_json').val('');
    $('#tituloModal').text('Nuevo Modelo');
    $('#editor_filas1').val(10);
    $('#editor_columnas1').val(4);
    $('#editor_filas2').val(10);
    $('#editor_columnas2').val(4);
    $('#modalModelo').modal('show');
}

function marcarPasilloColumna(idx) {
    if (!editorState || !editorState.pisos[idx]) return;
    const selectId = idx === 0 ? '#editor_pasillo_col1' : '#editor_pasillo_col2';
    const colsSel = $(selectId).val() || [];
    if (!colsSel.length) return;
    // Clonar matriz para evitar referencias compartidas entre pisos
    const matriz = editorState.pisos[idx].asientos = editorState.pisos[idx].asientos.map(row => row.slice());
    colsSel.forEach(val => {
        const colIndex = parseInt(val, 10) - 1;
        if (isNaN(colIndex)) return;
        for (let r = 0; r < matriz.length; r++) {
            matriz[r][colIndex] = 'P';
        }
    });
    renderEditorFromState();
}

function limpiarPasilloColumna(idx) {
    if (!editorState || !editorState.pisos[idx]) return;
    const selectId = idx === 0 ? '#editor_pasillo_col1' : '#editor_pasillo_col2';
    const colsSel = $(selectId).val() || [];
    if (!colsSel.length) return;
    // Clonar matriz para evitar referencias compartidas entre pisos
    const matriz = editorState.pisos[idx].asientos = editorState.pisos[idx].asientos.map(row => row.slice());
    colsSel.forEach(val => {
        const colIndex = parseInt(val, 10) - 1;
        if (isNaN(colIndex)) return;
        for (let r = 0; r < matriz.length; r++) {
            if (matriz[r][colIndex] === 'P') {
                matriz[r][colIndex] = 0;
            }
        }
    });
    renderEditorFromState();
}

function editarModelo(idModelo) {
    $.get('ctrl/ctrlModelosVehiculos.php', { action: 'getModelo', idModelo: idModelo }, function(response) {
        if (response.success && response.data) {
            const m = response.data;
            const dist = safeParseDistribucion(m.distribucion_json);
            editorState = dist ? normalizarState(dist) : null;
            if (!editorState) {
                const heuristicaDoble = (m.nombre || '').toLowerCase().includes('doble') || (parseInt(m.capacidad_total, 10) || 0) > 45 || (parseInt(m.filas, 10) || 0) >= 10;
                editorState = crearStateBase(m.filas, m.columnas, heuristicaDoble);
            }
            $('#idModelo').val(m.idModelo);
            $('#nombre').val(m.nombre);
            $('#tipo_transporte').val(m.tipo_transporte);
            $('#filas').val(m.filas);
            $('#columnas').val(m.columnas);
            $('#distribucion_json').val(m.distribucion_json || '');
            if (editorState && editorState.pisos && editorState.pisos[0]) {
                $('#editor_filas1').val(editorState.pisos[0].filas);
                $('#editor_columnas1').val(editorState.pisos[0].columnas);
                $('#editor_letra1').val(editorState.pisos[0].letraInicial || 'A');
                $('#editor_numero1').val(editorState.pisos[0].numeroInicial || 1);
            }
            if (editorState && editorState.pisos && editorState.pisos[1]) {
                $('#editor_filas2').val(editorState.pisos[1].filas);
                $('#editor_columnas2').val(editorState.pisos[1].columnas);
                $('#editor_letra2').val(editorState.pisos[1].letraInicial || 'A');
                $('#editor_numero2').val(editorState.pisos[1].numeroInicial || 1);
                $('#editor_doblepiso').prop('checked', true);
            } else {
                $('#editor_doblepiso').prop('checked', false);
            }
            $('#descripcion').val(m.descripcion);
            $('#habilitado').prop('checked', parseInt(m.habilitado) === 1);
            $('#tituloModal').text('Editar Modelo');
            $('#modalModelo').modal('show');
        } else {
            Swal.fire('Error', response.error || 'No se pudo cargar el modelo', 'error');
        }
    }, 'json');
}

function guardarModelo() {
    const idModelo = $('#idModelo').val();
    let distribucionStr = $('#distribucion_json').val();
    let capacidadCalculada = (parseInt($('#filas').val(), 10) || 0) * (parseInt($('#columnas').val(), 10) || 0);
    const distObj = safeParseDistribucion(distribucionStr);
    if (distObj) {
        const normalizada = normalizarState(distObj);
        if (normalizada) {
            distribucionStr = JSON.stringify(normalizada);
            const capacidadEstado = contarAsientosActivos(normalizada);
            capacidadCalculada = capacidadEstado || capacidadCalculada;
            $('#filas').val(normalizada.pisos.reduce((acc, p) => acc + (p.filas || p.asientos.length), 0));
            $('#columnas').val(normalizada.pisos.reduce((acc, p) => Math.max(acc, p.columnas || (p.asientos[0] ? p.asientos[0].length : 0)), 0));
        }
    }
    const datos = {
        action: idModelo ? 'updateModelo' : 'insertModelo',
        idModelo: idModelo,
        nombre: $('#nombre').val(),
        tipo_transporte: $('#tipo_transporte').val(),
        filas: $('#filas').val(),
        columnas: $('#columnas').val(),
        descripcion: $('#descripcion').val(),
        distribucion_json: distribucionStr,
        capacidad_total: capacidadCalculada,
        habilitado: $('#habilitado').is(':checked') ? 1 : 0
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

function verMapa(idModelo) {
    // Obtener datos del modelo
    const modelo = <?=json_encode($modelos)?>.find(m => m.idModelo == idModelo);
    
    if (!modelo) {
        Swal.fire('Error', 'Modelo no encontrado', 'error');
        return;
    }

    renderMapa(modelo);
}

function renderMapa(modeloBase) {
    const state = buildStateFromModelo(modeloBase);
    if (!state) {
        Swal.fire('Error', 'Completa filas y columnas para ver el mapa.', 'warning');
        return;
    }

    const esDoublePiso = !!(state.doblePiso && state.pisos.length > 1);
    const capacidadActiva = contarAsientosActivos(state);
    let html = '<div style="padding: 20px;">';
    html += '<div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 15px; border-radius: 8px; color: white; margin-bottom: 20px;">';
    html += '<h4 style="margin: 0 0 8px 0;">' + (modeloBase.nombre || 'Modelo') + '</h4>';
    const capConfig = parseInt(modeloBase.capacidad_total || 0, 10);
    html += '<p style="margin: 5px 0; font-size: 14px;"><strong>' + state.filas + ' filas × ' + state.columnas + ' columnas = ' + capacidadActiva + ' asientos activos</strong>' + (capConfig ? ' <span style="opacity:0.9;">(capacidad configurada: ' + capConfig + ')</span>' : '') + '</p>';
    if (esDoublePiso) {
        html += '<p style="margin: 5px 0; font-size: 12px; opacity: 0.9;">Doble Piso: distribución superior e inferior.</p>';
    }
    html += '</div>';

    let inicio = 0;
    const colores = esDoublePiso ? ['#29a745', '#007bff'] : ['#17a2b8'];

    state.pisos.forEach((piso, idx) => {
        const color = colores[Math.min(idx, colores.length - 1)];
        const titulo = esDoublePiso ? (idx === 0 ? 'Planta inferior' : 'Planta superior') : 'Planta única';
        html += '<div style="margin-bottom: 16px;">';
        html += '<p style="font-weight: 600; color: ' + color + '; margin-bottom: 10px;">' + titulo + '</p>';
        html += '<div style="display: inline-block; padding: 12px; background: #f8f9fa; border-radius: 6px;">';
        const letraInicial = (piso.letraInicial || 'A').toUpperCase();
        const numeroInicial = parseInt(piso.numeroInicial || 1, 10);
        const letraCode = letraInicial.charCodeAt(0) - 65;
        piso.asientos.forEach((fila, idxFila) => {
            html += '<div style="margin-bottom: 6px;">';
            fila.forEach((celda, idxCol) => {
                const letraIdx = (letraCode + inicio + idxFila) % letras.length;
                const etiqueta = letras[letraIdx] + (numeroInicial + idxCol);
                let fondo = '#e9ecef';
                let texto = '#6c757d';
                let contenido = etiqueta;
                let estiloExtra = '';
                if (celda === 1) {
                    fondo = color;
                    texto = '#fff';
                    estiloExtra = '';
                } else if (celda === 'B') {
                    fondo = '#ffc107';
                    texto = '#212529';
                    contenido = '<i class="fas fa-restroom"></i>';
                } else if (celda === 'P') {
                    fondo = '#6c757d';
                    texto = '#fff';
                    contenido = '<i class="fas fa-grip-lines-vertical"></i>';
                } else if (celda === 'C') {
                    fondo = '#8b6f47';
                    texto = '#fff';
                    contenido = '<i class="fas fa-coffee"></i>';
                } else if (celda === 'E') {
                    fondo = '#dc3545';
                    texto = '#fff';
                    contenido = '<i class="fas fa-arrow-up"></i>';
                } else if (celda === 'W') {
                    fondo = '#fd7e14';
                    texto = '#fff';
                    contenido = '<i class="fas fa-window-maximize"></i>';
                } else if (celda === 'D') {
                    fondo = '#28a745';
                    texto = '#fff';
                    contenido = '<i class="fas fa-bed"></i>';
                } else if (celda === 'S') {
                    fondo = '#5cb85c';
                    texto = '#fff';
                    contenido = '<i class="fas fa-bed"></i>';
                } else if (celda === 'F') {
                    fondo = '#a68873';
                    texto = '#fff';
                    contenido = 'F';
                } else {
                    estiloExtra = 'text-decoration: line-through;';
                }
                html += '<span style="display: inline-flex; width: 38px; height: 38px; background: ' + fondo + '; color: ' + texto + '; border-radius: 4px; margin: 2px; align-items: center; justify-content: center; font-size: 11px; font-weight: bold; ' + estiloExtra + '">' + contenido + '</span>';
            });
            html += '</div>';
        });
        html += '</div>';
        html += '</div>';
        inicio += piso.asientos.length;
    });

    html += '<div style="margin-top: 20px; padding: 15px; background: #f8f9fa; border-left: 4px solid #667eea; border-radius: 4px;">';
    html += '<p style="margin: 0; font-size: 12px; color: #666;">';
    html += '<span style="display: inline-block; width: 16px; height: 16px; background: ' + colores[0] + '; border-radius: 2px; margin-right: 8px; vertical-align: middle;"></span> Asiento';
    html += '<span style="display: inline-block; width: 16px; height: 16px; background: #e9ecef; border-radius: 2px; margin-right: 8px; margin-left: 12px; vertical-align: middle;"></span> Vacío';
    html += '<span style="display: inline-block; width: 16px; height: 16px; background: #ffc107; border-radius: 2px; margin-right: 8px; margin-left: 12px; vertical-align: middle;"></span> Baño';
    html += '<span style="display: inline-block; width: 16px; height: 16px; background: #6c757d; border-radius: 2px; margin-right: 8px; margin-left: 12px; vertical-align: middle;"></span> Pasillo';
    html += '<span style="display: inline-block; width: 16px; height: 16px; background: #8b6f47; border-radius: 2px; margin-right: 8px; margin-left: 12px; vertical-align: middle;"></span> Cafetera';
    html += '<span style="display: inline-block; width: 16px; height: 16px; background: #dc3545; border-radius: 2px; margin-right: 8px; margin-left: 12px; vertical-align: middle;"></span> Escalera';
    html += '<span style="display: inline-block; width: 16px; height: 16px; background: #fd7e14; border-radius: 2px; margin-right: 8px; margin-left: 12px; vertical-align: middle;"></span> Panorámico';
    html += '<span style="display: inline-block; width: 16px; height: 16px; background: #28a745; border-radius: 2px; margin-right: 8px; margin-left: 12px; vertical-align: middle;"></span> Cama';
    html += '<span style="display: inline-block; width: 16px; height: 16px; background: #5cb85c; border-radius: 2px; margin-right: 8px; margin-left: 12px; vertical-align: middle;"></span> Semicama';
    html += '<span style="display: inline-block; width: 16px; height: 16px; background: #a68873; border-radius: 2px; margin-right: 8px; margin-left: 12px; vertical-align: middle;"></span> Cerca cafetera';
    if (esDoublePiso) {
        html += '<span style="display: inline-block; width: 16px; height: 16px; background: ' + colores[1] + '; border-radius: 2px; margin-right: 8px; margin-left: 12px; vertical-align: middle;"></span> Planta inferior';
    }
    html += '</p>';
    if (capConfig && capConfig !== capacidadActiva) {
        html += '<div style="margin-top:10px; font-size:12px; color:#dc3545;"><i class="fas fa-exclamation-triangle"></i> Diferencia detectada: activos ' + capacidadActiva + ' vs configurados ' + capConfig + '. Ajusta el mapa o la capacidad para coincidir.</div>';
    }
    html += '</div>';

    html += '</div>';

    $('#contenidoMapa').html(html);
    $('#modalMapa').modal('show');
}

function previewMapaFormulario() {
    const dist = editorState || safeParseDistribucion($('#distribucion_json').val());
    const filasVal = dist?.filas || parseInt($('#filas').val() || 0, 10);
    const colsVal = dist?.columnas || parseInt($('#columnas').val() || 0, 10);
    if (!filasVal || !colsVal) {
        Swal.fire('Error', 'Completa filas y columnas para previsualizar.', 'warning');
        return;
    }
    const modelo = {
        nombre: $('#nombre').val() || 'Modelo sin nombre',
        filas: filasVal,
        columnas: colsVal,
        capacidad_total: filasVal * colsVal,
        distribucion_json: dist ? JSON.stringify(dist) : $('#distribucion_json').val()
    };
    renderMapa(modelo);
}

function toggleEstado(idModelo, estadoActual) {
    const nuevoEstado = estadoActual ? 0 : 1;
    const accion = nuevoEstado ? 'Activar' : 'Desactivar';
    
    Swal.fire({
        title: accion + ' modelo',
        text: '¿Estás seguro?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí',
        cancelButtonText: 'Cancelar'
    }).then(result => {
        if (result.isConfirmed) {
            $.post('ctrl/ctrlModelosVehiculos.php', {
                action: 'updateModelo',
                idModelo: idModelo,
                habilitado: nuevoEstado
            }, function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            }, 'json');
        }
    });
}

function limpiarFiltros() {
    $('#filtroTipo').val('');
    $('#busqueda').val('');
    location.reload();
}

function sincronizarCapacidad(idModelo, capacidadReal) {
    Swal.fire({
        title: 'Sincronizar capacidad',
        html: '<p>Se actualizará la capacidad en BD de <strong>' + (document.querySelector('[onclick="sincronizarCapacidad(' + idModelo)?.closest('tr')?.querySelector('strong')?.textContent || 'este modelo') + '</strong></p><p>Capacidad actual en BD: <span id="capBD">--</span></p><p>Capacidad real desde mapa: <strong style="color: #28a745;">' + capacidadReal + ' asientos</strong></p>',
        icon: 'info',
        showCancelButton: true,
        confirmButtonText: 'Sí, actualizar',
        cancelButtonText: 'Cancelar',
        allowOutsideClick: false
    }).then(result => {
        if (result.isConfirmed) {
            $.post('ctrl/ctrlModelosVehiculos.php', {
                action: 'updateModelo',
                idModelo: idModelo,
                capacidad_total: capacidadReal
            }, function(response) {
                if (response.success) {
                    Swal.fire('✓ Sincronizado', 'La capacidad se actualizó correctamente a ' + capacidadReal + ' asientos.', 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', response.error || 'No se pudo sincronizar', 'error');
                }
            }, 'json').fail(function() {
                Swal.fire('Error', 'Error de conexión al servidor', 'error');
            });
        }
    });
}

// Inicializar DataTable
$(document).ready(function() {
    $('#tablaModelos').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
        },
        "pageLength": 25
    });

    $('#editor_doblepiso, #editor_filas1, #editor_columnas1, #editor_filas2, #editor_columnas2').on('change', function() {
        if ($('#modalEditorVisual').hasClass('show')) {
            generarGridEditor();
        }
    });
});
</script>
