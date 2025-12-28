<?php
function getServiciosAdicionales()
{
  require("conexion.php");

  // Obtém o idioma da sessão
  $idioma = isset($_SESSION["idioma"]) ? $_SESSION["idioma"] : "ES"; // Padrão: espanhol

  // Consulta para buscar os serviços adicionais
  $consulta = "SELECT * FROM servicios_adicionales";
  $comando = $pdo->prepare($consulta);
  $comando->execute();
  $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

  // Ajusta os dados com base no idioma
  foreach ($resultado as &$row) {
    switch ($idioma) {
      case 'EN': // Inglês
        $row['nombre'] = $row['nombre_en'] ?? $row['nombre'];
        $row['descripcion_servicio_adicional'] = $row['descripcion_servicio_adicional_en'] ?? $row['descripcion_servicio_adicional'];
        break;
      case 'PT': // Português
        $row['nombre'] = $row['nombre_pt'] ?? $row['nombre'];
        $row['descripcion_servicio_adicional'] = $row['descripcion_servicio_adicional_pt'] ?? $row['descripcion_servicio_adicional'];
        break;
      case 'IT': // Italiano
        $row['nombre'] = $row['nombre_it'] ?? $row['nombre'];
        $row['descripcion_servicio_adicional'] = $row['descripcion_servicio_adicional_it'] ?? $row['descripcion_servicio_adicional'];
        break;
      // Caso padrão (ES ou qualquer outro idioma)
      default:
        // Mantém os valores originais em espanhol
        break;
    }
  }

  return $resultado;
}


/**
 * Obtener servicio adicional SIN traducción (para editor)
 * Devuelve todos los campos de nombres y descripciones en todos los idiomas
 */
function getServicioAdicionalParaEditar($idServiciosAdicionales)
{
  require("conexion.php");

  $data = ["idServiciosAdicionales" => $idServiciosAdicionales];
  $consulta = "SELECT * FROM servicios_adicionales WHERE idServiciosAdicionales = :idServiciosAdicionales";
  $comando = $pdo->prepare($consulta);
  $comando->execute($data);
  $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

  // Devuelve sin traducción automática
  return $resultado;
}

function getServicioAdicional($idServiciosAdicionales)
{
  require("conexion.php");

  // Obtém o idioma da sessão
  $idioma = isset($_SESSION["idioma"]) ? $_SESSION["idioma"] : "ES"; // Padrão: espanhol

  // Consulta para buscar o serviço adicional
  $data = ["idServiciosAdicionales" => $idServiciosAdicionales];
  $consulta = "SELECT * FROM servicios_adicionales WHERE idServiciosAdicionales = :idServiciosAdicionales";
  $comando = $pdo->prepare($consulta);
  $comando->execute($data);
  $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

  // Ajusta os dados com base no idioma
  foreach ($resultado as &$row) {
    switch ($idioma) {
      case 'EN': // Inglês
        $row['nombre'] = $row['nombre_en'] ?? $row['nombre'];
        $row['descripcion_servicio_adicional'] = $row['descripcion_servicio_adicional_en'] ?? $row['descripcion_servicio_adicional'];
        break;
      case 'PT': // Português
        $row['nombre'] = $row['nombre_pt'] ?? $row['nombre'];
        $row['descripcion_servicio_adicional'] = $row['descripcion_servicio_adicional_pt'] ?? $row['descripcion_servicio_adicional'];
        break;
      case 'IT': // Italiano
        $row['nombre'] = $row['nombre_it'] ?? $row['nombre'];
        $row['descripcion_servicio_adicional'] = $row['descripcion_servicio_adicional_it'] ?? $row['descripcion_servicio_adicional'];
        break;
      // Caso padrão (ES ou qualquer outro idioma)
      default:
        // Mantém os valores originais em espanhol
        break;
    }
  }

  return $resultado;
}


function getServicioAdicionalIncluidoYNoIncluido($idServiciosAdicionales, $idServicioSalidas)
{


  require("conexion.php");

  $data = ["idServicioSalidas" => $idServicioSalidas, "idServiciosAdicionales" => $idServiciosAdicionales,];

  $consulta = "select * from servicio_salidas_adicionales WHERE idServicioSalidas=:idServicioSalidas AND idServiciosAdicionales=:idServiciosAdicionales";


  $comando = $pdo->prepare($consulta);


  $comando->execute($data);

  $cuenta_col = $comando->columnCount();


  $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);


  return $resultado;


}


function getServicioAdicionalIdServicioSalida($idServicioSalidas)
{


  require("conexion.php");

  $data = ["idServicioSalidas" => $idServicioSalidas];

  $consulta = "select * from servicio_salidas_adicionales WHERE idServicioSalidas=:idServicioSalidas ";


  $comando = $pdo->prepare($consulta);


  $comando->execute($data);

  $cuenta_col = $comando->columnCount();


  $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);


  return $resultado;


}


function getServicioAdicionalSalida($idServicioSalidasAdicionales)
{


  require("conexion.php");

  $data = ["idServicioSalidasAdicionales" => $idServicioSalidasAdicionales];

  $consulta = "select * from servicio_salidas_adicionales ssad INNER JOIN servicios_adicionales sad ON ssad.idServiciosAdicionales=sad.idServiciosAdicionales WHERE idServicioSalidasAdicionales=:idServicioSalidasAdicionales";


  $comando = $pdo->prepare($consulta);


  $comando->execute($data);

  $cuenta_col = $comando->columnCount();


  $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);


  return $resultado;


}

function getServiciosAdicionalesCategoria($idCategoria)
{
  require("conexion.php"); // Inclui o arquivo de conexão

  // Obtém o idioma da sessão
  $idioma = isset($_SESSION["idioma"]) ? $_SESSION["idioma"] : "ES"; // Padrão: espanhol

  // Consulta para buscar os serviços adicionais da categoria
  $consulta = "SELECT SAD.*, SAC.* 
                 FROM servicios_adicionales_categoria SAC 
                 INNER JOIN servicios_adicionales SAD 
                 ON SAC.idServiciosAdicionales = SAD.idServiciosAdicionales 
                 WHERE SAC.idCatSrv = :idCategoria";

  $comando = $pdo->prepare($consulta);
  $comando->execute([":idCategoria" => $idCategoria]);
  $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

  // Ajusta os dados com base no idioma
  foreach ($resultado as &$row) {
    switch ($idioma) {
      case 'EN': // Inglês
        $row['nombre'] = $row['nombre_en'] ?? $row['nombre'] ?? '';
        $row['descripcion'] = $row['descripcion_en'] ?? $row['descripcion'] ?? '';
        break;
      case 'PT': // Português
        $row['nombre'] = $row['nombre_pt'] ?? $row['nombre'] ?? '';
        $row['descripcion'] = $row['descripcion_pt'] ?? $row['descripcion'] ?? '';
        break;
      // Caso padrão (ES ou qualquer outro idioma)
      default:
        // Mantém os valores originais em espanhol
        break;
    }
  }

  return $resultado;
}


function getServiciosAdicionalesQueNoEstanEnSalida($idCategoria, $idServicioSalidas)
{


  require("conexion.php");

  $data = ["idCategoria" => $idCategoria, 'idServicioSalidas' => $idServicioSalidas];


  $consulta = "select * from servicios_adicionales_categoria SAC INNER JOIN servicios_adicionales SAD ON SAC.idServiciosAdicionales =  SAD.idServiciosAdicionales WHERE NOT EXISTS (SELECT * FROM servicio_salidas_adicionales WHERE idServicioSalidas=:idServicioSalidas AND idServiciosAdicionales=SAC.idServiciosAdicionales) AND SAC.idCatSrv=:idCategoria";


  $comando = $pdo->prepare($consulta);


  $comando->execute($data);

  $cuenta_col = $comando->columnCount();


  $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);


  return $resultado;


}


function getServiciosAdicionalesReservados($idServiciosAdicionales, $idServicioSalidas)
{


  require("conexion.php");


  $data = ["idServiciosAdicionales" => $idServiciosAdicionales, 'idServicioSalidas' => $idServicioSalidas];///,

  $consulta = "select * from reserva_adicionales RA INNER JOIN reserva_horarios RH ON RA.idReservaHorarios=RH.idReservaHorarios 

    INNER JOIN reservas RE ON RH.idReserva=RE.idReserva

     WHERE RA.idServiciosAdicionales=:idServiciosAdicionales AND RH.idServicioSalidas=:idServicioSalidas";


  $comando = $pdo->prepare($consulta);


  $comando->execute($data);

  $cuenta_col = $comando->columnCount();


  $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

  // Imprimir en pantalla

  return $resultado;


}


function getServiciosAdicionalesSalidaIncluidos($idServicioSalidas)
{


  require("conexion.php");

  // Obtén el idioma de la sesión (verificar si está activa)
  $idioma = isset($_SESSION["idioma"]) ? $_SESSION["idioma"] : "ES"; // Valor por defecto: español

  $data = ["idServicioSalidas" => $idServicioSalidas];

  $consulta = "select * from servicio_salidas_adicionales ssa INNER JOIN servicios_adicionales SAD ON ssa.idServiciosAdicionales =  SAD.idServiciosAdicionales WHERE idServicioSalidas=:idServicioSalidas AND valor = 0";


  $comando = $pdo->prepare($consulta);


  $comando->execute($data);

  $cuenta_col = $comando->columnCount();


  $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

  // Ajusta los datos según el idioma
  foreach ($resultado as &$row) {
    switch ($idioma) {
      case 'EN': // Inglés
        $row['nombre'] = $row['nombre_en'] ?? $row['nombre'];
        $row['descripcion_servicio_adicional'] = $row['descripcion_servicio_adicional_en'] ?? $row['descripcion_servicio_adicional'];
        break;
      case 'PT': // Portugués
        $row['nombre'] = $row['nombre_pt'] ?? $row['nombre'];
        $row['descripcion_servicio_adicional'] = $row['descripcion_servicio_adicional_pt'] ?? $row['descripcion_servicio_adicional'];
        break;
      case 'IT': // Italiano
        $row['nombre'] = $row['nombre_it'] ?? $row['nombre'];
        $row['descripcion_servicio_adicional'] = $row['descripcion_servicio_adicional_it'] ?? $row['descripcion_servicio_adicional'];
        break;
      // Caso por defecto (ES u otro idioma)
      default:
        // Mantiene los valores originales en español
        break;
    }
  }

  // Imprimir en pantalla

  return $resultado;


}


function getServiciosAdicionalesAllSalidas($idServiciosAdicionales)
{


  require("conexion.php");

  $idServiciosAdicionales = $idServiciosAdicionales;

  $data = ["idServiciosAdicionales" => $idServiciosAdicionales];

  $consulta = "SELECT * FROM servicios_salidas_adicionales WHERE idServiciosAdicionales=:idServiciosAdicionales ";


  $comando = $pdo->prepare($consulta);


  $comando->execute($data);

  $cuenta_col = $comando->columnCount();


  $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);


  return $resultado;


}


function getServiciosAdicionalesAllCategorias($idServiciosAdicionales)
{


  require("conexion.php");


  $data = ["idServiciosAdicionales" => $idServiciosAdicionales];

  $consulta = "SELECT * FROM servicios_adicionales_categoria WHERE idServiciosAdicionales=:idServiciosAdicionales";


  $comando = $pdo->prepare($consulta);


  $comando->execute($data);

  $cuenta_col = $comando->columnCount();


  $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);


  return $resultado;


}


function getSvAdicionalesNoIncluidosEnCategoria($idCategoria_servicio)
{


  require("conexion.php");

  $idCatSrv = $idCategoria_servicio;

  $data = ["idCatSrv" => $idCatSrv];

  $consulta = "SELECT * FROM servicios_adicionales sa

     where not EXISTS (select idServiciosAdicionales from servicios_adicionales_categoria sac where sac.idServiciosAdicionales = sa.idServiciosAdicionales AND sac.idCatSrv=:idCatSrv) 

    ";


  $comando = $pdo->prepare($consulta);


  $comando->execute($data);

  $cuenta_col = $comando->columnCount();


  $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);


  return $resultado;


}

function eliminaSvAdicionalCategoria($idServiciosAdicionalesCategoria)
{


  require("conexion.php");


  $data = ["idServiciosAdicionalesCategoria" => $idServiciosAdicionalesCategoria];

  $consulta = "DELETE FROM servicios_adicionales_categoria where idServiciosAdicionalesCategoria=:idServiciosAdicionalesCategoria";

  $comando = $pdo->prepare($consulta);


  $comando->execute($data);

  $cuenta_col = $comando->columnCount();


  $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);


  return $resultado;


}


function agregaSvAdicionalCategoria($idCatSrv, $idServiciosAdicionales)
{

  require("conexion.php");


  $data = ["idCatSrv" => $idCatSrv, "idServiciosAdicionales" => $idServiciosAdicionales];

  $consulta = "INSERT INTO servicios_adicionales_categoria (idCatSrv, idServiciosAdicionales) VALUES(:idCatSrv, :idServiciosAdicionales)";

  $comando = $pdo->prepare($consulta);


  $comando->execute($data);

  $cuenta_col = $comando->columnCount();


  $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);


  return $resultado;

}


function getServiciosAdicionalesSalidaNoIncluidos($idServicioSalidas)
{


  require("conexion.php");

  // Obtén el idioma de la sesión (verificar si está activa)
  $idioma = isset($_SESSION["idioma"]) ? $_SESSION["idioma"] : "ES"; // Valor por defecto: español

  $data = ["idServicioSalidas" => $idServicioSalidas];

  $consulta = "select * from servicio_salidas_adicionales ssa INNER JOIN servicios_adicionales SAD ON ssa.idServiciosAdicionales =  SAD.idServiciosAdicionales WHERE idServicioSalidas=:idServicioSalidas AND valor > 0";


  $comando = $pdo->prepare($consulta);


  $comando->execute($data);

  $cuenta_col = $comando->columnCount();


  $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);


  // Imprimir en pantalla

  $serviciosAdicionalesSalidaNoIncluidos = array();

  for ($i = 0; $i < count($resultado); $i++) {

    $idMoneda = $resultado[$i]["idMoneda"];

    // Ajusta el nombre según el idioma
    $nombre = $resultado[$i]["nombre"];
    switch ($idioma) {
      case 'EN':
        $nombre = $resultado[$i]["nombre_en"] ?? $nombre;
        break;
      case 'PT':
        $nombre = $resultado[$i]["nombre_pt"] ?? $nombre;
        break;
      case 'IT':
        $nombre = $resultado[$i]["nombre_it"] ?? $nombre;
        break;
    }

    $serviciosAdicionalesSalidaNoIncluidos[$i]['idMoneda'] = $resultado[$i]["idMoneda"];

    $serviciosAdicionalesSalidaNoIncluidos[$i]['idServicioSalidas'] = $resultado[$i]["idServicioSalidas"];

    $serviciosAdicionalesSalidaNoIncluidos[$i]['idServicioSalidasAdicionales'] = $resultado[$i]["idServicioSalidasAdicionales"];

    $serviciosAdicionalesSalidaNoIncluidos[$i]['idServiciosAdicionales'] = $resultado[$i]["idServiciosAdicionales"];

    $serviciosAdicionalesSalidaNoIncluidos[$i]['nombre'] = $nombre;


    if (strlen($resultado[$i]["descripcion"]) > 0) {

      $serviciosAdicionalesSalidaNoIncluidos[$i]['descripcion'] = $resultado[$i]["descripcion"];

    } else {

      $serviciosAdicionalesSalidaNoIncluidos[$i]['descripcion'] = $resultado[$i]["descripcion_servicio_adicional"];

    }


    $serviciosAdicionalesSalidaNoIncluidos[$i]['valor'] = convierteMoneda($idMoneda, $_SESSION['moneda_sel'], $resultado[$i]["valor"]);

    // Aplicar impuestos del país si existen
    $impuestos_pais = isset($_SESSION["impuestos_pais"]) ? $_SESSION["impuestos_pais"] : 0;
    $serviciosAdicionalesSalidaNoIncluidos[$i]['valor'] = $serviciosAdicionalesSalidaNoIncluidos[$i]['valor'] * ($impuestos_pais + 1);


    $serviciosAdicionalesSalidaNoIncluidos[$i]['valor'] = round($serviciosAdicionalesSalidaNoIncluidos[$i]['valor'], 2, PHP_ROUND_HALF_UP);

    $serviciosAdicionalesSalidaNoIncluidos[$i]['valor'] = $_SESSION['moneda_sel_sym'] . "" . $serviciosAdicionalesSalidaNoIncluidos[$i]['valor'];


  }

  return $serviciosAdicionalesSalidaNoIncluidos;


}


function getValorServiciosAdicionalesSalida($idServicioSalidasAdicionales, $cantidad)
{


  require("conexion.php");


  $data = ["idServicioSalidasAdicionales" => $idServicioSalidasAdicionales];

  $consulta = "select * from servicio_salidas_adicionales ssa INNER JOIN servicios_adicionales SAD ON ssa.idServiciosAdicionales =  SAD.idServiciosAdicionales WHERE idServicioSalidasAdicionales=:idServicioSalidasAdicionales ";


  $comando = $pdo->prepare($consulta);


  $comando->execute($data);

  $cuenta_col = $comando->columnCount();


  $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

  // Imprimir en pantalla

  $serviciosAdicionalesSalidaNoIncluidos = array();

  for ($i = 0; $i < count($resultado); $i++) {

    $idMoneda = $resultado[$i]["idMoneda"];

    $serviciosAdicionalesSalidaNoIncluidos[$i]['idMoneda'] = $resultado[$i]["idMoneda"];

    $serviciosAdicionalesSalidaNoIncluidos[$i]['idServicioSalidas'] = $resultado[$i]["idServicioSalidas"];

    $serviciosAdicionalesSalidaNoIncluidos[$i]['idServicioSalidasAdicionales'] = $resultado[$i]["idServicioSalidasAdicionales"];

    $serviciosAdicionalesSalidaNoIncluidos[$i]['idServiciosAdicionales'] = $resultado[$i]["idServiciosAdicionales"];

    $serviciosAdicionalesSalidaNoIncluidos[$i]['nombre'] = $resultado[$i]["nombre"];

    $serviciosAdicionalesSalidaNoIncluidos[$i]['descripcion'] = $resultado[$i]["descripcion"];

    $precioUnitarioSIva = $resultado[$i]["valor"];

    $precio = $resultado[$i]["valor"] * $cantidad;

    $impuestos_pais_valor = isset($_SESSION["impuestos_pais"]) ? $_SESSION["impuestos_pais"] : 0;
    $valorIva = $precio * $impuestos_pais_valor;

    $precioConIva = $precio + $valorIva;

    $serviciosAdicionalesSalidaNoIncluidos[$i]['precioUnitarioSIva'] = $precioUnitarioSIva;

    $serviciosAdicionalesSalidaNoIncluidos[$i]['precio'] = $precio;

    $serviciosAdicionalesSalidaNoIncluidos[$i]['valorIva'] = $valorIva;

    $serviciosAdicionalesSalidaNoIncluidos[$i]['valor'] = convierteMoneda($idMoneda, $_SESSION['moneda_sel'], $precioConIva);

    $serviciosAdicionalesSalidaNoIncluidos[$i]['valor'] = convierteMoneda($idMoneda, $_SESSION['moneda_sel'], $precioConIva);


    $serviciosAdicionalesSalidaNoIncluidos[$i]['valor'] = round($serviciosAdicionalesSalidaNoIncluidos[$i]['valor'], 2, PHP_ROUND_HALF_UP);

    $serviciosAdicionalesSalidaNoIncluidos[$i]['valorSymUnitario'] = $_SESSION['moneda_sel_sym'] . "" . ($serviciosAdicionalesSalidaNoIncluidos[$i]['valor']);

    if ($cantidad > 0) {

      $serviciosAdicionalesSalidaNoIncluidos[$i]['valorSymUnitario'] = $_SESSION['moneda_sel_sym'] . "" . ($serviciosAdicionalesSalidaNoIncluidos[$i]['valor'] / $cantidad);


    }


    $serviciosAdicionalesSalidaNoIncluidos[$i]['valorSym'] = $_SESSION['moneda_sel_sym'] . "" . $serviciosAdicionalesSalidaNoIncluidos[$i]['valor'];

  }

  return $serviciosAdicionalesSalidaNoIncluidos;


}


function setServiciosAdicionalesSalida($idServicioSalidas, $idServiciosAdicionales, $valor, $idMoneda, $descripcion)
{


  require("conexion.php");

  $data = ["idServicioSalidas" => $idServicioSalidas, "idServiciosAdicionales" => $idServiciosAdicionales, "valor" => $valor, "idMoneda" => $idMoneda, "descripcion" => $descripcion];

  $consulta = "INSERT INTO servicio_salidas_adicionales (idServicioSalidas, idServiciosAdicionales, valor, idMoneda, descripcion) VALUES (:idServicioSalidas, :idServiciosAdicionales, :valor,:idMoneda, :descripcion) ";


  $comando = $pdo->prepare($consulta);


  $comando->execute($data);


  $id = $pdo->lastInsertId();

  $cuenta_col = $comando->columnCount();

  $cuenta_row = $comando->rowCount();

  $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);


  return $id;


}


function setServicioAdicional($nombres, $descripciones)
{
  require("conexion.php");

  // Prepara os dados para inserção
  $data = [
    "nombre" => $nombres['ES'],
    "nombre_en" => $nombres['EN'],
    "nombre_pt" => $nombres['PT'],
    "nombre_it" => $nombres['IT'],
    "descripcion_servicio_adicional" => $descripciones['ES'],
    "descripcion_servicio_adicional_en" => $descripciones['EN'],
    "descripcion_servicio_adicional_pt" => $descripciones['PT'],
    "descripcion_servicio_adicional_it" => $descripciones['IT']
  ];

  $consulta = "INSERT INTO servicios_adicionales (
        nombre, 
        nombre_en, 
        nombre_pt, 
        nombre_it,
        descripcion_servicio_adicional,
        descripcion_servicio_adicional_en,
        descripcion_servicio_adicional_pt,
        descripcion_servicio_adicional_it
    ) VALUES (
        :nombre, 
        :nombre_en, 
        :nombre_pt, 
        :nombre_it,
        :descripcion_servicio_adicional,
        :descripcion_servicio_adicional_en,
        :descripcion_servicio_adicional_pt,
        :descripcion_servicio_adicional_it
    )";

  $comando = $pdo->prepare($consulta);
  $comando->execute($data);

  return $pdo->lastInsertId();
}


function borraServicioAdicional($idServiciosAdicionales)
{


  require("conexion.php");

  $data = ["idServiciosAdicionales" => $idServiciosAdicionales];

  $consulta = "DELETE FROM servicios_adicionales WHERE idServiciosAdicionales=:idServiciosAdicionales ";


  $comando = $pdo->prepare($consulta);


  $comando->execute($data);

  $cuenta_col = $comando->columnCount();

  $cuenta_row = $comando->rowCount();

  $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);


  return $cuenta_row;


}

function borraServicioAdicionalSalida($idServiciosAdicionales, $idServicioSalidas)
{


  require("conexion.php");

  $data = ["idServiciosAdicionales" => $idServiciosAdicionales, "idServicioSalidas" => $idServicioSalidas];

  $consulta = "DELETE FROM servicio_salidas_adicionales WHERE idServiciosAdicionales=:idServiciosAdicionales AND idServicioSalidas=:idServicioSalidas";


  $comando = $pdo->prepare($consulta);


  $comando->execute($data);

  $cuenta_col = $comando->columnCount();

  $cuenta_row = $comando->rowCount();

  $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);


  return $cuenta_row;


}

// ==================== CRUD PARA EDITOR ====================

/**
 * Crear nuevo servicio adicional
 * @param array $nombres Array con claves ES, EN, PT, IT
 * @param array $descripciones Array con claves ES, EN, PT, IT
 */
function altaServicioAdicional($nombres, $descripciones)
{
  require("conexion.php");

  $data = [
    "nombre" => $nombres["ES"] ?? "",
    "nombre_en" => $nombres["EN"] ?? null,
    "nombre_pt" => $nombres["PT"] ?? null,
    "nombre_it" => $nombres["IT"] ?? null,
    "descripcion_servicio_adicional" => $descripciones["ES"] ?? "",
    "descripcion_servicio_adicional_en" => $descripciones["EN"] ?? null,
    "descripcion_servicio_adicional_pt" => $descripciones["PT"] ?? null,
    "descripcion_servicio_adicional_it" => $descripciones["IT"] ?? null
  ];

  $consulta = "INSERT INTO servicios_adicionales 
              (nombre, nombre_en, nombre_pt, nombre_it, descripcion_servicio_adicional, descripcion_servicio_adicional_en, descripcion_servicio_adicional_pt, descripcion_servicio_adicional_it) 
              VALUES 
              (:nombre, :nombre_en, :nombre_pt, :nombre_it, :descripcion_servicio_adicional, :descripcion_servicio_adicional_en, :descripcion_servicio_adicional_pt, :descripcion_servicio_adicional_it)";

  $comando = $pdo->prepare($consulta);
  $resultado = $comando->execute($data);

  return $resultado ? $pdo->lastInsertId() : 0;
}

/**
 * Editar servicio adicional existente
 * @param int $idServiciosAdicionales ID del servicio
 * @param array $nombres Array con claves ES, EN, PT, IT
 * @param array $descripciones Array con claves ES, EN, PT, IT
 */
function editaServicioAdicional($idServiciosAdicionales, $nombres, $descripciones)
{
  require("conexion.php");

  $data = [
    "idServiciosAdicionales" => $idServiciosAdicionales,
    "nombre" => $nombres["ES"] ?? "",
    "nombre_en" => $nombres["EN"] ?? null,
    "nombre_pt" => $nombres["PT"] ?? null,
    "nombre_it" => $nombres["IT"] ?? null,
    "descripcion_servicio_adicional" => $descripciones["ES"] ?? "",
    "descripcion_servicio_adicional_en" => $descripciones["EN"] ?? null,
    "descripcion_servicio_adicional_pt" => $descripciones["PT"] ?? null,
    "descripcion_servicio_adicional_it" => $descripciones["IT"] ?? null
  ];

  $consulta = "UPDATE servicios_adicionales 
              SET nombre = :nombre, 
                  nombre_en = :nombre_en, 
                  nombre_pt = :nombre_pt, 
                  nombre_it = :nombre_it,
                  descripcion_servicio_adicional = :descripcion_servicio_adicional,
                  descripcion_servicio_adicional_en = :descripcion_servicio_adicional_en,
                  descripcion_servicio_adicional_pt = :descripcion_servicio_adicional_pt,
                  descripcion_servicio_adicional_it = :descripcion_servicio_adicional_it
              WHERE idServiciosAdicionales = :idServiciosAdicionales";

  $comando = $pdo->prepare($consulta);
  $resultado = $comando->execute($data);

  return $resultado ? $comando->rowCount() : 0;
}

/**
 * Eliminar servicio adicional
 * @param int $idServiciosAdicionales ID del servicio a eliminar
 */
function eliminaServicioAdicional($idServiciosAdicionales)
{
  require("conexion.php");
  $data = ["idServiciosAdicionales" => $idServiciosAdicionales];

  // Bloquear eliminación si está asignado a salidas/servicios
  $check = $pdo->prepare("SELECT COUNT(*) AS total FROM servicio_salidas_adicionales WHERE idServiciosAdicionales = :idServiciosAdicionales");
  $check->execute($data);
  $uso = (int)($check->fetch(PDO::FETCH_ASSOC)["total"] ?? 0);
  if ($uso > 0) {
    return -1; // No se puede eliminar porque está asignado
  }

  // Limpiar posibles relaciones auxiliares y eliminar el registro
  $consulta2 = "DELETE FROM servicios_adicionales_categoria WHERE idServiciosAdicionales = :idServiciosAdicionales";
  $comando2 = $pdo->prepare($consulta2);
  $comando2->execute($data);

  $consulta3 = "DELETE FROM servicios_adicionales WHERE idServiciosAdicionales = :idServiciosAdicionales";
  $comando3 = $pdo->prepare($consulta3);
  $resultado = $comando3->execute($data);

  return $resultado ? $comando3->rowCount() : 0;
}

?>
