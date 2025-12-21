<?php
function getTiposCancelaciones() {
    require("conexion.php");

    // Obtém o idioma da sessão
    session_start();
    $idioma = isset($_SESSION["idioma"]) ? $_SESSION["idioma"] : "ES"; // Padrão: espanhol

    // Consulta para buscar as cancelaciones
    $consulta = "SELECT * FROM cancelaciones";
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

function getTipoCancelaciones($idCancelacion) {
    require("conexion.php");

    // Obtém o idioma da sessão
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    $idioma = isset($_SESSION["idioma"]) ? $_SESSION["idioma"] : "ES"; // Padrão: espanhol

    $data = ["idCancelacion" => $idCancelacion];
    $consulta = "SELECT * FROM cancelaciones WHERE idCancelacion = :idCancelacion";
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

function setTextoCancelacion($texto_es, $texto_pt, $texto_en, $texto_it) {
    require("conexion.php");

    $data = [
        "texto_es" => $texto_es,
        "texto_pt" => $texto_pt,
        "texto_en" => $texto_en,
        "texto_it" => $texto_it
    ];

    $consulta = "INSERT INTO cancelaciones (texto, texto_pt, texto_en, texto_it) VALUES (:texto_es, :texto_pt, :texto_en, :texto_it)";
    $comando = $pdo->prepare($consulta);
    $comando->execute($data);

    $id = $pdo->lastInsertId();
    return $id;
}

?>
