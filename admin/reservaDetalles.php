
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
            <h1 class="m-0 text-dark">Salidas</h1>
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
                        <h3 class="card-title">Carrito 1</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
              <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <div class="row">
     
            
<div class="table-responsive">
  <table class="table">
    <thead>
      <tr>

                    <th scope="col">Detalles</th>
                    <th scope="col">Nombre del Pax</th>
                    <th scope="col">COD-Reserva</th>
                    <th scope="col">Fecha de Compra                
                    <th scope="col">Total</th>
                    <th scope="col">Pagado</th>
                    <th scope="col">RESTA PAGAR</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Canal de Venta</th>

      </tr>
    </thead>
    <tbody>
      <tr class="accordion-toggle collapsed" id="accordion1" data-toggle="collapse" data-parent="#accordion1" href="#collapseOne">

                                 <td class="expand-button"><input id="boton" type="submit" name="proceso" class="btn btn-success" value="Lista de Pax">
</td>
                                 <td>Romina Alvez</td>
                                 <td>HEJ276</td>
                                 <td>12/02/21 16:23</td>
                                 <td>2435</td>
                                 <td>435</td>
                                 <td>2000</td>
                                 <td> <button type="button" class="btn btn-warning">Pendiente</button></td> 
                                 <td>Destino Fl..(Ag)</td>
             

</tr>
<tr class="hide-table-padding">
<td></td>
<td colspan="12">
<div id="collapseOne" class="collapse in p-12">
 


<div class="table-responsive">


<table class="table">                                

                        

            <h5 class="m-0 text-dark">Lista de Pax</h5> 


                                                      
                                                  
                                                  <tr>
                                                    <th scope="col">Nombre</th>
                                                    <th scope="col">Apellido</th>
                                                    <th scope="col">Pais</th>
                                                    <th scope="col">Idioma</th>

                                                  </tr>
                                                  <tr class="table-secondary">
                                        
                                                    <td>Raul</td>
                                                    <td>Sejas</td>
                                                    <td>Dinamarca</td>
                                                    <td>Español</td>

                                                    
                                                  </tr>

                                                  <tr>
<table class="table">
           
           <h5 class="m-0 text-dark">Adicionales contratados</h5>

          
                                                      

                                                  <tr>
                                                    <th scope="col">Nombre</th>
                                                    <th scope="col">Descripción</th>
                                                    <th scope="col">Valor</th>

                                                  </tr>


                                                  <tr class="table-secondary">
                                        
                                                    <td>Snorkel</td>
                                                    <td>Una hora de snorkel por las maravillosas playas</td>
                                                    <td>R$ 100.00</td>             
                                                 </tr>
                                                 </table>

</div>


      </div>



    </div>
  </div>
</div>

                  
                  </tfoot>
                </table>
              </div>
              <!-- /.card-body -->


            </div>
                </thead> 
         

      <div class="table-responsive">   
      <table class="table table-bordered">

                    <tr>
                    <th scope="col">Check in</th>
                    <th scope="col">Formas de Pago Anticipado</th>
                    <th scope="col">Formas de Pago Resta Pagar</th>
                    <th scope="col">Acción</th>

                    </tr>
                    <tr class="table table-bordered">
                                        
                     <td>22/12/2021 14:24</td>
                     <td>Credito Visa</td>
                     <td>Dinero en Check in</td>
                     <td>
                      <button type="button" class="btn btn-danger btn-lg btn-block">Cancelar Reserva</button>
                      <button type="button" class="btn btn-warning btn-lg btn-block">Enviar Cobranza</button>
                      <button type="button" class="btn btn-success btn-lg btn-block">Confirmar Reserva</button>
                      <button type="button" class="btn btn-secondary btn-lg btn-block">Generar Descuento</button>
                      <button type="button" class="btn btn-primary btn-lg btn-block">Agregar Nota</button>
                    </td>

                     </tr>

                     <tr>

                    <th scope="col">Impuestos</th>
                    <th scope="col">Valor de Impuesto</th>
                    <th scope="col">Promoción</th>
                    <th scope="col">Valor de Descuento</th>

                     
                                              
                     </tr>
                     <tr class="table table-bordered">
                     <td>ISS</td>
                     <td>12.2</td>
                     <td>ninguna</td>
                     <td>0</td>
                                                
                     </tr>
                     



            </table>
                       </div>
                         </div>  
            
                                       <table class="table">               
<h5 class="m-0 text-dark">
                                                  <tr>
                                                    <h3 class="p-2 bg-primary text-white"class="font-weight-bold">Notas</h3>
                                                    <th scope="col">Operador</th>
                                                    <th scope="col">Nota</th>
                                                    <th scope="col">Fecha</th>

                                                  </tr>


                                                  <tr class="table-secondary">
                                        
                                                    <td>Manguera</td>
                                                    <td>Quise cobrarle al Topo y me saco cagando, sera que puede cobrarle otro operador porque conmigo no esta todo bien</td>
                                                    <td>17/02/2021 13:12</td>             
                                                 </tr>
                                                 </table>
<div class="table-responsive">

          <table class="table table-bordered">

               <tr>
                    <th scope="col">Nombre del Responsable</th>
                    <th scope="col">Telefono</th>
                    <th scope="col">Email</th>
                    <th scope="col">Canal de Venta</th>
                    <th scope="col">Contacto Vendedor</th>                   

                     
                                              
                     </tr>

                     <tr class="table table-bordered">
                     <td>Juan Jose</td>
                     <td>+55 48 996837008</td>
                     <td>jorge@gmail.com</td>  
                     <td>Destino Florianopolis Agencia</td>
                     <td class="expand-button"><input id="boton" type="submit" name="proceso" class="btn btn-success" value="Datos Vendedor"></td>
                                                
                     </tr>

     </table>   
              </div>                

<!--<?php
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




