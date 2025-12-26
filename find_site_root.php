<?php
require_once 'vendor/autoload.php';
use phpseclib3\Net\SSH2;

$ssh = new SSH2('185.173.111.212', 65002);
$ssh->login('u925692129', 'Nueva$312');

echo "=== BUSCANDO DÓNDE ESTÁ EL SITIO REAL ===\n\n";

// Ver directorios principales
$output = $ssh->exec('ls -la /home/u925692129/ | grep -E "^d"');
echo "Directorios en /home/u925692129/:\n";
echo $output;

// Ver si hay dominio
$output = $ssh->exec('ls -la / | grep -E "home|public|www"');
echo "\nDirectorios en raíz:\n";
echo $output;

// Buscar index.php
$output = $ssh->exec('find /home/u925692129 -name "index.php" -type f 2>/dev/null');
echo "\nArchivos index.php encontrados:\n";
echo $output;

// Ver contenido de public_html
$output = $ssh->exec('ls -lah /home/u925692129/public_html/ 2>/dev/null | tail -20');
echo "\nContenido de public_html (últimas líneas):\n";
echo $output;

$ssh->disconnect();
?>
