<?php
/**
 * Renderizador de Items de Transporte en Carrito
 * Incluir DESPUÉS del loop de servicios en carrito.php
 */

// Este código se debe agregar DESPUÉS de la línea 235 en carrito.php
// (después del for loop que renderiza servicios)

// Detectar y renderizar items de transporte
for ($i = 0; $i < $cantCarrito; $i++) {
    // Verificar si es item de transporte (nueva estructura)
    if (isset($carrito[$i]['tipo']) && $carrito[$i]['tipo'] === 'transporte') {
        $item = $carrito[$i];
        ?>
        <div class="py-2">
            <div class="card card-visitas shadow-sm position-relative">
                <form method="post" action="carrito" class="btn-close-container" aria-label="<?= $lang["eliminar"] ?>">
                    <input type="hidden" name="eliminarActividad" value="<?= $i ?>">
                    <button type="submit" class="btn-close-win" title="<?= $lang["eliminar"] ?>">×</button>
                </form>
                
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-2 text-center">
                            <i class="fas fa-bus fa-4x text-primary"></i>
                        </div>
                        
                        <div class="col-md-7">
                            <div class="card-block">
                                <h4 class="text-left titulo-card-destinos text-primary mb-2">
                                    <i class="fas fa-route mr-2"></i><?= htmlspecialchars($item['empresa'] ?? 'Transporte') ?>
                                </h4>
                                <p class="mb-2">
                                    <strong><?= htmlspecialchars($item['origen_nombre']) ?></strong>
                                    <i class="fas fa-arrow-right text-primary mx-2"></i>
                                    <strong><?= htmlspecialchars($item['destino_nombre']) ?></strong>
                                </p>
                                
                                <div class="row no-gutters">
                                    <div class="col-md-4">
                                        <p class="mb-1">
                                            <span class="badge badge-info badge-pill p-2">
                                                <i class="far fa-calendar"></i>
                                                <?= date('d/m/Y', strtotime($item['fecha_viaje'])) ?>
                                            </span>
                                        </p>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="mb-1">
                                            <span class="badge badge-primary badge-pill p-2">
                                                <i class="far fa-clock"></i>
                                                <?= date('H:i', strtotime($item['hora_salida'])) ?>
                                            </span>
                                        </p>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="mb-1">
                                            <span class="badge badge-success badge-pill p-2">
                                                <i class="fas fa-users"></i>
                                                <?= $item['cantidad_pasajeros'] ?> pasajero<?= $item['cantidad_pasajeros'] > 1 ? 's' : '' ?>
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="mt-2">
                                    <p class="mb-0 small text-muted">
                                        <strong>Asientos:</strong>
                                        <?php
                                        if (!empty($item['asientos'])) {
                                            echo implode(', ', $item['asientos']);
                                        } else {
                                            echo 'Por confirmar';
                                        }
                                        ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 text-center">
                            <p class="mb-0 text-muted small">Total</p>
                            <h3 class="text-primary mb-0">
                                <?= $_SESSION['moneda_sel_sym'] ?>
                                <?= number_format($item['precio_total'], 2) ?>
                            </h3>
                            <p class="mb-0 small text-muted">
                                <?= number_format($item['precio_unitario'], 2) ?> × <?= $item['cantidad_pasajeros'] ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        
        // Sumar al total del carrito
        $precioTotalCarrito += $item['precio_total'];
    }
}
?>

<!-- 
INSTRUCCIONES DE INTEGRACIÓN:

1. Abrir carrito.php
2. Localizar la línea ~235 donde termina el for loop de servicios:
   <?php } ?>
   
3. INMEDIATAMENTE DESPUÉS, agregar:
   <?php include('includes/carrito_transporte_render.php'); ?>

4. Este archivo detectará automáticamente items tipo='transporte' y los renderizará
5. Actualizará el $precioTotalCarrito automáticamente

ESTRUCTURA ESPERADA DEL ITEM:
$item = [
    'tipo' => 'transporte',
    'idViaje' => int,
    'fecha_viaje' => 'YYYY-MM-DD',
    'idOrigen' => int,
    'idDestino' => int,
    'origen_nombre' => string,
    'destino_nombre' => string,
    'empresa' => string,
    'ruta' => string,
    'hora_salida' => 'HH:MM:SS',
    'asientos' => array,
    'cantidad_pasajeros' => int,
    'precio_unitario' => float,
    'precio_total' => float,
    'idMoneda' => int,
    'pasajeros' => array
];
-->
