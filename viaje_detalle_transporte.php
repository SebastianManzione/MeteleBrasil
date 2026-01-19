<?php
/**
 * Detalle de Viaje con Selección de Asientos
 * Usa distribucion_json del modelo de vehículo
 */
session_start();
require_once("admin/classes/conexion.php");
require_once("admin/classes/transporte.php");
require_once("admin/lang/" . ($_SESSION['idioma'] ?? 'ES') . ".php");

if (!isset($_GET['id']) || !isset($_GET['fecha'])) {
    header("Location: buscar_pasajes.php");
    exit;
}

$idViaje = intval($_GET['id']);
$fecha = $_GET['fecha'];
$idOrigen = intval($_GET['origen']);
$idDestino = intval($_GET['destino']);

// Obtener viaje completo con modelo
$sql = "SELECT v.*, r.nombre as ruta_nombre, r.descripcion_es, r.duracion_estimada,
               e.nombre as empresa_nombre, e.logo,
               m.nombre as modelo_nombre, m.capacidad_total, m.distribucion_json,
               vh.patente,
               t_origen.nombre as origen_nombre, t_origen.ciudad as origen_ciudad,
               t_destino.nombre as destino_nombre, t_destino.ciudad as destino_ciudad,
               tipo.nombre as tipo_transporte, tipo.icono
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
        WHERE v.idViaje = :idViaje
          AND p_origen.idRutaParada = :origen
          AND p_destino.idRutaParada = :destino";

$stmt = $pdo->prepare($sql);
$stmt->execute([':idViaje' => $idViaje, ':origen' => $idOrigen, ':destino' => $idDestino]);
$viaje = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$viaje) {
    die("Viaje no encontrado");
}

// Obtener asientos ocupados para este viaje+fecha
$sql_ocupados = "SELECT numero_asiento
                 FROM reserva_transporte_pasajeros rtp
                 INNER JOIN reserva_transporte_items rti ON rtp.idReservaTransporte = rti.idReservaTransporte
                 WHERE rti.idViaje = :idViaje
                   AND rti.fecha_viaje = :fecha";
$stmt_ocupados = $pdo->prepare($sql_ocupados);
$stmt_ocupados->execute([':idViaje' => $idViaje, ':fecha' => $fecha]);
$asientos_ocupados = array_column($stmt_ocupados->fetchAll(PDO::FETCH_ASSOC), 'numero_asiento');

// Decodificar JSON de distribución
$distribucion = json_decode($viaje['distribucion_json'], true);

include("includes/headPagos.php");
?>

<style>
.seat-map {
    background: #f5f5f5;
    padding: 20px;
    border-radius: 8px;
    max-width: 600px;
    margin: 0 auto;
}
.seat-floor {
    background: white;
    padding: 15px;
    border-radius: 6px;
    margin-bottom: 15px;
}
.seat-floor h5 {
    text-align: center;
    margin-bottom: 15px;
    color: #029ce2;
}
.seat-grid {
    display: grid;
    gap: 8px;
    justify-content: center;
}
.seat-cell {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s;
    font-size: 11px;
    font-weight: bold;
}
.seat-available {
    background: #e8f5e9;
    border: 2px solid #4caf50;
    color: #2e7d32;
}
.seat-available:hover {
    background: #a5d6a7;
    transform: scale(1.1);
}
.seat-occupied {
    background: #ffebee;
    border: 2px solid #f44336;
    color: #c62828;
    cursor: not-allowed;
}
.seat-selected {
    background: #029ce2;
    border: 2px solid #0277bd;
    color: white;
}
.seat-aisle {
    background: transparent;
    border: none;
}
.seat-special {
    background: #fff3e0;
    border: 2px dashed #ff9800;
    color: #e65100;
    cursor: default;
    font-size: 16px;
}
.leyenda {
    display: flex;
    gap: 20px;
    justify-content: center;
    margin-top: 20px;
    flex-wrap: wrap;
}
.leyenda-item {
    display: flex;
    align-items: center;
    gap: 8px;
}
.leyenda-box {
    width: 30px;
    height: 30px;
    border-radius: 4px;
}
</style>

<div class="container mt-4 mb-5">
    <div class="row">
        <!-- Información del Viaje -->
        <div class="col-md-5">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-3">
                        <i class="fas fa-<?=$viaje['icono'] ?? 'bus'?>"></i>
                        <?=htmlspecialchars($viaje['empresa_nombre'])?>
                    </h4>
                    
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Ruta</h6>
                        <p class="mb-0"><strong><?=htmlspecialchars($viaje['origen_nombre'])?></strong> (<?=htmlspecialchars($viaje['origen_ciudad'])?>)</p>
                        <p class="text-center my-2"><i class="fas fa-arrow-down text-primary"></i></p>
                        <p class="mb-0"><strong><?=htmlspecialchars($viaje['destino_nombre'])?></strong> (<?=htmlspecialchars($viaje['destino_ciudad'])?>)</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Fecha y Hora</h6>
                        <p class="mb-0">
                            <i class="far fa-calendar"></i> <?=date('d/m/Y', strtotime($fecha))?><br>
                            <i class="far fa-clock"></i> <?=date('H:i', strtotime($viaje['hora_salida']))?>
                        </p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Vehículo</h6>
                        <p class="mb-0">
                            <?=htmlspecialchars($viaje['modelo_nombre'])?><br>
                            <small class="text-muted">Patente: <?=htmlspecialchars($viaje['patente'])?></small>
                        </p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Disponibilidad</h6>
                        <div class="progress">
                            <div class="progress-bar bg-success" style="width: <?=($viaje['asientos_disponibles']/$viaje['capacidad_total'])*100?>%">
                                <?=$viaje['asientos_disponibles']?> / <?=$viaje['capacidad_total']?> disponibles
                            </div>
                        </div>
                    </div>
                    
                    <!-- Resumen de Selección -->
                    <div id="resumen-seleccion" class="alert alert-info" style="display:none;">
                        <h6>Asientos Seleccionados</h6>
                        <div id="lista-asientos"></div>
                        <hr>
                        <p class="mb-0"><strong>Total:</strong> <span id="total-precio"><?=$_SESSION['moneda_sel_sym']?> 0.00</span></p>
                    </div>
                    
                    <button id="btn-continuar" class="btn btn-primary btn-block btn-lg" disabled>
                        Continuar con la Reserva
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mapa de Asientos -->
        <div class="col-md-7">
            <div class="seat-map">
                <h4 class="text-center mb-3">Selecciona tus Asientos</h4>
                
                <div id="seat-container">
                    <?php
                    $doblePiso = $distribucion['doblePiso'] ?? false;
                    $pisos = $distribucion['pisos'] ?? [];
                    
                    foreach ($pisos as $idx => $piso):
                        $nombre = $piso['nombre'] ?? ($doblePiso ? ($idx == 0 ? 'Planta Inferior' : 'Planta Superior') : 'Asientos');
                        $filas = $piso['filas'] ?? 10;
                        $columnas = $piso['columnas'] ?? 4;
                        $asientos = $piso['asientos'] ?? [];
                        $letraInicial = $piso['letraInicial'] ?? 'A';
                        $numeroInicial = $piso['numeroInicial'] ?? 1;
                    ?>
                        <div class="seat-floor">
                            <h5><?=$nombre?></h5>
                            <div class="seat-grid" style="grid-template-columns: repeat(<?=$columnas?>, 40px);">
                                <?php
                                for ($f = 0; $f < $filas; $f++):
                                    for ($c = 0; $c < $columnas; $c++):
                                        $valor = $asientos[$f][$c] ?? 0;
                                        $numero = chr(ord($letraInicial) + $f) . ($numeroInicial + $c);
                                        
                                        if ($valor == 1):
                                            // Asiento real
                                            $ocupado = in_array($numero, $asientos_ocupados);
                                            $clase = $ocupado ? 'seat-occupied' : 'seat-available';
                                            $data_attr = !$ocupado ? "data-seat='$numero' data-precio='1200'" : '';
                                ?>
                                            <div class="seat-cell <?=$clase?>" <?=$data_attr?>>
                                                <?=$numero?>
                                            </div>
                                        <?php elseif ($valor == 'P'): ?>
                                            <div class="seat-cell seat-aisle"></div>
                                        <?php elseif (in_array($valor, ['Y', 'G', 'E', 'B', 'X', 'C', 'T', 'K'])): ?>
                                            <div class="seat-cell seat-special" title="<?=$valor?>">
                                                <?php
                                                $iconos = [
                                                    'Y' => 'fa-user-tie',
                                                    'G' => 'fa-glass-martini',
                                                    'E' => 'fa-stairs',
                                                    'B' => 'fa-restroom',
                                                    'X' => 'fa-door-open',
                                                    'C' => 'fa-coffee',
                                                    'T' => 'fa-tv',
                                                    'K' => 'fa-utensils'
                                                ];
                                                echo '<i class="fas '.$iconos[$valor].'"></i>';
                                                ?>
                                            </div>
                                        <?php else: ?>
                                            <div class="seat-cell"></div>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                <?php endfor; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Leyenda -->
                <div class="leyenda">
                    <div class="leyenda-item">
                        <div class="leyenda-box seat-available"></div>
                        <span>Disponible</span>
                    </div>
                    <div class="leyenda-item">
                        <div class="leyenda-box seat-occupied"></div>
                        <span>Ocupado</span>
                    </div>
                    <div class="leyenda-item">
                        <div class="leyenda-box seat-selected"></div>
                        <span>Seleccionado</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    let asientosSeleccionados = [];
    const precioPorAsiento = 1200;
    const simboloMoneda = '<?=$_SESSION['moneda_sel_sym']?>';
    
    // Click en asiento disponible
    $(document).on('click', '.seat-available', function() {
        const numero = $(this).data('seat');
        const precio = $(this).data('precio');
        
        if ($(this).hasClass('seat-selected')) {
            // Deseleccionar
            $(this).removeClass('seat-selected');
            asientosSeleccionados = asientosSeleccionados.filter(a => a !== numero);
        } else {
            // Seleccionar
            $(this).addClass('seat-selected');
            asientosSeleccionados.push(numero);
        }
        
        actualizarResumen();
    });
    
    function actualizarResumen() {
        if (asientosSeleccionados.length > 0) {
            $('#resumen-seleccion').show();
            $('#lista-asientos').html(
                'Asientos: <strong>' + asientosSeleccionados.join(', ') + '</strong>'
            );
            const total = asientosSeleccionados.length * precioPorAsiento;
            $('#total-precio').text(simboloMoneda + ' ' + total.toFixed(2));
            $('#btn-continuar').prop('disabled', false);
        } else {
            $('#resumen-seleccion').hide();
            $('#btn-continuar').prop('disabled', true);
        }
    }
    
    // Continuar con reserva
    $('#btn-continuar').click(function() {
        // Guardar en sesión y redirigir a checkout
        $.ajax({
            url: 'admin/ctrl/ctrlAgregarTransporteCarrito.php',
            method: 'POST',
            data: {
                idViaje: <?=$idViaje?>,
                fecha: '<?=$fecha?>',
                origen: <?=$idOrigen?>,
                destino: <?=$idDestino?>,
                asientos: asientosSeleccionados,
                precio: precioPorAsiento
            },
            success: function(response) {
                if (response.success) {
                    window.location.href = 'carrito.php';
                } else {
                    alert('Error al agregar al carrito: ' + response.message);
                }
            },
            error: function() {
                alert('Error de conexión');
            }
        });
    });
});
</script>

<?php include("includes/footer.php"); ?>
