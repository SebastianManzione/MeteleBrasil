<?php 

function getEmailsGuias(){

    require("conexion.php");
  
    $consulta = "select * from emails_guias";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute();
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla
    return $resultado;
    
    
    }

function insertaEmailGuia($nombre,$email, $idCategoria_servicio, $telefono, $mensaje){
require("conexion.php");

  $data=["nombre"=>$nombre,"email"=> $email, "idCategoria_servicio"=>$idCategoria_servicio, "telefono"=>$telefono, "mensaje"=>$mensaje];
        $consulta = "INSERT INTO emails_guias (nombre, email, idCategoria_servicio, telefono, mensaje) VALUES (:nombre, :email, :idCategoria_servicio, :telefono, :mensaje) ";
        
        $comando = $pdo->prepare($consulta);
        
        $comando->execute($data);
        
        $id = $pdo->lastInsertId(); 
        $cuenta_col = $comando->columnCount();
        $cuenta_row = $comando->rowCount();
        $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
return $id;
}




 ?>