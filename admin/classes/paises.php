<?php
function getPaises(){

    require("conexion.php");
  
    $consulta = "select * from paises";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute();
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla
    return $resultado;
    
    
    }

    function getPais($idPais){

    require("conexion.php");
    $data=["idPais"=>$idPais];
    $consulta = "select * from paises WHERE idPais=:idPais";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla
    return $resultado;
    
    
    }
   
    
?>