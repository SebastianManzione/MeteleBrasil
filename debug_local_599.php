<?php
$mysqli = new mysqli('localhost', 'root', '', 'metelebrasil');
if ($mysqli->connect_error) {
    die("Error BD local: " . $mysqli->connect_error);
}

$idServicio = 599;

$result = $mysqli->query("SELECT COUNT(*) as total FROM servicio_img WHERE idServicio = $idServicio");
$row = $result->fetch_assoc();
echo "Local servicio $idServicio: total registros = " . $row['total'] . "\n";

$result = $mysqli->query("SELECT idImgServicio, ruta, portada FROM servicio_img WHERE idServicio = $idServicio ORDER BY idImgServicio");
while ($r = $result->fetch_assoc()) {
    echo $r['idImgServicio'] . " | " . $r['ruta'] . " | portada=" . $r['portada'] . "\n";
}
?>