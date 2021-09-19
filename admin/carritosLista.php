

<?php 

include("includes/header.php");
include("includes/navbar.php");
include("includes/sidebar.php");
require("classes/functions.php");
require("classes/prestador.php");
require("classes/usuario.php");
require("classes/reserva.php");
require("classes/salidas.php");
require("classes/categoria.php");
require("classes/servicio.php");
require("classes/comprobantes.php");
require("classes/convierte_monedas.php");


if (!$_SESSION["login"]["rol"]==1) {

  alertar("Usted no tiene acceso a esta seccion del software", "error");

  redireccionarLento("index");

}



if ($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST["eliminaReserva"])) {
$resul=eliminarReserva($_POST["eliminaReserva"]);
if ($resul>0) {
  alertar("Borrado con exito", "success");
  // code...
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

            <h1 class="m-0 text-dark">Lista de carritos</h1>

          </div><!-- /.col -->

          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right">

              <li class="breadcrumb-item"><a href="#">Administración</a></li>

              <li class="breadcrumb-item active">Lista de carritos</li>

            </ol>

          </div><!-- /.col -->

        </div><!-- /.row -->

      </div><!-- /.container-fluid -->

    </div>

    <section class="content">

      <div class="container-fluid">

        <!-- SELECT2 EXAMPLE -->

        <!-- /.Responsive -->

        <!-- SELECT2 EXAMPLE -->

        <div class="card card-default">

          <div class="card-header">

            <h3 class="card-title">Carritos</h3>



            <div class="card-tools">

              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>

              <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>

            </div>

          </div>

          <!-- /.card-header -->

          <div class="card-body">

            <div class="row">

     

            

<div class="table-responsive">

  <table class="table" id="tablaCarrito">

    <thead>

      <tr>

                    <th scope="col">Servicios</th>

                    <th scope="col">COD-carrito</th>

                    <th scope="col">Responsable</th>

                    <th scope="col">Fecha de Compra                

                    <th scope="col">Total</th>

                    <th scope="col">RESTA PAGAR</th>

                    <th scope="col">Estado</th>

                    <th scope="col">Accion</th>



      </tr>

    </thead>

    <tbody>

      <?php 

$reservas=getReservas(); 

for ($i=0; $i < count($reservas); $i++) { 



	$idReserva=$reservas[$i]["idReserva"];









 

$monedaSel=$reservas[$i]["monedaSel"];

$total=$reservas[$i]["total"];

$impuestos=$reservas[$i]["impuestos"];

$precio=ConvierteMoneda($monedaSel,$_SESSION["moneda_sel"], $total);

  $totalComprobantes=getComprobantesIdReserva($idReserva);

  $diferenciaComprobantesPrecio=$precio-$totalComprobantes;

$horariosReserva=getReservaHorarios($idReserva);



$trs='';

for ($j=0; $j < count($horariosReserva); $j++) { 



	$idServicioSalidas=$horariosReserva[$j]["idServicioSalidas"];

	$salida=getSalida($idServicioSalidas);

  $fecha_salida=date("d-m-Y ", strtotime($salida[0]["fecha"]));

	$servicio=getServicio($salida[0]["idServicio"]);

  $idCategoria_servicio=$servicio[0]["idCategoria_servicio"];

  $categoria_servicio=getCategoria($idCategoria_servicio);

  $nombre_categoria_servicio=$categoria_servicio[0]["nombre_categoria_servicio"];

	$nombreServicio=$servicio[0]["nombre_servicio"];

	$trs=$trs.' 

          <tr class="table-secondary">

            <td>'.$nombreServicio.'</td>

            <td>'.$nombre_categoria_servicio.'</td>

            <td>'.$fecha_salida.'</td>

          	<td class="details-control"><a class="btn btn-success">Detalle Salida</a></td>



          </tr> ';

	

}

$dataChildValue=' <div class="table-responsive">                                      

                                                    <h5 class="m-0 text-dark">Servicios Contratados</h5> <table class="table">

                                                  <tr>

                                                    <th scope="col">Servicio</th>

                                                    <th scope="col">Categoria</th>

                                                    <th scope="col">Fecha del Evento</th>

                                                    <th scope="col">Acción</th>

                                                  </tr>

                                          '.$trs.'                                                

                            </div>';



?>



      <tr class="accordion-toggle collapsed" id="accordion1" data-toggle="collapse" data-parent="#accordion1" href="#collapseOne"  

                          data-child-name="row0"

                            data-child-value='<?=$dataChildValue;?>'>



                                 <td class="details-control"><input id="boton" type="submit" name="proceso" class="btn btn-info" value="Ver"></td>

                                 <td><?=$reservas[$i]["codigoAmigable"]?></td>

                                 <td><?=$reservas[$i]["nombreResponsable"]." ".$reservas[$i]["apellidoResponsable"]?></td>



                                 <td> <?=date("d-m-Y H:i", strtotime($reservas[$i]['fechaAlta']));?></td>

                                 <td><?=$_SESSION["moneda_sel_sym"].round($precio,2) ;?></td>

                                 <td><?=$_SESSION["moneda_sel_sym"].round($diferenciaComprobantesPrecio,2);?></td>

                                 <?php $claseBoton="btn btn-warning";

                                        $textoBoton="Pendiente";

                                        if ($diferenciaComprobantesPrecio<1 && $precio > 0) {

                                         $claseBoton="btn btn-success";

                                          $textoBoton="Confirmada";

                                        }

                                  ?>

                                 <td> <button type="button" class="<?= $claseBoton;?>"><?= $textoBoton;?></button></td> 

                                 <td > <form method="post" action="carritoDetalles"> <button class="btn btn-primary" name="detallesCarrito" value="<?=$idReserva;?>">Detalles carrito</button></form> 
                                        <?php if ($_SESSION["login"]["rol"]==1) {
                                          ?>

<form method="post" onsubmit="return confirm('Esta Seguro que desea borrar el carrito?');"><button class="btn-xs btn-danger" name="eliminaReserva" value="<?=$idReserva;?>">Eliminar Carrito</button></form>
                                          <?php
                                          // code...
                                        } ?>

                                 </td>

                              



</tr>



<?php

}

//print_r($reservas);

       ?>





  

                  

                  </tbody>

                </table>





              </div>

              <!-- /.card-body -->

        

         



<script type="text/javascript">

    function format(value) {

      return value  ;

  }

  $(document).ready(function () {

      var table = $('#tablaCarrito').DataTable({
        "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": true,
      "stateSave":true});



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









