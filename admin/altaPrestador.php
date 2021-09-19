<?php 

include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");
require("classes/functions.php");
require("classes/prestador.php");
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


if ($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST['altaPrestador'])) {
echo "********************************** ALTA PRESTADOR";
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

  $prestador=setPrestador($nombre, $rSocial, $documento, $telefono, $email, $observaciones, $txtDireccion, $txtLatitud, $txtLongitud, $idUsuario, $legajo, $celular, $facebook, $instagram, $web);

 if($prestador>1){

alertar("Prestador guardado con exito", "success");

redireccionarLento("prestadores.php");
exit();
 }

 else{

 	alertar("Error en la carga de prestador, intente nuevamente por favor", "warning ");

 }





}
if ($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST['editaPrestador'])) {


$idPrestador=$_POST["editaPrestador"];
$prestador=getPrestador($idPrestador)[0];
$nombre=$prestador["nombre"];
$rSocial=$prestador["razonSocial"];
$documento=$prestador["documento"];
$telefono=$prestador["telefono"];
$email=$prestador["email"];
$observaciones=$prestador["observaciones"];
$txtDireccion=$prestador["direccion"];
$txtLatitud=$prestador["latitud"];
$txtLongitud=$prestador["longitud"];
$idUsuario=$prestador["idUsuario"];
$legajo=$prestador["legajo"];
$celular=$prestador["celular"];
$instagram=$prestador["instagram"];
$facebook=$prestador["facebook"];
$web=$prestador["web"];

}
if ($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST['guardaEditaPrestador'])) {



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
  $idPrestador=$_POST['guardaEditaPrestador'];

  $resuUpdate=updatePrestador($idPrestador, $nombre, $rSocial, $documento, $telefono, $email, $observaciones, $txtDireccion, $txtLatitud, $txtLongitud, $idUsuario,$legajo, $celular, $facebook, $instagram, $web);
  if ($resuUpdate>0) {
    alertar("Alterações salvas com sucesso", "success");
    redireccionarLento("prestadores.php");
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

            <h1 class="m-0 text-dark">Alta Prestador</h1>

          </div><!-- /.col -->

          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right">

              <li class="breadcrumb-item"><a href="#">Home</a></li>

              <li class="breadcrumb-item active">Alta Prestador</li>

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

            <h3 class="card-title">Nuevo Prestador</h3>

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

                  <label>Nombre Del Prestador</label>

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

                  <input name="web" class="form-control select2bs4" style="width: 100%;" placeholder="www.prestador.com" value="<?=$web;?>">

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



<!--arranca el mapa!-->

       <script type='text/javascript' src='https://www.bing.com/api/maps/mapcontrol?callback=GetMap&key=Aul14kGJkus4tWL4PAZly9XkZ14uTOQ9PbJ9fHG4lEFmQKmVPjR38_O0kDsRTuOv' async defer></script>



      <script type='text/javascript'>



var localizacion= Array();



 var map, searchManager;

    function GetMap() {

        map = new Microsoft.Maps.Map('#myMap', {});

        Microsoft.Maps.loadModule(['Microsoft.Maps.AutoSuggest', 'Microsoft.Maps.Search'], function () {

            var manager = new Microsoft.Maps.AutosuggestManager({ map: map });

            manager.attachAutosuggest('#searchBox', '#searchBoxContainer', suggestionSelected);

            searchManager = new Microsoft.Maps.Search.SearchManager(map);

        });

    }





 function suggestionSelected(result) {

        //Remove previously results from the map.

        map.entities.clear();

        //Show the suggestion as a pushpin and center map over it.

        var pin = new Microsoft.Maps.Pushpin(result.location);

          

          var latitud=result.location["latitude"];

          var longitud=result.location["longitude"];

      $('#txtDireccion').attr('value', result.address['formattedAddress']);

  $('#txtLatitud').attr('value', result.location["latitude"]);

    $('#txtLongitud').attr('value', result.location["longitude"]);

    $('#direccion').attr('value', result.address['formattedAddress']);

  $('#latitud').attr('value', result.location["latitude"]);

    $('#longitud').attr('value', result.location["longitude"]);

localizacion[0]= $('#idSrv').val();      

localizacion[1]=result.address['formattedAddress'];

localizacion[2]=result.location['latitude'];

localizacion[3]=result.location['longitude'];



 /*  alert("formattedAddress:"+result.address['formattedAddress']+" adminDistrict:"+result.address['adminDistrict']+

    +" countryRegion:"+result.address['countryRegion']+

    " cp:"+result.address['postalCode']+" lat:"+latitud+" lon:"+longitud);

*/





        map.entities.push(pin);

        map.setView({ bounds: result.bestView });

      

    }

    function geocode() {



        //Remove previously results from the map.

        map.entities.clear();

        //Get the users query and geocode it.

        var query = document.getElementById('searchBox').value;

        var searchRequest = {



            where: query,

            callback: function (r) {





                if (r && r.results && r.results.length > 0) {

                    



                    var pin, pins = [], locs = [], output = 'Resultados:<br/>';

                    //Add a pushpin for each result to the map and create a list to display.

                    for (var i = 0; i < r.results.length; i++) {

                        //Create a pushpin for each result.

                        pin = new Microsoft.Maps.Pushpin(r.results[i].location, {

                            text: i + ''

                        });

                        pins.push(pin);

                        locs.push(r.results[i].location);

                        output += i + ') ' + r.results[i].name + '<br/>';

                    }

                    //Add the pins to the map

                    map.entities.push(pins);

                    //Display list of results

                    document.getElementById('output').innerHTML = output;

                    //Determine a bounding box to best view the results.

                    var bounds;



                    if (r.results.length == 1) {



                        bounds = r.results[0].bestView;









                    } else {

                        //Use the locations from the results to calculate a bounding box.

                        bounds = Microsoft.Maps.LocationRect.fromLocations(locs);

                    }

                    map.setView({ bounds: bounds, padding: 30 });

                }

            },

            errorCallback: function (e) {

                document.getElementById('output').innerHTML = "Sin resultados.";

            }

        };

        //Make the geocode request.

        searchManager.geocode(searchRequest);

    }







    </script>

  <div id='searchBoxContainer'>

  

       <input id='searchBox' type='text' class="form-control a75" value="" />

       

    <input type='hidden'value='Search' onclick='Search() ' class="btn btn-primary"/>

 </div>

    <br/>

    <div id="myMap" style="position:relative;width:800px;height:300px;float:center;"></div>

    <div id='output' style="margin-left:10px;float:left;"></div>

  

   





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
<?php if (isset($_POST['editaPrestador'])) {
  ?>
<button type="submit" name="guardaEditaPrestador"id="uploadfiles" value="<?=$idPrestador;?>" class="btn btn-success"><i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar</button>  

  <?php
}else{ ?>
           <button type="submit" name="altaPrestador"id="uploadfiles" value="Crear servicio" class="btn btn-success"><i class="fa fa-floppy-o" aria-hidden="true"></i> Continuar</button>  
<?php } ?>

            <a href="prestadores" class="btn btn-info" ><i class="fas fa-arrow-left"></i>Volver</a>

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