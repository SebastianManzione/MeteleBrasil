<?php
/**
 * Copiar servicios restaurados de dev a producción
 * Servicios: 740, 743, 613, 602, 615
 * 
 * EJECUTAR EN SERVIDOR DE PRODUCCIÓN:
 * php copy_servicios_to_prod.php
 */

echo "=== Copiar Servicios a Producción ===\n\n";

$serviciosIds = [740, 743, 613, 602, 615];
$serviciosStr = implode(',', $serviciosIds);

// Conectar a producción
try {
    $pdo = new PDO(
        'mysql:host=localhost;charset=utf8mb4',
        'u925692129_metelebrasil',
        'Cambiar2026'
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "[OK] Conectado a producción\n\n";
} catch (Exception $e) {
    die("ERROR: ".$e->getMessage()."\n");
}

// Verificar servicios actuales en producción
echo "[VERIFICANDO] Estado actual en producción:\n";
$existentes = [];
foreach ($serviciosIds as $id) {
    $stmt = $pdo->prepare("SELECT idServicio, nombre_servicio FROM u925692129_metelebrasil.servicio WHERE idServicio = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($row) {
        echo "  ⚠ $id: YA EXISTE - " . substr($row['nombre_servicio'], 0, 50) . "\n";
        $existentes[] = $id;
    } else {
        echo "  ✓ $id: No existe (se copiará)\n";
    }
}

if (count($existentes) === count($serviciosIds)) {
    echo "\n✓ Todos los servicios ya existen en producción\n";
    die("Si deseas reemplazarlos, modifica el script para usar REPLACE\n");
}

echo "\n⚠ IMPORTANTE: Este proceso copiará ".count($serviciosIds)." servicios completos\n";
echo "   Incluye: servicio, imágenes, salidas, tarifas, adicionales\n";
echo "\n¿Continuar? (yes/no): ";
$handle = fopen("php://stdin", "r");
$line = trim(fgets($handle));
fclose($handle);

if (strtolower($line) !== 'yes') {
    die("Operación cancelada\n");
}

// Leer datos del backup SQL local
$backupFile = __DIR__ . '/backup_servicios_export.sql';

if (!file_exists($backupFile)) {
    echo "\n[EXPORTANDO] Servicios desde dev...\n";
    
    // Crear export temporal desde dev (esto debe ejecutarse desde dev primero)
    echo "ERROR: Primero ejecuta en DEV:\n";
    echo "  php export_servicios_for_prod.php\n";
    die();
}

echo "\n[IMPORTANDO] Desde backup local...\n";

try {
    $pdo->beginTransaction();
    $pdo->exec("SET foreign_key_checks = 0");
    
    // Leer y ejecutar SQL
    $sql = file_get_contents($backupFile);
    $queries = explode(';', $sql);
    
    $count = 0;
    foreach ($queries as $query) {
        $query = trim($query);
        if (empty($query)) continue;
        
        try {
            $pdo->exec($query);
            $count++;
        } catch (Exception $e) {
            // Ignorar duplicados
            if (strpos($e->getMessage(), 'Duplicate entry') === false) {
                throw $e;
            }
        }
    }
    
    $pdo->exec("SET foreign_key_checks = 1");
    $pdo->commit();
    
    echo "✓ $count queries ejecutadas\n";
    echo "\n[SUCCESS] Servicios importados a producción!\n";
    
} catch (Exception $e) {
    $pdo->rollBack();
    die("\nERROR: ".$e->getMessage()."\n");
}

// Verificación final
echo "\n[VERIFICACIÓN FINAL]\n";
foreach ($serviciosIds as $id) {
    $stmt = $pdo->prepare("SELECT nombre_servicio FROM u925692129_metelebrasil.servicio WHERE idServicio = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($row) {
        $stmt2 = $pdo->prepare("SELECT COUNT(*) FROM u925692129_metelebrasil.servicio_salidas WHERE idServicio = ?");
        $stmt2->execute([$id]);
        $salidas = $stmt2->fetchColumn();
        
        echo "  ✓ $id: ".substr($row['nombre_servicio'],0,50)." ($salidas salidas)\n";
    } else {
        echo "  ✗ $id: ERROR - No encontrado\n";
    }
}

echo "\n✅ Proceso completado!\n";
?>
