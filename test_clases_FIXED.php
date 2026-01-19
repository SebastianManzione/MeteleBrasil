<!DOCTYPE html>
<html>
<head>
    <title>Test Viaje 5 - FIXED</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1>Test Viaje 5 - Sistema CLASES (CORREGIDO)</h1>
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
                url: 'admin/ctrl/ctrlTarifasViaje_fixed.php',
                type: 'POST',
                data: {
                    action: 'getClasesViaje',
                    idViaje: 5
                },
                dataType: 'json',
                success: function(response) {
                    console.log('Respuesta clases:', response);
                    
                    if (response.success && response.clases) {
                        let html = '<div class="alert alert-success">✓ Se encontraron ' + response.clases.length + ' clases</div>';
                        
                        response.clases.forEach(function(clase) {
                            html += '<div class="clase-item mb-4 p-4 border rounded bg-light">';
                            html += '<h4 class="text-primary">' + clase.nombre_clase + '</h4>';
                            html += '<p class="text-muted">' + clase.descripcion_clase + '</p>';
                            html += '<div class="row">';
                            html += '<div class="col-md-6">';
                            html += '<p><strong>Precio Base:</strong> <span class="badge badge-success">' + clase.moneda_usuario + ' ' + clase.precio_formateado + '</span></p>';
                            html += '</div>';
                            html += '<div class="col-md-6">';
                            html += '<p><strong>Disponibilidad:</strong> ' + clase.asientos_disponibles + '/' + clase.asientos_totales + ' asientos</p>';
                            html += '</div>';
                            html += '</div>';
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
                    $('#clasesContainer').html('<div class="alert alert-danger"><strong>Error al cargar clases:</strong> ' + error + '<br><br><strong>Respuesta del servidor:</strong><pre>' + xhr.responseText + '</pre></div>');
                }
            });
            
            // Paso 2: Al hacer click en "Ver Tarifas", cargar tarifas de esa clase
            $(document).on('click', '.ver-tarifas', function() {
                let idViajeClase = $(this).data('id-clase');
                let container = $('#tarifas-' + idViajeClase);
                let btn = $(this);
                
                // Toggle
                if (container.is(':visible')) {
                    container.slideUp();
                    btn.text('Ver Tarifas por Pasajero');
                    return;
                }
                
                btn.text('Cargando...');
                container.slideDown();
                container.html('<p class="text-muted">Cargando tarifas...</p>');
                
                $.ajax({
                    url: 'admin/ctrl/ctrlTarifasViaje_fixed.php',
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
                            let html = '<table class="table table-sm table-bordered mt-2 bg-white">';
                            html += '<thead class="thead-dark"><tr><th>Tipo Pasajero</th><th>Precio</th></tr></thead><tbody>';
                            
                            response.tarifas.forEach(function(tarifa) {
                                html += '<tr>';
                                html += '<td>' + tarifa.nombre_tipo_tarifa + '</td>';
                                html += '<td><strong>' + tarifa.moneda_usuario + ' ' + tarifa.precio_formateado + '</strong></td>';
                                html += '</tr>';
                            });
                            
                            html += '</tbody></table>';
                            container.html(html);
                            btn.text('Ocultar Tarifas');
                        } else {
                            container.html('<div class="alert alert-warning">No hay tarifas configuradas</div>');
                            btn.text('Ver Tarifas por Pasajero');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error AJAX:', error);
                        container.html('<div class="alert alert-danger">Error: ' + error + '</div>');
                        btn.text('Ver Tarifas por Pasajero');
                    }
                });
            });
        });
    </script>
</body>
</html>
