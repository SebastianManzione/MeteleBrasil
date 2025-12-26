<?php
require_once 'vendor/autoload.php';
use phpseclib3\Net\SSH2;

$ssh = new SSH2('185.173.111.212', 65002);
$ssh->login('u925692129', 'Nueva$312');

echo "=== VERIFICANDO ESTRUCTURA DE DIRECTORIOS EN PRODUCCIÓN ===\n\n";

// Ver qué hay en public_html
$output = $ssh->exec('ls -la /home/u925692129/public_html/ | head -20');
echo "Contenido de /home/u925692129/public_html/:\n";
echo $output;

// Ver si existe index.php
$output = $ssh->exec('test -f /home/u925692129/public_html/index.php && echo "EXISTS" || echo "NOT_FOUND"');
echo "\nArchivo index.php: " . trim($output) . "\n";

// Ver el admin
$output = $ssh->exec('test -d /home/u925692129/public_html/admin && echo "EXISTS" || echo "NOT_FOUND"');
echo "Directorio /admin: " . trim($output) . "\n";

$ssh->disconnect();
?>
