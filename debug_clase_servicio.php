<?php
require_once("admin/classes/conexion.php");

echo "<h2>Verificar tabla clase_servicio_transporte</h2>";

try {
    // Ver si existe la tabla
    $query = $pdo->query("SHOW TABLES LIKE 'clase_servicio_transporte'");
    $exists = $query->rowCount();
    
    if ($exists > 0) {
        echo "<p style='color: green;'>✓ Tabla existe</p>";
        
        // Ver estructura
        echo "<h3>Estructura:</h3>";
        $query2 = $pdo->query("DESCRIBE clase_servicio_transporte");
        echo "<pre>";
        print_r($query2->fetchAll(PDO::FETCH_ASSOC));
        echo "</pre>";
        
        // Ver datos
        echo "<h3>Datos:</h3>";
        $query3 = $pdo->query("SELECT * FROM clase_servicio_transporte");
        $datos = $query3->fetchAll(PDO::FETCH_ASSOC);
        echo "<p>Total registros: " . count($datos) . "</p>";
        echo "<pre>";
        print_r($datos);
        echo "</pre>";
        
    } else {
        echo "<p style='color: red;'>✗ Tabla NO existe</p>";
        echo "<p>Buscando tablas similares...</p>";
        
        $query = $pdo->query("SHOW TABLES LIKE '%clase%'");
        $tables = $query->fetchAll(PDO::FETCH_COLUMN);
        echo "<pre>";
        print_r($tables);
        echo "</pre>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>ERROR: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<h2>Probar getViajeClasesServicio(5) directamente</h2>";

try {
    require_once("admin/classes/transporte.php");
    $clases = getViajeClasesServicio(5);
    echo "<p>Total clases: " . count($clases) . "</p>";
    echo "<pre>";
    print_r($clases);
    echo "</pre>";
} catch (Exception $e) {
    echo "<p style='color: red;'>ERROR: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>
