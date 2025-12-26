<?php
/**
 * Página de Mantenimiento
 * Se muestra cuando el sitio está en modo mantenimiento
 */

// Obtener configuración sin incluir navbar
require_once(__DIR__ . '/admin/classes/conexion.php');
require_once(__DIR__ . '/admin/classes/configuracion.php');

$config = new Configuracion();

// Si no está en mantenimiento, redirigir a index
if (!$config->mantenimientoActivo()) {
    header('Location: index.php');
    exit;
}

$mensaje = $config->obtenerMensajeMantenimiento();
$sitio_nombre = $config->obtener('sitio_nombre', 'MeteleBrasil');
$sitio_email = $config->obtener('sitio_email', 'info@metelebrasil.com');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mantenimiento - <?=$sitio_nombre?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(-45deg, #00c6ff, #0072ff, #00c6ff, #0095ff);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
            position: relative;
        }
        
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        /* Partículas flotantes de fondo */
        .particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 1;
        }
        
        .particle {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 20s infinite ease-in-out;
        }
        
        .particle:nth-child(1) { width: 80px; height: 80px; left: 10%; animation-delay: 0s; }
        .particle:nth-child(2) { width: 60px; height: 60px; left: 20%; animation-delay: 2s; }
        .particle:nth-child(3) { width: 100px; height: 100px; left: 60%; animation-delay: 4s; }
        .particle:nth-child(4) { width: 40px; height: 40px; left: 80%; animation-delay: 6s; }
        .particle:nth-child(5) { width: 70px; height: 70px; left: 40%; animation-delay: 8s; }
        
        @keyframes float {
            0%, 100% { transform: translateY(100vh) rotate(0deg); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { transform: translateY(-100vh) rotate(720deg); opacity: 0; }
        }
        
        .maintenance-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 30px;
            box-shadow: 0 30px 90px rgba(0,0,0,0.3);
            padding: 60px 50px;
            text-align: center;
            max-width: 700px;
            width: 90%;
            animation: fadeInUp 0.8s ease-out;
            position: relative;
            z-index: 10;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(60px) scale(0.9);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        
        .logo-container {
            margin-bottom: 30px;
            animation: bounceIn 1s ease-out;
        }
        
        @keyframes bounceIn {
            0% { transform: scale(0); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        
        .maintenance-icon-container {
            position: relative;
            display: inline-block;
            margin-bottom: 30px;
        }
        
        .maintenance-icon {
            font-size: 120px;
            background: linear-gradient(135deg, #0072ff, #00c6ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: bounce 2s infinite ease-in-out;
            filter: drop-shadow(0 10px 20px rgba(0, 114, 255, 0.3));
        }
        
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }
        
        .gear-background {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 180px;
            color: rgba(0, 114, 255, 0.1);
            animation: rotateGear 20s linear infinite;
            z-index: -1;
        }
        
        @keyframes rotateGear {
            from { transform: translate(-50%, -50%) rotate(0deg); }
            to { transform: translate(-50%, -50%) rotate(360deg); }
        }
        
        h1 {
            color: #1a1a1a;
            font-size: 42px;
            margin-bottom: 15px;
            font-weight: 700;
            letter-spacing: -1px;
        }
        
        .subtitle {
            color: #666;
            font-size: 18px;
            font-weight: 300;
            margin-bottom: 35px;
        }
        
        .maintenance-message {
            color: #555;
            font-size: 18px;
            line-height: 1.8;
            margin-bottom: 40px;
            white-space: pre-wrap;
            font-weight: 400;
        }
        
        .status-badge {
            display: inline-flex;
            align-items: center;
            background: linear-gradient(135deg, #ffd700, #ffed4e);
            color: #1a1a1a;
            padding: 12px 24px;
            border-radius: 30px;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 35px;
            box-shadow: 0 8px 20px rgba(255, 215, 0, 0.4);
            animation: pulseGlow 2s infinite;
            letter-spacing: 1px;
        }
        
        @keyframes pulseGlow {
            0%, 100% { 
                box-shadow: 0 8px 20px rgba(255, 215, 0, 0.4);
                transform: scale(1);
            }
            50% { 
                box-shadow: 0 8px 30px rgba(255, 215, 0, 0.6);
                transform: scale(1.05);
            }
        }
        
        .status-dot {
            width: 10px;
            height: 10px;
            background: #ff6b00;
            border-radius: 50%;
            margin-right: 10px;
            animation: blink 1.5s infinite;
        }
        
        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }
        
        .contact-box {
            background: linear-gradient(135deg, rgba(0, 114, 255, 0.1), rgba(0, 198, 255, 0.1));
            padding: 30px;
            border-radius: 20px;
            margin: 40px 0;
            border: 2px solid rgba(0, 114, 255, 0.2);
            transition: all 0.3s ease;
        }
        
        .contact-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 114, 255, 0.2);
        }
        
        .contact-box p {
            margin: 0;
            color: #333;
            font-size: 16px;
        }
        
        .contact-box a {
            color: #0072ff;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .contact-box a:hover {
            color: #00c6ff;
            text-decoration: underline;
        }
        
        .icon-wrapper {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #0072ff, #00c6ff);
            border-radius: 50%;
            margin-right: 12px;
            color: white;
            box-shadow: 0 5px 15px rgba(0, 114, 255, 0.3);
        }
        
        .progress-bar {
            width: 100%;
            height: 4px;
            background: rgba(0, 114, 255, 0.2);
            border-radius: 10px;
            margin: 30px 0;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #0072ff, #00c6ff);
            border-radius: 10px;
            animation: loading 2s infinite ease-in-out;
        }
        
        @keyframes loading {
            0% { width: 0%; }
            50% { width: 70%; }
            100% { width: 100%; }
        }
        
        .timer-box {
            display: inline-flex;
            align-items: center;
            background: rgba(0, 114, 255, 0.1);
            padding: 12px 20px;
            border-radius: 15px;
            font-size: 14px;
            color: #555;
            margin-top: 25px;
        }
        
        .timer-box i {
            color: #0072ff;
            margin-right: 10px;
            animation: tick 1s infinite;
        }
        
        @keyframes tick {
            0%, 100% { transform: rotate(0deg); }
            50% { transform: rotate(360deg); }
        }
        
        .admin-link {
            margin-top: 35px;
            padding-top: 25px;
            border-top: 1px solid rgba(0,0,0,0.1);
        }
        
        .admin-link a {
            color: #999;
            font-size: 13px;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
        }
        
        .admin-link a:hover {
            color: #0072ff;
            transform: translateY(-2px);
        }
        
        .admin-link i {
            margin-right: 8px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .maintenance-container {
                padding: 40px 30px;
            }
            
            h1 {
                font-size: 32px;
            }
            
            .maintenance-icon {
                font-size: 80px;
            }
            
            .subtitle {
                font-size: 16px;
            }
            
            .maintenance-message {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <!-- Partículas de fondo -->
    <div class="particles">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>

    <div class="maintenance-container">
        <div class="status-badge">
            <span class="status-dot"></span>
            En Mantenimiento
        </div>
        
        <div class="maintenance-icon-container">
            <div class="gear-background">
                <i class="fas fa-cog"></i>
            </div>
            <div class="maintenance-icon">
                <i class="fas fa-rocket"></i>
            </div>
        </div>
        
        <h1><?=$sitio_nombre?></h1>
        <p class="subtitle">Estamos mejorando para ti</p>
        
        <div class="progress-bar">
            <div class="progress-fill"></div>
        </div>
        
        <div class="maintenance-message">
            <?=htmlspecialchars($mensaje)?>
        </div>
        
        <div class="contact-box">
            <div class="icon-wrapper">
                <i class="fas fa-envelope"></i>
            </div>
            <p style="display: inline;">
                <strong>¿Necesitas ayuda?</strong><br>
                <a href="mailto:<?=$sitio_email?>"><?=$sitio_email?></a>
            </p>
        </div>
        
        <div class="timer-box">
            <i class="fas fa-sync-alt"></i>
            Última verificación: <span id="last-check" style="font-weight: 600; margin-left: 5px;">Verificando...</span>
        </div>
        
        <div class="admin-link">
            <a href="admin/login.php" title="Acceso para administradores">
                <i class="fas fa-lock"></i> Acceso Administrador
            </a>
        </div>
    </div>

    <script>
        // Verificar cada 30 segundos si el mantenimiento terminó
        setInterval(() => {
            fetch('admin/classes/check_maintenance.php')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('last-check').textContent = 
                        new Date().toLocaleTimeString('es-ES');
                    
                    if (!data.maintenance_active) {
                        // El mantenimiento terminó, recargar página
                        location.reload();
                    }
                });
        }, 30000);
        
        // Actualizar hora de última verificación
        document.getElementById('last-check').textContent = 
            new Date().toLocaleTimeString('es-ES');
    </script>
</body>
</html>
