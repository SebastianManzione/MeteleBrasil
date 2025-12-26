<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Verificación Anti-Bot</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <style>
        .status-ok { color: #28a745; font-weight: bold; }
        .status-error { color: #dc3545; font-weight: bold; }
        .status-warning { color: #ffc107; font-weight: bold; }
        .code-block { 
            background: #f4f4f4; 
            padding: 15px; 
            border-radius: 5px; 
            font-family: monospace;
            margin: 10px 0;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center mb-4">🛡️ Panel de Verificación Anti-Bot</h1>
    
    <?php
    // Solo accesible para admin
    session_start();
    if (!isset($_SESSION['login']['idUsuario']) || $_SESSION['login']['idUsuario'] != 1) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-top: 20px; padding: 20px; margin: 20px;">';
        echo '<h4 class="alert-heading">⛔ Acceso Denegado</h4>';
        echo '<p>Solo los administradores pueden acceder a este panel.</p>';
        echo '<hr><a href="index.php" class="btn btn-primary btn-sm">← Volver al Dashboard</a>';
        echo '</div></div></body></html>';
        die();
    }
    
    require_once("admin/classes/conexion.php");
    require_once("config/recaptcha.php");
    
    $conn = conectar();
    
    // 1. Verificar archivos del sistema
    echo '<div class="card mb-4">';
    echo '<div class="card-header bg-primary text-white"><h4>1. Verificación de Archivos</h4></div>';
    echo '<div class="card-body">';
    
    $archivos_requeridos = [
        'config/recaptcha.php' => 'Configuración reCAPTCHA',
        'admin/classes/antibot.php' => 'Sistema Anti-Bot',
        'js/antibot.js' => 'JavaScript Anti-Bot'
    ];
    
    foreach ($archivos_requeridos as $archivo => $descripcion) {
        $existe = file_exists($archivo);
        $status = $existe ? 'status-ok' : 'status-error';
        $icono = $existe ? '✓' : '✗';
        echo "<div><span class='$status'>$icono</span> $descripcion ($archivo)</div>";
    }
    echo '</div></div>';
    
    // 2. Verificar configuración reCAPTCHA
    echo '<div class="card mb-4">';
    echo '<div class="card-header bg-info text-white"><h4>2. Configuración reCAPTCHA</h4></div>';
    echo '<div class="card-body">';
    
    $site_key = RECAPTCHA_SITE_KEY;
    $secret_key = RECAPTCHA_SECRET_KEY;
    $threshold = RECAPTCHA_SCORE_THRESHOLD;
    
    $es_test_key = ($site_key === '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI');
    
    echo "<div><strong>Site Key:</strong> " . substr($site_key, 0, 20) . "...</div>";
    echo "<div><strong>Secret Key:</strong> " . substr($secret_key, 0, 20) . "...</div>";
    echo "<div><strong>Score Threshold:</strong> $threshold</div>";
    
    if ($es_test_key) {
        echo '<div class="alert alert-warning mt-3">';
        echo '<strong>⚠️ ATENCIÓN:</strong> Estás usando las TEST KEYS de Google.<br>';
        echo 'Para producción, debes obtener tus propias claves en: ';
        echo '<a href="https://www.google.com/recaptcha/admin/create" target="_blank">https://www.google.com/recaptcha/admin/create</a>';
        echo '</div>';
    } else {
        echo '<div class="alert alert-success mt-3">';
        echo '<strong>✓ Claves personalizadas configuradas</strong>';
        echo '</div>';
    }
    echo '</div></div>';
    
    // 3. Verificar tablas de base de datos
    echo '<div class="card mb-4">';
    echo '<div class="card-header bg-success text-white"><h4>3. Tablas de Base de Datos</h4></div>';
    echo '<div class="card-body">';
    
    $tablas = [
        'form_rate_limit' => 'Control de Rate Limiting',
        'bot_attempts' => 'Log de Intentos de Bots'
    ];
    
    foreach ($tablas as $tabla => $descripcion) {
        $result = $conn->query("SHOW TABLES LIKE '$tabla'");
        $existe = ($result->num_rows > 0);
        $status = $existe ? 'status-ok' : 'status-warning';
        $icono = $existe ? '✓' : '⚠';
        $mensaje = $existe ? "$icono $descripcion ($tabla) - Existe" : "$icono $descripcion ($tabla) - Se creará automáticamente al primer uso";
        echo "<div><span class='$status'>$mensaje</span></div>";
        
        if ($existe) {
            $count = $conn->query("SELECT COUNT(*) as total FROM $tabla")->fetch_assoc()['total'];
            echo "<div class='ml-4 text-muted'>Registros actuales: $count</div>";
        }
    }
    echo '</div></div>';
    
    // 4. Estadísticas de Bots Bloqueados
    echo '<div class="card mb-4">';
    echo '<div class="card-header bg-danger text-white"><h4>4. Estadísticas de Protección</h4></div>';
    echo '<div class="card-body">';
    
    $tabla_existe = $conn->query("SHOW TABLES LIKE 'bot_attempts'")->num_rows > 0;
    
    if ($tabla_existe) {
        // Bots bloqueados hoy
        $hoy = $conn->query("SELECT COUNT(*) as total FROM bot_attempts WHERE DATE(attempt_time) = CURDATE()")->fetch_assoc()['total'];
        echo "<div><strong>Bots bloqueados hoy:</strong> <span class='badge badge-danger'>$hoy</span></div>";
        
        // Últimos 7 días
        $semana = $conn->query("SELECT COUNT(*) as total FROM bot_attempts WHERE attempt_time > DATE_SUB(NOW(), INTERVAL 7 DAY)")->fetch_assoc()['total'];
        echo "<div><strong>Últimos 7 días:</strong> <span class='badge badge-warning'>$semana</span></div>";
        
        // Total histórico
        $total = $conn->query("SELECT COUNT(*) as total FROM bot_attempts")->fetch_assoc()['total'];
        echo "<div><strong>Total histórico:</strong> <span class='badge badge-info'>$total</span></div>";
        
        // Por tipo
        echo "<div class='mt-3'><strong>Bloqueos por tipo:</strong></div>";
        $tipos = $conn->query("SELECT tipo, COUNT(*) as total FROM bot_attempts GROUP BY tipo ORDER BY total DESC");
        if ($tipos->num_rows > 0) {
            echo '<ul>';
            while ($row = $tipos->fetch_assoc()) {
                echo "<li>{$row['tipo']}: <span class='badge badge-secondary'>{$row['total']}</span></li>";
            }
            echo '</ul>';
        } else {
            echo "<div class='text-muted'>Sin bloqueos registrados aún</div>";
        }
        
        // Últimos 10 intentos
        echo "<div class='mt-4'><strong>Últimos 10 intentos bloqueados:</strong></div>";
        $ultimos = $conn->query("SELECT ip_address, tipo, detalles, attempt_time FROM bot_attempts ORDER BY attempt_time DESC LIMIT 10");
        if ($ultimos->num_rows > 0) {
            echo '<table class="table table-sm table-striped mt-2">';
            echo '<thead><tr><th>IP</th><th>Tipo</th><th>Detalles</th><th>Fecha</th></tr></thead><tbody>';
            while ($row = $ultimos->fetch_assoc()) {
                $ip = htmlspecialchars($row['ip_address']);
                $tipo = htmlspecialchars($row['tipo']);
                $detalles = htmlspecialchars(substr($row['detalles'], 0, 50));
                $fecha = date('d/m/Y H:i', strtotime($row['attempt_time']));
                echo "<tr><td>$ip</td><td><span class='badge badge-danger'>$tipo</span></td><td>$detalles...</td><td>$fecha</td></tr>";
            }
            echo '</tbody></table>';
        } else {
            echo "<div class='text-muted'>Sin intentos registrados</div>";
        }
    } else {
        echo '<div class="alert alert-info">La tabla se creará automáticamente cuando se detecte el primer bot.</div>';
    }
    echo '</div></div>';
    
    // 5. Formularios Protegidos
    echo '<div class="card mb-4">';
    echo '<div class="card-header bg-secondary text-white"><h4>5. Formularios Protegidos</h4></div>';
    echo '<div class="card-body">';
    
    $formularios = [
        'contact.php' => 'Formulario de Contacto',
        'registro.php' => 'Registro de Usuarios',
        'registroAgencias.php' => 'Registro de Agencias',
        'registroPrestadores.php' => 'Registro de Prestadores',
        'registroFreelancers.php' => 'Registro de Freelancers',
        'registroOperadores.php' => 'Registro de Operadores',
        'recuperar_contrasena.php' => 'Recuperación de Contraseña'
    ];
    
    echo '<ul>';
    foreach ($formularios as $archivo => $nombre) {
        // Verificar que incluye antibot.php
        $contenido = file_get_contents($archivo);
        $tiene_antibot = strpos($contenido, 'antibot.php') !== false;
        $tiene_validacion = strpos($contenido, 'validarAntiBot') !== false;
        $tiene_campos = strpos($contenido, 'generarCamposAntiBot') !== false;
        
        $status = ($tiene_antibot && $tiene_validacion && $tiene_campos) ? 'status-ok' : 'status-error';
        $icono = ($tiene_antibot && $tiene_validacion && $tiene_campos) ? '✓' : '✗';
        
        echo "<li><span class='$status'>$icono</span> <strong>$nombre</strong> ($archivo)";
        if (!$tiene_antibot) echo " <span class='badge badge-danger'>Sin include antibot</span>";
        if (!$tiene_validacion) echo " <span class='badge badge-danger'>Sin validación</span>";
        if (!$tiene_campos) echo " <span class='badge badge-danger'>Sin campos</span>";
        echo "</li>";
    }
    echo '</ul>';
    echo '</div></div>';
    
    // 6. Rate Limiting Activo
    echo '<div class="card mb-4">';
    echo '<div class="card-header bg-warning text-white"><h4>6. Rate Limiting Activo</h4></div>';
    echo '<div class="card-body">';
    
    $tabla_existe = $conn->query("SHOW TABLES LIKE 'form_rate_limit'")->num_rows > 0;
    
    if ($tabla_existe) {
        $activos = $conn->query("
            SELECT ip_address, action, COUNT(*) as intentos, MAX(attempt_time) as ultimo
            FROM form_rate_limit 
            WHERE attempt_time > DATE_SUB(NOW(), INTERVAL 1 HOUR)
            GROUP BY ip_address, action
            HAVING intentos >= 2
            ORDER BY intentos DESC
        ");
        
        if ($activos->num_rows > 0) {
            echo '<table class="table table-sm table-striped">';
            echo '<thead><tr><th>IP</th><th>Acción</th><th>Intentos (1h)</th><th>Último</th></tr></thead><tbody>';
            while ($row = $activos->fetch_assoc()) {
                $clase = $row['intentos'] >= 5 ? 'table-danger' : 'table-warning';
                echo "<tr class='$clase'>";
                echo "<td>{$row['ip_address']}</td>";
                echo "<td>{$row['action']}</td>";
                echo "<td><span class='badge badge-danger'>{$row['intentos']}/5</span></td>";
                echo "<td>" . date('H:i:s', strtotime($row['ultimo'])) . "</td>";
                echo "</tr>";
            }
            echo '</tbody></table>';
        } else {
            echo '<div class="text-success">✓ Sin rate limiting activo actualmente</div>';
        }
    } else {
        echo '<div class="alert alert-info">La tabla se creará automáticamente al primer uso.</div>';
    }
    echo '</div></div>';
    
    // 7. Herramientas de Admin
    echo '<div class="card mb-4">';
    echo '<div class="card-header bg-dark text-white"><h4>7. Herramientas de Administración</h4></div>';
    echo '<div class="card-body">';
    
    if (isset($_POST['limpiar_rate_limit'])) {
        $conn->query("DELETE FROM form_rate_limit WHERE attempt_time < DATE_SUB(NOW(), INTERVAL 1 HOUR)");
        echo '<div class="alert alert-success">✓ Rate limits antiguos limpiados</div>';
    }
    
    if (isset($_POST['limpiar_bots'])) {
        $conn->query("DELETE FROM bot_attempts WHERE attempt_time < DATE_SUB(NOW(), INTERVAL 30 DAY)");
        echo '<div class="alert alert-success">✓ Logs de bots antiguos limpiados</div>';
    }
    
    echo '<form method="post" class="d-inline">';
    echo '<button type="submit" name="limpiar_rate_limit" class="btn btn-warning mr-2">🧹 Limpiar Rate Limits Antiguos</button>';
    echo '</form>';
    
    echo '<form method="post" class="d-inline">';
    echo '<button type="submit" name="limpiar_bots" class="btn btn-secondary">🗑️ Limpiar Logs > 30 días</button>';
    echo '</form>';
    
    echo '</div></div>';
    
    // 8. Consultas SQL Útiles
    echo '<div class="card mb-4">';
    echo '<div class="card-header bg-primary text-white"><h4>8. Consultas SQL Útiles</h4></div>';
    echo '<div class="card-body">';
    
    echo '<div class="code-block">';
    echo '-- Ver todos los bots bloqueados hoy<br>';
    echo 'SELECT * FROM bot_attempts WHERE DATE(attempt_time) = CURDATE();<br><br>';
    
    echo '-- IPs más problemáticas<br>';
    echo 'SELECT ip_address, COUNT(*) as intentos FROM bot_attempts<br>';
    echo 'GROUP BY ip_address ORDER BY intentos DESC LIMIT 20;<br><br>';
    
    echo '-- Limpiar rate limit de IP específica<br>';
    echo "DELETE FROM form_rate_limit WHERE ip_address = 'IP_AQUI';<br>";
    echo '</div>';
    
    echo '</div></div>';
    
    $conn->close();
    ?>
    
    <div class="alert alert-info">
        <strong>📚 Documentación completa:</strong> Ver archivo <code>ANTIBOT_README.md</code>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
