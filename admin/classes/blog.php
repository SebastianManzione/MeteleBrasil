<?php

function getArticulosBlog(){



    require("conexion.php");

  

    $consulta = "select * from blog ";

    

    $comando = $pdo->prepare($consulta);

    

    $comando->execute();

    $cuenta_col = $comando->columnCount();

    

    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

    // Imprimir en pantalla

    return $resultado;

    

    

    }



    function getArticuloBlog($idPost){



    require("conexion.php");

    $data=["idPost"=>$idPost];

    $consulta = "select * from blog WHERE idPost=:idPost ";

    

    $comando = $pdo->prepare($consulta);

    

    $comando->execute($data);

    $cuenta_col = $comando->columnCount();

    

    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

    // Imprimir en pantalla

    return $resultado;

    

    

    }



   function getImgArticulo($idPost){



    require("conexion.php");

    $data=["idPost"=>$idPost];

    $consulta = "select * from blog_img WHERE idPost=:idPost ";

    

    $comando = $pdo->prepare($consulta);

    

    $comando->execute($data);

    $cuenta_col = $comando->columnCount();

    

    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

    // Imprimir en pantalla

    return $resultado;

    

    

    }


   function getArticulosBlogIdDestino($idDestino){



    require("conexion.php");

    $data=["idDestino"=>$idDestino];

    $consulta = "select * from blog WHERE idDestino=:idDestino ";

    

    $comando = $pdo->prepare($consulta);

    

    $comando->execute($data);

    $cuenta_col = $comando->columnCount();

    

    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

    // Imprimir en pantalla

    return $resultado;

    

    

    }



        function setArticuloBlog($titulo, $idDestino, $descripcionCorta, $contenido, $tipsYConsejos, $observaciones, $idTextoMiniaturasBlog){





        require("conexion.php");

        $data=["titulo"=> $titulo, "idDestino"=>$idDestino, "descripcionCorta"=>$descripcionCorta, "contenido"=>$contenido, "tipsYConsejos"=>$tipsYConsejos, "observaciones"=>$observaciones, "idTextoMiniaturasBlog"=>$idTextoMiniaturasBlog];

        $consulta = "INSERT INTO blog (titulo, idDestino, descripcionCorta, contenido, tipsYConsejos, observaciones, idTextoMiniaturasBlog) VALUES (:titulo, :idDestino, :descripcionCorta, :contenido, :tipsYConsejos, :observaciones, :idTextoMiniaturasBlog) ";

        

        $comando = $pdo->prepare($consulta);

        

        $comando->execute($data);

        

        $id = $pdo->lastInsertId(); 

        $cuenta_col = $comando->columnCount();

        $cuenta_row = $comando->rowCount();

        $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

   echo "\nPDO::errorInfo():\n";

    print_r($comando->errorInfo());



        return $id;

        

        

        }



    /*function habilitarCategoria($idCategoria_servicio){





        require("conexion.php");

        $data=["idCategoria_servicio"=> $idCategoria_servicio];

        $consulta = "UPDATE categoria_servicio SET habilitado=1 WHERE idCategoria_servicio=:idCategoria_servicio ";

        

        $comando = $pdo->prepare($consulta);

        

        $comando->execute($data);

        

        $id = $pdo->lastInsertId(); 

        $cuenta_col = $comando->columnCount();

        $cuenta_row = $comando->rowCount();

        $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);



        return $id;

        

        

        }

            function desHabilitarCategoria($idCategoria_servicio){





        require("conexion.php");

        $data=["idCategoria_servicio"=> $idCategoria_servicio];

        $consulta = "UPDATE categoria_servicio SET habilitado=0 WHERE idCategoria_servicio=:idCategoria_servicio ";

        

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