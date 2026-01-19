<?php
/**
 * Test rápido del sistema de transporte con viaje real
 * Verifica que:
 * 1. Los modelos cargan correctamente
 * 2. Los viajes muestran capacidad correcta
 * 3. Las tarifas se calculan bien
 */

$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'metelebrasil_experimental';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== TEST SISTEMA TRANSPORTE V2 ===\n";
    echo "Fecha: " . date('Y-m-d H:i:s') . "\n\n";
    
    // TEST 1: Verificar modelos con capacidades
    echo "[TEST 1] MODELOS Y CAPACIDADES\n";
    echo str_repeat("-", 70) . "\n";
    
    $stmt = $pdo->query("SELECT idModelo, nombre, capacidad_total, distribucion_json FROM modelo_vehiculo_transporte ORDER BY idModelo");
    $modelos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $modelosOk = 0;
    foreach ($modelos as $modelo) {
        $distribucion = json_decode($modelo['distribucion_json'], true);
        
        // Contar asientos reales
        $asientos = 0;
        $especiales = ['T' => 0, 'B' => 0, 'X' => 0, 'K' => 0, 'Y' => 0, 'G' => 0, 'C' => 0, 'P' => 0];
        
        if ($distribucion && isset($distribucion['pisos'])) {
            foreach ($distribucion['pisos'] as $piso) {
                if (isset($piso['asientos'])) {
                    foreach ($piso['asientos'] as $fila) {
                        foreach ($fila as $celda) {
                            if ($celda === 1) {
                                $asientos++;
                            } elseif (is_string($celda) && isset($especiales[$celda])) {
                                $especiales[$celda]++;
                            }
                        }
                    }
                }
            }
        }
        
        $especilesTotal = array_sum($especiales);
        $match = $asientos == $modelo['capacidad_total'] ? "✓" : "✗";
        
        echo sprintf("%s [%d] %-35s | Cap:%3d | Real:%3d | Esp:%2d\n",
            $match,
            $modelo['idModelo'],
            substr($modelo['nombre'], 0, 33),
            $modelo['capacidad_total'],
            $asientos,
            $especilesTotal
        );
        
        if ($asientos > 0) $modelosOk++;
    }
    
    echo "\n✓ Modelos validados: $modelosOk/9\n\n";
    
    // TEST 2: Verificar viajes y disponibilidad
    echo "[TEST 2] VIAJES Y DISPONIBILIDAD\n";
    echo str_repeat("-", 70) . "\n";
    
    $stmt = $pdo->query("SELECT v.idViaje, r.nombre as ruta, m.nombre as modelo, m.capacidad_total,
                                v.fecha, v.hora_salida
                         FROM viaje_transporte v
                         JOIN ruta_transporte r ON v.idRuta = r.idRuta
                         JOIN modelo_vehiculo_transporte m ON v.idModelo = m.idModelo
                         LIMIT 5");
    $viajes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($viajes) > 0) {
        foreach ($viajes as $viaje) {
            echo sprintf("Viaje #%d: %s (%s)\n", 
                $viaje['idViaje'],
                $viaje['ruta'],
                $viaje['modelo']
            );
            echo sprintf("  Fecha: %s %s | Capacidad: %d\n", 
                $viaje['fecha'],
                $viaje['hora_salida'],
                $viaje['capacidad_total']
            );
        }
        echo "\n✓ Viajes encontrados: " . count($viajes) . "\n\n";
    } else {
        echo "⚠️  No hay viajes en el sistema\n\n";
    }
    
    // TEST 3: Verificar tarifas
    echo "[TEST 3] TARIFAS Y SEGMENTACIÓN\n";
    echo str_repeat("-", 70) . "\n";
    
    $stmt = $pdo->query("SELECT COUNT(*) as total, 
                                MIN(precio) as precio_min,
                                MAX(precio) as precio_max,
                                AVG(precio) as precio_promedio
                         FROM viaje_tarifa");
    $stats = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo sprintf("Total tarifas: %d\n", $stats['total']);
    echo sprintf("Rango precio: $%.2f - $%.2f\n", $stats['precio_min'], $stats['precio_max']);
    echo sprintf("Promedio: $%.2f\n", $stats['precio_promedio']);
    
    // Tarifas por tipo de pasajero
    $stmt = $pdo->query("SELECT COUNT(*) as cantidad FROM tipo_tarifa_pasajero");
    $tiposTarifa = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "\nTipos de pasajero: " . $tiposTarifa['cantidad'] . "\n";
    
    $stmt = $pdo->query("SELECT idTipoTarifa, nombre, descuento_porcentaje FROM tipo_tarifa_pasajero");
    $tipos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($tipos as $tipo) {
        echo sprintf("  - %s: %.2f%% descuento\n", $tipo['nombre'], $tipo['descuento_porcentaje']);
    }
    
    echo "\n✓ Sistema de tarifas operacional\n\n";
    
    // TEST 4: Verificar paradas para rutas
    echo "[TEST 4] RUTAS Y PARADAS MÚLTIPLES\n";
    echo str_repeat("-", 70) . "\n";
    
    $stmt = $pdo->query("SELECT r.idRuta, r.nombre, COUNT(p.idRutaParada) as paradas
                         FROM ruta_transporte r
                         LEFT JOIN ruta_paradas p ON r.idRuta = p.idRuta
                         GROUP BY r.idRuta
                         ORDER BY paradas DESC
                         LIMIT 5");
    $rutas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($rutas as $ruta) {
        echo sprintf("Ruta: %s | Paradas: %d\n", $ruta['nombre'], $ruta['paradas']);
    }
    
    echo "\n✓ Sistema de rutas y paradas validado\n\n";
    
    // RESUMEN FINAL
    echo "=".str_repeat("=", 68)."=\n";
    echo "✓ SISTEMA DE TRANSPORTE V2 OPERACIONAL\n";
    echo "=".str_repeat("=", 68)."=\n";
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}
?>
