<!DOCTYPE html>

<html lang="es">

<head>

  <meta charset="UTF-8">

  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Panel de Inicio - Metele Brasil</title>



  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">



  <style>

    body {font-family:'Roboto',sans-serif;background:linear-gradient(to bottom right,#f7f9fc,#e9ecef);color:#333;min-height:100vh;}

    .header {background:linear-gradient(135deg,#0056b3,#0d6efd);color:white;text-align:center;padding:3rem 1rem 2.5rem;border-bottom:5px solid #ffc107;box-shadow:0 4px 15px rgba(0,0,0,0.2);}

    .header h1 {font-weight:700;letter-spacing:1px;}

    .header p {font-size:1.1rem;opacity:0.9;}

    .action-card {border:none;border-radius:1rem;text-decoration:none;color:inherit;transition:transform 0.2s ease,box-shadow 0.3s ease;height:100%;display:flex;flex-direction:column;justify-content:center;}

    .action-card:hover {transform:translateY(-6px);box-shadow:0 12px 30px rgba(0,0,0,0.15);}

    .action-card .card-body {text-align:center;padding:2.5rem 1.5rem;}

    .action-card i {font-size:3rem;margin-bottom:1rem;}

    .card-blue{background:#0d6efd;color:white;}

    .card-green{background:#198754;color:white;}

    .card-orange{background:#fd7e14;color:white;}

    .card-purple{background:#6f42c1;color:white;}

    .action-card h5{font-weight:700;margin-bottom:0.5rem;}

    .login-section{background:#212529;border-radius:1rem;text-align:center;padding:2rem 1.5rem;color:white;margin-top:4rem;}

    .login-section .btn{background-color:#ffc107;border:none;color:#000;font-weight:600;}

    .login-section .btn:hover{background-color:#ffcd39;}

    .modal-content{border-radius:1rem;border:none;}

    .modal-header{background-color:#0d6efd;color:white;border-bottom:none;}

    .btn-primary{background-color:#0d6efd;border:none;}

    .btn-primary:hover{background-color:#0b5ed7;}

    .btn-secondary{background-color:#6c757d;border:none;}

  </style>

</head>

<body>



<header class="header">

  <h1>Panel de Inicio</h1>

  <p>Gestión rápida de reservas y administración.</p>

</header>



<main class="container py-5">

  <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2 g-4 justify-content-center">



    <div class="col">

      <div class="card action-card card-blue" data-bs-toggle="modal" data-bs-target="#modalServicio">

        <div class="card-body">

          <i class="fas fa-bolt"></i>

          <h5>Reserva Rápida</h5>

          <p>Inicia una reserva con el ID del servicio.</p>

        </div>

      </div>

    </div>



    <div class="col">

      <a href="app.categorias.php" class="card action-card card-green">

        <div class="card-body">

          <i class="fas fa-search"></i>

          <h5>Ver Actividades</h5>

          <p>Consulta disponibilidad y realiza reservas fácilmente.</p>

        </div>

      </a>

    </div>



    <div class="col">

      <a href="admin/serviciosLista" class="card action-card card-purple">

        <div class="card-body">

          <i class="fas fa-chart-line"></i>

          <h5>Modificar Disponibilidad</h5>

          <p>Revisa disponibilidad, ventas y comisiones.</p>

        </div>

      </a>

    </div>



    <div class="col">

      <div class="card action-card card-orange" data-bs-toggle="modal" data-bs-target="#modalReserva">

        <div class="card-body">

          <i class="fas fa-credit-card"></i>

          <h5>Pagar Reserva</h5>

          <p>Paga tu reserva con el código recibido por email.</p>

        </div>

      </div>

    </div>



  </div>



  <div class="login-section mt-5">

    <p class="mb-3">Para ver comisiones y disponibilidad completa, inicia sesión.</p>

    <a href="admin" class="btn btn-lg px-4">

      <i class="fas fa-sign-in-alt me-2"></i> Acceso Admin

    </a>

    <div class="mt-3">

      <a href="index.php" style="color:#fff;text-decoration:underline;">Volver al sitio</a>

    </div>

  </div>

</main>



<!-- MODAL SERVICIO -->

<div class="modal fade" id="modalServicio" tabindex="-1" aria-labelledby="modalServicioLabel" aria-hidden="true">

  <div class="modal-dialog modal-dialog-centered">

    <div class="modal-content">

      <div class="modal-header">

        <h5 class="modal-title" id="modalServicioLabel">Iniciar Reserva Rápida</h5>

        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

      </div>

      <div class="modal-body">

        <p>Introduce el número de servicio para comenzar.</p>

        <form id="formServicio" onsubmit="return false;">

          <div class="mb-3">

            <label for="inputServicio" class="form-label">Código de Servicio</label>

            <input type="text" class="form-control form-control-lg" id="inputServicio" placeholder="Ej: 12345" required oninput="this.value=this.value.toUpperCase();">

          </div>

        </form>

      </div>

      <div class="modal-footer">

        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>

        <button type="button" class="btn btn-primary" id="btnServicio">Iniciar</button>

      </div>

    </div>

  </div>

</div>



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

  const btnServicio = document.getElementById('btnServicio');

  const inputServicio = document.getElementById('inputServicio');

  const btnReserva = document.getElementById('btnReserva');

  const inputReserva = document.getElementById('inputReserva');



  const goServicio = () => {

    const value = inputServicio.value.trim();

    if (!value || !/^[A-Z0-9]+$/.test(value)) {

      alert('Introduce un código de servicio válido.');

      inputServicio.focus();

      return;

    }

    window.location.href = `servicio.php?id=${value}`;

  };



  const goReserva = () => {

    const value = inputReserva.value.trim();

    if (!value || !/^[A-Z0-9]+$/.test(value)) {

      alert('Introduce un código de reserva válido.');

      inputReserva.focus();

      return;

    }

    window.location.href = `consultaReserva.php?reserva=${value}`;

  };



  btnServicio.addEventListener('click', goServicio);

  btnReserva.addEventListener('click', goReserva);



  inputServicio.addEventListener('keypress', e => { if (e.key === 'Enter') { e.preventDefault(); goServicio(); } });

  inputReserva.addEventListener('keypress', e => { if (e.key === 'Enter') { e.preventDefault(); goReserva(); } });

});

</script>



</body>

</html>

