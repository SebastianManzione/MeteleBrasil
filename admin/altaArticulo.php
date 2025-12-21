<?php 

        include("includes/header.php");
        include("includes/navbar.php");
        include("includes/sidebar.php");
        require("classes/functions.php");
        require("classes/categoria.php");
        require("classes/texto_miniaturas.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['txtNomEvt'])) {
    require("classes/servicio.php");
    require("classes/fotos_servicio.php");

    //$idUsuario=$_SESSION['login']['idUsuario'];
    $idUsuario=1;
$idServicio=altaServicio($_POST['txtNomEvt'], $_POST['selCategoria'],  $_POST['txtDescripcion'],  $_POST['txtDescripcionCorta'],  $_POST['txtDocumentacionViajero'],  $_POST['txtObservaciones'],  $_POST['idTextoMiniatura'],$idUsuario );

$_SESSION["altaServicio"] = $idServicio;

$fotos=altaFotosServicio($_FILES, $idServicio);
alertar("Servicio dado de alta correctamente...","success");

     



redireccionar("altaSalidas.php");
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
                                        <input name="txtNomEvt" id="txtNomEvt" class="form-control select2bs4" style="width: 100%;" placeholder="¿Como se llama la actividad?" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                    
                                        <label>Destino</label>
                                          <select name="selCategoria" class="form-group form-control" id="selCategoria"
                                            placeholder="Categoria" required>   
                                                                
                                                                                                   
                                        </select>
                                    </div>
                                </div>

                                    <!-- /.form-group -->
                                </div>
                                <!-- /.col -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Breve descripcion del Articulo</label>
                                        <textarea name="txtDescripcion"  placeholder="Ejemplo: (Cena Show con Orquesta y cantantes de Tango en vivo...)"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Articulo</label>
                                        <textarea rows="5" name="articulo" id="txtDescripcion"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Tips y cosejos</label>
                                        <textarea rows="5" name="txtDocumentacionViajero" id="txtDocumentacionViajero" class="form-control"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Observaciones</label>
                                        <textarea rows="5" name="txtObservaciones" id="txtObservaciones" class="form-control"></textarea>
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
                                       <select name="idTextoMiniatura" id="idTextoMiniatura" class="form-control" required>
                                       		<?php 
                                    	$textMiniaturas= getTextosMiniaturas();
                                    	for ($i=0; $i < count($textMiniaturas); $i++) { ?>
                                    		                             <option value="<?=$textMiniaturas[$i]["idTextoMiniaturas"];?>">
                                                        <?=$textMiniaturas[$i]["texto"];?></option>;
                                   
                                    	
                                    	<?php } ?>          
                                        
                                       </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <div align="center">
                                <button type="submit" id="uploadFiles" value="Crear servicio" class="btn btn-success">
                                    <i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar
                                </button>
                                <a href="servicios.php" class="btn btn-danger">
                                    <i class="fa fa-times" aria-hidden="true"></i> Salir sin guardar
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.card -->
   
            <script src="https://cdn.ckeditor.com/4.15.0/standard/ckeditor.js"></script>
            <script type="text/javascript">
                CKEDITOR.replace('txtDescripcionCorta');
                CKEDITOR.replace('txtDocumentacionViajero');
                CKEDITOR.replace('txtDescripcion');
                CKEDITOR.replace('txtObservaciones'); 
            </script>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
</div>
<?php include "includes/footer.php";?>