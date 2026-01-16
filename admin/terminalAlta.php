<?php
session_start();
require_once("classes/usuario.php");
require_once("classes/transporte.php");

// Validar sesión admin
if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit();
}

$tipos = getAllTiposTransporte();

// Si es edición, cargar datos
$esEdicion = false;
$terminal = null;
if (isset($_GET['id'])) {
    $esEdicion = true;
    $terminal = getTerminal($_GET['id']);
    if (!$terminal) {
        header("Location: terminalesLista.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$esEdicion ? 'Editar' : 'Nueva'?> Terminal - Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php include("navbar.php"); ?>
    
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-map-marker-alt"></i> 
                            <?=$esEdicion ? 'Editar Terminal' : 'Nueva Terminal'?>
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="ctrl/ctrlTerminales.php" method="POST" id="formTerminal">
                            <input type="hidden" name="action" value="<?=$esEdicion ? 'update' : 'insert'?>">
                            <?php if ($esEdicion) { ?>
                                <input type="hidden" name="idTerminal" value="<?=$terminal['idTerminal']?>">
                            <?php } ?>
                            
                            <!-- Información Básica -->
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label><i class="fas fa-building"></i> Nombre de la Terminal <span class="text-danger">*</span></label>
                                        <input type="text" name="nombre" class="form-control" required
                                               placeholder="Ej: Terminal de Retiro, Aeropuerto Ezeiza"
                                               value="<?=$esEdicion ? $terminal['nombre'] : ''?>">
                                        <small class="form-text text-muted">Nombre completo y descriptivo</small>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><i class="fas fa-bus"></i> Tipo de Transporte <span class="text-danger">*</span></label>
                                        <select name="idTipoTransporte" class="form-control" required>
                                            <option value="">Seleccionar...</option>
                                            <?php foreach ($tipos as $tipo) { ?>
                                                <option value="<?=$tipo['idTipoTransporte']?>"
                                                        <?=($esEdicion && $terminal['idTipoTransporte'] == $tipo['idTipoTransporte']) ? 'selected' : ''?>>
                                                    <?=$tipo['nombre']?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Ubicación -->
                            <h5 class="mt-4 mb-3 text-primary"><i class="fas fa-map-marked-alt"></i> Ubicación</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Ciudad <span class="text-danger">*</span></label>
                                        <input type="text" name="ciudad" class="form-control" required
                                               placeholder="Ej: Buenos Aires"
                                               value="<?=$esEdicion ? $terminal['ciudad'] : ''?>">
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Código IATA <small class="text-muted">(solo aeropuertos)</small></label>
                                        <input type="text" name="codigo_iata" class="form-control" maxlength="10"
                                               placeholder="Ej: EZE, GRU, GIG"
                                               value="<?=$esEdicion ? $terminal['codigo_iata'] : ''?>">
                                        <small class="form-text text-muted">Código internacional de aeropuerto</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label>Dirección completa</label>
                                <input type="text" name="direccion" class="form-control"
                                       placeholder="Ej: Av. Ramos Mejía 1680"
                                       value="<?=$esEdicion ? $terminal['direccion'] : ''?>">
                            </div>
                            
                            <!-- Coordenadas GPS -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><i class="fas fa-map-pin"></i> Latitud</label>
                                        <input type="text" name="latitud" class="form-control" 
                                               placeholder="Ej: -34.588886"
                                               value="<?=$esEdicion ? $terminal['latitud'] : ''?>">
                                        <small class="form-text text-muted">Formato decimal: -34.588886</small>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><i class="fas fa-map-pin"></i> Longitud</label>
                                        <input type="text" name="longitud" class="form-control"
                                               placeholder="Ej: -58.373993"
                                               value="<?=$esEdicion ? $terminal['longitud'] : ''?>">
                                        <small class="form-text text-muted">Formato decimal: -58.373993</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> 
                                <strong>Tip:</strong> Podés obtener las coordenadas desde 
                                <a href="https://www.google.com/maps" target="_blank">Google Maps</a> 
                                haciendo click derecho en el mapa → "¿Qué hay aquí?"
                            </div>
                            
                            <!-- Observaciones -->
                            <div class="form-group">
                                <label>Observaciones</label>
                                <textarea name="observaciones" class="form-control" rows="3"
                                          placeholder="Información adicional sobre la terminal..."><?=$esEdicion ? $terminal['observaciones'] : ''?></textarea>
                            </div>
                            
                            <!-- Estado -->
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="habilitado" 
                                           name="habilitado" value="1"
                                           <?=(!$esEdicion || $terminal['habilitado']) ? 'checked' : ''?>>
                                    <label class="custom-control-label" for="habilitado">
                                        <strong>Terminal habilitada</strong>
                                        <small class="d-block text-muted">Si está deshabilitada, no aparecerá en búsquedas</small>
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Botones -->
                            <hr>
                            <div class="d-flex justify-content-between">
                                <a href="terminalesLista.php" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Volver
                                </a>
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save"></i> <?=$esEdicion ? 'Actualizar' : 'Guardar'?> Terminal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Ayuda -->
                <div class="card mt-3 bg-light">
                    <div class="card-body">
                        <h6 class="text-primary"><i class="fas fa-question-circle"></i> Ayuda</h6>
                        <p class="mb-0">
                            <strong>Las terminales</strong> son los puntos de salida y llegada de las rutas de transporte. 
                            Una vez creadas, podrás asignarlas a rutas y viajes.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Validación del formulario
    $('#formTerminal').on('submit', function(e) {
        var latitud = $('input[name="latitud"]').val();
        var longitud = $('input[name="longitud"]').val();
        
        // Validar formato de coordenadas si están presentes
        if (latitud && !isValidCoordinate(latitud, -90, 90)) {
            alert('La latitud debe estar entre -90 y 90');
            e.preventDefault();
            return false;
        }
        
        if (longitud && !isValidCoordinate(longitud, -180, 180)) {
            alert('La longitud debe estar entre -180 y 180');
            e.preventDefault();
            return false;
        }
    });
    
    function isValidCoordinate(value, min, max) {
        var num = parseFloat(value);
        return !isNaN(num) && num >= min && num <= max;
    }
    </script>
</body>
</html>
