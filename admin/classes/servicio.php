<?php

function getAllServicios(){

    require("conexion.php");
    $consulta = "select * from servicio";
    $comando = $pdo->prepare($consulta);
    $comando->execute();
    $cuenta_col = $comando->columnCount();
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    return $resultado;

    }

function getAllServiciosPrestador($idPrestador){

    require("conexion.php");
     require_once("salidas.php");
    $consulta = "select * from servicio";
    $comando = $pdo->prepare($consulta);
    $comando->execute();
    $cuenta_col = $comando->columnCount();
       $retorno=Array();
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
foreach ($resultado as $key => $value) {
  
   $idServicio=($value['idServicio']);
   $salidas=getComisionesPrestadorServicioIdPrestadorIdServicio($idServicio, $idPrestador);

   if(count($salidas)>0){


array_push($retorno, $value);

   }
}
    return $retorno;


    }



function getServicios(){



    require("conexion.php");
    $consulta = "select * from servicio WHERE habilitado=1";
    $comando = $pdo->prepare($consulta);
    $comando->execute();
    $cuenta_col = $comando->columnCount();
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    return $resultado;

    }



function getServiciosPaginado($desde, $hasta){

    require("conexion.php");
    $consulta = "select * from servicio  WHERE habilitado=1 LIMIT :desde, :hasta";
    $comando = $pdo->prepare($consulta);
    $comando->bindParam(":desde", $desde, PDO::PARAM_INT);
    $comando->bindParam(":hasta", $hasta, PDO::PARAM_INT);
    $comando->execute();
    $cuenta_col = $comando->columnCount();
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    return $resultado;

    }




function getServiciosPaginadoNuevo($desde, $hasta){



    require("conexion.php");



    $consulta = "SELECT DISTINCT * from servicio sv LEFT JOIN servicio_salidas ss ON sv.idServicio = ss.idServicio WHERE sv.habilitado=1 LIMIT :desde, :hasta";

    

    $comando = $pdo->prepare($consulta);
$comando->bindParam(":desde", $desde, PDO::PARAM_INT);
$comando->bindParam(":hasta", $hasta, PDO::PARAM_INT);
    

    $comando->execute();

    $cuenta_col = $comando->columnCount();

    

    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

    // Imprimir en pantalla


    return $resultado;

    

    

    }




function getServiciosBusqueda($busqueda){



    require("conexion.php");

$busqueda="%".$busqueda."%";

    $consulta = "SELECT * FROM servicio WHERE nombre_servicio LIKE :busqueda  AND habilitado=1

    OR descripcion_servicio LIKE :busqueda  AND habilitado=1

    OR descripcion_corta LIKE :busqueda AND habilitado=1";



    $comando = $pdo->prepare($consulta);



    $comando->execute(["busqueda"=>$busqueda]);

    $cuenta_col = $comando->columnCount();

   



    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

    // Imprimir en pantalla

    return $resultado;

    

    

    }


function getServiciosBusquedaPaginada($busqueda, $desde, $hasta){



    require("conexion.php");

$busqueda="%".$busqueda."%";

    $consulta = "SELECT * FROM servicio WHERE nombre_servicio LIKE :busqueda  AND habilitado=1

    OR descripcion_servicio LIKE :busqueda  AND habilitado=1

    OR descripcion_corta LIKE :busqueda AND habilitado=1 LIMIT :desde, :hasta";



    $comando = $pdo->prepare($consulta);
    $comando->bindParam(":desde", $desde, PDO::PARAM_INT);
    $comando->bindParam(":hasta", $hasta, PDO::PARAM_INT);
    $comando->bindParam(":busqueda", $busqueda, PDO::PARAM_STR );


    $comando->execute();

    $cuenta_col = $comando->columnCount();

   



    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

    // Imprimir en pantalla

    return $resultado;

    

    

    }



   function getServiciosLimit6(){

    require("conexion.php");
    $consulta = "select * from servicio WHERE habilitado=1 AND destacado=1 LIMIT 6";
    $comando = $pdo->prepare($consulta);
    $comando->execute();
    $cuenta_col = $comando->columnCount();
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    return $resultado;

    } 


     function getServiciosLimit612(){





    require("conexion.php");
   $consulta = "select * from servicio WHERE habilitado=1 AND destacado=1 LIMIT 6,12";
    $comando = $pdo->prepare($consulta);
    $comando->execute();
    $cuenta_col = $comando->columnCount();
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    return $resultado;

    } 



    
   function getServiciosLimit6Nuevo($idCategoria_servicio,$desde, $hasta, $busqueda){

   require("conexion.php");
/*
  
*/


 
if ($idCategoria_servicio>0 && $desde!=(-5)) {
    echo "string";
   $consulta = "select * from servicio WHERE idCategoria_servicio=:idCategoria_servicio AND habilitado=1 LIMIT :desde, :hasta";
    $comando = $pdo->prepare($consulta);
    $comando->bindParam(":desde", $desde, PDO::PARAM_INT);
    $comando->bindParam(":hasta", $hasta, PDO::PARAM_INT);
    $comando->bindParam(":idCategoria_servicio", $idCategoria_servicio, PDO::PARAM_INT);
}
if ($idCategoria_servicio>0 && $desde==(-5)) {
    echo "string2";
   $consulta = "select * from servicio WHERE idCategoria_servicio=:idCategoria_servicio AND habilitado=1 ";
    $comando = $pdo->prepare($consulta);

    $comando->bindParam(":idCategoria_servicio", $idCategoria_servicio, PDO::PARAM_INT);
}
/*else{
      $consulta = "select distinct * from servicio S WHERE S.habilitado=1 "; //LIMIT 6

    

    $comando = $pdo->prepare($consulta); 
}*/
if ($idCategoria_servicio==(-5)) {
    echo "string4";
    $busqueda="%".$busqueda."%";

    $consulta = "SELECT * FROM servicio WHERE nombre_servicio LIKE :busqueda  AND habilitado=1

    OR descripcion_servicio LIKE :busqueda  AND habilitado=1

    OR descripcion_corta LIKE :busqueda AND habilitado=1 LIMIT :desde, :hasta";



    $comando = $pdo->prepare($consulta);
    $comando->bindParam(":desde", $desde, PDO::PARAM_INT);
    $comando->bindParam(":hasta", $hasta, PDO::PARAM_INT);
    $comando->bindParam(":busqueda", $busqueda, PDO::PARAM_STR );
}


 

    

    $comando->execute();

    $cuenta_col = $comando->columnCount();

    

    $servicios = $comando->fetchAll(PDO::FETCH_ASSOC);
$retorno=array();

    for ($i=0; $i < count($servicios); $i++) { 
        $idServicio=$servicios[$i]["idServicio"];
      $fecha=date("Y-m-d");
  
        $salidas=getSalidasFechaLuegoIdServicio($fecha,$idServicio);
    if (count($salidas )>0) {
      # code...
   
  $idMoneda=$salidas[0]['idMoneda'];
      $idServicioSalidas=$salidas[0]['idServicioSalidas'];
      $tarifas=getTarifas($idServicioSalidas);

   $tarifa=calculaTarifa($tarifas[0]['idServicioSalidasTarifas'],
1);
      $precioSugerido=($tarifa[0]["valorSym"]);
      $valor=$tarifa[0]["valor"];
$valorSym=$tarifa[0]["valorSym"];
 $cancelaciones=getTipoCancelaciones($tarifas[0]['idCancelaciones']);

       }
       else{
        $precioSugerido="AGOTADO!!!";
              $valor="AGOTADO!!!";
$valorSym="AGOTADO!!!";
$cancelaciones='';
$tarifa='';
$tarifas='';
       }

        $OpinionesServicio=GetOpinionesServicio($idServicio);
        $estrellasServicio=GetEstrellasServicio($idServicio);
        $textoMiniatura=getTextoMiniatura($servicios[$i]["idTextoMiniaturas"])[0]["texto"];
      $fotos=getFotosServicio($idServicio);

$nombre_servicio=$servicios[$i]["nombre_servicio"];

$duracion_servicio=getDuracionServicio($idServicio);
$descripcion_corta=$servicios[$i]["descripcion_corta"];

array_push($retorno, ["idServicio"=>$idServicio,"nombre_servicio"=>$nombre_servicio,"descripcion_corta"=>$descripcion_corta ,"valor"=>$valor, "valorSym"=>$valorSym, "salidas"=>$salidas,'opinionesServicio'=>$OpinionesServicio, "estrellasServicio"=>$estrellasServicio, "textoMiniatura"=>$textoMiniatura, "fotos"=>$fotos,"tarifas"=>$tarifas, "tarifa"=>$tarifa, "precioSugerido"=> $precioSugerido, "fecha"=>$fecha, "cancelaciones"=>$cancelaciones, "duracion_servicio"=>$duracion_servicio]);

  

    
}
   
array_multisort(array_column($retorno, 'valor'), SORT_ASC, $retorno);
  return $retorno;
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
    function getServiciosidCategoria_servicioPaginado($idCategoria_servicio, $desde, $hasta){
    require("conexion.php");

    $consulta = "select * from servicio WHERE idCategoria_servicio=:idCategoria_servicio AND habilitado=1 LIMIT :desde, :hasta";
    $comando = $pdo->prepare($consulta);
    $comando->bindParam(":desde", $desde, PDO::PARAM_INT);
    $comando->bindParam(":hasta", $hasta, PDO::PARAM_INT);
    $comando->bindParam(":idCategoria_servicio", $idCategoria_servicio, PDO::PARAM_INT);

    $comando->execute();
    $cuenta_col = $comando->columnCount();
    

    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

    // Imprimir en pantalla

    return $resultado;

    

    

    }

    function getServiciosidDestino($idDestino){



    require("conexion.php");

    $data=["idDestino"=>$idDestino];

    $consulta = "select * from servicio WHERE idDestino=:idDestino AND habilitado=1";

    

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



if ($resultado[0]["idCategoria_servicio"]==4) { //si es un paquete mostramos dias / noches

    $duracionMinima=($duracionMinima)." Dias ";

    $duracionMaxima=($duracionMaxima)." Noches ";



}  //fin si es un paquete mostramos dias / noches

else{

  if ($duracionMinima>24 ) {

   $duracionMinima=($duracionMinima/24)." Dias ";

}
elseif (  $duracionMinima<1) {

   $duracionMinima=round(($duracionMinima*60))." Minutos ";

}
else{

    $duracionMinima=($duracionMinima)." HS ";

}



if ($duracionMaxima>24) {

   $duracionMaxima=($duracionMaxima/24)." Dias ";

}
elseif($duracionMaxima<1){

$duracionMaxima=round(($duracionMaxima*60))." Minutos ";
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



   



  function altaServicio($nombre_servicio, $idCategoria_servicio, $descripcion_servicio, $descripcion_corta, $documentacionViajero, $observaciones, $idTextoMiniaturas, $operador_alta,$idOrigen, $idDestino){





        require("conexion.php");

        $data=["nombre_servicio"=> $nombre_servicio, "idCategoria_servicio"=>$idCategoria_servicio, "descripcion_servicio"=>$descripcion_servicio, "descripcion_corta"=>$descripcion_corta, "documentacionViajero"=> $documentacionViajero, "observaciones"=> $observaciones,"idTextoMiniaturas"=>$idTextoMiniaturas, "operador_alta"=>$operador_alta,"idOrigen"=>$idOrigen, "idDestino"=>$idDestino];

        $consulta = "INSERT INTO servicio (nombre_servicio, idCategoria_servicio, descripcion_servicio, descripcion_corta, documentacionViajero, observaciones,idTextoMiniaturas, operador_alta,idOrigen, idDestino) VALUES (:nombre_servicio, :idCategoria_servicio, :descripcion_servicio,:descripcion_corta,:documentacionViajero,:observaciones,:idTextoMiniaturas, :operador_alta,:idOrigen, :idDestino) ";

        

        $comando = $pdo->prepare($consulta);

        

        $comando->execute($data);

        

        $id = $pdo->lastInsertId(); 

        $cuenta_col = $comando->columnCount();

        $cuenta_row = $comando->rowCount();

        $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);


        return $id;

        

        

        }



  function updateServicio($nombre_servicio, $idCategoria_servicio, $descripcion_servicio, $descripcion_corta, $documentacionViajero, $observaciones, $idTextoMiniaturas, $operador_alta,$idOrigen, $idDestino, $idServicio){





        require("conexion.php");

        $data=["nombre_servicio"=> $nombre_servicio, "idCategoria_servicio"=>$idCategoria_servicio, "descripcion_servicio"=>$descripcion_servicio, "descripcion_corta"=>$descripcion_corta, "documentacionViajero"=> $documentacionViajero, "observaciones"=> $observaciones,"idTextoMiniaturas"=>$idTextoMiniaturas,"idOrigen"=>$idOrigen, "idDestino"=>$idDestino, "idServicio"=>$idServicio];

        $consulta = "UPDATE servicio SET nombre_servicio= :nombre_servicio, idCategoria_servicio=:idCategoria_servicio, descripcion_servicio=:descripcion_servicio, descripcion_corta=:descripcion_corta, documentacionViajero=:documentacionViajero, observaciones=:observaciones,idTextoMiniaturas=:idTextoMiniaturas,idOrigen=:idOrigen, idDestino=:idDestino WHERE idServicio=:idServicio ";

        

        $comando = $pdo->prepare($consulta);

        

        $comando->execute($data);

        

        $id = $pdo->lastInsertId(); 

        $cuenta_col = $comando->columnCount();

        $cuenta_row = $comando->rowCount();

        $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

        return $cuenta_row;

        

        

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

    

function vaciarServicio($idServicio){



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
 
 

    $comando = $pdo->prepare($consulta);

    

    $comando->execute($datos);
}
     $consulta = "DELETE FROM servicio_salidas WHERE idServicio=:idServicio ";

    

    $comando = $pdo->prepare($consulta);

    

    $comando->execute($data);


    $cuenta_col = $comando->columnCount();

    $cuenta_row = $comando->rowCount();

    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

      

    return $cuenta_row;

    

    

    }




       function destacarServicio($idServicio){
        require("conexion.php");
        $data=["idServicio"=> $idServicio];
        $consulta = "UPDATE servicio SET destacado=1 WHERE idServicio=:idServicio ";
        $comando = $pdo->prepare($consulta);
        $comando->execute($data);
        $id = $pdo->lastInsertId(); 
        $cuenta_col = $comando->columnCount();
        $cuenta_row = $comando->rowCount();
        $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
        return $id;
        }
              function quitarDestacarServicio($idServicio){
        require("conexion.php");
        $data=["idServicio"=> $idServicio];
        $consulta = "UPDATE servicio SET destacado=0 WHERE idServicio=:idServicio ";
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