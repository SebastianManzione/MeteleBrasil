<?php 

//ini_set('display_errors', 1);

//ini_set('display_startup_errors', 1);

//error_reporting(E_ALL);

// Verificar permisos de acceso ANTES de cualquier salida
require_once(__DIR__ . "/classes/permisos.php");
require_once(__DIR__ . "/includes/permisos_helper.php");
$permisos = new PermisosManager($GLOBALS['pdo'], $_SESSION['login'] ?? []);
$permisos->verificarAcceso('comisionesLista');

include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");


require("classes/functions.php");

require("classes/categoria.php");

require("classes/texto_miniaturas.php");

require("classes/tipos_tarifa.php");

require("classes/accesibilidad.php");

require("classes/comision_prestador.php");

require("classes/idiomas.php");

require("classes/edades.php");

require("classes/destinos.php");

 require("classes/servicio.php"); 

 require("classes/fotos_servicio.php"); 

 require("classes/prestador.php"); 

 require("classes/usuario.php"); 

  require("classes/comisiones.php"); 

   require("classes/reserva.php"); 

$usuarios=getUsuariosVendedores();

$totalComisionesVendedor=0;

$totalComisionesSistema=0;





 if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["verComoUsuario"]) ) {



$idUsuarioSeleccionado=$_POST['idUsuarioSeleccionado'];

$comisiones=getReservasConfirmadasIdUsuarioCupon($_POST['idUsuarioSeleccionado']);







} 



else if ($_SESSION["login"]["rol"]==1 && !isset($_POST["verComoUsuario"])) {

 $comisiones=getReservasConfirmadasIdUsuarioCupon(-5);

    }

else{

   $comisiones=getReservasConfirmadasIdUsuarioCupon($_SESSION['login']['idUsuario']);

}



$idMoneda="";


 ?>


 <!-- Content Wrapper. Contains page content -->



<div class="content-wrapper">



    <!-- Content Header (Page header) -->



    <div class="content-header">



        <div class="container-fluid">



            <div class="row mb-2">



                <div class="col-sm-6">



                    <h1 class="m-0 text-dark">Lista De Comisiones</h1>







                </div><!-- /.col -->



                <div class="col-sm-6">



                    <ol class="breadcrumb float-sm-right">



                        <li class="breadcrumb-item"><a href="#"><?=$lang["servicios"];?></a></li>



                        <li class="breadcrumb-item active">Lista De Comisiones</li>



                    </ol>



                </div><!-- /.col -->



            </div><!-- /.row -->



        </div><!-- /.container-fluid -->



    </div>



    <section class="content">



        <div class="container-fluid">



            <!-- SELECT2 EXAMPLE -->



            <!-- SELECT2 EXAMPLE -->



           



                                    



 <hr color="green" size=0.5 width="100%"> 











 <div class="card-header">

<?php if ($_SESSION['login']["idUsuario"]!=1) {

   $usuario=getUsuario($_SESSION['login']["idUsuario"]);

  ?>

 <h3 class="card-title">Usuario vendedor <?=$usuario[0]['usuario'];?> </h3>

 <?php }

 ?>

               

<?php 





if ($_SESSION['login']["idUsuario"]==1) {

?>

<form method="post">

  <select name="idUsuarioSeleccionado">

    <option value="-5">METELEBRASIL</option>

    <?php 

 

    foreach ($usuarios as $key => $value) {

      $selected= '';

   if ($value['idUsuario'] == $idUsuarioSeleccionado) {

    $selected= 'selected';

   }

      ?>



 <option value="<?=$value['idUsuario']?>" <?=$selected;?>><?=$value["usuario"];?> </option>

      <?php

    } ?>

    

  </select>

 

  <button class="btn btn-success" name="verComoUsuario">Ver como...</button>

</form>


<?php

} ?>





                <div class="card-tools">



                  <div class="input-group input-group-sm" style="width: 150px;">



                    <input type="text" name="table_search" class="form-control float-right" placeholder="Search">


                    <div class="input-group-append">



                      <button type="submit" class="btn btn-default">



                        <i class="fas fa-search"></i>



                      </button>



                    </div>



                  </div>



                </div>



              </div>



   <!-- /.card -->







      



              <!-- /.card-header -->



      <div class="card-body">



                <table id="tabla_servicios" class="table table-bordered table-striped">



                  <thead>



                  <tr>



                      <th>Cod.Reserva</th>
                      <th>Código Voucher Servicio</th>
                      <th>Nombre</th>
                      <th>Fechas</th>
                      <th>Responsable de Reserva</th>
                      <th>Monto Total sin IVA</th>
                      <th>Tipo de Tarifa</th>
                      <th>Monto por Registro</th>
                      <th>Comisión</th>



                       <!-- /.Descripcion corta -->



                  </tr>



                  </thead>



                  <tbody>

<?php 

 $comisionIndividual=0;
//print_r($_SESSION);
foreach ($comisiones as $key => $value) {



  $horarios=getReservaHorariosInnerHorarios($value['idReserva']);



  foreach ($horarios as $clave => $valor) {

   

 $monedaSel=$value['monedaSel'];

   $reservaTarifas=(getReservaTarifas($valor['idReservaHorarios']));

   $servicio=getServicio($valor['idServicioSeleccionado']);

   foreach ($reservaTarifas as $claveTarifas => $valorTarifas) {


    $comisionIndividual=ConvierteMoneda($monedaSel,$_SESSION["moneda_sel"], $valorTarifas['comisionVendedor']);
 
 
     $totalComisionesVendedor+=$comisionIndividual;

     // Tipo de tarifa (ahora obtenemos el nombre de la tarifa)
     $tipoTarifa = $valorTarifas['nombreTarifa'] ?? $valorTarifas['nombre'] ?? 'Sin especificar';

     // Monto por registro (valorTarifa convertido)
     $montoPorRegistro = ConvierteMoneda($monedaSel, $_SESSION["moneda_sel"], $valorTarifas['valorSinIva']);


      ?>



    <tr><a ></a>


   <td>#<?=$value["codigoAmigable"]?></td>
               <td><?=$valor["CodigoVoucherServicio"] ?? "N/A";?></td>
               <td><?=$servicio[0]["nombre_servicio"]?></td>
               <td><?=$value["fechaAlta"] ?? "N/A";?></td>
               <td><?=$value["nombreResponsable"].' '.$value["apellidoResponsable"];?></td>
               <td><?=$_SESSION["moneda_sel_sym"].(($value['total'] ?? 0) - ($value['impuestos'] ?? 0));?></td>
               <td><?=$tipoTarifa;?></td>
               <td><?=$_SESSION["moneda_sel_sym"].number_format($montoPorRegistro, 2);?></td>
               <td><?=$_SESSION["moneda_sel_sym"].$comisionIndividual;?></td>



                                  



                  </tr>



      <?php

   }

  }

  //print_r($test);

}



echo "<h3>total de comisiones del vendedor a cobrar: ".$_SESSION["moneda_sel_sym"].$totalComisionesVendedor.'</h3>';

 ?>

              <div class="text-right">
                    <button type="button" id="btnDescargarCSV" class="btn btn-success">
                        <i class="fas fa-download"></i> Descargar CSV
                    </button>
                </div>







             



           



                  </tbody>



                </table>


<script type="text/javascript">

  

   $('#tabla_servicios').DataTable({

      "paging": true,

      "stateSave": true,

      "lengthChange": true,

      "searching": true,

      "ordering": true,

      "info": true,

      "autoWidth": true,

    });

// Agregar funcionalidad al botón de descarga CSV
$('#btnDescargarCSV').on('click', function() {
    // Crear un formulario temporal para enviar POST con los mismos parámetros
    var form = $('<form>', {
        method: 'POST',
        action: 'export_comisiones_csv.php',
        target: '_blank'
    });

    // Agregar los mismos parámetros que se usan para filtrar los datos
    <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["verComoUsuario"])): ?>
        form.append($('<input>', {
            type: 'hidden',
            name: 'verComoUsuario',
            value: '1'
        }));
        form.append($('<input>', {
            type: 'hidden',
            name: 'idUsuarioSeleccionado',
            value: '<?= $_POST['idUsuarioSeleccionado'] ?>'
        }));
    <?php endif; ?>

    // Agregar al body y enviar
    $('body').append(form);
    form.submit();
    form.remove();
});

</script>







                                



                            </div>              <!-- /.card-body -->



            </div>



            <!-- /.card -->



          </div>



          <!-- /.col -->



        </div>



        <!-- /.row -->



      </div>



      <!-- /.container-fluid -->



    </section>



    <!-- /.content -->



  </div>



              



<!-- /.card-body -->









              <!-- /.card-body -->





            </div>



            <!-- /.card -->



          </section>



          <!-- /.Left col -->



          <!-- right col (We are only adding the ID to make the widgets sortable)-->



          <section class="col-lg-5 connectedSortable">







            <!-- /.card -->



          </section>



          <!-- right col -->



        </div>



        <!-- /.row (main row) -->



      </div><!-- /.container-fluid -->



   





                </form>



            </div>



        </div>



          </div>



            </div>



    </section>



</div>



<script type="text/javascript">



  $("#formAltaSalidas").submit(function(e){



    e.preventDefault();



  });



  

// Función para descargar CSV
$('#btnDescargarCSV').click(function() {
  // Crear un formulario temporal para enviar los mismos datos que se usan en la página
  var formData = new FormData();
  
  // Copiar los datos del formulario de filtro si existen
  var formElements = document.querySelectorAll('form input, form select');
  formElements.forEach(function(element) {
    if (element.name && element.value) {
      formData.append(element.name, element.value);
    }
  });
  
  // Mostrar indicador de carga
  var btnOriginal = $(this).html();
  $(this).html('<i class="fas fa-spinner fa-spin"></i> Generando...').prop('disabled', true);
  
  // Crear una solicitud fetch para descargar el archivo
  fetch('export_comisiones_csv.php', {
    method: 'POST',
    body: formData
  })
  .then(response => {
    if (!response.ok) {
      throw new Error('Error en la respuesta del servidor');
    }
    return response.blob();
  })
  .then(blob => {
    // Crear URL del blob y descargar
    var url = window.URL.createObjectURL(blob);
    var a = document.createElement('a');
    a.href = url;
    a.download = 'comisiones_' + new Date().toISOString().slice(0, 19).replace(/:/g, '-') + '.csv';
    document.body.appendChild(a);
    a.click();
    window.URL.revokeObjectURL(url);
    document.body.removeChild(a);
    
    // Restaurar botón
    $(this).html(btnOriginal).prop('disabled', false);
    
    // Mostrar mensaje de éxito
    Swal.fire({
      title: '¡Éxito!',
      text: 'Archivo CSV descargado correctamente',
      icon: 'success',
      timer: 2000,
      showConfirmButton: false
    });
  })
  .catch(error => {
    console.error('Error:', error);
    
    // Restaurar botón
    $(this).html(btnOriginal).prop('disabled', false);
    
    // Mostrar mensaje de error
    Swal.fire({
      title: 'Error',
      text: 'No se pudo descargar el archivo CSV',
      icon: 'error',
      confirmButtonText: 'OK'
    });
  });
});



</script>











  <?php 



  include("includes/footer.php"); ?>