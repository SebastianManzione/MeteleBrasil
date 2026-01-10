<?php 

        include("includes/header.php");
        include("includes/navbar.php");
        include("includes/sidebar.php");
        require("classes/functions.php");
        require("classes/prestador.php");
        require("classes/categoria.php");
        require("classes/texto_miniaturas.php");
        require("classes/usuario.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" ) {





$idPrestador=$_POST["selPrestador"];

$usuario=$_POST["txtNombre"];

$email=$_POST["txtEmail"];

$password=md5($_POST["txtPassword"]);

 $idVendedor=0;

if (isset($_POST["vendedor"])) {

   $idVendedor=1;

}

 $idCobrador=0;

if (isset($_POST["cobrador"])) {

   $idCobrador=1;

}

 $status=0;

if (isset($_POST["activado"])) {

   $status=1;

}



$idUsuario= altaUsuario($usuario, $email, $password, $status, $idVendedor, $idCobrador, $idPrestador);

if ($idUsuario > 0) {
    $fotos=updateFotoUsuario($_FILES, $idUsuario);
    alertar_redirect("Usuario dado de alta correctamente...","success", "usuariosLista");
    exit();
}
else if ($idUsuario == -1) {
    alertar("El email '" . htmlspecialchars($email) . "' ya está registrado. Por favor, utiliza otro email.","warning");
    redireccionarLento("usuarioAlta");
}
else {
    alertar("Error al crear el usuario. Intenta nuevamente.","danger");
    redireccionarLento("usuarioAlta");
}





    





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

                    <form method="post" enctype="multipart/form-data" onsubmit="return(validar())">

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

                                        <input type="password" name="txtPassword" id="txtPassword" class="form-control select2bs4" style="width: 100%;" placeholder="¿Contraseña?" required>

                                    </div>

                                </div>



<div class="col-md-6">

                                    <div class="form-group">

                                        <label>Repetir Contraseña</label>

                                        <input type="password" name="txtPassword2" id="txtPassword2" class="form-control select2bs4" style="width: 100%;" placeholder="¿Contraseña?" required>

                                    </div>

                                </div>





                                <div class="col-md-6">

                                    <div class="form-group">

                                    

                                        <label>Rol</label>

                                          <select name="selPrestador" class="form-group form-control"  required>   



                                               	<?php 

                                    	$prestadores=getPrestadores();

                                

                                    	for ($i=0; $i < count($prestadores); $i++) { ?>

                                  <option value="<?=$prestadores[$i]["idPrestador"];?>">

                                                        <?=$prestadores[$i]["nombre"];?></option>;

                                    	

                                    	

                                    	<?php } ?>                    

                                                                                                   

                                        </select>

                                    </div>

                                </div>

                                            <div class="col-md-6">

                                    <div class="form-group">

                                        <label>Vendedor?</label>

                             <input type="checkbox" name="vendedor">

                               <label>Cobrador?</label>

                             <input type="checkbox" name="cobrador">

                               <label>Activado?</label>

                             <input type="checkbox" name="activado" checked>

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

                function validar(){

                    if ($("#txtPassword").val()!==$("#txtPassword2").val()) {

                         alert("Las contraseñas no coinciden");

                         $("#txtPassword").focus();

                            return false;

                    }

                    else{

                       return true;

                    }

 

                }

    



            </script>

            <!-- /.row -->

        </div><!-- /.container-fluid -->

    </section>

</div>

<?php include "includes/footer.php";?>