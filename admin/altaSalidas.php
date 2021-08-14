<?php 



include("includes/header.php");

include("includes/navbar.php");

include("includes/sidebar.php");

require("classes/functions.php");

require("classes/categoria.php");

require("classes/texto_miniaturas.php");

require("classes/tipos_tarifa.php");

require("classes/cancelaciones.php");

require("classes/accesibilidad.php");

require("classes/idiomas.php");



require("classes/edades.php");

require("classes/salidas.php");

require("classes/tarifas.php");

require("classes/tarifas_ubicacion.php");

require("classes/salidas_comisiones.php");

require("classes/servicio_salidas_pack.php");

require("classes/servicio.php");

require("classes/fotos_servicio.php"); //$moneda=getMoneda($_SESSION["nuevoServicio"]["selMoneda"]);





if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["idServicio"]) ) {

$idServicio=$_POST["idServicio"];





}

else if(isset($_SESSION["altaServicio"])){

$idServicio=$_SESSION["altaServicio"];

}

else{

redireccionar("serviciosLista");

}

$servicio=getServicio($idServicio);

$idServicio=$idServicio;

$fotos=getFotosServicio($idServicio);





if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["duracionMinima"])) {

	   $idMoneda=$_POST["selMoneda"];

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




	$cantDias=calculaDias($_POST["inicio_periodo"],$_POST["fin_periodo"]);



 $idPackSalidas=altaSalidasPack();



for ($i=0; $i < $cantDias; $i++) { 

  $fechaSalida=SumaFecha($_POST["inicio_periodo"], $i);

  $diaSemana=retornaDiaSemana($fechaSalida);

$cantLugares=$_POST["cantLugares"];

$hSalida=$_POST["hSalida"];

$horaCheckIn=$_POST["horaCheckIn"];

$accesibilidad=$_POST["accesibilidad"];

$insert=false;



  if (isset($_POST["dom"]) && $diaSemana==0) {



    $insert=true;

  }

  if (isset($_POST["lun"]) && $diaSemana==1) {



    $insert=true;

  }

  if (isset($_POST["mar"]) && $diaSemana==2) {



    $insert=true;

  }

  if (isset($_POST["mie"]) && $diaSemana==3) {



    $insert=true;

  }

    if (isset($_POST["jue"]) && $diaSemana==4) {



    $insert=true;

  }

    if (isset($_POST["vie"]) && $diaSemana==5) {



    $insert=true;

  }

    if (isset($_POST["sab"]) && $diaSemana==6) {

 

    $insert=true;

  }

if ($insert) {

    $prestador=$_POST["selPrestador"];

    

    $nombre_periodo=$_POST["nombre_periodo"];

	$nota_salida=$_POST["nota_salida"];

 $sinHorario=0;
if (isset($_POST["sinHorario"]) && $_POST["sinHorario"]=="on") {
    $sinHorario=1;
    
}
$sinHorarioTexto=$_POST["sinHorarioTexto"];

  	$idServicioSalidas=  altaSalida($nombre_periodo,$idServicio, $fechaSalida,$hSalida,$horaCheckIn,$horasAnticipacion,$duracionMinima,$duracionMaxima,$cantLugares,$accesibilidad,$prestador, $idPackSalidas, $idMoneda, $nota_salida, $sinHorario, $sinHorarioTexto);

   $idiomas=$_POST["idiomas"];



  for ($k=0; $k < count($idiomas); $k++) { 

    $idiomaSalida= setIdiomaSalida($idServicioSalidas, $idiomas[$k]);

  }

 



     for ($j=0; $j <  count($_POST['txtDireccion']); $j++) { 

         

         $nombre=$_POST['nombre_tarifa'][$j];

         $idFromEdad=$_POST['from_edades'][$j];

         $idToEdad=$_POST['to_edades'][$j];

         $idTipoTarifa=$_POST['tipo_tarifa'][$j];

         $valor=$_POST['valor_tarifa'][$j];

         $minimo=$_POST['pago_minimo'][$j];

              

         $idCancelaciones=$_POST['selCancelaciones'][$j];

          $comisiona=$_POST['comisiona'][$j];

          $altaTarifa=altaTarifa($idServicioSalidas,$nombre, $idFromEdad,$idToEdad,$idTipoTarifa,$valor,$minimo, $idCancelaciones, $comisiona);

      

         $altaTarifaUbicacion= altaTarifaUbicacion($altaTarifa, $_POST['txtDireccion'][$j], $_POST['txtLatitud'][$j],  $_POST['txtLongitud'][$j]);

      

        

  $comisionVenta=$_POST['commision_venta'];

              $comisionSalida=setComisionServicioSalidasTarifas($altaTarifa, $comisionVenta, 1);

           $comisionSistema=$_POST['commision_sistema'];

             $comisionSalida=setComisionServicioSalidasTarifas($altaTarifa,$comisionSistema, 2);

          

             $comisionCompensatoria=$_POST['commision_compensatoria'];

               $comisionSalida=setComisionServicioSalidasTarifas($altaTarifa, $comisionCompensatoria, 3);





        

     }

    

}



}

 $_SESSION["idPackSalidas"]=$idPackSalidas;

 

 redireccionar("altaServiciosAdicionales");

}



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

                    <h1 class="m-0 text-dark">Carga de Tarifas <?= $servicio[0]['nombre_servicio']?></h1>



                </div><!-- /.col -->

                <div class="col-sm-6">

                    <ol class="breadcrumb float-sm-right">

                        <li class="breadcrumb-item"><a href="#">Home</a></li>

                        <li class="breadcrumb-item active">Carga de Tarifas</li>

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

                <form method="post" id="formAltaSalidas" enctype="multipart/form-data" autocomplete="off">

                  <input type="hidden" name="idServicio" value="<?=$idServicio;?>">

                    <div class="card-header">

                        <h3 class="card-title">Nuevo Periodo para <?= $servicio[0]['nombre_servicio']?> <img src="classes/imgServicio/<?=$fotos[0]['ruta']?>" style="width: 50px; border-radius: 100%;">

             

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

                                        <label class="label-default"> Nombre del Periodo</label>

                                        <input name="nombre_periodo" class="form-control select2bs4" style="width: 100%;" value="(Enero, Febrero, Alta Temproada, Baja temporada) ">

                                    </div>

                                </div>  <div class="col-md-12">

                               <div class="form-inline">

                                 <div class="form-group">

                                        <label class="label-default"> Inicio del periodo</label>

                                        <input class="form-control" name="inicio_periodo" type="date" value="<?= $hoy;?>" min="<?= $hoy;?>"> 

                                    </div>

                            <div class="form-group">

                                        <label class="label-default"> Final del periodo</label>

                                        <input class="form-control" name="fin_periodo" type="date" value="<?= $mesQueViene;?>" min="<?= $hoy;?>">

                                    </div>

                             

                               

                           </div>

                                <div class="col-md-6">

                                    <label class="label-default">Seleccione los dias de la Semana que se realiza la actividad</label>

                                    <div class="form-inline">

                                        <div class="form-checkbox">

                                            <span class="label label-default">

                                                <input type="checkbox" class="form-check-input" name="lun" value="1" > Lun 

                                            </span>

                                        </div>

                                        <div class="form-checkbox">

                                            <span class="label-default">

                                                <input type="checkbox" class="form-check-input" name="mar" value="2" > Mar 

                                            </span>

                                        </div>

                                        <div class="form-checkbox">

                                            <span class="label-default">

                                                <input type="checkbox" class="form-check-input" name="mie" value="3" > Mie 

                                            </span>

                                        </div>

                                        <div class="form-checkbox">

                                            <span class="label-default">

                                                <input type="checkbox" class="form-check-input" name="jue" value="4" > Jue 

                                            </span>

                                        </div>

                                        <div class="form-checkbox">

                                            <span class="label-default">

                                                <input type="checkbox" class="form-check-input" name="vie" value="5" > Vie 

                                            </span>

                                        </div>

                                        <div class="form-checkbox">

                                            <span class="label-warning">

                                                <input type="checkbox" class="form-check-input" name="sab" value="6" checked="true">

                                                <strong> Sab </strong>

                                            </span>

                                        </div>

                                        <div class="form-checkbox">

                                            <span class="label-warning">

                                                <input type="checkbox" class="form-check-input" name="dom" value="0" checked="true">

                                                <strong> Dom </strong>

                                            </span>

                                        </div>

                                    </div>

                                </div>
<div class="col-12">
       <label>¿Sin horario?</label><input type="checkbox" name="sinHorario">
       <input type="text" name="sinHorarioTexto">
</div>
                                <div class="form-inline">
<div class="form-group">
       
</div>
                                     <div class="form-group">

                                        <label for="appt" class="label label-warning"> Hora de Salida del Servico: </label>

                                        <input type="time" id="appt" name="hSalida" class="form-control" min="00:00" max="24:00" value="10:00">


                                    </div>   

                                     <div class="form-group">

                                        <label for="appt" class="label label-warning"> Hora de Llegada del Servicio: </label>

                                        <input type="time" id="appt" name="hLlegada"  class="form-control" min="00:00" max="24:00" value="18:00">

                                    </div>

                                </div>

                               

                            

                                <div class="col-md-6">

                                    <div class="form-group">

                                        <label class="label label-warning">¿Cuantos lugares disponibles para la venta tiene esta Salida?</label>

                                        <input name="cantLugares" class="form-control select2bs4" style="width: 100%;" placeholder="DISPONIBILIDAD TOTAL DE PAX" value="40">

                                    </div>

                                </div>

                                <div class="col-md-6">

                                    <label>Duración mínima de la actividad</label>

                                    <div class="form-group  form-inline">

                                        <input type="number" step="0.01" name="duracionMinima" id="duracionMinima" class="form-control" placeholder="Duracion minima de la actividad: (Ejemplo: 3 horas)" style="width: 40%;" value="4">

                                        <label style="margin-left:5px">Minutos</label>

                                        <input type="radio" style="margin-left:5px" name="modoHsDuracionMinima" value="minutos" >


                                        <label style="margin-left:5px">Horas</label>

                                        <input type="radio" style="margin-left:5px" name="modoHsDuracionMinima" value="horas" checked>

                                        <label style="margin-left:5px"> Días</label>

                                        <input type="radio" style="margin-left:5px" name="modoHsDuracionMinima" value="dias">

                                    </div>

                                </div>

                                <div class="col-md-6">

                                    <div class="form-group  form-inline">

                                        <label>Duración máxima de la actividad</label>

                                        <div class="form-group  form-inline">
                                    
                                   

                                        <input type="number" step="0.01" name="duracionMaxima" id="duracionMaxima" class="form-control" placeholder="Duracion maxima de la actividad: (Ejemplo: 3 dias)" style="width: 40%;" required value="4">
                                   <label style="margin-left:5px">Minutos</label>
                                        

                                        <input type="radio" style="margin-left:5px" name="modoHsDuracionMaxima" value="minutos" >
<label style="margin-left:5px">Horas</label>
                                           
                                        <input type="radio" style="margin-left:5px" name="modoHsDuracionMaxima" value="horas" checked>
 <label style="margin-left:5px"> Días</label>

                                  

                                        <input type="radio" style="margin-left:5px" name="modoHsDuracionMaxima" value="dias">

                                    </div>

                                    </div>

                                </div>



                                <div class="col-md-12">

                                    <div class="form-group">

                                        <label class="label label-warning"> Accesibilidad</label>

                                        <select class="form-control" name="accesibilidad">

                                        	<?php 

                                        	$accesiblilidades=getAccesibilidades(); 

                                        	for ($i=0; $i < count($accesiblilidades); $i++) { ?>

                                        		<option value="<?=$accesiblilidades[$i]['idAccesibilidad'];?>"><?=$accesiblilidades[$i]['texto'];?></option>

                                        	<?php }?>

                                        

                                        </select>

                                    </div>

                                </div>

                            



                                                                <!-- /.col -->

                               

                                <div class="col-md-12">

                                    <label>Anticipación de reserva</label>

                                    <div class="form-group form-inline">
                                        
                                        
                                        
                                        <input type="number" step="0.01" name="horasAnticipacion" class="form-control"style="width: 65%;" value="4">

                                       <label style="margin-left:5px">Minutos</label>

                                        <input type="radio" style="margin-left:5px" id="modoHsAnticipacion" name="modoHsAnticipacion" value="minutos" >

                                        <label style="margin-left:5px">Horas</label> 

                                        <input type="radio" style="margin-left:5px" id="modoHsAnticipacion" name="modoHsAnticipacion" value="horas" checked>

                                       <label style="margin-left:5px"> Días</label>

                                        <input type="radio" style="margin-left:5px" id="modoDiasAnticipacion" value="dias" name="modoHsAnticipacion">

                                    </div>



                                <div class="col-md-6">

                                    <div class="form-group">

                                        <label class="label label-default">Indique el Horario de Check In:</label>

                                        <input type="time" id="appt" name="horaCheckIn" class="form-control" min="09:00" max="18:00" value="09:55">

                                    </div>

                                </div>

                                <div class="col-md-6">

                                    <div class="form-group">

                                        <label class="label label-default">Idiomas</label>  

                                        <select name="idiomas[]" id="idiomas"  class=" form-control"  multiple required>

                                        <?php $idiomas=getIdiomas();

                                    

                                        for ($i=0; $i < count($idiomas); $i++) { 

                                      ?>

                                          <option value="<?=$idiomas[$i]['idIdioma']?>" selected><?=$idiomas[$i]['nombre']?></option><?php

                                         } ?>                                                     

                                        </select>

                                    </div>

                                </div>

                              <div class="col-md-12">

                                    <div class="form-group">

                                        <label class="label-default ">Nota de salida</label>

                                        <textarea name="nota_salida" class="form-control select2bs4" style="width: 100%;" placeholder="">Escriba DETALLES y TÓPICOS a tener en cuenta de esta salida</textarea>

                                    </div>

                                </div>



                                <div class="col-md-12">

                                    <div class="form-group">

                                        <label class="label label-default">Prestador</label>

                                        <a style="" class="btn-sm btn-success" target="_blank" href="../admin/altaPrestador.php">Nuevo Prestador</a>

                                        <select name="selPrestador" id="selPrestador" onfocus="getPrestadores();" class="form-control"  placeholder="Prestador">

                                            <option value="0" selected="">Sin Prestador</option>

                                            <option value="0"  disabled=""></option>

                                            <option value="0"  disabled=""></option>

                                            <option value="0"  disabled=""></option>

                                            <option value="0"  disabled=""></option>

                                            

                                        </select>

                                    </div>

                                </div>

                                

 <div class="col-md-12">

                                    <div class="form-group">

                                            

                                        <label>Moneda Del Servicio</label>

                                        <select name="selMoneda" class="form-group form-control" id="selMoneda"

                                            placeholder="Moneda" required>

                                            	<?php $monedas=getMonedas();

                                    

                                        for ($i=0; $i < count($monedas); $i++) { 

                                        	$active="";

                                        	if ($monedas[$i]['idMoneda']==283) {

                                        		$active=" selected ";

                                        	}

                                      ?>

                                          <option value="<?=$monedas[$i]['idMoneda'];?>"  <?=$active;?>><?=$monedas[$i]['CurrencyISO'];?></option><?php

                                         } ?>    

                                                                         

                                                                                            </select>

                                    </div>

                                </div>



   <div class="col-md-12">

    <div class="form-inline">





                                       

                                     <div class="col-md-3"  style="width: 30%;">

                                   

                                        <label class="label label-default">Comisión Sistema %</label>

                                        <input type="number" name="commision_sistema" class="form-control select2bs4"   required>

                                  

                                </div>

                                <div class="col-md-3" style="width: 30%;">

                                  

                                        <label class="label label-default">Comisión de Venta %</label>

                                        <input type="number" name="commision_venta" class="form-control select2bs4"  required>

                                 

                                </div>

                                <div class="col-md-3"  style="width: 30%;">

                                    

                                        <label class="label label-default">Comisión Compensatoria %</label>

                                        <input type="number" name="commision_compensatoria" class="form-control select2bs4" required>

                                   

                                </div>

                                        </div>

                                        </div>

 



<div class="col-md-12" style="padding-top: 30px;" id="searchBoxDIV">

                                    <h5 >

                                        <label class="m-0 text-dark">Indique el Punto de Salida del Servicio</label>

                                    </h5>

                                    <div id='searchBoxContainer' class="form-group">

                                        <input id='searchBox' type='text' class="form-control " style="padding:5px" value="" autocomplete="off"/>

                                        <input type='hidden' value='Search' onclick='Search() ' class="btn btn-primary" />

                                    </div>

                                    <div class="col-md-12">

                                        <div id="myMap" style="position:relative;width:auto ;height:300px;float:center;">

                                        </div>

                                        <div id='output' style="margin-left:10px;float:left;"></div>

                                    </div>

                                </div>

                              <div class="col-md-12" style="margin-top: 20px;">

                                <div class="form-inline">

                                       <div class="form-group"> 

                                        <label class="label-default ">Dirección</label>

                                        <input type="text" class="form-control" placeholder="Direccion" id="txtDireccion2" readonly>

                                        </div>

                                     <div class="form-group">

                                        <label class="label-default ">Latitud</label>

                                        <input type="text" name="txtLatitud" id="txtLatitud" class="form-control" placeholder="Latitud" readonly>

                                    </div>

                                        <div class="form-group">

                                        <label class="label-default ">Longitud</label>

                                        <input type="text" name="txtLongitud" id="txtLongitud" class="form-control" placeholder="Longitud" readonly>

                                    </div>

                                </div>

                          </div>

                           <hr color="green" size=0.5 width="100%"> 

                                <div class="col-md-12">

                                    <label class="control-label">Tipos de Pax</label>

                                    <div class="table-responsive">



                                        <table class="table table-bordered table-striped table-hover" id="tipos_pax_table" style="width:100%">

                                            <thead>

                                                <tr>

                                                      <th style="width: 20%;">Origen</th>

                                                    <th >Nombre Tarifa</th>

                                                    <th >Desde Anos</th>

                                                    <th >Hasta Anos </th>

                                                    <th style="width: 20%;"> Tipo de Tarifa</th>

                                                    <th > Precio</th> 

                                                    <th > Pago Minimo</th>

                                                    <th > Tipo de Cancelacion</th>

                                                     <th > Comision?</th>

                                                    <th > </th>





                                                </tr>

                                            </thead>

                                            <tbody id="rate_table_body" id="tipos_pax_body">

															<tr>

                                                                <td>

                                                                     <input type="text" name="txtDireccion" id="txtDireccion" class="form-control" placeholder="Origen" readonly></input>

                                                                </td>

                                                                           <td > 

                                                                <div class="form-group"> 

                                                                   

                                                                         <input type="text" class="form-control" id="nombre_tarifa_txt" required/>

                                          

                                                                     

                                                                 

                                                                </div>

                                                             </td>

                                                            <td > 

                                                                <div class="form-group"> 

                                                                   

                                                                         <select class="form-control" id="from_edades_txt" required>

                                                                      	<?php $edades=getEdades();

                                    

                                        for ($i=0; $i < count($edades); $i++) { 

                                        

                                      ?>

                                          <option value="<?=$edades[$i]['idEdad'];?>" ><?=$edades[$i]['valor'];?></option><?php

                                         } ?>  

                                                                                                                                                        

                                                                    </select>      

                                                                 

                                                                </div>

                                                             </td>

        												           <td>

                                                                <div class="form-group">

                                                                    <select class="form-control" id="to_edades_txt" required>

                                                                      	<?php $edades=getEdades();

                                    

                                        for ($i=(count($edades)-1); $i > 0; $i--) { 

                                        

                                      ?>

                                          <option value="<?=$edades[$i]['idEdad'];?>" ><?=$edades[$i]['valor'];?></option><?php

                                         } ?>  

                                                                                                                                                        

                                                                    </select>

                                                                </div>

                                                            </td>

      														 <td>

                                                                <div class="form-group">

                                                                    <select class="form-control" id="tipo_tarifa">

                                                               <?php $tarifas=getTiposTarifas();

                                                         

                                    

                                        for ($i=0; $i < count($tarifas); $i++) { 

                                        

                                      ?>

                                          <option value="<?=$tarifas[$i]['idTipoTarifa'];?>" ><?=$tarifas[$i]['nombre'];?></option><?php

                                         } ?>                                            

                             </div>

                                                            </td>

     														 <td >

                                                                <div class="form-group">

                                                                    <input id="vTarifa" class="form-control select2bs4" required>

                                                                </div>

                                                            </td>



                                                             <td >

                                                                <div class="form-group">

                                                                    <input id="pago_minimo" class="form-control select2bs4" required>

                                                                </div>

                                                            </td>



                                                             <td >

                                    <div class="form-group">

                                        <select class="form-control" id="selCancelaciones" name="cancelaciones">

                                        <?php $cancelaciones=getTiposCancelaciones();

                                        for ($i=0; $i < count($cancelaciones); $i++) { ?>

              <option value="<?=$cancelaciones[$i]['idCancelacion']?>"> <?=$cancelaciones[$i]['texto']?></option>



                                        <?php  } ?>       

                                      </select>

                                    </div>



                                                            </td>

                                                                      <td > 

                                                                <div class="form-group"> 

                                                                   

                                                                         <input type="checkbox" class="form-control" id="comisiona" required/>

                                          

                                                                     

                                                                 

                                                                </div>

                                                             </td>



                                                            	 <td >

                                                                <div class="form-group">

                                                                    <button class="btn btn-success" onclick="addPax();">+</button>

                                                                </div>

                                                            </td>

                                                        	</tr>





    	<script type="text/javascript" src="js/addPax.js">

                                    		

                                    	</script>

                                                                                               

                                            </tbody>

                                        </table>

                                        <div  id="tipos_pax_div"><label></label></div>

                                    </div>

                                </div>

                                

                                <script type="text/javascript">

                                	            function getPrestadores() {

                                    	

                                        var parametros = {

                                            "getPrestadores": "-5"

                                        };

                                        $.post("./ctrl/ctrl_prestador.php", parametros,

                                            function(data, status) {



                                                data = JSON.parse(data);

                                                if (true) {

                                                    $("#selPrestador").empty();

                                                    $.each(data, function(key, value) {

                                                        $("#selPrestador").append('<option value="' + value[

                                                                "idPrestador"] + '">' + value["nombre"] +

                                                            '</option>');

                                                    }); // close each()



                                                }

                                            });

                                    }

                                </script>

                                <script type='text/javascript' src='https://www.bing.com/api/maps/mapcontrol?callback=GetMap&key=Aul14kGJkus4tWL4PAZly9XkZ14uTOQ9PbJ9fHG4lEFmQKmVPjR38_O0kDsRTuOv' async defer></script>



                                <script type='text/javascript'>

                                 

                                  

                   



                        



                                    function eliminar(valor) {

                                        valor.closest('tr').remove();

                                    }





                      

                                </script>



                                <script type="text/javascript" src="js/map.js"></script>

                                                                  

                                       

                                        <!-- <hr color="green" size=0.5 width="600"> -->

                                                                               

                                     

                                     

                                    

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

                                        <button type="submit" onclick="upload();" value="Crear servicio"

                                            class="btn btn-success btn-sm">

                                            <i class="fa fa-floppy-o" aria-hidden="true"></i>Guardar

                                        </button>

                                        <a href="index.php" class="btn btn-danger btn-sm">

                                            <i class="fa fa-times" aria-hidden="true"></i> Salir

                                        </a>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </form>

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