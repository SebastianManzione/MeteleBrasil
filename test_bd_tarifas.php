<?php
require_once("admin/classes/conexion.php");

echo "<h2>Verificando viaje_clase_tarifa para idViajeClase=1</h2>";

try {
    $consulta = "SELECT * FROM viaje_clase_tarifa WHERE idViajeClase = 1";
    $comando = $pdo->prepare($consulta);
    $comando->execute();
    $tarifas = $comando->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<pre>";
    echo "Total tarifas encontradas: " . count($tarifas) . "\n\n";
    print_r($tarifas);
    echo "</pre>";
    
    // También verificar que existe el viaje_clase_servicio
    echo "<h2>Verificando viaje_clase_servicio id=1</h2>";
    $consulta2 = "SELECT * FROM viaje_clase_servicio WHERE idViajeClase = 1";
    $comando2 = $pdo->prepare($consulta2);
    $comando2->execute();
    $clase = $comando2->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<pre>";
    print_r($clase);
    echo "</pre>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>ERROR: " . $e->getMessage() . "</p>";
}
?>
