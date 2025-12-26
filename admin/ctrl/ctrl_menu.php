<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once(__DIR__ . '/../classes/conexion.php');
require_once(__DIR__ . '/../classes/menu.php');

header('Content-Type: application/json; charset=utf-8');

// Guard: check admin role
if (!isset($_SESSION['login']) || (int)$_SESSION['login']['rol'] !== 1) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Acceso denegado']);
    exit;
}

$action = isset($_GET['action']) ? $_GET['action'] : (isset($_POST['action']) ? $_POST['action'] : null);

try {
    $menu = new AdminMenu();

    switch ($action) {
        case 'getAll':
            // Get all menu items as tree
            $tree = $menu->getMenuTreeForRole(1);
            echo json_encode(['success' => true, 'data' => $tree]);
            break;

        case 'getTree':
            // Get menu tree for a specific role (for preview)
            $role = isset($_GET['role']) ? (int)$_GET['role'] : 1;
            $tree = $menu->getMenuTreeForRole($role);
            echo json_encode(['success' => true, 'data' => $tree]);
            break;

        case 'getRoles':
            // Get available roles (for select)
            $stmt = $GLOBALS['pdo']->query("SELECT DISTINCT role_id FROM admin_menu_roles ORDER BY role_id");
            $roles = $stmt->fetchAll(PDO::FETCH_COLUMN);
            echo json_encode(['success' => true, 'data' => $roles]);
            break;

        case 'save':
            // Save menu item (insert or update)
            $id = isset($_POST['id']) ? (int)$_POST['id'] : null;
            $label = isset($_POST['label']) ? $_POST['label'] : null;
            $route = isset($_POST['route']) ? $_POST['route'] : null;
            $icon = isset($_POST['icon']) ? $_POST['icon'] : 'fas fa-circle';
            $color = isset($_POST['color_class']) ? $_POST['color_class'] : null;
            $parent = isset($_POST['parent_id']) ? (int)$_POST['parent_id'] : null;
            $sort = isset($_POST['sort_order']) ? (int)$_POST['sort_order'] : 100;
            $enabled = isset($_POST['enabled']) ? (int)$_POST['enabled'] : 1;

            if (!$label || !$route) {
                throw new Exception('label y route son obligatorios');
            }

            if ($id) {
                $upd = $GLOBALS['pdo']->prepare("UPDATE admin_menu SET label=?, route=?, icon=?, color_class=?, parent_id=?, sort_order=?, enabled=? WHERE id=?");
                $upd->execute([$label, $route, $icon, $color, $parent, $sort, $enabled, $id]);
                echo json_encode(['success' => true, 'message' => 'Guardado', 'id' => $id]);
            } else {
                $ins = $GLOBALS['pdo']->prepare("INSERT INTO admin_menu (label, route, icon, color_class, parent_id, sort_order, enabled) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $ins->execute([$label, $route, $icon, $color, $parent, $sort, $enabled]);
                echo json_encode(['success' => true, 'message' => 'Creado', 'id' => (int)$GLOBALS['pdo']->lastInsertId()]);
            }
            break;

        case 'delete':
            $id = isset($_POST['id']) ? (int)$_POST['id'] : null;
            if (!$id) throw new Exception('id requerido');
            
            $del = $GLOBALS['pdo']->prepare("DELETE FROM admin_menu WHERE id=?");
            $del->execute([$id]);
            echo json_encode(['success' => true, 'message' => 'Eliminado']);
            break;

        case 'setRoles':
            // Set roles for a menu item
            $menuId = isset($_POST['menu_id']) ? (int)$_POST['menu_id'] : null;
            $roles = isset($_POST['roles']) ? $_POST['roles'] : [];
            
            if (!$menuId) throw new Exception('menu_id requerido');
            if (!is_array($roles)) $roles = [];

            $del = $GLOBALS['pdo']->prepare("DELETE FROM admin_menu_roles WHERE menu_id=?");
            $del->execute([$menuId]);

            $ins = $GLOBALS['pdo']->prepare("INSERT INTO admin_menu_roles (menu_id, role_id) VALUES (?, ?)");
            foreach ($roles as $r) {
                $ins->execute([$menuId, (int)$r]);
            }

            echo json_encode(['success' => true, 'message' => 'Roles actualizados']);
            break;

        case 'getRolesForMenu':
            $menuId = isset($_GET['menu_id']) ? (int)$_GET['menu_id'] : null;
            if (!$menuId) throw new Exception('menu_id requerido');

            $stmt = $GLOBALS['pdo']->prepare("SELECT role_id FROM admin_menu_roles WHERE menu_id=? ORDER BY role_id");
            $stmt->execute([$menuId]);
            $roleIds = $stmt->fetchAll(PDO::FETCH_COLUMN);
            echo json_encode(['success' => true, 'data' => $roleIds]);
            break;

        default:
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Acción no soportada: ' . $action]);
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
