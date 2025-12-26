<?php
/**
 * Script para restaurar BGM382 en producción
 * Ejecutar en el servidor: php restore_bgm382_prod.php
 */

putenv('APP_ENV=prod');

echo "=== Restaurar BGM382 en Producción ===\n\n";

try {
    $pdo = new PDO(
        'mysql:host=localhost;dbname=u925692129_metelebrasil;charset=utf8mb4',
        'u925692129_metelebrasil',
        'Cambiar2026'
    );
    $pdo->exec("SET NAMES utf8mb4");
    echo "[OK] Conectado a BD producción\n\n";
} catch (Exception $e) {
    die("ERROR: No se puede conectar: ".$e->getMessage()."\n");
}

// Validaciones
echo "[VALIDANDO]\n";

$check = $pdo->prepare("SELECT idPrestador FROM prestadores WHERE idPrestador=14");
$check->execute();
if ($check->rowCount() == 0) {
    die("ERROR: Prestador 14 (Lagomar) no existe en producción\n");
}
echo "✓ Prestador 14 existe\n";

$exist = $pdo->prepare("SELECT idReserva FROM reservas WHERE codigoAmigable=?");
$exist->execute(['BGM382']);
if ($exist->rowCount() > 0) {
    die("ERROR: BGM382 ya existe en producción. Abortando.\n");
}
echo "✓ BGM382 no existe (seguro insertar)\n\n";

// Transacción
echo "[INSERTANDO]\n";
try {
    $pdo->beginTransaction();
    
    // 1. Reserva
    $r1 = $pdo->prepare("INSERT INTO reservas (idUsuario,codigoAmigable,fechaAlta,nombreResponsable,apellidoResponsable,emailResponsable,idCountry,telefonoResponsable,monedaSel,impuestosPais,idCuponDescuento,idUsuarioCupon,total,total_dolares,impuestos,idEstado,nota_interna,idioma) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
    $r1->execute([1,'BGM382','2025-12-23 20:19:35','Daniela','Freitas Batista','djdanyfreitas@hotmail.com',30,'43996491047',283,0,0,0,598,99.67,0,3,NULL,'PT']);
    $idReserva = $pdo->lastInsertId();
    echo "✓ Reserva insertada (id=$idReserva)\n";
    
    // 2. Horario
    $r2 = $pdo->prepare("INSERT INTO reserva_horarios (idReserva,idServicioSalidas,idServicioSeleccionado,CodigoVoucherServicio,cantidadPasajeros,nombre,fecha,horaSalida,horaCheckIn,direccion,latitud,longitud,comentario) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");
    $r2->execute([$idReserva,64271,589,'BGM382-000589',0,'Meia Alta Temporada 11/12 - 25/12/2025','2025-12-23','10:00:00','09:55:00','R. Amaro Coelho, 67 - Barra da Lagoa, Florianópolis - SC, 88061-090, Brasil','-27.575059','-48.423394','']);
    $idReservaHorarios = $pdo->lastInsertId();
    echo "✓ Horario insertado (id=$idReservaHorarios)\n";
    
    // 3. Tarifa
    $r3 = $pdo->prepare("INSERT INTO reserva_tarifas (idReservaHorarios,idServicioSalidasTarifas,nombre,cantidad,monedaSel,valor,valorSinIva,valorDeIva,idFromEdad,idToEdad,comisionVendedor,comisionSistema,nombrePasajero) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");
    $r3->execute([$idReservaHorarios,183113,'INTEGRAL',2,283,598,598,0,17,13,59.8,59.8,'']);
    $idReservaTarifas = $pdo->lastInsertId();
    echo "✓ Tarifa insertada (id=$idReservaTarifas)\n";
    
    // 4. Pasajeros
    $r4 = $pdo->prepare("INSERT INTO reserva_pasajeros (idReservaTarifas,nombrePasajero,apellidoPasajero) VALUES (?,?,?)");
    $r4->execute([$idReservaTarifas,'Daniela','Freitas Batista']);
    $r4->execute([$idReservaTarifas,'Gerlandio','']);
    echo "✓ Pasajeros insertados (2)\n";
    
    // 5. Adicionales
    $adicionales = [
        [18,'Staff receptivo'],
        [151,'Marineiros especializados'],
        [152,'Agua mineral libre'],
        [153,'Embarcación autorizada'],
        [154,'Permiso municipal y ambiental'],
        [158,'Toaletes masculino e feminino'],
        [160,'Bar a bordo'],
        [217,'Tasa de preservación'],
        [250,'Tasa de desembarque en la isla'],
        [179,'Chalecos salvavidas'],
        [288,'Banheiro à bordo']
    ];
    
    $r5 = $pdo->prepare("INSERT INTO reserva_adicionales (idReservaHorarios,idServiciosAdicionales,nombre,descripcion,cantidad,precio,precioUnitarioSIva,valorIva,precioIva) VALUES (?,?,?,?,?,?,?,?,?)");
    foreach ($adicionales as $adc) {
        $r5->execute([$idReservaHorarios,$adc[0],$adc[1],'',0,0,0,0,0]);
    }
    echo "✓ Adicionales insertados (".count($adicionales).")\n";
    
    $pdo->commit();
    echo "\n[SUCCESS] Reserva BGM382 restaurada exitosamente!\n";
    
} catch (Exception $e) {
    $pdo->rollBack();
    die("\nERROR en transacción: ".$e->getMessage()."\n");
}

// Verificación final
echo "\n[VERIFICACIÓN]\n";
$verify = $pdo->prepare("SELECT idReserva, codigoAmigable, total, total_dolares, idEstado FROM reservas WHERE codigoAmigable='BGM382'");
$verify->execute();
$row = $verify->fetch(PDO::FETCH_ASSOC);
if ($row) {
    echo "✓ idReserva: ".$row['idReserva']."\n";
    echo "✓ codigoAmigable: ".$row['codigoAmigable']."\n";
    echo "✓ total: ".$row['total']."\n";
    echo "✓ total_dolares: ".$row['total_dolares']."\n";
    echo "✓ idEstado: ".$row['idEstado']."\n";
    echo "\n✅ BGM382 está lista en producción!\n";
} else {
    echo "ERROR: No se pudo verificar la inserción\n";
}
?>
