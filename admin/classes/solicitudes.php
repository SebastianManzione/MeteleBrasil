<?php
function getSolicitudes(){

    require("conexion.php");
  
    $consulta = "select * from solicitudes";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute();
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla
    return $resultado;
    
    
    }

    function getSolicitud($idSolicitud){

    require("conexion.php");
    $data=["idSolicitud"=>$idSolicitud];
    $consulta = "select * from solicitudes WHERE idSolicitud=:idSolicitud";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla
    return $resultado;
    
    
    }
   function setSolicitud($idTipoSolicitud, $email, $password, $nombre_agencia,$descripcion_agencia, $tipo_de_agencia, $nombre, $cargo, $whatsapp, $estado, $ciudad, $destino_que_opera){


        require("conexion.php");
        $data=["idTipoSolicitud"=> $idTipoSolicitud, "email"=>$email, "password"=>$password, "nombre_agencia"=>$nombre_agencia, "descripcion_agencia"=>$descripcion_agencia, "tipo_de_agencia"=> $tipo_de_agencia, "nombre"=> $nombre,"cargo"=>$cargo, "whatsapp"=>$whatsapp,"estado"=>$estado, "ciudad"=>$ciudad, "destino_que_opera"=>$destino_que_opera];
        $consulta = "INSERT INTO solicitudes (idTipoSolicitud, email, password, nombre_agencia,descripcion_agencia, tipo_de_agencia, nombre,cargo, whatsapp, estado, ciudad, destino_que_opera) VALUES (:idTipoSolicitud, :email, :password,:nombre_agencia,:descripcion_agencia,:tipo_de_agencia,:nombre,:cargo, :whatsapp, :estado, :ciudad, :destino_que_opera) ";
        
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