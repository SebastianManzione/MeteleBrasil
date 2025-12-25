<?php 
include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");
require("classes/functions.php");
require("classes/prestador.php");
require("classes/usuario.php");
require("classes/reserva.php");
require("classes/salidas.php");
require("classes/categoria.php");
require("classes/servicio.php");
require("classes/comprobantes.php");
require("classes/convierte_monedas.php");
require("classes/origenes_comprobantes.php");

// Función para obtener idServicioSalidasTarifas
function getIdServicioSalidasTarifas($codigoAmigable, $idServicio) {
    require("classes/conexion.php");

    $data = ["codigoAmigable" => $codigoAmigable, "idServicio" => $idServicio];

    $consulta = "SELECT rt.idServicioSalidasTarifas
                FROM reserva_tarifas rt
                INNER JOIN reserva_horarios rh ON rh.idReservaHorarios = rt.idReservaHorarios
                INNER JOIN reservas r ON r.idReserva = rh.idReserva
                WHERE r.codigoAmigable = :codigoAmigable
                AND rh.idServicioSeleccionado = :idServicio
                LIMIT 1";

    $comando = $pdo->prepare($consulta);
    $comando->execute($data);

    $resultado = $comando->fetch(PDO::FETCH_ASSOC);

    return $resultado ? $resultado['idServicioSalidasTarifas'] : null;
}

// Función para obtener valor de comisión por tipo
function getValorComision($idServicioSalidasTarifas, $idComision) {
    require("classes/conexion.php");

    $data = ["idServicioSalidasTarifas" => $idServicioSalidasTarifas, "idComision" => $idComision];

    $consulta = "SELECT valor
                FROM servicio_tarifas_comision
                WHERE idServicioSalidasTarifas = :idServicioSalidasTarifas
                AND idComision = :idComision
                LIMIT 1";

    $comando = $pdo->prepare($consulta);
    $comando->execute($data);

    $resultado = $comando->fetch(PDO::FETCH_ASSOC);

    return $resultado ? floatval($resultado['valor']) : 0;
}

// Función para obtener comisiones desde reserva_tarifas
function getComisionesReservaTarifas($codigoAmigable, $idServicio) {
    require("classes/conexion.php");

    $data = ["codigoAmigable" => $codigoAmigable, "idServicio" => $idServicio];

    $consulta = "SELECT rt.comisionVendedor, rt.comisionSistema
                FROM reserva_tarifas rt
                INNER JOIN reserva_horarios rh ON rh.idReservaHorarios = rt.idReservaHorarios
                INNER JOIN reservas r ON r.idReserva = rh.idReserva
                WHERE r.codigoAmigable = :codigoAmigable
                AND rh.idServicioSeleccionado = :idServicio
                LIMIT 1";

    $comando = $pdo->prepare($consulta);
    $comando->execute($data);

    $resultado = $comando->fetch(PDO::FETCH_ASSOC);

    if ($resultado) {
        // Los valores ya están en la base de datos como montos, no como porcentajes
        return [
            'comisionVendedor' => floatval($resultado['comisionVendedor'] ?? 0),
            'comisionSistema' => floatval($resultado['comisionSistema'] ?? 0)
        ];
    }

    return ['comisionVendedor' => 0, 'comisionSistema' => 0];
}

// Detectar si es Admin o Prestador y obtener idPrestador
$vistaAdmin = ($_SESSION['login']['idUsuario'] == 1);
$idPrestador = null;

if ($vistaAdmin && isset($_GET['idPrestador'])) {
    // Admin viendo un prestador específico
    $idPrestador = (int)$_GET['idPrestador'];
} elseif (!$vistaAdmin && isset($_SESSION['login']['idPrestador']) && $_SESSION['login']['idPrestador'] > 0) {
    // Prestador viendo sus propios datos
    $idPrestador = $_SESSION['login']['idPrestador'];
} else {
    // Si no es admin ni tiene idPrestador, no tiene acceso
    alertar("No tiene acceso a esta sección", "error");
    redireccionarLento("index");
    exit();
}

// Obtener todos los servicios de una vez
$allServicios = getAllServicios(); // Debe devolver array con idServicio => nombre_servicio
$serviciosMap = [];
foreach ($allServicios as $s) {
    $serviciosMap[$s['idServicio']] = $s['nombre_servicio'];
}
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark"><?=$lang["comprobantes_de_pagos"];?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#"><?=$lang["administracion"];?></a></li>
                        <li class="breadcrumb-item active"><?=$lang["comprobantes"];?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title"><?=$lang["comprobantes"];?></h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                    </div>
                </div>

                <?php if ($vistaAdmin): ?>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label>Filtrar por Prestador:</label>
                            <select class="form-control" id="filtroPrestador" onchange="window.location.href='financieroSalidas.php?idPrestador=' + this.value">
                                <option value="">Todos los prestadores</option>
                                <?php 
                                $prestadores = getPrestadores();
                                foreach ($prestadores as $prest) {
                                    $selected = ($idPrestador == $prest['idPrestador']) ? 'selected' : '';
                                    echo "<option value='{$prest['idPrestador']}' {$selected}>{$prest['nombre']} (ID: {$prest['idPrestador']})</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover" id="tablaCarrito" style="width: 100%;">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th scope="col"><?=$lang["fecha"];?></th>
                                            <th scope="col"><?=$lang["cod-carrito"];?></th>
                                            <th scope="col">Responsável</th>
                                            <th scope="col">Serviço</th>
                                            <th scope="col">Data compra</th>
                                            <th scope="col">Período</th>
                                            <th scope="col">Valor referente de Comissão</th>
                                            <th scope="col"><?=$lang["valor_pago"];?></th>
                                            <th scope="col"><?=$lang["origen"];?></th>
                                            <th scope="col"><?=$lang["codigo_pasarela"];?></th>
                                            <th scope="col">Valor do Serviço (com imposto no site)</th>
                                            <th scope="col">Imposto %</th>
                                            <th scope="col">Valor do imposto R$</th>
                                            <th scope="col">Comissão Total %</th>
                                            <th scope="col">% de Desconto</th>
                                            <th scope="col">Valor de Desconto R$</th>
                                            <th scope="col">Cupom de Desconto</th>
                                            <th scope="col">Vendedor</th>
                                            <th scope="col">Comissão Vendedor %</th>
                                            <th scope="col">Comissão Vendedor R$</th>
                                            <th scope="col">Valor pagar Vendedor</th>
                                            <th scope="col">Fornecedor</th>
                                            <th scope="col">Valor pagar Fornecedor</th>
                                            <th scope="col">Comissão do Sistema %</th>
                                            <th scope="col">Comissão Sistema R$</th>
                                            <th scope="col">Ganância do Sistema (final com desconto)</th>
                                            <th scope="col">Ganância Sistema + Comissão Vendedor</th>
                                            <th scope="col">Desconto Mercado Pago e Outros</th>
                                            <th scope="col">Ganância Sistema Final (Líquido)</th>
                                            <th scope="col"><?=$lang["detalles"];?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $comprobantes = getComprobantes();

                                        foreach ($comprobantes as $comprobante) { 
                                            $idReserva = $comprobante["idReserva"];
                                            $reserva = getReservaId($idReserva);

                                            // Valores por defecto
                                            $codigoAmigable = "RESERVA ELIMINADA";
                                            $fecha_reserva = "RESERVA ELIMINADA";
                                            $nombreResponsable = "";
                                            $total = 0;
                                            $precio = 0;
                                            $servicioNombre = "-";

                                            if (!empty($reserva)) {
                                                $codigoAmigable = $reserva[0]["codigoAmigable"];
                                                $codigoAmigableRow = $reserva[0]["codigoAmigable"];

                                                $fecha_reserva = date("d-m-Y", strtotime($reserva[0]["fechaAlta"]));
                                                $total = $reserva[0]["total"];
                                                $precio = ConvierteMoneda($reserva[0]["monedaSel"], $_SESSION["moneda_sel"], $total);
                                                $nombreResponsable = $reserva[0]["nombreResponsable"] . " " . $reserva[0]["apellidoResponsable"];
                                                
                                                // Obtener todos los servicios de la reserva
                                                $serviciosData = getReservaServicios($codigoAmigable);
                                                if (!empty($serviciosData)) {
                                                    // Si hay filtro de prestador, verificar si algún servicio pertenece al prestador
                                                    if ($idPrestador !== null) {
                                                        $perteneceAlPrestador = false;
                                                        foreach ($serviciosData as $svc) {
                                                            $servicioCompleto = getServicio($svc['idServicio'] ?? null);
                                                            if (!empty($servicioCompleto)) {
                                                                $salidaServicio = getSalida($svc['idServicioSalidas'] ?? null);
                                                                if (!empty($salidaServicio) && $salidaServicio[0]['idPrestador'] == $idPrestador) {
                                                                    $perteneceAlPrestador = true;
                                                                    break;
                                                                }
                                                            }
                                                        }
                                                        // Si no pertenece al prestador filtrado, saltar esta reserva
                                                        if (!$perteneceAlPrestador) {
                                                            continue;
                                                        }
                                                    }
                                                    
                                                    // Crear una fila por cada servicio
                                                    foreach ($serviciosData as $servicio) {
                                                        // Generar fila para este servicio
                                                        $fechaIngreso = date("d-m-Y", strtotime($comprobante["fechaIngreso"]));
                                                        $idMonedaOrigen = $comprobante["monedaComprobante"];
                                                        $totalComprobanteMonedaOrigen = $comprobante["total"];
                                                        $monedaComprobante = getMoneda($idMonedaOrigen);
                                                        $origenComprobante = getOrigenComprobante($comprobante["origenComprobante"])[0]["nombre"];

                                                        $totalComprobantesPagados = getComprobantesIdReserva($idReserva);
                                                        $diferenciaComprobantesPrecio = $precio - $totalComprobantesPagados;

                                                        $claseBoton = "btn btn-warning btn-sm";
                                                        $textoBoton = "Pendente";
                                                        if ($diferenciaComprobantesPrecio < 1 && $precio > 0) {
                                                            $claseBoton = "btn btn-success btn-sm";
                                                            $textoBoton = "Confirmada";
                                                        }

                                                        // Obtener comisiones específicas del servicio desde la base de datos
                                                        $comisionVendedorPorcentaje = $servicio['comisionVendedor'] ?? 0;
                                                        $comisionSistemaPorcentaje = $servicio['comisionSistema'] ?? 0;

                                                        // Obtener valores monetarios de las comisiones desde reserva_tarifas
                                                        $comisionesData = getComisionesReservaTarifas($codigoAmigableRow, $servicio['idServicio'] ?? null);
                                                        $comisionVendedorCalculada = $comisionesData['comisionVendedor'];
                                                        $comisionSistemaCalculada = $comisionesData['comisionSistema'];
                                                        
                                                        // Si aún están en cero, usar los porcentajes como fallback
                                                        if (!$comisionVendedorCalculada && $comisionVendedorPorcentaje > 0) {
                                                            $comisionVendedorCalculada = ($precio * $comisionVendedorPorcentaje) / 100;
                                                        }
                                                        
                                                        if (!$comisionSistemaCalculada && $comisionSistemaPorcentaje > 0) {
                                                            $comisionSistemaCalculada = ($precio * $comisionSistemaPorcentaje) / 100;
                                                        }
                                                        
                                                        // Calcular valores de ganancia
                                                        $descuentoValor = $reserva[0]["descuento_valor"] ?? 0;
                                                        $impuestoValor = $comprobante["impuesto_valor"] ?? 0;
                                                        
                                                        // Ganancia del Sistema = Solo la Comisión del Sistema
                                                        $gananciaDelSistema = $comisionSistemaCalculada;
                                                        
                                                        // Ganancia del Sistema + Comisión del Vendedor
                                                        $gananciaDelSistemaVendedor = $gananciaDelSistema + $comisionVendedorCalculada;
                                                        
                                                        // Descuento Mercado Pago y Otros
                                                        $descuentoMercadoPago = $impuestoValor;
                                                        
                                                        // Ganancia Final Líquida = Ganancia del Sistema - Descuentos de pasarelas
                                                        $gananciaFinalLiquida = $gananciaDelSistema - $descuentoMercadoPago;

                                                        $fecha_reservaRow = date("d-m-Y", strtotime($reserva[0]["fechaAlta"]));
                                                        $nombreResponsableRow = $reserva[0]["nombreResponsable"] . " " . $reserva[0]["apellidoResponsable"];

                                                        $trs = '
                                                            <tr class="table-light">
                                                                <td>'.$nombreResponsableRow.'</td>
                                                                <td>'.$fecha_reservaRow.'</td>
                                                                <td>'.$_SESSION["moneda_sel_sym"].number_format($precio, 2).'</td>
                                                                <td>'.$_SESSION["moneda_sel_sym"].number_format($diferenciaComprobantesPrecio, 2).'</td>
                                                                <td><span class="'.$claseBoton.'">'.$textoBoton.'</span></td>
                                                                <td>
                                                                    <form method="post" action="carritoDetalles" style="display: inline;">
                                                                        <button class="btn btn-success btn-sm" name="detallesCarrito" value="'.$idReserva.'">
                                                                            <i class="fas fa-shopping-cart"></i> Ver carrinho
                                                                        </button>
                                                                    </form>
                                                                </td>
                                                            </tr>';

                                                        $dataChildValue = '
                                                            <div class="p-3 bg-light" style="border-left: 5px solid #007bff;">
                                                                <h5 class="m-0 text-dark font-weight-bold mb-3">Detalhes da reserva</h5>
                                                                <div class="table-responsive">
                                                                    <table class="table table-sm table-bordered table-hover">
                                                                        <thead class="thead-light">
                                                                            <tr>
                                                                                <th scope="col">Nome responsável</th>
                                                                                <th scope="col">Data de contratação</th>
                                                                                <th scope="col">Total</th>
                                                                                <th scope="col">Falta pagar</th>
                                                                                <th scope="col">Estado</th>
                                                                                <th scope="col">Ação</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            '.$trs.'
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>';
                                                        ?>

                                                        <tr data-child-value="<?= htmlspecialchars($dataChildValue, ENT_QUOTES, 'UTF-8'); ?>">
                                                            <td><?= $fechaIngreso; ?></td>
                                                            <td><?= $codigoAmigableRow; ?></td>
                                                            <td><?= $nombreResponsableRow; ?></td>
                                                            <td><?= $servicio['nombre_servicio'] ?? '-'; ?></td>
                                                            <td><?= $fecha_reservaRow; ?></td>
                                                            <td><?= $servicio['periodo'] ?? '-'; ?></td>
                                                            <td><?= $precio == 0 ? $_SESSION["moneda_sel_sym"] . number_format(0, 2) : $_SESSION["moneda_sel_sym"] . number_format($comisionVendedorCalculada, 2); ?></td>
                                                            <td><?= $_SESSION["moneda_sel_sym"] . number_format($precio, 2); ?></td>
                                                            <td><?= $origenComprobante; ?></td>
                                                            <td><?= $comprobante["compOrigen"]; ?></td>
                                                            <td><?= $_SESSION["moneda_sel_sym"] . number_format($totalComprobanteMonedaOrigen, 2); ?></td>
                                                            <td><?= $comprobante["impuesto_porcentaje"] ?? 0; ?>%</td>
                                                            <td><?= $_SESSION["moneda_sel_sym"] . number_format($comprobante["impuesto_valor"] ?? 0, 2); ?></td>
                                                            <td><?= $reserva[0]["comision_total_porcentaje"] ?? 0; ?>%</td>
                                                            <td><?= $reserva[0]["descuento_porcentaje"] ?? 0; ?>%</td>
                                                            <td><?= $_SESSION["moneda_sel_sym"] . number_format($reserva[0]["descuento_valor"] ?? 0, 2); ?></td>
                                                            <td><?= $reserva[0]["idCuponDescuento"] ?? "-"; ?></td>
                                                            <td><?= $reserva[0]["vendedor"] ?? "-"; ?></td>
                                                            <td><?= $comisionVendedorPorcentaje; ?>%</td>
                                                            <td><?= $_SESSION["moneda_sel_sym"] . number_format($comisionVendedorCalculada, 2); ?></td>
                                                            <td><?= $_SESSION["moneda_sel_sym"] . number_format($reserva[0]["valor_pagar_vendedor"] ?? 0, 2); ?></td>
                                                            <td><?= $reserva[0]["idPrestador"] ?? "-"; ?></td>
                                                            <td><?= $_SESSION["moneda_sel_sym"] . number_format($reserva[0]["valor_pagar_proveedor"] ?? 0, 2); ?></td>
                                                            <td><?= $comisionSistemaPorcentaje; ?>%</td>
                                                            <td><?= $_SESSION["moneda_sel_sym"] . number_format($comisionSistemaCalculada, 2); ?></td>
                                                            <td><?= $_SESSION["moneda_sel_sym"] . number_format($gananciaDelSistema, 2); ?></td>
                                                            <td><?= $_SESSION["moneda_sel_sym"] . number_format($gananciaDelSistemaVendedor, 2); ?></td>
                                                            <td><?= $_SESSION["moneda_sel_sym"] . number_format($descuentoMercadoPago, 2); ?></td>
                                                            <td><?= $_SESSION["moneda_sel_sym"] . number_format($gananciaFinalLiquida, 2); ?></td>
                                                            <td class="details-control" style="cursor: pointer; text-align: center;"><i class="fas fa-plus-circle text-primary fa-lg"></i></td>
                                                        </tr>

                                                        <?php
                                                    }
                                                } else {
                                                    // Si no hay servicios, crear fila vacía
                                                    $servicioVacio = [
                                                        'nombre_servicio' => '-',
                                                        'periodo' => '-',
                                                        'comisionVendedor' => 0,
                                                        'comisionSistema' => 0
                                                    ];

                                                    // Valores por defecto cuando no hay servicios
                                                    $comisionVendedorPorcentaje = 0;
                                                    $comisionSistemaPorcentaje = 0;
                                                    $comisionVendedorCalculada = 0;
                                                    $comisionSistemaCalculada = 0;
                                                    
                                                    // Calcular ganancias (sin servicios)
                                                    $descuentoValor = $reserva[0]["descuento_valor"] ?? 0;
                                                    $impuestoValor = $comprobante["impuesto_valor"] ?? 0;
                                                    $gananciaDelSistema = $comisionSistemaCalculada;
                                                    $gananciaDelSistemaVendedor = $gananciaDelSistema + $comisionVendedorCalculada;
                                                    $descuentoMercadoPago = $impuestoValor;
                                                    $gananciaFinalLiquida = $gananciaDelSistema - $descuentoMercadoPago;

                                                    $fechaIngreso = date("d-m-Y", strtotime($comprobante["fechaIngreso"]));
                                                    $idMonedaOrigen = $comprobante["monedaComprobante"];
                                                    $totalComprobanteMonedaOrigen = $comprobante["total"];
                                                    $monedaComprobante = getMoneda($idMonedaOrigen);
                                                    $origenComprobante = getOrigenComprobante($comprobante["origenComprobante"])[0]["nombre"];

                                                    $totalComprobantesPagados = getComprobantesIdReserva($idReserva);
                                                    $diferenciaComprobantesPrecio = $precio - $totalComprobantesPagados;

                                                    $claseBoton = "btn btn-warning btn-sm";
                                                    $textoBoton = "Pendente";
                                                    if ($diferenciaComprobantesPrecio < 1 && $precio > 0) {
                                                        $claseBoton = "btn btn-success btn-sm";
                                                        $textoBoton = "Confirmada";
                                                    }

                                                    $codigoAmigableRow = $reserva[0]["codigoAmigable"];
                                                    $fecha_reservaRow = date("d-m-Y", strtotime($reserva[0]["fechaAlta"]));
                                                    $nombreResponsableRow = $reserva[0]["nombreResponsable"] . " " . $reserva[0]["apellidoResponsable"];

                                                    $trs = '
                                                        <tr class="table-light">
                                                            <td>'.$nombreResponsableRow.'</td>
                                                            <td>'.$fecha_reservaRow.'</td>
                                                            <td>'.$_SESSION["moneda_sel_sym"].number_format($precio, 2).'</td>
                                                            <td>'.$_SESSION["moneda_sel_sym"].number_format($diferenciaComprobantesPrecio, 2).'</td>
                                                            <td><span class="'.$claseBoton.'">'.$textoBoton.'</span></td>
                                                            <td>
                                                                <form method="post" action="carritoDetalles" style="display: inline;">
                                                                    <button class="btn btn-success btn-sm" name="detallesCarrito" value="'.$idReserva.'">
                                                                        <i class="fas fa-shopping-cart"></i> Ver carrinho
                                                                    </button>
                                                                </form>
                                                            </td>
                                                        </tr>';

                                                    $dataChildValue = '
                                                        <div class="p-3 bg-light" style="border-left: 5px solid #007bff;">
                                                            <h5 class="m-0 text-dark font-weight-bold mb-3">Detalhes da reserva</h5>
                                                            <div class="table-responsive">
                                                                <table class="table table-sm table-bordered table-hover">
                                                                    <thead class="thead-light">
                                                                        <tr>
                                                                            <th scope="col">Nome responsável</th>
                                                                            <th scope="col">Data de contratação</th>
                                                                            <th scope="col">Total</th>
                                                                            <th scope="col">Falta pagar</th>
                                                                            <th scope="col">Estado</th>
                                                                            <th scope="col">Ação</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        '.$trs.'
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>';
                                                    ?>

                                                    <tr data-child-value="<?= htmlspecialchars($dataChildValue, ENT_QUOTES, 'UTF-8'); ?>">
                                                        <td><?= $fechaIngreso; ?></td>
                                                        <td><?= $codigoAmigableRow; ?></td>
                                                        <td><?= $nombreResponsableRow; ?></td>
                                                        <td><?= $servicioVacio['nombre_servicio'] ?? '-'; ?></td>
                                                        <td><?= $fecha_reservaRow; ?></td>
                                                        <td><?= $servicioVacio['periodo'] ?? '-'; ?></td>
                                                        <td><?= $precio == 0 ? $_SESSION["moneda_sel_sym"] . number_format(0, 2) : $_SESSION["moneda_sel_sym"] . number_format(0, 2); ?></td>
                                                        <td><?= $_SESSION["moneda_sel_sym"] . number_format($precio, 2); ?></td>
                                                        <td><?= $origenComprobante; ?></td>
                                                        <td><?= $comprobante["compOrigen"]; ?></td>
                                                        <td><?= $_SESSION["moneda_sel_sym"] . number_format($totalComprobanteMonedaOrigen, 2); ?></td>
                                                        <td><?= $comprobante["impuesto_porcentaje"] ?? 0; ?>%</td>
                                                        <td><?= $_SESSION["moneda_sel_sym"] . number_format($comprobante["impuesto_valor"] ?? 0, 2); ?></td>
                                                        <td><?= $reserva[0]["comision_total_porcentaje"] ?? 0; ?>%</td>
                                                        <td><?= $reserva[0]["descuento_porcentaje"] ?? 0; ?>%</td>
                                                        <td><?= $_SESSION["moneda_sel_sym"] . number_format($reserva[0]["descuento_valor"] ?? 0, 2); ?></td>
                                                        <td><?= $reserva[0]["idCuponDescuento"] ?? "-"; ?></td>
                                                        <td><?= $reserva[0]["vendedor"] ?? "-"; ?></td>
                                                        <td><?= $comisionVendedorPorcentaje; ?>%</td>
                                                        <td><?= $_SESSION["moneda_sel_sym"] . number_format($comisionVendedorCalculada, 2); ?></td>
                                                        <td><?= $_SESSION["moneda_sel_sym"] . number_format($reserva[0]["valor_pagar_vendedor"] ?? 0, 2); ?></td>
                                                        <td><?= $reserva[0]["idPrestador"] ?? "-"; ?></td>
                                                        <td><?= $_SESSION["moneda_sel_sym"] . number_format($reserva[0]["valor_pagar_proveedor"] ?? 0, 2); ?></td>
                                                        <td><?= $comisionSistemaPorcentaje; ?>%</td>
                                                        <td><?= $_SESSION["moneda_sel_sym"] . number_format($comisionSistemaCalculada, 2); ?></td>
                                                        <td><?= $_SESSION["moneda_sel_sym"] . number_format($gananciaDelSistema, 2); ?></td>
                                                        <td><?= $_SESSION["moneda_sel_sym"] . number_format($gananciaDelSistemaVendedor, 2); ?></td>
                                                        <td><?= $_SESSION["moneda_sel_sym"] . number_format($descuentoMercadoPago, 2); ?></td>
                                                        <td><?= $_SESSION["moneda_sel_sym"] . number_format($gananciaFinalLiquida, 2); ?></td>
                                                        <td class="details-control" style="cursor: pointer; text-align: center;"><i class="fas fa-plus-circle text-primary fa-lg"></i></td>
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
    </section>
</div>

<script type="text/javascript">
function format(value) {
    return value;
}

$(document).ready(function () {
    var table = $('#tablaCarrito').DataTable({
        "responsive": true,
        "autoWidth": false,
        "order": [[0, "desc"]],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
        }
    });

    $('#tablaCarrito').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = table.row(tr);
        var icon = $(this).find('i');

        if (row.child.isShown()) {
            $('div.p-3', row.child()).slideUp(function () {
                row.child.hide();
                tr.removeClass('shown');
                icon.removeClass('fa-minus-circle text-danger').addClass('fa-plus-circle text-primary');
            });
        } else {
            row.child(format(tr.data('child-value')), 'details-row').show();
            tr.addClass('shown');
            icon.removeClass('fa-plus-circle text-primary').addClass('fa-minus-circle text-danger');
            $('div.p-3', row.child()).hide().slideDown();
        }
    });
});
</script>