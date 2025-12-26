<?php
/**
 * Restaurar servicios de Córdoba en PRODUCCIÓN
 * Servicios: 767, 763, 735, 765, 734, 768
 * 
 * Se extrae de dev y se inserta en producción de forma segura (INSERT IGNORE)
 */

try {
    // Conexión a DEV (origen)
    $dev = new PDO(
        'mysql:host=localhost;dbname=metelebrasil;charset=utf8mb4',
        'root',
        '',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (Exception $e) {
    die("[ERROR] No se pudo conectar a dev: " . $e->getMessage() . "\n");
}

try {
    // Conexión a PRODUCCIÓN (destino)
    $prod = new PDO(
        'mysql:host=localhost;dbname=u925692129_metelebrasil;charset=utf8mb4',
        'u925692129_metelebrasil',
        'Cambiar2026',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (Exception $e) {
    die("[ERROR] No se pudo conectar a producción: " . $e->getMessage() . "\n");
}

echo "=== Restaurar Servicios Córdoba en PRODUCCIÓN ===\n\n";

$servicios = [767, 763, 735, 765, 734, 768];

echo "[VALIDANDO]\n";

// Verificar cuáles ya existen en prod
$existentes = [];
$nuevos = [];

foreach ($servicios as $id) {
    $stmt = $prod->prepare("SELECT idServicio FROM servicio WHERE idServicio = ?");
    $stmt->execute([$id]);
    if ($stmt->fetch()) {
        $existentes[] = $id;
        echo "  ✓ Servicio $id ya existe (sin cambios)\n";
    } else {
        $nuevos[] = $id;
        echo "  ✗ Servicio $id faltante → será agregado\n";
    }
}

if (empty($nuevos)) {
    echo "\n[INFO] Todos los servicios ya existen en prod. Completado.\n";
    exit(0);
}

echo "\n[EXTRAYENDO de dev]\n";

// Datos a extraer
$datos = [
    'servicios' => [],
    'fotos' => [],
    'salidas' => [],
    'tarifas' => []
];

foreach ($nuevos as $idSvc) {
    echo "  Extrayendo servicio $idSvc...\n";
    
    // Servicio
    $stmt = $dev->prepare("SELECT * FROM servicio WHERE idServicio = ?");
    $stmt->execute([$idSvc]);
    $svc = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($svc) {
        $datos['servicios'][$idSvc] = $svc;
    }
    
    // Fotos
    $stmt = $dev->prepare("SELECT * FROM servicio_img WHERE idServicio = ?");
    $stmt->execute([$idSvc]);
    $fotos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($fotos) {
        $datos['fotos'][$idSvc] = $fotos;
    }
    
    // Salidas
    $stmt = $dev->prepare("SELECT * FROM servicio_salidas WHERE idServicio = ?");
    $stmt->execute([$idSvc]);
    $salidas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($salidas) {
        $datos['salidas'][$idSvc] = $salidas;
        
        // Tarifas por salida
        foreach ($salidas as $salida) {
            $stmt = $dev->prepare("SELECT * FROM servicio_salidas_tarifas WHERE idServicioSalidas = ?");
            $stmt->execute([$salida['idServicioSalidas']]);
            $tarifas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($tarifas) {
                $datos['tarifas'][$salida['idServicioSalidas']] = $tarifas;
            }
        }
    }
}

echo "\n[INSERTANDO en producción]\n";

$prod->beginTransaction();

try {
    $inserts = ['servicios' => 0, 'fotos' => 0, 'salidas' => 0, 'tarifas' => 0];
    
    // Insertar servicios
    foreach ($datos['servicios'] as $idSvc => $row) {
        $cols = implode(', ', array_keys($row));
        $placeholders = implode(', ', array_fill(0, count($row), '?'));
        
        $sql = "INSERT IGNORE INTO servicio ($cols) VALUES ($placeholders)";
        $stmt = $prod->prepare($sql);
        $stmt->execute(array_values($row));
        
        if ($stmt->rowCount() > 0) {
            $inserts['servicios']++;
        }
    }
    
    // Insertar fotos
    foreach ($datos['fotos'] as $idSvc => $fotos) {
        foreach ($fotos as $foto) {
            $cols = implode(', ', array_keys($foto));
            $placeholders = implode(', ', array_fill(0, count($foto), '?'));
            
            $sql = "INSERT IGNORE INTO servicio_img ($cols) VALUES ($placeholders)";
            $stmt = $prod->prepare($sql);
            $stmt->execute(array_values($foto));
            
            if ($stmt->rowCount() > 0) {
                $inserts['fotos']++;
            }
        }
    }
    
    // Insertar salidas
    foreach ($datos['salidas'] as $idSvc => $salidas) {
        foreach ($salidas as $salida) {
            $cols = implode(', ', array_keys($salida));
            $placeholders = implode(', ', array_fill(0, count($salida), '?'));
            
            $sql = "INSERT IGNORE INTO servicio_salidas ($cols) VALUES ($placeholders)";
            $stmt = $prod->prepare($sql);
            $stmt->execute(array_values($salida));
            
            if ($stmt->rowCount() > 0) {
                $inserts['salidas']++;
            }
        }
    }
    
    // Insertar tarifas
    foreach ($datos['tarifas'] as $idSalida => $tarifas) {
        foreach ($tarifas as $tarifa) {
            $cols = implode(', ', array_keys($tarifa));
            $placeholders = implode(', ', array_fill(0, count($tarifa), '?'));
            
            $sql = "INSERT IGNORE INTO servicio_salidas_tarifas ($cols) VALUES ($placeholders)";
            $stmt = $prod->prepare($sql);
            $stmt->execute(array_values($tarifa));
            
            if ($stmt->rowCount() > 0) {
                $inserts['tarifas']++;
            }
        }
    }
    
    $prod->commit();
    
    echo "\n[RESULTADOS]\n";
    echo "✓ Servicios insertados: " . $inserts['servicios'] . "\n";
    echo "✓ Fotos insertadas: " . $inserts['fotos'] . "\n";
    echo "✓ Salidas insertadas: " . $inserts['salidas'] . "\n";
    echo "✓ Tarifas insertadas: " . $inserts['tarifas'] . "\n";
    
    echo "\n[SUCCESS] Servicios Córdoba restaurados en producción\n";
    
} catch (Exception $e) {
    $prod->rollBack();
    echo "\n[ERROR] " . $e->getMessage() . "\n";
    exit(1);
}
?>
