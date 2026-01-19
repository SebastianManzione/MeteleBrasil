<?php
/**
 * VERSIÓN LIMPIA - SIN NAVBAR
 * Página de viaje de transporte 100% funcional
 */

require_once("admin/classes/transporte.php");
require_once("config/config.php");

$viaje = null;
$ruta = null;
$paradas = [];

if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
    $idViaje = (int)$_GET['id'];
    $viaje = getViaje($idViaje);

    if (!empty($viaje)) {
        $idRuta = $viaje['idRuta'];
        $ruta = getRuta($idRuta);
        $paradas = getParadasRuta($idRuta);
    }
} 

if (empty($viaje)) {
    die("Viaje no encontrado");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($viaje['ruta_nombre'] ?? 'Viaje') ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: Arial, sans-serif; }
        .card-header { cursor: pointer; background-color: #0099cc !important; }
        #mapRecorrido { width: 100%; height: 500px; border: 2px solid #17a2b8; border-radius: 8px; }
    </style>
</head>
<body>

<main class="container my-5">
    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-body">
                    <h1 class="text-primary mb-3">
                        <i class="fas fa-bus"></i> <?= htmlspecialchars($viaje['ruta_nombre']) ?>
                    </h1>
                    <p><strong>Fecha:</strong> <?= date('d/m/Y', strtotime($viaje['fecha'])) ?></p>
                    <p><strong>Hora:</strong> <?= substr($viaje['hora_salida'], 0, 5) ?></p>
                </div>
            </div>

            <div class="accordion" id="accordion">
                <!-- PARADAS -->
                <div class="card">
                    <div class="card-header" data-toggle="collapse" data-target="#paradas">
                        <h5 class="mb-0">
                            <i class="fas fa-map-marker-alt"></i> Paradas (<?= count($paradas) ?>)
                        </h5>
                    </div>
                    <div id="paradas" class="collapse show" data-parent="#accordion">
                        <div class="card-body">
                            <?php if (!empty($paradas)): ?>
                                <div class="list-group mb-3">
                                    <?php foreach ($paradas as $p): ?>
                                        <div class="list-group-item">
                                            <strong><?= $p['orden'] ?>. <?= $p['terminal_nombre'] ?></strong><br>
                                            <small class="text-muted"><?= $p['ciudad'] ?></small>
                                            <?php if ($p['es_origen']): ?><span class="badge badge-success">Origen</span><?php endif; ?>
                                            <?php if ($p['es_destino']): ?><span class="badge badge-danger">Destino</span><?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                
                                <hr>
                                <h5><i class="fas fa-map text-info"></i> Mapa de Recorrido</h5>
                                <div id="mapRecorrido"></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Paradas JSON -->
<script>
var paradasViaje = <?php echo json_encode(array_map(function($p) {
    return [
        'orden' => (int)$p['orden'],
        'nombre' => $p['terminal_nombre'],
        'ciudad' => $p['ciudad'],
        'latitud' => (float)$p['latitud'],
        'longitud' => (float)$p['longitud']
    ];
}, $paradas), JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK); ?>;

console.log('Paradas:', paradasViaje);
</script>

<!-- Función mapa (global scope) -->
<script>
function inicializarMapa() {
    console.log('=== CALLBACK EJECUTADO ===');
    console.log('Paradas:', paradasViaje);
    
    if (!paradasViaje || paradasViaje.length === 0) {
        console.error('No hay paradas');
        return;
    }
    
    var container = document.getElementById('mapRecorrido');
    if (!container) {
        console.error('Contenedor no encontrado');
        return;
    }
    
    var map = new google.maps.Map(container, {
        zoom: 6,
        center: {lat: paradasViaje[0].latitud, lng: paradasViaje[0].longitud}
    });
    
    var bounds = new google.maps.LatLngBounds();
    var path = [];
    
    paradasViaje.forEach(function(p) {
        var pos = {lat: p.latitud, lng: p.longitud};
        bounds.extend(pos);
        path.push(pos);
        
        new google.maps.Marker({
            position: pos,
            map: map,
            label: {text: p.orden.toString(), color: 'white', fontWeight: 'bold'},
            icon: {
                path: google.maps.SymbolPath.CIRCLE,
                fillColor: '#28a745',
                fillOpacity: 1,
                strokeColor: 'white',
                strokeWeight: 2,
                scale: 12
            }
        });
    });
    
    new google.maps.Polyline({
        path: path,
        geodesic: true,
        strokeColor: '#17a2b8',
        strokeOpacity: 0.8,
        strokeWeight: 4,
        map: map
    });
    
    map.fitBounds(bounds);
    console.log('✓ Mapa renderizado');
}
</script>

<!-- Google Maps API con callback -->
<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_MAPS_API_KEY; ?>&callback=inicializarMapa"></script>

</body>
</html>
