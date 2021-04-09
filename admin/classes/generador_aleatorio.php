<?php

function GeneradorAleatorio($cantidadLetras,$cantidadNumeros){

$string="ABCDEFGHIJKLMNOPQRSTUVWXYZ";
$numero_random= rand(100,10000000);

$letras_random= substr(str_shuffle($string),0,($cantidadLetras));

$resultado= substr($letras_random, 0,3).substr($numero_random, 0,($cantidadNumeros));

return $resultado;

}

    
?>