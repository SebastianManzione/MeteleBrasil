<?php
/**
 * Verificar integridad de servicios de Córdoba en DEV
 * Servicios: 767, 763, 735, 765, 734, 768
 */

putenv('APP_ENV=dev');

try {
    $pdo = new PDO(
        'mysql:host=localhost;dbname=metelebrasil;charset=utf8mb4',
        'root',
        '',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (Exception $e) {
    die("[ERROR] No se pudo conectar a BD dev: " . $e->getMessage() . "\n");
}

echo "=== Verificar Servicios Córdoba ===\n\n";

$servicios = [767, 763, 735, 765, 734, 768];

foreach ($servicios as $idSvc) {
    echo "Servicio $idSvc:\n";
    
    // Datos del servicio
    $stmt = $pdo->prepare("SELECT idServicio, nombre_servicio FROM servicio WHERE idServicio = ?");
    $stmt->execute([$idSvc]);
    $svc = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$svc) {
        echo "  ✗ NO EXISTE\n\n";
        continue;
    }
    
    echo "  Nombre: " . $svc['nombre_servicio'] . "\n";
    
    // Fotos
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM servicio_img WHERE idServicio = ?");
    $stmt->execute([$idSvc]);
    $fotos = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "  Fotos: " . $fotos['total'] . "\n";
    
    // Salidas
    $stmt = $pdo->prepare("SELECT idServicioSalidas, fecha, horaSalida FROM servicio_salidas WHERE idServicio = ? ORDER BY fecha DESC LIMIT 5");
    $stmt->execute([$idSvc]);
    $salidas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "  Salidas: " . count($salidas) . " (últimas 5):\n";
    
    foreach ($salidas as $salida) {
        // Tarifas por salida
        $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM servicio_salidas_tarifas WHERE idServicioSalidas = ?");
        $stmt->execute([$salida['idServicioSalidas']]);
        $tarifas = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo "    - " . $salida['fecha'] . " " . $salida['horaSalida'] . " (ID: " . $salida['idServicioSalidas'] . ", " . $tarifas['total'] . " tarifas)\n";
    }
    
    echo "\n";
}

echo "[SUCCESS] Verificación completada\n";
?>
