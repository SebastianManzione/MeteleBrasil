<?php
require_once __DIR__ . '/config/db.php';

$limit = 100;
$sql = "SELECT idContacto, nombre, email, telefono, LEFT(mensaje, 120) AS mensaje_preview, CHAR_LENGTH(nombre) AS len_nombre, CHAR_LENGTH(email) AS len_email, CHAR_LENGTH(mensaje) AS len_mensaje, fecha_alta FROM contacto ORDER BY fecha_alta DESC LIMIT $limit";
$res = $mysqli->query($sql);
if (!$res) { die("Error en query: " . $mysqli->error . "\n"); }

echo "=== ÚLTIMOS $limit CONTACTOS ===\n";
while ($row = $res->fetch_assoc()) {
    echo "ID: {$row['idContacto']} | Fecha: {$row['fecha_alta']}\n";
    echo "  Nombre(len={$row['len_nombre']}): " . str_replace(["\n","\r"], ' ', $row['nombre']) . "\n";
    echo "  Email(len={$row['len_email']}): {$row['email']} | Tel: {$row['telefono']}\n";
    echo "  Mensaje(len={$row['len_mensaje']}): " . str_replace(["\n","\r"], ' ', $row['mensaje_preview']) . "...\n";
    echo "---\n";
}
?>
