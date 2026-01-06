<?php
function getVisitas(){

    require("conexion.php");
  
    $consulta = "select * from visitantes";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute();
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla
    return $resultado;
    
    
    }

    function getVisita($idVisita){

    require("conexion.php");
    $data=["idVisita"=>$idVisita];
    $consulta = "select * from visitantes WHERE idVisitante=:idVisita";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla
    return $resultado;
    
    
    }
   /* function setPrestador($nombre, $rSocial, $documento, $telefono, $email, $observaciones, $direccion, $latitud, $longitud){


        require("conexion.php");
        $data=["nombre"=> $nombre, "rSocial"=>$rSocial, "documento"=>$documento, "telefono"=>$telefono, "email"=> $email, "observaciones"=> $observaciones,"direccion"=>$direccion, "latitud"=>$latitud,"longitud"=>$longitud];
        $consulta = "INSERT INTO prestadores (nombre, razonSocial, documento, telefono, email, observaciones,direccion, latitud, longitud) VALUES (:nombre, :rSocial, :documento,:telefono,:email,:observaciones,:direccion, :latitud, :longitud) ";
        
        $comando = $pdo->prepare($consulta);
        
        $comando->execute($data);
        
        $id = $pdo->lastInsertId(); 
        $cuenta_col = $comando->columnCount();
        $cuenta_row = $comando->rowCount();
        $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

        return $id;
        
        
        }

        
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