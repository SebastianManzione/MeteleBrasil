<?php 

        include("includes/header.php");
        include("includes/navbar.php");
        include("includes/sidebar.php");
        require("classes/functions.php");
        require("classes/categoria.php");
             require("classes/paises.php");
                require("classes/destinos.php");
        require("classes/texto_miniaturas.php");
        require("classes/servicio.php");
  require("classes/fotos_servicio.php");


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['actualizar'])) {
 
   $idServicio=$_GET['idServicio'];

    $idUsuario=$_SESSION['login']['idUsuario'];
 
$idServiciodd=updateServicio($_POST['txtNomEvt'], $_POST['selCategoria'],  $_POST['txtDescripcion'],  $_POST['txtDescripcionCorta'],  $_POST['txtDocumentacionViajero'],  $_POST['txtObservaciones'],  $_POST['idTextoMiniatura'],$idUsuario, $_POST['idOrigen'],$_POST['idDestino'],$_POST['idServicio'] );



$fotos=altaFotosServicio($_FILES, $idServicio);
if ($idServiciodd>0) {
   alertar("Servicio actualizado correctamente...","success");

     

redireccionar("servicioVer?idServicio=".$idServicio);
exit();
}

}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['guardar'])) {
  

    $idUsuario=$_SESSION['login']['idUsuario'];
   
$idServicio=altaServicio($_POST['txtNomEvt'], $_POST['selCategoria'],  $_POST['txtDescripcion'],  $_POST['txtDescripcionCorta'],  $_POST['txtDocumentacionViajero'],  $_POST['txtObservaciones'],  $_POST['idTextoMiniatura'],$idUsuario, $_POST['idOrigen'],$_POST['idDestino'] );

$_SESSION["altaServicio"] = $idServicio;

$fotos=altaFotosServicio($_FILES, $idServicio);
alertar("Servicio dado de alta correctamente...","success");

     



redireccionar("altaSalidas.php");
exit();
}


$textoNuevoOEditar="Nueva ";
$nombre_servicio='';
$idCategoria_servicio='';
$descripcion_corta='';
$descripcion_servicio='';
$documentacionViajero='';
$idTextoMiniaturas='';
$observaciones='';
$idOrigen="";
$idDestino="";
$idServicio=0;
        if (isset($_GET['idServicio'])) {

            $idServicio=$_GET['idServicio'];
            $textoNuevoOEditar="EDITANDO SERVICIO ".$idServicio;
$servicio=getServicio($idServicio);
$nombre_servicio=$servicio[0]['nombre_servicio'];
$idCategoria_servicio=$servicio[0]['idCategoria_servicio'];
$descripcion_corta=$servicio[0]['descripcion_corta'];
$descripcion_servicio=$servicio[0]['descripcion_servicio'];
$documentacionViajero=$servicio[0]['documentacionViajero'];
$idTextoMiniaturas=$servicio[0]['idTextoMiniaturas'];
$observaciones=$servicio[0]['observaciones'];
$idOrigen=$servicio[0]['idOrigen'];
$idDestino=$servicio[0]['idDestino'];




        }
 
  




 ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark"><?=$textoNuevoOEditar;?></h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active"><?=$textoNuevoOEditar;?></li>
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
                    <h3 class="card-title"><?=$textoNuevoOEditar?></h3>
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
                                        <label>Nombre De la Actividad</label>
                                        <input type="hidden" name="idServicio" value="<?=$idServicio;?>">
                                        <input name="txtNomEvt" id="txtNomEvt" class="form-control select2bs4" style="width: 100%;" placeholder="¿Como se llama la actividad?" value="<?=$nombre_servicio?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                    
                                        <label>Categoría</label>
                                          <select name="selCategoria" class="form-group form-control" id="selCategoria"
                                            placeholder="Categoria" required>   
                                               	<?php 
                                                
                                    	$categorias=getCategorias();
                                
                                    	for ($i=0; $i < count($categorias); $i++) { 
                                            $selected='';
                                                if ($idCategoria_servicio=$categorias[$i]['idCategoria_servicio']) {
                                                    $selected='selected';
                                                }
                                            ?>
                                    		                             <option value="<?=$categorias[$i]["idCategoria_servicio"];?>" <?=$selected;?>>
                                                        <?=$categorias[$i]["nombre_categoria_servicio"];?></option>;
                                    		echo " i ".;
                                    	
                                    	<?php } ?>                    
                                                                                                   
                                        </select>
                                    </div>
                                </div>

                                    <!-- /.form-group -->
                                </div>
                                <!-- /.col -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Breve descripción</label>
                                        <textarea rows="3" name="txtDescripcionCorta" id="txtDescripcionCorta" placeholder="Ejemplo: (Cena Show con Orquesta y cantantes de Tango en vivo...)"><?=$descripcion_corta;?></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Itinerario</label>
                                        <textarea rows="5" name="txtDescripcion" id="txtDescripcion">
                                            <?=$descripcion_servicio;?>
                                        </textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Documentación para el viajero</label>
                                        <textarea rows="5" name="txtDocumentacionViajero" id="txtDocumentacionViajero" class="form-control"><?=$documentacionViajero;?></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Observaciones</label>
                                        <textarea rows="5" name="txtObservaciones" id="txtObservaciones" class="form-control"><?=$observaciones;?></textarea>
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
                                     		 <label>Texto de la Miniatura</label>
                                       <select name="idTextoMiniatura" id="idTextoMiniatura" class="form-control" required>
                                       		<?php 
                                    	$textMiniaturas= getTextosMiniaturas();

                                    	for ($i=0; $i < count($textMiniaturas); $i++) {
                                            $selected="";
                                            if ($idTextoMiniaturas==$textMiniaturas[$i]['idTextoMiniaturas']) {
                                                $selected="selected";
                                                // code...
                                            }
                                         ?>
                                    		                             <option value="<?=$textMiniaturas[$i]["idTextoMiniaturas"];?>" <?=$selected;?>>
                                                        <?=$textMiniaturas[$i]["texto"];?></option>;
                                   
                                    	
                                    	<?php } ?>          
                                        
                                       </select>
                                    </div>
                                </div>
             <div class="col-md-6">
                                    <div class="form-group">
                                             <label>Origen</label>
                                       <select name="idOrigen" id="idOrigen" class="form-control" required>
                                            <?php 
                                        $destinos= getDestinos();
                                        for ($i=0; $i < count($destinos); $i++) { 
                                            $selected="";
                                            if ($idOrigen==$destinos[$i]["idDestino"]) {
                                                $selected="selected";
                                                // code...
                                            }
                                            $pais=getPais($destinos[$i]["idPais"]);
                                            ?>
                                                                         <option value="<?=$destinos[$i]["idDestino"];?>" <?=$selected; ?>>
                                                        <?=$destinos[$i]["nombre"];?>, <?=$destinos[$i]["estado"];?>, <?=$pais[0]["nombre"]?></option>;
                                   
                                        
                                        <?php } ?>          
                                        
                                       </select>
                                    </div>
                                </div>

                                         <div class="col-md-6">
                                    <div class="form-group">
                                             <label>Destino</label>
                                       <select name="idDestino" id="idDestino" class="form-control" required>
                                            <?php 
                                        $destinos= getDestinos();
                                        for ($i=0; $i < count($destinos); $i++) { 
                                            $selected="";
                                                  $selected="";
                                            if ($idDestino==$destinos[$i]["idDestino"]) {
                                                $selected="selected";
                                               }

                                            ?>
                                                                         <option value="<?=$destinos[$i]["idDestino"];?>" <?=$selected?>>
                                                       <?=$destinos[$i]["nombre"];?>, <?=$destinos[$i]["estado"];?>, <?=$pais[0]["nombre"]?></option>;
                                   
                                        
                                        <?php } ?>          
                                        
                                       </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <div align="center">
                                <?php   if ($_SERVER["REQUEST_METHOD"]=="GET" && isset($_GET['idServicio'])) { ?>
                                <button type="submit" id="uploadFiles" value="Crear servicio" class="btn btn-info" name="actualizar">
                                    <i class="fa fa-floppy-o" aria-hidden="true"></i> Actualizar Cambios
                                </button>
                            <?php } else{ ?>
                                       <button type="submit" id="uploadFiles" value="Crear servicio" class="btn btn-success" name="guardar">
                                    <i class="fa fa-floppy-o" aria-hidden="true"></i> Continuar
                                </button>
                            <?php }?>
                                <a href="servicios.php" class="btn btn-danger">
                                    <i class="fa fa-times" aria-hidden="true"></i> Salir sin guardar
                                </a>
                                <?php if (isset($_GET['idServicio'])) {
                                ?>
                                <a href="servicioVer.php?idServicio=<?=$_GET['idServicio'];?>" class="btn btn-primary">Volver Al Servicio</a>
                                <?php
                                } ?>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.card -->
   
            <script src="https://cdn.ckeditor.com/4.15.0/standard/ckeditor.js"></script>
            <script type="text/javascript">
                
                CKEDITOR.replace('txtDocumentacionViajero');
                CKEDITOR.replace('txtDescripcion');
                CKEDITOR.replace('txtObservaciones'); 
            </script>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
</div>
<?php include "includes/footer.php";?>