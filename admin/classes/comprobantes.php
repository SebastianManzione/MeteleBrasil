<?php
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



    function insertaComprobante($idReserva, $total, $origenComprobante, $monedaComprobante, $compOrigen, $total_dolares){


        require("conexion.php");
        $data=["idReserva"=> $idReserva, "total"=>$total, "origenComprobante"=>$origenComprobante, "monedaComprobante"=>$monedaComprobante, "compOrigen"=>$compOrigen, "total_dolares"=>$total_dolares];
        $consulta = "INSERT INTO comprobante (idReserva, total, origenComprobante, monedaComprobante, compOrigen, total_dolares) VALUES (:idReserva, :total, :origenComprobante,:monedaComprobante, :compOrigen, :total_dolares) ";
        
        $comando = $pdo->prepare($consulta);
        
        $comando->execute($data);
        
        $id = $pdo->lastInsertId(); 
        $cuenta_col = $comando->columnCount();
        $cuenta_row = $comando->rowCount();
        $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

        return $id;
        
        
        }


?>