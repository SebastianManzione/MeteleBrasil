<?php







function getSalidas(){


    require("conexion.php");
    $consulta = "select * from servicio_salidas";
    $comando = $pdo->prepare($consulta);
    $comando->execute();
$cuenta_col = $comando->columnCount();
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    return $resultado;
    }







    function getSalida($idServicioSalidas){
 require("conexion.php");

    $data=["idServicioSalidas"=>$idServicioSalidas];
    $consulta = "select * from servicio_salidas WHERE idServicioSalidas=:idServicioSalidas ";
    $comando = $pdo->prepare($consulta);
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla
    return $resultado;
    }





        function getAllSalidasServicio($idServicio){

         $fechaHoy =date("Y-m-d");
    require("conexion.php");
    $data=["idServicio"=>$idServicio, "fechaHoy"=>$fechaHoy];
    $consulta = "select * from servicio_salidas WHERE idServicio=:idServicio AND fecha>=:fechaHoy ORDER BY fecha ASC";
    $comando = $pdo->prepare($consulta);
   
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla
    return $resultado;


    }



        function getSalidasServicio($idServicio){



 $fechaHoy =date("Y-m-d");



    require("conexion.php");



    $data=["idServicio"=>$idServicio,"fechaHoy"=>$fechaHoy];



    $consulta = "select * from servicio_salidas WHERE idServicio=:idServicio AND fecha>=  :fechaHoy";



    



    $comando = $pdo->prepare($consulta);



    



    $comando->execute($data);



    $cuenta_col = $comando->columnCount();



    



    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);



    // Imprimir en pantalla



    return $resultado;



    



    



    }



        function getSalidasServicioIdPrestador($idServicio){



 $fechaHoy =date("Y-m-d");



    require("conexion.php");



    $idPrestador=$_SESSION["login"]["idPrestador"];



    $data=["idServicio"=>$idServicio,"idPrestador"=>$idPrestador];



    $consulta = "select * from servicio_salidas WHERE idServicio=:idServicio AND idPrestador= :idPrestador";



    



    $comando = $pdo->prepare($consulta);



    



    $comando->execute($data);



    $cuenta_col = $comando->columnCount();



    



    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);



    // Imprimir en pantalla



    return $resultado;



    



    



    }





        function getSalidasIdPrestadorHoy(){



 $fechaHoy =date("Y-m-d");



    require("conexion.php");



    $idPrestador=$_SESSION["login"]["idPrestador"];

$hoy=date('Y-m-d');



if ($_SESSION['login']['idUsuario']==1) {

        $data=["hoy"=>$hoy];

    $consulta = "select * from servicio_salidas WHERE fecha=:hoy";

}

else{

    $data=["idPrestador"=>$idPrestador, "hoy"=>$hoy];

    $consulta = "select * from servicio_salidas WHERE idPrestador= :idPrestador AND fecha=:hoy";

}

   



    



    $comando = $pdo->prepare($consulta);



    



    $comando->execute($data);



    $cuenta_col = $comando->columnCount();



    



    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);



    // Imprimir en pantalla



    return $resultado;



    



    



    }





   function getSalidasIdPrestadorFecha($fechaEspecifica){

 $fechaHoy =date("Y-m-d");

    require("conexion.php");

    $idPrestador=$_SESSION["login"]["idPrestador"];

$fecha=date('Y-m-d', strtotime($fechaEspecifica));

if ($_SESSION['login']['idUsuario']==1) {

        $data=["fecha"=>$fecha];

    $consulta = "select * from servicio_salidas WHERE fecha=:fecha";

}

else{

    $data=["idPrestador"=>$idPrestador, "fecha"=>$fecha];

    $consulta = "select * from servicio_salidas WHERE idPrestador= :idPrestador AND fecha=:fecha";

}

    $comando = $pdo->prepare($consulta);

    $comando->execute($data);

    $cuenta_col = $comando->columnCount();

    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

    // Imprimir en pantalla

    return $resultado;


    }


   function getSalidasComisionadoFecha($fechaEspecifica){

            require("conexion.php");

            $idUsuario = $_SESSION["login"]["idUsuario"];

            $fecha = date('Y-m-d', strtotime($fechaEspecifica));

            // Obtener salidas de comisionados:
            // 1. Buscar reservas que tienen idUsuarioCupon (reservas con cupón)
            // 2. Obtener los idServicioSalidas únicos de esas reservas
            // 3. Filtrar salidas por fecha y por esos IDs

            $data = ["fecha" => $fecha];
            $consulta = "SELECT DISTINCT ss.* FROM servicio_salidas ss
                        INNER JOIN reserva_horarios rh ON rh.idServicioSalidas = ss.idServicioSalidas
                        INNER JOIN reservas r ON r.idReserva = rh.idReserva
                        WHERE ss.fecha = :fecha AND r.idUsuarioCupon IS NOT NULL AND r.idUsuarioCupon != ''";

            $comando = $pdo->prepare($consulta);
            $comando->execute($data);
            $cuenta_col = $comando->columnCount();
            $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

            return $resultado;
        }


   function getSalidasFechaLuegoIdServicio($fecha,$idServicio){

    require("conexion.php");
    $data=["idServicio"=>$idServicio,"fecha"=>$fecha];
    $consulta = "select * from servicio_salidas WHERE idServicio=:idServicio AND fecha >= :fecha";
    $comando = $pdo->prepare($consulta);
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

    $retorno=array();

for ($i=0; $i < count($resultado); $i++) { 



$idServicioSalidas=$resultado[$i]["idServicioSalidas"];
$idPrestador=$resultado[$i]["idPrestador"];
$idServiciosSalidasPack=$resultado[$i]["idServiciosSalidasPack"];
$nombre=$resultado[$i]["nombre"];
$fecha=$resultado[$i]["fecha"];
$horaSalida=$resultado[$i]["horaSalida"];
$horaCheckIn=$resultado[$i]["horaCheckIn"];
$anticipacionReserva=$resultado[$i]["anticipacionReserva"];
$duracionMinima=$resultado[$i]["duracionMinima"];
$duracionMaxima=$resultado[$i]["duracionMaxima"];
$idAccesibilidad=$resultado[$i]["idAccesibilidad"];
$disponibilidadOriginal=$resultado[$i]["disponibilidadOriginal"];
$disponibilidad=$resultado[$i]["disponibilidad"];
$idMoneda=$resultado[$i]["idMoneda"];
$nota_salida=$resultado[$i]["nota_salida"];
$sinHorario=$resultado[$i]["sinHorario"];
$sinHorarioTexto=$resultado[$i]["sinHorarioTexto"];



   $parametro=" ";

if ($anticipacionReserva<1) {

   $parametro=" minutes";

   $hsAnticipacionReserva=$anticipacionReserva*60;

}

if ($anticipacionReserva>1) {

   $parametro=" hour";

   $hsAnticipacionReserva=$anticipacionReserva;

}



$fechaTimeActual = strtotime(date("d-m-Y H:i:00",time()));

$fechaTimeSalida = strtotime($fecha." ".$horaSalida.":00"." -".$hsAnticipacionReserva.$parametro);



if ($fechaTimeActual<$fechaTimeSalida) {

array_push($retorno, $resultado[$i]);

};





 

}

    return $retorno;



    



    



    }



        function getSalidasFechaIdServicio($fecha,$idServicio){

    require("conexion.php");
    $data=["idServicio"=>$idServicio,"fecha"=>$fecha];
    $consulta = "select * from servicio_salidas WHERE idServicio=:idServicio AND fecha = :fecha";
    $comando = $pdo->prepare($consulta);
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    return $resultado;


    }







   function GetEventosArray($idServicio){

    require("conexion.php");
    $fecha=date("Y-m-d");
    $data=["idServicio"=>$idServicio, "fecha"=>$fecha];
    $consulta = "select * from servicio_salidas WHERE idServicio=:idServicio AND fecha >= :fecha AND disponibilidad>0 ORDER BY fecha ASC";
    $comando = $pdo->prepare($consulta);
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();

    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
                 $hoy = time();
                 $nuevaFecha = date('Y-m-d', $hoy + 86400); //el dia de hoy + 24horas
                $eventos="";
                $eventArray="";
                $fechasConDisponibilidad = array(); // Para agrupar salidas por fecha

    
    // Primero agrupar por fecha y verificar disponibilidad
    for ($i=0; $i < count($resultado); $i++) {
$anticipacionReserva=$resultado[$i]["anticipacionReserva"];
$horaSalida=$resultado[$i]["horaSalida"];
$fecha=$resultado[$i]["fecha"];
$disponibilidadActual = intval($resultado[$i]['disponibilidad']);
 $parametro=" ";
   

if ($anticipacionReserva<1) {
   $parametro=" minutes";
   $hsAnticipacionReserva=$anticipacionReserva*60;

}

if ($anticipacionReserva>=1) {
   $parametro=" hours";
   $hsAnticipacionReserva=$anticipacionReserva;
}

$fechaTimeActual = strtotime(date("Y-m-d H:i:s"));
$fechaTimeSalida = strtotime($fecha." ".$horaSalida.":00 - ".$hsAnticipacionReserva.$parametro);

if (false) { //$_SESSION['login']['idUsuario']==1
echo "TEST ".date("Y-m-d H:i:s",$fechaTimeActual);
echo "TEST ".date("Y-m-d H:i:s",$fechaTimeSalida);
if ($fechaTimeActual<$fechaTimeSalida) {
   echo "ok";}
   echo "<br>";
}

// Solo considerar si cumple tiempo de anticipación Y tiene disponibilidad
if ($fechaTimeActual<=$fechaTimeSalida && $disponibilidadActual > 0) {
     $fechaFormateada = date("Y-m-d", strtotime($resultado[$i]['fecha']));
     
     // Agregar a array de fechas con disponibilidad
     if (!isset($fechasConDisponibilidad[$fechaFormateada])) {
         $fechasConDisponibilidad[$fechaFormateada] = true;
     }
}

    }
    
    // Ahora construir el eventArray solo con fechas que tienen disponibilidad
    foreach ($fechasConDisponibilidad as $fechaDisponible => $value) {
        $eventos=$eventos."
            {
             title: 'disponible', url: 'reservate!',
            date: '".$fechaDisponible."',
            endDate: '".$fechaDisponible."',
            startDate:'".$fechaDisponible."'
        }, ";
    }
    
    if (!empty($eventos)) {
        $eventArray="[". $eventos."]";
    }

    return $eventArray;


    }





      function getSalidasIdServiciosSalidasPack($IdServiciosSalidasPack){


    require("conexion.php");
    $data=["IdServiciosSalidasPack"=>$IdServiciosSalidasPack];
    $consulta = "select * from servicio_salidas WHERE IdServiciosSalidasPack=:IdServiciosSalidasPack";
    $comando = $pdo->prepare($consulta);
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

    return $resultado;

    }



function altaSalida($nombre, $idServicio, $fecha, $hSalida, $horaCheckIn, $anticipacionReserva, $duracionMinima, $duracionMaxima, $disponibilidad, $idAccesibilidad, $prestador, $idServiciosSalidasPack, $idMoneda, $nota_salida, $nota_salida_en, $nota_salida_pt, $nota_salida_it, $sinHorario, $sinHorarioTexto, $idComisionPrestador = null) {
    require("conexion.php");

    // Adicionar os novos campos ao array $data
    $data = [
        "nombre" => $nombre,
        "idServicio" => $idServicio,
        "fecha" => $fecha,
        "hSalida" => $hSalida,
        "horaCheckIn" => $horaCheckIn,
        "anticipacionReserva" => $anticipacionReserva,
        "duracionMinima" => $duracionMinima,
        "duracionMaxima" => $duracionMaxima,
        "disponibilidad" => $disponibilidad,
        "idAccesibilidad" => $idAccesibilidad,
        "prestador" => $prestador,
        "idServiciosSalidasPack" => $idServiciosSalidasPack,
        "idMoneda" => $idMoneda,
        "nota_salida" => $nota_salida,
        "nota_salida_en" => $nota_salida_en,
        "nota_salida_pt" => $nota_salida_pt,
        "nota_salida_it" => $nota_salida_it,
        "sinHorario" => $sinHorario,
        "sinHorarioTexto" => $sinHorarioTexto,
        "idComisionPrestador" => $idComisionPrestador
    ];

    // Atualizar a consulta SQL para incluir os novos campos
    $consulta = "INSERT INTO servicio_salidas (nombre, idServicio, fecha, horaSalida, horaCheckIn, anticipacionReserva, duracionMinima, duracionMaxima, disponibilidadOriginal, disponibilidad, idAccesibilidad, idPrestador, idServiciosSalidasPack, idMoneda, nota_salida, nota_salida_en, nota_salida_pt, nota_salida_it, sinHorario, sinHorarioTexto, idComisionPrestador)
                 VALUES (:nombre, :idServicio, :fecha, :hSalida, :horaCheckIn, :anticipacionReserva, :duracionMinima, :duracionMaxima, :disponibilidad, :disponibilidad, :idAccesibilidad, :prestador, :idServiciosSalidasPack, :idMoneda, :nota_salida, :nota_salida_en, :nota_salida_pt, :nota_salida_it, :sinHorario, :sinHorarioTexto, :idComisionPrestador)";

    $comando = $pdo->prepare($consulta);
    $comando->execute($data);
    $id = $pdo->lastInsertId();
    $cuenta_col = $comando->columnCount();
    $cuenta_row = $comando->rowCount();
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    return $id;
}




    function getIdiomasSalida($idServicioSalidas){


    require("conexion.php");
    $data=["idServicioSalidas"=>$idServicioSalidas];
    $consulta = "select * from servicio_salidas_idioma WHERE idServicioSalidas=:idServicioSalidas";
    $comando = $pdo->prepare($consulta);
    $comando->execute($data);
    $cuenta_col = $comando->columnCount();
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    // Imprimir en pantalla
    $idiomas=array();
    for ($i=0; $i < count( $resultado); $i++) { 
        $idioma=getIdioma($resultado[$i]["idIdioma"]);
        array_push($idiomas,  $idioma[0]["nombre"]);
    }

    return  $idiomas;
    }







    function restaDisponibilidadSalida($idServicioSalidas, $cantidadLugares){


require("conexion.php");

 $data=[
"idServicioSalidas"=>$idServicioSalidas, "cantidadLugares"=>$cantidadLugares, "lugaresOcupados"=>$cantidadLugares];

$consulta = "UPDATE servicio_salidas SET disponibilidad=disponibilidad - :cantidadLugares, lugaresOcupados=lugaresOcupados + :cantidadLugares WHERE idServicioSalidas = :idServicioSalidas ";
$comando = $pdo->prepare($consulta);
$comando->execute($data);
$cuenta_col = $comando->columnCount();
$resultado = $comando->rowCount();

return $resultado;

}


function updateSalida($idServicioSalidas, $nombre, $fecha, $horaSalida, $horaCheckIn, $anticipacionReserva, $duracionMinima, $duracionMaxima, $cantLugares, $nota_salida, $idAccesibilidad){





require("conexion.php");

$data=[

"idServicioSalidas"=>$idServicioSalidas, "nombre"=>$nombre,"fecha"=>$fecha, "horaSalida"=>$horaSalida, "horaCheckIn"=>$horaCheckIn, "anticipacionReserva"=>$anticipacionReserva,"duracionMinima"=>$duracionMinima, "duracionMaxima"=>$duracionMaxima, "disponibilidad"=>$cantLugares, "nota_salida"=>$nota_salida, "idAccesibilidad"=>$idAccesibilidad];
$consulta = "UPDATE servicio_salidas SET nombre=:nombre, fecha=:fecha, horaSalida=:horaSalida, horaCheckIn=:horaCheckIn, anticipacionReserva=:anticipacionReserva, duracionMinima=:duracionMinima, duracionMaxima=:duracionMaxima, disponibilidad=:disponibilidad, nota_salida=:nota_salida, idAccesibilidad=:idAccesibilidad WHERE idServicioSalidas = :idServicioSalidas ";

$comando = $pdo->prepare($consulta);
$comando->execute($data);
$cuenta_col = $comando->columnCount();
$resultado = $comando->rowCount();

return $resultado;

}





 function updateSalidasPack($idServiciosSalidasPack, $nombre, $fecha, $horaSalida, $horaCheckIn, $anticipacionReserva, $duracionMinima, $duracionMaxima, $cantLugares, $nota_salida, $idAccesibilidad){

require("conexion.php");

 $data=[
"idServiciosSalidasPack"=>$idServiciosSalidasPack, "nombre"=>$nombre, "horaSalida"=>$horaSalida, "horaCheckIn"=>$horaCheckIn, "anticipacionReserva"=>$anticipacionReserva,"duracionMinima"=>$duracionMinima, "duracionMaxima"=>$duracionMaxima, "disponibilidad"=>$cantLugares, "nota_salida"=>$nota_salida, "idAccesibilidad"=>$idAccesibilidad];

$consulta = "UPDATE servicio_salidas SET nombre=:nombre, horaSalida=:horaSalida, horaCheckIn=:horaCheckIn, anticipacionReserva=:anticipacionReserva, duracionMinima=:duracionMinima, duracionMaxima=:duracionMaxima, disponibilidad=:disponibilidad,disponibilidadOriginal=:disponibilidad, nota_salida=:nota_salida, idAccesibilidad=:idAccesibilidad WHERE idServiciosSalidasPack = :idServiciosSalidasPack ";


$comando = $pdo->prepare($consulta);
$comando->execute($data);
$cuenta_col = $comando->columnCount();
$resultado = $comando->rowCount();


return $resultado;

}





function borraSalida($idServicioSalidas){

    require("conexion.php");
       $datos=["idServicioSalidas"=> $idServicioSalidas];
         $consulta = "DELETE FROM servicio_salidas_adicionales WHERE idServicioSalidas=:idServicioSalidas ";
    $comando = $pdo->prepare($consulta);
    $comando->execute($datos);

     $consulta = "DELETE FROM servicio_salidas_idioma WHERE idServicioSalidas=:idServicioSalidas ";
    $comando = $pdo->prepare($consulta);
    $comando->execute($datos);

   $consulta = "DELETE FROM servicio_salidas_tarifas WHERE idServicioSalidas=:idServicioSalidas ";
    $comando = $pdo->prepare($consulta);
    $comando->execute($datos);


  $consulta = "DELETE FROM servicio_salidas WHERE idServicioSalidas=:idServicioSalidas ";
    $comando = $pdo->prepare($consulta);
    $comando->execute($datos);

 

        $cuenta_col = $comando->columnCount();
    $cuenta_row = $comando->rowCount();
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    return $cuenta_row;

}

/**
 * Obtiene las salidas de una fecha específica que tienen reservas creadas por vendedores
 * Si es admin (idUsuario = 1), muestra todas las salidas con reservas de vendedores
 * Si es vendedor, muestra solo las salidas con sus propias reservas
 */
function getSalidasVendedorFecha($fechaEspecifica) {
    require("conexion.php");
    
    $idUsuario = $_SESSION["login"]["idUsuario"];
    $fecha = date('Y-m-d', strtotime($fechaEspecifica));
    
    // Mostrar todas las salidas del día para admin y vendedores
    $consulta = "SELECT * FROM servicio_salidas 
                 WHERE fecha = :fecha
                 ORDER BY horaSalida ASC";
    $data = ["fecha" => $fecha];
    
    $comando = $pdo->prepare($consulta);
    $comando->execute($data);
    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    
    return $resultado;
}

/**
 * Obtiene las próximas salidas de un servicio con su disponibilidad
 * Usado para mostrar en tarjetas de servicios en categorias.php e index.php
 * 
 * @param $idServicio ID del servicio
 * @param $cantidadSalidas Cantidad de salidas a retornar (ej: 2 o 3)
 * @return array Array de salidas con disponibilidad
 */
function getProximasSalidasDisponibilidad($idServicio, $cantidadSalidas = 3) {
    require("conexion.php");
    
    $fechaHoy = date("Y-m-d");
    $data = ["idServicio" => $idServicio, "fechaHoy" => $fechaHoy];
    
    $consulta = "SELECT 
                    idServicioSalidas,
                    fecha, 
                    horaSalida,
                    disponibilidad,
                    disponibilidadOriginal,
                    lugaresOcupados,
                    anticipacionReserva
                 FROM servicio_salidas 
                 WHERE idServicio = :idServicio 
                 AND fecha >= :fechaHoy
                 AND disponibilidadOriginal > 0
                 ORDER BY fecha ASC 
                 LIMIT :limite";
    
    $comando = $pdo->prepare($consulta);
    $comando->bindParam(':idServicio', $idServicio, PDO::PARAM_INT);
    $comando->bindParam(':fechaHoy', $fechaHoy, PDO::PARAM_STR);
    $comando->bindParam(':limite', $cantidadSalidas, PDO::PARAM_INT);
    $comando->execute();
    
    return $comando->fetchAll(PDO::FETCH_ASSOC);
}

?>
