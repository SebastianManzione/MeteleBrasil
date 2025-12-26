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
  <title>Login Admin</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
  <div class="container" style="max-width:480px; margin-top:60px;">
    <div class="card shadow-sm">
      <div class="card-header"><strong>Acceso Administrador</strong></div>
      <div class="card-body">
        <?php if (!empty($error_message)): ?>
          <div class="alert alert-danger" role="alert">
            <i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($error_message) ?>
          </div>
        <?php endif; ?>
        <form method="POST" action="">
          <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Contraseña</label>
            <input type="password" name="clave" class="form-control" required>
          </div>
          <button type="submit" class="btn btn-primary btn-block">Ingresar</button>
        </form>
        <div class="mt-3 text-center">
          <a href="../mantenimiento.php" class="text-muted" style="font-size:12px;">Volver</a>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
