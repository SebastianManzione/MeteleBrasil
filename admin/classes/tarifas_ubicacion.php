<?php


    function getTarifasUbicacion($idServicioTarifasUbicacion){

    require("conexion.php");
    $data=["idServicioTarifasUbicacion"=>$idServicioTarifasUbicacion];
    $consulta = "select * from servicio_tarifas_ubicacion WHERE idServicioTarifasUbicacion=:idServicioTarifasUbicacion";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla
    return $resultado;
    
    
    }
        function getUbicacionIdTarifa($idServicioSalidasTarifas){

    require("conexion.php");
    $data=["idServicioSalidasTarifas"=>$idServicioSalidasTarifas];
    $consulta = "SELECT * FROM servicio_tarifas_ubicacion WHERE idServicioSalidasTarifas = :idServicioSalidasTarifas";

    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla
    return $resultado;
    
    
    }
  function altaTarifaUbicacion($idServicioSalidasTarifas, $direccion,$latitud, $longitud){


        require("conexion.php");
        $data=["idServicioSalidasTarifas"=> $idServicioSalidasTarifas, "direccion"=> $direccion, "latitud"=>$latitud, "longitud" => $longitud];

        $consulta = "INSERT INTO servicio_tarifas_ubicacion (idServicioSalidasTarifas, direccion,latitud, longitud) VALUES (:idServicioSalidasTarifas, :direccion, :latitud, :longitud) ";
        
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