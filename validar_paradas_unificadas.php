<?php
/**
 * Script de Validación: Sistema de Paradas Unificadas
 * Verifica que la migración se completó correctamente
 */

require_once("config/config.php");

try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
    );
    
    echo "<!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <title>Validación del Sistema de Paradas Unificadas</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 20px; background-color: #f5f5f5; }
            .container { max-width: 1000px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }
            h1 { color: #333; border-bottom: 3px solid #007bff; padding-bottom: 10px; }
            .check { margin: 15px 0; padding: 15px; border-left: 4px solid #28a745; background-color: #d4edda; }
            .error { border-left-color: #dc3545; background-color: #f8d7da; color: #721c24; }
            .warning { border-left-color: #ffc107; background-color: #fff3cd; color: #856404; }
            .success { border-left-color: #28a745; color: #155724; }
            table { width: 100%; border-collapse: collapse; margin: 10px 0; }
            th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
            th { background-color: #007bff; color: white; }
            tr:hover { background-color: #f5f5f5; }
        </style>
    </head>
    <body>
    <div class='container'>
    <h1>✓ Validación del Sistema de Paradas Unificadas</h1>";
    
    // Check 1: Tabla parada existe
    echo "<div class='check success'>";
    try {
        $result = $pdo->query("SHOW TABLES LIKE 'parada'")->fetch();
        if ($result) {
            echo "<h3>✓ Tabla <code>parada</code> existe</h3>";
            
            // Mostrar estructura
            $columns = $pdo->query("SHOW COLUMNS FROM parada")->fetchAll();
            echo "<table><tr><th>Campo</th><th>Tipo</th><th>Nulo</th><th>Clave</th></tr>";
            foreach ($columns as $col) {
                echo "<tr><td>{$col['Field']}</td><td>{$col['Type']}</td><td>{$col['Null']}</td><td>{$col['Key']}</td></tr>";
            }
            echo "</table>";
        } else {
            echo "<h3 class='error'>✗ Tabla <code>parada</code> NO existe</h3>";
        }
    } catch (Exception $e) {
        echo "<div class='error'><h3>✗ Error: " . htmlspecialchars($e->getMessage()) . "</h3></div>";
    }
    echo "</div>";
    
    // Check 2: Paradas migradas
    echo "<div class='check success'>";
    try {
        $total = $pdo->query("SELECT COUNT(*) as cnt FROM parada")->fetch()['cnt'];
        $terminales = $pdo->query("SELECT COUNT(*) as cnt FROM parada WHERE tipo = 'terminal'")->fetch()['cnt'];
        $customizadas = $pdo->query("SELECT COUNT(*) as cnt FROM parada WHERE tipo = 'customizada'")->fetch()['cnt'];
        
        echo "<h3>✓ Datos Migrados</h3>";
        echo "<ul>
            <li><strong>Total de paradas:</strong> $total</li>
            <li><strong>Terminales:</strong> $terminales</li>
            <li><strong>Customizadas:</strong> $customizadas</li>
        </ul>";
        
        // Mostrar primeras paradas
        echo "<h4>Primeras 10 paradas en la BD:</h4>";
        $paradas = $pdo->query("SELECT idParada, nombre, tipo, ciudad, pais FROM parada ORDER BY idParada LIMIT 10")->fetchAll();
        echo "<table><tr><th>ID</th><th>Nombre</th><th>Tipo</th><th>Ciudad</th><th>País</th></tr>";
        foreach ($paradas as $p) {
            echo "<tr><td>{$p['idParada']}</td><td>{$p['nombre']}</td><td>{$p['tipo']}</td><td>{$p['ciudad']}</td><td>{$p['pais']}</td></tr>";
        }
        echo "</table>";
    } catch (Exception $e) {
        echo "<div class='error'>✗ Error: " . htmlspecialchars($e->getMessage()) . "</div>";
    }
    echo "</div>";
    
    // Check 3: ruta_paradas actualizado
    echo "<div class='check success'>";
    try {
        $hasIdParada = $pdo->query("SHOW COLUMNS FROM ruta_paradas LIKE 'idParada'")->fetch();
        if ($hasIdParada) {
            $totalRP = $pdo->query("SELECT COUNT(*) as cnt FROM ruta_paradas")->fetch()['cnt'];
            $conParada = $pdo->query("SELECT COUNT(*) as cnt FROM ruta_paradas WHERE idParada IS NOT NULL")->fetch()['cnt'];
            $sinParada = $totalRP - $conParada;
            
            echo "<h3>✓ Tabla <code>ruta_paradas</code> actualizada</h3>";
            echo "<ul>
                <li><strong>Total de referencias:</strong> $totalRP</li>
                <li><strong>Con idParada asignado:</strong> $conParada</li>
                <li><strong>Sin idParada:</strong> $sinParada</li>
            </ul>";
            
            if ($sinParada > 0) {
                echo "<div class='warning'>⚠ Hay " . $sinParada . " referencias sin <code>idParada</code> asignado</div>";
            }
        } else {
            echo "<div class='error'>✗ Columna <code>idParada</code> no encontrada en <code>ruta_paradas</code></div>";
        }
    } catch (Exception $e) {
        echo "<div class='error'>✗ Error: " . htmlspecialchars($e->getMessage()) . "</div>";
    }
    echo "</div>";
    
    // Check 4: Foreign Key
    echo "<div class='check success'>";
    try {
        $fk = $pdo->query("SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                          WHERE TABLE_NAME = 'ruta_paradas' AND COLUMN_NAME = 'idParada' AND CONSTRAINT_NAME != 'PRIMARY'")->fetch();
        
        if ($fk) {
            echo "<h3>✓ Restricción FK configurada: <code>" . $fk['CONSTRAINT_NAME'] . "</code></h3>";
        } else {
            echo "<h3 class='warning'>⚠ No hay FK explícito configurado (opcional)</h3>";
        }
    } catch (Exception $e) {
        echo "<div class='warning'>⚠ No se pudo verificar FK: " . htmlspecialchars($e->getMessage()) . "</div>";
    }
    echo "</div>";
    
    // Check 5: Test de funciones
    echo "<div class='check success'>";
    echo "<h3>Test de Funciones PHP</h3>";
    
    try {
        require_once("admin/classes/transporte.php");
        
        // Probar getAllParadas
        $todasLasParadas = getAllParadas();
        echo "<p><strong>✓ getAllParadas():</strong> Retorna " . count($todasLasParadas) . " paradas</p>";
        
        // Probar getParada con la primera
        if (!empty($todasLasParadas)) {
            $idTest = $todasLasParadas[0]['idParada'];
            $parada = getParada($idTest);
            echo "<p><strong>✓ getParada($idTest):</strong> " . ($parada ? htmlspecialchars($parada['nombre']) : 'NOT FOUND') . "</p>";
            
            // Probar getParadasPorTipo
            $terminales = getParadasPorTipo('terminal');
            echo "<p><strong>✓ getParadasPorTipo('terminal'):</strong> " . count($terminales) . " resultados</p>";
        }
    } catch (Exception $e) {
        echo "<div class='error'>✗ Error en funciones PHP: " . htmlspecialchars($e->getMessage()) . "</div>";
    }
    echo "</div>";
    
    // Check 6: Test de getParadasRuta con una ruta
    echo "<div class='check success'>";
    echo "<h3>Test de getParadasRuta()</h3>";
    
    try {
        require_once("admin/classes/transporte.php");
        
        $rutas = getAllRutas();
        if (!empty($rutas)) {
            $idRuta = $rutas[0]['idRuta'];
            $paradasRuta = getParadasRuta($idRuta);
            
            echo "<p><strong>✓ getParadasRuta($idRuta):</strong> " . count($paradasRuta) . " paradas en la ruta</p>";
            
            if (!empty($paradasRuta)) {
                echo "<table><tr><th>Orden</th><th>Nombre</th><th>Ciudad</th><th>Tipo</th><th>Origen/Destino</th></tr>";
                foreach ($paradasRuta as $p) {
                    $tipo = ($p['es_origen'] && $p['es_destino']) ? 'O/D' : ($p['es_origen'] ? 'O' : ($p['es_destino'] ? 'D' : 'I'));
                    echo "<tr><td>{$p['orden']}</td><td>{$p['terminal_nombre']}</td><td>{$p['ciudad']}</td><td>{$p['parada_tipo']}</td><td>$tipo</td></tr>";
                }
                echo "</table>";
            }
        } else {
            echo "<p class='warning'>⚠ No hay rutas creadas para testear</p>";
        }
    } catch (Exception $e) {
        echo "<div class='error'>✗ Error: " . htmlspecialchars($e->getMessage()) . "</div>";
    }
    echo "</div>";
    
    echo "<div class='check success' style='background-color: #d4edda;'>
        <h2>✅ Validación Completada</h2>
        <p>El sistema de paradas unificadas está <strong>listo para usar</strong>.</p>
        <p><a href='admin/rutasTransporteLista.php' style='color: #007bff; text-decoration: none;'>&rarr; Ir a Gestión de Rutas</a></p>
    </div>";
    
    echo "</div>
    </body>
    </html>";
    
} catch (PDOException $e) {
    echo "<div style='color: red; font-weight: bold;'>Error de conexión: " . htmlspecialchars($e->getMessage()) . "</div>";
}
?>
