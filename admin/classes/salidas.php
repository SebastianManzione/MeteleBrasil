<?php



function getSalidas(){



    require("conexion.php");

  

    $consulta = "select * from servicio_salidas";

    

    $comando = $pdo->prepare($consulta);

    

    $comando->execute();

    $cuenta_col = $comando->columnCount();

    

    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

    // Imprimir en pantalla

    return $resultado;

    

    

    }



    function getSalida($idServicioSalidas){



    require("conexion.php");

    $data=["idServicioSalidas"=>$idServicioSalidas];

    $consulta = "select * from servicio_salidas WHERE idServicioSalidas=:idServicioSalidas";

    

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

    $data=["idServicio"=>$idServicio];

    $consulta = "select * from servicio_salidas WHERE idServicio=:idServicio ";

    

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


   function getSalidasFechaLuegoIdServicio($fecha,$idServicio){



    require("conexion.php");

    $data=["idServicio"=>$idServicio,"fecha"=>$fecha];

    $consulta = "select * from servicio_salidas WHERE idServicio=:idServicio AND fecha >= :fecha";

    

    $comando = $pdo->prepare($consulta);

    

    $comando->execute($data);

    $cuenta_col = $comando->columnCount();

    

    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

    // Imprimir en pantalla
$retorno=array();
for ($i=0; $i < count($resultado); $i++) { 
  //print_r($resultado[$i]);

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

    // Imprimir en pantalla

    return $resultado;

    

    

    }



   function GetEventosArray($idServicio){





    require("conexion.php");

    $fecha=date("Y-m-d");

    $data=["idServicio"=>$idServicio, "fecha"=>$fecha];

    $consulta = "select * from servicio_salidas WHERE idServicio=:idServicio AND fecha >= :fecha";

    

    $comando = $pdo->prepare($consulta);

    

    $comando->execute($data);

    $cuenta_col = $comando->columnCount();

    

    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);







                 $hoy=date('Y-m-d');

                 $hoy= strtotime ( '+24 hour' ,   strtotime ($hoy) ) ; 

                 $nuevaFecha=date('Y-m-d', $hoy); //el dia de hoy + 24horas

                $eventos="";
$eventArray="";
    // Imprimir en pantalla

    for ($i=0; $i < count($resultado); $i++) { 
$anticipacionReserva=$resultado[$i]["anticipacionReserva"];
$horaSalida=$resultado[$i]["horaSalida"];
$fecha=$resultado[$i]["fecha"];

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
     $eventos=$eventos."

            {

             title: '".$resultado[$i]['idServicioSalidas']."', url: 'reservate!',

            date: '".date("Y-m-d", strtotime($resultado[$i]['fecha']))."',

            endDate: '".date("Y-m-d", strtotime($resultado[$i]['fecha']))."',

            startDate:'".date("Y-m-d", strtotime($resultado[$i]['fecha']))."'

        }, ";



         $eventArray="[". $eventos."]";
   

}

 

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

    // Imprimir en pantalla

    return $resultado;

    

    

    }



  function altaSalida($nombre,$idServicio, $fecha,$hSalida,$horaCheckIn,$anticipacionReserva,$duracionMinima, $duracionMaxima, $disponibilidad, $idAccesibilidad,$prestador, $idServiciosSalidasPack, $idMoneda, $nota_salida, $sinHorario, $sinHorarioTexto){





        require("conexion.php");

        $data=["nombre"=> $nombre, "idServicio"=>$idServicio, "fecha" => $fecha,"hSalida"=> $hSalida, "horaCheckIn"=>$horaCheckIn, "anticipacionReserva"=>$anticipacionReserva,"duracionMinima"=>$duracionMinima, "duracionMaxima"=>$duracionMaxima,  "disponibilidad" => $disponibilidad, "idAccesibilidad"=>$idAccesibilidad, "prestador" =>  $prestador, "idServiciosSalidasPack"=>$idServiciosSalidasPack, "idMoneda"=>$idMoneda, "nota_salida"=>$nota_salida, "sinHorario"=>$sinHorario, "sinHorarioTexto"=>$sinHorarioTexto];



        $consulta = "INSERT INTO servicio_salidas (nombre,idServicio, fecha, horaSalida, horaCheckIn, anticipacionReserva, duracionMinima, duracionMaxima,disponibilidadOriginal, disponibilidad,idAccesibilidad, idPrestador, idServiciosSalidasPack, idMoneda, nota_salida, sinHorario, sinHorarioTexto) VALUES (:nombre, :idServicio, :fecha, :hSalida, :horaCheckIn,:anticipacionReserva,:duracionMinima,:duracionMaxima, :disponibilidad,:disponibilidad,:idAccesibilidad, :prestador, :idServiciosSalidasPack, :idMoneda, :nota_salida, :sinHorario, :sinHorarioTexto) ";

        

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

"idServicioSalidas"=>$idServicioSalidas, "cantidadLugares"=>$cantidadLugares];

$consulta = "UPDATE servicio_salidas SET disponibilidad=disponibilidad - :cantidadLugares WHERE idServicioSalidas = :idServicioSalidas ";



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


function borraSalida($idServicioSalidas){

    require("conexion.php");
           $idServicioSalidas=$resultado[$i]["idServicioSalidas"];
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

      $consulta = "DELETE FROM servicio_salidas_tarifas WHERE idServicioSalidas=:idServicioSalidas ";
    $comando = $pdo->prepare($consulta);
    $comando->execute($datos);
 
 

    $comando = $pdo->prepare($consulta);

    

    $comando->execute($datos);
        $cuenta_col = $comando->columnCount();

    $cuenta_row = $comando->rowCount();

    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);
    return $resultado;
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