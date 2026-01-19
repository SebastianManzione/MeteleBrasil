<?php
/**
 * Migración: Agregar soporte para hoteles en tabla parada
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('admin/classes/conexion.php');

echo "🔧 Migración: Soporte para Hoteles\n\n";

try {
    // 1. Modificar ENUM para agregar 'hotel'
    echo "1️⃣ Modificando ENUM de tipo para incluir 'hotel'...\n";
    $pdo->exec("ALTER TABLE parada MODIFY COLUMN tipo ENUM('terminal','customizada','intermedia','hotel') DEFAULT 'terminal'");
    echo "   ✅ ENUM actualizado\n\n";
    
    // 2. Agregar columna telefono
    echo "2️⃣ Agregando columna 'telefono'...\n";
    $stmt = $pdo->query("SHOW COLUMNS FROM parada LIKE 'telefono'");
    if ($stmt->rowCount() == 0) {
        $pdo->exec("ALTER TABLE parada ADD COLUMN telefono VARCHAR(50) NULL AFTER codigo_iata");
        echo "   ✅ Columna 'telefono' agregada\n\n";
    } else {
        echo "   ⚠️  Columna 'telefono' ya existe\n\n";
    }
    
    // 3. Agregar columna email
    echo "3️⃣ Agregando columna 'email'...\n";
    $stmt = $pdo->query("SHOW COLUMNS FROM parada LIKE 'email'");
    if ($stmt->rowCount() == 0) {
        $pdo->exec("ALTER TABLE parada ADD COLUMN email VARCHAR(255) NULL AFTER telefono");
        echo "   ✅ Columna 'email' agregada\n\n";
    } else {
        echo "   ⚠️  Columna 'email' ya existe\n\n";
    }
    
    // 4. Agregar columna descripcion
    echo "4️⃣ Agregando columna 'descripcion'...\n";
    $stmt = $pdo->query("SHOW COLUMNS FROM parada LIKE 'descripcion'");
    if ($stmt->rowCount() == 0) {
        $pdo->exec("ALTER TABLE parada ADD COLUMN descripcion TEXT NULL AFTER email");
        echo "   ✅ Columna 'descripcion' agregada\n\n";
    } else {
        echo "   ⚠️  Columna 'descripcion' ya existe\n\n";
    }
    
    echo str_repeat("=", 50) . "\n";
    echo "✅ Migración completada exitosamente\n\n";
    
    // Mostrar estructura actualizada
    echo "📋 Estructura actualizada de 'parada':\n\n";
    $stmt = $pdo->query('DESCRIBE parada');
    $columnas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($columnas as $col) {
        $marcador = in_array($col['Field'], ['tipo', 'telefono', 'email', 'descripcion']) ? ' ← ACTUALIZADO' : '';
        echo sprintf("   %-20s %s%s\n", $col['Field'], $col['Type'], $marcador);
    }
    
    echo "\n🎉 Ahora puedes crear hoteles con los campos completos\n";
    echo "👉 Ejecuta: php insertar_hoteles.php\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
