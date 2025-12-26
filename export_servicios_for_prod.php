<?php
/**
 * Exportar servicios restaurados para copiar a producción
 * Servicios: 740, 743, 613, 602, 615
 */

echo "=== Exportar Servicios para Producción ===\n\n";

$serviciosIds = [740, 743, 613, 602, 615];
$serviciosStr = implode(',', $serviciosIds);

try {
    $pdo = new PDO('mysql:host=localhost;dbname=metelebrasil', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "[OK] Conectado a dev\n\n";
} catch (Exception $e) {
    die("ERROR: ".$e->getMessage()."\n");
}

$outputFile = __DIR__ . '/backup_servicios_export.sql';
$fp = fopen($outputFile, 'w');

if (!$fp) {
    die("ERROR: No se puede crear archivo de export\n");
}

fwrite($fp, "-- Export servicios: ".implode(', ', $serviciosIds)."\n");
fwrite($fp, "-- Generado: ".date('Y-m-d H:i:s')."\n\n");
fwrite($fp, "SET foreign_key_checks = 0;\n\n");

$tables = [
    'servicio' => 'idServicio',
    'servicio_img' => 'idServicio',
    'servicio_salidas' => 'idServicio',
];

echo "[EXPORTANDO]\n";

foreach ($tables as $table => $idCol) {
    echo "  $table...\n";
    
    $stmt = $pdo->query("SELECT * FROM $table WHERE $idCol IN ($serviciosStr)");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($rows)) {
        echo "    ⚠ Sin datos\n";
        continue;
    }
    
    foreach ($rows as $row) {
        $columns = array_keys($row);
        $values = array_map(function($v) use ($pdo) {
            return $v === null ? 'NULL' : $pdo->quote($v);
        }, array_values($row));
        
        $sql = "REPLACE INTO `$table` (`".implode('`, `', $columns)."`) VALUES (".implode(', ', $values).");\n";
        fwrite($fp, $sql);
    }
    
    echo "    ✓ ".count($rows)." registros\n";
}

// Obtener IDs de salidas
$stmt = $pdo->query("SELECT idServicioSalidas FROM servicio_salidas WHERE idServicio IN ($serviciosStr)");
$salidasIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

if (!empty($salidasIds)) {
    $salidasStr = implode(',', $salidasIds);
    
    $tables2 = [
        'servicio_salidas_tarifas' => 'idServicioSalidas',
        'servicio_salidas_adicionales' => 'idServicioSalidas',
    ];
    
    foreach ($tables2 as $table => $idCol) {
        echo "  $table...\n";
        
        $stmt = $pdo->query("SELECT * FROM $table WHERE $idCol IN ($salidasStr)");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($rows)) {
            echo "    ⚠ Sin datos\n";
            continue;
        }
        
        foreach ($rows as $row) {
            $columns = array_keys($row);
            $values = array_map(function($v) use ($pdo) {
                return $v === null ? 'NULL' : $pdo->quote($v);
            }, array_values($row));
            
            $sql = "REPLACE INTO `$table` (`".implode('`, `', $columns)."`) VALUES (".implode(', ', $values).");\n";
            fwrite($fp, $sql);
        }
        
        echo "    ✓ ".count($rows)." registros\n";
    }
}

// Comisiones
echo "  servicio_comision_prestador...\n";
$stmt = $pdo->query("SELECT * FROM servicio_comision_prestador WHERE idServicio IN ($serviciosStr)");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($rows as $row) {
    $columns = array_keys($row);
    $values = array_map(function($v) use ($pdo) {
        return $v === null ? 'NULL' : $pdo->quote($v);
    }, array_values($row));
    
    $sql = "REPLACE INTO `servicio_comision_prestador` (`".implode('`, `', $columns)."`) VALUES (".implode(', ', $values).");\n";
    fwrite($fp, $sql);
}
echo "    ✓ ".count($rows)." registros\n";

fwrite($fp, "\nSET foreign_key_checks = 1;\n");
fclose($fp);

echo "\n✓ Export guardado: $outputFile\n";
echo "  Tamaño: ".number_format(filesize($outputFile))." bytes\n";
echo "\nPara copiar a producción:\n";
echo "  1. Subir backup_servicios_export.sql al servidor\n";
echo "  2. Subir copy_servicios_to_prod.php al servidor\n";
echo "  3. Ejecutar: php copy_servicios_to_prod.php\n";
?>
