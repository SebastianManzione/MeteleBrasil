<?php

try {
    // Detectar entorno: usa APP_ENV si está definido; si no, fuerza prod
    $envFromVar = getenv('APP_ENV');
    $productionMode = $envFromVar ? ($envFromVar === 'prod') : true;

    if ($productionMode) {
        $GLOBALS['pdo'] = new PDO('mysql:host=localhost;dbname=u925692129_metelebrasil;charset=utf8mb4', 'u925692129_metelebrasil', 'Cambiar2026');
        $GLOBALS['pdo']->exec("SET NAMES utf8mb4");
    } else {
        $GLOBALS['pdo'] = new PDO('mysql:host=localhost;dbname=metelebrasil;charset=utf8mb4', 'root', '');
        $GLOBALS['pdo']->exec("SET NAMES utf8mb4");
    }
    
    // Inicializar tablas requeridas (crear si no existen)
    require_once(__DIR__ . '/inicializar_tablas.php');
    $init = new InitializeTables();
    $init->inicializar();
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}

// Also make it available as local $pdo for existing code
$pdo = $GLOBALS['pdo'];

?>






		
