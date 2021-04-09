<?php 
include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");
require("classes/functions.php");
require("classes/prestador.php");
require("classes/usuario.php");
if ($_SERVER["REQUEST_METHOD"]=="POST") {

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
 }
 else{
 	alertar("Error en la carga de prestador, intente nuevamente por favor", "warning ");
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
            <h1 class="m-0 text-dark">Adicionales y Comisiones</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Alta de Servicio</li>
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
            <h3 class="card-title">Adicionales y Comiciones</h3>
<form method="post" >
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
              <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <div class="row">
             
                <div class="form-group">

</select>
    
                  <label>Marque las Salidas y sus servicios Adicionales</label>
                  <br />
                  <input type="checkbox" id="cbox1" value="first_checkbox"> Snorkel</label>
                  <br />
                  <input type="checkbox" id="cbox1" value="first_checkbox"> Sillas de Playa</lab>        
                  <br />       
                  <input type="checkbox" id="cbox1" value="first_checkbox"> Sombrillas</label>
                  <br />  
                  <input type="checkbox" id="cbox1" value="first_checkbox"> Coctel de Bienvenida</label>
                  <br />  
                  <input type="checkbox" id="cbox1" value="first_checkbox"> Cairpirinha Libre</label>
                  <br />                    
                  <input type="checkbox" id="cbox1" value="first_checkbox"> Agua Libre</label>
                  <br />  
                  <input type="checkbox" id="cbox1" value="first_checkbox"> Cafe Cortesia</label>
         
 </select>
  </div>  
<br />

    <div class="form-group">

</select>
    
                  <label>Seleccione los SERVICIOS INCLUIDOS en esta salida</label>
                  <br />
                  <input type="checkbox" id="cbox1" value="first_checkbox"> Snorkel</label>
                  <br />
                  <input type="checkbox" id="cbox1" value="first_checkbox"> Sillas de Playa</lab>              
                  <br />       
                  <input type="checkbox" id="cbox1" value="first_checkbox"> Sombrillas</label>
                  <br />  
                  <input type="checkbox" id="cbox1" value="first_checkbox"> Coctel de Bienvenida</label>
                  <br />  
                  <input type="checkbox" id="cbox1" value="first_checkbox"> Cairpirinha Libre</label>
                  <br />                    
                  <input type="checkbox" id="cbox1" value="first_checkbox"> Agua Libre</label>
                  <br />  
                  <input type="checkbox" id="cbox1" value="first_checkbox"> Cafe Cortesia</label>
          </div>  
<br />


 </select>
           <div class="form-group">      
            <label>Seleccione los SERVICIOS OPCIONALES Y SUS VALORES</label>
                  <br />
                  <input type="checkbox" id="cbox1" value="first_checkbox"> Snorkel</label>
                  <br />
                  <input type="checkbox" id="cbox1" value="first_checkbox"> Sillas de Playa</lab>              
                  <br />       
                  <input type="checkbox" id="cbox1" value="first_checkbox"> Sombrillas</label>
                  <br />  
                  <input type="checkbox" id="cbox1" value="first_checkbox"> Coctel de Bienvenida</label>
                  <br />  
                  <input type="checkbox" id="cbox1" value="first_checkbox"> Cairpirinha Libre</label>
                  <br />                    
                  <input type="checkbox" id="cbox1" value="first_checkbox"> Agua Libre</label>
                  <br />  
                  <input type="checkbox" id="cbox1" value="first_checkbox"> Cafe Cortesia</label>
    </div>
          
                     
                <!-- /.form-group -->
                
                  
         

<div class="form-group">
                  <label>Agregue Servicios Adicionales</label>
                   <select class="form-control" name="lista_de_seleccion">
        <option value="SI">SI, es posible. Te esperamos!</option>
        <option value="Lamentrablemente">Lamentrablemente NO contamos con ACCESIBILIDAD en esta Actividad</option>
        <option value="Rampa">Solo Rampa</option>
        <option value="sinuoso">Lugar muy sinuoso</option>
          
                   </select>
                </div>
                <div class="form-group">
                  <label>Tipos de Cancelaciones</label>
                   <select class="form-control" name="lista_de_seleccion">
        <option value="opcion1">Dentro de las 24hs No Hay Reemboslo solo por el incumplimiento del servicio</option>
        <option value="Antes de las 24hs devolvemos">Antes de las 24hs devolvemos el 20%</option>
        <option value="Antes de las 48hs devolvemos el 40%">Antes de las 48hs devolvemos el 40%</option>
        <option value= "Antes de las 72hs devolvemos el 20%">Antes de las 72hs devolvemos el 20%</option>
        <option value="Cancelacion Gratuita">Cancelacion Gratuita</option>
          
                   </select>
                </div>
              
          
            </div>
              <!-- /.col -->
         

      

        
               
                        
                <!-- /.form-group -->
        
               
                <!-- /.form-group -->
              </div>
              <!-- /.col -->

<!--arranca el mapa--> 
             
             <h4><label class="m-2 text-dark">CARGUE LAS COMISIONES DE ESTA SALIDA</label><h4>
        <div class="form-group form-inline">
               <h5><label>Comision Reservate</label><h5>

               <div style="padding: 5px;">
            </div>
                  <input name="rSocial" class="form-control select2bs4" style="width: 30%;" placeholder="Valor de Tarifa" required>
                </div>

   <div class="form-group form-inline"style="padding: 5px";>
    <h5><label>COMISION VENTA</label><h5>

                  <input name="rSocial" class="form-control select2bs4" style="width: 30%;" placeholder="Valor de Tarifa" required>
                </div>

<div class="form-group form-inline">
               <h5><label>COMISION COMPENSATORIA</label><h5>

               <div style="padding: 5px;">
            </div>
                  <input name="rSocial" class="form-control select2bs4" style="width: 30%;" placeholder="Valor de Tarifa" required>
                </div>

            </div>

            <!-- /.row -->
          </div>
          <!-- /.card-body -->
        
          <div class="card-footer">

            <div align="left"> <button type="submit" id="uploadfiles" value="Crear servicio" class="btn btn-primary"><i class="fa fa-floppy-o" aria-hidden="true"></i> + AGREGAR SALIDA</button>

            </div>

            <div align="right">
            <button type="submit" id="uploadfiles" value="Crear servicio" class="btn btn-success"><i class="fa fa-floppy-o" aria-hidden="true"></i> Continuar</button> 
            <a href="servicios.php" class="btn btn-danger" ><i class="fa fa-times" aria-hidden="true"></i>

           Salir sin guardar</a>
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