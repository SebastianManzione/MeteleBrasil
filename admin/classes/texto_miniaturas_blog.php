<?php
function getTextosMiniaturasBlog(){

    require("conexion.php");
  
    $consulta = "select * from texto_miniaturas_blog";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute();
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla
    return $resultado;
    
    
    }

    function getTextoMiniaturaBlog($idTextoMiniaturasBlog){

    require("conexion.php");
    $data=["idTextoMiniaturasBlog"=>$idTextoMiniaturasBlog];
    $consulta = "select * from texto_miniaturas_blog WHERE idTextoMiniaturasBlog=:idTextoMiniaturasBlog";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla
    return $resultado;
    
    
    }
 function setTextoMiniaturaBlog($texto){


        require("conexion.php");
        $data=["texto"=> $texto];
        $consulta = "INSERT INTO texto_miniaturas_blog (texto) VALUES (:texto) ";
        
        $comando = $pdo->prepare($consulta);
        
        $comando->execute($data);
        
        $id = $pdo->lastInsertId(); 
        $cuenta_col = $comando->columnCount();
        $cuenta_row = $comando->rowCount();
        $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

        return $id;
        
        
        }
 
   
function borraTextoMiniaturaBlog($idTextoMiniaturasBlog){

require("conexion.php");


$cantidad=count(getTextoMiniaturasBlog($idTextoMiniaturasBlog));
if ($cantidad==0) {
        $data=["idTextoMiniaturasBlog"=> $idTextoMiniaturasBlog];
    $consulta = "DELETE FROM texto_miniaturas_blog WHERE idTextoMiniaturasBlog=:idTextoMiniaturasBlog ";
    
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


    function getTextoMiniaturasBlog($idTextoMiniaturasBlog){

    require("conexion.php");
    $data=["idTextoMiniaturasBlog"=>$idTextoMiniaturasBlog];
    $consulta = "select * from blog WHERE idTextoMiniaturasBlog=:idTextoMiniaturasBlog";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla
    return $resultado;
    
    
    }
   
?>