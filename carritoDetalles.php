







<?php 

include("includes/header.php");

include("includes/navbar.php");

include("includes/sidebar.php");

require("classes/functions.php");

require("classes/prestador.php");

require("classes/usuario.php");

require("classes/edades.php");

require("classes/reserva.php");

require("classes/salidas.php");

require("classes/servicio.php");

require("classes/comprobantes.php");

require("classes/convierte_monedas.php");

require("classes/origenes_comprobantes.php");

require("classes/codigos_telefonicos.php");





if (!$_SESSION["login"]["rol"]==1) {



  alertar("Usted no tiene acceso a esta seccion del software", "error");



  redireccionarLento("index");



}







if ($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST["nota"])) {



  if (strlen($_POST["nota"])>3) {



    $notaReserva= altaNotaReserva($_SESSION["login"]['idUsuario'], $_POST["nota"],$_POST["idReserva"], $_FILES);



  

if ($notaReserva>0) {











}



  }



else



  {alertar("no se pueden agregar notas vacias","warning");}







$_POST["detallesCarrito"]=$_POST["idReserva"];



}











if ($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST["detallesCarrito"]) ) {



$idReserva=$_POST["detallesCarrito"];

$reserva=getReservaId($idReserva);

$codigo_telefonico=getCodigoTelefonico($reserva[0]["idCountry"]);

$nombre_pais = (is_array($codigo_telefonico) && isset($codigo_telefonico[0]['nicename'])) ? $codigo_telefonico[0]['nicename'] : 'N/A';

$idioma=$reserva[0]["idioma"];

$codigoAmigable=$reserva[0]["codigoAmigable"];

$fechaReserva=date("d-m-Y H:i:s", strtotime($reserva[0]['fechaAlta']));

$idUsuario=$reserva[0]['idUsuario'];

$usuario=getUsuario($idUsuario);

$nombre_usuario=$usuario[0]["usuario"];

$nombreResponsable=$reserva[0]["nombreResponsable"]." ".$reserva[0]["apellidoResponsable"];

$telefonoResponsable=$reserva[0]["telefonoResponsable"];

$emailResponsable=$reserva[0]["emailResponsable"];

$monedaSel=$reserva[0]["monedaSel"];

$total=$reserva[0]["total"];

$impuestos=$reserva[0]["impuestos"];

$precio_sin_impuestos=ConvierteMoneda($monedaSel,$_SESSION["moneda_sel"], ($total-$impuestos));

$precio=ConvierteMoneda($monedaSel,$_SESSION["moneda_sel"], $total);

$horarios=getReservaHorarios($idReserva);

$totalComprobantes=getComprobantesIdReserva($idReserva);

$diferenciaComprobantesPrecio=$precio-$totalComprobantes;



}







 ?>



  <!-- Content Wrapper. Contains page content -->



  <div class="content-wrapper">



    <!-- Content Header (Page header) -->



    <div class="content-header bg-gradient-primary text-white">



      <div class="container-fluid">



        <div class="row mb-2">



          <div class="col-sm-6">



            <h1 class="m-0"><i class="fas fa-receipt"></i> Detalles de la Reserva</h1>



            <small class="text-light mt-2">Gestiona la informaciÃ³n de tu reserva</small>



          </div><!-- /.col -->



          <div class="col-sm-6">



            <ol class="breadcrumb float-sm-right bg-transparent">



              <li class="breadcrumb-item"><a href="index" class="text-light"><i class="fas fa-home"></i> Dashboard</a></li>



              <li class="breadcrumb-item"><a href="carritosLista" class="text-light">Reservas</a></li>



              <li class="breadcrumb-item active text-light"><i class="fas fa-receipt"></i> Detalles</li>



            </ol>



          </div><!-- /.col -->



        </div><!-- /.row -->



      </div><!-- /.container-fluid -->



    </div>



    <section class="content">



      <div class="container-fluid">      







        <!-- INFO CARDS -->
        <div class="row">
          <div class="col-md-3">
            <div class="info-box bg-light-primary">
              <span class="info-box-icon bg-primary"><i class="fas fa-calendar-alt"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Fecha de Compra</span>
                <span class="info-box-number text-primary"><?=$fechaReserva;?></span>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="info-box bg-light-success">
              <span class="info-box-icon bg-success"><i class="fas fa-barcode"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">CÃ³digo de Reserva</span>
                <span class="info-box-number text-success" style="font-size: 1.5rem;"><?=$codigoAmigable;?></span>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="info-box bg-light-warning">
              <span class="info-box-icon bg-warning"><i class="fas fa-globe"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">PaÃ­s</span>
                <span class="info-box-number text-warning"><?=$nombre_pais;?></span>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="info-box bg-light-info">
              <span class="info-box-icon bg-info"><i class="fas fa-check-circle"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Estado</span>
                <span class="info-box-number">
                  <?php 
                    $claseBoton="badge badge-warning";
                    $textoBoton="Pendiente";
                    if ($diferenciaComprobantesPrecio<1 && $precio > 0) {
                      $claseBoton="badge badge-success";
                      $textoBoton="Confirmada";
                    }
                  ?>
                  <span class="<?= $claseBoton;?>" style="font-size: 0.9rem;"><?= $textoBoton;?></span>
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- CUSTOMER DETAILS CARD -->
        <div class="card card-primary card-outline mt-4">
          <div class="card-header">
            <h3 class="card-title"><i class="fas fa-user-circle"></i> InformaciÃ³n del Cliente</h3>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label><strong><i class="fas fa-user"></i> Nombre del Responsable</strong></label>
                  <p class="text-muted"><?=$nombreResponsable;?></p>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label><strong><i class="fas fa-phone"></i> TelÃ©fono</strong></label>
                  <p class="text-muted">+<?=$codigo_telefonico?> <?=$telefonoResponsable;?></p>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label><strong><i class="fas fa-envelope"></i> Email</strong></label>
                  <p class="text-muted"><?=$emailResponsable;?></p>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label><strong><i class="fas fa-language"></i> Idioma</strong></label>
                  <p class="text-muted"><?=$idioma;?></p>
                </div>
              </div>
            </div>
          </div>
        </div>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="table-responsive">   
                <table class="table" id="tablaCarrito">
                  <thead>
                    <tr>
                       <th scope="col">Lista de pax</th>
                       <th scope="col">Servicio</th>
                       <th scope="col">Prestador</th>
                       <th scope="col">Fecha de check in</th>
                                   <th scope="col">Fecha de check in</th>



                                   <th scope="col">Horario check in</th>                



                                   <th scope="col">Horario de salida</th>



                                   <th scope="col">Valor</th>







                                </tr>



                              </thead>



                  







                           <tbody>















<?php 







for ($i=0; $i < count($horarios); $i++) { 



$servicio=getServicio($horarios[$i]["idServicioSeleccionado"]);



$nombre_servicio=$servicio[0]["nombre_servicio"];

  $nombre_salida=$horarios[$i]["nombre"];



    $fecha_checkIn=date("d-m-Y", strtotime($horarios[$i]['fecha']));

    $monedaSel=$horarios[$i]["horaCheckIn"];

  $hora_checkIn=$horarios[$i]["horaCheckIn"];

    $horaSalida=$horarios[$i]["horaSalida"];

       $idReservaHorarios=$horarios[$i]["idReservaHorarios"];

  $adicionales=   getReservaAdicionalesNoIncluidos($idReservaHorarios);

    $tarifas=getReservaTarifas($idReservaHorarios);



    $salida=getSalida($horarios[$i]['idServicioSalidas']);

    $prestador=getPrestador($salida[0]['idPrestador']);

    $prestador_nombre=$prestador[0]['nombre'];
;
     $cantidad=($tarifas[0]["cantidad"]);
    $totalTarifa=0;
    $trs='';
       $trAdc='';
    $fromEdad=getEdad($tarifas[0]['idFromEdad'])[0]['valor'];
    $toEdad=getEdad($tarifas[0]['idToEdad'])[0]['valor'];


    for ($j=0; $j < count($tarifas); $j++) { 

    $nombre_tarifa=($tarifas[$j]["nombre"]);
 $idReservaTarifas=$tarifas[$j]['idReservaTarifas'];
 $pasajeros=getPasajeros($idReservaTarifas);
 	 

 for ($k=0; $k < count($pasajeros); $k++) { 

 	  $trs=$trs.'  
 	  <tr>
                                   <td>'.$nombre_tarifa.' '.$fromEdad.' a '.$toEdad.' Anos</td> <td >'. $pasajeros[$k]["nombrePasajero"].'</td><td>'.$pasajeros[$k]["apellidoPasajero"].'</td>
                                                  </tr>';

 }
    $totalTarifa+=  $tarifas[$j]["valor"];
    }

for ($k=0; $k < count($adicionales); $k++) { 



    $trAdc=$trAdc.'  







    <tr>



                                            <td>'. $adicionales[$k]["nombre"].'</td>
                                               <td>'. $adicionales[$k]["descripcion"].'</td>
                                              <td>'.$_SESSION["moneda_sel_sym"]." ". $adicionales[$k]["precioUnitarioSIva"].'</td>
                                                 <td>'. $adicionales[$k]["cantidad"].'</td>
                                                        <td>'.$_SESSION["moneda_sel_sym"]." ". $adicionales[$k]["precioUnitarioSIva"]*$adicionales[$k]["cantidad"].'</td>
                                                           <td>'.$_SESSION["moneda_sel_sym"]." ". $adicionales[$k]["precioIva"].'</td>
                                          </tr>';
}

?>
                             
                    <!-- TABLAS LIMPIAS Y BIEN ESTRUCTURADAS -->
                    <div class="row mt-4"><div class="col-12"><h5>Pasajeros y Tarifas</h5><div class="table-responsive"><table class="table table-sm table-striped"><thead class="thead-dark"><tr><th>Tarifa</th><th>Nombre</th><th>Apellido</th></tr></thead><tbody>
                              <?=$trs?>
                            </tbody></table></div></div></div><div class="row mt-3"><div class="col-12"><h5>Servicios Adicionales</h5><div class="table-responsive"><table class="table table-sm table-striped" id="tablaCarrito"><thead class="thead-dark"><tr><th>Servicio</th><th>Descripción</th><th>Valor por Unidad</th><th>Cantidad</th><th>Total sin Impuestos</th><th>Total con Impuestos</th></tr></thead><tbody>
                              <?=$trAdc;?>
                            </tbody></table></div></div></div>

                                      </thead>

                               <tbody>
                                       <?=$trs?>
                                    </tbody>
                              </table>

          <h5> Servicios Adicionales</h5>
                               <table class="table" id="tablaCarrito">
                                    <thead>
                                          <tr>
                                            <th scope="col">Servicio</th>
                                            <th scope="col">Descripcion</th>
                                            <th scope="col">Valor por Unidad s/ISS</th>                
                                            <th scope="col">Cantidad</th>
                                            <th scope="col">Total sin Impuestos</th>
                                            <th scope="col">Total con Impuetos</th>
                                          </tr>
                                      </thead>
                                     <tbody>
                              <?=$trAdc;?>
                                </tbody>
                              </table>

                             '>



                                <td class="details-control"><input id="boton" type="submit" name="proceso" class="btn btn-info" value="Ver"></td>



                                <td><?=$nombre_servicio; ?></td>

                                  <td><?=$prestador_nombre; ?></td>

                                <td> <?=$fecha_checkIn;?></td>



                                <td> <?=$hora_checkIn;?></td>



                                <td> <?= $horaSalida;?></td>



                                <td><?=$_SESSION["moneda_sel_sym"]." ".ConvierteMoneda($tarifas[0]["monedaSel"],$_SESSION["moneda_sel"], $totalTarifa);?></td>



                             



                             </tr>















            



<?php } ?>                        















                                           



                         </tbody>



                                    <!-- /.card-body -->



             </table>















                                        



                                  



                                   







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
          </div>
          <!-- /.card -->

        <!-- ACTION BUTTONS CARD -->
        <div class="card card-outline card-primary mt-4">
          <div class="card-header">
            <h3 class="card-title"><i class="fas fa-cogs"></i> Acciones de la Reserva</h3>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-12">
                <div class="d-flex flex-wrap gap-3">
                  <button type="button" class="btn btn-warning"><i class="fas fa-money-bill"></i> Cobros y Pagos</button>
                  <button type="button" class="btn btn-danger"><i class="fas fa-times-circle"></i> Cancelar Reserva</button>
                  <button type="button" class="btn btn-info"><i class="fas fa-envelope"></i> Enviar Cobranza</button>
                  <button type="button" class="btn btn-success"><i class="fas fa-check"></i> Confirmar Reserva</button>
                  <button type="button" class="btn btn-secondary"><i class="fas fa-percentage"></i> Generar Descuento</button>
                  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-sticky-note"></i> Agregar Nota</button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /.card -->











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















                    <div class="card card-outline card-primary mt-5"><div class="card-header"><h3 class="card-title"><i class="fas fa-cogs"></i> Acciones de la Reserva</h3></div><div class="card-body"><div class="d-flex flex-wrap gap-3"><button type="button" class="btn btn-warning btn-lg"><i class="fas fa-money-bill"></i> Cobros y Pagos</button><button type="button" class="btn btn-danger btn-lg"><i class="fas fa-times-circle"></i> Cancelar Reserva</button><button type="button" class="btn btn-info btn-lg"><i class="fas fa-envelope"></i> Enviar Cobranza</button><button type="button" class="btn btn-success btn-lg"><i class="fas fa-check"></i> Confirmar Reserva</button><button type="button" class="btn btn-secondary btn-lg"><i class="fas fa-percentage"></i> Generar Descuento</button><button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-sticky-note"></i> Agregar Nota</button></div></div></div></div><div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">



                                      <div class="modal-dialog" role="document">



                                        <div class="modal-content">



                                          <div class="modal-header">



                                            <h5 class="modal-title" id="exampleModalLabel">Nueva nota</h5>



                                            <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">



                                              <span aria-hidden="true">&times;</span>



                                            </button>



                                          </div>



                                          <div class="modal-body">



                                            <form method="post" enctype="multipart/form-data">



                                              <div class="form-group">



                                                <input type="hidden" name="idReserva" value="<?=$idReserva;?>">



                                                <label for="message-text" class="col-form-label">Mensaje:</label>



                                                <textarea class="form-control" name="nota" id="message-text"></textarea>

                                                <input type="file" name="adjunto" class="form-control-file">

                                              </div>



                                           



                                          </div>



                                          <div class="modal-footer">



                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>



                                            <input name="nuevaNota"  type="submit" class="btn btn-primary"></input>



                                          </div>



                                                 </form>



                                        </div>



                                      </div>



                                    </div>











                <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">



                       <div class="card-body">



                                 <table class="table" id="tablaCarrito">



                                     <thead>



                                          <tr>



                                            <th scope="col">Total con impuestos</th>



                                            <th scope="col">Total sin impuestos</th>



                                            <th scope="col">Resta Pagar</th>



                                          </tr>



                                  </thead>



                                                  



                                     <tbody>



                                          <tr>



                                           <td><?= $_SESSION["moneda_sel_sym"].$precio;?></td>



                                           <td><?= $_SESSION["moneda_sel_sym"].$precio_sin_impuestos;?></td>



                                           <td><?= $_SESSION["moneda_sel_sym"].$diferenciaComprobantesPrecio;?></td>



                                          </tr>



                                     </tbody>



                                  </table>















                              <table class="table" id="tablaCarrito">



                                      <thead>



                                          <tr>



                                            <th scope="col">Numero de Ticket</th>



                                            <th scope="col">Valor Pago</th>



                                            <th scope="col">Fecha Hora</th>                



                                            <th scope="col">Medio de Pago</th>



                                      



                                            <th scope="col">Comprobante</th>



                                          </tr>



                                      </thead>



                                                    



                                     <tbody>



                                      <?php



$comprobantesReserva=muestraComprobantes($idReserva);



                                       for ($i=0; $i < count($comprobantesReserva); $i++) { 



                                        $totalComprobante=$comprobantesReserva[$i]["total"];



                                        $totalComprobante= ConvierteMoneda($comprobantesReserva[$i]["monedaComprobante"],$_SESSION["moneda_sel"], $totalComprobante);



                              



                                      $fechaIngreso= date("d-m-Y H:i:s", strtotime($comprobantesReserva[$i]["fechaIngreso"]));



                                      $origen_comprobante=getOrigenComprobante($comprobantesReserva[$i]["origenComprobante"]);



                                  



                                       ?>



 <tr>



                                            <td><?= $comprobantesReserva[$i]["compOrigen"];?></td>



                                            <td><?=$_SESSION["moneda_sel_sym"].$totalComprobante; ?></td>



                                            <td><?=$fechaIngreso;?></td>



                                            <td><?=$origen_comprobante[0]["nombre"];?></td>



                                            <td class="details-control"><input id="boton" type="submit" name="proceso" class="btn btn-primary" value="Ver"></td>



                                          </tr>











                                       <?php



                                      } ?>



                                        







                                    </tbody>



                              </table>



                           </div>



                      </div>



                  </div>



              </div>











                    <div class="table-responsive">



                              <table class="table">               



                                    <h5 class="m-0 text-dark">



                                       <tr>



                                          <h3 class="p-2 bg-primary text-white"class="font-weight-bold">Notas</h3>



                                          <th scope="col">Operador</th>



                                          <th scope="col">Nota</th>



                                          <th scope="col">Fecha</th>

                                          <th scope="col">Adjunto</th>





                                       </tr>



                                    </h5>



                                    <?php $notasReserva=getNotasReserva($idReserva);



                                            for ($i=0; $i < count($notasReserva); $i++) { 



                                              $idUsuario=$notasReserva[$i]["idUsuario"];



                                              $usuario=getUsuario($idUsuario);



                                              



                                           ?>



   <tr class="table-secondary">



                                          <td><?=$usuario[0]["usuario"];?></td>



                                          <td><?=$notasReserva[$i]["nota"]?></td>



                                           <td><?=$notasReserva[$i]["fecha_alta"]?></td> 

                                            <td><a href="img/adjuntos_carrito/<?=$notasReserva[$i]["adjunto"]?>" download><img style="width: 100px;" src="img/adjuntos_carrito/<?=$notasReserva[$i]["adjunto"]?>"></a></td> 

                                      </tr>







                                           <?php



                                            }



                                     ?>



                                   



                              </table>



                        </div>



                 </div> <!-- /.card -->



         </div><!-- /.container-fluid -->



    



  <?php 



   include("includes/footer.php"); ?>
