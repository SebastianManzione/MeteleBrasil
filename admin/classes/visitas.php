<?php

/**
 * Obtiene todos los visitantes de la plataforma
 */
function getVisitas(){
    require("conexion.php");
    $consulta = "select * from visitantes ORDER BY fechaAlta DESC";
    $comando = $pdo->prepare($consulta);
    $comando->execute();
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    return $resultado;
}

/**
 * Obtiene un visitante específico por ID
 */
function getVisita($idVisita){
    require("conexion.php");
    $data=["idVisita"=>$idVisita];
    $consulta = "select * from visitantes WHERE idVisitante=:idVisita";
    $comando = $pdo->prepare($consulta);
    $comando->execute($data);
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    return $resultado;
}

/**
 * Obtiene visitantes de los últimos N días
 */
function getVisitasUltimosDias($dias = 7) {
    require("conexion.php");
    $fecha = date("Y-m-d", strtotime("-$dias days"));
    $consulta = "SELECT * FROM visitantes WHERE fechaAlta >= :fecha ORDER BY fechaAlta DESC";
    $data = ["fecha" => $fecha];
    $comando = $pdo->prepare($consulta);
    $comando->execute($data);
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    return $resultado;
}

/**
 * Obtiene visitantes por rango de fechas
 */
function getVisitasPorRango($fechaInicio, $fechaFin) {
    require("conexion.php");
    $consulta = "SELECT * FROM visitantes WHERE DATE(fechaAlta) BETWEEN :fechaInicio AND :fechaFin ORDER BY fechaAlta DESC";
    $data = [
        "fechaInicio" => $fechaInicio,
        "fechaFin" => $fechaFin
    ];
    $comando = $pdo->prepare($consulta);
    $comando->execute($data);
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    return $resultado;
}

/**
 * Registra un nuevo visitante
 */
function registrarVisitante($nombreVisitante = "Visitante", $email = "", $telefono = "", $idServicio = 0, $referencia = "") {
    require("conexion.php");
    
    $data = [
        "nombreVisitante" => $nombreVisitante,
        "email" => $email,
        "telefono" => $telefono,
        "idServicio" => $idServicio,
        "referencia" => $referencia,
        "fechaAlta" => date("Y-m-d H:i:s"),
        "ip" => $_SERVER['REMOTE_ADDR'] ?? "0.0.0.0"
    ];
    
    $consulta = "INSERT INTO visitantes (nombreVisitante, email, telefono, idServicio, referencia, fechaAlta, ip) 
                 VALUES (:nombreVisitante, :email, :telefono, :idServicio, :referencia, :fechaAlta, :ip)";
    
    $comando = $pdo->prepare($consulta);
    $resultado = $comando->execute($data);
    
    if ($resultado) {
        return $pdo->lastInsertId();
    }
    return 0;
}

/**
 * Obtiene estadísticas de visitantes
 */
function getEstadisticasVisitantes() {
    require("conexion.php");
    
    $hoy = date("Y-m-d");
    $ayer = date("Y-m-d", strtotime("-1 day"));
    $hace7Dias = date("Y-m-d", strtotime("-7 days"));
    $hace30Dias = date("Y-m-d", strtotime("-30 days"));
    
    $consultaHoy = "SELECT COUNT(*) as total FROM visitantes WHERE DATE(fechaAlta) = :fecha";
    $consultaAyer = "SELECT COUNT(*) as total FROM visitantes WHERE DATE(fechaAlta) = :fecha";
    $consulta7 = "SELECT COUNT(*) as total FROM visitantes WHERE DATE(fechaAlta) >= :fecha";
    $consulta30 = "SELECT COUNT(*) as total FROM visitantes WHERE DATE(fechaAlta) >= :fecha";
    $consultaTotal = "SELECT COUNT(*) as total FROM visitantes";
    
    $cmdHoy = $pdo->prepare($consultaHoy);
    $cmdHoy->execute(["fecha" => $hoy]);
    $totalHoy = $cmdHoy->fetch(PDO::FETCH_ASSOC)['total'];
    
    $cmdAyer = $pdo->prepare($consultaAyer);
    $cmdAyer->execute(["fecha" => $ayer]);
    $totalAyer = $cmdAyer->fetch(PDO::FETCH_ASSOC)['total'];
    
    $cmd7 = $pdo->prepare($consulta7);
    $cmd7->execute(["fecha" => $hace7Dias]);
    $total7Dias = $cmd7->fetch(PDO::FETCH_ASSOC)['total'];
    
    $cmd30 = $pdo->prepare($consulta30);
    $cmd30->execute(["fecha" => $hace30Dias]);
    $total30Dias = $cmd30->fetch(PDO::FETCH_ASSOC)['total'];
    
    $cmdTotal = $pdo->prepare($consultaTotal);
    $cmdTotal->execute();
    $totalGeneral = $cmdTotal->fetch(PDO::FETCH_ASSOC)['total'];
    
    return [
        "hoy" => $totalHoy,
        "ayer" => $totalAyer,
        "ultimos7" => $total7Dias,
        "ultimos30" => $total30Dias,
        "total" => $totalGeneral,
        "promedioDiario7" => $total7Dias > 0 ? round($total7Dias / 7, 0) : 0,
        "promedioDiario30" => $total30Dias > 0 ? round($total30Dias / 30, 0) : 0
    ];
}

/**
 * Obtiene visitantes más recientes
 */
function getVisitantesRecientes($limite = 10) {
    require("conexion.php");
    $consulta = "SELECT * FROM visitantes ORDER BY fechaAlta DESC LIMIT :limite";
    $comando = $pdo->prepare($consulta);
    $comando->bindValue(':limite', $limite, PDO::PARAM_INT);
    $comando->execute();
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    return $resultado;
}

/**
 * Elimina un visitante
 */
function eliminarVisitante($idVisitante) {
    require("conexion.php");
    $data = ["idVisitante" => $idVisitante];
    $consulta = "DELETE FROM visitantes WHERE idVisitante = :idVisitante";
    $comando = $pdo->prepare($consulta);
    $resultado = $comando->execute($data);
    return $comando->rowCount();
}

/**
 * Limpia visitantes antiguos (más de N días)
 */
function limpiarVisitantesAntiguos($diasRetener = 90) {
    require("conexion.php");
    $fecha = date("Y-m-d", strtotime("-$diasRetener days"));
    $consulta = "DELETE FROM visitantes WHERE fechaAlta < :fecha";
    $data = ["fecha" => $fecha];
    $comando = $pdo->prepare($consulta);
    $resultado = $comando->execute($data);
    return $comando->rowCount();
}

?>
