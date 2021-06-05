<?php

function getAllServicios(){



    require("conexion.php");

  

    $consulta = "select * from servicio";

    

    $comando = $pdo->prepare($consulta);

    

    $comando->execute();

    $cuenta_col = $comando->columnCount();

    

    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

    // Imprimir en pantalla

    return $resultado;

    

    

    }



function getServicios(){



    require("conexion.php");

  

    $consulta = "select * from servicio WHERE habilitado=1";

    

    $comando = $pdo->prepare($consulta);

    

    $comando->execute();

    $cuenta_col = $comando->columnCount();

    

    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

    // Imprimir en pantalla

    return $resultado;

    

    

    }







function getServiciosBusqueda($busqueda){



    require("conexion.php");

$busqueda="%".$busqueda."%";

    $consulta = "SELECT * FROM servicio WHERE nombre_servicio LIKE :busqueda 

    OR descripcion_servicio LIKE :busqueda

    OR descripcion_corta LIKE :busqueda AND habilitado=1";



    $comando = $pdo->prepare($consulta);



    $comando->execute(["busqueda"=>$busqueda]);

    $cuenta_col = $comando->columnCount();

   



    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

    // Imprimir en pantalla

    return $resultado;

    

    

    }



   function getServiciosLimit6(){





    require("conexion.php");



    $consulta = "select * from servicio WHERE habilitado=1 LIMIT 6";

    

    $comando = $pdo->prepare($consulta);

    

    $comando->execute();

    $cuenta_col = $comando->columnCount();

    

    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

    // Imprimir en pantalla

    return $resultado;

    

    

    } 

 function getServiciosLimit612(){





    require("conexion.php");



    $consulta = "select * from servicio WHERE habilitado=1 LIMIT 6,12";

    

    $comando = $pdo->prepare($consulta);

    

    $comando->execute();

    $cuenta_col = $comando->columnCount();

    

    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

    // Imprimir en pantalla

    return $resultado;

    

    

    } 

    function getServicio($idServicio){



    require("conexion.php");

    $data=["idServicio"=>$idServicio];

    $consulta = "select * from servicio WHERE idServicio=:idServicio ";

    

    $comando = $pdo->prepare($consulta);

    

    $comando->execute($data);

    $cuenta_col = $comando->columnCount();

    

    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

    // Imprimir en pantalla

    return $resultado;

    

    

    }

    function getServiciosidCategoria_servicio($idCategoria_servicio){



    require("conexion.php");

    $data=["idCategoria_servicio"=>$idCategoria_servicio];

    $consulta = "select * from servicio WHERE idCategoria_servicio=:idCategoria_servicio AND habilitado=1";

    

    $comando = $pdo->prepare($consulta);

    

    $comando->execute($data);

    $cuenta_col = $comando->columnCount();

    

    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

    // Imprimir en pantalla

    return $resultado;

    

    

    }

    function getDuracionServicio($idServicio){



    require("conexion.php");

    $data=["idServicio"=>$idServicio];

    $consulta = "select * from servicio_salidas SS  INNER JOIN servicio SE ON SS.idServicio=SE.idServicio WHERE SS.idServicio=:idServicio";

    

    $comando = $pdo->prepare($consulta);

    

    $comando->execute($data);

    $cuenta_col = $comando->columnCount();

    

    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

    // Imprimir en pantalla

    if(count($resultado)){

 $duracionMinima=$resultado[0]["duracionMinima"];

    $duracionMaxima=$resultado[0]["duracionMaxima"];



if ($resultado[0]["idCategoria_servicio"]==4) {

    $duracionMinima=($duracionMinima)." Dias ";

    $duracionMaxima=($duracionMaxima)." Noches ";



}

else{

  if ($duracionMinima>24) {

   $duracionMinima=($duracionMinima/24)." Dias ";

}

else{

    $duracionMinima=($duracionMinima)." HS ";

}



if ($duracionMaxima>24) {

   $duracionMaxima=($duracionMaxima/24)." Dias ";

}

else{

    $duracionMaxima=($duracionMaxima)." HS ";

}





  

}



$retorno=Array();

$retorno["duracionMinima"]= $duracionMinima;

$retorno["duracionMaxima"] =$duracionMaxima;

    }

    else{

    $retorno=Array();

$retorno["duracionMinima"]= 'N/D';

$retorno["duracionMaxima"] ='N/D';    

    }

   

    return $retorno;

    

    

    }



   



  function altaServicio($nombre_servicio, $idCategoria_servicio, $descripcion_servicio, $descripcion_corta, $documentacionViajero, $observaciones, $idTextoMiniaturas, $operador_alta){





        require("conexion.php");

        $data=["nombre_servicio"=> $nombre_servicio, "idCategoria_servicio"=>$idCategoria_servicio, "descripcion_servicio"=>$descripcion_servicio, "descripcion_corta"=>$descripcion_corta, "documentacionViajero"=> $documentacionViajero, "observaciones"=> $observaciones,"idTextoMiniaturas"=>$idTextoMiniaturas, "operador_alta"=>$operador_alta];

        $consulta = "INSERT INTO servicio (nombre_servicio, idCategoria_servicio, descripcion_servicio, descripcion_corta, documentacionViajero, observaciones,idTextoMiniaturas, operador_alta) VALUES (:nombre_servicio, :idCategoria_servicio, :descripcion_servicio,:descripcion_corta,:documentacionViajero,:observaciones,:idTextoMiniaturas, :operador_alta) ";

        

        $comando = $pdo->prepare($consulta);

        

        $comando->execute($data);

        

        $id = $pdo->lastInsertId(); 

        $cuenta_col = $comando->columnCount();

        $cuenta_row = $comando->rowCount();

        $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);



        return $id;

        

        

        }





            function habilitarServicio($idServicio){





        require("conexion.php");

        $data=["idServicio"=> $idServicio];

        $consulta = "UPDATE servicio SET habilitado=1 WHERE idServicio=:idServicio ";

        

        $comando = $pdo->prepare($consulta);

        

        $comando->execute($data);

        

        $id = $pdo->lastInsertId(); 

        $cuenta_col = $comando->columnCount();

        $cuenta_row = $comando->rowCount();

        $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);



        return $id;

        

        

        }

            function desHabilitarServicio($idServicio){





        require("conexion.php");

        $data=["idServicio"=> $idServicio];

        $consulta = "UPDATE servicio SET habilitado=0 WHERE idServicio=:idServicio ";

        

        $comando = $pdo->prepare($consulta);

        

        $comando->execute($data);

        

        $id = $pdo->lastInsertId(); 

        $cuenta_col = $comando->columnCount();

        $cuenta_row = $comando->rowCount();

        $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);



        return $id;

        

        

        }

function eliminarServicio($idServicio){



require("conexion.php");

    $data=["idServicio"=> $idServicio];



    $consulta = "select * from servicio_salidas  WHERE idServicio=:idServicio";

    

    $comando = $pdo->prepare($consulta);

    $comando->execute($data);
   $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

   for ($i=0; $i < count($resultado); $i++) { 
  
       $idServicioSalidas=$resultado[$i]["idServicioSalidas"];
       $datos=["idServicioSalidas"=> $idServicioSalidas];
         $consulta = "DELETE FROM servicio_salidas_adicionales WHERE idServicioSalidas=:idServicioSalidas ";
    $comando = $pdo->prepare($consulta);
    $comando->execute($datos);

     $consulta = "DELETE FROM servicio_salidas_idioma WHERE idServicioSalidas=:idServicioSalidas ";
    $comando = $pdo->prepare($consulta);
    $comando->execute($datos);

       $consulta = "DELETE FROM servicio_salidas_tarifas WHERE idServicioSalidas=:idServicioSalidas ";
    $comando = $pdo->prepare($consulta);
    $comando->execute($datos);

      $consulta = "DELETE FROM servicio_salidas_tarifas WHERE idServicioSalidas=:idServicioSalidas ";
    $comando = $pdo->prepare($consulta);
    $comando->execute($datos);
 


   }
  $consulta = "DELETE FROM servicio WHERE idServicio=:idServicio ";

    

    $comando = $pdo->prepare($consulta);

    

    $comando->execute($data);

     $consulta = "DELETE FROM servicio_salidas WHERE idServicio=:idServicio ";

    

    $comando = $pdo->prepare($consulta);

    

    $comando->execute($data);


    $cuenta_col = $comando->columnCount();

    $cuenta_row = $comando->rowCount();

    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

      



    $consulta = "select * from servicio_img WHERE idServicio=:idServicio";

    

    $comando = $pdo->prepare($consulta);

    $comando->execute($data);
   $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

for ($i=0; $i < count($resultado); $i++) { 


    $old = getcwd();

       unlink($old."/classes/imgServicio/".$resultado[$i]["ruta"]);
}
$consulta = "DELETE FROM servicio_img WHERE idServicio=:idServicio ";

    

    $comando = $pdo->prepare($consulta);

    

    $comando->execute($data);
    return $cuenta_row;

    

    

    }

          /*

*/

    

?>