<?php 
// Verificar permisos de acceso ANTES de cualquier salida
require_once(__DIR__ . "/classes/permisos.php");
require_once(__DIR__ . "/includes/permisos_helper.php");
$permisos = new PermisosManager($GLOBALS['pdo'], $_SESSION['login'] ?? []);
$permisos->verificarAcceso('reservasEstado');

include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");

require("classes/functions.php");
require("classes/reserva.php");
require("classes/salidas.php");
require("classes/convierte_monedas.php");
require("classes/prestador.php");

// Si el admin está logueado, puede ver como cualquier prestador
if ($_SESSION['login']['idUsuario'] == 1 && isset($_GET['idPrestador'])) {
    $idPrestador = (int)$_GET['idPrestador'];
    $vistaAdmin = false; // Admin viendo como prestador específico
} else {
    $idPrestador = $_SESSION['login']['idPrestador'];
    $vistaAdmin = ($_SESSION['login']['idUsuario'] == 1); // Admin viendo todas las reservas
}

// Obtener lista de prestadores para el select (solo si es admin)
$prestadores = [];
if ($_SESSION['login']['idUsuario'] == 1) {
    $prestadores = getPrestadores();
}

// Función helper: obtener fecha más próxima de una reserva
function getFechaProximaReserva($idReserva) {
    $horarios = getReservaHorarios($idReserva);
    $fechasMinimas = [];

    foreach ($horarios as $h) {
        $salida = getSalida($h['idServicioSalidas']);
        if (!empty($salida) && isset($salida[0]['fecha'])) {
            $fechasMinimas[] = strtotime($salida[0]['fecha']);
        }
    }

    if (empty($fechasMinimas)) {
        return PHP_INT_MAX; // Retornar valor alto si no hay fechas
    }

    return min($fechasMinimas);
}
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark"><?=$lang["reservas"];?> </h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#"><?=$lang["reservas"];?> </a></li>
            <li class="breadcrumb-item active"><?=$lang["estado_de_reservas"];?> </li>
          </ol>
        </div>
      </div>
      <?php if ($_SESSION['login']['idUsuario'] == 1 && count($prestadores) > 0): ?>
      <div class="row mt-3">
        <div class="col-sm-12">
          <div class="alert alert-info">
            <strong><i class="fas fa-user-tie"></i> Ver como Prestador:</strong>
            <select id="selectPrestador" class="form-control d-inline-block ml-2" style="width: auto; display: inline-block;">
              <option value="">-- Seleccione un prestador --</option>
              <?php foreach ($prestadores as $prest): ?>
                <option value="<?=$prest['idPrestador']?>" <?= ($idPrestador == $prest['idPrestador']) ? 'selected' : '' ?>>
                  <?=$prest['nombre'] ?? 'Sin nombre'?> (ID: <?=$prest['idPrestador']?>)
                </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
      </div>
      <?php endif; ?>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      <button type="button" class="btn btn-secondary btn-lg btn-block"><?=$lang["reservas"];?> </button>

      <div id="accordion">
        <div class="card">
          <div class="card-header" id="headingOne">
            <h5 class="mb-0">
              <button class="btn btn-success btn-lg btn-block" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne"><i class="fas fa-check-double"></i> <?=$lang["confirmadas"];?> </button>
            </h5>
          </div>
          <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
            <div class="card-body">
              <h3><?=$lang["lista_de_reservas_confirmadas"];?> </h3>
              <div class="row">
                <div class="table-responsive">
                  <table class="table" id="tablaCarritoConfirmadas">
                    <thead>
                      <tr>
                        <th scope="col"><?=$lang["nombre"];?> </th>
                        <th scope="col"><?=$lang["cod-carrito"];?> </th>
                        <th scope="col"><?=$lang["fecha_de_contratacion"];?> </th>
                        <th scope="col"><?=$lang["dia_del_evento"];?> </th>
                        <th scope="col"><?=$lang["cod-servicio-contratados"];?> </th>
                        <th scope="col"><?=$lang["valor_total"];?> </th>
                        <th scope="col">Acciones</th>
                      </tr>
                    </thead>
                    <tbody>
<?php
$reservas = getReservasConfirmadas($idPrestador, $vistaAdmin);
$hoy = strtotime(date('Y-m-d'));
$reservasYaMostradas = [];

for ($i = 0; $i < count($reservas); $i++) {
    $idReserva = $reservas[$i]['idReserva'];

    if (in_array($idReserva, $reservasYaMostradas)) continue;
    $reservasYaMostradas[] = $idReserva;

    $horarios = getReservaHorarios($idReserva);

    for ($j = 0; $j < count($horarios); $j++) {
        $idReservaHorarios = $horarios[$j]["idReservaHorarios"];
        $salida = getSalida($horarios[$j]["idServicioSalidas"]);

        if (empty($salida) || !isset($salida[0])) {
            continue;
        }

        $fechaEvento = strtotime($salida[0]['fecha']);
        $tarifas = getReservaTarifas($idReservaHorarios);

        $total = 0;
        for ($k = 0; $k < count($tarifas); $k++) {
            $total += ConvierteMoneda($tarifas[$k]["monedaSel"], $_SESSION["moneda_sel"], $tarifas[$k]["valor"]);
        }

        $adicionales = getReservaAdicionalesNoIncluidos($idReservaHorarios);
        foreach ($adicionales as $adic) {
            $total += ConvierteMoneda($salida[0]["idMoneda"], $_SESSION["moneda_sel"], $adic["precio"]);
        }

        $verTodasReservas = ($_SESSION['login']['idUsuario'] == 1 && !isset($_GET['idPrestador']));
        if (($verTodasReservas || $salida[0]["idPrestador"] == $idPrestador) && $fechaEvento >= $hoy) {
?>
                      <tr>
                        <td><?=$reservas[$i]["nombreResponsable"]." ".$reservas[$i]["apellidoResponsable"]?></td>
                        <td><?=$reservas[$i]["codigoAmigable"]?></td>
                        <td><?=date("d-m-Y H:i", strtotime($reservas[$i]['fechaAlta']));?></td>
                        <td><?=date("d/m/Y", strtotime($salida[0]['fecha']))?></td>
                        <td><?=count($tarifas);?></td>
                        <td><?=$_SESSION["moneda_sel_sym"].round(ConvierteMoneda($reservas[$i]["monedaSel"], $_SESSION["moneda_sel"], $reservas[$i]["total"]), 2);?></td>
                        <td><form method="post" action="voucherPrestador"><button type="submit" class="btn btn-info" name="idReservaHorarios" value="<?=$idReservaHorarios;?>"><?=$lang["voucher_prestador"];?> </button></form></td>
                      </tr>
<?php
        }
    }
}
?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>

<?php if ($_SESSION['login']['idUsuario'] == 1) { ?>
        <div class="card">
          <div class="card-header" id="headingTwo">
            <h5 class="mb-0">
              <button class="btn btn-warning btn-lg btn-block collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo"><i class="fas fa-exclamation-circle"></i> <?=$lang["pendientes"];?> </button>
            </h5>
          </div>
          <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
            <div class="card-body">
              <h3><?=$lang["lista_de_reservas_pendientes"];?> </h3>
              <div class="row">
                <div class="table-responsive">
                  <table class="table" id="tablaCarritoPendientes">
                    <thead>
                      <tr>
                        <th scope="col"><?=$lang["nombre"];?> </th>
                        <th scope="col"><?=$lang["cod-carrito"];?> </th>
                        <th scope="col"><?=$lang["fecha_de_contratacion"];?> </th>
                        <th scope="col"><?=$lang["dia_del_evento"];?> </th>
                        <th scope="col"><?=$lang["cod-servicio-contratados"];?> </th>
                        <th scope="col"><?=$lang["valor_total"];?> </th>
                      </tr>
                    </thead>
                    <tbody>
<?php
$reservas = getReservasPendientes($idPrestador);
$hoy = strtotime(date('Y-m-d'));
$reservasYaMostradas = [];

for ($i = 0; $i < count($reservas); $i++) {
    $idReserva = $reservas[$i]['idReserva'];
    $horarios = getReservaHorarios($idReserva);

    for ($j = 0; $j < count($horarios); $j++) {
        $idReservaHorarios = $horarios[$j]["idReservaHorarios"];
        $salida = getSalida($horarios[$j]["idServicioSalidas"]);

        if (empty($salida) || !isset($salida[0])) {
            continue;
        }

        $tarifas = getReservaTarifas($idReservaHorarios);
        $total = 0;
        for ($k = 0; $k < count($tarifas); $k++) {
            $total += ConvierteMoneda($tarifas[$k]["monedaSel"], $_SESSION["moneda_sel"], $tarifas[$k]["valor"]);
        }

        $fechaEvento = strtotime($salida[0]['fecha']);

        $adicionales = getReservaAdicionalesNoIncluidos($idReservaHorarios);
        foreach ($adicionales as $adic) {
            $total += ConvierteMoneda($salida[0]["idMoneda"], $_SESSION["moneda_sel"], $adic["precio"]);
        }

        $verTodasReservas = ($_SESSION['login']['idUsuario'] == 1 && !isset($_GET['idPrestador']));
        if (($verTodasReservas || $salida[0]["idPrestador"] == $idPrestador) && $fechaEvento >= $hoy) {
?>
                      <tr>
                        <td><?=$reservas[$i]["nombreResponsable"]." ".$reservas[$i]["apellidoResponsable"]?></td>
                        <td><?=$reservas[$i]["codigoAmigable"]?></td>
                        <td><?=date("d-m-Y H:i", strtotime($reservas[$i]['fechaAlta']));?></td>
                        <td><?=date("d/m/Y", strtotime($salida[0]['fecha']))?></td>
                        <td><?=count($tarifas);?></td>
                        <td><?=$_SESSION["moneda_sel_sym"].$total;?></td>
                      </tr>
<?php
        }
    }
}
?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
<?php } ?>

        <div class="card">
          <div class="card-header" id="headingThree">
            <h5 class="mb-0">
              <button class="btn btn-secondary btn-lg btn-block collapsed" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour"><i class="fas fa-clock"></i> <?=$lang["pasadas"];?> </button>
            </h5>
          </div>
          <div id="collapseFour" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
            <div class="card-body">
<?php if ($_SESSION['login']['idUsuario'] == 1) { ?>
              <h3><?=$lang["lista_de_reservas_pasadas_pendientes"];?> </h3>
              <div class="row">
                <div class="table-responsive">
                  <table class="table" id="tablaCarritoPasadasPendientes">
                    <thead>
                      <tr>
                        <th scope="col"><?=$lang["nombre"];?> </th>
                        <th scope="col"><?=$lang["cod-carrito"];?> </th>
                        <th scope="col"><?=$lang["fecha_de_contratacion"];?> </th>
                        <th scope="col"><?=$lang["dia_del_evento"];?> </th>
                        <th scope="col"><?=$lang["cod-servicio-contratados"];?> </th>
                        <th scope="col"><?=$lang["valor_total"];?> </th>
                      </tr>
                    </thead>
                    <tbody>
<?php
$reservas = getReservasPendientes($idPrestador);
$hoy = strtotime(date('Y-m-d'));
$reservasYaMostradas = [];

for ($i = 0; $i < count($reservas); $i++) {
    $idReserva = $reservas[$i]['idReserva'];
    $horarios = getReservaHorarios($idReserva);

    for ($j = 0; $j < count($horarios); $j++) {
        $idReservaHorarios = $horarios[$j]["idReservaHorarios"];
        $salida = getSalida($horarios[$j]["idServicioSalidas"]);
        if (empty($salida) || !isset($salida[0])) {
            continue;
        }

        $tarifas = getReservaTarifas($idReservaHorarios);
        $total = 0;
        for ($k = 0; $k < count($tarifas); $k++) {
            $total += ConvierteMoneda($tarifas[$k]["monedaSel"], $_SESSION["moneda_sel"], $tarifas[$k]["valor"]);
        }

        $adicionales = getReservaAdicionalesNoIncluidos($idReservaHorarios);
        foreach ($adicionales as $adic) {
            $total += ConvierteMoneda($salida[0]["idMoneda"], $_SESSION["moneda_sel"], $adic["precio"]);
        }

        $fechaEvento = strtotime($salida[0]['fecha']);
        $verTodasReservas = ($_SESSION['login']['idUsuario'] == 1 && !isset($_GET['idPrestador']));
        if (($verTodasReservas || $salida[0]["idPrestador"] == $idPrestador) && $fechaEvento < $hoy) {
?>
                      <tr>
                        <td><?=$reservas[$i]["nombreResponsable"]." ".$reservas[$i]["apellidoResponsable"]?></td>
                        <td><?=$reservas[$i]["codigoAmigable"]?></td>
                        <td><?=date("d-m-Y H:i", strtotime($reservas[$i]['fechaAlta']));?></td>
                        <td><?=date("d/m/Y", strtotime($salida[0]['fecha']))?></td>
                        <td><?=count($tarifas);?></td>
                        <td><?=$_SESSION["moneda_sel_sym"].round($total, 2);?></td>
                      </tr>
<?php
        }
    }
}
?>
                    </tbody>
                  </table>
                </div>
              </div>
<?php } ?>

              <h3 class="mt-4"><?=$lang["lista_de_reservas_confirmadas_pasadas"];?> </h3>
              <div class="row">
                <div class="table-responsive">
                  <table class="table" id="tablaCarritoConfirmadasPasadas">
                    <thead>
                      <tr>
                        <th scope="col"><?=$lang["nombre"];?> </th>
                        <th scope="col"><?=$lang["cod-carrito"];?> </th>
                        <th scope="col"><?=$lang["fecha_de_contratacion"];?> </th>
                        <th scope="col"><?=$lang["dia_del_evento"];?> </th>
                        <th scope="col"><?=$lang["cod-servicio-contratados"];?> </th>
                        <th scope="col"><?=$lang["valor_total"];?> </th>
                        <th scope="col">Acciones</th>
                      </tr>
                    </thead>
                    <tbody>
<?php
$reservas = getReservasConfirmadas($idPrestador, $vistaAdmin);
$hoy = strtotime(date('Y-m-d'));
$reservasYaMostradas = [];

for ($i = 0; $i < count($reservas); $i++) {
    $idReserva = $reservas[$i]['idReserva'];
    $horarios = getReservaHorarios($idReserva);

    for ($j = 0; $j < count($horarios); $j++) {
        $idReservaHorarios = $horarios[$j]["idReservaHorarios"];
        $salida = getSalida($horarios[$j]["idServicioSalidas"]);
        if (empty($salida) || !isset($salida[0])) {
            continue;
        }

        $tarifas = getReservaTarifas($idReservaHorarios);
        $total = 0;
        for ($k = 0; $k < count($tarifas); $k++) {
            $total += ConvierteMoneda($tarifas[$k]["monedaSel"], $_SESSION["moneda_sel"], $tarifas[$k]["valor"]);
        }

        $adicionales = getReservaAdicionalesNoIncluidos($idReservaHorarios);
        foreach ($adicionales as $adic) {
            $total += ConvierteMoneda($salida[0]["idMoneda"], $_SESSION["moneda_sel"], $adic["precio"]);
        }

        $fechaEvento = strtotime($salida[0]['fecha']);
        $verTodasReservas = ($_SESSION['login']['idUsuario'] == 1 && !isset($_GET['idPrestador']));
        if (($verTodasReservas || $salida[0]["idPrestador"] == $idPrestador) && $fechaEvento < $hoy) {
?>
                      <tr>
                        <td><?=$reservas[$i]["nombreResponsable"]." ".$reservas[$i]["apellidoResponsable"]?></td>
                        <td><?=$reservas[$i]["codigoAmigable"]?></td>
                        <td><?=date("d-m-Y H:i", strtotime($reservas[$i]['fechaAlta']));?></td>
                        <td><?=date("d/m/Y", strtotime($salida[0]['fecha']))?></td>
                        <td><?=count($tarifas);?></td>
                        <td><?=$_SESSION["moneda_sel_sym"].round($total, 2);?></td>
                        <td><form method="post" action="voucherPrestador"><button type="submit" class="btn btn-info" name="idReservaHorarios" value="<?=$idReservaHorarios;?>"><?=$lang["voucher_prestador"];?> </button></form></td>
                      </tr>
<?php
        }
    }
}
?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <?php if ($_SESSION['login']['idUsuario'] == 1): ?>
      <script>
      document.addEventListener('DOMContentLoaded', function() {
        const selectPrestador = document.getElementById('selectPrestador');
        if (selectPrestador) {
          selectPrestador.addEventListener('change', function() {
            const idPrestador = this.value;
            if (idPrestador) {
              window.location.href = 'reservasEstado.php?idPrestador=' + idPrestador;
            } else {
              window.location.href = 'reservasEstado.php';
            }
          });
        }
      });
      </script>
      <?php endif; ?>
    </div>
  </section>
</div>

<script type="text/javascript">
function format(value) {
    return value;
}
$(document).ready(function () {
  function initTable(selector) {
    const $table = $(selector);
    if ($table.length) {
      return $table.DataTable({});
    }
    return null;
  }

  const tablaConfirmadas = initTable('#tablaCarritoConfirmadas');
  initTable('#tablaCarritoPendientes');
  initTable('#tablaCarritoPasadasPendientes');
  initTable('#tablaCarritoConfirmadasPasadas');

  if (tablaConfirmadas) {
    $('#tablaCarritoConfirmadas').on('click', 'td.details-control', function () {
      const tr = $(this).closest('tr');
      const row = tablaConfirmadas.row(tr);

      if (row.child.isShown()) {
        row.child.hide();
        tr.removeClass('shown');
      } else {
        row.child(format(tr.data('child-value'))).show();
        tr.addClass('shown');
      }
    });
  }
});
</script>

<?php 
include("includes/footer.php");
?>
