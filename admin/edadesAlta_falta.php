<?php 
include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");
require("classes/reserva.php");
require("classes/usuario.php");
require("classes/visitas.php");
require("classes/servicio.php");

 ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Bienvenido (nombre del usuario)</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Administrador</a></li>
              <li class="breadcrumb-item active">Inicio</li>

            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">

<div class="container">

                <div class="row">
          <div class="col">
<div class="row justify-content-center align-items-center">
<div class="form-group">

<table id="tbl_impuestos" class="display" >
  <form method="post" action="editorTextoMiniaturas.php" onsubmit="return confirm('Esta seguro que desea agregar?');">
  <input type="text" name="TextoMiniaturas" required>
  <button  class="btn btn-success">Agregar</button>
  </form>
  <thead>
      <tr>
        
        <th>Nombre </th>
        <th>Acciones </th>
      
      </tr>
      </thead>
      <tbody>  
      <tr>

<tr>
      <td>'.$TextoMiniaturas[$i][1].'</td>
      <form method="post" action="editorCancelaciones.php" onsubmit="return confirm("Esta seguro que desea eliminar?");">
      <input type="hidden" name="eliminar" value="'.$TextoMiniaturas[$i][0].'"></input>
      
      <td><button class="btn btn-danger">Eliminar</buttton></td>

</form>


      </tr>

  ';
}

    
      
?>
<script >
  function valida(){
  
    if (confirm("Realmente desea eliminar el texto de miniaturas?")) {
      return  true;}
    else{
      return false;}
    
    
    
  }
</script>
      
    

 </tbody>
    </table>





</div>
</div>

              <!-- /.card-body -->
              <div class="card-footer clearfix">
<div class="container">

                <div class="row">
    <div class="col">
    <a href="javascript:void(0)" class="btn btn-sm btn-info float-left"> + Agregar tipo de Pax</a>
    </div>
    <div class="col+1">
      <div class="card-tools">
                  <ul class="pagination pagination-sm">
                    <li class="page-item"><a href="#" class="page-link">&laquo;</a></li>
                    <li class="page-item"><a href="#" class="page-link">1</a></li>
                    <li class="page-item"><a href="#" class="page-link">2</a></li>
                    <li class="page-item"><a href="#" class="page-link">3</a></li>
                    <li class="page-item"><a href="#" class="page-link">&raquo;</a></li>
                  </ul>
                  </div>
                  </div>

    <div class="col">
     <a href="javascript:void(0)" class="btn btn-sm btn-secondary float-right">Ver Todos</a>
    </div>


              <!-- /.card-footer -->
            
    </div>
  </div>
               
                

              </div>


 <!-- /.PAGINADO DE ORDERS-->

           
            <!-- /.card -->
          </div>
      
    
         
         
                </ul>
              </div>
              <!-- /.card-body -->
             <!-- /. <div class="card-footer clearfix">
                <button type="button" class="btn btn-info float-right"><i class="fas fa-plus"></i> Add item</button>
              </div> -->
            </div>
            <!-- /.card -->
          </section>
          <!-- /.Left col -->
          <!-- right col (We are only adding the ID to make the widgets sortable)-->
          <section class="col-lg-5 connectedSortable">

  


            <!-- /.card -->
          </section>
          <!-- right col -->
        </div>
        <!-- /.row (main row) -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <?php 
  include("includes/footer.php"); ?>