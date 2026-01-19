<?php
/**
 * Migración: Tipos de Paradas Dinámicos
 * 
 * Crea tabla tipo_parada y migra de ENUM a FK
 * Permite agregar nuevos tipos sin modificar código
 */

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
        <title>Migración: Tipos de Paradas Dinámicos</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 20px; background-color: #f5f5f5; }
            .container { max-width: 900px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
            h1 { color: #333; border-bottom: 3px solid #007bff; padding-bottom: 10px; }
            .step { margin: 20px 0; padding: 15px; background-color: #f8f9fa; border-left: 4px solid #007bff; }
            .success { color: #28a745; font-weight: bold; }
            .error { color: #dc3545; font-weight: bold; }
            .info { color: #17a2b8; }
            .stat { display: inline-block; margin: 10px 20px 10px 0; padding: 10px 15px; background-color: #e7f3ff; border-radius: 4px; }
            code { background-color: #f4f4f4; padding: 2px 6px; border-radius: 3px; font-family: monospace; }
            .progress-bar { width: 100%; height: 25px; background-color: #e0e0e0; border-radius: 4px; overflow: hidden; margin: 10px 0; }
            .progress { height: 100%; background-color: #28a745; width: 0%; text-align: center; color: white; line-height: 25px; }
            table { width: 100%; border-collapse: collapse; margin: 10px 0; }
            th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
            th { background-color: #007bff; color: white; }
        </style>
    </head>
    <body>
    <div class='container'>
    <h1>🚀 Tipos de Paradas Dinámicos</h1>
    
    <div class='progress-bar'>
        <div class='progress' id='progressBar' style='width: 5%;'>5%</div>
    </div>";
    
    $pdo->exec("SET NAMES utf8mb4");
    
    // PASO 1: Crear tabla tipo_parada
    echo "<div class='step'>
        <h3>Paso 1: Crear tabla <code>tipo_parada</code></h3>";
    
    try {
        $createTableSQL = "CREATE TABLE IF NOT EXISTS tipo_parada (
            idTipoPrada INT PRIMARY KEY AUTO_INCREMENT,
            nombre VARCHAR(50) NOT NULL UNIQUE,
            icono VARCHAR(50) DEFAULT 'fa-map-marker-alt',
            color VARCHAR(7) DEFAULT '#007bff',
            habilitado TINYINT(1) DEFAULT 1,
            fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_nombre (nombre),
            INDEX idx_habilitado (habilitado)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $pdo->exec($createTableSQL);
        echo "<p class='success'>✓ Tabla <code>tipo_parada</code> creada exitosamente</p>";
        
    } catch (Exception $e) {
        echo "<p class='error'>✗ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
        exit;
    }
    
    echo "<script>document.getElementById('progressBar').style.width = '20%'; document.getElementById('progressBar').textContent = '20%';</script>";
    
    // PASO 2: Insertar tipos predefinidos
    echo "<div class='step'>
        <h3>Paso 2: Insertar tipos de paradas predefinidos</h3>";
    
    try {
        $tiposSQL = "INSERT IGNORE INTO tipo_parada (nombre, icono, color, habilitado) VALUES
            ('terminal', 'fa-map-marker-alt', '#007bff', 1),
            ('hotel', 'fa-hotel', '#ff6b6b', 1),
            ('estación', 'fa-train', '#ffc107', 1),
            ('puerto', 'fa-anchor', '#17a2b8', 1),
            ('aeropuerto', 'fa-plane', '#28a745', 1),
            ('casa', 'fa-home', '#6c757d', 1),
            ('intermedia', 'fa-dot-circle', '#6f42c1', 1)";
        
        $pdo->exec($tiposSQL);
        
        $count = $pdo->query("SELECT COUNT(*) as cnt FROM tipo_parada")->fetch()['cnt'];
        echo "<p class='success'>✓ " . $count . " tipos de paradas insertados</p>";
        
        $tipos = $pdo->query("SELECT idTipoPrada, nombre, icono, color FROM tipo_parada ORDER BY idTipoPrada")->fetchAll();
        echo "<table><tr><th>ID</th><th>Nombre</th><th>Icono</th><th>Color</th></tr>";
        foreach ($tipos as $t) {
            echo "<tr><td>{$t['idTipoPrada']}</td><td>{$t['nombre']}</td><td>{$t['icono']}</td><td><span style='background-color:{$t['color']};padding:5px;color:white;'>$t[color]</span></td></tr>";
        }
        echo "</table>";
        
    } catch (Exception $e) {
        echo "<p class='error'>✗ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
    
    echo "<script>document.getElementById('progressBar').style.width = '40%'; document.getElementById('progressBar').textContent = '40%';</script>";
    
    // PASO 3: Agregar columna idTipoPrada a tabla parada
    echo "<div class='step'>
        <h3>Paso 3: Actualizar tabla <code>parada</code></h3>";
    
    try {
        // Verificar si la columna ya existe
        $stmt = $pdo->query("SHOW COLUMNS FROM parada LIKE 'idTipoPrada'");
        $existe = $stmt->fetch();
        
        if (!$existe) {
            // Agregar columna
            $pdo->exec("ALTER TABLE parada ADD COLUMN idTipoPrada INT AFTER tipo");
            echo "<p class='success'>✓ Columna <code>idTipoPrada</code> agregada</p>";
        } else {
            echo "<p class='info'>ℹ Columna <code>idTipoPrada</code> ya existe</p>";
        }
        
    } catch (Exception $e) {
        echo "<p class='error'>✗ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
    
    echo "<script>document.getElementById('progressBar').style.width = '60%'; document.getElementById('progressBar').textContent = '60%';</script>";
    
    // PASO 4: Migrar datos del campo tipo (ENUM) a idTipoPrada (FK)
    echo "<div class='step'>
        <h3>Paso 4: Migrar datos existentes</h3>";
    
    try {
        // Mapear valores ENUM a idTipoPrada
        $migraciones = [
            'terminal' => 1,
            'customizada' => 1,  // Mapear customizada como terminal
            'intermedia' => 7,
            'hotel' => 2,
            'estación' => 3,
            'puerto' => 4,
            'aeropuerto' => 5,
            'casa' => 6
        ];
        
        $totalActualizadas = 0;
        foreach ($migraciones as $tipoEnum => $idTipoPrada) {
            $stmt = $pdo->prepare("UPDATE parada SET idTipoPrada = :idTipoPrada WHERE tipo = :tipo AND idTipoPrada IS NULL");
            $stmt->execute([':idTipoPrada' => $idTipoPrada, ':tipo' => $tipoEnum]);
            $afectadas = $stmt->rowCount();
            if ($afectadas > 0) {
                echo "<p class='info'>✓ " . $afectadas . " paradas de tipo '$tipoEnum' → tipo $idTipoPrada</p>";
                $totalActualizadas += $afectadas;
            }
        }
        
        // Asignar tipo 'terminal' (1) a las que aún no tienen idTipoPrada
        $stmt = $pdo->prepare("UPDATE parada SET idTipoPrada = 1 WHERE idTipoPrada IS NULL");
        $stmt->execute();
        $porDefecto = $stmt->rowCount();
        
        echo "<p class='success'>✓ Total " . ($totalActualizadas + $porDefecto) . " paradas migradas</p>";
        
    } catch (Exception $e) {
        echo "<p class='error'>✗ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
    
    echo "<script>document.getElementById('progressBar').style.width = '80%'; document.getElementById('progressBar').textContent = '80%';</script>";
    
    // PASO 5: Agregar Foreign Key
    echo "<div class='step'>
        <h3>Paso 5: Agregar restricción de clave foránea</h3>";
    
    try {
        // Verificar si FK ya existe
        $stmt = $pdo->query("SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                            WHERE TABLE_NAME = 'parada' AND COLUMN_NAME = 'idTipoPrada' AND REFERENCED_TABLE_NAME IS NOT NULL");
        $fkExists = $stmt->fetch();
        
        if (!$fkExists) {
            $pdo->exec("ALTER TABLE parada 
                       ADD CONSTRAINT fk_parada_tipo_parada 
                       FOREIGN KEY (idTipoPrada) REFERENCES tipo_parada(idTipoPrada) 
                       ON DELETE RESTRICT ON UPDATE CASCADE");
            echo "<p class='success'>✓ Foreign Key agregada correctamente</p>";
        } else {
            echo "<p class='info'>ℹ Foreign Key ya existe</p>";
        }
        
    } catch (Exception $e) {
        echo "<p class='error'>✗ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
    
    echo "<script>document.getElementById('progressBar').style.width = '100%'; document.getElementById('progressBar').textContent = '100%';</script>";
    
    // PASO 6: Resumen
    echo "<div class='step'>
        <h3>✅ Migración Completada</h3>";
    
    try {
        $totalParadas = $pdo->query("SELECT COUNT(*) as cnt FROM parada")->fetch()['cnt'];
        $conTipo = $pdo->query("SELECT COUNT(*) as cnt FROM parada WHERE idTipoPrada IS NOT NULL")->fetch()['cnt'];
        $tiposDisponibles = $pdo->query("SELECT COUNT(*) as cnt FROM tipo_parada WHERE habilitado = 1")->fetch()['cnt'];
        
        echo "<p class='info'><strong>Estadísticas:</strong></p>";
        echo "<div class='stat'><strong>Total de Paradas:</strong> " . $totalParadas . "</div>";
        echo "<div class='stat'><strong>Con Tipo Asignado:</strong> " . $conTipo . "</div>";
        echo "<div class='stat'><strong>Tipos Disponibles:</strong> " . $tiposDisponibles . "</div>";
        
    } catch (Exception $e) {
        echo "<p class='warning'>⚠ No se pudieron obtener estadísticas</p>";
    }
    
    echo "</div>
    
    <div class='step' style='background-color: #d4edda; border-left-color: #28a745;'>
        <h3 style='color: #155724;'>✓ Próximos Pasos</h3>
        <ol style='color: #155724;'>
            <li>Actualizar <code>admin/classes/transporte.php</code> con nuevas funciones</li>
            <li>Crear interfaz de gestión de tipos de paradas</li>
            <li>Actualizar <code>terminalesLista.php</code> con tipos dinámicos</li>
            <li>Testear agregar nuevos tipos (Hotel, Estación, Puerto, etc)</li>
            <li>Opcionalmente: Eliminar columna <code>tipo</code> ENUM de parada</li>
        </ol>
    </div>
    
    </div>
    </body>
    </html>";
    
} catch (PDOException $e) {
    echo "<div style='color: red; font-weight: bold;'>Error de conexión: " . htmlspecialchars($e->getMessage()) . "</div>";
    exit;
}
?>
