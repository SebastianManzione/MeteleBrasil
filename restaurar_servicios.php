<?php
/**
 * Script de Restauración de Servicios
 * Corre en el servidor de producción para restaurar los servicios desde la BD local
 */

// Conexión a BD local
$local_host = 'localhost';
$local_user = 'root';
$local_pass = '';
$local_db = 'metelebrasil';

// Conexión a BD producción  
$prod_host = 'localhost';
$prod_user = 'u925692129_metelebrasil';
$prod_pass = 'Cambiar2026';
$prod_db = 'u925692129_metelebrasil';

try {
    echo "🔄 Restaurando servicios...\n";
    echo str_repeat("=", 80) . "\n\n";
    
    // Conectar a BD local
    $local_con = new mysqli($local_host, $local_user, $local_pass, $local_db);
    if ($local_con->connect_error) {
        throw new Exception("Error local: " . $local_con->connect_error);
    }
    
    // Conectar a BD producción
    $prod_con = new mysqli($prod_host, $prod_user, $prod_pass, $prod_db);
    if ($prod_con->connect_error) {
        throw new Exception("Error producción: " . $prod_con->connect_error);
    }
    
    // Contar servicios en local
    $result = $local_con->query("SELECT COUNT(*) FROM servicio");
    $local_count = $result->fetch_row()[0];
    echo "📊 Servicios en BD local: $local_count\n\n";
    
    // Obtener todos los servicios de local
    $servicios = $local_con->query("SELECT * FROM servicio");
    $count = 0;
    
    // Para cada servicio, insertarlo en producción
    while ($row = $servicios->fetch_assoc()) {
        $id = $row['idServicio'];
        
        // Primero eliminar si existe
        $prod_con->query("DELETE FROM servicio WHERE idServicio = $id");
        
        // Construir INSERT
        $cols = array_keys($row);
        $vals = array_map(function($col) use ($row, $prod_con) {
            $val = $row[$col];
            return $val === null ? 'NULL' : $prod_con->real_escape_string($val);
        }, $cols);
        
        $sql = "INSERT INTO servicio (`" . implode("`,`", $cols) . "`) VALUES ('" . implode("','", $vals) . "')";
        $sql = str_replace("'NULL'", "NULL", $sql);
        
        if ($prod_con->query($sql)) {
            $count++;
            echo "✅ Servicio $id restaurado\n";
        } else {
            echo "❌ Error en servicio $id: " . $prod_con->error . "\n";
        }
    }
    
    echo "\n" . str_repeat("=", 80) . "\n";
    echo "🎉 Restauración completada\n";
    echo "📊 Servicios restaurados: $count\n";
    
    $local_con->close();
    $prod_con->close();
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
