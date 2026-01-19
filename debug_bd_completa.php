<?php
require_once("admin/classes/conexion.php");

echo "<h2>1. Verificando conexión a BD</h2>";
try {
    $query = $pdo->query("SELECT DATABASE() as db_name");
    $dbName = $query->fetch(PDO::FETCH_ASSOC);
    echo "<p><strong>Base de datos actual: " . $dbName['db_name'] . "</strong></p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>ERROR: " . $e->getMessage() . "</p>";
}

echo "<h2>2. Viajes que existen en viaje_transporte</h2>";
try {
    $consulta = "SELECT idViaje, idRuta, fecha_salida, hora_salida, tipo_tarifa FROM viaje_transporte LIMIT 10";
    $comando = $pdo->prepare($consulta);
    $comando->execute();
    $viajes = $comando->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<pre>";
    echo "Total viajes: " . count($viajes) . "\n\n";
    print_r($viajes);
    echo "</pre>";
} catch (Exception $e) {
    echo "<p style='color: red;'>ERROR: " . $e->getMessage() . "</p>";
}

echo "<h2>3. Clases de servicio (viaje_clase_servicio)</h2>";
try {
    $consulta = "SELECT * FROM viaje_clase_servicio LIMIT 10";
    $comando = $pdo->prepare($consulta);
    $comando->execute();
    $clases = $comando->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<pre>";
    echo "Total clases: " . count($clases) . "\n\n";
    print_r($clases);
    echo "</pre>";
} catch (Exception $e) {
    echo "<p style='color: red;'>ERROR: " . $e->getMessage() . "</p>";
}

echo "<h2>4. Tarifas de clases (viaje_clase_tarifa)</h2>";
try {
    $consulta = "SELECT vct.*, vcs.nombre_clase 
                 FROM viaje_clase_tarifa vct
                 LEFT JOIN viaje_clase_servicio vcs ON vct.idViajeClase = vcs.idViajeClase
                 LIMIT 10";
    $comando = $pdo->prepare($consulta);
    $comando->execute();
    $tarifas = $comando->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<pre>";
    echo "Total tarifas: " . count($tarifas) . "\n\n";
    print_r($tarifas);
    echo "</pre>";
} catch (Exception $e) {
    echo "<p style='color: red;'>ERROR: " . $e->getMessage() . "</p>";
}

echo "<h2>5. Verificar viaje ID=5 específico</h2>";
try {
    $consulta = "SELECT * FROM viaje_transporte WHERE idViaje = 5";
    $comando = $pdo->prepare($consulta);
    $comando->execute();
    $viaje5 = $comando->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<pre>";
    print_r($viaje5);
    echo "</pre>";
    
    if (!empty($viaje5)) {
        echo "<p><strong>Tipo tarifa del viaje 5: " . $viaje5[0]['tipo_tarifa'] . "</strong></p>";
        
        if ($viaje5[0]['tipo_tarifa'] === 'CLASES') {
            echo "<h3>Buscando clases para este viaje...</h3>";
            $consulta2 = "SELECT * FROM viaje_clase_servicio WHERE idViaje = 5";
            $comando2 = $pdo->prepare($consulta2);
            $comando2->execute();
            $clasesViaje5 = $comando2->fetchAll(PDO::FETCH_ASSOC);
            echo "<pre>";
            print_r($clasesViaje5);
            echo "</pre>";
        }
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>ERROR: " . $e->getMessage() . "</p>";
}
?>
