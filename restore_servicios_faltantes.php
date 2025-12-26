<?php
/**
 * Script para restaurar servicios faltantes desde backup SQL
 * Servicios: 740, 743, 613, 602, 615
 * Fuente: C:\Users\SisteMANZ\Documents\Downloads\metelebr_metelebrasil (1).sql
 * 
 * IMPORTANTE: Este script extrae e inserta los servicios faltantes con todas sus relaciones
 */

// Verificar modo (dev/prod)
$modo = isset($argv[1]) ? $argv[1] : 'dev';
if (!in_array($modo, ['dev', 'prod'])) {
    die("Uso: php restore_servicios_faltantes.php [dev|prod]\n");
}

echo "=== Restaurar Servicios Faltantes ($modo) ===\n\n";

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
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $user,
        $pass
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET NAMES utf8mb4");
    echo "[OK] Conectado a BD $modo\n\n";
} catch (Exception $e) {
    die("ERROR: No se puede conectar: ".$e->getMessage()."\n");
}

// Servicios a restaurar
$serviciosIds = [740, 743, 613, 602, 615];

// Ruta al archivo SQL de backup
$sqlFile = 'C:\Users\SisteMANZ\Documents\Downloads\metelebr_metelebrasil (1).sql';

if (!file_exists($sqlFile)) {
    die("ERROR: No se encuentra el archivo SQL: $sqlFile\n");
}

echo "[LEYENDO] Archivo SQL...\n";
$sqlContent = file_get_contents($sqlFile);
if (!$sqlContent) {
    die("ERROR: No se pudo leer el archivo SQL\n");
}
echo "✓ Archivo leído (" . number_format(strlen($sqlContent)) . " bytes)\n\n";

// Función para extraer INSERTs de una tabla para IDs específicos
function extractInsertsForIds($sqlContent, $tableName, $idColumn, $ids) {
    $inserts = [];
    $idsStr = implode('|', $ids);
    
    // Buscar todos los INSERT de la tabla
    $pattern = "/INSERT INTO `?" . preg_quote($tableName) . "`? .*?VALUES (.*?);/is";
    
    preg_match_all($pattern, $sqlContent, $matches);
    
    foreach ($matches[0] as $fullInsert) {
        // Verificar si el INSERT contiene alguno de los IDs buscados
        foreach ($ids as $id) {
            if (preg_match("/\(?\s*'?" . $id . "'?\s*,/", $fullInsert)) {
                $inserts[] = $fullInsert;
                break;
            }
        }
    }
    
    return $inserts;
}

echo "[EXTRAYENDO] Datos de servicios...\n";

// Tablas relacionadas con servicios en orden de dependencia
$tables = [
    'servicio' => 'idServicio',
    'servicio_img' => 'idServicio',
    'servicio_salidas' => 'idServicio',
    'servicio_salidas_tarifas' => 'idServicioSalidas',
    'servicio_salidas_adicionales' => 'idServicioSalidas',
    'servicio_comision_prestador' => 'idServicio',
];

$allInserts = [];

foreach ($tables as $table => $idCol) {
    echo "  Extrayendo $table...\n";
    
    if ($table === 'servicio') {
        $extracted = extractInsertsForIds($sqlContent, $table, $idCol, $serviciosIds);
    } else {
        // Para tablas relacionadas, primero necesitamos obtener los IDs de salidas
        if (in_array($table, ['servicio_salidas_tarifas', 'servicio_salidas_adicionales'])) {
            // Obtener IDs de salidas de los servicios
            $placeholders = implode(',', array_fill(0, count($serviciosIds), '?'));
            $stmt = $pdo->prepare("SELECT idServicioSalidas FROM servicio_salidas WHERE idServicio IN ($placeholders)");
            $stmt->execute($serviciosIds);
            $salidasIds = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            if (!empty($salidasIds)) {
                $extracted = extractInsertsForIds($sqlContent, $table, $idCol, $salidasIds);
            } else {
                $extracted = [];
            }
        } else {
            $extracted = extractInsertsForIds($sqlContent, $table, $idCol, $serviciosIds);
        }
    }
    
    $allInserts[$table] = $extracted;
    echo "    ✓ " . count($extracted) . " registros encontrados\n";
}

echo "\n[RESUMEN]\n";
$totalInserts = 0;
foreach ($allInserts as $table => $inserts) {
    $count = count($inserts);
    $totalInserts += $count;
    echo "  $table: $count registros\n";
}
echo "  TOTAL: $totalInserts registros\n\n";

if ($totalInserts === 0) {
    die("ERROR: No se encontraron datos para restaurar\n");
}

// Confirmar antes de proceder
echo "¿Deseas continuar con la restauración en $modo? (yes/no): ";
$handle = fopen("php://stdin", "r");
$line = trim(fgets($handle));
fclose($handle);

if (strtolower($line) !== 'yes') {
    die("Operación cancelada por el usuario\n");
}

// Ejecutar restauración
echo "\n[RESTAURANDO]\n";

try {
    $pdo->beginTransaction();
    
    // Deshabilitar verificaciones temporalmente
    $pdo->exec("SET foreign_key_checks = 0");
    
    $insertCount = 0;
    foreach ($tables as $table => $idCol) {
        if (empty($allInserts[$table])) {
            continue;
        }
        
        echo "  Insertando en $table...\n";
        
        foreach ($allInserts[$table] as $insertStmt) {
            try {
                $pdo->exec($insertStmt);
                $insertCount++;
            } catch (Exception $e) {
                // Si el registro ya existe (duplicate key), continuar
                if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                    echo "    ⚠ Registro duplicado, omitiendo...\n";
                } else {
                    throw $e;
                }
            }
        }
        
        echo "    ✓ Completado\n";
    }
    
    // Rehabilitar verificaciones
    $pdo->exec("SET foreign_key_checks = 1");
    
    $pdo->commit();
    echo "\n[SUCCESS] $insertCount registros insertados exitosamente!\n";
    
} catch (Exception $e) {
    $pdo->rollBack();
    die("\nERROR en transacción: ".$e->getMessage()."\n");
}

// Verificación final
echo "\n[VERIFICACIÓN]\n";
foreach ($serviciosIds as $id) {
    $stmt = $pdo->prepare("SELECT idServicio, nombre_servicio FROM servicio WHERE idServicio = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($row) {
        echo "  ✓ Servicio $id: " . substr($row['nombre_servicio'], 0, 50) . "...\n";
    } else {
        echo "  ✗ Servicio $id: NO ENCONTRADO\n";
    }
}

echo "\n✅ Proceso completado!\n";
?>
