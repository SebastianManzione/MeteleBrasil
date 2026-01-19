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
// TIPOS DE PARADAS (DINÁMICOS) - NUEVO
// ========================================

function getAllTiposParada() {
    require("conexion.php");
    $consulta = "SELECT * FROM tipo_parada WHERE habilitado=1 ORDER BY nombre";
    $comando = $pdo->prepare($consulta);
    $comando->execute();
    return $comando->fetchAll(PDO::FETCH_ASSOC);
}

function getTipoParada($idTipoPrada) {
    require("conexion.php");
    $consulta = "SELECT * FROM tipo_parada WHERE idTipoPrada = :idTipoPrada";
    $comando = $pdo->prepare($consulta);
    $comando->execute(['idTipoPrada' => $idTipoPrada]);
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    return !empty($resultado) ? $resultado[0] : null;
}

function getTipoParadaPorNombre($nombre) {
    require("conexion.php");
    $consulta = "SELECT * FROM tipo_parada WHERE LOWER(nombre) = LOWER(:nombre)";
    $comando = $pdo->prepare($consulta);
    $comando->execute(['nombre' => $nombre]);
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    return !empty($resultado) ? $resultado[0] : null;
}

function insertTipoParada($datos) {
    require("conexion.php");
    $consulta = "INSERT INTO tipo_parada (nombre, icono, color, habilitado)
                 VALUES (:nombre, :icono, :color, :habilitado)";
    $comando = $pdo->prepare($consulta);
    $comando->execute($datos);
    return $pdo->lastInsertId();
}

function updateTipoParada($idTipoPrada, $datos) {
    require("conexion.php");
    $consulta = "UPDATE tipo_parada 
                SET nombre = :nombre,
                    icono = :icono,
                    color = :color,
                    habilitado = :habilitado
                WHERE idTipoPrada = :idTipoPrada";
    
    $datos['idTipoPrada'] = $idTipoPrada;
    $comando = $pdo->prepare($consulta);
    return $comando->execute($datos);
}

function deleteTipoParada($idTipoPrada) {
    require("conexion.php");
    // Verificar si hay paradas usando este tipo
    $stmt = $pdo->prepare("SELECT COUNT(*) as cnt FROM parada WHERE idTipoPrada = :idTipoPrada");
    $stmt->execute(['idTipoPrada' => $idTipoPrada]);
    $count = $stmt->fetch()['cnt'];
    
    if ($count > 0) {
        // Soft delete: marcar como inactivo en lugar de eliminar
        $stmt = $pdo->prepare("UPDATE tipo_parada SET habilitado = 0 WHERE idTipoPrada = :idTipoPrada");
        return $stmt->execute(['idTipoPrada' => $idTipoPrada]);
    } else {
        // Si no hay referencias, eliminar completamente
        $stmt = $pdo->prepare("DELETE FROM tipo_parada WHERE idTipoPrada = :idTipoPrada");
        return $stmt->execute(['idTipoPrada' => $idTipoPrada]);
    }
}

// ========================================
// PARADAS (TERMINALES Y CUSTOMIZADAS UNIFICADAS)
// ========================================

function getAllParadas() {
    require("conexion.php");
    // Usa terminal_transporte en lugar de tabla "parada"
    $consulta = "SELECT t.idTerminal as idParada, t.nombre, t.ciudad, t.estado, t.pais, t.direccion,
                 t.latitud, t.longitud, t.habilitado,
                 tt.nombre as tipo_nombre, 'fa-map-marker-alt' as icono, '#029ce2' as color
                 FROM terminal_transporte t
                 LEFT JOIN tipo_transporte tt ON t.idTipoTransporte = tt.idTipoTransporte
                 WHERE t.habilitado=1 
                 ORDER BY t.ciudad, t.nombre";
    $comando = $pdo->prepare($consulta);
    $comando->execute();
    return $comando->fetchAll(PDO::FETCH_ASSOC);
}

function getParada($idParada) {
    require("conexion.php");
    $consulta = "SELECT p.*, tp.nombre as tipo_nombre, tp.icono, tp.color
                 FROM parada p
                 LEFT JOIN tipo_parada tp ON p.idTipoPrada = tp.idTipoPrada
                 WHERE p.idParada = :idParada";
    $comando = $pdo->prepare($consulta);
    $comando->execute(['idParada' => $idParada]);
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    return !empty($resultado) ? $resultado[0] : null;
}

function getParadasPorTipo($tipo) {
    require("conexion.php");
    $consulta = "SELECT p.*, tp.nombre as tipo_nombre, tp.icono, tp.color
                 FROM parada p
                 LEFT JOIN tipo_parada tp ON p.idTipoPrada = tp.idTipoPrada
                 WHERE p.tipo = :tipo AND p.habilitado=1 
                 ORDER BY p.ciudad, p.nombre";
    $comando = $pdo->prepare($consulta);
    $comando->execute(['tipo' => $tipo]);
    return $comando->fetchAll(PDO::FETCH_ASSOC);
}

function getParadasPorTipoPrada($idTipoPrada) {
    require("conexion.php");
    $consulta = "SELECT p.*, tp.nombre as tipo_nombre, tp.icono, tp.color
                 FROM parada p
                 LEFT JOIN tipo_parada tp ON p.idTipoPrada = tp.idTipoPrada
                 WHERE p.idTipoPrada = :idTipoPrada AND p.habilitado=1 
                 ORDER BY p.ciudad, p.nombre";
    $comando = $pdo->prepare($consulta);
    $comando->execute(['idTipoPrada' => $idTipoPrada]);
    return $comando->fetchAll(PDO::FETCH_ASSOC);
}

function getParadasPorCiudad($ciudad) {
    require("conexion.php");
    $consulta = "SELECT p.*, tp.nombre as tipo_nombre, tp.icono, tp.color
                 FROM parada p
                 LEFT JOIN tipo_parada tp ON p.idTipoPrada = tp.idTipoPrada
                 WHERE p.ciudad LIKE :ciudad AND p.habilitado=1 
                 ORDER BY p.nombre";
    $comando = $pdo->prepare($consulta);
    $comando->execute(['ciudad' => "%$ciudad%"]);
    return $comando->fetchAll(PDO::FETCH_ASSOC);
}

function insertParada($datos) {
    require("conexion.php");
    $consulta = "INSERT INTO parada 
                 (nombre, tipo, direccion, ciudad, estado, pais, latitud, longitud, habilitado)
                 VALUES (:nombre, :tipo, :direccion, :ciudad, :estado, :pais, :latitud, :longitud, :habilitado)";
    $comando = $pdo->prepare($consulta);
    $comando->execute($datos);
    return $pdo->lastInsertId();
}

function updateParada($idParada, $datos) {
    require("conexion.php");
    $consulta = "UPDATE parada 
                SET nombre = :nombre,
                    tipo = :tipo,
                    direccion = :direccion,
                    ciudad = :ciudad,
                    estado = :estado,
                    pais = :pais,
                    latitud = :latitud,
                    longitud = :longitud,
                    habilitado = :habilitado
                WHERE idParada = :idParada";
    
    $datos['idParada'] = $idParada;
    $comando = $pdo->prepare($consulta);
    return $comando->execute($datos);
}

// COMPATIBILIDAD: Mantener funciones antiguas como alias (para no romper código existente)
function getAllTerminales() {
    return getAllParadas();
}

function getTerminal($idTerminal) {
    return getParada($idTerminal);
}

function getTerminalesByTipo($idTipoTransporte) {
    return getParadasPorTipo('terminal');
}

function getTerminalesByCiudad($ciudad) {
    return getParadasPorCiudad($ciudad);
}

function insertTerminal($datos) {
    require("conexion.php");
    $consulta = "INSERT INTO terminal_transporte 
                 (nombre, idTipoTransporte, direccion, ciudad, estado, pais, latitud, longitud, habilitado)
                 VALUES (:nombre, :idTipoTransporte, :direccion, :ciudad, :estado, :pais, :latitud, :longitud, :habilitado)";
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

function updateRuta($idRuta, $datos) {
    require("conexion.php");
    $consulta = "UPDATE ruta_transporte SET 
                 nombre = :nombre,
                 nombre_en = :nombre_en,
                 nombre_pt = :nombre_pt,
                 nombre_it = :nombre_it,
                 descripcion = :descripcion,
                 descripcion_en = :descripcion_en,
                 descripcion_pt = :descripcion_pt,
                 descripcion_it = :descripcion_it,
                 idTipoTransporte = :idTipoTransporte,
                 idEmpresa = :idEmpresa,
                 idPrestador = :idPrestador,
                 duracion_estimada = :duracion_estimada,
                 distancia_km = :distancia_km,
                 foto_principal = :foto_principal,
                 habilitado = :habilitado
                 WHERE idRuta = :idRuta";
    $comando = $pdo->prepare($consulta);
    $datos['idRuta'] = $idRuta;
    return $comando->execute($datos);
}

function deleteRuta($idRuta) {
    require("conexion.php");
    
    // Verificar si hay viajes asociados
    $consultaViajes = "SELECT COUNT(*) as total FROM viaje_transporte WHERE idRuta = :idRuta";
    $cmdViajes = $pdo->prepare($consultaViajes);
    $cmdViajes->execute(['idRuta' => $idRuta]);
    $resultado = $cmdViajes->fetch(PDO::FETCH_ASSOC);
    
    if ($resultado['total'] > 0) {
        // Si hay viajes, solo deshabilitar
        $consulta = "UPDATE ruta_transporte SET habilitado = 0 WHERE idRuta = :idRuta";
        $comando = $pdo->prepare($consulta);
        return $comando->execute(['idRuta' => $idRuta]);
    } else {
        // Si no hay viajes, eliminar completamente
        $consulta = "DELETE FROM ruta_transporte WHERE idRuta = :idRuta";
        $comando = $pdo->prepare($consulta);
        return $comando->execute(['idRuta' => $idRuta]);
    }
}

// ========================================
// PARADAS DE RUTA (origen/destino múltiples)
// ========================================

function getParadasRuta($idRuta) {
    require("conexion.php");
    $consulta = "SELECT rp.*, 
                 t.nombre as terminal_nombre, 
                 t.ciudad, 
                 t.codigo_iata, 
                 t.latitud, 
                 t.longitud,
                 t.idTipoTransporte as parada_tipo
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
    $consulta = "SELECT rp.*, 
                 t.nombre as terminal_nombre, 
                 t.ciudad, 
                 t.codigo_iata, 
                 t.latitud, 
                 t.longitud,
                 t.idTipoTransporte as parada_tipo
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
    $consulta = "SELECT rp.*, 
                 t.nombre as terminal_nombre, 
                 t.ciudad, 
                 t.codigo_iata, 
                 t.latitud, 
                 t.longitud,
                 t.idTipoTransporte as parada_tipo
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
    // Viaje: datos completos incluyendo origen/destino desde ruta_paradas
    $consulta = "SELECT v.*, 
                 r.nombre as ruta_nombre, r.idTipoTransporte, r.distancia_km, r.duracion_estimada,
                 -- Origen: parada inicial de la ruta
                 t_origen.nombre as terminal_origen_nombre, t_origen.ciudad as origen_ciudad, t_origen.estado as origen_estado,
                 -- Destino: parada final de la ruta
                 t_destino.nombre as terminal_destino_nombre, t_destino.ciudad as destino_ciudad, t_destino.estado as destino_estado,
                 tp.nombre as tipo_transporte_nombre, tp.icono as tipo_transporte_icono,
                 em.nombre as empresa_nombre
                 FROM viaje_transporte v
                 INNER JOIN ruta_transporte r ON v.idRuta = r.idRuta
                 -- Parada origen
                 LEFT JOIN ruta_paradas rp_desde ON v.idDesdeParada = rp_desde.idRutaParada
                 LEFT JOIN terminal_transporte t_origen ON rp_desde.idTerminal = t_origen.idTerminal
                 -- Parada destino
                 LEFT JOIN ruta_paradas rp_hasta ON v.idHastaParada = rp_hasta.idRutaParada
                 LEFT JOIN terminal_transporte t_destino ON rp_hasta.idTerminal = t_destino.idTerminal
                 LEFT JOIN tipo_transporte tp ON r.idTipoTransporte = tp.idTipoTransporte
                 LEFT JOIN empresa_transporte em ON r.idEmpresa = em.idEmpresa
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
                 (idRuta, idModelo, idDesdeParada, idHastaParada, fecha, hora_salida, hora_llegada, 
                  asientos_totales, asientos_disponibles, numero_vuelo_bus, observaciones, habilitado)
                 VALUES (:idRuta, :idModelo, :idDesdeParada, :idHastaParada, :fecha, :hora_salida, :hora_llegada,
                         :asientos_totales, :asientos_disponibles, :numero_vuelo_bus, :observaciones, :habilitado)";
    $comando = $pdo->prepare($consulta);
    
    // Asegurar valores por defecto
    if (!isset($datos['habilitado'])) {
        $datos['habilitado'] = 1;
    }
    if (!isset($datos['asientos_disponibles'])) {
        $datos['asientos_disponibles'] = $datos['asientos_totales'];
    }
    if (!isset($datos['hora_llegada'])) {
        $datos['hora_llegada'] = null;
    }
    if (!isset($datos['numero_vuelo_bus'])) {
        $datos['numero_vuelo_bus'] = null;
    }
    if (!isset($datos['idModelo'])) {
        $datos['idModelo'] = null;
    }
    if (!isset($datos['observaciones'])) {
        $datos['observaciones'] = null;
    }
    if (!isset($datos['idDesdeParada'])) {
        $datos['idDesdeParada'] = null;
    }
    if (!isset($datos['idHastaParada'])) {
        $datos['idHastaParada'] = null;
    }
    
    $comando->execute($datos);
    return $pdo->lastInsertId();
}

function getAllViajes() {
    require("conexion.php");
    $consulta = "SELECT v.*, 
                r.nombre as ruta_nombre,
                tt.nombre as tipo_transporte,
                e.nombre as empresa_nombre,
                to1.nombre as origen_nombre,
                td1.nombre as destino_nombre
                FROM viaje_transporte v
                INNER JOIN ruta_transporte r ON v.idRuta = r.idRuta
                INNER JOIN tipo_transporte tt ON r.idTipoTransporte = tt.idTipoTransporte
                LEFT JOIN empresa_transporte e ON r.idEmpresa = e.idEmpresa
                LEFT JOIN ruta_paradas rp_desde ON v.idDesdeParada = rp_desde.idRutaParada
                LEFT JOIN terminal_transporte to1 ON rp_desde.idTerminal = to1.idTerminal
                LEFT JOIN ruta_paradas rp_hasta ON v.idHastaParada = rp_hasta.idRutaParada
                LEFT JOIN terminal_transporte td1 ON rp_hasta.idTerminal = td1.idTerminal
                ORDER BY v.fecha DESC, v.hora_salida DESC";
    $comando = $pdo->query($consulta);
    return $comando->fetchAll(PDO::FETCH_ASSOC);
}

function updateViaje($idViaje, $datos) {
    require("conexion.php");
    $consulta = "UPDATE viaje_transporte SET 
                idRuta = :idRuta,
                idModelo = :idModelo,
                idDesdeParada = :idDesdeParada,
                idHastaParada = :idHastaParada,
                fecha = :fecha,
                hora_salida = :hora_salida,
                hora_llegada = :hora_llegada,
                asientos_totales = :asientos_totales,
                asientos_disponibles = :asientos_disponibles,
                numero_vuelo_bus = :numero_vuelo_bus,
                observaciones = :observaciones,
                habilitado = :habilitado
                WHERE idViaje = :idViaje";
    $comando = $pdo->prepare($consulta);
    $datos['idViaje'] = $idViaje;
    if (!isset($datos['idModelo'])) {
        $datos['idModelo'] = null;
    }
    if (!isset($datos['idDesdeParada'])) {
        $datos['idDesdeParada'] = null;
    }
    if (!isset($datos['idHastaParada'])) {
        $datos['idHastaParada'] = null;
    }
    return $comando->execute($datos);
}

function deleteViaje($idViaje) {
    require("conexion.php");
    
    // Verificar si tiene reservas
    $consultaCheck = "SELECT COUNT(*) as total FROM reserva_transporte WHERE idViaje = :idViaje";
    $cmdCheck = $pdo->prepare($consultaCheck);
    $cmdCheck->execute(['idViaje' => $idViaje]);
    $resultado = $cmdCheck->fetch(PDO::FETCH_ASSOC);
    
    if ($resultado['total'] > 0) {
        // Tiene reservas, solo deshabilitar
        $consulta = "UPDATE viaje_transporte SET habilitado = 0 WHERE idViaje = :idViaje";
        $comando = $pdo->prepare($consulta);
        return $comando->execute(['idViaje' => $idViaje]);
    } else {
        // No tiene reservas, eliminar físicamente
        $consulta = "DELETE FROM viaje_transporte WHERE idViaje = :idViaje";
        $comando = $pdo->prepare($consulta);
        return $comando->execute(['idViaje' => $idViaje]);
    }
}

function getViajesDisponibles($idRuta, $fecha_desde = null) {
    require("conexion.php");
    $consulta = "SELECT v.* FROM viaje_transporte v 
                WHERE v.idRuta = :idRuta 
                AND v.habilitado = 1
                AND v.asientos_disponibles > 0";
    
    $params = ['idRuta' => $idRuta];
    
    if ($fecha_desde) {
        $consulta .= " AND v.fecha >= :fecha_desde";
        $params['fecha_desde'] = $fecha_desde;
    }
    
    $consulta .= " ORDER BY v.fecha ASC, v.hora_salida ASC";
    
    $comando = $pdo->prepare($consulta);
    $comando->execute($params);
    return $comando->fetchAll(PDO::FETCH_ASSOC);
}

function updateDisponibilidad($idViaje, $cantidad) {
    require("conexion.php");
    $consulta = "UPDATE viaje_transporte 
                SET asientos_disponibles = asientos_disponibles - :cantidad 
                WHERE idViaje = :idViaje";
    $comando = $pdo->prepare($consulta);
    return $comando->execute(['cantidad' => $cantidad, 'idViaje' => $idViaje]);
}

// ========================================
// TARIFAS DE VIAJES
// ========================================

function getTarifasViaje($idViaje) {
    require("conexion.php");
    $consulta = "SELECT vt.*, 
                 rp_o.idTerminal as origen_terminal_id, t_o.nombre as origen_nombre, t_o.ciudad as origen_ciudad,
                 rp_d.idTerminal as destino_terminal_id, t_d.nombre as destino_nombre, t_d.ciudad as destino_ciudad,
                 tb.nombre as tipo_butaca_nombre, tb.icono as tipo_butaca_icono,
                 tt.nombre as tipo_tarifa_nombre,
                 m.*
                 FROM viaje_tarifa vt
                 INNER JOIN ruta_paradas rp_o ON vt.idOrigenParada = rp_o.idRutaParada
                 INNER JOIN ruta_paradas rp_d ON vt.idDestinoParada = rp_d.idRutaParada
                 INNER JOIN terminal_transporte t_o ON rp_o.idTerminal = t_o.idTerminal
                 INNER JOIN terminal_transporte t_d ON rp_d.idTerminal = t_d.idTerminal
                 INNER JOIN tipo_butaca tb ON vt.idTipoButaca = tb.idTipoButaca
                 INNER JOIN tipo_tarifa_pasajero tt ON vt.idTipoTarifa = tt.idTipoTarifa
                 INNER JOIN moneda m ON vt.idMoneda = m.idMoneda
                 WHERE vt.idViaje = :idViaje AND vt.habilitado = 1
                 ORDER BY rp_o.orden, rp_d.orden, tb.idTipoButaca, tt.idTipoTarifa";
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

// ========================================
// CLASES DE SERVICIO POR VIAJE
// ========================================

function getViajeClasesServicio($idViaje) {
    require("conexion.php");
    $consulta = "SELECT 
                    vcs.idViajeClase,
                    vcs.idViaje,
                    vcs.idClaseServicio,
                    vcs.asientos_totales,
                    vcs.asientos_disponibles,
                    vcs.precio_base,
                    vcs.idMoneda,
                    vcs.comisiona,
                    vcs.habilitado,
                    cs.nombre AS nombre_clase,
                    cs.descripcion AS descripcion_clase,
                    cs.orden AS orden_clase,
                    m.Symbol AS moneda_simbolo
                 FROM viaje_clase_servicio vcs
                 INNER JOIN clase_servicio_transporte cs ON vcs.idClaseServicio = cs.idClaseServicio
                 INNER JOIN moneda m ON vcs.idMoneda = m.idMoneda
                 WHERE vcs.idViaje = :idViaje
                 ORDER BY cs.orden";
    $comando = $pdo->prepare($consulta);
    $comando->execute(['idViaje' => $idViaje]);
    return $comando->fetchAll(PDO::FETCH_ASSOC);
}

function updateViajeClase($idViajeClase, $datos) {
    require("conexion.php");
    
    $set = [];
    foreach ($datos as $key => $value) {
        if (in_array($key, ['asientos_totales', 'asientos_disponibles', 'precio_base', 'idMoneda', 'comisiona', 'habilitado'])) {
            $set[] = "$key = :$key";
        }
    }
    
    if (empty($set)) return false;
    
    $consulta = "UPDATE viaje_clase_servicio SET " . implode(", ", $set) . " WHERE idViajeClase = :idViajeClase";
    $datos['idViajeClase'] = $idViajeClase;
    
    $comando = $pdo->prepare($consulta);
    return $comando->execute($datos);
}

function deleteViajeClase($idViajeClase) {
    require("conexion.php");
    
    // Verificar si tiene reservas
    $consulta = "SELECT COUNT(*) as count FROM reserva_transporte_clase WHERE idViajeClase = ?";
    $comando = $pdo->prepare($consulta);
    $comando->execute([$idViajeClase]);
    $resultado = $comando->fetch();
    
    if ($resultado['count'] > 0) {
        // Si tiene reservas, solo marcar como deshabilitado
        $consulta = "UPDATE viaje_clase_servicio SET habilitado = 0 WHERE idViajeClase = ?";
    } else {
        // Si no tiene reservas, eliminar completamente
        $consulta = "DELETE FROM viaje_clase_servicio WHERE idViajeClase = ?";
    }
    
    $comando = $pdo->prepare($consulta);
    return $comando->execute([$idViajeClase]);
}

/**
 * Obtener todas las clases de servicio por tipo de transporte
 * @param int $idTipoTransporte
 * @return array
 */
function getClasesPorTipo($idTipoTransporte) {
    require("conexion.php");
    $consulta = "SELECT * FROM clase_servicio_transporte 
                 WHERE idTipoTransporte = :idTipoTransporte 
                 AND habilitado = 1 
                 ORDER BY orden ASC";
    $comando = $pdo->prepare($consulta);
    $comando->execute(['idTipoTransporte' => $idTipoTransporte]);
    return $comando->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Obtener clases de servicio disponibles para un vehículo específico
 * Basado en el modelo del vehículo y las clases configuradas en modelo_clases
 * @param int $idVehiculo
 * @return array
 */
function getClasesPorVehiculo($idVehiculo) {
    require("conexion.php");
    
    $consulta = "SELECT c.*, mc.capacidad_clase, mc.orden as orden_modelo
                 FROM clase_servicio_transporte c
                 INNER JOIN modelo_clases mc ON c.idClaseServicio = mc.idClaseServicio
                 INNER JOIN vehiculo_transporte v ON mc.idModelo = v.idModelo
                 WHERE v.idVehiculo = :idVehiculo 
                 AND c.habilitado = 1 
                 AND mc.habilitado = 1
                 ORDER BY mc.orden ASC, c.orden ASC";
    
    $comando = $pdo->prepare($consulta);
    $comando->execute(['idVehiculo' => $idVehiculo]);
    $clases = $comando->fetchAll(PDO::FETCH_ASSOC);
    
    // Si el vehículo no tiene clases configuradas, devolver todas del tipo de transporte
    if (empty($clases)) {
        $consultaTipo = "SELECT tt.idTipoTransporte 
                         FROM vehiculo_transporte v
                         INNER JOIN modelo_vehiculo_transporte m ON v.idModelo = m.idModelo
                         WHERE v.idVehiculo = :idVehiculo";
        $cmdTipo = $pdo->prepare($consultaTipo);
        $cmdTipo->execute(['idVehiculo' => $idVehiculo]);
        $tipo = $cmdTipo->fetch(PDO::FETCH_ASSOC);
        
        if ($tipo) {
            return getClasesPorTipo($tipo['idTipoTransporte']);
        }
    }
    
    return $clases;
}

/**
 * Obtener clases de servicio disponibles para un viaje específico
 * Usa el modelo asignado al viaje
 * @param int $idViaje
 * @return array
 */
function getClasesPorViaje($idViaje) {
    require("conexion.php");
    
    $consulta = "SELECT idModelo FROM viaje_transporte WHERE idViaje = :idViaje";
    $comando = $pdo->prepare($consulta);
    $comando->execute(['idViaje' => $idViaje]);
    $viaje = $comando->fetch(PDO::FETCH_ASSOC);
    
    if ($viaje && $viaje['idModelo']) {
        // Obtener clases del modelo
        $consultaClases = "SELECT c.*, mc.capacidad_clase, mc.orden as orden_modelo
                           FROM clase_servicio_transporte c
                           INNER JOIN modelo_clases mc ON c.idClaseServicio = mc.idClaseServicio
                           WHERE mc.idModelo = :idModelo 
                           AND c.habilitado = 1 
                           AND mc.habilitado = 1
                           ORDER BY mc.orden ASC, c.orden ASC";
        
        $cmdClases = $pdo->prepare($consultaClases);
        $cmdClases->execute(['idModelo' => $viaje['idModelo']]);
        $clases = $cmdClases->fetchAll(PDO::FETCH_ASSOC);
        
        // Si el modelo no tiene clases configuradas, devolver todas del tipo de transporte
        if (empty($clases)) {
            $consultaTipo = "SELECT tipo_transporte 
                             FROM modelo_vehiculo_transporte 
                             WHERE idModelo = :idModelo";
            $cmdTipo = $pdo->prepare($consultaTipo);
            $cmdTipo->execute(['idModelo' => $viaje['idModelo']]);
            $tipo = $cmdTipo->fetch(PDO::FETCH_ASSOC);
            
            if ($tipo) {
                return getClasesPorTipo($tipo['tipo_transporte']);
            }
        }
        
        return $clases;
    }
    
    return [];
}

function insertViajeClase($datos) {
    require("conexion.php");
    
    $consulta = "INSERT INTO viaje_clase_servicio 
                 (idViaje, idClaseServicio, asientos_totales, asientos_disponibles, precio_base, idMoneda, comisiona, habilitado)
                 VALUES (:idViaje, :idClaseServicio, :asientos_totales, :asientos_disponibles, :precio_base, :idMoneda, :comisiona, 1)";
    
    $comando = $pdo->prepare($consulta);
    $resultado = $comando->execute($datos);
    
    if ($resultado) {
        $idViajeClase = $pdo->lastInsertId();
        
        // Crear tarifas por defecto para esta clase
        $stmt = $pdo->prepare("SELECT precio_base, idMoneda FROM viaje_clase_servicio WHERE idViajeClase = ?");
        $stmt->execute([$idViajeClase]);
        $clase = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $precioBase = $clase['precio_base'];
        $idMoneda = $clase['idMoneda'];
        
        // Adulto (idTipoTarifa = 1)
        $pdo->prepare("INSERT INTO viaje_clase_tarifa (idViajeClase, idTipoTarifa, precio, idMoneda, comisiona) VALUES (?, 1, ?, ?, 1)")
            ->execute([$idViajeClase, $precioBase, $idMoneda]);
        
        // Niño (idTipoTarifa = 2) - 50% descuento
        $pdo->prepare("INSERT INTO viaje_clase_tarifa (idViajeClase, idTipoTarifa, precio, idMoneda, comisiona) VALUES (?, 2, ?, ?, 1)")
            ->execute([$idViajeClase, $precioBase * 0.5, $idMoneda]);
        
        // Bebé (idTipoTarifa = 4) - gratis
        $pdo->prepare("INSERT INTO viaje_clase_tarifa (idViajeClase, idTipoTarifa, precio, idMoneda, comisiona) VALUES (?, 4, ?, ?, 1)")
            ->execute([$idViajeClase, 0, $idMoneda]);
        
        return $idViajeClase;
    }
    
    return false;
}

function getViajeClaseTarifas($idViajeClase) {
    require("conexion.php");
    
    $consulta = "SELECT 
                    vct.idViajeClaseTarifa,
                    vct.idViajeClase,
                    vct.idTipoTarifa,
                    vct.precio,
                    vct.idMoneda,
                    vct.comisiona,
                    tt.nombre AS nombre_tipo_tarifa
                 FROM viaje_clase_tarifa vct
                 INNER JOIN tipos_tarifa tt ON vct.idTipoTarifa = tt.idTipoTarifa
                 WHERE vct.idViajeClase = :idViajeClase
                 ORDER BY tt.idTipoTarifa";
    
    $comando = $pdo->prepare($consulta);
    $comando->execute(['idViajeClase' => $idViajeClase]);
    return $comando->fetchAll(PDO::FETCH_ASSOC);
}

function updateViajeClaseTarifa($idViajeClaseTarifa, $precio) {
    require("conexion.php");
    
    $consulta = "UPDATE viaje_clase_tarifa SET precio = :precio WHERE idViajeClaseTarifa = :idViajeClaseTarifa";
    $comando = $pdo->prepare($consulta);
    return $comando->execute(['precio' => $precio, 'idViajeClaseTarifa' => $idViajeClaseTarifa]);
}

// ========================================
// MODELOS DE VEHÍCULOS (TEMPLATES)
// ========================================

function getAllModelos() {
    require("conexion.php");
    $consulta = "SELECT m.*, t.nombre as tipo_nombre
                 FROM modelo_vehiculo_transporte m
                 LEFT JOIN tipo_transporte t ON m.tipo_transporte = t.idTipoTransporte
                 WHERE m.habilitado = 1
                 ORDER BY m.tipo_transporte, m.nombre";
    $comando = $pdo->prepare($consulta);
    $comando->execute();
    return $comando->fetchAll(PDO::FETCH_ASSOC);
}

function getModelo($idModelo) {
    require("conexion.php");
    $consulta = "SELECT * FROM modelo_vehiculo_transporte WHERE idModelo = :idModelo";
    $comando = $pdo->prepare($consulta);
    $comando->execute(['idModelo' => $idModelo]);
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    return !empty($resultado) ? $resultado[0] : null;
}

/**
 * Calcula la capacidad real contando solo asientos (tipo "1")
 * Ignora: TV, Baño, Puerta, Volante, Parabrisas, Pasillo, etc.
 */
function calcularCapacidadReal($distribucionJson) {
    if (!$distribucionJson) return 0;
    
    $dist = json_decode($distribucionJson, true);
    if (!isset($dist['pisos']) || !is_array($dist['pisos'])) return 0;
    
    $totalAsientos = 0;
    foreach ($dist['pisos'] as $piso) {
        if (isset($piso['asientos']) && is_array($piso['asientos'])) {
            foreach ($piso['asientos'] as $fila) {
                if (is_array($fila)) {
                    foreach ($fila as $celda) {
                        // Contar solo celdas con valor 1 (asiento regular)
                        if ($celda === 1 || $celda === '1') {
                            $totalAsientos++;
                        }
                    }
                }
            }
        }
    }
    return $totalAsientos;
}

function insertModelo($datos) {
    require("conexion.php");
    
    $consulta = "INSERT INTO modelo_vehiculo_transporte 
                 (nombre, tipo_transporte, capacidad_total, filas, columnas, descripcion, distribucion_json, habilitado)
                 VALUES (:nombre, :tipo, :capacidad, :filas, :columnas, :descripcion, :distribucion, :habilitado)";
    
    $comando = $pdo->prepare($consulta);
    $resultado = $comando->execute([
        'nombre' => $datos['nombre'] ?? '',
        'tipo' => $datos['tipo_transporte'] ?? 1,
        'capacidad' => $datos['capacidad_total'] ?? 0,
        'filas' => $datos['filas'] ?? 0,
        'columnas' => $datos['columnas'] ?? 0,
        'descripcion' => $datos['descripcion'] ?? '',
        'distribucion' => $datos['distribucion_json'] ?? '{}',
        'habilitado' => $datos['habilitado'] ?? 1
    ]);
    
    return $resultado ? $pdo->lastInsertId() : false;
}

function updateModelo($idModelo, $datos) {
    require("conexion.php");
    
    $setParts = [];
    $params = ['idModelo' => $idModelo];
    
    if (isset($datos['nombre'])) { $setParts[] = "nombre = :nombre"; $params['nombre'] = $datos['nombre']; }
    if (isset($datos['tipo_transporte'])) { $setParts[] = "tipo_transporte = :tipo"; $params['tipo'] = $datos['tipo_transporte']; }
    if (isset($datos['capacidad_total'])) { $setParts[] = "capacidad_total = :capacidad"; $params['capacidad'] = $datos['capacidad_total']; }
    if (isset($datos['filas'])) { $setParts[] = "filas = :filas"; $params['filas'] = $datos['filas']; }
    if (isset($datos['columnas'])) { $setParts[] = "columnas = :columnas"; $params['columnas'] = $datos['columnas']; }
    if (isset($datos['descripcion'])) { $setParts[] = "descripcion = :descripcion"; $params['descripcion'] = $datos['descripcion']; }
    if (isset($datos['distribucion_json'])) { $setParts[] = "distribucion_json = :distribucion"; $params['distribucion'] = $datos['distribucion_json']; }
    if (isset($datos['habilitado'])) { $setParts[] = "habilitado = :habilitado"; $params['habilitado'] = $datos['habilitado']; }
    
    if (empty($setParts)) return false;
    
    $consulta = "UPDATE modelo_vehiculo_transporte SET " . implode(", ", $setParts) . " WHERE idModelo = :idModelo";
    $comando = $pdo->prepare($consulta);
    return $comando->execute($params);
}

// ========================================
// VEHÍCULOS (INSTANCIAS)
// ========================================

function getAllVehiculos() {
    require("conexion.php");
    $consulta = "SELECT v.*, m.nombre as modelo_nombre, m.capacidad_total
                 FROM vehiculo_transporte v
                 LEFT JOIN modelo_vehiculo_transporte m ON v.idModelo = m.idModelo
                 WHERE v.estado != 'retirado'
                 ORDER BY m.nombre, v.patente";
    $comando = $pdo->prepare($consulta);
    $comando->execute();
    return $comando->fetchAll(PDO::FETCH_ASSOC);
}

function getVehiculo($idVehiculo) {
    require("conexion.php");
    $consulta = "SELECT v.*, m.nombre as modelo_nombre, m.filas, m.columnas, m.capacidad_total
                 FROM vehiculo_transporte v
                 LEFT JOIN modelo_vehiculo_transporte m ON v.idModelo = m.idModelo
                 WHERE v.idVehiculo = :idVehiculo";
    $comando = $pdo->prepare($consulta);
    $comando->execute(['idVehiculo' => $idVehiculo]);
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    return !empty($resultado) ? $resultado[0] : null;
}

function getVehiculosByModelo($idModelo) {
    require("conexion.php");
    $consulta = "SELECT * FROM vehiculo_transporte 
                 WHERE idModelo = :idModelo AND estado != 'retirado'
                 ORDER BY patente";
    $comando = $pdo->prepare($consulta);
    $comando->execute(['idModelo' => $idModelo]);
    return $comando->fetchAll(PDO::FETCH_ASSOC);
}

function insertVehiculo($datos) {
    require("conexion.php");
    
    $consulta = "INSERT INTO vehiculo_transporte 
                 (idModelo, patente, idEmpresa, estado, fecha_alta, observaciones)
                 VALUES (:idModelo, :patente, :idEmpresa, :estado, :fecha_alta, :observaciones)";
    
    $comando = $pdo->prepare($consulta);
    $resultado = $comando->execute([
        'idModelo' => $datos['idModelo'] ?? 1,
        'patente' => strtoupper($datos['patente'] ?? ''),
        'idEmpresa' => $datos['idEmpresa'] ?? null,
        'estado' => $datos['estado'] ?? 'activo',
        'fecha_alta' => $datos['fecha_alta'] ?? date('Y-m-d'),
        'observaciones' => $datos['observaciones'] ?? ''
    ]);
    
    return $resultado ? $pdo->lastInsertId() : false;
}

function updateVehiculo($idVehiculo, $datos) {
    require("conexion.php");
    
    $setParts = [];
    $params = ['idVehiculo' => $idVehiculo];
    
    if (isset($datos['idModelo'])) { $setParts[] = "idModelo = :idModelo"; $params['idModelo'] = $datos['idModelo']; }
    if (isset($datos['patente'])) { $setParts[] = "patente = :patente"; $params['patente'] = strtoupper($datos['patente']); }
    if (isset($datos['estado'])) { $setParts[] = "estado = :estado"; $params['estado'] = $datos['estado']; }
    if (isset($datos['observaciones'])) { $setParts[] = "observaciones = :observaciones"; $params['observaciones'] = $datos['observaciones']; }
    
    if (empty($setParts)) return false;
    
    $consulta = "UPDATE vehiculo_transporte SET " . implode(", ", $setParts) . " WHERE idVehiculo = :idVehiculo";
    $comando = $pdo->prepare($consulta);
    return $comando->execute($params);
}

// ========================================
// ASIENTOS DE VIAJE (MAPA)
// ========================================

function crearMapaAsientos($idViaje, $idVehiculo) {
    require("conexion.php");
    
    $vehiculo = getVehiculo($idVehiculo);
    if (!$vehiculo) return false;
    
    $filas = $vehiculo['filas'];
    $columnas = $vehiculo['columnas'];
    
    // Generar mapa: A1, A2, ..., B1, B2, etc
    $letras = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
    
    for ($f = 0; $f < $filas; $f++) {
        $letra = $letras[$f % count($letras)];
        for ($c = 1; $c <= $columnas; $c++) {
            $numeroAsiento = $letra . $c;
            
            $consulta = "INSERT INTO viaje_asiento (idViaje, idVehiculo, numero_asiento, fila, columna, estado)
                         VALUES (:idViaje, :idVehiculo, :numero_asiento, :fila, :columna, 'disponible')
                         ON DUPLICATE KEY UPDATE estado = 'disponible'";
            
            $comando = $pdo->prepare($consulta);
            $comando->execute([
                'idViaje' => $idViaje,
                'idVehiculo' => $idVehiculo,
                'numero_asiento' => $numeroAsiento,
                'fila' => $f + 1,
                'columna' => $c
            ]);
        }
    }
    
    return true;
}

function getAsientosViaje($idViaje) {
    require("conexion.php");
    $consulta = "SELECT * FROM viaje_asiento 
                 WHERE idViaje = :idViaje
                 ORDER BY fila, columna";
    $comando = $pdo->prepare($consulta);
    $comando->execute(['idViaje' => $idViaje]);
    return $comando->fetchAll(PDO::FETCH_ASSOC);
}

function getAsientosDisponibles($idViaje) {
    require("conexion.php");
    $consulta = "SELECT * FROM viaje_asiento 
                 WHERE idViaje = :idViaje AND estado = 'disponible'
                 ORDER BY fila, columna";
    $comando = $pdo->prepare($consulta);
    $comando->execute(['idViaje' => $idViaje]);
    return $comando->fetchAll(PDO::FETCH_ASSOC);
}

function actualizarAsiento($idViajeAsiento, $datos) {
    require("conexion.php");
    
    $setParts = [];
    $params = ['idViajeAsiento' => $idViajeAsiento];
    
    if (isset($datos['estado'])) { $setParts[] = "estado = :estado"; $params['estado'] = $datos['estado']; }
    if (isset($datos['nombre_pasajero'])) { $setParts[] = "nombre_pasajero = :nombre"; $params['nombre'] = $datos['nombre_pasajero']; }
    if (isset($datos['documento_pasajero'])) { $setParts[] = "documento_pasajero = :documento"; $params['documento'] = $datos['documento_pasajero']; }
    if (isset($datos['idReservaTransporte'])) { $setParts[] = "idReservaTransporte = :idReserva"; $params['idReserva'] = $datos['idReservaTransporte']; }
    if (isset($datos['precio_extra'])) { $setParts[] = "precio_extra = :precio_extra"; $params['precio_extra'] = $datos['precio_extra']; }
    
    if (empty($setParts)) return false;
    
    $consulta = "UPDATE viaje_asiento SET " . implode(", ", $setParts) . " WHERE idViajeAsiento = :idViajeAsiento";
    $comando = $pdo->prepare($consulta);
    return $comando->execute($params);
}

// ========================================
// ========================================
// TARIFAS POR SEGMENTO Y TIPO PASAJERO
// ========================================

function getAllTarifas($idViaje = null) {
    require("conexion.php");
    $consulta = "SELECT t.*, 
                        v.fecha_salida, v.hora_salida,
                        t1.nombre as tipo_pasajero,
                        term_o.nombre as origen_terminal,
                        term_d.nombre as destino_terminal,
                        m.nombre as moneda_simbolo
                 FROM viaje_tarifa t
                 LEFT JOIN viaje_transporte v ON t.idViaje = v.idViaje
                 LEFT JOIN tipo_tarifa_pasajero t1 ON t.idTipoTarifa = t1.idTipoTarifa
                 LEFT JOIN ruta_paradas rp_o ON t.idOrigenParada = rp_o.idRutaParada
                 LEFT JOIN ruta_paradas rp_d ON t.idDestinoParada = rp_d.idRutaParada
                 LEFT JOIN terminal_transporte term_o ON rp_o.idTerminal = term_o.idTerminal
                 LEFT JOIN terminal_transporte term_d ON rp_d.idTerminal = term_d.idTerminal
                 LEFT JOIN moneda m ON t.idMoneda = m.id";
    
    if ($idViaje) {
        $consulta .= " WHERE t.idViaje = :idViaje";
    }
    
    $consulta .= " ORDER BY t.idViaje DESC, t.idTipoTarifa";
    $comando = $pdo->prepare($consulta);
    
    if ($idViaje) {
        $comando->execute(['idViaje' => $idViaje]);
    } else {
        $comando->execute();
    }
    
    return $comando->fetchAll(PDO::FETCH_ASSOC);
}

function getTarifaViaje_Detail($idTarifa) {
    require("conexion.php");
    $consulta = "SELECT t.*, 
                        t1.nombre as tipo_pasajero,
                        term_o.nombre as origen_terminal,
                        term_d.nombre as destino_terminal
                 FROM viaje_tarifa t
                 LEFT JOIN tipo_tarifa_pasajero t1 ON t.idTipoTarifa = t1.idTipoTarifa
                 LEFT JOIN ruta_paradas rp_o ON t.idOrigenParada = rp_o.idRutaParada
                 LEFT JOIN ruta_paradas rp_d ON t.idDestinoParada = rp_d.idRutaParada
                 LEFT JOIN terminal_transporte term_o ON rp_o.idTerminal = term_o.idTerminal
                 LEFT JOIN terminal_transporte term_d ON rp_d.idTerminal = term_d.idTerminal
                 WHERE t.idTarifa = :idTarifa";
    $comando = $pdo->prepare($consulta);
    $comando->execute(['idTarifa' => $idTarifa]);
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    return !empty($resultado) ? $resultado[0] : null;
}



function deleteTarifaViaje_Detail($idTarifa) {
    require("conexion.php");
    $consulta = "DELETE FROM viaje_tarifa WHERE idTarifa = :idTarifa";
    $comando = $pdo->prepare($consulta);
    return $comando->execute(['idTarifa' => $idTarifa]);
}

function getTiposTarifa() {
    require("conexion.php");
    $consulta = "SELECT * FROM tipo_tarifa_pasajero WHERE habilitado = 1 ORDER BY nombre";
    $comando = $pdo->prepare($consulta);
    $comando->execute();
    return $comando->fetchAll(PDO::FETCH_ASSOC);
}

function getParadasParaTarifas($idRuta) {
    require("conexion.php");
    $consulta = "SELECT rp.idRutaParada, rp.orden, t.nombre as terminal_nombre
                 FROM ruta_paradas rp
                 JOIN terminal_transporte t ON rp.idTerminal = t.idTerminal
                 WHERE rp.idRuta = :idRuta
                 ORDER BY rp.orden";
    $comando = $pdo->prepare($consulta);
    $comando->execute(['idRuta' => $idRuta]);
    return $comando->fetchAll(PDO::FETCH_ASSOC);
}

function getAllMonedas() {
    require("conexion.php");
    $consulta = "SELECT idMoneda, CurrencyName, Symbol, CurrencyISO FROM moneda WHERE activado = 1 ORDER BY CurrencyName";
    $comando = $pdo->prepare($consulta);
    $comando->execute();
    return $comando->fetchAll(PDO::FETCH_ASSOC);
}

// ========================================
// PARADAS CUSTOMIZADAS
// ========================================

function getAllParadasCustomizadas() {
    require("conexion.php");
    $consulta = "SELECT * FROM parada_customizada WHERE habilitado=1 ORDER BY ciudad, nombre";
    $comando = $pdo->prepare($consulta);
    $comando->execute();
    return $comando->fetchAll(PDO::FETCH_ASSOC);
}

function getParadaCustomizada($idParadaCustomizada) {
    require("conexion.php");
    $consulta = "SELECT * FROM parada_customizada WHERE idParadaCustomizada = :idParadaCustomizada";
    $comando = $pdo->prepare($consulta);
    $comando->execute(['idParadaCustomizada' => $idParadaCustomizada]);
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    return !empty($resultado) ? $resultado[0] : null;
}

function insertParadaCustomizada($datos) {
    require("conexion.php");
    $consulta = "INSERT INTO parada_customizada 
                (nombre, direccion, ciudad, estado, pais, latitud, longitud, tipo_parada, habilitado) 
                VALUES 
                (:nombre, :direccion, :ciudad, :estado, :pais, :latitud, :longitud, :tipo_parada, :habilitado)";
    
    $comando = $pdo->prepare($consulta);
    $result = $comando->execute([
        ':nombre' => $datos['nombre'],
        ':direccion' => $datos['direccion'] ?? null,
        ':ciudad' => $datos['ciudad'] ?? null,
        ':estado' => $datos['estado'] ?? null,
        ':pais' => $datos['pais'] ?? null,
        ':latitud' => $datos['latitud'] ?? null,
        ':longitud' => $datos['longitud'] ?? null,
        ':tipo_parada' => $datos['tipo_parada'] ?? 'otro',
        ':habilitado' => $datos['habilitado'] ?? 1
    ]);
    
    return $result ? $pdo->lastInsertId() : false;
}

function updateParadaCustomizada($idParadaCustomizada, $datos) {
    require("conexion.php");
    $consulta = "UPDATE parada_customizada 
                SET nombre = :nombre,
                    direccion = :direccion,
                    ciudad = :ciudad,
                    estado = :estado,
                    pais = :pais,
                    latitud = :latitud,
                    longitud = :longitud,
                    tipo_parada = :tipo_parada,
                    habilitado = :habilitado
                WHERE idParadaCustomizada = :idParadaCustomizada";
    
    $comando = $pdo->prepare($consulta);
    return $comando->execute([
        ':idParadaCustomizada' => $idParadaCustomizada,
        ':nombre' => $datos['nombre'],
        ':direccion' => $datos['direccion'] ?? null,
        ':ciudad' => $datos['ciudad'] ?? null,
        ':estado' => $datos['estado'] ?? null,
        ':pais' => $datos['pais'] ?? null,
        ':latitud' => $datos['latitud'] ?? null,
        ':longitud' => $datos['longitud'] ?? null,
        ':tipo_parada' => $datos['tipo_parada'] ?? 'otro',
        ':habilitado' => $datos['habilitado'] ?? 1
    ]);
}

/**
 * Obtener servicios adicionales de un viaje específico
 * @param int $idViaje ID del viaje
 * @return array Lista de servicios adicionales con detalles
 */
function getServiciosAdicionalesViaje($idViaje)
{
    // Usar conexión experimental para transporte
    if (!isset($GLOBALS['pdo_experimental'])) {
        $GLOBALS['pdo_experimental'] = new PDO(
            'mysql:host=localhost;dbname=metelebrasil_experimental;charset=utf8mb4',
            'root',''
        );
        $GLOBALS['pdo_experimental']->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    $pdo = $GLOBALS['pdo_experimental'];
    
    $sql = "SELECT va.*, sa.nombre, sa.nombre_en, sa.nombre_pt, sa.nombre_it,
                   m.CurrencyName AS moneda_nombre, m.Symbol AS moneda_simbolo
            FROM viaje_transporte_adicionales va
            LEFT JOIN servicios_adicionales sa ON va.idServiciosAdicionales = sa.idServiciosAdicionales
            LEFT JOIN moneda m ON va.idMoneda = m.idMoneda
            WHERE va.idViaje = :idViaje
            ORDER BY sa.nombre";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':idViaje' => $idViaje]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Obtener servicios adicionales disponibles (no asignados al viaje)
 * @param int $idViaje ID del viaje
 * @return array Lista de servicios que se pueden agregar
 */
function getServiciosAdicionalesDisponiblesParaViaje($idViaje)
{
    require("conexion.php");
    
    $sql = "SELECT sa.* FROM servicios_adicionales sa
            WHERE sa.habilitado = 1
            AND sa.idServiciosAdicionales NOT IN (
                SELECT idServiciosAdicionales FROM viaje_transporte_adicionales 
                WHERE idViaje = :idViaje
            )
            ORDER BY sa.nombre";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':idViaje' => $idViaje]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Agregar servicio adicional a un viaje
 * @param int $idViaje ID del viaje
 * @param int $idServiciosAdicionales ID del servicio
 * @param float $precio Precio del servicio
 * @param int $idMoneda ID de la moneda
 * @return bool Éxito o fallo
 */
function setServicioAdicionalViaje($idViaje, $idServiciosAdicionales, $precio, $idMoneda)
{
    require("conexion.php");
    
    $sql = "INSERT INTO viaje_transporte_adicionales (idViaje, idServiciosAdicionales, precio, idMoneda)
            VALUES (:idViaje, :idServiciosAdicionales, :precio, :idMoneda)
            ON DUPLICATE KEY UPDATE precio = :precio, idMoneda = :idMoneda";
    
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        ':idViaje' => $idViaje,
        ':idServiciosAdicionales' => $idServiciosAdicionales,
        ':precio' => $precio,
        ':idMoneda' => $idMoneda
    ]);
}

/**
 * Eliminar servicio adicional de un viaje
 * @param int $idViajeAdicional ID del registro en viaje_transporte_adicionales
 * @return bool Éxito o fallo
 */
function deleteServicioAdicionalViaje($idViajeAdicional)
{
    require("conexion.php");
    
    $sql = "DELETE FROM viaje_transporte_adicionales WHERE idViajeAdicional = :idViajeAdicional";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([':idViajeAdicional' => $idViajeAdicional]);
}

/**
 * Actualizar precio y moneda de un servicio adicional en viaje
 * @param int $idViajeAdicional ID del registro
 * @param float $precio Nuevo precio
 * @param int $idMoneda Nueva moneda
 * @return bool Éxito o fallo
 */
function updateServicioAdicionalViaje($idViajeAdicional, $precio, $idMoneda)
{
    require("conexion.php");
    
    $sql = "UPDATE viaje_transporte_adicionales SET precio = :precio, idMoneda = :idMoneda 
            WHERE idViajeAdicional = :idViajeAdicional";
    
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        ':precio' => $precio,
        ':idMoneda' => $idMoneda,
        ':idViajeAdicional' => $idViajeAdicional
    ]);
}

// =====================================================
// SISTEMA DE SEGMENTOS - Precios por Tramo
// =====================================================

/**
 * Obtener todas las paradas origen disponibles de una ruta
 * @param int $idRuta
 * @return array
 */
function getOrigenesDisponiblesRuta($idRuta) {
    require("conexion.php");
    
    $consulta = "SELECT 
                    rp.idRutaParada,
                    rp.idTerminal,
                    rp.orden,
                    rp.tiempo_desde_inicio,
                    t.nombre as nombre_terminal,
                    t.ciudad,
                    t.estado,
                    t.pais
                 FROM ruta_paradas rp
                 JOIN terminal_transporte t ON rp.idTerminal = t.idTerminal
                 WHERE rp.idRuta = :idRuta 
                 AND rp.es_origen = 1
                 ORDER BY rp.orden ASC";
    
    $comando = $pdo->prepare($consulta);
    $comando->execute(['idRuta' => $idRuta]);
    return $comando->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Obtener paradas destino disponibles según origen seleccionado
 * @param int $idRuta
 * @param int $idOrigenParada idRutaParada del origen
 * @return array
 */
function getDestinosDisponibles($idRuta, $idOrigenParada) {
    require("conexion.php");
    
    // Obtener el orden de la parada origen
    $consultaOrden = "SELECT orden FROM ruta_paradas WHERE idRutaParada = :idOrigenParada";
    $cmdOrden = $pdo->prepare($consultaOrden);
    $cmdOrden->execute(['idOrigenParada' => $idOrigenParada]);
    $ordenOrigen = $cmdOrden->fetchColumn();
    
    if (!$ordenOrigen) {
        return [];
    }
    
    // Obtener destinos posteriores al origen
    $consulta = "SELECT 
                    rp.idRutaParada,
                    rp.idTerminal,
                    rp.orden,
                    rp.tiempo_desde_inicio,
                    t.nombre as nombre_terminal,
                    t.ciudad,
                    t.estado,
                    t.pais
                 FROM ruta_paradas rp
                 JOIN terminal_transporte t ON rp.idTerminal = t.idTerminal
                 WHERE rp.idRuta = :idRuta 
                 AND rp.es_destino = 1
                 AND rp.orden > :ordenOrigen
                 ORDER BY rp.orden ASC";
    
    $comando = $pdo->prepare($consulta);
    $comando->execute([
        'idRuta' => $idRuta,
        'ordenOrigen' => $ordenOrigen
    ]);
    return $comando->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Obtener todos los segmentos origen-destino posibles de una ruta
 * @param int $idRuta
 * @return array
 */
function getSegmentosPosiblesRuta($idRuta) {
    require("conexion.php");
    
    $consulta = "SELECT 
                    rp_origen.idRutaParada as idOrigenParada,
                    rp_destino.idRutaParada as idDestinoParada,
                    t_origen.nombre as nombre_origen,
                    t_destino.nombre as nombre_destino,
                    rp_origen.orden as orden_origen,
                    rp_destino.orden as orden_destino
                 FROM ruta_paradas rp_origen
                 JOIN terminal_transporte t_origen ON rp_origen.idTerminal = t_origen.idTerminal
                 CROSS JOIN ruta_paradas rp_destino
                 JOIN terminal_transporte t_destino ON rp_destino.idTerminal = t_destino.idTerminal
                 WHERE rp_origen.idRuta = :idRuta
                 AND rp_destino.idRuta = :idRuta
                 AND rp_origen.es_origen = 1
                 AND rp_destino.es_destino = 1
                 AND rp_destino.orden > rp_origen.orden
                 ORDER BY rp_origen.orden, rp_destino.orden";
    
    $comando = $pdo->prepare($consulta);
    $comando->execute(['idRuta' => $idRuta]);
    return $comando->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Obtener precios de un segmento específico en un viaje
 * @param int $idViaje
 * @param int $idOrigenParada
 * @param int $idDestinoParada
 * @return array
 */
function getPreciosSegmento($idViaje, $idOrigenParada, $idDestinoParada) {
    require("conexion.php");
    
    $consulta = "SELECT 
                    vsp.*,
                    cs.nombre as nombre_clase,
                    cs.descripcion as descripcion_clase,
                    m.Symbol as simbolo_moneda,
                    t_origen.nombre as nombre_origen,
                    t_destino.nombre as nombre_destino
                 FROM viaje_segmento_precio vsp
                 JOIN clase_servicio_transporte cs ON vsp.idClaseServicio = cs.idClaseServicio
                 JOIN moneda m ON vsp.idMoneda = m.idMoneda
                 JOIN ruta_paradas rp_origen ON vsp.idOrigenParada = rp_origen.idRutaParada
                 JOIN terminal_transporte t_origen ON rp_origen.idTerminal = t_origen.idTerminal
                 JOIN ruta_paradas rp_destino ON vsp.idDestinoParada = rp_destino.idRutaParada
                 JOIN terminal_transporte t_destino ON rp_destino.idTerminal = t_destino.idTerminal
                 WHERE vsp.idViaje = :idViaje
                 AND vsp.idOrigenParada = :idOrigenParada
                 AND vsp.idDestinoParada = :idDestinoParada
                 AND vsp.habilitado = 1
                 ORDER BY vsp.precio ASC";
    
    $comando = $pdo->prepare($consulta);
    $comando->execute([
        'idViaje' => $idViaje,
        'idOrigenParada' => $idOrigenParada,
        'idDestinoParada' => $idDestinoParada
    ]);
    return $comando->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Obtener todos los precios de segmentos de un viaje
 * @param int $idViaje
 * @return array
 */
function getAllPreciosSegmentosViaje($idViaje) {
    require("conexion.php");
    
    $consulta = "SELECT 
                    vsp.*,
                    cs.nombre as nombre_clase,
                    cs.orden as orden_clase,
                    t_origen.nombre as nombre_origen,
                    t_destino.nombre as nombre_destino,
                    rp_origen.orden as orden_origen,
                    rp_destino.orden as orden_destino,
                    m.Symbol as simbolo_moneda
                 FROM viaje_segmento_precio vsp
                 JOIN clase_servicio_transporte cs ON vsp.idClaseServicio = cs.idClaseServicio
                 JOIN moneda m ON vsp.idMoneda = m.idMoneda
                 JOIN ruta_paradas rp_origen ON vsp.idOrigenParada = rp_origen.idRutaParada
                 JOIN terminal_transporte t_origen ON rp_origen.idTerminal = t_origen.idTerminal
                 JOIN ruta_paradas rp_destino ON vsp.idDestinoParada = rp_destino.idRutaParada
                 JOIN terminal_transporte t_destino ON rp_destino.idTerminal = t_destino.idTerminal
                 WHERE vsp.idViaje = :idViaje
                 ORDER BY rp_origen.orden, rp_destino.orden, cs.orden";
    
    $comando = $pdo->prepare($consulta);
    $comando->execute(['idViaje' => $idViaje]);
    return $comando->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Insertar precio de segmento
 * @param array $datos
 * @return bool|int ID del segmento insertado o false
 */
function insertPrecioSegmento($datos) {
    require("conexion.php");
    
    // Validar que origen sea diferente de destino
    if ($datos['idOrigenParada'] == $datos['idDestinoParada']) {
        return false;
    }
    
    // Validar que el destino sea posterior al origen
    if (!validarOrdenSegmento($datos['idOrigenParada'], $datos['idDestinoParada'])) {
        return false;
    }
    
    $consulta = "INSERT INTO viaje_segmento_precio 
                 (idViaje, idOrigenParada, idDestinoParada, idClaseServicio, 
                  precio, idMoneda, asientos_disponibles, comisiona, habilitado)
                 VALUES 
                 (:idViaje, :idOrigenParada, :idDestinoParada, :idClaseServicio,
                  :precio, :idMoneda, :asientos_disponibles, :comisiona, :habilitado)";
    
    $comando = $pdo->prepare($consulta);
    
    $resultado = $comando->execute([
        'idViaje' => $datos['idViaje'],
        'idOrigenParada' => $datos['idOrigenParada'],
        'idDestinoParada' => $datos['idDestinoParada'],
        'idClaseServicio' => $datos['idClaseServicio'],
        'precio' => $datos['precio'],
        'idMoneda' => $datos['idMoneda'],
        'asientos_disponibles' => $datos['asientos_disponibles'] ?? 0,
        'comisiona' => $datos['comisiona'] ?? 1,
        'habilitado' => $datos['habilitado'] ?? 1
    ]);
    
    return $resultado ? $pdo->lastInsertId() : false;
}

/**
 * Actualizar precio de segmento
 * @param int $idSegmentoPrecio
 * @param array $datos
 * @return bool
 */
function updatePrecioSegmento($idSegmentoPrecio, $datos) {
    require("conexion.php");
    
    $setParts = [];
    $params = ['idSegmentoPrecio' => $idSegmentoPrecio];
    
    if (isset($datos['precio'])) { 
        $setParts[] = "precio = :precio"; 
        $params['precio'] = $datos['precio']; 
    }
    if (isset($datos['asientos_disponibles'])) { 
        $setParts[] = "asientos_disponibles = :asientos_disponibles"; 
        $params['asientos_disponibles'] = $datos['asientos_disponibles']; 
    }
    if (isset($datos['idMoneda'])) { 
        $setParts[] = "idMoneda = :idMoneda"; 
        $params['idMoneda'] = $datos['idMoneda']; 
    }
    if (isset($datos['comisiona'])) { 
        $setParts[] = "comisiona = :comisiona"; 
        $params['comisiona'] = $datos['comisiona']; 
    }
    if (isset($datos['habilitado'])) { 
        $setParts[] = "habilitado = :habilitado"; 
        $params['habilitado'] = $datos['habilitado']; 
    }
    
    if (empty($setParts)) return false;
    
    $consulta = "UPDATE viaje_segmento_precio SET " . implode(", ", $setParts) . 
                " WHERE idSegmentoPrecio = :idSegmentoPrecio";
    
    $comando = $pdo->prepare($consulta);
    return $comando->execute($params);
}

/**
 * Eliminar precio de segmento
 * @param int $idSegmentoPrecio
 * @return bool
 */
function deletePrecioSegmento($idSegmentoPrecio) {
    require("conexion.php");
    
    $consulta = "DELETE FROM viaje_segmento_precio WHERE idSegmentoPrecio = :idSegmentoPrecio";
    $comando = $pdo->prepare($consulta);
    return $comando->execute(['idSegmentoPrecio' => $idSegmentoPrecio]);
}

/**
 * Validar que el orden de destino sea posterior al origen
 * @param int $idOrigenParada
 * @param int $idDestinoParada
 * @return bool
 */
function validarOrdenSegmento($idOrigenParada, $idDestinoParada) {
    require("conexion.php");
    
    $consulta = "SELECT 
                    rp_origen.orden as orden_origen,
                    rp_destino.orden as orden_destino
                 FROM ruta_paradas rp_origen
                 CROSS JOIN ruta_paradas rp_destino
                 WHERE rp_origen.idRutaParada = :idOrigenParada
                 AND rp_destino.idRutaParada = :idDestinoParada
                 AND rp_origen.idRuta = rp_destino.idRuta";
    
    $comando = $pdo->prepare($consulta);
    $comando->execute([
        'idOrigenParada' => $idOrigenParada,
        'idDestinoParada' => $idDestinoParada
    ]);
    
    $resultado = $comando->fetch(PDO::FETCH_ASSOC);
    
    if (!$resultado) return false;
    
    return $resultado['orden_destino'] > $resultado['orden_origen'];
}

/**
 * Buscar viajes disponibles por segmento y fecha
 * @param int $idTerminalOrigen
 * @param int $idTerminalDestino
 * @param string $fecha YYYY-MM-DD
 * @return array
 */
function buscarViajesPorSegmento($idTerminalOrigen, $idTerminalDestino, $fecha) {
    require("conexion.php");
    
    $consulta = "SELECT DISTINCT
                    v.*,
                    r.nombre as nombre_ruta,
                    r.distancia_km,
                    r.duracion_estimada,
                    t_origen.nombre as nombre_terminal_origen,
                    t_destino.nombre as nombre_terminal_destino,
                    e.nombre as nombre_empresa,
                    e.logo as logo_empresa
                 FROM viaje_transporte v
                 JOIN ruta_transporte r ON v.idRuta = r.idRuta
                 JOIN ruta_paradas rp_origen ON r.idRuta = rp_origen.idRuta
                 JOIN ruta_paradas rp_destino ON r.idRuta = rp_destino.idRuta
                 JOIN terminal_transporte t_origen ON rp_origen.idTerminal = t_origen.idTerminal
                 JOIN terminal_transporte t_destino ON rp_destino.idTerminal = t_destino.idTerminal
                 LEFT JOIN empresa_transporte e ON r.idEmpresa = e.idEmpresa
                 WHERE rp_origen.idTerminal = :idTerminalOrigen
                 AND rp_origen.es_origen = 1
                 AND rp_destino.idTerminal = :idTerminalDestino
                 AND rp_destino.es_destino = 1
                 AND rp_destino.orden > rp_origen.orden
                 AND v.fecha = :fecha
                 AND v.habilitado = 1
                 AND EXISTS (
                     SELECT 1 FROM viaje_segmento_precio vsp
                     WHERE vsp.idViaje = v.idViaje
                     AND vsp.idOrigenParada = rp_origen.idRutaParada
                     AND vsp.idDestinoParada = rp_destino.idRutaParada
                     AND vsp.asientos_disponibles > 0
                     AND vsp.habilitado = 1
                 )
                 ORDER BY v.hora_salida ASC";
    
    $comando = $pdo->prepare($consulta);
    $comando->execute([
        'idTerminalOrigen' => $idTerminalOrigen,
        'idTerminalDestino' => $idTerminalDestino,
        'fecha' => $fecha
    ]);
    
    return $comando->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Obtener precio mínimo de un segmento
 * @param int $idViaje
 * @param int $idOrigenParada
 * @param int $idDestinoParada
 * @return float|null
 */
function getPrecioMinimoSegmento($idViaje, $idOrigenParada, $idDestinoParada) {
    require("conexion.php");
    
    $consulta = "SELECT MIN(precio) as precio_minimo
                 FROM viaje_segmento_precio
                 WHERE idViaje = :idViaje
                 AND idOrigenParada = :idOrigenParada
                 AND idDestinoParada = :idDestinoParada
                 AND asientos_disponibles > 0
                 AND habilitado = 1";
    
    $comando = $pdo->prepare($consulta);
    $comando->execute([
        'idViaje' => $idViaje,
        'idOrigenParada' => $idOrigenParada,
        'idDestinoParada' => $idDestinoParada
    ]);
    
    return $comando->fetchColumn();
}

/**
 * Actualizar disponibilidad de asientos de un segmento
 * @param int $idSegmentoPrecio
 * @param int $cantidad Cantidad a restar (positivo) o sumar (negativo)
 * @return bool
 */
function actualizarDisponibilidadSegmento($idSegmentoPrecio, $cantidad) {
    require("conexion.php");
    
    $consulta = "UPDATE viaje_segmento_precio 
                 SET asientos_disponibles = asientos_disponibles - :cantidad
                 WHERE idSegmentoPrecio = :idSegmentoPrecio
                 AND asientos_disponibles >= :cantidad";
    
    $comando = $pdo->prepare($consulta);
    return $comando->execute([
        'idSegmentoPrecio' => $idSegmentoPrecio,
        'cantidad' => $cantidad
    ]);
}

?>


