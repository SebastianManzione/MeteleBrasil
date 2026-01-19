<?php
echo "=== MOVIENDO TABLA HOTELES A EXPERIMENTAL ===\n\n";

try {
    // 1. Conectar a experimental
    $pdo_exp = new PDO('mysql:host=localhost;dbname=metelebrasil_experimental;charset=utf8mb4', 'root', '');
    $pdo_exp->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "1. Conectado a metelebrasil_experimental\n";
    
    // 2. Crear tabla en experimental
    $sql = "CREATE TABLE IF NOT EXISTS `hoteles` (
      `idHotel` int(11) NOT NULL AUTO_INCREMENT,
      `nombre` varchar(255) NOT NULL COMMENT 'Nombre del hotel',
      `idServicio` int(11) DEFAULT NULL COMMENT 'Servicio turístico asociado (opcional)',
      `direccion` varchar(500) DEFAULT NULL,
      `ciudad` varchar(200) DEFAULT NULL,
      `estado` varchar(200) DEFAULT NULL,
      `pais` varchar(100) DEFAULT NULL,
      `codigo_postal` varchar(20) DEFAULT NULL,
      `latitud` decimal(10,8) DEFAULT NULL,
      `longitud` decimal(11,8) DEFAULT NULL,
      `telefono` varchar(50) DEFAULT NULL,
      `email` varchar(200) DEFAULT NULL,
      `sitio_web` varchar(255) DEFAULT NULL,
      `estrellas` tinyint(1) DEFAULT NULL COMMENT '1-5 estrellas',
      `descripcion` text DEFAULT NULL,
      `servicios` text DEFAULT NULL COMMENT 'Wifi, piscina, desayuno, etc.',
      `check_in` varchar(20) DEFAULT NULL COMMENT 'Hora de check-in',
      `check_out` varchar(20) DEFAULT NULL COMMENT 'Hora de check-out',
      `habitaciones_total` int(11) DEFAULT NULL,
      `precio_desde` decimal(10,2) DEFAULT NULL COMMENT 'Precio base por noche',
      `idMoneda` int(11) DEFAULT 1 COMMENT 'Moneda del precio',
      `habilitado` tinyint(1) DEFAULT 1,
      `fecha_alta` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`idHotel`),
      KEY `idx_ciudad` (`ciudad`),
      KEY `idx_pais` (`pais`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    
    $pdo_exp->exec($sql);
    echo "2. ✓ Tabla 'hoteles' creada en metelebrasil_experimental\n\n";
    
    // 3. Verificar estructura
    echo "3. Estructura:\n";
    $stmt = $pdo_exp->query("DESCRIBE hoteles");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "   • {$row['Field']} ({$row['Type']})\n";
    }
    
    echo "\n✓✓✓ TABLA HOTELES LISTA EN EXPERIMENTAL\n";
    echo "\nNOTA: Ahora TODO el desarrollo nuevo va en metelebrasil_experimental\n";
    
} catch (PDOException $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}
?>
