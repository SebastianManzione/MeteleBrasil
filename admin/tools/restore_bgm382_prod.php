<?php
/**
 * Script para restaurar la reserva BGM382 en producción
 * Usa creds_loader para conectar de forma segura
 */

putenv('APP_ENV=prod');

// Cargar credenciales
require_once(__DIR__ . '/../../config/creds_loader.php');
$creds = loadCredentials();

if (empty($creds['db']['host']) || empty($creds['db']['user'])) {
    die("ERROR: Credenciales de producción no configuradas. Crear config/credentials.local.php\n");
}

try {
    // Conectar a BD producción
    $pdo = new PDO(
        'mysql:host='.$creds['db']['host'].';dbname='.$creds['db']['name'].';charset=utf8mb4',
        $creds['db']['user'],
        $creds['db']['pass']
    );
    $pdo->exec("SET NAMES utf8mb4");
    
    echo "[OK] Conectado a BD producción: ".$creds['db']['name']."\n";
    
} catch (Exception $e) {
    die("ERROR: No se puede conectar a producción: ".$e->getMessage()."\n");
}

// Verificar que prestador 14 existe en producción
$checkPrestador = $pdo->prepare("SELECT idPrestador FROM prestadores WHERE idPrestador=14");
$checkPrestador->execute();
if ($checkPrestador->rowCount() == 0) {
    die("ERROR: Prestador 14 (Lagomar) no existe en producción\n");
}
echo "[OK] Prestador 14 existe en producción\n";

// Verificar que BGM382 no existe ya
$checkExist = $pdo->prepare("SELECT idReserva FROM reservas WHERE codigoAmigable='BGM382'");
$checkExist->execute();
if ($checkExist->rowCount() > 0) {
    echo "[ADVERTENCIA] BGM382 ya existe en producción. Abortando.\n";
    exit(1);
}
echo "[OK] BGM382 no existe en producción. Procediendo...\n";

// Insertar reserva y sus relaciones con transacción
try {
    $pdo->beginTransaction();
    
    // 1. Insertar reserva
    $insertReserva = $pdo->prepare(
        "INSERT INTO reservas (idUsuario,codigoAmigable,fechaAlta,nombreResponsable,apellidoResponsable,emailResponsable,idCountry,telefonoResponsable,monedaSel,impuestosPais,idCuponDescuento,idUsuarioCupon,total,total_dolares,impuestos,idEstado,nota_interna,idioma)
        VALUES (1,'BGM382','2025-12-23 20:19:35','Daniela','Freitas Batista','djdanyfreitas@hotmail.com',30,'43996491047',283,0,0,0,598,99.67,0,3,NULL,'PT')"
    );
    $insertReserva->execute();
    $idReserva = $pdo->lastInsertId();
    echo "[OK] Reserva insertada: idReserva=$idReserva\n";
    
    // 2. Insertar horario
    $insertHorarios = $pdo->prepare(
        "INSERT INTO reserva_horarios (idReserva,idServicioSalidas,idServicioSeleccionado,CodigoVoucherServicio,cantidadPasajeros,nombre,fecha,horaSalida,horaCheckIn,direccion,latitud,longitud,comentario)
        VALUES (:idReserva,64271,589,'BGM382-000589',0,'Meia Alta Temporada 11/12 - 25/12/2025','2025-12-23','10:00:00','09:55:00','R. Amaro Coelho, 67 - Barra da Lagoa, Florianópolis - SC, 88061-090, Brasil','-27.575059','-48.423394','')"
    );
    $insertHorarios->execute([':idReserva' => $idReserva]);
    $idReservaHorarios = $pdo->lastInsertId();
    echo "[OK] Horario insertado: idReservaHorarios=$idReservaHorarios\n";
    
    // 3. Insertar tarifa
    $insertTarifas = $pdo->prepare(
        "INSERT INTO reserva_tarifas (idReservaHorarios,idServicioSalidasTarifas,nombre,cantidad,monedaSel,valor,valorSinIva,valorDeIva,idFromEdad,idToEdad,comisionVendedor,comisionSistema,nombrePasajero)
        VALUES (:idReservaHorarios,183113,'INTEGRAL',2,283,598,598,0,17,13,59.8,59.8,'')"
    );
    $insertTarifas->execute([':idReservaHorarios' => $idReservaHorarios]);
    $idReservaTarifas = $pdo->lastInsertId();
    echo "[OK] Tarifa insertada: idReservaTarifas=$idReservaTarifas\n";
    
    // 4. Insertar pasajeros
    $insertPasajeros = $pdo->prepare(
        "INSERT INTO reserva_pasajeros (idReservaTarifas,nombrePasajero,apellidoPasajero) VALUES
        (:idReservaTarifas,'Daniela','Freitas Batista'),
        (:idReservaTarifas,'Gerlandio','')"
    );
    $insertPasajeros->execute([':idReservaTarifas' => $idReservaTarifas]);
    echo "[OK] Pasajeros insertados (2)\n";
    
    // 5. Insertar adicionales (11)
    $adicionales = [
        [18, 'Staff receptivo'],
        [151, 'Marineiros especializados'],
        [152, 'Agua mineral libre'],
        [153, 'Embarcación autorizada'],
        [154, 'Permiso municipal y ambiental'],
        [158, 'Toaletes masculino e feminino'],
        [160, 'Bar a bordo'],
        [217, 'Tasa de preservación'],
        [250, 'Tasa de desembarque en la isla'],
        [179, 'Chalecos salvavidas'],
        [288, 'Banheiro à bordo']
    ];
    
    $insertAdicional = $pdo->prepare(
        "INSERT INTO reserva_adicionales (idReservaHorarios,idServiciosAdicionales,nombre,descripcion,cantidad,precio,precioUnitarioSIva,valorIva,precioIva)
        VALUES (:idReservaHorarios,:idServicio,:nombre,'',0,0,0,0,0)"
    );
    
    foreach ($adicionales as $adic) {
        $insertAdicional->execute([
            ':idReservaHorarios' => $idReservaHorarios,
            ':idServicio' => $adic[0],
            ':nombre' => $adic[1]
        ]);
    }
    echo "[OK] Adicionales insertados (".count($adicionales).")\n";
    
    // Confirmar transacción
    $pdo->commit();
    echo "\n[SUCCESS] Reserva BGM382 restaurada exitosamente en producción!\n";
    
} catch (Exception $e) {
    $pdo->rollBack();
    die("ERROR en transacción: ".$e->getMessage()."\n");
}

// Verificar inserción
$verify = $pdo->prepare("SELECT idReserva, codigoAmigable, total, idEstado FROM reservas WHERE codigoAmigable='BGM382'");
$verify->execute();
$result = $verify->fetch(PDO::FETCH_ASSOC);
if ($result) {
    echo "[VERIFICACIÓN]\n";
    echo "  idReserva: ".$result['idReserva']."\n";
    echo "  codigoAmigable: ".$result['codigoAmigable']."\n";
    echo "  total: ".$result['total']."\n";
    echo "  idEstado: ".$result['idEstado']."\n";
}
?>
