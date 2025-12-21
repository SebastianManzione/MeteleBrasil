<?php 





include("includes/header.php");

include("includes/navbar.php");

include("includes/sidebar.php");

require("classes/functions.php");

require("classes/prestador.php");

require("classes/solicitudes.php");
require("classes/tipos_solicitud.php");
if (!$_SESSION["login"]["rol"]==1) {

  alertar("Usted no tiene acceso a esta seccion del software", "error");

  redireccionarLento("index");
exit();
}








 ?>

  <!-- Content Wrapper. Contains page content -->

  <div class="content-wrapper">

    <!-- Content Header (Page header) -->

    <div class="content-header">

      <div class="container-fluid">

        <div class="row mb-2">

          <div class="col-sm-6">

            <h1 class="m-0 text-dark">Solicitudes</h1>

          </div><!-- /.col -->

          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right">

              <li class="breadcrumb-item"><a href="#">Home</a></li>

              <li class="breadcrumb-item active">Solicitudes</li>

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

            <h3 class="card-title">Solicitudes</h3>



            <div class="card-tools">

              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>

              <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>

            </div>

          </div>

          <!-- /.card-header -->

          <div class="card-body">

           



              <button type="button" class= "btn btn-secondary btn-lg btn-block" data-card-widget="collapse">Lista de solicitudes</button> 

</div>







           <div class="card-body">

            <div class="row">

                  <div class="table-responsive">  

               <table id="#collapseExample" class="table table-bordered table-striped">

 

                <thead>

         

                    <th>T.Solicitud</th>

                    <th>Email </th>        
      <th>Password </th>  
                    <th>nombre_agencia</th> 

                    <th>descripcion</th>  

                      <th>T.Agencia</th> 

                        <th>Nombre</th> 

                          <th>Cargo</th> 

                      <th>Whatsapp</th>

                    <th>ciudad</th>
       <th>estado</th>

       <th>destino que opera</th>

       <th>fecha alta</th>

                </thead> 

         



 





<?php



$solicitudes=getSolicitudes();


for($i=0;$i < count($solicitudes); $i++){

$tipo_solicitud=getTipoSolicitud($solicitudes[$i]["idTipoSolicitud"]);
$email=$solicitudes[$i]["email"];
$password=$solicitudes[$i]["password"];
$nombre_agencia=$solicitudes[$i]["nombre_agencia"];
$descripcion_agencia=$solicitudes[$i]["descripcion_agencia"];
$tipo_de_agencia=$solicitudes[$i]["tipo_de_agencia"];
$nombre=$solicitudes[$i]["nombre"];
$cargo=$solicitudes[$i]["cargo"];
$whatsapp=$solicitudes[$i]["whatsapp"];
$ciudad=$solicitudes[$i]["ciudad"];
$estado=$solicitudes[$i]["estado"];
$destino_que_opera=$solicitudes[$i]["destino_que_opera"];
$fecha_alta=$solicitudes[$i]["fecha_alta"];







    ?>

    

    <tr> 

      <form method="POST">

        <input type="hidden" name="idUsuario" value="<?=$idUsuario;?>">

 

   <td> <?= $tipo_solicitud[0]["nombre"]; ?> </td>



   <td> <?= $email; ?> </td>
      <td> <?= $password; ?> </td>
  <td> <?= $nombre_agencia; ?> </td>
    <td> <?= $descripcion_agencia; ?> </td>
      <td> <?= $tipo_de_agencia; ?> </td>
        <td> <?= $nombre; ?> </td>
          <td> <?= $cargo; ?> </td>
            <td> <?= $whatsapp; ?> </td>
              <td> <?= $ciudad; ?> </td>
                <td> <?= $estado; ?> </td>     
                 <td> <?= $destino_que_opera; ?> </td>





   <td> <?=  $fecha_alta ?> </td>

   

      </form>

</tr> 

    

    <?php

}

?>





</table>

    </div> </div> </div>





<script type="text/javascript">







function uploadForm(){



$("#formulario").submit();

}

            function borraPrestador(idPrestador){

                      

var parametros={"borraPrestador" : idPrestador};   

Swal.fire({

title: 'Esta seguro?',

text: 'Esta accion no se puede revertir!',

icon: 'warning',

showCancelButton: true,

confirmButtonColor: '#3085d6',

cancelButtonColor: '#d33',

confirmButtonText: 'Sí, borrar!'

}).then((result) => {



if (result.value) {

 $.post("./ctrl/ctrl_prestador.php",

parametros,

function(data, status){

console.log(data);

 if (data>0) {

  location.href = 'prestadores.php';

 }

});

 Swal.fire(

   'Eliminado!',

   'El prestador se elimino.',

   'success'

 )

}

})   

     

       }





           </script>

         

                </tfoot>

              </table>

              <!-- /.col -->

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