<?php
session_start();
echo "<h2>Debug de Sesión</h2>";
echo "<pre>";
echo "Sesión completa:\n";
print_r($_SESSION);
echo "\n\n";

if (isset($_SESSION['login'])) {
    echo "Datos de login:\n";
    print_r($_SESSION['login']);
    
    echo "\n\n";
    echo "¿Es admin? " . (isset($_SESSION['login']['rol']) && $_SESSION['login']['rol'] == 1 ? 'SI' : 'NO') . "\n";
    echo "¿Es prestador? " . (isset($_SESSION['login']['idPrestador']) && $_SESSION['login']['idPrestador'] > 0 ? 'SI - ID: ' . $_SESSION['login']['idPrestador'] : 'NO') . "\n";
} else {
    echo "No hay datos de login en la sesión\n";
}
echo "</pre>";
?>
