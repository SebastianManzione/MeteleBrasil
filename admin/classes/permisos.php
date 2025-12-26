<?php
/**
 * Clase PermisosManager
 * Gestiona la verificación de permisos de usuario basado en rutas del menú
 */

class PermisosManager {
    private $pdo;
    private $session;
    private $cache_permisos = [];

    public function __construct($pdo, $session = null) {
        $this->pdo = $pdo;
        $this->session = $session ?? $_SESSION['login'] ?? [];
    }

    /**
     * Obtiene los permisos del rol actual
     * Considera: rol directo + idPrestador + idVendedor + idCobrador
     * 
     * @return array Array de role_ids aplicables al usuario
     */
    private function obtenerRolesAplicables() {
        $roles = [];
        
        // Si es admin, tiene todos los permisos
        if (isset($this->session['rol']) && (int)$this->session['rol'] === 1) {
            $roles[] = 1;
        }
        
        // Agregar roles por campos especiales
        if (isset($this->session['idPrestador']) && (int)$this->session['idPrestador'] > 0) {
            $roles[] = 2; // Rol Prestador
        }
        
        if (isset($this->session['idVendedor']) && (int)$this->session['idVendedor'] > 0) {
            $roles[] = 5; // Rol Vendedor
        }
        
        if (isset($this->session['idCobrador']) && (int)$this->session['idCobrador'] > 0) {
            $roles[] = 4; // Rol Cobrador
        }
        
        // Si es agente
        if (isset($this->session['rol']) && (int)$this->session['rol'] === 3) {
            $roles[] = 3;
        }
        
        return array_unique($roles);
    }

    /**
     * Obtiene todos los menús permitidos para el usuario
     * Cachea el resultado para evitar múltiples queries
     * 
     * @return array Array de menu_ids permitidos
     */
    public function obtenerMenusPermitidos() {
        $cacheKey = 'menus_' . json_encode($this->obtenerRolesAplicables());
        
        if (isset($this->cache_permisos[$cacheKey])) {
            return $this->cache_permisos[$cacheKey];
        }

        $roles = $this->obtenerRolesAplicables();
        
        if (empty($roles)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($roles), '?'));
        
        $stmt = $this->pdo->prepare("
            SELECT DISTINCT menu_id 
            FROM admin_menu_roles 
            WHERE role_id IN ($placeholders)
        ");
        
        $stmt->execute($roles);
        $menus = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        $this->cache_permisos[$cacheKey] = $menus;
        return $menus;
    }

    /**
     * Obtiene la ruta desde un menu_id
     * 
     * @param int $menuId ID del menú
     * @return string|null Ruta del menú o null
     */
    public function obtenerRutaDesdeId($menuId) {
        $stmt = $this->pdo->prepare("
            SELECT route FROM admin_menu WHERE id = ?
        ");
        $stmt->execute([$menuId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['route'] ?? null;
    }

    /**
     * Obtiene el ID del menú desde una ruta
     * 
     * @param string $ruta Ruta del menú (ej. "altaServicio")
     * @return int|null ID del menú o null
     */
    public function obtenerIdDesdeRuta($ruta) {
        $stmt = $this->pdo->prepare("
            SELECT id FROM admin_menu WHERE route = ? AND enabled = 1
        ");
        $stmt->execute([$ruta]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['id'] ?? null;
    }

    /**
     * Verifica si el usuario tiene permiso para acceder a una ruta
     * 
     * @param string $ruta Ruta a verificar (ej. "altaServicio")
     * @return bool true si tiene permiso, false si no
     */
    public function tienePermiso($ruta) {
        // Admin siempre tiene acceso
        if (isset($this->session['rol']) && (int)$this->session['rol'] === 1) {
            return true;
        }

        // Obtener ID del menú
        $menuId = $this->obtenerIdDesdeRuta($ruta);
        
        if ($menuId === null) {
            // Si no existe en el menú, denegar por defecto (páginas no listadas en menú)
            return false;
        }

        // Verificar si el menú está en los permisos del usuario
        $menusPermitidos = $this->obtenerMenusPermitidos();
        return in_array($menuId, $menusPermitidos);
    }

    /**
     * Verifica permiso y redirige si no lo tiene
     * 
     * @param string $ruta Ruta a verificar
     * @param string $redirect URL a redireccionar (default: index)
     * @return bool true si tiene permiso
     */
    public function verificarAcceso($ruta, $redirect = 'index') {
        if ($this->tienePermiso($ruta)) {
            return true;
        }

        // Log opcional del intento no autorizado
        $this->registrarIntento($ruta);

        // Redireccionar
        if (headers_sent() === false) {
            header("Location: $redirect");
            exit;
        } else {
            // Si ya se enviaron headers, usar fallback en cliente para bloquear vista
            echo '<script>window.location.href = "' . htmlspecialchars($redirect, ENT_QUOTES, 'UTF-8') . '"; </script>';
            echo '<noscript><meta http-equiv="refresh" content="0;url=' . htmlspecialchars($redirect, ENT_QUOTES, 'UTF-8') . '"></noscript>';
            exit;
        }

        return false;
    }

    /**
     * Obtiene el menú y sus hijos permitidos
     * Útil para renderizar menú dinámico
     * 
     * @return array Árbol jerárquico de menús permitidos
     */
    public function obtenerArbolMenusPermitidos() {
        $menusPermitidos = $this->obtenerMenusPermitidos();

        if (empty($menusPermitidos)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($menusPermitidos), '?'));
        
        $stmt = $this->pdo->prepare("
            SELECT id, label, route, icon, parent_id, color_class, sort_order, enabled
            FROM admin_menu
            WHERE id IN ($placeholders)
            ORDER BY parent_id, sort_order
        ");
        
        $stmt->execute($menusPermitidos);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Construir árbol
        $tree = [];
        $items_por_id = [];

        foreach ($items as $item) {
            $items_por_id[$item['id']] = $item;
            $item['children'] = [];
            if ($item['parent_id'] === null || $item['parent_id'] === '') {
                $tree[$item['id']] = $item;
            }
        }

        foreach ($items as $item) {
            if ($item['parent_id'] !== null && $item['parent_id'] !== '') {
                if (isset($items_por_id[$item['parent_id']])) {
                    $items_por_id[$item['parent_id']]['children'][] = $item;
                }
            }
        }

        return array_values($tree);
    }

    /**
     * Registra intentos de acceso no autorizado (opcional)
     * 
     * @param string $ruta Ruta intentada
     */
    private function registrarIntento($ruta) {
        // Opcional: Registrar en logs
        $usuario = $this->session['usuario'] ?? 'desconocido';
        error_log("[ACCESO DENEGADO] Usuario: $usuario | Ruta: $ruta | IP: " . $_SERVER['REMOTE_ADDR']);
    }

    /**
     * Obtiene info del usuario actual
     * 
     * @return array Datos de sesión del usuario
     */
    public function obtenerUsuarioActual() {
        return $this->session;
    }

    /**
     * Retorna todos los roles aplicables al usuario
     * Útil para debugging
     * 
     * @return array Roles y sus descripción
     */
    public function debugRoles() {
        $rolesAplicables = $this->obtenerRolesAplicables();
        
        if (empty($rolesAplicables)) {
            return [];
        }
        
        $stmt = $this->pdo->prepare("
            SELECT idRol, rol, descripcion 
            FROM roles 
            WHERE idRol IN (" . implode(',', array_fill(0, count($rolesAplicables), '?')) . ")
        ");
        
        $stmt->execute($rolesAplicables);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>
