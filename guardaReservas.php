<?php
include("admin/classes/salidas.php");
include("admin/classes/tarifas.php");
include("admin/classes/tarifas_ubicacion.php");
include("admin/classes/idiomas.php");
include("admin/classes/servicio.php");
include("admin/classes/moneda.php");
include("admin/classes/convierte_monedas.php");
include("admin/classes/comisiones.php");
include("admin/classes/edades.php");
include("admin/classes/cancelaciones.php");
include("admin/classes/servicios_adicionales.php");
include("admin/classes/reserva.php");
include("admin/classes/prestador.php");
include("includes/headPagos.php");

  //  alertar($lang["estamos_guardando_su_reserva"], "success");







if ($_SERVER['REQUEST_METHOD'] == 'POST'){



$impuestos_pais=($_SESSION["impuestos_pais"]);

$idUsuario=1;

if (isset($_SESSION["login"]["idUsuario"])) {

  $idUsuario=$_SESSION["login"]["idUsuario"];

}



$reservas=$_POST["reservas"];

$monedaSel=$_SESSION["moneda_sel"];

$nombreResponsable=$_POST["txtNombreResponsable"];

$apellidoResponsable=$_POST["txtApellidoResponsable"];

$emailResponsable=$_POST["txtEmailResponsable"];

$telefonoResponsable=$_POST["txtTelefonoResponsable"];
$idCountry=$_POST["idCountry"];
$idioma=$_SESSION["idioma"];
$idCuponDescuento=0;
$idUsuarioCupon=0;
if (isset($_SESSION['cupon_descuento']['idCuponDescuento'])) {
 $idCuponDescuento=$_SESSION['cupon_descuento']['idCuponDescuento'];
 $idUsuarioCupon=$_SESSION["cupon_descuento"]["idUsuario"];
}



$reserva=altaReserva($idUsuario,$nombreResponsable, $apellidoResponsable, $emailResponsable, $idCountry, $telefonoResponsable, $monedaSel, $impuestos_pais, $idioma, $idCuponDescuento,$idUsuarioCupon);

$idReserva=$reserva["idReserva"];
$codigoAmigable=$reserva["codigoAmigable"];


if ($idReserva>1) {

//  echo "Alta reserva ok codigoAmigable: ".$codigoAmigable."<br>";

$cantidadPasajeros=0;
$precioTotalReserva=0;
$totalIvaReserva=0;
$cantidadPasajerosReserva=0;
$descuentoGanado=0; // Acumulador para descuento por redondeo (ARS, CLP, PYG)

// Usar $_SESSION["reserva"] que contiene los datos de la sesión (es más confiable)
$servicios=$_SESSION["reserva"] ?? [];

for ($i=0; $i < count($servicios); $i++) { 

    // Obtener datos del servicio/salida desde la primera tarifa
    $servicioData=$servicios[$i][0][0]; // Primera tarifa contiene datos del servicio
    $idServicioSalidasTarifas=$servicioData['idServicioSalidasTarifas'];
    $idServicioSeleccionado=$servicioData["idServicioSeleccionado"];
    
    // Comentario puede venir de POST o de sesión
    $comentario=$servicioData["comentario"] ?? '';
    if (isset($reservas[$i][0]["comentario"])) {
        $comentario=$reservas[$i][0]["comentario"];
    }
    
    // Obtener salida e información
    $tarifaBase=getTarifa($idServicioSalidasTarifas);
    if (empty($tarifaBase)) {
        error_log("ERROR: getTarifa retornó vacío para idServicioSalidasTarifas: $idServicioSalidasTarifas");
        continue;
    }
    
    $idServicioSalidas=$tarifaBase[0]['idServicioSalidas'];
    $salida=getSalida($idServicioSalidas)[0];
    
    // Obtener ubicación
    $ubicacion=getUbicacionIdTarifa($idServicioSalidasTarifas);
    $ubicacionDir=$ubicacion[0]["direccion"] ?? "Sin ubicación";
    $ubicacionLat=$ubicacion[0]["latitud"] ?? 0;
    $ubicacionLon=$ubicacion[0]["longitud"] ?? 0;

    // Crear registro de horarios (una sola vez por servicio)
    $altaReservaHorarios=altaReservaHorarios($idReserva, $idServicioSalidas, $idServicioSeleccionado, 0, 
                                              $salida["nombre"], $salida["fecha"], $salida["horaSalida"], 
                                              $salida["horaCheckIn"], $ubicacionDir, $ubicacionLat, $ubicacionLon, $comentario);
    
    if (empty($altaReservaHorarios)) {
        error_log("ERROR: altaReservaHorarios falló para idReserva: $idReserva");
        continue;
    }
    
    // Procesar cada tarifa/pasajero de este servicio
    $cantidadPasajerosPorServicio=0;
    
    for ($j=0; $j < count($servicios[$i][0]); $j++) { 
    
        $tarifaData=$servicios[$i][0][$j];
        $idServicioSalidasTarifas=$tarifaData['idServicioSalidasTarifas'];
        $cantidad=$tarifaData["cantidad"] ?? 1;
        
        // Calcular tarifa con cantidad
        $tarifa=calculaTarifa($idServicioSalidasTarifas, $cantidad);
        
        if (empty($tarifa) || !isset($tarifa[0])) {
            error_log("ERROR guardaReservas: calculaTarifa vacío para idServicioSalidasTarifas: $idServicioSalidasTarifas, cantidad: $cantidad");
            continue;
        }
        
        // Extraer datos de tarifa
        $valor=$tarifa[0]["valor"];
        $valorDeIva=$tarifa[0]["valorDeIva"];
        $valorSinIva=$tarifa[0]["valorSinIva"];
        $nombre=$tarifa[0]["nombre"];
        $idFromEdad=$tarifa[0]["idFromEdad"];
        $idToEdad=$tarifa[0]["idToEdad"];
        $comisionVendedor=$tarifa[0]["comisionVendedor"] ?? 0;
        $comisionSistema=$tarifa[0]["comisionSistema"] ?? 0;
        
        // Acumular descuento por redondeo (ARS, CLP, PYG)
        if (in_array($monedaSel, [270, 271, 225]) && isset($tarifa[0]["redondeoDiferencia"])) {
            $descuentoGanado += $tarifa[0]["redondeoDiferencia"];
        }
        
        // Acumular totales
        $cantidadPasajeros+=$cantidad;
        $cantidadPasajerosPorServicio+=$cantidad;
        $precioTotalReserva+=$valor;
        $totalIvaReserva+=$valorDeIva;
        
        // Guardar tarifa en BD
        $altaReservaTarifas=altaReservaTarifas($idServicioSalidasTarifas, $altaReservaHorarios, $cantidad, 
                                               $monedaSel, $valor, $valorSinIva, $valorDeIva, 
                                               $idFromEdad, $idToEdad, $comisionVendedor, $comisionSistema, $nombre);
        
        if (empty($altaReservaTarifas)) {
            error_log("ERROR guardaReservas: altaReservaTarifas falló para idReservaHorarios: $altaReservaHorarios");
            continue;
        }
        
        // Guardar pasajeros si existen en POST
        if (isset($_POST["pasajero"][$i][$j]) && is_array($_POST["pasajero"][$i][$j])) {
            $pasajerosPost=$_POST["pasajero"][$i][$j];
            
            for ($s=0; $s < count($pasajerosPost); $s++) { 
                if (isset($pasajerosPost[$s][0]) && isset($pasajerosPost[$s][1])) {
                    $nombrePasajero=$pasajerosPost[$s][0];
                    $apellidoPasajero=$pasajerosPost[$s][1];
                    
                    $reservaPasajero=altaReservaPasajero($altaReservaTarifas, $nombrePasajero, $apellidoPasajero);
                    
                    if (empty($reservaPasajero)) {
                        error_log("WARNING guardaReservas: altaReservaPasajero falló para $nombrePasajero $apellidoPasajero");
                    }
                }
            }
        }
    }
    
    // Actualizar cantidad de pasajeros en horarios (si se implementa en altaReservaHorarios)
    if ($cantidadPasajerosPorServicio > 0) {
        // Aquí podrías actualizar altaReservaHorarios con la cantidad real si necesario
    }
    
    // Restar disponibilidad
    if ($cantidadPasajerosPorServicio > 0) {
        $resultadoRestaDisponibilidad=restaDisponibilidadSalida($idServicioSalidas, $cantidadPasajerosPorServicio);
        if (!$resultadoRestaDisponibilidad) {
            error_log("WARNING guardaReservas: restaDisponibilidadSalida falló para idServicioSalidas: $idServicioSalidas");
        }
    }
    
    // Procesar servicios adicionales no incluidos
    $adicionales=$servicios[$i][1] ?? [];
    
    if (is_countable($adicionales) && count($adicionales) > 0) {
        for ($j=0; $j < count($adicionales); $j++) { 
            $cantidad=$adicionales[$j]["cantidad"] ?? 0;
            $idServicioSalidasAdicionales=$adicionales[$j]["idServicioSalidasAdicionales"];
            
            $servicioAdicional=getValorServiciosAdicionalesSalida($idServicioSalidasAdicionales, $cantidad);
            
            if (!empty($servicioAdicional)) {
                $precioTotalReserva+=($servicioAdicional[0]["valor"]);
                $totalIvaReserva+=$servicioAdicional[0]["valorIva"];
                
                $adicional=altaReservaAdicional($altaReservaHorarios, $servicioAdicional[0]["idServiciosAdicionales"],
                                               $servicioAdicional[0]["nombre"], $servicioAdicional[0]["descripcion"],
                                               $cantidad, $servicioAdicional[0]["precio"], 
                                               $servicioAdicional[0]["precioUnitarioSIva"], 
                                               $servicioAdicional[0]["valorIva"], $servicioAdicional[0]["valor"],
                                               $servicioAdicional[0]["idMoneda"]);
                
                if (empty($adicional)) {
                    error_log("WARNING guardaReservas: altaReservaAdicional falló para idServicioSalidasAdicionales: $idServicioSalidasAdicionales");
                }
            }
        }
    }
    
    // Procesar servicios adicionales incluidos
    $adicionalesIncluidos=getServiciosAdicionalesSalidaIncluidos($idServicioSalidas);
    
    if (!empty($adicionalesIncluidos)) {
        for ($j=0; $j < count($adicionalesIncluidos); $j++) {
            $adicional=altaReservaAdicional($altaReservaHorarios, $adicionalesIncluidos[$j]["idServiciosAdicionales"],
                                           $adicionalesIncluidos[$j]["nombre"], $adicionalesIncluidos[$j]["descripcion"],
                                           0, 0, 0, 0, 0, $adicionalesIncluidos[$j]["idMoneda"]);
            
            if (empty($adicional)) {
                error_log("WARNING guardaReservas: altaReservaAdicional (incluido) falló");
            }
        }
    }
}

// Validar que se guardaron datos
if ($precioTotalReserva <= 0) {
    error_log("ERROR CRÍTICO guardaReservas: precioTotalReserva es 0 o negativo para idReserva: $idReserva");
}

if ($cantidadPasajeros <= 0) {
    error_log("ERROR CRÍTICO guardaReservas: cantidadPasajeros es 0 para idReserva: $idReserva");
}

//echo "Total de pasajeros= ".$cantidadPasajeros."<br>";

//echo "Precio Total de reserva= ".$precioTotalReserva."<br>";

//echo "IVA COBRADO= ".$totalIvaReserva."<br>";

  $total_dolares=ConvierteMoneda($monedaSel,188, $precioTotalReserva);

// Aplicar descuento AR$ si se aceptó
if (isset($_SESSION['descuento_ars_aceptado']) && $_SESSION['descuento_ars_aceptado'] == '1') {
    $descuento_ars = isset($_SESSION['descuento_ars_monto']) ? floatval($_SESSION['descuento_ars_monto']) : 0;
    $precioTotalReserva -= $descuento_ars;
    $total_dolares = ConvierteMoneda($monedaSel, 188, $precioTotalReserva);
}

$resultadoUpdateTotal=updateTotalReserva($idReserva, $precioTotalReserva,$total_dolares,$totalIvaReserva);

// Guardar descuento por redondeo en BD
if ($descuentoGanado > 0 && in_array($monedaSel, [270, 271, 225])) {
    try {
        require_once("admin/classes/conexion.php");
        $sqlDescuento = "UPDATE reservas 
                         SET descuento_redondeo = :descuento, 
                             moneda_redondeo = :moneda 
                         WHERE idReserva = :idReserva";
        $cmdDescuento = $pdo->prepare($sqlDescuento);
        $cmdDescuento->execute([
            ':descuento' => round($descuentoGanado, 2),
            ':moneda' => $monedaSel,
            ':idReserva' => $idReserva
        ]);
    } catch (Exception $e) {
        error_log("ERROR guardaReservas: No se pudo guardar descuento_redondeo: " . $e->getMessage());
    }
}

//echo "Resultado update total reservca: ".print_r($resultadoUpdateTotal);



  }  //if ($idReserva>1) {



}



include("admin/classes/email_reserva_pendiente.php");

include("admin/classes/reservaEmail.php");

$cuerpo=getCuerpoEmailReservaPendiente($codigoAmigable);

$resumail=enviaMail($emailResponsable,"Reserva exitosa en MeteleBrasil", $cuerpo, $parametros[0]["site"]);



unset($_SESSION["reserva"]);

unset($_SESSION["cupon_descuento"]);

// Limpiar variables del descuento AR$ para evitar que se apliquen a futuras reservas
unset($_SESSION['descuento_ars_aceptado']);
unset($_SESSION['descuento_ars_monto']);

?>

<script type="text/javascript">
  Swal.fire("Reservate", "Reserva Exitosa con el codigo <?=$codigoAmigable?>", "success");
  window.location="consultaReserva?reserva=<?=$codigoAmigable?>";
</script>







       







              