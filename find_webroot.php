<?php
require_once 'vendor/autoload.php';
use phpseclib3\Net\SSH2;

$ssh = new SSH2('185.173.111.212', 65002);
$ssh->login('u925692129', 'Nueva$312');

echo "=== BUSCANDO RAÍZ WEB CORRECTA ===\n\n";

// Verificar acceso HTTP
echo "Acceso HTTP/HTTPS:\n";
$domains = [
    'metelebrasil.com',
    'www.metelebrasil.com',
];

foreach ($domains as $domain) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://$domain/index.php");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "  https://$domain/index.php: HTTP $code\n";
}

// Ver dónde apunta el dominio en Apache
echo "\nConfigración de Apache:\n";
$output = $ssh->exec('grep -r "metelebrasil" /etc/apache2/conf.d/ 2>/dev/null | head -5');
echo $output ? $output : "No encontrado en conf.d\n";

// Ver cpanel config
echo "\nDocumentRoot del dominio:\n";
$output = $ssh->exec('grep -A 5 "metelebrasil.com" /usr/local/apache/conf/httpd.conf 2>/dev/null | head -10');
echo $output ? $output : "No encontrado en httpd.conf\n";

// Ver qué hay en public_html del usuario
echo "\nListado de /home/u925692129/domains/metelebrasil.com/:\n";
$output = $ssh->exec('ls -la /home/u925692129/domains/metelebrasil.com/');
echo $output;

// Intentar acceder via SSH a un archivo que sabemos existe
echo "\nVerificando archivos principales:\n";
$output = $ssh->exec('test -f /home/u925692129/domains/metelebrasil.com/public_html/index.php && echo "EXISTE" || echo "NO EXISTE"');
echo "index.php: " . trim($output) . "\n";

$ssh->disconnect();
?>
