<?php
// Script temporal para revisar el contenido de parametros[body]
require_once("admin/classes/parametros.php");
$parametros = getParametros();

echo "<h2>Contenido de parametros[0]['body']:</h2>";
echo "<pre>";
echo htmlspecialchars($parametros[0]["body"] ?? 'VACÍO');
echo "</pre>";

echo "<hr>";
echo "<h2>Contenido RAW:</h2>";
echo "<textarea style='width:100%; height:300px;'>";
echo $parametros[0]["body"] ?? 'VACÍO';
echo "</textarea>";
?>
