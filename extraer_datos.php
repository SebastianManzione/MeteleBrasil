<?php
/**
 * Script para extraer servicios, salidas, tarifas y reservas del backup antiguo
 */

$backup_file = 'C:\Users\SisteMANZ\Documents\Downloads\metelebr_metelebrasil (1).sql';

// Leer el archivo
$sql_content = file_get_contents($backup_file);

// Extraer servicios disponibles
preg_match_all("/INSERT INTO `servicio`.*?VALUES\s*\((.*?)\);/s", $sql_content, $matches);

echo "=" . str_repeat("=", 80) . "\n";
echo "SERVICIOS DISPONIBLES EN EL BACKUP\n";
echo "=" . str_repeat("=", 80) . "\n\n";

if (!empty($matches[1])) {
    $services = array();
    $lines = explode("\n", $matches[1][0]);
    
    foreach ($lines as $line) {
        if (preg_match("/\((\d+),\s*'([^']+)'", $line, $m)) {
            $id = $m[1];
            $nombre = $m[2];
            $services[$id] = $nombre;
        }
    }
    
    foreach ($services as $id => $nombre) {
        echo "ID: $id | Nombre: $nombre\n";
    }
} else {
    echo "No se encontraron servicios.\n";
}

echo "\n" . str_repeat("=", 80) . "\n";
echo "RESERVAS DISPONIBLES EN EL BACKUP\n";
echo "=" . str_repeat("=", 80) . "\n\n";

preg_match_all("/INSERT INTO `reservas`.*?VALUES\s*\((.*?)\);/s", $sql_content, $matches);

if (!empty($matches[1])) {
    $reservas = array();
    $lines = explode("\n", $matches[1][0]);
    
    $count = 0;
    foreach ($lines as $line) {
        if (preg_match("/\((\d+),\s*'([^']+)'", $line, $m)) {
            $id = $m[1];
            $codigo = $m[2];
            echo "ID Reserva: $id | Código: $codigo\n";
            $count++;
            if ($count >= 20) break; // Mostrar solo las primeras 20
        }
    }
} else {
    echo "No se encontraron reservas.\n";
}

echo "\n¿Cuál servicio ID quieres recuperar? ¿Cuál ID de reserva quieres?\n";
echo "Ingresa los IDs que necesitas.\n";
?>
