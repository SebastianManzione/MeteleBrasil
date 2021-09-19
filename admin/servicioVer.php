<?php 

setlocale(LC_TIME, "es_ES");


include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");
require("classes/functions.php");
require("classes/categoria.php");
require("classes/texto_miniaturas.php");
require("classes/tipos_tarifa.php");
require("classes/accesibilidad.php");
require("classes/idiomas.php");
require("classes/reserva.php");
require("classes/edades.php");
require("classes/salidas.php");
require("classes/tarifas.php");
require("classes/tarifas_ubicacion.php");
require("classes/prestador.php");
require("classes/servicio.php"); 
require("classes/cancelaciones.php"); 
require("classes/fotos_servicio.php"); 
require("classes/servicios_adicionales.php");
require("classes/convierte_monedas.php");


if ($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST["eliminarServicio"])) {

    alertar($lang["por_favor_espere_no_cierre"], "success");
   
$idServicio=$_POST["eliminarServicio"];
$reservas=getHorariosReservados_idServicioSeleccionado($idServicio);

if (count($reservas)>0) { //count($reservas)>0
foreach ($reservas as $key => $value) {
  $idReserva=$value['idReserva'];
  $reserva=getReservaId($idReserva);
 echo "Reserva con horario en este servicio: ".$reserva[0]["codigoAmigable"]."<br>";
}


 alertar($lang["no_se_puede_eliminar_servicios"], "error");
}
else{

  $resEliminar=eliminarServicio($idServicio);
  alertar($lang["servicio_eliminado"], "success");
  redireccionarLento("serviciosLista");
  exit();
}


}

if (isset($_GET["idServicio"])) {

  $idServicio=$_GET["idServicio"];

  $servicio=getServicio($idServicio)[0];

 $fotos=getFotoMiniaturaServicio($idServicio);

  $categoria=getCategoria($servicio["idCategoria_servicio"]);

}

 ?>









<div class="content-wrapper">  <!-- Content Wrapper. Contains page content -->

    

    <div class="content-header"> <!-- Content Header (Page header) -->

        <div class="container-fluid">

            <div class="row mb-2">

                <div class="col-sm-6">

                    <h1 class="m-0 text-dark"></h1>



                </div><!-- /.col -->

                <div class="col-sm-6">

                    <ol class="breadcrumb float-sm-right">

                        <li class="breadcrumb-item"><a href="#"><?=$lang["servicio"];?> <?=$servicio["nombre_servicio"];?></a></li>

                        <li class="breadcrumb-item active"></li>

                    </ol>

                </div><!-- /.col -->

            </div><!-- /.row -->

        </div><!-- /.container-fluid -->

    </div>













    <section class="content">



      <!-- Default box -->

      <div class="card">

        <div class="card-header">

          <h3 class="card-title"><?=$lang["servicio"];?> <?=$servicio["nombre_servicio"];?></h3>



          <div class="card-tools">

            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">

              <i class="fas fa-minus"></i></button>



          </div>

        </div>

        <div class="card-body">

          <div class="row">

            <div class="col-12 col-md-12 col-lg-8 order-2 order-md-1">

              <div class="row">

<?php 



for ($i=0; $i <  count($fotos); $i++) { 

 ?>

    <div class="col-12 col-sm-4">

                  <img class="info-box bg-light" src="classes/imgServicio/<?=$fotos[$i]["ruta"]?>" style="width: 150px;">

                    

                  

                </div> 

 <?php

} ?>

                          <!--Fotos -->  

              </div>





              <div class="row" >

                <div class="col-12">

                  

                    <div class="post">

                      <div class="user-block">

                        <span class="description"><?=$lang["fecha_alta"];?> <?=date("d-m-Y", strtotime($servicio["fechaAlta"]))?></span>

                      </div>

                      <!-- /.user-block -->

                                  <p>



                                        <?=$lang["descripcion_del_servicio"];?>



                                 </p>



                    </div>



                </div>

              </div>

            </div>







            <div class="col-12 col-md-12 col-lg-4 order-1 order-md-2" >

              <h3 class="text-primary"><i class="fas fa-paint-brush"></i> <?=$servicio["nombre_servicio"];?></h3>

              <p class="text-muted"><?=$servicio["descripcion_corta"];?></p>

              <br>

              <div class="text-muted">

                <p class="text-sm"><?=$lang["categoria_del_servicio"];?>

                  <b class="d-block"><?=$categoria[0]["nombre_categoria_servicio"]?></b>

                </p>

                <p class="text-sm"><?=$lang["observaciones"];?>

                  <b class="d-block"><?=$servicio["observaciones"];?></b>

                </p> 

            

              </div>



  

          

            </div>



  



          </div> <!-- /.row -->

        </div> <!-- /.card-body -->

        <div class="col-12 form-inline">

          <form method="post" action="altaSalidas" style="padding: 3px;"><button name="idServicio" value="<?=$idServicio;?>" class="btn btn-info"><?=$lang["agregar_salida"];?> </button></form>
<?php if ($_SESSION['login']["idUsuario"]==1) { ?>
         <form method="post" action="servicioOpiniones"  style="padding: 3px;">
          <button name="idServicio" value="<?=$idServicio;?>" class="btn btn-success"><?=$lang["opiniones"];?></button></form>
         <form method="get" action="altaServicio.php"  style="padding: 3px;">
<input type="hidden" name="idServicio"  value="<?=$idServicio;?>">
            <button  class="btn btn-primary"type="submit"><?=$lang["editar_servicio"];?></button></form>
       

<?php } ?>

<?php if ($_SESSION['login']["idUsuario"]==1) {
 ?>
<form method="post" action="servicioFotos"  style="padding: 3px;">
  <button type="submit" class="btn btn-success" name="idServicio" value="<?=$idServicio?>">Editor de fotos</button>
</form>

<form method="post" action="servicioComisionPrestador"  style="padding: 3px;">
  <button type="submit" class="btn btn-info" name="idServicio" value="<?=$idServicio?>"><?=$lang["comision_inicial_prestador"];?></button>
</form>

   <form method="post" id="borraServicio"   style="padding: 3px;">
<input type="hidden" name="eliminarServicio"  value="<?=$idServicio;?>">
            <a  class="btn btn-danger" name="eliminarServicio" value="<?=$idServicio;?>" onclick="confirm1()"><?=$lang["eliminar_servicio"];?></a></form>
 <?php
} ?>



<script type="text/javascript">

    function confirm1()
        {

Swal.fire({
  title: 'Esta seguro que desea borrar el servicio, salidas y tarifas?',

  showCancelButton: true,
  confirmButtonText: `Si, Borrar`,
  denyButtonText: `No`,
}).then((result) => {
  /* Read more about isConfirmed, isDenied below */
  if (result.isConfirmed) {
     $( "#borraServicio" ).submit();
  } else if (result.isDenied) {
    return false
  }
})
           return false     
        }
</script>



      </div>     

      </div>  <!-- /.card. -->





    </section> <!-- Main content -->







      <!-- Default box -->

                  <?php

if ($_SESSION['login']["idUsuario"]==1) {
  $salidas=getAllSalidasServicio($idServicio); 
}
else{
$salidas=getSalidasServicioIdPrestador($idServicio);
}
                   

          

                    for ($i=0; $i < count($salidas); $i++) { 

                    	$idMoneda=($salidas[$i]["idMoneda"]);

//print_r($salidas[$i]);

                    	$moneda=getMoneda($idMoneda);

                    $idServiciosSalidasPack=$salidas[$i]["idServiciosSalidasPack"];

                    $idServicioSalidas=$salidas[$i]["idServicioSalidas"];

                    $idiomas=getIdiomasSalida($idServicioSalidas);

                    if ($salidas[$i]["idPrestador"]>0) {

                     $prestador=getPrestador($salidas[$i]["idPrestador"])[0];

                    }

                    



                    ?>

            

              <section class="content">

      <div class="card collapsed-card"><!--collapsed-card_-->

        <div class="card-header">

          <h3 class="card-title"><?=$salidas[$i]["nombre"]?> | id Salida: <?=$salidas[$i]["idServicioSalidas"]?> |<?=strftime("%A", strtotime($salidas[$i]["fecha"]))?> <?=date("d-m-Y", strtotime($salidas[$i]["fecha"]))?> <?=$salidas[$i]["horaSalida"]?></h3>













          <div class="card-tools">

            <button class="btn btn-primary" type="button" data-card-widget="collapse" data-toggle="tooltip" title="Collapse"><?=$lang["ver"];?><i></i></button>

            <button class="btn btn-danger" style="display: none;"><?=$lang["eliminar_salida"];?></button>

              <form method="post" action="pasajerosLista" >

               <button name="idServicioSalidas" value="<?=$idServicioSalidas;?>" class="btn btn-secondary"><?=$lang["lista_de_pasajeros"];?></button>

               </form>

 <form method="post" action="salidasEditar" >

               <button name="idServicioSalidas" value="<?=$idServicioSalidas;?>" class="btn btn-info"><?=$lang["editar_salida"];?></button>

               </form>
<?php if (isset($_SESSION['login']['adminManz'])) {
  ?>
 <form method="post" action="serviciosAdicionalesSalidaEdita" >

               <button name="idServicioSalidas" value="<?=$idServicioSalidas;?>" class="btn btn-warning">Editar Adicionales</button>

               </form>


               <?php
} ?>
   

          </div>

        </div>



        <div class="card-body"> <!-- /.card-body -->

          <div class="row">

            <div class="col-12">

              <div class="row">

        



                                                 <table style="width:100%">

                                                  <tr>

                                                    <th><?=$lang["dia_de_la_salida"];?></th>

                                                    <th><?=$lang["fecha_de_salida"];?></th>

                                                    <th><?=$lang["disponibilidad"];?></th>



                                                  </tr>

                                                  <tr>

                                        

                                                    <td><?=strftime("%A", strtotime($salidas[$i]["fecha"]))?></td>

                                                    <td><?= date('d-m-Y',strtotime($salidas[$i]["fecha"]))?></td>

                                                    <td><?=$salidas[$i]["disponibilidad"];?></td>

                                                  </tr>

                                                  <tr>

                                                    <th><?=$lang["horario_de_salida"];?></th>

                                                    <th><?=$lang["horario_de_check_in"];?></th>

                                                 <th><?=$lang["nota_de_salida"];?></th>

                                                  </tr>

                                                  <tr>

                                                    <td><?=$salidas[$i]["horaSalida"]?></td>

                                                    <td><?=$salidas[$i]["horaCheckIn"]?></td>

                                                       <th><?=$salidas[$i]["nota_salida"]?></th>

                                                

                                                 </tr>

                                             



                                               </table>



                                               

                                          

                                                 <button class="btn btn-link collapsed <?=$alerta;?>" type="button"data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">       

                                                  </button>

                                                      <button class="btn btn-secondary" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">

                                                      Tarifas de Salida

                                                    </button>

                                             



<div class="accordion" id="accordionExample" style="width: 100%;">







    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">

      <div class="card-body">

              <table class="table table-bordered table-striped table-hover" id="tipos_pax_table" style="width:100%">

                                            <thead>

                                                <tr>

                                                      <th style="width: 20%;"><?=$lang["origen"];?></th>

                                                    <th > <?=$lang["nombre_tarifa"];?></th>

                                                    <th ><?=$lang["desde_anos"];?></th>

                                                    <th ><?=$lang["hasta_anos"];?></th>

                                                      <th style="width: 20%;"> <?=$lang["tipo_de_tarifa"];?></th>

                                                    <th > <?=$lang["precio_"];?></th> 

                                                    <th > <?=$lang["pago_minimo"];?></th>

                                                    <th > <?=$lang["tipo_de_cancelacion"];?></th>

                                               





                                                </tr>

                                            </thead>

                                            <tbody>

                                            	               <?php 

$tarifas= getTarifas($idServicioSalidas);

for ($j=0; $j < count($tarifas); $j++) { 



                   $edadFrom= getEdad($tarifas[$j]["idFromEdad"])[0];

                    $edadTo= getEdad($tarifas[$j]["idToEdad"])[0];

                    $tipoTarifa=getTipoTarifa($tarifas[$j]["idTipoTarifa"])[0];

                    $idServicioSalidasTarifas=$tarifas[$j]["idServicioSalidasTarifas"];

 $ubicacion=getUbicacionIdTarifa($idServicioSalidasTarifas);

$idCancelacion=$tarifas[$j]["idCancelaciones"];

$cancelacion=getTipoCancelaciones($idCancelacion);



              ?>

                                            	<tr>

                                            		<th><?= $ubicacion[0]["direccion"]; ?></th>

                                            		<th><?=$tarifas[$j]['nombre'];?></th>

                                            		<th><?=$edadFrom['valor'];?></th>

                                            		<th><?=$edadTo['valor'];?></th>

                                            		<th><?=$tipoTarifa["nombre"];?></th>

                                            		<th><?=$moneda[0]["Symbol"].$tarifas[$j]["valor"];?></th>

                                            		<th><?=$moneda[0]["Symbol"].$tarifas[$j]["minimo"];?></th>

                                            		<th><?=$cancelacion[0]["texto"]?></th>

                                       

                                            	</tr>

                                            	       <?php

                    } ?> 

                                            </tbody>



                         </table>

      </div>

    </div>







  <?php  $adicionales= getServiciosAdicionalesSalidaNoIncluidos($idServicioSalidas);

          $adicionalesIncluidos= getServiciosAdicionalesSalidaIncluidos($idServicioSalidas);

          $cantidadServiciosAdicionales=count($adicionales)+count($adicionalesIncluidos);

          

          $alerta="";

          $msj=" Servicios Adicionales Salida (".$cantidadServiciosAdicionales.")";

          if ($cantidadServiciosAdicionales==0) {

            $alerta="alert alert-danger";

            $msj=" SIN SERVICIOS ADICIONALES CARGADOS!!!";



          }



   ?>

       <div class="card-header" id="headingTwo">

      <h5 class="mb-0">

        <button class="btn btn-warning collapsed <?=$alerta;?>" type="button" data-toggle="collapse" data-target="#collapseSalidas<?=$idServicioSalidas;?>" aria-expanded="false" aria-controls="collapseSalidas<?=$idServicioSalidas;?>">        

        <?= $msj;?>

        </button>

        <form method="post" action="altaServiciosAdicionales" ><button name="idServicioSalidas" value="<?=$idServicioSalidas;?>"class="btn btn-success"><?=$lang["agregar_servicios_adicionales"];?></button></form>

      </h5>

    </div>





<?php    if ($cantidadServiciosAdicionales>0) {

         ?>



    <div id="collapseSalidas<?=$idServicioSalidas;?>" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">

      <div class="card-body">

              <table class="table table-bordered table-striped table-hover" id="tipos_pax_table" style="width:100%">

                                            <thead>

                                                <tr>

                                                      <th style="width: 20%;"><?=$lang["nombre"];?></th>

                                                    <th ><?=$lang["descripcion"];?></th>

                                                    <th ><?=$lang["valor"];?></th>

                                                    <th > </th>

                                                

                                                





                                                </tr>

                                            </thead>

                                            <tbody>

                                              <?php

                          for ($j=0; $j < count($adicionales); $j++) { 

                                                ?>

                                              <tr>

                                                <th><?=$adicionales[$j]["nombre"];?></th>

                                                <th><?=$adicionales[$j]["descripcion"];?></th>

                                                <th><?=$adicionales[$j]["valor"];?></th>

                                             

                                                <th><?php //print_r($adicionales[$j])?></th>

                                              </tr>

                                              <?php 

                                               } ?>



                                                          <?php 

                          for ($j=0; $j < count($adicionalesIncluidos); $j++) { 

                                                ?>

                                              <tr>

                                                <th><?=$adicionalesIncluidos[$j]["nombre"];?></th>

                                                <th colspan="3"><?=$adicionalesIncluidos[$j]["descripcion"];?></th>

                                           

                                             

                                         

                                              </tr>

                                              <?php 

                                               } ?>

                                            </tbody>



                         </table>

      </div>

    </div>

<?php } ?>



</div>





          </div>

      </div>





             

      

        </div> <!-- /.card-body -->

      

  </div><!-- /.card collapsed-card del periodo-->
<?php } ?>





<!-- Content Header (Page header) -->





















            <!-- SELECT2 EXAMPLE -->

            <!-- SELECT2 EXAMPLE -->

           