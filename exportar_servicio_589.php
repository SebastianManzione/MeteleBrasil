<?php
/**
 * Exportar servicio 589 completo con todas sus dependencias
 */

require_once('C:\xampp\htdocs\metelebrasil_dev\admin\classes\conexion.php');

try {
    $con = new \conexion();
    $db = $con->conectar();
    
    echo "📊 Exportando Servicio ID 589 - ISLA DE CAMPECHE\n";
    echo str_repeat("=", 80) . "\n\n";
    
    // Obtener datos del servicio
    $result = $db->query("SELECT * FROM servicio WHERE idServicio = 589");
    $servicio = $result->fetch(PDO::FETCH_ASSOC);
    
    if (!$servicio) {
        echo "❌ Servicio no encontrado\n";
        exit(1);
    }
    
    echo "✅ Servicio: {$servicio['nombre_servicio']}\n";
    
    // Obtener salidas
    $result = $db->query("SELECT idServicioSalidas FROM servicio_salidas WHERE idServicio = 589");
    $salidas = $result->fetchAll(PDO::FETCH_COLUMN);
    echo "✅ Salidas: " . count($salidas) . "\n";
    
    // Contar tarifas
    $stmt = $db->prepare("SELECT COUNT(*) FROM servicio_salidas_tarifas WHERE idServicioSalidas IN (" . implode(",", $salidas) . ")");
    $stmt->execute();
    $tarifa_count = $stmt->fetchColumn();
    echo "✅ Tarifas: $tarifa_count\n";
    
    // Contar imágenes
    $result = $db->query("SELECT COUNT(*) FROM servicio_img WHERE idServicio = 589");
    $img_count = $result->fetchColumn();
    echo "✅ Imágenes: $img_count\n";
    
    echo "\n" . str_repeat("=", 80) . "\n";
    echo "📝 Iniciando exportación completa...\n\n";
    
    // Crear archivo SQL completo
    $sql_file = 'C:\xampp\htdocs\metelebrasil_dev\servicio_589_completo.sql';
    $sql_content = "-- ============================================\n";
    $sql_content .= "-- Servicio ID 589 - ISLA DE CAMPECHE\n";
    $sql_content .= "-- Exportado: " . date('Y-m-d H:i:s') . "\n";
    $sql_content .= "-- ============================================\n\n";
    
    // Tabla servicio
    $result = $db->query("SELECT * FROM servicio WHERE idServicio = 589");
    $row = $result->fetch(PDO::FETCH_ASSOC);
    $cols = array_keys($row);
    $sql_content .= "INSERT INTO `servicio` (`" . implode("`,`", $cols) . "`) VALUES (";
    $vals = array_map(function($v) use ($db) { return $v === null ? 'NULL' : $db->quote($v); }, $row);
    $sql_content .= implode(",", $vals) . ");\n\n";
    
    // Tabla servicio_salidas
    $result = $db->query("SELECT * FROM servicio_salidas WHERE idServicio = 589");
    $sql_content .= "-- SALIDAS (" . $result->rowCount() . " registros)\n";
    foreach ($result->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $cols = array_keys($row);
        $sql_content .= "INSERT INTO `servicio_salidas` (`" . implode("`,`", $cols) . "`) VALUES (";
        $vals = array_map(function($v) use ($db) { return $v === null ? 'NULL' : $db->quote($v); }, $row);
        $sql_content .= implode(",", $vals) . ");\n";
    }
    $sql_content .= "\n";
    
    // Tabla servicio_salidas_tarifas
    $in_list = implode(",", $salidas);
    $result = $db->query("SELECT * FROM servicio_salidas_tarifas WHERE idServicioSalidas IN ($in_list)");
    $sql_content .= "-- TARIFAS (" . $result->rowCount() . " registros)\n";
    foreach ($result->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $cols = array_keys($row);
        $sql_content .= "INSERT INTO `servicio_salidas_tarifas` (`" . implode("`,`", $cols) . "`) VALUES (";
        $vals = array_map(function($v) use ($db) { return $v === null ? 'NULL' : $db->quote($v); }, $row);
        $sql_content .= implode(",", $vals) . ");\n";
    }
    $sql_content .= "\n";
    
    // Tabla servicio_img
    $result = $db->query("SELECT * FROM servicio_img WHERE idServicio = 589");
    if ($result->rowCount() > 0) {
        $sql_content .= "-- IMÁGENES (" . $result->rowCount() . " registros)\n";
        foreach ($result->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $cols = array_keys($row);
            $sql_content .= "INSERT INTO `servicio_img` (`" . implode("`,`", $cols) . "`) VALUES (";
            $vals = array_map(function($v) use ($db) { return $v === null ? 'NULL' : $db->quote($v); }, $row);
            $sql_content .= implode(",", $vals) . ");\n";
        }
        $sql_content .= "\n";
    }
    
    // Guardar archivo
    file_put_contents($sql_file, $sql_content);
    $size = filesize($sql_file);
    
    echo "✅ Archivo exportado: servicio_589_completo.sql\n";
    echo "📊 Tamaño: " . number_format($size) . " bytes\n";
    echo "\n" . str_repeat("=", 80) . "\n";
    echo "🚀 Próximo paso: Subir a producción e importar\n";
    echo "Comando: scp ... servicio_589_completo.sql → production\n";
    
} catch(Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
