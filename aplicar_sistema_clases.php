<?php
/**
 * Script para aplicar sistema de clases de servicio al transporte
 */

header('Content-Type: text/html; charset=utf-8');
require_once("admin/classes/conexion.php");

echo "<h1>Aplicando Sistema de Clases de Servicio</h1>";
echo "<pre>";

try {
    echo "📦 Creando tabla clase_servicio_transporte...\n";
    $pdo->exec("DROP TABLE IF EXISTS viaje_clase_tarifa");
    $pdo->exec("DROP TABLE IF EXISTS reserva_transporte_clase");
    $pdo->exec("DROP TABLE IF EXISTS viaje_clase_servicio");
    $pdo->exec("DROP TABLE IF EXISTS clase_servicio_transporte");
    
    $pdo->exec("
        CREATE TABLE clase_servicio_transporte (
            idClaseServicio INT AUTO_INCREMENT PRIMARY KEY,
            nombre VARCHAR(100) NOT NULL,
            descripcion TEXT,
            idTipoTransporte INT NOT NULL,
            icon VARCHAR(50) DEFAULT 'fa-chair',
            orden INT DEFAULT 0,
            habilitado TINYINT DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (idTipoTransporte) REFERENCES tipo_transporte(idTipoTransporte) ON DELETE CASCADE,
            INDEX idx_tipo (idTipoTransporte)
        ) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "✅ Tabla clase_servicio_transporte creada\n";
    
    echo "📦 Creando tabla viaje_clase_servicio...\n";
    $pdo->exec("
        CREATE TABLE viaje_clase_servicio (
            idViajeClase INT AUTO_INCREMENT PRIMARY KEY,
            idViaje INT NOT NULL,
            idClaseServicio INT NOT NULL,
            asientos_totales INT NOT NULL DEFAULT 0,
            asientos_disponibles INT NOT NULL DEFAULT 0,
            precio_base DECIMAL(10,2) NOT NULL,
            idMoneda INT NOT NULL,
            comisiona TINYINT DEFAULT 1,
            habilitado TINYINT DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (idViaje) REFERENCES viaje_transporte(idViaje) ON DELETE CASCADE,
            FOREIGN KEY (idClaseServicio) REFERENCES clase_servicio_transporte(idClaseServicio) ON DELETE CASCADE,
            FOREIGN KEY (idMoneda) REFERENCES moneda(idMoneda),
            INDEX idx_viaje (idViaje),
            INDEX idx_clase (idClaseServicio),
            UNIQUE KEY unique_viaje_clase (idViaje, idClaseServicio)
        ) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "✅ Tabla viaje_clase_servicio creada\n";
    
    echo "📦 Creando tabla viaje_clase_tarifa...\n";
    $pdo->exec("
        CREATE TABLE viaje_clase_tarifa (
            idViajeClaseTarifa INT AUTO_INCREMENT PRIMARY KEY,
            idViajeClase INT NOT NULL,
            idTipoTarifa INT NOT NULL,
            precio DECIMAL(10,2) NOT NULL,
            idMoneda INT NOT NULL,
            comisiona TINYINT DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (idViajeClase) REFERENCES viaje_clase_servicio(idViajeClase) ON DELETE CASCADE,
            FOREIGN KEY (idTipoTarifa) REFERENCES tipos_tarifa(idTipoTarifa),
            FOREIGN KEY (idMoneda) REFERENCES moneda(idMoneda),
            INDEX idx_viaje_clase (idViajeClase),
            UNIQUE KEY unique_viaje_clase_tipo (idViajeClase, idTipoTarifa)
        ) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "✅ Tabla viaje_clase_tarifa creada\n";
    
    echo "📦 Creando tabla reserva_transporte_clase...\n";
    $pdo->exec("
        CREATE TABLE reserva_transporte_clase (
            idReservaTransporteClase INT AUTO_INCREMENT PRIMARY KEY,
            idReservaTransporte INT NOT NULL,
            idViajeClase INT NOT NULL,
            cantidad INT NOT NULL DEFAULT 1,
            precio_unitario DECIMAL(10,2) NOT NULL,
            precio_total DECIMAL(10,2) NOT NULL,
            idMoneda INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (idReservaTransporte) REFERENCES reserva_transporte(idReservaTransporte) ON DELETE CASCADE,
            FOREIGN KEY (idViajeClase) REFERENCES viaje_clase_servicio(idViajeClase),
            FOREIGN KEY (idMoneda) REFERENCES moneda(idMoneda),
            INDEX idx_reserva (idReservaTransporte)
        ) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "✅ Tabla reserva_transporte_clase creada\n";
    
    echo "\n" . str_repeat("=", 60) . "\n\n";
    
    echo "📦 Insertando catálogo de clases...\n";
    
    // BUSES
    $pdo->exec("INSERT INTO clase_servicio_transporte (nombre, descripcion, idTipoTransporte, orden) VALUES
        ('Semicama Común', 'Asiento reclinable 140°, sin servicios adicionales', 1, 1),
        ('Semicama Panorámico', 'Asiento reclinable 140° con vista panorámica', 1, 2),
        ('Semicama Cafetera', 'Asiento reclinable 140° + servicio de café y snacks', 1, 3),
        ('Cama', 'Asiento reclinable 160°, más espacio y comodidad', 1, 4),
        ('Suite', 'Cabina privada con cama completa, baño privado y servicios premium', 1, 5)
    ");
    
    // AVIONES
    $pdo->exec("INSERT INTO clase_servicio_transporte (nombre, descripcion, idTipoTransporte, orden) VALUES
        ('Económica', 'Clase económica estándar', 2, 1),
        ('Premium Economy', 'Más espacio entre asientos y servicios mejorados', 2, 2),
        ('Business', 'Asientos reclinables, comida premium y acceso a salas VIP', 2, 3),
        ('Primera Clase', 'Máximo confort, privacidad y servicios exclusivos', 2, 4)
    ");
    
    // TRENES
    $pdo->exec("INSERT INTO clase_servicio_transporte (nombre, descripcion, idTipoTransporte, orden) VALUES
        ('Turista', 'Clase turista estándar', 3, 1),
        ('Preferente', 'Asientos más cómodos y servicios adicionales', 3, 2),
        ('Coche Cama', 'Cabinas con literas para viajes nocturnos', 3, 3)
    ");
    
    // BARCOS
    $pdo->exec("INSERT INTO clase_servicio_transporte (nombre, descripcion, idTipoTransporte, orden) VALUES
        ('Butaca', 'Asiento estándar en salón común', 4, 1),
        ('Camarote Compartido', 'Cabina compartida de 4 personas', 4, 2),
        ('Camarote Doble', 'Cabina privada para 2 personas', 4, 3),
        ('Camarote Suite', 'Suite con vista al mar y servicios premium', 4, 4)
    ");
    
    echo "✅ Catálogo insertado (16 clases)\n\n";
    
    echo "📋 Clases por tipo de transporte:\n";
    $stmt = $pdo->query("SELECT cs.*, tt.nombre AS tipo_transporte 
                         FROM clase_servicio_transporte cs 
                         INNER JOIN tipo_transporte tt ON cs.idTipoTransporte = tt.idTipoTransporte
                         ORDER BY cs.idTipoTransporte, cs.orden");
    $clases = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $tipoActual = null;
    foreach ($clases as $clase) {
        if ($clase['tipo_transporte'] !== $tipoActual) {
            $tipoActual = $clase['tipo_transporte'];
            echo "\n  🚌 {$tipoActual}:\n";
        }
        echo "     {$clase['orden']}. {$clase['nombre']}\n";
    }
    
    echo "\n" . str_repeat("=", 60) . "\n\n";
    
    echo "📦 Asignando clases a viajes...\n";
    
    $stmt = $pdo->query("SELECT idViaje FROM viaje_transporte ORDER BY idViaje");
    $viajes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($viajes)) {
        throw new Exception("Sin viajes en la BD. Por favor crea viajes primero.");
    }
    
    foreach ($viajes as $v) {
        $idViaje = $v['idViaje'];
        
        // Asignar las 3 clases de bus más comunes
        $clasesAsignar = [
            ['idClase' => 1, 'asientos' => 15, 'precio' => 12500],
            ['idClase' => 2, 'asientos' => 20, 'precio' => 15000],
            ['idClase' => 4, 'asientos' => 10, 'precio' => 22000]
        ];
        
        foreach ($clasesAsignar as $clase) {
            $stmt = $pdo->prepare("
                INSERT INTO viaje_clase_servicio 
                (idViaje, idClaseServicio, asientos_totales, asientos_disponibles, precio_base, idMoneda, comisiona)
                VALUES (?, ?, ?, ?, ?, 270, 1)
                ON DUPLICATE KEY UPDATE 
                    asientos_disponibles = ?,
                    precio_base = ?
            ");
            $stmt->execute([
                $idViaje,
                $clase['idClase'],
                $clase['asientos'],
                $clase['asientos'],
                $clase['precio'],
                $clase['asientos'],
                $clase['precio']
            ]);
        }
    }
    
    echo "✅ Clases asignadas a " . count($viajes) . " viaje(s)\n\n";
    
    echo "📦 Asignando tarifas por tipo de pasajero...\n";
    
    $stmt = $pdo->query("SELECT idViajeClase FROM viaje_clase_servicio");
    $viajesClases = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($viajesClases as $vc) {
        $idViajeClase = $vc['idViajeClase'];
        
        $stmt = $pdo->prepare("SELECT precio_base, idMoneda FROM viaje_clase_servicio WHERE idViajeClase = ?");
        $stmt->execute([$idViajeClase]);
        $clase = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $precioBase = $clase['precio_base'];
        $idMoneda = $clase['idMoneda'];
        
        $stmt = $pdo->prepare("
            INSERT INTO viaje_clase_tarifa (idViajeClase, idTipoTarifa, precio, idMoneda, comisiona)
            VALUES (?, ?, ?, ?, 1)
            ON DUPLICATE KEY UPDATE precio = ?
        ");
        
        // Adulto (idTipoTarifa = 1)
        $stmt->execute([$idViajeClase, 1, $precioBase, $idMoneda, $precioBase]);
        
        // Niño (idTipoTarifa = 2) - 50% descuento
        $stmt->execute([$idViajeClase, 2, $precioBase * 0.5, $idMoneda, $precioBase * 0.5]);
        
        // Bebé (idTipoTarifa = 4) - gratis
        $stmt->execute([$idViajeClase, 4, 0, $idMoneda, 0]);
    }
    
    echo "✅ Tarifas asignadas\n\n";
    
    echo str_repeat("=", 60) . "\n";
    echo "📋 Viajes con clases configuradas:\n\n";
    
    $stmt = $pdo->query("
        SELECT 
            vt.idViaje,
            rt.nombre AS ruta,
            vt.fecha,
            vt.hora_salida,
            cs.nombre AS clase,
            vcs.asientos_totales,
            vcs.asientos_disponibles,
            vcs.precio_base,
            m.Symbol AS moneda
        FROM viaje_transporte vt
        INNER JOIN viaje_clase_servicio vcs ON vt.idViaje = vcs.idViaje
        INNER JOIN clase_servicio_transporte cs ON vcs.idClaseServicio = cs.idClaseServicio
        INNER JOIN ruta_transporte rt ON vt.idRuta = rt.idRuta
        INNER JOIN moneda m ON vcs.idMoneda = m.idMoneda
        ORDER BY vt.idViaje, cs.orden
    ");
    
    $viajes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $viajeActual = null;
    
    foreach ($viajes as $v) {
        if ($v['idViaje'] !== $viajeActual) {
            $viajeActual = $v['idViaje'];
            $fecha = date('d/m/Y', strtotime($v['fecha']));
            echo "\n  🚌 Viaje #{$v['idViaje']}: {$v['ruta']}\n";
            echo "     📅 {$fecha} a las {$v['hora_salida']}hs\n";
            echo "     Clases:\n";
        }
        
        $disponibilidad = "{$v['asientos_disponibles']}/{$v['asientos_totales']}";
        $precio = number_format($v['precio_base'], 0, ',', '.');
        echo "       • {$v['clase']}: {$v['moneda']} {$precio} - Disponibles: {$disponibilidad}\n";
    }
    
    echo "\n" . str_repeat("=", 60) . "\n";
    echo "✅ Sistema de clases aplicado correctamente!\n";
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}

echo "</pre>";
