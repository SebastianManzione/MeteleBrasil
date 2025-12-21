
<?php 


include("includes/header.php");

include("includes/navbar.php");

include("includes/sidebar.php");

require("classes/functions.php");



if (!$_SESSION["login"]["rol"]==1) {

  alertar("Usted no tiene acceso a esta seccion del software", "error");

  redireccionarLento("index");

}



if ($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST["head"]) && isset($_POST["body"]) ) {
  $head=$_POST["head"];
  $body=$_POST["body"];

$resuUpdate=updateParametros($head, $body);


if ($resuUpdate==1) {
  alertar("actualizacion de parametros realizada con exito", "success");
}

}


$parametros=getParametros();


 ?>

  <!-- Content Wrapper. Contains page content -->

  <div class="content-wrapper">

    <!-- Content Header (Page header) -->

    <div class="content-header">

      <div class="container-fluid">

        <div class="row mb-2">

          <div class="col-sm-6">

            <h1 class="m-0 text-dark">Administrador de parametros</h1>

          </div><!-- /.col -->

          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right">

              <li class="breadcrumb-item"><a href="#">parametros</a></li>

              <li class="breadcrumb-item active">Administrador de parametros</li>

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

    


                                          <div class="modal-body">

                                            <form method="post">

                                         

                                           
     <div class="form-group">

                                                   <label>Inyectar codigo al head</label>

                                                        <textarea name="head" required class="form-control" id="message-text"><?= $parametros[0]["head"];?></textarea> 

                                                  </div>

                                              <div class="form-group">

                                                 <label>Inyectar codigo al body</label>

                                                        <textarea name="body" required class="form-control" id="message-text"><?= $parametros[0]["body"];?></textarea> 

                                                  </div>

                                            


                                            

                                          <button class="btn btn-success">Guardar</button>

                                          </form>

                                       

                                     

                                  





</div>

<!-- /.card-header -->


<!-- /.card-body -->

             







                                        

                                  

                                   








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