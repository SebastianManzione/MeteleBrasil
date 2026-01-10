<?php
// Script de migración: Crear tabla slider
require_once("classes/conexion.php");

echo "<h2>Migración: Crear tabla slider</h2>";

try {
    // Crear tabla
    $sql = "CREATE TABLE IF NOT EXISTS `slider` (
      `idSlider` int(11) NOT NULL AUTO_INCREMENT,
      `imagen` varchar(255) NOT NULL,
      `orden` int(11) NOT NULL DEFAULT 0,
      `activo` tinyint(1) NOT NULL DEFAULT 1,
      `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`idSlider`),
      KEY `idx_orden` (`orden`),
      KEY `idx_activo` (`activo`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    
    $pdo->exec($sql);
    echo "<p>✓ Tabla 'slider' creada correctamente</p>";
    
    // Verificar si existe la columna 'orden' (por si la tabla ya existía)
    $stmt = $pdo->query("SHOW COLUMNS FROM slider LIKE 'orden'");
    if ($stmt->rowCount() == 0) {
        $pdo->exec("ALTER TABLE slider ADD COLUMN orden int(11) NOT NULL DEFAULT 0 AFTER imagen");
        echo "<p>✓ Columna 'orden' agregada</p>";
    }
    
    // Verificar si ya hay datos
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM slider");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($row['total'] == 0) {
        // Insertar imágenes existentes
        $sql = "INSERT INTO `slider` (`imagen`, `orden`, `activo`) VALUES
        ('slider4.jpg', 1, 1),
        ('slider2.jpg', 2, 1),
        ('slider3.jpg', 3, 1),
        ('slider1.jpg', 4, 1)";
        
        $pdo->exec($sql);
        echo "<p>✓ 4 imágenes insertadas</p>";
    } else {
        echo "<p>ℹ Ya existen {$row['total']} imágenes en el slider</p>";
    }
    
    echo "<p><strong>Migración completada exitosamente</strong></p>";
    echo "<p><a href='sliderLista.php'>Ir a Gestión de Slider →</a></p>";
    
} catch (PDOException $e) {
    echo "<p style='color:red'>✗ Error: " . $e->getMessage() . "</p>";
}
?>
