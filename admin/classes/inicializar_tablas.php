<?php
/**
 * Inicialización automática de tablas de configuración
 * Se ejecuta una sola vez al cargar conexion.php
 */

if (!function_exists('inicializarTablasConfiguracion')) {
    function inicializarTablasConfiguracion($pdo) {
        try {
            // Verificar si ya existe la tabla config
            $stmt = $pdo->query("SHOW TABLES LIKE 'config'");
            if ($stmt->rowCount() > 0) {
                return; // Ya existe, no hacer nada
            }
            
            // Crear tabla config si no existe
            $pdo->exec("
                CREATE TABLE IF NOT EXISTS `config` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `clave` varchar(100) NOT NULL,
                    `valor` text,
                    `descripcion` varchar(500) DEFAULT NULL,
                    `tipo` enum('texto','numero','boolean','json') DEFAULT 'texto',
                    `actualizado` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `clave` (`clave`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
            ");
            
            // Insertar configuraciones por defecto
            $configs = [
                ['modo_mantenimiento', '0', 'Activar/desactivar modo mantenimiento (0=OFF, 1=ON)', 'boolean'],
                ['mensaje_mantenimiento_es', 'Sitio en mantenimiento. Vuelve pronto.', 'Mensaje español', 'texto'],
                ['mensaje_mantenimiento_en', 'Site under maintenance. Come back soon.', 'Mensaje inglés', 'texto'],
                ['mensaje_mantenimiento_pt', 'Site em manutenção. Volte em breve.', 'Mensaje portugués', 'texto'],
                ['nombre_empresa', 'Metele Brasil', 'Nombre de la empresa', 'texto'],
                ['email_contacto', 'contato@metelebrasil.com', 'Email de contacto', 'texto'],
                ['telefono_contacto', '', 'Teléfono de contacto', 'texto']
            ];
            
            $stmt = $pdo->prepare("
                INSERT IGNORE INTO config (clave, valor, descripcion, tipo) 
                VALUES (?, ?, ?, ?)
            ");
            
            foreach ($configs as $config) {
                $stmt->execute($config);
            }
            
        } catch (PDOException $e) {
            // Silenciar errores en producción
            if (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false) {
                error_log("Error inicializando tablas: " . $e->getMessage());
            }
        }
    }
}
