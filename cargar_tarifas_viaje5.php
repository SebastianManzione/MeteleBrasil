<?php
/**
 * Script para cargar tarifas de ejemplo en viaje_tarifa
 * Carga datos para id_viaje = 5
 */

require_once("admin/classes/transporte.php");
require_once("admin/classes/conexion.php");

// Obtener viaje 5
$viaje = getViaje(5);
if (empty($viaje)) {
    die('Viaje 5 no existe');
}

$idRuta = $viaje['idRuta'];
$paradas = getParadasRuta($idRuta);

if (count($paradas) < 2) {
    die('La ruta no tiene suficientes paradas');
}

// Obtener tipos de tarifa (Adulto, Niño, Senior, Estudiante)
$consulta = "SELECT idTipoTarifa FROM tipo_tarifa_pasajero LIMIT 4";
$cmd = $pdo->prepare($consulta);
$cmd->execute();
$tiposTarifa = $cmd->fetchAll(PDO::FETCH_ASSOC);

if (count($tiposTarifa) < 4) {
    die('No hay 4 tipos de tarifa configurados');
}

// Moneda ARS (id=1)
$idMoneda = 1;

// Crear tarifas para todos los pares origen-destino
$contador = 0;
foreach ($paradas as $origen) {
    if (!$origen['es_origen']) continue;
    
    foreach ($paradas as $destino) {
        if (!$destino['es_destino']) continue;
        if ($origen['idRutaParada'] === $destino['idRutaParada']) continue;
        
        // Para cada tipo de tarifa
        foreach ($tiposTarifa as $tipo) {
            $precioBase = 15000 + (rand(0, 5) * 1000); // Precio aleatorio
            
            // Verificar si ya existe
            $check = "SELECT COUNT(*) as cnt FROM viaje_tarifa 
                      WHERE idViaje = 5 
                      AND idOrigenParada = ? 
                      AND idDestinoParada = ? 
                      AND idTipoTarifa = ?";
            $cmd = $pdo->prepare($check);
            $cmd->execute([5, $origen['idRutaParada'], $destino['idRutaParada'], $tipo['idTipoTarifa']]);
            $existe = $cmd->fetch(PDO::FETCH_ASSOC);
            
            if ($existe['cnt'] == 0) {
                // Insertar
                $insert = "INSERT INTO viaje_tarifa 
                          (idViaje, idOrigenParada, idDestinoParada, idTipoTarifa, precio, idMoneda, comisiona, habilitado)
                          VALUES (?, ?, ?, ?, ?, ?, 1, 1)";
                $cmd = $pdo->prepare($insert);
                $cmd->execute([5, $origen['idRutaParada'], $destino['idRutaParada'], $tipo['idTipoTarifa'], $precioBase, $idMoneda]);
                $contador++;
            }
        }
    }
}

echo "✅ Se insertaron $contador tarifas para el viaje 5\n";
echo "Ahora debería funcionar el sistema de reserva\n";
?>
