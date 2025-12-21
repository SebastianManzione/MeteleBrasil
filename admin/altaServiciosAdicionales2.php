<?php 

include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");
require("classes/functions.php");
require("classes/prestador.php");
require("classes/usuario.php");
require("classes/servicio.php");
require("classes/salidas.php");
require("classes/servicios_adicionales.php");
if (isset($_POST["idPackSalidas"])) {
  
  $idPackSalidas=$_POST["idPackSalidas"];
  $salidas=getSalidasIdServiciosSalidasPack($idPackSalidas);

  $servicio=getServicio($salidas[0]["idServicio"]);
$idCategoria_servicio=$servicio[0]["idCategoria_servicio"];
$serviciosAdicionalesCategoria=getServiciosAdicionalesCategoria($idCategoria_servicio);



}
if (isset($_POST["idServicioSalidas"])) {

  $idServicioSalidas=$_POST["idServicioSalidas"];
  $salidas=getSalida($idServicioSalidas);

  $servicio=getServicio($salidas[0]["idServicio"]);
$idCategoria_servicio=$servicio[0]["idCategoria_servicio"];
$serviciosAdicionalesCategoria=getServiciosAdicionalesCategoria($idCategoria_servicio);



}


else if (isset($_SESSION["idPackSalidas"])) {

  $idPackSalidas=$_SESSION["idPackSalidas"];
  $salidas=getSalidasIdServiciosSalidasPack($idPackSalidas);

  $servicio=getServicio($salidas[0]["idServicio"]);
    $idServicioSalidas=$salidas[0]["idServicioSalidas"];
$idCategoria_servicio=$servicio[0]["idCategoria_servicio"];
$serviciosAdicionalesCategoria=getServiciosAdicionalesCategoria($idCategoria_servicio);

}


else{
  redireccionar("serviciosLista.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["svAdicionales"])) {


$idServicioSalidasPost=$_POST["idServicioSalidas"];
$svAdicionales=$_POST["svAdicionales"];
$svAdicionalesPre=$_POST["svAdicionalesPre"];
$descripcionPost=$_POST["descripcion"];


  for ($i=0; $i < count($idServicioSalidasPost); $i++) { //salidas

$idServicioSalidas=$idServicioSalidasPost[$i];
$descripcion="";
if (isset($descripcionPost[$i])) {
$descripcion=$descripcionPost[$i];
}

  $salidas=getSalida($idServicioSalidas);
echo "<br><br>";

  $idMoneda=($salidas[0]["idMoneda"]);
echo "<br><br>";
   foreach ($svAdicionales as $key => $value) { //sv adicionales
    $precio=0;
      foreach ($svAdicionalesPre as $key2 => $value2) { //precios
      if ($value2>0 && $key2==$key) {
 
        $precio=$value2;
      }
            
     }

  $salidaAdicionalesSalida=   setServiciosAdicionalesSalida($idServicioSalidas, $key, $precio,  $idMoneda,  $descripcion);
  if($salidaAdicionalesSalida>0){
      $idServicio=($salidas[0]["idServicio"]);
alertar($lang["adicionales_cargado_con_exito"], "success");
redireccionar("servicioVer?idServicio=".$idServicio);
  }
  
   }

 
 }
}




 ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark"><?=$lang["carga_de_servicios_incluidos_y_opcionales"];?></h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#"><?=$lang["inicio"];?></a></li>
              <li class="breadcrumb-item active"><?=$lang["servicios_incluidos_y_opcionales"];?></li>
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
          <div class="card-header">
            <h3 class="card-title"><?=$lang["indique_los_servicios"];?> <strong><?=$lang["incluidos_y_opcionales"];?></strong><?=$lang["de_cada_salida"];?></h3>



<form method="post" >
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
              <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
         

                                <!-- /.ACA VA LO NUEVO PARA AGREGAR ADICIONALES SERIAN ADICIONALES A SELECCIONAR CUADRO DE DIALOGO ADICIONALES PARA SUMAR SELECCIONAR BOTON CARGAR -->




<!-- /.Fechas de Salidas cargadas -->



             <!-- /.ESTO LO TENGO Q BORRAR -->
                <div class="form-group">


    
                  <label><?=$lang["marque_las_salidas"];?></label>
                  <p><?=$lang["seleccione_las_salidas"];?></p>



                  <?php for ($i=0; $i < count($salidas); $i++) { 
                    ?>
   <br />
                  <input type="checkbox" value="<?=$salidas[$i]['idServicioSalidas']?>" name="idServicioSalidas[]" checked> <?=$salidas[$i]["nombre"]?> <?=date("d-m-Y", strtotime($salidas[$i]["fecha"]))?></label>

                    <?php
                  } ?>
               
                
         
 </select>
  </div>  




<!-- /.Boton agregar y Cargar -->


<div class="form-group">
</div>

                     
                <!-- /.form-group -->
                
             <hr color="green" size=0.5 width="600" >     

<!-- /.Servicios Incluidos -->

<form method="post">

                 <div class="form-group">


<label><?=$lang["servicios_incluidos_y_opcionales"];?></label>

                                  <p><?=$lang["seleccione_y_adicione"];?></p>

    <?php 

    for ($i=0; $i < count($serviciosAdicionalesCategoria); $i++) { 
        if (isset($idServicioSalidas)) {

  $idServicioAdicionales=$serviciosAdicionalesCategoria[$i]['idServiciosAdicionales'];

    $svNoIncluidos=getServicioAdicionalIncluidoYNoIncluido($idServicioAdicionales,$idServicioSalidas);

  }
if (count($svNoIncluidos)==0) {
    # code...
      ?>

<div id="divCHK<?=$idServicioAdicionales;?>">

<input type="checkbox" name="svAdicionales[<?=$idServicioAdicionales;?>]" id="cbox<?=$idServicioAdicionales;?>" onclick="habilitar(<?=$serviciosAdicionalesCategoria[$i]['idServiciosAdicionales'];?>)" > <?= $serviciosAdicionalesCategoria[$i]["nombre"];?></input>

<input type="checkbox" id="checkBox<?=$idServicioAdicionales;?>" onclick="free(<?=$idServicioAdicionales;?>)"  style="display: none;" checked ><label id="lbl<?=$idServicioAdicionales;?>" style="display: none;">Incluido</label></input>

<input type="number" name="svAdicionalesPre[<?=$idServicioAdicionales;?>]"  id="svAdicionalesPre<?=$idServicioAdicionales;?>" step="0.01" min="1" style="display: none;">
<label id="lblDescripcion<?=$idServicioAdicionales;?>" style="display: none;"><?=$lang["descripcion"];?>z</label>
<input type="text" name="descripcion[<?=$idServicioAdicionales;?>]"  id="descripcion<?=$idServicioAdicionales;?>" step="0.01" min="1" style="display: none;">
              </div>
      <?php
     } } ?>
                
                  

  </div>  



<script type="text/javascript">

  function habilitar(idServicioAdicionales){

   

     if($('#cbox'+idServicioAdicionales+"").prop('checked')){
                         
   $("#checkBox"+idServicioAdicionales).show(); 
    $("#lbl"+idServicioAdicionales).show();



         }
  else{
       $("#svAdicionalesPre"+idServicioAdicionales).hide();
       $("#checkBox"+idServicioAdicionales).hide();
       $("#checkBox"+idServicioAdicionales).prop("checked", true);
      $("#lbl"+idServicioAdicionales).hide();
      $("#descripcion"+idServicioAdicionales).hide(); 
       $("#descripcion"+idServicioAdicionales).val(''); 
       $("#lblDescripcion"+idServicioAdicionales).hide(); 
                                                           
                                                                       }
                                         
  }

  function free(idServicioAdicionales){

               if($('#checkBox'+idServicioAdicionales+"").prop('checked')){
                          $("#svAdicionalesPre"+idServicioAdicionales).hide(); 
                                  $("#descripcion"+idServicioAdicionales).hide(); 
                                   $("#descripcion"+idServicioAdicionales).val(''); 
                                     $("#lblDescripcion"+idServicioAdicionales).hide(); 

                             $("#svAdicionalesPre"+idServicioAdicionales).val(0); 
                           $("#lbl"+idServicioAdicionales).show();
                }
               else{
 $("#svAdicionalesPre"+idServicioAdicionales).show();   
  $("#descripcion"+idServicioAdicionales).show();
  $("#lblDescripcion"+idServicioAdicionales).show();
    $("#svAdicionalesPre"+idServicioAdicionales).val(0); 
  $("#lbl"+idServicioAdicionales).hide();

                                                           
                                                                       }


  }
</script>
<!-- /.Boton agregar y Cargar -->







                     
                <!-- /.form-group -->
                
             <hr color="green" size=0.5 width="600" > 

  
 <!-- /.Servicios Opcionales -->

 
          
<!-- /.Boton agregar y Cargar -->


                     
                <!-- /.form-group -->
   
         
               

            <!-- /.row -->
          </div>
          <!-- /.card-body -->
              
          
            </div>
              <!-- /.col -->
   
                <!-- /.form-group -->
              </div>
              <!-- /.col -->
               <div align="right">
            <button type="submit" id="uploadfiles" value="Crear servicio" class="btn btn-success"><i class="fa fa-floppy-o" aria-hidden="true"></i> <?=$lang["continuar_"];?></button> 
            <a href="serviciosLista.php" class="btn btn-danger" ><i class="fa fa-times" aria-hidden="true"></i><?=$lang["salir_sin_guardar"];?></a>
</div>


</div>




          </div>
        </div>
      </div>
    </form>
        <!-- /.card -->

        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
        </div>
        <!-- /.row (main row) -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <?php 
  include("includes/footer.php"); ?>