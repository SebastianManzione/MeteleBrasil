<?php
/**
 * Script para verificar y sincronizar BD en producción usando SSH
 * Ejecuta comandos remotamente via phpseclib3
 */

require_once 'vendor/autoload.php';

use phpseclib3\Net\SSH2;

$ssh = new SSH2('185.173.111.212', 65002);
if (!$ssh->login('u925692129', 'Nueva$312')) {
    die("❌ Error: No se puede conectar a SSH\n");
}

echo "✅ Conectado a servidor de producción\n\n";

// Ejecutar comando para verificar estado de BD
echo "=== VERIFICANDO ESTADO DE BD EN PRODUCCIÓN ===\n\n";

$output = $ssh->exec('php -r "
    \$mysqli = new mysqli(\"localhost\", \"u925692129_metelebrasil\", \"Cambiar2026\", \"u925692129_metelebrasil\");
    if (\$mysqli->connect_error) {
        echo \"Error: \" . \$mysqli->connect_error . PHP_EOL;
        exit(1);
    }
    
    // Contar imágenes totales
    \$result = \$mysqli->query(\"SELECT COUNT(*) as total FROM servicio_img\");
    \$row = \$result->fetch_assoc();
    echo \"Total de imágenes en BD: \" . \$row[\"total\"] . PHP_EOL;
    
    // Contar servicios con imágenes
    \$result = \$mysqli->query(\"SELECT COUNT(DISTINCT idServicio) as servicios FROM servicio_img\");
    \$row = \$result->fetch_assoc();
    echo \"Servicios con imágenes: \" . \$row[\"servicios\"] . PHP_EOL;
    
    // Listar primeros 5 servicios sin imágenes
    echo PHP_EOL . \"Servicios sin imágenes:\" . PHP_EOL;
    \$result = \$mysqli->query(\"
        SELECT s.idServicio, s.nombre_servicio 
        FROM servicio s 
        LEFT JOIN servicio_img si ON s.idServicio = si.idServicio 
        WHERE si.idImg IS NULL 
        LIMIT 5
    \");
    
    if (\$result->num_rows > 0) {
        while (\$row = \$result->fetch_assoc()) {
            echo \"  - ID \" . \$row[\"idServicio\"] . \": \" . \$row[\"nombre_servicio\"] . PHP_EOL;
        }
    } else {
        echo \"  ✓ Todos los servicios tienen imágenes\" . PHP_EOL;
    }
"');

echo $output;

// Verificar archivos de imagen en el servidor
echo "\n=== VERIFICANDO ARCHIVOS DE IMAGEN EN PRODUCCIÓN ===\n\n";

$output = $ssh->exec('ls -la /home/u925692129/public_html/admin/classes/imgServicio/ 2>/dev/null | head -20');
echo "Primeras imágenes en servidor:\n";
echo $output;

$output = $ssh->exec('find /home/u925692129/public_html/admin/classes/imgServicio/ -type f | wc -l');
echo "\nTotal de archivos de imagen (servicios): " . trim($output) . "\n";

$ssh->disconnect();
?>
