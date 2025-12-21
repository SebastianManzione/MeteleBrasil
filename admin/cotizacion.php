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
                                <div>
                                    <div>
<table id="tbl_impuestos">
    <thead>
            <tr>
                <th><i class="fa fa-money" aria-hidden="true"></i> Dolar </th>
                <th><i class="fa fa-money" aria-hidden="true"></i> Peso argentino </th>
                <th><i class="fa fa-money" aria-hidden="true"></i> Reales </th>
                <th><i class="fa fa-money" aria-hidden="true"></i> Guarani </th>
                <th><i class="fa fa-money" aria-hidden="true"></i> Dolar </th>
                <th><i class="fa fa-money" aria-hidden="true"></i> Dolar </th>
            </tr>
            </thead>
            <tbody>  
<?php 
/*
        $query=mysqli_query($conection,"SELECT * FROM moneda_cambio WHERE idMonedaCambio=1");
        $result=mysqli_num_rows($query);
        if ($result == 1) {
            while ($data = mysqli_fetch_array($query)) {     */   
                
?>

            <tr>
            
                <td><input type="number" step="0.01" id="txtDolar" value="<?php echo $data['dolar']; ?>" class="form-control" disabled></td>
                <td><input type="number" step="0.01" id="txtPeso" value="<?php echo $data['pesoArg']; ?>" class="form-control"></td>
                <td><input type="number" step="0.01" id="txtRs" value="<?php echo $data['rs']; ?>" class="form-control" ></td>
                <td><input type="number" step="0.01" id="txtGuarani" value="<?php echo $data['guarani']; ?>" class="form-control" ></td>
               <td><input type="number" step="0.01" id="txtGuarani" value="<?php echo $data['guarani']; ?>" class="form-control" ></td> 
               <td><input type="number" step="0.01" id="txtGuarani" value="<?php echo $data['guarani']; ?>" class="form-control" ></td>
        </tr>
                <?php/*
                }
        }
*/

 ?>     
 </tbody>
        </table>

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