<?php
/**
 * Script para crear tabla de paradas customizadas
 */

require_once("admin/classes/conexion.php");

try {
    // Crear tabla parada_customizada si no existe
    $sql = "CREATE TABLE IF NOT EXISTS parada_customizada (
        idParadaCustomizada INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(255) NOT NULL,
        direccion TEXT,
        ciudad VARCHAR(100),
        estado VARCHAR(100),
        pais VARCHAR(100),
        latitud DECIMAL(10, 8),
        longitud DECIMAL(11, 8),
        tipo_parada VARCHAR(50) COMMENT 'terminal, intermedia, otro',
        habilitado TINYINT DEFAULT 1,
        fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_ciudad (ciudad),
        INDEX idx_pais (pais)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    
    $pdo->exec($sql);
    echo "✓ Tabla parada_customizada creada exitosamente<br>";
    
    // Agregar columnas a ruta_paradas si no existen
    $columnas_a_agregar = [
        "idParadaCustomizada INT DEFAULT NULL COMMENT 'FK a parada_customizada si no es terminal'",
        "tipo_parada_registro VARCHAR(50) COMMENT 'terminal o customizada'"
    ];
    
    $schema = $pdo->query("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='ruta_paradas' AND TABLE_SCHEMA='metelebrasil'")->fetchAll();
    $columnas_existentes = array_map(fn($c) => $c['COLUMN_NAME'], $schema);
    
    foreach ($columnas_a_agregar as $def) {
        $nombre = trim(explode(' ', $def)[0]);
        if (!in_array($nombre, $columnas_existentes)) {
            $pdo->exec("ALTER TABLE ruta_paradas ADD COLUMN " . $def);
            echo "✓ Columna $nombre agregada<br>";
        }
    }
    
    echo "<br><strong>✓ Estructura de base de datos actualizada correctamente</strong>";
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage();
}
?>
<script>setTimeout(() => window.location.href = 'admin/modeloVehiculosLista.php', 3000);</script>
