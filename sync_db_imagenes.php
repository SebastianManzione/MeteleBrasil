<?php
/**
 * Sincronizar tabla servicio_img desde desarrollo a producción
 * Este script copia todos los registros de imágenes a la BD de producción
 */

require_once 'config/config.php';

echo "=== SINCRONIZACIÓN DE BD - TABLA servicio_img ===\n";
echo "Sincronizando registros de imágenes de desarrollo a producción...\n\n";

// Conectar a BD local (desarrollo)
$mysqli_dev = new mysqli('localhost', 'root', '', 'metelebrasil');
if ($mysqli_dev->connect_error) {
    die("Error BD DEV: " . $mysqli_dev->connect_error);
}

// Conectar a BD producción
$mysqli_prod = new mysqli('localhost', 'u925692129_metelebrasil', 'Cambiar2026', 'u925692129_metelebrasil');
if ($mysqli_prod->connect_error) {
    die("Error BD PROD: " . $mysqli_prod->connect_error);
}

try {
    // 1. Obtener todas las imágenes de desarrollo
    echo "1. Leyendo imágenes de BD de desarrollo...\n";
    $result = $mysqli_dev->query('SELECT idImg, idServicio, ruta, portada, miniatura FROM servicio_img');
    
    if (!$result) {
        throw new Exception("Error en consulta: " . $mysqli_dev->error);
    }
    
    $imagenes = [];
    while ($row = $result->fetch_assoc()) {
        $imagenes[] = $row;
    }
    
    echo "   ✓ Se encontraron " . count($imagenes) . " imágenes\n\n";
    
    // 2. Limpiar tabla en producción
    echo "2. Limpiando tabla servicio_img en producción...\n";
    if (!$mysqli_prod->query('TRUNCATE TABLE servicio_img')) {
        throw new Exception("Error al limpiar: " . $mysqli_prod->error);
    }
    echo "   ✓ Tabla limpiada\n\n";
    
    // 3. Insertar imágenes en producción
    echo "3. Insertando imágenes en producción...\n";
    
    $insertados = 0;
    $errores = 0;
    
    foreach ($imagenes as $img) {
        $idImg = intval($img['idImg']);
        $idServicio = intval($img['idServicio']);
        $ruta = $mysqli_prod->real_escape_string($img['ruta']);
        $portada = intval($img['portada']);
        $miniatura = intval($img['miniatura']);
        
        $sql = "INSERT INTO servicio_img (idImg, idServicio, ruta, portada, miniatura) 
                VALUES ($idImg, $idServicio, '$ruta', $portada, $miniatura)";
        
        if ($mysqli_prod->query($sql)) {
            $insertados++;
            if ($insertados % 100 == 0) {
                echo "   ... $insertados registros insertados\n";
            }
        } else {
            $errores++;
            if ($errores <= 5) {
                echo "   ✗ Error insertando imagen $idImg: " . $mysqli_prod->error . "\n";
            }
        }
    }
    
    echo "   ✓ Total insertados: $insertados\n";
    if ($errores > 0) {
        echo "   ✗ Total errores: $errores\n";
    }
    
    echo "\n" . str_repeat("=", 60) . "\n";
    echo "✅ SINCRONIZACIÓN COMPLETADA\n";
    echo "   Imágenes sincronizadas: $insertados\n";
    echo "\nAhora las imágenes debería verse en producción.\n";
    echo "Limpia el caché del navegador si no ves cambios.\n";
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
} finally {
    $mysqli_dev->close();
    $mysqli_prod->close();
}
?>
