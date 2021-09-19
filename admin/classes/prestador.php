<?php
function getPrestadores(){

    require("conexion.php");
    
    $consulta = "select * from prestadores";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute();
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla
    return $resultado;
    
    
    }

        function getPrestador($idPrestador){

    require("conexion.php");
    $data=["idPrestador"=>$idPrestador];
    $consulta = "select * from prestadores WHERE idPrestador=:idPrestador";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla
    return $resultado;
    
    
    }
    function setPrestador($nombre, $rSocial, $documento, $telefono, $email, $observaciones, $direccion, $latitud, $longitud, $idUsuario,$legajo, $celular, $facebook, $instagram, $web){


        require("conexion.php");
        $data=["nombre"=> $nombre, "rSocial"=>$rSocial, "documento"=>$documento, "telefono"=>$telefono, "email"=> $email, "observaciones"=> $observaciones,"direccion"=>$direccion, "latitud"=>$latitud,"longitud"=>$longitud, "idUsuario"=>$idUsuario ,"legajo"=>$legajo, "celular"=>$celular, "facebook"=>$facebook, "instagram"=>$instagram, "web"=>$web];
        $consulta = "INSERT INTO prestadores (nombre, razonSocial, documento, telefono, email, observaciones,direccion, latitud, longitud, idUsuario, legajo, celular, facebook, instagram, web) VALUES (:nombre, :rSocial, :documento,:telefono,:email,:observaciones,:direccion, :latitud, :longitud, :idUsuario, :legajo, :celular,:facebook, :instagram, :web) ";
        
        $comando = $pdo->prepare($consulta);
        
        $comando->execute($data);
        
        $id = $pdo->lastInsertId(); 
        $cuenta_col = $comando->columnCount();
        $cuenta_row = $comando->rowCount();
        $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
  
        return $id;
        
        
        }    


        function updatePrestador($idPrestador, $nombre, $rSocial, $documento, $telefono, $email, $observaciones, $direccion, $latitud, $longitud, $idUsuario,$legajo, $celular, $facebook, $instagram, $web){


        require("conexion.php");
        $data=["idPrestador"=>$idPrestador, "nombre"=> $nombre, "rSocial"=>$rSocial, "documento"=>$documento, "telefono"=>$telefono, "email"=> $email, "observaciones"=> $observaciones,"direccion"=>$direccion, "latitud"=>$latitud,"longitud"=>$longitud, "idUsuario"=>$idUsuario ,"legajo"=>$legajo, "celular"=>$celular, "facebook"=>$facebook, "instagram"=>$instagram, "web"=>$web];
        $consulta = "UPDATE prestadores SET nombre=:nombre, razonSocial=:rSocial, documento=:documento, telefono=:telefono, email=:email, observaciones=:observaciones ,direccion=:direccion, latitud=:latitud, longitud=:longitud, idUsuario=:idUsuario, legajo=:legajo, celular=:celular, facebook=:facebook, instagram=:instagram, web=:web WHERE idPrestador=:idPrestador";
        
        $comando = $pdo->prepare($consulta);
        
        $comando->execute($data);
        
        $id = $pdo->lastInsertId(); 
        $cuenta_col = $comando->columnCount();
        $cuenta_row = $comando->rowCount();
        $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

        return $cuenta_row;
        
        
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
    
    
    }
    
?>
