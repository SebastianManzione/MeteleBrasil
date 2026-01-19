<?php
require_once(__DIR__ . "/classes/permisos.php");
require_once(__DIR__ . "/includes/permisos_helper.php");
require_once(__DIR__ . "/classes/transporte.php");
require_once(__DIR__ . "/classes/moneda.php");

// Verificar permisos
$permisos = new PermisosManager($GLOBALS['pdo'], $_SESSION['login'] ?? []);
$permisos->verificarAcceso('viajeSegmentosPreciosEditor');

// Obtener viaje seleccionado
$idViaje = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($idViaje == 0) {
    header("Location: viajesTransporteLista.php");
    exit;
}

// Obtener datos del viaje
$viaje = getViaje($idViaje);
if (!$viaje) {
    header("Location: viajesTransporteLista.php");
    exit;
}

$ruta = getRuta($viaje['idRuta']);
$segmentosPosibles = getSegmentosPosiblesRuta($viaje['idRuta']);
$preciosExistentes = getAllPreciosSegmentosViaje($idViaje);
$clases = getClasesPorViaje($idViaje); // Obtener clases del vehículo del viaje
$monedas = getAllMonedas();

// Organizar precios por origen-destino-clase
$matrizPrecios = [];
foreach ($preciosExistentes as $precio) {
    $key = $precio['idOrigenParada'] . '-' . $precio['idDestinoParada'] . '-' . $precio['idClaseServicio'];
    $matrizPrecios[$key] = $precio;
}

include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-0">
                                    <i class="fas fa-dollar-sign mr-2"></i>
                                    Configurar Precios por Segmento
                                </h4>
                                <small><?= $ruta['nombre'] ?> - <?= date('d/m/Y', strtotime($viaje['fecha'])) ?> <?= substr($viaje['hora_salida'], 0, 5) ?></small>
                            </div>
                            <a href="viajesTransporteLista.php" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        
                        <!-- Tabs por clase de servicio -->
                        <ul class="nav nav-tabs mb-4" id="claseTabs" role="tablist">
                            <?php foreach ($clases as $index => $clase): ?>
                            <li class="nav-item">
                                <a class="nav-link <?= $index == 0 ? 'active' : '' ?>" 
                                   id="clase-<?= $clase['idClaseServicio'] ?>-tab" 
                                   data-toggle="tab" 
                                   href="#clase-<?= $clase['idClaseServicio'] ?>" 
                                   role="tab">
                                    <i class="<?= $clase['icon'] ?> mr-1"></i>
                                    <?= $clase['nombre'] ?>
                                </a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        
                        <div class="tab-content" id="claseTabContent">
                            <?php foreach ($clases as $index => $clase): ?>
                            <div class="tab-pane fade <?= $index == 0 ? 'show active' : '' ?>" 
                                 id="clase-<?= $clase['idClaseServicio'] ?>" 
                                 role="tabpanel">
                                
                                <div class="alert alert-info">
                                    <strong><?= $clase['nombre'] ?>:</strong> <?= $clase['descripcion'] ?>
                                </div>
                                
                                <form id="form-clase-<?= $clase['idClaseServicio'] ?>" class="form-precios-segmentos">
                                    <input type="hidden" name="idViaje" value="<?= $idViaje ?>">
                                    <input type="hidden" name="idClaseServicio" value="<?= $clase['idClaseServicio'] ?>">
                                    
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm table-hover">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th width="200">Origen</th>
                                                    <th width="200">Destino</th>
                                                    <th width="150">Precio</th>
                                                    <th width="100">Moneda</th>
                                                    <th width="120">Asientos</th>
                                                    <th width="80">Comisiona</th>
                                                    <th width="80">Estado</th>
                                                    <th width="100">Acción</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                foreach ($segmentosPosibles as $segmento): 
                                                    $key = $segmento['idOrigenParada'] . '-' . $segmento['idDestinoParada'] . '-' . $clase['idClaseServicio'];
                                                    $precioActual = isset($matrizPrecios[$key]) ? $matrizPrecios[$key] : null;
                                                    $exists = $precioActual !== null;
                                                ?>
                                                <tr class="<?= $exists ? 'table-success' : '' ?>" 
                                                    data-origen="<?= $segmento['idOrigenParada'] ?>"
                                                    data-destino="<?= $segmento['idDestinoParada'] ?>">
                                                    <td>
                                                        <small class="text-muted">#<?= $segmento['orden_origen'] ?></small>
                                                        <?= $segmento['nombre_origen'] ?>
                                                        <input type="hidden" name="segmentos[<?= $segmento['idOrigenParada'] ?>-<?= $segmento['idDestinoParada'] ?>][idOrigenParada]" value="<?= $segmento['idOrigenParada'] ?>">
                                                    </td>
                                                    <td>
                                                        <small class="text-muted">#<?= $segmento['orden_destino'] ?></small>
                                                        <?= $segmento['nombre_destino'] ?>
                                                        <input type="hidden" name="segmentos[<?= $segmento['idOrigenParada'] ?>-<?= $segmento['idDestinoParada'] ?>][idDestinoParada]" value="<?= $segmento['idDestinoParada'] ?>">
                                                        <?php if ($exists): ?>
                                                        <input type="hidden" name="segmentos[<?= $segmento['idOrigenParada'] ?>-<?= $segmento['idDestinoParada'] ?>][idSegmentoPrecio]" value="<?= $precioActual['idSegmentoPrecio'] ?>">
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <input type="number" 
                                                               step="0.01" 
                                                               min="0"
                                                               class="form-control form-control-sm" 
                                                               name="segmentos[<?= $segmento['idOrigenParada'] ?>-<?= $segmento['idDestinoParada'] ?>][precio]"
                                                               value="<?= $exists ? $precioActual['precio'] : '' ?>"
                                                               placeholder="0.00">
                                                    </td>
                                                    <td>
                                                        <select class="form-control form-control-sm" 
                                                                name="segmentos[<?= $segmento['idOrigenParada'] ?>-<?= $segmento['idDestinoParada'] ?>][idMoneda]">
                                                            <?php foreach ($monedas as $moneda): ?>
                                                            <option value="<?= $moneda['idMoneda'] ?>" 
                                                                    <?= $exists && $precioActual['idMoneda'] == $moneda['idMoneda'] ? 'selected' : '' ?>>
                                                                <?= $moneda['Symbol'] ?> - <?= $moneda['CurrencyName'] ?>
                                                            </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="number" 
                                                               min="0"
                                                               class="form-control form-control-sm" 
                                                               name="segmentos[<?= $segmento['idOrigenParada'] ?>-<?= $segmento['idDestinoParada'] ?>][asientos_disponibles]"
                                                               value="<?= $exists ? $precioActual['asientos_disponibles'] : $viaje['asientos_totales'] ?>"
                                                               placeholder="0">
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" 
                                                                   class="custom-control-input" 
                                                                   id="comisiona-<?= $clase['idClaseServicio'] ?>-<?= $segmento['idOrigenParada'] ?>-<?= $segmento['idDestinoParada'] ?>"
                                                                   name="segmentos[<?= $segmento['idOrigenParada'] ?>-<?= $segmento['idDestinoParada'] ?>][comisiona]"
                                                                   value="1"
                                                                   <?= !$exists || $precioActual['comisiona'] == 1 ? 'checked' : '' ?>>
                                                            <label class="custom-control-label" for="comisiona-<?= $clase['idClaseServicio'] ?>-<?= $segmento['idOrigenParada'] ?>-<?= $segmento['idDestinoParada'] ?>"></label>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox" 
                                                                   class="custom-control-input" 
                                                                   id="habilitado-<?= $clase['idClaseServicio'] ?>-<?= $segmento['idOrigenParada'] ?>-<?= $segmento['idDestinoParada'] ?>"
                                                                   name="segmentos[<?= $segmento['idOrigenParada'] ?>-<?= $segmento['idDestinoParada'] ?>][habilitado]"
                                                                   value="1"
                                                                   <?= !$exists || $precioActual['habilitado'] == 1 ? 'checked' : '' ?>>
                                                            <label class="custom-control-label" for="habilitado-<?= $clase['idClaseServicio'] ?>-<?= $segmento['idOrigenParada'] ?>-<?= $segmento['idDestinoParada'] ?>"></label>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php if ($exists): ?>
                                                        <span class="badge badge-success">
                                                            <i class="fas fa-check"></i> Guardado
                                                        </span>
                                                        <?php else: ?>
                                                        <span class="badge badge-secondary">
                                                            <i class="fas fa-plus"></i> Nuevo
                                                        </span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    <div class="mt-3 text-right">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save mr-1"></i>
                                            Guardar Precios - <?= $clase['nombre'] ?>
                                        </button>
                                    </div>
                                </form>
                                
                            </div>
                            <?php endforeach; ?>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.content-wrapper -->

<script>
$(document).ready(function() {
    // Manejar submit de formularios
    $('.form-precios-segmentos').on('submit', function(e) {
        e.preventDefault();
        
        const $form = $(this);
        const $btn = $form.find('button[type="submit"]');
        const btnText = $btn.html();
        
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');
        
        $.ajax({
            url: 'ctrl/ctrlViajeSegmentosPrecio.php',
            type: 'POST',
            data: $form.serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: response.message || 'Precios guardados correctamente',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Error al guardar precios'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error de conexión al servidor'
                });
            },
            complete: function() {
                $btn.prop('disabled', false).html(btnText);
            }
        });
    });
    
    // Colorear filas según si tienen precio
    $('input[name*="[precio]"]').on('change', function() {
        const $tr = $(this).closest('tr');
        const precio = parseFloat($(this).val());
        
        if (precio > 0) {
            $tr.addClass('table-warning');
        } else {
            $tr.removeClass('table-warning');
        }
    });
});
</script>

<?php include("includes/footer.php"); ?>
