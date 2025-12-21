<?php
function getTiposSolicitud(){

    require("conexion.php");
  
    $consulta = "select * from tipos_solicitud";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute();
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla
    return $resultado;
    
    
    }

    function getTipoSolicitud($idTipoSolicitud){

    require("conexion.php");
    $data=["idTipoSolicitud"=>$idTipoSolicitud];
    $consulta = "select * from tipos_solicitud WHERE idTipoSolicitud=:idTipoSolicitud";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla
    return $resultado;
    
    
    }
    
?>