<?php
/**
 * Restaurar salidas faltantes para servicios 767 y 768
 * Se extrae del backup y se inserta en dev
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

try {
    $backup = new PDO(
        'mysql:host=localhost;dbname=metelebrasil_bkp_tmp2;charset=utf8mb4',
        'root',
        '',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (Exception $e) {
    die("[ERROR] No se pudo conectar a backup: " . $e->getMessage() . "\n");
}

echo "=== Restaurar Salidas Faltantes (767, 768) ===\n\n";

$servicios = [767, 768];
$pdo->beginTransaction();

try {
    foreach ($servicios as $idSvc) {
        echo "Procesando servicio $idSvc...\n";
        
        // Extraer salidas del backup
        $stmt = $backup->prepare("SELECT * FROM servicio_salidas WHERE idServicio = ?");
        $stmt->execute([$idSvc]);
        $salidas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($salidas)) {
            echo "  ✗ Sin salidas en backup\n";
            continue;
        }
        
        echo "  Encontradas " . count($salidas) . " salidas\n";
        
        // Insertar cada salida y sus tarifas
        foreach ($salidas as $salida) {
            $idSalidaAnterior = $salida['idServicioSalidas'];
            
            // Insertar salida
            $cols = implode(', ', array_keys($salida));
            $placeholders = implode(', ', array_fill(0, count($salida), '?'));
            $sql = "INSERT IGNORE INTO servicio_salidas ($cols) VALUES ($placeholders)";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array_values($salida));
            $idSalidaNueva = $pdo->lastInsertId() ?: $salida['idServicioSalidas'];
            
            // Extraer y insertar tarifas
            $stmt = $backup->prepare("SELECT * FROM servicio_salidas_tarifas WHERE idServicioSalidas = ?");
            $stmt->execute([$idSalidaAnterior]);
            $tarifas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($tarifas as $tarifa) {
                $tarifa['idServicioSalidas'] = $idSalidaNueva;
                $cols = implode(', ', array_keys($tarifa));
                $placeholders = implode(', ', array_fill(0, count($tarifa), '?'));
                $sql = "INSERT IGNORE INTO servicio_salidas_tarifas ($cols) VALUES ($placeholders)";
                
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array_values($tarifa));
            }
            
            echo "    ✓ Salida " . $salida['fecha'] . " " . $salida['horaSalida'] . " + " . count($tarifas) . " tarifas\n";
        }
    }
    
    $pdo->commit();
    echo "\n[SUCCESS] Salidas restauradas\n";
    
} catch (Exception $e) {
    $pdo->rollBack();
    echo "\n[ERROR] " . $e->getMessage() . "\n";
    exit(1);
}
?>
