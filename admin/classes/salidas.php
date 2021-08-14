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





   function getSalidasFechaLuegoIdServicio($fecha,$idServicio){



    require("conexion.php");

    $data=["idServicio"=>$idServicio,"fecha"=>$fecha];

    $consulta = "select * from servicio_salidas WHERE idServicio=:idServicio AND fecha >= :fecha";

    

    $comando = $pdo->prepare($consulta);

    

    $comando->execute($data);

    $cuenta_col = $comando->columnCount();

    

    $resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

    // Imprimir en pantalla

    return $resultado;

    

    

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

    // Imprimir en pantalla

    for ($i=0; $i < count($resultado); $i++) { 

         $eventos=$eventos."

            {

             title: '".$resultado[$i]['idServicioSalidas']."', url: 'reservate!',

            date: '".date("Y-m-d", strtotime($resultado[$i]['fecha']))."',

            endDate: '".date("Y-m-d", strtotime($resultado[$i]['fecha']))."',

            startDate:'".date("Y-m-d", strtotime($resultado[$i]['fecha']))."'

        }, ";



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



   echo "\nPDO::errorInfo():\n";
    print_r($comando->errorInfo());



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