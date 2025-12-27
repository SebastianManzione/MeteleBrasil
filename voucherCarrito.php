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



// Correctly calculate totalComprobantesAMostrar by summing individual amounts and then converting.

// Assuming getComprobantesIdReservaDolar returns an array of records, where each record has a 'monto' field

// and these 'monto' values are all in USD (currency ID 188, inferred from the original code's usage).

$comprobante_records_dolar = getComprobantesIdReservaDolar($idReserva);

$sum_comprobantes_usd = 0;

if (!empty($comprobante_records_dolar) && is_array($comprobante_records_dolar)) {

    foreach ($comprobante_records_dolar as $comp_record) {

        $sum_comprobantes_usd += (float)$comp_record['monto'];

    }

}

// Convert the total sum of payments (which are in USD) to the reservation's selected currency ($monedaSel)

$totalComprobantesAMostrar = ConvierteMoneda(188, $monedaSel, $sum_comprobantes_usd); // Total paid in reservation's currency



// FIX: Correct floating point comparison and capping for remaining balance

$diferenciaAPagar = round($reserva["total"] - $totalComprobantesAMostrar, 2); // Round to 2 decimal places for currency precision

if (abs($diferenciaAPagar) < 0.01) { // If difference is very small (e.g., due to floating point), treat as 0

    $diferenciaAPagar = 0; 

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

                    <tr>

                        <th>Total da Reserva:</th>

                        <td><?=$_SESSION["moneda_sel_sym"].number_format($precio, 2, ',', '.');?></td>

                    </tr>

                    <tr>

                        <th>Pagamento realizado:</th>

                        <td><?=$_SESSION["moneda_sel_sym"].number_format(ConvierteMoneda($monedaSel, $_SESSION["moneda_sel"], $totalComprobantesAMostrar), 2, ',', '.');?></td>

                    </tr>

                    <tr>

                        <th>Resta pagar:</th>

                        <td><?=$_SESSION["moneda_sel_sym"].number_format(ConvierteMoneda($monedaSel, $_SESSION["moneda_sel"], $diferenciaAPagar), 2, ',', '.');?></td>

                    </tr>

                </table>

            </div>

        </div>



        <!-- POLITICAS -->

        <div class="callout">

            <h5><i class="fas fa-info"></i> Políticas do Voucher:</h5>

            

            

            

            <!-- Política de Cancelación multilíngüe responsive -->

<style>

  .cancel-card {

    max-width: 100%;

    border: 1px solid #e0e0e0;

    border-radius: 8px;

    overflow: hidden;

    box-shadow: 0 6px 18px rgba(0,0,0,0.06);

    font-family: Arial, Helvetica, sans-serif;

    background: #ffffff;

  }



  .cancel-card summary {

    list-style: none;

    cursor: pointer;

    padding: 14px 18px;

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 10px;

    font-weight: 600;

    font-size: 15px;

    background: linear-gradient(90deg, #f9fafb, #ffffff);

    border-bottom: 1px solid #f1f1f1;

  }



  .cancel-card summary::-webkit-details-marker { display: none; }



  .toggle-arrow {

    transition: transform .22s ease;

  }



  details[open] .toggle-arrow {

    transform: rotate(180deg);

  }



  .cancel-content {

      width: 100%;

    padding: 16px 18px;

    line-height: 1.45;

    color: #222;

  }



  .lang-block {

    margin-bottom: 12px;

    padding: 10px;

    border-left: 3px solid #efefef;

    background: #fbfbfb;

    border-radius: 6px;

  }



  .lang-title {

    font-weight: 700;

    margin-bottom: 8px;

  }



  @media (max-width:480px){

    .cancel-card { padding: 0 8px; }

    .cancel-card summary { padding: 12px; font-size: 14px; }

    .cancel-content { padding: 12px; }

  }

  

  .btn-imprimir {

    display: block;

    width: 100%;

    max-width: 100%;

    margin: 16px auto 0 auto;

    padding: 12px 0;

    font-size: 16px;

    font-weight: 600;

    border-radius: 6px;

  }

  

</style>



<details class="cancel-card" aria-live="polite">

  <summary>

    Política de Cancelación / Política de Cancelamento / Cancellation Policy / Politica di Cancellazione

    <svg class="toggle-arrow" width="18" height="18" viewBox="0 0 24 24" aria-hidden="true">

      <path fill="currentColor" d="M7 10l5 5 5-5z"/>

    </svg>

  </summary>



  <div class="cancel-content">



    <!-- Português -->

    <div class="lang-block" lang="pt">

      <div class="lang-title">🇧🇷 Política de Cancelamento (Português)</div>

      <ul>

        <li>Cancelamentos feitos com <strong>mais de 72 horas de antecedência</strong> da data do passeio terão <strong>reembolso total</strong>.</li>

        <li>Cancelamentos feitos <strong>dentro de 72 horas</strong> não são reembolsáveis.</li>

        <li>Em caso de <strong>condições climáticas adversas</strong> que impeçam a saída, o passeio poderá ser <strong>remarcado</strong> ou será oferecido <strong>reembolso total</strong>.</li>

        <li><strong>Não comparecimentos</strong> (sem aviso prévio) não terão direito a reembolso.</li>

        <li>Para <strong>tours internacionais</strong>:

          <ul>

            <li>Cancelamentos com <strong>45 dias de antecedência</strong> – reembolso de <strong>100%</strong>.</li>

            <li>Cancelamentos com <strong>30 dias de antecedência</strong> – reembolso de <strong>50%</strong>.</li>

            <li>Cancelamentos com menos de <strong>30 dias</strong> – <strong>sem reembolso</strong>.</li>

          </ul>

        </li>

        <li>A empresa reserva-se o direito de alterar ou cancelar a atividade por motivos operacionais ou de segurança.</li>

      </ul>

    </div>



    <!-- Español -->

    <div class="lang-block" lang="es">

      <div class="lang-title">🇪🇸 Política de Cancelación (Español)</div>

      <ul>

        <li>Las cancelaciones realizadas con <strong>más de 72 horas de antelación</strong> a la fecha del paseo recibirán un <strong>reembolso completo</strong>.</li>

        <li>Las cancelaciones realizadas <strong>dentro de las 72 horas previas</strong> no son reembolsables.</li>

        <li>En caso de <strong>condiciones climáticas adversas</strong> que impidan la salida, el paseo podrá <strong>reprogramarse</strong> o se ofrecerá un <strong>reembolso total</strong>.</li>

        <li>Los <strong>no shows</strong> (ausencias sin aviso previo) no tendrán derecho a reembolso.</li>

        <li>Para <strong>tours internacionales</strong>:

          <ul>

            <li>Cancelaciones con <strong>45 días de anticipación</strong> – reembolso del <strong>100%</strong>.</li>

            <li>Cancelaciones con <strong>30 días de anticipación</strong> – reembolso del <strong>50%</strong>.</li>

            <li>Cancelaciones con menos de <strong>30 días</strong> – <strong>sin reembolso</strong>.</li>

          </ul>

        </li>

        <li>La empresa se reserva el derecho de modificar o cancelar la actividad por motivos operativos o de seguridad.</li>

      </ul>

    </div>



    <!-- English -->

    <div class="lang-block" lang="en">

      <div class="lang-title">🇬🇧 Cancellation Policy (English)</div>

      <ul>

        <li>Cancellations made <strong>more than 72 hours before</strong> the scheduled tour will receive a <strong>full refund</strong>.</li>

        <li>Cancellations made <strong>within 72 hours</strong> of the tour date are <strong>non-refundable</strong>.</li>

        <li>In case of <strong>adverse weather conditions</strong> that prevent the tour from departing, the tour may be <strong>rescheduled</strong> or a <strong>full refund</strong> will be offered.</li>

        <li><strong>No-shows</strong> (without prior notice) will not be eligible for a refund.</li>

        <li>For <strong>international tours</strong>:

          <ul>

            <li>Cancellations made <strong>45 days prior</strong> – <strong>100% refund</strong>.</li>

            <li>Cancellations made <strong>30 days prior</strong> – <strong>50% refund</strong>.</li>

            <li>Cancellations made in less than <strong>30 days</strong> – <strong>no refund</strong>.</li>

          </ul>

        </li>

        <li>The company reserves the right to modify or cancel the activity for operational or safety reasons.</li>

      </ul>

    </div>



    <!-- Italiano -->

    <div class="lang-block" lang="it">

      <div class="lang-title">🇮🇹 Politica di Cancellazione (Italiano)</div>

      <ul>

        <li>Le cancellazioni effettuate con <strong>più di 72 ore</strong> di anticipo rispetto alla data dell’escursione riceveranno un <strong>rimborso completo</strong>.</li>

        <li>Le cancellazioni effettuate <strong>entro 72 ore</strong> dalla data dell’escursione non sono rimborsabili.</li>

        <li>In caso di <strong>condizioni meteorologiche avverse</strong> che impediscano la partenza, l’escursione potrà essere <strong>riprogrammata</strong> o sarà offerto un <strong>rimborso totale</strong>.</li>

        <li>I <strong>no-show</strong> (assenze senza preavviso) non avranno diritto a rimborso.</li>

        <li>Per i <strong>tour internazionali</strong>:

          <ul>

            <li>Cancellazioni effettuate con <strong>45 giorni di anticipo</strong> – rimborso del <strong>100%</strong>.</li>

            <li>Cancellazioni effettuate con <strong>30 giorni di anticipo</strong> – rimborso del <strong>50%</strong>.</li>

            <li>Cancellazioni con menos de <strong>30 giorni</strong> – <strong>nessun rimborso</strong>.</li>

          </ul>

        </li>

        <li>L’azienda si riserva il diritto di modificare o cancellare l’attività per motivi operativi o di sicurezza.</li>

      </ul>

    </div>



  </div>

</details>



            

            

            

            

            

            

            

            <?php if(isset($cancelacionesArr)) { foreach($cancelacionesArr as $canc) { echo $canc."<br>"; }} ?>

        </div>



        <!-- BOTON IMPRIMIR Y VOLVER -->

        <div class="text-end mb-3 d-flex gap-2 justify-content-end no-print">

            <a href="consultaReserva.php?reserva=<?= $codigoAmigable; ?>" class="btn btn-secondary btn-imprimir">
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