<?php
/**
 * Extractor de datos del backup - Servicio 589
 */

$backup_file = 'C:\Users\SisteMANZ\Documents\Downloads\metelebr_metelebrasil (1).sql';
$servicio_id = 589;

echo "📊 Analizando backup para extraer servicio ID: $servicio_id\n";
echo str_repeat("=", 80) . "\n\n";

// Leer archivo en chunks para no saturar memoria
$handle = fopen($backup_file, 'r');
$extracted = [
    'servicio' => null,
    'servicio_img' => [],
    'servicio_salidas' => [],
    'servicio_salidas_tarifas' => [],
    'servicios_adicionales' => [],
    'servicio_salidas_adicionales' => [],
    'servicio_comision_prestador' => [],
    'reservas' => [],
    'reserva_horarios' => [],
    'reserva_tarifas' => [],
    'reserva_pasajeros' => [],
    'reserva_adicionales' => []
];

$current_table = null;
$buffer = '';
$line_count = 0;

while (($line = fgets($handle)) !== false) {
    $line_count++;
    
    // Detectar tabla actual
    if (preg_match('/INSERT INTO `(\w+)`/', $line, $matches)) {
        $current_table = $matches[1];
    }
    
    $buffer .= $line;
    
    // Procesar cuando encuentre ; (fin de INSERT)
    if (strpos($line, ');') !== false) {
        
        // SERVICIO
        if ($current_table === 'servicio' && strpos($buffer, "($servicio_id,") !== false) {
            preg_match("/\($servicio_id,[^)]+\)/", $buffer, $m);
            if (!empty($m)) {
                $extracted['servicio'] = trim($m[0]) . ";";
                echo "✅ Servicio encontrado\n";
            }
        }
        
        // SERVICIO IMÁGENES
        if ($current_table === 'servicio_img' && strpos($buffer, ",$servicio_id,") !== false) {
            preg_match_all("/\(\d+,$servicio_id,[^)]+\)/", $buffer, $matches);
            foreach ($matches[0] as $img) {
                $extracted['servicio_img'][] = trim($img) . ";";
            }
            echo "✅ Imágenes encontradas: " . count($extracted['servicio_img']) . "\n";
        }
        
        // SERVICIO SALIDAS
        if ($current_table === 'servicio_salidas' && strpos($buffer, ",$servicio_id,") !== false) {
            preg_match_all("/\(\d+,$servicio_id,[^)]+\)/", $buffer, $matches);
            foreach ($matches[0] as $salida) {
                $extracted['servicio_salidas'][] = trim($salida) . ";";
                if (preg_match("/^\\((\d+),/", $salida, $m)) {
                    echo "   - Salida ID: {$m[1]}\n";
                }
            }
            echo "✅ Salidas encontradas: " . count($extracted['servicio_salidas']) . "\n";
        }
        
        // SERVICIO SALIDAS TARIFAS
        if ($current_table === 'servicio_salidas_tarifas') {
            foreach ($extracted['servicio_salidas'] as $salida_line) {
                if (preg_match("/^\\((\d+),/", $salida_line, $m)) {
                    $salida_id = $m[1];
                    if (strpos($buffer, ",$salida_id,") !== false) {
                        preg_match_all("/\(\d+,$salida_id,[^)]+\)/", $buffer, $matches);
                        foreach ($matches[0] as $tarifa) {
                            $extracted['servicio_salidas_tarifas'][] = trim($tarifa) . ";";
                        }
                    }
                }
            }
            if (!empty($extracted['servicio_salidas_tarifas'])) {
                echo "✅ Tarifas encontradas: " . count($extracted['servicio_salidas_tarifas']) . "\n";
            }
        }
        
        $buffer = '';
    }
    
    // Mostrar progreso cada 10000 líneas
    if ($line_count % 10000 === 0) {
        echo "   Procesadas $line_count líneas...\n";
    }
}
fclose($handle);

echo "\n" . str_repeat("=", 80) . "\n";
echo "📋 RESUMEN EXTRAÍDO\n";
echo str_repeat("=", 80) . "\n\n";

echo "Servicio: " . (!empty($extracted['servicio']) ? "✅ SÍ" : "❌ NO") . "\n";
echo "Imágenes: " . count($extracted['servicio_img']) . " encontradas\n";
echo "Salidas: " . count($extracted['servicio_salidas']) . " encontradas\n";
echo "Tarifas: " . count($extracted['servicio_salidas_tarifas']) . " encontradas\n";

// Guardar en archivo SQL
$output_file = 'C:\xampp\htdocs\metelebrasil_dev\servicio_589_extract.sql';

$sql = "-- Extracción del Servicio ID 589\n";
$sql .= "-- Fecha: " . date('Y-m-d H:i:s') . "\n";
$sql .= "-- Advertencia: Revisa los IDs antes de importar\n\n";

if (!empty($extracted['servicio'])) {
    $sql .= "-- SERVICIO\n";
    $sql .= "INSERT INTO `servicio` VALUES " . str_replace(');', '', $extracted['servicio']) . ");\n\n";
}

if (!empty($extracted['servicio_img'])) {
    $sql .= "-- IMÁGENES\n";
    $sql .= "INSERT INTO `servicio_img` VALUES ";
    $sql .= implode(", ", array_map(fn($x) => str_replace(');', '', $x), $extracted['servicio_img'])) . ");\n\n";
}

if (!empty($extracted['servicio_salidas'])) {
    $sql .= "-- SALIDAS\n";
    $sql .= "INSERT INTO `servicio_salidas` VALUES ";
    $sql .= implode(", ", array_map(fn($x) => str_replace(');', '', $x), $extracted['servicio_salidas'])) . ");\n\n";
}

if (!empty($extracted['servicio_salidas_tarifas'])) {
    $sql .= "-- TARIFAS\n";
    $sql .= "INSERT INTO `servicio_salidas_tarifas` VALUES ";
    $sql .= implode(", ", array_map(fn($x) => str_replace(');', '', $x), $extracted['servicio_salidas_tarifas'])) . ");\n\n";
}

file_put_contents($output_file, $sql);

echo "\n✅ Datos extraídos guardados en: servicio_589_extract.sql\n";
echo "📄 Puedes revisar el archivo antes de importar.\n";
echo "\nPróximo paso: Importar en la base de datos nueva\n";
?>
