<?php
// Redirigir a tiposParadaAlta.php con parámetro id (funcionará en ambos modos)
header("Location: tiposParadaAlta.php?id=" . (isset($_GET['id']) ? intval($_GET['id']) : 0));
exit;
?>
