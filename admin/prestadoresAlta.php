<?php 
require("classes/functions.php");
include "includes/header.php";
include "includes/navbar.php";

if (!$_SESSION["login"]["rol"]==1) {
  alertar("Usted no tiene acceso a esta seccion del software", "error");
  redireccionarLento("index");
}
?>


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Nuevo Prestador</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Nuevo Prestador</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- SELECT2 EXAMPLE -->


        <!-- SELECT2 EXAMPLE -->
        <div class="card card-default">
          <div class="card-header">
            <h3 class="card-title">Nuevo Prestador</h3>
<form id="upload-widget" class="dropzone" action="ctrl/prestadorCtrl.php" method="post" enctype="multipart/form-data">
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
                  <label>Nombre</label>
                  <input name="nombre" id="nombre" class="form-control select2bs4" style="width: 100%;" placeholder="Nombre" required>

                </div>
   
 <div class="form-group">
                  <label>Email</label>
                  <input type="email" name="email" id="email" class="form-control select2bs4" style="width: 100%;" required>
                </div>
               <!-- /.form-group -->
               <!-- /.form-group -->
              </div>
              <!-- /.col -->
              <div class="col-md-6">
          
                <!-- /.form-group --> <div class="form-group">
                  <label>Cuit</label>
                 <input name="cuit" id="cuit" class="form-control select2bs4" style="width: 100%;" placeholder="Cuit">
                </div>
         
    <div class="form-group">
                  <label>Telefono</label>
                  <input name="telefono" id="telefono" class="form-control select2bs4" style="width: 100%;" required>
                </div>
                <!-- /.form-group -->
              </div>
              <!-- /.col -->
            </div>


            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Nro Legajo</label>
                  <input name="legajo" id="legajo" class="form-control select2bs4" style="width: 100%;">

                </div>
   

               <!-- /.form-group -->
               <!-- /.form-group -->
              </div>
              <!-- /.col -->
         
              <!-- /.col -->
            </div>

         <div class="row">
                  <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Responsable</h3>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-3">
                      <h3 class="card-title">Nombre</h3>
                    <input  type="text" step="0.1" name="nombreResponsable" class="form-control"  required>
                  </div>
                  <div class="col-3">
                     <h3 class="card-title">Teléfono</h3>
                     <input  type="text" name="telefonoResponsable"  class="form-control"  required>
                  </div><div class="col-3">
                     <h3 class="card-title">Email</h3>
                      <input  type="text"name="emailResponsable"  class="form-control" required>
                  </div><div class="col-3">
                     <h3 class="card-title">WhatsApp</h3>
                     <input  type="text"  name="whatsappResponsable"  class="form-control"  required>
                  </div>
                
                </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
              <!-- /.col -->
            <div id="myMap" style="position:relative;width:100%;height:300px;float:center;"></div>

    <div id='output' style="margin-left:10px;float:left;"></div>
</div>
<!arranca el mapa!>
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
          var pais=result.address['countryRegion'];
          var provincia=result.address['adminDistrict'];
          var latitud=result.location["latitude"];
          var longitud=result.location["longitude"];
      $('#txtDireccion').attr('value', result.address['formattedAddress']);

localizacion[0]= $('#idSrv').val();      
localizacion[1]=result.address['formattedAddress'];
localizacion[2]=result.location['latitude'];
localizacion[3]=result.location['longitude'];
  $('#txtLatitud').attr('value', result.location['latitude']);
$('#txtLongitud').attr('value', result.location['longitude']);
  $('#txtPais').attr('value', pais);
$('#txtProvincia').attr('value', provincia);
 /*  alert("formattedAddress:"+result.address['formattedAddress']+" adminDistrict:"+result.address['adminDistrict']+
    +" countryRegion:"+result.address['countryRegion']+
    " cp:"+result.address['postalCode']+" lat:"+latitud+" lon:"+longitud);
*/


        map.entities.push(pin);
        map.setView({ bounds: result.bestView });
      //  document.getElementById('output').innerHTML = 'Resultados:<br/>' + result.name;
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


                      <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Direccion</h3>

              </div>              

              <div class="card-body">
              	    <div id='searchBoxContainer' class="row">
  
       <input id='searchBox' type='text' class="form-control" value="" />
       
    <input type='hidden'value='Search' onclick='Search() ' class="btn btn-primary"/>
 </div>
                <div class="row">
                  <div class="col-3">
                      <h3 class="card-title">Direccion</h3>
                     <input type="text" name="txtDireccion" id="txtDireccion" class="form-control a25" placeholder="Direccion" readonly>
                  </div>
                  <div class="col-3">
                     <h3 class="card-title">Latitud</h3>
                     <input type="text" name="txtLatitud" id="txtLatitud" class="form-control a25" placeholder="Latitud" readonly>
                  </div><div class="col-3">
                     <h3 class="card-title">Longitud</h3>
                  <input type="text" name="txtLongitud" id="txtLongitud" class="form-control a25" placeholder="Longitud" readonly>
                  </div>
                
                </div>

                  <div class="row">
                  <div class="col-3">
                      <h3 class="card-title">Pais</h3>
                     <input type="text" name="txtPais" id="txtPais" class="form-control a25" placeholder="Direccion" readonly>
                  </div>
                  <div class="col-3">
                     <h3 class="card-title">Provincia</h3>
                     <input type="text" name="txtProvincia" id="txtProvincia" class="form-control a25" placeholder="Latitud" readonly>
                  </div>
                
                </div>
              </div>
              <!-- /.card-body -->
            </div>

  
    
              <!-- /.col -->
            </div>
             <!-- /.row -->
          </div>
          <!-- /.card-body -->
          <div class="card-footer">
          <div align="center"> <button type="submit" name="altaPrestador" class="btn btn-success"><i class="fa fa-floppy-o" aria-hidden="true"></i> Continuar</button>   <a href="servicios.php" class="btn btn-danger" ><i class="fa fa-times" aria-hidden="true"></i> Salir sin guardar</a>
</div>
          </div>
        </div>
        <!-- /.card -->

        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  </form>
  <!-- /.content-wrapper -->
<?php include "includes/footer.php";?>