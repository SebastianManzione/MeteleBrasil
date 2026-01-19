<?php
require_once "classes/transporte.php";

$modelos = getAllModelos();
echo "ID | Nombre | Filas x Columnas | Capacidad\n";
echo str_repeat("-", 80) . "\n";
foreach($modelos as $m) {
    echo $m['idModelo'] . " | " . $m['nombre'] . " | " . $m['filas'] . "x" . $m['columnas'] . " | " . $m['capacidad_total'] . "\n";
}
?>
