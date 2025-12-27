<?php
include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");
require_once("classes/idioma_pais.php");
require_once("classes/paises.php");

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

$mensaje = '';

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['guardar_mapeo'])) {
        $codigoPais = trim($_POST['codigo_pais'] ?? '');
        $idioma = trim($_POST['idioma'] ?? '');
        $nombrePais = trim($_POST['nombre_pais'] ?? '');
        $activo = isset($_POST['activo']) ? 1 : 0;
        
        if ($codigoPais && $idioma) {
            $result = saveIdiomaPais($codigoPais, $idioma, $nombrePais, $activo);
            if ($result) {
                $mensaje = '<div class="alert alert-success alert-dismissible fade show"><i class="fas fa-check-circle"></i> Mapeo guardado correctamente. <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button></div>';
            } else {
                $mensaje = '<div class="alert alert-danger alert-dismissible fade show"><i class="fas fa-times-circle"></i> Error al guardar el mapeo. <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button></div>';
            }
        } else {
            $mensaje = '<div class="alert alert-warning alert-dismissible fade show"><i class="fas fa-exclamation-triangle"></i> Debes completar código de país e idioma. <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button></div>';
        }
    } elseif (isset($_POST['eliminar_mapeo'])) {
        $codigoPais = trim($_POST['codigo_pais'] ?? '');
        if ($codigoPais) {
            $result = deleteIdiomaPais($codigoPais);
            if ($result) {
                $mensaje = '<div class="alert alert-success alert-dismissible fade show"><i class="fas fa-check-circle"></i> Mapeo eliminado. <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button></div>';
            } else {
                $mensaje = '<div class="alert alert-danger alert-dismissible fade show"><i class="fas fa-times-circle"></i> Error al eliminar. <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button></div>';
            }
        }
    }
}

$mapeos = getAllIdiomasPais();
?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-globe"></i> Mapeo Idioma por País</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Idioma por País</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <?php if ($mensaje) echo $mensaje; ?>
            
            <!-- Tarjeta: Agregar/Editar Mapeo -->
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-plus-circle"></i> Agregar/Editar Mapeo</h3>
                </div>
                <form method="POST">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Código País (ISO 2) <span class="text-danger">*</span></label>
                                    <input type="text" name="codigo_pais" class="form-control" placeholder="AR" maxlength="2" style="text-transform:uppercase" required>
                                    <small class="form-text text-muted">2 letras, ej: AR, BR, US, IT</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Idioma <span class="text-danger">*</span></label>
                                    <select name="idioma" class="form-control" required>
                                        <option value="">-- Seleccionar --</option>
                                        <option value="ES">🇪🇸 Español (ES)</option>
                                        <option value="PT">🇧🇷 Portugués (PT)</option>
                                        <option value="EN">🇺🇸 Inglés (EN)</option>
                                        <option value="IT">🇮🇹 Italiano (IT)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Nombre del País</label>
                                    <input type="text" name="nombre_pais" class="form-control" placeholder="Argentina">
                                    <small class="form-text text-muted">Opcional, para referencia</small>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Activo</label><br>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" name="activo" class="custom-control-input" id="activo" checked>
                                        <label class="custom-control-label" for="activo">Sí</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" name="guardar_mapeo" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar Mapeo
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tarjeta: Lista de Mapeos -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-list"></i> Mapeos Actuales (<?= count($mapeos) ?>)</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th style="width:80px">País</th>
                                <th>Nombre</th>
                                <th style="width:100px">Idioma</th>
                                <th style="width:80px" class="text-center">Estado</th>
                                <th style="width:120px" class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($mapeos)): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        <i class="fas fa-info-circle"></i> No hay mapeos configurados
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($mapeos as $mapeo): ?>
                                    <tr>
                                        <td><strong><?= htmlspecialchars($mapeo['codigo_pais']) ?></strong></td>
                                        <td><?= htmlspecialchars($mapeo['nombre_pais']) ?></td>
                                        <td>
                                            <?php
                                            $badges = [
                                                'ES' => '<span class="badge badge-success">🇪🇸 ES</span>',
                                                'PT' => '<span class="badge badge-info">🇧🇷 PT</span>',
                                                'EN' => '<span class="badge badge-primary">🇺🇸 EN</span>',
                                                'IT' => '<span class="badge badge-warning">🇮🇹 IT</span>'
                                            ];
                                            echo $badges[$mapeo['idioma']] ?? htmlspecialchars($mapeo['idioma']);
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($mapeo['activo']): ?>
                                                <span class="badge badge-success"><i class="fas fa-check"></i> Activo</span>
                                            <?php else: ?>
                                                <span class="badge badge-secondary"><i class="fas fa-times"></i> Inactivo</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <form method="POST" style="display:inline" onsubmit="return confirm('¿Eliminar mapeo para <?= htmlspecialchars($mapeo['codigo_pais']) ?>?');">
                                                <input type="hidden" name="codigo_pais" value="<?= htmlspecialchars($mapeo['codigo_pais']) ?>">
                                                <button type="submit" name="eliminar_mapeo" class="btn btn-sm btn-danger" title="Eliminar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tarjeta: Ayuda -->
            <div class="card card-info collapsed-card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-question-circle"></i> Ayuda</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <h5>¿Qué hace esta configuración?</h5>
                    <p>Permite configurar qué idioma se muestra automáticamente cuando un usuario accede desde un determinado país, basándose en su geolocalización IP.</p>
                    
                    <h5>Códigos de País (ISO 3166-1 alpha-2)</h5>
                    <ul>
                        <li><strong>AR</strong> = Argentina</li>
                        <li><strong>BR</strong> = Brasil</li>
                        <li><strong>CL</strong> = Chile</li>
                        <li><strong>US</strong> = Estados Unidos</li>
                        <li><strong>ES</strong> = España</li>
                        <li><strong>IT</strong> = Italia</li>
                        <li>Y así sucesivamente... <a href="https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2" target="_blank">Ver lista completa</a></li>
                    </ul>
                    
                    <h5>Idiomas Soportados</h5>
                    <ul>
                        <li><strong>ES</strong> = Español</li>
                        <li><strong>PT</strong> = Portugués</li>
                        <li><strong>EN</strong> = Inglés</li>
                        <li><strong>IT</strong> = Italiano</li>
                    </ul>
                    
                    <p class="mb-0"><strong>Nota:</strong> Si un país no tiene mapeo configurado, el sistema usa Español (ES) por defecto.</p>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include("includes/footer.php"); ?>
