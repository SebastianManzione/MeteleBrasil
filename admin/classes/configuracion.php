<?php
/**
 * Clase para gestionar configuración global del sistema
 * Almacena: OAuth, API keys, modo mantenimiento, etc.
 */

if (!class_exists('Configuracion')) {

require_once(__DIR__ . '/conexion.php');

class Configuracion {
    private $pdo;
    
    public function __construct() {
        $this->pdo = $GLOBALS['pdo'] ?? null;
        if (!$this->pdo) {
            // En dev, no romper; solo registrar
            @file_put_contents(__DIR__ . '/../../logs/configuracion.log', date('c') . ' __construct: PDO no disponible' . PHP_EOL, FILE_APPEND);
            return;
        }
        try {
            $this->crearTabla();
        } catch (Throwable $e) {
            @file_put_contents(__DIR__ . '/../../logs/configuracion.log', date('c') . ' crearTabla error: ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
        }
    }
    
    /**
     * Crear tabla de configuración si no existe
     */
    private function crearTabla() {
        $sql = "CREATE TABLE IF NOT EXISTS configuracion (
            id INT AUTO_INCREMENT PRIMARY KEY,
            clave VARCHAR(100) UNIQUE NOT NULL,
            valor LONGTEXT,
            tipo ENUM('string', 'number', 'boolean', 'json') DEFAULT 'string',
            descripcion TEXT,
            visible_admin BOOLEAN DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_clave (clave)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        
        try {
            $this->pdo->exec($sql);
        } catch (PDOException $e) {
            // Tabla ya existe, es ok
        }
    }
    
    /**
     * Obtener valor de configuración
     */
    public function obtener($clave, $default = null) {
        try {
            $stmt = $this->pdo->prepare("SELECT valor, tipo FROM configuracion WHERE clave = ?");
            $stmt->execute([$clave]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$result) {
                return $default;
            }
            
            $valor = $result['valor'];
            $tipo = $result['tipo'];
            
            // Convertir según tipo
            if ($tipo === 'json') {
                return json_decode($valor, true);
            } elseif ($tipo === 'boolean') {
                return $valor === '1' || $valor === 'true';
            } elseif ($tipo === 'number') {
                return (float)$valor;
            }
            
            return $valor;
        } catch (PDOException $e) {
            return $default;
        }
    }
    
    /**
     * Guardar/actualizar configuración
     */
    public function guardar($clave, $valor, $tipo = 'string', $descripcion = '') {
        try {
            // Si es array/objeto, convertir a JSON
            if (is_array($valor) || is_object($valor)) {
                $valor = json_encode($valor);
                $tipo = 'json';
            } elseif (is_bool($valor)) {
                $valor = $valor ? '1' : '0';
                $tipo = 'boolean';
            } elseif (is_numeric($valor) && $tipo === 'string') {
                $tipo = 'number';
            }
            
            // Verificar si existe
            $stmt = $this->pdo->prepare("SELECT id FROM configuracion WHERE clave = ?");
            $stmt->execute([$clave]);
            $existe = $stmt->rowCount() > 0;
            
            if ($existe) {
                // Actualizar
                $stmt = $this->pdo->prepare(
                    "UPDATE configuracion 
                     SET valor = ?, tipo = ?, descripcion = ?, updated_at = NOW()
                     WHERE clave = ?"
                );
                return $stmt->execute([$valor, $tipo, $descripcion, $clave]);
            } else {
                // Insertar
                $stmt = $this->pdo->prepare(
                    "INSERT INTO configuracion (clave, valor, tipo, descripcion)
                     VALUES (?, ?, ?, ?)"
                );
                return $stmt->execute([$clave, $valor, $tipo, $descripcion]);
            }
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Obtener todas las configuraciones
     */
    public function obtenerTodas($visibles_solo = true) {
        try {
            $sql = "SELECT * FROM configuracion";
            if ($visibles_solo) {
                $sql .= " WHERE visible_admin = 1";
            }
            $sql .= " ORDER BY clave ASC";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $config = [];
            foreach ($result as $row) {
                $config[$row['clave']] = [
                    'valor' => $row['valor'],
                    'tipo' => $row['tipo'],
                    'descripcion' => $row['descripcion']
                ];
            }
            
            return $config;
        } catch (PDOException $e) {
            return [];
        }
    }
    
    /**
     * Eliminar configuración
     */
    public function eliminar($clave) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM configuracion WHERE clave = ?");
            return $stmt->execute([$clave]);
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Verificar si modo mantenimiento está activo
     */
    public function mantenimientoActivo() {
        return (bool)$this->obtener('mantenimiento_activo', false);
    }
    
    /**
     * Obtener mensaje de mantenimiento
     */
    public function obtenerMensajeMantenimiento() {
        return $this->obtener(
            'mantenimiento_mensaje',
            'El sitio está en mantenimiento. Por favor, intenta nuevamente más tarde.'
        );
    }
    
    /**
     * Cerrar conexión (no necesario con PDO)
     */
    public function __destruct() {
        // PDO se cierra automáticamente
    }
}

} // fin if !class_exists('Configuracion')

// Instancia global
if (!isset($config)) {
    $config = new Configuracion();
}
