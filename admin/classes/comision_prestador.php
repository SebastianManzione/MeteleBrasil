<?php
function getComisionesPrestadorServicio($idServicio){

    require("conexion.php");
  $data=["idServicio"=>$idServicio];
    $consulta = "SELECT scp.*,
                        CASE
                            WHEN pc.nombre IS NOT NULL THEN pc.nombre
                            WHEN scp.idPrestadorComision IS NULL THEN 'Comisión asignada'
                            ELSE 'Sin nombre'
                        END as nombre_comision
                 FROM servicio_comision_prestador scp
                 LEFT JOIN prestador_comision pc ON scp.idPrestadorComision = pc.idPrestadorComision
                 WHERE scp.idServicio =:idServicio";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla
    return $resultado;
    
    
    }



    function getComisionesPrestadorServicioIdPrestadorIdServicio($idServicio, $idPrestador){

    require("conexion.php");
  $data=["idServicio"=>$idServicio, "idPrestador"=>$idPrestador];
    $consulta = "SELECT scp.*,
                        CASE
                            WHEN pc.nombre IS NOT NULL THEN pc.nombre
                            WHEN scp.idPrestadorComision IS NULL THEN 'Comisión asignada'
                            ELSE 'Sin nombre'
                        END as nombre_comision
                 FROM servicio_comision_prestador scp
                 LEFT JOIN prestador_comision pc ON scp.idPrestadorComision = pc.idPrestadorComision
                 WHERE scp.idServicio =:idServicio AND scp.idPrestador=:idPrestador";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla
    return $resultado;
    
    
    }

    function getComisionPrestadorServicio($idServicioComisionPrestador){

    require("conexion.php");
    $data=["idServicioComisionPrestador"=>$idServicioComisionPrestador];
    $consulta = "select * from servicio_comision_prestador WHERE idServicioComisionPrestador=:idServicioComisionPrestador";
    
    $comando = $pdo->prepare($consulta);
    
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla
    return $resultado;
    
    
    }
 function setComisionPrestadorServicio($idServicio, $idPrestador, $comisionVendedor, $comisionSistema){


        require("conexion.php");
        $data=["idServicio"=> $idServicio, "idPrestador"=>$idPrestador, "comisionVendedor"=>$comisionVendedor, "comisionSistema"=>$comisionSistema];
        $consulta = "INSERT INTO servicio_comision_prestador (idServicio, idPrestador, comisionVendedor, comisionSistema) VALUES (:idServicio,:idPrestador,:comisionVendedor,:comisionSistema) ";
        
        $comando = $pdo->prepare($consulta);
        
        $comando->execute($data);
        
        $id = $pdo->lastInsertId(); 
        $cuenta_col = $comando->columnCount();
        $cuenta_row = $comando->rowCount();
        $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

        return $id;
        
        
        }

function asignarComisionPrestadorServicio($idServicio, $idPrestadorComision){

    require("conexion.php");

    // Obtener los datos de la comisión existente
    $comisionData = getComisionPrestador($idPrestadorComision);

    if (count($comisionData) > 0) {
        $comision = $comisionData[0];
        $idPrestador = $comision['idPrestador'];
        $comisionVendedor = $comision['comisionVendedor'];
        $comisionSistema = $comision['comisionSistema'];

        // Verificar que no esté ya asignada exactamente la misma comisión
        $existing = getComisionesPrestadorServicioIdPrestadorIdServicio($idServicio, $idPrestador);
        foreach ($existing as $exist) {
            if ($exist['idPrestadorComision'] == $idPrestadorComision) {
                return 0; // Esta comisión específica ya está asignada
            }
        }

        // Asignar la nueva comisión al servicio (relación 1:N - múltiples comisiones por prestador/servicio)
        $data = [
            "idServicio" => $idServicio,
            "idPrestador" => $idPrestador,
            "idPrestadorComision" => $idPrestadorComision,
            "comisionVendedor" => $comisionVendedor,
            "comisionSistema" => $comisionSistema
        ];

        $consulta = "INSERT INTO servicio_comision_prestador (idServicio, idPrestador, idPrestadorComision, comisionVendedor, comisionSistema)
                     VALUES (:idServicio, :idPrestador, :idPrestadorComision, :comisionVendedor, :comisionSistema)";

        $comando = $pdo->prepare($consulta);
        $comando->execute($data);

        $id = $pdo->lastInsertId();
        return $id;
    }

    return 0;
}

function deleteComisionPrestadorServicio($idServicioComisionPrestador){

    require("conexion.php");
    $data = ["idServicioComisionPrestador" => $idServicioComisionPrestador];
    $consulta = "DELETE FROM servicio_comision_prestador WHERE idServicioComisionPrestador = :idServicioComisionPrestador";

    $comando = $pdo->prepare($consulta);
    $comando->execute($data);

    $cuenta_row = $comando->rowCount();
    return $cuenta_row;
}

function comisionPrestadorEnUso($idPrestadorComision){

    require("conexion.php");
    $data = ["idPrestadorComision" => $idPrestadorComision];

    // Verificar si la comisión está siendo usada en alguna salida
    // El idComisionPrestador en servicio_salidas apunta a servicio_comision_prestador.idServicioComisionPrestador
    $consulta = "SELECT COUNT(*) as total FROM servicio_salidas ss
                 INNER JOIN servicio_comision_prestador scp ON scp.idServicioComisionPrestador = ss.idComisionPrestador
                 WHERE scp.idPrestadorComision = :idPrestadorComision";

    $comando = $pdo->prepare($consulta);
    $comando->execute($data);
    $resultado = $comando->fetch(PDO::FETCH_ASSOC);

    return $resultado['total'] > 0;
}

function obtenerSalidasConComision($idPrestadorComision){

    require("conexion.php");
    $data = ["idPrestadorComision" => $idPrestadorComision];

    // Obtener detalles de las salidas que usan esta comisión
    // El idComisionPrestador en servicio_salidas apunta a servicio_comision_prestador.idServicioComisionPrestador
    $consulta = "SELECT ss.idServicioSalidas, ss.nombre, ss.fecha, s.nombre_servicio
                 FROM servicio_salidas ss
                 INNER JOIN servicios s ON ss.idServicio = s.idServicio
                 INNER JOIN servicio_comision_prestador scp ON scp.idServicioComisionPrestador = ss.idComisionPrestador
                 WHERE scp.idPrestadorComision = :idPrestadorComision
                 ORDER BY ss.fecha DESC
                 LIMIT 5"; // Limitar a 5 resultados para no sobrecargar

    $comando = $pdo->prepare($consulta);
    $comando->execute($data);
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

    return $resultado;
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
    
function obtenerSalidasConAsignacion($idServicioComisionPrestador){

    require("conexion.php");
    $data = ["idServicioComisionPrestador" => $idServicioComisionPrestador];

    // Obtener detalles de las salidas que usan esta asignación específica
    $consulta = "SELECT ss.idServicioSalidas, ss.nombre, ss.fecha, s.nombre_servicio
                 FROM servicio_salidas ss
                 INNER JOIN servicios s ON ss.idServicio = s.idServicio
                 WHERE ss.idComisionPrestador = :idServicioComisionPrestador
                 ORDER BY ss.fecha DESC
                 LIMIT 5"; // Limitar a 5 resultados para no sobrecargar

    $comando = $pdo->prepare($consulta);
    $comando->execute($data);
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

    return $resultado;
}

?>