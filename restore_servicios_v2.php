<?php
/**
 * Script mejorado para restaurar servicios faltantes
 * Servicios: 740, 743, 613, 602, 615
 * 
 * Estrategia: Importar el backup completo a una BD temporal y luego copiar los servicios
 */

// Verificar modo
$modo = isset($argv[1]) ? $argv[1] : 'dev';
if (!in_array($modo, ['dev', 'prod'])) {
    die("Uso: php restore_servicios_v2.php [dev|prod]\n");
}

echo "=== Restaurar Servicios Faltantes v2 ($modo) ===\n\n";

// Configurar conexión según entorno
if ($modo === 'dev') {
    putenv('APP_ENV=dev');
    $host = 'localhost';
    $dbname = 'metelebrasil';
    $user = 'root';
    $pass = '';
} else {
    putenv('APP_ENV=prod');
    $host = 'localhost';
    $dbname = 'u925692129_metelebrasil';
    $user = 'u925692129_metelebrasil';
    $pass = 'Cambiar2026';
}

try {
    $pdo = new PDO(
        "mysql:host=$host;charset=utf8mb4",
        $user,
        $pass
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET NAMES utf8mb4");
    echo "[OK] Conectado a MySQL\n";
} catch (Exception $e) {
    die("ERROR: No se puede conectar: ".$e->getMessage()."\n");
}

// Servicios a restaurar
$serviciosIds = [740, 743, 613, 602, 615];
$serviciosStr = implode(',', $serviciosIds);

// Nombre de BD temporal
$tempDb = 'metele_backup_temp';

echo "\n[PASO 1] Crear BD temporal\n";
try {
    $pdo->exec("DROP DATABASE IF EXISTS $tempDb");
    $pdo->exec("CREATE DATABASE $tempDb CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✓ BD temporal creada: $tempDb\n";
} catch (Exception $e) {
    die("ERROR creando BD temporal: ".$e->getMessage()."\n");
}

echo "\n[PASO 2] Importar backup a BD temporal\n";
$sqlFile = 'C:\Users\SisteMANZ\Documents\Downloads\metelebr_metelebrasil (1).sql';

if (!file_exists($sqlFile)) {
    die("ERROR: No se encuentra: $sqlFile\n");
}

$mysqlPath = 'C:\xampp\mysql\bin\mysql.exe';
$passArg = $pass ? "-p$pass" : '';
$cmd = "\"$mysqlPath\" -u$user $passArg $tempDb < \"$sqlFile\" 2>&1";

echo "Ejecutando: mysql < backup.sql\n";
echo "(Esto puede tardar varios minutos...)\n";

exec($cmd, $output, $returnCode);

if ($returnCode !== 0) {
    echo "ERROR importando backup:\n";
    echo implode("\n", $output);
    die("\n");
}
echo "✓ Backup importado a BD temporal\n";

echo "\n[PASO 3] Copiar servicios específicos\n";

try {
    $pdo->exec("USE $dbname");
    
    $pdo->beginTransaction();
    $pdo->exec("SET foreign_key_checks = 0");
    
    // Tablas en orden de dependencia
    $tables = [
        'servicio',
        'servicio_img',
        'servicio_salidas',
        'servicio_salidas_tarifas',
        'servicio_salidas_adicionales',
        'servicio_comision_prestador'
    ];
    
    foreach ($tables as $table) {
        echo "  Copiando $table...\n";
        
        if ($table === 'servicio') {
            $sql = "INSERT IGNORE INTO $dbname.$table 
                    SELECT * FROM $tempDb.$table 
                    WHERE idServicio IN ($serviciosStr)";
        } elseif ($table === 'servicio_img') {
            $sql = "INSERT IGNORE INTO $dbname.$table 
                    SELECT * FROM $tempDb.$table 
                    WHERE idServicio IN ($serviciosStr)";
        } elseif ($table === 'servicio_salidas') {
            $sql = "INSERT IGNORE INTO $dbname.$table 
                    SELECT * FROM $tempDb.$table 
                    WHERE idServicio IN ($serviciosStr)";
        } elseif (in_array($table, ['servicio_salidas_tarifas', 'servicio_salidas_adicionales'])) {
            // Primero obtener IDs de salidas
            $stmt = $pdo->query("SELECT idServicioSalidas FROM $tempDb.servicio_salidas WHERE idServicio IN ($serviciosStr)");
            $salidasIds = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            if (empty($salidasIds)) {
                echo "    ⚠ No hay salidas para copiar\n";
                continue;
            }
            
            $salidasStr = implode(',', $salidasIds);
            $sql = "INSERT IGNORE INTO $dbname.$table 
                    SELECT * FROM $tempDb.$table 
                    WHERE idServicioSalidas IN ($salidasStr)";
        } else {
            $sql = "INSERT IGNORE INTO $dbname.$table 
                    SELECT * FROM $tempDb.$table 
                    WHERE idServicio IN ($serviciosStr)";
        }
        
        $result = $pdo->exec($sql);
        echo "    ✓ $result registros copiados\n";
    }
    
    $pdo->exec("SET foreign_key_checks = 1");
    $pdo->commit();
    
    echo "\n[SUCCESS] Servicios copiados exitosamente!\n";
    
} catch (Exception $e) {
    $pdo->rollBack();
    die("\nERROR: ".$e->getMessage()."\n");
}

echo "\n[PASO 4] Limpiar BD temporal\n";
try {
    $pdo->exec("DROP DATABASE IF EXISTS $tempDb");
    echo "✓ BD temporal eliminada\n";
} catch (Exception $e) {
    echo "⚠ No se pudo eliminar BD temporal: ".$e->getMessage()."\n";
}

// Verificación final
echo "\n[VERIFICACIÓN]\n";
foreach ($serviciosIds as $id) {
    $stmt = $pdo->prepare("SELECT idServicio, nombre_servicio FROM $dbname.servicio WHERE idServicio = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($row) {
        // Contar salidas
        $stmt2 = $pdo->prepare("SELECT COUNT(*) FROM $dbname.servicio_salidas WHERE idServicio = ?");
        $stmt2->execute([$id]);
        $salidas = $stmt2->fetchColumn();
        
        echo "  ✓ Servicio $id: " . substr($row['nombre_servicio'], 0, 50) . "... ($salidas salidas)\n";
    } else {
        echo "  ✗ Servicio $id: NO ENCONTRADO\n";
    }
}

echo "\n✅ Proceso completado!\n";
?>
