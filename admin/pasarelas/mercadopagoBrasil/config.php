<?php
// Try to load from DB
$accessTokenMLTest = 'TEST-7870778771559994-082007-9ffcf821ab79914252778d85a21d2ffe-138180833';
$accessTokenML = 'APP_USR-7870778771559994-082007-492e7493924d2c0a8f5a0ae8dedcc9cd-138180833';

if (file_exists(__DIR__ . '/../../classes/configuracion.php')) {
    require_once(__DIR__ . '/../../classes/configuracion.php');
    try {
        $config = new Configuracion();
        $env = $config->obtener('mp_environment', 'production');
        $token = $config->obtener("mp_br_{$env}_access_token", null);
        if ($token) {
            if ($env === 'sandbox') {
                $accessTokenMLTest = $token;
            } else {
                $accessTokenML = $token;
            }
        }
    } catch (Exception $e) {
        // Fall through to hardcoded
    }
}
?>