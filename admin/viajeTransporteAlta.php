<?php
// Verificar permisos de acceso ANTES de cualquier salida
require_once(__DIR__ . "/classes/permisos.php");
require_once(__DIR__ . "/includes/permisos_helper.php");
$permisos = new PermisosManager($GLOBALS['pdo'], $_SESSION['login'] ?? []);
$permisos->verificarAcceso('viajeTransporteAlta');

include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");

require_once("classes/transporte.php");

// Obtener datos después de cargar includes
$idViaje = $_GET['id'] ?? null;
$viaje = null;
$esEdicion = false;

if ($idViaje) {
    $viaje = getViaje($idViaje);
    $esEdicion = true;
}

// Obtener todas las rutas para el select
$rutas = getAllRutas();

// Obtener todos los vehículos activos
$vehiculos = getAllVehiculos();
$modelos = getAllModelos();
?>

<style>
    .info-ruta {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 20px;
        display: none;
    }
    .info-ruta.show {
        display: block;
    }
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">
                        <i class="fas fa-calendar-alt"></i>
                        <?= $esEdicion ? 'Editar Viaje' : 'Nuevo Viaje' ?>
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="viajesTransporteLista.php">Viajes</a></li>
                        <li class="breadcrumb-item active"><?= $esEdicion ? 'Editar' : 'Nuevo' ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header">
                        <h3 class="card-title">
                            <?= $esEdicion ? 'Modificar datos del viaje' : 'Completar datos del nuevo viaje' ?>
                        </h3>
                    </div>
                    
                    <form id="formViaje" method="POST">
                        <div class="card-body">
                            
                            <?php if ($esEdicion): ?>
                            <input type="hidden" name="idViaje" value="<?= $viaje['idViaje'] ?>">
                            <?php endif; ?>
                            
                            <!-- Selección de Ruta -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="idRuta">Ruta <span class="text-danger">*</span></label>
                                        <select class="form-control" id="idRuta" name="idRuta" required>
                                            <option value="">Seleccione una ruta</option>
                                            <?php foreach($rutas as $ruta): ?>
                                            <option value="<?= $ruta['idRuta'] ?>" 
                                                    <?= ($esEdicion && $viaje['idRuta'] == $ruta['idRuta']) ? 'selected' : '' ?>
                                                    data-duracion="<?= htmlspecialchars($ruta['duracion_estimada']) ?>"
                                                    data-distancia="<?= $ruta['distancia_km'] ?>"
                                                    data-tipo="<?= htmlspecialchars($ruta['tipo_transporte'] ?? '') ?>">
                                                <?= htmlspecialchars($ruta['nombre']) ?>
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Origen/Destino por Paradas -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="idDesdeParada">Origen (parada) <span class="text-danger">*</span></label>
                                        <select class="form-control" id="idDesdeParada" name="idDesdeParada" required>
                                            <option value="">Seleccione origen</option>
                                        </select>
                                        <small class="form-text text-muted">Paradas marcadas como origen en la ruta</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="idHastaParada">Destino (parada) <span class="text-danger">*</span></label>
                                        <select class="form-control" id="idHastaParada" name="idHastaParada" required>
                                            <option value="">Seleccione destino</option>
                                        </select>
                                        <small class="form-text text-muted">Paradas marcadas como destino en la ruta</small>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Info de la ruta seleccionada -->
                            <div id="infoRuta" class="info-ruta">
                                <div class="row">
                                    <div class="col-md-4">
                                        <strong>Tipo:</strong> <span id="infoTipo"></span>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Duración estimada:</strong> <span id="infoDuracion"></span>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Distancia:</strong> <span id="infoDistancia"></span> km
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Fecha y Hora -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="fecha">Fecha de Salida <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="fecha" name="fecha" 
                                               value="<?= $esEdicion ? $viaje['fecha'] : '' ?>" 
                                               min="<?= date('Y-m-d') ?>"
                                               required>
                                        <small class="form-text text-muted">Fecha en que sale el viaje</small>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="hora_salida">Hora de Salida <span class="text-danger">*</span></label>
                                        <input type="time" class="form-control" id="hora_salida" name="hora_salida" 
                                               value="<?= $esEdicion ? substr($viaje['hora_salida'], 0, 5) : '' ?>" 
                                               required>
                                        <small class="form-text text-muted">Hora de partida (formato 24hs)</small>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Modelo de Vehículo -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="idModelo">Modelo de Vehículo <span class="text-danger">*</span></label>
                                        <select class="form-control" id="idModelo" name="idModelo" required onchange="cargarCapacidadModelo()">
                                            <option value="">-- Seleccionar Modelo --</option>
                                            <?php foreach($modelos as $modelo): ?>
                                            <option value="<?=$modelo['idModelo']?>" 
                                                    data-capacidad="<?=$modelo['capacidad_total']?>" 
                                                    <?=($esEdicion && intval($viaje['idModelo']) === intval($modelo['idModelo'])) ? 'selected' : ''?>>
                                                <?=htmlspecialchars($modelo['nombre'])?> (Cap: <?=$modelo['capacidad_total']?> pas.)
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <small class="form-text text-muted">Selecciona el modelo de vehículo para este viaje</small>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Asientos -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="asientos_totales">Asientos Totales <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="asientos_totales" name="asientos_totales" 
                                               value="<?= $esEdicion ? $viaje['asientos_totales'] : '' ?>" 
                                               min="1" max="999"
                                               required>
                                        <small class="form-text text-muted">Capacidad total del vehículo</small>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="asientos_disponibles">Asientos Disponibles <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="asientos_disponibles" name="asientos_disponibles" 
                                               value="<?= $esEdicion ? $viaje['asientos_disponibles'] : '' ?>" 
                                               min="0" max="999"
                                               required>
                                        <small class="form-text text-muted">Asientos disponibles para venta</small>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Estado -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="habilitado">Estado <span class="text-danger">*</span></label>
                                        <select class="form-control" id="habilitado" name="habilitado" required>
                                            <option value="1" <?= (!$esEdicion || $viaje['habilitado'] == 1) ? 'selected' : '' ?>>
                                                Activo (Disponible para venta)
                                            </option>
                                            <option value="0" <?= ($esEdicion && $viaje['habilitado'] == 0) ? 'selected' : '' ?>>
                                                Deshabilitado (No disponible)
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                <?= $esEdicion ? 'Actualizar Viaje' : 'Crear Viaje' ?>
                            </button>
                            <a href="viajesTransporteLista.php" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
    
    <?php include("includes/footer.php"); ?>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE -->
<script src="dist/js/adminlte.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    
    // Mostrar info de ruta al seleccionar
    $('#idRuta').on('change', function() {
        var option = $(this).find('option:selected');
        if (option.val()) {
            $('#infoTipo').text(option.data('tipo'));
            $('#infoDuracion').text(option.data('duracion'));
            $('#infoDistancia').text(option.data('distancia'));
            $('#infoRuta').addClass('show');

            // Cargar orígenes/destinos según ruta
            const idRuta = option.val();
            cargarParadasRuta(idRuta);
        } else {
            $('#infoRuta').removeClass('show');
            $('#idDesdeParada').html('<option value="">Seleccione origen</option>');
            $('#idHastaParada').html('<option value="">Seleccione destino</option>');
        }
    });
    
    // Si es edición, mostrar info de ruta
    <?php if ($esEdicion): ?>
    $('#idRuta').trigger('change');
    <?php endif; ?>
    
    // Cargar capacidad del modelo seleccionado
    window.cargarCapacidadModelo = function() {
        const select = document.getElementById('idModelo');
        const option = select.options[select.selectedIndex];
        const capacidad = option.getAttribute('data-capacidad');
        
        if (capacidad) {
            document.getElementById('asientos_totales').value = capacidad;
            document.getElementById('asientos_disponibles').value = capacidad;
        }
    };
    
    // Sincronizar asientos disponibles con totales
    $('#asientos_totales').on('input', function() {
        var totales = parseInt($(this).val()) || 0;
        var disponibles = parseInt($('#asientos_disponibles').val()) || 0;
        
        if (disponibles > totales) {
            $('#asientos_disponibles').val(totales);
        }
        
        $('#asientos_disponibles').attr('max', totales);
    });
    
    // Validación al enviar
    $('#formViaje').on('submit', function(e) {
        e.preventDefault();
        
        var totales = parseInt($('#asientos_totales').val());
        var disponibles = parseInt($('#asientos_disponibles').val());
        
        if (disponibles > totales) {
            Swal.fire({
                icon: 'error',
                title: 'Error de validación',
                text: 'Los asientos disponibles no pueden ser mayores a los totales'
            });
            return false;
        }
        
        // Preparar datos
        var formData = $(this).serialize();
        var action = '<?= $esEdicion ? "update" : "insert" ?>';
        
        $.ajax({
            url: 'ctrl/ctrlViajesTransporte.php?action=' + action,
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: '<?= $esEdicion ? "Viaje actualizado" : "Viaje creado" ?> correctamente',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        <?php if ($esEdicion): ?>
                            // Si es edición, recargar la página
                            location.reload();
                        <?php else: ?>
                            // Si es creación, ir a la lista
                            window.location.href = 'viajesTransporteLista.php';
                        <?php endif; ?>
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.error || 'No se pudo guardar el viaje'
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Error AJAX:', xhr, status, error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error de conexión',
                    text: 'No se pudo comunicar con el servidor: ' + error
                });
            }
        });
    });
});

// Cargar paradas por ruta (orígenes/destinos)
function cargarParadasRuta(idRuta) {
    // Orígenes
    $.get('ctrl/ctrlParadasRuta.php', { action: 'getOrigenes', idRuta: idRuta }, function(data) {
        const selOrigen = $('#idDesdeParada');
        selOrigen.empty();
        selOrigen.append('<option value="">Seleccione origen</option>');
        (data || []).forEach(function(p) {
            const texto = (p.orden ? ('#'+p.orden+' - ') : '') + (p.nombre || (p.ciudad ? p.ciudad : 'Parada'));
            const opt = $('<option></option>').val(p.idRutaParada).text(texto);
            selOrigen.append(opt);
        });
        // Preselección en edición
        <?php if ($esEdicion && !empty($viaje['idDesdeParada'])): ?>
        selOrigen.val('<?=intval($viaje['idDesdeParada'])?>');
        <?php endif; ?>
    }, 'json');

    // Destinos
    $.get('ctrl/ctrlParadasRuta.php', { action: 'getDestinos', idRuta: idRuta }, function(data) {
        const selDestino = $('#idHastaParada');
        selDestino.empty();
        selDestino.append('<option value="">Seleccione destino</option>');
        (data || []).forEach(function(p) {
            const texto = (p.orden ? ('#'+p.orden+' - ') : '') + (p.nombre || (p.ciudad ? p.ciudad : 'Parada'));
            const opt = $('<option></option>').val(p.idRutaParada).text(texto);
            selDestino.append(opt);
        });
        // Preselección en edición
        <?php if ($esEdicion && !empty($viaje['idHastaParada'])): ?>
        selDestino.val('<?=intval($viaje['idHastaParada'])?>');
        <?php endif; ?>
    }, 'json');
}
</script>

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
