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
$idServicio=altaServicio($_POST['txtNomEvt'], $_POST['selCategoria'],  $_POST['txtDescripcion'],  $_POST['txtDescripcionCorta'],  $_POST['txtDocumentacionViajero'],  $_POST['txtObservaciones'],  $_POST['idTextoMiniatura'],$idUsuario  );

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
                    <h1 class="m-0 text-dark">Nuevo Usuario</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Nuevo Usuario</li>
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
                    <h3 class="card-title">Nuevo Usuario</h3>
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
                                        <label>Nombre</label>
                                        <input name="txtNombre" id="txtNombre" class="form-control select2bs4" style="width: 100%;" placeholder="¿Nombre Usuario?" required>
                                    </div>
                                </div>

<div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" name="txtEmail" id="txtEmail" class="form-control select2bs4" style="width: 100%;" placeholder="correo electronico" required>
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Contraseña</label>
                                        <input type="password" name="txtPassword" id="txtNomEvt" class="form-control select2bs4" style="width: 100%;" placeholder="¿Contraseña?" required>
                                    </div>
                                </div>

<div class="col-md-6">
                                    <div class="form-group">
                                        <label>Repetir Contraseña</label>
                                        <input type="password" name="txtPassword" id="txtNomEvt" class="form-control select2bs4" style="width: 100%;" placeholder="¿Contraseña?" required>
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="form-group">
                                    
                                        <label>Rol</label>
                                          <select name="selCategoria" class="form-group form-control" id="selCategoria"
                                            placeholder="Categoria" required>   

                                               	<?php 
                                    	$categorias=getCategorias();
                                
                                    	for ($i=0; $i < count($categorias); $i++) { ?>
                                    		                             <option value="<?=$categorias[$i]["idCategoria_servicio"];?>">
                                                        <?=$categorias[$i]["nombre_categoria_servicio"];?></option>;
                                    		echo " i ".;
                                    	
                                    	<?php } ?>                    
                                                                                                   
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                    
                                        <label>Foto</label>
                                    <input type="file" name="file[]" class="file-input form-control-file"
                                            id="foto" />
                                    </div>
                                </div>
                                    <!-- /.form-group -->
                                </div>
                                <!-- /.col -->
                    
                            </div>
                        </div>

                        <div class="card-footer">
                            <div align="center">
                                <button type="submit" id="uploadFiles" value="Crear servicio" class="btn btn-success">
                                    <i class="fa fa-floppy-o" aria-hidden="true"></i> Continuar
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