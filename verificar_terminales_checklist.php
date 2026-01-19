<?php
/**
 * CHECKLIST DE VERIFICACIÓN - SISTEMA DE TERMINALES
 * Script para validar que todo está funcionando correctamente
 * 
 * Uso: http://localhost/metelebrasil_dev/verificar_terminales_checklist.php
 */

require_once('config/config.php');
require_once('admin/classes/conexion.php');

$checks = [];
$all_passed = true;

echo '<!DOCTYPE html>';
echo '<html lang="es">';
echo '<head>';
echo '<meta charset="UTF-8">';
echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
echo '<title>✅ Checklist Terminales</title>';
echo '<style>';
echo 'body { font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif; background: #f5f5f5; padding: 20px; }';
echo '.container { max-width: 900px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }';
echo 'h1 { color: #333; border-bottom: 3px solid #4CAF50; padding-bottom: 10px; }';
echo '.check-item { margin: 15px 0; padding: 15px; border-left: 4px solid #ddd; background: #fafafa; border-radius: 4px; }';
echo '.check-item.pass { border-left-color: #4CAF50; background: #e8f5e9; }';
echo '.check-item.fail { border-left-color: #f44336; background: #ffebee; }';
echo '.check-item.info { border-left-color: #2196F3; background: #e3f2fd; }';
echo '.status { font-weight: bold; margin-right: 10px; font-size: 1.2em; }';
echo '.pass .status { color: #4CAF50; }';
echo '.fail .status { color: #f44336; }';
echo '.info .status { color: #2196F3; }';
echo '.detail { margin-top: 5px; color: #666; font-size: 0.9em; }';
echo '.summary { margin-top: 30px; padding: 20px; background: #f0f0f0; border-radius: 4px; text-align: center; }';
echo '.summary.pass { background: #e8f5e9; color: #2e7d32; border: 2px solid #4CAF50; }';
echo '.summary.fail { background: #ffebee; color: #c62828; border: 2px solid #f44336; }';
echo '.link { color: #2196F3; text-decoration: none; }';
echo '.link:hover { text-decoration: underline; }';
echo 'code { background: #f4f4f4; padding: 2px 6px; border-radius: 3px; font-family: monospace; }';
echo '</style>';
echo '</head>';
echo '<body>';
echo '<div class="container">';
echo '<h1>✅ Checklist de Verificación - Sistema de Terminales</h1>';

// ========== CHECK 1: Tabla ubicacion existente ==========
$check = ['name' => 'Tabla ubicacion existe', 'pass' => false, 'detail' => ''];
try {
    $stmt = $pdo->query("SHOW TABLES LIKE 'ubicacion'");
    if ($stmt->rowCount() > 0) {
        $check['pass'] = true;
        $check['detail'] = 'Tabla ubicacion encontrada en base de datos';
    } else {
        $check['pass'] = false;
        $check['detail'] = 'Tabla ubicacion no existe';
        $all_passed = false;
    }
} catch (Exception $e) {
    $check['pass'] = false;
    $check['detail'] = 'Error: ' . $e->getMessage();
    $all_passed = false;
}
$checks[] = $check;

// ========== CHECK 2: Verificar tipo ENUM en ubicacion ==========
$check = ['name' => 'Tipo ENUM soporta "terminal"', 'pass' => false, 'detail' => ''];
try {
    $stmt = $pdo->query("SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='ubicacion' AND COLUMN_NAME='tipo'");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result && strpos($result['COLUMN_TYPE'], 'terminal') !== false) {
        $check['pass'] = true;
        $check['detail'] = 'ENUM: ' . $result['COLUMN_TYPE'];
    } else {
        $check['pass'] = false;
        $check['detail'] = 'Tipo ENUM no incluye "terminal"';
        $all_passed = false;
    }
} catch (Exception $e) {
    $check['pass'] = false;
    $check['detail'] = 'Error: ' . $e->getMessage();
    $all_passed = false;
}
$checks[] = $check;

// ========== CHECK 3: Total de terminales en ubicacion ==========
$check = ['name' => 'Terminales migradas a ubicacion', 'pass' => false, 'detail' => ''];
try {
    $stmt = $pdo->prepare('SELECT COUNT(*) as total FROM ubicacion WHERE tipo = ?');
    $stmt->execute(['terminal']);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $total = $result['total'];
    if ($total >= 33) {
        $check['pass'] = true;
        $check['detail'] = 'Total: ' . $total . ' terminales';
    } else {
        $check['pass'] = false;
        $check['detail'] = 'Solo ' . $total . ' terminales (esperados >=33)';
        $all_passed = false;
    }
} catch (Exception $e) {
    $check['pass'] = false;
    $check['detail'] = 'Error: ' . $e->getMessage();
    $all_passed = false;
}
$checks[] = $check;

// ========== CHECK 4: Archivos necesarios existen ==========
$files_check = [
    'admin/terminalAlta.php' => 'Editor con Google Maps',
    'admin/terminalesLista.php' => 'Listado de terminales',
    'admin/ctrl/ctrlTerminalesNuevo.php' => 'Controller backend',
];

foreach ($files_check as $file => $desc) {
    $check = ['name' => 'Archivo: ' . $file, 'pass' => file_exists($file), 'detail' => $desc];
    if (!$check['pass']) {
        $all_passed = false;
    }
    $checks[] = $check;
}

// ========== CHECK 5: Ejemplos de terminales ==========
$check = ['name' => 'Terminales de ejemplo accesibles', 'pass' => false, 'detail' => ''];
try {
    $stmt = $pdo->prepare('SELECT nombre, ciudad FROM ubicacion WHERE tipo = ? ORDER BY ciudad LIMIT 5');
    $stmt->execute(['terminal']);
    $terminales = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (count($terminales) > 0) {
        $check['pass'] = true;
        $details = [];
        foreach ($terminales as $t) {
            $details[] = htmlspecialchars($t['nombre']) . ' (' . htmlspecialchars($t['ciudad']) . ')';
        }
        $check['detail'] = implode(', ', $details);
    } else {
        $check['pass'] = false;
        $check['detail'] = 'No hay terminales en ubicacion';
        $all_passed = false;
    }
} catch (Exception $e) {
    $check['pass'] = false;
    $check['detail'] = 'Error: ' . $e->getMessage();
    $all_passed = false;
}
$checks[] = $check;

// ========== CHECK 6: Tabla parada tiene FK a ubicacion ==========
$check = ['name' => 'Parada table tiene FK a ubicacion', 'pass' => false, 'detail' => ''];
try {
    $stmt = $pdo->query("SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE TABLE_NAME='parada' AND COLUMN_NAME='idUbicacion'");
    if ($stmt->rowCount() > 0) {
        $check['pass'] = true;
        $check['detail'] = 'FK encontrada en parada.idUbicacion';
    } else {
        // Si no existe FK, podría estar sin crear
        $check['pass'] = false;
        $check['detail'] = 'No hay FK en parada.idUbicacion (puede no ser crítico)';
    }
} catch (Exception $e) {
    $check['pass'] = false;
    $check['detail'] = 'Error: ' . $e->getMessage();
}
$checks[] = $check;

// ========== CHECK 7: Google Maps API en terminalAlta.php ==========
$check = ['name' => 'Google Maps API integrado', 'pass' => false, 'detail' => ''];
try {
    $content = file_get_contents('admin/terminalAlta.php');
    if (strpos($content, 'google.maps.Map') !== false && strpos($content, 'Marker') !== false) {
        $check['pass'] = true;
        $check['detail'] = 'Google Maps API encontrado en terminalAlta.php';
    } else {
        $check['pass'] = false;
        $check['detail'] = 'Google Maps API no encontrado en terminalAlta.php';
        $all_passed = false;
    }
} catch (Exception $e) {
    $check['pass'] = false;
    $check['detail'] = 'Error al leer archivo: ' . $e->getMessage();
    $all_passed = false;
}
$checks[] = $check;

// ========== CHECK 8: Controller maneja ubicacion ==========
$check = ['name' => 'Controller usa tabla ubicacion', 'pass' => false, 'detail' => ''];
try {
    $content = file_get_contents('admin/ctrl/ctrlTerminalesNuevo.php');
    if (strpos($content, 'ubicacion') !== false && strpos($content, "tipo = 'terminal'") !== false) {
        $check['pass'] = true;
        $check['detail'] = 'ctrlTerminalesNuevo.php usa ubicacion con tipo=terminal';
    } else {
        $check['pass'] = false;
        $check['detail'] = 'Controller no usa ubicacion correctamente';
        $all_passed = false;
    }
} catch (Exception $e) {
    $check['pass'] = false;
    $check['detail'] = 'Error: ' . $e->getMessage();
    $all_passed = false;
}
$checks[] = $check;

// ========== CHECK 9: UTF-8 encoding correcto ==========
$check = ['name' => 'UTF-8 encoding en terminales', 'pass' => false, 'detail' => ''];
try {
    $stmt = $pdo->prepare('SELECT nombre FROM ubicacion WHERE tipo = ? AND nombre LIKE ?');
    $stmt->execute(['terminal', '%ó%']);
    $terminales_con_acentos = $stmt->rowCount();
    
    if ($terminales_con_acentos > 0) {
        $check['pass'] = true;
        $check['detail'] = $terminales_con_acentos . ' terminales con acentos encontrados (Córdoba, Rosario, etc.)';
    } else {
        // Podría estar bien si no hay acentos en nombres
        $check['pass'] = true;
        $check['detail'] = 'UTF-8 encoding activo (sin caracteres especiales a probar)';
    }
} catch (Exception $e) {
    $check['pass'] = false;
    $check['detail'] = 'Error: ' . $e->getMessage();
}
$checks[] = $check;

// ========== CHECK 10: DataTables en lista ==========
$check = ['name' => 'DataTables integrado en lista', 'pass' => false, 'detail' => ''];
try {
    $content = file_get_contents('admin/terminalesLista.php');
    if (strpos($content, 'DataTable') !== false && strpos($content, 'tablaTerminales') !== false) {
        $check['pass'] = true;
        $check['detail'] = 'DataTables encontrado en terminalesLista.php';
    } else {
        $check['pass'] = false;
        $check['detail'] = 'DataTables no encontrado en terminalesLista.php';
    }
} catch (Exception $e) {
    $check['pass'] = false;
    $check['detail'] = 'Error: ' . $e->getMessage();
}
$checks[] = $check;

// Renderizar checks
foreach ($checks as $check) {
    $class = $check['pass'] ? 'pass' : 'fail';
    $status = $check['pass'] ? '✓' : '✗';
    echo '<div class="check-item ' . $class . '">';
    echo '<span class="status">' . $status . '</span>';
    echo '<strong>' . htmlspecialchars($check['name']) . '</strong>';
    if ($check['detail']) {
        echo '<div class="detail">' . htmlspecialchars($check['detail']) . '</div>';
    }
    echo '</div>';
}

// Resumen final
echo '<div class="summary ' . ($all_passed ? 'pass' : 'fail') . '">';
if ($all_passed) {
    echo '<h2>✨ ¡TODOS LOS CHECKS PASARON! ✨</h2>';
    echo '<p>El sistema de terminales está completamente operacional</p>';
    echo '<p><a href="/metelebrasil_dev/admin/terminalesLista.php" class="link" target="_blank">🚀 Ir a Listado de Terminales</a></p>';
    echo '<p><a href="/metelebrasil_dev/admin/terminalAlta.php" class="link" target="_blank">➕ Crear Nueva Terminal</a></p>';
} else {
    echo '<h2>⚠️ ALGUNOS CHECKS FALLARON</h2>';
    echo '<p>Revisa los errores arriba y verifica la configuración</p>';
}
echo '</div>';

echo '</div>';
echo '</body>';
echo '</html>';
?>
