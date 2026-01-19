<?php
require_once("admin/classes/conexion.php");
require_once("admin/classes/convierte_monedas.php");
require_once("admin/classes/transporte.php");

echo "<h1>Debug Conversión de Moneda</h1>";

// Test 1: Verificar tarifas en BD
echo "<h2>1. Tarifas en BD (viaje_clase_tarifa)</h2>";
$sql = "SELECT vct.*, tt.nombre 
        FROM viaje_clase_tarifa vct
        INNER JOIN tipos_tarifa tt ON vct.idTipoTarifa = tt.idTipoTarifa
        WHERE vct.idViajeClase = 1
        LIMIT 5";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$tarifas = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($tarifas)) {
    echo "<p style='color:red;'>NO HAY TARIFAS en viaje_clase_tarifa para idViajeClase=1</p>";
} else {
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Tipo</th><th>Precio</th><th>idMoneda</th></tr>";
    foreach ($tarifas as $t) {
        echo "<tr><td>{$t['nombre']}</td><td>{$t['precio']}</td><td>{$t['idMoneda']}</td></tr>";
    }
    echo "</table>";
}

// Test 2: Verificar tabla moneda_cambio
echo "<h2>2. Tabla moneda_cambio</h2>";
$sql2 = "SELECT * FROM moneda_cambio WHERE idMonedaCambio = 1";
$stmt2 = $pdo->prepare($sql2);
$stmt2->execute();
$cambios = $stmt2->fetchAll(PDO::FETCH_ASSOC);

if (empty($cambios)) {
    echo "<p style='color:red;'>NO HAY REGISTROS en moneda_cambio</p>";
    echo "<p>Crear registro con:</p>";
    echo "<pre>INSERT INTO moneda_cambio (idMonedaCambio, ars, usd, eur, brl) VALUES (1, 1, 1000, 1100, 200);</pre>";
} else {
    echo "<pre>" . print_r($cambios[0], true) . "</pre>";
}

// Test 3: Probar ConvierteMoneda
echo "<h2>3. Test ConvierteMoneda()</h2>";

if (!empty($tarifas)) {
    $precio = $tarifas[0]['precio'];
    $idMonedaOrigen = $tarifas[0]['idMoneda'];
    $idMonedaDestino = 1; // ARS
    
    echo "<p>Convertir: $precio de moneda $idMonedaOrigen a moneda $idMonedaDestino</p>";
    
    $resultado = ConvierteMoneda($idMonedaOrigen, $idMonedaDestino, $precio);
    
    echo "<p><strong>Resultado: $resultado</strong></p>";
    
    if ($resultado == 0) {
        echo "<p style='color:red;'>⚠️ PROBLEMA: La función retorna 0</p>";
        
        // Verificar si la moneda origen existe
        $sqlMoneda = "SELECT * FROM moneda WHERE idMoneda = :id";
        $stmtMoneda = $pdo->prepare($sqlMoneda);
        $stmtMoneda->execute(['id' => $idMonedaOrigen]);
        $moneda = $stmtMoneda->fetch(PDO::FETCH_ASSOC);
        
        if ($moneda) {
            echo "<p>Moneda origen existe: " . $moneda['Symbol'] . "</p>";
        } else {
            echo "<p style='color:red;'>Moneda origen NO EXISTE en tabla moneda</p>";
        }
    }
}

// Test 4: Probar getViajeClaseTarifas
echo "<h2>4. Test getViajeClaseTarifas(1)</h2>";
$tarifasFunc = getViajeClaseTarifas(1);
echo "<pre>" . print_r($tarifasFunc, true) . "</pre>";
?>
