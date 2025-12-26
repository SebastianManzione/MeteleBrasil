<?php
// Diagnóstico de imágenes en la BD
require_once 'config/config.php';
require_once 'admin/classes/conexion.php';
require_once 'admin/classes/fotos_servicio.php';

echo "=== DIAGNÓSTICO DETALLADO DE IMÁGENES ===\n\n";

// Test 589
echo "SERVICIO 589 (QUE FUNCIONA):\n";
echo str_repeat("-", 60) . "\n";
$fotos589 = getFotoMiniaturaServicio(589);
echo "getFotoMiniaturaServicio(589) retorna: " . count($fotos589) . " foto(s)\n";
if (!empty($fotos589)) {
    echo "Primera foto:\n";
    print_r($fotos589[0]);
    echo "\nRuta esperada: admin/classes/imgServicio/" . $fotos589[0]['ruta'] . "\n";
}

echo "\n";

// Test otros servicios
echo "SERVICIO 590:\n";
echo str_repeat("-", 60) . "\n";
$fotos590 = getFotoMiniaturaServicio(590);
echo "getFotoMiniaturaServicio(590) retorna: " . count($fotos590) . " foto(s)\n";
if (!empty($fotos590)) {
    echo "Primera foto:\n";
    print_r($fotos590[0]);
}

echo "\n";

echo "SERVICIO 591:\n";
echo str_repeat("-", 60) . "\n";
$fotos591 = getFotoMiniaturaServicio(591);
echo "getFotoMiniaturaServicio(591) retorna: " . count($fotos591) . " foto(s)\n";
if (!empty($fotos591)) {
    echo "Primera foto:\n";
    print_r($fotos591[0]);
}

echo "\n\n";

// Verificar BD directamente
try {
    $conexion = new conexion();
    $pdo = $conexion->conectar();
    
    echo "VERIFICACIÓN DIRECTA EN BD:\n";
    echo str_repeat("=", 60) . "\n\n";
    
    // Servicios con imágenes
    $query = "SELECT DISTINCT idServicio FROM servicio_img ORDER BY idServicio LIMIT 10";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $servicios_con_img = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "Servicios que TIENEN imágenes en BD:\n";
    echo implode(", ", $servicios_con_img) . "\n\n";
    
    // Ver qué dice la miniatura para 589
    $query = "SELECT idImg, idServicio, ruta, miniatura, portada FROM servicio_img WHERE idServicio = 589 LIMIT 3";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "BD - Servicio 589:\n";
    foreach ($resultado as $row) {
        echo "  ID: {$row['idImg']}, Ruta: {$row['ruta']}, Miniatura: {$row['miniatura']}, Portada: {$row['portada']}\n";
    }
    
    // Ver qué dice la miniatura para 590
    $query = "SELECT idImg, idServicio, ruta, miniatura, portada FROM servicio_img WHERE idServicio = 590 LIMIT 3";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "\nBD - Servicio 590:\n";
    if (empty($resultado)) {
        echo "  No hay imágenes registradas\n";
    } else {
        foreach ($resultado as $row) {
            echo "  ID: {$row['idImg']}, Ruta: {$row['ruta']}, Miniatura: {$row['miniatura']}, Portada: {$row['portada']}\n";
        }
    }
    
} catch (Exception $e) {
    echo "Error BD: " . $e->getMessage();
}
?>
