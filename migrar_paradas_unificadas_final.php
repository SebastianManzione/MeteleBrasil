<?php
/**
 * Migración Final: Consolidar Sistema de Paradas
 * 
 * Este script unifica terminal_transporte y parada_customizada en una sola tabla "parada"
 * y actualiza todas las referencias correspondientes en ruta_paradas
 */

// Conexión a BD
require_once("config/config.php");

try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
    
    echo "<!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <title>Migración de Paradas Unificadas</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 20px; background-color: #f5f5f5; }
            .container { max-width: 900px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
            h1 { color: #333; border-bottom: 3px solid #007bff; padding-bottom: 10px; }
            .step { margin: 20px 0; padding: 15px; background-color: #f8f9fa; border-left: 4px solid #007bff; }
            .success { color: #28a745; font-weight: bold; }
            .error { color: #dc3545; font-weight: bold; }
            .warning { color: #ffc107; font-weight: bold; }
            .info { color: #17a2b8; }
            .stat { display: inline-block; margin: 10px 20px 10px 0; padding: 10px 15px; background-color: #e7f3ff; border-radius: 4px; }
            code { background-color: #f4f4f4; padding: 2px 6px; border-radius: 3px; font-family: monospace; }
            .progress-bar { width: 100%; height: 25px; background-color: #e0e0e0; border-radius: 4px; overflow: hidden; margin: 10px 0; }
            .progress { height: 100%; background-color: #28a745; width: 0%; text-align: center; color: white; line-height: 25px; }
        </style>
    </head>
    <body>
    <div class='container'>
    <h1>🚀 Migración Final: Sistema de Paradas Unificadas</h1>
    
    <div class='progress-bar'>
        <div class='progress' id='progressBar' style='width: 5%;'>5%</div>
    </div>";
    
    $pdo->exec("SET NAMES utf8mb4");
    
    // PASO 1: Crear tabla parada unificada
    echo "<div class='step'>
        <h3>Paso 1: Crear tabla <code>parada</code> unificada</h3>";
    
    try {
        $pdo->exec("DROP TABLE IF EXISTS parada");
        
        $createTableSQL = "CREATE TABLE parada (
            idParada INT PRIMARY KEY AUTO_INCREMENT,
            nombre VARCHAR(255) NOT NULL,
            tipo ENUM('terminal', 'customizada', 'intermedia') DEFAULT 'terminal',
            direccion VARCHAR(500),
            ciudad VARCHAR(100),
            estado VARCHAR(100),
            pais VARCHAR(100),
            latitud DECIMAL(10, 8),
            longitud DECIMAL(11, 8),
            codigo_iata VARCHAR(10),
            habilitado TINYINT(1) DEFAULT 1,
            fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_tipo (tipo),
            INDEX idx_ciudad (ciudad),
            INDEX idx_pais (pais),
            INDEX idx_habilitado (habilitado)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $pdo->exec($createTableSQL);
        echo "<p class='success'>✓ Tabla <code>parada</code> creada exitosamente</p>";
        
    } catch (Exception $e) {
        echo "<p class='error'>✗ Error al crear tabla: " . htmlspecialchars($e->getMessage()) . "</p>";
        exit;
    }
    
    // Actualizar progress
    echo "<script>document.getElementById('progressBar').style.width = '20%'; document.getElementById('progressBar').textContent = '20%';</script>";
    
    // PASO 2: Migrar terminales
    echo "<div class='step'>
        <h3>Paso 2: Migrar terminales existentes</h3>";
    
    try {
        // Obtener todas las terminales
        $stmt = $pdo->query("SELECT * FROM terminal_transporte");
        $terminales = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $insertTerminalSQL = "INSERT INTO parada 
            (nombre, tipo, direccion, ciudad, estado, pais, codigo_iata, habilitado) 
            VALUES (:nombre, 'terminal', :direccion, :ciudad, :estado, :pais, :codigo_iata, :habilitado)";
        
        $insertStmt = $pdo->prepare($insertTerminalSQL);
        
        foreach ($terminales as $terminal) {
            $insertStmt->execute([
                ':nombre' => $terminal['nombre'],
                ':direccion' => $terminal['direccion'] ?? null,
                ':ciudad' => $terminal['ciudad'],
                ':estado' => $terminal['estado'] ?? null,
                ':pais' => $terminal['pais'],
                ':codigo_iata' => $terminal['codigo_iata'] ?? null,
                ':habilitado' => $terminal['habilitado'] ?? 1
            ]);
        }
        
        echo "<p class='success'>✓ " . count($terminales) . " terminales migradas correctamente</p>";
        echo "<div class='stat'>Terminales: " . count($terminales) . "</div>";
        
    } catch (Exception $e) {
        echo "<p class='error'>✗ Error al migrar terminales: " . htmlspecialchars($e->getMessage()) . "</p>";
        exit;
    }
    
    // Actualizar progress
    echo "<script>document.getElementById('progressBar').style.width = '40%'; document.getElementById('progressBar').textContent = '40%';</script>";
    
    // PASO 3: Migrar paradas customizadas
    echo "<div class='step'>
        <h3>Paso 3: Migrar paradas customizadas</h3>";
    
    try {
        // Verificar si existe tabla parada_customizada
        $stmt = $pdo->query("SHOW TABLES LIKE 'parada_customizada'");
        $tableExists = $stmt->fetch();
        
        if ($tableExists) {
            // Obtener todas las paradas customizadas
            $stmt = $pdo->query("SELECT * FROM parada_customizada");
            $paradasCustomizadas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $insertCustomSQL = "INSERT INTO parada 
                (nombre, tipo, direccion, ciudad, estado, pais, latitud, longitud, habilitado) 
                VALUES (:nombre, 'customizada', :direccion, :ciudad, :estado, :pais, :latitud, :longitud, :habilitado)";
            
            $insertCustomStmt = $pdo->prepare($insertCustomSQL);
            
            foreach ($paradasCustomizadas as $parada) {
                $insertCustomStmt->execute([
                    ':nombre' => $parada['nombre'],
                    ':direccion' => $parada['direccion'] ?? null,
                    ':ciudad' => $parada['ciudad'] ?? null,
                    ':estado' => $parada['estado'] ?? null,
                    ':pais' => $parada['pais'] ?? null,
                    ':latitud' => $parada['latitud'] ?? null,
                    ':longitud' => $parada['longitud'] ?? null,
                    ':habilitado' => $parada['habilitado'] ?? 1
                ]);
            }
            
            echo "<p class='success'>✓ " . count($paradasCustomizadas) . " paradas customizadas migradas correctamente</p>";
            echo "<div class='stat'>Customizadas: " . count($paradasCustomizadas) . "</div>";
        } else {
            echo "<p class='info'>ℹ Tabla <code>parada_customizada</code> no existe (sin paradas customizadas para migrar)</p>";
        }
        
    } catch (Exception $e) {
        echo "<p class='error'>✗ Error al migrar paradas customizadas: " . htmlspecialchars($e->getMessage()) . "</p>";
        exit;
    }
    
    // Actualizar progress
    echo "<script>document.getElementById('progressBar').style.width = '60%'; document.getElementById('progressBar').textContent = '60%';</script>";
    
    // PASO 4: Actualizar ruta_paradas con las nuevas referencias
    echo "<div class='step'>
        <h3>Paso 4: Actualizar tabla <code>ruta_paradas</code></h3>";
    
    try {
        // Crear tabla temporal si no existe la nueva estructura
        $stmt = $pdo->query("SHOW COLUMNS FROM ruta_paradas LIKE 'idParada'");
        $columnaExists = $stmt->fetch();
        
        if (!$columnaExists) {
            // Agregar columna idParada si no existe
            $pdo->exec("ALTER TABLE ruta_paradas ADD COLUMN idParada INT AFTER idRuta");
            echo "<p class='info'>ℹ Columna <code>idParada</code> agregada a <code>ruta_paradas</code></p>";
        }
        
        // Mapear terminales a nuevas paradas
        $stmt = $pdo->query("SELECT rp.idRutaParada, rp.idTerminal, t.nombre as terminal_nombre
                            FROM ruta_paradas rp
                            LEFT JOIN terminal_transporte t ON rp.idTerminal = t.idTerminal
                            WHERE rp.idParada IS NULL AND rp.idTerminal IS NOT NULL");
        
        $rutasParadas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $updateSQL = "UPDATE ruta_paradas SET idParada = :idParada WHERE idRutaParada = :idRutaParada";
        $updateStmt = $pdo->prepare($updateSQL);
        
        foreach ($rutasParadas as $rp) {
            // Buscar la parada migrada por nombre
            $findSQL = "SELECT idParada FROM parada WHERE nombre = :nombre AND tipo = 'terminal' LIMIT 1";
            $findStmt = $pdo->prepare($findSQL);
            $findStmt->execute([':nombre' => $rp['terminal_nombre']]);
            $result = $findStmt->fetch();
            
            if ($result) {
                $updateStmt->execute([
                    ':idParada' => $result['idParada'],
                    ':idRutaParada' => $rp['idRutaParada']
                ]);
            }
        }
        
        echo "<p class='success'>✓ " . count($rutasParadas) . " referencias actualizadas en <code>ruta_paradas</code></p>";
        echo "<div class='stat'>Rutas/Paradas actualizadas: " . count($rutasParadas) . "</div>";
        
    } catch (Exception $e) {
        echo "<p class='error'>✗ Error al actualizar ruta_paradas: " . htmlspecialchars($e->getMessage()) . "</p>";
        exit;
    }
    
    // Actualizar progress
    echo "<script>document.getElementById('progressBar').style.width = '80%'; document.getElementById('progressBar').textContent = '80%';</script>";
    
    // PASO 5: Agregar restricción FK
    echo "<div class='step'>
        <h3>Paso 5: Agregar restricción de clave foránea</h3>";
    
    try {
        // Verificar si FK ya existe
        $stmt = $pdo->query("SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                            WHERE TABLE_NAME = 'ruta_paradas' AND COLUMN_NAME = 'idParada'");
        $fkExists = $stmt->fetch();
        
        if (!$fkExists) {
            $pdo->exec("ALTER TABLE ruta_paradas 
                       ADD CONSTRAINT fk_ruta_paradas_parada 
                       FOREIGN KEY (idParada) REFERENCES parada(idParada) 
                       ON DELETE CASCADE ON UPDATE CASCADE");
            echo "<p class='success'>✓ Restricción FK agregada correctamente</p>";
        } else {
            echo "<p class='info'>ℹ Restricción FK ya existe</p>";
        }
        
    } catch (Exception $e) {
        echo "<p class='error'>✗ Error al agregar FK: " . htmlspecialchars($e->getMessage()) . "</p>";
        exit;
    }
    
    // Actualizar progress
    echo "<script>document.getElementById('progressBar').style.width = '100%'; document.getElementById('progressBar').textContent = '100%';</script>";
    
    // PASO 6: Resumen de Migración
    echo "<div class='step'>
        <h3>✅ Migración Completada Exitosamente</h3>";
    
    // Estadísticas
    try {
        $totalParadas = $pdo->query("SELECT COUNT(*) as count FROM parada")->fetch()['count'];
        $totalTerminales = $pdo->query("SELECT COUNT(*) as count FROM parada WHERE tipo = 'terminal'")->fetch()['count'];
        $totalCustomizadas = $pdo->query("SELECT COUNT(*) as count FROM parada WHERE tipo = 'customizada'")->fetch()['count'];
        $totalRutasParadas = $pdo->query("SELECT COUNT(*) as count FROM ruta_paradas WHERE idParada IS NOT NULL")->fetch()['count'];
        
        echo "<p class='info'><strong>Estadísticas de Migración:</strong></p>";
        echo "<div class='stat'><strong>Total de Paradas:</strong> " . $totalParadas . "</div>";
        echo "<div class='stat'><strong>Terminales:</strong> " . $totalTerminales . "</div>";
        echo "<div class='stat'><strong>Customizadas:</strong> " . $totalCustomizadas . "</div>";
        echo "<div class='stat'><strong>Rutas/Paradas Vinculadas:</strong> " . $totalRutasParadas . "</div>";
        
    } catch (Exception $e) {
        echo "<p class='warning'>⚠ No se pudieron obtener las estadísticas: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
    
    echo "</div>
    
    <div class='step' style='background-color: #d4edda; border-left-color: #28a745;'>
        <h3 style='color: #155724;'>✓ Próximos Pasos</h3>
        <ol style='color: #155724;'>
            <li>Actualizar el archivo <code>admin/classes/transporte.php</code> con las nuevas funciones que usen la tabla <code>parada</code></li>
            <li>Verificar que <code>getParadasRuta()</code>, <code>getOrigenesRuta()</code> y <code>getDestinosRuta()</code> usen JOIN con <code>parada</code></li>
            <li>Testear la interfaz <code>admin/rutaTransporteParadas.php</code></li>
            <li>Verificar que las rutas se creen y editen correctamente</li>
            <li>Opcionalmente, eliminar las tablas antiguas: <code>terminal_transporte</code> y <code>parada_customizada</code></li>
        </ol>
    </div>
    
    <div class='step' style='background-color: #fff3cd; border-left-color: #ffc107;'>
        <h3 style='color: #856404;'>⚠ Tablas Antiguas Disponibles para Respaldo</h3>
        <p style='color: #856404;'>Las siguientes tablas aún existen en caso de necesitar recuperar datos:</p>
        <ul style='color: #856404;'>
            <li><code>terminal_transporte</code> - Terminales originales</li>
            <li><code>parada_customizada</code> - Paradas customizadas originales (si existen)</li>
        </ul>
        <p style='color: #856404;'><strong>Después de verificar que todo funciona correctamente, puedes eliminarlas manualmente para limpiar la BD.</strong></p>
    </div>
    
    </div>
    </body>
    </html>";
    
} catch (PDOException $e) {
    echo "<div class='error'>Error de conexión a base de datos: " . htmlspecialchars($e->getMessage()) . "</div>";
    exit;
}
?>
