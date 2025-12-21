<?php
$mysqli = new mysqli("localhost", "metelebr_admin", "EjGLC(7~lolq7WeW", "metelebr_metelebrasil");
if ($mysqli->connect_error) {
    die("Error de conexión: " . $mysqli->connect_error);
}

// Solo para ver las columnas
$result = $mysqli->query("DESCRIBE servicio");
if(!$result){
    die("Error en DESCRIBE: " . $mysqli->error);
}

echo "<pre>";
while($row = $result->fetch_assoc()){
    print_r($row);
}
echo "</pre>";

$mysqli->close();
?>