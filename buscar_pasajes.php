<?php
/**
 * Búsqueda de Pasajes de Transporte
 * Integrado con sistema de reservas existente
 */
session_start();
require_once("admin/classes/conexion.php");
require_once("admin/classes/transporte.php");
require_once("admin/lang/" . ($_SESSION['idioma'] ?? 'ES') . ".php");

// Obtener tipos de transporte para filtros
$tipos = getAllTiposTransporte();
$origenes = [];
$destinos = [];

// Si hay parámetros de búsqueda, ejecutar consulta
$viajes = [];
if (isset($_GET['origen']) && isset($_GET['destino']) && isset($_GET['fecha'])) {
    $idOrigen = intval($_GET['origen']);
    $idDestino = intval($_GET['destino']);
    $fecha = $_GET['fecha'];
    $tipo = isset($_GET['tipo']) ? intval($_GET['tipo']) : null;
    
    // Query para obtener viajes disponibles
    $sql = "SELECT v.*, r.nombre as ruta_nombre, r.duracion_estimada, r.distancia_km,
                   e.nombre as empresa_nombre, m.nombre as modelo_nombre, m.capacidad_total,
                   t_origen.nombre as origen_nombre, t_destino.nombre as destino_nombre,
                   tipo.nombre as tipo_transporte
            FROM viaje_transporte v
            INNER JOIN ruta_transporte r ON v.idRuta = r.idRuta
            LEFT JOIN empresa_transporte e ON r.idEmpresa = e.idEmpresa
            LEFT JOIN vehiculo_transporte vh ON v.idVehiculo = vh.idVehiculo
            LEFT JOIN modelo_vehiculo_transporte m ON vh.idModelo = m.idModelo
            INNER JOIN ruta_paradas p_origen ON r.idRuta = p_origen.idRuta
            INNER JOIN ruta_paradas p_destino ON r.idRuta = p_destino.idRuta
            INNER JOIN terminal_transporte t_origen ON p_origen.idTerminal = t_origen.idTerminal
            INNER JOIN terminal_transporte t_destino ON p_destino.idTerminal = t_destino.idTerminal
            LEFT JOIN tipo_transporte tipo ON r.idTipoTransporte = tipo.idTipoTransporte
            WHERE p_origen.idRutaParada = :origen
              AND p_destino.idRutaParada = :destino
              AND p_origen.orden < p_destino.orden
              AND v.fecha_salida = :fecha
              AND v.asientos_disponibles > 0
              AND r.habilitado = 1";
    
    if ($tipo) {
        $sql .= " AND r.idTipoTransporte = :tipo";
    }
    
    $sql .= " ORDER BY v.hora_salida ASC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':origen', $idOrigen, PDO::PARAM_INT);
    $stmt->bindParam(':destino', $idDestino, PDO::PARAM_INT);
    $stmt->bindParam(':fecha', $fecha, PDO::PARAM_STR);
    if ($tipo) {
        $stmt->bindParam(':tipo', $tipo, PDO::PARAM_INT);
    }
    $stmt->execute();
    $viajes = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Obtener todas las paradas para orígenes/destinos
$paradas_sql = "SELECT DISTINCT rp.idRutaParada, t.nombre, t.ciudad, t.estado
                FROM ruta_paradas rp
                INNER JOIN terminal_transporte t ON rp.idTerminal = t.idTerminal
                WHERE rp.es_origen = 1
                ORDER BY t.nombre";
$origenes = $pdo->query($paradas_sql)->fetchAll(PDO::FETCH_ASSOC);

$paradas_sql2 = "SELECT DISTINCT rp.idRutaParada, t.nombre, t.ciudad, t.estado
                 FROM ruta_paradas rp
                 INNER JOIN terminal_transporte t ON rp.idTerminal = t.idTerminal
                 WHERE rp.es_destino = 1
                 ORDER BY t.nombre";
$destinos = $pdo->query($paradas_sql2)->fetchAll(PDO::FETCH_ASSOC);

include("includes/headPagos.php");
?>

<style>
.busqueda-transporte {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 40px 0;
    color: white;
}
.card-viaje {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 15px;
    transition: all 0.3s;
}
.card-viaje:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    border-color: #029ce2;
}
.precio-viaje {
    font-size: 28px;
    font-weight: bold;
    color: #029ce2;
}
.btn-reservar {
    background: #029ce2;
    color: white;
    padding: 12px 30px;
    border-radius: 6px;
    text-decoration: none;
    display: inline-block;
    transition: all 0.3s;
}
.btn-reservar:hover {
    background: #0277bd;
    color: white;
    text-decoration: none;
}
</style>

<!-- Buscador de Pasajes -->
<section class="busqueda-transporte">
    <div class="container">
        <h1 class="text-white mb-4"><i class="fas fa-bus"></i> Buscar Pasajes de Transporte</h1>
        
        <form method="GET" action="" class="bg-white p-4 rounded">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="text-dark">Origen</label>
                    <select name="origen" class="form-control" required>
                        <option value="">Seleccione...</option>
                        <?php foreach ($origenes as $o): ?>
                            <option value="<?=$o['idRutaParada']?>" <?= (isset($_GET['origen']) && $_GET['origen'] == $o['idRutaParada']) ? 'selected' : '' ?>>
                                <?=htmlspecialchars($o['nombre'])?> - <?=htmlspecialchars($o['ciudad'])?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="col-md-3 mb-3">
                    <label class="text-dark">Destino</label>
                    <select name="destino" class="form-control" required>
                        <option value="">Seleccione...</option>
                        <?php foreach ($destinos as $d): ?>
                            <option value="<?=$d['idRutaParada']?>" <?= (isset($_GET['destino']) && $_GET['destino'] == $d['idRutaParada']) ? 'selected' : '' ?>>
                                <?=htmlspecialchars($d['nombre'])?> - <?=htmlspecialchars($d['ciudad'])?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="col-md-2 mb-3">
                    <label class="text-dark">Fecha</label>
                    <input type="date" name="fecha" class="form-control" 
                           value="<?= $_GET['fecha'] ?? date('Y-m-d') ?>" 
                           min="<?= date('Y-m-d') ?>" required>
                </div>
                
                <div class="col-md-2 mb-3">
                    <label class="text-dark">Tipo</label>
                    <select name="tipo" class="form-control">
                        <option value="">Todos</option>
                        <?php foreach ($tipos as $t): ?>
                            <option value="<?=$t['idTipoTransporte']?>" <?= (isset($_GET['tipo']) && $_GET['tipo'] == $t['idTipoTransporte']) ? 'selected' : '' ?>>
                                <?=htmlspecialchars($t['nombre'])?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="col-md-2 mb-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                </div>
            </div>
        </form>
    </div>
</section>

<!-- Resultados -->
<section class="py-5">
    <div class="container">
        <?php if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['fecha'])): ?>
            <h3 class="mb-4"><?= count($viajes) ?> viaje(s) encontrado(s)</h3>
            
            <?php if (empty($viajes)): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> No se encontraron viajes disponibles para esta búsqueda.
                    Intenta con otras fechas u orígenes/destinos.
                </div>
            <?php else: ?>
                <?php foreach ($viajes as $viaje): ?>
                    <div class="card-viaje">
                        <div class="row align-items-center">
                            <div class="col-md-2 text-center">
                                <i class="fas fa-<?= $viaje['tipo_transporte'] == 'Bus' ? 'bus' : ($viaje['tipo_transporte'] == 'Avión' ? 'plane' : 'train') ?> fa-3x text-primary"></i>
                                <p class="mb-0 mt-2"><small><?= htmlspecialchars($viaje['tipo_transporte'] ?? 'Transporte') ?></small></p>
                            </div>
                            
                            <div class="col-md-6">
                                <h5 class="mb-2"><?= htmlspecialchars($viaje['empresa_nombre'] ?? 'Empresa') ?></h5>
                                <p class="mb-1"><strong><?= htmlspecialchars($viaje['origen_nombre']) ?></strong> → <strong><?= htmlspecialchars($viaje['destino_nombre']) ?></strong></p>
                                <p class="mb-1 text-muted">
                                    <i class="far fa-clock"></i> Salida: <?= date('H:i', strtotime($viaje['hora_salida'])) ?> · 
                                    Duración: <?= htmlspecialchars($viaje['duracion_estimada']) ?> · 
                                    <?= htmlspecialchars($viaje['distancia_km']) ?> km
                                </p>
                                <p class="mb-0">
                                    <span class="badge badge-success"><?= $viaje['asientos_disponibles'] ?> asientos disponibles</span>
                                    <?php if ($viaje['modelo_nombre']): ?>
                                        <span class="badge badge-info"><?= htmlspecialchars($viaje['modelo_nombre']) ?></span>
                                    <?php endif; ?>
                                </p>
                            </div>
                            
                            <div class="col-md-2 text-center">
                                <p class="mb-0 text-muted">Desde</p>
                                <p class="precio-viaje mb-0">
                                    <?= $_SESSION['moneda_sel_sym'] ?> <?= number_format(1200, 2) ?>
                                </p>
                                <p class="mb-0"><small class="text-muted">por persona</small></p>
                            </div>
                            
                            <div class="col-md-2 text-center">
                                <a href="viaje_detalle_transporte.php?id=<?=$viaje['idViaje']?>&origen=<?=$_GET['origen']?>&destino=<?=$_GET['destino']?>&fecha=<?=$_GET['fecha']?>" 
                                   class="btn-reservar">
                                    Ver Asientos
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-search fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">Realiza una búsqueda para ver viajes disponibles</h4>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include("includes/footer.php"); ?>
