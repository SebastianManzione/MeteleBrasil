<?php
function getTextosTraduccion(){

$linea = 0;
//Abrimos nuestro archivo
$archivo = fopen($_SESSION["parametros"]["pathAdmin"]."classes/espanol.csv", "r");
//Lo recorremos

while ($data = fgetcsv ($archivo, 1000, ";")) {
$num = count ($data);
print "";
echo $data[0].' -> '.$data[1];
}
    
    
    }


          /*
function borraPrestador($idPrestador){

require("conexion.php");
    $data=["idPrestador"=> $idPrestador];
    $consulta = "DELETE FROM prestadores WHERE idPrestador=:idPrestador ";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    $cuenta_row = $comando->rowCount();
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    
    
    return $cuenta_row;
    
    
    }*/
    
?>