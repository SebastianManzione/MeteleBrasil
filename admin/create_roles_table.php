<?php
require_once(__DIR__ . '/classes/conexion.php');

echo "=== Creando tabla de roles ===\n\n";

// Crear tabla de roles si no existe
$sql = "CREATE TABLE IF NOT EXISTS roles (
    idRol INT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    descripcion TEXT,
    activo TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY idx_nombre (nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

$GLOBALS['pdo']->exec($sql);
echo "✅ Tabla 'roles' creada/verificada\n\n";

// Insertar roles básicos
$roles = [
    [1, 'Admin', 'Administrador con acceso completo al sistema'],
    [2, 'Vendedor', 'Vendedor con acceso a reservas y comisiones'],
    [3, 'Prestador', 'Prestador de servicios con acceso limitado'],
    [4, 'Cobrador', 'Cobrador con acceso a módulo financiero']
];

echo "Insertando roles básicos:\n";
foreach ($roles as $role) {
    $stmt = $GLOBALS['pdo']->prepare("
        INSERT INTO roles (idRol, nombre, descripcion) 
        VALUES (?, ?, ?) 
        ON DUPLICATE KEY UPDATE 
            nombre = VALUES(nombre),
            descripcion = VALUES(descripcion)
    ");
    $stmt->execute($role);
    echo "  - {$role[1]} (ID: {$role[0]})\n";
}

echo "\n✅ Roles configurados correctamente\n";
?>
