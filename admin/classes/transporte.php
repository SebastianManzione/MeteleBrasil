<?php
/**
 * CLASE: TRANSPORTE
 * Gestión de rutas, viajes y reservas de transporte
 * Fecha: 2026-01-16
 * Branch: feature/cambios-grosos
 */

// ========================================
// TIPOS DE TRANSPORTE
// ========================================

function getAllTiposTransporte() {
    require("conexion.php");
    $consulta = "SELECT * FROM tipo_transporte WHERE habilitado=1 ORDER BY nombre";
    $comando = $pdo->prepare($consulta);
    $comando->execute();
    return $comando->fetchAll(PDO::FETCH_ASSOC);
}

// ========================================
// TERMINALES/PUNTOS DE PARADA
// ========================================

function getAllTerminales() {
    require("conexion.php");
    $consulta = "SELECT t.*, tt.nombre as tipo_transporte_nombre 
                 FROM terminal_transporte t 
                 LEFT JOIN tipo_transporte tt ON t.idTipoTransporte = tt.idTipoTransporte
                 WHERE t.habilitado=1 
                 ORDER BY t.ciudad, t.nombre";
    $comando = $pdo->prepare($consulta);
    $comando->execute();
    return $comando->fetchAll(PDO::FETCH_ASSOC);
}

function getTerminal($idTerminal) {
    require("conexion.php");
    $consulta = "SELECT * FROM terminal_transporte WHERE idTerminal = :idTerminal";
    $comando = $pdo->prepare($consulta);
    $comando->execute(['idTerminal' => $idTerminal]);
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    return !empty($resultado) ? $resultado[0] : null;
}

function getTerminalesByTipo($idTipoTransporte) {
    require("conexion.php");
    $consulta = "SELECT * FROM terminal_transporte 
                 WHERE idTipoTransporte = :idTipoTransporte AND habilitado=1
                 ORDER BY ciudad, nombre";
    $comando = $pdo->prepare($consulta);
    $comando->execute(['idTipoTransporte' => $idTipoTransporte]);
    return $comando->fetchAll(PDO::FETCH_ASSOC);
}

function getTerminalesByCiudad($ciudad) {
    require("conexion.php");
    $consulta = "SELECT * FROM terminal_transporte 
                 WHERE ciudad LIKE :ciudad AND habilitado=1
                 ORDER BY nombre";
    $comando = $pdo->prepare($consulta);
    $comando->execute(['ciudad' => "%$ciudad%"]);
    return $comando->fetchAll(PDO::FETCH_ASSOC);
}

function insertTerminal($datos) {
    require("conexion.php");
    $consulta = "INSERT INTO terminal_transporte 
                 (nombre, direccion, latitud, longitud, idPais, idEstado, ciudad, 
                  codigo_iata, idTipoTransporte, observaciones, habilitado)
                 VALUES (:nombre, :direccion, :latitud, :longitud, :idPais, :idEstado, 
                         :ciudad, :codigo_iata, :idTipoTransporte, :observaciones, :habilitado)";
    $comando = $pdo->prepare($consulta);
    $comando->execute($datos);
    return $pdo->lastInsertId();
}

// ========================================
// EMPRESAS DE TRANSPORTE
// ========================================

function getAllEmpresas() {
    require("conexion.php");
    $consulta = "SELECT e.*, t.nombre as tipo_transporte_nombre
                 FROM empresa_transporte e
                 LEFT JOIN tipo_transporte t ON e.idTipoTransporte = t.idTipoTransporte
                 WHERE e.habilitado=1
                 ORDER BY e.nombre";
    $comando = $pdo->prepare($consulta);
    $comando->execute();
    return $comando->fetchAll(PDO::FETCH_ASSOC);
}

function getEmpresa($idEmpresa) {
    require("conexion.php");
    $consulta = "SELECT * FROM empresa_transporte WHERE idEmpresa = :idEmpresa";
    $comando = $pdo->prepare($consulta);
    $comando->execute(['idEmpresa' => $idEmpresa]);
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    return !empty($resultado) ? $resultado[0] : null;
}

// ========================================
// RUTAS DE TRANSPORTE
// ========================================

function getAllRutas() {
    require("conexion.php");
    
    // Obtener idioma de sesión
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $idioma = isset($_SESSION["idioma"]) ? $_SESSION["idioma"] : "ES";
    
    $consulta = "SELECT r.*, 
                 t.nombre as tipo_transporte_nombre,
                 e.nombre as empresa_nombre,
                 p.nombre as prestador_nombre
                 FROM ruta_transporte r
                 LEFT JOIN tipo_transporte t ON r.idTipoTransporte = t.idTipoTransporte
                 LEFT JOIN empresa_transporte e ON r.idEmpresa = e.idEmpresa
                 LEFT JOIN prestadores p ON r.idPrestador = p.idPrestador
                 WHERE r.habilitado=1
                 ORDER BY r.nombre";
    $comando = $pdo->prepare($consulta);
    $comando->execute();
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    
    // Ajustar idioma
    foreach ($resultado as &$row) {
        switch ($idioma) {
            case 'EN':
                $row['nombre'] = $row['nombre_en'] ?? $row['nombre'];
                $row['descripcion'] = $row['descripcion_en'] ?? $row['descripcion'];
                break;
            case 'PT':
                $row['nombre'] = $row['nombre_pt'] ?? $row['nombre'];
                $row['descripcion'] = $row['descripcion_pt'] ?? $row['descripcion'];
                break;
            case 'IT':
                $row['nombre'] = $row['nombre_it'] ?? $row['nombre'];
                $row['descripcion'] = $row['descripcion_it'] ?? $row['descripcion'];
                break;
        }
    }
    
    return $resultado;
}

function getRuta($idRuta) {
    require("conexion.php");
    
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $idioma = isset($_SESSION["idioma"]) ? $_SESSION["idioma"] : "ES";
    
    $consulta = "SELECT r.*, 
                 t.nombre as tipo_transporte_nombre, t.icono as tipo_transporte_icono,
                 e.nombre as empresa_nombre, e.logo as empresa_logo
                 FROM ruta_transporte r
                 LEFT JOIN tipo_transporte t ON r.idTipoTransporte = t.idTipoTransporte
                 LEFT JOIN empresa_transporte e ON r.idEmpresa = e.idEmpresa
                 WHERE r.idRuta = :idRuta";
    $comando = $pdo->prepare($consulta);
    $comando->execute(['idRuta' => $idRuta]);
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    
    if (!empty($resultado)) {
        $row = &$resultado[0];
        switch ($idioma) {
            case 'EN':
                $row['nombre'] = $row['nombre_en'] ?? $row['nombre'];
                $row['descripcion'] = $row['descripcion_en'] ?? $row['descripcion'];
                break;
            case 'PT':
                $row['nombre'] = $row['nombre_pt'] ?? $row['nombre'];
                $row['descripcion'] = $row['descripcion_pt'] ?? $row['descripcion'];
                break;
            case 'IT':
                $row['nombre'] = $row['nombre_it'] ?? $row['nombre'];
                $row['descripcion'] = $row['descripcion_it'] ?? $row['descripcion'];
                break;
        }
        return $row;
    }
    
    return null;
}

function insertRuta($datos) {
    require("conexion.php");
    $consulta = "INSERT INTO ruta_transporte 
                 (nombre, nombre_en, nombre_pt, nombre_it, descripcion, descripcion_en, 
                  descripcion_pt, descripcion_it, idTipoTransporte, idEmpresa, idPrestador,
                  duracion_estimada, distancia_km, foto_principal, habilitado)
                 VALUES (:nombre, :nombre_en, :nombre_pt, :nombre_it, :descripcion, 
                         :descripcion_en, :descripcion_pt, :descripcion_it, :idTipoTransporte,
                         :idEmpresa, :idPrestador, :duracion_estimada, :distancia_km, 
                         :foto_principal, :habilitado)";
    $comando = $pdo->prepare($consulta);
    $comando->execute($datos);
    return $pdo->lastInsertId();
}

// ========================================
// PARADAS DE RUTA (origen/destino múltiples)
// ========================================

function getParadasRuta($idRuta) {
    require("conexion.php");
    $consulta = "SELECT rp.*, t.nombre as terminal_nombre, t.ciudad, t.codigo_iata
                 FROM ruta_paradas rp
                 INNER JOIN terminal_transporte t ON rp.idTerminal = t.idTerminal
                 WHERE rp.idRuta = :idRuta
                 ORDER BY rp.orden";
    $comando = $pdo->prepare($consulta);
    $comando->execute(['idRuta' => $idRuta]);
    return $comando->fetchAll(PDO::FETCH_ASSOC);
}

function getOrigenesRuta($idRuta) {
    require("conexion.php");
    $consulta = "SELECT rp.*, t.nombre as terminal_nombre, t.ciudad, t.codigo_iata
                 FROM ruta_paradas rp
                 INNER JOIN terminal_transporte t ON rp.idTerminal = t.idTerminal
                 WHERE rp.idRuta = :idRuta AND rp.es_origen = 1
                 ORDER BY rp.orden";
    $comando = $pdo->prepare($consulta);
    $comando->execute(['idRuta' => $idRuta]);
    return $comando->fetchAll(PDO::FETCH_ASSOC);
}

function getDestinosRuta($idRuta) {
    require("conexion.php");
    $consulta = "SELECT rp.*, t.nombre as terminal_nombre, t.ciudad, t.codigo_iata
                 FROM ruta_paradas rp
                 INNER JOIN terminal_transporte t ON rp.idTerminal = t.idTerminal
                 WHERE rp.idRuta = :idRuta AND rp.es_destino = 1
                 ORDER BY rp.orden";
    $comando = $pdo->prepare($consulta);
    $comando->execute(['idRuta' => $idRuta]);
    return $comando->fetchAll(PDO::FETCH_ASSOC);
}

function insertParadaRuta($datos) {
    require("conexion.php");
    $consulta = "INSERT INTO ruta_paradas 
                 (idRuta, idTerminal, orden, es_origen, es_destino, tiempo_desde_inicio)
                 VALUES (:idRuta, :idTerminal, :orden, :es_origen, :es_destino, :tiempo_desde_inicio)";
    $comando = $pdo->prepare($consulta);
    $comando->execute($datos);
    return $pdo->lastInsertId();
}

// ========================================
// VIAJES (equivalente a salidas)
// ========================================

function getViajesRuta($idRuta, $soloFuturos = true) {
    require("conexion.php");
    $fechaHoy = date("Y-m-d");
    
    $consulta = "SELECT v.*, 
                 to1.nombre as origen_nombre, to1.ciudad as origen_ciudad,
                 td1.nombre as destino_nombre, td1.ciudad as destino_ciudad
                 FROM viaje_transporte v
                 INNER JOIN terminal_transporte to1 ON v.idTerminalOrigen = to1.idTerminal
                 INNER JOIN terminal_transporte td1 ON v.idTerminalDestino = td1.idTerminal
                 WHERE v.idRuta = :idRuta AND v.habilitado = 1";
    
    if ($soloFuturos) {
        $consulta .= " AND v.fecha >= :fechaHoy";
    }
    
    $consulta .= " ORDER BY v.fecha ASC, v.hora_salida ASC";
    
    $comando = $pdo->prepare($consulta);
    $params = ['idRuta' => $idRuta];
    if ($soloFuturos) {
        $params['fechaHoy'] = $fechaHoy;
    }
    $comando->execute($params);
    return $comando->fetchAll(PDO::FETCH_ASSOC);
}

function getViaje($idViaje) {
    require("conexion.php");
    $consulta = "SELECT v.*, 
                 r.nombre as ruta_nombre,
                 to1.nombre as origen_nombre, to1.ciudad as origen_ciudad, to1.codigo_iata as origen_iata,
                 td1.nombre as destino_nombre, td1.ciudad as destino_ciudad, td1.codigo_iata as destino_iata
                 FROM viaje_transporte v
                 INNER JOIN ruta_transporte r ON v.idRuta = r.idRuta
                 INNER JOIN terminal_transporte to1 ON v.idTerminalOrigen = to1.idTerminal
                 INNER JOIN terminal_transporte td1 ON v.idTerminalDestino = td1.idTerminal
                 WHERE v.idViaje = :idViaje";
    $comando = $pdo->prepare($consulta);
    $comando->execute(['idViaje' => $idViaje]);
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    return !empty($resultado) ? $resultado[0] : null;
}

function buscarViajes($filtros) {
    /**
     * Buscar viajes por:
     * - idTerminalOrigen
     * - idTerminalDestino
     * - fecha
     * - idTipoTransporte
     */
    require("conexion.php");
    
    $consulta = "SELECT v.*, 
                 r.nombre as ruta_nombre, r.idTipoTransporte,
                 to1.nombre as origen_nombre, to1.ciudad as origen_ciudad,
                 td1.nombre as destino_nombre, td1.ciudad as destino_ciudad,
                 e.nombre as empresa_nombre
                 FROM viaje_transporte v
                 INNER JOIN ruta_transporte r ON v.idRuta = r.idRuta
                 INNER JOIN terminal_transporte to1 ON v.idTerminalOrigen = to1.idTerminal
                 INNER JOIN terminal_transporte td1 ON v.idTerminalDestino = td1.idTerminal
                 LEFT JOIN empresa_transporte e ON r.idEmpresa = e.idEmpresa
                 WHERE v.habilitado = 1";
    
    $params = [];
    
    if (!empty($filtros['idTerminalOrigen'])) {
        $consulta .= " AND v.idTerminalOrigen = :idTerminalOrigen";
        $params['idTerminalOrigen'] = $filtros['idTerminalOrigen'];
    }
    
    if (!empty($filtros['idTerminalDestino'])) {
        $consulta .= " AND v.idTerminalDestino = :idTerminalDestino";
        $params['idTerminalDestino'] = $filtros['idTerminalDestino'];
    }
    
    if (!empty($filtros['fecha'])) {
        $consulta .= " AND v.fecha = :fecha";
        $params['fecha'] = $filtros['fecha'];
    }
    
    if (!empty($filtros['idTipoTransporte'])) {
        $consulta .= " AND r.idTipoTransporte = :idTipoTransporte";
        $params['idTipoTransporte'] = $filtros['idTipoTransporte'];
    }
    
    $consulta .= " ORDER BY v.fecha ASC, v.hora_salida ASC";
    
    $comando = $pdo->prepare($consulta);
    $comando->execute($params);
    return $comando->fetchAll(PDO::FETCH_ASSOC);
}

function insertViaje($datos) {
    require("conexion.php");
    $consulta = "INSERT INTO viaje_transporte 
                 (idRuta, fecha, hora_salida, hora_llegada, asientos_totales, 
                  asientos_disponibles, idTerminalOrigen, idTerminalDestino, 
                  numero_vuelo_bus, observaciones, habilitado)
                 VALUES (:idRuta, :fecha, :hora_salida, :hora_llegada, :asientos_totales,
                         :asientos_disponibles, :idTerminalOrigen, :idTerminalDestino,
                         :numero_vuelo_bus, :observaciones, :habilitado)";
    $comando = $pdo->prepare($consulta);
    $comando->execute($datos);
    return $pdo->lastInsertId();
}

// ========================================
// TARIFAS DE VIAJES
// ========================================

function getTarifasViaje($idViaje) {
    require("conexion.php");
    $consulta = "SELECT vt.*, 
                 to1.nombre as origen_nombre, to1.ciudad as origen_ciudad,
                 td1.nombre as destino_nombre, td1.ciudad as destino_ciudad,
                 m.moneda as moneda_simbolo
                 FROM viaje_tarifa vt
                 INNER JOIN terminal_transporte to1 ON vt.idTerminalOrigen = to1.idTerminal
                 INNER JOIN terminal_transporte td1 ON vt.idTerminalDestino = td1.idTerminal
                 INNER JOIN moneda m ON vt.idMoneda = m.idMoneda
                 WHERE vt.idViaje = :idViaje
                 ORDER BY vt.idTerminalOrigen, vt.idTerminalDestino, vt.idTipoTarifa";
    $comando = $pdo->prepare($consulta);
    $comando->execute(['idViaje' => $idViaje]);
    return $comando->fetchAll(PDO::FETCH_ASSOC);
}

function insertTarifaViaje($datos) {
    require("conexion.php");
    $consulta = "INSERT INTO viaje_tarifa 
                 (idViaje, idTerminalOrigen, idTerminalDestino, idTipoTarifa, 
                  precio, idMoneda, comisiona)
                 VALUES (:idViaje, :idTerminalOrigen, :idTerminalDestino, :idTipoTarifa,
                         :precio, :idMoneda, :comisiona)";
    $comando = $pdo->prepare($consulta);
    $comando->execute($datos);
    return $pdo->lastInsertId();
}

?>
