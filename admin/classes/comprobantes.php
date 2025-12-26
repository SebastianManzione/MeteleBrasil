<?php


function getComprobantes()
{


  require("conexion.php");


  $consulta = "select * from comprobante";


  $comando = $pdo->prepare($consulta);


  $comando->execute();


  $cuenta_col = $comando->columnCount();


  $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);


  return $resultado;


}


function muestraComprobantes($idReserva)
{


  require("conexion.php");


  $data = ["idReserva" => $idReserva];


  $consulta = "select * from comprobante WHERE idReserva=:idReserva ";


  $comando = $pdo->prepare($consulta);


  $comando->execute($data);


  $cuenta_col = $comando->columnCount();


  $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);


  return $resultado;


}


function getComprobantesIdReserva($idReserva)
{


  require("conexion.php");


  $data = ["idReserva" => $idReserva];


  $consulta = "select * from comprobante WHERE idReserva=:idReserva ";


  $comando = $pdo->prepare($consulta);


  $comando->execute($data);


  $cuenta_col = $comando->columnCount();


  $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);


  // Imprimir en pantalla


  $total = 0;


  for ($i = 0; $i < count($resultado); $i++) {


    $total += ConvierteMoneda($resultado[$i]["monedaComprobante"], $_SESSION["moneda_sel"], $resultado[$i]["total"]);


  }


  return $total;


}


function getComprobantesIdReservaDolar($idReserva)
{


  require("conexion.php");


  $data = ["idReserva" => $idReserva];


  $consulta = "select * from comprobante WHERE idReserva = :idReserva";


  $comando = $pdo->prepare($consulta);


  $comando->execute($data);


  $cuenta_col = $comando->columnCount();


  $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);


  // Imprimir en pantalla


  $total = 0;


  for ($i = 0; $i < count($resultado); $i++) {


    $total += $resultado[$i]["total_dolares"];


  }


  return $total;


}


function insertaComprobante($idReserva, $total, $origenComprobante, $monedaComprobante, $compOrigen, $total_dolares, $idUsuario)
{
  require("conexion.php");
  $data = ["idReserva" => $idReserva, "total" => $total, "origenComprobante" => $origenComprobante, "monedaComprobante" => $monedaComprobante, "compOrigen" => $compOrigen, "total_dolares" => $total_dolares, "idUsuario" => $idUsuario];
  $consulta = "INSERT INTO comprobante (idReserva, total, origenComprobante, monedaComprobante, compOrigen, total_dolares, idUsuario) VALUES (:idReserva, :total, :origenComprobante,:monedaComprobante, :compOrigen, :total_dolares, :idUsuario) ";
  $comando = $pdo->prepare($consulta);
  $comando->execute($data);
  $id = $pdo->lastInsertId();
  $cuenta_col = $comando->columnCount();
  $cuenta_row = $comando->rowCount();
  $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
  $idComprobante = $id;
  if ($id > 0) {
    require_once(__DIR__ . "/reservaEmail.php");
    include_once(__DIR__ . "/reserva.php");
    $reserva = getReservaId($idReserva);
    $comprobantes = getComprobantesIdReservaDolar($idReserva);
    $total_dolares = $reserva[0]["total_dolares"];
    $horariosReserva = getReservaHorarios($idReserva);
    include_once(__DIR__ . "/salidas.php");
    include_once(__DIR__ . "/prestador.php");
    include_once(__DIR__ . "/email_prestador_reserva_confirmada.php");
    include_once(__DIR__ . "/email_reserva_confirmada.php");
    include_once(__DIR__ . "/email_renderer.php");
    if ($comprobantes >= $total_dolares) {
      $renderer = new EmailRenderer();
      $idioma = $_SESSION['idioma'] ?? 'ES';
      $codigo = $reserva[0]["codigoAmigable"];
      $varsCliente = [
        'codigo_reserva' => $codigo,
        'enlace_reserva' => "http://metelebrasil.com/consultaReserva?reserva=" . $codigo
      ];
      $fallbackBodyCliente = getCuerpoEmailReservaConfirmada($codigo);
      $renderCliente = $renderer->render('reserva_confirmada', $idioma, $varsCliente, 'Reserva confirmada', $fallbackBodyCliente);

      $resumail = enviaMail($reserva[0]["emailResponsable"], $renderCliente['asunto'], $renderCliente['html'], "metelebrasil.com");
      confirmaReserva($idReserva);
      foreach ($horariosReserva as $key => $value) {
        $salida = getSalida($value['idServicioSalidas']);
        $prestador = getPrestador($salida[0]['idPrestador']);
        $idReservaHorarios = $value['idReservaHorarios'];
        $nombrePrestador = ($prestador[0]['nombre']);
        $fecha_salida = date("d-m-Y", strtotime($salida[0]['fecha']));
        $fallbackPrestador = getCuerpoEmailPrestadorReservaConfirmada($idReservaHorarios, "metelebrasil.com", $nombrePrestador);
        $varsPrestador = [
          'nombre_prestador' => $nombrePrestador,
          'fecha_salida' => $fecha_salida,
          'codigo_reserva' => $codigo,
          'id_salida' => $value['idServicioSalidas']
        ];
        $renderPrestador = $renderer->render('prestador_reserva_confirmada', $idioma, $varsPrestador, "Nova reserva! " . $fecha_salida, $fallbackPrestador);
        $resumail = enviaMail($prestador[0]['email'], $renderPrestador['asunto'], $renderPrestador['html'], "metelebrasil.com");
      }
    }
  }
  return $idComprobante;
}


?>
