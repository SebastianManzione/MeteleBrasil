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
    include_once(__DIR__ . "/usuario.php");
    include_once(__DIR__ . "/configuracion.php");
    include_once(__DIR__ . "/email_prestador_reserva_confirmada.php");
    include_once(__DIR__ . "/email_reserva_confirmada.php");
    include_once(__DIR__ . "/email_renderer.php");
    if ($comprobantes >= $total_dolares) {
      $renderer = new EmailRenderer();
      $idioma = $_SESSION['idioma'] ?? 'ES';
      $codigo = $reserva[0]["codigoAmigable"];
      
      // Email al cliente
      $varsCliente = [
        'codigo_reserva' => $codigo,
        'enlace_reserva' => "http://metelebrasil.com/consultaReserva?reserva=" . $codigo
      ];
      $fallbackBodyCliente = getCuerpoEmailReservaConfirmada($codigo);
      $renderCliente = $renderer->render('reserva_confirmada', $idioma, $varsCliente, 'Reserva confirmada', $fallbackBodyCliente);

      $resumail = enviaMail($reserva[0]["emailResponsable"], $renderCliente['asunto'], $renderCliente['html'], "metelebrasil.com");
      confirmaReserva($idReserva);
      
      // Email al vendedor (si tiene idVendedor)
      if (isset($reserva[0]['idVendedor']) && $reserva[0]['idVendedor'] > 0) {
          $vendedor = getUsuario($reserva[0]['idVendedor']);
          if (!empty($vendedor) && !empty($vendedor[0]['email'])) {
              $config = new Configuracion();
              $asuntoVendedor = $config->obtener('email_vendedor_asunto', 'Nueva reserva confirmada - {{codigo_reserva}}');
              $cuerpoVendedor = $config->obtener('email_vendedor_cuerpo', '<h2>¡Nueva Reserva Confirmada!</h2><p>Se ha confirmado una nueva reserva:</p><ul><li><strong>Código:</strong> {{codigo_reserva}}</li><li><strong>Cliente:</strong> {{nombre_cliente}}</li><li><strong>Email:</strong> {{email_cliente}}</li><li><strong>Total:</strong> {{total}}</li></ul>');
              
              // Reemplazar variables
              $variables = [
                  '{{codigo_reserva}}' => $codigo,
                  '{{nombre_cliente}}' => $reserva[0]['nombreResponsable'] . ' ' . $reserva[0]['apellidoResponsable'],
                  '{{email_cliente}}' => $reserva[0]['emailResponsable'],
                  '{{total}}' => '$' . number_format($total_dolares, 2),
                  '{{enlace_reserva}}' => "http://metelebrasil.com/admin/reservaDetalles?idReserva=" . $idReserva
              ];
              
              $asuntoVendedor = str_replace(array_keys($variables), array_values($variables), $asuntoVendedor);
              $cuerpoVendedor = str_replace(array_keys($variables), array_values($variables), $cuerpoVendedor);
              
              enviaMail($vendedor[0]['email'], $asuntoVendedor, $cuerpoVendedor, "metelebrasil.com");
          }
      }
      
      // Email a prestadores
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
