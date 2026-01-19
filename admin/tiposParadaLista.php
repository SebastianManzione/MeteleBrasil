<?php
// Verificar permisos de acceso
require_once(__DIR__ . "/classes/permisos.php");
require_once(__DIR__ . "/includes/permisos_helper.php");
$permisos = new PermisosManager($GLOBALS['pdo'], $_SESSION['login'] ?? []);
$permisos->verificarAcceso('tiposParadaLista');

include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");

require_once("classes/transporte.php");

// Obtener todos los tipos
$tipos = $GLOBALS['pdo']->query("SELECT * FROM tipo_parada ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
    .tipo-badge {
        font-size: 18px;
        padding: 8px 12px;
        border-radius: 5px;
        color: white;
    }
</style>

<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">
                        <i class="fas fa-tag"></i> Tipos de Parada
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Transporte</a></li>
                        <li class="breadcrumb-item active">Tipos</li>
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
                    <a href="tiposParadaAlta.php" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Nuevo Tipo
                    </a>
                </div>
            </div>

            <!-- Card con DataTable -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-list"></i> Lista de Tipos de Parada</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped table-bordered" id="tiposTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Icono</th>
                                        <th>Color</th>
                                        <th>Estado</th>
                                        <th>Paradas</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($tipos as $tipo) { 
                                        // Contar paradas
                                        $stmt = $GLOBALS['pdo']->prepare("SELECT COUNT(*) as total FROM parada WHERE idTipoPrada = :id");
                                        $stmt->execute(['id' => $tipo['idTipoPrada']]);
                                        $cantParadas = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
                                    ?>
                                    <tr>
                                        <td><strong><?= htmlspecialchars($tipo['idTipoPrada']) ?></strong></td>
                                        <td><?= htmlspecialchars($tipo['nombre']) ?></td>
                                        <td>
                                            <i class="fas <?= htmlspecialchars($tipo['icono']) ?>" style="font-size: 18px;"></i>
                                            <code><?= htmlspecialchars($tipo['icono']) ?></code>
                                        </td>
                                        <td>
                                            <span class="tipo-badge" style="background-color: <?= htmlspecialchars($tipo['color']) ?>;">
                                                <?= htmlspecialchars($tipo['color']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($tipo['habilitado']) { ?>
                                                <span class="badge badge-success">Activo</span>
                                            <?php } else { ?>
                                                <span class="badge badge-danger">Inactivo</span>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <span class="badge badge-info"><?= $cantParadas ?></span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="tiposParadaEdita.php?id=<?= htmlspecialchars($tipo['idTipoPrada']) ?>" 
                                                   class="btn btn-warning" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <?php if ($cantParadas == 0) { ?>
                                                    <button class="btn btn-danger" onclick="eliminarTipo(<?= $tipo['idTipoPrada'] ?>)" title="Eliminar">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                <?php } else { ?>
                                                    <button class="btn btn-secondary" disabled title="No se puede eliminar (hay paradas)">
                                                        <i class="fas fa-ban"></i>
                                                    </button>
                                                <?php } ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>

<script>
function eliminarTipo(idTipo) {
    if (!confirm('¿Eliminar este tipo de parada?')) return;
    
    $.post('ctrl/ctrlTiposParada.php', {
        action: 'delete',
        idTipoPrada: idTipo
    }, function(response) {
        if (response.success) {
            location.reload();
        } else {
            alert('Error: ' + response.message);
        }
    }, 'json');
}

$(document).ready(function() {
    $('#tiposTable').DataTable({
        "language": {
            "sSearch": "Buscar:",
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ tipos",
            "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ tipos",
            "sInfoEmpty": "No hay tipos"
        },
        "pageLength": 25,
        "order": [[0, 'asc']]
    });
});
</script>

<?php include("includes/footer.php"); ?>
