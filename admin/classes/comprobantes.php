<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function getComprobantes(){



    require("conexion.php");

  

    $consulta = "select * from comprobante";

    

    $comando = $pdo->prepare($consulta);

    

    $comando->execute();

    $cuenta_col = $comando->columnCount();

    

    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

    // Imprimir en pantalla

    return $resultado;

    

    

    }

    

    function muestraComprobantes($idReserva){



    require("conexion.php");

    $data=["idReserva"=>$idReserva];

    $consulta = "select * from comprobante WHERE idReserva=:idReserva ";

    

    $comando = $pdo->prepare($consulta);

    

    $comando->execute($data);

    $cuenta_col = $comando->columnCount();

    

    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

    // Imprimir en pantalla



    return $resultado;

    

    

    }

    

    function getComprobantesIdReserva($idReserva){



    require("conexion.php");

    $data=["idReserva"=>$idReserva];

    $consulta = "select * from comprobante WHERE idReserva=:idReserva ";

    

    $comando = $pdo->prepare($consulta);

    

    $comando->execute($data);

    $cuenta_col = $comando->columnCount();

    

    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

    // Imprimir en pantalla

$total=0;

 for ($i=0; $i < count($resultado); $i++) { 

 $total+=ConvierteMoneda($resultado[$i]["monedaComprobante"],$_SESSION["moneda_sel"], $resultado[$i]["total"]);

 }

    return $total;

    

    

    }




    

    function getComprobantesIdReservaDolar($idReserva){



    require("conexion.php");

    $data=["idReserva"=>$idReserva];

    $consulta = "select * from comprobante WHERE idReserva = :idReserva";

    

    $comando = $pdo->prepare($consulta);

    

    $comando->execute($data);

    $cuenta_col = $comando->columnCount();

    

    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

    // Imprimir en pantalla

 

$total=0;

 for ($i=0; $i < count($resultado); $i++) { 

 $total+= $resultado[$i]["total_dolares"];

 }

    return $total;

    

    

    }




ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);




    function insertaComprobante($idReserva, $total, $origenComprobante, $monedaComprobante, $compOrigen, $total_dolares, $idUsuario){





        require("conexion.php");

        $data=["idReserva"=> $idReserva, "total"=>$total, "origenComprobante"=>$origenComprobante, "monedaComprobante"=>$monedaComprobante, "compOrigen"=>$compOrigen, "total_dolares"=>$total_dolares, "idUsuario"=>$idUsuario];

        $consulta = "INSERT INTO comprobante (idReserva, total, origenComprobante, monedaComprobante, compOrigen, total_dolares, idUsuario) VALUES (:idReserva, :total, :origenComprobante,:monedaComprobante, :compOrigen, :total_dolares, :idUsuario) ";

        

        $comando = $pdo->prepare($consulta);

        

        $comando->execute($data);

        

        $id = $pdo->lastInsertId(); 

        $cuenta_col = $comando->columnCount();

        $cuenta_row = $comando->rowCount();

        $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);



$idComprobante=$id;
if ($id>0) {
    require_once($_SERVER['DOCUMENT_ROOT']."/admin/classes/reservaEmail.php");
include_once($_SERVER['DOCUMENT_ROOT']."/admin/classes/reserva.php");

$reserva=getReservaId($idReserva);
        $comprobantes= getComprobantesIdReservaDolar($idReserva);

$total_dolares=$reserva[0]["total_dolares"];


$horariosReserva=getReservaHorarios($idReserva);

include_once($_SERVER['DOCUMENT_ROOT']."/admin/classes/salidas.php");
include_once($_SERVER['DOCUMENT_ROOT']."/admin/classes/prestador.php");
include_once($_SERVER['DOCUMENT_ROOT']."/admin/classes/email_prestador_reserva_confirmada.php");
include_once($_SERVER['DOCUMENT_ROOT']."/admin/classes/email_reserva_confirmada.php");

if ($comprobantes>=$total_dolares){

   

$cuerpo=getCuerpoEmailReservaConfirmada($reserva[0]["codigoAmigable"]);


$resumail=enviaMail($reserva[0]["emailResponsable"], "Reserva Confirmada ", $cuerpo, "metelebrasil.com");  





confirmaReserva($idReserva);

foreach ($horariosReserva as $key => $value) {
$salida=getSalida($value['idServicioSalidas']);
$prestador=getPrestador($salida[0]['idPrestador']);

$nombrePrestador=($prestador[0]['nombre']);


$cuerpo=getCuerpoEmailPrestadorReservaConfirmada($reserva[0]["codigoAmigable"], "metelebrasil.com", $nombrePrestador);
$resumail=enviaMail($prestador[0]['email'], "voce recebeu uma nova reserva! ", $cuerpo, "metelebrasil.com");  

}

}





        
}  

 return $idComprobante;
        }





?>