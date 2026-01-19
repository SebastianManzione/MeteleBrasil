<?php
// Verificar permisos de acceso
require_once(__DIR__ . "/classes/permisos.php");
require_once(__DIR__ . "/includes/permisos_helper.php");
$permisos = new PermisosManager($GLOBALS['pdo'], $_SESSION['login'] ?? []);
$permisos->verificarAcceso('hotelAlta');

include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");

require_once("classes/transporte.php");

// Obtener ID si es edición
$idUbicacion = isset($_GET['id']) ? intval($_GET['id']) : 0;
$hotel = null;

if ($idUbicacion > 0) {
    $stmt = $GLOBALS['pdo']->prepare("SELECT * FROM ubicacion WHERE idUbicacion = :id AND tipo = 'hotel'");
    $stmt->execute(['id' => $idUbicacion]);
    $hotel = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$hotel) {
        header("Location: hotelLista.php");
        exit();
    }
}

$modo = $hotel ? 'edicion' : 'alta';
$titulo = $modo === 'edicion' ? 'Editar Hotel' : 'Nuevo Hotel';
?>

<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">
                        <i class="fas fa-hotel"></i> <?= htmlspecialchars($titulo) ?>
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="hotelLista.php">Hoteles</a></li>
                        <li class="breadcrumb-item active"><?= htmlspecialchars($modo === 'edicion' ? 'Editar Hotel' : 'Nuevo Hotel') ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header bg-danger text-white">
                            <h5 class="mb-0"><i class="fas fa-hotel"></i> Datos del Hotel</h5>
                        </div>
                        <form method="POST" action="ctrl/ctrlHoteles.php" id="formHotel">
                            <input type="hidden" name="action" value="<?= $modo === 'edicion' ? 'update' : 'insert' ?>">
                            <?php if ($modo === 'edicion'): ?>
                                <input type="hidden" name="idUbicacion" value="<?= htmlspecialchars($hotel['idUbicacion']) ?>">
                            <?php endif; ?>

                            <div class="card-body">
                                <!-- Nombre del Hotel -->
                                <div class="form-group">
                                    <label for="nombre"><i class="fas fa-heading"></i> Nombre del Hotel <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" required 
                                           placeholder="Ej: Hotel Paradise, Hostería La Posada"
                                           value="<?= $hotel ? htmlspecialchars($hotel['nombre']) : '' ?>">
                                    <small class="form-text text-muted">Nombre completo del hotel</small>
                                </div>

                                <!-- Dirección -->
                                <div class="form-row">
                                    <div class="form-group col-md-8">
                                        <label for="direccion"><i class="fas fa-map-marker-alt"></i> Dirección <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="direccion" name="direccion" required 
                                               placeholder="Calle y número"
                                               value="<?= $hotel ? htmlspecialchars($hotel['direccion'] ?? '') : '' ?>">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="codigo_iata"><i class="fas fa-barcode"></i> Código</label>
                                        <input type="text" class="form-control" id="codigo_iata" name="codigo_iata" 
                                               placeholder="Ej: HLP, PSR"
                                               value="<?= $hotel ? htmlspecialchars($hotel['codigo_iata'] ?? '') : '' ?>">
                                        <small class="form-text text-muted">Código único opcional</small>
                                    </div>
                                </div>

                                <!-- Ubicación -->
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="ciudad"><i class="fas fa-city"></i> Ciudad <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="ciudad" name="ciudad" required 
                                               placeholder="Buenos Aires, Mendoza, etc."
                                               value="<?= $hotel ? htmlspecialchars($hotel['ciudad'] ?? '') : '' ?>">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="estado"><i class="fas fa-map"></i> Provincia/Estado <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="estado" name="estado" required 
                                               placeholder="Buenos Aires, Mendoza, etc."
                                               value="<?= $hotel ? htmlspecialchars($hotel['estado'] ?? '') : '' ?>">
                                    </div>
                                </div>

                                <!-- País -->
                                <div class="form-group">
                                    <label for="pais"><i class="fas fa-globe"></i> País <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="pais" name="pais" required 
                                           placeholder="Argentina, Brasil, Paraguay, Uruguay"
                                           value="<?= $hotel ? htmlspecialchars($hotel['pais'] ?? '') : '' ?>">
                                </div>

                                <!-- Coordenadas -->
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="latitud"><i class="fas fa-map-pin"></i> Latitud</label>
                                        <input type="text" class="form-control" id="latitud" name="latitud" 
                                               placeholder="Ej: -34.6037"
                                               value="<?= $hotel ? htmlspecialchars($hotel['latitud'] ?? '') : '' ?>">
                                        <small class="form-text text-muted">Dejar vacío para geocodificar automáticamente</small>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="longitud"><i class="fas fa-map-pin"></i> Longitud</label>
                                        <input type="text" class="form-control" id="longitud" name="longitud" 
                                               placeholder="Ej: -58.3816"
                                               value="<?= $hotel ? htmlspecialchars($hotel['longitud'] ?? '') : '' ?>">
                                    </div>
                                </div>

                                <!-- Información Adicional -->
                                <div class="form-group">
                                    <label for="descripcion"><i class="fas fa-file-alt"></i> Descripción</label>
                                    <textarea class="form-control" id="descripcion" name="descripcion" rows="3" 
                                              placeholder="Descripción del hotel (servicios, comodidades, etc.)"><?= $hotel ? htmlspecialchars($hotel['descripcion'] ?? '') : '' ?></textarea>
                                </div>

                                <!-- Contacto -->
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="telefono"><i class="fas fa-phone"></i> Teléfono</label>
                                        <input type="text" class="form-control" id="telefono" name="telefono" 
                                               placeholder="+54 11 1234-5678"
                                               value="<?= $hotel ? htmlspecialchars($hotel['telefono'] ?? '') : '' ?>">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="email"><i class="fas fa-envelope"></i> Email</label>
                                        <input type="email" class="form-control" id="email" name="email" 
                                               placeholder="info@hotel.com"
                                               value="<?= $hotel ? htmlspecialchars($hotel['email'] ?? '') : '' ?>">
                                    </div>
                                </div>

                                <!-- Estado -->
                                <div class="form-group">
                                    <label>
                                        <input type="checkbox" name="habilitado" value="1" 
                                               <?= (!$hotel || $hotel['habilitado']) ? 'checked' : '' ?>>
                                        <i class="fas fa-toggle-on"></i> Habilitado
                                    </label>
                                    <small class="form-text text-muted">Marcar para que este hotel esté disponible</small>
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check-circle"></i> <?= $modo === 'edicion' ? 'Actualizar' : 'Crear Hotel' ?>
                                </button>
                                <a href="hotelLista.php" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancelar
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Sidebar: Información -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">Información</h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <strong>Tipo:</strong> Hotel<br>
                                <strong>Color:</strong> Rojo (#ff6b6b)<br>
                                <strong>Icono:</strong> fa-hotel
                            </div>

                            <h6>Campos Requeridos:</h6>
                            <ul style="font-size: 13px;">
                                <li>Nombre del hotel</li>
                                <li>Dirección</li>
                                <li>Ciudad</li>
                                <li>Provincia/Estado</li>
                                <li>País</li>
                            </ul>

                            <h6>Campos Opcionales:</h6>
                            <ul style="font-size: 13px;">
                                <li>Código único</li>
                                <li>Coordenadas (lat/lon)</li>
                                <li>Descripción</li>
                                <li>Teléfono</li>
                                <li>Email</li>
                            </ul>

                            <div class="alert alert-warning mt-3">
                                <small>
                                    <strong>Tip:</strong> Deja las coordenadas vacías y se geocodificarán automáticamente
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">Uso en Paquetes</h5>
                        </div>
                        <div class="card-body">
                            <p style="font-size: 13px;">
                                Este hotel puede ser usado como parada en:
                            </p>
                            <ul style="font-size: 13px;">
                                <li>Paquetes turísticos</li>
                                <li>Traslados</li>
                                <li>Tours</li>
                                <li>Excursiones con alojamiento</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include("includes/footer.php"); ?>
