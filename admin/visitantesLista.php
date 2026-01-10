<?php 
// Verificar permisos de acceso ANTES de cualquier salida
require_once(__DIR__ . "/classes/permisos.php");
require_once(__DIR__ . "/includes/permisos_helper.php");
$permisos = new PermisosManager($GLOBALS['pdo'], $_SESSION['login'] ?? []);
$permisos->verificarAcceso('index'); // Solo admin puede ver

include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");

require("classes/visitas.php");

// Solo admin puede acceder
if (!isset($_SESSION["login"]["idUsuario"]) || $_SESSION["login"]["idUsuario"] != 1) {
  alertar("Usted no tiene acceso a esta sección del software", "error");
  redireccionarLento("index");
}

// Variables de paginación
$por_pagina = 50;
$pagina = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
$desde = ($pagina - 1) * $por_pagina;

// Filtros
$filtro_fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : '';
$filtro_fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : '';
$filtro_ip = isset($_GET['ip']) ? trim($_GET['ip']) : '';
$filtro_pagina = isset($_GET['pagina_filtro']) ? trim($_GET['pagina_filtro']) : '';

// Obtener visitantes con filtros
$todos_visitantes = getVisitas();
$visitantes_filtrados = $todos_visitantes;

// Aplicar filtros
if (!empty($filtro_fecha_inicio) || !empty($filtro_fecha_fin)) {
  $inicio = !empty($filtro_fecha_inicio) ? strtotime($filtro_fecha_inicio) : strtotime("2000-01-01");
  $fin = !empty($filtro_fecha_fin) ? strtotime($filtro_fecha_fin . " 23:59:59") : strtotime("2099-12-31");
  
  $visitantes_filtrados = array_filter($visitantes_filtrados, function($v) use ($inicio, $fin) {
    $fecha = strtotime($v['diahora']);
    return $fecha >= $inicio && $fecha <= $fin;
  });
}

if (!empty($filtro_ip)) {
  $visitantes_filtrados = array_filter($visitantes_filtrados, function($v) use ($filtro_ip) {
    return stripos($v['ip'], $filtro_ip) !== false;
  });
}

if (!empty($filtro_pagina)) {
  $visitantes_filtrados = array_filter($visitantes_filtrados, function($v) use ($filtro_pagina) {
    return stripos($v['pagina'], $filtro_pagina) !== false;
  });
}

// Re-indexar array después de filtros
$visitantes_filtrados = array_values($visitantes_filtrados);
$total_registros = count($visitantes_filtrados);
$total_paginas = ceil($total_registros / $por_pagina);

// Obtener visitantes para la página actual
$visitantes = array_slice($visitantes_filtrados, $desde, $por_pagina);

// Estadísticas
$estadisticas = getEstadisticasVisitantes();
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Visitantes</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item active">Visitantes</li>
          </ol>
        </div>
      </div>
    </div>
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      
      <!-- Estadísticas -->
      <div class="row mb-3">
        <div class="col-lg-3 col-6">
          <div class="info-box">
            <span class="info-box-icon bg-info"><i class="fas fa-eye"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Visitantes Hoy</span>
              <span class="info-box-number"><?= $estadisticas['hoy'] ?></span>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="info-box">
            <span class="info-box-icon bg-success"><i class="fas fa-arrow-up"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Últimos 7 días</span>
              <span class="info-box-number"><?= $estadisticas['ultimos7'] ?></span>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="info-box">
            <span class="info-box-icon bg-warning"><i class="fas fa-chart-bar"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Últimos 30 días</span>
              <span class="info-box-number"><?= $estadisticas['ultimos30'] ?></span>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="info-box">
            <span class="info-box-icon bg-primary"><i class="fas fa-users"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Total Visitantes</span>
              <span class="info-box-number"><?= $estadisticas['total'] ?></span>
            </div>
          </div>
        </div>
      </div>

      <!-- Filtros -->
      <div class="card card-primary collapsed-card">
        <div class="card-header" data-card-widget="collapse">
          <h3 class="card-title"><i class="fas fa-filter mr-2"></i>Filtros</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool"><i class="fas fa-plus"></i></button>
          </div>
        </div>
        <div class="card-body" style="display: none;">
          <form method="GET" class="form-inline">
            <div class="form-group mr-2 mb-2">
              <label for="fecha_inicio" class="mr-2">Desde:</label>
              <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="<?= $filtro_fecha_inicio ?>">
            </div>
            <div class="form-group mr-2 mb-2">
              <label for="fecha_fin" class="mr-2">Hasta:</label>
              <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" value="<?= $filtro_fecha_fin ?>">
            </div>
            <div class="form-group mr-2 mb-2">
              <label for="ip" class="mr-2">IP:</label>
              <input type="text" class="form-control" id="ip" name="ip" placeholder="Buscar IP" value="<?= htmlspecialchars($filtro_ip) ?>">
            </div>
            <div class="form-group mr-2 mb-2">
              <label for="pagina_filtro" class="mr-2">Página:</label>
              <input type="text" class="form-control" id="pagina_filtro" name="pagina_filtro" placeholder="Buscar página" value="<?= htmlspecialchars($filtro_pagina) ?>">
            </div>
            <button type="submit" class="btn btn-primary mb-2">Filtrar</button>
            <a href="visitantesLista.php" class="btn btn-secondary mb-2 ml-2">Limpiar</a>
          </form>
        </div>
      </div>

      <!-- Tabla de Visitantes -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Listado de Visitantes</h3>
          <div class="card-tools">
            <span class="badge badge-info">Total: <?= $total_registros ?></span>
          </div>
        </div>
        <div class="card-body table-responsive p-0">
          <table class="table table-striped table-hover table-bordered">
            <thead class="thead-light">
              <tr>
                <th style="width: 5%;">ID</th>
                <th style="width: 35%;">Página</th>
                <th style="width: 20%;">Lugar / Referencia</th>
                <th style="width: 20%;">IP</th>
                <th style="width: 20%;">Fecha / Hora</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($visitantes)) { ?>
                <tr>
                  <td colspan="5" class="text-center p-4 text-muted">
                    <i class="fas fa-inbox"></i> No hay visitantes registrados
                  </td>
                </tr>
              <?php } else {
                foreach ($visitantes as $v) {
              ?>
                <tr>
                  <td><?= $v['idVisitante'] ?></td>
                  <td>
                    <small><?= htmlspecialchars($v['pagina'] ?? '-') ?></small>
                  </td>
                  <td>
                    <small><?= htmlspecialchars($v['lugar'] ?? '-') ?></small>
                  </td>
                  <td>
                    <code><?= htmlspecialchars($v['ip'] ?? '-') ?></code>
                  </td>
                  <td>
                    <small><?= date("d/m/Y H:i:s", strtotime($v['diahora'])) ?></small>
                  </td>
                </tr>
              <?php 
                }
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Paginación -->
      <?php if ($total_paginas > 1): ?>
      <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
          <li class="page-item <?= $pagina <= 1 ? 'disabled' : '' ?>">
            <a class="page-link" href="?pagina=1<?php 
              echo ($filtro_fecha_inicio ? '&fecha_inicio=' . $filtro_fecha_inicio : '');
              echo ($filtro_fecha_fin ? '&fecha_fin=' . $filtro_fecha_fin : '');
              echo ($filtro_ip ? '&ip=' . urlencode($filtro_ip) : '');
              echo ($filtro_pagina ? '&pagina_filtro=' . urlencode($filtro_pagina) : '');
            ?>">Primera</a>
          </li>
          
          <?php for ($i = max(1, $pagina - 2); $i <= min($total_paginas, $pagina + 2); $i++): ?>
            <li class="page-item <?= $i == $pagina ? 'active' : '' ?>">
              <a class="page-link" href="?pagina=<?= $i ?><?php 
                echo ($filtro_fecha_inicio ? '&fecha_inicio=' . $filtro_fecha_inicio : '');
                echo ($filtro_fecha_fin ? '&fecha_fin=' . $filtro_fecha_fin : '');
                echo ($filtro_ip ? '&ip=' . urlencode($filtro_ip) : '');
                echo ($filtro_pagina ? '&pagina_filtro=' . urlencode($filtro_pagina) : '');
              ?>"><?= $i ?></a>
            </li>
          <?php endfor; ?>
          
          <li class="page-item <?= $pagina >= $total_paginas ? 'disabled' : '' ?>">
            <a class="page-link" href="?pagina=<?= $total_paginas ?><?php 
              echo ($filtro_fecha_inicio ? '&fecha_inicio=' . $filtro_fecha_inicio : '');
              echo ($filtro_fecha_fin ? '&fecha_fin=' . $filtro_fecha_fin : '');
              echo ($filtro_ip ? '&ip=' . urlencode($filtro_ip) : '');
              echo ($filtro_pagina ? '&pagina_filtro=' . urlencode($filtro_pagina) : '');
            ?>">Última</a>
          </li>
        </ul>
      </nav>
      <?php endif; ?>

    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php
include("includes/footer.php");
?>
