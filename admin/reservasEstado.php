<?php 
include("includes/header.php");

include("includes/navbar.php");

include("includes/sidebar.php");

require("classes/functions.php");

require("classes/reserva.php");

require("classes/salidas.php");

require("classes/convierte_monedas.php");

$idPrestador=($_SESSION['login']['idPrestador']);







 ?>

  <!-- Content Wrapper. Contains page content -->

  <div class="content-wrapper">

    <!-- Content Header (Page header) -->

    <div class="content-header">

      <div class="container-fluid">

        <div class="row mb-2">

          <div class="col-sm-6">

            <h1 class="m-0 text-dark"><?=$lang["reservas"];?></h1>

          </div><!-- /.col -->

          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right">

              <li class="breadcrumb-item"><a href="#"><?=$lang["reservas"];?></a></li>

              <li class="breadcrumb-item active"><?=$lang["estado_de_reservas"];?></li>

            </ol>

          </div><!-- /.col -->

        </div><!-- /.row -->

      </div><!-- /.container-fluid -->

    </div>

    <section class="content">

      <div class="container-fluid">

        <!-- SELECT2 EXAMPLE -->

      

<button type="button" class= "btn btn-secondary btn-lg btn-block"><?=$lang["reservas"];?></button>

        



<div id="accordion">

  <div class="card">

    <div class="card-header" id="headingOne">

      <h5 class="mb-0">

        <button class="btn btn-success btn-lg btn-block" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne"><i class="fas fa-check-double"></i> 

          <?=$lang["confirmadas"];?>

        </button>

      </h5>

    </div>



    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">

      <div class="card-body">

        <h3><?=$lang["lista_de_reservas_confirmadas"];?></h3> 

            <div class="row">

                  <div class="table-responsive">   

                            

                            <table class="table" id="tablaCarrito">

                              <thead>

                                <tr>

                                    <th scope="col"><?=$lang["nombre"];?></th>

                                    <th scope="col"><?=$lang["cod-carrito"];?></th>

                                    <th scope="col"><?=$lang["fecha_de_contratacion"];?></th>

                                    <th scope="col"><?=$lang["dia_del_evento"];?></th>

                                    <th scope="col"><?=$lang["cod-servicio-contratados"];?></th>

                                    <th scope="col"><?=$lang["valor_total"];?></th>

                      

                                 </tr>

                              </thead>

                    <tbody>

<?php



$reservas=getReservasConfirmadas($idPrestador);

  $hoy=strtotime(date('Y-m-d'));



 for ($i=0; $i < count($reservas); $i++) { 



  $idReserva=$reservas[$i]['idReserva'];

  $horarios=getReservaHorarios($idReserva);

  for ($j=0; $j < count($horarios); $j++) { 

 

              $idReservaHorarios=$horarios[$j]["idReservaHorarios"];

              $salida=getSalida($horarios[$j]["idServicioSalidas"]);

                 $fechaEvento=strtotime($salida[0]['fecha']);

                   $tarifas=getReservaTarifas($idReservaHorarios);

                   for ($k=0; $k < count($tarifas); $k++) { 

                

                        $nombre_tarifa=($tarifas[0]["nombre"]);

                        $monedaSel=$tarifas[0]["monedaSel"];

                        $valorSinIva=$tarifas[0]["valorSinIva"];

                       $cantidad=($tarifas[0]["cantidad"]);

                        $totalTarifa=$valorSinIva*$cantidad;

$total=ConvierteMoneda($tarifas[0]["monedaSel"],$_SESSION["moneda_sel"], $reservas[$i]["total"]);

                     

 }

                    if ($salida[0]["idPrestador"]==$idPrestador && $fechaEvento>=$hoy || $_SESSION['login']['idUsuario']==1 && $fechaEvento>=$hoy ) {

                      // code...

                   

                     

  

?>







                                 <tr>

                                    <td><?=$reservas[$i]["nombreResponsable"]." ".$reservas[$i]["apellidoResponsable"]?></td>

                                    <td><?=$reservas[$i]["codigoAmigable"]?></td>

                                    <td> <?=date("d-m-Y H:i", strtotime($reservas[$i]['fechaAlta']));?></td>

                                    <td><?=date("d/m/Y", strtotime($salida[0]['fecha']))?></td>

                                    <td><?=count($tarifas);?></td>

                                    <td><?=$_SESSION["moneda_sel_sym"].$total;?></td>

                                    <td><form method="post" action="voucherPrestador"><button type="submit" class="btn btn-info" name="idReservaHorarios" value="<?=$idReservaHorarios;?>"><?=$lang["voucher_prestador"];?></button></form></td>

                             



                             </tr>



                             <?php

                                          }    

  }



} ?>

                        </tbody>

                      </table>

                    </div>

                 </div>

             </div>

          </div>

     </div>



<?php if ($_SESSION['login']['idUsuario']==1) {

  ?>









  <div class="card">

    <div class="card-header" id="headingTwo">

      <h5 class="mb-0">

        <button class="btn btn-warning btn-lg btn-block collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo"><i class="fas fa-exclamation-circle"></i> 

          <?=$lang["pendientes"];?>

        </button>

      </h5>

    </div>

    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">

      <div class="card-body">

        <h3><?=$lang["lista_de_reservas_pendientes"];?></h3> 

            <div class="row">

                  <div class="table-responsive">   

                                     <table class="table" id="tablaCarrito">

                              <thead>

                                <tr>

                                    <th scope="col"><?=$lang["nombre"];?></th>

                                    <th scope="col"><?=$lang["cod-carrito"];?></th>

                                    <th scope="col"><?=$lang["fecha_de_contratacion"];?></th>

                                    <th scope="col"><?=$lang["dia_del_evento"];?></th>

                                    <th scope="col"><?=$lang["cod-servicio-contratados"];?></th>

                                    <th scope="col"><?=$lang["valor_total"];?></th>

                      

                                 </tr>

                              </thead>

                    <tbody>

<?php



$reservas=getReservasPendientes($idPrestador);

   $hoy=strtotime(date('Y-m-d'));

 for ($i=0; $i < count($reservas); $i++) { 



  $idReserva=$reservas[$i]['idReserva'];

  $horarios=getReservaHorarios($idReserva);

  for ($j=0; $j < count($horarios); $j++) { 

 

              $idReservaHorarios=$horarios[$j]["idReservaHorarios"];

              $salida=getSalida($horarios[$j]["idServicioSalidas"]);

          

                   $tarifas=getReservaTarifas($idReservaHorarios);

                   for ($k=0; $k < count($tarifas); $k++) { 

                

                        $nombre_tarifa=($tarifas[0]["nombre"]);

                        $monedaSel=$tarifas[0]["monedaSel"];

                        $valorSinIva=$tarifas[0]["valorSinIva"];

                       $cantidad=($tarifas[0]["cantidad"]);

                        $totalTarifa=$valorSinIva*$cantidad;

$total=ConvierteMoneda($tarifas[0]["monedaSel"],$_SESSION["moneda_sel"], $reservas[$i]["total"]);

                     $fechaEvento=strtotime($salida[0]['fecha']);

}

                    if ($salida[0]["idPrestador"]==$idPrestador && $fechaEvento>=$hoy || $_SESSION['login']['idUsuario']==1  && $fechaEvento>=$hoy) {



                      // code...

                   

                     

  

?>







                                 <tr>

                                    <td><?=$reservas[$i]["nombreResponsable"]." ".$reservas[$i]["apellidoResponsable"]?></td>

                                    <td><?=$reservas[$i]["codigoAmigable"]?></td>

                                    <td> <?=date("d-m-Y H:i", strtotime($reservas[$i]['fechaAlta']));?></td>

                                    <td><?=date("d/m/Y", strtotime($salida[0]['fecha']))?></td>

                                    <td><?=count($tarifas);?></td>

                                    <td><?=$_SESSION["moneda_sel_sym"].$total;?></td>

                             



                             </tr>



                             <?php

                                          }     }

  



} ?>

                        </tbody>

                      </table>

                    </div>

                 </div>

             </div>

    </div>

  </div>

  <?php

} ?>

  <div class="card">

    <div class="card-header" id="headingThree">

      <h5 class="mb-0">

        <button class="btn btn-secondary btn-lg btn-block collapsed" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseThree"><i class="fas fa-clock"></i> 

          <?=$lang["pasadas"];?>

        </button>

      </h5>

    </div>

    <?php if ($_SESSION['login']['idUsuario']==1) {

  ?>

    <div id="collapseFour" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">

      <div class="card-body">

        <h3><?=$lang["lista_de_reservas_pasadas_pendientes"];?></h3> 

            <div class="row">

                  <div class="table-responsive">   

                            

                                              <table class="table" id="tablaCarrito">

                              <thead>

                                <tr>

                                    <th scope="col"><?=$lang["nombre"];?></th>

                                    <th scope="col"><?=$lang["cod-carrito"];?></th>

                                    <th scope="col"><?=$lang["fecha_de_contratacion"];?></th>

                                    <th scope="col"><?=$lang["dia_del_evento"];?></th>

                                    <th scope="col"><?=$lang["cod-servicio-contratados"];?></th>

                                    <th scope="col"><?=$lang["valor_total"];?></th>

                      

                                 </tr>

                              </thead>

                    <tbody>

<?php



$reservas=getReservasPendientes($idPrestador);

   $hoy=strtotime(date('Y-m-d'));

 for ($i=0; $i < count($reservas); $i++) { 



  $idReserva=$reservas[$i]['idReserva'];

  $horarios=getReservaHorarios($idReserva);

  for ($j=0; $j < count($horarios); $j++) { 

 

              $idReservaHorarios=$horarios[$j]["idReservaHorarios"];

              $salida=getSalida($horarios[$j]["idServicioSalidas"]);

          

                   $tarifas=getReservaTarifas($idReservaHorarios);

                   for ($k=0; $k < count($tarifas); $k++) { 

                

                        $nombre_tarifa=($tarifas[0]["nombre"]);

                        $monedaSel=$tarifas[0]["monedaSel"];

                        $valorSinIva=$tarifas[0]["valorSinIva"];

                       $cantidad=($tarifas[0]["cantidad"]);

                        $totalTarifa=$valorSinIva*$cantidad;

$total=ConvierteMoneda($tarifas[0]["monedaSel"],$_SESSION["moneda_sel"], $reservas[$i]["total"]);

                     $fechaEvento=strtotime($salida[0]['fecha']);

    }

                    if ($salida[0]["idPrestador"]==$idPrestador && $fechaEvento<=$hoy || $_SESSION['login']['idUsuario']==1  && $fechaEvento<=$hoy) {



                      // code...

                   

                     

  

?>







                                 <tr>

                                    <td><?=$reservas[$i]["nombreResponsable"]." ".$reservas[$i]["apellidoResponsable"]?></td>

                                    <td><?=$reservas[$i]["codigoAmigable"]?></td>

                                    <td> <?=date("d-m-Y H:i", strtotime($reservas[$i]['fechaAlta']));?></td>

                                    <td><?=date("d/m/Y", strtotime($salida[0]['fecha']))?></td>

                                    <td><?=count($tarifas);?></td>

                                    <td><?=$_SESSION["moneda_sel_sym"].$total;?></td>

                             



                             </tr>



                             <?php

                                          } 

  }



} ?>

                        </tbody>

                      </table>

                    </div>

                 </div>

             </div>

    </div>

  <?php } ?>

        <div id="collapseFour" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">

      <div class="card-body">

        <h3><?=$lang["lista_de_reservas_confirmadas_pasadas"];?></h3> 

            <div class="row">

                  <div class="table-responsive">   

                            

                                              <table class="table" id="tablaCarrito">

                              <thead>

                                <tr>

                                    <th scope="col"><?=$lang["nombre"];?></th>

                                    <th scope="col"><?=$lang["cod-carrito"];?></th>

                                    <th scope="col"><?=$lang["fecha_de_contratacion"];?></th>

                                    <th scope="col"><?=$lang["dia_del_evento"];?></th>

                                    <th scope="col"><?=$lang["cod-servicio-contratados"];?></th>

                                    <th scope="col"><?=$lang["valor_total"];?></th>

                      

                                 </tr>

                              </thead>

                    <tbody>

<?php



$reservas=getReservasConfirmadas($idPrestador);

   $hoy=strtotime(date('Y-m-d'));

 for ($i=0; $i < count($reservas); $i++) { 



  $idReserva=$reservas[$i]['idReserva'];

  $horarios=getReservaHorarios($idReserva);

  for ($j=0; $j < count($horarios); $j++) { 

 

              $idReservaHorarios=$horarios[$j]["idReservaHorarios"];

              $salida=getSalida($horarios[$j]["idServicioSalidas"]);

          

                   $tarifas=getReservaTarifas($idReservaHorarios);

                   for ($k=0; $k < count($tarifas); $k++) { 

                

                        $nombre_tarifa=($tarifas[$k]["nombre"]);

                        $monedaSel=$tarifas[$k]["monedaSel"];

                        $valorSinIva=$tarifas[$k]["valorSinIva"];

                       $cantidad=($tarifas[$k]["cantidad"]);

                        $totalTarifa=$valorSinIva;

$total=ConvierteMoneda($tarifas[$k]["monedaSel"],$_SESSION["moneda_sel"], $totalTarifa);



$totalReserva=ConvierteMoneda($tarifas[$k]["monedaSel"],$_SESSION["moneda_sel"], $totalTarifa);



                     $fechaEvento=strtotime($salida[0]['fecha']);



                    if ($salida[0]["idPrestador"]==$idPrestador && $fechaEvento<=$hoy || $_SESSION['login']['idUsuario']==1  && $fechaEvento<=$hoy) {



                      // code...

                   

                     

  

?>







                                 <tr>

                                    <td><?=$reservas[$i]["nombreResponsable"]." ".$reservas[$i]["apellidoResponsable"]?></td>

                                    <td><?=$reservas[$i]["codigoAmigable"]?></td>

                                    <td> <?=date("d-m-Y H:i", strtotime($reservas[$i]['fechaAlta']));?></td>

                                    <td><?=date("d/m/Y", strtotime($salida[0]['fecha']))?></td>

                                    <td><?=count($tarifas);?></td>

                                    <td><?=$_SESSION["moneda_sel_sym"].$totalReserva;?></td>

                             



                             </tr>



                             <?php

                                          }     }

  }



} ?>

                        </tbody>

                      </table>

                    </div>

                 </div>

             </div>

    </div>

  </div>

</div>



                                        

                                  

                                   



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