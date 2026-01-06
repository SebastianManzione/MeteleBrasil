<?php
require_once('admin/classes/conexion.php');

// Agregar permiso de rol Prestador (role_id=2) para el menú de comisiones
$stmt = $pdo->prepare('SELECT * FROM admin_menu_roles WHERE menu_id = 40 AND role_id = 2');
$stmt->execute();
$existe = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$existe) {
    echo "Agregando permiso de Prestador (role_id=2) para Financiero Admin (menu_id=40)...\n";
    $stmt = $pdo->prepare('INSERT INTO admin_menu_roles (menu_id, role_id) VALUES (40, 2)');
    $stmt->execute();
    echo "✓ Permiso agregado exitosamente\n";
} else {
    echo "✓ El permiso ya existe\n";
}

// Actualizar la ruta para que sea sin parámetros
$stmt = $pdo->prepare('UPDATE admin_menu SET route = ? WHERE id = 40');
$stmt->execute(['comisionesprestador.php']);
echo "✓ Ruta actualizada a: comisionesprestador.php\n";

echo "\nListo. Ahora:\n";
echo "- Los admins verán todas las comisiones con selector\n";
echo "- Los prestadores verán solo sus propias comisiones\n";
?>
