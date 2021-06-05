<?php 
function getParametros(){

require("conexion.php");

$consulta = "select * from parametros WHERE idParametros=1";

$comando = $pdo->prepare($consulta);

$comando->execute();
$cuenta_col = $comando->columnCount();

$resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
// Imprimir en pantalla
return $resultado;


}

function updateParametros($head, $body){



        require("conexion.php");

        $data=[ "head"=>$head, "body"=>$body];

        $consulta = "UPDATE parametros SET head=:head, body=:body WHERE idParametros=1 ";

        

        $comando = $pdo->prepare($consulta);

        

        $comando->execute($data);

        

        $id = $pdo->lastInsertId(); 

        $cuenta_col = $comando->columnCount();

        $cuenta_row = $comando->rowCount();

        $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);


        return $cuenta_row;

        

}

 ?>