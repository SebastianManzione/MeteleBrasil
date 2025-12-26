<?php
// Simular sesión del usuario
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$_SESSION['login'] = ['rol' => 1, 'usuario' => 'Admin'];

// Variables requeridas
$archivo_actual = 'index.php';

// Incluir sidebar
ob_start();
include('admin/includes/sidebar_db.php');
$html = ob_get_clean();

// Validar HTML
$errors = [];
preg_match_all('/<li[^>]*>/', $html, $opens);
preg_match_all('/<\/li>/', $html, $closes);
echo "Aberturas <li>: " . count($opens[0]) . "\n";
echo "Cierres </li>: " . count($closes[0]) . "\n";

if (count($opens[0]) !== count($closes[0])) {
    echo "ERROR: Número de <li> y </li> no coinciden\n";
}

preg_match_all('/<ul[^>]*>/', $html, $uopens);
preg_match_all('/<\/ul>/', $html, $ucloses);
echo "Aberturas <ul>: " . count($uopens[0]) . "\n";
echo "Cierres </ul>: " . count($ucloses[0]) . "\n";

if (count($uopens[0]) !== count($ucloses[0])) {
    echo "ERROR: Número de <ul> y </ul> no coinciden\n";
}

// Mostrar últimas 500 chars
echo "\n=== Últimas 500 caracteres del HTML ===\n";
echo htmlspecialchars(substr($html, -500));
?>
