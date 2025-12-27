<?php
/**
 * Widget de Estado de Configuración para Dashboard
 * Incluir en admin/index.php para mostrar estado rápido
 */

require_once('classes/configuracion.php');
$config = new Configuracion();

// Verificar qué credenciales están configuradas
$google_ok = !empty($config->obtener('google_client_id'));
$facebook_ok = !empty($config->obtener('facebook_app_id'));
$recaptcha_ok = !empty($config->obtener('recaptcha_site_key')) && 
                 strpos($config->obtener('recaptcha_site_key'), '6LeIxAc') === false; // No test key
$smtp_ok = !empty($config->obtener('smtp_host')) && !empty($config->obtener('smtp_usuario'));
$stripe_ok = !empty($config->obtener('stripe_public_key')) && !empty($config->obtener('stripe_secret_key'));
$mantenimiento = $config->mantenimientoActivo();

$total_configurado = 
    ($google_ok ? 1 : 0) + 
    ($facebook_ok ? 1 : 0) + 
    ($recaptcha_ok ? 1 : 0) + 
    ($smtp_ok ? 1 : 0) + 
    ($stripe_ok ? 1 : 0);
$total_posible = 5;
$porcentaje = round(($total_configurado / $total_posible) * 100);
?>

<!-- Card de Configuración -->
<div class="col-md-6">
    <div class="card <?=$mantenimiento ? 'card-danger' : 'card-info'?>">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-cog"></i> Estado de Configuración
            </h3>
            <div class="card-tools">
                <a href="configuracion.php" class="btn btn-sm btn-primary">
                    <i class="fas fa-edit"></i> Editar
                </a>
            </div>
        </div>
        <div class="card-body">
            <!-- Estado de Mantenimiento -->
            <?php if ($mantenimiento): ?>
                <div class="alert alert-danger mb-3">
                    <i class="fas fa-exclamation-triangle"></i> 
                    <strong>¡MODO MANTENIMIENTO ACTIVO!</strong>
                    <a href="configuracion.php" class="btn btn-sm btn-outline-light float-right">Desactivar</a>
                </div>
            <?php endif; ?>

            <!-- Barra de Progreso -->
            <div class="mb-3">
                <div class="d-flex justify-content-between mb-2">
                    <span>Configuración completada</span>
                    <span class="badge badge-primary"><?=$total_configurado?>/<?=$total_posible?></span>
                </div>
                <div class="progress progress-sm">
                    <div class="progress-bar <?=$porcentaje >= 80 ? 'bg-success' : 'bg-warning'?>" 
                         role="progressbar" 
                         style="width: <?=$porcentaje?>%"
                         aria-valuenow="<?=$porcentaje?>" 
                         aria-valuemin="0" 
                         aria-valuemax="100">
                    </div>
                </div>
            </div>

            <!-- Listado de Servicios -->
            <ul class="list-unstyled">
                <li class="mb-2">
                    <span class="badge <?=$google_ok ? 'badge-success' : 'badge-secondary'?>">
                        <i class="fab fa-google"></i> Google OAuth
                    </span>
                    <small class="text-muted">
                        <?=$google_ok ? 'Configurado' : '<a href="configuracion.php#google">Configurar</a>'?>
                    </small>
                </li>
                <li class="mb-2">
                    <span class="badge <?=$facebook_ok ? 'badge-success' : 'badge-secondary'?>">
                        <i class="fab fa-facebook"></i> Facebook OAuth
                    </span>
                    <small class="text-muted">
                        <?=$facebook_ok ? 'Configurado' : '<a href="configuracion.php#facebook">Configurar</a>'?>
                    </small>
                </li>
                <li class="mb-2">
                    <span class="badge <?=$recaptcha_ok ? 'badge-success' : 'badge-warning'?>">
                        <i class="fas fa-shield-alt"></i> reCAPTCHA
                    </span>
                    <small class="text-muted">
                        <?=$recaptcha_ok ? 'Configurado (claves reales)' : '<a href="configuracion.php#recaptcha">Actualizar (usando test keys)</a>'?>
                    </small>
                </li>
                <li class="mb-2">
                    <span class="badge <?=$smtp_ok ? 'badge-success' : 'badge-secondary'?>">
                        <i class="fas fa-envelope"></i> Email SMTP
                    </span>
                    <small class="text-muted">
                        <?=$smtp_ok ? 'Configurado' : '<a href="configuracion.php#smtp">Configurar</a>'?>
                    </small>
                </li>
                <li>
                    <span class="badge <?=$stripe_ok ? 'badge-success' : 'badge-secondary'?>">
                        <i class="fab fa-stripe-s"></i> Stripe
                    </span>
                    <small class="text-muted">
                        <?=$stripe_ok ? 'Configurado' : '<a href="configuracion.php#stripe">Configurar</a>'?>
                    </small>
                </li>
            </ul>
        </div>
        <div class="card-footer">
            <small class="text-muted">
                Última actualización: <?=date('d/m/Y H:i')?>
            </small>
        </div>
    </div>
</div>
