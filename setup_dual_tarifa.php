<?php
/**
 * Script para agregar soporte a ambos sistemas de tarifas
 * Agrega campo tipo_tarifa a tabla viaje
 */

require_once("admin/classes/conexion.php");

// 1. Agregar columna tipo_tarifa si no existe
$check = "SHOW COLUMNS FROM viaje LIKE 'tipo_tarifa'";
$cmd = $pdo->prepare($check);
$cmd->execute();
$existe = $cmd->fetch();

if (!$existe) {
    $alter = "ALTER TABLE viaje ADD COLUMN tipo_tarifa VARCHAR(20) DEFAULT 'clases' COMMENT 'clases o segmentado'";
    $cmd = $pdo->prepare($alter);
    $cmd->execute();
    echo "✅ Columna tipo_tarifa agregada a tabla viaje\n";
} else {
    echo "ℹ️ Columna tipo_tarifa ya existe\n";
}

// 2. Establecer viaje 5 como tipo 'clases' (tiene datos en viaje_clase_tarifa)
$update = "UPDATE viaje SET tipo_tarifa = 'clases' WHERE idViaje = 5";
$cmd = $pdo->prepare($update);
$cmd->execute();
echo "✅ Viaje 5 configurado como tipo 'clases'\n";

echo "\nNota: Para usar 'segmentado', necesitas crear viajes con tipo_tarifa='segmentado' y datos en viaje_tarifa\n";
?>
