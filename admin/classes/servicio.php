<?php

function getAllServicios() {
    require("conexion.php"); // Inclui o arquivo de conexão

    // Obtém o idioma da sessão
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $idioma = isset($_SESSION["idioma"]) ? $_SESSION["idioma"] : "ES"; // Padrão: espanhol

    // Consulta para buscar todos os serviços
    $consulta = "SELECT * FROM servicio";
    $comando = $pdo->prepare($consulta);
    $comando->execute();
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

    // Ajusta os dados com base no idioma
    foreach ($resultado as &$row) {
        switch ($idioma) {
            case 'EN': // Inglês
                $row['nombre_servicio'] = $row['nombre_servicio_en'] ?? $row['nombre_servicio'];
                $row['descripcion'] = $row['descripcion_en'] ?? $row['descripcion'];
                break;
            case 'PT': // Português
                $row['nombre_servicio'] = $row['nombre_servicio_pt'] ?? $row['nombre_servicio'];
                $row['descripcion'] = $row['descripcion_pt'] ?? $row['descripcion'];
                break;
            // Caso padrão (ES ou qualquer outro idioma)
            default:
                // Mantém os valores originais em espanhol
                break;
        }
    }

    return $resultado;
}

function getAllServiciosPrestador($idPrestador) {
    require("conexion.php"); // Inclui o arquivo de conexão
    require_once("salidas.php"); // Inclui o arquivo de salidas

    // Obtém o idioma da sessão
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $idioma = isset($_SESSION["idioma"]) ? $_SESSION["idioma"] : "ES"; // Padrão: espanhol

    // Consulta para buscar todos os serviços
    $consulta = "SELECT * FROM servicio";
    $comando = $pdo->prepare($consulta);
    $comando->execute();
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

    // Filtra os serviços do prestador
    $retorno = [];
    foreach ($resultado as $key => $value) {
        $idServicio = $value['idServicio'];
        $salidas = getComisionesPrestadorServicioIdPrestadorIdServicio($idServicio, $idPrestador);

        if (count($salidas) > 0) {
            // Ajusta os dados com base no idioma
            switch ($idioma) {
                case 'EN': // Inglês
                    $value['nombre_servicio'] = $value['nombre_servicio_en'] ?? $value['nombre_servicio'];
                    $value['descripcion'] = $value['descripcion_en'] ?? $value['descripcion'];
                    break;
                case 'PT': // Português
                    $value['nombre_servicio'] = $value['nombre_servicio_pt'] ?? $value['nombre_servicio'];
                    $value['descripcion'] = $value['descripcion_pt'] ?? $value['descripcion'];
                    break;
                // Caso padrão (ES ou qualquer outro idioma)
                default:
                    // Mantém os valores originais em espanhol
                    break;
            }
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
return $resultado; }


function getServiciosPaginado($desde, $hasta){
require("conexion.php");
$consulta = "select * from servicio  WHERE habilitado=1 LIMIT :desde, :hasta";
$comando = $pdo->prepare($consulta);
$comando->bindParam(":desde", $desde, PDO::PARAM_INT);
$comando->bindParam(":hasta", $hasta, PDO::PARAM_INT);
$comando->execute();
$cuenta_col = $comando->columnCount();
$resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
return $resultado;}


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
return $resultado; }


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
return $resultado; }


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
return $resultado; }


function getServiciosLimit6() {
    require("conexion.php"); // Inclui o arquivo de conexão

    // Obtém o idioma da sessão
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $idioma = isset($_SESSION["idioma"]) ? $_SESSION["idioma"] : "ES"; // Padrão: espanhol

    // Consulta para buscar os serviços destacados (limite de 6)
    $consulta = "SELECT * FROM servicio WHERE habilitado = 1 AND destacado = 1 LIMIT 6";
    $comando = $pdo->prepare($consulta);
    $comando->execute();
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

    // Ajusta os dados com base no idioma
    foreach ($resultado as &$row) {
        switch ($idioma) {
            case 'EN': // Inglês
                $row['nombre_servicio'] = $row['nombre_servicio_en'] ?? $row['nombre_servicio'];
                $row['descripcion'] = $row['descripcion_en'] ?? $row['descripcion'];
                $row['descripcion_corta'] = $row['descripcion_corta_en'] ?? $row['descripcion_corta'];
                break;
            case 'PT': // Português
                $row['nombre_servicio'] = $row['nombre_servicio_pt'] ?? $row['nombre_servicio'];
                $row['descripcion'] = $row['descripcion_pt'] ?? $row['descripcion'];
                $row['descripcion_corta'] = $row['descripcion_corta_pt'] ?? $row['descripcion_corta'];
                break;
            case 'IT': // Italiano
                $row['nombre_servicio'] = $row['nombre_servicio_it'] ?? $row['nombre_servicio'];
                $row['descripcion'] = $row['descripcion_it'] ?? $row['descripcion'];
                $row['descripcion_corta'] = $row['descripcion_corta_it'] ?? $row['descripcion_corta'];
                break;
            // Caso padrão (ES ou qualquer outro idioma)
            default:
                // Mantém os valores originais em espanhol
                break;
        }
    }

    return $resultado;
}


function getServiciosLimit612(){require("conexion.php");
$consulta = "select * from servicio WHERE habilitado=1 AND destacado=1 LIMIT 6,12";
$comando = $pdo->prepare($consulta);
$comando->execute();
$cuenta_col = $comando->columnCount();
$resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
return $resultado;}

function getServiciosLimit6Nuevo($idCategoria_servicio,$desde, $hasta, $busqueda){
require("conexion.php");
if ($idCategoria_servicio>0 && $desde!=(-5)) {
$consulta = "select * from servicio WHERE idCategoria_servicio=:idCategoria_servicio AND habilitado=1 LIMIT :desde, :hasta";
$comando = $pdo->prepare($consulta);
$comando->bindParam(":desde", $desde, PDO::PARAM_INT);
$comando->bindParam(":hasta", $hasta, PDO::PARAM_INT);
$comando->bindParam(":idCategoria_servicio", $idCategoria_servicio, PDO::PARAM_INT); }
if ($idCategoria_servicio>0 && $desde==(-5)) {
$consulta = "select * from servicio WHERE idCategoria_servicio=:idCategoria_servicio AND habilitado=1 ";
$comando = $pdo->prepare($consulta);
$comando->bindParam(":idCategoria_servicio", $idCategoria_servicio, PDO::PARAM_INT);}
/*else{ $consulta = "select distinct * from servicio S WHERE S.habilitado=1 "; //LIMIT 6
    $comando = $pdo->prepare($consulta); }*/
if ($idCategoria_servicio==(-5)) {
$busqueda="%".$busqueda."%";
$consulta = "SELECT * FROM servicio WHERE nombre_servicio LIKE :busqueda  AND habilitado=1
OR descripcion_servicio LIKE :busqueda  AND habilitado=1
OR descripcion_corta LIKE :busqueda AND habilitado=1 LIMIT :desde, :hasta";
$comando = $pdo->prepare($consulta);
$comando->bindParam(":desde", $desde, PDO::PARAM_INT);
$comando->bindParam(":hasta", $hasta, PDO::PARAM_INT);
$comando->bindParam(":busqueda", $busqueda, PDO::PARAM_STR ); }
$comando->execute();
$cuenta_col = $comando->columnCount();
$servicios = $comando->fetchAll(PDO::FETCH_ASSOC);
$retorno=array();
for ($i=0; $i < count($servicios); $i++) { $idServicio=$servicios[$i]["idServicio"];
$fecha=date("Y-m-d");
 $salidas=getSalidasFechaLuegoIdServicio($fecha,$idServicio);
if (count($salidas )>0) { $idMoneda=$salidas[0]['idMoneda'];
$idServicioSalidas=$salidas[0]['idServicioSalidas'];
$tarifas=getTarifas($idServicioSalidas);
$tarifa=calculaTarifa($tarifas[0]['idServicioSalidasTarifas'],1);
$precioSugerido=($tarifa[0]["valorSym"]);
$valor=$tarifa[0]["valor"];
$valorSym=$tarifa[0]["valorSym"];
$cancelaciones=getTipoCancelaciones($tarifas[0]['idCancelaciones']);}
else{$precioSugerido="ESGOTADO";$valor="ESGOTADO";
$valorSym="ESGOTADO";
$cancelaciones='';
$tarifa='';
$tarifas=''; }
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


function getServicio($idServicio) {
    require("conexion.php"); // Inclui o arquivo de conexão

    // Obtém o idioma da sessão
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $idioma = isset($_SESSION["idioma"]) ? $_SESSION["idioma"] : "ES"; // Padrão: espanhol

    // Consulta para buscar o serviço pelo ID
    $consulta = "SELECT * FROM servicio WHERE idServicio = :idServicio";
    $comando = $pdo->prepare($consulta);
    $comando->execute([":idServicio" => $idServicio]);
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

    // Ajusta os dados com base no idioma
    foreach ($resultado as &$row) {
        switch ($idioma) {
            case 'EN': // Inglês
                $row['nombre_servicio'] = $row['nombre_servicio_en'] ?? $row['nombre_servicio'];
                $row['descripcion_servicio'] = $row['descripcion_servicio_en'] ?? $row['descripcion_servicio'];
                $row['descripcion_corta'] = $row['descripcion_corta_en'] ?? $row['descripcion_corta'];
                $row['observaciones'] = $row['observaciones_en'] ?? $row['observaciones'];
                $row['documentacionViajero'] = $row['documentacionViajero_en'] ?? $row['documentacionViajero'];
                break;
            case 'PT': // Português
                $row['nombre_servicio'] = $row['nombre_servicio_pt'] ?? $row['nombre_servicio'];
                $row['descripcion_servicio'] = $row['descripcion_servicio_pt'] ?? $row['descripcion_servicio'];
                $row['descripcion_corta'] = $row['descripcion_corta_pt'] ?? $row['descripcion_corta'];
                $row['observaciones'] = $row['observaciones_pt'] ?? $row['observaciones'];
                $row['documentacionViajero'] = $row['documentacionViajero_pt'] ?? $row['documentacionViajero'];
                break;
            case 'IT': // Italiano
                $row['nombre_servicio'] = $row['nombre_servicio_it'] ?? $row['nombre_servicio'];
                $row['descripcion_servicio'] = $row['descripcion_servicio_it'] ?? $row['descripcion_servicio'];
                $row['descripcion_corta'] = $row['descripcion_corta_it'] ?? $row['descripcion_corta'];
                $row['observaciones'] = $row['observaciones_it'] ?? $row['observaciones'];
                $row['documentacionViajero'] = $row['documentacionViajero_it'] ?? $row['documentacionViajero'];
                break;
            // Caso padrão (ES ou qualquer outro idioma)
            default:
                // Mantém os valores originais em espanhol
                break;
        }
    }

    return $resultado;
}

function getServiciosidCategoria_servicio($idCategoria_servicio) {
    require("conexion.php");

    // Obtém o idioma da sessão
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $idioma = isset($_SESSION["idioma"]) ? $_SESSION["idioma"] : "ES"; // Padrão: espanhol

    $data = ["idCategoria_servicio" => $idCategoria_servicio];
    $consulta = "SELECT * FROM servicio WHERE idCategoria_servicio=:idCategoria_servicio AND habilitado=1";
    $comando = $pdo->prepare($consulta);
    $comando->execute($data);
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

    // Ajusta os dados com base no idioma
    foreach ($resultado as &$row) {
        switch ($idioma) {
            case 'EN': // Inglês
                $row['nombre_servicio'] = $row['nombre_servicio_en'] ?? $row['nombre_servicio'];
                $row['descripcion_corta'] = $row['descripcion_corta_en'] ?? $row['descripcion_corta'];
                break;
            case 'PT': // Português
                $row['nombre_servicio'] = $row['nombre_servicio_pt'] ?? $row['nombre_servicio'];
                $row['descripcion_corta'] = $row['descripcion_corta_pt'] ?? $row['descripcion_corta'];
                break;
            case 'IT': // Italiano
                $row['nombre_servicio'] = $row['nombre_servicio_it'] ?? $row['nombre_servicio'];
                $row['descripcion_corta'] = $row['descripcion_corta_it'] ?? $row['descripcion_corta'];
                break;
            // Caso padrão (ES ou qualquer outro idioma)
            default:
                // Mantém os valores originais em espanhol
                break;
        }
    }

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
    return $resultado;}

function getServiciosidDestino($idDestino) {
    require("conexion.php");

    // Obtém o idioma da sessão
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $idioma = isset($_SESSION["idioma"]) ? $_SESSION["idioma"] : "ES"; // Padrão: espanhol

    $data = ["idDestino" => $idDestino];
    $consulta = "SELECT * FROM servicio WHERE idDestino=:idDestino AND habilitado=1";
    $comando = $pdo->prepare($consulta);
    $comando->execute($data);
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

    // Ajusta os dados com base no idioma
    foreach ($resultado as &$row) {
        switch ($idioma) {
            case 'EN': // Inglês
                $row['nombre_servicio'] = $row['nombre_servicio_en'] ?? $row['nombre_servicio'];
                $row['descripcion_corta'] = $row['descripcion_corta_en'] ?? $row['descripcion_corta'];
                break;
            case 'PT': // Português
                $row['nombre_servicio'] = $row['nombre_servicio_pt'] ?? $row['nombre_servicio'];
                $row['descripcion_corta'] = $row['descripcion_corta_pt'] ?? $row['descripcion_corta'];
                break;
            case 'IT': // Italiano
                $row['nombre_servicio'] = $row['nombre_servicio_it'] ?? $row['nombre_servicio'];
                $row['descripcion_corta'] = $row['descripcion_corta_it'] ?? $row['descripcion_corta'];
                break;
            // Caso padrão (ES ou qualquer outro idioma)
            default:
                // Mantém os valores originais em espanhol
                break;
        }
    }

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



function altaServicio(
    $nombre_servicio, $nombre_servicio_en, $nombre_servicio_pt, $nombre_servicio_it,
    $idCategoria_servicio,
    $descripcion_servicio, $descripcion_servicio_en, $descripcion_servicio_pt, $descripcion_servicio_it,
    $descripcion_corta, $descripcion_corta_en, $descripcion_corta_pt, $descripcion_corta_it,
    $documentacionViajero, $documentacionViajero_en, $documentacionViajero_pt, $documentacionViajero_it,
    $observaciones, $observaciones_en, $observaciones_pt, $observaciones_it,
    $idTextoMiniaturas, $operador_alta, $idOrigen, $idDestino
) {
    require("conexion.php");

    $data = [
        "nombre_servicio" => $nombre_servicio,
        "nombre_servicio_en" => $nombre_servicio_en,
        "nombre_servicio_pt" => $nombre_servicio_pt,
        "nombre_servicio_it" => $nombre_servicio_it,
        "idCategoria_servicio" => $idCategoria_servicio,
        "descripcion_servicio" => $descripcion_servicio,
        "descripcion_servicio_en" => $descripcion_servicio_en,
        "descripcion_servicio_pt" => $descripcion_servicio_pt,
        "descripcion_servicio_it" => $descripcion_servicio_it,
        "descripcion_corta" => $descripcion_corta,
        "descripcion_corta_en" => $descripcion_corta_en,
        "descripcion_corta_pt" => $descripcion_corta_pt,
        "descripcion_corta_it" => $descripcion_corta_it,
        "documentacionViajero" => $documentacionViajero,
        "documentacionViajero_en" => $documentacionViajero_en,
        "documentacionViajero_pt" => $documentacionViajero_pt,
        "documentacionViajero_it" => $documentacionViajero_it,
        "observaciones" => $observaciones,
        "observaciones_en" => $observaciones_en,
        "observaciones_pt" => $observaciones_pt,
        "observaciones_it" => $observaciones_it,
        "idTextoMiniaturas" => $idTextoMiniaturas,
        "operador_alta" => $operador_alta,
        "idOrigen" => $idOrigen,
        "idDestino" => $idDestino
    ];

    $consulta = "INSERT INTO servicio (
        nombre_servicio, nombre_servicio_en, nombre_servicio_pt, nombre_servicio_it,
        idCategoria_servicio,
        descripcion_servicio, descripcion_servicio_en, descripcion_servicio_pt, descripcion_servicio_it,
        descripcion_corta, descripcion_corta_en, descripcion_corta_pt, descripcion_corta_it,
        documentacionViajero, documentacionViajero_en, documentacionViajero_pt, documentacionViajero_it,
        observaciones, observaciones_en, observaciones_pt, observaciones_it,
        idTextoMiniaturas, operador_alta, idOrigen, idDestino
    ) VALUES (
        :nombre_servicio, :nombre_servicio_en, :nombre_servicio_pt, :nombre_servicio_it,
        :idCategoria_servicio,
        :descripcion_servicio, :descripcion_servicio_en, :descripcion_servicio_pt, :descripcion_servicio_it,
        :descripcion_corta, :descripcion_corta_en, :descripcion_corta_pt, :descripcion_corta_it,
        :documentacionViajero, :documentacionViajero_en, :documentacionViajero_pt, :documentacionViajero_it,
        :observaciones, :observaciones_en, :observaciones_pt, :observaciones_it,
        :idTextoMiniaturas, :operador_alta, :idOrigen, :idDestino
    )";

    $comando = $pdo->prepare($consulta);
    $comando->execute($data);

    $id = $pdo->lastInsertId();
    return $id;
}
function updateServicio(
    $nombre_servicio, $nombre_servicio_en, $nombre_servicio_pt, $nombre_servicio_it,
    $idCategoria_servicio,
    $descripcion_servicio, $descripcion_servicio_en, $descripcion_servicio_pt, $descripcion_servicio_it,
    $descripcion_corta, $descripcion_corta_en, $descripcion_corta_pt, $descripcion_corta_it,
    $documentacionViajero, $documentacionViajero_en, $documentacionViajero_pt, $documentacionViajero_it,
    $observaciones, $observaciones_en, $observaciones_pt, $observaciones_it,
    $idTextoMiniaturas, $operador_alta, $idOrigen, $idDestino, $idServicio
) {
    require("conexion.php");

    $data = [
        "nombre_servicio" => $nombre_servicio,
        "nombre_servicio_en" => $nombre_servicio_en,
        "nombre_servicio_pt" => $nombre_servicio_pt,
        "nombre_servicio_it" => $nombre_servicio_it,
        "idCategoria_servicio" => $idCategoria_servicio,
        "descripcion_servicio" => $descripcion_servicio,
        "descripcion_servicio_en" => $descripcion_servicio_en,
        "descripcion_servicio_pt" => $descripcion_servicio_pt,
        "descripcion_servicio_it" => $descripcion_servicio_it,
        "descripcion_corta" => $descripcion_corta,
        "descripcion_corta_en" => $descripcion_corta_en,
        "descripcion_corta_pt" => $descripcion_corta_pt,
        "descripcion_corta_it" => $descripcion_corta_it,
        "documentacionViajero" => $documentacionViajero,
        "documentacionViajero_en" => $documentacionViajero_en,
        "documentacionViajero_pt" => $documentacionViajero_pt,
        "documentacionViajero_it" => $documentacionViajero_it,
        "observaciones" => $observaciones,
        "observaciones_en" => $observaciones_en,
        "observaciones_pt" => $observaciones_pt,
        "observaciones_it" => $observaciones_it,
        "idTextoMiniaturas" => $idTextoMiniaturas,
        "operador_alta" => $operador_alta,
        "idOrigen" => $idOrigen,
        "idDestino" => $idDestino,
        "idServicio" => $idServicio
    ];

    $consulta = "UPDATE servicio SET 
        nombre_servicio = :nombre_servicio, 
        nombre_servicio_en = :nombre_servicio_en, 
        nombre_servicio_pt = :nombre_servicio_pt,
        nombre_servicio_it = :nombre_servicio_it,
        idCategoria_servicio = :idCategoria_servicio, 
        descripcion_servicio = :descripcion_servicio, 
        descripcion_servicio_en = :descripcion_servicio_en, 
        descripcion_servicio_pt = :descripcion_servicio_pt,
        descripcion_servicio_it = :descripcion_servicio_it,
        descripcion_corta = :descripcion_corta, 
        descripcion_corta_en = :descripcion_corta_en, 
        descripcion_corta_pt = :descripcion_corta_pt,
        descripcion_corta_it = :descripcion_corta_it,
        documentacionViajero = :documentacionViajero, 
        documentacionViajero_en = :documentacionViajero_en, 
        documentacionViajero_pt = :documentacionViajero_pt,
        documentacionViajero_it = :documentacionViajero_it,
        observaciones = :observaciones, 
        observaciones_en = :observaciones_en, 
        observaciones_pt = :observaciones_pt,
        observaciones_it = :observaciones_it,
        idTextoMiniaturas = :idTextoMiniaturas, 
        operador_alta = :operador_alta, 
        idOrigen = :idOrigen, 
        idDestino = :idDestino 
        WHERE idServicio = :idServicio";

    $comando = $pdo->prepare($consulta);
    $comando->execute($data);

    // Retorna o número de linhas afetadas pela atualização
    return $comando->rowCount();
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

function getServiciosPorDistancia($latUsuario = null, $lonUsuario = null, $limit = 6, $offset = 0) {
    require("conexion.php");

    if (session_status() === PHP_SESSION_NONE) session_start();
    $idioma = isset($_SESSION["idioma"]) ? $_SESSION["idioma"] : "ES";

    $lat = $latUsuario !== null ? (float)$latUsuario : null;
    $lon = $lonUsuario !== null ? (float)$lonUsuario : null;
    $limit  = (int)$limit;
    $offset = (int)$offset;

    $consulta = "
        SELECT 
            s.*,
            GROUP_CONCAT(CONCAT(u.latitud, ',', u.longitud) SEPARATOR '|') AS coords
        FROM servicio s
        LEFT JOIN (
            SELECT ss1.*
            FROM servicio_salidas ss1
            JOIN (
                SELECT idServicio, MIN(fecha) AS proxima_fecha
                FROM servicio_salidas
                WHERE fecha >= CURDATE()
                GROUP BY idServicio
            ) ss2 
              ON ss1.idServicio = ss2.idServicio 
             AND ss1.fecha = ss2.proxima_fecha
        ) ss ON s.idServicio = ss.idServicio
        LEFT JOIN servicio_salidas_tarifas st 
               ON st.idServicioSalidas = ss.idServicioSalidas
        LEFT JOIN servicio_tarifas_ubicacion u 
               ON u.idServicioSalidasTarifas = st.idServicioSalidasTarifas
        WHERE s.destacado = 1 
          AND s.habilitado = 1
        GROUP BY s.idServicio
    ";

    $comando = $pdo->prepare($consulta);
    $comando->execute();
    $filas = $comando->fetchAll(PDO::FETCH_ASSOC);

    $servicios = [];
    foreach ($filas as $row) {
        $minKm = 99999.0;

        if ($lat !== null && $lon !== null && !empty($row['coords'])) {
            $pares = explode('|', $row['coords']);
            foreach ($pares as $par) {
                if (strpos($par, ',') === false) continue;
                list($ulat, $ulon) = explode(',', $par, 2);
                $ulat = (float)$ulat;
                $ulon = (float)$ulon;
                // Haversine mínima
                $d = haversineKm($lat, $lon, $ulat, $ulon);
                if ($d < $minKm) $minKm = $d;
            }
        }

        $row['distancia_km'] = round($minKm, 3);
        unset($row['coords']);
        $servicios[] = $row;
    }

    usort($servicios, function($a, $b) {
        if ($a['distancia_km'] == $b['distancia_km']) {
            // destacado DESC
            return ($b['destacado'] <=> $a['destacado']);
        }
        return ($a['distancia_km'] <=> $b['distancia_km']);
    });

    $resultado = array_slice($servicios, $offset, $limit);

    foreach ($resultado as &$row) {
        switch ($idioma) {
            case 'EN':
                $row['nombre_servicio']   = $row['nombre_servicio_en']   ?? $row['nombre_servicio'];
                $row['descripcion']       = $row['descripcion_en']       ?? $row['descripcion'];
                $row['descripcion_corta'] = $row['descripcion_corta_en'] ?? $row['descripcion_corta'];
                break;
            case 'PT':
                $row['nombre_servicio']   = $row['nombre_servicio_pt']   ?? $row['nombre_servicio'];
                $row['descripcion']       = $row['descripcion_pt']       ?? $row['descripcion'];
                $row['descripcion_corta'] = $row['descripcion_corta_pt'] ?? $row['descripcion_corta'];
                break;
            case 'IT':
                $row['nombre_servicio']   = $row['nombre_servicio_it']   ?? $row['nombre_servicio'];
                $row['descripcion']       = $row['descripcion_it']       ?? $row['descripcion'];
                $row['descripcion_corta'] = $row['descripcion_corta_it'] ?? $row['descripcion_corta'];
                break;
            default:
                break;
        }
    }

    return $resultado;
}

function haversineKm($lat1, $lon1, $lat2, $lon2) {
    $R = 6371.0; // km
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);
    $a = sin($dLat/2) ** 2 +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         (sin($dLon/2) ** 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    return $R * $c;
}
?>
