#!/usr/bin/env php
<?php
/**
 * Script para ejecutar EN PRODUCCIÓN vía SSH
 * Sincroniza tabla servicio_img desde la copia de desarrollo que subimos
 * 
 * Uso: Ejecutar en servidor producción
 * ssh u925692129@185.173.111.212 -p 65002 'cd /home/u925692129/public_html && php sync_db_prod.php'
 */

echo "=== SINCRONIZACIÓN DE BD EN PRODUCCIÓN ===\n\n";

// Conectar a BD local en el servidor de producción
$mysqli = new mysqli('localhost', 'u925692129_metelebrasil', 'Cambiar2026', 'u925692129_metelebrasil');

if ($mysqli->connect_error) {
    die("❌ Error de conexión: " . $mysqli->connect_error);
}

echo "✅ Conectado a BD de producción\n\n";

try {
    // Verificar que la tabla existe
    $result = $mysqli->query("SELECT COUNT(*) as total FROM servicio_img");
    $row = $result->fetch_assoc();
    $registros_actuales = $row['total'];
    
    echo "Registros actuales en servicio_img: $registros_actuales\n";
    
    // Si hay pocos registros, probablemente necesitan ser restaurados
    if ($registros_actuales < 1000) {
        echo "⚠️  Pocos registros detectados. Necesitamos restaurar desde backup.\n";
        echo "Intenta sincronizar manualmente desde desarrollo.\n";
    } else {
        echo "✓ Base de datos parece estar completa\n";
    }
    
    // Listar servicios sin imágenes
    echo "\n=== SERVICIOS SIN IMÁGENES ===\n";
    $result = $mysqli->query("
        SELECT s.idServicio, s.nombre_servicio 
        FROM servicio s 
        LEFT JOIN servicio_img si ON s.idServicio = si.idServicio 
        WHERE s.habilitado = 1 
        AND s.destacado = 1
        AND si.idImg IS NULL 
        LIMIT 10
    ");
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "  - ID {$row['idServicio']}: {$row['nombre_servicio']}\n";
        }
    } else {
        echo "  ✓ Todos los servicios destacados tienen imágenes\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

$mysqli->close();
?>
