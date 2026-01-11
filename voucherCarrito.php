<?php 

include("includes/headPagos.php");

include("admin/classes/salidas.php");

include("admin/classes/tarifas.php");

include("admin/classes/idiomas.php");

include("admin/classes/servicio.php");

include("admin/classes/comisiones.php");

include("admin/classes/edades.php");

include("admin/classes/cancelaciones.php");

include("admin/classes/servicios_adicionales.php");

include("admin/classes/reserva.php");

include("admin/classes/comprobantes.php");

require_once("admin/classes/moneda.php");

include("admin/classes/accesibilidad.php");

include("admin/classes/convierte_monedas.php");



if ($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST["codigoAmigable"])) {

    $codigoAmigable=$_POST["codigoAmigable"];

    $reserva=getReserva($codigoAmigable)[0];

    $idReserva=$reserva["idReserva"];

    $monedaSel=$reserva["monedaSel"];

    $moneda=getMoneda($reserva["monedaSel"])[0]["Symbol"];

    $fechaAlta=date("d/m/Y",strtotime($reserva["fechaAlta"]));

    $nombreResponsable=$reserva['nombreResponsable']." ".$reserva['apellidoResponsable'];

    $emailResponsable=$reserva['emailResponsable'];

    $totalReserva=$reserva["total"]; // Original total from DB in its currency

    $impuestos=$reserva["impuestos"];

    $precio=ConvierteMoneda($monedaSel,$_SESSION["moneda_sel"], $totalReserva); // Total converted to display currency

}



if (empty($reserva) || count($reserva)<1) {

    alertar("La Reserva con el codigo ".$codigoAmigable." no existe","error");

    redireccionarLento("index");

    exit();

}



// Obtener políticas de cancelación de las tarifas de la reserva
$cancelacionesArr = [];
$horariosReserva = getReservaHorarios($idReserva);
if (!empty($horariosReserva)) {
    foreach ($horariosReserva as $hr) {
        $tarifasReserva = getReservaTarifas($hr['idReservaHorarios']);
        if (!empty($tarifasReserva)) {
            foreach ($tarifasReserva as $tr) {
                if (isset($tr['idCancelaciones']) && !empty($tr['idCancelaciones'])) {
                    $cancelacionData = getTipoCancelaciones($tr['idCancelaciones']);
                    if (!empty($cancelacionData) && isset($cancelacionData[0]['texto'])) {
                        $textoC = $cancelacionData[0]['texto'];
                        if (!in_array($textoC, $cancelacionesArr)) {
                            $cancelacionesArr[] = $textoC;
                        }
                    }
                }
            }
        }
    }
}

// Obtener comprobantes de pago en USD
$comprobantes = getComprobantesIdReservaDolar($idReserva);
$total_dolares = $reserva["total_dolares"];
$monedaOriginalReserva = $reserva["monedaSel"];

// Calcular total en la moneda seleccionada
// Si la moneda seleccionada coincide con la moneda original de la reserva, usar el total original
// Esto evita errores de redondeo en reconversiones
$total_en_moneda_seleccionada = ($monedaOriginalReserva == $_SESSION['moneda_sel'] && isset($reserva["total"])) 
    ? $reserva["total"] 
    : ConvierteMoneda(188, $_SESSION['moneda_sel'], $total_dolares);

// Calcular comprobantes en la moneda seleccionada
if ($comprobantes > 0) {
    if ($monedaOriginalReserva == $_SESSION['moneda_sel'] && isset($reserva["total"]) && $total_dolares > 0) {
        // Proporcional si es la misma moneda
        $comprobantes_en_moneda_sel = $comprobantes * ($reserva["total"] / $total_dolares);
    } else {
        $comprobantes_en_moneda_sel = ConvierteMoneda(188, $_SESSION['moneda_sel'], $comprobantes);
    }
    
    $totalComprobantesAMostrar = $comprobantes;
    $diferenciaAPagar_usd = $total_dolares - $totalComprobantesAMostrar;
    
    // Calcular diferencia en moneda seleccionada manteniendo precisión
    $diferenciaAPagar_moneda_sel = $total_en_moneda_seleccionada - $comprobantes_en_moneda_sel;
    
    // Si la diferencia es menor a 1 USD, considerarla como 0 (totalmente pagado)
    if ($diferenciaAPagar_usd < 1) {
        $diferenciaAPagar_usd = 0;
        $diferenciaAPagar_moneda_sel = 0;
    }
} else {
    // No hay pagos previos, resta pagar es el total
    $totalComprobantesAMostrar = 0;
    $comprobantes_en_moneda_sel = 0;
    $diferenciaAPagar_usd = $total_dolares;
    $diferenciaAPagar_moneda_sel = $total_en_moneda_seleccionada;
}

?>



<!DOCTYPE html>

<html lang="es">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Voucher de Reserva</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>

body { font-family: 'Poppins', sans-serif; background: #f9f9f9; margin:0; }

.invoice { background: #fff; padding: 25px; border-radius: 12px; box-shadow: 0 8px 25px rgba(0,0,0,0.1); margin-bottom: 30px; }

.page-header { border-bottom: 2px solid #eaeaea; margin-bottom: 20px; padding-bottom: 10px; }

.invoice-col strong { display: block; margin-bottom: 5px; color: #333; }

.table th, .table td { vertical-align: middle !important; }

.table thead th { background: #f1f1f1; border-bottom: 2px solid #ddd; }

.table tbody tr:hover { background: #f9f9f9; }

.callout { padding: 15px 20px; border-left: 5px solid #17a2b8; background: #e9f7fc; border-radius: 8px; margin-bottom: 20px; }

.btn { border-radius: 8px; transition: all 0.3s; }

.btn:hover { transform: translateY(-2px); }

@media (max-width: 767px){

    .invoice-col { margin-bottom: 15px; }

    .table-responsive { overflow-x: auto; }

    .lead { font-size: 1rem; }

}

</style>

</head>

<body>



<section class="py-4">

<div class="container">

    <div class="invoice" id="imprimible">

        <div class="row mb-3">

            <div class="col-md-6">

                <h3 class="page-header"><strong>VOUCHER DE RESERVA</strong></h3>

            </div>

            <div class="col-md-6 text-md-end">

                <small>Consulta hoy: <?= date('d/m/Y');?></small>

            </div>
        </div>

        <!-- INFO RESPONSABLE -->
        <div class="row invoice-info mb-4">
            <div class="col-md-6">
                <strong>Responsável da reserva:</strong>
                <?= $nombreResponsable;?><br>
                <address>
                    <b>Email:</b> <?=$emailResponsable; ?><br>
                    <b>Responsável do pagamento:</b> <?= $nombreResponsable;?><br>
                </address>
            </div>
            <div class="col-md-6">
                <b>Data de Compra:</b> <?=$fechaAlta;?><br>
                <b>Numero de comprovante:</b> #<?= $codigoAmigable;?><br>
                <b>Numero de ordem ID:</b> <?= $codigoAmigable;?><br>
                <b>Passageiros Totais:</b> <span id="total_passengers_display"></span><br>
            </div>
        </div>

<!-- TABLA SERVICIOS -->

<div class="table-responsive mb-4">

    <table class="table table-striped table-hover">

        <thead>

            <tr>

                <th>Serviço</th>

                <th>Quant.</th>

                <th>Check in</th>

                <th>Subtotal</th>

                <th>Acción</th>

            </tr>

        </thead>

        <tbody>

        <?php 

        $horarios = getReservaHorarios($idReserva);

        $totalCarrito = 0; // Sum of subtotal for services and additional services (without IVA) in display currency

        $totalIva = 0;     // Sum of IVA for services and additional services in display currency

        $totalPassengers = 0; // Initialize total passengers for the entire reservation



        if(empty($horarios)) {

            echo "<tr><td colspan='5' class='text-danger'>No se encontraron horarios para esta reserva.</td></tr>";

        } else {

            foreach($horarios as $h) {

                $idReservaHorarios = $h['idReservaHorarios'];



                // Servicio

                $servicio = getServicio($h["idServicioSeleccionado"]);

                $nombreServicio = !empty($servicio) ? $servicio[0]["nombre_servicio"] : "Servicio no encontrado";



                // Tarifas

                $reservaTarifas = getReservaTarifas($idReservaHorarios);

                $cantidad = 0; // Initialize correctly for each service

                $subtotalHorario = 0; // Initialize correctly for each service

                

                



                if(!empty($reservaTarifas)) {

                    foreach($reservaTarifas as $tarifa) {

                        $cantidad += (int)$tarifa['cantidad']; // Ensure quantity is an integer

                        // Convert value to session's display currency

                        $subtotalHorario += ConvierteMoneda($tarifa["monedaSel"], $_SESSION["moneda_sel"], $tarifa['valorSinIva']);

                        $totalIva += ConvierteMoneda($tarifa["monedaSel"], $_SESSION["moneda_sel"], $tarifa['valorDeIva']);

                    }

                } 

                $totalPassengers += $cantidad; // Sum up for grand total passengers

                // If there are no tariffs, $cantidad remains 0 and $subtotalHorario remains 0, which is accurate.



                $totalCarrito += $subtotalHorario;



                // Adicionales

                $adicionales = getReservaAdicionalesNoIncluidos($idReservaHorarios);

        ?>

                <tr>

                    <td><?=$nombreServicio;?></td>

                    <td><?=$cantidad;?></td>

                    <td><?=date("d/m/Y", strtotime($h['fecha']))." ".$h['horaCheckIn'];?></td>

                    <td><?=$_SESSION["moneda_sel_sym"].number_format($subtotalHorario, 2, ',', '.');?></td>

                    <td>

                        <form method="post" action="voucherSalida">

                            <button type="submit" name="idReservaHorarios" value="<?=$idReservaHorarios;?>" class="btn btn-sm btn-primary">Detalhes Serviço</button>

                        </form>

                    </td>

                </tr>

        <?php 

                // Mostrar adicionales debajo del servicio

                if(!empty($adicionales)) {

                    foreach($adicionales as $ad) {

                        // Determine the source currency for additional services. Assume it's the main reservation's currency

                        // unless 'ad' object itself provides a specific currency.

                        // For robustness, we assume additional service prices are in the main reservation's currency ($monedaSel).

                        $sourceCurrencyAdicional = $monedaSel;

                        // Convert additional price to session's display currency

                        $precioAdicional = ConvierteMoneda($sourceCurrencyAdicional, $_SESSION["moneda_sel"], $ad['precio']);

                        // FIX: Corrected typo 'sourceCurrencyAdacional' to '$sourceCurrencyAdicional'

                        $valorIva = ConvierteMoneda($sourceCurrencyAdicional, $_SESSION["moneda_sel"], $ad['valorIva']);

                        $totalCarrito += $precioAdicional;

                        $totalIva += $valorIva;

        ?>

                <tr>

                    <td><?=$ad['nombre'];?></td>

                    <td><?=$ad['cantidad'];?></td>

                    <td></td>

                    <td><?=$_SESSION["moneda_sel_sym"].number_format($precioAdicional, 2, ',', '.');?></td>

                    <td></td>

                </tr>

        <?php 

                    }

                }

            }

        }

        ?>

        </tbody>

    </table>

</div>



        <?php

        // Output the total passengers using JavaScript to populate the span.

        ?>

        <script>

            document.getElementById('total_passengers_display').innerText = '<?= $totalPassengers; ?>';

        </script>





        <!-- RESUMEN -->

        <div class="row mb-4">

            <div class="col-md-6">

                <p class="lead">Métodos de Pagamento:</p>

                <img src="img/credit/visa.png" alt="Visa">

                <img src="img/credit/mastercard.png" alt="Mastercard">

                <img src="img/credit/american-express.png" alt="American Express">

                <img src="img/credit/paypal2.png" alt="Paypal">

                <p class="text-muted mt-2">Para sua segurança, caso haja qualquer divergência entre as informações cadastrais e de pagamento, nos reservamos o direito de não aprovar o seu pedido.</p>

            </div>

            <div class="col-md-6">

                <h5>Resumo</h5>

                <table class="table table-borderless">

                    <tr>

                        <th>Subtotal (Serviços):</th>

                        <td><?=$_SESSION["moneda_sel_sym"].number_format($totalCarrito, 2, ',', '.');?></td>

                    </tr>

                    <tr>

                        <th>Impostos:</th>

                        <td><?=$_SESSION["moneda_sel_sym"].number_format($totalIva, 2, ',', '.');?></td>

                    </tr>

                    <?php if (isset($reserva["descuento_redondeo"]) && $reserva["descuento_redondeo"] > 0): ?>
                    <tr class="text-success">

                        <th><i class="fas fa-gift"></i> Descuento por Redondeo:</th>

                        <td>-<?=$_SESSION["moneda_sel_sym"].number_format($reserva["descuento_redondeo"], 2, ',', '.');?></td>

                    </tr>
                    <?php endif; ?>

                    <tr>

                        <th>Total da Reserva:</th>

                        <td><?=$_SESSION["moneda_sel_sym"].number_format($precio, 2, ',', '.');?></td>

                    </tr>

                    <tr>

                        <th>Pagamento realizado:</th>

                        <td><?=$_SESSION["moneda_sel_sym"].number_format($comprobantes_en_moneda_sel, 2, ',', '.');?></td>

                    </tr>

                    <tr>

                        <th>Resta pagar:</th>

                        <td><?=$_SESSION["moneda_sel_sym"].number_format($diferenciaAPagar_moneda_sel, 2, ',', '.');?></td>

                    </tr>

                </table>

            </div>

        </div>



        <!-- POLITICAS -->
        <div class="callout">
            <h5><i class="fas fa-info"></i> Políticas do Voucher:</h5>
            
            <!-- Política de Cancelación dinámica desde BD -->
            <?php if (!empty($cancelacionesArr)) { ?>
                <h6 class="mt-3"><i class="fas fa-info-circle"></i> Política de Cancelamento:</h6>
                <ul class="mb-0">
                    <?php foreach ($cancelacionesArr as $cancelacionTexto) { ?>
                        <li><?= $cancelacionTexto; ?></li>
                    <?php } ?>
                </ul>
            <?php } else { ?>
                <p class="text-muted"><small><i class="fas fa-info-circle"></i> Consultar política de cancelación al momento de reservar.</small></p>
            <?php } ?>
        </div>

        <!-- BOTON IMPRIMIR Y VOLVER -->
        <div class="text-end mb-3 d-flex gap-2 justify-content-end no-print">
            <a href="consultaReserva?reserva=<?= $codigoAmigable; ?>" class="btn btn-secondary btn-imprimir">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            <button class="btn btn-primary btn-imprimir" onclick="window.print()">
                <i class="fas fa-print"></i> Imprimir
            </button>
        </div>
    </div>
</div>
</section>

</body>
</html>