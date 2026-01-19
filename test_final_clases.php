<!DOCTYPE html>
<html>
<head>
    <title>Test Viaje 5 - FINAL</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1>Test Viaje 5 - Sistema CLASES (FINAL)</h1>
        <p class="lead">Usando controller original corregido</p>
        
        <div id="clasesContainer" class="mt-4">
            <div class="text-center">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Cargando...</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Cargar clases
            $.ajax({
                url: 'admin/ctrl/ctrlTarifasViaje.php',
                type: 'POST',
                data: { action: 'getClasesViaje', idViaje: 5 },
                dataType: 'json',
                success: function(response) {
                    if (response.success && response.clases) {
                        let html = '<div class="alert alert-success">✓ ' + response.clases.length + ' clases encontradas</div>';
                        
                        response.clases.forEach(function(clase) {
                            html += '<div class="card mb-3">';
                            html += '<div class="card-body">';
                            html += '<h4 class="card-title text-primary">' + clase.nombre_clase + '</h4>';
                            html += '<p class="card-text text-muted">' + clase.descripcion_clase + '</p>';
                            html += '<p><strong>Precio:</strong> <span class="badge badge-success badge-lg">' + clase.moneda_usuario + ' ' + clase.precio_formateado + '</span></p>';
                            html += '<p><strong>Disponibilidad:</strong> ' + clase.asientos_disponibles + '/' + clase.asientos_totales + ' asientos</p>';
                            html += '<button class="btn btn-primary ver-tarifas" data-id="' + clase.idViajeClase + '">Ver Tarifas por Tipo de Pasajero</button>';
                            html += '<div class="mt-3" id="tarifas-' + clase.idViajeClase + '" style="display:none;"></div>';
                            html += '</div></div>';
                        });
                        
                        $('#clasesContainer').html(html);
                    } else {
                        $('#clasesContainer').html('<div class="alert alert-warning">Sin clases</div>');
                    }
                },
                error: function(xhr) {
                    $('#clasesContainer').html('<div class="alert alert-danger">Error: <pre>' + xhr.responseText + '</pre></div>');
                }
            });
            
            // Ver tarifas al hacer click
            $(document).on('click', '.ver-tarifas', function() {
                let id = $(this).data('id');
                let container = $('#tarifas-' + id);
                
                if (container.is(':visible')) {
                    container.slideUp();
                    $(this).text('Ver Tarifas por Tipo de Pasajero');
                    return;
                }
                
                $(this).text('Cargando...');
                container.slideDown().html('<p>Cargando...</p>');
                
                $.ajax({
                    url: 'admin/ctrl/ctrlTarifasViaje.php',
                    type: 'POST',
                    data: { action: 'getClasesTarifas', idViaje: 5, idViajeClase: id },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success && response.tarifas) {
                            let html = '<table class="table table-sm table-bordered bg-white">';
                            html += '<thead class="thead-dark"><tr><th>Tipo</th><th>Precio</th></tr></thead><tbody>';
                            
                            response.tarifas.forEach(function(t) {
                                html += '<tr><td>' + t.nombre_tipo_tarifa + '</td>';
                                html += '<td><strong>' + t.moneda_usuario + ' ' + t.precio_formateado + '</strong></td></tr>';
                            });
                            
                            html += '</tbody></table>';
                            container.html(html);
                            $('.ver-tarifas[data-id="' + id + '"]').text('Ocultar Tarifas');
                        } else {
                            container.html('<div class="alert alert-warning">Sin tarifas</div>');
                        }
                    },
                    error: function() {
                        container.html('<div class="alert alert-danger">Error al cargar</div>');
                    }
                });
            });
        });
    </script>
</body>
</html>
