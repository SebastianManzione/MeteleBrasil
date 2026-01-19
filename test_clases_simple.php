<!DOCTYPE html>
<html>
<head>
    <title>Test Viaje 5 - Clases y Tarifas</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1>Test Viaje 5 - Sistema CLASES</h1>
        <p>Cargando clases disponibles y sus tarifas por tipo de pasajero...</p>
        
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
            console.log('Iniciando carga de clases...');
            
            // Paso 1: Obtener clases del viaje
            $.ajax({
                url: 'admin/ctrl/ctrlTarifasViaje.php',
                type: 'POST',
                data: {
                    action: 'getClasesViaje',
                    idViaje: 5
                },
                dataType: 'json',
                success: function(response) {
                    console.log('Respuesta clases:', response);
                    
                    if (response.success && response.clases) {
                        let html = '';
                        
                        response.clases.forEach(function(clase) {
                            html += '<div class="clase-item mb-4 p-3 border rounded">';
                            html += '<h4>' + clase.nombre_clase + '</h4>';
                            html += '<p>' + clase.descripcion + '</p>';
                            html += '<p><strong>Precio Base:</strong> ' + clase.moneda_usuario + ' ' + clase.precio_formateado + '</p>';
                            html += '<p><strong>Disponibilidad:</strong> ' + clase.asientos_disponibles + '/' + clase.asientos_totales + '</p>';
                            html += '<button class="btn btn-primary btn-sm ver-tarifas" data-id-clase="' + clase.idViajeClase + '">Ver Tarifas por Pasajero</button>';
                            html += '<div class="tarifas-container mt-3" id="tarifas-' + clase.idViajeClase + '" style="display:none;"></div>';
                            html += '</div>';
                        });
                        
                        $('#clasesContainer').html(html);
                    } else {
                        $('#clasesContainer').html('<div class="alert alert-warning">No hay clases configuradas para este viaje</div>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error AJAX:', error);
                    console.error('Response:', xhr.responseText);
                    $('#clasesContainer').html('<div class="alert alert-danger">Error al cargar clases: ' + error + '<br><br><strong>Respuesta del servidor:</strong><pre>' + xhr.responseText + '</pre></div>');
                }
            });
            
            // Paso 2: Al hacer click en "Ver Tarifas", cargar tarifas de esa clase
            $(document).on('click', '.ver-tarifas', function() {
                let idViajeClase = $(this).data('id-clase');
                let container = $('#tarifas-' + idViajeClase);
                
                // Toggle
                if (container.is(':visible')) {
                    container.slideUp();
                    $(this).text('Ver Tarifas por Pasajero');
                    return;
                }
                
                $(this).text('Cargando...');
                container.slideDown();
                container.html('<p class="text-muted">Cargando tarifas...</p>');
                
                $.ajax({
                    url: 'admin/ctrl/ctrlTarifasViaje.php',
                    type: 'POST',
                    data: {
                        action: 'getClasesTarifas',
                        idViaje: 5,
                        idViajeClase: idViajeClase
                    },
                    dataType: 'json',
                    success: function(response) {
                        console.log('Respuesta tarifas:', response);
                        
                        if (response.success && response.tarifas) {
                            let html = '<table class="table table-sm table-bordered mt-2"><thead class="thead-light"><tr><th>Tipo Pasajero</th><th>Precio</th></tr></thead><tbody>';
                            
                            response.tarifas.forEach(function(tarifa) {
                                html += '<tr>';
                                html += '<td>' + tarifa.nombre_tipo_tarifa + '</td>';
                                html += '<td><strong>' + tarifa.moneda_usuario + ' ' + tarifa.precio_formateado + '</strong></td>';
                                html += '</tr>';
                            });
                            
                            html += '</tbody></table>';
                            container.html(html);
                            $('.ver-tarifas[data-id-clase="' + idViajeClase + '"]').text('Ocultar Tarifas');
                        } else {
                            container.html('<div class="alert alert-warning">No hay tarifas configuradas</div>');
                            $('.ver-tarifas[data-id-clase="' + idViajeClase + '"]').text('Ver Tarifas por Pasajero');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error AJAX:', error);
                        container.html('<div class="alert alert-danger">Error: ' + error + '</div>');
                        $('.ver-tarifas[data-id-clase="' + idViajeClase + '"]').text('Ver Tarifas por Pasajero');
                    }
                });
            });
        });
    </script>
</body>
</html>
