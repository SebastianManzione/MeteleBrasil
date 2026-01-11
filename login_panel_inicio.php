<?php
session_start();
$login = isset($_SESSION['login']) ? $_SESSION['login'] : [];
$esPrivilegiado = isset($login['rol']) && ($login['rol'] == 1 || $login['rol'] == 5);
$esOperador = !empty($login['idPrestador']) || !empty($login['idVendedor']) || !empty($login['idCobrador']);
$puedeVerAdmin = $esPrivilegiado || $esOperador;
?>
<!DOCTYPE html>

<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel de Inicio - Metele Brasil</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

  <style>

    :root {
      --primary: #029ce2;
      --primary-strong: #0278b1;
      --accent: #ffc107;
      --dark: #0f1f2d;
      --muted: #5b6b7a;
      --card: #ffffff;
      --bg: #f5f7fb;
      --shadow: 0 18px 45px rgba(2, 156, 226, 0.12);
    }

    body {font-family:'Poppins',sans-serif;background:var(--bg);color:var(--dark);min-height:100vh;}

    .login-hero {position:relative;overflow:hidden;color:#fff;padding:3.6rem 1rem 3rem;box-shadow:0 10px 35px rgba(0,0,0,0.25);border-bottom:4px solid var(--accent);text-align:center;background:linear-gradient(180deg,rgba(0,0,0,0.55),rgba(0,0,0,0.25)),url('img/slider4.jpg');background-size:cover;background-position:center;}
    .login-hero::before {content:'';position:absolute;inset:0;background:radial-gradient(circle at 20% 20%,rgba(2,156,226,0.35),transparent 35%),radial-gradient(circle at 80% 30%,rgba(2,120,177,0.3),transparent 38%);}    
    .login-hero .content {position:relative;z-index:1;}
    .login-hero .eyebrow {text-transform:uppercase;letter-spacing:0.12em;font-size:0.85rem;color:rgba(255,255,255,0.78);margin-bottom:0.55rem;font-weight:600;}
    .login-hero h1 {font-weight:700;letter-spacing:0.03em;margin-bottom:0.65rem;}
    .login-hero .lede {font-size:1.05rem;max-width:780px;margin:0 auto;color:rgba(255,255,255,0.86);} 
    .login-hero .chips {margin-top:1.2rem;display:flex;gap:0.65rem;flex-wrap:wrap;justify-content:center;}
    .chip {background:rgba(255,255,255,0.12);color:#fff;border:1px solid rgba(255,255,255,0.25);border-radius:999px;padding:0.45rem 0.95rem;font-size:0.9rem;font-weight:600;backdrop-filter:blur(2px);}   

    .brand-lockup {display:inline-flex;align-items:center;gap:0.75rem;padding:0.55rem 0.95rem;border-radius:14px;background:rgba(0,0,0,0.32);backdrop-filter:blur(3px);margin-bottom:0.8rem;border:1px solid rgba(255,255,255,0.2);}    
    .brand-logo {width:58px;height:58px;border-radius:12px;object-fit:cover;box-shadow:0 8px 18px rgba(0,0,0,0.35);}   
    .brand-text {font-weight:800;letter-spacing:0.12em;font-size:1.05rem;color:#fff;text-transform:uppercase;text-shadow:0 2px 8px rgba(0,0,0,0.4);}  

    main.container {margin-top:-2.4rem;}

    .action-card {border:1px solid #e7ecf1;border-radius:1.1rem;text-decoration:none;color:inherit;transition:transform 0.2s ease,box-shadow 0.3s ease,border-color 0.3s ease;background:var(--card);box-shadow:var(--shadow);height:100%;display:flex;flex-direction:column;justify-content:center;}
    .action-card:hover {transform:translateY(-6px);border-color:rgba(2,156,226,0.4);box-shadow:0 22px 50px rgba(2,156,226,0.16);}
    .action-card .card-body {text-align:center;padding:2.4rem 1.7rem;}
    .action-card i {font-size:2.6rem;margin-bottom:1rem;color:var(--primary);}
    .action-card h5{font-weight:700;margin-bottom:0.55rem;color:var(--dark);}
    .action-card p{color:var(--muted);margin-bottom:0;}

    .card-blue i {color:var(--primary);} 
    .card-green i {color:#1fab89;}
    .card-orange i {color:#ff9f43;}
    .card-purple i {color:#8a5ee0;}

    .badge-role {display:inline-flex;align-items:center;gap:0.4rem;background:rgba(2,156,226,0.14);color:var(--primary-strong);padding:0.45rem 0.85rem;border-radius:999px;font-weight:600;font-size:0.92rem;border:1px solid rgba(2,156,226,0.28);}

    .link-back {color:var(--muted);font-weight:600;text-decoration:none;}
    .link-back:hover {color:var(--primary);}    

    .modal-content{border-radius:1rem;border:none;box-shadow:0 16px 42px rgba(0,0,0,0.2);}    
    .modal-header{background-color:var(--primary);color:white;border-bottom:none;}   
    .btn-primary{background-color:var(--primary);border:none;}    
    .btn-primary:hover{background-color:var(--primary-strong);}    
    .btn-secondary{background-color:#6c757d;border:none;}

  </style>

</head>

<body>



<header class="login-hero">
  <div class="container text-center content">
    <div class="brand-lockup">
      <img src="img/favicon.png" alt="Metele Brasil" class="brand-logo">
      <span class="brand-text">Metele Brasil</span>
    </div>
    <p class="eyebrow">Accesos rápidos</p>
    <h1>Tu panel Metele Brasil</h1>
    <p class="lede">Gestiona reservas, pagos y operaciones con el mismo estilo del sitio.</p>
    <div class="chips">
      <span class="chip">Reservas</span>
      <span class="chip">Pagos</span>
      <?php if ($puedeVerAdmin): ?><span class="chip">Operaciones</span><?php endif; ?>
    </div>
    <?php if ($puedeVerAdmin): ?>
    <div class="mt-3">
      <span class="badge-role"><i class="fas fa-user-shield"></i> Acceso operativo</span>
    </div>
    <?php endif; ?>
  </div>
</header>



<main class="container py-5">
  <div class="row g-4 justify-content-center">
    <div class="col-12 col-md-6 col-lg-4">
      <a href="categorias" class="card action-card card-blue">
        <div class="card-body">
          <i class="fas fa-compass"></i>
          <h5>Ver actividades</h5>
          <p>Explora excursiones, paseos en barco y experiencias disponibles.</p>
        </div>
      </a>
    </div>

    <div class="col-12 col-md-6 col-lg-4">
      <div class="card action-card card-green" data-bs-toggle="modal" data-bs-target="#modalReserva">
        <div class="card-body">
          <i class="fas fa-credit-card"></i>
          <h5>Pagar reserva</h5>
          <p>Ingresa tu código para finalizar el pago de forma segura.</p>
        </div>
      </div>
    </div>

    <?php if ($puedeVerAdmin): ?>
    <div class="col-12 col-md-6 col-lg-4">
      <a href="admin" class="card action-card card-orange">
        <div class="card-body">
          <i class="fas fa-chart-line"></i>
          <h5>Panel administración</h5>
          <p>Accede a reservas, comisiones y reportes financieros.</p>
        </div>
      </a>
    </div>

    <div class="col-12 col-md-6 col-lg-4">
      <a href="admin/serviciosLista" class="card action-card card-purple">
        <div class="card-body">
          <i class="fas fa-calendar-check"></i>
          <h5>Disponibilidad</h5>
          <p>Edita horarios y cupos de tus salidas activas.</p>
        </div>
      </a>
    </div>
    <?php endif; ?>
  </div>

  <div class="text-center mt-4">
    <a class="link-back" href="index"><i class="fas fa-arrow-left me-2"></i>Volver al sitio</a>
  </div>

</main>

<!-- MODAL RESERVA -->

<div class="modal fade" id="modalReserva" tabindex="-1" aria-labelledby="modalReservaLabel" aria-hidden="true">

  <div class="modal-dialog modal-dialog-centered">

    <div class="modal-content">

      <div class="modal-header">

        <h5 class="modal-title" id="modalReservaLabel">Pagar Reserva</h5>

        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

      </div>

      <div class="modal-body">

        <p>Introduce el código de reserva para continuar.</p>

        <form id="formReserva" onsubmit="return false;">

          <div class="mb-3">

            <label for="inputReserva" class="form-label">Código de Reserva</label>

            <input type="text" class="form-control form-control-lg" id="inputReserva" placeholder="Ej: ESX189" required oninput="this.value=this.value.toUpperCase();">

          </div>

        </form>

      </div>

      <div class="modal-footer">

        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>

        <button type="button" class="btn btn-primary" id="btnReserva">Pagar</button>

      </div>

    </div>

  </div>

</div>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>



<script>

document.addEventListener('DOMContentLoaded', function() {

  const btnReserva = document.getElementById('btnReserva');
  const inputReserva = document.getElementById('inputReserva');

  const goReserva = () => {
    const value = inputReserva.value.trim();
    if (!value || !/^[A-Z0-9]+$/.test(value)) {
      alert('Introduce un código de reserva válido.');
      inputReserva.focus();
      return;
    }
    window.location.href = `consultaReserva?reserva=${value}`;
  };

  btnReserva.addEventListener('click', goReserva);
  inputReserva.addEventListener('keypress', e => { if (e.key === 'Enter') { e.preventDefault(); goReserva(); } });
});

</script>



</body>

</html>

