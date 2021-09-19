<?php
function getContactos(){

    require("conexion.php");
  
    $consulta = "select * from contacto";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute();
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla
    return $resultado;
    
    
    }

    function getContacto($idContacto){

    require("conexion.php");
    $data=["idAccesibilidad"=>$idAccesibilidad];
    $consulta = "select * from contacto WHERE idContacto=:idContacto";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla
    return $resultado;
    
    
    }

  
  function setContacto($nombre, $email, $telefono, $mensaje){


        require("conexion.php");
        $data=["nombre"=> $nombre, "email"=>$email, "telefono"=>$telefono, "mensaje"=>$mensaje];
        $consulta = "INSERT INTO contacto (nombre,email,telefono,mensaje) VALUES (:nombre,:email,:telefono,:mensaje) ";
        
        $comando = $pdo->prepare($consulta);
        
        $comando->execute($data);
        
        $id = $pdo->lastInsertId(); 
        $cuenta_col = $comando->columnCount();
        $cuenta_row = $comando->rowCount();
        $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);


        return $id;
        
        
        }


    
?>