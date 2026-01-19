<?php
/**
 * Migración Tipo Parada Dinámico V2
 * 
 * Descr: Crea tabla tipo_parada para tipos dinámicos
 * Pasos:
 * 1. Crea tabla tipo_parada
 * 2. Inserta 7 tipos estándar
 * 3. Agrega columna idTipoPrada a parada (si no existe)
 * 4. Migra valores de tipo ENUM a FK
 * 5. Agrega constraint FK
 * 
 * Ejecutar vía:
 * http://localhost/metelebrasil_dev/migrar_tipos_paradas_dinamicos_v2.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once("admin/classes/conexion.php");

// Ejecutar migración
$resultados = [];
try {
    // PASO 1: Crear tabla tipo_parada
    echo "<h2>Paso 1: Crear tabla tipo_parada...</h2>";
    
    $sqlTipoParada = "CREATE TABLE IF NOT EXISTS tipo_parada (
        idTipoPrada INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(100) NOT NULL UNIQUE,
        icono VARCHAR(50) DEFAULT 'fa-map-marker-alt',
        color VARCHAR(7) DEFAULT '#6c757d',
        habilitado TINYINT(1) DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX(nombre),
        INDEX(habilitado)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    
    $pdo->exec($sqlTipoParada);
    echo "<div class='alert alert-success'>✓ Tabla tipo_parada creada/verificada</div>";
    
    // PASO 2: Insertar tipos estándar
    echo "<h2>Paso 2: Insertar tipos estándar...</h2>";
    
    $tiposEstandar = [
        ['nombre' => 'terminal', 'icono' => 'fa-map-marker-alt', 'color' => '#007bff'],
        ['nombre' => 'hotel', 'icono' => 'fa-hotel', 'color' => '#ff6b6b'],
        ['nombre' => 'estación', 'icono' => 'fa-train', 'color' => '#ffc107'],
        ['nombre' => 'puerto', 'icono' => 'fa-anchor', 'color' => '#17a2b8'],
        ['nombre' => 'aeropuerto', 'icono' => 'fa-plane', 'color' => '#28a745'],
        ['nombre' => 'casa', 'icono' => 'fa-home', 'color' => '#6c757d'],
        ['nombre' => 'intermedia', 'icono' => 'fa-dot-circle', 'color' => '#6f42c1']
    ];
    
    $tiposInsertados = 0;
    foreach ($tiposEstandar as $tipo) {
        try {
            $stmt = $pdo->prepare("INSERT INTO tipo_parada (nombre, icono, color) 
                                  VALUES (:nombre, :icono, :color)
                                  ON DUPLICATE KEY UPDATE 
                                  icono = :icono, color = :color, habilitado = 1");
            $stmt->execute($tipo);
            $tiposInsertados++;
        } catch (Exception $e) {
            // Ya existe, no es error
        }
    }
    echo "<div class='alert alert-info'>✓ $tiposInsertados tipos estándar procesados</div>";
    
    // PASO 3: Agregar columna idTipoPrada a parada
    echo "<h2>Paso 3: Agregar columna idTipoPrada a tabla parada...</h2>";
    
    // Verificar si columna ya existe
    $stmt = $pdo->prepare("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS 
                          WHERE TABLE_NAME='parada' AND COLUMN_NAME='idTipoPrada'");
    $stmt->execute();
    $columnExists = $stmt->rowCount() > 0;
    
    if (!$columnExists) {
        $sqlAddColumn = "ALTER TABLE parada 
                        ADD COLUMN idTipoPrada INT DEFAULT NULL AFTER tipo,
                        ADD INDEX(idTipoPrada)";
        $pdo->exec($sqlAddColumn);
        echo "<div class='alert alert-success'>✓ Columna idTipoPrada agregada</div>";
    } else {
        echo "<div class='alert alert-info'>✓ Columna idTipoPrada ya existe</div>";
    }
    
    // PASO 4: Migrar valores ENUM a FK
    echo "<h2>Paso 4: Migrar valores ENUM a referencias FK...</h2>";
    
    $migraciones = [
        'terminal' => 'terminal',
        'hotel' => 'hotel',
        'estación' => 'estación',
        'puerto' => 'puerto',
        'aeropuerto' => 'aeropuerto',
        'casa' => 'casa',
        'intermedia' => 'intermedia'
    ];
    
    $registrosMigrados = 0;
    foreach ($migraciones as $tipoEnum => $nombreTipo) {
        // Obtener idTipoPrada
        $stmt = $pdo->prepare("SELECT idTipoPrada FROM tipo_parada WHERE nombre = :nombre");
        $stmt->execute(['nombre' => $nombreTipo]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($resultado) {
            $idTipoPrada = $resultado['idTipoPrada'];
            
            // Actualizar paradas con este tipo
            $stmt = $pdo->prepare("UPDATE parada SET idTipoPrada = :idTipoPrada 
                                  WHERE tipo = :tipo AND idTipoPrada IS NULL");
            $stmt->execute(['idTipoPrada' => $idTipoPrada, 'tipo' => $tipoEnum]);
            $registrosMigrados += $stmt->rowCount();
        }
    }
    echo "<div class='alert alert-success'>✓ $registrosMigrados paradas migraron a tipos dinámicos</div>";
    
    // PASO 5: Agregar constraint FK
    echo "<h2>Paso 5: Agregar constraint de clave foránea...</h2>";
    
    // Verificar si FK ya existe
    $stmt = $pdo->prepare("SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                          WHERE TABLE_NAME='parada' AND COLUMN_NAME='idTipoPrada' 
                          AND REFERENCED_TABLE_NAME='tipo_parada'");
    $stmt->execute();
    $fkExists = $stmt->rowCount() > 0;
    
    if (!$fkExists) {
        try {
            // Primero intentar eliminar constraint viejo si existe
            try {
                $pdo->exec("ALTER TABLE parada DROP FOREIGN KEY parada_ibfk_1");
            } catch (Exception $e) {
                // No existe, ignorar
            }
            
            $sqlAddFK = "ALTER TABLE parada 
                        ADD CONSTRAINT fk_parada_tipo 
                        FOREIGN KEY (idTipoPrada) 
                        REFERENCES tipo_parada(idTipoPrada) 
                        ON DELETE RESTRICT ON UPDATE CASCADE";
            $pdo->exec($sqlAddFK);
            echo "<div class='alert alert-success'>✓ Foreign key constraint agregado</div>";
        } catch (Exception $e) {
            echo "<div class='alert alert-warning'>⚠ FK no pudo agregarse (posible conflicto): " . htmlspecialchars($e->getMessage()) . "</div>";
        }
    } else {
        echo "<div class='alert alert-info'>✓ Foreign key constraint ya existe</div>";
    }
    
    // VALIDACIÓN FINAL
    echo "<h2>Validación Final</h2>";
    
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM tipo_parada WHERE habilitado = 1");
    $tiposActivos = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM parada WHERE idTipoPrada IS NOT NULL");
    $paradasAsignadas = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM parada");
    $totalParadas = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    echo "<div class='alert alert-info'>";
    echo "✓ Tipos activos: <strong>$tiposActivos</strong><br>";
    echo "✓ Paradas con tipo asignado: <strong>$paradasAsignadas/$totalParadas</strong><br>";
    echo "</div>";
    
    // Listar tipos creados
    echo "<h2>Tipos Creados</h2>";
    echo "<table class='table table-striped'>";
    echo "<tr><th>ID</th><th>Nombre</th><th>Icono</th><th>Color</th><th>Estado</th></tr>";
    
    $stmt = $pdo->query("SELECT * FROM tipo_parada ORDER BY idTipoPrada");
    while ($tipo = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($tipo['idTipoPrada']) . "</td>";
        echo "<td>" . htmlspecialchars($tipo['nombre']) . "</td>";
        echo "<td><i class='fas " . htmlspecialchars($tipo['icono']) . "'></i> " . htmlspecialchars($tipo['icono']) . "</td>";
        echo "<td><span style='width:20px;height:20px;background-color:" . htmlspecialchars($tipo['color']) . ";display:inline-block;'></span> " . htmlspecialchars($tipo['color']) . "</td>";
        echo "<td>" . ($tipo['habilitado'] ? '<span class="badge badge-success">Activo</span>' : '<span class="badge badge-danger">Inactivo</span>') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Resumen de paradas por tipo
    echo "<h2>Distribución de Paradas por Tipo</h2>";
    echo "<table class='table table-striped'>";
    echo "<tr><th>Tipo</th><th>Cantidad</th></tr>";
    
    $stmt = $pdo->query("SELECT tp.nombre, COUNT(p.idParada) as cantidad 
                        FROM tipo_parada tp 
                        LEFT JOIN parada p ON tp.idTipoPrada = p.idTipoPrada 
                        GROUP BY tp.idTipoPrada, tp.nombre 
                        ORDER BY cantidad DESC");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
        echo "<td><strong>" . $row['cantidad'] . "</strong></td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<div class='alert alert-success mt-4'>";
    echo "<h3>✓ Migración Completada Exitosamente</h3>";
    echo "Los tipos de parada ahora son dinámicos y pueden administrarse libremente.<br>";
    echo "<a href='admin/terminalesLista.php' class='btn btn-primary mt-2'>Ver Terminales</a>";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>";
    echo "❌ Error en migración: " . htmlspecialchars($e->getMessage());
    echo "</div>";
    error_log("Error migración tipos: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Migración Tipos Parada Dinámicos</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        body { padding: 20px; font-family: Arial, sans-serif; }
        .alert { margin: 15px 0; }
        table { margin: 20px 0; }
        h2 { margin-top: 30px; color: #333; border-bottom: 2px solid #007bff; padding-bottom: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Migración: Tipos de Parada Dinámicos</h1>
        <p class="text-muted">Este script crea el sistema de tipos dinámicos para paradas (terminales, hoteles, etc.)</p>
    </div>
</body>
</html>
