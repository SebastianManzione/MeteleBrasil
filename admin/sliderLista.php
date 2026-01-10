<?php
include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");
require_once("classes/conexion.php");

// Verificar permisos (solo admin)
if (!isset($_SESSION['login']['idUsuario']) || $_SESSION['login']['idUsuario'] != 1) {
    echo '<div class="content-wrapper"><div class="container-fluid"><div class="alert alert-danger">⛔ Acceso Denegado</div></div></div>';
    include("includes/footer.php");
    die();
}

$mensaje = '';

// Procesar acciones
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accion'])) {
        if ($_POST['accion'] === 'subir') {
            // Subir nueva imagen
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
                $permitidos = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
                if (in_array($_FILES['imagen']['type'], $permitidos)) {
                    $ext = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
                    $nombre_archivo = 'slider' . time() . '.' . $ext;
                    $ruta_destino = '../img/' . $nombre_archivo;
                    
                    if (move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_destino)) {
                        $orden = isset($_POST['orden']) ? intval($_POST['orden']) : 0;
                        $activo = isset($_POST['activo']) ? 1 : 0;
                        $intervalo = isset($_POST['intervalo']) ? intval($_POST['intervalo']) : 5000;
                        
                        $sql = "INSERT INTO slider (imagen, orden, activo, intervalo) VALUES (?, ?, ?, ?)";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([$nombre_archivo, $orden, $activo, $intervalo]);
                        
                        $mensaje = '<div class="alert alert-success">✓ Imagen agregada correctamente</div>';
                    } else {
                        $mensaje = '<div class="alert alert-danger">✗ Error al subir la imagen</div>';
                    }
                } else {
                    $mensaje = '<div class="alert alert-danger">✗ Formato no permitido. Use JPG, PNG o WEBP</div>';
                }
            }
        } elseif ($_POST['accion'] === 'actualizar') {
            $id = intval($_POST['id']);
            $orden = intval($_POST['orden']);
            $intervalo = intval($_POST['intervalo']);
            
            $sql = "UPDATE slider SET orden = ?, intervalo = ? WHERE idSlider = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$orden, $intervalo, $id]);
            $mensaje = '<div class="alert alert-success">✓ Actualizado correctamente</div>';
        } elseif ($_POST['accion'] === 'eliminar') {
            $id = intval($_POST['id']);
            $sql = "SELECT imagen FROM slider WHERE idSlider = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($row) {
                @unlink('../img/' . $row['imagen']);
                $sql = "DELETE FROM slider WHERE idSlider = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$id]);
                $mensaje = '<div class="alert alert-success">✓ Imagen eliminada</div>';
            }
        } elseif ($_POST['accion'] === 'toggle') {
            $id = intval($_POST['id']);
            $sql = "UPDATE slider SET activo = NOT activo WHERE idSlider = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);
            $mensaje = '<div class="alert alert-success">✓ Estado actualizado</div>';
        }
    }
}

// Obtener imágenes
$sql = "SELECT * FROM slider ORDER BY orden ASC, idSlider ASC";
$stmt = $pdo->query($sql);
$imagenes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-images"></i> Gestión de Slider</h1>
                </div>
                <div class="col-sm-6">
                    <button class="btn btn-primary float-right" data-toggle="modal" data-target="#modalSubir">
                        <i class="fas fa-upload"></i> Subir Imagen
                    </button>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <?= $mensaje ?>
            
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Imágenes del Slider</h3>
                </div>
                <div class="card-body">
                    <?php if (empty($imagenes)): ?>
                        <div class="alert alert-info">No hay imágenes en el slider. Sube la primera imagen.</div>
                    <?php else: ?>
                        <div class="row">
                            <?php foreach ($imagenes as $img): ?>
                                <div class="col-md-4 mb-4">
                                    <div class="card">
                                        <img src="../img/<?= htmlspecialchars($img['imagen']) ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
                                        <div class="card-body">
                                            <form method="post" class="mb-2">
                                                <input type="hidden" name="accion" value="actualizar">
                                                <input type="hidden" name="id" value="<?= $img['idSlider'] ?>">
                                                <div class="form-group mb-2">
                                                    <label class="small mb-1"><strong>Orden:</strong></label>
                                                    <input type="number" name="orden" class="form-control form-control-sm" value="<?= $img['orden'] ?>" min="0">
                                                </div>
                                                <div class="form-group mb-2">
                                                    <label class="small mb-1"><strong>Intervalo (ms):</strong></label>
                                                    <input type="number" name="intervalo" class="form-control form-control-sm" value="<?= $img['intervalo'] ?? 5000 ?>" min="1000" step="500">
                                                    <small class="form-text text-muted"><?= number_format(($img['intervalo'] ?? 5000) / 1000, 1) ?>s</small>
                                                </div>
                                                <button type="submit" class="btn btn-sm btn-success btn-block">
                                                    <i class="fas fa-save"></i> Guardar
                                                </button>
                                            </form>
                                            <p class="mb-2">
                                                <strong>Estado:</strong> 
                                                <span class="badge badge-<?= $img['activo'] ? 'success' : 'secondary' ?>">
                                                    <?= $img['activo'] ? 'Activo' : 'Inactivo' ?>
                                                </span>
                                            </p>
                                            <div class="btn-group btn-block">
                                                <form method="post" class="d-inline">
                                                    <input type="hidden" name="accion" value="toggle">
                                                    <input type="hidden" name="id" value="<?= $img['idSlider'] ?>">
                                                    <button type="submit" class="btn btn-sm btn-info">
                                                        <i class="fas fa-toggle-on"></i> Toggle
                                                    </button>
                                                </form>
                                                <form method="post" class="d-inline" onsubmit="return confirm('¿Eliminar esta imagen?')">
                                                    <input type="hidden" name="accion" value="eliminar">
                                                    <input type="hidden" name="id" value="<?= $img['idSlider'] ?>">
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="fas fa-trash"></i> Eliminar
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal Subir -->
<div class="modal fade" id="modalSubir" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">Subir Imagen al Slider</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="accion" value="subir">
                    <div class="form-group">
                        <label>Imagen (JPG, PNG, WEBP)</label>
                        <input type="file" name="imagen" class="form-control" required accept="image/jpeg,image/jpg,image/png,image/webp">
                    </div>
                    <div class="form-group">
                        <label>Orden</label>
                        <input type="number" name="orden" class="form-control" value="<?= count($imagenes) + 1 ?>" min="0">
                    </div>
                    <div class="form-group">
                        <label>Intervalo de transición (ms)</label>
                        <input type="number" name="intervalo" class="form-control" value="5000" min="1000" step="500">
                        <small class="form-text text-muted">Tiempo en milisegundos (5000 = 5 segundos)</small>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="activo" class="form-check-input" id="activo" checked>
                        <label class="form-check-label" for="activo">Activo</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Subir</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>
