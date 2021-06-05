<?php
function getTextosMiniaturas(){

    require("conexion.php");
  
    $consulta = "select * from texto_miniaturas";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute();
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla
    return $resultado;
    
    
    }

    function getTextoMiniatura($idTextoMiniaturas){

    require("conexion.php");
    $data=["idTextoMiniaturas"=>$idTextoMiniaturas];
    $consulta = "select * from texto_miniaturas WHERE idTextoMiniaturas=:idTextoMiniaturas";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla
    return $resultado;
    
    
    }
 function setTextoMiniatura($texto){


        require("conexion.php");
        $data=["texto"=> $texto];
        $consulta = "INSERT INTO texto_miniaturas (texto) VALUES (:texto) ";
        
        $comando = $pdo->prepare($consulta);
        
        $comando->execute($data);
        
        $id = $pdo->lastInsertId(); 
        $cuenta_col = $comando->columnCount();
        $cuenta_row = $comando->rowCount();
        $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

        return $id;
        
        
        }
 
        
function borraTextoMiniatura($idTextoMiniaturas){

require("conexion.php");


$cantidad=count(getTextoMiniaturasServicio($idTextoMiniaturas));
if ($cantidad==0) {
        $data=["idTextoMiniaturas"=> $idTextoMiniaturas];
    $consulta = "DELETE FROM texto_miniaturas WHERE idTextoMiniaturas=:idTextoMiniaturas ";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    $cuenta_row = $comando->rowCount();
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    
    
    return $cuenta_row;
}
else{
    return -5;
}


    
    
    }      


    function getTextoMiniaturasServicio($idTextoMiniaturas){

    require("conexion.php");
    $data=["idTextoMiniaturas"=>$idTextoMiniaturas];
    $consulta = "select * from servicio WHERE idTextoMiniaturas=:idTextoMiniaturas";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla
    return $resultado;
    
    
    }

?>