<?php 



include("includes/header.php");

include("includes/navbar.php");

include("includes/sidebar.php");

require("classes/functions.php");

require("classes/categoria.php");

require("classes/destinos.php");

require("classes/blog.php");

require("classes/fotos_blog.php");

require("classes/texto_miniaturas_blog.php");



if (!$_SESSION["login"]["rol"]==1) {

  alertar("Usted no tiene acceso a esta seccion del software", "error");

  redireccionarLento("index");

exit();

}

 $editar=0;

    $idPost='';

    $articulo='';

    $titulo='';

    $idDestino='';

    $descripcionCorta='';

    $contenido='';

    $tipsYConsejos='';

    $observaciones='';

    $idTextoMiniaturasBlog='';









if ($_SERVER["REQUEST_METHOD"] == "POST" ) {



    if (isset($_POST["editaPost"]) ) {



       $idPost=$_POST["editaPost"];

   $editar=1;



  $articulo=getArticuloBlog($idPost)[0];

 

    $titulo=$articulo['titulo'];

    $idDestino=$articulo['idDestino'];

    $descripcionCorta=$articulo['descripcionCorta'];

    $contenido=$articulo['contenido'];

    $tipsYConsejos=$articulo['tipsYConsejos'];

    $observaciones=$articulo['observaciones'];

    $idTextoMiniaturasBlog=$articulo['idTextoMiniaturasBlog'];

if (isset($_FILEs)) {

      $fotos=altaFotosBlog($_FILES, $idPost);

}

}







    if (isset($_POST['guardaEditaPost'])) {

$idPost=$_POST["guardaEditaPost"];

$editar=1;

$titulo=$_POST["titulo"];

$idDestino=$_POST["idDestino"];

$descripcionCorta=$_POST["descripcionCorta"];

$contenido=$_POST["contenido"];

$tipsYConsejos=$_POST["tipsYConsejos"];

$observaciones=$_POST["observaciones"];

$idTextoMiniaturasBlog=$_POST["idTextoMiniaturasBlog"];



        $resul=updatePostBlog($idPost, $titulo, $idDestino, $descripcionCorta, $contenido, $tipsYConsejos, $observaciones, $idTextoMiniaturasBlog);

        if ($resul>0) {



             alertar("Articulo actualizado correctamente...","success");

        }

      



    }

















    if (isset($_POST['altaPost']) ) {



        

    $titulo=$_POST['titulo'];

    $idDestino=$_POST['idDestino'];

    $descripcionCorta=$_POST['descripcionCorta'];

    $contenido=$_POST['contenido'];

    $tipsYConsejos=$_POST['tipsYConsejos'];

    $observaciones=$_POST['observaciones'];

    $idTextoMiniaturasBlog=$_POST['idTextoMiniaturasBlog'];

         $idPost=setArticuloBlog($titulo, $idDestino, $descripcionCorta, $contenido, $tipsYConsejos, $observaciones, $idTextoMiniaturasBlog);

        if ($idPost>0) {



    alertar("Articulo dado de alta correctamente...","success");

    $fotos=altaFotosBlog($_FILES, $idPost);

    redireccionar("blogLista.php");

        }

        else{

            alertar("Error","error");

        }

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



                    <h1 class="m-0 text-dark">Carga de Blog</h1>



                </div><!-- /.col -->



                <div class="col-sm-6">



                    <ol class="breadcrumb float-sm-right">



                        <li class="breadcrumb-item"><a href="#">Blog</a></li>



                        <li class="breadcrumb-item active">Nuevo Articulo</li>



                    </ol>



                </div><!-- /.col -->



            </div><!-- /.row -->



        </div><!-- /.container-fluid -->



    </div>



    <section class="content">



        <div class="container-fluid">



            <!-- SELECT2 EXAMPLE -->



            <div class="card card-default">



                <div class="card-header">



                    <h3 class="card-title">Nuevo Articulo</h3>



                    <form method="post" enctype="multipart/form-data">



                        <div class="card-tools">



                            <button type="button" class="btn btn-tool" data-card-widget="collapse">



                                <i class="fas fa-minus"></i>



                            </button>



                            <button type="button" class="btn btn-tool" data-card-widget="remove">



                                <i class="fas fa-remove"></i>



                            </button>



                        </div>



                        <!-- </div>



                        /.card-header -->



                        <div class="card-body">



                            <div class="row  clearfix ">



                                <div class="col-md-6">



                                    <div class="form-group">



                                        <label>Titulo del Articulo</label>



                                        <input name="titulo" id="txtNomEvt" class="form-control select2bs4" style="width: 100%;" placeholder="¿Como se llama el articulo?" value="<?=$titulo;?>" required>



                                    </div>



                                </div>



                                <div class="col-md-6">



                                    <div class="form-group">



                                    



                                        <label>Destino</label> 



                                        <select name="idDestino" class="form-group form-control" 



                                            placeholder="Categoria" required> 



                                        <?php



                                            $destinos=getDestinos();



                                        for ($i=0; $i < count($destinos); $i++) { 

                                            $selected='';

                                            if ($destinos[$i]['idDestino']==$idDestino) {

                                                $selected='selected';

                                            }



                                            ?>



                                           



                                             <option value="<?= $destinos[$i]['idDestino']?>" <?=$selected?> ><?= $destinos[$i]["nombre"]?></option>                   



                                              <?php } ?>                                                     



                                        </select>



                                    </div>



                                </div>







                                    <!-- /.form-group -->



                                </div>



                                <!-- /.col -->



                                <div class="col-md-12">



                                    <div class="form-group">



                                        <label>Breve descripcion del Articulo</label>



                                        <textarea name="descripcionCorta"  placeholder="Ejemplo: (Cena Show con Orquesta y cantantes de Tango en vivo...)"> <?=$descripcionCorta;?> </textarea>



                                    </div>



                                </div>



                                <div class="col-md-12">



                                    <div class="form-group">



                                        <label>Articulo</label>



                                        <textarea rows="5" name="contenido" id="txtDescripcion"><?=$contenido?></textarea>



                                    </div>



                                </div>



                                <div class="col-md-12">



                                    <div class="form-group">



                                        <label>Tips y cosejos</label>



                                        <textarea rows="5" name="tipsYConsejos" id="txtDocumentacionViajero" class="form-control"> <?=$tipsYConsejos?> </textarea>



                                    </div>



                                </div>



                                <div class="col-md-12">



                                    <div class="form-group">



                                        <label>Observaciones</label>



                                        <textarea rows="5" name="observaciones" id="txtObservaciones" class="form-control"> <?=$observaciones?> </textarea>



                                    </div>



                                </div>



                                <div class="col-md-12">



                                    <div class="form-group">



                                        <input type="file" name="file[]" class="file-input form-control-file" multiple



                                            id="gallery-photo-add" />



                                    </div>



                                </div>



                               



                                <div class="col-md-6">



                                    <div class="form-group">



                                             <label>Texto de la Miniatura Blog</label>



                                 <select name="idTextoMiniaturasBlog" class="form-control" required> 

  

                                            <?php 



                                        $textMiniaturas= getTextosMiniaturasBlog();



                                        for ($i=0; $i < count($textMiniaturas); $i++) {

                                        $selected='';

                                        if ($textMiniaturas[$i]["idTextoMiniaturasBlog"]==$idTextoMiniaturasBlog) {

                                           $selected='selected';

                                        }

                                         ?>

   

                                <option value="<?=$textMiniaturas[$i]["idTextoMiniaturasBlog"];?>" <?=$selected?>>



                                                        <?=$textMiniaturas[$i]["texto"];?></option>



                                   



                                        



                                        <?php } ?>          



                                        



                                       </select>



                                    </div>



                                </div>



                            </div>



                        </div>







                        <div class="card-footer">



                            <div align="center">

<?php if ($editar) {

?>

   <button type="submit" id="uploadFiles" name="guardaEditaPost" value="<?=$idPost?>" class="btn btn-success">



                                    <i class="fa fa fa-save" aria-hidden="true"></i> Guardar



                                </button>



<?php

}else{

    ?>



                      <button type="submit" id="uploadFiles" value="Crear servicio" name="altaPost" class="btn btn-success">



                                    <i class="fa fa-save" aria-hidden="true"></i> Guardar



                                </button>

    <?php

} ?>

                              



                                <a href="blogLista.php" class="btn btn-danger">



                                    <i class="fas fa-arrow-left" aria-hidden="true"></i> Voltar



                                </a>



                            </div>



                        </div>



                    </form>



                </div>



            </div>



            <!-- /.card -->



   



        <script src="https://cdn.tiny.cloud/1/tmziljuhbvkgvh6s3nraitzg8kqwidrdhvdwhhna089a987b/tinymce/6/tinymce.min.js" referrerpolicy="origin">



           </script>



            <script>

    tinymce.init({

      selector: 'textarea',

       plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount ',

      toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',

      tinycomments_mode: 'embedded',

      tinycomments_author: 'Author name',

      mergetags_list: [

        { value: 'First.Name', title: 'First Name' },

        { value: 'Email', title: 'Email' },

      ], language : 'pt_BR'

    });

  </script>



            <!-- /.row -->



        </div><!-- /.container-fluid -->



    </section>



</div>



<?php include "includes/footer.php";?>