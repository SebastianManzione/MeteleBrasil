<?php

    function getFotosCategoria($idCategoria_servicio){

    require("conexion.php");
    $data=["idCategoria_servicio"=>$idCategoria_servicio];
    $consulta = "select * from servicio_img WHERE idCategoria_servicio=:idCategoria_servicio";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla
    return $resultado;
    
    
    }


    
?>