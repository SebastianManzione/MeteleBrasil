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
require("classes/prestador.php");
require("classes/comision_prestador.php");
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

$prestador=getPrestador($_SESSION['login']['idPrestador']);
$idPrestador=$_SESSION['login']['idPrestador'];
$comisiones=getComisionesPrestadorServicioIdPrestadorIdServicio($idServicio, $idPrestador);



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

      
       

                $comisionVenta=$comisiones[0]['comisionVendedor'];

              $comisionSalida=setComisionServicioSalidasTarifas($altaTarifa, $comisionVenta, 1);

           $comisionSistema=$comisiones[0]['comisionSistema'];

             $comisionSalida=setComisionServicioSalidasTarifas($altaTarifa,$comisionSistema, 2);

                       $comisionCompensatoria=$_POST['commision_compensatoria'];

               $comisionSalida=setComisionServicioSalidasTarifas($altaTarifa, $comisionCompensatoria, 3);





        

     }

    

}



}

 $_SESSION["idPackSalidas"]=$idPackSalidas;

 

 redireccionar("altaServiciosAdicionales");
exit();
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

                    <h1 class="m-0 text-dark"><?=$lang["carga_de_tarifas"];?></p><?= $servicio[0]['nombre_servicio']?></h1>



                </div><!-- /.col -->

                <div class="col-sm-6">

                    <ol class="breadcrumb float-sm-right">

                        <li class="breadcrumb-item"><a href="#"><?=$lang["inicio"];?></a></li>

                        <li class="breadcrumb-item active"><?=$lang["carga_de_tarifas"];?></li>

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

                        <h3 class="card-title"><?=$lang["nuevo_periodo_para"];?> <?= $servicio[0]['nombre_servicio']?> <img src="classes/imgServicio/<?=$fotos[0]['ruta']?>" style="width: 50px; border-radius: 100%;">

             

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

                                        <input name="nombre_periodo" class="form-control select2bs4" style="width: 100%;" value="(Enero, Febrero, Alta Temproada, Baja temporada) ">

                                    </div>

                                </div>  


                                <div class="col-md-12">

                               <div class="form-inline">   

                                        <label class="label-default"> <?=$lang["inicio_periodo"];?></label>

                                    <div class="form-group">

                                        <input class="form-control" name="inicio_periodo" type="date" value="<?= $hoy;?>" min="<?= $hoy;?>"> 

                                    </div>



                            

                                        <label class="label-default"> <?=$lang["final_periodo"];?></label>

                                   <div class="form-group">     

                                        <input class="form-control" name="fin_periodo" type="date" value="<?= $mesQueViene;?>" min="<?= $hoy;?>">

                                    </div>

                             

                               

                           </div>

                           <br>

                                <div class="col-md-6">

                                    <label class="label-default"><?=$lang["seleccione_los_dias_de_la_semana"];?></label>

                                    <div class="form-inline">

                                        <div class="form-checkbox">

                                            <span class="label label-default">

                                                <input type="checkbox" class="form-check-input" name="lun" value="1"> <?=$lang["lunes"];?> 

                                            </span>

                                        </div>

                                        <div class="form-checkbox">

                                            <span class="label-default">

                                                <input type="checkbox" class="form-check-input" name="mar" value="2" > <?=$lang["martes"];?> 

                                            </span>

                                        </div>

                                        <div class="form-checkbox">

                                            <span class="label-default">

                                                <input type="checkbox" class="form-check-input" name="mie" value="3" > <?=$lang["miercoles"];?> 

                                            </span>

                                        </div>

                                        <div class="form-checkbox">

                                            <span class="label-default">

                                                <input type="checkbox" class="form-check-input" name="jue" value="4" > <?=$lang["jueves"];?> 

                                            </span>

                                        </div>

                                        <div class="form-checkbox">

                                            <span class="label-default">

                                                <input type="checkbox" class="form-check-input" name="vie" value="5" > <?=$lang["viernes"];?> 

                                            </span>

                                        </div>

                                        <div class="form-checkbox">

                                            <span class="label-warning">

                                                <input type="checkbox" class="form-check-input" name="sab" value="6" checked="true">

                                                <strong> <?=$lang["sabado"];?> </strong>

                                            </span>

                                        </div>

                                        <div class="form-checkbox">

                                            <span class="label-warning">

                                                <input type="checkbox" class="form-check-input" name="dom" value="0" checked="true">

                                                <strong> <?=$lang["domingo"];?> </strong>

                                            </span>

                                        </div>

                                    </div>

                                </div>

<br>

<div class="col-12">
       <label><?=$lang["desea_agregar_una_palabra"];?></label><input type="checkbox" name="sinHorario">
       <p> <?=$lang["ejemplo_centro"];?> </p>

       <input type="text" name="sinHorarioTexto">
</div>


<br>


                                <div class="form-inline">

                                     <div class="form-group">

                                        <label for="appt" class="label label-warning"> <?=$lang["hora_de_salida_del_servicio"];?></label>

                                        <input type="time" id="appt" name="hSalida" class="form-control" min="00:00" max="24:00" value="10:00">


                                    </div>   

                                     <div class="form-group">

                                        <label for="appt" class="label label-warning"> <?=$lang["hora_de_llegada"];?></label>

                                        <input type="time" id="appt" name="hLlegada"  class="form-control" min="00:00" max="24:00" value="18:00">

                                    </div>

                                </div>

                               <br>

                            

                                <div class="col-md-6">

                                    <div class="form-group">

                                        <label class="label label-warning"><?=$lang["cuantos_lugares_disponibles_para_la_venta"];?></label>

                                        <input name="cantLugares" class="form-control select2bs4" style="width: 100%;" placeholder="DISPONIBILIDAD TOTAL DE PAX" value="40">

                                    </div>

                                </div>

                                <br>

                                <div class="col-md-6">

                                    <label><?=$lang["duracion_minima_de_la_actividad"];?></label>

                                    <div class="form-group  form-inline">

                                        <input type="number" step="0.01" name="duracionMinima" id="duracionMinima" class="form-control" placeholder="Duracion minima de la actividad: (Ejemplo: 3 horas)" style="width: 40%;" value="4">

                                        <label style="margin-left:5px"><?=$lang["minutos"];?></label>

                                        <input type="radio" style="margin-left:5px" name="modoHsDuracionMinima" value="minutos" >


                                        <label style="margin-left:5px"><?=$lang["horas"];?></label>

                                        <input type="radio" style="margin-left:5px" name="modoHsDuracionMinima" value="horas" checked>

                                        <label style="margin-left:5px"> <?=$lang["dias"];?></label>

                                        <input type="radio" style="margin-left:5px" name="modoHsDuracionMinima" value="dias">

                                    </div>

                                </div>




                                <div class="col-md-6">

                          

                                        <label><?=$lang["duracion_maxima"];?></label>
                                  
                                             <div class="form-group  form-inline">

                                        <input type="number" step="0.01" name="duracionMaxima" id="duracionMaxima" class="form-control" placeholder="Duracion maxima de la actividad: (Ejemplo: 3 dias)" style="width: 40%;" required value="4">
                                   <label style="margin-left:5px"><?=$lang["minutos"];?></label>
                                        

                                        <input type="radio" style="margin-left:5px" name="modoHsDuracionMaxima" value="minutos" >
<label style="margin-left:5px"> <?=$lang["horas"];?></label>
                                           
                                        <input type="radio" style="margin-left:5px" name="modoHsDuracionMaxima" value="horas" checked>
 <label style="margin-left:5px"> <?=$lang["dias"];?></label>

                                  

                                        <input type="radio" style="margin-left:5px" name="modoHsDuracionMaxima" value="dias">


                                    </div>

                                </div>



                                <div class="col-md-12">

                                    <div class="form-group">

                                        <label class="label label-warning"> <?=$lang["accesibilidad_"];?></label>

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

                                    <label><?=$lang["anticipacion_de_reserva"];?></label>

                                    <div class="form-group form-inline">
                                        
                                        
                                        
                                        <input type="number" step="0.01" name="horasAnticipacion" class="form-control"style="width: 65%;" value="4">

                                       <label style="margin-left:5px"><?=$lang["minutos"];?></label>

                                        <input type="radio" style="margin-left:5px" id="modoHsAnticipacion" name="modoHsAnticipacion" value="minutos" >

                                        <label style="margin-left:5px"><?=$lang["horas"];?></label> 

                                        <input type="radio" style="margin-left:5px" id="modoHsAnticipacion" name="modoHsAnticipacion" value="horas" checked>

                                       <label style="margin-left:5px"> <?=$lang["dias"];?></label>

                                        <input type="radio" style="margin-left:5px" id="modoDiasAnticipacion" value="dias" name="modoHsAnticipacion">

                                    </div>



                                <div class="col-md-6">

                                    <div class="form-group">

                                        <label class="label label-default"><?=$lang["indique_el_horario_de_check_in"];?></label>

                                        <input type="time" id="appt" name="horaCheckIn" class="form-control" min="09:00" max="18:00" value="09:55">

                                    </div>

                                </div>

                                <div class="col-md-6">

                                    <div class="form-group">

                                        <label class="label label-default"><?=$lang["idiomas_del_servicio"];?></label>



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

                                        <label class="label-default "><?=$lang["nota_de_salida"];?></label>

                                        <textarea name="nota_salida" class="form-control select2bs4" style="width: 100%;" placeholder="<?=$lang['escriba_detalles_y_topicos'];?>"></textarea>

                                    </div>

                                </div>



                                <div class="col-md-12">

                                    <div class="form-group">

                                        <label class="label label-default"><?=$lang["prestador"];?></label>
<?php if ($_SESSION["login"]["rol"]==1) {
?>
          <select name="selPrestador" id="selPrestador" class="form-control"  placeholder="Prestador">
<?php
$prestadores=getPrestadores();
 for ($i=0; $i < count($prestadores); $i++) { 
   
?>

 <option value="<?=$prestadores[$i]["idPrestador"];?>" selected><?=$prestadores[$i]["nombre"];?></option>
<?php
} ?>
                                           
                                                                          

                                        </select>
<?php
} else{

    ?>
          <select name="selPrestador" id="selPrestador" class="form-control"  placeholder="Prestador">

                                            <option value="<?=$prestador[0]["idPrestador"];?>" selected><?=$prestador[0]["nombre"];?></option>
                                                                          

                                        </select>

    <?php
} ?>



                                

                                    </div>

                                </div>

                                

 <div class="col-md-12">

                                    <div class="form-group">

                                            

                                        <label><?=$lang["moneda_del_servicio"];?></label>

                                        <p><?=$lang["indique_la_moneda"];?></p>

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



  


<div class="col-md-12" style="padding-top: 30px;" id="searchBoxDIV">

                                    <h5 >

                                        <label class="m-0 text-dark"><?=$lang["indique_el_punto_de_salida"];?></label>

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

                                        <label class="label-default "><?=$lang["direccion"];?></label>

                                        <input type="text" class="form-control" placeholder="Direccion" id="txtDireccion2" readonly>

                                        </div>

                                     <div class="form-group">

                                        <label class="label-default "><?=$lang["latitud"];?></label>

                                        <input type="text" name="txtLatitud" id="txtLatitud" class="form-control" placeholder="Latitud" readonly>

                                    </div>

                                        <div class="form-group">

                                        <label class="label-default "><?=$lang["longitud"];?></label>

                                        <input type="text" name="txtLongitud" id="txtLongitud" class="form-control" placeholder="Longitud" readonly>

                                    </div>

                                </div>

                          </div>

                           <hr color="green" size=0.5 width="100%"> 

                                <div class="col-md-12">

                                    <label class="control-label"><?=$lang["tipos_de_pax"];?></label>

                                    <div class="table-responsive">



                                        <table class="table table-bordered table-striped table-hover" id="tipos_pax_table" style="width:100%">

                                            <thead>

                                                <tr>

                                                      <th style="width: 20%;"><?=$lang["origen"];?></th>

                                                    <th ><?=$lang["nombre_tarifa"];?></th>

                                                    <th ><?=$lang["desde_anos"];?></th>

                                                    <th ><?=$lang["hasta_anos"];?></th>

                                                    <th style="width: 20%;"> <?=$lang["tipo_de_tarifa"];?></th>

                                                    <th > <?=$lang["precio_"];?></th> 

                                                    <th > <?=$lang["pago_minimo"];?></th>

                                                    <th > <?=$lang["tipo_de_cancelacion"];?></th>

                                                     <th > <?=$lang["comision"];?></th>

                                                    <th > </th>





                                                </tr>

                                            </thead>

                                            <tbody id="rate_table_body" id="tipos_pax_body">

															<tr>
                                                                <td>
                                                                     <input type="text" name="txtDireccion" id="txtDireccion" class="form-control" placeholder="Origen" readonly></input>
                                                                </td>

                                                                <td> 

                                                                    <div class="form-group"> 
                                                                         <input type="text" class="form-control-sm" id="nombre_tarifa_txt" required/>
                                                                    </div>
                                                             </td>

                                                            <td> 
                                                                <div class="form-group"> 
                                                                         <select class="form-select" id="from_edades_txt" required>

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

                                                                    <select class="form-select" id="to_edades_txt" required>

                                                                      	<?php $edades=getEdades();

                                    

                                      for ($i=0; $i < count($edades); $i++) { 
                                            $selected="";
                                            if (($i+1)==count($edades)) {
                                                  $selected="selected";
                                            }

                                        

                                      ?>

                                          <option value="<?=$edades[$i]['idEdad'];?>" <?=$selected?> ><?=$edades[$i]['valor'];?></option><?php

                                         } ?>  

                                                                                                                                                        

                                                                    </select>

                                                                </div>

                                                            </td>

      														 <td>

                                                                <div class="form-group">

                                                                    <select class="form-select" id="tipo_tarifa">

                                                               <?php $tarifas=getTiposTarifas();

                                                         

                                    

                                        for ($i=0; $i < count($tarifas); $i++) { 
                                            if ($tarifas[$i]['idTipoTarifa']==2 || $tarifas[$i]['idTipoTarifa']==4) {
                                                // code...
                                            
                                        

                                      ?>

                                          <option value="<?=$tarifas[$i]['idTipoTarifa'];?>" ><?=$tarifas[$i]['nombre'];?></option><?php

                                         }} ?>                                            

                             </div>

                                                            </td>

     														 <td >

                                                                <div class="form-group">

                                                                    <input id="vTarifa" class="form-control-sm" required>

                                                                </div>

                                                            </td>



                                                             <td >

                                                                <div class="form-group">

                                                                    <input id="pago_minimo" class="form-control-sm" required>

                                                                </div>

                                                            </td>



                                                             <td >

                                    <div class="form-group">

                                        <select class="form-control" id="selCancelaciones" name="cancelaciones">

                                        <?php $cancelaciones=getTiposCancelaciones();

                                        for ($i=0; $i < count($cancelaciones); $i++) { 
                                                if ($cancelaciones[$i]['idCancelacion']==2) {
                                                    // code...
                                                
                                            ?>

              <option value="<?=$cancelaciones[$i]['idCancelacion']?>" > <?=$cancelaciones[$i]['texto']?></option>



                                        <?php  } }
                                        ?>       

                                      </select>

                                    </div>



                                                            </td>

                                                                      <td > 

                                                                <div class="form-group"> 

                                                                   

                                                                         <input type="checkbox" class="form-control readonly" id="comisiona" required checked readonly="true" onclick="return false;"/>

                                          

                                                                     

                                                                 

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

                                            <i class="fa fa-floppy-o" aria-hidden="true"></i> <?=$lang["guardar"];?>

                                        </button>

                                        <a href="index.php" class="btn btn-danger btn-sm">

                                            <i class="fa fa-times" aria-hidden="true"></i> <?=$lang["salir"];?>

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