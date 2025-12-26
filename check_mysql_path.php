<?php
require_once 'vendor/autoload.php';
use phpseclib3\Net\SSH2;

$ssh = new SSH2('185.173.111.212',65002); $ssh->login('u925692129','Nueva$312');
echo "Paths:\n";
echo $ssh->exec('which mysql');
echo $ssh->exec('which mariadb');
echo $ssh->exec('type mysql');
echo $ssh->exec('type mariadb');
?>