





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



        include("includes/headPagos.php");

       

    include ("admin/classes/functions.php"); 

    alertar("Su reserva esta siendo guardada", "success");

?>





<?php





if ($_SERVER['REQUEST_METHOD'] == 'POST'){

$impuestos_pais=($_SESSION["impuestos_pais"]);

//print_r($_POST);

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

$reserva=altaReserva($idUsuario,$nombreResponsable, $apellidoResponsable, $emailResponsable, $idCountry, $telefonoResponsable, $monedaSel, $impuestos_pais, $idioma);

$idReserva=$reserva["idReserva"];

$codigoAmigable=$reserva["codigoAmigable"];



if ($idReserva>1) {

//  echo "Alta reserva ok codigoAmigable: ".$codigoAmigable."<br>";

$cantidadPasajeros=0;

$precioTotalReserva=0;

$totalIvaReserva=0;

$cantidadPasajerosReserva=0;

for ($i=0; $i < count($reservas); $i++) { 



/*

  echo "***** ADICIONALES: <BR>";

print_r($_SESSION["reserva"][$i][1]);

echo "*****<br><br>";*/

$idServicioSalidasTarifas=$reservas[$i][0]["idServicioSalidasTarifas"];



  $tarifa=calculaTarifa($idServicioSalidasTarifas,1);

  $idServicioSalidas=$tarifa[0]["idServicioSalidas"];

   $salida=getSalida($tarifa[0]['idServicioSalidas'])[0];



  $pasajerosReserva=$reservas[$i];

 



$idServicioSeleccionado=$pasajerosReserva[0]["idServicioSeleccionado"];

   $comentario=$pasajerosReserva[0]["comentario"];

   $ubicacion=getUbicacionIdTarifa($idServicioSalidasTarifas);



//print_r($ubicacion);





$altaReservaHorarios=altaReservaHorarios($idReserva, $idServicioSalidas, $idServicioSeleccionado, $cantidadPasajeros,$salida["nombre"], $salida["fecha"], $salida["horaSalida"], $salida["horaCheckIn"], $ubicacion[0]["direccion"], $ubicacion[0]["latitud"], $ubicacion[0]["longitud"],  $comentario);





  for ($j=0; $j < count($pasajerosReserva); $j++) { 





 



$idServicioSalidasTarifas=$_SESSION["reserva"][$i][0][$j]['idServicioSalidasTarifas'];

$idServicioSeleccionado=$_SESSION["reserva"][$i][0][$j]["idServicioSeleccionado"];

$cantidad=$_SESSION["reserva"][$i][0][$j]["cantidad"];



  $tarifa=calculaTarifa($idServicioSalidasTarifas,$cantidad);

$idServicioSalidas=$tarifa[0]["idServicioSalidas"];



  $valor=$tarifa[0]["valor"];

  $cantidadPasajeros+=$cantidad;

$precioTotalReserva+=$valor;

$valorDeIva=$tarifa[0]["valorDeIva"];

$valorSinIva=$tarifa[0]["valorSinIva"];

$nombre=$tarifa[0]["nombre"];

$idFromEdad=$tarifa[0]["idFromEdad"];

$idToEdad=$tarifa[0]["idToEdad"];

$comisionVendedor=$tarifa[0]["comisionVendedor"];

$comisionSistema=$tarifa[0]["comisionSistema"];

   $totalIvaReserva+=$valorDeIva;





//$nombrePasajero=$pasajerosReserva[$j]['nombre']." ".$pasajerosReserva[$j]['apellido'];

//, $nombrePasajero

 $altaReservaTarifas=altaReservaTarifas($idServicioSalidasTarifas, $altaReservaHorarios,$cantidad, $monedaSel, $valor, $valorSinIva, $valorDeIva, $idFromEdad, $idToEdad, $comisionVendedor, $comisionSistema, $nombre);



 $pasajerosPost=$_POST["pasajero"][$i][$j];



for ($s=0; $s < count($pasajerosPost); $s++) { 



    $reservaPasajero=altaReservaPasajero($altaReservaTarifas, $pasajerosPost[$s][0],$pasajerosPost[$s][1]);

  //echo "reservaPasajero".$reservaPasajero;

  //echo "<br>";

}



// 

  }  //  for ($j=0; $j < count($pasajerosReserva); $j++) { 

//aca tendriamos que bloquear los lugares de la reserva







  	

  	$resultadoRestaDisponibilidad=restaDisponibilidadSalida($idServicioSalidas, $cantidadPasajeros);

  

  $adicionales=$_SESSION["reserva"][$i][1];

  if (is_countable($adicionales)) {

    # code...

 

  for ($j=0; $j < count($adicionales); $j++) { 

    $cantidad= $adicionales[$j]["cantidad"];

    $idServicioSalidasAdicionales=$adicionales[$j]["idServicioSalidasAdicionales"];

     $servicioAdicional= getValorServiciosAdicionalesSalida($idServicioSalidasAdicionales,$cantidad);

     $precioTotalReserva+=($servicioAdicional[0]["valor"]);





    $adicional=altaReservaAdicional( $altaReservaHorarios, $servicioAdicional[0]["idServiciosAdicionales"],$servicioAdicional[0]["nombre"],$servicioAdicional[0]["descripcion"], $cantidad, $servicioAdicional[0]["precio"], $servicioAdicional[0]["precioUnitarioSIva"],  $servicioAdicional[0]["valorIva"], $servicioAdicional[0]["valor"]);



    $totalIvaReserva+=$servicioAdicional[0]["valorIva"];



  }

 }

  $adicionalesIncluidos=getServiciosAdicionalesSalidaIncluidos($idServicioSalidas);



  for ($j=0; $j < count(  $adicionalesIncluidos); $j++) { //los incluidos



  	    $adicional=altaReservaAdicional( $altaReservaHorarios, $adicionalesIncluidos[$j]["idServiciosAdicionales"],$adicionalesIncluidos[$j]["nombre"],$adicionalesIncluidos[$j]["descripcion"], 0, 0, 0, 0, 0);

  }





}  //for ($i=0; $i < count($reservas); $i++) { 

//echo "Total de pasajeros= ".$cantidadPasajeros."<br>";

//echo "Precio Total de reserva= ".$precioTotalReserva."<br>";

//echo "IVA COBRADO= ".$totalIvaReserva."<br>";

  $total_dolares=ConvierteMoneda($monedaSel,188, $precioTotalReserva);

$resultadoUpdateTotal=updateTotalReserva($idReserva, $precioTotalReserva,$total_dolares,$totalIvaReserva);

//echo "Resultado update total reservca: ".print_r($resultadoUpdateTotal);

}  //if ($idReserva>1) {

}

include("admin/classes/email_reserva_pendiente.php");
include("admin/classes/reservaEmail.php");
$cuerpo=getCuerpoEmailReservaPendiente($codigoAmigable);
//$cuerpo=getCuerpoEmailReserva($codigoAmigable, $parametros[0]["site"]);

$resumail=enviaMail($emailResponsable,"Reserva exitosa en MeteleBrasil", $cuerpo, $parametros[0]["site"]);
//echo "Resumail: ".$resumail;



unset($_SESSION["reserva"]);

unset($_SESSION["cupon_descuento"]);

?>

<a href="consultaReserva?reserva=<?=$codigoAmigable?>">ver Reserva</a>

<script type="text/javascript">

	Swal.fire("Reservate", "Reserva Exitosa con el codigo <?=$codigoAmigable?>", "success");


window.location="consultaReserva?reserva=<?=$codigoAmigable?>";



</script>

<?php

/*



enviaMail($txtEmailResponsable , "Reserva en metelebrasil.com", 



















echo ("<script>location.href='metodo-pago.php?idReserva=".$idReserva."'</script>");



}*/

 ?>



                           

                           

              