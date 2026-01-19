<?php
/**
 * Migrar terminales existentes a tabla ubicacion
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('admin/classes/conexion.php');
$pdo->exec("SET NAMES utf8mb4");

echo "🔄 Migrando terminales de parada a ubicacion...\n\n";

try {
    // 1. Obtener todas las terminales de parada
    $stmt = $pdo->query("SELECT * FROM parada WHERE tipo = 'terminal' ORDER BY idParada");
    $terminales = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($terminales)) {
        echo "ℹ️  No hay terminales para migrar\n";
        exit;
    }
    
    echo "Encontradas " . count($terminales) . " terminales\n\n";
    
    // 2. Insertar en ubicacion
    $insertados = 0;
    $errores = 0;
    
    foreach ($terminales as $terminal) {
        try {
            // Verificar si ya existe
            $checkStmt = $pdo->prepare("SELECT idUbicacion FROM ubicacion WHERE nombre = :nombre AND tipo = 'terminal'");
            $checkStmt->execute(['nombre' => $terminal['nombre']]);
            
            if ($checkStmt->fetch()) {
                echo "⚠️  {$terminal['nombre']} ya existe en ubicacion\n";
                continue;
            }
            
            // Insertar en ubicacion
            $insertStmt = $pdo->prepare(
                "INSERT INTO ubicacion (nombre, tipo, direccion, ciudad, estado, pais, latitud, longitud, codigo_iata, habilitado) 
                 VALUES (:nombre, 'terminal', :direccion, :ciudad, :estado, :pais, :latitud, :longitud, :codigo_iata, :habilitado)"
            );
            
            $insertStmt->execute([
                'nombre' => $terminal['nombre'],
                'direccion' => $terminal['direccion'],
                'ciudad' => $terminal['ciudad'],
                'estado' => $terminal['estado'],
                'pais' => $terminal['pais'],
                'latitud' => $terminal['latitud'],
                'longitud' => $terminal['longitud'],
                'codigo_iata' => $terminal['codigo_iata'],
                'habilitado' => $terminal['habilitado']
            ]);
            
            $idUbicacion = $pdo->lastInsertId();
            
            // Vincular parada a ubicacion
            $updateStmt = $pdo->prepare("UPDATE parada SET idUbicacion = :idUbicacion WHERE idParada = :idParada");
            $updateStmt->execute(['idUbicacion' => $idUbicacion, 'idParada' => $terminal['idParada']]);
            
            $insertados++;
            echo "✅ {$terminal['nombre']} (ID ubicacion: {$idUbicacion})\n";
            
        } catch (Exception $e) {
            $errores++;
            echo "❌ Error con {$terminal['nombre']}: " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n" . str_repeat("=", 60) . "\n";
    echo "📊 Resumen:\n";
    echo "   ✅ Terminales migradas: {$insertados}\n";
    echo "   ❌ Errores: {$errores}\n";
    echo "\n🎉 Migración completada\n";
    
} catch (Exception $e) {
    echo "❌ Error general: " . $e->getMessage() . "\n";
}
