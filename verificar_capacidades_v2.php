<?php
/**
 * Verificar capacidades de modelos después de redistribución
 */

$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'metelebrasil_experimental';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== VERIFICACIÓN CAPACIDADES V2 ===\n";
    echo "Fecha: " . date('Y-m-d H:i:s') . "\n\n";
    
    $stmt = $pdo->query("SELECT idModelo, nombre, capacidad_total, distribucion_json FROM modelo_vehiculo_transporte ORDER BY idModelo");
    $modelos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $totalAsientos = 0;
    
    foreach ($modelos as $modelo) {
        $idModelo = $modelo['idModelo'];
        $nombre = $modelo['nombre'];
        $capacidadDb = $modelo['capacidad_total'];
        $distribucion = json_decode($modelo['distribucion_json'], true);
        
        // Contar asientos reales
        $asientosReales = 0;
        $totalCeldas = 0;
        
        if ($distribucion && isset($distribucion['pisos'])) {
            foreach ($distribucion['pisos'] as $piso) {
                if (isset($piso['asientos'])) {
                    foreach ($piso['asientos'] as $fila) {
                        foreach ($fila as $celda) {
                            $totalCeldas++;
                            if ($celda === 1) {
                                $asientosReales++;
                            }
                        }
                    }
                }
            }
        }
        
        $doblePiso = $distribucion['doblePiso'] ?? false ? "✓ DP" : "  -";
        $porciento = $capacidadDb > 0 ? round(($asientosReales / $capacidadDb) * 100, 1) : 0;
        
        echo sprintf("[%d] %-40s | DB:%3d | Real:%3d | Celdas:%4d | %s\n", 
            $idModelo, 
            substr($nombre, 0, 38), 
            $capacidadDb, 
            $asientosReales,
            $totalCeldas,
            $doblePiso
        );
        
        $totalAsientos += $asientosReales;
    }
    
    echo "\n✓ Total asientos en sistema: $totalAsientos\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
?>
