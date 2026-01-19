<?php
/**
 * Script para recalcular capacidades reales desde distribucion_json
 * Cuenta solo asientos tipo "1", ignorando elementos especiales
 */

require_once("config/config.php");
require_once("admin/classes/conexion.php");
require_once("admin/classes/transporte.php");

// Obtener todos los modelos
$modelos = getAllModelos();

if (empty($modelos)) {
    die("No hay modelos para procesar");
}

echo "Recalculando capacidades de " . count($modelos) . " modelos...\n\n";

foreach ($modelos as $modelo) {
    $capacidadReal = calcularCapacidadReal($modelo['distribucion_json']);
    $capacidadActual = $modelo['capacidad_total'];
    
    // Actualizar si es diferente
    if ($capacidadReal !== $capacidadActual) {
        $actualizado = updateModelo($modelo['idModelo'], ['capacidad_total' => $capacidadReal]);
        $status = $actualizado ? "✓ ACTUALIZADO" : "✗ ERROR";
        echo sprintf(
            "%s - Modelo %d: %s\n   Capacidad anterior: %d\n   Capacidad real: %d\n\n",
            $status,
            $modelo['idModelo'],
            $modelo['nombre'],
            $capacidadActual,
            $capacidadReal
        );
    } else {
        echo sprintf(
            "✓ CORRECTO   - Modelo %d: %s (Capacidad: %d)\n",
            $modelo['idModelo'],
            $modelo['nombre'],
            $capacidadReal
        );
    }
}

echo "\n✓ Proceso completado\n";

// Mostrar resultado final
$consulta = "SELECT idModelo, nombre, capacidad_total FROM modelo_vehiculo_transporte WHERE habilitado=1 ORDER BY idModelo";
$stmt = $pdo->prepare($consulta);
$stmt->execute();
$resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "\n==== CAPACIDADES FINALES ====\n";
foreach ($resultados as $r) {
    echo sprintf("ID %d: %s - %d asientos\n", $r['idModelo'], $r['nombre'], $r['capacidad_total']);
}
?>
