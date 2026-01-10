<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include("config/mercadopago.php");
// Conexión DB según entorno (dev/prod)
$envFromVar = getenv('APP_ENV');
$productionMode = $envFromVar ? ($envFromVar === 'prod') : true;


    // Dev: usa configuración compartida (root sin password, DB metelebrasil)
    include("config/db.php");



$ajax_pagamento = isset($_POST['ajax_pagamento']) ? $_POST['ajax_pagamento'] : '';
if ($ajax_pagamento) {
    $total = isset($_POST['total']) ? $_POST['total_dolares'] : '';
    $total_dolares = isset($_POST['total_dolares']) ? $_POST['total_dolares'] : '';
    $gateway_pagamento = isset($_POST['gateway_pagamento']) ? $_POST['gateway_pagamento'] : '';
    $codigo_amigavel = isset($_POST['codigo_amigavel']) ? $_POST['codigo_amigavel'] : '';
    $amount_currency_code = isset($_POST['amount_currency_code']) ? $_POST['amount_currency_code'] : '';
    $id_transacao = isset($_POST['id_transacao']) ? $_POST['id_transacao'] : '';


    $status = false;
    $retorno = '';
    $idReserva = '';
    $idUsuario = '';
    $codigoAmigable = '';
    $servicio = '';


    $sql_origenes_gateway_pagamento_reserva = "SELECT idOrigen FROM origenes WHERE nombre = '$gateway_pagamento'";
    $query_origenes_gateway_pagamento_reserva = $mysqli->query($sql_origenes_gateway_pagamento_reserva);
    $idOrigen = $query_origenes_gateway_pagamento_reserva->fetch_assoc()['idOrigen'];


    $sql_select_moeda = "SELECT idMoneda FROM moneda WHERE CurrencyISO = '$amount_currency_code'";
    $query_select_moeda = $mysqli->query($sql_select_moeda);
    $idMoneda = $query_select_moeda->fetch_assoc()['idMoneda'];


    $sql_select_reserva = "SELECT * FROM reservas WHERE codigoAmigable = '$codigo_amigavel'";
    $query_select_reserva = $mysqli->query($sql_select_reserva);


    if ($query_select_reserva->num_rows > 0) {
        while ($db_select_reservas = $query_select_reserva->fetch_assoc()) {
            $idReserva = $db_select_reservas['idReserva'];
            $idUsuario = $db_select_reservas['idUsuario'];
        }
    }


    if (isset($_SESSION['login'])) {
        $idUsuario = $_SESSION['login']['idUsuario'];
    }


    $sql_confirma_reserva = "UPDATE reservas SET idEstado=3 WHERE codigoAmigable = '$codigo_amigavel'";
    $query_confirma_reserva = $mysqli->query($sql_confirma_reserva);


    if ($query_confirma_reserva) {

        $sq_insert_comprovante = "INSERT INTO comprobante
                                  (
                                    idReserva,
                                    total,
                                    total_dolares,
                                    origenComprobante,
                                    monedaComprobante,
                                    fechaIngreso,
                                    compOrigen,
                                    idUsuario
                                  )
                                  VALUES
                                  (
                                    '$idReserva',
                                    '$total',
                                    '$total_dolares',
                                    '$idOrigen',
                                    '$idMoneda',
                                    NOW(),
                                    '$id_transacao',
                                    '$idUsuario'
                                  )
                                  ";

        $query_insert_comprovante = $mysqli->query($sq_insert_comprovante);
        if ($query_insert_comprovante) {
            $status = true;
            $retorno = "Reserva confirmada com sucesso!";
        }
    }

    echo json_encode([
            'status' => $status,
            'retorno' => $retorno,
    ]);
    exit();
}
require_once("includes/headPagos.php");
require_once("admin/classes/salidas.php");
require_once("admin/classes/tarifas.php");
require_once("admin/classes/idiomas.php");
require_once("admin/classes/servicio.php");
require_once("admin/classes/comisiones.php");
require_once("admin/classes/edades.php");
require_once("admin/classes/cancelaciones.php");
require_once("admin/classes/servicios_adicionales.php");
require_once("admin/classes/reserva.php");
require_once("admin/classes/comprobantes.php");
require_once("admin/classes/moneda.php");
require_once("admin/classes/convierte_monedas.php");

// Formatea montos: sin decimales si es entero, 2 decimales si tiene centavos
if (!function_exists('formatarMonedaCondicional')) {
    function formatarMonedaCondicional($valorNumerico)
    {
        $valorNumerico = (float)$valorNumerico;
        $esEntero = fmod($valorNumerico, 1.0) === 0.0;
        
        // Detectar moneda para formato correcto
        $moneda = $_SESSION['moneda_sel_sym'] ?? 'US$';
        
        // Formato latino (R$, AR$, etc.): 1.234,56
        // Formato anglosajón (US$, etc.): 1,234.56
        if ($moneda === 'R$' || $moneda === 'AR$') {
            return $esEntero
                ? number_format($valorNumerico, 0, ',', '.')
                : number_format($valorNumerico, 2, ',', '.');
        }
        
        // Formato por defecto (anglosajón)
        return $esEntero
            ? number_format($valorNumerico, 0, '.', ',')
            : number_format($valorNumerico, 2, '.', ',');
    }
}

if (!function_exists('formatarMonedaPorSimbolo')) {
    function formatarMonedaPorSimbolo($valorNumerico, $simboloMoneda, $decimales = 2)
    {
        $valorNumerico = (float)$valorNumerico;
        
        // Formato latino (R$, AR$, etc.): 1.234,56
        if ($simboloMoneda === 'R$' || $simboloMoneda === 'AR$') {
            return number_format($valorNumerico, $decimales, ',', '.');
        }
        
        // Formato por defecto (anglosajón): 1,234.56
        return number_format($valorNumerico, $decimales, '.', ',');
    }
}


// unset($_SESSION['login']);
// echo '<pre>';
// print_r($_SESSION);
// echo '</pre>';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["merchant_payment_code"])) {
    $codigoAmigable = $_GET["merchant_payment_code"];
    $reserva = getReserva($codigoAmigable);
    
    if (empty($reserva) || count($reserva) < 1) {
        alertar("La reserva con el código " . $codigoAmigable . " no existe", "error");
        redireccionarLento("index");
        exit();
    }
    
    $reserva = $reserva[0];
    $idReserva = $reserva["idReserva"];
    $moneda = getMoneda($reserva["monedaSel"])[0]["Symbol"];
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["reserva"])) {
    $codigoAmigable = $_GET["reserva"];
    $reserva = getReserva($codigoAmigable);

    if (empty($reserva) || count($reserva) < 1) {
        $mensaje = isset($lang["la_reserva_con_el_codigo"]) ? 
            $lang["la_reserva_con_el_codigo"] . " " . $codigoAmigable . " " . $lang["no_existe"] : 
            "La reserva con el código " . $codigoAmigable . " no existe";
        alertar($mensaje, "error");
        redireccionarLento("index");
        exit();
    }

    $reserva = getReserva($codigoAmigable)[0];
    $idReserva = $reserva["idReserva"];
    $moneda = getMoneda($reserva["monedaSel"])[0]["Symbol"];
}
$comprobantes = getComprobantesIdReservaDolar($idReserva);
$total_dolares = $reserva["total_dolares"];

// Si la moneda seleccionada coincide con la moneda original de la reserva, usar el total original
// Esto evita errores de redondeo en reconversiones
$monedaOriginalReserva = $reserva["monedaSel"];
$total_en_moneda_seleccionada = ($monedaOriginalReserva == $_SESSION['moneda_sel'] && isset($reserva["total"])) 
    ? $reserva["total"] 
    : convierteMoneda(188, $_SESSION['moneda_sel'], $total_dolares);

$comprobantes225 = convierteMoneda(188, 225, $comprobantes);
$comprobantes270 = convierteMoneda(188, 270, $comprobantes);
$comprobantes271 = convierteMoneda(188, 271, $comprobantes);
$comprobantes283 = convierteMoneda(188, 283, $comprobantes); ?>

<!-- Material Icons -->
<link href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp"
      rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Material+Symbols|Material+Symbols+Outlined|Material+Symbols+Two+Tone|Material+Symbols+Round|Material+Symbols+Sharp"
      rel="stylesheet">


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
      integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

<!-- consultaReserva Romeci -->
<link href="css/consultaReserva.css?v=<?= uniqid(); ?>" rel="stylesheet">

<!--PASOS PARA RESERVA-->

<section class="py-2 bg-white">
    <div class="container">
        <div class="row">
            <div class="col-lg-12  ">
                <ul class="lista-pasos-form">
                    <li class="active"><strong></i><?= $lang["consulta_de_reserva"] ?> <?= $codigoAmigable; ?></strong>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!--FIN PASOS PARA RESERVA-->


<!--SECCION DATOS PERSONALES-->

<section>
    <div class="container">
        <div class="row">

            <!--RESUMEN DE PEDIDO-->

            <div class="col-lg-4 col-md-4">
                <div class="py-2">
                    <div class="card card-visitas">
                        <div class="card-body">
                            <h5><?= $lang["resumen_de_compra"] ?></h5>
                            <?php
                            $cantCarrito = 0;
                            $resTmp = getReserva($codigoAmigable);
                            if (!empty($resTmp)) {
                                $horTmp = getReservaHorarios($resTmp[0]['idReserva']);
                                $cantCarrito = count($horTmp);
                            }
                            ?>
                            <style>
                                .card-accordion > div[data-toggle="collapse"] i.fa-chevron-down {
                                    transform: rotate(0deg);
                                    transition: transform 0.3s ease;
                                }
                                .card-accordion > div.collapsed[data-toggle="collapse"] i.fa-chevron-down {
                                    transform: rotate(180deg);
                                }
                                .card-accordion > div {
                                    cursor: pointer;
                                }
                                .card-accordion > div:hover {
                                    opacity: 0.9;
                                }
                            </style>

                            <!--ACORDEON CARACTERISTICAS-->
                            <div class="accordion" id="faq1">
                                <div class="card card-accordion" style="border-top: 3px solid #029ce2 !important; border: none;">
                                    <div class="" id="headingOne">
                                        <h5 class="mb-0">
                                            <div class="d-flex justify-content-between align-items-center w-100" style="padding: 1rem; background: linear-gradient(90deg, #029ce2 0%, #0284c7 100%); border-radius: 6px; cursor: pointer;" data-toggle="collapse" data-target="#collapseOne2" aria-expanded="true" aria-controls="collapseOne">
                                                <div>
                                                    <span class="badge badge-light badge-pill mr-2"><i class="fas fa-shopping-bag mr-1"></i><?= $cantCarrito; ?></span>
                                                    <span style="font-size: 18px; font-weight: 500; color: white;"><?= $lang["servicios"] ?></span>
                                                </div>
                                                <i class="fas fa-chevron-down text-white" style="font-size: 12px;"></i>
                                            </div>
                                        </h5>
                                    </div>

                                    <div id="collapseOne2" class="collapse show" aria-labelledby="headingOne"
                                         data-parent="#faq1">
                                        <ul class="lista-caracteristicas-r mx-4">


                                            <?php
                                            // Obter dados da reserva
                                            $reserva = getReserva($codigoAmigable);
                                            if (!empty($reserva)) {
                                                $idReserva = $reserva[0]["idReserva"];
                                                $horarios = getReservaHorarios($idReserva);

                                                // Exibir informações básicas da reserva
                                                echo "<li><i class=\"fas fa-user text-primary mr-1\"></i><strong>Responsável:</strong> {$reserva[0]['nombreResponsable']} {$reserva[0]['apellidoResponsable']}</li>";
                                                echo "<li><i class=\"fas fa-envelope text-primary mr-1\"></i><strong>Email:</strong> {$reserva[0]['emailResponsable']}</li>";
                                                echo "<li><i class=\"fas fa-phone text-primary mr-1\"></i><strong>Telefone:</strong> {$reserva[0]['telefonoResponsable']}</li>";
                                                echo "<li><i class=\"fas fa-wallet text-success mr-1\"></i><strong>Total em Dólares:</strong> " . formatarMonedaCondicional($total_dolares) . "</li>";
                                                echo "<li><i class=\"fas fa-receipt text-info mr-1\"></i><strong>Impostos:</strong> {$reserva[0]['impuestos']}</li>";

                                                                                                // Exibir horários, tarifas e adicionais, em cartões separados
                                                                                                for ($i = 0; $i < count($horarios); $i++) {
                                                                                                        $servicio = getServicio($horarios[$i]["idServicioSeleccionado"]);
                                                                                                        $idReservaHorarios = $horarios[$i]["idReservaHorarios"];
                                                                                                        $reservaTarifas = getReservaTarifas($idReservaHorarios);
                                                                                                        $adicionales = getReservaAdicionalesNoIncluidos($idReservaHorarios);
                                                                                                        $fechaHora = date("d/m/Y", strtotime($horarios[$i]['fecha'])) . " · Check IN: " . substr($horarios[$i]["horaCheckIn"], 0, 5);
                                                                                                        ?>

                                                                                                        <li class="mb-3">
                                                                                                            <div class="card shadow-sm border-0">
                                                                                                                <div class="card-body p-3">
                                                                                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                                                                                        <div>
                                                                                                                            <i class="fas fa-map-marker-alt text-primary mr-2"></i>
                                                                                                                            <strong><?= $servicio[0]["nombre_servicio"]; ?></strong>
                                                                                                                        </div>
                                                                                                                        <span class="badge badge-primary badge-pill p-2">
                                                                                                                            <i class="fas fa-calendar-alt mr-1"></i><?= $fechaHora; ?>
                                                                                                                        </span>
                                                                                                                    </div>

                                                                                                                    <ul class="list-unstyled small text-muted mb-0">
                                                                                                                        <?php for ($j = 0; $j < count($reservaTarifas); $j++) {
                                                                                                                                $edadFrom = getEdad($reservaTarifas[$j]["idFromEdad"]);
                                                                                                                                $edadTo = getEdad($reservaTarifas[$j]["idToEdad"]);
                                                                                                                                $idMonedaSel = $reservaTarifas[$j]["monedaSel"];
                                                                                                                                $moneda = getMoneda($idMonedaSel)[0]["Symbol"]; ?>
                                                                                                                                <li>
                                                                                                                                    <i class="fas fa-ticket-alt text-secondary mr-1"></i><strong>Tarifa:</strong> <?= $reservaTarifas[$j]["cantidad"]; ?> <?= $reservaTarifas[$j]["nombre"]; ?> (<?= $edadFrom[0]["valor"] ?> a <?= $edadTo[0]["valor"] ?> anos)
                                                                                                                                </li>
                                                                                                                                <li>
                                                                                                                                    <i class="fas fa-percent text-warning mr-1"></i><strong>ISS:</strong> <?= $moneda . formatarMonedaPorSimbolo($reservaTarifas[$j]["valorDeIva"], $moneda); ?>
                                                                                                                                </li>
                                                                                                                                <li>
                                                                                                                                    <i class="fas fa-calculator text-muted mr-1"></i><strong>Subtotal:</strong> <?= $moneda . formatarMonedaPorSimbolo($reservaTarifas[$j]["valorSinIva"] + $reservaTarifas[$j]["valorDeIva"], $moneda); ?>
                                                                                                                                </li>
                                                                                                                        <?php } ?>

                                                                                                                        <?php for ($j = 0; $j < count($adicionales); $j++) { ?>
                                                                                                                                <li>
                                                                                                                                    <i class="fas fa-plus-circle text-info mr-1"></i><strong>Adicional:</strong> <?= $adicionales[$j]["cantidad"]; ?> <?= $adicionales[$j]["nombre"]; ?> <?= $moneda . formatarMonedaPorSimbolo($adicionales[$j]["precioIva"], $moneda); ?>
                                                                                                                                </li>
                                                                                                                        <?php } ?>
                                                                                                                    </ul>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </li>

                                                                                                <?php } ?>

                                                <?php if ($comprobantes > 0) { ?>
                                                    <li>
                                                        <strong>Total:</strong> <?= $moneda . formatarMonedaCondicional($total_en_moneda_seleccionada); ?>
                                                    </li>
                                                    <?php 
                                                    // Calcular comprobantes en moneda seleccionada
                                                    if ($monedaOriginalReserva == $_SESSION['moneda_sel'] && isset($reserva["total"]) && $total_dolares > 0) {
                                                        // Proporcional si es la misma moneda
                                                        $comprobantes_en_moneda_sel = $comprobantes * ($reserva["total"] / $total_dolares);
                                                    } else {
                                                        $comprobantes_en_moneda_sel = convierteMoneda(188, $_SESSION['moneda_sel'], $comprobantes);
                                                    }
                                                    
                                                    $totalComprobantesAMostrar = $comprobantes;
                                                    $diferenciaAPagar_usd = $total_dolares - $totalComprobantesAMostrar;
                                                    
                                                    // Calcular diferencia en moneda seleccionada manteniendo precisión
                                                    $diferenciaAPagar_moneda_sel = $total_en_moneda_seleccionada - $comprobantes_en_moneda_sel;

                                                    if ($diferenciaAPagar_usd < 1) {
                                                        $diferenciaAPagar_usd = 0;
                                                        $diferenciaAPagar_moneda_sel = 0;
                                                    } ?>
                                                        <li><strong>Pagamento
                                                            Realizado:</strong> <?= $moneda . formatarMonedaCondicional($comprobantes_en_moneda_sel); ?>
                                                    </li>

                                                <?php } else {
                                                    $diferenciaAPagar_usd = $total_dolares; // Fixed: If no previous payments, remaining is the full total.
                                                    $diferenciaAPagar_moneda_sel = $total_en_moneda_seleccionada;
                                                    ?>


                                                    <li id='total-mostrar-moeda'>
                                                        <strong>Total:</strong> <?= $moneda . formatarMonedaCondicional($total_en_moneda_seleccionada); ?>
                                                    </li>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <li>Nenhuma reserva encontrada.</li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!--FIN ACORDEON CARACTERISTICAS EL -->

                            <hr class="hr-puntuada">

                            <!--PRECIO TOTAL-->
                            <div class="div-precio-t">
                                <p class="mb-0 float-left"><strong>Resta pagar</strong></p>
                                <p class="mb-0 float-right"><strong
                                            id="total-resta-pagar"><?= $moneda . " " . formatarMonedaCondicional($diferenciaAPagar_moneda_sel); ?></strong>
                                </p>
                            </div>
                            <!--FIN PRECIO TOTAL-->
                        </div>
                    </div>
                </div>
                
                  <div class="payment-secure-section text-center mt-4 mb-3">
                  <div class="trust-text mb-3">
                    <div class="trust-badge d-inline-flex align-items-center justify-content-center mb-2">
                      <i class="fas fa-lock mr-2"></i>
                      <span>Pago 100% seguro</span>
                    </div>
                    <p class="text-muted mb-0" style="font-size: 0.9rem;">
                      Protegemos tus datos con encriptación SSL y plataformas certificadas.
                    </p>
                  </div>

  
            </div>

<style>
  /* Fondo premium con sombra */
  .payment-secure-section {
    background: #f9f9f9;
    border: 1px solid #e0e0e0;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    padding: 20px 10px 15px;
    transition: box-shadow 0.3s ease;
  }

  .payment-secure-section:hover {
    box-shadow: 0 6px 14px rgba(0,0,0,0.08);
  }

  /* Sello de confianza */
  .trust-badge {
    background: linear-gradient(90deg, #4CAF50, #2E7D32);
    color: #fff;
    font-weight: 600;
    font-size: 1rem;
    padding: 6px 14px;
    border-radius: 50px;
    box-shadow: 0 2px 6px rgba(76,175,80,0.4);
    letter-spacing: 0.5px;
  }

  .trust-badge i {
    font-size: 1.2rem;
  }

  /* Carrusel */
  .payment-carousel {
    overflow: hidden;
    position: relative;
    width: 100%;
    margin-top: 10px;
  }

  .carousel-track {
    display: inline-flex;
    white-space: nowrap;
    animation: scroll-left 25s linear infinite;
  }

  @keyframes scroll-left {
    0% { transform: translateX(0); }
    100% { transform: translateX(-50%); }
  }

  /* Logos */
  .payment-logo {
    height: 46px;
    width: auto;
    opacity: 0.9;
    filter: grayscale(100%);
    transition: all 0.3s ease;
  }

  .payment-logo:hover {
    opacity: 1;
    filter: grayscale(0%);
    transform: scale(1.08);
  }

  /* Responsivo */
  @media (max-width: 768px) {
    .payment-logo {
      height: 34px;
      margin: 0 6px;
    }
  }

  /* Pausa animación al pasar el mouse */
  .payment-carousel:hover .carousel-track {
    animation-play-state: paused;
  }
</style>
            </div>
            
            <!--FIN RESUMEN DE PEDIDO-->
            <!--DATOS DE PAGO-->
            <?php
            if ($comprobantes < $total_dolares) { ?>


            <div class="col-lg-8 col-md-8">

                <!--METODOS DE PAGO-->


                <?php $countryEbanx = '';
                $currencyEbanx = '';
                $habilita_pesos_arg = '';
                $habilita_reales = '';
                $habilita_pesos_ch = '';
                $habilita_guaranies = '';
                $habilita_dolares = '';
                $habilita_soles_peruanos = '';
                $habilita_ebanx = '';

                /*
array(9) { 
["parametros"]=> array(2) {
    ["pathSv"]=> string(0) ""
    ["pathAdmin"]=> string(6) "admin/"
}
["realIP"]=> string(14) "170.82.181.232"
["geo"]=> array(10) {
    ["longitud"]=> NULL
    ["latitud"]=> NULL
    ["ciudad"]=> NULL
    [0]=> NULL
    ["nombre_pais"]=> string(11) "Desconocido"
    ["idPais"]=> int(188)
    ["sym"]=> string(3) "U$S"
    ["countryCode"]=> NULL
    ["lang"]=> string(2) "EN"
    ["langFunny"]=> string(6) "INGLES"
}
["impuestos_pais"]=> int(0)
["moneda_sel"]=> int(188)
["moneda_sel_sym"]=> string(3) "U$S"
["idioma_bandera"]=> string(46) "img/countries/United-States-of-Americ-icon.png"
["idioma"]=> string(2) "EN"
["login"]=> array(1) {
    ["idVendedor"]=> int(0)

}
} 

*/


               //session_start(); // session_start() is already called at the top of the file

                //foreach ($_SESSION as $chave => $valor) {echo "$chave: $valor<br>";}


                if (isset($_GET['countryCode'])) {
                    $_SESSION['geo']['countryCode'] = $_GET['countryCode'];
                    switch ($_GET['countryCode']) {
                        case 'AR':
                            $_SESSION['moneda_sel'] = 270;
                            $_SESSION['moneda_sel_sym'] = 'AR$';
                            break;
                        case 'BR':
                            $_SESSION['moneda_sel'] = 283;
                            $_SESSION['moneda_sel_sym'] = 'R$';
                            break;
                        case 'PY':
                            $_SESSION['moneda_sel'] = 225;
                            $_SESSION['moneda_sel_sym'] = 'Gs$';
                            break;
                        case 'CL':
                            $_SESSION['moneda_sel'] = 271;
                            $_SESSION['moneda_sel_sym'] = 'CL$';
                            break;
                        case 'USE':
                            $_SESSION['moneda_sel'] = 188;
                            $_SESSION['moneda_sel_sym'] = 'U$S';
                            break;
                        default:
                            break;
                    }
                }

				if (isset($_SESSION['moneda_sel']) && is_string($_SESSION['moneda_sel'])) {
					switch ($_SESSION['moneda_sel']) {
						case 'AR':
							$_SESSION['moneda_sel'] = 270;
							$_SESSION['moneda_sel_sym'] = 'AR$';
							break;
						case 'BR':
							$_SESSION['moneda_sel'] = 283;
							$_SESSION['moneda_sel_sym'] = 'R$';
							break;
						case 'PY':
							$_SESSION['moneda_sel'] = 225;
							$_SESSION['moneda_sel_sym'] = 'Gs$';
							break;
						case 'CL':
							$_SESSION['moneda_sel'] = 271;
							$_SESSION['moneda_sel_sym'] = 'CL$';
							break;
						case 'USE':
							$_SESSION['moneda_sel'] = 188;
							$_SESSION['moneda_sel_sym'] = 'U$S';
							break;
						default:
							break;
					}
				}
                $monedaSel = isset($_SESSION['moneda_sel']) ? $_SESSION['moneda_sel'] : null;
                if (!$monedaSel) {
                    // Handle case where moneda_sel is not set, possibly default to USD or show error.
                    // For now, let's assume a default to avoid immediate exit.
                    // echo "Seleção de moeda não definida.";
                    // exit;
                    $_SESSION['moneda_sel'] = 188; // Default to USD (188) if not set
                    $monedaSel = 188;
                }
                switch ($_SESSION['moneda_sel']) {
                    case 270:
                        $habilita_reales = false;
                        $habilita_pesos_arg = true;
                        $habilita_pesos_ch = false;
                        $countryEbanx = 'AR';
                        $currencyEbanx = 'ARS';
                        $habilita_ebanx = true;
                        $_SESSION['geo']['nombre_pais'] = "Argentina";
                        $totalEbanx = convierteMoneda(188, 270, $total_dolares) - $comprobantes;

                        break;
                    case 283:
                        $habilita_reales = true;
                        $habilita_pesos_arg = false;
                        $habilita_pesos_ch = false;
                        $countryEbanx = 'BR';
                        $currencyEbanx = 'BRL';
                        $totalEbanx = convierteMoneda(188, 283, $total_dolares) - $comprobantes;
                        $habilita_ebanx = false;
                        $_SESSION['geo']['nombre_pais'] = 'Brasil';

                        break;
                    case 271:
                        $habilita_reales = false;
                        $habilita_pesos_arg = false;
                        $habilita_pesos_ch = true;
                        $countryEbanx = 'CL';
                        $currencyEbanx = 'CLP';
                        $totalEbanx = convierteMoneda(188, 271, $total_dolares) - $comprobantes;
                        $habilita_ebanx = true;
                        $_SESSION['geo']['nombre_pais'] = 'Chile';
                        break;

                    case 'UY':
                        $habilita_dolares = true;
                        $countryEbanx = 'UY';
                        $currencyEbanx = 'USD';
                        $totalEbanx = convierteMoneda(188, 188, $total_dolares) - $comprobantes;
                        $habilita_ebanx = true;
                        $_SESSION['geo']['nombre_pais'] = 'Uruguay';
                        break;

                    case 'PE':
                        $habilita_dolares = true;
                        $countryEbanx = 'PE';
                        $currencyEbanx = 'USD';
                        $totalEbanx = convierteMoneda(188, 188, $total_dolares) - $comprobantes;
                        $habilita_ebanx = true;
                        $_SESSION['geo']['nombre_pais'] = 'Peru';
                        break;

                    case 225:
                        $habilita_guaranies = true;
                        $countryEbanx = 'PY';
                        $currencyEbanx = 'USD';
                        $totalEbanx = convierteMoneda(188, 225, $total_dolares) - $comprobantes;
                        $habilita_ebanx = true;
                        $_SESSION['geo']['nombre_pais'] = 'Paraguay';

                        break;
                    default:
                        break;
                } ?>


                <div class="row g-3 py-3">

                    <!--Modicicação-->


                    <?php
                    // Usar el valor en USD para conversiones a otras monedas (pasarelas de pago)
                    $diferenciaAPagar = $diferenciaAPagar_usd;
                    
                    $reales = convierteMoneda(188, 283, $diferenciaAPagar);
                    $euro = convierteMoneda(188, 213, $diferenciaAPagar);
                    $dolares = $diferenciaAPagar;
                    // CORRECTED LINE: Calculate Guaraníes based on the remaining amount to pay in dollars
                    $guaranies = convierteMoneda(188, 225, $diferenciaAPagar);
                    $pesos_argentinos = convierteMoneda(188, 270, $diferenciaAPagar);
                    $pesos_chilenos = convierteMoneda(188, 271, $diferenciaAPagar);

                    $arr_valores_pagamento = [
                            [
                                    'valor' => $reales,
                                    'moeda' => [
                                            'codigo' => 'BRL',
                                            'simbolo' => "R$",
                                            'nome' => "Real Brasileiro",
                                    ],
                                    'obs' => "Aceita parcelado"
                            ],
                            [
                                    'valor' => $euro,
                                    'moeda' => [
                                            'codigo' => 'EUR',
                                            'simbolo' => "€",
                                            'nome' => "Euros",
                                    ],
                                    'obs' => ""
                            ],
                            [
                                    'valor' => $dolares,
                                    'moeda' => [
                                            'codigo' => 'USD',
                                            'simbolo' => "USD$",
                                            'nome' => "Dólares US",
                                    ],
                                    'obs' => ""
                            ],
                            [
                                    'valor' => $guaranies,
                                    'moeda' => [
                                            'codigo' => 'PYG',
                                            'simbolo' => "Gs$",
                                            'nome' => "Guaraníes",
                                    ],
                                    'obs' => ""
                            ],
                            [
                                    'valor' => $pesos_argentinos,
                                    'moeda' => [
                                            'codigo' => 'ARS',
                                            'simbolo' => "AR$",
                                            'nome' => "Peso Argentino",
                                    ],
                                    'obs' => ""
                            ],
                            [
                                    'valor' => $pesos_chilenos,
                                    'moeda' => [
                                            'codigo' => 'CLP',
                                            'simbolo' => "CL$",
                                            'nome' => "Peso Chileno",
                                    ],
                                    'obs' => ""
                            ],
                    ];


                    ?>

                        <style>
                            /* --- Mantiene tu diseño original --- */
                            .container-valor-moeda div:first-child {
                              display: flex;
                              align-items: center;
                              gap: 5px; /* espacio entre número y bandera */
                            }
                            
                            .flag-icon {
                              width: 24px;
                              height: 16px;
                              border-radius: 3px;
                              object-fit: cover;
                            }
                            
                            /* --- Responsive --- */
                            @media (max-width: 767px) {
                              #container-valores-moedas .col-4 {
                                flex: 0 0 100%;
                                max-width: 100%;
                              }
                            
                              .flag-icon {
                                width: 18px;
                                height: 12px;
                              }
                            }
                            </style>
                            
                            <div class="col-12">
                              <div id="container-valores-moedas">
                                <div class="row mx-auto w-100 g-3">
                            
                                  <div class='hr w-100 mb-2 mt-3' style="opacity: 0.3;"></div>
                                  <div class="col-12 mx-auto">
                                    <h4 style="text-align: center; font-weight: 200; opacity: 0.8;">Qual moeda você deseja pagar?</h4>
                                  </div>
                                  <div class='hr w-100 mt-1 mb-3' style="opacity: 0.3;"></div>

                                                                    <style>
                                                                        /* Evita que precio y bandera se salgan del recuadro en el selector de moneda */
                                                                        #container-valores-moedas .container-vm { min-width: 0; }
                                                                        #container-valores-moedas .container-valor-moeda div { display: flex; align-items: center; gap: 6px; flex-wrap: wrap; }
                                                                        #container-valores-moedas .container-valor-moeda strong { white-space: normal; }
                                                                        #container-valores-moedas .flag-icon { flex-shrink: 0; }
                                                                    </style>
                            
                                  <?php
                                                                    function formatarParaDecimal($valor)
                                                                    {
                                                                        // Si ya es numérico, formatearlo directamente
                                                                        if (is_numeric($valor)) {
                                                                            return formatarMonedaCondicional((float)$valor);
                                                                        }
                                                                        
                                                                        // Limpiar y convertir string a número
                                                                        // Detectar si usa coma como decimal (ej: "1.234,56") o punto (ej: "1,234.56")
                                                                        $valorLimpio = preg_replace('/[^\d.,]/', '', $valor);
                                                                        
                                                                        // Si tiene ambos separadores, determinar cuál es el decimal
                                                                        if (strpos($valorLimpio, '.') !== false && strpos($valorLimpio, ',') !== false) {
                                                                            // Si el último separador es coma, formato europeo: 1.234,56 → 1234.56
                                                                            if (strrpos($valorLimpio, ',') > strrpos($valorLimpio, '.')) {
                                                                                $valorLimpio = str_replace('.', '', $valorLimpio);
                                                                                $valorLimpio = str_replace(',', '.', $valorLimpio);
                                                                            } else {
                                                                                // Formato americano: 1,234.56 → 1234.56
                                                                                $valorLimpio = str_replace(',', '', $valorLimpio);
                                                                            }
                                                                        } elseif (strpos($valorLimpio, ',') !== false) {
                                                                            // Solo comas: puede ser miles o decimal, asumimos decimal si hay 2 dígitos después
                                                                            $valorLimpio = str_replace(',', '.', $valorLimpio);
                                                                        }
                                                                        // Si solo tiene puntos, ya está en formato correcto
                                                                        
                                                                        return formatarMonedaCondicional((float)$valorLimpio);
                                                                    }

								  $flags = [
									'BRL' => 'https://flagcdn.com/w20/br.png',
									'USD' => 'https://flagcdn.com/w20/us.png',
									'EUR' => 'https://flagcdn.com/w20/eu.png',
									'ARS' => 'https://flagcdn.com/w20/ar.png',
									'PYG' => 'https://flagcdn.com/w20/py.png',
									'CLP' => 'https://flagcdn.com/w20/cl.png',
								  ];

								  $moeda_codigo_selecionado = '';
								  if (isset($_SESSION['moneda_sel'])) {
									switch ($_SESSION['moneda_sel']) {
										case 283:
											$moeda_codigo_selecionado = 'BRL';
											break;
										case 213:
											$moeda_codigo_selecionado = 'EUR';
											break;
										case 188:
											$moeda_codigo_selecionado = 'USD';
											break;
										case 225:
											$moeda_codigo_selecionado = 'PYG';
											break;
										case 270:
											$moeda_codigo_selecionado = 'ARS';
											break;
										case 271:
											$moeda_codigo_selecionado = 'CLP';
											break;
										default:
											break;
									}
								  }

								  $html_valor_moeda = "";
								  $active_set = false;
								  foreach ($arr_valores_pagamento as $k => $v) {
                                    $valor = $v['valor'];
                                    $valor_formatado = formatarParaDecimal($valor);
									$moeda_codigo = $v['moeda']['codigo'];
									$moeda_simbolo = $v['moeda']['simbolo'];
									$moeda_nome = $v['moeda']['nome'];
									$obs = "";
									if (array_key_exists('obs', $v) && !empty($v['obs'])) {
										$obs = $v['obs'];
									}
									$flag_url = $flags[$moeda_codigo] ?? '';
									$active_valor_moeda = '';
									if (!$active_set && (($moeda_codigo_selecionado && $moeda_codigo_selecionado === $moeda_codigo) || (!$moeda_codigo_selecionado && $k === 0))) {
										$active_valor_moeda = 'active';
										$active_set = true;
									}

									$html_valor_moeda .= "<div class='col-4'>";
									$html_valor_moeda .= "<div class='container-vm {$active_valor_moeda}' data-codigo='{$moeda_codigo}' data-simbolo='{$moeda_simbolo}' data-valor='{$valor}' data-valor-formatado='{$valor_formatado}'>";
									$html_valor_moeda .= "<div class='container-icone-valor-moeda'>";
									$html_valor_moeda .= "<i></i>";
									$html_valor_moeda .= "</div>";
                                    $html_valor_moeda .= "<div class='container-valor-moeda'>";
                                    $html_valor_moeda .= "<div>";
                                    $html_valor_moeda .= "<span>{$moeda_simbolo}&nbsp;</span>";
                                    $html_valor_moeda .= "<strong>{$valor_formatado}</strong>";
									if ($flag_url) {
										$html_valor_moeda .= "<img src='{$flag_url}' alt='{$moeda_nome}' class='flag-icon ms-1'>";
									}
									$html_valor_moeda .= "</div>";
									$html_valor_moeda .= "<div>";
									$html_valor_moeda .= "<span>{$moeda_nome}</span>";
									if ($obs) {
										$html_valor_moeda .= "<span>{$obs}</span>";
									}
									$html_valor_moeda .= "</div>";
									$html_valor_moeda .= "</div>";
									$html_valor_moeda .= "</div>";
									$html_valor_moeda .= "</div>";
								  }
								  echo $html_valor_moeda;
								  ?>

                            </div>
                          </div>

                    <hr>

                    <script type="text/javascript">
                        let interval_verifica_pagamento = null;
                        let codigo_forma_pag_selecionada_start;

                        function stopPaymentCheck() {
                            if (interval_verifica_pagamento) {
                                clearInterval(interval_verifica_pagamento);
                                interval_verifica_pagamento = null;
                            }
                        }

                        $.when(
                            $.each($('#container-valores-moedas .container-vm'), function (i, v) {
                                if ($(v).hasClass('active')) {
                                    codigo_forma_pag_selecionada_start = $(v).data('codigo');
                                    let simbolo = $(this).data('simbolo');
                                    let valorFormatado = $(this).data('valor-formatado') || $(this).data('valorFormatado') || $(this).data('valor');
                                    $('#total-resta-pagar, #total-mostrar-moeda').text(`${simbolo} ${valorFormatado}`);
                                    return false;
                                }
                            })
                        ).done(() => {
                            let interval_check_formas_pagamento_show = setInterval(() => {
                                $.when(
                                    $.each($('.container-formas-pagamento').find('.payment-option'), function (i, v) {
                                        if ($(v).hasClass(codigo_forma_pag_selecionada_start)) {
                                            $(v).addClass('show');
                                        } else {
                                            $(v).removeClass('show');
                                        }
                                    })
                                ).done(() => {
                                    clearInterval(interval_check_formas_pagamento_show);
                                });
                            }, 300);
                        });

                        $('#container-valores-moedas .container-vm').click(function (e) {
                            e.preventDefault();

                            stopPaymentCheck();
                            let codigo = $(this).data('codigo');
                            let simbolo = $(this).data('simbolo');
                            let valorFormatado = $(this).data('valor-formatado') || $(this).data('valorFormatado') || $(this).data('valor');

                            $.when(
                                $.each($(this).parent().parent().find('.container-vm'), function (i, v) {
                                    if ($(v).hasClass('active')) {
                                        $(v).removeClass('active');
                                    }
                                })
                            ).done(() => {
                                $(this).addClass('active');
                                $('#total-resta-pagar, #total-mostrar-moeda').text(`${simbolo} ${valorFormatado}`);
                                $.when(
                                    $.each($('.container-formas-pagamento').find('.payment-option'), function (i, v) {
                                        if ($(v).hasClass(codigo)) {
                                            $(v).addClass('show');
                                        } else {
                                            $(v).removeClass('show');
                                        }
                                    })
                                ).done(() => {
                                    // remove e recria jdk paypal
                                    let sdk_paypal = '';
                                    // let sdk_mercado_pago = '';
                                    $('#sdk-paypal').empty();
                                    // $('#sdk-mercado-pago').empty();
                                    switch (codigo) {
                                        case 'BRL':
                                            // Crie um novo elemento script paypal
                                            sdk_paypal = document.createElement('script');
                                            sdk_paypal.src = `https://www.paypal.com/sdk/js?client-id=AcfLam9LvePwGz5ICPiLrSw-s3gdr5BVbq-YpwoYGQwTKOuu8Ai8llIdY5LAl0jnUULO85QkJ4rVGqcZ&currency=${codigo}`;
                                            sdk_paypal.type = 'text/javascript';
                                            // Crie um novo elemento script mercado pago
                                            // sdk_mercado_pago = document.createElement('script');
                                            // sdk_mercado_pago.src = `https://sdk.mercadopago.com/js/v2`;
                                            // sdk_mercado_pago.type = 'text/javascript';
                                            break;
                                        case 'EUR':
                                            // Crie um novo elemento script paypal
                                            sdk_paypal = document.createElement('script');
                                            sdk_paypal.src = `https://www.paypal.com/sdk/js?client-id=AcfLam9LvePwGz5ICPiLrSw-s3gdr5BVbq-YpwoYGQwTKOuu8Ai8llIdY5LAl0jnUULO85QkJ4rVGqcZ&currency=${codigo}`;
                                            sdk_paypal.type = 'text/javascript';
                                            break;
                                        case 'ARS':
                                            // Crie um novo elemento script mercado pago
                                            codigo = 'USD'
                                            sdk_paypal = document.createElement('script');
                                            sdk_paypal.src = `https://www.paypal.com/sdk/js?client-id=AcekW2Qsin1cKBHzO3NPRBZ5VlqRayrk6O3w6vEf4pb-cVRncCgY-cJXYLWqn11MMKxXpmRZrvS8jisW&currency=${codigo}`;
                                            sdk_paypal.type = 'text/javascript';

                                            // Crie um novo elemento script mercado pago
                                            // sdk_mercado_pago = document.createElement('script');
                                            // sdk_mercado_pago.src = `https://sdk.mercadopago.com/js/v2`;
                                            // sdk_mercado_pago.type = 'text/javascript';
                                            break;
                                        default:
                                            // Crie um novo elemento script paypal
                                            sdk_paypal = document.createElement('script');
                                            sdk_paypal.src = `https://www.paypal.com/sdk/js?client-id=AcekW2Qsin1cKBHzO3NPRBZ5VlqRayrk6O3w6vEf4pb-cVRncCgY-cJXYLWqn11MMKxXpmRZrvS8jisW&currency=${codigo}`;
                                            sdk_paypal.type = 'text/javascript';
                                            break;
                                    }
                                    // Adicione o script ao DOM
                                    $('#sdk-paypal').html(sdk_paypal);
                                    // $('#sdk-mercado-pago').html(sdk_mercado_pago);


                                    if (codigo == 'BRL') {
                                        // Remova o script antigo

                                    }
                                    $.each($('.container-formas-pagamento').find('.form-real').find('.custom-radio'), function (i, v) {
                                        $(v).removeClass('active');
                                    });
                                });

                            });

                        });

                        function selectFormaPagamento(el) {

                            $.when(
                                $.each($(el).parent().find('.custom-radio'), function (i, v) {
                                    $(v).removeClass('active');
                                })
                            ).done(() => {
                                $(el).addClass('active');
                            });

                        }


                        const currencyOptions = document.querySelectorAll('input[name="currency"]');
                        currencyOptions.forEach(option => {
                            option.addEventListener('change', function () {
                                if (this.checked) {
                                    const parentLabel = this.closest('label');
                                    const priceElement = parentLabel.querySelector('.price');
                                    const selectedPrice = priceElement.textContent.trim();
                                    console.log("Moeda selecionada:", this.value);
                                    console.log("Valor da moeda selecionada:", selectedPrice);

                                    document.getElementById('total-resta-pagar').innerHTML = selectedPrice;
                                    document.getElementById('total-mostrar-moeda').innerHTML = `Total ${selectedPrice}`;

                                    let [, codigoMoeda, valorNumerico] = selectedPrice.match(/^(\D+)\s*([\d.]+)$/);
                                    if (codigoMoeda === 'AR$') updateCountryCode('AR');
                                    else if (codigoMoeda === 'R$') updateCountryCode('BR');
                                    else if (codigoMoeda === 'G$') updateCountryCode('PY');
                                    else if (codigoMoeda === 'CH$') updateCountryCode('CL');
                                    else if (codigoMoeda === 'US$') updateCountryCode('USE');
                                }
                            });
                        });

                        function updateCountryCode(countryCode) {
                            let reserva = document.getElementById('inputCodigoReserva').value;
                            let url = `https://pontopraia.com.br/consultaReserva?reserva=${reserva}&countryCode=${countryCode}`;
                            console.log("Redirecionando para:", url);
                            window.location.href = url;
                        }

                        // Função para habilitar/desabilitar os métodos de pagamento
                        function togglePaymentMethods(selectedCurrency) {
                            // Seleciona todos os métodos de pagamento
                            const paymentMethods = document.querySelectorAll('.paymentMethod');

                            // Lógica para habilitar ou desabilitar os métodos
                            paymentMethods.forEach(method => {
                                const methodId = method.id;

                                // Se a moeda selecionada não é compatível com o método de pagamento, desabilite
                                if ((selectedCurrency === 'BRL' && methodId !== 'reales') ||
                                    (selectedCurrency === 'PEN' && methodId !== 'pesos') ||
                                    (selectedCurrency === 'CLP' && methodId !== 'pesos') ||
                                    (selectedCurrency === 'PYG' && methodId !== 'guaranies') ||
                                    (selectedCurrency === 'USD' && methodId !== 'dolar')) {
                                    method.classList.add('disabled');
                                } else {
                                    method.classList.remove('disabled');
                                }
                            });
                        }

                        // Detecta quando o radio button é alterado
                        document.querySelectorAll('input[name="currency"]').forEach(radio => {
                            radio.addEventListener('change', (event) => {
                                const selectedCurrency = event.target.value;
                                togglePaymentMethods(selectedCurrency); // Atualiza os métodos de pagamento com base na moeda selecionada
                            });
                        });
                    </script>

                    <!--<div style="width:100%;">-->
                    <!-- <label class="btn btn-primary paymentMethod texto-moneda" id="reales"> R$ <?= $reales; ?>-->
                    <!--</label>-->

                    <!--<label class="btn btn-primary paymentMethod texto-moneda" id="pesos"> € <?= $euro; ?>-->
                    <!--</label>-->

                    <!--<label class="btn btn-primary paymentMethod texto-moneda" id="pesos"> CL$ <?= $pesos_chilenos; ?>-->
                    <!--</label>-->

                    <!--<label class="btn btn-primary paymentMethod texto-moneda" id="pesos"> AR$ <?= convierteMoneda(188, 270, $diferenciaAPagar); ?>-->
                    <!--</label>-->

                    <!--<label class="btn btn-primary paymentMethod texto-moneda" id="guaranies"> Gs$ <?= $guaranies; ?>-->
                    <!--</label>-->

                    <!--<label class="btn btn-primary paymentMethod texto-moneda" id="dolar"> USD$ <?= $dolares; ?> -->
                    <!--</label>-->
                    <!--</div>-->
                    <style>
                        /* Style Payment Forms */

                        .form-real {
                            display: flex;
                            flex-direction: column;
                            gap: 40px;
                        }

                        .form-real label {
                            height: 50px;
                            padding: 10px 10px;
                        }

                        .content-cards {
                            display: grid;
                            gap: 10px;
                            align-items: center;
                            grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr 1fr;
                        }

                        .card-img {
                            width: 35px;
                        }

                        .secudary-options {
                            display: flex;
                            width: 100%;
                            align-items: center;
                            gap: 10px;
                        }

                        .payment-option {
                            display: none;
                        }

                        p {
                            margin-bottom: 0px;
                        }

                        .hidden {
                            display: none;
                        }
                    </style>
                    <!-- REAL OPTIONS PAYMENT -->

                    <div class="container container-formas-pagamento">
                        <div class="row g-3">

                            <div class='hr w-100 mb-2 mt-3' style="opacity: 0.3;"></div>
                            <div class="col-12 mx-auto">
                                <h4 style="text-align: center; font-weight: 200; opacity: 0.8;">Formas de Pagamento</h4>
                            </div>
                            <div class='hr w-100 mt-1 mb-3' style="opacity: 0.3;"></div>


                            <div id="real-optionscol-12 " class="payment-Real payment-option BRL">
                                <div class="form-real">

                                    <div class="custom-radio option" onclick="selectFormaPagamento(this);">
                                        <div class="container-card-pagamento-topo">
                                            <i></i>
                                            <!-- <input type="radio" name="currency"  /> -->
                                            <!-- <span class="checkmark"></span> -->
                                            <div class="secudary-options">
                                                <p>Mercado Pago</p>
                                                <img class="card-img" src="img/mercado-pago-logo.png" alt=""
                                                     style="width: 95px;">
                                            </div>
                                        </div>
                                        <div class="container-card-pagamento-body">
                                            <p>Finalize sua compra de forma segura com Mercado Pago. Clique no botão
                                                "Pagar" para concluir seu pagamento em reais de forma rápida e fácil com
                                                seus meios preferidos.</p>
                                            <button class="btn btn-dark abre_mercado_pago">Pagar</button>
                                        </div>
                                    </div>
                                    <div class="custom-radio option" onclick="selectFormaPagamento(this);">
                                        <div class="container-card-pagamento-topo">
                                            <i></i>
                                            <!-- <input type="radio" name="currency" onclick="displayOpenPixModal()" /> -->
                                            <!-- <span class="checkmark"></span> -->
                                            <div class="secudary-options">
                                                <p>Pix</p>
                                                <img class="card-img" src="img/pix.png" alt="" style="width: 85px;">
                                            </div>
                                        </div>
                                        <div class="container-card-pagamento-body">
                                            <p>Finalize sua compra de forma segura com Pix. Clique no botão para
                                                realizar seu pagamento de maneira rápida e prática.</p>
                                            <button class="btn btn-dark" onclick="displayOpenPixModal()">Pagar</button>
                                        </div>
                                    </div>
                                    <div class="custom-radio option" onclick="selectFormaPagamento(this);">
                                        <div class="container-card-pagamento-topo">
                                            <i></i>
                                            <!-- <input type="radio" name="currency" class="btn btn-primary paymentMethod" id="paypal-radio-br"  /> -->
                                            <!-- <span class="checkmark"></span> -->
                                            <div class="secudary-options">
                                                <p>PayPal</p>
                                                <img class="card-img" src="img/paypal-2.png" alt=""
                                                     style="width: 95px;">
                                            </div>
                                        </div>
                                        <div class="container-card-pagamento-body">
                                            <p>Finalize sua compra em reais de forma segura com PayPal. Clique no botão
                                                para concluir seu pagamento em apenas alguns passos.</p>
                                            <button class="btn btn-dark paymentMethod abre_paypal" id="paypal-radio-br">
                                                Pagar
                                            </button>
                                        </div>
                                    </div>

                                    <?php 
                                    // Definir variables necesarias antes de incluir ebanx
                                    $totalPayPal = isset($totalPayPal) ? $totalPayPal : 0;
                                    
                                    include("admin/pasarelas/ebanx/ebanx.php");

                                    if (isset($url_ebanx) && $url_ebanx == (-5)) { //alertar($lang["el_metodo_seleccionado_no_puede_cobrar"],$lang["error"]);
                                    }

                                    $url_ebanx_dolares = url_ebanx($codigoAmigable, 'USD', $countryEbanx, $totalPayPal); ?>

                                    <!-- <div class="btn btn-primary paymentMethod" id="ebanx"> -->

                                </div>
                            </div>

                            <!-- EURO OPTIONS PAYMENT -->
                            <div id="euro-options" class="col-12 payment-Real payment-option EUR">
                                <div class="form-real">

                                    <div class="custom-radio option" onclick="selectFormaPagamento(this);">
                                        <div class="container-card-pagamento-topo">
                                            <i></i>
                                            <!-- <input type="radio" name="currency" /> -->
                                            <!-- <span class="checkmark"></span> -->
                                            <div class="secudary-options">
                                                <p>PayPal</p>
                                                <img class="card-img" src="img/paypal-2.png" alt=""
                                                     style="width: 95px;">
                                            </div>
                                        </div>
                                        <div class="container-card-pagamento-body">
                                            <p>Finaliza tu compra en euros de forma segura con PayPal. Haz clic en el
                                                botón "Pagar" para completar tu pago en solo unos pasos.</p>
                                            <button class="btn btn-dark abre_paypal">Pagar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- DOLLAR OPTIONS PAYMENT -->
                            <div id="dollar-options" class="col-12 payment-Real payment-option USD">
                                <div class="form-real">

                                    <div class="custom-radio option" onclick="selectFormaPagamento(this);">
                                        <div class="container-card-pagamento-topo">
                                            <i></i>
                                            <!-- <input type="radio" name="currency" /> -->
                                            <!-- <span class="checkmark"></span> -->
                                            <div class="secudary-options">
                                                <p>PayPal</p>
                                                <img class="card-img" src="img/paypal-2.png" alt=""
                                                     style="width: 95px;">
                                            </div>
                                        </div>
                                        <div class="container-card-pagamento-body">
                                            <p>Finaliza tu compra en dolares de forma segura con PayPal. Haz clic en el
                                                botón "Pagar" para completar tu pago en solo unos pasos.</p>
                                            <button class="btn btn-dark abre_paypal">Pagar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- GUARANI OPTIONS PAYMENT -->
                            <div id="guarani-options" class="col-12 payment-Real payment-option PYG">
                                <div class="form-real">

                                    <div class="custom-radio option" onclick="selectFormaPagamento(this);">
                                        <div class="container-card-pagamento-topo">
                                            <i></i>
                                            <!-- <input type="radio" name="currency" /> -->
                                            <!-- <span class="checkmark"></span> -->
                                            <div class="secudary-options">
                                                <p>PayPal</p>
                                                <img class="card-img" src="img/paypal-2.png" alt=""
                                                     style="width: 95px;">
                                            </div>
                                        </div>
                                        <div class="container-card-pagamento-body">
                                            <p>Finaliza tu compra de forma segura con PayPal. Haz clic en el botón
                                                "Pagar" para completar tu pago en solo unos pasos.</p>
                                            <button class="btn btn-dark abre_paypal">Pagar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- PESO ARGENTINO OPTIONS PAYMENT -->
                            <div id="peso-argentino-options" class="col-12 payment-Real payment-option ARS">
                                <div class="form-real">

                                    <div class="custom-radio option" onclick="selectFormaPagamento(this);">
                                        <div class="container-card-pagamento-topo">
                                            <i></i>
                                            <!-- <input type="radio" name="currency"  /> -->
                                            <!-- <span class="checkmark"></span> -->
                                            <div class="secudary-options">
                                                <p>Mercado Pago</p>
                                                <img class="card-img" src="img/mercado-pago-logo.png" alt=""
                                                     style="width: 75px;">
                                            </div>
                                        </div>
                                        <div class="container-card-pagamento-body">
                                            <p>Finaliza tu compra en pesos argentinos de manera rápida y segura con
                                                Mercado Pago. Haz clic en el botón "Pagar" para completar tu pago con
                                                tus métodos preferidos.</p>
                                            <button class="btn btn-dark abre_mercado_pago">Pagar</button>
                                        </div>
                                    </div>

                                    <div class="custom-radio option" onclick="selectFormaPagamento(this);">
                                        <div class="container-card-pagamento-topo">
                                            <i></i>
                                            <!-- <input type="radio" name="currency" id="paypal-radio-argentino" /> -->
                                            <!-- <span class="checkmark"></span> -->
                                            <div class="secudary-options">
                                                <p>PayPal</p>
                                                <img class="card-img" src="img/paypal-2.png" alt=""
                                                     style="width: 85px;">
                                            </div>
                                        </div>
                                        <div class="container-card-pagamento-body">
                                            <p>Finaliza tu compra de forma segura con PayPal. Haz clic en el botón
                                                "Pagar" para completar tu pago en solo unos pasos.</p>
                                            <button class="btn btn-dark abre_paypal">Pagar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- PESO CHILENO OPTIONS PAYMENT -->
                            <div id="peso-chileno-options" class="col-12 payment-Real payment-option CLP">
                                <div class="form-real">

                                    <div class="custom-radio option" onclick="selectFormaPagamento(this);">
                                        <div class="container-card-pagamento-topo">
                                            <i></i>
                                            <!-- <input type="radio" name="currency" /> -->
                                            <!-- <span class="checkmark"></span> -->
                                            <div class="secudary-options">
                                                <p>PayPal</p>
                                                <img class="card-img" src="img/paypal-2.png" alt=""
                                                     style="width: 85px;">
                                            </div>
                                        </div>
                                        <div class="container-card-pagamento-body">
                                            <p>Finaliza tu compra de forma segura con PayPal. Haz clic en el botón
                                                "Pagar" para completar tu pago en solo unos pasos.</p>
                                            <button class="btn btn-dark abre_paypal">Pagar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <script>
                        // Seleciona o input específico e a div a ser exibida
                        const paypalarge = document.getElementById('paypal-radio-argentino');
                        const paypalRadio = document.getElementById('paypal-radio-br');
                        const paypalButtonContainer = document.getElementById('paypal-button-container');

                        // // Evento 'change' para verificar quando o input muda
                        // document.querySelectorAll('input[name="currency"]').forEach(radio => {
                        //     radio.addEventListener('change', function() {
                        //         if (paypalRadio.checked || paypalarge.checked) {
                        //             paypalButtonContainer.classList.remove('hidden'); // Exibe se selecionado
                        //         } else {
                        //             paypalButtonContainer.classList.add('hidden'); // Oculta se desmarcado
                        //         }
                        //     });
                        // });
                    </script>


                    <?php
                    $total = 0; //0ConvierteMoneda($monedaNativa,270, $totalAPagar);
                    $totalMercadopagoArgentina = convierteMoneda(188, 270, $diferenciaAPagar); // Pesos Argentino
                    // Usar el saldo pendiente (diferencia a pagar) convertido a BRL para MP Brasil
                    $totalMercadopagoBrasil = convierteMoneda(188, 283, $diferenciaAPagar);
                    $totalReales = convierteMoneda(188, 283, $diferenciaAPagar); // Real Brasil
                    $totalPayPal = convierteMoneda($_SESSION['moneda_sel'], 188, (isset($reserva["total"]) ? $reserva["total"] : 0)) - $comprobantes; // Dolar

                    include("./admin/pasarelas/mercadopagoArgentina/procesaPago.php");
                    include("./admin/pasarelas/mercadopagoBrasil/procesaPago.php");
                    //include("../query.php");

                    ?>
                    <div id="sdk-paypal">
                        <script src="https://www.paypal.com/sdk/js?client-id=AcfLam9LvePwGz5ICPiLrSw-s3gdr5BVbq-YpwoYGQwTKOuu8Ai8llIdY5LAl0jnUULO85QkJ4rVGqcZ&currency=BRL"></script>
                    </div>
                    <div id="sdk-mercado-pago">
                        <script src="https://sdk.mercadopago.com/js/v2"></script>
                    </div>


                    <script>
                        function loadMercadoPagoSDK(publicKey, locale, callback) {
                            $('script[data-mp-sdk]').remove();
                            const script = document.createElement('script');
                            script.src = "https://sdk.mercadopago.com/js/v2";
                            script.async = true;
                            script.dataset.mpSdk = 'true';
                            script.onload = function () {
                                const mp = new MercadoPago(publicKey, {locale: locale});
                                callback(mp);
                            };
                            document.head.appendChild(script);
                        }


                        $(document).ready(function () {
                            const myOffcanvasPaypal = document.getElementById('offcanvasWithBothOptions')
                            myOffcanvasPaypal.addEventListener('shown.bs.offcanvas', event => {
                                $('#paypal-button-container').removeClass('hidden');
                            });
                            myOffcanvasPaypal.addEventListener('hidden.bs.offcanvas', event => {
                                $('#paypal-button-container').empty();
                            });
                        });

                        $('.abre_mercado_pago').click(function (e) {
                            e.preventDefault();
                            stopPaymentCheck();
                            $('.mercadopago-checkout-frame').remove();
                            let amount_value = '';
                            let amount_currency_code = '';
                            $.each($('#container-valores-moedas .container-vm'), function (i, v) {
                                if ($(v).hasClass('active')) {
                                    amount_value = $(v).data('valor');
                                    amount_currency_code = $(v).data('codigo');
                                    return false;
                                }
                            });
                            var idReserva = '<?= $idReserva ?>';
                            var codigoAmigable = '<?= $codigoAmigable ?>';
                            const url_atual = window.location.href;
                            $.ajax({
                                type: "POST",
                                url: "criar_preferencia_mercado_pago.php",
                                data: {
                                    title: '<?= $servicio[0]["nombre_servicio"]; ?>',
                                    quantity: 1,
                                    currency_id: amount_currency_code,
                                    unit_price: amount_value,
                                    reserva: codigoAmigable,
                                    url_atual: url_atual,
                                },
                                dataType: "json",
                                success: function (r) {
                                    if (!r.status) return;

                                    let Public_Key = '';
                                    let locale = 'es-AR';

                                    if (amount_currency_code === 'BRL') {
                                        Public_Key = '<?php echo MercadoPagoConfig::getCredentials('BR')['public_key']; ?>';
                                        locale = 'pt-BR';
                                    } else if (amount_currency_code === 'ARS') {
                                        Public_Key = '<?php echo MercadoPagoConfig::getCredentials('AR')['public_key']; ?>'  // Test Argentina
                                        locale = 'es-AR';
                                    }

                                    loadMercadoPagoSDK(Public_Key, locale, function (mp) {
                                        mp.checkout({
                                            preference: {id: r.retorno.id},
                                            autoOpen: true,
                                            iframe: true,
                                        });

                                        let tentativas = 0;
                                        stopPaymentCheck(); // seguridad extra
                                        interval_verifica_pagamento = setInterval(() => {
                                            $.ajax({
                                                type: "POST",
                                                url: "criar_preferencia_mercado_pago.php",
                                                data: {
                                                    verifica_pagamento: 1,
                                                    currency_id: amount_currency_code,
                                                    reservaCode: '<?= $codigoAmigable ?>',
                                                },
                                                dataType: "json",
                                                success: function (resp) {
                                                    if (resp.status) {
                                                        stopPaymentCheck();
                                                        $.ajax({
                                                            type: "POST",
                                                            url: "consultaReserva.php",
                                                            data: {
                                                                ajax_pagamento: 1,
                                                                codigo_amigavel: codigoAmigable,
                                                                amount_currency_code: amount_currency_code,
                                                                gateway_pagamento: 'MercadoPago',
                                                                total: amount_value,
                                                                total_dolares: <?= $total_dolares; ?>,
                                                                id_transacao: 'MP-' + r.retorno.id
                                                            },
                                                            dataType: "json",
                                                            success: function () {
                                                                window.location.reload();
                                                            }
                                                        });
                                                    } else if (++tentativas > 60) {
                                                        stopPaymentCheck();
                                                    }
                                                },
                                                error: function () {
                                                    stopPaymentCheck();
                                                }
                                            });
                                        }, 5000);
                                    });
                                }
                            });
                        });


                        $('.abre_paypal').click(function (e) {
                            e.preventDefault();

                            let amount_value = '';
                            let amount_currency_code = '';

                            $.when(
                                $.each($('#container-valores-moedas').find('.container-vm'), function (i, v) {
                                    // console.log(v);

                                    if ($(v).hasClass('active')) {
                                        amount_value = $(v).data('valor');
                                        amount_currency_code = $(v).data('codigo');

                                        if (amount_currency_code == 'ARS' || amount_currency_code == 'PYG' || amount_currency_code == 'CLP') {
                                            $.each($('#container-valores-moedas').find('.container-vm'), function (i, v) {

                                                if ($(v).data('codigo') == 'USD') {
                                                    // console.log($(v).data('valor'));
                                                    // console.log($(v).data('codigo'));

                                                    amount_value = $(v).data('valor');
                                                    amount_currency_code = $(v).data('codigo');
                                                    return false;
                                                }
                                            });
                                        }

                                        return false;
                                    }
                                })
                            ).done(() => {

                                var idReserva = '<?= $idReserva ?>';
                                var codigoAmigable = '<?= $codigoAmigable ?>';


                                // $("#textoMetodoDePago").html("Divisa");

                                $(document).ready(function () {

                                    $.when(
                                        paypal.Buttons({
                                            createOrder: function (data, actions) {
                                                // This function sets up the details of the transaction, including the amount and line item details.
                                                return actions.order.create({
                                                    purchase_units: [{
                                                        "reference_id": idReserva,
                                                        "custom_id": codigoAmigable,
                                                        amount: {
                                                            value: amount_value,
                                                            currency_code: amount_currency_code
                                                        },
                                                        description: "Reserva en meteleargentina.com",
                                                        notify_url: "https://meteleargentina.com/admin/pasarelas/PayPal/notificaciones.php"

                                                    }]
                                                });
                                            },
                                            onApprove: function (data, actions) {
                                                // This function captures the funds from the transaction.
                                                return actions.order.capture().then(function (details) {
                                                    // This function shows a transaction success message to your buyer.
                                                    // console.log('status pagamento', details);

                                                    if (details.status == 'COMPLETED') {
                                                        $.ajax({
                                                            type: "POST",
                                                            url: "consultaReserva.php",
                                                            data: {
                                                                ajax_pagamento: 1,
                                                                codigo_amigavel: codigoAmigable,
                                                                amount_currency_code: amount_currency_code,
                                                                gateway_pagamento: 'Paypal',
                                                                total: amount_value,
                                                                total_dolares: <?= $total_dolares; ?>,
                                                                id_transacao: details.id
                                                            },
                                                            dataType: "json",
                                                            success: function (r) {
                                                                console.log(r);
                                                                alert('<?php echo isset($lang["gracias_por_confiar_en_metele_brasil"]) ? $lang["gracias_por_confiar_en_metele_brasil"] : "Gracias por confiar en metelebrasil"; ?>' + details.payer.name.given_name + '<?php echo isset($lang["el_pago_de_paypal"]) ? $lang["el_pago_de_paypal"] : " el pago de PayPal fue exitoso"; ?>');
                                                                window.location.reload();

                                                            },
                                                            error: function (e) {
                                                                console.log(e.responseText);

                                                            }
                                                        });
                                                    } else {
                                                        console.log('status pagamento', details);
                                                    }


                                                });
                                            },
                                            onCancel: function (data) {
                                                // Quando o comprador fecha o modal ou cancela o pagamento
                                                console.log('Pagamento cancelado pelo usuário.');
                                                console.log('Detalhes do cancelamento:', data);
                                            },
                                            onError: function (err) {
                                                // Quando ocorre um erro durante o processo de pagamento
                                                console.log('Erro durante o pagamento. Por favor, tente novamente.');
                                            }
                                        }).render('#paypal-button-container')
                                    ).done(() => {

                                        const bsOffcanvasPaypal = new bootstrap.Offcanvas('#offcanvasWithBothOptions');
                                        bsOffcanvasPaypal.show();

                                    });

                                });


                            });

                        });

                        //This function displays Smart Payment Buttons on your web page.
                    </script>
                    <?php
                    $totalOpenpixRs = sprintf('%.2f', $totalReales); // pega o valor em reais
                    $totalOpenpixRs = str_replace(".", "", $totalOpenpixRs);
                    ?>
                    <script src="https://plugin.openpix.com.br/v1/openpix.js" async></script>
                    <script>
                        totalOpenPix = parseInt('<?= $totalOpenpixRs; ?>')
                        codigoAmigable = '<?= $codigoAmigable; ?>'

                        function displayOpenPixModal() {

                            let amount_value = '';
                            let amount_currency_code = '';

                            $.when(
                                $.each($('#container-valores-moedas').find('.container-vm'), function (i, v) {
                                    // console.log(v);

                                    if ($(v).hasClass('active')) {
                                        amount_value = $(v).data('valor');
                                        amount_currency_code = $(v).data('codigo');

                                        if (amount_currency_code == 'ARS') {
                                            $.each($('#container-valores-moedas').find('.container-vm'), function (i, v) {

                                                if ($(v).data('codigo') == 'USD') {
                                                    // console.log($(v).data('valor'));
                                                    // console.log($(v).data('codigo'));

                                                    amount_value = $(v).data('valor');
                                                    amount_currency_code = $(v).data('codigo');
                                                    return false;
                                                }
                                            });
                                        }

                                        return false;
                                    }
                                })
                            ).done(() => {

                                // Configuração inicial do OpenPix
                                window.$openpix = window.$openpix || [];

                                // Configuração do appID
                                window.$openpix.push(['config', {
                                    appID: 'Q2xpZW50X0lkXzliNTY2N2Q2LTk2MzAtNGIzMi1hZWIzLTc4YTY2ZWQxZWVkMzpDbGllbnRfU2VjcmV0X0l1V1NoSGFIeEV2c2hnRXN4blFHQVUyaExOcms2ZytPSloyYlduOVh5NXc9'
                                }]);

                                // Variável global para cancelar eventos
                                let unsubscribe = null;

                                // Função para iniciar um novo PIX
                                function iniciarPagamento(valor, correlationID) {
                                    // Certifique-se de encerrar o listener anterior, se existir
                                    if (unsubscribe) {
                                        unsubscribe();
                                        console.log("Listener anterior encerrado.");
                                    }

                                    // Criar nova cobrança PIX
                                    window.$openpix.push(
                                        ['pix',
                                            {
                                                value: valor, // Valor do PIX
                                                correlationID: correlationID, // Identificador único para rastrear a transação
                                            },
                                        ]);

                                    // Função para capturar eventos
                                    const logEvents = (e) => {
                                        if (e.type === 'CHARGE_COMPLETED') {
                                            console.log('Transação concluída:', e);

                                            //window.location.reload();

                                            // console.log('status pagamento', details);


                                            $.ajax({
                                                type: "POST",
                                                url: "consultaReserva.php",
                                                data: {
                                                    ajax_pagamento: 1,
                                                    codigo_amigavel: codigoAmigable,
                                                    amount_currency_code: amount_currency_code,
                                                    gateway_pagamento: 'OpenPix',
                                                    total: amount_value,
                                                    total_dolares: <?= $total_dolares; ?>,
                                                    id_transacao: 'pix-' + Date.now()
                                                },
                                                dataType: "json",
                                                success: function (r) {
                                                    console.log(r);
                                                    alert('Pagamento Confirmado');
                                                    window.location.reload();

                                                },
                                                error: function (e) {
                                                    console.log(e.responseText);

                                                }
                                            });


                                            // Encerra o listener após conclusão
                                            if (unsubscribe) {
                                                unsubscribe();
                                                console.log("Listener encerrado após conclusão.");
                                            }
                                        }

                                        if (e.type === 'CHARGE_EXPIRED') {
                                            console.log('Cobrança expirada:', e);
                                            alert('A cobrança expirou. Por favor, tente novamente.');
                                        }

                                        if (e.type === 'ON_CLOSE') {
                                            console.log('Modal fechado pelo usuário.');
                                        }
                                    };

                                    // Registrar o listener de eventos e salvar a função para cancelamento
                                    if (!!window.$openpix?.addEventListener) {
                                        unsubscribe = window.$openpix.addEventListener(logEvents);
                                        console.log("Novo listener registrado.");
                                    }
                                }


                                const correlationID = 'novaTransacao' + Date.now(); // Exemplo de ID único
                                iniciarPagamento(totalOpenPix, correlationID);


                            });

                        }
                    </script>
                    <!--fin openpix-->
                    <?php if (0) { //$habilita_pesos_arg
                        ?>
                        <!-- <label class="btn btn-primary paymentMethod" style="">
                                <a href="<?= $preference->init_point; ?>">
                                    <div class="method paypal">
                                        <div class="method mercadopagoBrasil">
                                        </div>
                                    </div>
                            </label> -->
                    <?php } ?>
                    <?php if ($habilita_reales) { ?>
                        <!-- <label class="btn btn-primary paymentMethod" id="mercadopagoBrasil" style=""> Finalizar
                                <a href="<?= $preferenceBr->init_point; ?>">
                                    <div class="method paypal">
                                        <div class="method mercadopagoBrasil">
                                        </div>
                                    </div>
                                </a> -->
                        </label>
                    <?php } ?>

                    <?php
                    if (0) { //$habilita_ebanx $_SESSION['geo']['countryCode']!='BR' && $url_ebanx!=(-5)
                        include("admin/pasarelas/ebanx/ebanx.php");

                        $url_ebanx = url_ebanx($codigoAmigable, $currencyEbanx, $countryEbanx, $totalEbanx);

                        if ($url_ebanx == (-5)) {

                            //alertar($lang["el_metodo_seleccionado_no_puede_cobrar"],$lang["error"]);
                        }

                        $url_ebanx_dolares = url_ebanx($codigoAmigable, 'USD', $countryEbanx, $totalPayPal);
                        ?>
                        <label class="btn btn-primary paymentMethod" id="ebanx" style="display:none">
                            <a href="<?= $url_ebanx; ?>">
                                <div class="method paypal"></div>
                                <div class="method ebanxs"></div>
                            </a>
                        </label>
                    <?php } ?>
                    <!-- <label class="btn btn-primary paymentMethod" id="ebanxDolares" style="display:none">
                            <a href="<?= $url_ebanx_dolares; ?>">
                                <div class="method paypal"> </div>
                                <div class="method ebanxs"></div>
                            </a>
                        </label> -->
                    <!--FIN AQUI VA LA CARGA DE METODOS DE PAGO-->
                </div>
            </div>
        </div>
        
                                    <!-- SECCIÓN DE BANDERAS DE TARJETAS DE CREDITO CREDIT CARD -->
<div class="payment-secure-section text-center mt-4 mb-3">
 
  <div class="payment-carousel">
    <div class="carousel-track d-flex align-items-center justify-content-center">
      <?php for($i=0; $i<2; $i++): ?>
        <img src="img/mastercard.png" alt="Mastercard" class="mx-3 payment-logo">
        <img src="https://upload.wikimedia.org/wikipedia/commons/3/30/American_Express_logo.svg" alt="American Express" class="mx-3 payment-logo">
        <img src="img/paypal-2.png" alt="PayPal" class="mx-3 payment-logo">
        <img src="img/mercado-pago-logo.png" alt="Mercado Pago" class="mx-3 payment-logo">
        <img src="img/pix.png" alt="Pix" class="mx-3 payment-logo">
        <img src="https://upload.wikimedia.org/wikipedia/commons/3/31/Logo_Banco_Galicia.svg" alt="Banco Galicia" class="mx-3 payment-logo">
        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/c/c5/Bitcoin_logo.svg/614px-Bitcoin_logo.svg.png" alt="Banco Galicia" class="mx-3 payment-logo">
        <img src="https://upload.wikimedia.org/wikipedia/commons/b/b8/Banco_Santander_Logotipo.svg" alt="Banco Santander" class="mx-3 payment-logo">
        <img src="img/Belo.png" alt="Belo" class="mx-3 payment-logo">
        <img src="img/pago-facil.png" alt="Pago Facil" class="mx-3 payment-logo">
        <img src="img/rapi-pago.png" alt="rapiPago" class="mx-3 payment-logo">
      <?php endfor; ?>
    </div>
  </div>
</div>

<style>
  /* Fondo premium con sombra */
  .payment-secure-section {
    background: #f9f9f9;
    border: 1px solid #e0e0e0;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    padding: 20px 10px 15px;
    transition: box-shadow 0.3s ease;
  }

  .payment-secure-section:hover {
    box-shadow: 0 6px 14px rgba(0,0,0,0.08);
  }

  /* Sello de confianza */
  .trust-badge {
    background: linear-gradient(90deg, #4CAF50, #2E7D32);
    color: #fff;
    font-weight: 600;
    font-size: 1rem;
    padding: 6px 14px;
    border-radius: 50px;
    box-shadow: 0 2px 6px rgba(76,175,80,0.4);
    letter-spacing: 0.5px;
  }

  .trust-badge i {
    font-size: 1.2rem;
  }

  /* Carrusel */
  .payment-carousel {
    overflow: hidden;
    position: relative;
    width: 100%;
    margin-top: 10px;
  }

  .carousel-track {
    display: inline-flex;
    white-space: nowrap;
    animation: scroll-left 25s linear infinite;
  }

  @keyframes scroll-left {
    0% { transform: translateX(0); }
    100% { transform: translateX(-50%); }
  }

  /* Logos */
  .payment-logo {
    height: 46px;
    width: auto;
    opacity: 0.9;
    filter: grayscale(100%);
    transition: all 0.3s ease;
  }

  .payment-logo:hover {
    opacity: 1;
    filter: grayscale(0%);
    transform: scale(1.08);
  }

  /* Responsivo */
  @media (max-width: 768px) {
    .payment-logo {
      height: 34px;
      margin: 0 6px;
    }
  }

  /* Pausa animación al pasar el mouse */
  .payment-carousel:hover .carousel-track {
    animation-play-state: paused;
  }
</style>

        
        </form>
        <!--FIN FORM DE PAGO-->

         <?php if (isset($_SESSION["login"]["idCobrador"]) && $_SESSION["login"]["idCobrador"] > 0) { ?>
        <!--COBRO EN MANO-->
        <div class="col-md-12 py-3">
            <div class="card card-visitas">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-hand-holding-usd fa-3x text-success mb-3"></i>
                        <h5 class="card-title text-success font-weight-bold" id="textoMetodoDePago">
                            <?= $lang["cobro_en_mano"] ?>
                        </h5>
                        <p class="text-muted small">Opción disponible para cobradores autorizados</p>
                    </div>

                    <a href="admin/cobroSignal?reserva=<?= $codigoAmigable ?>" class="btn btn-success btn-lg btn-radius" style="width: 100% !important;">
                        <i class="fas fa-check-circle mr-2"></i>
                        Confirmar Cobro Manual
                    </a>

                    <p class="text-muted mt-2 mb-0">
                        <small>Procesar pago en efectivo o método alternativo</small>
                    </p>
                </div>
            </div>
        </div>
    <?php } ?>

    </div>
    </div>

    <!--FIN METODOS DE PAGO-->
    <?php }
    if ($comprobantes > 0) { // ($comprobantes<$total_dolares) {

        ?>

        <!--METODOS DE PAGO-->
        <div class="col-md-6 py-3">
            <div class="card card-visitas shadow-lg border-0" id="cardVisitas">
                <div class="card-body text-center py-5">
                    <!-- Icono de éxito -->
                    <div class="mb-4">
                        <div class="rounded-circle bg-success d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="fas fa-check text-white" style="font-size: 40px;"></i>
                        </div>
                    </div>
                    
                    <!-- Título -->
                    <h2 class="text-success font-weight-bold mb-2"><?= $lang["Felicidades"] ?? "¡Felicitações!" ?></h2>
                    <h4 class="text-dark mb-4">Reserva Confirmada</h4>
                    
                    <!-- Código de reserva -->
                    <div class="alert alert-info mb-4">
                        <p class="mb-1"><small class="text-muted">Código de reserva</small></p>
                        <h3 class="mb-0 font-weight-bold"><?= $codigoAmigable; ?></h3>
                    </div>
                    
                    <!-- Mensaje informativo -->
                    <p class="text-muted mb-4">
                        <i class="fas fa-info-circle mr-1"></i>
                        Enviamos todos los detalles a tu correo electrónico
                    </p>
                    
                    <!-- Botones de acción -->
                    <form method="post" action="voucherCarrito" class="mb-3">
                        <button type="submit" name="codigoAmigable" value="<?= $codigoAmigable; ?>"
                                class="btn btn-lg btn-block btn-radius shadow-sm"
                                style="background-color: rgb(2, 156, 226); border-color: rgb(2, 156, 226); color: white;">
                            <i class="fas fa-file-alt mr-2"></i><?= $lang["detalles_reserva"] ?? "Detalles de la Reserva"; ?>
                        </button>
                    </form>
                    
                    <a href="index.php" class="btn btn-outline-primary btn-lg btn-block btn-radius">
                        <i class="fas fa-home mr-2"></i><?= $lang["volver_al_site"] ?? "Volver al sitio"; ?>
                    </a>
                </div>
            </div>
        </div>

    <?php } ?>
    <!--FIN DATOS DE PAGO-->
    </div>
    </div>
    <!--BOTON SIGUIENTE-->
    <?php
    if ($comprobantes < $total_dolares) {
        ?>
        <!-- <div class="container py-4">
            <div class="row">
                <div class="col-lg-8 col-md-8"></div>
                <div class="col-lg-4 col-md-4 col-12 text-right">
                </div>
            </div>
        </div> -->
        <?php
    }
    // Ensure $lang variables are defined, if not, provide a fallback or define them before use
    // This is a common issue with `isset($lang['key']) ? $lang['key'] : 'Fallback text'`
    if (!isset($lang["gracias_por_confiar_en_metele_brasil"])) {
        $lang["gracias_por_confiar_en_metele_brasil"] = "Gracias por confiar en metelebrasil";
    }
    if (!isset($lang["el_pago_de_paypal"])) {
        $lang["el_pago_de_paypal"] = "el pago de PayPal fue exitoso";
    }

    ?>
    <!--FIN BOTON SIGUIENTE-->
</section>
<!--FIN SECCION DATOS PERSONALES-->
<!-- Footer -->

<!-- Copyright Section -->
<section class="copyright py-4 text-center text-white">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="text-left">
                    <small><span>METELE BRASIL</span><?= $lang["es_una_marca_registrada_de_reservate_sl"] ?></small>
                </h4>
            </div>
        </div>
    </div>
</section>
<!-- BOTON SUBIR-->
<div class="scroll-to-top  position-fixed ">
    <a class="js-scroll-trigger d-block text-center text-white rounded" href="#page-top">
        <i class="fa fa-chevron-up"></i>
    </a>
</div>
<!-- FIN BOTON SUBIR-->
<!-- SCRIPTS NECESARIOS-->
<script type="text/javascript">
    //por default arrancamos en reales
    $("#reales").css('background', ' #029ce2'); //pinta
    $("#reales").css('color', ' #fff '); //pinta
    $("#paypal").css('display', 'none');
    $("#mercadopago").css('display', 'none');
    $("#mercadopagoBrasil").css('display', 'block');
    $("#ebanx").css('display', 'block');
    $("#reales").click(function () {
        $("#paypal").css('display', 'none');
        $("#mercadopago").css('display', 'none');
        $("#mercadopagoBrasil").css('display', 'block');
        $("#ebanx").css('display', 'none');
        $("#reales").css('background', ' #029ce2'); //pinta
        $("#reales").css('color', ' #fff '); //pinta
        $("#pesos").css('background', ' #fff'); //despinta
        $("#pesos").css('color', ' #929292 '); //despinta
        $("#dolar").css('background', ' #fff'); //despinta
        $("#dolar").css('color', ' #929292 '); //despinta
    });
    $("#dolar").click(function () {
        $("#paypal").css('display', 'block');
        $("#mercadopago").css('display', 'none');
        $("#mercadopagoBrasil").css('display', 'none');
        $("#ebanxDolares").css('display', 'block');
        $("#ebanx").css('display', 'none');
        $("#dolar").css('background', ' #029ce2'); //pinta
        $("#dolar").css('color', ' #fff '); //pinta
        $("#pesos").css('background', ' #fff'); //despinta
        $("#pesos").css('color', ' #929292 '); //despinta
        $("#reales").css('background', ' #fff'); //despinta
        $("#reales").css('color', ' #929292 '); //despinta
    });


    var mercadoPagoLink = '<?php echo isset($preference->init_point) ? $preference->init_point : ''; ?>';
    var mercadoPagoLinkBrasil = '<?php echo isset($preferenceBr->init_point) ? $preferenceBr->init_point : ''; ?>';
    $("#pesos").click(function () {

        $("#mercadopago").css('display', 'block');
        $("#mercadopagoBrasil").css('display', 'none');
        $("#ebanx").css('display', 'block');
        $("#ebanxDolares").css('display', 'none');
        $("#paypal").css('display', 'none');
        $("#pesos").css('background', ' #029ce2'); //pinta
        $("#pesos").css('color', ' #fff '); //pinta
        $("#dolar").css('background', ' #fff'); //despinta
        $("#dolar").css('color', ' #929292 '); //despinta
        $("#reales").css('background', ' #fff'); //despinta
        $("#reales").css('color', ' #929292 '); //despinta
    });


    $("#paypal").click(function () {

        $("#mercadopago").css('border', '  none '); //pinta
        $("#paypal").css('border', '  4px solid  #029ce2 '); //pinta
        $("#mercadopagoBrasil").css('display', 'none');
    });

    $("#mercadopago").click(function () {
        $("#paypal").css('border', '  none '); //pinta
        $("#mercadopago").css('border', '  4px solid  #029ce2 '); //pinta
        $("#mercadopagoBrasil").css('display', 'none');
    });
    $("#mercadopagoBrasil").click(function () {
        $("#paypal").css('border', '  none '); //pinta
        $("#mercadopago").css('border', '  4px solid  #029ce2 '); //pinta
    });
</script>

</body>

</html>


<div class="offcanvas offcanvas-start" data-bs-scroll="true" tabindex="-1" id="offcanvasWithBothOptions"
     aria-labelledby="offcanvasWithBothOptionsLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasWithBothOptionsLabel">Pagamento Paypal</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"
                style="position: relative;"></button>
    </div>
    <div class="offcanvas-body">
        <div class="hidden" id="paypal-button-container"></div>
    </div>
</div>