<?php
/**
 * Fix final para acentos en admin_menu
 * Usa PDO con UTF-8 configurado correctamente
 */

require_once __DIR__ . '/admin/classes/db.php';

// Forzar charset en PDO
$pdo->exec("SET NAMES utf8mb4");

echo "<h2>Corrigiendo acento en Vehículos</h2>";
echo "<pre>";

// Corregir el registro 49
$sql = "UPDATE admin_menu SET label = ? WHERE id = ?";
$stmt = $pdo->prepare($sql);
$result = $stmt->execute(['Vehículos', 49]);

if ($result) {
    echo "✓ Registro actualizado exitosamente\n\n";
} else {
    echo "✗ Error en la actualización\n\n";
}

// Verificar el resultado
$check = $pdo->query("SELECT id, label FROM admin_menu WHERE id = 49");
$row = $check->fetch(PDO::FETCH_ASSOC);

echo "=== VERIFICACIÓN ===\n";
echo "ID: {$row['id']}\n";
echo "Label: {$row['label']}\n";
echo "</pre>";
echo "<p style='color: green; font-weight: bold;'>✓ Done. Haz Ctrl+Shift+Del para limpiar caché y luego Ctrl+F5</p>";
?>
