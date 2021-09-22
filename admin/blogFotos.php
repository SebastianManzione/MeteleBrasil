



<?php 





include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");
require("classes/functions.php");
require("classes/categoria.php");
require("classes/fotos_blog.php");
require("classes/blog.php");

if (!$_SESSION["login"]["rol"]==1) {

  alertar("Usted no tiene acceso a esta seccion del software", "error");

  redireccionarLento("index");

}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["idPost"])) {
  $idPost=$_POST["idPost"];


}
  

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["portada"])) {
  $idPost=$_POST["idPost"];
  $idImgPost=$_POST["portada"];
$resul=setPortadaBlog($idImgPost, $idPost);
if ($resul>0) {
  alertar("Portada cambiada con exito", "success");
};

}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["miniatura"])) {
  $idPost=$_POST["idPost"];
  $idImgPost=$_POST["miniatura"];
$resul=setMiniaturaBlog($idImgPost, $idPost);
if ($resul>0) {
  alertar("Miniatura cambiada con exito", "success");
};

}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["altaFotos"])) {
  $idPost=$_POST["altaFotos"];

$resul=$fotos=altaFotosBlog($_FILES, $idPost);
if ($resul>0) {
  alertar("Foto agregada con exito", "success");
};

}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["eliminarFoto"])) {
  $idPost=$_POST["idPost"];
  $idImgPost=$_POST["eliminarFoto"];
$resul=borraFotoBlog($idImgPost);
if ($resul>0) {
  alertar("Foto eliminada con exito", "success");
};

}

if ($idPost<1) {
  redireccionar("serviciosLista.php");
}

$post=getArticuloBlog($idPost);
$fotos=getFotosBlogIdPost($idPost);




 ?>

  <!-- Content Wrapper. Contains page content -->

  <div class="content-wrapper">

    <!-- Content Header (Page header) -->

    <div class="content-header">

      <div class="container-fluid">

        <div class="row mb-2">

          <div class="col-sm-6">

            <h1 class="m-0 text-dark">Fotos post <?=$post[0]["titulo"];?></h1>

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



                                            <button type="submit" class= "btn btn-primary" name="altaFotos" value="<?=$idPost?>">Agregar</button>

                                            </form>

                                          </div>

                                          

                                        </div>

                                      </div>

                                    </div>



        <button type="button" class= "btn btn-secondary btn-lg btn-block" data-card-widget="collapse">Blog lista de fotos
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

  $idImgPost=$fotos[$i]["idImgPost"];
  $ruta=$fotos[$i]["ruta"];
  $portada=$fotos[$i]["portada"];
 $miniatura=$fotos[$i]["miniatura"];


?>

    <tr>

                                <td><img src="classes/imgBlog/<?=$ruta;?>" style="width: 150px;"></td>

                             
<?php if ($portada==0) {
?>
  <td><form method="post">
    <input type="hidden" name="idPost" value="<?=$idPost;?>">
    <button class="btn-sm btn-primary" name="portada" value="<?=$idImgPost?>">Bandeira</button></form></td>
<?php
}else{
  ?>
 <td><label class="success">Foto Bandeira</label></td>
  <?php
} ?>
                               
                             
<?php if ($miniatura==0) {
?>
  <td><form method="post">
    <input type="hidden" name="idPost" value="<?=$idPost;?>">
    <button class="btn-sm btn-primary" name="miniatura" value="<?=$idImgPost?>">Miniatura</button></form></td>
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
  <input type="hidden" name="idPost" value="<?=$idPost;?>">
<button type="submit" name="eliminarFoto" value="<?=$idImgPost;?>" class="btn-sm btn-danger">Retirar</button></form>
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


<form method="get" action="blogLista"><button class="btn btn-info" name="idPost" value="<?=$idPost;?>">Volver</button></form>
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