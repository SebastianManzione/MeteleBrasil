<?php 
function getParametros(){
    require("conexion.php");
    
    if (!isset($pdo) || !($pdo instanceof PDO)) {
        return []; // Tolerancia si PDO no estÃ¡ disponible
    }

    try {
        $consulta = "select * from parametros WHERE idParametros=1";
        $comando = $pdo->prepare($consulta);
        $comando->execute();
        $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
    } catch (Throwable $e) {
        @file_put_contents(__DIR__ . '/../../logs/parametros.log', date('c') . ' getParametros: ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
        return [];
    }
}

function updateParametros($head, $body){
    require("conexion.php");
    
    if (!isset($pdo) || !($pdo instanceof PDO)) {
        return false; // Tolerancia
    }

    try {
        $data = ["head" => $head, "body" => $body];
        $consulta = "UPDATE parametros SET head=:head, body=:body WHERE idParametros=1 ";
        $comando = $pdo->prepare($consulta);
        return $comando->execute($data);
    } catch (Throwable $e) {
        @file_put_contents(__DIR__ . '/../../logs/parametros.log', date('c') . ' updateParametros: ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
        return false;
    }
}

