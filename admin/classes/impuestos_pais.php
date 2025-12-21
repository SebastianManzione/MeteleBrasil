<?php

    function getImpuestosPais($idPais){

    require("conexion.php");
    $data=["idPais"=>$idPais];
    $consulta = "select * from impuestos_pais WHERE idPais=:idPais";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla
    $valor=0;
 for ($i=0; $i < count($resultado); $i++) { 
     $valor+=$resultado[$i]["valor"];
 }
    return ($valor/100);
    
    
    }
/*
    function getTarifas($idServicioSalidas){

    require("conexion.php");
    $data=["idServicioSalidas"=>$idServicioSalidas];
    $consulta = "select * from servicio_salidas_tarifas WHERE idServicioSalidas=:idServicioSalidas";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla
    return $resultado;
    
    
    }
  
  function altaTarifa($idServicioSalidas,$nombre, $idFromEdad,$idToEdad,$idTipoTarifa,$valor,$minimo, $idCancelaciones){


        require("conexion.php");
        $data=["idServicioSalidas"=> $idServicioSalidas, "nombre"=>$nombre, "idFromEdad" => $idFromEdad,"idToEdad"=> $idToEdad, "idTipoTarifa"=>$idTipoTarifa, "valor"=>$valor,"minimo"=>$minimo, "idCancelaciones"=>$idCancelaciones];

        $consulta = "INSERT INTO servicio_salidas_tarifas (idServicioSalidas,nombre, idFromEdad, idToEdad, idTipoTarifa, valor, minimo, idCancelaciones) VALUES (:idServicioSalidas, :nombre, :idFromEdad, :idToEdad, :idTipoTarifa,:valor,:minimo,:idCancelaciones) ";
        
        $comando = $pdo->prepare($consulta);
        
        $comando->execute($data);
        
        $id = $pdo->lastInsertId(); 
        $cuenta_col = $comando->columnCount();
        $cuenta_row = $comando->rowCount();
        $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

        return $id;
        
        
        }
function getComisionTarifa($idServicioSalidasTarifas){

    require("conexion.php");
    $data=["idServicioSalidasTarifas"=>$idServicioSalidasTarifas];
    $consulta = "select * from servicio_tarifas_comision WHERE idServicioSalidasTarifas=:idServicioSalidasTarifas";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla
    return $resultado;
    
    
    }*/
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