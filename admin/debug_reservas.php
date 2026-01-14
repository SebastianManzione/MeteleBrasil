<?php
session_start();
require_once(__DIR__ . "/classes/conexion.php");
require_once(__DIR__ . "/classes/reserva.php");
require_once(__DIR__ . "/classes/salidas.php");
require_once(__DIR__ . "/classes/comprobantes.php");
require_once(__DIR__ . "/classes/convierte_monedas.php");

// Códigos de reserva a analizar
$codigos = ['VZN963', 'SVC789'];

echo "<h1>Debug de Reservas</h1>";
echo "<style>body{font-family:monospace;} .error{color:red;font-weight:bold;} .ok{color:green;font-weight:bold;} .warning{color:orange;font-weight:bold;}</style>";

foreach ($codigos as $codigo) {
    echo "<hr><h2>Analizando reserva: $codigo</h2>";
    
    // 1. Buscar en BD
    $consulta = "SELECT * FROM reservas WHERE codigoAmigable = :codigo";
    $cmd = $pdo->prepare($consulta);
    $cmd->execute(['codigo' => $codigo]);
    $reserva = $cmd->fetch(PDO::FETCH_ASSOC);
    
    if (!$reserva) {
        echo "<p class='error'>❌ NO EXISTE en la base de datos</p>";
        continue;
    }
    
    echo "<p class='ok'>✅ Existe en BD</p>";
    echo "<ul>";
    echo "<li><strong>idReserva:</strong> " . $reserva['idReserva'] . "</li>";
    echo "<li><strong>Estado:</strong> " . $reserva['idEstado'] . " ";
    
    // Verificar estado
    if ($reserva['idEstado'] == 3) {
        echo "<span class='ok'>(Confirmada ✓)</span>";
    } else {
        echo "<span class='error'>(NO confirmada - Estado {$reserva['idEstado']})</span>";
    }
    echo "</li>";
    
    echo "<li><strong>Nombre:</strong> " . $reserva['nombreResponsable'] . " " . $reserva['apellidoResponsable'] . "</li>";
    echo "<li><strong>Fecha Alta:</strong> " . $reserva['fechaAlta'] . "</li>";
    echo "<li><strong>Total:</strong> " . $reserva['total'] . " (Moneda: " . $reserva['monedaSel'] . ")</li>";
    echo "<li><strong>Total USD:</strong> $" . $reserva['total_dolares'] . "</li>";
    
    // 2. Verificar pagos/comprobantes
    $idReserva = $reserva['idReserva'];
    $totalComprobantes = getComprobantesIdReserva($idReserva);
    $diferencia = $reserva['total'] - $totalComprobantes;
    
    echo "<li><strong>Comprobantes pagados:</strong> " . $totalComprobantes . "</li>";
    echo "<li><strong>Diferencia:</strong> " . $diferencia . " ";
    
    if ($diferencia > 1.00) {
        echo "<span class='error'>❌ BLOQUEADO: Diferencia > 1.00 (función getReservasConfirmadas línea 194)</span>";
    } else {
        echo "<span class='ok'>✓ OK</span>";
    }
    echo "</li>";
    
    // 3. Verificar horarios y salidas
    echo "<li><strong>Horarios/Salidas:</strong><ul>";
    $horarios = getReservaHorarios($idReserva);
    
    if (empty($horarios)) {
        echo "<li class='error'>❌ NO tiene horarios asociados</li>";
    } else {
        foreach ($horarios as $h) {
            $salida = getSalida($h['idServicioSalidas']);
            
            if (empty($salida) || !isset($salida[0])) {
                echo "<li class='error'>❌ Salida ID {$h['idServicioSalidas']} no existe</li>";
            } else {
                $fechaEvento = $salida[0]['fecha'];
                $prestador = $salida[0]['idPrestador'];
                $fechaTimestamp = strtotime($fechaEvento);
                $hoy = strtotime(date('Y-m-d'));
                
                echo "<li>";
                echo "idServicioSalidas: {$h['idServicioSalidas']}<br>";
                echo "Fecha evento: $fechaEvento ";
                
                if ($fechaTimestamp >= $hoy) {
                    echo "<span class='ok'>(FUTURO ✓)</span>";
                } else {
                    echo "<span class='warning'>(PASADO)</span>";
                }
                
                echo "<br>Prestador: $prestador<br>";
                echo "</li>";
            }
        }
    }
    echo "</ul></li>";
    
    // 4. Verificar si aparecería con getReservasConfirmadas
    echo "<li><strong>Test función getReservasConfirmadas():</strong><ul>";
    
    if ($reserva['idEstado'] != 3) {
        echo "<li class='error'>❌ FILTRADO: idEstado != 3</li>";
    } else {
        echo "<li class='ok'>✓ Pasa filtro estado</li>";
    }
    
    if ($diferencia > 1.00) {
        echo "<li class='error'>❌ FILTRADO: Diferencia comprobantes > 1.00</li>";
    } else {
        echo "<li class='ok'>✓ Pasa filtro pagos</li>";
    }
    
    // Verificar prestador (simulando vista admin)
    $horarios = getReservaHorarios($idReserva);
    if (empty($horarios)) {
        echo "<li class='error'>❌ FILTRADO: No tiene horarios</li>";
    } else {
        echo "<li class='ok'>✓ Tiene horarios</li>";
        
        // Check si algún horario tiene prestador válido
        $tienePrestadorValido = false;
        foreach ($horarios as $h) {
            $salida = getSalida($h['idServicioSalidas']);
            if (!empty($salida) && isset($salida[0]['idPrestador']) && $salida[0]['idPrestador'] > 0) {
                $tienePrestadorValido = true;
                break;
            }
        }
        
        if ($tienePrestadorValido) {
            echo "<li class='ok'>✓ Tiene prestador válido</li>";
        } else {
            echo "<li class='error'>❌ PROBLEMA: Salidas sin prestador válido</li>";
        }
    }
    
    echo "</ul></li>";
    echo "</ul>";
    
    // 5. Conclusión
    echo "<h3>CONCLUSIÓN:</h3>";
    if ($reserva['idEstado'] != 3) {
        echo "<p class='error'>⛔ NO aparecerá: Estado no es Confirmada (3)</p>";
    } elseif ($diferencia > 1.00) {
        echo "<p class='error'>⛔ NO aparecerá: Falta pagar {$diferencia} (función getReservasConfirmadas la excluye)</p>";
    } elseif (empty($horarios)) {
        echo "<p class='error'>⛔ NO aparecerá: No tiene horarios asociados</p>";
    } else {
        echo "<p class='ok'>✅ DEBERÍA aparecer en la lista</p>";
    }
}

echo "<hr><h2>Información adicional</h2>";
echo "<p><strong>Usuario actual:</strong> " . ($_SESSION['login']['idUsuario'] ?? 'No logueado') . "</p>";
echo "<p><strong>Prestador actual:</strong> " . ($_SESSION['login']['idPrestador'] ?? 'Ninguno') . "</p>";
echo "<p><strong>Vista Admin:</strong> " . (($_SESSION['login']['idUsuario'] ?? 0) == 1 ? 'Sí' : 'No') . "</p>";
?>
