<?php
function getAllCategorias() {
    require("conexion.php");
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    $idioma = isset($_SESSION["idioma"]) ? $_SESSION["idioma"] : "ES";

    $consulta = "SELECT * FROM categoria_servicio WHERE idCategoria_servicio > 0";
    $comando = $pdo->prepare($consulta);
    $comando->execute();
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

    foreach ($resultado as &$row) {
        switch ($idioma) {
            case 'EN':
                $row['nombre_categoria_servicio'] = $row['nombre_categoria_servicio_en'] ?? $row['nombre_categoria_servicio'];
                $row['descripcion_categoria_servicio'] = $row['descripcion_categoria_servicio_en'] ?? $row['descripcion_categoria_servicio'];
                $row['descripcionCorta_categoria_servicio'] = $row['descripcionCorta_categoria_servicio_en'] ?? $row['descripcionCorta_categoria_servicio'];
                $row['guia'] = $row['guia_en'] ?? $row['guia'];
                break;
            case 'PT':
                $row['nombre_categoria_servicio'] = $row['nombre_categoria_servicio_pt'] ?? $row['nombre_categoria_servicio'];
                $row['descripcion_categoria_servicio'] = $row['descripcion_categoria_servicio_pt'] ?? $row['descripcion_categoria_servicio'];
                $row['descripcionCorta_categoria_servicio'] = $row['descripcionCorta_categoria_servicio_pt'] ?? $row['descripcionCorta_categoria_servicio'];
                $row['guia'] = $row['guia_pt'] ?? $row['guia'];
                break;
            case 'IT':
                $row['nombre_categoria_servicio'] = $row['nombre_categoria_servicio_it'] ?? $row['nombre_categoria_servicio'];
                $row['descripcion_categoria_servicio'] = $row['descripcion_categoria_servicio_it'] ?? $row['descripcion_categoria_servicio'];
                $row['descripcionCorta_categoria_servicio'] = $row['descripcionCorta_categoria_servicio_it'] ?? $row['descripcionCorta_categoria_servicio'];
                $row['guia'] = $row['guia_it'] ?? $row['guia'];
                break;
            default:
                break;
        }
    }

    return $resultado;
}

function getCategorias()
{

  require("conexion.php");
  if (session_status() == PHP_SESSION_NONE) {
    session_start();
  }
  $idioma = isset($_SESSION["idioma"]) ? $_SESSION["idioma"] : "ES";

  $consulta = "select * from categoria_servicio WHERE idCategoria_servicio>0 AND habilitado=1";

  $comando = $pdo->prepare($consulta);

  $comando->execute();
  $cuenta_col = $comando->columnCount();

  $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
  
  // Ajustar nombres según idioma
  foreach ($resultado as &$row) {
    switch ($idioma) {
      case 'EN':
        $row['nombre_categoria_servicio'] = $row['nombre_categoria_servicio_en'] ?? $row['nombre_categoria_servicio'];
        break;
      case 'PT':
        $row['nombre_categoria_servicio'] = $row['nombre_categoria_servicio_pt'] ?? $row['nombre_categoria_servicio'];
        break;
      case 'IT':
        $row['nombre_categoria_servicio'] = $row['nombre_categoria_servicio_it'] ?? $row['nombre_categoria_servicio'];
        break;
      default:
        break;
    }
  }
  
  return $resultado;


}

function getCategoriasLimit6() {
    require("conexion.php");

    // Consulta para buscar as categorias (limite de 6) - apenas colunas existentes
    $consulta = "SELECT 
                    idCategoria_servicio, 
                    nombre_categoria_servicio, 
                    descripcion_categoria_servicio, 
                    descripcionCorta_categoria_servicio, 
                    nViajeros, 
                    img_categoria_servicio, 
                    guia
                 FROM categoria_servicio 
                 WHERE idCategoria_servicio > 0 AND habilitado = 1 
                 LIMIT 6";

    $comando = $pdo->prepare($consulta);
    $comando->execute();
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

    return $resultado;
}

function getCategoriasLimit612() {
    require("conexion.php");

    // Consulta para buscar as categorias (limite de 6, começando do 7º)
    $consulta = "SELECT * FROM categoria_servicio 
                 WHERE idCategoria_servicio > 0 AND habilitado = 1 
                 LIMIT 6 OFFSET 6"; // Retorna 6 categorias, começando do 7º registro

    $comando = $pdo->prepare($consulta);
    $comando->execute();
    return $comando->fetchAll(PDO::FETCH_ASSOC);
}

function getCategoria($idCategoria_servicio)
{

  require("conexion.php");
  $data = ["idCategoria_servicio" => $idCategoria_servicio];
  $consulta = "select * from categoria_servicio WHERE idCategoria_servicio=:idCategoria_servicio ";

  $comando = $pdo->prepare($consulta);

  $comando->execute($data);
  $cuenta_col = $comando->columnCount();

  $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
  // Imprimir en pantalla
  return $resultado;


}

function updateCategoria(
    $idCategoria_servicio,
    $nombre_es, $nombre_en, $nombre_pt, $nombre_it,
    $descripcion_es, $descripcion_en, $descripcion_pt, $descripcion_it,
    $descripcionCorta_es, $descripcionCorta_en, $descripcionCorta_pt, $descripcionCorta_it,
    $nViajeros
) {
    require("conexion.php");

    $data = [
        ":idCategoria_servicio" => $idCategoria_servicio,
        ":nombre_es" => $nombre_es,
        ":nombre_en" => $nombre_en,
        ":nombre_pt" => $nombre_pt,
        ":nombre_it" => $nombre_it,
        ":descripcion_es" => $descripcion_es,
        ":descripcion_en" => $descripcion_en,
        ":descripcion_pt" => $descripcion_pt,
        ":descripcion_it" => $descripcion_it,
        ":descripcionCorta_es" => $descripcionCorta_es,
        ":descripcionCorta_en" => $descripcionCorta_en,
        ":descripcionCorta_pt" => $descripcionCorta_pt,
        ":descripcionCorta_it" => $descripcionCorta_it,
        ":nViajeros" => $nViajeros
    ];

    $consulta = "UPDATE categoria_servicio SET
        nombre_categoria_servicio = :nombre_es,
        nombre_categoria_servicio_en = :nombre_en,
        nombre_categoria_servicio_pt = :nombre_pt,
        nombre_categoria_servicio_it = :nombre_it,
        descripcion_categoria_servicio = :descripcion_es,
        descripcion_categoria_servicio_en = :descripcion_en,
        descripcion_categoria_servicio_pt = :descripcion_pt,
        descripcion_categoria_servicio_it = :descripcion_it,
        descripcionCorta_categoria_servicio = :descripcionCorta_es,
        descripcionCorta_categoria_servicio_en = :descripcionCorta_en,
        descripcionCorta_categoria_servicio_pt = :descripcionCorta_pt,
        descripcionCorta_categoria_servicio_it = :descripcionCorta_it,
        nViajeros = :nViajeros
        WHERE idCategoria_servicio = :idCategoria_servicio";

    try {
        $comando = $pdo->prepare($consulta);
        $comando->execute($data);
        return $comando->rowCount(); // Retorna o número de linhas afetadas
    } catch (PDOException $e) {
        // Log do erro (opcional)
        error_log("Erro ao atualizar categoria: " . $e->getMessage());
        return false; // Retorna false em caso de erro
    }
}

function habilitarCategoria($idCategoria_servicio)
{


  require("conexion.php");
  $data = ["idCategoria_servicio" => $idCategoria_servicio];
  $consulta = "UPDATE categoria_servicio SET habilitado=1 WHERE idCategoria_servicio=:idCategoria_servicio ";

  $comando = $pdo->prepare($consulta);

  $comando->execute($data);

  $id = $pdo->lastInsertId();
  $cuenta_col = $comando->columnCount();
  $cuenta_row = $comando->rowCount();
  $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

  return $id;


}

function desHabilitarCategoria($idCategoria_servicio)
{


  require("conexion.php");
  $data = ["idCategoria_servicio" => $idCategoria_servicio];
  $consulta = "UPDATE categoria_servicio SET habilitado=0 WHERE idCategoria_servicio=:idCategoria_servicio ";

  $comando = $pdo->prepare($consulta);

  $comando->execute($data);

  $id = $pdo->lastInsertId();
  $cuenta_col = $comando->columnCount();
  $cuenta_row = $comando->rowCount();
  $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

  return $id;


}

function borraGuiaCategoria($idCategoria_servicio)
{

  require("conexion.php");
  $data = ["idCategoria_servicio" => $idCategoria_servicio];

  $consulta = "select * from categoria_servicio WHERE idCategoria_servicio=:idCategoria_servicio ";

  $comando = $pdo->prepare($consulta);

  $comando->execute($data);
  $cuenta_col = $comando->columnCount();

  $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
  // Imprimir en pantalla

  $guia = $resultado[0]["guia"];

  $resuUnlik = unlink("classes/guias/" . $guia);


}

function uploadGuia($file, $categoria_id, $idioma) {
    $target_dir = "classes/guias/";
    $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $file_name = "guia_{$categoria_id}_{$idioma}.{$file_extension}";
    $target_file = $target_dir . $file_name;

    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        return $file_name;
    } else {
        throw new Exception("Erro ao fazer upload do arquivo.");
    }
}

function updateGuiaCategoria($categoria_id, $guia_name, $idioma) {
    require("conexion.php");

    $column = "guia";
    if ($idioma === 'en') {
        $column = "guia_en";
    } elseif ($idioma === 'pt') {
        $column = "guia_pt";
    } elseif ($idioma === 'it') {
        $column = "guia_it";
    }

    $data = [
        ":idCategoria_servicio" => $categoria_id,
        ":guia" => $guia_name
    ];

    $consulta = "UPDATE categoria_servicio SET $column = :guia WHERE idCategoria_servicio = :idCategoria_servicio";
    $comando = $pdo->prepare($consulta);
    $comando->execute($data);

    return $comando->rowCount();
}

function saveCategoria(
    $nombre_es, $nombre_en, $nombre_pt, $nombre_it,
    $descripcion_es, $descripcion_en, $descripcion_pt, $descripcion_it,
    $descripcionCorta_es, $descripcionCorta_en, $descripcionCorta_pt, $descripcionCorta_it,
    $nViajeros
) {
    require("conexion.php");

    $data = [
        ":nombre_es" => $nombre_es,
        ":nombre_en" => $nombre_en,
        ":nombre_pt" => $nombre_pt,
        ":nombre_it" => $nombre_it,
        ":descripcion_es" => $descripcion_es,
        ":descripcion_en" => $descripcion_en,
        ":descripcion_pt" => $descripcion_pt,
        ":descripcion_it" => $descripcion_it,
        ":descripcionCorta_es" => $descripcionCorta_es,
        ":descripcionCorta_en" => $descripcionCorta_en,
        ":descripcionCorta_pt" => $descripcionCorta_pt,
        ":descripcionCorta_it" => $descripcionCorta_it,
        ":nViajeros" => $nViajeros,
        ":habilitado" => 1,
        ":img_categoria_servicio" => "",
        ":orden" => 0,
        ":guia" => ""
    ];

    $consulta = "INSERT INTO categoria_servicio (
        nombre_categoria_servicio, nombre_categoria_servicio_en, nombre_categoria_servicio_pt, nombre_categoria_servicio_it,
        descripcion_categoria_servicio, descripcion_categoria_servicio_en, descripcion_categoria_servicio_pt, descripcion_categoria_servicio_it,
        descripcionCorta_categoria_servicio, descripcionCorta_categoria_servicio_en, descripcionCorta_categoria_servicio_pt, descripcionCorta_categoria_servicio_it,
        nViajeros, habilitado, img_categoria_servicio, orden, guia
    ) VALUES (
        :nombre_es, :nombre_en, :nombre_pt, :nombre_it,
        :descripcion_es, :descripcion_en, :descripcion_pt, :descripcion_it,
        :descripcionCorta_es, :descripcionCorta_en, :descripcionCorta_pt, :descripcionCorta_it,
        :nViajeros, :habilitado, :img_categoria_servicio, :orden, :guia
    )";

    try {
        $comando = $pdo->prepare($consulta);
        $comando->execute($data);
        return $pdo->lastInsertId();
    } catch (PDOException $e) {
        throw new Exception("Erro ao salvar categoria: " . $e->getMessage());
    }
}

function uploadImagem($file, $categoria_id) {
    $target_dir = "img/categoria_servicio/";
    $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $file_name = "categoria_{$categoria_id}.{$file_extension}";
    $target_file = $target_dir . $file_name;

    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        return $file_name;
    } else {
        throw new Exception("Erro ao fazer upload da imagem.");
    }
}

function updateImagemCategoria($categoria_id, $img_name) {
    require("conexion.php");

    $data = [
        ":idCategoria_servicio" => $categoria_id,
        ":img_categoria_servicio" => $img_name
    ];

    $consulta = "UPDATE categoria_servicio SET img_categoria_servicio = :img_categoria_servicio WHERE idCategoria_servicio = :idCategoria_servicio";
    $comando = $pdo->prepare($consulta);
    $comando->execute($data);

    return $comando->rowCount();
}

?>
