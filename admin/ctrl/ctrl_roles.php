<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once(__DIR__ . '/../classes/conexion.php');

header('Content-Type: application/json; charset=utf-8');

// Solo Admin puede acceder
if (!isset($_SESSION['login']) || (int)$_SESSION['login']['rol'] !== 1) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Acceso denegado']);
    exit;
}

$action = isset($_GET['action']) ? $_GET['action'] : (isset($_POST['action']) ? $_POST['action'] : null);

try {
    switch ($action) {
        case 'getRoles':
            $stmt = $GLOBALS['pdo']->query("SELECT * FROM roles ORDER BY idRol");
            $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['success' => true, 'data' => $roles]);
            break;

        case 'getRol':
            $id = (int)$_GET['id'];
            $stmt = $GLOBALS['pdo']->prepare("SELECT * FROM roles WHERE idRol = ?");
            $stmt->execute([$id]);
            $rol = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode(['success' => true, 'data' => $rol]);
            break;

        case 'saveRol':
            $idRol = (int)$_POST['idRol'];
            $rol = $_POST['rol'];
            $descripcion = $_POST['descripcion'] ?? '';

            $stmt = $GLOBALS['pdo']->prepare("
                INSERT INTO roles (idRol, rol, descripcion) 
                VALUES (?, ?, ?) 
                ON DUPLICATE KEY UPDATE 
                    rol = VALUES(rol),
                    descripcion = VALUES(descripcion)
            ");
            $stmt->execute([$idRol, $rol, $descripcion]);
            echo json_encode(['success' => true, 'message' => 'Rol guardado']);
            break;

        case 'getPermisosRol':
            $roleId = (int)$_GET['roleId'];
            $stmt = $GLOBALS['pdo']->prepare("
                SELECT menu_id FROM admin_menu_roles WHERE role_id = ?
            ");
            $stmt->execute([$roleId]);
            $permisos = $stmt->fetchAll(PDO::FETCH_COLUMN);
            echo json_encode(['success' => true, 'data' => $permisos]);
            break;

        case 'savePermisosRol':
            $roleId = (int)$_POST['roleId'];
            $menuIds = isset($_POST['menuIds']) ? $_POST['menuIds'] : [];

            // Eliminar permisos anteriores
            $stmt = $GLOBALS['pdo']->prepare("DELETE FROM admin_menu_roles WHERE role_id = ?");
            $stmt->execute([$roleId]);

            // Insertar nuevos permisos
            if (!empty($menuIds)) {
                $stmt = $GLOBALS['pdo']->prepare("
                    INSERT INTO admin_menu_roles (menu_id, role_id) VALUES (?, ?)
                ");
                foreach ($menuIds as $menuId) {
                    $stmt->execute([(int)$menuId, $roleId]);
                }
            }

            echo json_encode(['success' => true, 'message' => 'Permisos actualizados']);
            break;

        default:
            echo json_encode(['success' => false, 'error' => 'Acción no válida']);
            break;
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
