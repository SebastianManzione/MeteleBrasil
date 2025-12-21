



<?php 





include("includes/header.php");

include("includes/navbar.php");

include("includes/sidebar.php");

require("classes/functions.php");

require("classes/prestador.php");

require("classes/usuario.php");

require("classes/reserva.php");

require("classes/salidas.php");

require("classes/servicio.php");

require("classes/comprobantes.php");

require("classes/convierte_monedas.php");


if (!$_SESSION["login"]["rol"]==1) {

  alertar("Usted no tiene acceso a esta seccion del software", "error");

  redireccionarLento("index");

}



if ($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST["updateMonedas"])) {


$pesoArg=$_POST["pesoArg"];
$rs=$_POST["rs"];
$guarani=$_POST["guarani"];
$pesoCh=$_POST["pesoCh"];
$euro=$_POST["euro"];
$resu=updateCotizacionMonedas($pesoArg, $rs, $guarani, $pesoCh, $euro);
if ($resu>0) {
  alertar("Cambio actualizado con exito", "success");
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

            <h1 class="m-0 text-dark">Administrador de monedas</h1>

          </div><!-- /.col -->

          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right">

              <li class="breadcrumb-item"><a href="#">Moneda</a></li>

              <li class="breadcrumb-item active">Administrador de monedas</li>

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

                





        



                                    

                                            <h5 class="modal-title" id="exampleModalLabel">Cargue aqui el cambio de moneda</h5>

                                          

                                          </div>


         <form method="post">   
                                          <div class="modal-body">

                                
                                      <div class="col-12">
                                        <?php 
                                          $monedas=getCotizacionMonedas();

                                      
                                          ?>
                                          <div class="col-6">
                                          <label>Dolar</label>
                                          <input type="number" name="dolar" value="<?=$monedas[0]['dolar']?>" step="0.01" disabled>
                                          </div>   

                                           <div class="col-6">
                                          <label>Peso Argentino</label>
                                          <input type="number" name="pesoArg"  value="<?=$monedas[0]['pesoArg']?>" step="0.01">
                                          </div>   

                                           <div class="col-6">
                                          <label>Reales</label>
                                          <input type="number" name="rs"  value="<?=$monedas[0]['rs']?>" step="0.01">
                                          </div>   

                                           <div class="col-6">
                                          <label>Guaranies</label>
                                          <input type="number" name="guarani"  value="<?=$monedas[0]['guarani']?>" step="0.01">
                                          </div>     
                                               <div class="col-6">
                                          <label>pesoCh</label>
                                          <input type="number" name="pesoCh"  value="<?=$monedas[0]['pesoCh']?>" step="0.01">
                                          </div>   

                                           <div class="col-6">
                                          <label>Euros</label>
                                          <input type="number" name="euro"  value="<?=$monedas[0]['euro']?>" step="0.01">
                                  </div>
                                   
                                      </div>
                                      <button class= "btn btn-success" name="updateMonedas">Guardar</button>

                                    </form>
                                       

                                     

                                  






</div>

<!-- /.card-header -->


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