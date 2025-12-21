<?php
function getAccesibilidades() {
    require("conexion.php");

    // Obtém o idioma da sessão
    session_start();
    $idioma = isset($_SESSION["idioma"]) ? $_SESSION["idioma"] : "ES"; // Padrão: espanhol

    // Consulta para buscar as acessibilidades
    $consulta = "SELECT * FROM accesibilidad";
    $comando = $pdo->prepare($consulta);
    $comando->execute();
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

    // Ajusta os dados com base no idioma
    foreach ($resultado as &$row) {
        switch ($idioma) {
            case 'EN': // Inglês
                $row['texto'] = $row['texto_en'] ?? $row['texto'];
                break;
            case 'PT': // Português
                $row['texto'] = $row['texto_pt'] ?? $row['texto'];
                break;
            case 'IT': // Italiano
                $row['texto'] = $row['texto_it'] ?? $row['texto'];
                break;
            // Caso padrão (ES ou qualquer outro idioma)
            default:
                // Mantém o valor original em espanhol
                break;
        }
    }

    return $resultado;
}
function getAccesibilidad($idAccesibilidad) {
    require("conexion.php");

    // Obtém o idioma da sessão
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $idioma = isset($_SESSION["idioma"]) ? $_SESSION["idioma"] : "ES"; // Padrão: espanhol

    $data = ["idAccesibilidad" => $idAccesibilidad];
    $consulta = "SELECT * FROM accesibilidad WHERE idAccesibilidad = :idAccesibilidad";
    $comando = $pdo->prepare($consulta);
    $comando->execute($data);
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

    // Ajusta os dados com base no idioma
    foreach ($resultado as &$row) {
        switch ($idioma) {
            case 'EN': // Inglês
                $row['texto'] = $row['texto_en'] ?? $row['texto'];
                break;
            case 'PT': // Português
                $row['texto'] = $row['texto_pt'] ?? $row['texto'];
                break;
            case 'IT': // Italiano
                $row['texto'] = $row['texto_it'] ?? $row['texto'];
                break;
            // Caso padrão (ES ou qualquer outro idioma)
            default:
                // Mantém o valor original em espanhol
                break;
        }
    }

    return $resultado;
}

function getAllAccesiblidadesSalidas($idAccesibilidad) {
    require("conexion.php");
    $data = ["idAccesibilidad" => $idAccesibilidad];
    $consulta = "SELECT * FROM servicios_salidas WHERE idAccesibilidad = :idAccesibilidad";
    $comando = $pdo->prepare($consulta);
    $comando->execute($data);
    return $comando->fetchAll(PDO::FETCH_ASSOC);
}

function setTextoAccesibilidad($texto_es, $texto_en, $texto_pt, $texto_it) {
    require("conexion.php");

    $data = [
        "texto_es" => $texto_es,
        "texto_en" => $texto_en,
        "texto_pt" => $texto_pt,
        "texto_it" => $texto_it
    ];

    $consulta = "INSERT INTO accesibilidad (texto, texto_en, texto_pt, texto_it) VALUES (:texto_es, :texto_en, :texto_pt, :texto_it)";
    $comando = $pdo->prepare($consulta);
    $comando->execute($data);

    $id = $pdo->lastInsertId();
    return $id;
}


function borraTextoAccesibilidad($idAccesibilidad) {
    require("conexion.php");
    $data = ["idAccesibilidad" => $idAccesibilidad];
    $consulta = "DELETE FROM accesibilidad WHERE idAccesibilidad = :idAccesibilidad";
    $comando = $pdo->prepare($consulta);
    $comando->execute($data);
    return $comando->rowCount();
}


?>
