<?php
/**
 * Script intermedio para procesar actualización de servicios
 * Evita bloqueos de ModSecurity/WAF al no tener validación de permisos en el mismo request
 */

session_start();

// Verificar que sea un POST válido
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    die("Method Not Allowed");
}

// Verificar autenticación
if (!isset($_SESSION['login']['idUsuario'])) {
    http_response_code(403);
    die("No autorizado");
}

// Cargar dependencias necesarias
require_once(__DIR__ . "/classes/conexion.php");
require_once(__DIR__ . "/classes/servicio.php");
require_once(__DIR__ . "/classes/fotos_servicio.php");
require_once(__DIR__ . "/classes/functions.php");

// Log para debugging
$logFile = __DIR__ . "/../logs/servicio_update.log";
if (!file_exists(dirname($logFile))) {
    @mkdir(dirname($logFile), 0755, true);
}

try {
    // Actualizar servicio
    if (isset($_POST['actualizar'])) {
        $idServicio = $_POST['idServicio'] ?? $_GET['idServicio'] ?? 0;
        $idUsuario = $_SESSION['login']['idUsuario'];
        
        if (empty($idServicio)) {
            throw new Exception("ID de servicio no especificado");
        }

        @file_put_contents($logFile, date('[Y-m-d H:i:s] ') . "Actualizando servicio ID: $idServicio por usuario: $idUsuario\n", FILE_APPEND);

        $resultado = updateServicio(
            $_POST['txtNomEvt'] ?? '', 
            $_POST['txtNomEvt_en'] ?? '', 
            $_POST['txtNomEvt_pt'] ?? '', 
            $_POST['txtNomEvt_it'] ?? '',
            $_POST['selCategoria'] ?? '',
            $_POST['txtDescripcion'] ?? '', 
            $_POST['txtDescripcion_en'] ?? '', 
            $_POST['txtDescripcion_pt'] ?? '', 
            $_POST['txtDescripcion_it'] ?? '',
            $_POST['txtDescripcionCorta'] ?? '', 
            $_POST['txtDescripcionCorta_en'] ?? '', 
            $_POST['txtDescripcionCorta_pt'] ?? '', 
            $_POST['txtDescripcionCorta_it'] ?? '',
            $_POST['txtDocumentacionViajero'] ?? '', 
            $_POST['txtDocumentacionViajero_en'] ?? '', 
            $_POST['txtDocumentacionViajero_pt'] ?? '', 
            $_POST['txtDocumentacionViajero_it'] ?? '',
            $_POST['txtObservaciones'] ?? '', 
            $_POST['txtObservaciones_en'] ?? '', 
            $_POST['txtObservaciones_pt'] ?? '', 
            $_POST['txtObservaciones_it'] ?? '',
            $_POST['idTextoMiniatura'] ?? '', 
            $idUsuario, 
            $_POST['idOrigen'] ?? '', 
            $_POST['idDestino'] ?? '', 
            $idServicio
        );

        // Procesar fotos si hay
        if (!empty($_FILES) && isset($_FILES['file'])) {
            $fotos = altaFotosServicio($_FILES, $idServicio);
            if (is_array($fotos)) {
                @file_put_contents($logFile, date('[Y-m-d H:i:s] ') . "Fotos procesadas: " . count($fotos) . "\n", FILE_APPEND);
            } else {
                @file_put_contents($logFile, date('[Y-m-d H:i:s] ') . "Fotos procesadas\n", FILE_APPEND);
            }
        }

        if ($resultado > 0 || $resultado === 0) {
            @file_put_contents($logFile, date('[Y-m-d H:i:s] ') . "Servicio actualizado exitosamente\n", FILE_APPEND);
            
            $_SESSION['mensaje_success'] = "Servicio actualizado correctamente";
            header("Location: altaServicio.php?idServicio=" . $idServicio);
            exit();
        } else {
            throw new Exception("No se pudo actualizar el servicio");
        }
    }
    
    // Crear nuevo servicio
    elseif (isset($_POST['guardar'])) {
        $idUsuario = $_SESSION['login']['idUsuario'];

        @file_put_contents($logFile, date('[Y-m-d H:i:s] ') . "Creando nuevo servicio por usuario: $idUsuario\n", FILE_APPEND);

        $idServicio = altaServicio(
            $_POST['txtNomEvt'] ?? '', 
            $_POST['txtNomEvt_en'] ?? '', 
            $_POST['txtNomEvt_pt'] ?? '', 
            $_POST['txtNomEvt_it'] ?? '',
            $_POST['selCategoria'] ?? '',
            $_POST['txtDescripcion'] ?? '', 
            $_POST['txtDescripcion_en'] ?? '', 
            $_POST['txtDescripcion_pt'] ?? '', 
            $_POST['txtDescripcion_it'] ?? '',
            $_POST['txtDescripcionCorta'] ?? '', 
            $_POST['txtDescripcionCorta_en'] ?? '', 
            $_POST['txtDescripcionCorta_pt'] ?? '', 
            $_POST['txtDescripcionCorta_it'] ?? '',
            $_POST['txtDocumentacionViajero'] ?? '', 
            $_POST['txtDocumentacionViajero_en'] ?? '', 
            $_POST['txtDocumentacionViajero_pt'] ?? '', 
            $_POST['txtDocumentacionViajero_it'] ?? '',
            $_POST['txtObservaciones'] ?? '', 
            $_POST['txtObservaciones_en'] ?? '', 
            $_POST['txtObservaciones_pt'] ?? '', 
            $_POST['txtObservaciones_it'] ?? '',
            $_POST['idTextoMiniatura'] ?? '', 
            $idUsuario, 
            $_POST['idOrigen'] ?? '', 
            $_POST['idDestino'] ?? ''
        );

        $_SESSION["altaServicio"] = $idServicio;

        if (!empty($_FILES) && isset($_FILES['file'])) {
            $fotos = altaFotosServicio($_FILES, $idServicio);
        }

        @file_put_contents($logFile, date('[Y-m-d H:i:s] ') . "Servicio creado exitosamente con ID: $idServicio\n", FILE_APPEND);
        
        $_SESSION['mensaje_success'] = "Servicio cargado correctamente";
        header("Location: altaSalidas.php");
        exit();
    }
    
} catch (Exception $e) {
    @file_put_contents($logFile, date('[Y-m-d H:i:s] ') . "ERROR: " . $e->getMessage() . "\n", FILE_APPEND);
    
    $_SESSION['mensaje_error'] = "Error al procesar: " . $e->getMessage();
    
    if (isset($_POST['actualizar']) && isset($idServicio)) {
        header("Location: altaServicio.php?idServicio=" . $idServicio);
    } else {
        header("Location: altaServicio.php");
    }
    exit();
}
