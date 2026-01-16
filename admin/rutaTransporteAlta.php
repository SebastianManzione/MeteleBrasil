<?php
// Verificar permisos de acceso ANTES de cualquier salida
require_once(__DIR__ . "/classes/permisos.php");
require_once(__DIR__ . "/includes/permisos_helper.php");
$permisos = new PermisosManager($GLOBALS['pdo'], $_SESSION['login'] ?? []);
$permisos->verificarAcceso('rutaTransporteAlta');

include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");

require_once("classes/transporte.php");
require_once("classes/prestador.php");

$tipos = getAllTiposTransporte();
$empresas = getAllEmpresas();
$prestadores = getAllPrestadores();

// Si es edición, cargar datos
$esEdicion = false;
$ruta = null;
if (isset($_GET['id'])) {
    $esEdicion = true;
    $ruta = getRuta($_GET['id']);
    if (!$ruta) {
        header("Location: rutasTransporteLista.php");
        exit();
    }
}
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">
                        <i class="fas fa-route"></i> 
                        <?=$esEdicion ? 'Editar Ruta' : 'Nueva Ruta'?>
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Transporte</a></li>
                        <li class="breadcrumb-item"><a href="rutasTransporteLista.php">Rutas</a></li>
                        <li class="breadcrumb-item active"><?=$esEdicion ? 'Editar' : 'Nueva'?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-route"></i> 
                            <?=$esEdicion ? 'Editar Ruta de Transporte' : 'Nueva Ruta de Transporte'?>
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="ctrl/ctrlRutasTransporte.php<?=$esEdicion ? '?id='.$ruta['idRuta'] : ''?>" method="POST" id="formRuta">
                            <input type="hidden" name="action" value="<?=$esEdicion ? 'update' : 'insert'?>">
                            
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> 
                                <strong>Importante:</strong> Después de crear la ruta, podrás agregar las paradas (origen y destino) desde el botón "Gestionar paradas".
                            </div>
                            
                            <!-- Tabs de idiomas -->
                            <ul class="nav nav-tabs" id="idiomasTabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="tab-es" data-toggle="tab" href="#contenido-es" role="tab">
                                        <img src="img/countries/Spain-icon.png" width="20"> Español
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="tab-en" data-toggle="tab" href="#contenido-en" role="tab">
                                        <img src="img/countries/United-States-of-Americ-icon.png" width="20"> English
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="tab-pt" data-toggle="tab" href="#contenido-pt" role="tab">
                                        <img src="img/countries/Brazil-icon.png" width="20"> Português
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="tab-it" data-toggle="tab" href="#contenido-it" role="tab">
                                        <img src="img/countries/italy-icon.png" width="20"> Italiano
                                    </a>
                                </li>
                            </ul>
                            
                            <!-- Contenido de tabs -->
                            <div class="tab-content border border-top-0 p-3 mb-4" id="idiomasContent">
                                <!-- Español -->
                                <div class="tab-pane fade show active" id="contenido-es" role="tabpanel">
                                    <div class="form-group">
                                        <label><i class="fas fa-heading"></i> Nombre de la Ruta <span class="text-danger">*</span></label>
                                        <input type="text" name="nombre" class="form-control" required
                                               placeholder="Ej: Buenos Aires - Mar del Plata"
                                               value="<?=$esEdicion ? htmlspecialchars($ruta['nombre']) : ''?>">
                                        <small class="form-text text-muted">Incluir origen - destino principal</small>
                                    </div>
                                    <div class="form-group">
                                        <label><i class="fas fa-align-left"></i> Descripción</label>
                                        <textarea name="descripcion" class="form-control" rows="3"
                                                  placeholder="Descripción detallada de la ruta..."><?=$esEdicion ? htmlspecialchars($ruta['descripcion']) : ''?></textarea>
                                    </div>
                                </div>
                                
                                <!-- English -->
                                <div class="tab-pane fade" id="contenido-en" role="tabpanel">
                                    <div class="form-group">
                                        <label><i class="fas fa-heading"></i> Route Name</label>
                                        <input type="text" name="nombre_en" class="form-control"
                                               placeholder="Ex: Buenos Aires - Mar del Plata"
                                               value="<?=$esEdicion ? htmlspecialchars($ruta['nombre_en']) : ''?>">
                                    </div>
                                    <div class="form-group">
                                        <label><i class="fas fa-align-left"></i> Description</label>
                                        <textarea name="descripcion_en" class="form-control" rows="3"
                                                  placeholder="Route detailed description..."><?=$esEdicion ? htmlspecialchars($ruta['descripcion_en']) : ''?></textarea>
                                    </div>
                                </div>
                                
                                <!-- Português -->
                                <div class="tab-pane fade" id="contenido-pt" role="tabpanel">
                                    <div class="form-group">
                                        <label><i class="fas fa-heading"></i> Nome da Rota</label>
                                        <input type="text" name="nombre_pt" class="form-control"
                                               placeholder="Ex: Buenos Aires - Mar del Plata"
                                               value="<?=$esEdicion ? htmlspecialchars($ruta['nombre_pt']) : ''?>">
                                    </div>
                                    <div class="form-group">
                                        <label><i class="fas fa-align-left"></i> Descrição</label>
                                        <textarea name="descripcion_pt" class="form-control" rows="3"
                                                  placeholder="Descrição detalhada da rota..."><?=$esEdicion ? htmlspecialchars($ruta['descripcion_pt']) : ''?></textarea>
                                    </div>
                                </div>
                                
                                <!-- Italiano -->
                                <div class="tab-pane fade" id="contenido-it" role="tabpanel">
                                    <div class="form-group">
                                        <label><i class="fas fa-heading"></i> Nome del Percorso</label>
                                        <input type="text" name="nombre_it" class="form-control"
                                               placeholder="Es: Buenos Aires - Mar del Plata"
                                               value="<?=$esEdicion ? htmlspecialchars($ruta['nombre_it']) : ''?>">
                                    </div>
                                    <div class="form-group">
                                        <label><i class="fas fa-align-left"></i> Descrizione</label>
                                        <textarea name="descripcion_it" class="form-control" rows="3"
                                                  placeholder="Descrizione dettagliata del percorso..."><?=$esEdicion ? htmlspecialchars($ruta['descripcion_it']) : ''?></textarea>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Configuración de la ruta -->
                            <h5 class="text-primary mb-3"><i class="fas fa-cogs"></i> Configuración de la Ruta</h5>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><i class="fas fa-bus"></i> Tipo de Transporte <span class="text-danger">*</span></label>
                                        <select name="idTipoTransporte" class="form-control" required>
                                            <option value="">Seleccionar tipo...</option>
                                            <?php foreach ($tipos as $tipo) { ?>
                                                <option value="<?=$tipo['idTipoTransporte']?>"
                                                        <?=$esEdicion && $ruta['idTipoTransporte'] == $tipo['idTipoTransporte'] ? 'selected' : ''?>>
                                                    <?=$tipo['nombre']?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><i class="fas fa-building"></i> Empresa Operadora</label>
                                        <select name="idEmpresa" class="form-control">
                                            <option value="">Sin empresa asignada</option>
                                            <?php foreach ($empresas as $empresa) { ?>
                                                <option value="<?=$empresa['idEmpresa']?>"
                                                        <?=$esEdicion && $ruta['idEmpresa'] == $empresa['idEmpresa'] ? 'selected' : ''?>>
                                                    <?=$empresa['nombre']?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                        <small class="form-text text-muted">Opcional: Compañía que opera esta ruta</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><i class="fas fa-user-tie"></i> Prestador</label>
                                        <select name="idPrestador" class="form-control">
                                            <option value="">Sin prestador asignado</option>
                                            <?php foreach ($prestadores as $prestador) { ?>
                                                <option value="<?=$prestador['idPrestador']?>"
                                                        <?=$esEdicion && $ruta['idPrestador'] == $prestador['idPrestador'] ? 'selected' : ''?>>
                                                    <?=$prestador['nombre']?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                        <small class="form-text text-muted">Opcional: Proveedor que vende esta ruta</small>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><i class="far fa-clock"></i> Duración Estimada</label>
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="input-group input-group-sm">
                                                    <input type="number" id="duracion_dias" class="form-control" 
                                                           placeholder="0" min="0" max="99"
                                                           value="<?php 
                                                           if ($esEdicion && !empty($ruta['duracion_estimada'])) {
                                                               preg_match('/(\d+)\s*d[ií]as?/i', $ruta['duracion_estimada'], $matches);
                                                               echo isset($matches[1]) ? $matches[1] : '';
                                                           }
                                                           ?>">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">días</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="input-group input-group-sm">
                                                    <input type="number" id="duracion_horas" class="form-control" 
                                                           placeholder="0" min="0" max="23"
                                                           value="<?php 
                                                           if ($esEdicion && !empty($ruta['duracion_estimada'])) {
                                                               preg_match('/(\d+)h/', $ruta['duracion_estimada'], $matches);
                                                               echo isset($matches[1]) ? $matches[1] : '';
                                                           }
                                                           ?>">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">h</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="input-group input-group-sm">
                                                    <input type="number" id="duracion_minutos" class="form-control" 
                                                           placeholder="0" min="0" max="59"
                                                           value="<?php 
                                                           if ($esEdicion && !empty($ruta['duracion_estimada'])) {
                                                               preg_match('/(\d+)min/', $ruta['duracion_estimada'], $matches);
                                                               echo isset($matches[1]) ? $matches[1] : '';
                                                           }
                                                           ?>">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">min</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="duracion_estimada" id="duracion_estimada_hidden">
                                        <small class="form-text text-muted">Para cruceros/barcos usar días. Ej: 7 días, 2h 30min</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><i class="fas fa-road"></i> Distancia (km)</label>
                                        <input type="number" name="distancia_km" class="form-control"
                                               placeholder="404"
                                               value="<?=$esEdicion ? $ruta['distancia_km'] : ''?>">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><i class="fas fa-image"></i> Foto Principal (URL)</label>
                                        <input type="text" name="foto_principal" class="form-control"
                                               placeholder="URL de la imagen"
                                               value="<?=$esEdicion ? htmlspecialchars($ruta['foto_principal']) : ''?>">
                                        <small class="form-text text-muted">Opcional: URL de imagen destacada de la ruta</small>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><i class="fas fa-toggle-on"></i> Estado</label>
                                        <div class="custom-control custom-switch" style="padding-top: 8px;">
                                            <input type="checkbox" class="custom-control-input" id="habilitado" 
                                                   name="habilitado" <?=$esEdicion ? ($ruta['habilitado'] ? 'checked' : '') : 'checked'?>>
                                            <label class="custom-control-label" for="habilitado">Ruta activa</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Botones -->
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <a href="rutasTransporteLista.php" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> Cancelar
                                    </a>
                                </div>
                                <div class="col-md-6 text-right">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-save"></i> Guardar Ruta
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php include("includes/footer.php"); ?>

<script>
// Combinar días, horas y minutos en campo oculto antes de enviar
$('#formRuta').on('submit', function(e) {
    var dias = parseInt($('#duracion_dias').val()) || 0;
    var horas = parseInt($('#duracion_horas').val()) || 0;
    var minutos = parseInt($('#duracion_minutos').val()) || 0;
    
    if (dias > 0 || horas > 0 || minutos > 0) {
        var duracionFormateada = '';
        
        if (dias > 0) {
            duracionFormateada += dias + (dias === 1 ? ' día' : ' días');
        }
        if (horas > 0) {
            duracionFormateada += (dias > 0 ? ' ' : '') + horas + 'h';
        }
        if (minutos > 0) {
            duracionFormateada += (dias > 0 || horas > 0 ? ' ' : '') + minutos + 'min';
        }
        
        $('#duracion_estimada_hidden').val(duracionFormateada);
    }
});
</script>

