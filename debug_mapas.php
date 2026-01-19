<?php
require_once 'config/config.php';
require_once 'admin/classes/conexion.php';

echo "<h2>DEBUG MAPAS</h2>";

// 1. Verificar terminales con coordenadas
echo "<h3>1. Terminales en BD</h3>";
$stmt = $pdo->query('SELECT COUNT(*) as total FROM terminal_transporte');
$count = $stmt->fetch(PDO::FETCH_ASSOC);
echo "Total terminales: " . $count['total'] . "<br>";

// 2. Verificar cuáles tienen lat/lng
$stmt = $pdo->query('SELECT COUNT(*) as total FROM terminal_transporte WHERE latitud IS NOT NULL AND latitud != 0 AND longitud IS NOT NULL AND longitud != 0');
$valid = $stmt->fetch(PDO::FETCH_ASSOC);
echo "Con coordenadas válidas: " . $valid['total'] . "<br>";

// 3. Mostrar primeros 5 terminales
echo "<h3>2. Primeros 5 terminales</h3>";
echo "<table border='1'>";
echo "<tr><th>ID</th><th>Nombre</th><th>Ciudad</th><th>Latitud</th><th>Longitud</th></tr>";
$stmt = $pdo->query('SELECT idTerminal, nombre, ciudad, latitud, longitud FROM terminal_transporte LIMIT 5');
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "<tr>";
    echo "<td>" . $row['idTerminal'] . "</td>";
    echo "<td>" . $row['nombre'] . "</td>";
    echo "<td>" . $row['ciudad'] . "</td>";
    echo "<td>" . ($row['latitud'] ?? 'NULL') . "</td>";
    echo "<td>" . ($row['longitud'] ?? 'NULL') . "</td>";
    echo "</tr>";
}
echo "</table>";

// 4. Verificar ruta 8 (Rosario - Florianópolis - Río)
echo "<h3>3. Ruta 8 - Paradas configuradas</h3>";
$stmt = $pdo->prepare('SELECT rp.orden, t.nombre, t.ciudad, t.latitud, t.longitud, rp.es_origen, rp.es_destino FROM ruta_paradas rp INNER JOIN terminal_transporte t ON rp.idTerminal = t.idTerminal WHERE rp.idRuta = 8 ORDER BY rp.orden');
$stmt->execute();
echo "<table border='1'>";
echo "<tr><th>Orden</th><th>Terminal</th><th>Ciudad</th><th>Lat</th><th>Lng</th><th>Origen</th><th>Destino</th></tr>";
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "<tr>";
    echo "<td>" . $row['orden'] . "</td>";
    echo "<td>" . $row['nombre'] . "</td>";
    echo "<td>" . $row['ciudad'] . "</td>";
    echo "<td>" . ($row['latitud'] ?? 'NULL') . "</td>";
    echo "<td>" . ($row['longitud'] ?? 'NULL') . "</td>";
    echo "<td>" . ($row['es_origen'] ? 'SÍ' : '') . "</td>";
    echo "<td>" . ($row['es_destino'] ? 'SÍ' : '') . "</td>";
    echo "</tr>";
}
echo "</table>";

// 5. Verificar Google Maps API Key
echo "<h3>4. Google Maps API Key</h3>";
if (defined('GOOGLE_MAPS_API_KEY')) {
    $key = GOOGLE_MAPS_API_KEY;
    $masked = substr($key, 0, 10) . "..." . substr($key, -10);
    echo "API Key definida: <code>$masked</code><br>";
} else {
    echo "<span style='color:red;'>ERROR: GOOGLE_MAPS_API_KEY no está definida</span><br>";
}

// 6. Verificar entorno
echo "<h3>5. Entorno</h3>";
echo "APP_ENV: " . (defined('APP_ENV') ? APP_ENV : 'NO DEFINIDO') . "<br>";
echo "DB_HOST: " . (defined('DB_HOST') ? DB_HOST : 'NO DEFINIDO') . "<br>";
echo "Database: " . (defined('DB_NAME') ? DB_NAME : 'NO DEFINIDO') . "<br>";
?>
