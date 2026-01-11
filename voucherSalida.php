<?php
include("includes/headPagos.php");
include("admin/classes/salidas.php");
include("admin/classes/tarifas.php");
include("admin/classes/idiomas.php");
include("admin/classes/servicio.php");
include("admin/classes/comisiones.php");
include("admin/classes/edades.php");
include("admin/classes/cancelaciones.php");
include("admin/classes/tarifas_ubicacion.php");
include("admin/classes/servicios_adicionales.php");
include("admin/classes/reserva.php");
include("admin/classes/comprobantes.php");
require_once("admin/classes/moneda.php");
include("admin/classes/prestador.php");
include("admin/classes/accesibilidad.php");
include("admin/classes/convierte_monedas.php");
include("admin/classes/destinos.php");

$totalIva = 0;
$totalCarrito = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["idReservaHorarios"])) {
    $idReservaHorarios = $_POST['idReservaHorarios'];
    $horarios = getReservaHorariosId($idReservaHorarios);
    $reserva = getReservaId($horarios[0]['idReserva'])[0];
    $codigoAmigable = $reserva['codigoAmigable'];
    $idReserva = $reserva['idReserva'];
    $fechaAlta = date("d/m/Y", strtotime($reserva["fechaAlta"]));
    $nombreResponsable = $reserva['nombreResponsable'] . " " . $reserva['apellidoResponsable'];
    $emailResponsable = $reserva['emailResponsable'];
    $totalReserva = $reserva["total"];
    $impuestos = $reserva["impuestos"];
    $monedaSel = $reserva["monedaSel"];
    $precio = ConvierteMoneda($monedaSel, $_SESSION["moneda_sel"], $totalReserva);
    $servicio = getServicio($horarios[0]["idServicioSeleccionado"]);
    $idServicioSalidas = ($horarios[0]['idServicioSalidas']);
    $salida = getSalida($idServicioSalidas)[0];

    $idiomasSalida = getIdiomasSalida($idServicioSalidas);
    $prestador = getPrestador($salida["idPrestador"])[0];
    $horaSalida = $salida['horaSalida'];
    $horaCheckIn = $salida['horaCheckIn'];
    $destino = getDestino($servicio[0]['idDestino']);

    $idiomasSalidaTxt = '';
    foreach ($idiomasSalida as $key => $value) {
        $idiomasSalidaTxt = $value . ' ';
    }
    $fechaCheckIn = date("d/m/Y", strtotime($salida['fecha']));
    $idReservaHorarios = $horarios[0]["idReservaHorarios"];
    $codigoVoucherServicio = !empty($horarios[0]['CodigoVoucherServicio']) ? $horarios[0]['CodigoVoucherServicio'] : 'N/A';
    $adicionales = getReservaAdicionalesNoIncluidos($idReservaHorarios);
    $reservaTarifas = getReservaTarifas($idReservaHorarios);

    // Ubicación
    $ubicacion = getUbicacionIdTarifa($reservaTarifas[0]['idServicioSalidasTarifas']); // primera tarifa
    $lat = !empty($horarios[0]['latitud']) ? $horarios[0]['latitud'] : null;
    $lng = !empty($horarios[0]['longitud']) ? $horarios[0]['longitud'] : null;
    $direccion = !empty($horarios[0]['direccion']) ? $horarios[0]['direccion'] : (!empty($ubicacion[0]['direccion']) ? $ubicacion[0]['direccion'] : 'Dirección no disponible');
    $ubicacion_disponible = $lat && $lng;
}

$comprobantes = getComprobantesIdReservaDolar($idReserva);
$totalComprobantesAMostrar = convierteMoneda(188, $monedaSel, $comprobantes);

// Calcular descuento por redondeo (si aplica) y convertir a moneda seleccionada
$descuentoRedondeo = 0;
if (isset($reserva["descuento_redondeo"]) && $reserva["descuento_redondeo"] > 0) {
    $monedaDescuento = isset($reserva["moneda_redondeo"]) ? $reserva["moneda_redondeo"] : $monedaSel;
    $descuentoRedondeo = ConvierteMoneda($monedaDescuento, $_SESSION["moneda_sel"], $reserva["descuento_redondeo"]);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voucher de Servicio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f9f9f9;
            margin: 0;
        }

        .invoice {
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .page-header {
            border-bottom: 2px solid #eaeaea;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }

        .invoice-col strong {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }

        .table th, .table td {
            vertical-align: middle !important;
        }

        .table thead th {
            background: #f1f1f1;
            border-bottom: 2px solid #ddd;
        }

        .table tbody tr:hover {
            background: #f9f9f9;
        }

        .callout {
            padding: 15px 20px;
            border-left: 5px solid #17a2b8;
            background: #e9f7fc;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .btn {
            border-radius: 8px;
            transition: all 0.3s;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        @media (max-width: 767px) {
            .invoice-col {
                margin-bottom: 15px;
            }

            .table-responsive {
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>

<section class="py-4">
    <div class="container">
        <div class="invoice" id="imprimible">
            <div class="row mb-3">
                <div class="col-md-6">
                    <h3 class="page-header"><strong>VOUCHER DE <?= $servicio[0]["nombre_servicio"]; ?></strong></h3>
                </div>
                <div class="col-md-6 text-md-end">
                    <small>Fecha: <?= $fechaCheckIn; ?></small>
                </div>
            </div>

            <div class="row invoice-info mb-4">
                <div class="col-md-4 invoice-col">
                    <strong>Responsável da reserva:</strong>
                    <address>
                        <?= $nombreResponsable; ?><br>
                        <b>Email:</b> <?= $emailResponsable; ?><br>
                        <b>Fecha de compra:</b> <?= $fechaAlta; ?><br>
                        <b>Validez del Voucher:</b> <?= $fechaCheckIn; ?><br>
                    </address>
                </div>
                <div class="col-md-4 invoice-col">
                    <strong>Pais / Evento:</strong>
                    <address>
                        <?= $destino[0]['nombre']; ?><br>
                        <b>Idioma:</b> <?= $idiomasSalidaTxt; ?><br>
                        <b>Check In:</b> <?= $horaCheckIn; ?><br>
                        <b>Salida:</b> <?= $horaSalida; ?><br>
                    </address>
                </div>
                <div class="col-md-4 invoice-col">
                    <b>Comprobante:</b> #<?= $codigoAmigable; ?><br>
                    <b>ID Orden:</b> <?= $codigoAmigable; ?><br>
                    <b>Código Voucher Servicio:</b> <?= $codigoVoucherServicio; ?><br>
                    <b>Tel Prestador:</b> <?= $prestador['telefono']; ?><br>
                    <b>Email Prestador:</b> <?= $prestador['email']; ?><br>
                </div>
            </div>
            <!-- php
            $horarios = getReservaHorarios($idReserva);
            var_dump($horarios); // Muestra todos los horarios y sus campos
            foreach($horarios as $h) {
                $reservaTarifas = getReservaTarifas($h['idReservaHorarios']);
                var_dump($reservaTarifas); // Muestra todas las tarifas de ese horario
                $servicio = getServicio($h["idServicioSeleccionado"]);
                var_dump($servicio); // Muestra el servicio real
            }
              VAR_DUMP PARA REVISAR PORQUE NO LLEGA LA CANTIDAD DE PASAJEROS EL NOMBRE Y POR SOBRE TODO PORQUE NO TOMA LA GEOLOCALIZACION-->


            <div class="table-responsive mb-4">
                <table class="table ">
                    <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Tarifa</th>
                        <th>Edad</th>
                        <th>Check In</th>
                        <th>Subtotal</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $cancelacionesArr = array();
                    for ($j = 0; $j < count($reservaTarifas); $j++) {
                        $tarifa = $reservaTarifas[$j];
                        $idServicioSalidasTarifas = $tarifa['idServicioSalidasTarifas'];
                        $tarifaOrigi = getTarifa($idServicioSalidasTarifas);
                        $cancelacion = getTipoCancelaciones($tarifaOrigi[0]["idCancelaciones"]);
                        if (!in_array($cancelacion[0]['texto'], $cancelacionesArr)) array_push($cancelacionesArr, $cancelacion[0]['texto']);

                        $valorSinIva = $tarifa["valorSinIva"] / $tarifa["cantidad"];
                        $valorDelIva = ConvierteMoneda($tarifa["monedaSel"], $_SESSION["moneda_sel"], $tarifa['valorDeIva']);

                        $edadFrom = getEdad($tarifa["idFromEdad"]);
                        $edadTo = getEdad($tarifa["idToEdad"]);
                        $pasajeros = getPasajeros($tarifa['idReservaTarifas']);
                        foreach ($pasajeros as $p) {
                    echo "<tr>
                        <td>{$p['nombrePasajero']} {$p['apellidoPasajero']}</td>
                        <td>{$tarifa['nombre']}</td>
                        <td>{$edadFrom[0]['valor']} A {$edadTo[0]['valor']} años</td>
                        <td>{$fechaCheckIn} {$horaCheckIn}</td>
                        <td>{$_SESSION['moneda_sel_sym']}{$valorSinIva}</td>
                    </tr>";
                       $totalIva += $valorDelIva;
                        $totalCarrito += $valorSinIva;

                        }
                    }

                    foreach ($adicionales as $adicional) {
                        $precioAdicional = ConvierteMoneda($tarifa["monedaSel"], $_SESSION["moneda_sel"], $adicional['precio']);
                        $totalCarrito += $precioAdicional;
                        $totalIva += ConvierteMoneda($tarifa["monedaSel"], $_SESSION["moneda_sel"], $adicional['valorIva']);
                        echo "<tr>
                    <td colspan='4'>{$adicional['cantidad']} {$adicional['nombre']}</td>
                    <td>{$_SESSION['moneda_sel_sym']}{$precioAdicional}</td>
                </tr>";
                    }

                    $diferenciaAPagar = $reserva["total"] - $totalComprobantesAMostrar;
                    if ($diferenciaAPagar < 10) $diferenciaAPagar = 0;


                    ?>
                    </tbody>
                </table>
            </div>

            <!-- Observaciones -->
            <div class="callout callout-info mb-4">
                <h5><i class="fas fa-info"></i> Observaciones:</h5>
                <textarea class="form-control" rows="3" disabled><?= $salida['nota_salida']; ?></textarea>
            </div>

            <!-- Punto de salida -->
            <div class="callout callout-info mb-4">
                <h5><i class="fas fa-map-marker-alt"></i> Punto de salida:</h5>

                <?php if (!empty($lat) && !empty($lng)): ?>

                    <pre>
 Latitud: <?= $lat ?>
 Longitud: <?= $lng ?>
 Dirección: <?= $direccion ?>
</pre>
                    <iframe
                            width="100%"
                            height="300"
                            style="border:0;"
                            loading="lazy"
                            allowfullscreen
                            src="https://www.google.com/maps/embed/v1/place?key=AIzaSyBEshzf3yb1ZWmvpGJSskvgCMYGbkiRIPw&q=<?= $lat ?>,<?= $lng ?>">
                    </iframe>
                    <p><?= $direccion ?></p>
                <?php else: ?>
                    <p>La ubicación del punto de salida no está disponible.</p>
                <?php endif; ?>
            </div>


            <!-- Resumen -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <p class="lead">Métodos de Pago:</p>
                    <img src="img/credit/visa.png" alt="Visa">
                    <img src="img/credit/mastercard.png" alt="Mastercard">
                    <img src="img/credit/american-express.png" alt="American Express">
                    <img src="img/credit/paypal2.png" alt="Paypal">
                </div>
                <div class="col-md-6 table-responsive">
                    <h5>Resumen</h5>
                    <table class="table">
                        <tr>
                            <th>Impuestos:</th>
                            <td><?= $_SESSION["moneda_sel_sym"] . $totalIva; ?></td>
                        </tr>
                        <?php if ($descuentoRedondeo > 0): ?>
                        <tr class="text-success">
                            <th><i class="fas fa-gift"></i> Descuento por Redondeo:</th>
                            <td>-<?= $_SESSION["moneda_sel_sym"] . number_format($descuentoRedondeo, 2, ',', '.'); ?></td>
                        </tr>
                        <?php endif; ?>
                        <tr>
                            <th>Total:</th>
                            <td><?= $_SESSION["moneda_sel_sym"] . number_format(($totalCarrito + $totalIva - $descuentoRedondeo), 2, ',', '.'); ?></td>
                        </tr>
                        <tr>
                            <th>Resta pagar:</th>
                            <td><?= $_SESSION["moneda_sel_sym"] . " " . number_format(($diferenciaAPagar - $descuentoRedondeo), 2, ',', '.'); ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Políticas -->
            <div class="callout callout-info mb-4">
                <h5><i class="fas fa-info"></i> Políticas:</h5>
                <?php foreach ($cancelacionesArr as $texto) echo $texto . "<br>"; ?>
            </div>

            <!-- Botones -->
            <div class="row no-print">
                <div class="col-12 d-flex gap-2 flex-wrap">
                    <form method="post" action="voucherCarrito" class="flex-fill">
                        <button class="btn btn-secondary w-100" type="submit" name="codigoAmigable"
                                value="<?= $codigoAmigable; ?>">
                            <i class="fas fa-arrow-left"></i> Volver
                        </button>
                    </form>
                    <button id="btnImprimir" class="btn btn-primary flex-fill"><i class="fas fa-print"></i> Imprimir
                    </button>
                </div>
            </div>

        </div>
    </div>
</section>

<script>
    function imprimirElemento(elemento) {
        var ventana = window.open('', 'PRINT', 'height=600,width=800');
        ventana.document.write('<html><head><title>' + document.title + '</title>');
        ventana.document.write('<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">');
        ventana.document.write('</head><body>');
        ventana.document.write(elemento.innerHTML);
        ventana.document.write('</body></html>');
        ventana.document.close();
        ventana.focus();
        ventana.print();
    }

    document.querySelector("#btnImprimir").addEventListener("click", function () {
        var div = document.querySelector("#imprimible");
        imprimirElemento(div);
    });
</script>

</body>
</html>
