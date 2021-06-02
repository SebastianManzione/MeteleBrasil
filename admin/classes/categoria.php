<?php
function getAllCategorias(){

    require("conexion.php");
  
    $consulta = "select * from categoria_servicio WHERE idCategoria_servicio>0";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute();
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla
    return $resultado;
    
    
    }

function getCategorias(){

    require("conexion.php");
  
    $consulta = "select * from categoria_servicio WHERE idCategoria_servicio>0 AND habilitado=1";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute();
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla
    return $resultado;
    
    
    }

function getCategoriasLimit6(){

    require("conexion.php");
  
    $consulta = "select * from categoria_servicio WHERE idCategoria_servicio>0 AND habilitado=1 LIMIT 6";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute();
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla
    return $resultado;
    
    
    }
function getCategoriasLimit612(){

    require("conexion.php");
  
    $consulta = "select * from categoria_servicio WHERE idCategoria_servicio>0 AND habilitado=1 LIMIT 6,12";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute();
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla
    return $resultado;
    
    
    }
    function getCategoria($idCategoria_servicio){

    require("conexion.php");
    $data=["idCategoria_servicio"=>$idCategoria_servicio];
    $consulta = "select * from categoria_servicio WHERE idCategoria_servicio=:idCategoria_servicio ";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla
    return $resultado;
    
    
    }

    function habilitarCategoria($idCategoria_servicio){


        require("conexion.php");
        $data=["idCategoria_servicio"=> $idCategoria_servicio];
        $consulta = "UPDATE categoria_servicio SET habilitado=1 WHERE idCategoria_servicio=:idCategoria_servicio ";
        
        $comando = $pdo->prepare($consulta);
        
        $comando->execute($data);
        
        $id = $pdo->lastInsertId(); 
        $cuenta_col = $comando->columnCount();
        $cuenta_row = $comando->rowCount();
        $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

        return $id;
        
        
        }
            function desHabilitarCategoria($idCategoria_servicio){


        require("conexion.php");
        $data=["idCategoria_servicio"=> $idCategoria_servicio];
        $consulta = "UPDATE categoria_servicio SET habilitado=0 WHERE idCategoria_servicio=:idCategoria_servicio ";
        
        $comando = $pdo->prepare($consulta);
        
        $comando->execute($data);
        
        $id = $pdo->lastInsertId(); 
        $cuenta_col = $comando->columnCount();
        $cuenta_row = $comando->rowCount();
        $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

        return $id;
        
        
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