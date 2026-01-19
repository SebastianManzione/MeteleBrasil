<?php
require_once("admin/classes/conexion.php");

try {
    echo "<h2>🔄 Migrando a tabla unificada 'parada'</h2>";
    
    // PASO 1: Crear tabla parada unificada
    echo "<h3>Paso 1: Crear tabla 'parada'</h3>";
    $pdo->exec("CREATE TABLE IF NOT EXISTS parada (
        idParada INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(255) NOT NULL,
        tipo VARCHAR(50) DEFAULT 'terminal',
        direccion TEXT,
        ciudad VARCHAR(100),
        estado VARCHAR(100),
        pais VARCHAR(100),
        latitud DECIMAL(10, 8),
        longitud DECIMAL(11, 8),
        habilitado TINYINT DEFAULT 1,
        fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_tipo (tipo),
        INDEX idx_ciudad (ciudad),
        INDEX idx_pais (pais)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "✓ Tabla 'parada' creada<br>";
    
    // PASO 2: Migrar datos de terminal_transporte a parada
    echo "<h3>Paso 2: Migrar terminales existentes</h3>";
    $resultado = $pdo->query("SELECT COUNT(*) as total FROM parada")->fetch();
    $totalParadas = $resultado['total'];
    
    if ($totalParadas == 0) {
        $pdo->exec("INSERT INTO parada (nombre, tipo, direccion, ciudad, estado, pais, latitud, longitud, habilitado)
                   SELECT nombre, 'terminal' as tipo, direccion, ciudad, estado, pais, latitud, longitud, habilitado
                   FROM terminal_transporte");
        echo "✓ Terminales migradas a tabla 'parada'<br>";
    } else {
        echo "⚠ Ya existen paradas. Omitiendo migración de terminales<br>";
    }
    
    // PASO 3: Agregar columna idParada a ruta_paradas si no existe
    echo "<h3>Paso 3: Actualizar tabla 'ruta_paradas'</h3>";
    $columnas = $pdo->query("DESCRIBE ruta_paradas")->fetchAll(PDO::FETCH_COLUMN, 0);
    
    if (!in_array('idParada', $columnas)) {
        $pdo->exec("ALTER TABLE ruta_paradas ADD COLUMN idParada INT NULL AFTER idRuta");
        echo "✓ Columna 'idParada' agregada<br>";
    } else {
        echo "✓ Columna 'idParada' ya existe<br>";
    }
    
    // PASO 4: Migrar datos de ruta_paradas (terminal → parada)
    echo "<h3>Paso 4: Migrar referencias de paradas</h3>";
    
    // Contar cuantas paradas aún no tienen idParada migrado
    $resultado = $pdo->query("SELECT COUNT(*) as total FROM ruta_paradas WHERE idTerminal IS NOT NULL AND idParada IS NULL")->fetch();
    $porMigrar = $resultado['total'];
    
    if ($porMigrar > 0) {
        $pdo->exec("UPDATE ruta_paradas rp
                   SET rp.idParada = (SELECT idParada FROM parada p WHERE p.nombre = 
                                     (SELECT nombre FROM terminal_transporte WHERE idTerminal = rp.idTerminal) LIMIT 1)
                   WHERE rp.idTerminal IS NOT NULL AND rp.idParada IS NULL");
        echo "✓ " . $porMigrar . " referencias de terminales migradas<br>";
    } else {
        echo "✓ Todas las referencias ya están migradas<br>";
    }
    
    // PASO 5: Migrar paradas customizadas si existen
    echo "<h3>Paso 5: Migrar paradas customizadas</h3>";
    
    $existeTablaCustomizada = $pdo->query("SELECT COUNT(*) FROM information_schema.TABLES 
                                          WHERE TABLE_NAME='parada_customizada' AND TABLE_SCHEMA='metelebrasil'")->fetchColumn();
    
    if ($existeTablaCustomizada) {
        $resultado = $pdo->query("SELECT COUNT(*) as total FROM parada_customizada")->fetch();
        $totalCustomizadas = $resultado['total'];
        
        if ($totalCustomizadas > 0) {
            $pdo->exec("INSERT INTO parada (nombre, tipo, direccion, ciudad, estado, pais, latitud, longitud, habilitado)
                       SELECT nombre, 'customizada' as tipo, direccion, ciudad, estado, pais, latitud, longitud, habilitado
                       FROM parada_customizada");
            
            // Actualizar referencias
            $pdo->exec("UPDATE ruta_paradas rp
                       SET rp.idParada = (SELECT idParada FROM parada p WHERE p.nombre = 
                                         (SELECT nombre FROM parada_customizada WHERE idParadaCustomizada = rp.idParadaCustomizada) LIMIT 1)
                       WHERE rp.idParadaCustomizada IS NOT NULL AND rp.idParada IS NULL");
            
            echo "✓ " . $totalCustomizadas . " paradas customizadas migradas<br>";
        }
    }
    
    // PASO 6: Agregar FK a ruta_paradas
    echo "<h3>Paso 6: Agregar restricción de clave foránea</h3>";
    
    $existeFk = $pdo->query("SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE 
                            WHERE TABLE_NAME='ruta_paradas' AND COLUMN_NAME='idParada' AND TABLE_SCHEMA='metelebrasil'")->fetchColumn();
    
    if (!$existeFk) {
        $pdo->exec("ALTER TABLE ruta_paradas 
                   ADD CONSTRAINT ruta_paradas_ibfk_parada 
                   FOREIGN KEY (idParada) REFERENCES parada(idParada) ON DELETE CASCADE");
        echo "✓ FK de 'idParada' agregada<br>";
    } else {
        echo "✓ FK de 'idParada' ya existe<br>";
    }
    
    // PASO 7: Mostrar estadísticas
    echo "<h3>📊 Estadísticas Finales</h3>";
    $stats = $pdo->query("SELECT 
                          COUNT(*) as total_paradas,
                          SUM(CASE WHEN tipo='terminal' THEN 1 ELSE 0 END) as terminales,
                          SUM(CASE WHEN tipo='customizada' THEN 1 ELSE 0 END) as customizadas
                          FROM parada")->fetch();
    
    echo "<ul>";
    echo "<li><strong>Total de paradas:</strong> " . $stats['total_paradas'] . "</li>";
    echo "<li><strong>Terminales:</strong> " . ($stats['terminales'] ?? 0) . "</li>";
    echo "<li><strong>Customizadas:</strong> " . ($stats['customizadas'] ?? 0) . "</li>";
    echo "</ul>";
    
    $statsRutas = $pdo->query("SELECT COUNT(*) as total FROM ruta_paradas WHERE idParada IS NOT NULL")->fetch();
    echo "<li><strong>Paradas en rutas:</strong> " . $statsRutas['total'] . "</li>";
    
    echo "<br><h3 style='color: green;'>✅ Migración completada exitosamente</h3>";
    
} catch (Exception $e) {
    echo "<h3 style='color: red;'>✗ Error: " . $e->getMessage() . "</h3>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>
<br><br>
<a href="admin/rutasTransporteLista.php" class="btn btn-primary" style="padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 4px;">
    Ir a Rutas
</a>
