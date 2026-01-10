<?php
// Script para agregar "Gestión Slider" al menú
require_once("classes/conexion.php");

echo "<h2>Agregar menú: Gestión Slider</h2>";

try {
    // Buscar el ID del padre "Administración"
    $stmt = $pdo->query("SELECT id FROM admin_menu WHERE label = 'Administración' LIMIT 1");
    $parent = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$parent) {
        // Crear el padre si no existe
        $pdo->exec("INSERT INTO admin_menu (label, route, icon, parent_id, orden, color_class) 
                    VALUES ('Administración', '#', 'fas fa-cogs', NULL, 90, NULL)");
        $parent_id = $pdo->lastInsertId();
        echo "<p>✓ Menú padre 'Administración' creado (ID: $parent_id)</p>";
    } else {
        $parent_id = $parent['id'];
        echo "<p>ℹ Menú padre 'Administración' ya existe (ID: $parent_id)</p>";
    }
    
    // Verificar si ya existe "Gestión Slider"
    $stmt = $pdo->prepare("SELECT id FROM admin_menu WHERE label = 'Gestión Slider' LIMIT 1");
    $stmt->execute();
    $existing = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($existing) {
        echo "<p>ℹ 'Gestión Slider' ya existe en el menú (ID: {$existing['id']})</p>";
        $slider_menu_id = $existing['id'];
    } else {
        // Insertar "Gestión Slider"
        $stmt = $pdo->prepare("INSERT INTO admin_menu (label, route, icon, parent_id, orden, color_class) 
                               VALUES ('Gestión Slider', 'sliderLista.php', 'fas fa-images', ?, 10, NULL)");
        $stmt->execute([$parent_id]);
        $slider_menu_id = $pdo->lastInsertId();
        echo "<p>✓ 'Gestión Slider' agregado al menú (ID: $slider_menu_id)</p>";
    }
    
    // Asignar permiso al rol admin (role_id = 1)
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM admin_menu_roles WHERE menu_id = ? AND role_id = 1");
    $stmt->execute([$slider_menu_id]);
    $perm = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($perm['total'] == 0) {
        $stmt = $pdo->prepare("INSERT INTO admin_menu_roles (menu_id, role_id) VALUES (?, 1)");
        $stmt->execute([$slider_menu_id]);
        echo "<p>✓ Permiso asignado al rol Admin</p>";
    } else {
        echo "<p>ℹ Permiso ya existe para el rol Admin</p>";
    }
    
    echo "<hr><p><strong>✓ Menú 'Gestión Slider' configurado correctamente</strong></p>";
    echo "<p><a href='index.php'>← Volver al Dashboard</a> | <a href='sliderLista.php'>Ir a Gestión Slider →</a></p>";
    
} catch (PDOException $e) {
    echo "<p style='color:red'>✗ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>
