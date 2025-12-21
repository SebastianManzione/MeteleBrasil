
<?php 


include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");
require("classes/functions.php");
require("classes/prestador.php");
require("classes/usuario.php");
if ($_SERVER["REQUEST_METHOD"]=="POST") {

 if($prestador>1){
alertar("Prestador guardado con exito", "success");
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
            <h1 class="m-0 text-dark">Reservas</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Salidas</li>
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
            <h3 class="card-title">Reserva VSH123 ROMINA ALVEZ</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
              <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <div class="row">
     
            <table id="example2" class="table table-bordered table-striped">
                <thead>
         
            
                                                 <table style="width:100%">
                                                  <tr>
                                                    <th>FECHA DE CONTRATACION</th>
                                                    <th>NOMBRE DEL RESPONSABLE</th>
                                                    <th>CANTIDAD DE PAX</th>
                                                    <th>TIPO DE TARIFA</th>

                                                  </tr>
                                                  <tr>
                                        
                                                    <td>11/10/2020 14:24</td>
                                                    <td>ROMINA ALVES</td>
                                                    <td>4</td>
                                                    <td>ADULTOS NO REEMBOLSABLE/CRIANCA MENOR DE 12 ANOS</td>
                                                  </tr>
                                                  <tr>
                                                    <th>TOTAL</th>
                                                    <th>PAGOU</th>
                                                    <th>TIPO DE PAGAMENTO PAGOU</th>
                                                     <th>RESTA PAGAR</th>
                                                     
                                              
                                                  </tr>
                                                  <tr>
                                                    <td>1275,80</td>
                                                    <td>1000</td>
                                                    <td>CREDITO</td>
                                                    <td>275,80</td>
                                                
                                                 </tr>
                                                 <tr>
                                                    <th>VALOR COMISIONABLE</th>
                                                    <th>COMISION TOTAL (VENDEDOR+SISTEMA EN 4)</th>
                                                     <th>CANAL DE VENTA</th>
                                                     <th>IDIOMA PAX</th>
                                                     <th>PAIS</th>
                                              
                                                  </tr>
                                                  <tr>
                                                    <td>1250</td>
                                                    <td>125</td>
                                                    <td>Agencia</td>
                                                    <td>Español</td>
                                                    <td>Chile</td>

                                                
                                                 </tr>



                                               </table>
              



                </thead> 
         



<?php
$prestadores=getPrestadores();
for($i=0;$i < count($prestadores); $i++){
 $idPrestador=$prestadores[$i]["idPrestador"];
 $idUsuario=$prestadores[$i]["idUsuario"];
 $usuario=getUsuario($idUsuario);
    ?>
    
    <tr> 
   <td> <?= $prestadores[$i]["nombre"]; ?> </td>
   <td> <?= $prestadores[$i]["razonSocial"]; ?> </td>

   <td> <?= $prestadores[$i]["documento"]; ?> </td>
   <td> <?= $prestadores[$i]["telefono"]; ?> </td> 
   <td> <?= $prestadores[$i]["email"]; ?> </td>
   <td> <?= $prestadores[$i]["observaciones"]; ?> </td>
  
   <td> <?=  $usuario[0]["email"]; ?> </td>
      <td> <?= $prestadores[$i]["celular"]; ?> </td>
         <td> <?= $prestadores[$i]["facebook"]; ?> </td>
            <td> <?= $prestadores[$i]["instagram"]; ?> </td>
               <td> <?= $prestadores[$i]["web"]; ?> </td>
      <td>
          <a class="btn-sm btn-danger"onclick="borraPrestador('<?=$idPrestador;?>')"><i class="fas fa-trash"></i> Eliminar</a>
      </td>
</tr> 
    
    <?php
}
?>



    
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




