<?php
session_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$archivo_actual = basename($_SERVER['PHP_SELF']);

require_once "includes/header.php";
require_once "includes/navbar.php";
require_once "includes/sidebar.php";
require_once "classes/transporte.php";

$idViaje = isset($_GET['id']) ? intval($_GET['id']) : null;
if (!$idViaje) {
    header("Location: viajesTransporteLista.php");
    exit;
}

$viaje = getViaje($idViaje);
if (!$viaje) {
    header("Location: viajesTransporteLista.php?error=Viaje no encontrado");
    exit;
}

$ruta = getRuta($viaje['idRuta']);
$paradas = getParadasRuta($viaje['idRuta']);
$tiposTarifa = getTiposTarifa();
$tarifas = getAllTarifas($idViaje);
$monedas = getAllMonedas(); // Función que ya debe existir en transporte.php
?>

<!-- Content Wrapper -->
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark"><i class="fas fa-dollar-sign"></i> Tarifas del Viaje</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="viajesTransporteLista.php">Viajes</a></li>
                        <li class="breadcrumb-item active">Tarifas</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Info del Viaje -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card shadow border-primary">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-info-circle"></i> Información del Viaje</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <strong>Ruta:</strong> <?=$ruta['nombre']?><br>
                                    <strong>ID:</strong> <?=$viaje['idViaje']?>
                                </div>
                                <div class="col-md-3">
                                    <strong>Fecha Salida:</strong> <?=date('d/m/Y', strtotime($viaje['fecha_salida']))?><br>
                                    <strong>Hora:</strong> <?=substr($viaje['hora_salida'], 0, 5)?>
                                </div>
                                <div class="col-md-3">
                                    <strong>Asientos:</strong> <?=$viaje['asientos_totales']?><br>
                                    <strong>Disponibles:</strong> <span class="badge badge-info"><?=$viaje['asientos_disponibles']?></span>
                                </div>
                                <div class="col-md-3">
                                    <strong>Estado:</strong> <span class="badge badge-success"><?=$viaje['estado']?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Panel Izquierdo: Formulario para crear tarifas -->
                <div class="col-lg-5">
                    <div class="card shadow">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="fas fa-plus-circle"></i> Nueva Tarifa</h5>
                        </div>
                        <div class="card-body">
                            <form id="formTarifa">
                                <input type="hidden" name="idViaje" value="<?=$viaje['idViaje']?>">
                                
                                <!-- Origen -->
                                <div class="form-group">
                                    <label for="idOrigenParada" class="font-weight-bold">Origen <span class="text-danger">*</span></label>
                                    <select id="idOrigenParada" name="idOrigenParada" class="form-control" required>
                                        <option value="">-- Seleccionar Origen --</option>
                                        <?php foreach ($paradas as $parada): ?>
                                            <?php if ($parada['es_origen']): ?>
                                            <option value="<?=$parada['idRutaParada']?>">
                                                <?=$parada['orden']?>. <?=$parada['terminal_nombre']?>
                                            </option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- Destino -->
                                <div class="form-group">
                                    <label for="idDestinoParada" class="font-weight-bold">Destino <span class="text-danger">*</span></label>
                                    <select id="idDestinoParada" name="idDestinoParada" class="form-control" required>
                                        <option value="">-- Seleccionar Destino --</option>
                                        <?php foreach ($paradas as $parada): ?>
                                            <?php if ($parada['es_destino']): ?>
                                            <option value="<?=$parada['idRutaParada']?>">
                                                <?=$parada['orden']?>. <?=$parada['terminal_nombre']?>
                                            </option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- Tipo de Tarifa -->
                                <div class="form-group">
                                    <label for="idTipoTarifa" class="font-weight-bold">Tipo de Pasajero <span class="text-danger">*</span></label>
                                    <select id="idTipoTarifa" name="idTipoTarifa" class="form-control" required>
                                        <option value="">-- Seleccionar Tipo --</option>
                                        <?php foreach ($tiposTarifa as $tipo): ?>
                                        <option value="<?=$tipo['idTipoTarifa']?>"><?=$tipo['nombre']?> (-<?=$tipo['descuento_porcentaje']?>%)</option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- Valor -->
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="valor" class="font-weight-bold">Precio <span class="text-danger">*</span></label>
                                            <input type="number" id="valor" name="valor" class="form-control" step="0.01" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="idMoneda" class="font-weight-bold">Moneda <span class="text-danger">*</span></label>
                                            <select id="idMoneda" name="idMoneda" class="form-control">
                                                <?php foreach ($monedas as $moneda): ?>
                                                <option value="<?=$moneda['id']?>"><?=$moneda['simbolo']?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Comisiona -->
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="comisiona" name="comisiona" value="1" checked>
                                        <label class="custom-control-label" for="comisiona">
                                            Esta tarifa comisiona a prestadores
                                        </label>
                                    </div>
                                </div>

                                <!-- Botones -->
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success btn-block">
                                        <i class="fas fa-save"></i> Agregar Tarifa
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Panel Derecho: Tabla de Tarifas Existentes -->
                <div class="col-lg-7">
                    <div class="card shadow">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="fas fa-list"></i> Tarifas Configuradas (<?=count($tarifas)?>)</h5>
                        </div>
                        <div class="card-body">
                            <?php if (empty($tarifas)): ?>
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i> No hay tarifas configuradas. Agrega una para este viaje.
                            </div>
                            <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Origen</th>
                                            <th>Destino</th>
                                            <th>Tipo</th>
                                            <th>Precio</th>
                                            <th>Moneda</th>
                                            <th>Comisiona</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($tarifas as $tarifa): ?>
                                        <tr>
                                            <td><?=$tarifa['origen_terminal']?></td>
                                            <td><?=$tarifa['destino_terminal']?></td>
                                            <td><?=$tarifa['tipo_pasajero']?></td>
                                            <td class="text-right"><strong><?=number_format($tarifa['valor'], 2)?></strong></td>
                                            <td><?=$tarifa['moneda_simbolo'] ?? 'ARS'?></td>
                                            <td>
                                                <?php if ($tarifa['comisiona']): ?>
                                                    <span class="badge badge-success">Sí</span>
                                                <?php else: ?>
                                                    <span class="badge badge-secondary">No</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm" onclick="eliminarTarifa(<?=$tarifa['idTarifa']?>)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones Generales -->
            <div class="row mt-4">
                <div class="col-12">
                    <a href="viajesTransporteLista.php" class="btn btn-secondary btn-lg">
                        <i class="fas fa-arrow-left"></i> Volver a Viajes
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
// Agregar nueva tarifa
document.getElementById('formTarifa').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    formData.append('action', 'insertTarifa');
    
    $.ajax({
        type: 'POST',
        url: 'ctrl/ctrlViajesTarifas.php',
        data: formData,
        dataType: 'json',
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.success) {
                alert(response.message);
                location.reload();
            } else {
                alert('Error: ' + response.message);
            }
        }
    });
});

function eliminarTarifa(idTarifa) {
    if (confirm('¿Eliminar esta tarifa?')) {
        $.ajax({
            type: 'POST',
            url: 'ctrl/ctrlViajesTarifas.php?action=deleteTarifa',
            data: { idTarifa: idTarifa },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                }
            }
        });
    }
}

// Validar que destino sea posterior a origen
document.getElementById('idDestinoParada').addEventListener('change', function() {
    const origen = document.getElementById('idOrigenParada').value;
    const destino = this.value;
    
    if (origen && destino && origen === destino) {
        alert('El origen y destino no pueden ser el mismo');
        this.value = '';
    }
});
</script>

<?php require_once "includes/footer.php"; ?>
