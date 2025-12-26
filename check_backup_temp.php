<?php
// Quick check backup temp DB
try {
    $pdo = new PDO('mysql:host=localhost;dbname=metele_backup_temp', 'root', '');
    echo "Conectado a metele_backup_temp\n\n";
    
    // Ver qué servicios hay
    $stmt = $pdo->query('SELECT COUNT(*) FROM servicio');
    echo "Total servicios en backup: " . $stmt->fetchColumn() . "\n\n";
    
    // Buscar los específicos
    $stmt = $pdo->query('SELECT idServicio, nombre_servicio FROM servicio WHERE idServicio IN (740,743,613,602,615)');
    $found = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Servicios encontrados:\n";
    foreach($found as $r) {
        echo "  - ".$r['idServicio'].": ".$r['nombre_servicio']."\n";
    }
    
    if (empty($found)) {
        echo "\n⚠ No se encontraron los servicios buscados\n";
        echo "Mostrando primeros 10 servicios en backup:\n";
        $stmt = $pdo->query('SELECT idServicio, nombre_servicio FROM servicio ORDER BY idServicio LIMIT 10');
        while($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "  - ".$r['idServicio'].": ".$r['nombre_servicio']."\n";
        }
    }
    
} catch(Exception $e) {
    echo "ERROR: ".$e->getMessage()."\n";
}
?>
