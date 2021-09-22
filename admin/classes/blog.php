<?php

function getArticulosBlog(){
    require("conexion.php");
    $consulta = "select * from blog ";
    $comando = $pdo->prepare($consulta);
    $comando->execute();
    $cuenta_col = $comando->columnCount();
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    return $resultado;

    }



    function getArticuloBlog($idPost){



    require("conexion.php");

    $data=["idPost"=>$idPost];

    $consulta = "select * from blog WHERE idPost=:idPost ";

    

    $comando = $pdo->prepare($consulta);

    

    $comando->execute($data);

    $cuenta_col = $comando->columnCount();

    $cuenta_row = $comando->rowCount();

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

        return $id;

        

        

        }

function updatePostBlog($idPost, $titulo, $idDestino, $descripcionCorta, $contenido, $tipsYConsejos, $observaciones, $idTextoMiniaturasBlog){





        require("conexion.php");

             $data=["idPost"=>$idPost, "titulo"=> $titulo, "idDestino"=>$idDestino, "descripcionCorta"=>$descripcionCorta, "contenido"=>$contenido, "tipsYConsejos"=>$tipsYConsejos, "observaciones"=>$observaciones, "idTextoMiniaturasBlog"=>$idTextoMiniaturasBlog];

        $consulta = "UPDATE categoria_servicio SET habilitado=1 WHERE idCategoria_servicio=:idCategoria_servicio ";
     $consulta = "UPDATE blog SET titulo=:titulo, idDestino=:idDestino, descripcionCorta=:descripcionCorta, contenido=:contenido, tipsYConsejos=:tipsYConsejos, observaciones=:observaciones, idTextoMiniaturasBlog=:idTextoMiniaturasBlog WHERE idPost=:idPost ";
        

        $comando = $pdo->prepare($consulta);

        

        $comando->execute($data);

        

        $id = $pdo->lastInsertId(); 

        $cuenta_col = $comando->columnCount();

        $cuenta_row = $comando->rowCount();

        $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);



        return $cuenta_row;

        

        

        }




        function borraPost($idPost){



require("conexion.php");

    $data=["idPost"=> $idPost];

    $consulta = "DELETE FROM blog WHERE idPost=:idPost ";
    $comando = $pdo->prepare($consulta);
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    $cuenta_row = $comando->rowCount();
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

      $data=["idPost"=> $idPost];
    $consulta = "select * from blog_img WHERE idPost=:idPost";



    $comando = $pdo->prepare($consulta);

    $comando->execute($data);
   $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

for ($i=0; $i < count($resultado); $i++) { 


    $old = getcwd();

       unlink($old."/classes/imgBlog/".$resultado[$i]["ruta"]);
}
$consulta = "DELETE FROM blog_img WHERE idPost=:idPost ";

    

    $comando = $pdo->prepare($consulta);

    

    $comando->execute($data);

    return $cuenta_row;
    

    

    }

    /*

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



        

*/

    

?>