<?php
/**
 * Script para corregir encoding UTF-8 de rutas de transporte
 * Fecha: 2026-01-16
 */

require_once("config/config.php");
require_once("admin/classes/conexion.php");

// Configurar conexión UTF-8
$pdo->exec("SET NAMES utf8mb4");
$pdo->exec("SET CHARACTER SET utf8mb4");

echo "Corrigiendo encoding de rutas de transporte...\n\n";

// Primero eliminar las rutas existentes
$pdo->exec("DELETE FROM ruta_transporte");
echo "✓ Rutas anteriores eliminadas\n";

// Reiniciar auto_increment
$pdo->exec("ALTER TABLE ruta_transporte AUTO_INCREMENT = 1");
echo "✓ Auto_increment reseteado\n\n";

// Datos corregidos con UTF-8
$rutas = [
    // RUTAS DE BUS
    [
        'nombre' => 'Buenos Aires - Mar del Plata',
        'nombre_en' => 'Buenos Aires - Mar del Plata',
        'nombre_pt' => 'Buenos Aires - Mar del Plata',
        'nombre_it' => 'Buenos Aires - Mar del Plata',
        'descripcion' => 'Ruta directa desde Terminal Retiro hasta Mar del Plata con servicios cama ejecutivo y semi cama.',
        'descripcion_en' => 'Direct route from Retiro Terminal to Mar del Plata with executive and semi-sleeper services.',
        'descripcion_pt' => 'Rota direta do Terminal Retiro a Mar del Plata com serviços executivo e semi-leito.',
        'descripcion_it' => 'Percorso diretto dal Terminal Retiro a Mar del Plata con servizi executivo e semi-cuccetta.',
        'idTipoTransporte' => 1,
        'duracion_estimada' => '5h 30min',
        'distancia_km' => 404
    ],
    [
        'nombre' => 'Buenos Aires - Bariloche',
        'nombre_en' => 'Buenos Aires - Bariloche',
        'nombre_pt' => 'Buenos Aires - Bariloche',
        'nombre_it' => 'Buenos Aires - Bariloche',
        'descripcion' => 'Viaje nocturno desde Buenos Aires hasta San Carlos de Bariloche con servicios cama y semi cama.',
        'descripcion_en' => 'Overnight trip from Buenos Aires to San Carlos de Bariloche with sleeper and semi-sleeper services.',
        'descripcion_pt' => 'Viagem noturna de Buenos Aires a San Carlos de Bariloche com serviços leito e semi-leito.',
        'descripcion_it' => 'Viaggio notturno da Buenos Aires a San Carlos de Bariloche con servizi cuccetta e semi-cuccetta.',
        'idTipoTransporte' => 1,
        'duracion_estimada' => '20h 00min',
        'distancia_km' => 1641
    ],
    [
        'nombre' => 'Buenos Aires - Córdoba',
        'nombre_en' => 'Buenos Aires - Córdoba',
        'nombre_pt' => 'Buenos Aires - Córdoba',
        'nombre_it' => 'Buenos Aires - Córdoba',
        'descripcion' => 'Ruta directa a la ciudad de Córdoba con múltiples frecuencias diarias.',
        'descripcion_en' => 'Direct route to Córdoba city with multiple daily frequencies.',
        'descripcion_pt' => 'Rota direta para a cidade de Córdoba com múltiplas frequências diárias.',
        'descripcion_it' => 'Percorso diretto per la città di Córdoba con multiple frequenze giornaliere.',
        'idTipoTransporte' => 1,
        'duracion_estimada' => '9h 30min',
        'distancia_km' => 710
    ],
    [
        'nombre' => 'Buenos Aires - Mendoza',
        'nombre_en' => 'Buenos Aires - Mendoza',
        'nombre_pt' => 'Buenos Aires - Mendoza',
        'nombre_it' => 'Buenos Aires - Mendoza',
        'descripcion' => 'Ruta nocturna hacia Mendoza capital con servicios premium disponibles.',
        'descripcion_en' => 'Overnight route to Mendoza capital with premium services available.',
        'descripcion_pt' => 'Rota noturna para Mendoza capital com serviços premium disponíveis.',
        'descripcion_it' => 'Percorso notturno verso Mendoza capitale con servizi premium disponibili.',
        'idTipoTransporte' => 1,
        'duracion_estimada' => '14h 00min',
        'distancia_km' => 1037
    ],
    
    // RUTAS DE AVIÓN
    [
        'nombre' => 'Buenos Aires (EZE) - Bariloche (BRC)',
        'nombre_en' => 'Buenos Aires (EZE) - Bariloche (BRC)',
        'nombre_pt' => 'Buenos Aires (EZE) - Bariloche (BRC)',
        'nombre_it' => 'Buenos Aires (EZE) - Bariloche (BRC)',
        'descripcion' => 'Vuelo directo desde Aeropuerto Internacional de Ezeiza hasta Aeropuerto de Bariloche.',
        'descripcion_en' => 'Direct flight from Ezeiza International Airport to Bariloche Airport.',
        'descripcion_pt' => 'Voo direto do Aeroporto Internacional de Ezeiza ao Aeroporto de Bariloche.',
        'descripcion_it' => 'Volo diretto dall\'Aeroporto Internazionale di Ezeiza all\'Aeroporto di Bariloche.',
        'idTipoTransporte' => 2,
        'duracion_estimada' => '2h 15min',
        'distancia_km' => 1345
    ],
    [
        'nombre' => 'Buenos Aires (AEP) - Iguazú (IGR)',
        'nombre_en' => 'Buenos Aires (AEP) - Iguazu (IGR)',
        'nombre_pt' => 'Buenos Aires (AEP) - Iguaçu (IGR)',
        'nombre_it' => 'Buenos Aires (AEP) - Iguazú (IGR)',
        'descripcion' => 'Vuelo directo desde Aeroparque Jorge Newbery hasta Aeropuerto de Iguazú.',
        'descripcion_en' => 'Direct flight from Jorge Newbery Airport to Iguazú Airport.',
        'descripcion_pt' => 'Voo direto do Aeroporto Jorge Newbery ao Aeroporto de Iguaçu.',
        'descripcion_it' => 'Volo diretto dall\'Aeroporto Jorge Newbery all\'Aeroporto di Iguazú.',
        'idTipoTransporte' => 2,
        'duracion_estimada' => '1h 50min',
        'distancia_km' => 1080
    ],
    
    // RUTA DE TREN
    [
        'nombre' => 'Buenos Aires Retiro - Tigre',
        'nombre_en' => 'Buenos Aires Retiro - Tigre',
        'nombre_pt' => 'Buenos Aires Retiro - Tigre',
        'nombre_it' => 'Buenos Aires Retiro - Tigre',
        'descripcion' => 'Tren de cercanías línea Mitre ramal Tigre. Servicio frecuente durante todo el día.',
        'descripcion_en' => 'Suburban train Mitre line Tigre branch. Frequent service throughout the day.',
        'descripcion_pt' => 'Trem suburbano linha Mitre ramal Tigre. Serviço frequente durante todo o dia.',
        'descripcion_it' => 'Treno suburbano linea Mitre ramo Tigre. Servizio frequente durante tutto il giorno.',
        'idTipoTransporte' => 3,
        'duracion_estimada' => '1h 10min',
        'distancia_km' => 32
    ]
];

// Preparar consulta
$sql = "INSERT INTO ruta_transporte (
    nombre, nombre_en, nombre_pt, nombre_it,
    descripcion, descripcion_en, descripcion_pt, descripcion_it,
    idTipoTransporte, idEmpresa, idPrestador,
    duracion_estimada, distancia_km, foto_principal, habilitado
) VALUES (
    :nombre, :nombre_en, :nombre_pt, :nombre_it,
    :descripcion, :descripcion_en, :descripcion_pt, :descripcion_it,
    :idTipoTransporte, :idEmpresa, :idPrestador,
    :duracion_estimada, :distancia_km, :foto_principal, :habilitado
)";

$stmt = $pdo->prepare($sql);

echo "Insertando rutas con UTF-8 correcto:\n";
foreach ($rutas as $ruta) {
    $ruta['idEmpresa'] = null;
    $ruta['idPrestador'] = null;
    $ruta['foto_principal'] = '';
    $ruta['habilitado'] = 1;
    
    $stmt->execute($ruta);
    echo "✓ {$ruta['nombre']}\n";
}

echo "\n¡Rutas corregidas exitosamente!\n";

// Verificar resultado
echo "\n--- Verificación ---\n";
$verificar = $pdo->query("SELECT idRuta, nombre, duracion_estimada FROM ruta_transporte ORDER BY idRuta");
foreach ($verificar as $row) {
    echo "{$row['idRuta']}. {$row['nombre']} ({$row['duracion_estimada']})\n";
}
?>
