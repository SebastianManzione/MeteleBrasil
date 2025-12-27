<?php
require_once(__DIR__ . '/conexion.php');

class AdminMenu {
    private $pdo;

    public function __construct() {
        $this->pdo = $GLOBALS['pdo'] ?? null;
        if (!$this->pdo) {
            throw new Exception('Conexión a BD no disponible');
        }
    }

    /**
     * Obtiene el árbol de menú para un rol dado
     * Considera también idPrestador, idVendedor, idCobrador
     * @param int $roleId
     * @param array $session Datos de sesión completos
     * @return array
     */
    public function getMenuTreeForRole($roleId, $session = []) {
        // Determinar roles efectivos basados en sesión (alineado con PermisosManager)
        $roles = [];

        // Admin
        if (isset($session['rol']) && (int)$session['rol'] === 1) {
            $roles[] = 1;
        }
        // Prestador
        if (isset($session['idPrestador']) && (int)$session['idPrestador'] > 0) {
            $roles[] = 2;
        }
        // Vendedor
        if (isset($session['idVendedor']) && (int)$session['idVendedor'] > 0) {
            $roles[] = 5;
        }
        // Cobrador
        if (isset($session['idCobrador']) && (int)$session['idCobrador'] > 0) {
            $roles[] = 4;
        }
        // Agente
        if (isset($session['rol']) && (int)$session['rol'] === 3) {
            $roles[] = 3;
        }
        $roles = array_unique($roles);

        // Si no se detectó ningún rol, usar roleId recibido como fallback
        if (empty($roles) && $roleId) {
            $roles = [(int)$roleId];
        }

        // Verificar si hay permisos para ALGUNO de los roles
        $placeholders = implode(',', array_fill(0, count($roles), '?'));
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as total FROM admin_menu_roles WHERE role_id IN ($placeholders)");
        $stmt->execute($roles);
        $hasPermissionsForRoles = $stmt->fetch()['total'] > 0;
        
        // FALLBACK: Si no hay permisos para ninguno de los roles detectados, mostrar todo
        // (la protección se hace via verificarAcceso() en cada página)
        if (!$hasPermissionsForRoles) {
            $sql = "SELECT m.id, m.label, m.route, m.icon, m.color_class, m.parent_id, m.sort_order
                    FROM admin_menu m
                    WHERE m.enabled = 1
                    ORDER BY COALESCE(m.parent_id, m.id), m.sort_order, m.id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
        } else {
            
            // Query mejorada: Trae items con permiso + sus ancestros (padres necesarios)
            $sql = "WITH RECURSIVE menu_with_parents AS (
                        -- Items con permiso directo
                        SELECT DISTINCT m.id, m.label, m.route, m.icon, m.color_class, m.parent_id, m.sort_order
                        FROM admin_menu m
                        INNER JOIN admin_menu_roles r ON r.menu_id = m.id
                        WHERE r.role_id IN ($placeholders) AND m.enabled = 1
                        
                        UNION
                        
                        -- Padres recursivos de esos items
                        SELECT m.id, m.label, m.route, m.icon, m.color_class, m.parent_id, m.sort_order
                        FROM admin_menu m
                        INNER JOIN menu_with_parents mwp ON m.id = mwp.parent_id
                        WHERE m.enabled = 1
                    )
                    SELECT DISTINCT id, label, route, icon, color_class, parent_id, sort_order
                    FROM menu_with_parents
                    ORDER BY COALESCE(parent_id, id), sort_order, id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($roles);
        }
        
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Organizar en árbol
        $byParent = [];
        foreach ($items as $it) {
            $pid = $it['parent_id'] ? (int)$it['parent_id'] : 0;
            if (!isset($byParent[$pid])) { $byParent[$pid] = []; }
            $byParent[$pid][] = $it;
        }
        
        $tree = [];
        $top = $byParent[0] ?? [];
        foreach ($top as $parent) {
            $pid = (int)$parent['id'];
            $children = $byParent[$pid] ?? [];
            $parent['children'] = $children;
            $tree[] = $parent;
        }
        return $tree;
    }

    /**
     * Obtiene un valor de configuración
     */
    public function getConfig($clave, $default = null) {
        try {
            $stmt = $this->pdo->prepare("SELECT valor, tipo FROM configuracion WHERE clave = :clave LIMIT 1");
            $stmt->execute([':clave' => $clave]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) return $default;
            $valor = $row['valor'];
            $tipo = $row['tipo'] ?? 'string';
            if ($tipo === 'boolean') {
                return in_array(strtolower($valor), ['1','true','yes','si'], true);
            }
            return $valor;
        } catch (Exception $e) {
            return $default;
        }
    }
}

?>
