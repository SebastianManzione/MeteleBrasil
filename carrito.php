<?php

include("includes/headPagos.php");
include("admin/classes/salidas.php");
include("admin/classes/tarifas.php");
include("admin/classes/tarifas_ubicacion.php");
include("admin/classes/idiomas.php");
include("admin/classes/servicio.php");
include("admin/classes/comisiones.php");
include("admin/classes/edades.php");
include("admin/classes/cancelaciones.php");
include("admin/classes/servicios_adicionales.php");
include("admin/classes/convierte_monedas.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Suponiendo que el formulario envía la moneda seleccionada
    if (isset($_POST['moneda'])) {
        $_SESSION['monedaSel'] = $_POST['moneda']; // Guarda la moneda seleccionada
    }
    if (isset($_POST["eliminarPaquete"])) {
        $posicion = $_POST["eliminarPaquete"];
        unset ($_SESSION['reserva'][$posicion]);
        $_SESSION['reserva'] = array_values($_SESSION['reserva']);

        // Limpiar descuento AR$ cuando se modifica el carrito
        unset($_SESSION['descuento_ars_aceptado']);
        unset($_SESSION['descuento_ars_monto']);
        
        // Si el carrito quedó vacío, redirigir inmediatamente
        if (count($_SESSION['reserva']) < 1) {
            alertar("Seu carrinho está vazio", "info");
            redireccionar('index.php');
            exit();
        }
    }
    if (isset($_POST["eliminarActividad"])) {
        $posicion = $_POST["eliminarActividad"];
        unset ($_SESSION['reserva'][$posicion]);
        $_SESSION['reserva'] = array_values($_SESSION['reserva']);

        // Limpiar descuento AR$ cuando se modifica el carrito
        unset($_SESSION['descuento_ars_aceptado']);
        unset($_SESSION['descuento_ars_monto']);
        
        // Si el carrito quedó vacío, redirigir inmediatamente
        if (count($_SESSION['reserva']) < 1) {
            alertar("Seu carrinho está vazio", "info");
            redireccionar('index.php');
            exit();
        }
    }
}

$totalCarrito = 0;
$carrito = $_SESSION['reserva'];
$cantCarrito = count($carrito);

// Obtener código de cupón desde la sesión si existe
$codCupon = isset($_SESSION['codCupon']) ? $_SESSION['codCupon'] : '';

// Verificación final del carrito vacío (solo si no es POST, para evitar doble redirección)
if ($cantCarrito < 1 && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    alertar("Seu carrinho está vazio", "info");
    redireccionar('index.php');
    exit();
}
?>

<!--PASOS PARA RESERVA-->
<section class="py-2 bg-white">
    <div class="container">
        <div class="row">
            <div class="col-lg-12  ">
                <ul class="lista-pasos-form">
                    <li class="active"><a href="carrito" class="btn-pasos"><strong><span>1</span>
                                <b><?= $lang["revisa_tus_reservas"] ?></b></strong></a></li>
                    <li><span>2</span><?= $lang["datos_personales"] ?></li>
                    <li><span>3</span> <strong><?= $lang["metodo_de_pago"] ?></strong></li>
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
            <?php include('resumo_compra_carrinho.php'); ?>
            <!--FIN RESUMEN DE PEDIDO-->

            <!--DATOS PERSONALES-->
            <div class="col-lg-8 col-md-8">
                <?php
                require("admin/classes/fotos_servicio.php");
                $precioTotalCarrito = 0;
                for ($i = 0; $i < $cantCarrito; $i++) {
                    $reserva = $carrito[$i][0];
                    $idServicio = $reserva[0]['idServicioSeleccionado'];
                    $servicio = getServicio($reserva[0]['idServicioSeleccionado']);
                    $fotos = getFotoMiniaturaServicio($idServicio);
                    ?>
                    <div class="py-2">
                        <div class="card card-visitas shadow-sm position-relative">
                            <form method="post" action="carrito" class="btn-close-container" aria-label="<?= $lang["eliminar"] ?>">
                                <input type="hidden" name="eliminarActividad" value="<?= $i ?>">
                                <button type="submit" class="btn-close-win" title="<?= $lang["eliminar"] ?>">×</button>
                            </form>
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-4 col-4">
                                        <img src="admin/classes/imgServicio/<?= $fotos[0]['ruta']; ?>"
                                             class="img-fluid img-card-destinos rounded">
                                    </div>
                                    <div class="col-md-8 col-8">
                                        <div class="card-block">
                                            <h4 class="text-left titulo-card-destinos text-primary mb-3">
                                                <i class="fas fa-map-marker-alt mr-2"></i><?= $servicio[0]["nombre_servicio"]; ?>
                                            </h4>
                                            <div class="d-md-block">
                                                <div class="row no-gutters text-center">
                                                    <div class="col-lg-5 col-md-5">
                                                        <p class="mb-2">
                                                            <span class="badge badge-info badge-pill p-2">
                                                                <i class="fas fa-users mr-1"></i>
                                                                <?php
                                                                $cantidadPasajeros = 0;
                                                                for ($j = 0; $j < count($reserva); $j++) {
                                                                    $cantidadPasajeros += $reserva[$j]['cantidad'];
                                                                }
                                                                ?>
                                                                <?= $cantidadPasajeros; ?> <?= $cantidadPasajeros == 1 ? 'Pessoa' : 'Pessoas'; ?>
                                                            </span>
                                                        </p>


                                                        <?php
                                                        $precioReserva = 0;
                                                        $cantidadPasajeros = 0;
                                                        $tarifasResumen = [];
                                                        for ($j = 0; $j < count($reserva); $j++) {
                                                            $idServicioSalidasTarifas = $reserva[$j]['idServicioSalidasTarifas'];
                                                            $cantidad = $reserva[$j]['cantidad'];
                                                            $cantidadPasajeros += $cantidad;
                                                            $tarifa = calculaTarifa($idServicioSalidasTarifas, $cantidad);
                                                            $salida = getSalida($tarifa[0]['idServicioSalidas']);
                                                            $idiomas = getIdiomaSalida($salida[0]['idServicioSalidas']);
                                                            error_log("CARRITO DEBUG - Tarifa: $idServicioSalidasTarifas | Cant: $cantidad | Valor retornado: {$tarifa[0]['valor']} | Diferencia: {$tarifa[0]['redondeoDiferencia']}");
                                                            $precioReserva += $tarifa[0]["valor"];
                                                            $fecha = strtotime($salida[0]['fecha']);
                                                            require("admin/classes/locale.php");
                                                            if (!isset($tarifasResumen[$idServicioSalidasTarifas])) {
                                                                $tarifasResumen[$idServicioSalidasTarifas] = [
                                                                    'nombre' => $tarifa[0]['nombre'],
                                                                    'edadFrom' => $tarifa[0]['edadFrom'],
                                                                    'edadTo' => $tarifa[0]['edadTo'],
                                                                    'cantidad' => 0
                                                                ];
                                                            }
                                                            $tarifasResumen[$idServicioSalidasTarifas]['cantidad'] += $cantidad;
                                                            $precioTotalCarrito += $tarifa[0]["valor"];
                                                        }
                                                        ?>
                                                        <?php foreach ($tarifasResumen as $t) { ?>
                                                            <li class="text-muted"><small><i class="fas fa-ticket-alt mr-1"></i><?= $t['cantidad'] . ' ' . $t['nombre'] . ' (' . $t['edadFrom'] . ' a ' . $t['edadTo'] . ' Anos)' ?></small></li>
                                                        <?php } ?>

                                                    </div>


                                                    <div class="col-lg-3 col-md-3">
                                                        <p class="mb-2">
                                                            <span class="badge badge-secondary badge-pill p-2">
                                                                <i class="fas fa-language mr-1"></i> Idiomas
                                                            </span>
                                                        </p>
                                                        <ul class="list-unstyled small text-muted">
                                                            <?php for ($m = 0; $m < count($idiomas); $m++) { ?>
                                                                <li><i class="fas fa-check-circle text-success mr-1"></i><?= $idiomas[$m]; ?></li>
                                                            <?php } ?>
                                                        </ul>
                                                    </div>

                                                    <div class="col-lg-2 col-md-2">
                                                        <p class="mb-2">
                                                            <span class="badge badge-primary badge-pill p-2">
                                                                <i class="fas fa-calendar-alt mr-1"></i><?= date("d", strtotime($salida[0]['fecha'])); ?>
                                                            </span>
                                                        </p>
                                                        <p class="mb-0 small text-muted">
                                                            <?php echo(strftime("%B", $fecha)); ?>
                                                            <?= date("Y", strtotime($salida[0]['fecha'])); ?>

                                                        </p>

                                                    </div>

                                                    <div class="col-lg-2 col-md-2">
                                                        <p class="mb-2">
                                                            <span class="badge badge-success badge-pill p-2">
                                                                <i class="fas fa-clock mr-1"></i> Horário
                                                            </span>
                                                        </p>
                                                        <p class="mb-0 small text-muted">
                                                            <?= $salida[0]['horaCheckIn']; ?>
                                                        </p>

                                                    </div>

                                                </div>

                                            </div>


                                        </div>

                                    </div>

                                </div>
                                
                            </div>

                        </div>

                    </div>

                    <?php


                }
                ?>

                <!--DATOS DE ACTIVIDADES -->
                <!--FIN DATOS DE ACTIVIDADES-->
                <!--FIN DATOS PERSONALES-->

                <script>

                    function carga() {

                        $('#body').css('display', 'none');

                        $('#bodyCarga').html('<div class="d-flex justify-content-center" style="margin-top: 15em;">  <div class="spinner-border" role="status">    <span class="sr-only">Loading...</span>  </div></div>');

                    }

                  /*  function enviaa() {


                        chkCondiciones = $('#customControlAutosizing').prop('checked');

                        datos = Array();

                        datos[0] = '<?= $horarioId; ?>';

                        datos[1] = '<?= $cantidadAdul; ?>';

                        datos[2] = '<?= $cantidad12; ?>';

                        datos[3] = '<?= $cantidad5; ?>';

                        datos[4] = '<?= $cantidad3; ?>';

                        datos[5] = '<?= $codCupon; ?>';

                        datos[6] = '<?= $tmp; ?>';


                        datos[7] = Array();

                        datos[7][0] = document.getElementById("txtNombre").value;

                        datos[7][1] = document.getElementById("txtApellido").value;

                        datos[7][2] = document.getElementById("txtPrefijo").value;

                        datos[7][3] = document.getElementById("txtTelefono").value;

                        datos[7][4] = document.getElementById("txtEmail").value;

                        datos[8] = '<?= $money?>';


                        if (chkCondiciones) {

                            if (datos[7][0] == "") {

                                alert("Todos los campos deben estar completos");

                            } else {

                                contador = 0;

                                if (contador == 0) {

                                    contador += 1;

                                    $.post('ctrlReserva.php', {

                                        data: {'datos': JSON.stringify(datos)}

                                    }, function (response) {

                                        if (response > 1) {

                                            alert("Reserva guardada con exito con el id: " + response);

                                            window.location = "metodo-pago.php?idReserva=" + response;

                                        }

                                    });

                                }

                            }


                        } else {

                            alert("Aceptar la política de privacidad  y las condiciones generales es una obligación legal.");

                        }


                    } */


                </script>

                <!--BOTON SIGUIENTE-->

                <div class="container mt-4 mb-5">

                    <div class="row">

                        <div class="col-lg-6 col-md-6 col-12 mb-2 mb-md-0">
                            <a href="servicios.php" class="btn btn-outline-secondary btn-lg btn-radius shadow-sm px-4 py-3"
                               style="width: 100% !important;">
                                <i class="fas fa-shopping-bag mr-2"></i>Seguir comprando
                            </a>
                        </div>

                        <div class="col-lg-6 col-md-6 col-12 text-right">

                                     <a href="datosPersonales.php" class="btn btn-primary btn-lg btn-radius shadow-sm px-4 py-3"
                                         style="width: 100% !important;" onclick="carga();"><?= $lang["continuar"] ?> <i class="fas fa-arrow-right ml-2"></i></a>

                        </div>

                    </div>

                </div>

                <!--FIN BOTON SIGUIENTE-->


</section>

<!--FIN SECCION DATOS PERSONALES-->


<!-- Footer -->

<footer class="footer footer-reserva ">

    <div class="container">

        <div class="row">

            <div class="col-lg-4"></div>

            <div class="col-lg-2">

                <p class="text-gris text-pagos"><i class="fa fa-lock mx-2 "></i> PAGO SEGURO</p>

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

                <h4 class="text-left">
                    <small><span>METELE BRASIL</span><?= $lang["es_una_marca_registrada_de_reservate_sl"] ?></small>
                </h4>

            </div>

        </div>

    </div>

</section>


<!-- MODAL POLITICA DE PRIVACIDAD-->

<div class="modal fade" id="politicas" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">

    <div class="modal-dialog" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <h4 class="text-center"><?= $lang["politica_de_privacidad"] ?></h4>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>

            <div class="modal-body">

                <div class="container">

                    <div class="row">

                        <div class="col-lg-12">

                            <p><?= $lang["en_metelebrasil_queremos_ser_transparente"] ?></p>

                            <p><?= $lang["les_informamos_que_todos_los_datos"] ?></p>

                            <p><?= $lang["asimismo_para_ofrecerles"] ?></p>

                            <p><?= $lang["tenes_derecho_a_presentar_una_reclamacion_ante"] ?></p>

                            <p class="text-center"><a href="#"
                                                      class="btn btn-primary btn-radius"><?= $lang["leer_mas"] ?></a>
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- FIN MODAL POLITICA DE PRIVACIDAD-->


<!-- BOTON SUBIR-->

<div class="scroll-to-top  position-fixed ">

    <a class="js-scroll-trigger d-block text-center text-white rounded" href="#page-top">

        <i class="fa fa-chevron-up"></i>

    </a>

</div>

<!-- FIN BOTON SUBIR-->


<!-- SCRIPTS NECESARIOS-->


<!-- JQUERY-->

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

<!-- JQUERY-->


<!-- UNDERSCORE-->

<script src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js"></script>

<!-- UNDERSCORE-->


<!-- MOMENT -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment.min.js"></script>

<!-- MOMENT -->


<!-- WOW ANIMACION -->

<script src="js/wow.min.js"></script>

<!-- WOW ANIMACION -->


<!-- BOOTSTRAP BUNDLE -->

<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- BOOTSTRAP BUNDLE -->


<!-- JQUERY EASING -->

<script src="vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- JQUERY EASING -->


<!-- CUSTOM -->

<script src="js/scriptcarrito.js"></script>

<!-- CUSTOM -->


<!-- FIN SCRIPTS NECESARIOS-->

</div>

</body>


</html>

