<?php 











include("includes/header.php");



include("includes/navbar.php");



include("includes/sidebar.php");



require("classes/functions.php");



require("classes/agencia.php");



require("classes/usuario.php");



if (!$_SESSION["login"]["rol"]==1) {



  alertar("Usted no tiene acceso a esta seccion del software", "error");



  redireccionarLento("index");



}



















 ?>



  <!-- Content Wrapper. Contains page content -->



  <div class="content-wrapper">



    <!-- Content Header (Page header) -->



    <div class="content-header">



      <div class="container-fluid">



        <div class="row mb-2">



          <div class="col-sm-6">



            <h1 class="m-0 text-dark">agencias</h1>



          </div><!-- /.col -->



          <div class="col-sm-6">



            <ol class="breadcrumb float-sm-right">



              <li class="breadcrumb-item"><a href="#">Home</a></li>



              <li class="breadcrumb-item active">agencias</li>



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



            <h3 class="card-title">agencias</h3>







            <div class="card-tools">



              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>



              <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>



            </div>



          </div>



          <!-- /.card-header -->



          <div class="card-body">



            <div class="row">



     


          <table id="example1" class="table-responsive table-bordered " style="white-space: nowrap; overflow-x: auto; width: 100%;">



                <thead>

                  <th>Nombre</th>

                  <th>RSocial </th> 


               

                  <th>Acciones</th>

                </thead> 

     

<tbody>





<?php



$agencias=getagencias();



for($i=0;$i < count($agencias); $i++){



 $idAgencia=$agencias[$i]["idAgencia"];



 $idUsuario=$agencias[$i]["idUsuario"];



 $usuario=getUsuario($idUsuario);



    ?>



    



  <tr> 



   <td> <?= $agencias[$i]["nombre"]; ?> </td>

   <td> <?= $agencias[$i]["razonSocial"]; ?> </td>




   <td><form method="post" action="altaAgencia">

              <button name="editaAgencia" value="<?=$idAgencia;?>" type="submit" class="btn btn-success">Editar</button>

        </form>

              <a class="btn-sm btn-danger"onclick="borraAgencia('<?=$idAgencia;?>')"> Eliminar</a>

    </td>



  </tr> 



    



    <?php



}



?>







</tbody>







    



<script type="text/javascript">



function uploadForm(){



$("#formulario").submit();



}



            function borraAgencia(idAgencia){



                      



var parametros={"borraAgencia" : idAgencia};   



Swal.fire({



title: 'Esta seguro?',



text: 'Esta accion no se puede revertir!',



icon: 'warning',



showCancelButton: true,



confirmButtonColor: '#3085d6',



cancelButtonColor: '#d33',



confirmButtonText: 'Sí, borrar!'



}).then((result) => {







if (result.value) {



 $.post("./ctrl/ctrl_agencia.php",



parametros,



function(data, status){



console.log(data);



 if (data>0) {



  location.href = 'agencias.php';



 }



});



 Swal.fire(



   'Eliminado!',



   'El agencia se elimino.',



   'success'



 )



}



})   



     



       }











           </script>



         



             



              </table>



              <!-- /.col -->



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







        <!-- /.row -->



      </div><!-- /.container-fluid -->



    </section>



        </div>



        <!-- /.row (main row) -->



      </div><!-- /.container-fluid -->



    </section>



    <!-- /.content -->



  </div>



  <!-- /.content-wrapper -->



  <?php 



  include("includes/footer.php"); ?>