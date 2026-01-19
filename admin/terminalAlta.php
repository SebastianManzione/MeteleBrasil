<?php
// Verificar permisos de acceso ANTES de cualquier salida
require_once(__DIR__ . "/classes/permisos.php");
require_once(__DIR__ . "/includes/permisos_helper.php");
$permisos = new PermisosManager($GLOBALS['pdo'], $_SESSION['login'] ?? []);
$permisos->verificarAcceso('terminalAlta');

require_once("classes/transporte.php");

// Conectar a BD experimental ANTES de cualquier include
if (!isset($GLOBALS['pdo_experimental'])) {
    $GLOBALS['pdo_experimental'] = new PDO(
        'mysql:host=localhost;dbname=metelebrasil_experimental;charset=utf8mb4',
        'root',
        ''
    );
    $GLOBALS['pdo_experimental']->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}

// Si es edición, cargar datos de terminal_transporte ANTES de include
$esEdicion = false;
$terminal = null;
if (isset($_GET['id'])) {
    $esEdicion = true;
    $stmt = $GLOBALS['pdo_experimental']->prepare("SELECT * FROM terminal_transporte WHERE idTerminal = :id");
    $stmt->execute(['id' => $_GET['id']]);
    $terminal = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$terminal) {
        // Redirect ANTES de output
        header("Location: terminalesLista.php");
        exit();
    }
}

// Ahora sí, incluir los archivos de layout
include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");

// Cargar tipos de transporte
$stmt = $GLOBALS['pdo_experimental']->query("SELECT * FROM tipo_transporte WHERE habilitado=1 ORDER BY nombre");
$tiposTransporte = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">
                        <i class="fas fa-map-marker-alt"></i> 
                        <?=$esEdicion ? 'Editar Terminal' : 'Nueva Terminal'?>
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Transporte</a></li>
                        <li class="breadcrumb-item"><a href="terminalesLista.php">Terminales</a></li>
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
            <div class="row">
                <!-- Formulario -->
                <div class="col-lg-6">
                    <div class="card shadow">
                        <div class="card-header bg-primary text-white">
                            <h4 class="mb-0">
                                <i class="fas fa-map-marker-alt"></i> 
                                <?=$esEdicion ? 'Editar Terminal' : 'Nueva Terminal'?>
                            </h4>
                        </div>
                        <div class="card-body">
                            <form action="ctrl/ctrlTerminalesNuevo.php" method="POST" id="formTerminal">
                                <input type="hidden" name="action" value="<?=$esEdicion ? 'update' : 'insert'?>">
                                <input type="hidden" name="redirect" value="terminalesLista.php">
                                <?php if ($esEdicion) { ?>
                                    <input type="hidden" name="idTerminal" value="<?=$terminal['idTerminal']?>">
                                <?php } ?>
                                
                                <!-- Información Básica -->
                                <h5 class="text-primary mb-3"><i class="fas fa-info-circle"></i> Información Básica</h5>
                                
                                <div class="form-group">
                                    <label><i class="fas fa-building"></i> Nombre de la Terminal <span class="text-danger">*</span></label>
                                    <input type="text" name="nombre" class="form-control" required
                                           placeholder="Ej: Terminal de Retiro, Aeropuerto Ezeiza"
                                           value="<?=$esEdicion ? $terminal['nombre'] : ''?>">
                                    <small class="form-text text-muted">Nombre completo y descriptivo</small>
                                </div>
                                
                                <div class="form-group">
                                    <label><i class="fas fa-bus"></i> Tipo de Transporte <span class="text-danger">*</span></label>
                                    <select name="idTipoTransporte" class="form-control" required>
                                        <option value="">Seleccione...</option>
                                        <?php foreach ($tiposTransporte as $tipo) {
                                            $idTipo = htmlspecialchars($tipo['idTipoTransporte'] ?? '', ENT_QUOTES, 'UTF-8');
                        $nombreTipo = htmlspecialchars($tipo['nombre'] ?? '', ENT_QUOTES, 'UTF-8');
                        $selected = ($esEdicion && ($terminal['idTipoTransporte'] ?? null) == $tipo['idTipoTransporte']) ? 'selected' : '';
                        ?>
                                            <option value="<?= $idTipo ?>" <?= $selected ?>><?= $nombreTipo ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label><i class="fas fa-address-card"></i> Código IATA <small class="text-muted">(solo aeropuertos)</small></label>
                                    <input type="text" name="codigo_iata" class="form-control" maxlength="10"
                                           placeholder="Ej: EZE, GRU, GIG"
                                           value="<?=$esEdicion ? $terminal['codigo_iata'] : ''?>">
                                    <small class="form-text text-muted">Código internacional de aeropuerto</small>
                                </div>
                                
                                <!-- Ubicación -->
                                <h5 class="text-primary mt-4 mb-3"><i class="fas fa-map-marked-alt"></i> Ubicación</h5>
                                
                                <div class="form-group">
                                    <label><i class="fas fa-search"></i> Buscar ubicación en el mapa</label>
                                    <input id="searchBoxTerminal" type="text" class="form-control" 
                                           placeholder="Buscar dirección, terminal, aeropuerto..." />
                                    <small class="form-text text-muted">Escriba y seleccione de la lista para autocompletar</small>
                                </div>
                                
                                <div class="form-group">
                                    <label>País <span class="text-danger">*</span></label>
                                    <input type="text" name="pais" id="pais" class="form-control" required readonly
                                           placeholder="Se completará automáticamente"
                                           value="<?=$esEdicion ? $terminal['pais'] : ''?>">
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Estado/Provincia <span class="text-danger">*</span></label>
                                            <input type="text" name="estado" id="estado" class="form-control" required readonly
                                                   placeholder="Se completará automáticamente"
                                                   value="<?=$esEdicion ? $terminal['estado'] : ''?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Ciudad <span class="text-danger">*</span></label>
                                            <input type="text" name="ciudad" id="ciudad" class="form-control" required readonly
                                                   placeholder="Se completará automáticamente"
                                                   value="<?=$esEdicion ? $terminal['ciudad'] : ''?>">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label>Dirección completa</label>
                                    <input type="text" name="direccion" id="direccion" class="form-control" readonly
                                           placeholder="Se completará automáticamente"
                                           value="<?=$esEdicion ? $terminal['direccion'] : ''?>">
                                </div>
                                
                                <!-- Coordenadas GPS -->
                                <h5 class="text-primary mt-4 mb-3"><i class="fas fa-map-pin"></i> Coordenadas GPS</h5>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><i class="fas fa-map-pin"></i> Latitud</label>
                                            <input type="text" name="latitud" id="latitud" class="form-control" 
                                                   placeholder="Ej: -34.588886"
                                                   value="<?=$esEdicion ? $terminal['latitud'] : ''?>">
                                            <small class="form-text text-muted">O selecciona en el mapa →</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><i class="fas fa-map-pin"></i> Longitud</label>
                                            <input type="text" name="longitud" id="longitud" class="form-control"
                                                   placeholder="Ej: -58.373993"
                                                   value="<?=$esEdicion ? $terminal['longitud'] : ''?>">
                                            <small class="form-text text-muted">O selecciona en el mapa →</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Contacto -->
                                <h5 class="text-primary mt-4 mb-3"><i class="fas fa-phone"></i> Contacto</h5>
                                
                                <div class="form-group">
                                    <label>Teléfono</label>
                                    <input type="text" name="telefono" class="form-control"
                                           placeholder="Ej: +54 11 4361-1111"
                                           value="<?=$esEdicion ? $terminal['telefono'] : ''?>">
                                </div>
                                
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control"
                                           placeholder="Ej: info@terminal.com.ar"
                                           value="<?=$esEdicion ? $terminal['email'] : ''?>">
                                </div>
                                
                                <div class="form-group">
                                    <label>Sitio Web</label>
                                    <input type="url" name="sitio_web" class="form-control"
                                           placeholder="Ej: www.terminal.com.ar"
                                           value="<?=$esEdicion ? $terminal['sitio_web'] : ''?>">
                                </div>
                                
                                <!-- Descripción -->
                                <div class="form-group">
                                    <label>Descripción</label>
                                    <textarea name="descripcion" class="form-control" rows="3"
                                              placeholder="Información adicional sobre la terminal..."><?=$esEdicion ? $terminal['descripcion'] : ''?></textarea>
                                </div>
                                
                                <div class="form-group">
                                    <label>Horario de Atención</label>
                                    <input type="text" name="horario_atencion" class="form-control"
                                           placeholder="Ej: Lunes a Viernes 06:00 - 22:00, Sábados y Domingos 08:00 - 20:00"
                                           value="<?=$esEdicion ? $terminal['horario_atencion'] : ''?>">
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
                </div>
                
                <!-- Mapa Google Maps -->
                <div class="col-lg-6">
                    <div class="card shadow">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-map"></i> Mapa Interactivo
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div id="map" style="width: 100%; height: 500px;"></div>
                            <div class="p-3">
                                <p class="text-muted mb-0">
                                    <i class="fas fa-info-circle"></i> 
                                    Haz click en el mapa para seleccionar la ubicación. Las coordenadas se llenarán automáticamente.
                                </p>
                            </div>
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

<!-- Google Maps API (clave de producción desde config) -->
<?php
// Asegurar que la clave API está disponible
if (!defined('GOOGLE_MAPS_API_KEY')) {
    require_once(__DIR__ . '/../config/config.php');
}
?>
<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo defined('GOOGLE_MAPS_API_KEY') ? GOOGLE_MAPS_API_KEY : ''; ?>&libraries=places"></script>

<script>
let map;
let marker;
let geocoder;
let autocomplete;

function initMap() {
    // Coordenadas iniciales
    let initialLat = <?= ($esEdicion && $terminal['latitud']) ? $terminal['latitud'] : '-34.6037' ?>;
    let initialLng = <?= ($esEdicion && $terminal['longitud']) ? $terminal['longitud'] : '-58.3816' ?>;
    
    const initialLocation = { lat: parseFloat(initialLat), lng: parseFloat(initialLng) };
    
    // Crear mapa
    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 12,
        center: initialLocation,
        mapTypeId: 'roadmap'
    });
    
    // Inicializar geocoder
    geocoder = new google.maps.Geocoder();
    
    // Crear marcador inicial
    marker = new google.maps.Marker({
        position: initialLocation,
        map: map,
        draggable: true,
        title: '<?= $esEdicion ? addslashes($terminal['nombre']) : 'Arrastre para posicionar' ?>'
    });
    
    // Configurar autocomplete
    const searchInput = document.getElementById('searchBoxTerminal');
    autocomplete = new google.maps.places.Autocomplete(searchInput, {
        types: ['establishment', 'geocode']
    });
    autocomplete.bindTo('bounds', map);
    
    // Listener para autocomplete
    autocomplete.addListener('place_changed', function() {
        const place = autocomplete.getPlace();
        
        if (!place.geometry) {
            alert('Seleccione un lugar de la lista de sugerencias');
            return;
        }
        
        // Centrar mapa
        map.setCenter(place.geometry.location);
        map.setZoom(17);
        
        // Mover marcador
        marker.setPosition(place.geometry.location);
        
        // Extraer datos del lugar
        geocodePlace(place);
    });
    
    // Listener para arrastrar marcador
    marker.addListener('dragend', function() {
        const position = marker.getPosition();
        const lat = position.lat();
        const lng = position.lng();
        
        // Reverse geocoding
        geocoder.geocode({ location: { lat: lat, lng: lng } }, function(results, status) {
            if (status === 'OK' && results[0]) {
                geocodePlace(results[0]);
            }
        });
    });
    
    // Listener para click en el mapa
    map.addListener('click', function(event) {
        const lat = event.latLng.lat();
        const lng = event.latLng.lng();
        
        // Mover marcador
        marker.setPosition(event.latLng);
        
        // Reverse geocoding
        geocoder.geocode({ location: { lat: lat, lng: lng } }, function(results, status) {
            if (status === 'OK' && results[0]) {
                geocodePlace(results[0]);
            }
        });
    });
}

function geocodePlace(place) {
    const lat = place.geometry.location.lat();
    const lng = place.geometry.location.lng();
    const address = place.formatted_address;
    
    // Actualizar coordenadas
    document.getElementById('latitud').value = lat.toFixed(8);
    document.getElementById('longitud').value = lng.toFixed(8);
    
    // Actualizar dirección
    document.getElementById('direccion').value = address;
    
    // Extraer componentes de dirección
    let ciudad = '';
    let estado = '';
    let pais = '';
    
    if (place.address_components) {
        place.address_components.forEach(function(component) {
            const types = component.types;
            
            if (types.includes('locality')) {
                ciudad = component.long_name;
            } else if (types.includes('administrative_area_level_2') && !ciudad) {
                ciudad = component.long_name;
            } else if (types.includes('administrative_area_level_1')) {
                estado = component.long_name;
            } else if (types.includes('country')) {
                pais = component.long_name;
            }
        });
    }
    
    // Actualizar campos
    if (ciudad) document.getElementById('ciudad').value = ciudad;
    if (estado) document.getElementById('estado').value = estado;
    if (pais) document.getElementById('pais').value = pais;
    
    console.log('Geocode completo:', { lat, lng, address, ciudad, estado, pais });
}

function updateMapFromInputs() {
    const lat = parseFloat(document.getElementById('latitud').value);
    const lng = parseFloat(document.getElementById('longitud').value);
    
    if (!isNaN(lat) && !isNaN(lng)) {
        const location = { lat: lat, lng: lng };
        map.setCenter(location);
        
        if (marker) {
            marker.setPosition(location);
        } else {
            marker = new google.maps.Marker({
                position: location,
                map: map,
                draggable: true
            });
            
            marker.addListener('dragend', function() {
                const newLat = marker.getPosition().lat();
                const newLng = marker.getPosition().lng();
                document.getElementById('latitud').value = newLat.toFixed(6);
                document.getElementById('longitud').value = newLng.toFixed(6);
            });
        }
    }
}

// Inicializar mapa cuando el documento esté listo
document.addEventListener('DOMContentLoaded', initMap);

// Validación del formulario
document.getElementById('formTerminal').addEventListener('submit', function(e) {
    const latitud = document.querySelector('input[name="latitud"]').value;
    const longitud = document.querySelector('input[name="longitud"]').value;
    
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

function isValidCoordinate(num, min, max) {
    return !isNaN(num) && num >= min && num <= max;
}

// Enviar con fetch + SweetAlert
document.addEventListener('DOMContentLoaded', function() {
    var form = document.getElementById('formTerminal');
    if (!form) return;
    form.addEventListener('submit', async function(ev) {
        ev.preventDefault();

        const formData = new FormData(form);
        try {
            const resp = await fetch('ctrl/ctrlTerminalesNuevo.php', {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json'
                }
            });
            const data = await resp.json();

            if (data && data.success) {
                if (window.Swal) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Terminal guardada',
                        text: data.message || 'Operación exitosa',
                        timer: 1800,
                        showConfirmButton: false
                    }).then(function() {
                        window.location.href = 'terminalesLista.php';
                    });
                } else {
                    alert(data.message || 'Terminal guardada');
                    window.location.href = 'terminalesLista.php';
                }
            } else {
                const msg = (data && data.message) ? data.message : 'No se pudo guardar';
                if (window.Swal) {
                    Swal.fire({ icon: 'error', title: 'Error', text: msg });
                } else {
                    alert(msg);
                }
            }
        } catch (err) {
            const msg = 'Error al guardar: ' + err.message;
            if (window.Swal) {
                Swal.fire({ icon: 'error', title: 'Error', text: msg });
            } else {
                alert(msg);
            }
        }
    });
});
</script>

</body>
</html>
