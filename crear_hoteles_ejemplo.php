<?php
/**
 * Script para agregar hoteles de ejemplo
 * Ejecutar vía: http://localhost/metelebrasil_dev/crear_hoteles_ejemplo.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once("admin/classes/conexion.php");

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Hoteles de Ejemplo</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        body { background: #f8f9fa; padding: 20px; }
        .container { background: white; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); padding: 30px; margin-top: 20px; }
        .success { color: #28a745; }
        .error { color: #dc3545; }
    </style>
</head>
<body>
<div class="container" style="max-width: 800px;">
    <h1><i class="fas fa-hotel"></i> Crear Hoteles de Ejemplo</h1>
    <hr>

    <?php
    // Definir hoteles de ejemplo
    $hotelesEjemplo = [
        [
            'nombre' => 'Hotel Paradise Mendoza',
            'direccion' => 'Calle Las Heras 123',
            'ciudad' => 'Mendoza',
            'estado' => 'Mendoza',
            'pais' => 'Argentina',
            'codigo_iata' => 'HPM',
            'telefono' => '+54 261 4205000',
            'email' => 'info@hotelparadise.com.ar',
            'descripcion' => 'Hotel de lujo con viñedos propios, spa y degustación de vinos',
            'latitud' => '-32.8892',
            'longitud' => '-68.8452'
        ],
        [
            'nombre' => 'Hostería La Posada',
            'direccion' => 'Avenida Victoria Aguirre 470',
            'ciudad' => 'Puerto Iguazú',
            'estado' => 'Misiones',
            'pais' => 'Argentina',
            'codigo_iata' => 'HLP',
            'telefono' => '+54 3757 420722',
            'email' => 'info@laposadaiguazu.com.ar',
            'descripcion' => 'Hostería cerca de las Cataratas del Iguazú con vista panorámica',
            'latitud' => '-25.5951',
            'longitud' => '-54.5783'
        ],
        [
            'nombre' => 'Hotel Floripa Beach Resort',
            'direccion' => 'Avenida Atlântica 2000',
            'ciudad' => 'Florianópolis',
            'estado' => 'Santa Catarina',
            'pais' => 'Brasil',
            'codigo_iata' => 'HFB',
            'telefono' => '+55 48 3027-8000',
            'email' => 'reservas@floriparesortt.com.br',
            'descripcion' => 'Resort frente a la playa con piscina infinita y restaurante gourmet',
            'latitud' => '-27.4240',
            'longitud' => '-48.5331'
        ],
        [
            'nombre' => 'Resort Rio Spa Luxe',
            'direccion' => 'Av. Niemeyer 2000, Leblon',
            'ciudad' => 'Río de Janeiro',
            'estado' => 'Río de Janeiro',
            'pais' => 'Brasil',
            'codigo_iata' => 'RRL',
            'telefono' => '+55 21 2172-1000',
            'email' => 'info@resortriosppa.com.br',
            'descripcion' => 'Resort 5 estrellas con vista a Río, spa y acceso a playas privadas',
            'latitud' => '-23.0186',
            'longitud' => '-43.2653'
        ],
        [
            'nombre' => 'Hotel Boutique Asunción',
            'direccion' => 'Calle Mariscal López 1500',
            'ciudad' => 'Asunción',
            'estado' => 'Asunción',
            'pais' => 'Paraguay',
            'codigo_iata' => 'HBA',
            'telefono' => '+595 21 214567',
            'email' => 'info@hotelboutiqu easuncion.com.py',
            'descripcion' => 'Hotel boutique con arquitectura colonial en el centro histórico',
            'latitud' => '-25.2637',
            'longitud' => '-57.5759'
        ],
        [
            'nombre' => 'Hotel Rosario Gran',
            'direccion' => 'Avenida Pellegrini 935',
            'ciudad' => 'Rosario',
            'estado' => 'Santa Fe',
            'pais' => 'Argentina',
            'codigo_iata' => 'HRG',
            'telefono' => '+54 341 4405500',
            'email' => 'info@rosariogran.com.ar',
            'descripcion' => 'Hotel de 4 estrellas en el centro de Rosario, frente al Paraná',
            'latitud' => '-32.9459',
            'longitud' => '-60.6326'
        ]
    ];

    $creados = 0;
    $errores = 0;

    foreach ($hotelesEjemplo as $hotel) {
        try {
            // Verificar que no exista
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM parada WHERE nombre = :nombre AND tipo = 'hotel'");
            $stmt->execute(['nombre' => $hotel['nombre']]);
            
            if ($stmt->fetchColumn() > 0) {
                echo "<div class='alert alert-info'>";
                echo "⚠️ <strong>" . htmlspecialchars($hotel['nombre']) . "</strong> ya existe<br>";
                echo "</div>";
                continue;
            }

            // Insertar hotel
            $stmt = $pdo->prepare("INSERT INTO parada 
                                (nombre, tipo, direccion, ciudad, estado, pais, latitud, longitud,
                                 codigo_iata, email, descripcion, telefono, habilitado)
                                VALUES 
                                (:nombre, 'hotel', :direccion, :ciudad, :estado, :pais, :latitud, :longitud,
                                 :codigo_iata, :email, :descripcion, :telefono, 1)");

            $stmt->execute([
                ':nombre' => $hotel['nombre'],
                ':direccion' => $hotel['direccion'],
                ':ciudad' => $hotel['ciudad'],
                ':estado' => $hotel['estado'],
                ':pais' => $hotel['pais'],
                ':latitud' => $hotel['latitud'],
                ':longitud' => $hotel['longitud'],
                ':codigo_iata' => $hotel['codigo_iata'],
                ':email' => $hotel['email'],
                ':descripcion' => $hotel['descripcion'],
                ':telefono' => $hotel['telefono']
            ]);

            echo "<div class='alert alert-success'>";
            echo "<i class='fas fa-check-circle'></i> <strong>" . htmlspecialchars($hotel['nombre']) . "</strong> creado exitosamente<br>";
            echo "<small>" . htmlspecialchars($hotel['ciudad']) . ", " . htmlspecialchars($hotel['pais']) . "</small>";
            echo "</div>";
            $creados++;

        } catch (Exception $e) {
            echo "<div class='alert alert-danger'>";
            echo "<i class='fas fa-times-circle'></i> Error al crear <strong>" . htmlspecialchars($hotel['nombre']) . "</strong><br>";
            echo "<small>" . htmlspecialchars($e->getMessage()) . "</small>";
            echo "</div>";
            $errores++;
        }
    }

    echo "<hr>";
    echo "<div class='alert alert-primary'>";
    echo "<strong>Resumen:</strong><br>";
    echo "<span class='success'>✓ $creados hoteles creados</span><br>";
    if ($errores > 0) {
        echo "<span class='error'>✗ $errores errores</span><br>";
    }
    echo "</div>";

    echo "<div style='text-align: center; margin-top: 20px;'>";
    echo "<a href='admin/hotelLista.php' class='btn btn-primary'>";
    echo "<i class='fas fa-hotel'></i> Ver Hoteles Creados";
    echo "</a>";
    echo "</div>";
    ?>
</div>

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
