<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once(__DIR__ . '/includes/sidebar.php');
require_once(__DIR__ . '/includes/footer.php');
?><!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Hoteles - Lista</title>
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1><i class="fas fa-hotel"></i> Hoteles</h1>
            <p class="text-muted mb-0">Listado de hoteles (en desarrollo)</p>
          </div>
        </div>
      </div>
    </section>
    <section class="content">
      <div class="container-fluid">
        <div class="card">
          <div class="card-body">
            <p>Esta sección está en desarrollo. Próximamente: DataTables con filtros, búsqueda y acciones.</p>
          </div>
        </div>
      </div>
    </section>
  </div>
</div>
</body>
</html>