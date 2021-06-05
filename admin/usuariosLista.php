<?php 


include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");
require("classes/functions.php");
require("classes/prestador.php");
require("classes/usuario.php");
if (!$_SESSION["login"]["rol"]==1) {
  alertar("Usted no tiene acceso a esta seccion del software", "error");
  redireccionarLento("index");
}


if ($_SERVER["REQUEST_METHOD"]=="POST") {

if(isset($_POST['actDes']) && is_numeric($_POST["idUsuario"])){
  $idUsuario=$_POST['idUsuario'];
  if ($idUsuario==1) {
     alertar("El usuario admin no se puede desabilitar", "warning");
  }
  else{
  $habilitado=$_POST['actDes'];


$habilitar=habilitarUsuario($idUsuario, $habilitado);
if ($habilitar==1) {
  alertar("Cambio en el estado de usuario exitoso", "success");
}
  
  }


}


if(isset($_POST['cobrador']) && is_numeric($_POST["idUsuario"])){
$habilitado=$_POST['cobrador'];
$idUsuario=$_POST['idUsuario'];
$habilitar=habilitarCobrador($idUsuario, $habilitado);
if ($habilitar==1) {
  alertar("Cambio en el estado de cobrador de usuario exitoso", "success");
}


}

if(isset($_POST['vendedor']) && is_numeric($_POST["idUsuario"])){
$habilitado=$_POST['vendedor'];
$idUsuario=$_POST['idUsuario'];
$habilitar=habilitarVendedor($idUsuario, $habilitado);
if ($habilitar==1) {
  alertar("Cambio en el estado de vendedor de usuario exitoso", "success");
}


}



if(isset($_POST['guardar']) && is_numeric($_POST["idUsuario"])){
$idPrestador=$_POST['idPrestador'];
$idUsuario=$_POST['idUsuario'];
$habilitar=updatePrestadorUsuario($idUsuario, $idPrestador);
if ($habilitar==1) {
  alertar("Cambios en el usuario ok", "success");
}


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
            <h1 class="m-0 text-dark">Usuarios</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Usuario</li>
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
            <h3 class="card-title">Usuario</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
              <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
              <a href="usuarioAlta" class="btn btn-primary btn-lg btn-block">Nuevo usuario</a>

              <button type="button" class= "btn btn-secondary btn-lg btn-block" data-card-widget="collapse">Lista de usuarios</button> 
</div>



           <div class="card-body">
            <div class="row">
                  <div class="table-responsive">  
               <table id="#collapseExample" class="table table-bordered table-striped">
 
                <thead>
         
                  <th>Foto</th>
                  <th>Nombre </th>        
                  <th>Email</th> 
                  <th>Estado</th>  
                    <th>Vendedor</th> 
                      <th>Cobrador</th> 
                        <th>Prestador</th> 
                    <th>F.Alta</th>
                  <th>Acciones</th>
                </thead> 
         

 


<?php

$prestadores=getPrestadores();
$usuarios=getUsuarios();
for($i=0;$i < count($usuarios); $i++){
$prestador=getPrestador($usuarios[$i]["idPrestador"]);

$nombrePrestador="SIN PRESTADOR";
if (is_array($prestador) && count($prestador)==1) {
  $nombrePrestador=$prestador[0]["razonSocial"];
}
 $idUsuario=$usuarios[$i]["idUsuario"];
/* Tema fotito*/
$imagen="./classes/imgUsuario/".$usuarios[$i]['fotoUsuario'];


if (stripos ( $usuarios[$i]['fotoUsuario'], "ttps:")==1) {
  $imagen= $usuarios[$i]['fotoUsuario'];
}


/*fin tema fotito*/
 /* boton habilitar desabilitar usuario   */
 $botonStatus="btn-sm btn-success";
  $botonStatusTexto="Habilitar";
  $botonAccion=1;
   if ($usuarios[$i]["status"]>0) {
   $botonStatus="btn-sm btn-danger";
   $botonStatusTexto="Deshabilitar";
   $botonAccion=0;
   };
 /* finn   boton habilitar desabilitar usuario   */


  /* boton habilitar desabilitar vendedor   */
 $botonVendedorStatus="btn-sm btn-success";
  $botonVendedorStatusTexto="Habilitar Ventas";
  $botonVendedorAccion=1;
   if ($usuarios[$i]["idVendedor"]>0) {
   $botonVendedorStatus="btn-sm btn-danger";
   $botonVendedorStatusTexto="Deshabilitar Ventas";
   $botonVendedorAccion=0;
   };
 /* finn   boton habilitar desabilitar vendedor   */


  /* boton habilitar desabilitar cobrador   */
 $botonCobradorStatus="btn-sm btn-success";
  $botonCobradorStatusTexto="Habilitar Cobros";
  $botonCobradorAccion=1;
   if ($usuarios[$i]["idCobrador"]>0) {
   $botonCobradorStatus="btn-sm btn-danger";
   $botonCobradorStatusTexto="Deshabilitar Cobros";
   $botonCobradorAccion=0;
   };
 /* finn   boton habilitar desabilitar cobrador   */
 $fecha_alta=date("d-m-Y H:i", strtotime($usuarios[$i]["fecha_alta"]));

    ?>
    
    <tr> 
      <form method="POST">
        <input type="hidden" name="idUsuario" value="<?=$idUsuario;?>">
   <td><img src="<?=$imagen;?>" style="width:  75px;"/>  </td>
   <td> <?= $usuarios[$i]["usuario"]; ?> </td>

   <td> <?= $usuarios[$i]["email"]; ?> </td>
   <td> <button class="<?=$botonStatus; ?>" value="<?=$botonAccion;?>" name="actDes"><?=$botonStatusTexto;?></button> </td> 


     <td> <button class="<?=$botonVendedorStatus; ?>" value="<?=$botonVendedorAccion;?>" name="vendedor"><?=$botonVendedorStatusTexto;?></button>  </td>

         <td> <button class="<?=$botonCobradorStatus; ?>" value="<?=$botonCobradorAccion;?>" name="cobrador"><?=$botonCobradorStatusTexto;?></button>  </td>

            <td> 
<select name="idPrestador">
  <?php 
  for ($k=0; $k < count($prestadores); $k++) { 
     $selected=" ";
    if($usuarios[$i]["idPrestador"]==$prestadores[$k]["idPrestador"]){
      $selected="selected";
    }

     ?>  <option value="<?=$prestadores[$k]["idPrestador"];?>" <?=$selected;?> ><?=$prestadores[$k]["razonSocial"];?></option><?php
   } ?>

</select>

              </td>
   <td> <?=  $fecha_alta ?> </td>
      <td>
          <button class="btn-sm btn-info" name="guardar"><i class="fas fa-save"></i>Guardar</button>
      </td>
      </form>
</tr> 
    
    <?php
}
?>


</table>
    </div> </div> </div>


<script type="text/javascript">



function uploadForm(){

$("#formulario").submit();
}
            function borraPrestador(idPrestador){
                      
var parametros={"borraPrestador" : idPrestador};   
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
 $.post("./ctrl/ctrl_prestador.php",
parametros,
function(data, status){
console.log(data);
 if (data>0) {
  location.href = 'prestadores.php';
 }
});
 Swal.fire(
   'Eliminado!',
   'El prestador se elimino.',
   'success'
 )
}
})   
     
       }


           </script>
         
                </tfoot>
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