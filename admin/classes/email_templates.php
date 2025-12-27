<?php
require_once(__DIR__ . '/conexion.php');

class EmailTemplates {
    private $pdo;

    public function __construct() {
        $this->pdo = $GLOBALS['pdo'] ?? null;
        if (!$this->pdo) {
            throw new Exception('Conexión a BD no disponible');
        }
    }

    public function obtener($clave, $idioma = 'ES') {
        try {
            $stmt = $this->pdo->prepare("SELECT asunto, html FROM email_templates WHERE clave = ? AND idioma = ?");
            $stmt->execute([$clave, $idioma]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ?: null;
        } catch (PDOException $e) {
            return null;
        }
    }

    public function guardar($clave, $idioma, $asunto, $html) {
        try {
            $stmt = $this->pdo->prepare(
                "INSERT INTO email_templates (clave, idioma, asunto, html) VALUES (?, ?, ?, ?)
                 ON DUPLICATE KEY UPDATE asunto = VALUES(asunto), html = VALUES(html), updated_at = CURRENT_TIMESTAMP"
            );
            return $stmt->execute([$clave, $idioma, $asunto, $html]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function listarPorClave($clave) {
        try {
            $stmt = $this->pdo->prepare("SELECT idioma, asunto, updated_at FROM email_templates WHERE clave = ? ORDER BY idioma");
            $stmt->execute([$clave]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }
}

?>
