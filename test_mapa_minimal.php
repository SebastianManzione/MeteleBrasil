<?php
// Test ultra simple para verificar el mapa
require_once("admin/classes/transporte.php");
require_once("config/config.php");

$viaje = getViaje(5);
$paradas = getParadasRuta($viaje['idRuta']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Test Ultra Simple</title>
</head>
<body>
    <h1>Test Mapa - Servicio Contransporte</h1>
    <div id="mapRecorrido" style="width:100%; height:500px; border:3px solid red;"></div>
    
    <h2>Debug:</h2>
    <pre><?php 
        echo "Total paradas: " . count($paradas) . "\n";
        foreach($paradas as $p) {
            echo "- {$p['terminal_nombre']}: {$p['latitud']}, {$p['longitud']}\n";
        }
    ?></pre>
    
    <script>
    var paradasViaje = <?= json_encode(array_map(function($p) {
        return [
            'orden' => (int)$p['orden'],
            'nombre' => $p['terminal_nombre'],
            'ciudad' => $p['ciudad'],
            'latitud' => (float)$p['latitud'],
            'longitud' => (float)$p['longitud'],
            'es_origen' => (int)$p['es_origen'],
            'es_destino' => (int)$p['es_destino']
        ];
    }, $paradas), JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK) ?>;
    
    console.log('Paradas:', paradasViaje);
    
    function inicializarMapa() {
        console.log('=== CALLBACK EJECUTADO ===');
        console.log('Google disponible?', typeof google !== 'undefined');
        
        if (!paradasViaje || paradasViaje.length === 0) {
            console.error('No hay paradas');
            return;
        }
        
        var map = new google.maps.Map(document.getElementById('mapRecorrido'), {
            zoom: 6,
            center: {lat: paradasViaje[0].latitud, lng: paradasViaje[0].longitud}
        });
        
        console.log('Mapa creado');
        
        var bounds = new google.maps.LatLngBounds();
        
        paradasViaje.forEach(function(p) {
            var pos = {lat: p.latitud, lng: p.longitud};
            bounds.extend(pos);
            
            var color = p.es_origen && p.es_destino ? '#ffc107' : 
                        p.es_origen ? '#28a745' : 
                        p.es_destino ? '#dc3545' : '#6c757d';
            
            new google.maps.Marker({
                position: pos,
                map: map,
                label: {text: p.orden.toString(), color: 'white', fontWeight: 'bold'},
                icon: {
                    path: google.maps.SymbolPath.CIRCLE,
                    fillColor: color,
                    fillOpacity: 1,
                    strokeColor: 'white',
                    strokeWeight: 2,
                    scale: 12
                },
                title: p.nombre
            });
            
            console.log('Marker:', p.nombre, pos);
        });
        
        map.fitBounds(bounds);
        console.log('✓ Mapa completado');
    }
    </script>
    
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=<?= GOOGLE_MAPS_API_KEY ?>&callback=inicializarMapa"></script>
</body>
</html>
