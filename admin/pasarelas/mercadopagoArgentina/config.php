<?php
// Try to load from DB
$accessTokenML = 'APP_USR-199473761358972-101620-cae572d51b80f9e4152070592e0fcf84-342426513';

if (file_exists(__DIR__ . '/../../classes/configuracion.php')) {
    require_once(__DIR__ . '/../../classes/configuracion.php');
    try {
        $config = new Configuracion();
        $env = $config->obtener('mp_environment', 'production');
        $token = $config->obtener("mp_ar_{$env}_access_token", null);
        if ($token) {
            $accessTokenML = $token;
        }
    } catch (Exception $e) {
        // Fall through to hardcoded
    }
}
?>