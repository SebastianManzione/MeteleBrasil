<?php
/**
 * Script para recalcular capacidades reales de modelos de transporte
 * Cuenta SOLO asientos (valor 1) ignorando elementos especiales
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/admin/classes/conexion.php';
require_once __DIR__ . '/admin/classes/transporte.php';

$transporte = new Transporte();

echo "=== RECALCULAR CAPACIDADES V2 ===\n";
echo "Fecha: " . date('Y-m-d H:i:s') . "\n\n";

// Obtener todos los modelos
try {
    $modelos = $transporte->getAllModelos();
    
    foreach ($modelos as $modelo) {
        $idModelo = $modelo['idModelo'];
        $nombre = $modelo['nombre'];
        $capacidadActual = $modelo['capacidad_total'];
        $distribucionJson = json_decode($modelo['distribucion_json'], true);
        
        // Calcular capacidad real
        $capacidadReal = 0;
        if ($distribucionJson && isset($distribucionJson['pisos'])) {
            foreach ($distribucionJson['pisos'] as $piso) {
                if (isset($piso['asientos'])) {
                    foreach ($piso['asientos'] as $fila) {
                        foreach ($fila as $celda) {
                            if ($celda === 1) {
                                $capacidadReal++;
                            }
                        }
                    }
                }
            }
        }
        
        // Actualizar si es diferente
        if ($capacidadReal !== $capacidadActual) {
            $stmt = $pdo->prepare("UPDATE modelo_vehiculo_transporte SET capacidad_total = :capacidad WHERE idModelo = :id");
            $stmt->execute([
                ':capacidad' => $capacidadReal,
                ':id' => $idModelo
            ]);
            
            echo "✓ Modelo $idModelo: $nombre\n";
            echo "  Anterior: $capacidadActual → Actual: $capacidadReal\n";
            echo "  Diferencia: " . ($capacidadReal - $capacidadActual) . " (" . round(($capacidadReal/$capacidadActual)*100, 1) . "%)\n";
            echo "  Elementos especiales: " . (count($distribucionJson['pisos'][0]['asientos'] ?? []) * count($distribucionJson['pisos'][0]['asientos'][0] ?? []) - $capacidadReal) . "\n\n";
        } else {
            echo "→ Modelo $idModelo: $nombre (sin cambios: $capacidadReal)\n\n";
        }
    }
    
    echo "✓ Recálculo completado\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
?>
