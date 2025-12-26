<?php
/**
 * Extractor mejorado - Servicio 589 y todas sus dependencias
 */

$backup_file = 'C:\Users\SisteMANZ\Documents\Downloads\metelebr_metelebrasil (1).sql';
$servicio_id = 589;

echo "📊 Extrayendo servicio ID: $servicio_id\n";
echo str_repeat("=", 80) . "\n\n";

// Leer el archivo completo (es más rápido para búsquedas)
echo "Cargando archivo de backup... (esto toma un momento)\n";
$sql_content = file_get_contents($backup_file);
echo "Archivo cargado. Buscando datos...\n\n";

$extract_sql = "-- ============================================\n";
$extract_sql .= "-- Extracción de Servicio ID: $servicio_id\n";
$extract_sql .= "-- Fecha: " . date('Y-m-d H:i:s') . "\n";
$extract_sql .= "-- ============================================\n\n";

// 1. SERVICIO
echo "🔍 Buscando servicio...\n";
if (preg_match("/INSERT INTO `servicio`[^V]*VALUES\s*\(($servicio_id,[^;]+)\);/", $sql_content, $m)) {
    $extract_sql .= "-- SERVICIO\n";
    $extract_sql .= "INSERT INTO `servicio` VALUES (" . $m[1] . ");\n\n";
    echo "   ✅ Servicio encontrado\n";
} else {
    echo "   ❌ Servicio NO encontrado\n";
}

// 2. SERVICIO SALIDAS - buscar por idServicio
echo "🔍 Buscando salidas...\n";
preg_match_all("/INSERT INTO `servicio_salidas`[^V]*VALUES\s*\((\d+,$servicio_id,[^;]+)\);/m", $sql_content, $matches);
if (!empty($matches[1])) {
    $extract_sql .= "-- SERVICIO SALIDAS\n";
    
    $salida_ids = [];
    foreach ($matches[1] as $salida_data) {
        $extract_sql .= "INSERT INTO `servicio_salidas` VALUES (" . $salida_data . ");\n";
        
        // Extraer idServicioSalidas
        if (preg_match('/^(\d+),/', $salida_data, $id_match)) {
            $salida_ids[] = $id_match[1];
        }
    }
    echo "   ✅ Salidas encontradas: " . count($salida_ids) . "\n";
    $extract_sql .= "\n";
    
    // 3. SERVICIO SALIDAS TARIFAS
    echo "🔍 Buscando tarifas...\n";
    preg_match_all("/INSERT INTO `servicio_salidas_tarifas`[^V]*VALUES\s*\(([^)]+)\);/m", $sql_content, $tarifa_matches);
    
    if (!empty($tarifa_matches[1])) {
        $tarifa_count = 0;
        $extract_sql .= "-- SERVICIO SALIDAS TARIFAS\n";
        
        foreach ($tarifa_matches[1] as $tarifa_data) {
            // Verificar si esta tarifa pertenece a alguna salida
            foreach ($salida_ids as $sid) {
                if (strpos($tarifa_data, ",$sid,") !== false) {
                    $extract_sql .= "INSERT INTO `servicio_salidas_tarifas` VALUES (" . $tarifa_data . ");\n";
                    $tarifa_count++;
                    break;
                }
            }
        }
        if ($tarifa_count > 0) {
            echo "   ✅ Tarifas encontradas: $tarifa_count\n";
            $extract_sql .= "\n";
        }
    }
    
    // 4. SERVICIO SALIDAS ADICIONALES
    echo "🔍 Buscando servicios adicionales...\n";
    preg_match_all("/INSERT INTO `servicio_salidas_adicionales`[^V]*VALUES\s*\(([^)]+)\);/m", $sql_content, $adicionales_matches);
    
    if (!empty($adicionales_matches[1])) {
        $adicionales_count = 0;
        $extract_sql .= "-- SERVICIO SALIDAS ADICIONALES\n";
        
        foreach ($adicionales_matches[1] as $adicional_data) {
            foreach ($salida_ids as $sid) {
                if (strpos($adicional_data, ",$sid,") !== false) {
                    $extract_sql .= "INSERT INTO `servicio_salidas_adicionales` VALUES (" . $adicional_data . ");\n";
                    $adicionales_count++;
                    break;
                }
            }
        }
        if ($adicionales_count > 0) {
            echo "   ✅ Servicios adicionales encontrados: $adicionales_count\n";
            $extract_sql .= "\n";
        }
    }
} else {
    echo "   ⚠️  No se encontraron salidas para este servicio\n";
}

// 5. SERVICIO IMÁGENES
echo "🔍 Buscando imágenes...\n";
preg_match_all("/INSERT INTO `servicio_img`[^V]*VALUES\s*\(([^)]+)\);/m", $sql_content, $img_matches);

if (!empty($img_matches[1])) {
    $img_count = 0;
    $extract_sql .= "-- SERVICIO IMÁGENES\n";
    
    foreach ($img_matches[1] as $img_data) {
        if (preg_match("/^\\d+,$servicio_id,/", $img_data)) {
            $extract_sql .= "INSERT INTO `servicio_img` VALUES (" . $img_data . ");\n";
            $img_count++;
        }
    }
    if ($img_count > 0) {
        echo "   ✅ Imágenes encontradas: $img_count\n";
        $extract_sql .= "\n";
    }
}

// 6. SERVICIO COMISIÓN PRESTADOR
echo "🔍 Buscando comisiones...\n";
preg_match_all("/INSERT INTO `servicio_comision_prestador`[^V]*VALUES\s*\(([^)]+)\);/m", $sql_content, $com_matches);

if (!empty($com_matches[1])) {
    $com_count = 0;
    $extract_sql .= "-- SERVICIO COMISIÓN PRESTADOR\n";
    
    foreach ($com_matches[1] as $com_data) {
        if (preg_match("/,\d+,$servicio_id,/", ",$com_data,")) {
            $extract_sql .= "INSERT INTO `servicio_comision_prestador` VALUES (" . $com_data . ");\n";
            $com_count++;
        }
    }
    if ($com_count > 0) {
        echo "   ✅ Comisiones encontradas: $com_count\n";
        $extract_sql .= "\n";
    }
}

// Guardar archivo
$output_file = 'C:\xampp\htdocs\metelebrasil_dev\servicio_589_extract.sql';
file_put_contents($output_file, $extract_sql);

echo "\n" . str_repeat("=", 80) . "\n";
echo "✅ EXTRACCIÓN COMPLETADA\n";
echo str_repeat("=", 80) . "\n";
echo "📄 Archivo guardado: servicio_589_extract.sql\n";
echo "📊 Tamaño: " . filesize($output_file) . " bytes\n\n";

echo "⚠️  IMPORTANTE ANTES DE IMPORTAR:\n";
echo "   1. Revisa el archivo para verificar que los IDs no conflictúen\n";
echo "   2. Si hay conflictos, puedes renumerar los IDs en el archivo SQL\n";
echo "   3. Verifica que el idPrestador exista en la nueva BD\n\n";

echo "Próximo paso: Importar en la base de datos nueva en producción\n";
?>
