



<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include("includes/header.php");

include("includes/navbar.php");

include("includes/sidebar.php");

require("classes/functions.php");

require("classes/servicio.php");

require("classes/reserva.php");

require("classes/salidas.php");
require("classes/prestador.php");
require("classes/comision_prestador.php");
require("classes/convierte_monedas.php");
$idPrestador=0;
$idPrestador=($_SESSION['login']['idPrestador']);
$prestadores=getPrestadores();
$dt = strtotime(date("Y-m-d"));
$desde= date("Y-m-")."01";
$hasta=date("Y-m-t");

if ($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST['desde']) ){
 $desde=$_POST['desde'];
$hasta=$_POST['hasta'];
}
if ($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST['idPrestador']) ){
 $idPrestador=$_POST['idPrestador'];

}

if (!isset($_POST['idPrestador']) && $_SESSION['login']['idUsuario']==1) {

 $idPrestador=(-5);
}


 ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Financiero Prestador</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Financiero</a></li>
              <li class="breadcrumb-item active">Financiero Prestador</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>

    <section class="content">

      <div class="container-fluid">

        <!-- SELECT2 EXAMPLE -->

      

  



<div id="accordion">

  <div class="card">

     <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">

      <div class="card-body">
<form method="post">
  <?php 


  if ($_SESSION['login']['idUsuario']==1) {
    ?>
<select name="idPrestador">
 <option value="-5" >TODOS</option>
                                                      

                                                         <?php for ($i=0; $i < count($prestadores) ; $i++) { 

                                                          $idPrestadorTmp=$prestadores[$i]["idPrestador"];

                                                          $nombre=$prestadores[$i]["nombre"];
                                                   

                                                           $disabled="";
                                                                $selected="";
                                                                if ($prestadores[$i]['idPrestador']==$idPrestador) {
                                                                  $selected=" SELECTED ";
                                                                }
    

                                                         ?>

                                                     

                                                        <option value="<?=$idPrestadorTmp?>" <?=$disabled?><?=$selected?>><?=$nombre?></option>



                                                         <?php } ?>

                                                      

                                                      </select>
    <?php
  } ?>
<label>Desde </label>
<input class="form-control-sm" type="date" name="desde" value="<?=$desde?>"> <label>Até</label> 
<input type="date" class="form-control-sm" name="hasta" value="<?=$hasta?>"> <button class="btn-sm btn-info">Ver</button>
</form>
            <div class="row">

                  <div class="table-responsive">   

                            

                            <table class="table" id="tablaCarrito">

                              <thead>

                                <tr>
<th></th>
<th></th>
                                    <th scope="col"><?=$lang["nombre"];?></th>

                                    <th scope="col"><?=$lang["cod-carrito"];?></th>

                                    <th scope="col"><?=$lang["fecha_de_contratacion"];?></th>

                                    <th scope="col"><?=$lang["dia_del_evento"];?></th>

                                    <th scope="col">Tarifa</th>
                                    <th scope="col">Valor Unitario</th>
                                  <th scope="col">Cant Pax</th>

                                    <th scope="col"><?=$lang["valor_total"];?></th>


                                 </tr>

                              </thead>

                    <tbody>

<?php

$cantidadReservas=0;
$totalALiquidar=0;

$reservas=getReservasConfirmadasFechasPrestador(($idPrestador), $desde, $hasta);

  $hoy=strtotime(date('Y-m-d'));

//print_r($reservas);
 for ($i=0; $i < count($reservas); $i++) { 

  $idReserva=$reservas[$i]['idReserva'];

  $horarios=getReservaHorarios($idReserva);
  
  for ($j=0; $j < count($horarios); $j++) { 



   $idReservaHorarios=$horarios[$j]["idReservaHorarios"];
              $salida=getSalida($horarios[$j]["idServicioSalidas"]);
             // print_r($salida)
;$idPrestadorSalida=($salida[0]['idPrestador']);
                  $idServicio=$salida[0]['idServicio'];
$servicio=getServicio($idServicio)[0];
//print_r($servicio);
$nombreServicio=$servicio['nombre_servicio'];
               $fechaEvento=strtotime($salida[0]['fecha']);
               $tarifas=getReservaTarifas($idReservaHorarios);
$comisionesServicioPrestador=getComisionesPrestadorServicioIdPrestadorIdServicio($idServicio, $idPrestadorSalida)[0];
$comisionesPrestador=$comisionesServicioPrestador['comisionVendedor'];
$comisionesPrestador+=$comisionesServicioPrestador['comisionSistema'];


                   for ($k=0; $k < count($tarifas); $k++) { 
   
                        $nombre_tarifa=($tarifas[$k]["nombre"]);
                        $monedaSel=$tarifas[$k]["monedaSel"];
                        $valorSinIva=$tarifas[$k]["valorSinIva"];

                       $cantidad=($tarifas[$k]["cantidad"]);
                   

$total=ConvierteMoneda($tarifas[$k]["monedaSel"],$_SESSION["moneda_sel"], $reservas[$i]["total"]);

                $totalAMostrar=    0; 
$comisionSistema=$tarifas[$k]["comisionSistema"];
$comisionVendedor=$tarifas[$k]["comisionVendedor"];
$aPagarPrestador=$valorSinIva*(($comisionesPrestador/100)*$cantidad);;;//*
$valorUnitario=$aPagarPrestador;
$totalALiquidar+=$aPagarPrestador;

                  
 

?>







                                 <tr>
                                  <td><?php print_r($tarifas);;?></td>
<td><?=$nombreServicio;?></td>
            
                                    <td><?=$reservas[$i]["nombreResponsable"]." ".$reservas[$i]["apellidoResponsable"]?></td>

                                    <td><?=$reservas[$i]["codigoAmigable"]?></td>

                                    <td> <?=date("d-m-Y H:i", strtotime($reservas[$i]['fechaAlta']));?></td>

                                    <td><?=date("d/m/Y", strtotime($salida[0]['fecha']))?></td>
                                    <td><?=$nombre_tarifa;?></td>
                                    <td><?="comisiones".$comisionesPrestador."valorTarifaSinIva".$valorSinIva; ?></td>
                                    <td><?=$_SESSION["moneda_sel_sym"].round($valorUnitario,2);?></td>
                                      <td><?=$cantidad;?></td>
                                    <td><?=$_SESSION["moneda_sel_sym"].round($aPagarPrestador,2);?></td>

                             

                             



                             </tr>



                             <?php
 
}
  }



} ?>

                        </tbody>

                      </table>
<h3>Total a liquidar: <?=$_SESSION["moneda_sel_sym"].$totalALiquidar;?></h3>
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