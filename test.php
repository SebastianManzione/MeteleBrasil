<?php
echo "PHP FUNCIONA\n";
echo "BD disponible\n";

\$mysqli = new mysqli("localhost", "u925692129_metelebrasil", "Cambiar2026", "u925692129_metelebrasil");
if (\$mysqli->connect_error) {
    echo "Error BD: " . \$mysqli->connect_error . "\n";
} else {
    echo "Conectado a BD\n";
    
    // Verificar estado actual
    \$result = \$mysqli->query("SELECT COUNT(*) as total FROM servicio_img");
    \$row = \$result->fetch_assoc();
    echo "Registros actuales: " . \$row["total"] . "\n";
}
?>