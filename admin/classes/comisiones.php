<?php

function getComisiones(){



    require("conexion.php");

  

    $consulta = "select * from servicio_tarifas_comision";

    

    $comando = $pdo->prepare($consulta);

    

    $comando->execute();

    $cuenta_col = $comando->columnCount();

    

    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

    // Imprimir en pantalla

    return $resultado;

    

    

    }



    function getComision($idComision){



    require("conexion.php");

    $data=["idComision"=>$idComision];

    $consulta = "select * from servicio_tarifas_comision WHERE idComision=:idComision";

    

    $comando = $pdo->prepare($consulta);

    

    $comando->execute($data);

    $cuenta_col = $comando->columnCount();

    

    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

    // Imprimir en pantalla

    return $resultado;

    

    

    }

    function getComisionIdServicioSalidasTarifas($idServicioSalidasTarifas, $idComision){



    require("conexion.php");

    $data=["idServicioSalidasTarifas"=>$idServicioSalidasTarifas, "idComision"=> $idComision];

    $consulta = "select * from servicio_tarifas_comision WHERE idServicioSalidasTarifas=:idServicioSalidasTarifas AND idComision=:idComision";

    

    $comando = $pdo->prepare($consulta);

    

    $comando->execute($data);

    $cuenta_col = $comando->columnCount();

    

    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

    // Imprimir en pantalla

    $valor=0;
 $valor=$resultado[0]["valor"];


    return ($valor/100);

    

    

    }





        function getComisionesIdUsuarioCupon($idUsuarioCupon){



    require("conexion.php");

    if ($idUsuarioCupon==(-5)) {

         

    $consulta = "select * from reservas WHERE idUsuarioCupon=0";



    $comando = $pdo->prepare($consulta);

    

    $comando->execute();

    }

    else{

         $data=["idUsuarioCupon"=>$idUsuarioCupon];

    $consulta = "select * from reservas WHERE idUsuarioCupon=:idUsuarioCupon";



    $comando = $pdo->prepare($consulta);

    

    $comando->execute($data);

    }

   



    $cuenta_col = $comando->columnCount();

    

    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

    // Imprimir en pantalla

    return $resultado;

    

    

    }



   /* function setPrestador($nombre, $rSocial, $documento, $telefono, $email, $observaciones, $direccion, $latitud, $longitud){





        require("conexion.php");

        $data=["nombre"=> $nombre, "rSocial"=>$rSocial, "documento"=>$documento, "telefono"=>$telefono, "email"=> $email, "observaciones"=> $observaciones,"direccion"=>$direccion, "latitud"=>$latitud,"longitud"=>$longitud];

        $consulta = "INSERT INTO prestadores (nombre, razonSocial, documento, telefono, email, observaciones,direccion, latitud, longitud) VALUES (:nombre, :rSocial, :documento,:telefono,:email,:observaciones,:direccion, :latitud, :longitud) ";

        

        $comando = $pdo->prepare($consulta);

        

        $comando->execute($data);

        

        $id = $pdo->lastInsertId(); 

        $cuenta_col = $comando->columnCount();

        $cuenta_row = $comando->rowCount();

        $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);



        return $id;

        

        

        }



        

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