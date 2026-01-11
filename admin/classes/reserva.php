<?php



function getReservas(){



    require("conexion.php");

    $consulta = "select * from reservas re inner join reserva_horarios rh ON re.idReserva = rh.idReserva";

    $comando = $pdo->prepare($consulta);

    $comando->execute();

    $cuenta_col = $comando->columnCount();

    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

    // Imprimir en pantalla

    return $resultado;



    }







    function getReserva($codigoAmigable){



    require("conexion.php");

    $data=["codigoAmigable"=>$codigoAmigable];

    $consulta = "select * from reservas WHERE codigoAmigable=:codigoAmigable";

    $comando = $pdo->prepare($consulta);

    $comando->execute($data);

    $cuenta_col = $comando->columnCount();

    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);



    // Imprimir en pantalla



    return $resultado;



    



    



    }
    
    function getReservaWithItems($codigoAmigable){
        require("conexion.php");

        $data=["codigoAmigable"=>$codigoAmigable];

        $consulta = "SELECT
            r.idReserva,
            r.idUsuario,
            r.codigoAmigable,
            r.fechaAlta,
            r.nombreResponsable,
            r.apellidoResponsable,
            r.emailResponsable,
            r.idCountry,
            r.telefonoResponsable,
            r.monedaSel,
            r.impuestosPais,
            r.idCuponDescuento,
            r.idUsuarioCupon,
            r.total,
            r.total_dolares,
            r.impuestos,
            r.idEstado,
            r.nota_interna,
            r.idioma,
            rh.idReservaHorarios,
            rh.idReserva,
            rh.idServicioSalidas,
            rh.idServicioSeleccionado,
            rh.cantidadPasajeros,
            rh.nombre,
            rh.fecha,
            rh.horaSalida,
            rh.horaCheckIn,
            rh.direccion,
            rh.latitud,
            rh.longitud,
            rh.comentario,
            rt.idReservaTarifas,
            rt.idReservaHorarios,
            rt.idServicioSalidasTarifas,
            rt.nombre,
            rt.cantidad,
            rt.monedaSel,
            rt.valor,
            rt.valorSinIva,
            rt.valorDeIva,
            rt.idFromEdad,
            rt.idToEdad,
            rt.comisionVendedor,
            rt.comisionSistema,
            rt.nombre as nombrePasajero,
            rt.valor as valorTarifa
        FROM reservas r
        INNER JOIN reserva_horarios rh ON rh.idReserva = r.idReserva
        INNER JOIN reserva_tarifas rt ON rt.idReservaHorarios = rh.idReservaHorarios
        WHERE r.codigoAmigable = :codigoAmigable";

        $comando = $pdo->prepare($consulta);
        $comando->execute($data);

        $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

        return $resultado;
    }

    function getReservaServicios($codigoAmigable){
        require("conexion.php");

        $data=["codigoAmigable"=>$codigoAmigable];

        $consulta = "SELECT s.idServicio, s.nombre_servicio, rh.nombre as periodo,
        rh.idServicioSalidas,
        scp.comisionVendedor, scp.comisionSistema
        FROM reservas r
        INNER JOIN reserva_horarios rh ON r.idReserva = rh.idReserva
        INNER JOIN servicio s ON rh.idServicioSeleccionado = s.idServicio
        LEFT JOIN servicio_comision_prestador scp ON scp.idServicio = s.idServicio
        WHERE r.codigoAmigable = :codigoAmigable";

        $comando = $pdo->prepare($consulta);
        $comando->execute($data);

        $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

        return $resultado;
    }

    function getReservasConfirmadas($idPrestador, $vistaAdmin = true){


    require("conexion.php");
    include_once('convierte_monedas.php');
    include_once('comprobantes.php');
    $data=["idPrestador"=>$idPrestador];
    $consulta = "select * from reservas WHERE idEstado=3";  // idEstado=3 is confirmada
    $comando = $pdo->prepare($consulta);
    $comando->execute();
    $cuenta_col = $comando->columnCount();
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    $retorno=array();

foreach ($resultado as $key => $value) {

  

$horarios=getReservaHorariosInnerHorarios($value['idReserva']);
$envia=false;
$idReserva=$value["idReserva"];
$monedaSel=$value["monedaSel"];
$total=$value["total"];
$total_dolares=$value["total_dolares"];
$impuestos=$value["impuestos"];
$precio=ConvierteMoneda($monedaSel,$_SESSION["moneda_sel"], $total);
$totalComprobantes=getComprobantesIdReserva($idReserva);

$diferenciaComprobantesPrecio=$precio-$totalComprobantes;
$totalComprobantesDolar=getComprobantesIdReservaDolar($idReserva);

$totalComprobantesDolar=$total_dolares-$totalComprobantesDolar;

// VALIDACIÓN CRÍTICA: Solo mostrar si está pagado 100% (tolerancia de 1.00 para arredondamientos)
if ($diferenciaComprobantesPrecio > 1.00) {
    // Hay dinero pendiente significativo, no es realmente confirmada
    continue;
}

// Determinar si debe ver todas las reservas (admin sin filtro de prestador)
$verTodasReservas = ($_SESSION['login']['idUsuario']==1 && $vistaAdmin);

   // Si es admin viendo todas, incluir todas las reservas confirmadas
   if ($verTodasReservas) {
       $envia=true;
   } else {
       // Si no es admin, filtrar por prestador
       for ($i=0; $i < count($horarios); $i++) { 
           if ($horarios[$i]["idPrestador"]==$idPrestador) {
               $envia=true; 
               break;
           }
       }
   }

   if ($envia) {
        array_push($retorno, $value);
   }
}

    // Imprimir en pantalla



    return $retorno;

    }



    function getReservasConfirmadasFechasPrestador($idPrestador, $desde, $hasta){

    require("conexion.php");
    include_once('convierte_monedas.php');
    include_once('comprobantes.php');
    $data=["idPrestador"=>$idPrestador];
    $consulta = "select * from reservas WHERE 1";
    $comando = $pdo->prepare($consulta);


    $comando->execute();//$data
    $cuenta_col = $comando->columnCount();
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    $retorno=array();

foreach ($resultado as $key => $value) {

 $horarios= getReservaHorariosInnerHorariosFechas($value['idReserva'], $desde, $hasta);
$envia=false;
$idReserva=$value["idReserva"];
$monedaSel=$value["monedaSel"];
$total=$value["total"];
$impuestos=$value["impuestos"];
$precio=ConvierteMoneda($monedaSel,$_SESSION["moneda_sel"], $total);
$totalComprobantes=getComprobantesIdReserva($idReserva);
$diferenciaComprobantesPrecio=$precio-$totalComprobantes;


   for ($i=0; $i < count($horarios); $i++) { 
 
       if ($horarios[$i]["idPrestador"]==$idPrestador && $diferenciaComprobantesPrecio < 1 || $idPrestador== (-5) && $diferenciaComprobantesPrecio < 1 ) {
        $envia=true; 
           }


   }

   if ($envia) {
        array_push($retorno, $value);
   }









}

    // Imprimir en pantalla



    return $retorno;



    



    



    }





    function getReservasConfirmadasIdUsuarioCupon($idUsuarioCupon){





    require("conexion.php");

    include_once('convierte_monedas.php');

    include_once('comprobantes.php');

    $data=["idUsuarioCupon"=>$idUsuarioCupon];



if ($idUsuarioCupon==(-5)) {

      $consulta = "select * from reservas WHERE idUsuarioCupon=0";

    $comando = $pdo->prepare($consulta);

    $comando->execute();//$data

}else{

      $consulta = "select * from reservas WHERE idUsuarioCupon=:idUsuarioCupon";

    $comando = $pdo->prepare($consulta);

    $comando->execute($data);//$data

}

  



    $cuenta_col = $comando->columnCount();



    



    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

$retorno=array();

foreach ($resultado as $key => $value) {

  

$horarios=getReservaHorariosInnerHorarios($value['idReserva']);

$envia=false;

$idReserva=$value["idReserva"];

$monedaSel=$value["monedaSel"];

$total=$value["total_dolares"];

$impuestos=$value["impuestos"];

//$precio=ConvierteMoneda($monedaSel,$_SESSION["moneda_sel"], $total);
$precio=$total;

$totalComprobantes=getComprobantesIdReservaDolar($idReserva);

$diferenciaComprobantesPrecio=$precio-$totalComprobantes;



   for ($i=0; $i < count($horarios); $i++) { 

       if ( $diferenciaComprobantesPrecio <=0 ) {

        $envia=true; 

       }

   }

   if ($envia) {

        array_push($retorno, $value);

            }

        }

    return $retorno;

    }









    function getReservasPendientes($idPrestador){





    require("conexion.php");

include_once('convierte_monedas.php');

include_once('comprobantes.php');

    $data=["idPrestador"=>$idPrestador];



    $consulta = "select * from reservas WHERE 1";



    



    $comando = $pdo->prepare($consulta);



    



    $comando->execute();//$data



    $cuenta_col = $comando->columnCount();



    



    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

$retorno=array();

foreach ($resultado as $key => $value) {

  

$horarios=getReservaHorariosInnerHorarios($value['idReserva']);

$envia=false;

$idReserva=$value["idReserva"];

$monedaSel=$value["monedaSel"];

$total=$value["total"];
$total_dolares=$value["total_dolares"];

$impuestos=$value["impuestos"];

$precio=ConvierteMoneda($monedaSel,$_SESSION["moneda_sel"], $total);

$totalComprobantes=getComprobantesIdReserva($idReserva);
$totalComprobantesDolar=getComprobantesIdReservaDolar($idReserva);

$diferenciaComprobantesPrecio=$precio-$totalComprobantes;

$diferenciaComprobantesPrecioDolar=$total_dolares-$totalComprobantesDolar;

// Determinar si debe ver todas las reservas (admin sin filtro de prestador)
$verTodasReservas = ($_SESSION['login']['idUsuario']==1 && !isset($_GET['idPrestador']));

   for ($i=0; $i < count($horarios); $i++) { 













       if (($verTodasReservas || $horarios[$i]["idPrestador"]==$idPrestador) && (($value['idEstado'] != 3) || ($diferenciaComprobantesPrecioDolar > 1) || ($diferenciaComprobantesPrecio > 0.01))) {

        $envia=true; 

       







       }

   }

   if ($envia) {

        array_push($retorno, $value);

   }









}

    // Imprimir en pantalla



    return $retorno;



    



    



    }









    function getReservaId($idReserva){







    require("conexion.php");



    $data=["idReserva"=>$idReserva];



    $consulta = "select * from reservas WHERE idReserva=:idReserva";



    



    $comando = $pdo->prepare($consulta);



    



    $comando->execute($data);



    $cuenta_col = $comando->columnCount();



    



    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);



    // Imprimir en pantalla



    return $resultado;



    



    



    }







    function altaReserva($idUsuario, $nombreResponsable, $apellidoResponsable, $emailResponsable, $idCountry, $telefonoResponsable, $monedaSel, $impuestosPais, $idioma, $idCuponDescuento,$idUsuarioCupon){



        $conta=0;



            require("conexion.php");



            require("generador_aleatorio.php");







do{



 







 $codigoAmigable=GeneradorAleatorio(3,3);







$idEstadoVenta=1;







$data=["idUsuario"=>$idUsuario, "codigoAmigable"=>$codigoAmigable, "nombreResponsable"=>$nombreResponsable, "apellidoResponsable"=>$apellidoResponsable, "emailResponsable"=>$emailResponsable, "idCountry"=>$idCountry, "telefonoResponsable"=>$telefonoResponsable, "monedaSel"=>$monedaSel, "impuestosPais"=>$impuestosPais, "idioma"=>$idioma,"idCuponDescuento"=>$idCuponDescuento,"idUsuarioCupon"=>$idUsuarioCupon ];







$consulta = "INSERT INTO reservas (idUsuario,codigoAmigable, nombreResponsable, apellidoResponsable, emailResponsable, idCountry, telefonoResponsable, monedaSel, impuestosPais, idioma, idCuponDescuento, idUsuarioCupon) VALUES (:idUsuario,:codigoAmigable, :nombreResponsable,:apellidoResponsable,:emailResponsable, :idCountry,:telefonoResponsable,:monedaSel,:impuestosPais, :idioma,:idCuponDescuento, :idUsuarioCupon) ";







$comando = $pdo->prepare($consulta);







$comando->execute($data);



$cuenta_col = $comando->columnCount();



$cuenta_row = $comando->rowCount();



$resultado = $comando->fetchAll(PDO::FETCH_ASSOC);



$error=$comando->errorInfo()[1];



$lastInsertId = $pdo->lastInsertId();



$reserva=["codigoAmigable"=> $codigoAmigable, "idReserva"=>$lastInsertId];



sleep(1);







$conta++;



if ($conta==3) {



  



 



 return 0;



}



}







while ($error>0) ;











  return  $reserva; 



    }















function updateTotalReserva($idReserva, $total,$total_dolares, $impuestos){







require("conexion.php");







 $data=[



"idReserva"=>$idReserva, "total"=>$total,"total_dolares"=>$total_dolares, "impuestos"=>$impuestos];



$consulta = "UPDATE reservas SET total=:total, total_dolares=:total_dolares, impuestos=:impuestos WHERE idReserva = :idReserva ";







$comando = $pdo->prepare($consulta);







$comando->execute($data);



$cuenta_col = $comando->columnCount();







$resultado = $comando->fetchAll(PDO::FETCH_ASSOC);











// Imprimir en pantalla











return $resultado;











}















 function altaReservaHorarios($idReserva, $idServicioSalidas, $idServicioSeleccionado, $cantidadPasajeros, $nombre, $fecha, $horaSalida, $horaCheckIn, $direccion, $latitud, $longitud, $comentario){











        require("conexion.php");

        // Obtener el codigoAmigable de la reserva
        $dataReserva = ["idReserva" => $idReserva];
        $consultaReserva = "SELECT codigoAmigable FROM reservas WHERE idReserva = :idReserva LIMIT 1";
        $comandoReserva = $pdo->prepare($consultaReserva);
        $comandoReserva->execute($dataReserva);
        $resultadoReserva = $comandoReserva->fetch(PDO::FETCH_ASSOC);
        
        // Generar el codigoAmigable para reserva_horarios: codigoAmigable + "-" + idServicioSeleccionado (6 dígitos con ceros a la izquierda)
        $codigoAmigableHorario = '';
        if ($resultadoReserva && isset($resultadoReserva['codigoAmigable'])) {
            $codigoAmigableHorario = $resultadoReserva['codigoAmigable'] . '-' . str_pad($idServicioSeleccionado, 6, '0', STR_PAD_LEFT);
        }

        $data=["idReserva"=>$idReserva, "idServicioSalidas"=> $idServicioSalidas, "idServicioSeleccionado"=>$idServicioSeleccionado, "CodigoVoucherServicio"=>$codigoAmigableHorario, "cantidadPasajeros"=>$cantidadPasajeros,"nombre"=>$nombre, "fecha"=> $fecha, "horaSalida"=>$horaSalida, "horaCheckIn"=>$horaCheckIn, "direccion"=>$direccion, "latitud"=>$latitud, "longitud"=>$longitud, "comentario"=> $comentario ];



        $consulta = "INSERT INTO reserva_horarios (idReserva, idServicioSalidas, idServicioSeleccionado, CodigoVoucherServicio, cantidadPasajeros, nombre, fecha, horaSalida, horaCheckIn, direccion, latitud, longitud, comentario) VALUES (:idReserva,:idServicioSalidas, :idServicioSeleccionado, :CodigoVoucherServicio, :cantidadPasajeros, :nombre, :fecha, :horaSalida, :horaCheckIn, :direccion, :latitud, :longitud, :comentario) ";



        



        $comando = $pdo->prepare($consulta);



        



        $comando->execute($data);



        



        $id = $pdo->lastInsertId(); 



        $cuenta_col = $comando->columnCount();



        $cuenta_row = $comando->rowCount();



        $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);







        return $id;



        



        



        }











 function altaReservaTarifas($idServicioSalidasTarifas, $idReservaHorarios,$cantidad, $monedaSel, $valor, $valorSinIva, $valorDeIva, $idFromEdad, $idToEdad, $comisionVendedor, $comisionSistema, $nombre, $valorOriginal = 0){











        require("conexion.php");

        // Si no se pasó valorOriginal, usar el valor actual
        if ($valorOriginal == 0) {
            $valorOriginal = $valor;
        }

        $data=["idServicioSalidasTarifas"=> $idServicioSalidasTarifas, "idReservaHorarios"=> $idReservaHorarios, "cantidad"=>$cantidad, "monedaSel"=>$monedaSel, "valor"=>$valor, "valorSinIva"=>$valorSinIva, "valorDeIva"=> $valorDeIva, "idFromEdad"=> $idFromEdad, "idToEdad"=> $idToEdad,  "comisionVendedor"=> $comisionVendedor, "comisionSistema"=> $comisionSistema, "nombre"=> $nombre, "valorOriginal"=> $valorOriginal];



        $consulta = "INSERT INTO reserva_tarifas (idServicioSalidasTarifas, idReservaHorarios, cantidad, monedaSel, valor, valorSinIva, valorDeIva, idFromEdad, idToEdad, comisionVendedor, comisionSistema, nombre, valorOriginal) VALUES (:idServicioSalidasTarifas, :idReservaHorarios, :cantidad, :monedaSel, :valor, :valorSinIva, :valorDeIva, :idFromEdad, :idToEdad, :comisionVendedor, :comisionSistema, :nombre, :valorOriginal) ";



        $comando = $pdo->prepare($consulta);



        



        $comando->execute($data);



        



        $id = $pdo->lastInsertId(); 



        $cuenta_col = $comando->columnCount();



        $cuenta_row = $comando->rowCount();



        $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);







        return $id;



        



        



        }











 function altaReservaPasajero($idReservaTarifas, $nombrePasajero, $apellidoPasajero){











        require("conexion.php");



        $data=["idReservaTarifas"=> $idReservaTarifas, "nombrePasajero"=> $nombrePasajero, "apellidoPasajero"=>$apellidoPasajero];



        $consulta = "INSERT INTO reserva_pasajeros (idReservaTarifas, nombrePasajero, apellidoPasajero) VALUES (:idReservaTarifas,  :nombrePasajero,:apellidoPasajero) ";



        



        $comando = $pdo->prepare($consulta);



        



        $comando->execute($data);



        



        $id = $pdo->lastInsertId(); 



        $cuenta_col = $comando->columnCount();



        $cuenta_row = $comando->rowCount();



        $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);







        return $id;



        



        



        }







 function altaReservaAdicional($idReservaHorarios, $idServiciosAdicionales,$nombre, $descripcion, $cantidad, $precio, $precioUnitarioSIva, $valorIva, $precioIva, $idMoneda=0){











        require("conexion.php");



        $data=["idReservaHorarios"=> $idReservaHorarios, "idServiciosAdicionales"=> $idServiciosAdicionales, "nombre"=>$nombre, "descripcion"=>$descripcion, "cantidad"=>$cantidad, "precio"=>$precio, "precioUnitarioSIva"=> $precioUnitarioSIva, "valorIva"=> $valorIva, "precioIva"=> $precioIva, "idMoneda"=> $idMoneda];



        $consulta = "INSERT INTO reserva_adicionales (idReservaHorarios, idServiciosAdicionales, nombre, descripcion, cantidad, precio, precioUnitarioSIva, valorIva, precioIva, idMoneda) VALUES (:idReservaHorarios,  :idServiciosAdicionales,:nombre, :descripcion, :cantidad, :precio, :precioUnitarioSIva, :valorIva, :precioIva, :idMoneda) ";



        



        $comando = $pdo->prepare($consulta);



        



        $comando->execute($data);



        



        $id = $pdo->lastInsertId(); 



        $cuenta_col = $comando->columnCount();



        $cuenta_row = $comando->rowCount();



        $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);



 



        return $id;



        



        



        }







    function getReservaHorarios($idReserva){







    require("conexion.php");



    $data=["idReserva"=>$idReserva];



    $consulta = "select * from reserva_horarios WHERE idReserva=:idReserva ORDER BY idServicioSeleccionado";



    



    $comando = $pdo->prepare($consulta);



    



    $comando->execute($data);



    $cuenta_col = $comando->columnCount();



    



    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);



    // Imprimir en pantalla



    return $resultado;



    



    



    }

 function getReservaHorariosIdServicioSalidas($idServicioSalidas){







    require("conexion.php");



    $data=["idServicioSalidas"=>$idServicioSalidas];



    $consulta = "select * from reserva_horarios WHERE idServicioSalidas=:idServicioSalidas ";



    



    $comando = $pdo->prepare($consulta);



    



    $comando->execute($data);



    $cuenta_col = $comando->columnCount();



    



    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);



    // Imprimir en pantalla



    return $resultado;



    



    



    }

    function getReservaHorariosInnerHorarios($idReserva){







    require("conexion.php");



    $data=["idReserva"=>$idReserva];



    $consulta = "select * from reserva_horarios RH INNER JOIN servicio_salidas SS ON RH.idServicioSalidas = SS.idServicioSalidas WHERE idReserva=:idReserva ORDER BY idServicioSeleccionado";



    



    $comando = $pdo->prepare($consulta);



    



    $comando->execute($data);



    $cuenta_col = $comando->columnCount();



    



    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);



    // Imprimir en pantalla



    return $resultado;



    



    



    }



      function getReservaHorariosInnerHorariosFechas($idReserva, $desde, $hasta){







    require("conexion.php");



    $data=["idReserva"=>$idReserva, "desde"=>$desde, "hasta"=>$hasta];



    $consulta = "select * from reserva_horarios RH INNER JOIN servicio_salidas SS ON RH.idServicioSalidas = SS.idServicioSalidas WHERE idReserva=:idReserva AND RH.fecha BETWEEN :desde AND :hasta  ORDER BY idServicioSeleccionado";



    



    $comando = $pdo->prepare($consulta);



    



    $comando->execute($data);



    $cuenta_col = $comando->columnCount();



    



    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);



    // Imprimir en pantalla



    return $resultado;



    



    



    }





    function getReservaHorariosId($idReservaHorarios){







    require("conexion.php");



    $data=["idReservaHorarios"=>$idReservaHorarios];



    $consulta = "select * from reserva_horarios WHERE idReservaHorarios=:idReservaHorarios ORDER BY idServicioSeleccionado";



    



    $comando = $pdo->prepare($consulta);



    



    $comando->execute($data);



    $cuenta_col = $comando->columnCount();



    



    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);



    // Imprimir en pantalla



    return $resultado;



    



    



    }



    function getReservaTarifas($idReservaHorarios){


    require("conexion.php");
    $data=["idReservaHorarios"=>$idReservaHorarios];
    $consulta = "SELECT rt.*, sst.nombre as nombreTarifa, sst.idTipoTarifa, sst.idCancelaciones, sst.comisiona as comisionaTarifa 
                 FROM reserva_tarifas rt 
                 LEFT JOIN servicio_salidas_tarifas sst ON rt.idServicioSalidasTarifas = sst.idServicioSalidasTarifas
                 WHERE rt.idReservaHorarios=:idReservaHorarios ORDER BY rt.idReservaHorarios";
    $comando = $pdo->prepare($consulta);
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);


    return $resultado;


    }


 function getReservaAdicionalesNoIncluidos($idReservaHorarios){


    require("conexion.php");
    $data=["idReservaHorarios"=>$idReservaHorarios];
    $consulta = "select * from reserva_adicionales WHERE idReservaHorarios=:idReservaHorarios AND precio>0";
    $comando = $pdo->prepare($consulta);
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

    return $resultado;



    



    



    }



/*



function getHorariosReservados($idPrestador){



        require("conexion.php");



      $consulta =  "SELECT * FROM `reserva_horarios` RH INNER JOIN servicio_salidas SS ON  RH.idServicioSalidas=SS.idServicioSalidas 



      INNER JOIN reservas RES ON RH.idReserva= RES.idReserva



      WHERE SS.idPrestador=2";



       $comando = $pdo->prepare($consulta);



    



    $comando->execute();



    $cuenta_col = $comando->columnCount();



    



    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);



    // Imprimir en pantalla



    return $resultado;



}

*/





function getTarifasReservadas($idServicioSalidas){

        require("conexion.php");
$data=["idServicioSalidas"=>$idServicioSalidas];
      $consulta =  "SELECT * FROM reserva_tarifas RT INNER JOIN  reserva_horarios RH ON RT.idReservaHorarios=RH.idReservaHorarios WHERE RH.idServicioSalidas=:idServicioSalidas";

       $comando = $pdo->prepare($consulta);
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

    // Imprimir en pantalla
    return $resultado;


}







function getTarifasReservadasIdTarifa($idReservaTarifas){



        require("conexion.php");



        $data=["idReservaTarifas"=>$idReservaTarifas];



      $consulta =  "SELECT * FROM reserva_tarifas WHERE idReservaTarifas=:idReservaTarifas



      ";



       $comando = $pdo->prepare($consulta);



    



    $comando->execute($data);



    $cuenta_col = $comando->columnCount();



    



    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);







    // Imprimir en pantalla



    return $resultado;



}






function getCantidadPasajerosIdReservaTarifas($idServicioSalidas){



$tarifasReservadas=getTarifasReservadas($idServicioSalidas);

$cantidadPasajeros=0;

for ($i=0; $i < count($tarifasReservadas); $i++) { 

$cantidadPasajeros+=$tarifasReservadas[$i]['cantidad'];





}

return $cantidadPasajeros;


    
}












   function getPasajeros($idReservaTarifas){







    require("conexion.php");



    $data=["idReservaTarifas"=>$idReservaTarifas];



    $consulta = "select * from reserva_pasajeros WHERE idReservaTarifas=:idReservaTarifas";



    



    $comando = $pdo->prepare($consulta);



    



    $comando->execute($data);



    $cuenta_col = $comando->columnCount();



    



    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);



    // Imprimir en pantalla



    return $resultado;



    



    



    }



function getHorariosReservados_idServicioSeleccionado($idServicioSeleccionado){







    require("conexion.php");



    $data=["idServicioSeleccionado"=>$idServicioSeleccionado];



    $consulta = "select * from reserva_horarios WHERE idServicioSeleccionado=:idServicioSeleccionado";



    



    $comando = $pdo->prepare($consulta);



    



    $comando->execute($data);



    $cuenta_col = $comando->columnCount();



    



    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);



    // Imprimir en pantalla



    return $resultado;



    



    



    }







function updateAdjuntoReserva($idNotaReserva, $files){

print_r($files);

    $file_name = $files['adjunto']['name'];

      $file_size =$files['adjunto']['size'];

      $file_tmp =$files['adjunto']['tmp_name'];

      $file_type=$files['adjunto']['type'];

     

      $ext = pathinfo($file_name, PATHINFO_EXTENSION);

      $fileName=$idNotaReserva.microtime().".".$ext;

      $carpeta='img/adjuntos_carrito/';



      echo "string".$file_type;

move_uploaded_file($file_tmp, $carpeta.$fileName);

         echo "Success";



require("conexion.php");







 $data=[



"idNotaReserva"=>$idNotaReserva, "fileName"=>$fileName];



$consulta = "UPDATE reserva_notas SET adjunto=:fileName WHERE idNotaReserva = :idNotaReserva ";







$comando = $pdo->prepare($consulta);







$comando->execute($data);



$cuenta_col = $comando->columnCount();







$resultado = $comando->fetchAll(PDO::FETCH_ASSOC);





}



 function altaNotaReserva($idUsuario, $nota,$idReserva, $files){











        require("conexion.php");



        $data=["idUsuario"=> $idUsuario, "nota"=> $nota, "idReserva"=>$idReserva];



        $consulta = "INSERT INTO reserva_notas (idUsuario, nota, idReserva) VALUES (:idUsuario,  :nota,:idReserva) ";



        



        $comando = $pdo->prepare($consulta);



        



        $comando->execute($data);



        



        $id = $pdo->lastInsertId(); 



        $cuenta_col = $comando->columnCount();



        $cuenta_row = $comando->rowCount();



        $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);



updateAdjuntoReserva($id, $files);



        return $id;



        



        



        }







        function getNotasReserva($idReserva){



        require("conexion.php");



        $data=["idReserva"=>$idReserva];



      $consulta =  "SELECT * FROM reserva_notas WHERE idReserva=:idReserva



      ";



       $comando = $pdo->prepare($consulta);



    



    $comando->execute($data);



    $cuenta_col = $comando->columnCount();



    



    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);



    // Imprimir en pantalla



    return $resultado;



}







function confirmaReserva($idReserva){







require("conexion.php");







 $data=[



"idReserva"=>$idReserva];



$consulta = "UPDATE reservas SET idEstado=3 WHERE idReserva = :idReserva ";







$comando = $pdo->prepare($consulta);







$comando->execute($data);



$cuenta_col = $comando->columnCount();







$resultado = $comando->fetchAll(PDO::FETCH_ASSOC);











// Imprimir en pantalla











return $resultado;











}











function eliminarReserva($idReserva){







require("conexion.php");



    $data=["idReserva"=> $idReserva];







    $consulta = "select * from reserva_horarios  WHERE idReserva=:idReserva";



    



    $comando = $pdo->prepare($consulta);



    $comando->execute($data);

   $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);





   for ($i=0; $i < count($resultado); $i++) { 



$idReserva=$resultado[$i]["idReserva"];

  $data2=["idReserva"=> $idReserva];



    $consulta2 = "select * from reserva_horarios  WHERE idReserva=:idReserva";

   



    $comando2 = $pdo->prepare($consulta2);



    $comando2->execute($data2);

   $resultado2 = $comando2->fetchAll(PDO::FETCH_ASSOC);





   for ($j=0; $j < count($resultado2); $j++) { 

        $idReservaHorarios=$resultado2[$j]["idReservaHorarios"];

  $data3=["idReservaHorarios"=> $idReservaHorarios];



    $consulta3 = "select * from reserva_tarifas  WHERE idReservaHorarios=:idReservaHorarios";

   



    $comando3 = $pdo->prepare($consulta3);



    $comando3->execute($data3);

   $resultado3 = $comando3->fetchAll(PDO::FETCH_ASSOC);



   for ($k=0; $k < count($resultado3); $k++) { 

       $idReservaTarifas=$resultado3[$k]['idReservaTarifas'];

  $data4=["idReservaTarifas"=> $idReservaTarifas];

 



    $consulta4 = "DELETE FROM reserva_pasajeros WHERE idReservaTarifas=:idReservaTarifas ";

    $comando4 = $pdo->prepare($consulta4);

    $comando4->execute($data4);

 

   }

    $data3=["idReservaHorarios"=> $idReservaHorarios];



    $consulta3 = "DELETE FROM reserva_tarifas  WHERE idReservaHorarios=:idReservaHorarios";

   



    $comando3 = $pdo->prepare($consulta3);



    $comando3->execute($data3);

   $data3=["idReservaHorarios"=> $idReservaHorarios];



    $consulta3 = "DELETE FROM reserva_adicionales  WHERE idReservaHorarios=:idReservaHorarios";

   



    $comando3 = $pdo->prepare($consulta3);



    $comando3->execute($data3);



   }



  $data2=["idReserva"=> $idReserva];



    $consulta2 = "DELETE FROM reserva_horarios  WHERE idReserva=:idReserva";

   



    $comando2 = $pdo->prepare($consulta2);



    $comando2->execute($data2);







 





   }





    $consulta = "DELETE FROM reservas  WHERE idReserva=:idReserva";



    



    $comando = $pdo->prepare($consulta);



    $comando->execute($data);







    

return 1;

    



    }

















/*



        



function borraPrestador($idPrestador){







require("conexion.php");



    $data=["idPrestador"=> $idPrestador];



    $consulta = "DELETE FROM prestadores WHERE idPrestador=:idPrestador ";



    



    $comando = $pdo->prepare($consulta);



    



    $comando->execute($data);



    $cuenta_col = $comando->columnCount();



    $cuenta_row = $comando->rowCount();



    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);



    



    



    return $cuenta_row;



    



    



    }*/



    



?>
