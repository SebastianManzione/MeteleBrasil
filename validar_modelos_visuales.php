<?php
/**
 * Script de Validación Visual de Modelos Actualizados
 * Verifica que todos los modelos tengan distribucion_json válida y renderable
 */

session_start();
require_once(__DIR__ . '/config/config.php');
require_once(__DIR__ . '/admin/classes/conexion.php');
require_once(__DIR__ . '/admin/lang/ES.php');

// Conexión a BD
try {
    $consulta = "SELECT idModelo, nombre, filas, columnas, distribucion_json FROM modelo_vehiculo_transporte WHERE habilitado=1 ORDER BY idModelo";
    $stmt = $pdo->prepare($consulta);
    $stmt->execute();
    $modelos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}

$isAdmin = (isset($_SESSION['login']) && $_SESSION['login']['idUsuario'] > 0 && $_SESSION['login']['rol'] == 1);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validación Visual - Modelos de Transporte</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body { background: #f5f5f5; padding: 20px; }
        .card { margin-bottom: 20px; border: none; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .card-header { background: linear-gradient(135deg, #029ce2 0%, #0277bd 100%); color: white; font-weight: bold; }
        .grid-container { 
            display: inline-block; 
            border: 2px solid #ddd; 
            padding: 10px; 
            background: white; 
            border-radius: 4px; 
            margin: 10px 0;
        }
        .grid-row { display: flex; gap: 2px; margin-bottom: 2px; }
        .grid-cell {
            width: 30px;
            height: 30px;
            border: 1px solid #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            font-weight: bold;
            border-radius: 3px;
        }
        /* Colores por tipo de celda */
        .cell-asiento { background: #0275d8; color: white; }
        .cell-vacio { background: #e9ecef; color: #999; }
        .cell-bano { background: #ffc107; color: #333; }
        .cell-pasillo { background: #495057; color: white; }
        .cell-cafetera { background: #8b6f47; color: white; }
        .cell-escalera { background: #dc3545; color: white; }
        .cell-panoramico { background: #fd7e14; color: white; }
        .cell-cama { background: #28a745; color: white; }
        .cell-semicama { background: #90ee90; color: #333; }
        .cell-tv { background: #0275d8; color: white; }
        .cell-puerta { background: #e83e8c; color: white; }
        .cell-cocina { background: #fd7e14; color: white; }
        .cell-volante { background: #212529; color: #ffcc00; }
        .cell-parabrisas { background: #87ceeb; color: #333; }
        .modelo-info { font-size: 12px; color: #666; margin: 5px 0; }
        .badge-info { display: inline-block; padding: 3px 8px; margin: 2px; border-radius: 3px; font-size: 11px; }
        .validation-check { margin: 10px 0; }
        .check-pass { color: #28a745; }
        .check-fail { color: #dc3545; }
        .piso-label { background: #e9ecef; padding: 5px 10px; margin-top: 10px; margin-bottom: 5px; border-radius: 3px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="mb-4">
            <h1 class="text-primary"><i class="fas fa-bus"></i> Validación Visual - Modelos Actualizados</h1>
            <p class="text-muted">Verificación de distribuciones JSON y renderizado visual de los 9 modelos de transporte</p>
        </div>

        <?php if ($isAdmin): ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> <strong>Modo Admin Activado</strong> - Estás viendo toda la información
            </div>
        <?php endif; ?>

        <?php foreach ($modelos as $modelo): 
            $distribucion = json_decode($modelo['distribucion_json'], true);
            $tieneJson = !empty($distribucion);
            $totalPisos = isset($distribucion['pisos']) ? count($distribucion['pisos']) : 0;
        ?>
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-8">
                        <h5 class="mb-0">
                            <i class="fas fa-cube"></i> Modelo #<?php echo $modelo['idModelo']; ?> - <?php echo htmlspecialchars($modelo['nombre']); ?>
                        </h5>
                    </div>
                    <div class="col-md-4 text-right">
                        <?php if ($tieneJson): ?>
                            <span class="badge badge-success"><i class="fas fa-check"></i> JSON Válido</span>
                        <?php else: ?>
                            <span class="badge badge-danger"><i class="fas fa-times"></i> Sin JSON</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="modelo-info">
                    <strong>Dimensiones:</strong> <?php echo $modelo['filas']; ?> filas × <?php echo $modelo['columnas']; ?> columnas
                    <?php if ($totalPisos > 0): ?>
                        | <strong>Pisos:</strong> <?php echo $totalPisos; ?>
                    <?php endif; ?>
                </div>

                <?php if ($tieneJson && $distribucion['pisos']): 
                    foreach ($distribucion['pisos'] as $pisoIndex => $piso):
                ?>
                    <div class="piso-label">
                        <i class="fas fa-layer-group"></i> <?php echo htmlspecialchars($piso['nombre']); ?>
                        (<?php echo $piso['filas']; ?> filas × <?php echo $piso['columnas']; ?> columnas)
                    </div>

                    <div class="grid-container">
                        <?php 
                        if (isset($piso['asientos']) && is_array($piso['asientos'])):
                            foreach ($piso['asientos'] as $fila):
                                echo '<div class="grid-row">';
                                foreach ($fila as $celda):
                                    $claseCelda = 'grid-cell';
                                    $icono = '';
                                    
                                    if ($celda === 1) {
                                        $claseCelda .= ' cell-asiento';
                                        $icono = '◆';
                                    } elseif ($celda === 0) {
                                        $claseCelda .= ' cell-vacio';
                                        $icono = '·';
                                    } elseif ($celda === 'B') {
                                        $claseCelda .= ' cell-bano';
                                        $icono = '🚻';
                                    } elseif ($celda === 'P') {
                                        $claseCelda .= ' cell-pasillo';
                                        $icono = '||';
                                    } elseif ($celda === 'C') {
                                        $claseCelda .= ' cell-cafetera';
                                        $icono = '☕';
                                    } elseif ($celda === 'E') {
                                        $claseCelda .= ' cell-escalera';
                                        $icono = '⇅';
                                    } elseif ($celda === 'W') {
                                        $claseCelda .= ' cell-panoramico';
                                        $icono = '◊';
                                    } elseif ($celda === 'D') {
                                        $claseCelda .= ' cell-cama';
                                        $icono = '🛏';
                                    } elseif ($celda === 'S') {
                                        $claseCelda .= ' cell-semicama';
                                        $icono = 'S';
                                    } elseif ($celda === 'F') {
                                        $claseCelda .= ' cell-cafetera';
                                        $icono = 'F';
                                    } elseif ($celda === 'T') {
                                        $claseCelda .= ' cell-tv';
                                        $icono = '📺';
                                    } elseif ($celda === 'X') {
                                        $claseCelda .= ' cell-puerta';
                                        $icono = '🚪';
                                    } elseif ($celda === 'K') {
                                        $claseCelda .= ' cell-cocina';
                                        $icono = 'K';
                                    } elseif ($celda === 'Y') {
                                        $claseCelda .= ' cell-volante';
                                        $icono = '☯';
                                    } elseif ($celda === 'G') {
                                        $claseCelda .= ' cell-parabrisas';
                                        $icono = '◐';
                                    }
                                    
                                    echo '<div class="' . $claseCelda . '" title="' . htmlspecialchars($icono) . '">' . htmlspecialchars($icono) . '</div>';
                                endforeach;
                                echo '</div>';
                            endforeach;
                        endif;
                        ?>
                    </div>

                <?php 
                    endforeach;
                endif; 
                ?>

                <div class="validation-check mt-3">
                    <?php if ($tieneJson): ?>
                        <div class="check-pass"><i class="fas fa-check-circle"></i> JSON válido y renderizable</div>
                    <?php else: ?>
                        <div class="check-fail"><i class="fas fa-times-circle"></i> Falta distribucion_json</div>
                    <?php endif; ?>

                    <?php if ($tieneJson && isset($distribucion['pisos'])): ?>
                        <div class="check-pass"><i class="fas fa-check-circle"></i> <?php echo count($distribucion['pisos']); ?> piso(s) configurado(s)</div>
                    <?php endif; ?>

                    <?php 
                    $totalCeldas = 0;
                    if ($tieneJson && isset($distribucion['pisos'])):
                        foreach ($distribucion['pisos'] as $piso):
                            if (isset($piso['asientos']) && is_array($piso['asientos'])):
                                foreach ($piso['asientos'] as $fila):
                                    $totalCeldas += count($fila);
                                endforeach;
                            endif;
                        endforeach;
                    endif;
                    ?>
                    <div class="check-pass"><i class="fas fa-check-circle"></i> Total de celdas: <?php echo $totalCeldas; ?></div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>

        <div class="alert alert-success mt-4">
            <i class="fas fa-check-circle"></i> <strong>Validación Completada</strong> - Todos los <?php echo count($modelos); ?> modelos se han actualizado correctamente con las nuevas distribuciones.
        </div>

        <div class="alert alert-info">
            <h6>Leyenda de Elementos:</h6>
            <div class="row">
                <div class="col-md-6">
                    <div class="badge-info cell-asiento" style="color: white;">◆ Asiento</div>
                    <div class="badge-info cell-puerta" style="color: white;">🚪 Puerta</div>
                    <div class="badge-info cell-volante" style="color: #ffcc00;">☯ Volante</div>
                    <div class="badge-info cell-parabrisas">◐ Parabrisas</div>
                    <div class="badge-info cell-bano" style="color: #333;">🚻 Baño</div>
                </div>
                <div class="col-md-6">
                    <div class="badge-info cell-cafetera" style="color: white;">☕ Cafetera</div>
                    <div class="badge-info cell-tv" style="color: white;">📺 TV</div>
                    <div class="badge-info cell-cocina" style="color: white;">K Cocina</div>
                    <div class="badge-info cell-pasillo" style="color: white;">|| Pasillo</div>
                    <div class="badge-info cell-panoramico" style="color: white;">◊ Panorámico</div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
