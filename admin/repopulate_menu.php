<?php
require_once(__DIR__ . '/classes/conexion.php');

try {
    $pdo = $GLOBALS['pdo'];
    
    // Desactivar FK temporalmente
    $pdo->exec("SET FOREIGN_KEY_CHECKS=0");
    $pdo->exec("TRUNCATE admin_menu_roles");
    $pdo->exec("TRUNCATE admin_menu");
    $pdo->exec("SET FOREIGN_KEY_CHECKS=1");
    
    $stmt = $pdo->prepare("INSERT INTO admin_menu (label, route, icon, color_class, parent_id, sort_order) VALUES (?, ?, ?, ?, ?, ?)");
    
    $items = [
        ['Home', 'index', 'fas fa-home', NULL, NULL, 10],
        ['Prestadores', '#', 'fas fa-user-tie', 'text-info', NULL, 20],
        ['Blog', '#', 'fas fa-newspaper', 'text-warning', NULL, 30],
        ['Servicios', '#', 'fas fa-concierge-bell', 'text-primary', NULL, 40],
        ['Reservas', '#', 'fas fa-calendar-alt', 'text-success', NULL, 50],
        ['Administración', '#', 'fas fa-cog', 'text-secondary', NULL, 60],
        ['Financiero', '#', 'fas fa-dollar-sign', 'text-danger', NULL, 70],
        ['Test', '#', 'fas fa-vial', 'text-muted', NULL, 80],
        ['Menú Editor', 'menuEditor', 'fas fa-edit', 'text-info', NULL, 90],
    ];
    
    $menuIds = [];
    foreach ($items as [$label, $route, $icon, $color, $parent, $order]) {
        $stmt->execute([$label, $route, $icon, $color, $parent, $order]);
        $menuIds[$label] = (int)$pdo->lastInsertId();
    }
    
    // Prestadores
    $stmt->execute(['Lista de prestadores', 'prestadores', 'fas fa-list', 'text-info', $menuIds['Prestadores'], 21]);
    $stmt->execute(['Alta prestador', 'altaPrestador', 'fas fa-user-plus', 'text-info', $menuIds['Prestadores'], 22]);
    
    // Blog
    $stmt->execute(['Lista de artículos', 'blogLista', 'fas fa-list-ul', 'text-warning', $menuIds['Blog'], 31]);
    $stmt->execute(['Alta artículo', 'blogAlta', 'fas fa-pen-fancy', 'text-warning', $menuIds['Blog'], 32]);
    
    // Servicios
    $stmt->execute(['Alta servicio', 'altaServicio', 'fas fa-plus-circle', 'text-primary', $menuIds['Servicios'], 41]);
    $stmt->execute(['Lista de servicios', 'serviciosLista', 'fas fa-list', 'text-primary', $menuIds['Servicios'], 42]);
    
    // Reservas
    $stmt->execute(['Carrito', 'carritosLista', 'fas fa-shopping-cart', 'text-success', $menuIds['Reservas'], 51]);
    $stmt->execute(['Estado de reservas', 'reservasEstado', 'fas fa-check-circle', 'text-success', $menuIds['Reservas'], 52]);
    
    // Administración
    $stmt->execute(['Usuarios', 'usuariosLista', 'fas fa-users', 'text-secondary', $menuIds['Administración'], 61]);
    $stmt->execute(['Solicitudes', 'solicitudes', 'fas fa-file-alt', 'text-secondary', $menuIds['Administración'], 62]);
    $stmt->execute(['Contacto', 'contacto', 'fas fa-envelope', 'text-secondary', $menuIds['Administración'], 63]);
    $stmt->execute(['Cupones', 'cupones', 'fas fa-ticket-alt', 'text-secondary', $menuIds['Administración'], 64]);
    $stmt->execute(['Edades', 'edades', 'fas fa-birthday-cake', 'text-secondary', $menuIds['Administración'], 65]);
    $stmt->execute(['Cancelaciones', 'cancelaciones', 'fas fa-times-circle', 'text-secondary', $menuIds['Administración'], 66]);
    $stmt->execute(['Moneda', 'monedaAdmin', 'fas fa-coins', 'text-secondary', $menuIds['Administración'], 67]);
    $stmt->execute(['Textos Editor', 'textoMiniaturaLista', 'fas fa-heading', 'text-secondary', $menuIds['Administración'], 68]);
    $stmt->execute(['Accesibilidad', 'textosAccesibilidad', 'fas fa-universal-access', 'text-secondary', $menuIds['Administración'], 69]);
    $stmt->execute(['Destinos', 'destinosAlta', 'fas fa-map-marker-alt', 'text-secondary', $menuIds['Administración'], 70]);
    $stmt->execute(['Servicios Adicionales', 'serviciosAdicionalesEditor.php', 'fas fa-plus-square', 'text-secondary', $menuIds['Administración'], 71]);
    $stmt->execute(['Categorías', 'categoriasLista', 'fas fa-sitemap', 'text-secondary', $menuIds['Administración'], 72]);
    $stmt->execute(['Comisiones', 'comisionesEditor', 'fas fa-percentage', 'text-secondary', $menuIds['Administración'], 73]);
    $stmt->execute(['Parámetros', 'configuracion#parametros', 'fas fa-sliders-h', 'text-secondary', $menuIds['Administración'], 74]);
    
    // Financiero
    $stmt->execute(['Comprobantes', 'comprobantesLista', 'fas fa-receipt', 'text-danger', $menuIds['Financiero'], 81]);
    $stmt->execute(['Comisiones Vendedor', 'comisionesLista', 'fas fa-handshake', 'text-danger', $menuIds['Financiero'], 82]);
    $stmt->execute(['Comisiones Prestador', 'financieroSalidas', 'fas fa-chart-pie', 'text-danger', $menuIds['Financiero'], 83]);
    $stmt->execute(['Cobro Signal', 'cobroSignal', 'fas fa-credit-card', 'text-danger', $menuIds['Financiero'], 84]);
    
    // Test
    $stmt->execute(['Emails', 'emailsLista', 'fas fa-envelope-open', 'text-muted', $menuIds['Test'], 91]);
    
    // Grant all to role 1
    $allIds = $pdo->query("SELECT id FROM admin_menu")->fetchAll(PDO::FETCH_COLUMN);
    $insRole = $pdo->prepare("INSERT INTO admin_menu_roles (menu_id, role_id) VALUES (?, 1)");
    foreach ($allIds as $id) { $insRole->execute([$id]); }
    
    echo json_encode(['success' => true, 'message' => 'Menú repoblado', 'items' => count($allIds)]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
