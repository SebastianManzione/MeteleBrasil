<?php
session_start();

echo "<h2>Estado de Sesión - Descuento</h2>";

echo "<pre>";
echo "descuento_ars_aceptado: " . (isset($_SESSION['descuento_ars_aceptado']) ? $_SESSION['descuento_ars_aceptado'] : 'NO SET') . "\n";
echo "descuento_ars_monto: " . (isset($_SESSION['descuento_ars_monto']) ? $_SESSION['descuento_ars_monto'] : 'NO SET') . "\n";
echo "moneda_sel: " . (isset($_SESSION['moneda_sel']) ? $_SESSION['moneda_sel'] : 'NO SET') . "\n";
echo "moneda_sel_sym: " . (isset($_SESSION['moneda_sel_sym']) ? $_SESSION['moneda_sel_sym'] : 'NO SET') . "\n";
echo "</pre>";

echo "<h3>Limpiar variables de descuento</h3>";
echo '<a href="?limpiar=1" class="btn btn-danger">Limpiar descuento_ars_aceptado</a>';

if (isset($_GET['limpiar'])) {
    unset($_SESSION['descuento_ars_aceptado']);
    unset($_SESSION['descuento_ars_monto']);
    echo "<p style='color:green;'>✓ Variables limpiadas. <a href='datosPersonales.php'>Ir a datosPersonales.php</a></p>";
}
?>
<style>
.btn { padding: 10px 20px; background: #dc3545; color: white; text-decoration: none; border-radius: 5px; display: inline-block; margin: 10px 0; }
.btn:hover { background: #c82333; }
</style>
