



<?php 





include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");
require("classes/functions.php");
require("classes/categoria.php");
require("classes/fotos_servicio.php");
require("classes/servicio.php");

if (!$_SESSION["login"]["rol"]==1) {

  alertar("Usted no tiene acceso a esta seccion del software", "error");

  redireccionarLento("index");

}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["idServicio"])) {
  $idServicio=$_POST["idServicio"];


}
  

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["portada"])) {
  $idServicio=$_POST["idServicio"];
  $idImgServicio=$_POST["portada"];
$resul=setPortada($idImgServicio, $idServicio);
if ($resul>0) {
  alertar("Portada cambiada con exito", "success");
};

}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["miniatura"])) {
  $idServicio=$_POST["idServicio"];
  $idImgServicio=$_POST["miniatura"];
$resul=setMiniatura($idImgServicio, $idServicio);
if ($resul>0) {
  alertar("Miniatura cambiada con exito", "success");
};

}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["altaFotos"])) {
  $idServicio=$_POST["altaFotos"];

$resul=$fotos=altaFotosServicio($_FILES, $idServicio);
if ($resul>0) {
  alertar("Foto agregada con exito", "success");
};

}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["eliminarFoto"])) {
  $idServicio=$_POST["idServicio"];
  $idImgServicio=$_POST["eliminarFoto"];
$resul=borraFoto($idImgServicio);
if ($resul>0) {
  alertar("Foto eliminada con exito", "success");
};

}

if ($idServicio<1) {
  redireccionar("serviciosLista.php");
}
$servicio=getServicio($idServicio);
$fotos=getFotosServicio($idServicio);




 ?>

  <!-- Content Wrapper. Contains page content -->

  <div class="content-wrapper">

    <!-- Content Header (Page header) -->

    <div class="content-header">

      <div class="container-fluid">

        <div class="row mb-2">

          <div class="col-sm-6">

            <h1 class="m-0 text-dark">Fotos servicio <?=$servicio[0]["nombre_servicio"];?></h1>

          </div><!-- /.col -->

          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right">

              <li class="breadcrumb-item"><a href="#">Editor de fotos de serviço
</a></li>

              <li class="breadcrumb-item active">Editor de fotos de serviço
</li>

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

               <button type="button" class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#exampleModal" data-whatever="agregarNota">Adicionar nova foto

</button>          



                                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

                                      <div class="modal-dialog" role="document">

                                        <div class="modal-content">

                                          <div class="modal-header">

                                            <h5 class="modal-title" id="exampleModalLabel">Adicionar nova foto
</h5>

                                            <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">

                                              <span aria-hidden="true">&times;</span></button>

                                          </div>



                                          <div class="modal-body">

                                            <form method="post" enctype="multipart/form-data">




                                                  <label class="col-form-label">Foto</label>
<p>
                                              <input type="file" name="file[]" class="file-input form-control-file" multiple>

 </p>                                                                                                                                   



                                            <button type="submit" class= "btn btn-primary" name="altaFotos" value="<?=$idServicio?>">Agregar</button>

                                            </form>

                                          </div>

                                          

                                        </div>

                                      </div>

                                    </div>



        <button type="button" class= "btn btn-secondary btn-lg btn-block" data-card-widget="collapse">Serviço de lista de fotos
</button>



</div>

<!-- /.card-header -->





        <div class="card-body">

            <div class="row">

                  <div class="table-responsive">   

                            

                            <table class="table" id="tablaCarrito">

                              <thead>

                                <tr>

                                  <th scope="col">imagem</th>

                                   <th scope="col">Açao</th>

                            <th scope="col">Açao</th>


                                </tr>

                              </thead>

                  



                           <tbody>

<?php 



for ($i=0; $i < count($fotos); $i++) { 

  $idImgServicio=$fotos[$i]["idImgServicio"];
  $ruta=$fotos[$i]["ruta"];
  $portada=$fotos[$i]["portada"];
 $miniatura=$fotos[$i]["miniatura"];


?>

    <tr>

                                <td><img src="classes/imgServicio/<?=$ruta;?>" style="width: 150px;"></td>

                             
<?php if ($portada==0) {
?>
  <td><form method="post">
    <input type="hidden" name="idServicio" value="<?=$idServicio;?>">
    <button class="btn-sm btn-primary" name="portada" value="<?=$idImgServicio?>">Bandeira</button></form></td>
<?php
}else{
  ?>
 <td><label class="success">Foto Bandeira</label></td>
  <?php
} ?>
                               
                             
<?php if ($miniatura==0) {
?>
  <td><form method="post">
    <input type="hidden" name="idServicio" value="<?=$idServicio;?>">
    <button class="btn-sm btn-primary" name="miniatura" value="<?=$idImgServicio?>">Miniatura</button></form></td>
<?php
}else{
  ?>
 <td><label class="success">Foto Miniatura</label></td>
  <?php
} ?>                            




<td>
<?php if ($miniatura==0 && $portada==0) {
?>
<form method="post" >
  <input type="hidden" name="idServicio" value="<?=$idServicio;?>">
<button type="submit" name="eliminarFoto" value="<?=$idImgServicio;?>" class="btn-sm btn-danger">Retirar</button></form>
<?php
} ?>


</td>

                             </tr> 



<?php

} ?>



                                            







                                           

                         </tbody>



                         </table>

                                    </div>

                                    </div>


<form method="get" action="servicioVer"><button class="btn btn-info" name="idServicio" value="<?=$idServicio;?>">Voltar</button></form>
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