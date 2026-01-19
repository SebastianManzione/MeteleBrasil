<?php
/**
 * Crear arquitectura correcta:
 * - Tabla ubicacion (centraliza toda la info)
 * - Parada solo referencia a ubicacion
 * - Hotel, terminal, aeropuerto, etc. referencia a ubicacion
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('admin/classes/conexion.php');

echo "🏗️  Rediseño de arquitectura: Tabla UBICACION como centro\n\n";

try {
    // 1. Crear tabla ubicacion si no existe
    echo "1️⃣ Creando tabla 'ubicacion'...\n";
    
    $sqlCreateUbicacion = "CREATE TABLE IF NOT EXISTS ubicacion (
        idUbicacion INT PRIMARY KEY AUTO_INCREMENT,
        nombre VARCHAR(255) NOT NULL,
        tipo ENUM('hotel','terminal','aeropuerto','parada','restaurante','atraccion','otro') NOT NULL DEFAULT 'otro',
        direccion VARCHAR(500),
        ciudad VARCHAR(100),
        estado VARCHAR(100),
        pais VARCHAR(100),
        latitud DECIMAL(10,8),
        longitud DECIMAL(11,8),
        codigo_iata VARCHAR(10),
        telefono VARCHAR(50),
        email VARCHAR(255),
        sitio_web VARCHAR(255),
        descripcion TEXT,
        horario_atencion VARCHAR(200),
        url_foto VARCHAR(500),
        habilitado TINYINT(1) DEFAULT 1,
        fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_tipo (tipo),
        INDEX idx_pais (pais),
        INDEX idx_ciudad (ciudad),
        FULLTEXT idx_nombre (nombre)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    $pdo->exec($sqlCreateUbicacion);
    echo "   ✅ Tabla 'ubicacion' creada\n\n";
    
    // 2. Revertir cambios a tabla parada (quitar las columnas que agregué)
    echo "2️⃣ Reviriendo cambios a tabla 'parada'...\n";
    
    $stmt = $pdo->query("SHOW COLUMNS FROM parada LIKE 'telefono'");
    if ($stmt->rowCount() > 0) {
        $pdo->exec("ALTER TABLE parada DROP COLUMN telefono");
        echo "   ✅ Columna 'telefono' removida de parada\n";
    }
    
    $stmt = $pdo->query("SHOW COLUMNS FROM parada LIKE 'email'");
    if ($stmt->rowCount() > 0) {
        $pdo->exec("ALTER TABLE parada DROP COLUMN email");
        echo "   ✅ Columna 'email' removida de parada\n";
    }
    
    $stmt = $pdo->query("SHOW COLUMNS FROM parada LIKE 'descripcion'");
    if ($stmt->rowCount() > 0) {
        $pdo->exec("ALTER TABLE parada DROP COLUMN descripcion");
        echo "   ✅ Columna 'descripcion' removida de parada\n";
    }
    
    echo "\n";
    
    // 3. Agregar FK a ubicacion en tabla parada
    echo "3️⃣ Agregando FK 'idUbicacion' a tabla 'parada'...\n";
    
    $stmt = $pdo->query("SHOW COLUMNS FROM parada LIKE 'idUbicacion'");
    if ($stmt->rowCount() == 0) {
        $pdo->exec("ALTER TABLE parada ADD COLUMN idUbicacion INT NULL AFTER codigo_iata");
        $pdo->exec("ALTER TABLE parada ADD CONSTRAINT fk_parada_ubicacion FOREIGN KEY (idUbicacion) REFERENCES ubicacion(idUbicacion) ON DELETE SET NULL");
        echo "   ✅ FK agregado a parada\n\n";
    } else {
        echo "   ⚠️  FK 'idUbicacion' ya existe\n\n";
    }
    
    // 4. Cambiar ENUM de tipo en parada
    echo "4️⃣ Actualizando ENUM 'tipo' en parada (solo tipos de parada)...\n";
    $pdo->exec("ALTER TABLE parada MODIFY COLUMN tipo ENUM('terminal','customizada','intermedia','parada') DEFAULT 'parada'");
    echo "   ✅ ENUM actualizado\n\n";
    
    echo str_repeat("=", 60) . "\n";
    echo "✅ Arquitectura rediseñada exitosamente\n\n";
    
    // Mostrar estructura
    echo "📋 Nueva estructura de tablas:\n\n";
    
    echo "TABLE: ubicacion (centraliza toda la información)\n";
    $stmt = $pdo->query('DESCRIBE ubicacion');
    $cols = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($cols as $col) {
        echo sprintf("   %-20s %s\n", $col['Field'], $col['Type']);
    }
    
    echo "\nTABLE: parada (solo referencia ubicaciones)\n";
    $stmt = $pdo->query('DESCRIBE parada');
    $cols = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($cols as $col) {
        $marca = ($col['Field'] === 'idUbicacion' ? ' ← NUEVO FK' : '');
        echo sprintf("   %-20s %s%s\n", $col['Field'], $col['Type'], $marca);
    }
    
    echo "\n🎉 Ahora podés:\n";
    echo "   - Crear ubicaciones genéricas (hotel, terminal, restaurante, etc.)\n";
    echo "   - Asignarlas a paradas, hoteles, terminales, etc.\n";
    echo "   - Centralizar toda la info de contacto y descripción\n";
    echo "   - Evitar duplicación de datos\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
