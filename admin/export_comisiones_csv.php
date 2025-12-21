<?php
// Archivo separado para exportación CSV de comisiones
// SOLUCIÓN SIMPLE: generar archivo temporal y servirlo

// LIMPIAR cualquier output anterior que pueda estar causando líneas vacías
ob_clean();
ob_start();

session_start();

// Asegurar que tengamos las variables de sesión necesarias
if (!isset($_SESSION["moneda_sel"])) {
    $_SESSION["moneda_sel"] = 1;
}
if (!isset($_SESSION["moneda_sel_sym"])) {
    $_SESSION["moneda_sel_sym"] = "$";
}

// Solo incluir las clases necesarias
require("classes/conexion.php");
require("classes/convierte_monedas.php");
require("classes/functions.php");
require("classes/comision_prestador.php");
require("classes/servicio.php");
require("classes/usuario.php");
require("classes/reserva.php");

// Obtener los mismos datos que en comisionesLista.php
$usuarios = getUsuariosVendedores();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["verComoUsuario"])) {
    $idUsuarioSeleccionado = $_POST['idUsuarioSeleccionado'];
    $comisiones = getReservasConfirmadasIdUsuarioCupon($_POST['idUsuarioSeleccionado']);
} else if ($_SESSION["login"]["rol"] == 1 && (!isset($_POST["verComoUsuario"]) || !$_SERVER["REQUEST_METHOD"] == "POST")) {
    $comisiones = getReservasConfirmadasIdUsuarioCupon(-5);
} else {
    $comisiones = getReservasConfirmadasIdUsuarioCupon($_SESSION['login']['idUsuario']);
}

// SOLUCIÓN SIMPLE: generar archivo temporal y servirlo
$tempFile = tempnam(sys_get_temp_dir(), 'comisiones_') . '.csv';

// Generar el contenido CSV
$csvContent = "Cod.Reserva,\"Código Voucher Servicio\",Nombre,Fechas,\"Responsable de Reserva\",\"Monto Total sin IVA\",\"Tipo de Tarifa\",\"Monto por Registro\",Comisión\n";

$filasEscritas = 0;

foreach ($comisiones as $key => $value) {
    if (empty($value['codigoAmigable'])) continue;

    $horarios = getReservaHorariosInnerHorarios($value['idReserva']);
    if (empty($horarios)) continue;

    foreach ($horarios as $valor) {
        if (empty($valor['idServicioSeleccionado'])) continue;

        $reservaTarifas = getReservaTarifas($valor['idReservaHorarios']);
        if (empty($reservaTarifas)) continue;

        $servicio = getServicio($valor['idServicioSeleccionado']);
        if (empty($servicio) || !isset($servicio[0]["nombre_servicio"])) continue;

        foreach ($reservaTarifas as $valorTarifas) {
            $comisionIndividual = ConvierteMoneda($value['monedaSel'], $_SESSION["moneda_sel"], $valorTarifas['comisionVendedor']);

            // Tipo de tarifa (nombre de la tarifa desde servicio_salidas_tarifas)
            $tipoTarifa = $valorTarifas['nombreTarifa'] ?? $valorTarifas['nombre'] ?? 'Sin especificar';

            // Monto por registro (valorTarifa convertido)
            $montoPorRegistro = ConvierteMoneda($value['monedaSel'], $_SESSION["moneda_sel"], $valorTarifas['valorSinIva']);

            // Escapar comillas y comas para CSV
            $codReserva = str_replace('"', '""', $value["codigoAmigable"]);
            $codigoVoucherServicio = str_replace('"', '""', $valor["CodigoVoucherServicio"] ?? "N/A");
            $nombreServicio = str_replace('"', '""', $servicio[0]["nombre_servicio"]);
            $fecha = $value["fechaAlta"] ?? "N/A";
            // Usar la misma lógica que la tabla HTML para el responsable
            $responsable = str_replace('"', '""', ($value["nombreResponsable"] ?? "N/A") . ' ' . ($value["apellidoResponsable"] ?? ""));

            // Formatear montos (igual que la tabla HTML)
            $montoSinIVA = $_SESSION["moneda_sel_sym"] . (($value['total'] ?? 0) - ($value['impuestos'] ?? 0));
            $montoPorRegistroFormateado = $_SESSION["moneda_sel_sym"] . number_format($montoPorRegistro, 2);
            $comisionFormateada = $_SESSION["moneda_sel_sym"] . number_format($comisionIndividual, 2);

            // Si contiene comas, envolver en comillas
            if (strpos($codigoVoucherServicio, ',') !== false) $codigoVoucherServicio = '"' . $codigoVoucherServicio . '"';
            if (strpos($nombreServicio, ',') !== false) $nombreServicio = '"' . $nombreServicio . '"';
            if (strpos($responsable, ',') !== false) $responsable = '"' . $responsable . '"';
            if (strpos($tipoTarifa, ',') !== false) $tipoTarifa = '"' . $tipoTarifa . '"';

            $csvContent .= $codReserva . ',' . $codigoVoucherServicio . ',' . $nombreServicio . ',' . $fecha . ',' . $responsable . ',' . $montoSinIVA . ',' . $tipoTarifa . ',' . $montoPorRegistroFormateado . ',' . $comisionFormateada . "\n";
            $filasEscritas++;
        }
    }
}

$csvContent .= "\nTotal de filas exportadas: " . $filasEscritas . "\n";

// LIMPIAR: eliminar líneas vacías al inicio y final
$csvContent = trim($csvContent);
$lines = explode("\n", $csvContent);
$cleanLines = array_filter($lines, function($line) {
    return trim($line) !== '';
});
$csvContent = implode("\n", $cleanLines);

// Si hay contenido, agregar una línea final
if (!empty($csvContent)) {
    $csvContent .= "\n";
}

// Escribir al archivo temporal
file_put_contents($tempFile, $csvContent);

// LIMPIAR cualquier output residual
$content = ob_get_clean();

// Servir el archivo
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=comisiones_' . date('Y-m-d_H-i-s') . '.csv');
header('Content-Length: ' . filesize($tempFile));

readfile($tempFile);

// Eliminar archivo temporal
unlink($tempFile);
exit();
?>
