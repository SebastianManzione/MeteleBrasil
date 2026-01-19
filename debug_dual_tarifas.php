<?php
/**
 * Script de debugging para sistema dual de tarifas
 */
require_once("admin/classes/transporte.php");
require_once("admin/classes/conexion.php");

// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Asegurar moneda por defecto
if (!isset($_SESSION['moneda_sel'])) {
    $_SESSION['moneda_sel'] = 1;
    $_SESSION['moneda_sel_sym'] = 'ARS';
}

echo "<h1>🔍 DEBUG: Sistema Dual de Tarifas</h1>";

// ============ VERIFICACIÓN BÁSICA ============
echo "<h2>1️⃣ Verificación Básica</h2>";

$idViaje = 5;
$viaje = getViaje($idViaje);

if (empty($viaje)) {
    echo "<p style='color:red;'><strong>✗ Viaje #5 NO EXISTE</strong></p>";
    exit;
}

echo "<p style='color:green;'><strong>✓ Viaje #5 encontrado</strong></p>";
echo "<pre>";
echo "tipo_tarifa: " . ($viaje['tipo_tarifa'] ?? 'NULL') . "\n";
echo "Ruta: " . $viaje['ruta_nombre'] . "\n";
echo "Fecha: " . $viaje['fecha'] . "\n";
echo "Asientos: " . $viaje['asientos_disponibles'] . "/" . $viaje['asientos_totales'];
echo "</pre>";

// ============ VERIFICACIÓN DE TIPO ============
echo "<h2>2️⃣ Tipo de Tarifa</h2>";

$tipoTarifa = isset($viaje['tipo_tarifa']) ? $viaje['tipo_tarifa'] : 'clases';

if ($tipoTarifa === 'clases') {
    echo "<p style='color:blue;'><strong>ℹ Tipo: CLASES</strong></p>";
    
    // Buscar clases disponibles
    $clases = getViajeClasesServicio($idViaje);
    
    if (empty($clases)) {
        echo "<p style='color:orange;'><strong>⚠ AVISO: No hay clases configuradas para este viaje</strong></p>";
        echo "<p>SQL a ejecutar para agregar clase:</p>";
        echo "<pre>";
        echo "INSERT INTO viaje_clase_servicio 
        (idViaje, idClaseServicio, asientos_totales, asientos_disponibles, precio_base, idMoneda, comisiona, habilitado)
        VALUES (5, 1, 30, 30, 5000, 1, 1, 1);";
        echo "</pre>";
    } else {
        echo "<p style='color:green;'><strong>✓ " . count($clases) . " clase(s) encontrada(s)</strong></p>";
        
        foreach ($clases as $clase) {
            echo "<h3>Clase: " . htmlspecialchars($clase['nombre_clase']) . " (ID: " . $clase['idViajeClase'] . ")</h3>";
            
            $tarifas = getViajeClaseTarifas($clase['idViajeClase']);
            
            if (empty($tarifas)) {
                echo "<p style='color:orange;'>⚠ Sin tarifas en viaje_clase_tarifa</p>";
            } else {
                echo "<p style='color:green;'>✓ " . count($tarifas) . " tarifa(s) encontrada(s)</p>";
                echo "<table border='1' cellpadding='10'>";
                echo "<tr><th>Tipo</th><th>Precio</th><th>Moneda</th></tr>";
                
                foreach ($tarifas as $t) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($t['nombre_tipo_tarifa'] ?? 'DESCONOCIDO') . "</td>";
                    echo "<td>" . $t['precio'] . "</td>";
                    echo "<td>" . $t['idMoneda'] . "</td>";
                    echo "</tr>";
                }
                
                echo "</table>";
            }
        }
    }
    
} else if ($tipoTarifa === 'segmentado') {
    echo "<p style='color:blue;'><strong>ℹ Tipo: SEGMENTADO</strong></p>";
    
    // Buscar tarifas segmentadas
    $tarifas = getTarifasViaje($idViaje);
    
    if (empty($tarifas)) {
        echo "<p style='color:orange;'><strong>⚠ AVISO: No hay tarifas segmentadas configuradas</strong></p>";
    } else {
        echo "<p style='color:green;'><strong>✓ " . count($tarifas) . " tarifa(s) encontrada(s)</strong></p>";
    }
}

// ============ VERIFICACIÓN DE CONTROLLER ============
echo "<h2>3️⃣ Verificación del Controller</h2>";

$controllerPath = __DIR__ . "/admin/ctrl/ctrlTarifasViaje.php";

if (file_exists($controllerPath)) {
    echo "<p style='color:green;'><strong>✓ ctrlTarifasViaje.php EXISTE</strong></p>";
    
    // Verificar que tiene ambos cases
    $content = file_get_contents($controllerPath);
    
    if (strpos($content, "case 'getTarifasViaje'") !== false) {
        echo "<p style='color:green;'>✓ Case getTarifasViaje presente</p>";
    } else {
        echo "<p style='color:red;'>✗ Case getTarifasViaje FALTA</p>";
    }
    
    if (strpos($content, "case 'getClasesTarifas'") !== false) {
        echo "<p style='color:green;'>✓ Case getClasesTarifas presente</p>";
    } else {
        echo "<p style='color:red;'>✗ Case getClasesTarifas FALTA</p>";
    }
} else {
    echo "<p style='color:red;'><strong>✗ ctrlTarifasViaje.php NO EXISTE</strong></p>";
}

// ============ VERIFICACIÓN DE JAVASCRIPT ============
echo "<h2>4️⃣ Verificación de servicio_contransporte.php</h2>";

$pagePath = __DIR__ . "/servicio_contransporte.php";

if (file_exists($pagePath)) {
    echo "<p style='color:green;'><strong>✓ servicio_contransporte.php EXISTE</strong></p>";
    
    $content = file_get_contents($pagePath);
    
    // Verificar que tiene detectores de tipo
    if (strpos($content, "\$tipoTarifa") !== false) {
        echo "<p style='color:green;'>✓ Detección de tipoTarifa en PHP</p>";
    } else {
        echo "<p style='color:orange;'>⚠ No hay detección de tipoTarifa en PHP</p>";
    }
    
    // Verificar que tiene selectores condicionales
    if (strpos($content, "selectClaseDesktop") !== false) {
        echo "<p style='color:green;'>✓ Selector selectClaseDesktop presente</p>";
    } else {
        echo "<p style='color:orange;'>⚠ No hay selectClaseDesktop</p>";
    }
    
    if (strpos($content, "selectClaseMovil") !== false) {
        echo "<p style='color:green;'>✓ Selector selectClaseMovil presente</p>";
    } else {
        echo "<p style='color:orange;'>⚠ No hay selectClaseMovil</p>";
    }
    
    // Verificar JavaScript
    if (strpos($content, "tipoTarifa === 'clases'") !== false) {
        echo "<p style='color:green;'>✓ JavaScript detecta tipoTarifa=clases</p>";
    } else {
        echo "<p style='color:orange;'>⚠ JavaScript no detecta tipoTarifa=clases</p>";
    }
    
    if (strpos($content, "getClasesTarifas") !== false) {
        echo "<p style='color:green;'>✓ JavaScript llama getClasesTarifas</p>";
    } else {
        echo "<p style='color:red;'>✗ JavaScript NO llama getClasesTarifas</p>";
    }
    
} else {
    echo "<p style='color:red;'><strong>✗ servicio_contransporte.php NO EXISTE</strong></p>";
}

// ============ VERIFICACIÓN DE BASE DE DATOS ============
echo "<h2>5️⃣ Verificación de Base de Datos</h2>";

try {
    $stmt = $pdo->prepare("DESCRIBE viaje");
    $stmt->execute();
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $tipoTarifaExists = array_filter($columns, function($col) {
        return $col['Field'] === 'tipo_tarifa';
    });
    
    if (!empty($tipoTarifaExists)) {
        echo "<p style='color:green;'><strong>✓ Columna tipo_tarifa EXISTE</strong></p>";
    } else {
        echo "<p style='color:red;'><strong>✗ Columna tipo_tarifa NO EXISTE</strong></p>";
        echo "<p>Ejecutar: ALTER TABLE viaje ADD COLUMN tipo_tarifa VARCHAR(20) DEFAULT 'clases';</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color:red;'><strong>✗ Error en base de datos: " . $e->getMessage() . "</strong></p>";
}

// ============ TEST AJAX ============
echo "<h2>6️⃣ Test AJAX (Simular)</h2>";

if ($tipoTarifa === 'clases' && !empty($clases)) {
    $primeraClase = $clases[0];
    
    echo "<p>Simulando AJAX para clase ID: " . $primeraClase['idViajeClase'] . "</p>";
    echo "<p>Request: POST ctrlTarifasViaje.php con accion=getClasesTarifas</p>";
    
    $tarifas = getViajeClaseTarifas($primeraClase['idViajeClase']);
    
    $response = [
        'success' => !empty($tarifas),
        'tarifas' => array_slice($tarifas, 0, 3) // Solo primeras 3
    ];
    
    echo "<p>Response (primeras 3 tarifas):</p>";
    echo "<pre>" . json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "</pre>";
}

// ============ RESUMEN ============
echo "<h2>📋 RESUMEN</h2>";

$allOk = true;
$warnings = 0;

if (!file_exists($controllerPath)) {
    echo "<p style='color:red;'><strong>❌ CRÍTICO: ctrlTarifasViaje.php no existe</strong></p>";
    $allOk = false;
} else if ($tipoTarifa === 'clases' && empty($clases)) {
    echo "<p style='color:orange;'><strong>⚠️ ADVERTENCIA: Viaje 5 es CLASES pero no tiene clases configuradas</strong></p>";
    $warnings++;
}

if ($allOk && $warnings === 0) {
    echo "<p style='color:green;'><strong>✅ Sistema listo para usar</strong></p>";
    echo "<p><a href='servicio_contransporte.php?id=5' target='_blank'>→ Abrir viaje 5</a></p>";
} else if ($allOk && $warnings > 0) {
    echo "<p style='color:blue;'><strong>⚠️ Sistema funcional pero con advertencias</strong></p>";
}

echo "<hr>";
echo "<p><small>Debug timestamp: " . date('Y-m-d H:i:s') . "</small></p>";
?>
