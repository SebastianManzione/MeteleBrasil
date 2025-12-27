<?php
/**
 * Inicializar tablas requeridas por el sistema
 * Ejecutar una vez al iniciar la aplicación
 */

require_once(__DIR__ . '/conexion.php');

class InitializeTables {
    private $pdo;
    
    public function __construct() {
        $this->pdo = $GLOBALS['pdo'] ?? null;
        if (!$this->pdo) {
            throw new Exception('Conexión a BD no disponible');
        }
    }
    
    /**
     * Crear tabla de configuración
     */
    public function crearTablaConfiguracion() {
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
            return ['success' => true, 'message' => 'Tabla configuracion creada/verificada'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    /**
     * Crear tabla para rate limiting anti-bot
     */
    public function crearTablaRateLimit() {
        $sql = "CREATE TABLE IF NOT EXISTS form_rate_limit (
            id INT AUTO_INCREMENT PRIMARY KEY,
            ip_address VARCHAR(45) NOT NULL,
            action VARCHAR(50) NOT NULL,
            attempt_time DATETIME NOT NULL,
            INDEX idx_ip_action (ip_address, action),
            INDEX idx_time (attempt_time)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        
        try {
            $this->pdo->exec($sql);
            return ['success' => true, 'message' => 'Tabla form_rate_limit creada/verificada'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    /**
     * Crear tabla para registro de intentos de bot
     */
    public function crearTableBotAttempts() {
        $sql = "CREATE TABLE IF NOT EXISTS bot_attempts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            ip_address VARCHAR(45) NOT NULL,
            tipo VARCHAR(50) NOT NULL,
            detalles TEXT,
            user_agent TEXT,
            attempt_time DATETIME NOT NULL,
            INDEX idx_ip (ip_address),
            INDEX idx_time (attempt_time)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        
        try {
            $this->pdo->exec($sql);
            return ['success' => true, 'message' => 'Tabla bot_attempts creada/verificada'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Crear tabla para plantillas de email
     */
    public function crearTablaEmailTemplates() {
        $sql = "CREATE TABLE IF NOT EXISTS email_templates (
            id INT AUTO_INCREMENT PRIMARY KEY,
            clave VARCHAR(100) NOT NULL,
            idioma VARCHAR(5) NOT NULL DEFAULT 'ES',
            asunto VARCHAR(255) NOT NULL,
            html LONGTEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            UNIQUE KEY uniq_clave_idioma (clave, idioma),
            INDEX idx_clave (clave),
            INDEX idx_idioma (idioma)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

        try {
            $this->pdo->exec($sql);
            return ['success' => true, 'message' => 'Tabla email_templates creada/verificada'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Crear tablas para menú de administración
     */
    public function crearTablasAdminMenu() {
        $results = [];

        $sqlMenu = "CREATE TABLE IF NOT EXISTS admin_menu (
            id INT AUTO_INCREMENT PRIMARY KEY,
            label VARCHAR(100) NOT NULL,
            route VARCHAR(120) NOT NULL,
            icon VARCHAR(120) DEFAULT 'fas fa-circle',
            color_class VARCHAR(60) DEFAULT NULL,
            parent_id INT DEFAULT NULL,
            sort_order INT DEFAULT 100,
            enabled TINYINT(1) DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_parent (parent_id),
            INDEX idx_route (route)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

        $sqlRoles = "CREATE TABLE IF NOT EXISTS admin_menu_roles (
            menu_id INT NOT NULL,
            role_id INT NOT NULL,
            PRIMARY KEY (menu_id, role_id),
            CONSTRAINT fk_admin_menu_roles_menu FOREIGN KEY (menu_id) REFERENCES admin_menu(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

        try { $this->pdo->exec($sqlMenu); $results[] = ['success' => true, 'message' => 'Tabla admin_menu creada/verificada']; } catch (PDOException $e) { $results[] = ['success' => false, 'message' => $e->getMessage()]; }
        try { $this->pdo->exec($sqlRoles); $results[] = ['success' => true, 'message' => 'Tabla admin_menu_roles creada/verificada']; } catch (PDOException $e) { $results[] = ['success' => false, 'message' => $e->getMessage()]; }

        // Seed completo si la tabla está vacía o tiene pocos items
        try {
            $count = (int)$this->pdo->query("SELECT COUNT(*) FROM admin_menu")->fetchColumn();
            $countRoles = (int)$this->pdo->query("SELECT COUNT(*) FROM admin_menu_roles")->fetchColumn();
            
            // IMPORTANTE: Si ya hay datos en admin_menu_roles, NO hacer TRUNCATE
            // para preservar los permisos configurados manualmente
            if ($count <= 3 && $countRoles === 0) {
                // Solo hacer seed si admin_menu está vacío Y admin_menu_roles está vacío
                $this->pdo->exec("TRUNCATE admin_menu_roles");
                $this->pdo->exec("TRUNCATE admin_menu");

                $stmt = $this->pdo->prepare("INSERT INTO admin_menu (label, route, icon, color_class, parent_id, sort_order) VALUES (?, ?, ?, ?, ?, ?)");

                $items = [
                    ['Home', 'index', 'fas fa-home', NULL, NULL, 10],
                    ['Prestadores', '#', 'fas fa-id-card-alt', NULL, NULL, 20],
                    ['Blog', '#', 'fas fa-newspaper', NULL, NULL, 30],
                    ['Servicios', '#', 'fa fa-concierge-bell', NULL, NULL, 40],
                    ['Reservas', '#', 'fa fa-calendar-check', NULL, NULL, 50],
                    ['Administración', '#', 'fa fa-briefcase', NULL, NULL, 60],
                    ['Financiero', '#', 'fa fa-comment-dollar', NULL, NULL, 70],
                    ['Test', '#', 'fas fa-vial', NULL, NULL, 80],
                    ['Menú Editor', 'menuEditor', 'fas fa-edit', 'text-info', NULL, 90],
                ];

                $menuIds = [];
                foreach ($items as [$label, $route, $icon, $color, $parent, $order]) {
                    $stmt->execute([$label, $route, $icon, $color, $parent, $order]);
                    $menuIds[$label] = (int)$this->pdo->lastInsertId();
                }

                // Prestadores submenu
                $stmt->execute(['Lista de prestadores', 'prestadores', 'fas fa-id-card', NULL, $menuIds['Prestadores'], 21]);
                $stmt->execute(['Alta prestador', 'altaPrestador', 'fas fa-plus', NULL, $menuIds['Prestadores'], 22]);

                // Blog submenu
                $stmt->execute(['Lista de artículos', 'blogLista', 'fas fa-list', NULL, $menuIds['Blog'], 31]);
                $stmt->execute(['Alta artículo', 'blogAlta', 'fas fa-plus', NULL, $menuIds['Blog'], 32]);

                // Servicios submenu
                $stmt->execute(['Alta servicio', 'altaServicio', 'fa fa-plus', NULL, $menuIds['Servicios'], 41]);
                $stmt->execute(['Lista de servicios', 'serviciosLista', 'fa fa-file-alt', NULL, $menuIds['Servicios'], 42]);

                // Reservas submenu
                $stmt->execute(['Carrito', 'carritosLista', 'fa fa-cart-arrow-down', NULL, $menuIds['Reservas'], 51]);
                $stmt->execute(['Estado de reservas', 'reservasEstado', 'fa fa-compact-disc', NULL, $menuIds['Reservas'], 52]);

                // Administración submenu
                $stmt->execute(['Usuarios', 'usuariosLista', 'fa fa-address-book', NULL, $menuIds['Administración'], 61]);
                $stmt->execute(['Solicitudes', 'solicitudes', 'fa fa-file-contract', NULL, $menuIds['Administración'], 62]);
                $stmt->execute(['Contacto', 'contacto', 'fa fa-envelope', NULL, $menuIds['Administración'], 63]);
                $stmt->execute(['Cupones', 'cupones', 'fas fa-percent', NULL, $menuIds['Administración'], 64]);
                $stmt->execute(['Edades', 'edades', 'fas fa-user', NULL, $menuIds['Administración'], 65]);
                $stmt->execute(['Cancelaciones', 'cancelaciones', 'fas fa-user-times', NULL, $menuIds['Administración'], 66]);
                $stmt->execute(['Moneda', 'monedaAdmin', 'fa fa-file-invoice-dollar', NULL, $menuIds['Administración'], 67]);
                $stmt->execute(['Textos Editor', 'textoMiniaturaLista', 'fa fa-text-width', NULL, $menuIds['Administración'], 68]);
                $stmt->execute(['Accesibilidad', 'textosAccesibilidad', 'fa fa-universal-access', NULL, $menuIds['Administración'], 69]);
                $stmt->execute(['Destinos', 'destinosAlta', 'fa fa-map-marker-alt', NULL, $menuIds['Administración'], 70]);
                $stmt->execute(['Servicios Adicionales', 'serviciosAdicionalesEditor.php', 'fa fa-plus-circle', NULL, $menuIds['Administración'], 71]);
                $stmt->execute(['Categorías', 'categoriasLista', 'fa fa-sitemap', NULL, $menuIds['Administración'], 72]);
                $stmt->execute(['Comisiones', 'comisionesEditor', 'fa fa-handshake', NULL, $menuIds['Administración'], 73]);
                $stmt->execute(['Parámetros', 'configuracion#parametros', 'fa fa-sliders-h', NULL, $menuIds['Administración'], 74]);

                // Financiero submenu
                $stmt->execute(['Comprobantes', 'comprobantesLista', 'fa fa-check-square', NULL, $menuIds['Financiero'], 81]);
                $stmt->execute(['Comisiones Vendedor', 'comisionesLista', 'fa fa-handshake', NULL, $menuIds['Financiero'], 82]);
                $stmt->execute(['Comisiones Prestador', 'financieroSalidas', 'fas fa-check', NULL, $menuIds['Financiero'], 83]);
                $stmt->execute(['Cobro Signal', 'cobroSignal', 'fas fa-cash-register', NULL, $menuIds['Financiero'], 84]);

                // Test submenu
                $stmt->execute(['Emails', 'emailsLista', 'fas fa-list-ol', NULL, $menuIds['Test'], 91]);

                // Grant all menu items to role 1 (admin)
                $allIds = $this->pdo->query("SELECT id FROM admin_menu")->fetchAll(PDO::FETCH_COLUMN);
                $insRole = $this->pdo->prepare("INSERT IGNORE INTO admin_menu_roles (menu_id, role_id) VALUES (?, 1)");
                foreach ($allIds as $id) { $insRole->execute([$id]); }

                $results[] = ['success' => true, 'message' => 'Seed admin_menu completo aplicado'];
            } else {
                $results[] = ['success' => true, 'message' => 'admin_menu ya tiene datos'];
            }

            // Habilitar feature flag en configuracion si no existe
            $exists = $this->pdo->prepare("SELECT COUNT(*) FROM configuracion WHERE clave = 'admin_menu_db_enabled'");
            $exists->execute();
            if ((int)$exists->fetchColumn() === 0) {
                $ins = $this->pdo->prepare("INSERT INTO configuracion (clave, valor, tipo, descripcion, visible_admin) VALUES ('admin_menu_db_enabled', 'false', 'boolean', 'Usar menú de administración desde BD', 1)");
                $ins->execute();
            }
        } catch (PDOException $e) {
            $results[] = ['success' => false, 'message' => 'Seed admin_menu error: ' . $e->getMessage()];
        }

        return $results;
    }
    
    /**
     * Ejecutar todas las inicializaciones
     */
    public function inicializar() {
        $results = [];
        
        $results[] = $this->crearTablaConfiguracion();
        $results[] = $this->crearTablaRateLimit();
        $results[] = $this->crearTableBotAttempts();
        $results[] = $this->crearTablaEmailTemplates();
        $results[] = $this->crearTablasAdminMenu();
        
        return $results;
    }
}

// Si se ejecuta directamente (CLI o AJAX)
if (php_sapi_name() === 'cli' || (isset($_REQUEST['action']) && $_REQUEST['action'] === 'init')) {
    try {
        $init = new InitializeTables();
        $results = $init->inicializar();
        
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'success' => true,
            'message' => 'Inicialización completada',
            'results' => $results
        ]);
    } catch (Exception $e) {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
}
