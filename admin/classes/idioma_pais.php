<?php
// Evita redeclaraciones si el archivo se incluye más de una vez
if (function_exists('getIdiomaPorPais')) { return; }

require_once(__DIR__ . '/conexion.php');

/**
 * Obtener el idioma configurado para un código de país
 * @param string $countryCode Código ISO de país (AR, BR, US, etc.)
 * @return string|null Código de idioma (ES, PT, EN, IT) o null si no existe
 */
function getIdiomaPorPais($countryCode) {
    $pdo = $GLOBALS['pdo'] ?? null;
    if (!$pdo) return null;
    
    try {
        $stmt = $pdo->prepare("SELECT idioma FROM idioma_pais WHERE codigo_pais = ? AND activo = 1");
        $stmt->execute([strtoupper($countryCode)]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['idioma'] : null;
    } catch (Throwable $e) {
        @file_put_contents(__DIR__ . '/../../logs/idioma_pais.log', date('c') . ' getIdiomaPorPais error: ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
        return null;
    }
}

/**
 * Obtener todos los mapeos país→idioma
 * @return array
 */
function getAllIdiomasPais() {
    $pdo = $GLOBALS['pdo'] ?? null;
    if (!$pdo) return [];
    
    try {
        $stmt = $pdo->query("SELECT * FROM idioma_pais ORDER BY codigo_pais ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Throwable $e) {
        @file_put_contents(__DIR__ . '/../../logs/idioma_pais.log', date('c') . ' getAllIdiomasPais error: ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
        return [];
    }
}

/**
 * Guardar o actualizar mapeo país→idioma
 * @param string $codigoPais Código ISO de país
 * @param string $idioma Código de idioma (ES, PT, EN, IT)
 * @param string $nombrePais Nombre del país
 * @param bool $activo
 * @return bool
 */
function saveIdiomaPais($codigoPais, $idioma, $nombrePais = '', $activo = true) {
    $pdo = $GLOBALS['pdo'] ?? null;
    if (!$pdo) return false;
    
    try {
        $sql = "INSERT INTO idioma_pais (codigo_pais, idioma, nombre_pais, activo) 
                VALUES (?, ?, ?, ?) 
                ON DUPLICATE KEY UPDATE 
                idioma = VALUES(idioma), 
                nombre_pais = VALUES(nombre_pais), 
                activo = VALUES(activo)";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([strtoupper($codigoPais), strtoupper($idioma), $nombrePais, $activo ? 1 : 0]);
    } catch (Throwable $e) {
        @file_put_contents(__DIR__ . '/../../logs/idioma_pais.log', date('c') . ' saveIdiomaPais error: ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
        return false;
    }
}

/**
 * Eliminar mapeo país→idioma
 * @param string $codigoPais
 * @return bool
 */
function deleteIdiomaPais($codigoPais) {
    $pdo = $GLOBALS['pdo'] ?? null;
    if (!$pdo) return false;
    
    try {
        $stmt = $pdo->prepare("DELETE FROM idioma_pais WHERE codigo_pais = ?");
        return $stmt->execute([strtoupper($codigoPais)]);
    } catch (Throwable $e) {
        @file_put_contents(__DIR__ . '/../../logs/idioma_pais.log', date('c') . ' deleteIdiomaPais error: ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
        return false;
    }
}

/**
 * Crear tabla y poblar con valores iniciales
 * @return bool
 */
function crearTablaIdiomaPais() {
    $pdo = $GLOBALS['pdo'] ?? null;
    if (!$pdo) {
        // Si no hay PDO global, intentar crear conexión directa
        try {
            $pdo = new PDO('mysql:host=localhost;dbname=metelebrasil', 'root', '');
        } catch (Exception $e) {
            @file_put_contents(__DIR__ . '/../../logs/idioma_pais.log', date('c') . ' crearTablaIdiomaPais: No hay conexión PDO disponible' . PHP_EOL, FILE_APPEND);
            return false;
        }
    }
    
    try {
        $sql = "CREATE TABLE IF NOT EXISTS idioma_pais (
            id INT AUTO_INCREMENT PRIMARY KEY,
            codigo_pais VARCHAR(2) NOT NULL UNIQUE,
            idioma VARCHAR(2) NOT NULL,
            nombre_pais VARCHAR(100),
            activo BOOLEAN DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_pais (codigo_pais),
            INDEX idx_idioma (idioma)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        
        $pdo->exec($sql);
        
        // Poblar con valores por defecto si la tabla está vacía
        $count = $pdo->query("SELECT COUNT(*) FROM idioma_pais")->fetchColumn();
        if ($count == 0) {
            $defaults = [
                // América Latina - Español
                ['AR', 'ES', 'Argentina'],
                ['BO', 'ES', 'Bolivia'],
                ['CL', 'ES', 'Chile'],
                ['CO', 'ES', 'Colombia'],
                ['CR', 'ES', 'Costa Rica'],
                ['CU', 'ES', 'Cuba'],
                ['DO', 'ES', 'Rep. Dominicana'],
                ['EC', 'ES', 'Ecuador'],
                ['SV', 'ES', 'El Salvador'],
                ['GT', 'ES', 'Guatemala'],
                ['HN', 'ES', 'Honduras'],
                ['MX', 'ES', 'Mexico'],
                ['NI', 'ES', 'Nicaragua'],
                ['PA', 'ES', 'Panama'],
                ['PY', 'ES', 'Paraguay'],
                ['PE', 'ES', 'Peru'],
                ['UY', 'ES', 'Uruguay'],
                ['VE', 'ES', 'Venezuela'],
                ['ES', 'ES', 'Espana'],
                // Portugués
                ['BR', 'PT', 'Brasil'],
                ['PT', 'PT', 'Portugal'],
                ['AO', 'PT', 'Angola'],
                ['MZ', 'PT', 'Mozambique'],
                // Inglés
                ['US', 'EN', 'Estados Unidos'],
                ['GB', 'EN', 'Reino Unido'],
                ['CA', 'EN', 'Canada'],
                ['AU', 'EN', 'Australia'],
                ['NZ', 'EN', 'Nueva Zelanda'],
                ['IE', 'EN', 'Irlanda'],
                ['ZA', 'EN', 'Sudafrica'],
                ['IN', 'EN', 'India'],
                // Italiano
                ['IT', 'IT', 'Italia'],
                ['CH', 'IT', 'Suiza'],
                ['SM', 'IT', 'San Marino'],
                ['VA', 'IT', 'Ciudad del Vaticano']
            ];
            
            $stmt = $pdo->prepare("INSERT INTO idioma_pais (codigo_pais, idioma, nombre_pais) VALUES (?, ?, ?)");
            foreach ($defaults as $row) {
                $stmt->execute($row);
            }
        }
        
        return true;
    } catch (Throwable $e) {
        @file_put_contents(__DIR__ . '/../../logs/idioma_pais.log', date('c') . ' crearTablaIdiomaPais error: ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
        return false;
    }
}

// Crear tabla automáticamente al cargar el archivo
crearTablaIdiomaPais();
