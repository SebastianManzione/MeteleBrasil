<?php


    function getPackSalidas($idServicioSalidasPack){

    require("conexion.php");
    $data=["idServicioSalidasPack"=>$idServicioSalidasPack];
    $consulta = "select * from servicio_salidas WHERE idServicioSalidasPack=:idServicioSalidasPack";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla
    return $resultado;
    
    
    }
    







    
?>