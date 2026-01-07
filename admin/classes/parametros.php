<?php
require_once(__DIR__ . '/configuracion.php');

function getParametros(){
    require("conexion.php");
    
    if (!isset($pdo) || !($pdo instanceof PDO)) {
        return []; // Tolerancia si PDO no estÃ¡ disponible
    }

    $legacy = [];
    try {
        $consulta = "SELECT * FROM parametros WHERE idParametros=1";
        $comando = $pdo->prepare($consulta);
        $comando->execute();
        $legacy = $comando->fetchAll(PDO::FETCH_ASSOC);
    } catch (Throwable $e) {
        @file_put_contents(__DIR__ . '/../../logs/parametros.log', date('c') . ' getParametros legacy: ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
    }

    // Asegurar estructura base para evitar notices
    if (empty($legacy) || !isset($legacy[0])) {
        $legacy = [[
            'head' => '',
            'body' => '',
            'footer' => '',
            'site' => $_SERVER['HTTP_HOST'] ?? ''
        ]];
    } else {
        // Normalizar claves faltantes
        $legacy[0]['head'] = $legacy[0]['head'] ?? '';
        $legacy[0]['body'] = $legacy[0]['body'] ?? '';
        $legacy[0]['footer'] = $legacy[0]['footer'] ?? '';
        $legacy[0]['site'] = $legacy[0]['site'] ?? ($_SERVER['HTTP_HOST'] ?? '');
    }

    // Leer desde nueva tabla configuracion
    $config = new Configuracion();
    $headNuevo = $config->obtener('parametros_head', '');
    $bodyNuevo = $config->obtener('parametros_body', '');
    $footerNuevo = $config->obtener('parametros_footer', '');

    // Migrar valores legados si la nueva tabla estÃ¡ vacÃ­a
    if ($headNuevo === '' && $legacy[0]['head'] !== '') {
        $headNuevo = $legacy[0]['head'];
        $config->guardar('parametros_head', $headNuevo, 'text', 'CÃ³digo Head');
    }
    if ($bodyNuevo === '' && $legacy[0]['body'] !== '') {
        $bodyNuevo = $legacy[0]['body'];
        $config->guardar('parametros_body', $bodyNuevo, 'text', 'CÃ³digo Body');
    }
    if ($footerNuevo === '' && $legacy[0]['footer'] !== '') {
        $footerNuevo = $legacy[0]['footer'];
        $config->guardar('parametros_footer', $footerNuevo, 'text', 'CÃ³digo Footer');
    }

    // Sobrescribir con los valores nuevos (o migrados)
    $legacy[0]['head'] = $headNuevo;
    $legacy[0]['body'] = $bodyNuevo;
    $legacy[0]['footer'] = $footerNuevo;

    return $legacy;
}

function updateParametros($head, $body, $footer = ''){
    require("conexion.php");
    
    if (!isset($pdo) || !($pdo instanceof PDO)) {
        return false; // Tolerancia
    }

    // Guardar en la nueva tabla de configuraciÃ³n
    try {
        $config = new Configuracion();
        $config->guardar('parametros_head', $head, 'text', 'CÃ³digo Head');
        $config->guardar('parametros_body', $body, 'text', 'CÃ³digo Body');
        $config->guardar('parametros_footer', $footer, 'text', 'CÃ³digo Footer');
    } catch (Throwable $e) {
        @file_put_contents(__DIR__ . '/../../logs/parametros.log', date('c') . ' updateParametros config: ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
    }

    // Actualizar tabla legada para compatibilidad
    try {
        $data = ["head" => $head, "body" => $body];
        $consulta = "UPDATE parametros SET head=:head, body=:body WHERE idParametros=1";
        $comando = $pdo->prepare($consulta);
        $comando->execute($data);
    } catch (Throwable $e) {
        @file_put_contents(__DIR__ . '/../../logs/parametros.log', date('c') . ' updateParametros legacy: ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
    }

    return true;
}

