<?php
/**
 * Insertar hoteles de ejemplo - Versión simplificada
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('admin/classes/conexion.php');

// Configurar UTF-8
$pdo->exec("SET NAMES utf8mb4");

echo "🏨 Creando hoteles de ejemplo...\n\n";

$hoteles = [
    [
        'nombre' => 'Hotel Paradise Mendoza',
        'direccion' => 'Calle Las Heras 123',
        'ciudad' => 'Mendoza',
        'estado' => 'Mendoza',
        'pais' => 'Argentina',
        'codigo_iata' => 'HPM',
        'telefono' => '+54 261 423-4567',
        'email' => 'reservas@paradisemendoza.com',
        'descripcion' => 'Hotel boutique con vistas a los Andes. Ideal para paquetes de viñedos y degustación de vinos.',
        'latitud' => -32.8892,
        'longitud' => -68.8452
    ],
    [
        'nombre' => 'Hostería La Posada',
        'direccion' => 'Avenida Victoria Aguirre 470',
        'ciudad' => 'Puerto Iguazú',
        'estado' => 'Misiones',
        'pais' => 'Argentina',
        'codigo_iata' => 'HLP',
        'telefono' => '+54 3757 421-234',
        'email' => 'contacto@hosteriaposada.com',
        'descripcion' => 'Hostería familiar a minutos de las Cataratas. Perfecto para tours de naturaleza.',
        'latitud' => -25.5968,
        'longitud' => -54.5777
    ],
    [
        'nombre' => 'Hotel Floripa Beach Resort',
        'direccion' => 'Avenida das Rendeiras 2000',
        'ciudad' => 'Florianópolis',
        'estado' => 'Santa Catarina',
        'pais' => 'Brasil',
        'codigo_iata' => 'HFB',
        'telefono' => '+55 48 3234-5678',
        'email' => 'reservas@floripabeach.com.br',
        'descripcion' => 'Resort frente al mar con piscinas y actividades acuáticas. Ideal para paquetes de playa.',
        'latitud' => -27.5969,
        'longitud' => -48.5495
    ],
    [
        'nombre' => 'Resort Rio Spa Luxe',
        'direccion' => 'Avenida Atlântica 1800',
        'ciudad' => 'Río de Janeiro',
        'estado' => 'Rio de Janeiro',
        'pais' => 'Brasil',
        'codigo_iata' => 'RRS',
        'telefono' => '+55 21 2234-5678',
        'email' => 'reservas@riospaluxe.com.br',
        'descripcion' => 'Resort de lujo en Copacabana con spa completo. Perfecto para paquetes premium.',
        'latitud' => -22.9068,
        'longitud' => -43.1729
    ],
    [
        'nombre' => 'Hotel Boutique Asunción',
        'direccion' => 'Calle Palma 568',
        'ciudad' => 'Asunción',
        'estado' => 'Asunción',
        'pais' => 'Paraguay',
        'codigo_iata' => 'HBA',
        'telefono' => '+595 21 444-5678',
        'email' => 'info@hotelboutiqueasuncion.com.py',
        'descripcion' => 'Hotel boutique en el centro histórico. Ideal para turismo cultural.',
        'latitud' => -25.2637,
        'longitud' => -57.5759
    ],
    [
        'nombre' => 'Hotel Rosario Gran',
        'direccion' => 'Boulevard Oroño 1524',
        'ciudad' => 'Rosario',
        'estado' => 'Santa Fe',
        'pais' => 'Argentina',
        'codigo_iata' => 'HRG',
        'telefono' => '+54 341 423-4567',
        'email' => 'reservas@rosariogran.com',
        'descripcion' => 'Hotel céntrico con todas las comodidades. Base perfecta para circuitos regionales.',
        'latitud' => -32.9442,
        'longitud' => -60.6505
    ]
];

$creados = 0;
$errores = 0;

foreach ($hoteles as $hotel) {
    try {
        // Verificar si ya existe
        $checkStmt = $pdo->prepare("SELECT idParada FROM parada WHERE nombre = :nombre AND tipo = 'hotel'");
        $checkStmt->execute(['nombre' => $hotel['nombre']]);
        
        if ($checkStmt->fetch()) {
            echo "⚠️  {$hotel['nombre']} ya existe, omitiendo...\n";
            continue;
        }
        
        // Insertar hotel
        $sql = "INSERT INTO parada (nombre, tipo, direccion, ciudad, estado, pais, latitud, longitud, codigo_iata, telefono, email, descripcion, habilitado) 
                VALUES (:nombre, 'hotel', :direccion, :ciudad, :estado, :pais, :latitud, :longitud, :codigo_iata, :telefono, :email, :descripcion, 1)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'nombre' => $hotel['nombre'],
            'direccion' => $hotel['direccion'],
            'ciudad' => $hotel['ciudad'],
            'estado' => $hotel['estado'],
            'pais' => $hotel['pais'],
            'latitud' => $hotel['latitud'],
            'longitud' => $hotel['longitud'],
            'codigo_iata' => $hotel['codigo_iata'],
            'telefono' => $hotel['telefono'],
            'email' => $hotel['email'],
            'descripcion' => $hotel['descripcion']
        ]);
        
        $creados++;
        echo "✅ {$hotel['nombre']} - {$hotel['ciudad']}, {$hotel['pais']}\n";
        
    } catch (Exception $e) {
        $errores++;
        echo "❌ Error creando {$hotel['nombre']}: " . $e->getMessage() . "\n";
    }
}

echo "\n" . str_repeat("=", 50) . "\n";
echo "📊 Resumen:\n";
echo "   ✅ Hoteles creados: {$creados}\n";
echo "   ❌ Errores: {$errores}\n";
echo "\n🎉 ¡Proceso completado!\n\n";
echo "👉 Verifica los hoteles en: http://localhost/metelebrasil_dev/admin/hotelLista.php\n";
