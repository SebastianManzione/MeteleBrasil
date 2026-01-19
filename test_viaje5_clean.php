<?php
/**
 * TEST: Viaje sin navbar.php para evitar conflictos
 */
session_start();
require_once("admin/classes/transporte.php");

// Configurar sesión básica
if (!isset($_SESSION['moneda_sel'])) {
    $_SESSION['moneda_sel'] = 1;
    $_SESSION['moneda_sel_sym'] = 'ARS';
}

$idViaje = 5;
$viaje = getViaje($idViaje);
$ruta = getRuta($viaje['idRuta']);
$paradas = getParadasRuta($viaje['idRuta']);
$tipoTarifa = isset($viaje['tipo_tarifa']) ? $viaje['tipo_tarifa'] : 'clases';

if ($tipoTarifa === 'clases') {
    $clasesDisponibles = getViajeClasesServicio($idViaje);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Test Viaje 5 - Sin Navbar</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <h1>Test Viaje 5 - Sin Navbar</h1>
    <p>Tipo Tarifa: <strong><?= $tipoTarifa ?></strong></p>
    
    <?php if ($tipoTarifa === 'clases'): ?>
        <div class="form-group">
            <label>Seleccionar Clase:</label>
            <select id="selectClase" class="form-control">
                <option value="">-- Seleccione --</option>
                <?php if (!empty($clasesDisponibles)): ?>
                    <?php foreach ($clasesDisponibles as $c): ?>
                        <option value="<?= $c['idViajeClase'] ?>"><?= htmlspecialchars($c['nombre_clase'] ?? 'Clase') ?></option>
                    <?php endforeach; ?>
                <?php else: ?>
                    <option disabled>No hay clases disponibles</option>
                <?php endif; ?>
            </select>
        </div>
        
        <div id="tarifasContainer"></div>
        
        <h3>Precio Total: <span id="precioTotal">ARS 0</span></h3>
    <?php endif; ?>
</div>

<script>
var idViaje = <?= $idViaje ?>;
var tipoTarifa = '<?= $tipoTarifa ?>';
var tarifasActuales = [];
var cantidadesPasajeros = {1: 0, 2: 0, 3: 0, 4: 0};

console.log('Tipo:', tipoTarifa);

$('#selectClase').on('change', function() {
    var idViajeClase = $(this).val();
    console.log('Clase seleccionada:', idViajeClase);
    
    if (!idViajeClase) return;
    
    $.ajax({
        url: 'admin/ctrl/ctrlTarifasViaje.php',
        method: 'POST',
        data: {
            accion: 'getClasesTarifas',
            idViaje: idViaje,
            idViajeClase: idViajeClase
        },
        dataType: 'json',
        success: function(response) {
            console.log('Response:', response);
            
            if (response.success) {
                tarifasActuales = response.tarifas;
                mostrarTarifas();
            } else {
                alert('Error: ' + response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', status, error);
            console.log('Response Text:', xhr.responseText);
            
            // Mostrar el error completo en la página
            $('#tarifasContainer').html('<div class="alert alert-danger"><h4>Error del Controller:</h4><pre>' + xhr.responseText + '</pre></div>');
            
            alert('Error AJAX. Ver la página - el error se muestra arriba.');
        }
    });
});

function mostrarTarifas() {
    var html = '<h4>Seleccionar Pasajeros:</h4>';
    
    tarifasActuales.forEach(function(t) {
        html += '<div class="form-group">';
        html += '<label>' + t.nombre_tipo_tarifa + ' (' + t.moneda_usuario + ' ' + t.precio_convertido + ')</label>';
        html += '<div class="input-group">';
        html += '<button class="btn btn-secondary" onclick="cambiar(' + t.idTipoTarifa + ', -1)">-</button>';
        html += '<input type="text" class="form-control text-center" id="cant_' + t.idTipoTarifa + '" value="0" readonly>';
        html += '<button class="btn btn-secondary" onclick="cambiar(' + t.idTipoTarifa + ', 1)">+</button>';
        html += '</div>';
        html += '</div>';
    });
    
    $('#tarifasContainer').html(html);
}

function cambiar(idTipo, delta) {
    cantidadesPasajeros[idTipo] = Math.max(0, (cantidadesPasajeros[idTipo] || 0) + delta);
    $('#cant_' + idTipo).val(cantidadesPasajeros[idTipo]);
    calcularTotal();
}

function calcularTotal() {
    var total = 0;
    tarifasActuales.forEach(function(t) {
        total += (cantidadesPasajeros[t.idTipoTarifa] || 0) * parseFloat(t.precio_convertido);
    });
    $('#precioTotal').text(tarifasActuales[0].moneda_usuario + ' ' + total.toFixed(2));
}
</script>
</body>
</html>
