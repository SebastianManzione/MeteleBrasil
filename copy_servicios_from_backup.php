<?php
/**
 * Script final para copiar servicios desde backup temporal a dev
 * Servicios: 740, 743, 613, 602, 615
 */

$modo = isset($argv[1]) ? $argv[1] : 'dev';

echo "=== Copiar Servicios desde Backup Temporal ===\n\n";

// Configuración
if ($modo === 'dev') {
    $dbTarget = 'metelebrasil';
    $user = 'root';
    $pass = '';
} else {
    $dbTarget = 'u925692129_metelebrasil';
    $user = 'u925692129_metelebrasil';
    $pass = 'Cambiar2026';
}

$dbSource = 'metele_backup_temp';
$serviciosIds = [740, 743, 613, 602, 615];

try {
    $pdo = new PDO("mysql:host=localhost;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "[OK] Conectado\n\n";
} catch (Exception $e) {
    die("ERROR: ".$e->getMessage()."\n");
}

// Verificar que exista BD temporal
try {
    $pdo->exec("USE $dbSource");
    echo "✓ BD backup temporal existe\n";
} catch (Exception $e) {
    die("ERROR: BD temporal no existe. Ejecuta restore_servicios_v2.php primero\n");
}

echo "\n[VERIFICANDO] Servicios en backup:\n";
foreach ($serviciosIds as $id) {
    $stmt = $pdo->prepare("SELECT nombre_servicio FROM $dbSource.servicio WHERE idServicio = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        echo "  ✓ $id: " . substr($row['nombre_servicio'], 0, 60) . "\n";
    } else {
        echo "  ✗ $id: NO ENCONTRADO en backup\n";
    }
}

echo "\n¿Continuar con la copia a $dbTarget? (yes/no): ";
$handle = fopen("php://stdin", "r");
$line = trim(fgets($handle));
fclose($handle);

if (strtolower($line) !== 'yes') {
    die("Operación cancelada\n");
}

$serviciosStr = implode(',', $serviciosIds);

echo "\n[COPIANDO]\n";

try {
    $pdo->beginTransaction();
    $pdo->exec("SET foreign_key_checks = 0");
    
    // 1. Servicios principales
    echo "  1. Tabla servicio...\n";
    $sql = "REPLACE INTO $dbTarget.servicio 
            SELECT * FROM $dbSource.servicio 
            WHERE idServicio IN ($serviciosStr)";
    $result = $pdo->exec($sql);
    echo "     ✓ $result registros\n";
    
    // 2. Imágenes
    echo "  2. Tabla servicio_img...\n";
    $sql = "REPLACE INTO $dbTarget.servicio_img 
            SELECT * FROM $dbSource.servicio_img 
            WHERE idServicio IN ($serviciosStr)";
    $result = $pdo->exec($sql);
    echo "     ✓ $result registros\n";
    
    // 3. Salidas
    echo "  3. Tabla servicio_salidas...\n";
    $sql = "REPLACE INTO $dbTarget.servicio_salidas 
            SELECT * FROM $dbSource.servicio_salidas 
            WHERE idServicio IN ($serviciosStr)";
    $result = $pdo->exec($sql);
    echo "     ✓ $result registros\n";
    
    // 4. Obtener IDs de salidas copiadas
    $stmt = $pdo->query("SELECT idServicioSalidas FROM $dbTarget.servicio_salidas WHERE idServicio IN ($serviciosStr)");
    $salidasIds = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (!empty($salidasIds)) {
        $salidasStr = implode(',', $salidasIds);
        
        // 5. Tarifas
        echo "  4. Tabla servicio_salidas_tarifas...\n";
        $sql = "REPLACE INTO $dbTarget.servicio_salidas_tarifas 
                SELECT * FROM $dbSource.servicio_salidas_tarifas 
                WHERE idServicioSalidas IN ($salidasStr)";
        $result = $pdo->exec($sql);
        echo "     ✓ $result registros\n";
        
        // 6. Adicionales por salida
        echo "  5. Tabla servicio_salidas_adicionales...\n";
        $sql = "REPLACE INTO $dbTarget.servicio_salidas_adicionales 
                SELECT * FROM $dbSource.servicio_salidas_adicionales 
                WHERE idServicioSalidas IN ($salidasStr)";
        $result = $pdo->exec($sql);
        echo "     ✓ $result registros\n";
    } else {
        echo "     ⚠ No hay salidas para copiar tarifas/adicionales\n";
    }
    
    // 7. Comisiones prestador
    echo "  6. Tabla servicio_comision_prestador...\n";
    $sql = "REPLACE INTO $dbTarget.servicio_comision_prestador 
            SELECT * FROM $dbSource.servicio_comision_prestador 
            WHERE idServicio IN ($serviciosStr)";
    $result = $pdo->exec($sql);
    echo "     ✓ $result registros\n";
    
    $pdo->exec("SET foreign_key_checks = 1");
    $pdo->commit();
    
    echo "\n[SUCCESS] Servicios copiados!\n";
    
} catch (Exception $e) {
    $pdo->rollBack();
    die("\nERROR: ".$e->getMessage()."\n");
}

// Verificación
echo "\n[VERIFICACIÓN FINAL]\n";
$pdo->exec("USE $dbTarget");
foreach ($serviciosIds as $id) {
    $stmt = $pdo->prepare("SELECT nombre_servicio FROM servicio WHERE idServicio = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($row) {
        $stmt2 = $pdo->prepare("SELECT COUNT(*) FROM servicio_salidas WHERE idServicio = ?");
        $stmt2->execute([$id]);
        $salidas = $stmt2->fetchColumn();
        
        $stmt3 = $pdo->prepare("SELECT COUNT(*) FROM servicio_img WHERE idServicio = ?");
        $stmt3->execute([$id]);
        $imgs = $stmt3->fetchColumn();
        
        echo "  ✓ $id: ".substr($row['nombre_servicio'],0,50)." ($salidas salidas, $imgs imgs)\n";
    } else {
        echo "  ✗ $id: NO COPIADO\n";
    }
}

echo "\n✅ Proceso completado!\n";
echo "\nPara limpiar la BD temporal ejecuta:\n";
echo "  mysql -uroot -e \"DROP DATABASE metele_backup_temp\"\n";
?>
