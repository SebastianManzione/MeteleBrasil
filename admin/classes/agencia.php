<?php

function getAgencias(){



    require("conexion.php");

    

    $consulta = "select * from agencias";

    

    $comando = $pdo->prepare($consulta);

    

    $comando->execute();

    $cuenta_col = $comando->columnCount();

    

    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

    // Imprimir en pantalla

    return $resultado;

    

    

    }






        function getAgencia($idAgencia){



    require("conexion.php");

    $data=["idAgencia"=>$idAgencia];

    $consulta = "select * from agencias WHERE idAgencia=:idAgencia";

    

    $comando = $pdo->prepare($consulta);

    

    $comando->execute($data);

    $cuenta_col = $comando->columnCount();

    

    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

    // Imprimir en pantalla

    return $resultado;

    

    

    }

    function setAgencia($nombre, $rSocial, $documento, $telefono, $email, $observaciones, $direccion, $latitud, $longitud, $idUsuario,$legajo, $celular, $facebook, $instagram, $web){





        require("conexion.php");

        $data=["nombre"=> $nombre, "rSocial"=>$rSocial, "documento"=>$documento, "telefono"=>$telefono, "email"=> $email, "observaciones"=> $observaciones,"direccion"=>$direccion, "latitud"=>$latitud,"longitud"=>$longitud, "idUsuario"=>$idUsuario ,"legajo"=>$legajo, "celular"=>$celular, "facebook"=>$facebook, "instagram"=>$instagram, "web"=>$web];

        $consulta = "INSERT INTO agencias (nombre, razonSocial, documento, telefono, email, observaciones,direccion, latitud, longitud, idUsuario, legajo, celular, facebook, instagram, web) VALUES (:nombre, :rSocial, :documento,:telefono,:email,:observaciones,:direccion, :latitud, :longitud, :idUsuario, :legajo, :celular,:facebook, :instagram, :web) ";

        

        $comando = $pdo->prepare($consulta);

        

        $comando->execute($data);

        

        $id = $pdo->lastInsertId(); 

        $cuenta_col = $comando->columnCount();

        $cuenta_row = $comando->rowCount();

        $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);



        return $id;

        

        

        }    





        function updateAgencia($idAgencia, $nombre, $rSocial, $documento, $telefono, $email, $observaciones, $direccion, $latitud, $longitud, $idUsuario,$legajo, $celular, $facebook, $instagram, $web){





        require("conexion.php");

        $data=["idAgencia"=>$idAgencia, "nombre"=> $nombre, "rSocial"=>$rSocial, "documento"=>$documento, "telefono"=>$telefono, "email"=> $email, "observaciones"=> $observaciones,"direccion"=>$direccion, "latitud"=>$latitud,"longitud"=>$longitud, "idUsuario"=>$idUsuario ,"legajo"=>$legajo, "celular"=>$celular, "facebook"=>$facebook, "instagram"=>$instagram, "web"=>$web];

        $consulta = "UPDATE agencias SET nombre=:nombre, razonSocial=:rSocial, documento=:documento, telefono=:telefono, email=:email, observaciones=:observaciones ,direccion=:direccion, latitud=:latitud, longitud=:longitud, idUsuario=:idUsuario, legajo=:legajo, celular=:celular, facebook=:facebook, instagram=:instagram, web=:web WHERE idAgencia=:idAgencia";

        

        $comando = $pdo->prepare($consulta);

        

        $comando->execute($data);

        

        $id = $pdo->lastInsertId(); 

        $cuenta_col = $comando->columnCount();

        $cuenta_row = $comando->rowCount();

        $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);



        return $cuenta_row;

        

        

        }



        

function borraAgencia($idAgencia){



require("conexion.php");

    $data=["idAgencia"=> $idAgencia];

    $consulta = "DELETE FROM agencias WHERE idAgencia=:idAgencia ";

    

    $comando = $pdo->prepare($consulta);

    

    $comando->execute($data);

    $cuenta_col = $comando->columnCount();

    $cuenta_row = $comando->rowCount();

    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

    

    

    return $cuenta_row;

    

    

    }

    

?>

