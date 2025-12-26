<?php
include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");
require_once("classes/configuracion.php");
require_once("classes/email_templates.php");

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
$emailTemplates = new EmailTemplates();

$plantillasDisponibles = [
    'reserva_confirmada' => 'Reserva confirmada',
    'reserva_pendiente' => 'Reserva pendiente',
    'pago_recibido' => 'Pago recibido',
    'recuperar_contrasena' => 'Recuperar contraseña',
    'registro_usuario' => 'Registro de usuario',
    'registro_prestador' => 'Registro de prestador',
    'prestador_reserva_confirmada' => 'Prestador: reserva confirmada'
];

$idiomas = ['ES' => 'Español', 'EN' => 'Inglés', 'PT' => 'Portugués', 'IT' => 'Italiano'];

$clave = $_POST['clave'] ?? $_GET['clave'] ?? array_key_first($plantillasDisponibles);
$idioma = $_POST['idioma'] ?? $_GET['idioma'] ?? 'ES';

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar_plantilla'])) {
    $asunto = trim($_POST['asunto'] ?? '');
    $html = $_POST['html'] ?? '';

    if ($asunto === '') {
        $mensaje = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-times-circle"></i> Debes indicar un asunto.
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>';
    } else {
        $ok = $emailTemplates->guardar($clave, $idioma, $asunto, $html);
        $mensaje = '<div class="alert alert-' . ($ok ? 'success' : 'danger') . ' alert-dismissible fade show" role="alert">
            <i class="fas fa-' . ($ok ? 'check-circle' : 'times-circle') . '"></i> ' . ($ok ? 'Plantilla guardada' : 'No se pudo guardar') . '.
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>';
    }
}

$plantilla = $emailTemplates->obtener($clave, $idioma) ?: ['asunto' => '', 'html' => '<p>Contenido del email...</p>'];
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark"><i class="fas fa-envelope-open-text"></i> Plantillas de Email</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item active">Plantillas Email</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <?php echo $mensaje; ?>
            <form method="post">
                <div class="card">
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label>Plantilla</label>
                                <select name="clave" class="form-control" onchange="this.form.submit()">
                                    <?php foreach ($plantillasDisponibles as $k => $label): ?>
                                        <option value="<?=$k?>" <?=($k === $clave ? 'selected' : '')?>><?=$label?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Idioma</label>
                                <select name="idioma" class="form-control" onchange="this.form.submit()">
                                    <?php foreach ($idiomas as $code => $label): ?>
                                        <option value="<?=$code?>" <?=($code === $idioma ? 'selected' : '')?>><?=$label?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group col-md-5">
                                <label>Asunto</label>
                                <input type="text" class="form-control" name="asunto" value="<?=htmlspecialchars($plantilla['asunto'])?>" placeholder="Asunto del email" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Contenido (HTML)</label>
                            <textarea name="html" id="html" class="form-control"><?=htmlspecialchars($plantilla['html'])?></textarea>
                            <small class="form-text text-muted">Usa el editor. Variables: {{codigo_reserva}}, {{enlace_reserva}}, {{nombre}}, {{fecha_salida}}, {{id_salida}}, {{enlace_reset}} según la plantilla.</small>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <a href="configuracion.php#email" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left"></i> Volver</a>
                            </div>
                            <div>
                                <button type="submit" name="guardar_plantilla" value="1" class="btn btn-primary"><i class="fas fa-save"></i> Guardar plantilla</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>

<link href="plugins/summernote/summernote.css" rel="stylesheet">
<script src="plugins/summernote/summernote.min.js"></script>
<script>
    $(document).ready(function() {
        $('#html').summernote({
            height: 380,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['table', ['table']],
                ['insert', ['link', 'picture']],
                ['view', ['fullscreen', 'codeview']]
            ]
        });
    });
</script>

<style>
.card-body label { font-weight: 600; }
</style>

<?php include("includes/footer.php"); ?>
