



<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");
require("classes/functions.php");
require("classes/categoria.php");


if (!$_SESSION["login"]["rol"]==1) {

  alertar("Usted no tiene acceso a esta seccion del software", "error");

  redireccionarLento("index");

}



if ($_SERVER["REQUEST_METHOD"] == "GET" ) {
$idCategoria=$_GET['idCategoria'];
$categoria=getCategoria($idCategoria);



}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["editarGuia"]) && strlen($_FILES["file"]["name"][0])>0 ) {

$idCategoria=$_POST['editarGuia'];
$editarGuia=$_POST['editarGuia'];

$resul=updateGuiaCategoria($_FILES, $editarGuia);

$categoria=getCategoria($idCategoria);

}




if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["editarCategoria"]) ) {

 
$idCategoria=$_POST['editarCategoria'];
$nombre=$_POST['nombre_categoria'];
$descripcion_categoria_servicio=$_POST['descripcion_categoria_servicio'];
$descripcionCorta_categoria_servicio=$_POST['descripcionCorta_categoria_servicio'];
$nViajeros=$_POST['nViajeros'];
$idCategoria=$_POST['editarCategoria'];

$resul=updateCategoria($idCategoria, $nombre, $descripcion_categoria_servicio, $descripcionCorta_categoria_servicio, $nViajeros);

if ($resul) {
  alertar("Categoria atualizada com sucesso", "success");
}


}

$categoria=getCategoria($idCategoria);
$nombre_categoria=$categoria[0]["nombre_categoria_servicio"];
$descripcion_categoria_servicio=$categoria[0]["descripcion_categoria_servicio"];
$nViajeros=$categoria[0]["nViajeros"];
$guia=$categoria[0]["guia"];
$nombre_categoria=$categoria[0]["nombre_categoria_servicio"];
$descripcionCorta_categoria_servicio=$categoria[0]["descripcionCorta_categoria_servicio"];
$nombre_categoria=$categoria[0]["nombre_categoria_servicio"];

 ?>

  <!-- Content Wrapper. Contains page content -->

  <div class="content-wrapper">

    <!-- Content Header (Page header) -->

    <div class="content-header">

      <div class="container-fluid">

        <div class="row mb-2">

          <div class="col-sm-6">

            <h1 class="m-0 text-dark">Editor de categoria <?=$nombre_categoria?></h1>

          </div><!-- /.col -->

          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right">

              <li class="breadcrumb-item"><a href="#">Editor</a></li>

              <li class="breadcrumb-item active">Editor de categoria <?=$nombre_categoria?></li>

            </ol>

          </div><!-- /.col -->

        </div><!-- /.row -->

      </div><!-- /.container-fluid -->

    </div>



    <!-- Main content -->
    <section class="content">
      <div class="row">
 
      <div class="col-md-12">
      <div class="form-inline">

<div class="form-group">
  


</div>
</div>  
</div>
</div>
      <div class="row">
        <div class="col-md-6">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">em geral</h3>

              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                  <i class="fas fa-minus"></i></button>
              </div>
            </div>
            <div class="card-body">
              <form method="post">
              <div class="form-group">
                <label for="inputName">Nome</label>
                <input type="text" name="nombre_categoria" class="form-control" value="<?=$nombre_categoria;?>">
              </div>
              <div class="form-group">
                <label for="inputDescription">Descrição</label>
                <textarea name="descripcion_categoria_servicio" class="form-control" rows="4"><?=$descripcion_categoria_servicio;?></textarea>
              </div>
                      <div class="form-group">
                <label for="inputEstimatedBudget">Cantidad de viajeros</label>
                <input type="number" name="nViajeros" class="form-control" value="<?=$nViajeros?>" step="1">
              </div>

                  <div class="form-group">
                <label for="inputDescription">Descrição corta</label>
                <textarea name="descripcionCorta_categoria_servicio" class="form-control" rows="4"><?=$descripcionCorta_categoria_servicio;?></textarea>
              </div>
              <button type="submit" name="editarCategoria" value="<?=$idCategoria;?>" class="btn-info">Salvar</button>
       </form>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <div class="col-md-6">

          <!-- /.card -->
          <div class="card card-info">
            <div class="card-header">
              <h3 class="card-title">Guia</h3>

              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                  <i class="fas fa-minus"></i></button>
              </div>
            </div>
            <div class="card-body">
              <table class="table">
                <thead>
                  <tr>
                    <th>Nome do arquivo</th>
        
                    <th></th>
                  </tr>
                </thead>
                <tbody>

                  <tr>
                    <td><?=$guia;?></td>
       
            
                  </tr>
             
               
                

                </tbody>
              </table> <form method="post" enctype="multipart/form-data">
                 <div class="form-group">
                 
                      <label for="inputEstimatedBudget">Trocar Guia</label>
                <input type="file" name="file[]" class="form-control" accept="application/pdf">
             
                  
             
              
              </div>  
              <button name="editarGuia" class="btn-success" value="<?=$idCategoria;?>">Salvar novo guia</button>     </form>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
      </div>
      <div class="row">
        <div class="col-12">
          <div class="form-inline">
                     <div class="form-group">
         
             <form method="post"><?php 

                                if($categoria[0]["habilitado"]==0){ ?>

                                <button class="btn btn-sm btn-info" name="habilitarCategoria" value="<?=$idCategoria?>">Habilitar categoria</button>

                                <?php }

                                else{

                                  ?><button class="btn btn-sm btn-warning" name="desHabilitarCategoria" 

                                   value="<?=$idCategoria?>">Deshabilitar Categoria</button>

                               <?php }?>    </form>
              </div>
              <div class="form-group">
                              
<form method="post" action="categoriaOpiniones">

<button type="submit" name="idCategoria_servicio" value="<?=$idCategoria?>" class="btn-sm btn-primary">Editar Opiniones</button></form>


              </div>
             <div class="form-group">
   <form method="post" action="categoriaServiciosAdicionales">

<button type="submit" name="idCategoria_servicio" value="<?=$idCategoria?>" class="btn-sm btn-warning" target="_blank">Serviços adicionais
</button></form>       
        </div>
</div>

<div class="form-inline  float-right">
  <div class="form-group">
    <a href="categoriasLista" class="btn btn-success float-right"><i class="fas fa-arrow-left"></i>Voltar</a>
  </div>
</div>
          
        </div>
      </div>
    </section>
    <!-- /.content -->
    <section>
      <div class="card">
        <div class="card-body">
           
        </div>
      </div>
    </section>

  <?php 

   include("includes/footer.php"); ?>