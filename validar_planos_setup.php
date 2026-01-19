<?php
/**
 * VALIDADOR DE SETUP - PLANOS REALES
 * 
 * Verifica que todo esté listo para integración de planos reales
 * Usar: http://localhost/metelebrasil_dev/validar_planos_setup.php
 */

ini_set('display_errors', 1);
header('Content-Type: text/html; charset=utf-8');

$checks = [];
$errors = [];
$warnings = [];

echo "<!DOCTYPE html>
<html>
<head>
  <meta charset='UTF-8'>
  <title>Validador Setup - Planos Reales</title>
  <style>
    body { font-family: Arial; margin: 20px; background: #f5f5f5; }
    .container { max-width: 1000px; margin: 0 auto; }
    .header { background: #029ce2; color: white; padding: 20px; border-radius: 5px; }
    .check-item { 
      background: white; 
      padding: 15px; 
      margin: 10px 0; 
      border-left: 5px solid #ddd; 
      border-radius: 3px;
    }
    .check-item.ok { border-left-color: #28a745; background: #f0fdf4; }
    .check-item.error { border-left-color: #dc3545; background: #fdf0f0; }
    .check-item.warning { border-left-color: #ffc107; background: #fffbf0; }
    .icon { font-weight: bold; margin-right: 10px; }
    .icon.ok:before { content: '✓'; color: #28a745; }
    .icon.error:before { content: '✗'; color: #dc3545; }
    .icon.warning:before { content: '⚠'; color: #ffc107; }
    .code { background: #f8f9fa; padding: 5px 10px; border-radius: 3px; font-family: monospace; }
    .summary { background: white; padding: 20px; margin: 20px 0; border-radius: 5px; }
    .summary h2 { margin-top: 0; }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
    th { background: #f8f9fa; }
  </style>
</head>
<body>
  <div class='container'>
    <div class='header'>
      <h1>🔍 Validador de Setup - Planos Reales MeteleBrasil</h1>
      <p>Estado de preparación para integración de 3,090 planos reales</p>
    </div>";

// =============================================================================
// CHECK 1: Archivos de documentación
// =============================================================================
echo "<h2>📚 Documentación</h2>";

$docs = [
    'README_PLANOS_REALES.md' => 'Guía rápida',
    'PLAN_PLANOS_REALES_MICROS.md' => 'Plan técnico detallado',
    'ESTADO_PROYECTO_TRANSPORTE.md' => 'Estado del proyecto',
    'EJECUTAR_PLANOS_REALES.md' => 'Checklist ejecutable',
];

foreach ($docs as $file => $desc) {
    $path = __DIR__ . '/' . $file;
    if (file_exists($path)) {
        $size = filesize($path);
        echo "<div class='check-item ok'>
          <span class='icon ok'></span>
          <strong>$file</strong> - $desc
          <br><small>Tamaño: " . round($size / 1024) . " KB</small>
        </div>";
        $checks['doc_' . $file] = true;
    } else {
        echo "<div class='check-item error'>
          <span class='icon error'></span>
          <strong>$file</strong> - FALTA
        </div>";
        $errors[] = "Falta archivo: $file";
        $checks['doc_' . $file] = false;
    }
}

// =============================================================================
// CHECK 2: Scripts Python
// =============================================================================
echo "<h2>🐍 Scripts Python</h2>";

$scripts = [
    'descargar_planos_mundocolectivo.py' => 'Descargador de planos',
    'procesar_planos_ocr.py' => 'Procesador OCR (OPCIONAL)',
];

foreach ($scripts as $file => $desc) {
    $path = __DIR__ . '/' . $file;
    if (file_exists($path)) {
        $size = filesize($path);
        echo "<div class='check-item ok'>
          <span class='icon ok'></span>
          <strong>$file</strong> - $desc
          <br><small>Tamaño: " . round($size / 1024) . " KB</small>
        </div>";
        $checks['script_' . $file] = true;
    } else {
        echo "<div class='check-item error'>
          <span class='icon error'></span>
          <strong>$file</strong> - FALTA
        </div>";
        if (strpos($file, 'OCR') === false) {
            $errors[] = "Falta script: $file";
        } else {
            $warnings[] = "Falta script opcional: $file";
        }
        $checks['script_' . $file] = false;
    }
}

// =============================================================================
// CHECK 3: Migraciones SQL
// =============================================================================
echo "<h2>📊 Migraciones SQL</h2>";

$migrations = [
    'migrations/014_planos_reales_mundocolectivo.sql' => 'Crear tabla carroceria_planos',
];

foreach ($migrations as $file => $desc) {
    $path = __DIR__ . '/' . $file;
    if (file_exists($path)) {
        $size = filesize($path);
        echo "<div class='check-item ok'>
          <span class='icon ok'></span>
          <strong>$file</strong> - $desc
          <br><small>Tamaño: " . round($size / 1024) . " KB</small>
        </div>";
        $checks['migration_' . str_replace('/', '_', $file)] = true;
    } else {
        echo "<div class='check-item error'>
          <span class='icon error'></span>
          <strong>$file</strong> - FALTA
        </div>";
        $errors[] = "Falta migración: $file";
        $checks['migration_' . str_replace('/', '_', $file)] = false;
    }
}

// =============================================================================
// CHECK 4: Bases de Datos
// =============================================================================
echo "<h2>💾 Base de Datos</h2>";

try {
    $pdo = new PDO('mysql:host=localhost;dbname=metelebrasil_experimental;charset=utf8mb4', 'root', '');
    
    // Verificar tabla carroceria_planos
    $stmt = $pdo->query("SELECT 1 FROM carroceria_planos LIMIT 1");
    if ($stmt) {
        $count = $pdo->query("SELECT COUNT(*) as cnt FROM carroceria_planos")->fetch()['cnt'];
        echo "<div class='check-item ok'>
          <span class='icon ok'></span>
          <strong>Tabla carroceria_planos</strong> - Existe
          <br><small>Registros: $count</small>
        </div>";
        $checks['db_carroceria'] = true;
    } else {
        echo "<div class='check-item error'>
          <span class='icon error'></span>
          <strong>Tabla carroceria_planos</strong> - NO EXISTE
          <br><small>Ejecutar: <span class='code'>mysql -u root metelebrasil_experimental &lt; migrations/014_planos_reales_mundocolectivo.sql</span></small>
        </div>";
        $errors[] = "Tabla carroceria_planos no existe";
        $checks['db_carroceria'] = false;
    }
    
    // Verificar FK en modelo_vehiculo_transporte
    $stmt = $pdo->query("DESCRIBE modelo_vehiculo_transporte");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    // Aceptar ambos nombres por compatibilidad (acentuado y ASCII)
    if (in_array('idCarroceríaPlano', $columns) || in_array('idCarroceriaPlano', $columns)) {
        echo "<div class='check-item ok'>
          <span class='icon ok'></span>
          <strong>FK idCarroceríaPlano/idCarroceriaPlano</strong> - Agregada a modelo_vehiculo_transporte
        </div>";
        $checks['db_fk'] = true;
    } else {
        echo "<div class='check-item error'>
          <span class='icon error'></span>
          <strong>FK idCarroceríaPlano/idCarroceriaPlano</strong> - NO AGREGADA
        </div>";
        $errors[] = "FK no agregada en modelo_vehiculo_transporte (idCarroceríaPlano/idCarroceriaPlano)";
        $checks['db_fk'] = false;
    }
    
} catch (Exception $e) {
    echo "<div class='check-item error'>
      <span class='icon error'></span>
      <strong>Conexión BD</strong> - Error: " . $e->getMessage() . "
    </div>";
    $errors[] = "No hay conexión a BD: " . $e->getMessage();
    $checks['db_connection'] = false;
}

// =============================================================================
// CHECK 5: Carpetas y Permisos
// =============================================================================
echo "<h2>📁 Carpetas y Permisos</h2>";

$folders = [
    'img/planos_carroceria' => 'Almacenar imágenes descargadas',
    'logs' => 'Logs de procesamiento',
    'migrations' => 'Scripts SQL',
];

foreach ($folders as $folder => $desc) {
    $path = __DIR__ . '/' . $folder;
    if (is_dir($path)) {
        $writable = is_writable($path) ? '(RW)' : '(RO)';
        $count = count(glob($path . '/*'));
        echo "<div class='check-item ok'>
          <span class='icon ok'></span>
          <strong>$folder/</strong> - Existe $writable
          <br><small>Descripción: $desc | Archivos: $count</small>
        </div>";
        $checks['folder_' . $folder] = true;
    } else {
        echo "<div class='check-item warning'>
          <span class='icon warning'></span>
          <strong>$folder/</strong> - NO EXISTE (se creará al descargar)
          <br><small>Descripción: $desc</small>
        </div>";
        $warnings[] = "Carpeta no existe: $folder (normal, se crea al descargar)";
        $checks['folder_' . $folder] = false;
    }
}

// =============================================================================
// CHECK 6: Dependencias Python
// =============================================================================
echo "<h2>🐍 Dependencias Python</h2>";

$python_ok = false;
$python_version = 'N/A';

exec('python --version 2>&1', $output, $code);
if ($code === 0 && !empty($output)) {
    $python_version = trim($output[0]);
    $python_ok = true;
}

if ($python_ok) {
    echo "<div class='check-item ok'>
      <span class='icon ok'></span>
      <strong>Python</strong> - Instalado: $python_version
    </div>";
} else {
    echo "<div class='check-item error'>
      <span class='icon error'></span>
      <strong>Python</strong> - NO INSTALADO
      <br><small>Descargar desde: <a href='https://www.python.org/downloads/' target='_blank'>python.org</a></small>
    </div>";
    $errors[] = "Python no instalado";
}

// Verificar librerías Python (si Python está instalado)
if ($python_ok) {
    $libs = ['requests', 'beautifulsoup4', 'pillow', 'opencv-python', 'pytesseract', 'numpy'];
    $missing_libs = [];
    
    foreach ($libs as $lib) {
        exec("python -c \"import " . str_replace('-', '_', $lib) . "\" 2>&1", $output, $code);
        if ($code !== 0) {
            $missing_libs[] = $lib;
        }
    }
    
    if (empty($missing_libs)) {
        echo "<div class='check-item ok'>
          <span class='icon ok'></span>
          <strong>Librerías Python</strong> - Todas instaladas
          <br><small>" . implode(', ', $libs) . "</small>
        </div>";
    } else {
        echo "<div class='check-item warning'>
          <span class='icon warning'></span>
          <strong>Librerías Python</strong> - Faltan: " . implode(', ', $missing_libs) . "
          <br><small>Instalar: <span class='code'>pip install " . implode(' ', $missing_libs) . "</span></small>
        </div>";
        $warnings[] = "Faltan librerías Python: " . implode(', ', $missing_libs);
    }
}

// =============================================================================
// RESUMEN
// =============================================================================

$ok_count = count(array_filter($checks, fn($v) => $v === true));
$total_count = count($checks);
$error_count = count($errors);
$warning_count = count($warnings);

$status = 'LISTO' . ($error_count > 0 ? ' CON ERRORES' : '') . ($warning_count > 0 ? ' (avisos)' : '');
$status_color = $error_count > 0 ? '#dc3545' : ($warning_count > 0 ? '#ffc107' : '#28a745');

echo "<div class='summary'>
  <h2 style='border-bottom: 3px solid $status_color; padding-bottom: 10px;'>
    📊 Resumen: $status
  </h2>
  
  <table>
    <tr>
      <th>Categoría</th>
      <th>Estado</th>
      <th>Detalles</th>
    </tr>
    <tr>
      <td>Checklists completados</td>
      <td style='color: #028a45;'><strong>$ok_count / $total_count</strong></td>
      <td>" . round(($ok_count / $total_count) * 100) . "%</td>
    </tr>
    <tr>
      <td>Errores críticos</td>
      <td style='color: " . ($error_count > 0 ? '#dc3545' : '#28a745') . ";'><strong>$error_count</strong></td>
      <td>" . ($error_count > 0 ? '⚠️ REVISAR' : '✓ OK') . "</td>
    </tr>
    <tr>
      <td>Avisos / Recomendaciones</td>
      <td style='color: #ffc107;'><strong>$warning_count</strong></td>
      <td>" . ($warning_count > 0 ? '⚠️ Revisar' : '✓ OK') . "</td>
    </tr>
  </table>
</div>";

// Mostrar errores
if (!empty($errors)) {
    echo "<div class='summary' style='border-left: 5px solid #dc3545;'>
      <h3>❌ Errores Críticos</h3>
      <ul>";
    foreach ($errors as $error) {
        echo "<li>$error</li>";
    }
    echo "</ul>
      <p><strong>Acción requerida:</strong> Resolver estos errores antes de continuar.</p>
    </div>";
}

// Mostrar warnings
if (!empty($warnings)) {
    echo "<div class='summary' style='border-left: 5px solid #ffc107;'>
      <h3>⚠️ Avisos / Recomendaciones</h3>
      <ul>";
    foreach ($warnings as $warning) {
        echo "<li>$warning</li>";
    }
    echo "</ul>
      <p><strong>Nota:</strong> Estos no bloquean, pero se recomienda revisar.</p>
    </div>";
}

// Próximos pasos
echo "<div class='summary'>
  <h3>🚀 Próximos Pasos</h3>";

if ($error_count === 0) {
    echo "
    <ol>
      <li><strong>Leer documentación:</strong>
        <ul>
          <li>Abre <span class='code'>README_PLANOS_REALES.md</span> para resumen ejecutivo</li>
          <li>Abre <span class='code'>PLAN_PLANOS_REALES_MICROS.md</span> para detalles técnicos</li>
          <li>Abre <span class='code'>EJECUTAR_PLANOS_REALES.md</span> para checklist paso a paso</li>
        </ul>
      </li>
      <li><strong>Ejecutar descarga:</strong>
        <br><span class='code'>python descargar_planos_mundocolectivo.py</span>
        <br><em>(Durará ~1-2 horas para descargar todos)</em>
      </li>
      <li><strong>Procesar con OCR:</strong>
        <br><span class='code'>python procesar_planos_ocr.py</span>
        <br><em>(Durará ~2-3 horas, puede correr de noche)</em>
      </li>
      <li><strong>Crear frontend:</strong>
        <br>Implementar <span class='code'>pasaje_detalle.php</span> y <span class='code'>js/gestor_asientos.js</span>
        <br><em>Usar templates en PLAN_PLANOS_REALES_MICROS.md</em>
      </li>
      <li><strong>Testing:</strong>
        <br>Verificar en http://localhost/metelebrasil_dev/pasaje_detalle.php
      </li>
    </ol>
    <p style='background: #f0fdf4; padding: 10px; border-radius: 5px; color: #028a45;'>
      ✅ <strong>¡Sistema listo para integración!</strong> Tiempo estimado: 7-9 horas
    </p>";
} else {
    echo "
    <p style='background: #fdf0f0; padding: 10px; border-radius: 5px; color: #dc3545;'>
      ❌ <strong>Resolver errores críticos antes de proceder</strong>
    </p>";
}

echo "</div>";

// Final
echo "
  </div>
</body>
</html>";
?>
