<div class="col-lg-4 col-md-4">
    <div class=" py-2">
        <div class="card card-visitas">
            <div class="card-body">

                <h5><?= $lang["resumen_de_compra"] ?></h5>

                <!--ACORDEON CARACTERISTICAS-->
                <div class="accordion" id="faq1">
                    <div class="card card-accordion">
                        <div class="" id="headingOne">
                            <h5 class="mb-0">
                                <a class="btn btn-accordion text-primary bg-white " href="#" data-toggle="collapse"
                                   data-target="#collapseOne2" aria-expanded="true" aria-controls="collapseOne">
                                    <p style="font-size: 20px;"> <?= $cantCarrito; ?> <?= $lang["servicios"] ?><br>  <small style="font-size:12px; color: #000;">Clique para fechar/expandir</small></p>
                                </a>
                            </h5>
                        </div>

                        <!--<div id="collapseOne2" class="collapse show" aria-labelledby="headingOne" data-parent="#faq1">
                            <ul class="lista-caracteristicas-r mx-4">
                                <?php
/*                                $precioTotalCarrito = 0;
                                $totalDescuentos = 0;

                                for ($i = 0; $i < $cantCarrito; $i++) {
                                    echo "RESERVA nro: " . ($i + 1);
                                    $reserva = $carrito[$i][0];
                                    $reservaAdicionales = $carrito[$i][1];
                                    $precioReserva = 0;
                                    $cantidadPasajeros = 0;
                                    for ($j = 0; $j < count($reserva); $j++) {
                                        $servicio = getServicio($reserva[$j]['idServicioSeleccionado']);
                                        $idServicioSalidasTarifas = $reserva[$j]['idServicioSalidasTarifas'];
                                        $cantidad = $reserva[$j]['cantidad'];
                                        $tarifa = calculaTarifa($reserva[$j]["idServicioSalidasTarifas"], $reserva[$j]["cantidad"]);
                                        $salida = getSalida($tarifa[0]['idServicioSalidas']);
                                        $fecha = strtotime($salida[0]['fecha']);
                                        $cantidadPasajeros += $reserva[$j]['cantidad'];
                                        $totalDescuentos += $tarifa[0]["totalDescuentos"];
                                        $precioReserva += $tarifa[0]["valor"];
                                        if ($j == 0) {
                                            */?>

                                            <li><?php /*= $servicio[0]["nombre_servicio"]; */?>
                                            <li>
                                            <li> <?php /*= date("d-m-Y", strtotime($salida[0]['fecha'])); */?> <?php /*= $salida[0]['horaCheckIn']; */?></li>


                                            <?php
/*
                                        }

                                        */?>

                                        <li><?php /*= $cantidad . ' ' . $tarifa[0]["nombre"] . ' (' . $tarifa[0]["edadFrom"] . ' a ' . $tarifa[0]["edadTo"] . ' Anos)' */?>
                                        <li>

                                        <li>Subtotal <?php /*= $tarifa[0]["valorSinIvaSym"]; */?>
                                        <li>

                                        <li>ISS <?php /*= $tarifa[0]["valorDeIvaSym"]; */?><li>

                                        <?php
/*
                                        $precioTotalCarrito += $tarifa[0]["valor"];
                                        if (isset($_SESSION['login']['idUsuario']) && $_SESSION['login']['idUsuario'] == 1) {

                                        }


                                    }

                                    if ($_SESSION["login"]["idUsuario"] == 1) {

                                        echo("<li>comision Vendedor: " . $_SESSION['moneda_sel_sym'] . "" . $tarifa[0]["comisionVendedor"] . '</li>');

                                        echo("<li>comision Sistema: " . $_SESSION['moneda_sel_sym'] . "" . $tarifa[0]["comisionSistema"] . '</li>');

                                    }

                                    */?>

                                    <li>Subtotal Reserva <?php /*= $_SESSION['moneda_sel_sym'] . "" . $precioReserva; */?></li>


                                    <?php
/*

                                    if (isset($reservaAdicionales) && count($reservaAdicionales) > 0) {


                                        */?>

                                        <li>Adicionales:</li>

                                        <?php
/*

                                        for ($j = 0; $j < count($reservaAdicionales); $j++) {

                                            $idServicioSalidasAdicionales = $reservaAdicionales[$j]["idServicioSalidasAdicionales"];

                                            $servicioAdicional = getServicioAdicionalSalida($idServicioSalidasAdicionales);

                                            $cantidad = $reservaAdicionales[$j]["cantidad"];


                                            $valor = getValorServiciosAdicionalesSalida($idServicioSalidasAdicionales, $cantidad);


                                            $precioTotalCarrito += $valor[0]["valor"];

                                            */?>

                                            <li><?php /*= $cantidad; */?> <?php /*= $servicioAdicional[0]["nombre"] */?> (X
                                                PAX <?php /*= $valor[0]["valorSymUnitario"]; */?>)
                                            </li>

                                            <?php
/*
                                        }
                                    }

                                    */?>

                                    <hr>

                                    <?php
/*

                                }

                                if ($totalDescuentos > 0) {
                                    */?>
                                    <li><?php /*= $lang["su_descuento"] */?><?php /*= $_SESSION['moneda_sel_sym'] . $totalDescuentos; */?></li>
                                    <li><?php /*= $lang["anfitrion"] */?><?php /*= $_SESSION['cupon_descuento']['anfitrion']; */?></li>
                                    <?php
/*                                }
                                */?>
                        </div>-->

                        <div id="collapseOne2" class="collapse show" aria-labby="headingOne" data-parent="#faq1">
                            <ul class="lista-caracteristicas-r mx-4">
                                <?php
                                $precioTotalCarrito = 0; // Subtotal geral de todos os serviços e adicionais
                                $totalDescuentos = 0;
                                $totalComisionVendedor = 0; // Comissão total do vendedor
                                $totalComisionSistema = 0;  // Comissão total do sistema

                                foreach ($carrito as $index => $item) {
                                    $reserva = $item[0];
                                    $reservaAdicionales = $item[1];
                                    $precioReserva = 0; // Subtotal da reserva atual
                                    $cantidadPasajeros = 0;

                                    // Informações do primeiro serviço para exibição inicial
                                    $firstService = getServicio($reserva[0]['idServicioSeleccionado']);
                                    $firstTarifa = calculaTarifa($reserva[0]["idServicioSalidasTarifas"], $reserva[0]["cantidad"]);
                                    $firstSalida = getSalida($firstTarifa[0]['idServicioSalidas']);

                                    $serviceName = $firstService[0]["nombre_servicio"];
                                    $serviceDate = date("d/m/Y", strtotime($firstSalida[0]['fecha']));
                                    $serviceTime = $firstSalida[0]['horaCheckIn'];

                                    echo "<li><strong>RESERVA nro:</strong> " . ($index + 1) . "</li>";
                                    echo "<li><strong>Serviço:</strong> {$serviceName}</li>";
                                    echo "<li><strong>Data e Hora:</strong> {$serviceDate} {$serviceTime}</li>";

                                    // Detalhes de cada item da reserva
                                    foreach ($reserva as $j => $reservaItem) {
                                        $servicio = getServicio($reservaItem['idServicioSeleccionado']);
                                        $tarifa = calculaTarifa($reservaItem["idServicioSalidasTarifas"], $reservaItem["cantidad"]);
                                        $cantidadPasajeros += $reservaItem['cantidad'];
                                        $totalDescuentos += $tarifa[0]["totalDescuentos"];
                                        $precioReserva += $tarifa[0]["valor"];
                                        $precioTotalCarrito += $tarifa[0]["valor"]; // Acumula no subtotal geral

                                        // Separação entre Adultos e Crianças
                                        $tipoPasajero = ($tarifa[0]["edadFrom"] >= 10) ? "Adulto(s)" : "Criança(s)";
                                        echo "<li><strong>{$tipoPasajero}:</strong> {$reservaItem['cantidad']} {$tarifa[0]["nombre"]} ({$tarifa[0]["edadFrom"]} a {$tarifa[0]["edadTo"]} Anos)</li>";
                                        echo "<li><strong>Subtotal:</strong> {$tarifa[0]["valorSinIvaSym"]}</li>";
                                        echo "<li><strong>ISS:</strong> {$tarifa[0]["valorDeIvaSym"]}</li>";

                                        // Comissões por serviço (apenas para usuário específico)
                                        if ($_SESSION["login"]["idUsuario"] == 1) {
                                            echo "<li><strong>Comissão Vendedor (Serviço):</strong> {$_SESSION['moneda_sel_sym']}{$tarifa[0]["comisionVendedor"]}</li>";
                                            echo "<li><strong>Comissão Sistema (Serviço):</strong> {$_SESSION['moneda_sel_sym']}{$tarifa[0]["comisionSistema"]}</li>";

                                            // Acumula as comissões totais
                                            $totalComisionVendedor += $tarifa[0]["comisionVendedor"];
                                            $totalComisionSistema += $tarifa[0]["comisionSistema"];
                                        }
                                    }

                                    // Adicionais da reserva
                                    if (!empty($reservaAdicionales)) {
                                        echo "<li><strong>Adicionais:</strong></li>";
                                        foreach ($reservaAdicionales as $adicional) {
                                            $servicioAdicional = getServicioAdicionalSalida($adicional["idServicioSalidasAdicionales"]);
                                            $valor = getValorServiciosAdicionalesSalida($adicional["idServicioSalidasAdicionales"], $adicional["cantidad"]);
                                            $precioReserva += $valor[0]["valor"]; // Acumula no subtotal da reserva
                                            $precioTotalCarrito += $valor[0]["valor"]; // Acumula no subtotal geral

                                            echo "<li><strong>Quantidade:</strong> {$adicional['cantidad']} {$servicioAdicional[0]["nombre"]} (X PAX {$valor[0]["valorSymUnitario"]})</li>";
                                        }
                                    }

                                    // Subtotal da reserva atual
                                    echo "<li><strong>Subtotal Reserva:</strong> {$_SESSION['moneda_sel_sym']}{$precioReserva}</li>";
                                    echo "<hr>";
                                }

                                // Descontos (se houver)
                                if ($totalDescuentos > 0) {
                                    echo "<li><strong>{$lang["su_descuento"]}</strong> {$_SESSION['moneda_sel_sym']}{$totalDescuentos}</li>";
                                    echo "<li><strong>{$lang["anfitrion"]}</strong> {$_SESSION['cupon_descuento']['anfitrion']}</li>";
                                }

                                // Comissões totais (apenas para usuário específico)
                                if ($_SESSION["login"]["idUsuario"] == 1) {
                                    echo "<li><strong>Comissão Vendedor (Total):</strong> {$_SESSION['moneda_sel_sym']}{$totalComisionVendedor}</li>";
                                    echo "<li><strong>Comissão Sistema (Total):</strong> {$_SESSION['moneda_sel_sym']}{$totalComisionSistema}</li>";
                                }

                                // Subtotal geral de todos os serviços e adicionais
                                echo "<li><strong>Subtotal Geral:</strong> {$_SESSION['moneda_sel_sym']}{$precioTotalCarrito}</li>";

                                // Desconto AR$ (se aceito)
                                $descuentoARS = 0;
                                if (isset($_SESSION['descuento_ars_aceptado']) && $_SESSION['descuento_ars_aceptado'] == '1' && isset($_SESSION['descuento_ars_monto'])) {
                                    $descuentoARS = floatval($_SESSION['descuento_ars_monto']);
                                    echo "<li><strong>Descuento Promocional:</strong> -{$_SESSION['moneda_sel_sym']}{$descuentoARS}</li>";
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>

                <!--FIN ACORDEON CARACTERISTICAS-->

                <hr class="hr-puntuada">

                <!--PRECIO TOTAL-->
                <?php
                $totalFinal = $precioTotalCarrito - $descuentoARS;
                ?>
                <div class="div-precio-t">
                    <p class="mb-0 d-inline-block"><strong><?= $lang["total_carrito"] ?></strong></p>
                    <h5 class="mb-0 bold d-inline-block float-right">
                        <strong><?= $_SESSION['moneda_sel_sym'] . "" . ($totalFinal); ?></strong>
                    </h5>
                </div>
                <!--FIN PRECIO TOTAL-->

            </div>
        </div>
    </div>
</div>