<?php
require_once 'config/config.php';
require_once 'admin/classes/conexion.php';
require_once 'admin/classes/transporte.php';

$paradas = getParadasRuta(8);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Test Mapa Simple</title>
    <style>
        #map { height: 600px; width: 100%; border: 2px solid red; }
        body { margin: 20px; font-family: Arial; }
    </style>
</head>
<body>
    <h1>Test Mapa Ruta 8</h1>
    <p>Paradas encontradas: <?php echo count($paradas); ?></p>
    
    <?php if (count($paradas) > 0): ?>
    <table border="1">
        <tr><th>Orden</th><th>Terminal</th><th>Ciudad</th><th>Lat</th><th>Lng</th></tr>
        <?php foreach ($paradas as $p): ?>
        <tr>
            <td><?php echo $p['orden']; ?></td>
            <td><?php echo $p['terminal_nombre']; ?></td>
            <td><?php echo $p['ciudad']; ?></td>
            <td><?php echo $p['latitud'] ?? 'NULL'; ?></td>
            <td><?php echo $p['longitud'] ?? 'NULL'; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php endif; ?>
    
    <h2>Mapa de Recorrido</h2>
    <div id="map"></div>
    
    <script>
    // Datos de paradas
    var paradas = <?php echo json_encode($paradas, JSON_UNESCAPED_UNICODE); ?>;
    console.log('Paradas:', paradas);
    
    // Función para inicializar el mapa
    function initMap() {
        console.log('initMap() ejecutado');
        
        if (!paradas || paradas.length === 0) {
            alert('No hay paradas para mostrar');
            return;
        }
        
        // Centro inicial
        var center = { 
            lat: parseFloat(paradas[0].latitud), 
            lng: parseFloat(paradas[0].longitud) 
        };
        
        console.log('Centro:', center);
        
        // Crear mapa
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 6,
            center: center,
            mapTypeId: 'roadmap'
        });
        
        console.log('Mapa creado');
        
        // Bounds para ajustar zoom
        var bounds = new google.maps.LatLngBounds();
        var path = [];
        
        // Crear marcadores
        paradas.forEach(function(parada, index) {
            var lat = parseFloat(parada.latitud);
            var lng = parseFloat(parada.longitud);
            
            console.log('Parada ' + index + ':', parada.terminal_nombre, lat, lng);
            
            var position = { lat: lat, lng: lng };
            
            var marker = new google.maps.Marker({
                position: position,
                map: map,
                label: String(index + 1),
                title: parada.terminal_nombre
            });
            
            bounds.extend(position);
            path.push(position);
        });
        
        // Línea de recorrido
        var polyline = new google.maps.Polyline({
            path: path,
            geodesic: true,
            strokeColor: '#FF0000',
            strokeOpacity: 1.0,
            strokeWeight: 3
        });
        
        polyline.setMap(map);
        
        // Ajustar zoom
        map.fitBounds(bounds);
        
        console.log('Mapa completado');
    }
    </script>
    
    <script async defer 
            src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_MAPS_API_KEY; ?>&callback=initMap">
    </script>
</body>
</html>
