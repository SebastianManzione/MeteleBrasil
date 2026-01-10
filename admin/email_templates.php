<?php
include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");
require("classes/configuracion.php");

// Verificar permisos (solo admin)
if (!isset($_SESSION['login']['idUsuario']) || $_SESSION['login']['idUsuario'] != 1) {
    echo '<div class="content-wrapper" style="margin-top: 20px;"><div class="container-fluid"><div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-top: 20px; padding: 20px;">';
    echo '<h4 class="alert-heading">⛔ Acceso Denegado</h4>';
    echo '<p>Solo los administradores pueden acceder a este panel.</p>';
    echo '<hr><a href="index.php" class="btn btn-primary btn-sm">← Volver al Dashboard</a>';
    echo '</div></div></div>';
    include("includes/footer.php");
    die();
}

$config = new Configuracion();
$mensaje = '';

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar_plantillas'])) {
    // Email Vendedor
    if (isset($_POST['email_vendedor_asunto'])) {
        $config->guardar('email_vendedor_asunto', $_POST['email_vendedor_asunto'], 'string', 'Email Vendedor - Asunto');
    }
    if (isset($_POST['email_vendedor_cuerpo'])) {
        $config->guardar('email_vendedor_cuerpo', $_POST['email_vendedor_cuerpo'], 'text', 'Email Vendedor - Cuerpo');
    }
    
    // Email Prestador
    if (isset($_POST['email_prestador_asunto'])) {
        $config->guardar('email_prestador_asunto', $_POST['email_prestador_asunto'], 'string', 'Email Prestador - Asunto');
    }
    if (isset($_POST['email_prestador_cuerpo'])) {
        $config->guardar('email_prestador_cuerpo', $_POST['email_prestador_cuerpo'], 'text', 'Email Prestador - Cuerpo');
    }
    
    $mensaje = '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> <strong>¡Éxito!</strong> Plantillas de email guardadas correctamente.
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>';
}

// Obtener configuraciones actuales
$config_data = [
    'email_vendedor_asunto' => $config->obtener('email_vendedor_asunto', 'Nueva reserva confirmada - {{codigo_reserva}}'),
    'email_vendedor_cuerpo' => $config->obtener('email_vendedor_cuerpo', '<h2>¡Nueva Reserva Confirmada!</h2><p>Hola,</p><p>Se ha confirmado una nueva reserva:</p><ul><li><strong>Código:</strong> {{codigo_reserva}}</li><li><strong>Cliente:</strong> {{nombre_cliente}}</li><li><strong>Email:</strong> {{email_cliente}}</li><li><strong>Total:</strong> {{total}}</li></ul><p><a href="{{enlace_reserva}}">Ver detalles de la reserva</a></p>'),
    'email_prestador_asunto' => $config->obtener('email_prestador_asunto', 'Nova reserva! {{fecha_salida}}'),
    'email_prestador_cuerpo' => $config->obtener('email_prestador_cuerpo', '<h2>Hola {{nombre_prestador}}!</h2><p>Tienes una nueva reserva confirmada:</p><ul><li><strong>Código:</strong> {{codigo_reserva}}</li><li><strong>Fecha de salida:</strong> {{fecha_salida}}</li><li><strong>Salida ID:</strong> {{id_salida}}</li></ul><p><a href="{{enlace_reserva}}">Ver detalles en el panel</a></p>')
];
?>

<div class="content-wrapper" style="margin-top: 20px;">
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-12">
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-envelope-open-text"></i> Plantillas de Email
                </h1>
                <p class="text-muted">Personaliza los emails automáticos que se envían cuando se confirman reservas</p>
            </div>
        </div>

        <?= $mensaje ?>

        <form method="POST" action="">
            <!-- Email al Vendedor -->
            <div class="card mb-4 shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-user-tie"></i> Email Automático al Vendedor</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> <strong>Variables disponibles:</strong><br>
                        <code>{{codigo_reserva}}</code> - Código de la reserva<br>
                        <code>{{nombre_cliente}}</code> - Nombre completo del cliente<br>
                        <code>{{email_cliente}}</code> - Email del cliente<br>
                        <code>{{total}}</code> - Monto total de la reserva<br>
                        <code>{{enlace_reserva}}</code> - Link directo a los detalles en el admin
                    </div>
                    
                    <div class="form-group">
                        <label for="email_vendedor_asunto"><strong>Asunto del Email</strong></label>
                        <input type="text" class="form-control" id="email_vendedor_asunto" name="email_vendedor_asunto" value="<?=htmlspecialchars($config_data['email_vendedor_asunto'])?>" placeholder="Nueva reserva confirmada - {{codigo_reserva}}">
                        <small class="form-text text-muted">Usa variables como {{codigo_reserva}} para personalizar</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="email_vendedor_cuerpo"><strong>Cuerpo del Email (HTML)</strong></label>
                        <textarea class="form-control summernote" id="email_vendedor_cuerpo" name="email_vendedor_cuerpo"><?=htmlspecialchars($config_data['email_vendedor_cuerpo'])?></textarea>
                        <small class="form-text text-muted">Puedes usar HTML y las variables mencionadas arriba</small>
                    </div>
                </div>
            </div>

            <!-- Email al Prestador -->
            <div class="card mb-4 shadow">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-briefcase"></i> Email Automático al Prestador</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> <strong>Variables disponibles:</strong><br>
                        <code>{{nombre_prestador}}</code> - Nombre del prestador<br>
                        <code>{{fecha_salida}}</code> - Fecha de la salida<br>
                        <code>{{codigo_reserva}}</code> - Código de la reserva<br>
                        <code>{{id_salida}}</code> - ID de la salida<br>
                        <code>{{enlace_reserva}}</code> - Link directo a los detalles
                    </div>
                    
                    <div class="form-group">
                        <label for="email_prestador_asunto"><strong>Asunto del Email</strong></label>
                        <input type="text" class="form-control" id="email_prestador_asunto" name="email_prestador_asunto" value="<?=htmlspecialchars($config_data['email_prestador_asunto'])?>" placeholder="Nova reserva! {{fecha_salida}}">
                        <small class="form-text text-muted">Usa variables como {{fecha_salida}} para personalizar</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="email_prestador_cuerpo"><strong>Cuerpo del Email (HTML)</strong></label>
                        <textarea class="form-control summernote" id="email_prestador_cuerpo" name="email_prestador_cuerpo"><?=htmlspecialchars($config_data['email_prestador_cuerpo'])?></textarea>
                        <small class="form-text text-muted">Puedes usar HTML y las variables mencionadas arriba</small>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <button type="submit" name="guardar_plantillas" class="btn btn-primary btn-lg">
                        <i class="fas fa-save"></i> Guardar Plantillas
                    </button>
                    <a href="configuracion.php#email" class="btn btn-secondary btn-lg ml-2">
                        <i class="fas fa-arrow-left"></i> Volver a Configuración
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<?php include("includes/footer.php"); ?>

<link href="plugins/summernote/summernote.css" rel="stylesheet">
<script src="plugins/summernote/summernote.min.js"></script>
<script>
$(document).ready(function() {
    $('.summernote').summernote({
        height: 300,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['fontname', ['fontname']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ]
    });
});
</script>
