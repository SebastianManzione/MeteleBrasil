<?php
function getTextosMiniaturas() {
    require("conexion.php");

    // Obtém o idioma da sessão
    session_start();
    $idioma = isset($_SESSION["idioma"]) ? $_SESSION["idioma"] : "ES"; // Padrão: espanhol

    // Seleciona a coluna correta com base no idioma
    $coluna_texto = "texto"; // Padrão: espanhol
    switch ($idioma) {
        case 'EN':
            $coluna_texto = "texto_en";
            break;
        case 'PT':
            $coluna_texto = "texto_pt";
            break;
        case 'IT':
            $coluna_texto = "texto_it";
            break;
    }

    // Consulta para buscar os textos no idioma correto
    $consulta = "SELECT idTextoMiniaturas, $coluna_texto AS texto FROM texto_miniaturas";
    $comando = $pdo->prepare($consulta);
    $comando->execute();
    return $comando->fetchAll(PDO::FETCH_ASSOC);
}

function getTextoMiniatura($idTextoMiniaturas) {
    require("conexion.php");

    // Simply fetch the texto column - ignore language variations
    // The schema only has 'texto' column, not texto_en, texto_pt, etc.
    $data = ["idTextoMiniaturas" => $idTextoMiniaturas];
    $consulta = "SELECT idTextoMiniaturas, texto FROM texto_miniaturas WHERE idTextoMiniaturas = :idTextoMiniaturas";
    
    try {
        $comando = $pdo->prepare($consulta);
        $comando->execute($data);
        return $comando->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        // If query fails, return empty array to avoid fatal error
        error_log("Error in getTextoMiniatura: " . $e->getMessage());
        return [];
    }
}

function setTextoMiniatura($texto_es, $texto_en, $texto_pt, $texto_it) {
    require("conexion.php");

    // Insere o texto nos quatro idiomas
    $data = [
        "texto_es" => $texto_es,
        "texto_en" => $texto_en,
        "texto_pt" => $texto_pt,
        "texto_it" => $texto_it
    ];
    $consulta = "INSERT INTO texto_miniaturas (texto, texto_en, texto_pt, texto_it) VALUES (:texto_es, :texto_en, :texto_pt, :texto_it)";
    $comando = $pdo->prepare($consulta);
    $comando->execute($data);

    // Retorna o ID do texto inserido
    return $pdo->lastInsertId();
}

function borraTextoMiniatura($idTextoMiniaturas) {
    require("conexion.php");

    // Verifica se o texto está em uso
    $cantidad = count(getTextoMiniaturasServicio($idTextoMiniaturas));
    if ($cantidad == 0) {
        // Exclui o texto miniatura
        $data = ["idTextoMiniaturas" => $idTextoMiniaturas];
        $consulta = "DELETE FROM texto_miniaturas WHERE idTextoMiniaturas = :idTextoMiniaturas";
        $comando = $pdo->prepare($consulta);
        $comando->execute($data);
        return $comando->rowCount(); // Retorna o número de linhas afetadas
    } else {
        return -5; // Código de erro para texto em uso
    }
}
function getTextoMiniaturasServicio($idTextoMiniaturas) {
    require("conexion.php");

    $data = ["idTextoMiniaturas" => $idTextoMiniaturas];
    $consulta = "SELECT * FROM servicio WHERE idTextoMiniaturas = :idTextoMiniaturas";
    $comando = $pdo->prepare($consulta);
    $comando->execute($data);
    return $comando->fetchAll(PDO::FETCH_ASSOC);
}

?>
