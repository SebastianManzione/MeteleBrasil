<?php
/**
 * Script de emergencia para decodificar servicios que se guardaron en base64
 */

require_once("classes/conexion.php");

// Obtener todos los servicios con nombres que parecen estar en base64
$query = "SELECT idServicio, nombre_servicio, nombre_servicio_en, nombre_servicio_pt, nombre_servicio_it,
          descripcion_servicio, descripcion_servicio_en, descripcion_servicio_pt, descripcion_servicio_it,
          descripcion_corta, descripcion_corta_en, descripcion_corta_pt, descripcion_corta_it,
          documentacionViajero, documentacionViajero_en, documentacionViajero_pt, documentacionViajero_it,
          observaciones, observaciones_en, observaciones_pt, observaciones_it
          FROM servicio 
          WHERE nombre_servicio LIKE '%==%' OR nombre_servicio REGEXP '^[A-Za-z0-9+/]+=*$'";

$stmt = $pdo->prepare($query);
$stmt->execute();
$servicios = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<h2>Servicios encontrados con posible codificación base64: " . count($servicios) . "</h2>";

$campos = [
    'nombre_servicio', 'nombre_servicio_en', 'nombre_servicio_pt', 'nombre_servicio_it',
    'descripcion_servicio', 'descripcion_servicio_en', 'descripcion_servicio_pt', 'descripcion_servicio_it',
    'descripcion_corta', 'descripcion_corta_en', 'descripcion_corta_pt', 'descripcion_corta_it',
    'documentacionViajero', 'documentacionViajero_en', 'documentacionViajero_pt', 'documentacionViajero_it',
    'observaciones', 'observaciones_en', 'observaciones_pt', 'observaciones_it'
];

foreach ($servicios as $servicio) {
    echo "<h3>Servicio ID: {$servicio['idServicio']}</h3>";
    echo "<p><strong>Nombre actual:</strong> {$servicio['nombre_servicio']}</p>";
    
    $updates = [];
    $data = ['idServicio' => $servicio['idServicio']];
    
    foreach ($campos as $campo) {
        $valor = $servicio[$campo];
        
        // Verificar si parece base64 y intentar decodificar
        if (!empty($valor) && base64_encode(base64_decode($valor, true)) === $valor) {
            $decodificado = base64_decode($valor);
            
            // Verificar que la decodificación produjo texto válido
            if (mb_check_encoding($decodificado, 'UTF-8')) {
                $updates[] = "$campo = :$campo";
                $data[$campo] = $decodificado;
                
                echo "<p><strong>$campo:</strong><br>";
                echo "ANTES: " . htmlspecialchars(substr($valor, 0, 50)) . "...<br>";
                echo "DESPUÉS: " . htmlspecialchars(substr($decodificado, 0, 100)) . "...</p>";
            }
        }
    }
    
    // Actualizar solo si hay campos para actualizar
    if (!empty($updates)) {
        $sql = "UPDATE servicio SET " . implode(', ', $updates) . " WHERE idServicio = :idServicio";
        $updateStmt = $pdo->prepare($sql);
        
        if ($updateStmt->execute($data)) {
            echo "<p style='color: green;'>✓ Servicio actualizado correctamente</p>";
        } else {
            echo "<p style='color: red;'>✗ Error al actualizar servicio</p>";
        }
    } else {
        echo "<p>No se encontraron campos codificados en base64</p>";
    }
    
    echo "<hr>";
}

echo "<h2>Proceso completado</h2>";
echo "<p><a href='serviciosLista.php'>Volver a lista de servicios</a></p>";
?>
