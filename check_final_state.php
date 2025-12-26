<?php
require_once 'vendor/autoload.php';
use phpseclib3\Net\SSH2;

$ssh = new SSH2('185.173.111.212', 65002);
$ssh->login('u925692129', 'Nueva$312');

echo "Verificando el estado de la BD en producción...\n\n";

// Verificar cuántos registros hay ahora
$output = $ssh->exec('php -r "
\\$mysqli = new mysqli(\"localhost\", \"u925692129_metelebrasil\", \"Cambiar2026\", \"u925692129_metelebrasil\");
\\$result = \\$mysqli->query(\"SELECT COUNT(*) as total FROM servicio_img\");
\\$row = \\$result->fetch_assoc();
echo \"Total registros: \" . \\$row[\"total\"] . PHP_EOL;

\\$result = \\$mysqli->query(\"SELECT COUNT(DISTINCT idServicio) as servicios FROM servicio_img\");
\\$row = \\$result->fetch_assoc();
echo \"Servicios: \" . \\$row[\"servicios\"] . PHP_EOL;

echo PHP_EOL . \"Top 10:\\n\";
\\$result = \\$mysqli->query(\"SELECT idServicio, COUNT(*) as cant FROM servicio_img GROUP BY idServicio ORDER BY cant DESC LIMIT 10\");
while (\\$row = \\$result->fetch_assoc()) {
    echo \"  ID \" . \\$row[\"idServicio\"] . \": \" . \\$row[\"cant\"] . \" imágenes\\n\";
}
"');

echo $output;

$ssh->disconnect();
?>
