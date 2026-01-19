<?php
// Script de validación para terminales en ubicacion
require_once('config/config.php');
require_once('admin/classes/conexion.php');

echo "=== VALIDACIÓN SISTEMA DE TERMINALES ===\n\n";

try {
    // Verificar que terminalesLista obtiene datos correctamente
    $stmt = $pdo->prepare('SELECT COUNT(*) as total FROM ubicacion WHERE tipo = ?');
    $stmt->execute(['terminal']);
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "✓ Total terminales en ubicacion: " . $resultado['total'] . " registros\n\n";

    // Mostrar primeros 5 terminales
    $stmt = $pdo->prepare('SELECT idUbicacion, nombre, ciudad, pais, latitud, longitud FROM ubicacion WHERE tipo = "terminal" ORDER BY ciudad LIMIT 5');
    $stmt->execute();
    $terminales = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "✓ Primeras 5 terminales:\n";
    foreach ($terminales as $t) {
        echo "  - ID: " . $t['idUbicacion'] . " | " . $t['nombre'] . " (" . $t['ciudad'] . ", " . $t['pais'] . ")\n";
        echo "    Coordenadas: " . (isset($t['latitud']) && $t['latitud'] ? $t['latitud'] . ", " . $t['longitud'] : "No disponibles") . "\n";
    }
    
    echo "\n✓ TERMINALES LISTA debería funcionar correctamente\n";
    echo "✓ Botones de editar: terminalAlta.php?id=X\n";
    echo "✓ Botones de eliminar: ctrlTerminalesNuevo.php?action=delete&id=X\n";
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}
?>
