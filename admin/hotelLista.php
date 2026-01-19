<?php
// Verificar permisos de acceso
require_once(__DIR__ . "/classes/permisos.php");
require_once(__DIR__ . "/includes/permisos_helper.php");
$permisos = new PermisosManager($GLOBALS['pdo'], $_SESSION['login'] ?? []);
$permisos->verificarAcceso('hotelLista');

include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");

require_once("classes/transporte.php");

// Obtener todos los hoteles (ubicaciones tipo 'hotel')
$stmt = $GLOBALS['pdo']->prepare("SELECT * FROM ubicacion WHERE tipo = 'hotel' ORDER BY ciudad, nombre");
$stmt->execute();
$hoteles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">
                        <i class="fas fa-hotel"></i> Hoteles
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item active">Hoteles</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            
            <!-- Botón Nuevo -->
            <div class="row mb-3">
                <div class="col-12">
                    <a href="hotelAlta.php" class="btn btn-danger">
                        <i class="fas fa-plus"></i> Nuevo Hotel
                    </a>
                </div>
            </div>

            <!-- Card con DataTable -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-danger text-white">
                            <h5 class="mb-0"><i class="fas fa-list"></i> Hoteles</h5>
                        </div>
                        <div class="card-body">
                            <?php if (count($hoteles) > 0): ?>
                            <table class="table table-striped table-bordered" id="hotelTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Ciudad</th>
                                        <th>Dirección</th>
                                        <th>Teléfono</th>
                                        <th>Email</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($hoteles as $hotel): ?>
                                    <tr>
                                        <td><strong><?= htmlspecialchars($hotel['idUbicacion']) ?></strong></td>
                                        <td>
                                            <i class="fas fa-hotel" style="color: #ff6b6b; margin-right: 8px;"></i>
                                            <?= htmlspecialchars($hotel['nombre']) ?>
                                        </td>
                                        <td><?= htmlspecialchars($hotel['ciudad'] ?? '-') ?></td>
                                        <td>
                                            <small class="text-muted">
                                                <?= htmlspecialchars($hotel['direccion'] ?? '-') ?>
                                            </small>
                                        </td>
                                        <td>
                                            <?php if (!empty($hotel['telefono'])): ?>
                                                <a href="tel:<?= htmlspecialchars($hotel['telefono']) ?>">
                                                    <?= htmlspecialchars($hotel['telefono']) ?>
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if (!empty($hotel['email'])): ?>
                                                <a href="mailto:<?= htmlspecialchars($hotel['email']) ?>">
                                                    <?= htmlspecialchars($hotel['email']) ?>
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($hotel['habilitado']): ?>
                                                <span class="badge badge-success">Activo</span>
                                            <?php else: ?>
                                                <span class="badge badge-danger">Inactivo</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="hotelAlta.php?id=<?= htmlspecialchars($hotel['idUbicacion']) ?>" 
                                                   class="btn btn-warning" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button class="btn btn-danger" onclick="eliminarHotel(<?= $hotel['idUbicacion'] ?>)" title="Eliminar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <?php else: ?>
                            <div class="alert alert-info">
                                <strong>Sin hoteles registrados.</strong>
                                <a href="hotelAlta.php" class="btn btn-sm btn-info ml-2">
                                    <i class="fas fa-plus"></i> Nuevo Hotel
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>

<script>
function eliminarHotel(idParada) {
    if (!confirm('¿Eliminar este hotel?')) return;
    
    $.post('ctrl/ctrlHoteles.php', {
        action: 'delete',
        idParada: idParada
    }, function(response) {
        try {
            var res = typeof response === 'string' ? JSON.parse(response) : response;
            if (res.success) {
                location.reload();
            } else {
                alert('Error: ' + res.message);
            }
        } catch(e) {
            alert('Error: ' + response);
        }
    });
}

$(document).ready(function() {
    $('#hotelTable').DataTable({
        "language": {
            "sSearch": "Buscar:",
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ hoteles",
            "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ hoteles",
            "sInfoEmpty": "No hay hoteles"
        },
        "pageLength": 25,
        "order": [[1, 'asc']]
    });
});
</script>

<?php include("includes/footer.php"); ?>
