<?php

try {
    $host = gethostname();
    $envFromVar = getenv('APP_ENV');
    $productionMode = $envFromVar ? ($envFromVar === 'prod') : (strpos($host, 'server') !== false);

    if ($productionMode) {
        $GLOBALS['pdo'] = new PDO('mysql:host=localhost;dbname=metelebr_metelebrasil;charset=utf8mb4', 'metelebr_admin', 'EjGLC(7~lolq7WeW');
        $GLOBALS['pdo']->exec("SET CHARACTER SET utf8");
    } else {
        $GLOBALS['pdo'] = new PDO('mysql:host=localhost;dbname=metelebrasil;charset=utf8mb4', 'root', '');
        $GLOBALS['pdo']->exec("SET CHARACTER SET utf8");
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}

// Also make it available as local $pdo for existing code
$pdo = $GLOBALS['pdo'];

?>






		
