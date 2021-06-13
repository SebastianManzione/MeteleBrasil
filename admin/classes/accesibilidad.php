<?php
function getAccesibilidades(){

    require("conexion.php");
  
    $consulta = "select * from accesibilidad";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute();
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla
    return $resultado;
    
    
    }

    function getAccesibilidad($idAccesibilidad){

    require("conexion.php");
    $data=["idAccesibilidad"=>$idAccesibilidad];
    $consulta = "select * from accesibilidad WHERE idAccesibilidad=:idAccesibilidad";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla
    return $resultado;
    
    
    }

  function getAllAccesiblidadesSalidas($idAccesibilidad){

    require("conexion.php");
    $data=["idAccesibilidad"=>$idAccesibilidad];
    $consulta = "select * from servicios_salidas WHERE idAccesibilidad=:idAccesibilidad";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla
    return $resultado;
    
    
    }

  function setTextoAccesibilidad($texto){


        require("conexion.php");
        $data=["texto"=> $texto];
        $consulta = "INSERT INTO accesibilidad (texto) VALUES (:texto) ";
        
        $comando = $pdo->prepare($consulta);
        
        $comando->execute($data);
        
        $id = $pdo->lastInsertId(); 
        $cuenta_col = $comando->columnCount();
        $cuenta_row = $comando->rowCount();
        $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

        return $id;
        
        
        }

       
function borraTextoAccesibilidad($idAccesibilidad){

require("conexion.php");
    $data=["idAccesibilidad"=> $idAccesibilidad];
    $consulta = "DELETE FROM accesibilidad WHERE idAccesibilidad=:idAccesibilidad ";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    $cuenta_row = $comando->rowCount();
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    
    
    return $cuenta_row;
    
    
    }  /* */
    
?>