<?php
require_once("admin/classes/conexion.php");

echo "<h2>Estructura de viaje_transporte</h2>";
try {
    $query = $pdo->query("DESCRIBE viaje_transporte");
    $columns = $query->fetchAll(PDO::FETCH_ASSOC);
    echo "<pre>";
    print_r($columns);
    echo "</pre>";
} catch (Exception $e) {
    echo "<p style='color: red;'>ERROR: " . $e->getMessage() . "</p>";
}

echo "<h2>Estructura de viaje_clase_servicio</h2>";
try {
    $query = $pdo->query("DESCRIBE viaje_clase_servicio");
    $columns = $query->fetchAll(PDO::FETCH_ASSOC);
    echo "<pre>";
    print_r($columns);
    echo "</pre>";
} catch (Exception $e) {
    echo "<p style='color: red;'>ERROR: " . $e->getMessage() . "</p>";
}

echo "<h2>Estructura de viaje_clase_tarifa</h2>";
try {
    $query = $pdo->query("DESCRIBE viaje_clase_tarifa");
    $columns = $query->fetchAll(PDO::FETCH_ASSOC);
    echo "<pre>";
    print_r($columns);
    echo "</pre>";
} catch (Exception $e) {
    echo "<p style='color: red;'>ERROR: " . $e->getMessage() . "</p>";
}

echo "<h2>Ver si hay datos en viaje_clase_tarifa</h2>";
try {
    $query = $pdo->query("SELECT COUNT(*) as total FROM viaje_clase_tarifa");
    $result = $query->fetch(PDO::FETCH_ASSOC);
    echo "<p><strong>Total registros: " . $result['total'] . "</strong></p>";
    
    if ($result['total'] > 0) {
        $query2 = $pdo->query("SELECT * FROM viaje_clase_tarifa LIMIT 10");
        $data = $query2->fetchAll(PDO::FETCH_ASSOC);
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>ERROR: " . $e->getMessage() . "</p>";
}
?>
