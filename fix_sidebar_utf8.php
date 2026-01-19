<?php
/**
 * Script para corregir encoding UTF-8 en admin_menu
 * Usa PDO con charset utf8mb4 configurado correctamente
 */

require_once __DIR__ . '/admin/classes/db.php';

$fixes = [
    49 => 'Vehículos',
    47 => 'Clases de Servicio',
    48 => 'Modelos'
];

echo "<h2>Corrigiendo acentos en admin_menu</h2>";
echo "<pre>";

foreach ($fixes as $id => $newLabel) {
    $sql = "UPDATE admin_menu SET label = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([$newLabel, $id]);
    
    if ($result) {
        echo "✓ ID {$id}: {$newLabel} - ACTUALIZADO\n";
    } else {
        echo "✗ ID {$id}: Error en actualización\n";
    }
}

// Verificar
echo "\n=== VERIFICACIÓN ===\n";
$check = $pdo->query("SELECT id, label FROM admin_menu WHERE id IN (41,45,47,48,49) ORDER BY id");
$rows = $check->fetchAll(PDO::FETCH_ASSOC);

foreach ($rows as $row) {
    echo "ID {$row['id']}: {$row['label']}\n";
}

echo "</pre>";
echo "<p><a href='admin/index.php'>Volver al admin</a></p>";
?>
