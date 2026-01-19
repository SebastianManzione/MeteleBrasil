<?php
/**
 * Agregar submenú "Hoteles" al menú TRANSPORTE (ID 41)
 * El menú TRANSPORTE se muestra/oculta con F9/F8
 */

require_once('admin/classes/conexion.php');

try {
    // Verificar si el menú TRANSPORTE existe
    $stmt = $pdo->query("SELECT id, label FROM admin_menu WHERE id = 41");
    $menuTransporte = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$menuTransporte) {
        die("❌ ERROR: El menú TRANSPORTE (ID 41) no existe en la base de datos.\n");
    }
    
    echo "✅ Menú TRANSPORTE encontrado: {$menuTransporte['label']}\n\n";
    
    // Verificar submenús actuales
    echo "📋 Submenús actuales del menú TRANSPORTE:\n";
    $stmt = $pdo->query("SELECT id, label, route, icon, sort_order, enabled FROM admin_menu WHERE parent_id = 41 ORDER BY sort_order, id");
    $submenus = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($submenus)) {
        echo "   (Sin submenús configurados)\n\n";
    } else {
        foreach ($submenus as $sub) {
            $status = $sub['enabled'] ? '✓' : '✗';
            echo "   {$status} [{$sub['id']}] {$sub['label']} → {$sub['route']} (orden: {$sub['sort_order']})\n";
        }
        echo "\n";
    }
    
    // Verificar si ya existe el menú Hoteles
    $stmt = $pdo->prepare("SELECT id FROM admin_menu WHERE parent_id = 41 AND label = 'Hoteles'");
    $stmt->execute();
    $hotelExiste = $stmt->fetch();
    
    if ($hotelExiste) {
        echo "⚠️  El menú 'Hoteles' ya existe (ID: {$hotelExiste['id']})\n";
        echo "   No se realizará ninguna acción.\n";
        exit;
    }
    
    // Insertar el menú Hoteles
    echo "🔧 Agregando submenú 'Hoteles'...\n";
    
    $insertSQL = "INSERT INTO admin_menu (label, route, icon, parent_id, sort_order, enabled, color_class) 
                  VALUES ('Hoteles', 'hotelLista.php', 'fas fa-hotel', 41, 2, 1, NULL)";
    
    $pdo->exec($insertSQL);
    $nuevoId = $pdo->lastInsertId();
    
    echo "✅ Submenú 'Hoteles' agregado exitosamente\n";
    echo "   ID: {$nuevoId}\n";
    echo "   Ruta: hotelLista.php\n";
    echo "   Ícono: fas fa-hotel\n";
    echo "   Orden: 2 (entre Terminales y Rutas)\n\n";
    
    // Mostrar estado final
    echo "📋 Submenús actualizados del menú TRANSPORTE:\n";
    $stmt = $pdo->query("SELECT id, label, route, icon, sort_order, enabled FROM admin_menu WHERE parent_id = 41 ORDER BY sort_order, id");
    $submenusNuevos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($submenusNuevos as $sub) {
        $status = $sub['enabled'] ? '✓' : '✗';
        $nuevo = ($sub['id'] == $nuevoId) ? ' ← NUEVO' : '';
        echo "   {$status} [{$sub['id']}] {$sub['label']} → {$sub['route']} (orden: {$sub['sort_order']}){$nuevo}\n";
    }
    
    echo "\n";
    echo "🎉 ¡Listo!\n\n";
    echo "📍 Cómo acceder:\n";
    echo "   1. Entra al panel admin\n";
    echo "   2. Presiona F9 para mostrar el menú TRANSPORTE\n";
    echo "   3. Haz clic en: TRANSPORTE → Hoteles\n";
    echo "   4. Presiona F8 para ocultar el menú TRANSPORTE\n\n";
    echo "🔗 URL directa: http://localhost/metelebrasil_dev/admin/hotelLista.php\n";
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
