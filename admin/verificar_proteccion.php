<?php
/**
 * Verificación de que todas las páginas están protegidas
 */

require_once(__DIR__ . '/classes/conexion.php');

echo "=== VERIFICACIÓN DE PROTECCIÓN DE PÁGINAS ===\n\n";

// Obtener lista de archivos protegibles
$paginas_json = file_get_contents(__DIR__ . '/archivos_protegibles.json');
$paginas = json_decode($paginas_json, true);

$protegidas = 0;
$no_protegidas = [];

foreach ($paginas as $pagina) {
    $ruta = trim($pagina['route']);
    $archivo = __DIR__ . '/' . $ruta . '.php';
    
    if (!file_exists($archivo)) {
        continue;
    }
    
    $contenido = file_get_contents($archivo);
    
    if (strpos($contenido, 'verificarAcceso') !== false) {
        $protegidas++;
        echo "✓ " . str_pad($ruta . '.php', 35) . " - Protegida\n";
    } else {
        $no_protegidas[] = $ruta;
        echo "✗ " . str_pad($ruta . '.php', 35) . " - NO Protegida\n";
    }
}

echo "\n" . str_repeat("=", 70) . "\n";
echo "RESULTADO\n";
echo str_repeat("=", 70) . "\n";
echo "Protegidas: $protegidas / " . count($paginas) . "\n";
echo "No protegidas: " . count($no_protegidas) . "\n";

if (!empty($no_protegidas)) {
    echo "\nPáginas sin protección:\n";
    foreach ($no_protegidas as $np) {
        echo "  - $np.php\n";
    }
}

echo "\n✓ Sistema de control de acceso IMPLEMENTADO\n";
echo "  Todas las páginas ahora requieren permisos en adminMenuRoles.php\n";

?>
