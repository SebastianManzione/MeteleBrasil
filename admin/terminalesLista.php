<?php
session_start();
require_once("classes/usuario.php");
require_once("classes/transporte.php");

// Validar sesión admin
if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit();
}

$terminales = getAllTerminales();
$tipos = getAllTiposTransporte();

$success = isset($_GET['success']) ? $_GET['success'] : '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terminales de Transporte - Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .badge-bus { background-color: #007bff; }
        .badge-plane { background-color: #28a745; }
        .badge-train { background-color: #ffc107; color: #000; }
        .badge-ship { background-color: #17a2b8; }
    </style>
</head>
<body>
    <?php include("navbar.php"); ?>
    
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0"><i class="fas fa-map-marker-alt"></i> Terminales de Transporte</h4>
                        <a href="terminalAlta.php" class="btn btn-light">
                            <i class="fas fa-plus"></i> Nueva Terminal
                        </a>
                    </div>
                    <div class="card-body">
                        <?php if ($success == '1') { ?>
                            <div class="alert alert-success alert-dismissible fade show">
                                <i class="fas fa-check-circle"></i> Terminal guardada correctamente
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                            </div>
                        <?php } ?>
                        
                        <?php if ($success == 'deleted') { ?>
                            <div class="alert alert-info alert-dismissible fade show">
                                <i class="fas fa-trash"></i> Terminal eliminada
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                            </div>
                        <?php } ?>
                        
                        <!-- Filtros -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label>Filtrar por tipo:</label>
                                <select id="filtroTipo" class="form-control">
                                    <option value="">Todos los tipos</option>
                                    <?php foreach ($tipos as $tipo) { ?>
                                        <option value="<?=$tipo['idTipoTransporte']?>"><?=$tipo['nombre']?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label>Buscar por ciudad:</label>
                                <input type="text" id="filtroCiudad" class="form-control" placeholder="Ej: Buenos Aires">
                            </div>
                        </div>
                        
                        <!-- Tabla -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="tablaTerminales">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Ciudad</th>
                                        <th>Tipo</th>
                                        <th>Código IATA</th>
                                        <th>Dirección</th>
                                        <th>Coordenadas</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($terminales as $terminal) { 
                                        // Determinar clase de badge según tipo
                                        $badgeClass = 'badge-secondary';
                                        $icono = 'fa-bus';
                                        switch($terminal['idTipoTransporte']) {
                                            case 1: $badgeClass = 'badge-bus'; $icono = 'fa-bus'; break;
                                            case 2: $badgeClass = 'badge-plane'; $icono = 'fa-plane'; break;
                                            case 3: $badgeClass = 'badge-train'; $icono = 'fa-train'; break;
                                            case 4: $badgeClass = 'badge-ship'; $icono = 'fa-ship'; break;
                                        }
                                    ?>
                                        <tr data-tipo="<?=$terminal['idTipoTransporte']?>" data-ciudad="<?=strtolower($terminal['ciudad'])?>">
                                            <td><?=$terminal['idTerminal']?></td>
                                            <td><strong><?=$terminal['nombre']?></strong></td>
                                            <td><?=$terminal['ciudad']?></td>
                                            <td>
                                                <span class="badge <?=$badgeClass?>">
                                                    <i class="fas <?=$icono?>"></i> <?=$terminal['tipo_transporte_nombre']?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if (!empty($terminal['codigo_iata'])) { ?>
                                                    <span class="badge badge-info"><?=$terminal['codigo_iata']?></span>
                                                <?php } else { ?>
                                                    <span class="text-muted">-</span>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <?=!empty($terminal['direccion']) ? $terminal['direccion'] : '-'?>
                                                </small>
                                            </td>
                                            <td>
                                                <?php if (!empty($terminal['latitud']) && !empty($terminal['longitud'])) { ?>
                                                    <a href="https://www.google.com/maps?q=<?=$terminal['latitud']?>,<?=$terminal['longitud']?>" 
                                                       target="_blank" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-map-marked-alt"></i> Ver mapa
                                                    </a>
                                                <?php } else { ?>
                                                    <span class="text-muted">-</span>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <?php if ($terminal['habilitado']) { ?>
                                                    <span class="badge badge-success">Activo</span>
                                                <?php } else { ?>
                                                    <span class="badge badge-secondary">Inactivo</span>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <a href="terminalEditar.php?id=<?=$terminal['idTerminal']?>" 
                                                   class="btn btn-sm btn-warning" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button onclick="eliminarTerminal(<?=$terminal['idTerminal']?>, '<?=$terminal['nombre']?>')" 
                                                        class="btn btn-sm btn-danger" title="Eliminar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <?php if (empty($terminales)) { ?>
                            <div class="alert alert-info text-center">
                                <i class="fas fa-info-circle"></i> No hay terminales registradas. 
                                <a href="terminalAlta.php">Crear primera terminal</a>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Filtro por tipo
    $('#filtroTipo').change(function() {
        var tipo = $(this).val();
        if (tipo === '') {
            $('#tablaTerminales tbody tr').show();
        } else {
            $('#tablaTerminales tbody tr').hide();
            $('#tablaTerminales tbody tr[data-tipo="' + tipo + '"]').show();
        }
    });
    
    // Filtro por ciudad
    $('#filtroCiudad').on('keyup', function() {
        var ciudad = $(this).val().toLowerCase();
        $('#tablaTerminales tbody tr').each(function() {
            var ciudadRow = $(this).data('ciudad');
            if (ciudadRow.indexOf(ciudad) > -1) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
    
    // Eliminar terminal
    function eliminarTerminal(id, nombre) {
        if (confirm('¿Estás seguro de eliminar la terminal "' + nombre + '"?\n\nEsto puede afectar rutas y viajes asociados.')) {
            window.location.href = 'ctrl/ctrlTerminales.php?action=delete&id=' + id;
        }
    }
    </script>
</body>
</html>
