<?php 
// Verificar permisos de acceso ANTES de cualquier salida
require_once(__DIR__ . "/classes/permisos.php");
require_once(__DIR__ . "/includes/permisos_helper.php");
$permisos = new PermisosManager($GLOBALS['pdo'], $_SESSION['login'] ?? []);
$permisos->verificarAcceso('tarifasEditor');

include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");
require_once("classes/functions.php");
require_once("classes/categoria.php");
require_once("classes/tarifas.php");
require_once("classes/tipos_tarifa.php");
require_once("classes/edades.php");
require_once("classes/texto_miniaturas.php");
require_once("classes/cancelaciones.php");
require_once("classes/salidas.php");


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['updateTarifa'])) {


$idServicioSalidasTarifas=$_POST['idServicioSalidasTarifas'];
$nombre=$_POST['nombre'];
$idFromEdad=$_POST['fromEdad'];
$idToEdad=$_POST['toEdad'];
$idTipoTarifa=$_POST['tipo_tarifa'];
$valor=$_POST['valor'];
$minimo=$_POST['minimo'];
$idCancelaciones=$_POST['idCancelacion'];
 $comisiona='';
if (isset($_POST['comisiona']) && $_POST['comisiona']=="on") {
  $comisiona=1;
  // code...
}

$resul=updateTarifa($idServicioSalidasTarifas, $nombre, $idFromEdad, $idToEdad, $idTipoTarifa, $valor, $minimo, $idCancelaciones, $comisiona);

if ($resul>0) {
  alertar("tarifa guardada con exito", "success");
}


}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['idServicioSalidasTarifas'])) {

$idServicioSalidasTarifas=$_GET['idServicioSalidasTarifas'];


  // code...
}

$tarifas=getTarifa($idServicioSalidasTarifas);
$idServicioSalidas=$tarifas[0]["idServicioSalidas"];
$salida=getSalida($idServicioSalidas);
if($salida[0]["idPrestador"]!= $_SESSION['login']["idPrestador"] && $_SESSION['login']["rol"]!= 1){

    alertar("Usted no tiene acceso a esta seccion del software", "error");
    redireccionarLento("index");
exit();
}
 $idTipoTarifa=$tarifas[0]['idTipoTarifa'];
                     
                        $valor=$tarifas[0]['valor'];
                         $minimo=$tarifas[0]['minimo'];
                         $idTipoCancelacion=$tarifas[0]["idCancelaciones"];
                    
                        $comisiona=" ";
                     if ($tarifas[0]['comisiona']==1) {
                         $comisiona="checked";
                     }



 ?>

  <!-- Content Wrapper. Contains page content -->

  <div class="content-wrapper">

    <!-- Content Header (Page header) -->

    <div class="content-header">

      <div class="container-fluid">

        <div class="row mb-2">

          <div class="col-sm-6">

            <h1 class="m-0 text-dark">Tarifas</h1>

          </div><!-- /.col -->

          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right">

              <li class="breadcrumb-item"><a href="#">Editor Tarifas</a></li>

              <li class="breadcrumb-item active">Editor Tarifas</li>

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

<!-- /.card-header -->


        <div class="card-body">

            <div class="row">

                  <div >   

       <form method="post">                     
<div class="col-12">
  <div class="col-6">
<label>Nombre</label>
<input type="hidden" name="idServicioSalidasTarifas" value="<?=$idServicioSalidasTarifas?>">
<input type="text" name="nombre" class="form-control" value="<?=$tarifas[0]['nombre']?>">
</div>
<div class="col-12">
  <label>From Edad</label>

  <select name="fromEdad" class="form-control">
<?php $edades=getEdades();

                   for ($i=0; $i < count($edades); $i++) { 
                                   $selected="";
                                   if ($tarifas[0]['idFromEdad']==$edades[$i]["idEdad"]) {
                                        $selected="selected";
                                     }  

                                     ?>

                                          <option value="<?=$edades[$i]['idEdad'];?>" <?=$selected?>><?=$edades[$i]['valor'];?></option><?php

                                         } ?>  

  </select>
</div>
<div class="col-12">
  <label>To Edad</label>
  <select name="toEdad" class="form-control">
<?php $edades=getEdades();

                   for ($i=0; $i < count($edades); $i++) { 
                                   $selected="";
                                   if ($tarifas[0]['idToEdad']==$edades[$i]["idEdad"]) {
                                        $selected="selected";
                                     }  

                                     ?>

                                          <option value="<?=$edades[$i]['idEdad'];?>" <?=$selected?>><?=$edades[$i]['valor'];?></option><?php

                                         } ?>  
  </select>
</div>
                    
              <div class="col-12">
                    <label>Tipo Tarifa</label>
            <select name="tipo_tarifa" class="form-control">
                    <?php $tipos_tarifas=getTiposTarifas();
                        
                            for ($i=0; $i < count($tipos_tarifas); $i++) { 
                                  $selected="";

                              if ($tipos_tarifas[$i]["idTipoTarifa"]==$idTipoTarifa) {
                         
                              $selected="selected";
                              }
                             ?>
       
                                <option value="<?=$tipos_tarifas[$i]['idTipoTarifa']?>" <?=$selected?> ><?=$tipos_tarifas[$i]["nombre"]?></option>
                             <?php
                            }
                     ?>
                     
                   </select>
                
                  </div>
                    <label>Valor</label>
                    <input type="number" name="valor" value="<?=$valor?>" class="form-control" step="0.01">
                    <label>Minimo</label>
                          <input type="number" name="minimo" value="<?=$minimo?>" class="form-control" step="0.01">
                    <label>Cancelacion</label>
                    <select name="idCancelacion" class="form-control">
                      

                
                    <?php $tipos_cancelaciones=getTiposCancelaciones();
                            for ($i=0; $i < count($tipos_cancelaciones); $i++) {
                                     if ($tipos_cancelaciones[$i]['idCancelacion']==2) {
                            $selected=""; 
                              if ($idTipoCancelacion==$tipos_cancelaciones[$i]["idCancelacion"]) {
                                $selected="selected";
                              }
                           ?>
<option value="<?=$tipos_cancelaciones[$i]["idCancelacion"]?>" <?=$selected?> ><?=$tipos_cancelaciones[$i]["texto"]?></option>

                           <?php
                         }
                            }

                     ?>
                         </select>
                    <label>comision</label>
                    <input type="checkbox" name="comisiona" class="form-control" <?=$comisiona?>>
</div>
<button class="btn-sm btn-success" name="updateTarifa">Guardar</button>

</form>
<form method="post" action="salidaVer">
  <button class="btn-sm btn-info" name="idServicioSalidas" value="<?=$idServicioSalidas?>">Voltar</button>
</form>                                    </div>

                                    </div>



                                    </div><!-- /.card-body -->

             







                                        

                                  

                                   



        <script type="text/javascript">

                        function format(value) {

                        return value  ;

                            }

                            $(document).ready(function () {

                                var table = $('#tablaCarrito').DataTable({});



                                // Add event listener for opening and closing details

                                $('#tablaCarrito').on('click', 'td.details-control', function () {



                                    var tr = $(this).closest('tr');

                                    var row = table.row(tr);



                                    if (row.child.isShown()) {

                                        // This row is already open - close it

                                        row.child.hide();

                                        tr.removeClass('shown');

                                    } else {

                                        // Open this row

                                        row.child(format(tr.data('child-value'))).show();

                                        tr.addClass('shown');

                                    }

                                });

                            });

                   </script>





                                             </div>   

                                            </div>   

                                          </div>

                            <!-- /.row -->

                                      </div>

                          <!-- /.card-body -->

               <div class="card-footer">

                                                 <!-- <div align="center"> <button type="submit" id="uploadfiles" value="Crear servicio" class="btn btn-success"><i class="fa fa-floppy-o" aria-hidden="true"></i> Continuar</button>   <a href="servicios.php" class="btn btn-danger" ><i class="fa fa-times" aria-hidden="true"></i> Salir sin guardar</a>

                                        </div>-->

          </div>

     </div>

     <!-- /.card -->







                     <!-- /.card -->

         </div><!-- /.container-fluid -->

    

  <?php 

   include("includes/footer.php"); ?>