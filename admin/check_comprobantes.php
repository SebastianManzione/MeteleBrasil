<?php
session_start();
require_once(__DIR__ . "/classes/conexion.php");

$codigo = 'VZN963';

echo "<h1>Verificación de Comprobantes - Reserva $codigo</h1>";
echo "<style>body{font-family:monospace;} table{border-collapse:collapse;width:100%;} td,th{border:1px solid #ddd;padding:8px;text-align:left;} th{background:#029ce2;color:white;}</style>";

// Buscar reserva
$consulta = "SELECT * FROM reservas WHERE codigoAmigable = :codigo";
$cmd = $pdo->prepare($consulta);
$cmd->execute(['codigo' => $codigo]);
$reserva = $cmd->fetch(PDO::FETCH_ASSOC);

if (!$reserva) {
    die("<p style='color:red;'>Reserva no encontrada</p>");
}

$idReserva = $reserva['idReserva'];

echo "<h2>Datos de la Reserva</h2>";
echo "<ul>";
echo "<li><strong>ID Reserva:</strong> $idReserva</li>";
echo "<li><strong>Código:</strong> {$reserva['codigoAmigable']}</li>";
echo "<li><strong>Total a pagar:</strong> {$reserva['total']} (Moneda ID: {$reserva['monedaSel']})</li>";
echo "<li><strong>Total USD:</strong> \${$reserva['total_dolares']}</li>";
echo "<li><strong>Estado:</strong> {$reserva['idEstado']} (3 = Confirmada)</li>";
echo "</ul>";

// Buscar comprobantes asociados
echo "<h2>Comprobantes Registrados</h2>";
$consultaComp = "SELECT * FROM comprobante WHERE idReserva = :idReserva ORDER BY fechaAlta DESC";
$cmdComp = $pdo->prepare($consultaComp);
$cmdComp->execute(['idReserva' => $idReserva]);
$comprobantes = $cmdComp->fetchAll(PDO::FETCH_ASSOC);

if (empty($comprobantes)) {
    echo "<p style='color:red;font-weight:bold;'>❌ NO HAY COMPROBANTES REGISTRADOS para esta reserva</p>";
} else {
    echo "<table>";
    echo "<tr><th>ID</th><th>Fecha</th><th>Total</th><th>Total USD</th><th>Moneda</th><th>Origen</th><th>Usuario</th></tr>";
    
    $totalComprobantes = 0;
    foreach ($comprobantes as $comp) {
        echo "<tr>";
        echo "<td>{$comp['idComprobante']}</td>";
        echo "<td>" . (isset($comp['fechaIngreso']) ? $comp['fechaIngreso'] : 'N/A') . "</td>";
        echo "<td>" . (isset($comp['total']) ? $comp['total'] : 0) . "</td>";
        echo "<td>\$" . (isset($comp['total_dolares']) ? $comp['total_dolares'] : 0) . "</td>";
        echo "<td>" . (isset($comp['monedaComprobante']) ? $comp['monedaComprobante'] : 'N/A') . "</td>";
        echo "<td>" . (isset($comp['origenComprobante']) ? $comp['origenComprobante'] : 'N/A') . "</td>";
        echo "<td>" . (isset($comp['idUsuario']) ? $comp['idUsuario'] : 'N/A') . "</td>";
        echo "</tr>";
        if (isset($comp['total'])) {
            $totalComprobantes += $comp['total'];
        }
    }
    
    echo "</table>";
    echo "<p><strong>Total en comprobantes:</strong> $totalComprobantes</p>";
    
    $diferencia = $reserva['total'] - $totalComprobantes;
    echo "<p><strong>Diferencia:</strong> <span style='color:" . ($diferencia > 1 ? 'red' : 'green') . ";font-weight:bold;'>$diferencia</span></p>";
    
    if ($diferencia > 10) {
        echo "<p style='color:orange;'><strong>⚠️ ADVERTENCIA:</strong> Diferencia mayor a 10. Puede ser error de conversión de moneda o falta registrar pago adicional.</p>";
    }
}

// Verificar si hay pagos en otras tablas
echo "<h2>Verificación en otras tablas</h2>";

// Tabla usuario_comprobantes
$consultaUC = "SELECT * FROM usuario_comprobantes WHERE idReserva = :idReserva";
$cmdUC = $pdo->prepare($consultaUC);
$cmdUC->execute(['idReserva' => $idReserva]);
$usuarioComp = $cmdUC->fetchAll(PDO::FETCH_ASSOC);

if (!empty($usuarioComp)) {
    echo "<h3>usuario_comprobantes:</h3>";
    echo "<table>";
    echo "<tr><th>ID</th><th>Fecha</th><th>Monto</th><th>Estado</th></tr>";
    foreach ($usuarioComp as $uc) {
        echo "<tr>";
        echo "<td>{$uc['idUsuarioComprobante']}</td>";
        echo "<td>" . (isset($uc['fechaPago']) ? $uc['fechaPago'] : 'N/A') . "</td>";
        echo "<td>" . (isset($uc['monto']) ? $uc['monto'] : 'N/A') . "</td>";
        echo "<td>" . (isset($uc['estado']) ? $uc['estado'] : 'N/A') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

echo "<hr>";
echo "<h2>Análisis</h2>";

if (!empty($comprobantes)) {
    $diferencia = $reserva['total'] - $totalComprobantes;
    
    if ($diferencia > 1) {
        echo "<p style='color:red;'><strong>❌ PROBLEMA:</strong> Falta registrar <strong>$diferencia</strong> en comprobantes.</p>";
        
        if ($diferencia > 250) {
            echo "<p>Probablemente falta el comprobante principal. Verifica en MercadoPago/PayPal el pago real recibido.</p>";
            echo "<p>Si recibiste <strong>263.50</strong>, ejecuta:</p>";
            echo "<pre style='background:#f5f5f5;padding:15px;border-left:4px solid #029ce2;'>";
            echo "UPDATE comprobante SET total = 263.50, total_dolares = 0.17 WHERE idComprobante = {$comprobantes[0]['idComprobante']};\n";
            echo "</pre>";
        } else {
            echo "<p>Diferencia pequeña, probablemente error de conversión de moneda o redondeo.</p>";
        }
    } else {
        echo "<p style='color:green;'><strong>✅ OK:</strong> Los comprobantes coinciden con el total de la reserva.</p>";
    }
} else {
    echo "<p style='color:red;'><strong>❌ NO HAY COMPROBANTES:</strong> Debes insertar el comprobante del pago.</p>";
    echo "<pre style='background:#f5f5f5;padding:15px;border-left:4px solid #029ce2;'>";
    echo "INSERT INTO comprobante (idReserva, total, total_dolares, origenComprobante, monedaComprobante, fechaIngreso, idUsuario)\n";
    echo "VALUES ($idReserva, 263.50, 0.17, 1, 270, NOW(), 1);\n";
    echo "</pre>";
}
?>
