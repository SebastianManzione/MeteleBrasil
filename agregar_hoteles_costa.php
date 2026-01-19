<?php
echo "=== AGREGANDO HOTELES Y TERMINALES DE LA COSTA ===\n\n";

try {
    $pdo = new PDO('mysql:host=localhost;dbname=metelebrasil_experimental;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "1. Conectado a metelebrasil_experimental\n\n";
    
    // ========== PASO 1: INSERTAR TERMINALES ==========
    echo "2. Creando terminales de la costa atlántica...\n";
    
    $terminales = [
        [
            'nombre' => 'Terminal de Ómnibus de Tapiales',
            'direccion' => 'Av. Eva Perón 5000, Tapiales, Buenos Aires',
            'ciudad' => 'Tapiales',
            'estado' => 'Buenos Aires',
            'pais' => 'Argentina',
            'latitud' => -34.7000,
            'longitud' => -58.5000,
            'idTipo' => 1 // Bus
        ],
        [
            'nombre' => 'Terminal de Ómnibus de Liniers',
            'direccion' => 'Av. Gral. Paz, Liniers, CABA',
            'ciudad' => 'Liniers',
            'estado' => 'CABA',
            'pais' => 'Argentina',
            'latitud' => -34.6400,
            'longitud' => -58.5200,
            'idTipo' => 1 // Bus
        ],
        [
            'nombre' => 'Terminal de San Clemente del Tuyú',
            'direccion' => 'Av. Costanera y Calle 1, San Clemente del Tuyú',
            'ciudad' => 'San Clemente del Tuyú',
            'estado' => 'Buenos Aires',
            'pais' => 'Argentina',
            'latitud' => -36.3600,
            'longitud' => -56.7200,
            'idTipo' => 1 // Bus
        ],
        [
            'nombre' => 'Terminal de Las Toninas',
            'direccion' => 'Av. 10 y Av. Costanera, Las Toninas',
            'ciudad' => 'Las Toninas',
            'estado' => 'Buenos Aires',
            'pais' => 'Argentina',
            'latitud' => -36.4800,
            'longitud' => -56.7000,
            'idTipo' => 1 // Bus
        ],
        [
            'nombre' => 'Terminal de Mar del Tuyú',
            'direccion' => 'Av. Costanera y Calle 50, Mar del Tuyú',
            'ciudad' => 'Mar del Tuyú',
            'estado' => 'Buenos Aires',
            'pais' => 'Argentina',
            'latitud' => -36.5700,
            'longitud' => -56.6800,
            'idTipo' => 1 // Bus
        ]
    ];
    
    $idsTerminales = [];
    foreach ($terminales as $term) {
        $stmt = $pdo->prepare("
            INSERT INTO terminal_transporte 
            (nombre, direccion, ciudad, estado, pais, idTipoTransporte, latitud, longitud)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $term['nombre'],
            $term['direccion'],
            $term['ciudad'],
            $term['estado'],
            $term['pais'],
            $term['idTipo'],
            $term['latitud'],
            $term['longitud']
        ]);
        $idsTerminales[$term['ciudad']] = $pdo->lastInsertId();
        echo "   ✓ Terminal: {$term['nombre']} (ID: {$idsTerminales[$term['ciudad']]})\n";
    }
    
    echo "\n3. Creando hoteles de la costa atlántica...\n";
    
    // ========== PASO 2: INSERTAR HOTELES ==========
    $hoteles = [
        [
            'nombre' => 'Hotel Costa Atlántica - San Clemente',
            'ciudad' => 'San Clemente del Tuyú',
            'estado' => 'Buenos Aires',
            'pais' => 'Argentina',
            'direccion' => 'Av. Costanera 1250, San Clemente del Tuyú',
            'latitud' => -36.3580,
            'longitud' => -56.7180,
            'telefono' => '+54 2252 421234',
            'email' => 'contacto@costaatlantica-sc.com.ar',
            'estrellas' => 3,
            'descripcion' => 'Hotel frente al mar con vista panorámica a la costa atlántica. Habitaciones cómodas con aire acondicionado.',
            'servicios' => 'WiFi gratuito, Estacionamiento, Desayuno buffet, Piscina climatizada, Solarium',
            'check_in' => '14:00',
            'check_out' => '10:00',
            'habitaciones_total' => 40,
            'precio_desde' => 8500.00,
            'idMoneda' => 1 // ARS
        ],
        [
            'nombre' => 'Apart Hotel Las Toninas',
            'ciudad' => 'Las Toninas',
            'estado' => 'Buenos Aires',
            'pais' => 'Argentina',
            'direccion' => 'Calle 10 N° 654, Las Toninas',
            'latitud' => -36.4750,
            'longitud' => -56.6950,
            'telefono' => '+54 2246 430123',
            'email' => 'reservas@apartlastoninas.com.ar',
            'estrellas' => 2,
            'descripcion' => 'Apart hotel ideal para familias, a 2 cuadras de la playa. Departamentos equipados con cocina.',
            'servicios' => 'WiFi, Parrilla, Estacionamiento, Cocina equipada, Ropa de cama',
            'check_in' => '15:00',
            'check_out' => '10:00',
            'habitaciones_total' => 25,
            'precio_desde' => 6000.00,
            'idMoneda' => 1 // ARS
        ],
        [
            'nombre' => 'Hotel Mar del Tuyú Resort',
            'ciudad' => 'Mar del Tuyú',
            'estado' => 'Buenos Aires',
            'pais' => 'Argentina',
            'direccion' => 'Av. Costanera 2100, Mar del Tuyú',
            'latitud' => -36.5680,
            'longitud' => -56.6750,
            'telefono' => '+54 2246 421890',
            'email' => 'info@mardeltuyuresort.com',
            'estrellas' => 4,
            'descripcion' => 'Resort de lujo frente al mar con spa, restaurante gourmet y actividades recreativas para toda la familia.',
            'servicios' => 'WiFi Premium, Spa & Wellness, Restaurante, Bar, Piscina cubierta y al aire libre, Gimnasio, Animación infantil',
            'check_in' => '14:00',
            'check_out' => '11:00',
            'habitaciones_total' => 80,
            'precio_desde' => 15000.00,
            'idMoneda' => 1 // ARS
        ],
        [
            'nombre' => 'Hostel Joven San Clemente',
            'ciudad' => 'San Clemente del Tuyú',
            'estado' => 'Buenos Aires',
            'pais' => 'Argentina',
            'direccion' => 'Calle 3 N° 890, San Clemente del Tuyú',
            'latitud' => -36.3620,
            'longitud' => -56.7250,
            'telefono' => '+54 2252 420987',
            'email' => 'hola@hosteljoven.com.ar',
            'estrellas' => 2,
            'descripcion' => 'Hostel juvenil con ambiente relajado, ideal para viajeros mochileros. A 3 cuadras de la playa.',
            'servicios' => 'WiFi gratuito, Cocina compartida, Sala común, Parrilla, Estacionamiento para bicicletas',
            'check_in' => '13:00',
            'check_out' => '10:00',
            'habitaciones_total' => 15,
            'precio_desde' => 3500.00,
            'idMoneda' => 1 // ARS
        ],
        [
            'nombre' => 'Hotel Familiar Las Toninas',
            'ciudad' => 'Las Toninas',
            'estado' => 'Buenos Aires',
            'pais' => 'Argentina',
            'direccion' => 'Av. 12 y Av. Costanera, Las Toninas',
            'latitud' => -36.4820,
            'longitud' => -56.7020,
            'telefono' => '+54 2246 432100',
            'email' => 'contacto@hotelfamiliarlastoninas.com',
            'estrellas' => 3,
            'descripcion' => 'Hotel familiar con excelente atención personalizada. Ideal para estadías prolongadas.',
            'servicios' => 'WiFi, Desayuno incluido, Estacionamiento cubierto, Juegos para niños, Parrilla',
            'check_in' => '14:00',
            'check_out' => '10:00',
            'habitaciones_total' => 30,
            'precio_desde' => 7200.00,
            'idMoneda' => 1 // ARS
        ]
    ];
    
    foreach ($hoteles as $hotel) {
        $stmt = $pdo->prepare("
            INSERT INTO hoteles 
            (nombre, ciudad, estado, pais, direccion, latitud, longitud, telefono, email, 
             estrellas, descripcion, servicios, check_in, check_out, habitaciones_total, 
             precio_desde, idMoneda, habilitado)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)
        ");
        $stmt->execute([
            $hotel['nombre'],
            $hotel['ciudad'],
            $hotel['estado'],
            $hotel['pais'],
            $hotel['direccion'],
            $hotel['latitud'],
            $hotel['longitud'],
            $hotel['telefono'],
            $hotel['email'],
            $hotel['estrellas'],
            $hotel['descripcion'],
            $hotel['servicios'],
            $hotel['check_in'],
            $hotel['check_out'],
            $hotel['habitaciones_total'],
            $hotel['precio_desde'],
            $hotel['idMoneda']
        ]);
        echo "   ✓ Hotel: {$hotel['nombre']} (ID: {$pdo->lastInsertId()})\n";
    }
    
    echo "\n✓✓✓ HOTELES Y TERMINALES DE LA COSTA AGREGADOS\n\n";
    
    // Resumen
    echo "RESUMEN:\n";
    echo "• 5 Terminales de bus agregados\n";
    echo "• 5 Hoteles agregados (2-4 estrellas)\n";
    echo "• Ruta: Tapiales → Liniers → San Clemente → Las Toninas → Mar del Tuyú\n";
    echo "• Precios desde ARS 3,500 hasta ARS 15,000 por noche\n\n";
    
} catch (PDOException $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}
?>
