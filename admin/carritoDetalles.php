







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



    <div class="content-header">



      <div class="container-fluid">



        <div class="row mb-2">



          <div class="col-sm-6">



            <h1 class="m-0 text-dark">Detalles del carrito</h1>



          </div><!-- /.col -->



          <div class="col-sm-6">



            <ol class="breadcrumb float-sm-right">



              <li class="breadcrumb-item"><a href="#">Administración</a></li>



              <li class="breadcrumb-item active">Detalles del carrito</li>



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



                <div class="table-responsive">   







                     <table class="table">



                                  <thead>



                                      <tr>                                        



                                        <th scope="col">Fecha de compra</th>



                                        <th scope="col">COD-carrito</th>



                                        <th scope="col">Pais</th>



                                        <th scope="col">Idioma</th>



                                        <th scope="col">Tipo de Usuario</th>



                                        <th scope="col">Estado</th>



                                      </tr>



                                  </thead>



                                      <tr>



                                         <td><?=$fechaReserva;?></td>



                                         <td><?=$codigoAmigable;?></td>



                                         <td><?=$nombre_pais;?></td>



                                         <td><?=$idioma;?></td>



                                         <td><?=$nombre_usuario;?></td>



                                         <td><?php 



                                         $claseBoton="btn btn-warning";



                                        $textoBoton="Pendiente";



                                      if ($diferenciaComprobantesPrecio<1 && $precio > 0) {



                                         $claseBoton="btn btn-success";



                                          $textoBoton="Confirmada";



                                      }



                                        ?> <button type="button" class="<?= $claseBoton;?>"><?= $textoBoton;?></button></td>



                                      </tr>



                   </table>



















                      <div class="table-responsive">







                           <table class="table">



                                  



                                  <thead>



                                     <tr>



                                          <th>Nombre del responsable</th>



                                          <th>Telefono</th>



                                          <th>Email</th>



                                          <th>Canal de venta</th>



                                          <th>Contacto vendedor</th>                                              



                                     </tr>



                                  </thead>







                                     <tr>



                                          <td><?=$nombreResponsable;?></td>



                                          <td>+<?=$codigo_telefonico.$telefonoResponsable;?></td>



                                          <td><?=$emailResponsable;?></td>  



                                          <td>Destino Florianopolis Agencia</td>



                                          <td class="expand-button"><input id="boton" type="submit" name="proceso" class="btn btn-success" value="Datos Vendedor"></td>



                                                                      



                                    </tr>



                    </table>



                </div>



           </div>







        <button type="button" class="btn btn-secondary btn-lg btn-block" data-card-widget="collapse">Servicios Contratados</button>







</div>



<!-- /.card-header -->











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

    // Validar que salida existe antes de acceder
    if (empty($salida) || !isset($salida[0])) {
        error_log("WARNING carritoDetalles: getSalida retornó vacío para idServicioSalidas: ".$horarios[$i]['idServicioSalidas']);
        $prestador_nombre = 'Prestador no disponible';
    } else {
        $prestador=getPrestador($salida[0]['idPrestador']);
        $prestador_nombre=$prestador[0]['nombre'] ?? 'Prestador desconocido';
    }

    // Validar que tarifas exista
    if (empty($tarifas) || !isset($tarifas[0])) {
        error_log("WARNING carritoDetalles: getReservaTarifas retornó vacío para idReservaHorarios: ".$idReservaHorarios);
        $cantidad = 0;
        $fromEdad = 'N/A';
        $toEdad = 'N/A';
    } else {
        $cantidad=($tarifas[0]["cantidad"]);
        $fromEdad=getEdad($tarifas[0]['idFromEdad'])[0]['valor'];
        $toEdad=getEdad($tarifas[0]['idToEdad'])[0]['valor'];
    }
    
    $totalTarifa=0;
    $trs='';
    $trAdc='';


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
                             <tr class="accordion-toggle collapsed" id="accordion1" data-toggle="collapse" data-parent="#accordion1" href="#collapseTwo" data-child-name="row0" data-child-value='
 <table class="table" >
                                      <thead>
                                          <tr>
                                            <th scope="col">Tarifa</th>
                                            <th scope="col">Nombre</th>
                                           <th scope="col">Apellido</th>
                                          </tr>
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



               <div class="card-footer">



                                                 <!-- <div align="center"> <button type="submit" id="uploadfiles" value="Crear servicio" class="btn btn-success"><i class="fa fa-floppy-o" aria-hidden="true"></i> Continuar</button>   <a href="servicios.php" class="btn btn-danger" ><i class="fa fa-times" aria-hidden="true"></i> Salir sin guardar</a>



                                        </div>-->



          </div>



     </div>



     <!-- /.card -->















                    <div class="card-body"> 



                       <div id="accordion">



                         <div class="card">



                           <div class="table responsive">



                              <div class="card-header" id="headingOne">



                                 <button class="btn btn-warning" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne"> Cobros y Pagos</button>



                                 <button type="button" class="btn btn-danger">Cancelar Reserva</button>



                                 <button type="button" class="btn btn-warning">Enviar Cobranza</button>



                                 <button type="button" class="btn btn-success">Confirmar Reserva</button>



                                 <button type="button" class="btn btn-secondary">Generar Descuento</button>



                                 <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-whatever="agregarNota">Agregar Nota</button>          



                              </div>



                          </div>







                                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">



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