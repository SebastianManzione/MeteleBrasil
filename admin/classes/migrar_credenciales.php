<?php
/**
 * Script de migración de credenciales a BD
 * Ejecutar una sola vez: php admin/classes/migrar_credenciales.php
 */

require_once(__DIR__ . '/conexion.php');
require_once(__DIR__ . '/configuracion.php');

// Desactivar output buffering para ver el progreso
ob_implicit_flush(true);

echo "==================================================\n";
echo "Migración de Credenciales a Base de Datos\n";
echo "==================================================\n\n";

try {
    $config = new Configuracion();
    
    // 1. Migrar reCAPTCHA
    echo "1. Migrando reCAPTCHA...\n";
    $config->guardar('recaptcha_site_key', '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI', 'string', 'reCAPTCHA v3 Site Key (TEST)');
    $config->guardar('recaptcha_secret_key', '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe', 'string', 'reCAPTCHA v3 Secret Key (TEST)');
    echo "   ✓ reCAPTCHA migrado\n\n";
    
    // 2. Migrar PayPal
    echo "2. Migrando PayPal...\n";
    $config->guardar('paypal_client_id_1', 'AeV_6mpCIQUkgigJeObgPjqNJm9dtGpRbtWMZpF4z773Tw-Adj8hfrNA8WxzwM1_psRTaPXAv7akGBk9', 'string', 'PayPal Client ID (Sandbox)');
    $config->guardar('paypal_client_id_2', 'AY_f6DGMcccB4MHNGbvKcJsDN-3V0jw_45N9PVuM3apECjAqy1GtrZF413qTZeLzYGusTC2Uc3wz7W2D', 'string', 'PayPal Client ID (Alternative)');
    echo "   ✓ PayPal migrado\n\n";
    
    // 3. Crear valores vacíos para credenciales que no están hardcodeadas
    echo "3. Creando campos para OAuth (vacíos - completar manualmente)...\n";
    $config->guardar('google_client_id', '', 'string', 'Google OAuth 2.0 Client ID');
    $config->guardar('google_client_secret', '', 'string', 'Google OAuth 2.0 Client Secret');
    $config->guardar('facebook_app_id', '', 'string', 'Facebook App ID');
    $config->guardar('facebook_app_secret', '', 'string', 'Facebook App Secret');
    echo "   ✓ OAuth fields creados\n\n";
    
    // 4. Crear campos SMTP vacíos
    echo "4. Creando campos SMTP (vacíos - completar manualmente)...\n";
    $config->guardar('smtp_host', '', 'string', 'SMTP Host');
    $config->guardar('smtp_port', 587, 'number', 'SMTP Port');
    $config->guardar('smtp_usuario', '', 'string', 'SMTP Usuario');
    $config->guardar('smtp_password', '', 'string', 'SMTP Password');
    $config->guardar('smtp_de', '', 'string', 'SMTP Remitente');
    echo "   ✓ SMTP fields creados\n\n";
    
    // 5. Crear campos Stripe vacíos
    echo "5. Creando campos Stripe (vacíos - completar manualmente)...\n";
    $config->guardar('stripe_public_key', '', 'string', 'Stripe Publishable Key');
    $config->guardar('stripe_secret_key', '', 'string', 'Stripe Secret Key');
    echo "   ✓ Stripe fields creados\n\n";
    
    // 6. Crear configuración general
    echo "6. Creando configuración general...\n";
    $config->guardar('sitio_nombre', 'MeteleBrasil', 'string', 'Nombre del Sitio');
    $config->guardar('sitio_email', '', 'string', 'Email Principal del Sitio');
    $config->guardar('sitio_telefono', '', 'string', 'Teléfono Principal');
    echo "   ✓ Configuración general creada\n\n";
    
    // 7. Crear configuración de mantenimiento
    echo "7. Creando configuración de mantenimiento...\n";
    $config->guardar('mantenimiento_activo', false, 'boolean', 'Modo Mantenimiento Activo');
    $config->guardar('mantenimiento_mensaje', '', 'string', 'Mensaje de Mantenimiento');
    echo "   ✓ Configuración de mantenimiento creada\n\n";
    
    // 8. Crear configuración MercadoPago
    echo "8. Creando configuración MercadoPago...\n";
    $config->guardar('mp_ar_sandbox_public_key', 'APP_USR-cf99fd0d-bd3b-4038-9e53-c32f6e1fe899', 'string', 'MercadoPago AR Sandbox Public Key');
    $config->guardar('mp_ar_sandbox_access_token', 'APP_USR-8384221837051411-102019-a6f7b59c139b14d53d7376658a168a9e-2113221059', 'string', 'MercadoPago AR Sandbox Access Token');
    $config->guardar('mp_ar_production_public_key', 'APP_USR-1e84af2f-980f-456d-9b04-8498b5c4820a', 'string', 'MercadoPago AR Production Public Key');
    $config->guardar('mp_ar_production_access_token', 'APP_USR-199473761358972-101620-cae572d51b80f9e4152070592e0fcf84-342426513', 'string', 'MercadoPago AR Production Access Token');
    $config->guardar('mp_br_sandbox_public_key', 'APP_USR-7dd6b817-5156-4593-87b5-0bc42f3973d3', 'string', 'MercadoPago BR Sandbox Public Key');
    $config->guardar('mp_br_sandbox_access_token', 'APP_USR-4482222184019651-102019-da6ec2127f08cb8017297fb49a686524-2116010774', 'string', 'MercadoPago BR Sandbox Access Token');
    $config->guardar('mp_br_production_public_key', 'TEST-4d2c6227-0f58-4aff-93af-961aede6efd7', 'string', 'MercadoPago BR Production Public Key');
    $config->guardar('mp_br_production_access_token', 'APP_USR-7870778771559994-082007-492e7493924d2c0a8f5a0ae8dedcc9cd-138180833', 'string', 'MercadoPago BR Production Access Token');
    $config->guardar('mp_environment', 'production', 'string', 'MercadoPago Environment (sandbox/production)');
    echo "   ✓ MercadoPago configurado\n\n";
    
    echo "==================================================\n";
    echo "✓ MIGRACIÓN COMPLETADA EXITOSAMENTE\n";
    echo "==================================================\n\n";
    
    echo "Próximos pasos:\n";
    echo "1. Ir a: http://localhost/metelebrasil_dev/admin/configuracion.php\n";
    echo "2. Completar campos vacíos:\n";
    echo "   - Google OAuth 2.0 (Client ID y Secret)\n";
    echo "   - Facebook OAuth (App ID y Secret)\n";
    echo "   - SMTP (Host, Puerto, Usuario, Contraseña)\n";
    echo "   - Stripe (Publishable y Secret Key)\n";
    echo "   - Email y Teléfono del sitio\n";
    echo "3. Luego remover credenciales hardcodeadas de archivos\n\n";
    
} catch (Exception $e) {
    echo "✗ ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
