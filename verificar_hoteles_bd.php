<?php
require_once('admin/classes/conexion.php');

echo "🏨 Verificando hoteles en la base de datos...\n\n";

$stmt = $pdo->query("SELECT idParada, nombre, ciudad, pais, telefono, email FROM parada WHERE tipo='hotel' ORDER BY idParada");
$hoteles = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($hoteles)) {
    echo "❌ NO hay hoteles en la base de datos\n\n";
    echo "¿Quieres crear los 6 hoteles de ejemplo?\n";
    echo "Ejecuta: php crear_hoteles_ejemplo.php\n";
} else {
    echo "✅ Total de hoteles: " . count($hoteles) . "\n\n";
    
    foreach ($hoteles as $h) {
        echo sprintf(
            "[%d] %s\n   📍 %s, %s\n   📞 %s\n   📧 %s\n\n",
            $h['idParada'],
            $h['nombre'],
            $h['ciudad'],
            $h['pais'],
            $h['telefono'] ?: 'Sin teléfono',
            $h['email'] ?: 'Sin email'
        );
    }
}
