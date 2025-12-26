<?php
/**
 * Ver rol actual del usuario en sesión
 */

session_start();

echo "=== INFORMACIÓN DE SESIÓN ACTUAL ===\n\n";

if (!empty($_SESSION['login'])) {
    echo "Datos de login:\n";
    foreach ($_SESSION['login'] as $key => $value) {
        echo "  $key: " . ($value ?: "null/vacío") . "\n";
    }
    
    echo "\n=== ANÁLISIS ===\n";
    $rol = $_SESSION['login']['rol'] ?? null;
    $idPrestador = $_SESSION['login']['idPrestador'] ?? null;
    $idVendedor = $_SESSION['login']['idVendedor'] ?? null;
    $idCobrador = $_SESSION['login']['idCobrador'] ?? null;
    
    echo "\nEres Admin?     " . ($rol == 1 ? "✓ SÍ (rol=1)" : "✗ NO") . "\n";
    echo "Eres Prestador? " . (!empty($idPrestador) && $idPrestador > 0 ? "✓ SÍ (idPrestador=$idPrestador)" : "✗ NO") . "\n";
    echo "Eres Vendedor?  " . (!empty($idVendedor) && $idVendedor > 0 ? "✓ SÍ (idVendedor=$idVendedor)" : "✗ NO") . "\n";
    echo "Eres Cobrador?  " . (!empty($idCobrador) && $idCobrador > 0 ? "✓ SÍ (idCobrador=$idCobrador)" : "✗ NO") . "\n";
    
    echo "\n=== RECOMENDACIONES ===\n\n";
    
    if ($rol == 1) {
        echo "⚠️  Estás logueado como ADMIN (rol=1)\n";
        echo "Los admins siempre tienen acceso a todo.\n";
        echo "Para probar permisos de Prestador, necesitas:\n";
        echo "  1. Salir de sesión (logout)\n";
        echo "  2. Crear una cuenta test de Prestador (sin rol=1)\n";
        echo "  3. Loguear como ese Prestador\n";
    } else {
        echo "✓ Estás logueado como: " . ($idPrestador ? "Prestador" : ($idVendedor ? "Vendedor" : "Usuario")) . "\n";
    }
    
} else {
    echo "❌ No hay sesión activa\n";
    echo "Debes estar logueado para probar\n";
}

?>
