<?php
function getComisionesPrestadorServicio($idServicio){

    require("conexion.php");
  $data=["idServicio"=>$idServicio];
    $consulta = "select * from servicio_comision_prestador WHERE idServicio =:idServicio";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla
    return $resultado;
    
    
    }



    function getComisionesPrestadorServicioIdPrestadorIdServicio($idServicio, $idPrestador){

    require("conexion.php");
  $data=["idServicio"=>$idServicio, "idPrestador"=>$idPrestador];
    $consulta = "select * from servicio_comision_prestador WHERE idServicio =:idServicio AND idPrestador=:idPrestador";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla
    return $resultado;
    
    
    }

    function getComisionPrestadorServicio($idServicioComisionPrestador){

    require("conexion.php");
    $data=["idServicioComisionPrestador"=>$idServicioComisionPrestador];
    $consulta = "select * from servicio_comision_prestador WHERE idServicioComisionPrestador=:idServicioComisionPrestador";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla
    return $resultado;
    
    
    }
 function setComisionPrestadorServicio($idServicio, $idPrestador, $comisionVendedor, $comisionSistema){


        require("conexion.php");
        $data=["idServicio"=> $idServicio, "idPrestador"=>$idPrestador, "comisionVendedor"=>$comisionVendedor, "comisionSistema"=>$comisionSistema];
        $consulta = "INSERT INTO servicio_comision_prestador (idServicio, idPrestador, comisionVendedor, comisionSistema) VALUES (:idServicio,:idPrestador,:comisionVendedor,:comisionSistema) ";
        
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