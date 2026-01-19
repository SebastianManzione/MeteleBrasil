<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once(__DIR__ . '/classes/conexion.php');
require_once(__DIR__ . '/classes/functions.php');
$error_message = '';

// Si ya está logueado y es admin, ir al panel
if (isset($_SESSION['login']['rol']) && $_SESSION['login']['rol'] == 1) {
  header('Location: index.php');
  exit;
}

// Procesar login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'] ?? '';
  $clave = $_POST['clave'] ?? '';
  $clave64 = md5($clave);

  $data = ["email" => $email];
  $consulta = "select * from usuario WHERE email=:email";
  $comando = $pdo->prepare($consulta);
  $comando->execute($data);
  $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

  if (count($resultado) == 1 && $resultado[0]["clave"] == $clave64 && intval($resultado[0]['rol']) === 1) {
    $_SESSION["login"]['active']    = true;
    $_SESSION["login"]['idUsuario'] = $resultado[0]['idUsuario'];
    $_SESSION["login"]['usuario']   = $resultado[0]['usuario'];
    $_SESSION["login"]['foto']      = $resultado[0]['fotoUsuario'];
    $_SESSION["login"]['rol']       = $resultado[0]['rol'];
    $_SESSION["login"]['idVendedor']= $resultado[0]['idVendedor'];
    $_SESSION["login"]['idCobrador']= $resultado[0]['idCobrador'];
    $_SESSION["login"]['idPrestador']= $resultado[0]['idPrestador'];

    alertar('Bienvenido al panel admin ' . $_SESSION["login"]['usuario'], 'success');
    // Desde /admin/login.php, usar ruta relativa al directorio actual
    redireccionar('index.php');
    exit;
  } else {
    // Mensajes claros para credenciales inválidas o sin rol admin
    if (count($resultado) == 1 && intval($resultado[0]['rol']) !== 1) {
      $error_message = 'Tu usuario no tiene permisos de administrador.';
    } else {
      $error_message = 'Usuario o contraseña incorrectos.';
    }
    alertar($error_message, 'error');
  }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login Admin - MeteleBrasil</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <style>
    body {
      background: linear-gradient(135deg, #029ce2 0%, #0277bd 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      position: relative;
      overflow: hidden;
    }
    
    body::before {
      content: '';
      position: absolute;
      top: -50%;
      right: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
      animation: rotate 30s linear infinite;
    }
    
    @keyframes rotate {
      from { transform: rotate(0deg); }
      to { transform: rotate(360deg); }
    }
    
    .login-container {
      max-width: 450px;
      width: 100%;
      padding: 15px;
      position: relative;
      z-index: 1;
    }
    
    .logo-container {
      text-align: center;
      margin-bottom: 2rem;
      animation: fadeInDown 0.8s ease;
    }
    
    .logo-container img {
      max-width: 200px;
      height: auto;
      filter: drop-shadow(0 5px 15px rgba(0,0,0,0.3));
    }
    
    @keyframes fadeInDown {
      from {
        opacity: 0;
        transform: translateY(-30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    .card {
      border: none;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 20px 60px rgba(0,0,0,0.3);
      animation: fadeInUp 0.8s ease;
      background: white;
    }
    
    .card-header {
      background: linear-gradient(135deg, #029ce2 0%, #0277bd 100%);
      color: white;
      text-align: center;
      padding: 2rem 1.5rem 1.5rem;
      border: none;
    }
    
    .card-header h3 {
      margin: 0;
      font-weight: 700;
      font-size: 1.8rem;
      text-transform: uppercase;
      letter-spacing: 1px;
    }
    
    .card-header p {
      margin: 0.5rem 0 0 0;
      opacity: 0.95;
      font-size: 0.95rem;
      font-weight: 300;
    }
    
    .card-body {
      padding: 2.5rem;
    }
    
    .form-group {
      margin-bottom: 1.5rem;
    }
    
    .form-group label {
      font-weight: 600;
      color: #2c3e50;
      font-size: 0.95rem;
      margin-bottom: 0.5rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }
    
    .form-control {
      border-radius: 10px;
      padding: 0.85rem 1rem;
      border: 2px solid #e9ecef;
      transition: all 0.3s ease;
      font-size: 1rem;
    }
    
    .form-control:focus {
      border-color: #029ce2;
      box-shadow: 0 0 0 0.2rem rgba(2, 156, 226, 0.15);
      background-color: #f8fcff;
    }
    
    .input-group-text {
      background: linear-gradient(135deg, #029ce2 0%, #0277bd 100%);
      border: none;
      border-radius: 10px 0 0 10px;
      color: white;
      padding: 0.85rem 1rem;
    }
    
    .input-group .form-control {
      border-left: 2px solid #e9ecef;
      border-radius: 0 10px 10px 0;
    }
    
    .btn-primary {
      background: linear-gradient(135deg, #029ce2 0%, #0277bd 100%);
      border: none;
      border-radius: 10px;
      padding: 1rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 1px;
      font-size: 1rem;
      transition: all 0.3s ease;
      box-shadow: 0 5px 15px rgba(2, 156, 226, 0.3);
    }
    
    .btn-primary:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 25px rgba(2, 156, 226, 0.5);
      background: linear-gradient(135deg, #0277bd 0%, #029ce2 100%);
    }
    
    .btn-primary:active {
      transform: translateY(-1px);
    }
    
    .alert {
      border-radius: 10px;
      border: none;
      font-size: 0.9rem;
      padding: 1rem 1.2rem;
      animation: shake 0.5s ease;
    }
    
    @keyframes shake {
      0%, 100% { transform: translateX(0); }
      25% { transform: translateX(-10px); }
      75% { transform: translateX(10px); }
    }
    
    .alert-danger {
      background-color: #ffe6e6;
      color: #c92a2a;
      border-left: 4px solid #c92a2a;
    }
    
    .back-link {
      text-align: center;
      margin-top: 1.5rem;
      animation: fadeInUp 1s ease;
    }
    
    .back-link a {
      color: white;
      text-decoration: none;
      font-size: 0.95rem;
      font-weight: 500;
      transition: all 0.2s ease;
      padding: 0.5rem 1rem;
      border-radius: 20px;
      display: inline-block;
      background: rgba(255,255,255,0.1);
      backdrop-filter: blur(10px);
    }
    
    .back-link a:hover {
      background: rgba(255,255,255,0.2);
      transform: translateY(-2px);
    }
    
    .security-badge {
      text-align: center;
      margin-top: 1rem;
      color: #6c757d;
      font-size: 0.75rem;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
    }
    
    .security-badge i {
      color: #28a745;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <!-- Logo MeteleBrasil -->
    <div class="logo-container">
      <img src="../img/metelebrasil.png" alt="MeteleBrasil">
    </div>
    
    <div class="card">
      <div class="card-header">
        <h3><i class="fas fa-shield-alt"></i> Panel Admin</h3>
        <p>Sistema de Gestión y Administración</p>
      </div>
      <div class="card-body">
        <?php if (!empty($error_message)): ?>
          <div class="alert alert-danger" role="alert">
            <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error_message) ?>
          </div>
        <?php endif; ?>
        <form method="POST" action="">
          <div class="form-group">
            <label>
              <i class="fas fa-envelope"></i> Email
            </label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-user"></i></span>
              </div>
              <input type="email" name="email" class="form-control" placeholder="correo@ejemplo.com" required autocomplete="username">
            </div>
          </div>
          <div class="form-group">
            <label>
              <i class="fas fa-lock"></i> Contraseña
            </label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-key"></i></span>
              </div>
              <input type="password" name="clave" class="form-control" placeholder="••••••••" required autocomplete="current-password">
            </div>
          </div>
          <button type="submit" class="btn btn-primary btn-block mt-4">
            <i class="fas fa-sign-in-alt"></i> Ingresar al Sistema
          </button>
        </form>
        <div class="security-badge">
          <i class="fas fa-lock"></i> Conexión segura y encriptada
        </div>
      </div>
    </div>
    
    <div class="back-link">
      <a href="../index.php">
        <i class="fas fa-arrow-left"></i> Volver al sitio web
      </a>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
