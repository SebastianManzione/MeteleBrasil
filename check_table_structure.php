<?php
$mysqli = new mysqli('localhost', 'root', '', 'metelebrasil');
$result = $mysqli->query('DESCRIBE servicio_img');
echo "Columnas en servicio_img:\n";
while ($row = $result->fetch_assoc()) {
    echo "  - " . $row['Field'] . " (" . $row['Type'] . ")\n";
}
?>
