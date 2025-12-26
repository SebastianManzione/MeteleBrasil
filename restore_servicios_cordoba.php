<?php
/**
 * Restaurar servicios de Córdoba en DEV
 * Servicios: 767, 763, 735, 765, 734, 768
 * 
 * Proceso seguro:
 * 1. Extrae de backup_tmp.sql (metelebrasil_bkp_tmp2)
 * 2. Verifica si existen en metelebrasil
 * 3. Si NO existen → inserta (sin eliminar nada)
 * 4. Si SÍ existen → reporta sin tocar
 */

// Detectar entorno
if (getenv('APP_ENV') === false) {
    putenv('APP_ENV=dev');
}

// Conexión directa PDO
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

if (!$pdo) {
    die("[ERROR] No se pudo conectar a BD dev\n");
}

echo "=== Restaurar Servicios Córdoba ===\n";
echo "Servicios a restaurar: 767, 763, 735, 765, 734, 768\n\n";

$servicios = [767, 763, 735, 765, 734, 768];

// Verificar cuáles existen
echo "[VERIFICANDO]\n";
$existentes = [];
$faltantes = [];

foreach ($servicios as $id) {
    $stmt = $pdo->prepare("SELECT idServicio FROM servicio WHERE idServicio = ?");
    $stmt->execute([$id]);
    if ($stmt->fetch()) {
        $existentes[] = $id;
        echo "  ✓ Servicio $id YA EXISTE en dev (sin cambios)\n";
    } else {
        $faltantes[] = $id;
        echo "  ✗ Servicio $id faltante → será restaurado\n";
    }
}

if (empty($faltantes)) {
    echo "\n[INFO] Todos los servicios ya existen en dev. Nada que hacer.\n";
    exit(0);
}

echo "\n[EXTRAYENDO del backup]\n";

// Conectar a BD backup para extraer datos
try {
    $backup_db = new PDO(
        'mysql:host=localhost;dbname=metelebrasil_bkp_tmp2;charset=utf8mb4',
        'root',
        '',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (Exception $e) {
    die("[ERROR] No se puede conectar a backup: " . $e->getMessage() . "\n");
}

// Extraer cada servicio y sus relaciones
$datos = [
    'servicios' => [],
    'fotos' => [],
    'salidas' => [],
    'tarifas' => [],
    'adicionales' => []
];

foreach ($faltantes as $idSvc) {
    echo "  Extrayendo servicio $idSvc...\n";
    
    // Servicio
    $stmt = $backup_db->prepare("SELECT * FROM servicio WHERE idServicio = ?");
    $stmt->execute([$idSvc]);
    $svc = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($svc) {
        $datos['servicios'][$idSvc] = $svc;
        echo "    - Servicio: OK\n";
    }
    
    // Fotos
    $stmt = $backup_db->prepare("SELECT * FROM servicio_img WHERE idServicio = ?");
    $stmt->execute([$idSvc]);
    $fotos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($fotos) {
        $datos['fotos'][$idSvc] = $fotos;
        echo "    - Fotos: " . count($fotos) . " imágenes\n";
    }
    
    // Salidas
    $stmt = $backup_db->prepare("SELECT * FROM servicio_salidas WHERE idServicio = ?");
    $stmt->execute([$idSvc]);
    $salidas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($salidas) {
        $datos['salidas'][$idSvc] = $salidas;
        echo "    - Salidas: " . count($salidas) . "\n";
        
        // Tarifas por salida
        foreach ($salidas as $salida) {
            $stmt = $backup_db->prepare("SELECT * FROM servicio_salidas_tarifas WHERE idServicioSalidas = ?");
            $stmt->execute([$salida['idServicioSalidas']]);
            $tarifas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($tarifas) {
                $datos['tarifas'][$salida['idServicioSalidas']] = $tarifas;
            }
        }
    }
}

echo "\n[INSERTANDO en dev]\n";

// Iniciar transacción
$pdo->beginTransaction();

try {
    $inserts = ['servicios' => 0, 'fotos' => 0, 'salidas' => 0, 'tarifas' => 0];
    
    // Insertar servicios
    foreach ($datos['servicios'] as $idSvc => $row) {
        $cols = implode(', ', array_keys($row));
        $placeholders = implode(', ', array_fill(0, count($row), '?'));
        
        $sql = "INSERT IGNORE INTO servicio ($cols) VALUES ($placeholders)";
        $stmt = $pdo->prepare($sql);
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
            $stmt = $pdo->prepare($sql);
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
            $stmt = $pdo->prepare($sql);
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
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array_values($tarifa));
            
            if ($stmt->rowCount() > 0) {
                $inserts['tarifas']++;
            }
        }
    }
    
    $pdo->commit();
    
    echo "\n[RESULTADOS]\n";
    echo "✓ Servicios insertados: " . $inserts['servicios'] . "\n";
    echo "✓ Fotos insertadas: " . $inserts['fotos'] . "\n";
    echo "✓ Salidas insertadas: " . $inserts['salidas'] . "\n";
    echo "✓ Tarifas insertadas: " . $inserts['tarifas'] . "\n";
    
    echo "\n[SUCCESS] Restauración completada\n";
    
} catch (Exception $e) {
    $pdo->rollBack();
    echo "\n[ERROR] " . $e->getMessage() . "\n";
    exit(1);
}
?>
