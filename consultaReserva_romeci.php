<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$servidor_db = 'localhost';
$usuario_db = 'root';
$senha_db = '';
$banco_db = 'metelebrasil'; 



$mysqli = new mysqli($servidor_db, $usuario_db, $senha_db, $banco_db);

if ($mysqli->connect_error) {

    $status = false;

    $retorno = "Erro ao conectar ao banco de dados: " . $mysqli->connect_error;

    echo json_encode(['status' => $status, 'retorno' => $retorno]);

    exit;

}







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

include("admin/classes/moneda.php");

include("admin/classes/convierte_monedas.php");





// unset($_SESSION['login']);

// echo '<pre>';

// print_r($_SESSION);

// echo '</pre>';



if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["merchant_payment_code"])) {

    $codigoAmigable = $_GET["merchant_payment_code"];

    $reserva = getReserva($codigoAmigable)[0];

    $idReserva = $reserva["idReserva"];

    $moneda = getMoneda($reserva["monedaSel"])[0]["Symbol"];

}



if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["reserva"])) {

    $codigoAmigable = $_GET["reserva"];

    $reserva = getReserva($codigoAmigable);



    if (count($reserva) < 1) {

        alertar($lang["la_reserva_con_el_codigo"] . " " . $codigoAmigable . " " . $lang["no_existe"], "error");

        redireccionarLento("index");

        exit();

    }



    $reserva = getReserva($codigoAmigable)[0];

    $idReserva = $reserva["idReserva"];

    $moneda = getMoneda($reserva["monedaSel"])[0]["Symbol"];

}

$comprobantes = getComprobantesIdReservaDolar($idReserva);

$total_dolares = $reserva["total_dolares"];

$comprobantes225 = convierteMoneda(188, 225, $comprobantes);

$comprobantes270 = convierteMoneda(188, 270, $comprobantes);

$comprobantes271 = convierteMoneda(188, 271, $comprobantes);

$comprobantes283 = convierteMoneda(188, 283, $comprobantes); ?>



<!-- Material Icons -->

<link href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp" rel="stylesheet">

<link href="https://fonts.googleapis.com/css?family=Material+Symbols|Material+Symbols+Outlined|Material+Symbols+Two+Tone|Material+Symbols+Round|Material+Symbols+Sharp" rel="stylesheet">





<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>



<!-- consultaReserva Romeci -->

<link href="css/consultaReserva_romeci.css?v=<?= uniqid(); ?>" rel="stylesheet">



<!--PASOS PARA RESERVA-->



<section class="py-2 bg-white">

    <div class="container">

        <div class="row">

            <div class="col-lg-12  ">

                <ul class="lista-pasos-form">

                    <li class="active"> <strong></i><?= $lang["consulta_de_reserva"] ?> <?= $codigoAmigable; ?></strong></li>

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

                <div class=" py-3">

                    <div class="card card-visitas ">

                        <div class="card-body">

                            <h5><?= $lang["resumen"] ?><a class="float-right"><small> </small></a></h5>



                            <!--ACORDEON CARACTERISTICAS-->



                            <div class="accordion" id="faq1">

                                <div class="card card-accordion">

                                    <div class="" id="headingOne">

                                        <h5 class="mb-0">

                                            <a class="btn btn-accordion text-primary bg-white " data-toggle="collapse" data-target="#collapseOne2" aria-expanded="true" aria-controls="collapseOne">

                                                <p> <small class="float-right"></small></p>

                                            </a>

                                        </h5>

                                    </div>

                                    <!--de aca le saque class="collapse"-->

                                    <div id="collapseOne2" class=" " aria-labelledby="headingOne" data-parent="#faq1">

                                        <ul class="lista-caracteristicas-r mx-4">





                                            <?php $horarios = getReservaHorarios($idReserva);

                                            for ($i = 0; $i < count($horarios); $i++) {

                                                $servicio = getServicio($horarios[$i]["idServicioSeleccionado"]);

                                                $idReservaHorarios = $horarios[$i]["idReservaHorarios"];

                                                $reservaTarifas = getReservaTarifas($idReservaHorarios);

                                                $adicionales = getReservaAdicionalesNoIncluidos($idReservaHorarios); ?>



                                                <li><?= $servicio[0]["nombre_servicio"]; ?></li>



                                                <li><?= date("d/m/Y", strtotime($horarios[$i]['fecha'])) . " Check IN: " . substr($horarios[$i]["horaCheckIn"], 0, 5); ?></li>



                                                <?php for ($j = 0; $j < count($reservaTarifas); $j++) {

                                                    $edadFrom = getEdad($reservaTarifas[$j]["idFromEdad"]);

                                                    $edadTo = getEdad($reservaTarifas[$j]["idToEdad"]);

                                                    $idMonedaSel = $reservaTarifas[$j]["monedaSel"];

                                                    $moneda = getMoneda($idMonedaSel)[0]["Symbol"]; ?>





                                                    <li><?= $reservaTarifas[$j]["cantidad"]; ?> <?= $reservaTarifas[$j]["nombre"]; ?> ( <?= $edadFrom[0]["valor"] ?> A <?= $edadTo[0]["valor"] ?> Anos)</li>

                                                    <li>ISS <?= $moneda . $reservaTarifas[$j]["valorDeIva"]; ?>

                                                    <li>

                                                    <li>Subtotal <?= $moneda . ($reservaTarifas[$j]["valorSinIva"] + $reservaTarifas[$j]["valorDeIva"]); ?></li>



                                                <?php } ?>



                                                <?php for ($j = 0; $j < count($adicionales); $j++) { ?>



                                                    <li><?= $adicionales[$j]["cantidad"]; ?> <?= $adicionales[$j]["nombre"]; ?> <?= $moneda . $adicionales[$j]["precioIva"]; ?></li>



                                                <?php } ?>



                                                <hr>



                                            <?php } ?>



                                            <?php if ($comprobantes > 0) { ?>



                                                <li>Total <?= $moneda . convierteMoneda(188, $_SESSION['moneda_sel'], $reserva["total_dolares"]); ?></li>



                                                <?php $totalComprobantesAMostrar = $comprobantes;

                                                $diferenciaAPagar = $reserva["total_dolares"] - $totalComprobantesAMostrar;



                                                if ($diferenciaAPagar < 1) {

                                                    $diferenciaAPagar = 0;

                                                } ?>

                                                <li>Pagamento realizado <?= $moneda . convierteMoneda(188, $_SESSION['moneda_sel'], $totalComprobantesAMostrar) ?></li>



                                            <?php } else {

                                                $diferenciaAPagar = $reserva["total_dolares"] - $totalComprobantesAMostrar; ?>



                                                <li id='total-mostrar-moeda'>Total <?= $moneda . convierteMoneda(188, $_SESSION['moneda_sel'], $reserva["total_dolares"]); ?></li>

                                            <?php

                                            } ?>

                                        </ul>

                                    </div>

                                </div>

                            </div>

                            <!--FIN ACORDEON CARACTERISTICAS-->

                            <hr class="hr-puntuada">

                            <!--PRECIO TOTAL-->

                            <div class="div-precio-t">

                                <p class="mb-0 float-left"><strong>Resta pagar</strong></p>

                                <p class="mb-0 float-right"><strong id="total-resta-pagar"><?= $moneda . " " . convierteMoneda(188, $_SESSION['moneda_sel'], $diferenciaAPagar); ?></strong></p>

                            </div>

                            <!--FIN PRECIO TOTAL-->



                        </div>

                    </div>

                </div>

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





                    session_start();



                    //foreach ($_SESSION as $chave => $valor) {echo "$chave: $valor<br>";}





                    //if (isset($_GET['countryCode'])) { $_SESSION['geo']['countryCode'] = $_GET['countryCode']; } else {echo "Código da moeda do país não fornecido.";}

                    $monedaSel = isset($_SESSION['moneda_sel']) ? $_SESSION['moneda_sel'] : null;

                    if (!$monedaSel) {

                        echo "Seleção de moeda não definida.";

                        exit;

                    }

                    switch ($_SESSION['moneda_sel']) {

                        case 'AR':

                            $habilita_reales = false;

                            $habilita_pesos_arg = true;

                            $habilita_pesos_ch = false;

                            $countryEbanx = 'AR';

                            $currencyEbanx = 'ARS';

                            $habilita_ebanx = true;

                            $_SESSION['geo']['nombre_pais'] = "Argentina";

                            $totalEbanx = convierteMoneda(188, 270, $reserva["total_dolares"]) - $comprobantes;



                            break;

                        case 283:

                            $habilita_reales = true;

                            $habilita_pesos_arg = false;

                            $habilita_pesos_ch = false;

                            $countryEbanx = 'BR';

                            $currencyEbanx = 'BRL';

                            $totalEbanx = convierteMoneda(188, 283, $reserva["total_dolares"]) - $comprobantes;

                            $habilita_ebanx = false;

                            $_SESSION['geo']['nombre_pais'] = 'Brasil';



                            break;

                        case 'CL':

                            $habilita_reales = false;

                            $habilita_pesos_arg = false;

                            $habilita_pesos_ch = true;

                            $countryEbanx = 'CL';

                            $currencyEbanx = 'CLP';

                            $totalEbanx = convierteMoneda(188, 271, $reserva["total_dolares"]) - $comprobantes;

                            $habilita_ebanx = true;

                            $_SESSION['geo']['nombre_pais'] = 'Chile';

                            break;



                        case 'UY':

                            $habilita_dolares = true;

                            $countryEbanx = 'UY';

                            $currencyEbanx = 'USD';

                            $totalEbanx = convierteMoneda(188, 188, $reserva["total_dolares"]) - $comprobantes;

                            $habilita_ebanx = true;

                            $_SESSION['geo']['nombre_pais'] = 'Uruguay';

                            break;



                        case 'PE':

                            $habilita_dolares = true;

                            $countryEbanx = 'PE';

                            $currencyEbanx = 'USD';

                            $totalEbanx = convierteMoneda(188, 188, $reserva["total_dolares"]) - $comprobantes;

                            $habilita_ebanx = true;

                            $_SESSION['geo']['nombre_pais'] = 'Peru';

                            break;



                        case 'PY':

                            $habilita_guaranies = true;

                            $countryEbanx = 'PY';

                            $currencyEbanx = 'USD';

                            $totalEbanx = convierteMoneda(188, 225, $reserva["total"]) - $comprobantes;

                            $habilita_ebanx = true;

                            $_SESSION['geo']['nombre_pais'] = 'Paraguay';



                            break;

                        default:

                            break;

                    } ?>





                    <div class="row g-3 py-3">



                        <!--Modicicação-->







                        <?php

                        $reales = convierteMoneda(188, 283, $diferenciaAPagar);

                        $euro = convierteMoneda(188, 213, $diferenciaAPagar);

                        $dolares = $diferenciaAPagar;

                        $guaranies = convierteMoneda(188, 225, $reserva["total_dolares"]) - $comprobantes;

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



                        <div class="col-12">

                            <div id="container-valores-moedas">

                                <div class="row mx-auto w-100 g-3">



                                    <div class='hr w-100 mb-2 mt-3' style="opacity: 0.3;"></div>

                                    <div class="col-12 mx-auto">

                                        <h4 style="text-align: center; font-weight: 200; opacity: 0.8;">Selecione a moeda com a que deseja pagar</h4>

                                    </div>

                                    <div class='hr w-100 mt-1 mb-3' style="opacity: 0.3;"></div>



                                    <?php

                                    function formatarParaDecimal($valor)

                                    {

                                        // Remover quaisquer caracteres que não sejam números, pontos ou vírgulas

                                        $valorLimpo = preg_replace('/[^\d.,]/', '', $valor);



                                        // Substituir a vírgula decimal por um ponto

                                        $valorFormatado = str_replace(',', '.', str_replace('.', '', $valorLimpo));



                                        // Converter para float

                                        return number_format((float)$valorFormatado, 2, '.', '');

                                    }

                                    $html_valor_moeda = "";

                                    $active_valor_moeda = "active";

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



                                        $html_valor_moeda .= "<div class='col-4'>";

                                        $html_valor_moeda .= "<div class='container-vm {$active_valor_moeda}' data-codigo='{$moeda_codigo}' data-simbolo='{$moeda_simbolo}' data-valor='{$valor}' data-valor-formatado='{$valor_formatado}'>";

                                        $html_valor_moeda .= "<div class='container-icone-valor-moeda'>";

                                        $html_valor_moeda .= "<i></i>";

                                        $html_valor_moeda .= "</div>";

                                        $html_valor_moeda .= "<div class='container-valor-moeda'>";

                                        $html_valor_moeda .= "<div>";

                                        $html_valor_moeda .= "<span>{$moeda_simbolo}</span>";

                                        $html_valor_moeda .= "<strong>{$valor}</strong>";

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



                                        $active_valor_moeda = '';

                                    }

                                    echo $html_valor_moeda;

                                    ?>



                                </div>

                            </div>



                        </div>

                        <hr>





                        <script type="text/javascript">

                            let codigo_forma_pag_selecionada_start;

                            $.when(

                                $.each($('#container-valores-moedas .container-vm'), function(i, v) {

                                    if ($(v).hasClass('active')) {

                                        codigo_forma_pag_selecionada_start = $(v).data('codigo');

                                        let simbolo = $(this).data('simbolo');

                                        let valor = $(this).data('valor');

                                        $('#total-resta-pagar, #total-mostrar-moeda').text(`${simbolo} ${valor}`);

                                        return false;

                                    }

                                })

                            ).done(() => {

                                let interval_check_formas_pagamento_show = setInterval(() => {

                                    $.when(

                                        $.each($('.container-formas-pagamento').find('.payment-option'), function(i, v) {

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





                            $('#container-valores-moedas .container-vm').click(function(e) {

                                e.preventDefault();



                                let codigo = $(this).data('codigo');

                                let simbolo = $(this).data('simbolo');

                                let valor = $(this).data('valor');



                                $.when(

                                    $.each($(this).parent().parent().find('.container-vm'), function(i, v) {

                                        if ($(v).hasClass('active')) {

                                            $(v).removeClass('active');

                                        }

                                    })

                                ).done(() => {

                                    $(this).addClass('active');

                                    $('#total-resta-pagar, #total-mostrar-moeda').text(`${simbolo} ${valor}`);

                                    $.when(

                                        $.each($('.container-formas-pagamento').find('.payment-option'), function(i, v) {

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

                                        let producao_paypal = true;

                                        let client_id_paypal = '';

                                        let ambiente_sdk_paypal = '';

                                        switch (codigo) {

                                            case 'BRL': // Brasil

                                                // Crie um novo elemento script paypal

                                                sdk_paypal = document.createElement('script');

                                                if (producao_paypal) {

                                                    ambiente_sdk_paypal = 'www';

                                                    client_id_paypal = 'AWkWp8R-_lG1LzLhoyvgvGfofSx5zzrVOcpnxUsA2cPaNyL5K3MEuBBEipYuh_G5x-L40-VncCSo3vID';

                                                } else {

                                                    ambiente_sdk_paypal = 'sandbox';

                                                    client_id_paypal = 'AZBo2zA7emoQ_Xw3tCqG4E97FomzRErv6HizYwDr0YOEk_tUAgPYNQp9yERuLTz7HeD-JftY5_FcaRqv';

                                                }

                                                sdk_paypal.src = `https://${ambiente_sdk_paypal}.paypal.com/sdk/js?client-id=${client_id_paypal}&currency=${codigo}`;

                                                sdk_paypal.type = 'text/javascript';

                                                break;

                                            case 'EUR': // Europa

                                                // Crie um novo elemento script paypal

                                                sdk_paypal = document.createElement('script');

                                                if (producao_paypal) {

                                                    ambiente_sdk_paypal = 'www';

                                                    client_id_paypal = 'AWkWp8R-_lG1LzLhoyvgvGfofSx5zzrVOcpnxUsA2cPaNyL5K3MEuBBEipYuh_G5x-L40-VncCSo3vID';

                                                } else {

                                                    ambiente_sdk_paypal = 'sandbox';

                                                    client_id_paypal = 'AZBo2zA7emoQ_Xw3tCqG4E97FomzRErv6HizYwDr0YOEk_tUAgPYNQp9yERuLTz7HeD-JftY5_FcaRqv';

                                                }

                                                sdk_paypal.src = `https://${ambiente_sdk_paypal}.paypal.com/sdk/js?client-id=${client_id_paypal}&currency=${codigo}`;

                                                sdk_paypal.type = 'text/javascript';

                                                break;

                                            case 'ARS': // Argentina

                                            case 'PYG': // Guaranies

                                            case 'CLP': // Chile

                                                // Crie um novo elemento script paypal

                                                codigo = 'USD';

                                                sdk_paypal = document.createElement('script');

                                                if (producao_paypal) {

                                                    ambiente_sdk_paypal = 'www';

                                                    client_id_paypal = 'AWkWp8R-_lG1LzLhoyvgvGfofSx5zzrVOcpnxUsA2cPaNyL5K3MEuBBEipYuh_G5x-L40-VncCSo3vID';

                                                } else {

                                                    ambiente_sdk_paypal = 'sandbox';

                                                    client_id_paypal = 'AcekW2Qsin1cKBHzO3NPRBZ5VlqRayrk6O3w6vEf4pb-cVRncCgY-cJXYLWqn11MMKxXpmRZrvS8jisW';

                                                }

                                                sdk_paypal.src = `https://${ambiente_sdk_paypal}.paypal.com/sdk/js?client-id=${client_id_paypal}&currency=${codigo}`;

                                                sdk_paypal.type = 'text/javascript';                                                

                                                break;

                                            default: // todo o resto

                                                // Crie um novo elemento script paypal

                                                sdk_paypal = document.createElement('script');

                                                if (producao_paypal) {

                                                    ambiente_sdk_paypal = 'www';

                                                    client_id_paypal = 'AWkWp8R-_lG1LzLhoyvgvGfofSx5zzrVOcpnxUsA2cPaNyL5K3MEuBBEipYuh_G5x-L40-VncCSo3vID';

                                                } else {

                                                    ambiente_sdk_paypal = 'sandbox';

                                                    client_id_paypal = 'AcekW2Qsin1cKBHzO3NPRBZ5VlqRayrk6O3w6vEf4pb-cVRncCgY-cJXYLWqn11MMKxXpmRZrvS8jisW';

                                                }

                                                sdk_paypal.src = `https://${ambiente_sdk_paypal}.paypal.com/sdk/js?client-id=${client_id_paypal}&currency=${codigo}`;

                                                sdk_paypal.type = 'text/javascript';

                                                break;

                                        }

                                        // Adicione o script ao DOM

                                        $('#sdk-paypal').html(sdk_paypal);

                                        // $('#sdk-mercado-pago').html(sdk_mercado_pago);





                                        $.each($('.container-formas-pagamento').find('.form-real').find('.custom-radio'), function(i, v) {

                                            $(v).removeClass('active');

                                        });

                                    });



                                });



                            });



                            function selectFormaPagamento(el) {



                                $.when(

                                    $.each($(el).parent().find('.custom-radio'), function(i, v) {

                                        $(v).removeClass('active');

                                    })

                                ).done(() => {

                                    $(el).addClass('active');

                                });



                            }





                            const currencyOptions = document.querySelectorAll('input[name="currency"]');

                            currencyOptions.forEach(option => {

                                option.addEventListener('change', function() {

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

                                                    <img class="card-img" src="/img/mercado-pago-logo.png" alt="" style="width: 95px;">

                                                </div>

                                            </div>

                                            <div class="container-card-pagamento-body">

                                                <p>Finalize sua compra de forma segura com Mercado Pago. Clique no botão "Pagar" para concluir seu pagamento em reais de forma rápida e fácil com seus meios preferidos.</p>

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

                                                    <img class="card-img" src="/img/pix.png" alt="" style="width: 85px;">

                                                </div>

                                            </div>

                                            <div class="container-card-pagamento-body">

                                                <p>Finalize sua compra de forma segura com Pix. Clique no botão para realizar seu pagamento de maneira rápida e prática.</p>

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

                                                    <img class="card-img" src="/img/Pay_Pal.png" alt="" style="width: 95px;">

                                                </div>

                                            </div>

                                            <div class="container-card-pagamento-body">

                                                <p>Finalize sua compra em reais de forma segura com PayPal. Clique no botão para concluir seu pagamento em apenas alguns passos.</p>

                                                <button class="btn btn-dark paymentMethod abre_paypal" id="paypal-radio-br">Pagar</button>

                                            </div>

                                        </div>



                                        <?php include("admin/pasarelas/ebanx/ebanx.php");



                                        if ($url_ebanx == (-5)) { //alertar($lang["el_metodo_seleccionado_no_puede_cobrar"],$lang["error"]);

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

                                                    <img class="card-img" src="/img/Pay_Pal.png" alt="" style="width: 95px;">

                                                </div>

                                            </div>

                                            <div class="container-card-pagamento-body">

                                                <p>Finaliza tu compra en euros de forma segura con PayPal. Haz clic en el botón "Pagar" para completar tu pago en solo unos pasos.</p>

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

                                                    <img class="card-img" src="/img/Pay_Pal.png" alt="" style="width: 95px;">

                                                </div>

                                            </div>

                                            <div class="container-card-pagamento-body">

                                                <p>Finaliza tu compra en dolares de forma segura con PayPal. Haz clic en el botón "Pagar" para completar tu pago en solo unos pasos.</p>

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

                                                    <img class="card-img" src="/img/Pay_Pal.png" alt="" style="width: 95px;">

                                                </div>

                                            </div>

                                            <div class="container-card-pagamento-body">

                                                <p>Finaliza tu compra de forma segura con PayPal. Haz clic en el botón "Pagar" para completar tu pago en solo unos pasos.</p>

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

                                                    <img class="card-img" src="/img/mercado-pago-logo.png" alt="" style="width: 75px;">

                                                </div>

                                            </div>

                                            <div class="container-card-pagamento-body">

                                                <p>Finaliza tu compra en pesos argentinos de manera rápida y segura con Mercado Pago. Haz clic en el botón "Pagar" para completar tu pago con tus métodos preferidos.</p>

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

                                                    <img class="card-img" src="/img/Pay_Pal.png" alt="" style="width: 85px;">

                                                </div>

                                            </div>

                                            <div class="container-card-pagamento-body">

                                                <p>Finaliza tu compra de forma segura con PayPal. Haz clic en el botón "Pagar" para completar tu pago en solo unos pasos.</p>

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

                                                    <img class="card-img" src="/img/Pay_Pal.png" alt="" style="width: 85px;">

                                                </div>

                                            </div>

                                            <div class="container-card-pagamento-body">

                                                <p>Finaliza tu compra de forma segura con PayPal. Haz clic en el botón "Pagar" para completar tu pago en solo unos pasos.</p>

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

                        $totalMercadopagoBrasil = convierteMoneda($_SESSION['moneda_sel'], 283, $reserva["total"]) - $comprobantes283;

                        $totalReales = convierteMoneda(188, 283, $diferenciaAPagar); // Real Brasil

                        $totalPayPal = convierteMoneda($_SESSION['moneda_sel'], 188, $reserva["total"]) - $comprobantes; // Dolar



                        include("./admin/pasarelas/mercadopagoArgentina/procesaPago.php");

                        include("./admin/pasarelas/mercadopagoBrasil/procesaPago.php");

                        //include("../query.php");



                        ?>

                        <div id="sdk-paypal">

                            <script src="https://sandbox.paypal.com/sdk/js?client-id=AZBo2zA7emoQ_Xw3tCqG4E97FomzRErv6HizYwDr0YOEk_tUAgPYNQp9yERuLTz7HeD-JftY5_FcaRqv&currency=BRL"></script>

                        </div>

                        <div id="sdk-mercado-pago">

                            <script src="https://sdk.mercadopago.com/js/v2"></script>

                        </div>



                        

                        <script>

                            $(document).ready(function() {

                                const myOffcanvasPaypal = document.getElementById('offcanvasWithBothOptions')

                                myOffcanvasPaypal.addEventListener('shown.bs.offcanvas', event => {

                                    $('#paypal-button-container').removeClass('hidden');

                                });

                                myOffcanvasPaypal.addEventListener('hidden.bs.offcanvas', event => {

                                    $('#paypal-button-container').empty();

                                });

                            });



                            $('.abre_mercado_pago').click(function(e) {

                                e.preventDefault();





                                let amount_value = '';

                                let amount_currency_code = '';



                                $.when(

                                    $.each($('#container-valores-moedas').find('.container-vm'), function(i, v) {

                                        // console.log(v);



                                        if ($(v).hasClass('active')) {

                                            amount_value = $(v).data('valor');

                                            amount_currency_code = $(v).data('codigo');



                                            // if (amount_currency_code == 'ARS') {

                                            //     $.each($('#container-valores-moedas').find('.container-vm'), function(i, v) {



                                            //         if ($(v).data('codigo') == 'USD') {

                                            //             // console.log($(v).data('valor'));

                                            //             // console.log($(v).data('codigo'));



                                            //             amount_value = $(v).data('valor');

                                            //             amount_currency_code = $(v).data('codigo');

                                            //             return false;

                                            //         }

                                            //     });

                                            // }



                                            return false;

                                        }

                                    })

                                ).done(() => {



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

                                        success: function(r) {

                                            console.log(r);



                                            if (r.status) {

                                                let locale = 'pt-BR';



                                                if (amount_currency_code == 'ARS') {

                                                    locale = 'es-AR';

                                                }



                                                const mp = new MercadoPago(r.Public_Key, {

                                                    locale: locale,

                                                    advancedFraudPrevention: true,

                                                });





                                                $.when(

                                                    mp.checkout({

                                                        preference: {

                                                            id: r.retorno.id,

                                                        },

                                                        autoOpen: true,

                                                        iframe: true,

                                                    })

                                                ).done(() => {



                                                    let tentativas = 0;

                                                    let interval_verifica_pagamento = setInterval(() => {



                                                        $.ajax({

                                                            type: "POST",

                                                            url: "criar_preferencia_mercado_pago.php",

                                                            data: {

                                                                verifica_pagamento: 1,

                                                                currency_id: amount_currency_code,

                                                                paymentId: r.retorno.id,

                                                            },

                                                            dataType: "json",

                                                            success: function(r) {

                                                                console.log('verifica_pagamento', r);

                                                                tentativas++;

                                                                if (r.status) {

                                                                    clearInterval(interval_verifica_pagamento);



                                                                    $.ajax({

                                                                        type: "POST",

                                                                        url: "consultaReserva_romeci.php",

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

                                                                        success: function(r) {

                                                                            console.log(r);

                                                                            alert('<? $lang["gracias_por_confiar_en_metele_brasil"] ?>' + details.payer.name.given_name + '<? $lang["el_pago_de_paypal"] ?>');

                                                                            window.location.reload();



                                                                        },

                                                                        error: function(e) {

                                                                            console.log(e.responseText);



                                                                        }

                                                                    });





                                                                } else {

                                                                    if (tentativas > 60) {

                                                                        clearInterval(interval_verifica_pagamento);

                                                                    }

                                                                }



                                                            },

                                                            error: function(e) {

                                                                console.log(e);

                                                                clearInterval(interval_verifica_pagamento);

                                                            }

                                                        });



                                                        if (tentativas > 60) {

                                                            clearInterval(interval_verifica_pagamento);

                                                        }





                                                    }, 5000);



                                                });







                                            } else {

                                                // 

                                            }



                                        },

                                        error: function(e) {

                                            console.log(e);



                                        }

                                    });





                                });





                            });



                            $('.abre_paypal').click(function(e) {

                                e.preventDefault();



                                let amount_value = '';

                                let amount_currency_code = '';



                                $.when(

                                    $.each($('#container-valores-moedas').find('.container-vm'), function(i, v) {

                                        // console.log(v);



                                        if ($(v).hasClass('active')) {

                                            amount_value = $(v).data('valor');

                                            amount_currency_code = $(v).data('codigo');



                                            if (amount_currency_code == 'ARS' || amount_currency_code == 'PYG' || amount_currency_code == 'CLP') {

                                                $.each($('#container-valores-moedas').find('.container-vm'), function(i, v) {



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



                                    $(document).ready(function() {



                                        $.when(

                                            paypal.Buttons({

                                                createOrder: function(data, actions) {

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

                                                onApprove: function(data, actions) {

                                                    // This function captures the funds from the transaction.

                                                    return actions.order.capture().then(function(details) {

                                                        // This function shows a transaction success message to your buyer.

                                                        // console.log('status pagamento', details);



                                                        if (details.status == 'COMPLETED') {

                                                            $.ajax({

                                                                type: "POST",

                                                                url: "consultaReserva_romeci.php",

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

                                                                success: function(r) {

                                                                    console.log(r);

                                                                    alert('<? $lang["gracias_por_confiar_en_metele_brasil"] ?>' + details.payer.name.given_name + '<? $lang["el_pago_de_paypal"] ?>');

                                                                    window.location.reload();



                                                                },

                                                                error: function(e) {

                                                                    console.log(e.responseText);



                                                                }

                                                            });

                                                        } else {

                                                            console.log('status pagamento', details);

                                                        }









                                                    });

                                                },

                                                onCancel: function(data) {

                                                    // Quando o comprador fecha o modal ou cancela o pagamento

                                                    console.log('Pagamento cancelado pelo usuário.');

                                                    console.log('Detalhes do cancelamento:', data);

                                                },

                                                onError: function(err) {

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

                                    $.each($('#container-valores-moedas').find('.container-vm'), function(i, v) {

                                        // console.log(v);



                                        if ($(v).hasClass('active')) {

                                            amount_value = $(v).data('valor');

                                            amount_currency_code = $(v).data('codigo');



                                            if (amount_currency_code == 'ARS') {

                                                $.each($('#container-valores-moedas').find('.container-vm'), function(i, v) {



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

                                        window.$openpix.push([

                                            'pix',

                                            {

                                                value: valor, // Valor do PIX

                                                correlationID: correlationID, // Identificador único para rastrear a transação

                                            },

                                        ]);



                                        // Função para capturar eventos

                                        const logEvents = (e) => {

                                            if (e.type === 'CHARGE_COMPLETED') {

                                                console.log('Transação concluída:', e);

                                                alert('Pagamento concluído com sucesso!');



                                                //window.location.reload();



                                                // console.log('status pagamento', details);





                                                $.ajax({

                                                    type: "POST",

                                                    url: "consultaReserva_romeci.php",

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

                                                    success: function(r) {

                                                        console.log(r);

                                                        alert('Pagamento Confirmado');

                                                        window.location.reload();



                                                    },

                                                    error: function(e) {

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

                                    <div class="method paypal"> </div>

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

        </form>

        <!--FIN FORM DE PAGO-->

    </div>

    </div>

    <?php if (isset($_SESSION["login"]["idCobrador"])) {

                    if ($_SESSION["login"]["idCobrador"] > 0) {

    ?>





            <div class="card card-visitas">

                <h5 class="mb-4" id="textoMetodoDePago"><?= $lang["cobro_en_mano"] ?></h5>

                <div class="container paymentCont ">



                    <a href="admin/cobroSignal?reserva=<?= $codigoAmigable ?>">

                        <label class="btn btn-primary paymentMethod metele-pago">

                            <div class="method paypal">

                                <div class="method ">

                                </div>

                            </div>

                        </label>

                    </a>





                </div>

            </div>



    <?php

                    }

                }

    ?>

    <!--FIN METODOS DE PAGO-->

<?php }

            if ($comprobantes > 0) { // ($comprobantes<$total_dolares) {



?>



    <!--METODOS DE PAGO-->

    <div class="col-md-6 py-3">

        <div class="card card-visitas" id="cardVisitas">

            <div class="card-body">

                <h3 class="mb-4" id="textoMetodoDePago"><?= $lang["Felicidades"] ?></h5>

                    <h5 class="success">Reserva confirmada

                </h3>

                <form method="post" action="voucherCarrito">

                    <button type="submit" name="codigoAmigable" value="<?= $codigoAmigable; ?>" class="btn btn-secondary btn-lg btn-radius" style="width: 100% !important;"><?= $lang["detalles_reserva"]; ?></button>

                </form>

                <a href="https://meteleargentina.com" class="btn btn-primary btn-lg btn-radius" id="btnPagar" style="width: 100% !important;"><?= $lang["volver_al_site"]; ?></a>

            </div>

        </div>

    </div>



<?php   } ?>

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

} ?>

<!--FIN BOTON SIGUIENTE-->

</section>

<!--FIN SECCION DATOS PERSONALES-->

<!-- Footer -->

<footer class="footer footer-reserva ">

    <div class="container">

        <div class="row">

            <div class="col-lg-4"></div>

            <div class="col-lg-2">

                <p class="text-gris text-pagos"> <i class="fa fa-lock mx-2 "></i><?= $lang["pago_seguro"] ?></p>

            </div>

            <div class="col-lg-2">

                <img src="img/paypal-2.png" class="img-fluid img-foter">

            </div>

            <div class="col-lg-2">

                <img src="img/mastercard-2.png" class="img-fluid img-foter">

            </div>

            <div class="col-lg-2">

                <img src="img/visa-2.png" class="img-fluid img-foter">

            </div>

        </div>

    </div>

</footer>

<!-- Copyright Section -->

<section class="copyright py-4 text-center text-white">

    <div class="container">

        <div class="row">

            <div class="col-lg-12">

                <h4 class="text-left"><small><span>METELE BRASIL</span><?= $lang["es_una_marca_registrada_de_reservate_sl"] ?></small></h4>

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

    $("#reales").click(function() {

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

    $("#dolar").click(function() {

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





    var mercadoPagoLink = '<?= $preference->init_point; ?>';

    var mercadoPagoLinkBrasil = '<?= $preferenceBr->init_point; ?>';

    $("#pesos").click(function() {



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





    $("#paypal").click(function() {



        $("#mercadopago").css('border', '  none '); //pinta

        $("#paypal").css('border', '  4px solid  #029ce2 '); //pinta

        $("#mercadopagoBrasil").css('display', 'none');

    });



    $("#mercadopago").click(function() {

        $("#paypal").css('border', '  none '); //pinta

        $("#mercadopago").css('border', '  4px solid  #029ce2 '); //pinta

        $("#mercadopagoBrasil").css('display', 'none');

    });

    $("#mercadopagoBrasil").click(function() {

        $("#paypal").css('border', '  none '); //pinta

        $("#mercadopago").css('border', '  4px solid  #029ce2 '); //pinta

    });

</script>



</body>



</html>





<div class="offcanvas offcanvas-start" data-bs-scroll="true" tabindex="-1" id="offcanvasWithBothOptions" aria-labelledby="offcanvasWithBothOptionsLabel">

    <div class="offcanvas-header">

        <h5 class="offcanvas-title" id="offcanvasWithBothOptionsLabel">Pagamento Paypal</h5>

        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close" style="position: relative;"></button>

    </div>

    <div class="offcanvas-body">

        <div class="hidden" id="paypal-button-container"></div>

    </div>

</div>