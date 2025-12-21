<?php
function getComisionesPrestador($idPrestador){

    require("conexion.php");
    $data=["idPrestador"=>$idPrestador];
    $consulta = "SELECT * FROM prestador_comision WHERE idPrestador =:idPrestador";

    $comando = $pdo->prepare($consulta);

    $comando->execute($data);
    $cuenta_col = $comando->columnCount();

    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla
    return $resultado;


    }

function getComisionPrestador($idPrestadorComision){

    require("conexion.php");
    $data=["idPrestadorComision"=>$idPrestadorComision];
    $consulta = "SELECT * FROM prestador_comision WHERE idPrestadorComision=:idPrestadorComision";

    $comando = $pdo->prepare($consulta);

    $comando->execute($data);
    $cuenta_col = $comando->columnCount();

    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla
    return $resultado;


    }

function setComisionPrestador($idPrestador, $nombre, $comisionVendedor, $comisionSistema){


        require("conexion.php");
        $data=["idPrestador"=>$idPrestador, "nombre"=>$nombre, "comisionVendedor"=>$comisionVendedor, "comisionSistema"=>$comisionSistema];
        $consulta = "INSERT INTO prestador_comision (idPrestador, nombre, comisionVendedor, comisionSistema) VALUES (:idPrestador,:nombre,:comisionVendedor,:comisionSistema) ";

        $comando = $pdo->prepare($consulta);

        $comando->execute($data);

        $id = $pdo->lastInsertId();
        $cuenta_col = $comando->columnCount();
        $cuenta_row = $comando->rowCount();
        $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

        return $id;


        }

function updateComisionPrestador($idPrestadorComision, $nombre, $comisionVendedor, $comisionSistema){

    require("conexion.php");

    // PRIMERO: Actualizar la comisión base
    $data=["idPrestadorComision"=>$idPrestadorComision, "nombre"=>$nombre, "comisionVendedor"=>$comisionVendedor, "comisionSistema"=>$comisionSistema];
    $consulta = "UPDATE prestador_comision SET nombre=:nombre, comisionVendedor=:comisionVendedor, comisionSistema=:comisionSistema WHERE idPrestadorComision=:idPrestadorComision";

    $comando = $pdo->prepare($consulta);
    $comando->execute($data);
    $comision_actualizada = $comando->rowCount();

    // SEGUNDO: Actualizar todas las asignaciones relacionadas en servicio_comision_prestador
    $dataAsignaciones = ["idPrestadorComision" => $idPrestadorComision, "comisionVendedor" => $comisionVendedor, "comisionSistema" => $comisionSistema];
    $consultaAsignaciones = "UPDATE servicio_comision_prestador SET comisionVendedor=:comisionVendedor, comisionSistema=:comisionSistema WHERE idPrestadorComision=:idPrestadorComision";

    $comandoAsignaciones = $pdo->prepare($consultaAsignaciones);
    $comandoAsignaciones->execute($dataAsignaciones);
    $asignaciones_actualizadas = $comandoAsignaciones->rowCount();

    // Retornar información completa de las actualizaciones
    return array(
        'comision_actualizada' => $comision_actualizada,
        'asignaciones_actualizadas' => $asignaciones_actualizadas,
        'total_actualizado' => $comision_actualizada + $asignaciones_actualizadas
    );

    }

function deleteComisionPrestador($idPrestadorComision){

    require("conexion.php");

    // PRIMERO: Eliminar todas las asignaciones relacionadas en servicio_comision_prestador
    $dataAsignaciones = ["idPrestadorComision" => $idPrestadorComision];
    $consultaAsignaciones = "DELETE FROM servicio_comision_prestador WHERE idPrestadorComision = :idPrestadorComision";

    $comandoAsignaciones = $pdo->prepare($consultaAsignaciones);
    $comandoAsignaciones->execute($dataAsignaciones);
    $asignacionesEliminadas = $comandoAsignaciones->rowCount();

    // SEGUNDO: Eliminar la comisión base de prestador_comision
    $data = ["idPrestadorComision" => $idPrestadorComision];
    $consulta = "DELETE FROM prestador_comision WHERE idPrestadorComision = :idPrestadorComision";

    $comando = $pdo->prepare($consulta);
    $comando->execute($data);
    $cuenta_row = $comando->rowCount();

    // Retornar array con información completa de lo eliminado
    return array(
        'comision_eliminada' => $cuenta_row,
        'asignaciones_eliminadas' => $asignacionesEliminadas,
        'total_eliminado' => $cuenta_row + $asignacionesEliminadas
    );


    }

?>
