<?php

function getDestinos(){



    require("conexion.php");

  

    $consulta = "select * from destinos ORDER BY 'valor'";

    

    $comando = $pdo->prepare($consulta);

    

    $comando->execute();

    $cuenta_col = $comando->columnCount();

    

    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

    // Imprimir en pantalla

    return $resultado;

    

    

    }



    function getDestino($idDestino){



    require("conexion.php");

    $data=["idDestino"=>$idDestino];

    $consulta = "select * from destinos WHERE idDestino=:idDestino";

    

    $comando = $pdo->prepare($consulta);

    

    $comando->execute($data);

    $cuenta_col = $comando->columnCount();

    

    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

    // Imprimir en pantalla

    return $resultado;

    

    

    }



    function getAllDestinosBlog($idDestino){



    require("conexion.php");

    $data=["idDestino"=>$idDestino];

    $consulta = "select * from blog WHERE idDestino=:idDestino";

    

    $comando = $pdo->prepare($consulta);

    

    $comando->execute($data);

    $cuenta_col = $comando->columnCount();

    

    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

    // Imprimir en pantalla

    return $resultado;

    

    

    }



  function setDestino($nombre, $estado, $idPais){





        require("conexion.php");

        $data=["nombre"=> $nombre,"estado"=>$estado, "idPais"=>$idPais];

        $consulta = "INSERT INTO destinos (nombre,estado, idPais) VALUES (:nombre,:estado, :idPais) ";

        

        $comando = $pdo->prepare($consulta);

        

        $comando->execute($data);

        

        $id = $pdo->lastInsertId(); 

        $cuenta_col = $comando->columnCount();

        $cuenta_row = $comando->rowCount();

        $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);




        return $id;

        

        

        }



        

function borraDestino($idDestino){



require("conexion.php");

    $data=["idDestino"=> $idDestino];

    $consulta = "DELETE FROM destinos WHERE idDestino=:idDestino ";

    

    $comando = $pdo->prepare($consulta);

    

    $comando->execute($data);

    $cuenta_col = $comando->columnCount();

    $cuenta_row = $comando->rowCount();

    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

    

    

    return $cuenta_row;

    

    

    }  /**/

    

?>