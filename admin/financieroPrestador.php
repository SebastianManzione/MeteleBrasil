

<?php 

include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");
require("classes/functions.php");
require("classes/prestador.php");
require("classes/usuario.php");
require("classes/edades.php");
require("classes/servicio.php");
require("classes/reserva.php");
require("classes/salidas.php");
require("classes/tarifas.php");
require("classes/comprobantes.php");
require("classes/cancelaciones.php");
require("classes/convierte_monedas.php");

// Verificar acceso segÃºn rol
$isPrestador = isset($_SESSION["login"]["idPrestador"]) && $_SESSION["login"]["idPrestador"] > 0;
$isAdmin = isset($_SESSION["login"]["rol"]) && $_SESSION["login"]["rol"] == 1;

if (!$isPrestador && !$isAdmin) {
  alertar("No tiene acceso a esta secciÃ³n", "error");
  redireccionarLento("index");
}

// Si el admin estÃ¡ logueado, puede ver como cualquier prestador
if ($isAdmin && isset($_GET['idPrestador'])) {
    $idPrestador = (int)$_GET['idPrestador'];
    $vistaAdmin = false; // Admin viendo como prestador especÃ­fico
} else {
    $idPrestador = $_SESSION['login']['idPrestador'] ?? null;
    $vistaAdmin = $isAdmin; // Admin viendo todas las reservas
}

// Obtener lista de prestadores para el select (solo si es admin)
$prestadores = [];
if ($isAdmin) {
    $prestadores = getPrestadores();
}

 ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Financiero Prestador</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Financiero</a></li>
              <li class="breadcrumb-item active">Prestador</li>
            </ol>
          </div>
        </div>
        
        <?php if ($isAdmin && count($prestadores) > 0): ?>
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
        
        <?php if ($idPrestador === null): ?>
        <div class="row">
          <div class="col-12">
            <div class="alert alert-warning">
              <i class="fas fa-exclamation-triangle"></i> Debe especificar un prestador para ver el financiero.
            </div>
          </div>
        </div>
        <?php endif; ?>

      </div><!-- /.container-fluid -->
    </div>

    <section class="content">
      <div class="container-fluid">
        <?php if ($idPrestador !== null): ?>
        <div class="card card-default">
          <div class="card-header">
            <h3 class="card-title">Reservas Confirmadas Pasadas - Desglose Financiero</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
            </div>
          </div>

          <div class="card-body">
            <!-- Filtros de fecha -->
            <div class="row mb-3">
              <div class="col-md-3">
                <label for="fechaDesde">Fecha Desde:</label>
                <input type="date" id="fechaDesde" class="form-control" value="<?=isset($_GET['fechaDesde']) ? $_GET['fechaDesde'] : ''?>">
              </div>
              <div class="col-md-3">
                <label for="fechaHasta">Fecha Hasta:</label>
                <input type="date" id="fechaHasta" class="form-control" value="<?=isset($_GET['fechaHasta']) ? $_GET['fechaHasta'] : ''?>">
              </div>
              <div class="col-md-3">
                <label>&nbsp;</label>
                <button type="button" id="btnFiltrar" class="btn btn-primary btn-block"><i class="fas fa-filter"></i> Filtrar</button>
              </div>
              <div class="col-md-3">
                <label>&nbsp;</label>
                <button type="button" id="btnLimpiar" class="btn btn-secondary btn-block"><i class="fas fa-times"></i> Limpiar</button>
              </div>
            </div>
            <div class="table-responsive">
              <table class="table table-striped table-hover" id="tablaFinanciero">
                <thead class="thead-dark">
                  <tr>
                    <th>Data do evento</th>
                    <th>Voucher Servicio</th>
                    <th>Responsável</th>
                    <th>Serviço</th>
                    <th>Data compra</th>
                    <th>Período</th>
                    <th>Cantidad de Pax Comission</th>
                    <th>Cantidad de pax total</th>
                    <th>Valor já pego</th>
                    <th>Valor pagar Fornecedor</th>
                    <th>Detalhes</th>
                  </tr>
                </thead>
                <tbody>
<?php
if ($idPrestador !== null) {
    $reservas = getReservasConfirmadas($idPrestador, $vistaAdmin);
    $hoy = strtotime(date('Y-m-d'));
    
    // Obtener filtros de fecha desde GET
    $fechaDesde = isset($_GET['fechaDesde']) && !empty($_GET['fechaDesde']) ? strtotime($_GET['fechaDesde']) : null;
    $fechaHasta = isset($_GET['fechaHasta']) && !empty($_GET['fechaHasta']) ? strtotime($_GET['fechaHasta'] . ' 23:59:59') : null;

    for ($i = 0; $i < count($reservas); $i++) {
        $idReserva = $reservas[$i]['idReserva'];
        $horarios = getReservaHorarios($idReserva);
        
        $responsable = trim($reservas[$i]["nombreResponsable"] . " " . $reservas[$i]["apellidoResponsable"]);
        $fechaCompra = date("d/m/Y", strtotime($reservas[$i]['fechaAlta']));

        for ($j = 0; $j < count($horarios); $j++) {
            $idReservaHorarios = $horarios[$j]["idReservaHorarios"];
            $codigoVoucherServicio = !empty($horarios[$j]['CodigoVoucherServicio']) ? $horarios[$j]['CodigoVoucherServicio'] : 'N/A';
            $salida = getSalida($horarios[$j]["idServicioSalidas"]);
            
            if (empty($salida) || !isset($salida[0])) {
                continue;
            }

            $fechaEvento = strtotime($salida[0]['fecha']);
            $verTodasReservas = ($isAdmin && !isset($_GET['idPrestador']));
            
            // Aplicar filtros de fecha si están definidos
            if ($fechaDesde !== null && $fechaEvento < $fechaDesde) {
                continue; // Saltar si la fecha del evento es anterior a la fecha desde
            }
            if ($fechaHasta !== null && $fechaEvento > $fechaHasta) {
                continue; // Saltar si la fecha del evento es posterior a la fecha hasta
            }
            
            if (($verTodasReservas || $salida[0]["idPrestador"] == $idPrestador)) {
                $servicio = getServicio($salida[0]["idServicio"]);
                $nombreServicio = !empty($servicio) && isset($servicio[0]["nombre_servicio"]) ? $servicio[0]["nombre_servicio"] : 'N/A';
                
                $prestadorData = getPrestador($salida[0]["idPrestador"]);
                $nombrePrestador = !empty($prestadorData) && isset($prestadorData[0]["nombre"]) ? $prestadorData[0]["nombre"] : 'N/A';
                
                $periodo = ($salida[0]["nombre"] ?? 'N/A');
                
                $tarifas = getReservaTarifas($idReservaHorarios);
                
                // SUMAR TODOS LOS VALORES DE ESTA SALIDA
                $totalPasajerosReserva = 0;
                $totalPaxComision = 0;
                $totalValorComision = 0;
                $totalAPagarPrestador = 0;
                
                foreach ($tarifas as $tarifa) {
                    $pasajeros = getPasajeros($tarifa['idReservaTarifas']);
                    $cantidadPasajerosTarifa = count($pasajeros);
                    $totalPasajerosReserva += $cantidadPasajerosTarifa;
                    
                    // Convertir el precio total a la moneda seleccionada
                    $precioTotal = ConvierteMoneda($tarifa["monedaSel"], $_SESSION["moneda_sel"], $tarifa["valor"]);
                    $totalValorComision += $precioTotal;
                    
                    // Las comisiones ya están guardadas como valores monetarios, NO porcentajes
                    $comisionSistema = ConvierteMoneda($tarifa["monedaSel"], $_SESSION["moneda_sel"], $tarifa['comisionSistema'] ?? 0);
                    $comisionVendedor = ConvierteMoneda($tarifa["monedaSel"], $_SESSION["moneda_sel"], $tarifa['comisionVendedor'] ?? 0);
                    
                    // Lo que le pagamos al prestador = precio - comisión sistema - comisión vendedor
                    $aPagarPrestador = $precioTotal - $comisionSistema - $comisionVendedor;
                    $totalAPagarPrestador += $aPagarPrestador;
                    
                    $comisiona = ($tarifa['comisiona'] ?? 1) == 1;
                    if ($comisiona) {
                        $totalPaxComision += $cantidadPasajerosTarifa;
                    }
                }
                
                // Calcular comprobantes (valor ya pagado)
                // getComprobantesIdReserva() devuelve el total ya convertido a moneda actual
                $totalPagado = getComprobantesIdReserva($idReserva);
?>
                  <tr>
                    <td><?=date("d-m-Y", $fechaEvento)?></td>
                    <td><?=$codigoVoucherServicio?></td>
                    <td><?=$responsable?></td>
                    <td><?=$nombreServicio?></td>
                    <td><?=$fechaCompra?></td>
                    <td><?=$periodo?></td>
                    <td><?=$totalPaxComision?></td>
                    <td><?=$totalPasajerosReserva?></td>
                    <td><?=$_SESSION["moneda_sel_sym"].number_format($totalPagado, 2)?></td>
                    <td><strong><?=$_SESSION["moneda_sel_sym"].number_format($totalAPagarPrestador, 2)?></strong></td>
                    <td><button class="btn btn-sm btn-info" onclick="verDetallePasajeros(<?=$idReservaHorarios?>)"><i class="fas fa-eye"></i></button></td>
                  </tr>
<?php
            }
        }
    }
}
?>
                </tbody>
                <tfoot>
                  <tr class="table-info">
                    <th colspan="9" class="text-right">TOTAL A PAGAR AL PRESTADOR:</th>
                    <th id="totalPrestador"></th>
                    <th></th>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
        <?php endif; ?>
      </div><!-- /.container-fluid -->
    </section>
  </div>

<script>
function verDetallePasajeros(idReservaHorarios) {
  $.ajax({
    url: 'ajax_get_pasajeros_salida.php',
    type: 'POST',
    dataType: 'json',
    data: { idReservaHorarios: idReservaHorarios },
    success: function(response) {
      console.log('Response:', response); // Debug
      if (response.success && response.pasajeros && response.pasajeros.length > 0) {
        var html = '<table class="table table-sm table-bordered"><thead><tr><th>Pasajero</th><th>Tarifa</th><th>A Pagar Prestador</th></tr></thead><tbody>';
        response.pasajeros.forEach(function(p) {
          html += '<tr><td>' + p.nombre + '</td><td>' + p.tarifa + '</td><td class="text-success font-weight-bold">' + (p.aPagar || 'N/A') + '</td></tr>';
        });
        html += '</tbody></table>';
        
        Swal.fire({
          title: '<i class="fas fa-users"></i> Pasajeros del Voucher Servicio',
          html: html,
          width: '650px',
          showCloseButton: true
        });
      } else {
        var mensaje = response.message || 'No se encontraron pasajeros para esta salida.';
        Swal.fire({
          title: 'Sin información',
          text: mensaje,
          icon: 'warning'
        });
      }
    },
    error: function(xhr, status, error) {
      console.error('Error AJAX:', xhr.responseText); // Debug
      Swal.fire({
        title: 'Error',
        text: 'No se pudo cargar la información de pasajeros. ' + error,
        icon: 'error'
      });
    }
  });
}

$(document).ready(function() {
  var table = $('#tablaFinanciero').DataTable({
    "language": {
      "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
    },
    "order": [[0, 'desc']], // Ordenar por fecha descendente
    "footerCallback": function(row, data, start, end, display) {
      var api = this.api();
      
      // Función helper para parsear moneda
      var parseCurrency = function(val) {
        if (typeof val === 'string') {
          return parseFloat(val.replace(/[^\d.-]/g, '')) || 0;
        }
        return parseFloat(val) || 0;
      };
      
      // Sumar total a pagar al prestador (columna 9)
      var totalPrestador = api.column(9, {page: 'current'}).data().reduce(function(a, b) {
        return parseCurrency(a) + parseCurrency(b);
      }, 0);
      
      // Actualizar footer
      $('#totalPrestador').html('<strong><?=$_SESSION["moneda_sel_sym"]?>' + totalPrestador.toFixed(2) + '</strong>');
    }
  });
  
  // Filtros de fecha
  $('#btnFiltrar').on('click', function() {
    var fechaDesde = $('#fechaDesde').val();
    var fechaHasta = $('#fechaHasta').val();
    var url = new URL(window.location.href);
    
    if (fechaDesde) url.searchParams.set('fechaDesde', fechaDesde);
    if (fechaHasta) url.searchParams.set('fechaHasta', fechaHasta);
    
    window.location.href = url.toString();
  });
  
  $('#btnLimpiar').on('click', function() {
    var url = new URL(window.location.href);
    url.searchParams.delete('fechaDesde');
    url.searchParams.delete('fechaHasta');
    window.location.href = url.toString();
  });
  
  <?php if ($isAdmin): ?>
  // Event listener para el selector de prestador
  const selectPrestador = document.getElementById('selectPrestador');
  if (selectPrestador) {
    selectPrestador.addEventListener('change', function() {
      const idPrestador = this.value;
      if (idPrestador) {
        window.location.href = 'financieroPrestador?idPrestador=' + idPrestador;
      } else {
        window.location.href = 'financieroPrestador';
      }
    });
  }
  <?php endif; ?>
});
</script>

<?php include("includes/footer.php"); ?>