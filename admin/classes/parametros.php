<?php 
function getParametros(){

require("conexion.php");

$consulta = "select * from parametros";

$comando = $pdo->prepare($consulta);

$comando->execute();
$cuenta_col = $comando->columnCount();

$resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
// Imprimir en pantalla
return $resultado;


}

 ?>