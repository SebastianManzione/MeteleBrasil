

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
require("classes/accesibilidad.php");
require("classes/convierte_monedas.php");
require("classes/servicios_adicionales.php");
if (!$_SESSION["login"]["rol"]==1) {

  alertar("Usted no tiene acceso a esta seccion del software", "error");

  redireccionarLento("index");

}


if ($_SERVER["REQUEST_METHOD"]=="POST") {

$idServicioSalidas=$_POST['idServicioSalidas'];
$salida=getSalida($idServicioSalidas);

}


if ($_SERVER["REQUEST_METHOD"]=="GET") {

$idServicioSalidas=$_GET['idServicioSalidas'];
$salida=getSalida($idServicioSalidas);

}
if ($_SERVER["REQUEST_METHOD"]=="POST"&& isset($_POST["valor"]) && isset($_POST["descripcion"]) ) {

  $idServicioSalidas=$_POST['idServicioSalidas'];
  $salida=getSalida($idServicioSalidas);
  $idMoneda=$salida[0]["idMoneda"];
  $idServiciosAdicionales=$_POST['idServiciosAdicionales'];
  $valor=$_POST['valor'];
  $descripcion=$_POST['descripcion'];
  $resul=setServiciosAdicionalesSalida($idServicioSalidas, $idServiciosAdicionales, $valor, $idMoneda, $descripcion);
  if ($resul>0) {
    alertar("Serviço adicional adicionado com sucesso", "success");

  }
}
$salida=getSalida($idServicioSalidas);

 ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Editor de serviços adicionais
 <?=$salida[0]["nombre"];?> Saída <?=$idServicioSalidas;?></h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Administración</a></li>
              <li class="breadcrumb-item active">Editor de serviços adicionais </li>
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
                


<button type="button" class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#exampleModal" data-whatever="agregarNota">Adicionar serviço adicional</button>          

                                    <div class="modal fade show" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                      <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                          <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">adicionar serviço adicional na partida
 <?=$idServicioSalidas;?></h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                              <span aria-hidden="true">&times;</span></button>
                                          </div>

                                          <div class="modal-body">
                                            <form method="post">
                                              <div class="form-group">
                                              <?php 

                                               $adicionales=getServiciosAdicionalesQueNoEstanEnSalida(1, $idServicioSalidas);

                                                ?>  
                                                  <label for="recipient-name" class="col-form-label">serviço adicional:</label>
<input type="hidden" name="idServicioSalidas" value="<?=$idServicioSalidas?>">
                                                  <select class="form-control" name="idServiciosAdicionales">
                                                    <?php for ($i=0; $i < count($adicionales); $i++) {
                                                                $idServiciosAdicionales=$adicionales[$i]["idServiciosAdicionales"];
                                                                $nombre_servicio_adicional=$adicionales[$i]["nombre"];

                                                     ?>
                                                        <option value="<?=$idServiciosAdicionales;?>" > <?=$nombre_servicio_adicional;?> </option>
                                                    <?php } ?>
                                                 
                                                  </select>
                                             </div>
                                                    <div class="form-group">
                                                    <label class="col-form-label">Descripcion</label>
                                                    <input class="form-input" name="descripcion" id="descripcion" type="text"></input>
                                                  </div>
                                                  <div class="form-group">
                                                    <label class="col-form-label">Precio</label>
                                                    <input class="form-input" name="valor" id="precio" type="number" step="0.1"></input>
                                                  </div>
                                                <div class="form-group">
                                                    <label class="col-form-label">Free?</label>
                                                    <input class="form-input" id="chkFree" type="checkbox"></input>
                                                  </div>
                                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                            <button type="submit" name="addTextoAccesiblidad" class= "btn btn-primary">Agregar</button>
                                            </form>
                                          </div>
                                          
                                        </div>
                                      </div>
                                    </div>

        <button type="button" class="btn btn-secondary btn-lg btn-block" data-card-widget="collapse">Lista de Adicionales</button>

</div>
<!-- /.card-header -->
<script type="text/javascript">
$("#chkFree").change(function() {
    if(this.checked) {
    $('#precio').prop('readonly', true);
    $('#precio').val(0);
    }else{
       $('#precio').prop('readonly', false);
    $('#precio').val(1);
    }
});


</script>

        <div class="card-body">
            <div class="row">
                  <div class="table-responsive">   
                            
                            <table class="table" id="tablaCarrito">
                              <thead>
                                <tr>
                             
                                   <th scope="col">Nome</th>  
                                    <th scope="col">Descrição</th>  
                                      <th scope="col">Preço</th>              
                                   <th scope="col">Açao</th>

                                </tr>
                              </thead>
                  
                           <tbody>
<?php


$adicionales=getServicioAdicionalIdServicioSalida($idServicioSalidas);


 for ($i=0; $i < count($adicionales); $i++) { 

  $idServiciosAdicionales=$adicionales[$i]['idServiciosAdicionales'];
  $valor=$adicionales[$i]['valor'];
  $svAdicional=getServicioAdicional($idServiciosAdicionales);
  $boxValor='';
  if ($valor==0) {

   $boxValor='FREE';
  }
  else{
     $boxValor='<label name="precio'.$idServiciosAdicionales.'"> '.$valor.'</label>';
  }
?>



                             <tr>
                                <td><?=$svAdicional[0]['nombre']?></td>
                                <td><?=$adicionales[$i]['descripcion']?></td>
                                 <td> <?=$boxValor?></td>
                                <td><a class="btn btn-danger" onclick="borrar(<?=$idServiciosAdicionales;?>,<?=$idServicioSalidas?>);">Excluir</a>
            </td>
                             </tr>                    

<?php
} ?>

                                           
                         </tbody>
                         </table>
                                    </div>
                                    </div>
                                    </div><!-- /.card-body -->
             



                          <script type="text/javascript">



            function borrar(idServiciosAdicionales, idServicioSalidas){
           


Swal.fire({
title: 'Tem certeza?',
text: 'Tem certeza de que deseja excluir o serviço adicional!',
icon: 'warning',
showCancelButton: true,
confirmButtonColor: '#3085d6',
cancelButtonColor: '#d33',
confirmButtonText: 'Sim deletar!'
}).then((result) => {
if (result.value) {

  $.post( "ctrl/ctrlServiciosAdicionales.php", { borraAdicionalSalida: idServiciosAdicionales, idServicioSalidas:idServicioSalidas } ,function( data ) {

  if (data==(-5)) {

     Swal.fire("Sucesso","serviço adicional removido com sucesso ","success");
     window.location.href = "serviciosAdicionalesSalidaEdita?idServicioSalidas=<?=$idServicioSalidas?>";
  }
  else{
     datos=JSON.parse(data);
   carritos="";
    for (var i = 0; i < datos.length; i++) {
 
      carritos+= datos[i]
    }
      Swal.fire("Error","O serviço adicional está reservado nos seguintes carrinhos: "+carritos,"error");
  }
 

});


}
else{
  return false;
}

})   
    
       }


           </script>                       
                                  
                                   

        <script type="text/javascript">
                        function format(value) {
                        return value  ;
                            }
                            $(document).ready(function () {
                                var table = $('#tablaCarrito').DataTable({});

                                // Add event listener for opening and closing details
                                $('#tablaCarrito').on('click', 'td.details-control', function () {

                                    var tr = $(this).closest('tr');
                                    var row = table.row(tr);

                                    if (row.child.isShown()) {
                                        // This row is already open - close it
                                        row.child.hide();
                                        tr.removeClass('shown');
                                    } else {
                                        // Open this row
                                        row.child(format(tr.data('child-value'))).show();
                                        tr.addClass('shown');
                                    }
                                });
                            });
                   </script>

                                        <form method="post" action="salidaVer">
  <button class="btn-sm btn-info" name="idServicioSalidas" value="<?=$idServicioSalidas?>">Voltar</button>
</form> 
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