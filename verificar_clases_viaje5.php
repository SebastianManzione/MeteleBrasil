<?php
require_once("admin/classes/conexion.php");

echo "<h1>Clases disponibles para Viaje 5</h1>";

$sql = "SELECT * FROM viaje_clase_servicio WHERE idViaje = 5";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$clases = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($clases)) {
    echo "<p style='color:red;'><strong>NO HAY CLASES - Viaje 5 no tiene registros en viaje_clase_servicio</strong></p>";
    
    echo "<h2>Insertar clase de ejemplo:</h2>";
    echo "<pre>";
    echo "INSERT INTO viaje_clase_servicio (idViaje, idClaseServicio, asientos_totales, asientos_disponibles, precio_base, idMoneda, comisiona, habilitado)
VALUES (5, 1, 30, 30, 5000, 1, 1, 1);";
    echo "</pre>";
    
    echo "<form method='POST'>";
    echo "<button type='submit' name='insertar'>Insertar Clase Ahora</button>";
    echo "</form>";
    
    if (isset($_POST['insertar'])) {
        $sqlInsert = "INSERT INTO viaje_clase_servicio (idViaje, idClaseServicio, asientos_totales, asientos_disponibles, precio_base, idMoneda, comisiona, habilitado)
        VALUES (5, 1, 30, 30, 5000, 1, 1, 1)";
        $pdo->exec($sqlInsert);
        
        $idViajeClase = $pdo->lastInsertId();
        
        echo "<p style='color:green;'>✓ Clase insertada con ID: $idViajeClase</p>";
        
        // Insertar tarifas
        $tarifas = [
            [1, 5000], // Adulto
            [2, 3500], // Niño (70%)
            [3, 4250], // Senior (85%)
            [4, 4000]  // Estudiante (80%)
        ];
        
        foreach ($tarifas as $t) {
            $sqlTarifa = "INSERT INTO viaje_clase_tarifa (idViajeClase, idTipoTarifa, precio, idMoneda, comisiona)
            VALUES ($idViajeClase, {$t[0]}, {$t[1]}, 1, 1)";
            $pdo->exec($sqlTarifa);
        }
        
        echo "<p style='color:green;'>✓ 4 tarifas insertadas</p>";
        echo "<p><a href='servicio_contransporte.php?id=5'>→ Ir a viaje 5</a></p>";
    }
    
} else {
    echo "<p style='color:green;'><strong>✓ " . count($clases) . " clase(s) encontrada(s)</strong></p>";
    
    echo "<table border='1' cellpadding='10'>";
    echo "<tr><th>ID</th><th>idClaseServicio</th><th>Asientos</th><th>Precio Base</th><th>Moneda</th></tr>";
    
    foreach ($clases as $c) {
        echo "<tr>";
        echo "<td>" . $c['idViajeClase'] . "</td>";
        echo "<td>" . $c['idClaseServicio'] . "</td>";
        echo "<td>" . $c['asientos_totales'] . "/" . $c['asientos_disponibles'] . "</td>";
        echo "<td>" . $c['precio_base'] . "</td>";
        echo "<td>" . $c['idMoneda'] . "</td>";
        echo "</tr>";
        
        // Ver tarifas de esta clase
        $sqlTarifas = "SELECT vct.*, tt.nombre 
                       FROM viaje_clase_tarifa vct
                       INNER JOIN tipos_tarifa tt ON vct.idTipoTarifa = tt.idTipoTarifa
                       WHERE vct.idViajeClase = :id";
        $stmtTarifas = $pdo->prepare($sqlTarifas);
        $stmtTarifas->execute(['id' => $c['idViajeClase']]);
        $tarifas = $stmtTarifas->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<tr><td colspan='5'>";
        echo "<strong>Tarifas:</strong> ";
        if (empty($tarifas)) {
            echo "<span style='color:orange;'>Sin tarifas</span>";
        } else {
            echo "<ul>";
            foreach ($tarifas as $tar) {
                echo "<li>" . $tar['nombre'] . ": $" . $tar['precio'] . "</li>";
            }
            echo "</ul>";
        }
        echo "</td></tr>";
    }
    
    echo "</table>";
}
?>
