<?php
require_once(__DIR__ . '/classes/conexion.php');

// Definir permisos por rol
$PERMISOS = [
    1 => [  // ADMIN: Todos los items
        'query' => "SELECT id FROM admin_menu WHERE enabled = 1"
    ],
    2 => [  // PRESTADOR
        'ids' => [1, 5, 17, 33, 34]  // Home, Reservas, Estado de reservas, Comisiones Vendedor, Comisiones Prestador
    ],
    3 => [  // AGENTE
        'ids' => [1, 5, 17]  // Home, Reservas, Estado de reservas
    ],
    4 => [  // COBRADOR
        'ids' => [1, 7, 33, 34]  // Home, Financiero, Comisiones Vendedor, Comisiones Prestador
    ],
    5 => [  // VENDEDOR
        'ids' => [1, 5, 17, 33]  // Home, Reservas, Estado de reservas, Comisiones Vendedor
    ]
];

echo "<h2>Configurando permisos por rol</h2>";

try {
    // Borrar todos los permisos existentes
    $GLOBALS['pdo']->exec("DELETE FROM admin_menu_roles");
    echo "<p>✓ Borrados permisos existentes</p>";
    
    $stmt = $GLOBALS['pdo']->prepare("INSERT INTO admin_menu_roles (menu_id, role_id) VALUES (?, ?)");
    
    foreach ($PERMISOS as $role_id => $config) {
        $menu_ids = [];
        
        if (isset($config['query'])) {
            // Para Admin, obtener TODOS los IDs
            $result = $GLOBALS['pdo']->query($config['query']);
            $menu_ids = $result->fetchAll(PDO::FETCH_COLUMN);
        } else if (isset($config['ids'])) {
            // Para otros roles, usar los IDs especificados
            $menu_ids = $config['ids'];
        }
        
        // Insertar permisos
        foreach ($menu_ids as $menu_id) {
            $stmt->execute([$menu_id, $role_id]);
        }
        
        $role_names = [1 => 'Admin', 2 => 'Prestador', 3 => 'Agente', 4 => 'Cobrador', 5 => 'Vendedor'];
        echo "<p>✓ Configurados " . count($menu_ids) . " permisos para " . ($role_names[$role_id] ?? "Role $role_id") . "</p>";
    }
    
    // Mostrar resumen
    echo "<h3>Resumen de permisos por rol:</h3>";
    foreach ([1, 2, 3, 4, 5] as $role_id) {
        $countStmt = $GLOBALS['pdo']->prepare("SELECT COUNT(*) as total FROM admin_menu_roles WHERE role_id = ?");
        $countStmt->execute([$role_id]);
        $count = $countStmt->fetch()['total'];
        
        $role_names = [1 => 'Admin', 2 => 'Prestador', 3 => 'Agente', 4 => 'Cobrador', 5 => 'Vendedor'];
        echo "<p><strong>" . ($role_names[$role_id] ?? "Role $role_id") . ":</strong> $count permisos</p>";
    }
    
    echo "<p style='color:green'><strong>✅ Configuración completada correctamente</strong></p>";
    
} catch (Exception $e) {
    echo "<p style='color:red'><strong>❌ Error: " . htmlspecialchars($e->getMessage()) . "</strong></p>";
}
