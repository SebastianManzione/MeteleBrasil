<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include("includes/header.php");

include("includes/navbar.php");

include("includes/sidebar.php");

require("classes/functions.php");

require("classes/agencia.php");

require("classes/usuario.php");







if (!$_SESSION["login"]["rol"]==1) {



  alertar("Usted no tiene acceso a esta seccion del software", "error");



  redireccionarLento("index");



}





$nombre='';

$rSocial='';

$documento='';

$telefono='';

$email='';

$observaciones='';

$txtDireccion='';

$txtLatitud='';

$txtLongitud='';

$idUsuario='';

$legajo='';

$celular='';

$instagram='';

$facebook='';

$web='';





if ($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST['altaAgencia'])) {



$nombre=$_POST["nombre"];

$rSocial=$_POST["rSocial"];

$documento=$_POST["documento"];

$telefono=$_POST["telefono"];

$email=$_POST["email"];

$observaciones=$_POST["observaciones"];

$txtDireccion=$_POST["direccion"];

$txtLatitud=$_POST["latitud"];

$txtLongitud=$_POST["longitud"];

$idUsuario=$_POST["idUsuario"];

$legajo=$_POST["legajo"];

$celular=$_POST["celular"];

$instagram=$_POST["instagram"];

$facebook=$_POST["facebook"];

$web=$_POST["web"];



  $Agencia=setAgencia($nombre, $rSocial, $documento, $telefono, $email, $observaciones, $txtDireccion, $txtLatitud, $txtLongitud, $idUsuario, $legajo, $celular, $facebook, $instagram, $web);



 if($Agencia>1){



alertar("Agencia guardado con exito", "success");



redireccionarLento("agencias.php");

exit();

 }



 else{



 	alertar("Error en la carga de Agencia, intente nuevamente por favor", "warning ");



 }











}

if ($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST['editaAgencia'])) {





$idAgencia=$_POST["editaAgencia"];

$Agencia=getAgencia($idAgencia)[0];

$nombre=$Agencia["nombre"];

$rSocial=$Agencia["razonSocial"];

$documento=$Agencia["documento"];

$telefono=$Agencia["telefono"];

$email=$Agencia["email"];

$observaciones=$Agencia["observaciones"];

$txtDireccion=$Agencia["direccion"];

$txtLatitud=$Agencia["latitud"];

$txtLongitud=$Agencia["longitud"];

$idUsuario=$Agencia["idUsuario"];

$legajo=$Agencia["legajo"];

$celular=$Agencia["celular"];

$instagram=$Agencia["instagram"];

$facebook=$Agencia["facebook"];

$web=$Agencia["web"];



}

if ($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST['guardaEditaAgencia'])) {







 $nombre=$_POST["nombre"];

$rSocial=$_POST["rSocial"];

$documento=$_POST["documento"];

$telefono=$_POST["telefono"];

$email=$_POST["email"];

$observaciones=$_POST["observaciones"];

$txtDireccion=$_POST["direccion"];

$txtLatitud=$_POST["latitud"];

$txtLongitud=$_POST["longitud"];

$idUsuario=$_POST["idUsuario"];

$legajo=$_POST["legajo"];

$celular=$_POST["celular"];

$instagram=$_POST["instagram"];

$facebook=$_POST["facebook"];

$web=$_POST["web"];

  $idAgencia=$_POST['guardaEditaAgencia'];



  $resuUpdate=updateAgencia($idAgencia, $nombre, $rSocial, $documento, $telefono, $email, $observaciones, $txtDireccion, $txtLatitud, $txtLongitud, $idUsuario,$legajo, $celular, $facebook, $instagram, $web);

  if ($resuUpdate>0) {

    alertar("Alterações salvas com sucesso", "success");

    redireccionarLento("agencias.php");

exit();

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



            <h1 class="m-0 text-dark">Alta Agencia</h1>



          </div><!-- /.col -->



          <div class="col-sm-6">



            <ol class="breadcrumb float-sm-right">



              <li class="breadcrumb-item"><a href="#">Home</a></li>



              <li class="breadcrumb-item active">Alta Agencia</li>



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



            <h3 class="card-title">Nuevo Agencia</h3>



<form method="post" >



            <div class="card-tools">



              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>



              <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>



            </div>



          </div>



          <!-- /.card-header -->



          <div class="card-body">



            <div class="row">



              <div class="col-md-6">



                <div class="form-group">



                  <label>Nombre Del Agencia</label>



                  <input name="nombre" class="form-control select2bs4" style="width: 100%;" placeholder="Nombre de Fantasia de La Empresa" value="<?=$nombre;?>" required>







                </div>



 <!-- /.form-group -->



                            <div class="form-group">



                  <label>CNPJ/CUIL/CUIT</label>



                  <input name="documento" class="form-control select2bs4" style="width: 100%;" placeholder="Número de Inscripcion" value="<?=$documento;?>"  required>



                </div>



                       



                <!-- /.form-group -->



                <div class="form-group">



                  <label>Usuario</label>



                  <select name="idUsuario" class="form-control">



                    <option value=0>Agregue aquí un Operador de confianza para que tenga accesso a la gestión de reservas. </option>



                    <?php 



$usuarios=getUsuarios();



for ($i=0; $i < count($usuarios); $i++) {

$selected= " ";

if ($idUsuario==$usuarios[$i]["idUsuario"]) {

  $selected="selected";

   // code...

 } 



?>



<option value="<?=$usuarios[$i]['idUsuario']?>" <?=$selected;?>><?=$usuarios[$i]['usuario'].' | '.$usuarios[$i]['email']?></option>



<?php



}



                     ?>



                  </select>



       <a class="btn btn-success" target="_blank" href="../sistema/altaUsuario.php">Nuevo Usuario</a> 



        <a class="btn btn-warning" onclick="window.location.reload()">Recargar</a>



                </div>



    <div class="form-group">



                  <label>Teléfono</label>



                  <input name="telefono" class="form-control select2bs4" style="width: 100%;" placeholder="Teléfono de operaciones" value="<?=$telefono;?>" required>



                </div>



  <div class="form-group">



                  <label>Celular / WhatsApp</label>



                  <input name="celular" class="form-control select2bs4" style="width: 100%;" placeholder="Celular / WhatsApp" value="<?=$celular?>" required>



                </div>



                  <div class="form-group">



                  <label>Pagina Web</label>



                  <input name="web" class="form-control select2bs4" style="width: 100%;" placeholder="www.Agencia.com" value="<?=$web;?>">



                </div>



              </div>



              <!-- /.col -->



              <div class="col-md-6">



                <div class="form-group">



                  <label>Razon Social</label>



                  <input name="rSocial" class="form-control select2bs4" style="width: 100%;" placeholder="Nombre de inscripcion tributaria" value="<?=$rSocial;?>" required>



                </div>



                      <div class="form-group">



                  <label>Numero de Legajo</label>



                  <input name="legajo" class="form-control select2bs4" style="width: 100%;" placeholder="Inscripcion Ministerio de Turismo" value="<?=$legajo?>" required>



                </div>



                <div class="form-group">



                  <label>Email</label>



                  <input name="email" value="<?=$email;?>" class="form-control select2bs4" style="width: 100%;" placeholder="Email de operaciones" required>



                </div>



                <div class="form-group">



                  <label>Observaciones</label>



                  <textarea name="observaciones" class="form-control select2bs4" style="width: 100%;" placeholder="Describe como fue El contacto comercial con este operador Y sus detalles a tener en cuenta para el Team de Metele Brasil" required><?=$observaciones;?></textarea>



                </div>



                        <div class="form-group">



                  <label>Facebook</label>



                  <input name="facebook" class="form-control select2bs4" style="width: 100%;" placeholder="facebook.com/" value="<?=$facebook;?>" >



                </div>



                        <div class="form-group">



                  <label>Instagram</label>



                  <input name="instagram" class="form-control select2bs4" style="width: 100%;" placeholder="instagram.com/" value="<?=$instagram;?>" >



                </div>



                <!-- /.form-group -->



       



                <!-- /.form-group -->



              </div>



              <!-- /.col -->



    
  <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo defined('GOOGLE_MAPS_API_KEY') ? GOOGLE_MAPS_API_KEY : ''; ?>&callback=initMap&v=weekly" defer></script>

<div class="col-12">
  
  <div id="map" style="  height: 200px;"></div>
</div>
     

 <script type="text/javascript" src="js/mapGoogle.js?ver=<?php echo(rand()); ?>"></script>  



<style type="text/css">

  /**

 * @license

 * Copyright 2019 Google LLC. All Rights Reserved.

 * SPDX-License-Identifier: Apache-2.0

 */

/* 

 * Always set the map height explicitly to define the size of the div element

 * that contains the map. 

 */

#map {



}



/* 

 * Optional: Makes the sample page fill the window. 

 */

html,

body {

  height: 100%;

  margin: 0;

  padding: 0;

}





</style>





    <!-- 

     The `defer` attribute causes the callback to execute after the full HTML

     document has been parsed. For non-blocking uses, avoiding race conditions,

     and consistent behavior across browsers, consider loading using Promises

     with https://www.npmjs.com/package/@googlemaps/js-api-loader.

    -->

  

   











</div>







<div class="form-group a75">











<div class="a75">		



<h5>Dirección</h5>



<input type="hidden" name="direccion" id="direccion" value="<?=$txtDireccion;?>">



<input type="hidden" name="latitud" id="latitud" value="<?=$txtLatitud?>">



<input type="hidden" name="longitud" id="longitud" value="<?=$txtLongitud?>">



	<input type="text" name="txtDireccion" id="txtDireccion" class="form-control a75" placeholder="Direccion" value="<?=$txtDireccion?>" >



</div>



<div class="a75">		



<h5>Latitud</h5>



	<input type="text" name="txtLatitud" id="txtLatitud" class="form-control a75" value="<?=$txtLatitud?>">



</div>



<div class="a75">		



<h5>Longitud</h5>







	<input type="text" name="txtLongitud" id="txtLongitud" class="form-control a75" value="<?=$txtLongitud?>">



</div>































            </div>







            <!-- /.row -->



          </div>



          <!-- /.card-body -->



          <div class="card-footer">



          <div align="center">

<?php if (isset($_POST['editaAgencia'])) {

  ?>

<button type="submit" name="guardaEditaAgencia"id="uploadfiles" value="<?=$idAgencia;?>" class="btn btn-success"><i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar</button>  



  <?php

}else{ ?>

           <button type="submit" name="altaAgencia"id="uploadfiles" value="Crear servicio" class="btn btn-success"><i class="fa fa-floppy-o" aria-hidden="true"></i> Continuar</button>  

<?php } ?>



            <a href="agencias" class="btn btn-info" ><i class="fas fa-arrow-left"></i>Voltar</a>



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