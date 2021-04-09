<?php
function getIdiomas(){

    require("conexion.php");
  
    $consulta = "select * from idiomas";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute();
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla
    return $resultado;
    
    
    }

    function getIdioma($idIdioma){

    require("conexion.php");
    $data=["idIdioma"=>$idIdioma];
    $consulta = "select * from idiomas WHERE idIdioma=:idIdioma";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla
    return $resultado;
    
    
    }


    function getIdiomaSalida($idServicioSalidas){

    require("conexion.php");
    $data=["idServicioSalidas"=>$idServicioSalidas];
    $consulta = "select * from servicio_salidas_idioma WHERE idServicioSalidas=:idServicioSalidas";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla
    $idiomas=Array();
    for ($i=0; $i < count($resultado); $i++) { 
        $idiomaTmp=getIdioma($resultado[$i]['idIdioma']);
        array_push($idiomas,  $idiomaTmp[0]['nombre']);
      
        
    }
    return $idiomas;
    
    
    }
   function setIdiomaSalida($idServicioSalidas, $idIdioma){


        require("conexion.php");
        $data=["idServicioSalidas"=> $idServicioSalidas, "idIdioma"=>$idIdioma];
        $consulta = "INSERT INTO servicio_salidas_idioma (idServicioSalidas, idIdioma) VALUES (:idServicioSalidas, :idIdioma) ";
        
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