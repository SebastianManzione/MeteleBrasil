<?php
$input = '/home/u925692129/servicio_img.sql';
$output = '/home/u925692129/servicio_img_clean.sql';

$content = file_get_contents($input);
$content = preg_replace('/\x00+/', '', $content);
file_put_contents($output, $content);

echo "✅ Archivo limpiado: " . strlen($content) . " bytes\n";
?>
