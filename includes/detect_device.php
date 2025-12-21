<?php
// includes/detect_device.php

function esDispositivoMovil() {
    $userAgent = strtolower($_SERVER['HTTP_USER_AGENT'] ?? '');
    return preg_match('/(iphone|ipod|ipad|android|blackberry|opera mini|windows phone|mobile)/i', $userAgent);
}

$ruta = basename($_SERVER['PHP_SELF']); // nombre del archivo actual
$queryString = $_SERVER['QUERY_STRING'] ?? '';

if (esDispositivoMovil()) {
    // Si es móvil y está en versión PC, redirige a versión app
    if ($ruta === 'categorias.php') {
        $destino = 'app.categorias.php';

        if (!empty($queryString)) {
            $destino .= '?' . $queryString;
        }

        header('Location: ' . $destino);
        exit;
    }
} else {
    // Si es PC y está en versión móvil, redirige a versión normal
    if ($ruta === 'app.categorias.php') {
        $destino = 'categorias.php';

        if (!empty($queryString)) {
            $destino .= '?' . $queryString;
        }

        header('Location: ' . $destino);
        exit;
    }
}
?>