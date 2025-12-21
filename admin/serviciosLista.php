<?php
include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");
require("classes/functions.php");
require("classes/categoria.php");
require("classes/texto_miniaturas.php");
require("classes/tipos_tarifa.php");
require("classes/accesibilidad.php");
require("classes/comision_prestador.php");
require("classes/idiomas.php");
require("classes/edades.php");
require("classes/destinos.php");
require("classes/servicio.php");
require("classes/fotos_servicio.php");
require("classes/prestador.php");

// Manejo centralizado de las acciones POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $resul = false;
    if (isset($_POST["desHabilitarServicio"])) {
        quitarDestacarServicio($_POST["desHabilitarServicio"]);
        $resul = desHabilitarServicio($_POST["desHabilitarServicio"]);
        if ($resul) {
            alertar("Servicio deshabilitado con ¨¦xito.", "warning");
        }
    } elseif (isset($_POST["habilitarServicio"])) {
        $resul = habilitarServicio($_POST["habilitarServicio"]);
        if ($resul) {
            alertar("Servicio habilitado con ¨¦xito.", "success");
        }
    } elseif (isset($_POST["destacarServicio"])) {
        $resul = destacarServicio($_POST["destacarServicio"]);
        if ($resul) {
            alertar("Servicio destacado con ¨¦xito.", "success");
        }
    } elseif (isset($_POST["quitarDestacarServicio"])) {
        $resul = quitarDestacarServicio($_POST["quitarDestacarServicio"]);
        if ($resul) {
            alertar("Se ha quitado el destacado del servicio.", "info");
        }
    } elseif (isset($_POST["verComoPrestador"])) {
        $idPrestador = $_POST["idPrestador"];
    }
}

// Determinar qu¨¦ servicios mostrar
if ($_SESSION["login"]["rol"] == 1) {
    if (isset($_POST['verComoPrestador']) && $_POST['idPrestador'] != -5) {
        $idPrestador = $_POST['idPrestador'];
        $servicios = getAllServiciosPrestador($idPrestador);
    } else {
        $servicios = getAllServicios();
    }
} else {
    $servicios = getAllServiciosPrestador($_SESSION["login"]["idPrestador"]);
}
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark"><?= $lang["lista_de_servicios"]; ?></h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#"><?= $lang["servicios"]; ?></a></li>
                        <li class="breadcrumb-item active"><?= $lang["lista_de_servicios"]; ?></li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><?= $lang["lista_de_servicios"]; ?></h3>
                            <?php if ($_SESSION['login']["idUsuario"] == 1) : ?>
                                <div class="card-tools">
                                    <form method="post" class="form-inline">
                                        <div class="input-group">
                                            <select name="idPrestador" class="form-control">
                                                <option value="-5">Ver todos los prestadores</option>
                                                <?php
                                                $prestadores = getPrestadores();
                                                foreach ($prestadores as $value) {
                                                    $selected = (isset($idPrestador) && $value['idPrestador'] == $idPrestador) ? " selected " : "";
                                                    echo '<option value="' . $value['idPrestador'] . '"' . $selected . '>' . $value["nombre"] . '</option>';
                                                }
                                                ?>
                                            </select>
                                            <div class="input-group-append">
                                                <button class="btn btn-success" name="verComoPrestador">Filtrar</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="tabla_servicios" class="table table-bordered table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th><?= $lang["foto"]; ?></th>
                                        <th><?= $lang["nombre"]; ?></th>
                                        <th><?= $lang["destino"]; ?></th>
                                        <th><?= $lang["fecha_alta"]; ?></th>
                                        <?php if ($_SESSION["login"]["rol"] == 1) { ?>
                                            <th><?= $lang["acciÃ³n"]; ?></th>
                                        <?php } ?>
                                        <th><?= $lang["detalles"]; ?></th>
                                        <th>Vista Publica</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($servicios as $servicio) :
                                        $idServicio = $servicio["idServicio"];
                                        $fotos = getFotoMiniaturaServicio($idServicio);
                                        $destino = getDestino($servicio["idDestino"]);
                                        $fechaAlta = $servicio["fechaAlta"];
                                    ?>
                                        <tr>
                                            <td>
                                                <?php if (!empty($fotos)) : ?>
                                                    <img style="width: 100px; border-radius: 5px;" src="classes/imgServicio/<?= $fotos[0]['ruta'] ?>" alt="<?= htmlspecialchars($servicio["nombre_servicio"]) ?>">
                                                <?php endif; ?>
                                            </td>
                                            <td><?= htmlspecialchars($servicio["nombre_servicio"]) ?></td>
                                            <td><?= !empty($destino) ? htmlspecialchars($destino[0]["nombre"]) : 'N/A' ?></td>
                                            <td><?= date("d-m-Y", strtotime($fechaAlta)) ?></td>
                                            <?php if ($_SESSION["login"]["rol"] == 1) : ?>
                                                <td>
                                                    <form method="post">
                                                        <div class="btn-group btn-group-sm" role="group">
                                                            <?php if ($servicio["habilitado"] == 0) : ?>
                                                                <button class="btn btn-success" name="habilitarServicio" value="<?= $idServicio ?>"><?= $lang["habilitar"]; ?></button>
                                                            <?php else : ?>
                                                                <button class="btn btn-warning" name="desHabilitarServicio" value="<?= $idServicio; ?>"><?= $lang["deshabilitar"]; ?></button>
                                                            <?php endif; ?>

                                                            <?php if ($servicio["destacado"] == 1) : ?>
                                                                <button class="btn btn-primary" name="quitarDestacarServicio" value="<?= $idServicio; ?>">Quitar Destacado</button>
                                                            <?php else : ?>
                                                                <button class="btn btn-secondary" name="destacarServicio" value="<?= $idServicio; ?>">Destacar</button>
                                                            <?php endif; ?>
                                                        </div>
                                                    </form>
                                                </td>
                                            <?php endif; ?>
                                            <td><a href="servicioVer.php?idServicio=<?= $idServicio; ?>" class="btn btn-sm btn-info"><i class="fas fa-eye"></i> <?= $lang["ver"]; ?></a></td>
                                            <td><a class="btn btn-sm btn-secondary" href="../servicio?id=<?= $idServicio; ?>" target="_blank">veja em metelebrasil.com</a></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php
include("includes/footer.php");
?>

<script>
    $(function() {
        $('#tabla_servicios').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "stateSave": true,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Portuguese-Brasil.json"
            },
            "order": [[ 3, "desc" ]] // Ordenar por fecha de alta descendente por defecto
        });
    });
</script>