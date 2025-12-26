<?php
/**
 * Script de inserción para ejecutar EN PRODUCCIÓN
 * Este script debe correrse en: /home/u925692129/public_html/insert_sync.php
 */

// Conexión a BD producción
$mysqli = new mysqli('localhost', 'u925692129_metelebrasil', 'Cambiar2026', 'u925692129_metelebrasil');
if ($mysqli->connect_error) {
    die("Error de conexión: " . $mysqli->connect_error);
}

// Datos a insertar (se pasarán como JSON en POST o directamente aquí)
$datos = json_decode(file_get_contents('php://input'), true);

if (!$datos || empty($datos)) {
    die("No hay datos para insertar");
}

echo "Iniciando sincronización...\n";

// Limpiar tabla
$mysqli->query("TRUNCATE TABLE servicio_img");

$insertados = 0;
$errores = [];

foreach ($datos as $row) {
    $idImgServicio = (int)$row['idImgServicio'];
    $idServicio = (int)$row['idServicio'];
    $ruta = $mysqli->real_escape_string($row['ruta']);
    $portada = (int)$row['portada'];
    
    $sql = "INSERT INTO servicio_img (idImgServicio, idServicio, ruta, portada) VALUES ($idImgServicio, $idServicio, '$ruta', $portada)";
    
    if ($mysqli->query($sql)) {
        $insertados++;
    } else {
        $errores[] = "Error en {$row['idServicio']}: " . $mysqli->error;
    }
}

echo "✓ Insertados: $insertados\n";
if (!empty($errores)) {
    echo "⚠ Errores: " . count($errores) . "\n";
    echo implode("\n", array_slice($errores, 0, 5));
}

// Verificación
$result = $mysqli->query("SELECT COUNT(*) as total FROM servicio_img");
$row = $result->fetch_assoc();
echo "Total en BD: " . $row['total'] . "\n";

$result = $mysqli->query("SELECT COUNT(DISTINCT idServicio) as servicios FROM servicio_img");
$row = $result->fetch_assoc();
echo "Servicios: " . $row['servicios'] . "\n";

$mysqli->close();
?>
