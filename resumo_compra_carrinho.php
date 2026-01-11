<div class="col-lg-4 col-md-4">
    <div class=" py-2">
        <div class="card card-visitas shadow-sm border-0">
            <div class="card-body">

                <h5 class="mb-3">
                    <i class="fas fa-file-invoice-dollar text-primary mr-2"></i>
                    <?= $lang["resumen_de_compra"] ?>
                </h5>

                <!--ACORDEON CARACTERISTICAS-->
                <div class="accordion" id="faq1">
                    <div class="card card-accordion border-0" style="border-top: 3px solid #029ce2 !important;">
                        <div class="" id="headingOne">
                            <h5 class="mb-0">
                                <a class="btn btn-accordion text-primary bg-white d-flex justify-content-between align-items-center w-100" href="#" data-toggle="collapse"
                                   data-target="#collapseOne2" aria-expanded="true" aria-controls="collapseOne" style="padding: 1rem;">
                                    <div>
                                        <span class="badge badge-primary badge-pill mr-2"><i class="fas fa-list mr-1"></i><?= $cantCarrito; ?></span>
                                        <span style="font-size: 18px; font-weight: 500;"><?= $lang["servicios"] ?></span>
                                    </div>
                                </a>
                            </h5>
                        </div>

                        <div id="collapseOne2" class="collapse show" aria-labelledby="headingOne" data-parent="#faq1">
                            <ul class="lista-caracteristicas-r mx-3">
                                <?php
                                $precioTotalCarrito = 0;
                                $totalDescuentos = 0;

                                for ($i = 0; $i < $cantCarrito; $i++) {
                                    echo "<li class=\"font-weight-bold text-primary mb-1\">RESERVA nro: " . ($i + 1) . "</li>";
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
                                        
                                        // MOSTRAR precio redondeado (inflado) en subtotales para ancla psicológica
                                        $valorMostrar = $tarifa[0]["valor"];
                                        $valorSinIvaMostrar = $tarifa[0]["valorSinIva"];
                                        if (in_array($_SESSION['moneda_sel'], [270, 271, 225]) && isset($tarifa[0]['redondeoDiferencia']) && $tarifa[0]['redondeoDiferencia'] > 0) {
                                            $valorMostrar = $tarifa[0]["valor"] + $tarifa[0]['redondeoDiferencia'];
                                            $valorSinIvaMostrar = $tarifa[0]["valorSinIva"] + $tarifa[0]['redondeoDiferencia'];
                                        }
                                        
                                        $precioReserva += $valorMostrar;
                                        if ($j == 0) {
                                            ?>

                                            <li class="mb-1"><i class="fas fa-map-marker-alt text-danger mr-1"></i><?= $servicio[0]["nombre_servicio"]; ?></li>
                                            <li class="mb-2"><i class="fas fa-calendar-alt text-primary mr-1"></i> <?= date("d-m-Y", strtotime($salida[0]['fecha'])); ?> <span class="ml-2"><i class="fas fa-clock text-success mr-1"></i><?= $salida[0]['horaCheckIn']; ?></span></li>

                                            <?php
                                        }

                                        ?>

                                        <li class="small"><i class="fas fa-ticket-alt text-secondary mr-1"></i><?= $cantidad . ' ' . $tarifa[0]["nombre"] . ' (' . $tarifa[0]["edadFrom"] . ' a ' . $tarifa[0]["edadTo"] . ' Anos)' ?></li>

                                        <li class="small text-muted">Subtotal <?= $_SESSION['moneda_sel_sym'] . number_format($valorSinIvaMostrar, 0, ',', '.'); ?></li>

                                        <li class="small text-muted">ISS <?= $tarifa[0]["valorDeIvaSym"]; ?></li>

                                        <?php

                                        $precioTotalCarrito += $valorMostrar;
                                        if (isset($_SESSION['login']['idUsuario']) && $_SESSION['login']['idUsuario'] == 1) {

                                        }


                                    }

                                    if (isset($_SESSION["login"]["idUsuario"]) && $_SESSION["login"]["idUsuario"] == 1) {

                                        echo("<li>comision Vendedor: " . $_SESSION['moneda_sel_sym'] . number_format($tarifa[0]["comisionVendedor"], 0, ',', '.') . '</li>');

                                        echo("<li>comision Sistema: " . $_SESSION['moneda_sel_sym'] . number_format($tarifa[0]["comisionSistema"], 0, ',', '.') . '</li>');

                                    }

                                    ?>

                                    <li class="mt-2"><span class="badge badge-light border">Subtotal Reserva <?= $_SESSION['moneda_sel_sym'] . number_format($precioReserva, 0, ',', '.'); ?></span></li>

                                    <hr class="my-2">

                                    <?php

                                    if (isset($reservaAdicionales) && count($reservaAdicionales) > 0) {


                                        ?>

                                        <li class="mt-2"><i class="fas fa-plus-circle text-info mr-1"></i>Adicionales:</li>

                                        <?php

                                        for ($j = 0; $j < count($reservaAdicionales); $j++) {

                                            $idServicioSalidasAdicionales = $reservaAdicionales[$j]["idServicioSalidasAdicionales"];

                                            $servicioAdicional = getServicioAdicionalSalida($idServicioSalidasAdicionales);

                                            $cantidad = $reservaAdicionales[$j]["cantidad"];


                                            $valor = getValorServiciosAdicionalesSalida($idServicioSalidasAdicionales, $cantidad);


                                            $precioTotalCarrito += $valor[0]["valor"];

                                            ?>

                                            <li class="small"><i class="fas fa-plus mr-1"></i><?= $cantidad; ?> <?= $servicioAdicional[0]["nombre"] ?> (X PAX <?= $valor[0]["valorSymUnitario"]; ?>)</li>

                                            <?php
                                        }
                                    }

                                    ?>

                                    <hr>

                                    <?php

                                }

                                if ($totalDescuentos > 0) {
                                    ?>
                                    <li class="small text-success"><i class="fas fa-tags mr-1"></i><?= $lang["su_descuento"] ?><?= $_SESSION['moneda_sel_sym'] . $totalDescuentos; ?></li>
                                    <li class="small text-muted"><i class="fas fa-user-friends mr-1"></i><?= $lang["anfitrion"] ?><?= $_SESSION['cupon_descuento']['anfitrion']; ?></li>
                                    <?php
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
                // Asegurar que $descuentoGanado existe
                if (!isset($descuentoGanado)) {
                    $descuentoGanado = 0;
                }
                
                // Mostrar descuento por redondeo si existe (ARS, CLP, PYG)
                $mostrarDescuentoRedondeo = false;
                if (in_array($_SESSION['moneda_sel'], [270, 271, 225]) && $descuentoGanado > 0) {
                    $mostrarDescuentoRedondeo = true;
                }
                
                // Restar el descuento del total (el cliente paga menos gracias al "descuento")
                $totalFinal = $precioTotalCarrito - $descuentoGanado;
                ?>
                
                <?php if ($mostrarDescuentoRedondeo): ?>
                <!-- Descuento por Redondeo -->
                <div class="div-precio-t py-2 px-3 rounded shadow-sm mb-2" style="background: linear-gradient(135deg, #d4edda 0%, #e7f4ea 100%); border: 1px solid #28a745;">
                    <div class="row align-items-center">
                        <div class="col-7">
                            <p class="mb-0 small text-success"><i class="fas fa-gift mr-2"></i><strong>Descuento por Redondeo</strong></p>
                        </div>
                        <div class="col-5 text-right">
                            <p class="mb-0 text-success font-weight-bold">
                                -<?= $_SESSION['moneda_sel_sym'] . number_format($descuentoGanado, 0, ',', '.'); ?>
                            </p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
                <div class="div-precio-t py-3 px-3 rounded shadow-sm" style="margin-top: 15px; background: linear-gradient(135deg, #e7f3ff 0%, #f0f8ff 100%); border: 1px solid #029ce2;">
                    <div class="row align-items-center">
                        <div class="col-5">
                            <p class="mb-0 h6" style="color: #0066cc;"><strong><i class="fas fa-wallet mr-2"></i><?= $lang["total_carrito"] ?></strong></p>
                        </div>
                        <div class="col-7 text-right">
                            <h5 class="mb-0 bold" style="color: #029ce2; font-size: 24px; white-space: nowrap;">
                                <strong><?= $_SESSION['moneda_sel_sym'] . "" . number_format($totalFinal, 0, ',', '.'); ?></strong>
                            </h5>
                        </div>
                    </div>
                </div>
                <!--FIN PRECIO TOTAL-->

            </div>
        </div>
    </div>
</div>
