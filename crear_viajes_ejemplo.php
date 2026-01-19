<?php
// Conexión directa
$host = 'localhost';
$db = 'metelebrasil_experimental';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== Creando viajes de ejemplo ===\n\n";
    echo "✓ Conexión establecida\n";
    
    // Actualizar URL del menú Viajes
    echo "Actualizando menú...\n";
    $sqlMenu = "UPDATE admin_menu SET route='viajesTransporteLista.php' WHERE id=44";
    $pdo->exec($sqlMenu);
    echo "✓ Menú actualizado\n";
    
// Viajes de ejemplo (solo ruta 8 que tiene terminal 66)
$viajes = [
    [
        'idRuta' => 8,
        'fecha' => '2026-02-20',
        'hora_salida' => '22:00:00',
        'asientos_totales' => 45,
        'asientos_disponibles' => 45,
        'idTerminalOrigen' => 66,
        'idTerminalDestino' => 66
    ],
    [
        'idRuta' => 8,
        'fecha' => '2026-02-27',
        'hora_salida' => '22:00:00',
        'asientos_totales' => 45,
        'asientos_disponibles' => 30,
        'idTerminalOrigen' => 66,
        'idTerminalDestino' => 66
    ],
    [
        'idRuta' => 8,
        'fecha' => '2026-03-05',
        'hora_salida' => '22:00:00',
        'asientos_totales' => 45,
        'asientos_disponibles' => 45,
        'idTerminalOrigen' => 66,
        'idTerminalDestino' => 66
    ]
];

$sqlInsert = "INSERT INTO viaje_transporte 
              (idRuta, fecha, hora_salida, asientos_totales, asientos_disponibles, idTerminalOrigen, idTerminalDestino) 
              VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $pdo->prepare($sqlInsert);

$count = 0;
foreach ($viajes as $viaje) {
    $stmt->execute([
        $viaje['idRuta'],
        $viaje['fecha'],
        $viaje['hora_salida'],
        $viaje['asientos_totales'],
        $viaje['asientos_disponibles'],
        $viaje['idTerminalOrigen'],
        $viaje['idTerminalDestino']
    ]);
    $count++;
}

echo "✓ $count viajes creados\n\n";

// Verificar viajes creados
echo "--- Viajes creados ---\n";
$sqlVerify = "SELECT v.idViaje, r.nombre as ruta, v.fecha, v.hora_salida, 
              v.asientos_disponibles, v.asientos_totales, v.habilitado,
              to1.nombre as terminal_origen, td1.nombre as terminal_destino
              FROM viaje_transporte v
              INNER JOIN ruta_transporte r ON v.idRuta = r.idRuta
              LEFT JOIN terminal_transporte to1 ON v.idTerminalOrigen = to1.idTerminal
              LEFT JOIN terminal_transporte td1 ON v.idTerminalDestino = td1.idTerminal
              ORDER BY v.fecha, v.hora_salida";
$result = $pdo->query($sqlVerify);
$viajesCreados = $result->fetchAll(PDO::FETCH_ASSOC);

foreach ($viajesCreados as $v) {
    $estado = $v['habilitado'] ? 'activo' : 'deshabilitado';
    echo sprintf(
        "ID %d: %s - %s %s - %d/%d asientos - %s\n",
        $v['idViaje'],
        $v['ruta'],
        date('d/m/Y', strtotime($v['fecha'])),
        substr($v['hora_salida'], 0, 5),
        $v['asientos_disponibles'],
        $v['asientos_totales'],
        $estado
    );
}

echo "\n✓ Proceso completado\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
    exit(1);
}

// Viajes para ruta 8 (Rosario - Florianópolis - Río)
$viajes = [
    [
        'idRuta' => 8,
        'fecha_salida' => '2026-02-20',
        'hora_salida' => '22:00:00',
        'asientos_totales' => 45,
        'asientos_disponibles' => 45,
        'estado' => 'activo'
    ],
    [
        'idRuta' => 8,
        'fecha_salida' => '2026-02-27',
        'hora_salida' => '22:00:00',
        'asientos_totales' => 45,
        'asientos_disponibles' => 30,
        'estado' => 'activo'
    ],
    [
        'idRuta' => 8,
        'fecha_salida' => '2026-03-05',
        'hora_salida' => '22:00:00',
        'asientos_totales' => 45,
        'asientos_disponibles' => 45,
        'estado' => 'activo'
    ],
    // Viajes para ruta 1 (Buenos Aires - Córdoba)
    [
        'idRuta' => 1,
        'fecha_salida' => '2026-01-18',
        'hora_salida' => '08:00:00',
        'asientos_totales' => 50,
        'asientos_disponibles' => 50,
        'estado' => 'activo'
    ],
    [
        'idRuta' => 1,
        'fecha_salida' => '2026-01-18',
        'hora_salida' => '15:00:00',
        'asientos_totales' => 50,
        'asientos_disponibles' => 25,
        'estado' => 'activo'
    ],
    [
        'idRuta' => 1,
        'fecha_salida' => '2026-01-19',
        'hora_salida' => '08:00:00',
        'asientos_totales' => 50,
        'asientos_disponibles' => 50,
        'estado' => 'activo'
    ],
    // Viajes para ruta 5 (Buenos Aires - Santiago) - Avión
    [
        'idRuta' => 5,
        'fecha_salida' => '2026-01-20',
        'hora_salida' => '10:30:00',
        'asientos_totales' => 180,
        'asientos_disponibles' => 180,
        'estado' => 'activo'
    ],
    [
        'idRuta' => 5,
        'fecha_salida' => '2026-01-20',
        'hora_salida' => '16:45:00',
        'asientos_totales' => 180,
        'asientos_disponibles' => 120,
        'estado' => 'activo'
    ],
    [
        'idRuta' => 5,
        'fecha_salida' => '2026-01-21',
        'hora_salida' => '10:30:00',
        'asientos_totales' => 180,
        'asientos_disponibles' => 180,
        'estado' => 'activo'
    ]
];

$sqlInsert = "INSERT INTO viaje_transporte 
              (idRuta, fecha_salida, hora_salida, asientos_totales, asientos_disponibles, estado) 
              VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $pdo->prepare($sqlInsert);

$count = 0;
foreach ($viajes as $viaje) {
    $stmt->execute([
        $viaje['idRuta'],
        $viaje['fecha_salida'],
        $viaje['hora_salida'],
        $viaje['asientos_totales'],
        $viaje['asientos_disponibles'],
        $viaje['estado']
    ]);
    $count++;
}

echo "✓ $count viajes creados\n\n";

// Verificar viajes creados
echo "--- Viajes creados ---\n";
$sqlVerify = "SELECT v.idViaje, r.nombre as ruta, v.fecha_salida, v.hora_salida, 
              v.asientos_disponibles, v.asientos_totales, v.estado
              FROM viaje_transporte v
              INNER JOIN ruta_transporte r ON v.idRuta = r.idRuta
              ORDER BY v.fecha_salida, v.hora_salida";
$result = $pdo->query($sqlVerify);
$viajesCreados = $result->fetchAll(PDO::FETCH_ASSOC);

foreach ($viajesCreados as $v) {
    echo sprintf(
        "ID %d: %s - %s %s - %d/%d asientos - Estado: %s\n",
        $v['idViaje'],
        $v['ruta'],
        date('d/m/Y', strtotime($v['fecha_salida'])),
        substr($v['hora_salida'], 0, 5),
        $v['asientos_disponibles'],
        $v['asientos_totales'],
        $v['estado']
    );
}

echo "\n✓ Proceso completado\n";
?>
