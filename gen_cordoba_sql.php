<?php
/**
 * Generar SQL seguro solo para servicios de Córdoba
 */

putenv('APP_ENV=dev');

try {
    $dev = new PDO(
        'mysql:host=localhost;dbname=metelebrasil;charset=utf8mb4',
        'root',
        '',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (Exception $e) {
    die("[ERROR] No se pudo conectar a dev: " . $e->getMessage() . "\n");
}

$servicios = [767, 763, 735, 765, 734, 768];

echo "Generando SQL para servicios Córdoba...\n";

$output = "";

// Obtener IDs de salidas para estos servicios
$salidaIds = [];
$stmt = $dev->prepare("SELECT idServicioSalidas FROM servicio_salidas WHERE idServicio IN (" . implode(',', $servicios) . ")");
$stmt->execute();
foreach ($stmt->fetchAll(PDO::FETCH_COLUMN) as $id) {
    $salidaIds[] = $id;
}

if (empty($salidaIds)) {
    die("ERROR: No se encontraron salidas\n");
}

$salidaPlaceholder = implode(',', $salidaIds);

// Exportar con mysqldump solo para estos IDs
$tables = [
    'servicio' => "WHERE idServicio IN (" . implode(',', $servicios) . ")",
    'servicio_img' => "WHERE idServicio IN (" . implode(',', $servicios) . ")",
    'servicio_salidas' => "WHERE idServicio IN (" . implode(',', $servicios) . ")",
    'servicio_salidas_tarifas' => "WHERE idServicioSalidas IN ($salidaPlaceholder)"
];

foreach ($tables as $table => $where) {
    // mysqldump no soporta WHERE en command line bien, así que lo hacemos manual
    $query = "SELECT * FROM $table $where";
    $stmt = $dev->prepare($query);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($rows as $row) {
        $cols = implode('`,`', array_keys($row));
        $vals = [];
        foreach ($row as $v) {
            $vals[] = is_null($v) ? 'NULL' : "'" . addslashes($v) . "'";
        }
        $output .= "INSERT IGNORE INTO `$table` (`$cols`) VALUES (" . implode(',', $vals) . ");\n";
    }
}

$filename = 'cordoba_insert.sql';
file_put_contents($filename, $output);

$size = filesize($filename);
echo "✓ Archivo generado: $filename\n";
echo "  Tamaño: " . round($size / 1024, 2) . " KB\n";
echo "  Lineas: " . substr_count($output, "\n") . "\n";
?>
