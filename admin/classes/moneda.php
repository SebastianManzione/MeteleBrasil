<?php
// Evita redeclaraciones si el archivo se incluye más de una vez
if (!function_exists('getMonedas')) {
function getMonedas(){

    require_once("conexion.php");
    
    // Usar $GLOBALS['pdo'] como fallback
    $pdo = $pdo ?? $GLOBALS['pdo'] ?? null;
    
    if (!($pdo instanceof PDO)) {
        return [];  // Retornar array vacío si PDO no está disponible
    }
  
    $consulta = "select * from moneda where activado = 1";
    
    try {
        $comando = $pdo->prepare($consulta);
        $comando->execute();
        $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
    } catch (Throwable $e) {
        @file_put_contents(__DIR__ . '/../../logs/moneda.log', date('c') . ' getMonedas error: ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
        return [];
    }
    
}
} // Cierre del if (!function_exists('getMonedas'))

if (!function_exists('getMoneda')) {
function getMoneda($idMoneda){

    require_once("conexion.php");
    
    // Usar $GLOBALS['pdo'] como fallback
    $pdo = $pdo ?? $GLOBALS['pdo'] ?? null;
    
    if (!($pdo instanceof PDO)) {
        return [];  // Retornar array vacío si PDO no está disponible
    }
    
    $data=["idMoneda"=>$idMoneda];
    $consulta = "select * from moneda WHERE idMoneda=:idMoneda";
    
    try {
        $comando = $pdo->prepare($consulta);
        $comando->execute($data);
        $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
    } catch (Throwable $e) {
        @file_put_contents(__DIR__ . '/../../logs/moneda.log', date('c') . ' getMoneda error: ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
        return [];
    }
}
} // Cierre del if (!function_exists('getMoneda'))

/*
// Función comentada (setPrestador)

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