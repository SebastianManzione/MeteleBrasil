<?php 
// Verificar permisos de acceso ANTES de cualquier salida
require_once(__DIR__ . "/classes/permisos.php");
require_once(__DIR__ . "/includes/permisos_helper.php");
$permisos = new PermisosManager($GLOBALS['pdo'], $_SESSION['login'] ?? []);
$permisos->verificarAcceso('salidasEditar');

include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");
require_once("classes/functions.php");
require_once("classes/categoria.php");
require_once("classes/texto_miniaturas.php");
require_once("classes/tipos_tarifa.php");
require_once("classes/cancelaciones.php");
require_once("classes/accesibilidad.php");
require_once("classes/idiomas.php");
require_once("classes/edades.php");
require_once("classes/salidas.php");
require_once("classes/tarifas.php");
require_once("classes/tarifas_ubicacion.php");
require_once("classes/salidas_comisiones.php");
require_once("classes/servicio_salidas_pack.php");
require_once("classes/servicio.php");
require_once("classes/reserva.php");
require("classes/fotos_servicio.php"); 


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["idServicioSalidas"]) ) {
    $idServicioSalidas=$_POST["idServicioSalidas"];
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["duracionMinima"])) {
    $duracionMinima=$_POST["duracionMinima"];

if(isset($_POST["modoHsDuracionMinima"]) && $_POST["modoHsDuracionMinima"]=="dias"){
    $duracionMinima=$_POST["duracionMinima"]*24;
}
if(isset($_POST["modoHsDuracionMinima"]) && $_POST["modoHsDuracionMinima"]=="minutos"){
    $duracionMinima=$_POST["duracionMinima"]/60;
}
    $duracionMaxima=$_POST["duracionMaxima"];
if(isset($_POST["modoHsDuracionMaxima"]) && $_POST["modoHsDuracionMaxima"]=="dias"){
    $duracionMaxima=$_POST["duracionMaxima"]*24;
}

    if(isset($_POST["modoHsDuracionMaxima"]) && $_POST["modoHsDuracionMaxima"]=="minutos"){
        $duracionMaxima=$_POST["duracionMaxima"]/60;
}

$horasAnticipacion=$_POST["horasAnticipacion"];

if(isset($_POST["modoHsAnticipacion"]) && $_POST["modoHsAnticipacion"]=="dias"){
    $horasAnticipacion=$_POST["horasAnticipacion"]*24;
}

if(isset($_POST["modoHsAnticipacion"]) && $_POST["modoHsAnticipacion"]=="minutos"){
    $horasAnticipacion=$_POST["horasAnticipacion"]/60;
}

    $nombre_periodo=$_POST["nombre_periodo"];
	$nota_salida=$_POST["nota_salida"];
     $sinHorario=0;

if (isset($_POST["sinHorario"]) && $_POST["sinHorario"]=="on") {
    $sinHorario=1;
}

$idServicioSalidas=$_POST['idServicioSalidas'];
$sinHorarioTexto=$_POST["sinHorarioTexto"];
$fechaSalida=$_POST['fechaSalida'];
$horaSalida=$_POST['horaSalida'];
$horaCheckIn=$_POST['horaCheckIn'];
$cantLugares=$_POST['cantLugares'];
$horasAnticipacion=$_POST['horasAnticipacion'];
$nota_salida=$_POST['nota_salida'];
$accesibilidad=$_POST['accesibilidad'];

  	$idServicioSalidasResu=  updateSalida($idServicioSalidas, $nombre_periodo,$fechaSalida, $horaSalida, $horaCheckIn, $horasAnticipacion, $duracionMinima, $duracionMaxima,$cantLugares, $nota_salida, $accesibilidad);



}





$salida=getSalida($idServicioSalidas);
$idServicio=$salida[0]['idServicio'];
$nombre_periodo=$salida[0]['nombre'];
$fecha_salida=$salida[0]['fecha'];
$hora_salida=$salida[0]['horaSalida'];
$hora_check_in=$salida[0]['horaCheckIn'];
$anticipacionReserva=$salida[0]['anticipacionReserva'];
$anticipacionMinutos='';
$anticipacionHoras='';
$anticipacionDias='';
$tarifas=getTarifas($idServicioSalidas);

if ($anticipacionReserva<24 && $anticipacionReserva>=1) {

    $anticipacionHoras="checked";

 

}

if ($anticipacionReserva>=24) {

    $anticipacionDias='checked';

    $anticipacionReserva=$anticipacionReserva/24;

    

}

if ($anticipacionReserva<1) {

    $anticipacionMinutos='checked';

    $anticipacionReserva=$anticipacionReserva*60;



}





$duracionMinima=$salida[0]['duracionMinima'];

$minutosMinima='';

$horasMinima='';

$diasMinima='';

if ($duracionMinima<24 && $duracionMinima>=1) {

    $horasMinima="checked";

 

}

if ($duracionMinima>24) {

    $diasMinima='checked';

       $duracionMinima=$duracionMinima/24;

    

}

if ($duracionMinima<1) {

    $minutosMinima='checked';

    $duracionMinima=$duracionMinima*60;



}



$duracionMaxima=$salida[0]['duracionMaxima'];

$minutosMaxima='';

$horasMaxima='';

$diasMaxima='';

if ($duracionMaxima<24 && $duracionMaxima>=1) {

    $horasMaxima="checked";

 

}

if ($duracionMaxima>=24) {

    $diasMaxima='checked';

        $duracionMaxima=$duracionMaxima/24;

    

}

if ($duracionMaxima<1) {

    $minutosMaxima='checked';

        $duracionMaxima=$duracionMaxima*60;



}





$idAccesibilidad=$salida[0]['idAccesibilidad'];



$disponibilidad=$salida[0]['disponibilidad'];

$disponibilidadOriginal=$salida[0]['disponibilidadOriginal'];


$cantidadPasajeros=getCantidadPasajerosIdReservaTarifas($idServicioSalidas);


     



$asientosReservados=$disponibilidadOriginal-$disponibilidad;

$idMoneda=$salida[0]['idMoneda'];



$nota_salida=$salida[0]['nota_salida'];







$servicio=getServicio($idServicio);





$fotos=getFotosServicio($idServicio);

























$hoy= date("Y-m-d");



$mesQueViene= date("Y-m-d",strtotime($hoy."+ 1 month")); 

































 ?>



 <!-- Content Wrapper. Contains page content -->



<div class="content-wrapper">



    <!-- Content Header (Page header) -->



    <div class="content-header">



        <div class="container-fluid">



            <div class="row mb-2">



                <div class="col-sm-6">



                    <h1 class="m-0 text-dark"><?=$lang["carga_de_tarifas"];?> <?= $servicio[0]['nombre_servicio']?></h1>







                </div><!-- /.col -->



                <div class="col-sm-6">



                    <ol class="breadcrumb float-sm-right">



                        <li class="breadcrumb-item"><a href="#"> <?=$lang["inicio"];?></a></li>



                        <li class="breadcrumb-item active">Editor de Saída</li>



                    </ol>



                </div><!-- /.col -->



            </div><!-- /.row -->



        </div><!-- /.container-fluid -->



    </div>



    <section class="content">



        <div class="container-fluid">



            <!-- SELECT2 EXAMPLE -->



            <!-- SELECT2 EXAMPLE -->



 



            <div class="card card-default">



                <form method="post" id="altaSalidas2" enctype="multipart/form-data" autocomplete="off">



                  <input type="hidden" name="idServicioSalidas" value="<?=$idServicioSalidas;?>">



                    <div class="card-header">



                        <h3 class="card-title"> <?=$lang["nombre_del_periodo"];?><?= $servicio[0]['nombre_servicio']?> <img src="classes/imgServicio/<?=$fotos[0]['ruta']?>" style="width: 50px; border-radius: 100%;">



             



                        </h3>



                            <div class="card-tools">



                                <button type="button" class="btn btn-tool" data-card-widget="collapse">



                                    <i class="fas fa-minus"></i>



                                </button>



                                <button type="button" class="btn btn-tool" data-card-widget="remove">



                                    <i class="fas fa-remove"></i>



                                </button>



                            </div>



                    </div>



                    <!-- /.card-header -->



                    <div class="card-body">



                            <div class="row clearfix">



                                <div class="col-md-12">



                                    <div class="form-group">



                                         <label class="label-default"> <?=$lang["nombre_del_periodo"];?></label>



                                        <p><?=$lang["indique_el_nombre_del_periodo"];?></p>



                                        <input name="nombre_periodo" class="form-control select2bs4" style="width: 100%;" value="<?=$nombre_periodo?>">



                                    </div>



                                </div>  <div class="col-md-12">



                               <div class="form-inline">



                            



                            <div class="form-group">



                                        <label class="label-default"> Data da Saída</label>



                                        <input class="form-control" name="fechaSalida" type="date" value="<?= $fecha_salida;?>" >

                                       

                                    </div>



                             



                               



                           </div>

<?php

$sinHorario=$salida[0]['sinHorario'];

$sinHorarioTexto='';

if ($salida[0]['sinHorario']==1) {

    $sinHorario='selected';

    $sinHorarioTexto=$salida[0]['sinHorarioTexto'];

}



?>

               

<div class="col-12">

    <label><?=$lang["desea_agregar_una_palabra"];?></label>

       <p> <?=$lang["ejemplo_centro"];?> </p>

       <label>¿Agregar palavra?</label><input type="checkbox" name="sinHorario" <?=$sinHorario?>>

       <input type="text" name="sinHorarioTexto" value="<?=$sinHorarioTexto?>">

</div>

                                <div class="form-inline">

<div class="form-group">

       

</div>

                                     <div class="form-group">



                                        <label for="appt" class="label label-warning"> <?=$lang["hora_de_salida_del_servicio"];?> </label>



                                        <input type="time" id="appt" name="horaSalida" class="form-control" min="00:00" max="24:00" value="<?=$hora_salida;?>">





                       



                                    <div class="form-group">



                                        <label class="label label-default"><?=$lang["indique_el_horario_de_check_in"];?></label>



                                        <input type="time" id="appt" name="horaCheckIn" class="form-control" value="<?=$hora_check_in;?>">



                                    </div>



                     



                                    </div>   



                        

                                </div>



                               



                            



                                <div class="col-md-6">



                                    <div class="form-group">



                                        <label class="label label-warning"><?=$lang["cuantos_lugares_disponibles_para_la_venta"];?></label>



                                        <input name="cantLugares" class="form-control select2bs4" type="number" step="1" style="width: 100%;" placeholder="DISPONIBILIDAD TOTAL DE PAX" value="<?=$disponibilidad;?>" > Reservados: <?= $cantidadPasajeros;?>



                                    </div>



                                </div>



                                <div class="col-md-6">



                                    <label><?=$lang["duracion_minima_de_la_actividad"];?></label>



                                    <div class="form-group  form-inline">



                                        <input type="number" step="1" name="duracionMinima" id="duracionMinima" class="form-control" placeholder="Duracion minima de la actividad: (Ejemplo: 3 horas)" style="width: 40%;" value="<?=$duracionMinima?>" >



                                        <label style="margin-left:5px">Minutos</label>



                                        <input type="radio" style="margin-left:5px" name="modoHsDuracionMinima" value="minutos" <?=$minutosMinima?>>





                                        <label style="margin-left:5px">Horas</label>



                                        <input type="radio" style="margin-left:5px" name="modoHsDuracionMinima" value="horas" <?=$horasMinima?>>



                                        <label style="margin-left:5px"> Días</label>



                                        <input type="radio" style="margin-left:5px" name="modoHsDuracionMinima" value="dias" <?=$diasMinima?>>



                                    </div>



                                </div>



                                <div class="col-md-6">



                                    <div class="form-group  form-inline">



                                        <label><?=$lang["duracion_maxima"];?></label>



                                        <div class="form-group  form-inline">

                                    

                                   



                                        <input type="number" step="1" name="duracionMaxima" id="duracionMaxima" class="form-control" placeholder="Duracion maxima de la actividad: (Ejemplo: 3 dias)" style="width: 40%;" required value="<?=$duracionMaxima?>">

                                   <label style="margin-left:5px">Minutos</label>

                                        



                                        <input type="radio" style="margin-left:5px" name="modoHsDuracionMaxima" value="minutos" <?=$minutosMaxima?>>

<label style="margin-left:5px">Horas</label>

                                           

                                        <input type="radio" style="margin-left:5px" name="modoHsDuracionMaxima" value="horas" <?=$horasMaxima?>>

 <label style="margin-left:5px"> Días</label>



                                  



                                        <input type="radio" style="margin-left:5px" name="modoHsDuracionMaxima" value="dias" <?=$diasMaxima;?>>



                                    </div>



                                    </div>



                                </div>







                                <div class="col-md-12">



                                    <div class="form-group">



                                        <label class="label label-warning"> <?=$lang["accesibilidad_"];?></label>



                                       



                                        	<?php 



                                        	$accesiblilidades=getAccesibilidades(); 



?>

 <select class="form-control" name="accesibilidad">

<?php

                                        	for ($i=0; $i < count($accesiblilidades); $i++) {

                                                $selectedd='';

                                                if ($accesiblilidades[$i]['idAccesibilidad']==$idAccesibilidad) {

                                                    $selectedd='selected';

                                                }

                                             ?>



                                        		<option value="<?=$accesiblilidades[$i]['idAccesibilidad'];?>" <?=$selectedd?>><?=$accesiblilidades[$i]['texto'];?></option>



                                        	<?php }?>



                                        



                                        </select>



                                    </div>



                                </div>



                            







                                                                <!-- /.col -->



                               



                                <div class="col-md-12">



                                    <label><?=$lang["anticipacion_de_reserva"];?></label>



                                    <div class="form-group form-inline">

                                        

                                        

                                        

                                        <input type="number" step="1" name="horasAnticipacion" class="form-control"style="width: 65%;">



                                       <label style="margin-left:5px">Minutos</label>



                                        <input type="radio" style="margin-left:5px" id="modoHsAnticipacion" name="modoHsAnticipacion" value="minutos" <?=$anticipacionMinutos;?>>



                                        <label style="margin-left:5px">Horas</label> 



                                        <input type="radio" style="margin-left:5px" id="modoHsAnticipacion" name="modoHsAnticipacion" value="horas" <?=$anticipacionHoras;?>>



                                       <label style="margin-left:5px"> Días</label>



                                        <input type="radio" style="margin-left:5px" id="modoDiasAnticipacion" value="dias" name="modoHsAnticipacion" <?=$anticipacionDias;?>>



                                    </div>





<div class="col-md-12">

    <div class="form-group">

        <label class="label-default">Tarifas</label>

        <table class="table table-bordered table-striped">

            <thead>

               <tr>

                    <th>Nome da Tarifa</th>

                    <th>Desde Idade</th>

                    <th>Até Idade</th>

                    <th>Tipo Tarifa</th>

                    <th>Valor</th>

                    <th>Minimo</th>

                    <th>Cancelamento</th>

                    <th>comissão</th>

                    <th>Ação</th>

               </tr>

            </thead>

            <tbody>

                <?php



                 for ($i=0; $i < count($tarifas); $i++) {

                    $idServicioSalidasTarifas=$tarifas[$i]['idServicioSalidasTarifas'];

                     $edadFrom=getEdad($tarifas[$i]['idFromEdad'])[0]["valor"];

                     $edadTo=getEdad($tarifas[$i]['idToEdad'])[0]["valor"];

                     $idTipoTarifa=$tarifas[$i]['idTipoTarifa'];

                     $tipo_tarifa=getTipoTarifa($idTipoTarifa)[0]['nombre'];

                        $valor=$tarifas[$i]['valor'];

                         $minimo=$tarifas[$i]['minimo'];

                     $tipo_cancelacion=getTipoCancelaciones($tarifas[$i]["idCancelaciones"]);

                        $comisiona="no";

                     if ($tarifas[$i]['comisiona']==1) {

                         $comisiona="si";

                     }

                   ?>

    <tr>

                     <td><?=$tarifas[$i]['nombre']?></td>

                    <td><?=$edadFrom;?></td>

                    <td><?=$edadTo;?></td>

                    <td><?=$tipo_tarifa;?></td>

                    <td><?=$valor;?></td>

                    <td><?=$minimo?></td>

                    <td><?=$tipo_cancelacion[0]['texto'];?></td>

                    <td><?=$comisiona;?></td>

                    <td>

                        <a href="tarifasEditor?idServicioSalidasTarifas=<?=$idServicioSalidasTarifas;?>" class="btn-sm btn-info">Editar</a>

                   

                    </td>

                </tr>





                   <?php

                } ?>

            

            </tbody>

        </table>

    </div>

</div>

                      



                              <div class="col-md-12">



                                    <div class="form-group">



                                        <label class="label-default "><?=$lang["nota_de_salida"];?></label>



                                        <textarea name="nota_salida" class="form-control select2bs4" style="width: 100%;" placeholder=""><?=$nota_salida;?></textarea>



                                    </div>



                                </div>



             

                               



                                    



 <hr color="green" size=0.5 width="100%"> 







                            <!-- /.row -->



                    </div>



                    <!-- /.card-body -->



                    <div class="card-footer">



                        <div clas="container">



                            <div clas="row clearfix">



                                <div class="col-md-8">



                                    <div align="left">



                                        



                                    </div>



                                </div>



                                <div clas="col-md-4">



                                    <div align="right">



  



                </form>



                                        <button type="submit" onclick="upload();" value="Crear servicio"



                                            class="btn btn-success btn-sm">



                                         <i class="fas fa-save"></i> <?=$lang["guardar"];?>



                                        </button>

                                       <form method="post" action="salidaVer.php"><button class="btn-sm btn-info" name="idServicioSalidas" value="<?=$idServicioSalidas?>"><i class="fas fa-arrow-left"></i>  <?=$lang["salir"];?></button></form>



                                    </div>



                                </div>



                            </div>



                        </div>



                    </div>



            </div>



        </div>



    </section>



</div>



<script type="text/javascript">



    function upload(){



        document.getElementById("formAltaSalidas").submit();







    }



	$("#formAltaSalidas").submit(function(e){



    e.preventDefault();



  });



	



</script>



<?php include "includes/footer.php";?>
