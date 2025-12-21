<?php
// Renderizar una tarjeta de servicio (se usa tanto en categorias.php como en AJAX)
// Este archivo espera que $servicio esté disponible

if (!isset($servicio)) return;

$idServicio = $servicio["idServicio"];

// Obtener salidas
require_once('admin/classes/conexion.php');
$consulta_salidas = "SELECT * FROM servicio_salidas WHERE idServicio=:idServicio ORDER BY fecha DESC LIMIT 1";
$cmd_salidas = $pdo->prepare($consulta_salidas);
$cmd_salidas->bindParam(":idServicio", $idServicio, PDO::PARAM_INT);
$cmd_salidas->execute();
$salidas = $cmd_salidas->fetchAll(PDO::FETCH_ASSOC);

$idMoneda = 1;
$idServicioSalidas = 0;
$precioSugerido = "Consultar";
$cancelacion = "";

// Skip salida logic for now - just show services
if (!empty($salidas)) {

  $idMoneda = $salidas[0]['idMoneda'];

  $idServicioSalidas = $salidas[0]['idServicioSalidas'];

  $tarifas = getTarifas($idServicioSalidas);


  if (!empty($tarifas) && isset($tarifas[0])) {
    $tarifa = calculaTarifa(
      $tarifas[0]['idServicioSalidasTarifas'],

      1
    );

    if (!empty($tarifa) && isset($tarifa[0])) {
      $precioSugerido = ($tarifa[0]["valorSym"]);
    } else {
      $precioSugerido = "N/A";
    }
  }
}

// Obtener fotos
$fotos_servicio = getFotosServicio($idServicio);

// Obtener opiniones
$opiniones_servicio = getServicio_opiniones($idServicio);
?>

<!--TARJETA DE SERVICIO-->
<div class="col-lg-4 col-md-6 col-sm-12 mb-4 servicio-item">
  <div class="card h-100">
    <!-- Fotos -->
    <div id="carousel<?= $idServicio ?>" class="carousel slide" data-ride="carousel">
      <div class="carousel-inner">
        <?php
        $esLatoprimera = true;
        foreach ($fotos_servicio as $foto) {
          $clase = $esLatoprimera ? "active" : "";
          $esLatoprimera = false;
        ?>
          <div class="carousel-item <?= $clase ?>">
            <img src="admin/img/fotos_servicios/<?= $foto['ruta_foto'] ?>" class="d-block w-100" alt="Foto de <?= $servicio['nombre_servicio'] ?>">
          </div>
        <?php } ?>
      </div>
      <a class="carousel-control-prev" href="#carousel<?= $idServicio ?>" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      </a>
      <a class="carousel-control-next" href="#carousel<?= $idServicio ?>" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
      </a>
    </div>

    <!-- Contenido -->
    <div class="card-body">
      <h5 class="card-title"><?= $servicio['nombre_servicio'] ?></h5>
      <p class="card-text"><?= substr($servicio['descripcion_corta'], 0, 100) ?>...</p>

      <!-- Rating -->
      <?php if (!empty($opiniones_servicio)) { ?>
        <div class="rating mb-2">
          <small class="text-muted">
            <?= count($opiniones_servicio) ?> opiniones
          </small>
        </div>
      <?php } ?>

      <!-- Precio -->
      <div class="d-flex justify-content-between align-items-center">
        <span class="text-muted">Desde</span>
        <h4 class="text-primary"><?= $precioSugerido ?></h4>
      </div>
    </div>

    <!-- Footer -->
    <div class="card-footer bg-white border-top">
      <a href="servicio.php?id=<?= $idServicio ?>" class="btn btn-primary btn-block">Ver Detalles</a>
    </div>
  </div>
</div>
<!--FIN TARJETA DE SERVICIO-->
