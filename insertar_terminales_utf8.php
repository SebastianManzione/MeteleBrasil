<?php
/**
 * Script para insertar terminales con UTF-8 correcto
 * Ejecutar: php insertar_terminales_utf8.php
 */

require_once("admin/classes/conexion.php");

// Forzar UTF-8
$pdo->exec("SET NAMES utf8mb4");
$pdo->exec("SET CHARACTER SET utf8mb4");

echo "=== Limpiando datos anteriores ===\n";
$pdo->exec("DELETE FROM terminal_transporte");
$pdo->exec("DELETE FROM tipo_transporte WHERE idTipoTransporte > 0");

echo "=== Insertando tipos de transporte ===\n";
$tipos = [
    [1, 'Micro/Bus', 'fa-bus'],
    [2, 'Avión', 'fa-plane'],
    [3, 'Tren', 'fa-train'],
    [4, 'Barco/Ferry', 'fa-ship']
];

$stmt = $pdo->prepare("INSERT INTO tipo_transporte (idTipoTransporte, nombre, icono, habilitado) VALUES (?, ?, ?, 1)");
foreach ($tipos as $tipo) {
    $stmt->execute($tipo);
    echo "✓ {$tipo[1]}\n";
}

echo "\n=== Insertando terminales ===\n";

$terminales = [
    // ARGENTINA - BUENOS AIRES
    ['Terminal de Retiro', 'Av. Ramos Mejía 1680, Buenos Aires', 'Buenos Aires', NULL, -34.588886, -58.373993, 1],
    ['Aeropuerto Ezeiza', 'Autopista Teniente General Pablo Riccheri, Ezeiza', 'Buenos Aires', 'EZE', -34.822222, -58.535833, 2],
    ['Aeropuerto Aeroparque', 'Av. Rafael Obligado, Buenos Aires', 'Buenos Aires', 'AEP', -34.559171, -58.415600, 2],
    ['Estación Retiro - Mitre', 'Av. Ramos Mejía 1358, Buenos Aires', 'Buenos Aires', NULL, -34.591944, -58.374444, 3],
    
    // ARGENTINA - OTRAS
    ['Terminal Mar del Plata', NULL, 'Mar del Plata', NULL, -38.002530, -57.550130, 1],
    ['Aeropuerto Astor Piazzolla', NULL, 'Mar del Plata', 'MDQ', -37.934167, -57.573333, 2],
    ['Terminal Bariloche', NULL, 'San Carlos de Bariloche', NULL, -41.133333, -71.300000, 1],
    ['Aeropuerto Bariloche', NULL, 'San Carlos de Bariloche', 'BRC', -41.151111, -71.157500, 2],
    ['Terminal del Sol Mendoza', NULL, 'Mendoza', NULL, -32.889722, -68.845278, 1],
    ['Aeropuerto El Plumerillo', NULL, 'Mendoza', 'MDZ', -32.831667, -68.793333, 2],
    
    // BRASIL - RÍO
    ['Rodoviária Novo Rio', 'Av. Francisco Bicalho, 1, Santo Cristo', 'Río de Janeiro', NULL, -22.898333, -43.222222, 1],
    ['Aeroporto Santos Dumont', 'Praça Senador Salgado Filho', 'Río de Janeiro', 'SDU', -22.910461, -43.163133, 2],
    ['Aeroporto do Galeão', 'Av. Vinte de Janeiro', 'Río de Janeiro', 'GIG', -22.809444, -43.250556, 2],
    
    // BRASIL - SÃO PAULO
    ['Terminal Tietê', 'Av. Cruzeiro do Sul, 1800', 'São Paulo', NULL, -23.514722, -46.626111, 1],
    ['Terminal Barra Funda', 'Rua Mário de Andrade, 664', 'São Paulo', NULL, -23.525556, -46.669444, 1],
    ['Aeroporto de Congonhas', 'Av. Washington Luís', 'São Paulo', 'CGH', -23.626111, -46.655833, 2],
    ['Aeroporto de Guarulhos', 'Rod. Hélio Smidt, Cumbica', 'São Paulo', 'GRU', -23.432222, -46.469444, 2],
    
    // BRASIL - OUTRAS
    ['Rodoviária de Porto Alegre', NULL, 'Porto Alegre', NULL, -30.027222, -51.228611, 1],
    ['Aeroporto Salgado Filho', NULL, 'Porto Alegre', 'POA', -29.994444, -51.171389, 2],
    ['Terminal Rita Maria', NULL, 'Florianópolis', NULL, -27.593889, -48.518611, 1],
    ['Aeroporto Hercílio Luz', NULL, 'Florianópolis', 'FLN', -27.670278, -48.552500, 2],
    
    // URUGUAY
    ['Terminal Tres Cruces', NULL, 'Montevideo', NULL, -34.893889, -56.166667, 1],
    ['Aeropuerto de Carrasco', NULL, 'Montevideo', 'MVD', -34.838333, -56.030556, 2],
    ['Terminal Punta del Este', NULL, 'Punta del Este', NULL, -34.966667, -54.950000, 1],
    ['Aeropuerto Capitán Corbeta', NULL, 'Punta del Este', 'PDP', -34.855000, -55.093333, 2],
    
    // CHILE
    ['Terminal San Borja', NULL, 'Santiago', NULL, -33.451111, -70.662222, 1],
    ['Terminal Alameda', NULL, 'Santiago', NULL, -33.448611, -70.678056, 1],
    ['Aeropuerto Arturo Merino Benítez', NULL, 'Santiago', 'SCL', -33.393056, -70.785833, 2],
    
    // PERÚ
    ['Terminal Plaza Norte', NULL, 'Lima', NULL, -11.994444, -77.062500, 1],
    ['Aeropuerto Jorge Chávez', NULL, 'Lima', 'LIM', -12.021944, -77.114444, 2],
    
    // PARAGUAY
    ['Terminal de Ómnibus Asunción', NULL, 'Asunción', NULL, -25.286389, -57.633611, 1],
    ['Aeropuerto Silvio Pettirossi', NULL, 'Asunción', 'ASU', -25.240000, -57.519444, 2]
];

$stmt = $pdo->prepare("INSERT INTO terminal_transporte 
    (nombre, direccion, ciudad, codigo_iata, latitud, longitud, idTipoTransporte, habilitado) 
    VALUES (?, ?, ?, ?, ?, ?, ?, 1)");

foreach ($terminales as $terminal) {
    $stmt->execute($terminal);
    echo "✓ {$terminal[0]} ({$terminal[2]})\n";
}

echo "\n=== Verificando ===\n";
$result = $pdo->query("SELECT COUNT(*) as total FROM terminal_transporte")->fetch();
echo "Total terminales: {$result['total']}\n";

$result = $pdo->query("SELECT nombre FROM tipo_transporte ORDER BY idTipoTransporte")->fetchAll();
echo "\nTipos de transporte:\n";
foreach ($result as $row) {
    echo "  - {$row['nombre']}\n";
}

echo "\n¡Listo! Datos insertados con UTF-8 correcto.\n";
?>
