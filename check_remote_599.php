<?php
require_once 'vendor/autoload.php';
use phpseclib3\Net\SSH2;

$ssh = new SSH2('185.173.111.212', 65002);
if (!$ssh->login('u925692129', 'Nueva$312')) {
    die("Error SSH");
}

$path = '/home/u925692129/domains/metelebrasil.com/public_html/admin/classes/imgServicio';

// Contar archivos 599_
$out = $ssh->exec("find $path -type f -name '599_*' | wc -l");
echo "Archivos 599_* en prod: " . trim($out) . "\n";

// Listar primeros 15
$out = $ssh->exec("find $path -type f -name '599_*' | sort | head -n 15");
echo $out;

$ssh->disconnect();
?>