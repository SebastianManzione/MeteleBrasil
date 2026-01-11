<?php 





include("includes/headPagos.php");





include("admin/classes/salidas.php");



include("admin/classes/tarifas.php");



include("admin/classes/idiomas.php");



include("admin/classes/servicio.php");



  include("admin/classes/comisiones.php");



    include("admin/classes/edades.php");



    include("admin/classes/cancelaciones.php");



    include("admin/classes/servicios_adicionales.php");



        include("admin/classes/convierte_monedas.php");



    include("admin/classes/codigos_telefonicos.php");




$totalCarrito=0;
$descuentoGanado=0; // Para AR$ - suma de redondeoDiferencia





$carrito=$_SESSION['reserva'];







//print_r($carrito);



$cantCarrito=count($carrito);















/*



if ($cantCarrito<1) {



echo '



<script>



location.href="carrito";



</script>



';



}



*/



?>













<!--PASOS PARA RESERVA-->



<section class="py-2 bg-white">



  <div class="container">



    <div class="row">



      <div class="col-lg-12  ">



        <ul class="lista-pasos-form">



          <li><a href="carrito.php" class="btn-pasos"><span>1</span><?=$lang["revisa_tus_reservas"]?></a></li>



          <li  class="active"><b><strong><span>2</span><?=$lang["datos_personales"]?></strong></b></li>



          <li><span>3</span> <strong><?=$lang["metodo_de_pago"]?></strong></li>



        </ul>



      </div>



    </div>



  </div>



</section>



<!--FIN PASOS PARA RESERVA-->



<script type="text/javascript">



  function validateForm(){









    if($('#politicasPC').prop('checked')){



  $('#body').css('display','none');



  $('#bodyCarga').html('<div class="d-flex justify-content-center" style="margin-top: 15em;">  <div class="spinner-border" role="status">    <span class="sr-only">Loading...</span>  </div></div>');







     return true;



    }



    else{



      Swal.fire("Reservate", "Aceptar la politica de privacidad y cookies es una obligacion legal", "error");



   // alert("Aceptar la politica de privacidad y cookies es una obligacion legal");



      return false;



    }


  }











</script>



<form method="post" action="guardaReservas.php"  onsubmit="return validateForm()" >



<!--SECCION DATOS PERSONALES-->







<section>



  <div class="container">



    <div class="row">







  <!--RESUMEN DE PEDIDO-->



<?php include('resumo_compra_carrinho.php'); ?>



      <!--FIN RESUMEN DE PEDIDO-->







      <!--DATOS PERSONALES-->



      <div class="col-lg-8 col-md-8">







<div class="card-body">



                <h5><?=$lang["responsable_de_la_reserva"]?></h5>







                  <div class="form-row ">



                    <div class="form-group col-6">



                      <input type="text" class="form-control" placeholder="<?=$lang["nombre"];?>" name="txtNombreResponsable" required>



                    </div>



                    <div class="form-group col-6">



                      <input type="text" class="form-control" placeholder="<?=$lang["apellido"];?>" name="txtApellidoResponsable" required>



                    </div>



                  </div>     



                    



                  <div class="form-row">



                    <div class="form-group col-6">



                      <input type="email" class="form-control" placeholder="E-mail" name="txtEmailResponsable" required>



                    </div>



                    <div class="form-group col-3">



                      <select class="form-control" name="idCountry">



                        <?php $codigosTelefonicos=getCodigosTelefonicos();



                for ($i=0; $i < count($codigosTelefonicos); $i++) { 



                  $selected="";



                  if ($codigosTelefonicos[$i]["phonecode"]==55) {



                  $selected="selected";



                  }



               ?><option value="<?=$codigosTelefonicos[$i]['id'];?>"<?=$selected;?>> +<?=$codigosTelefonicos[$i]['phonecode'];?> <?=$codigosTelefonicos[$i]['nicename'];?> </option><?php



                 } ?>



                        



                      </select>



                    </div>



                    <div class="form-group col-3">



                      <input type="text" class="form-control" placeholder="<?=$lang["telefono"];?>" name="txtTelefonoResponsable" required>



                    </div>



                  </div>



              



              </div>







            



           <!--DATOS DE ACTIVIDADES -->



           



           <?php 



            require("admin/classes/fotos_servicio.php");  



           $carrito=$_SESSION['reserva'];







           //print_r($carrito);



           $cantCarrito=count($carrito);











           for ($i=0; $i < $cantCarrito; $i++) {  



            echo "RESERVA nro: ".($i+1);



            $reserva=$carrito[$i][0];







            $reservaAdicionales=$carrito[$i][1];



            $idServicio=$reserva[0]['idServicioSeleccionado'];



             $precioReserva=0;



             $cantidadPasajeros=0;



             $servicio=getServicio($reserva[0]['idServicioSeleccionado']);



             $fotos=getFotoMiniaturaServicio($idServicio);



            



            



          ?>







       <div class=" py-2">



             <div class="card card-visitas">



                <div class="card-body">



                   <div class="row ">



                      <div class="col-md-4  col-4">



                                <img   src="admin/classes/imgServicio/<?=$fotos[0]['ruta'];?>" class=" img-fluid img-card-destinos">



                       



             



           



                      </div>



                       <div class="col-md-8 col-8">



                         <div class="card-block ">



                          <h4 class="text-left titulo-card-destinos text-primary  mb-4"><?=$servicio[0]["nombre_servicio"];?> </h4>



                          <div class="d-md-block">



                            <div class="row no-gutters text-center ">



                            <div class="col-lg-3 col-md-3">



                  <p class="mb-0"><label class="h3 text-gris">



                             <?php 



                               $cantidadPasajeros=0;



                              for ($j=0; $j < count($reserva); $j++) { 



                                $cantidadPasajeros+=$reserva[$j]['cantidad'];



                              



                              }



                             ?>



                             <?=  $cantidadPasajeros;?>  



 <i class="fas fa-users text-gris"></i> 



                              </label></p>



                           



      <?php



       $precioReserva=0;



       $cantidadPasajeros=0;







      for ($j=0; $j < count($reserva); $j++) { 



  



      $idServicioSalidasTarifas=$reserva[$j]['idServicioSalidasTarifas'];



      $cantidad=$reserva[$j]['cantidad'];



      $cantidadPasajeros+=$reserva[$j]['cantidad'];



        $tarifa=calculaTarifa($reserva[$j]["idServicioSalidasTarifas"],



$reserva[$j ]["cantidad"]);

        // Acumular descuento del redondeo para monedas devaluadas (ARS, CLP, PYG)
        if (in_array($_SESSION['moneda_sel'], [270, 271, 225]) && isset($tarifa[0]['redondeoDiferencia'])) {
            $descuentoGanado += $tarifa[0]['redondeoDiferencia'];
        }   



        $salida=getSalida($tarifa[0]['idServicioSalidas'] );



        $idiomas= getIdiomaSalida($salida[0]['idServicioSalidas']);



          $precioReserva+=$tarifa[0]["valor"];

          $fecha=strtotime($salida[0]['fecha']);

          require("admin/classes/locale.php");

         if($j==0){







         }



      ?>



     <li> <?= $cantidad.' '.$tarifa[0]["nombre"].' ('.$tarifa[0]["edadFrom"].' a '.$tarifa[0]["edadTo"].' Anos)'?></li> 



  











     <?php



$precioTotalCarrito+=$tarifa[0]["valor"];







      } //     for ($j=0; $j < count($reserva); $j++) { 











?>  







                  



                            </div>



                            <div class="col-lg-3 col-md-3 ">



                              <p class="mb-0 h3 text-gris"><i class="fa fa-language "></i></p>



        <?php for ($m=0; $m < count($idiomas); $m++) { 



                                ?>



<li><?=$idiomas[$m];?></li>



                                <?php



                              } ?>



                            



                            </div>



                            <div class="col-lg-3 col-md-3">



                               <p class="mb-0 h3 text-gris"> <i class="fa fa-calendar-alt"></i>



                     <?=date("d", strtotime($salida[0]['fecha']));?>



                               </p>



                             <!--<p><?php //echo DevuelveFechaHorario($horarioId)[0][2]; ?></p>-->



                             <p class="mb-0">



                   <?php echo (strftime("%B", $fecha));?>



                              <?=date("Y", strtotime($salida[0]['fecha']));?>



                               



                             </p>



                            </div>



                            <div class="col-lg-3 col-md-3">



                               <p class="mb-1  h3 text-gris"><i class="fa fa-clock"></i></p>



                               <p>      <?=$salida[0]['horaCheckIn'];?></p>



                            </div>



                          </div>



                          </div>



                        </div>



                  </div>



                </div>



                <div class="row">



              



                </div>



                <?php for ($k=0; $k < count($reserva); $k++) { 







        $tarifa=calculaTarifa($reserva[$k]["idServicioSalidasTarifas"],$reserva[$k]["cantidad"]);

        // Si es AR$, acumular el descuento del redondeo
        //if ($_SESSION['moneda_sel_sym'] == 'AR$') {
        //   $descuentoGanado += $tarifa[0]['redondeoDiferencia'];
        //}







                  $cantidadPasajeros=($reserva[$k]["cantidad"]);



                  for ($l=0; $l < $cantidadPasajeros; $l++) { 



                    # code...



                  



                 ?>



               <div class="card-body">



                <h5><?=$lang["pasajero"]?> <?= $tarifa[0]["nombre"].' ('.$tarifa[0]["edadFrom"].' a '.$tarifa[0]["edadTo"].' Anos)'?></h5>



           <input type="hidden" name='reservas[<?=$i?>][<?=$k?>][idServicioSalidasTarifas]' value='<?=$reserva[$k]["idServicioSalidasTarifas"]?>'>



                      <input type="hidden" name='reservas[<?=$i?>][<?=$k?>][idServicioSeleccionado]' value='<?=$reserva[$k]["idServicioSeleccionado"]?>'>







                  <div class="form-row ">



                    <div class="form-group col-6">



                      <input type="text" class="form-control" placeholder="<?=$lang["nombre"];?>"  name="pasajero[<?=$i?>][<?=$k?>][<?=$l?>][0]" required>



                    </div>



                    <div class="form-group col-6">



                      <input type="text" class="form-control" placeholder="<?=$lang["apellido"];?>" name="pasajero[<?=$i?>][<?=$k?>][<?=$l?>][1]" required>



                    </div>



                  </div>







     







      



              



              </div>



  



                 <?php 



                }?>







     



              <?php }?>



 







       <div class="row">



                  <div class="col-md-12">

                    <button class="btn btn-link p-0 text-left" type="button" onclick="toggleComentario('collapseExample<?=$i?>comentario<?=$k?>')">
                      <i class="fas fa-comment"></i> <?=$lang["comentarios_al_proveedor"]?>
                    </button>

                    <div class="collapse mt-2" id="collapseExample<?=$i?>comentario<?=$k?>">



                   



                        <div class="form-group">



                        <textarea class="form-control"



                        name='reservas[<?=$i?>][0][comentario]'  placeholder="



Comentarios (opcional) - 0/300" id="exampleFormControlTextarea1" rows="3"></textarea>



                      </div>



                     



                    </div>



                  </div>



                </div>



















              </div>



             </div>



            </div>



      







<?php











}







            ?>







           <!--FIN DATOS DE ACTIVIDADES-->







          <!--CONTENEDOR POLITICAS DE PRIVACIDAD-->



            <div class="container">



              <div class="row">



                <div class="col-lg-12">



                  <div class="custom-control custom-checkbox mr-sm-2">



                    <input type="checkbox" class="custom-control-input" id="politicasPC" >



                    <label class="custom-control-label" for="politicasPC"><?=$lang["acepto_las"];?> <a href="aviso" target="_BLANK"><?=$lang["politicas"]?></a> <a data-toggle="modal" data-target="#politicas"><i class="fa fa-exclamation-circle"></i> </a><?=$lang["y_las_condiciones_generales"]?> </label>



                  </div>



                </div>



              </div>



            </div>



        <!--CONTENEDOR POLITICAS DE PRIVACIDAD-->







      </div>



      <!--FIN DATOS PERSONALES-->



    </div>







  </div>















<!--BOTON SIGUIENTE-->



    <div class="container mb-4">



      <div class="row justify-content-end">



        <div class="col-lg-4 col-md-6 col-12 text-right">



          <button type="submit" class="btn btn-primary btn-lg btn-radius shadow-sm px-4 py-3" style="width: 100% !important;">
            <?=$lang["continuar"]?> <i class="fas fa-arrow-right ml-2"></i>
          </button>



        </div>



      </div>



    </div>



<!--FIN BOTON SIGUIENTE-->







</section>



<!--FIN SECCION DATOS PERSONALES-->







</form>







  <!-- Footer -->



  <footer class="footer footer-reserva ">



    <div class="container">



      <div class="row">



        <div class="col-lg-4"></div>



        <div class="col-lg-2">



         <p class="text-gris text-pagos"> <i class="fa fa-lock mx-2 "></i> PAGO SEGURO</p>



        </div>



        <div class="col-lg-2">



          <img src="img/paypal-2.png" class="img-fluid img-foter">



        </div>



        <div class="col-lg-2">



          <img src="img/mastercard-2.png" class="img-fluid img-foter">



        </div>



        <div class="col-lg-2">



          <img src="img/visa-2.png" class="img-fluid img-foter">



        </div>



      </div>



    </div>



  </footer>







  <!-- Copyright Section -->



  <section class="copyright py-4 text-center text-white">



    <div class="container">



      <div class="row">



        <div class="col-lg-12">



           <h4 class="text-left"><small><span>METELE BRASIL</span><?=$lang["es_una_marca_registrada_de_reservate_sl"]?></small></h4>



        </div>



      </div>



    </div>



  </section>























  <!-- BOTON SUBIR-->



  <div class="scroll-to-top  position-fixed ">



    <a class="js-scroll-trigger d-block text-center text-white rounded" href="#page-top">



      <i class="fa fa-chevron-up"></i>



    </a>



  </div>



  <!-- FIN BOTON SUBIR-->











 <!-- SCRIPTS NECESARIOS-->



 



  <!-- JQUERY-->



  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>



  <!-- JQUERY-->



  



  <!-- UNDERSCORE-->



  <script src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js"></script>



  <!-- UNDERSCORE-->



  



  <!-- MOMENT -->



  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment.min.js"></script>



  <!-- MOMENT -->



  



  <!-- WOW ANIMACION -->



  <script src="js/wow.min.js"></script>



  <!-- WOW ANIMACION -->







  



  <!-- BOOTSTRAP BUNDLE -->

  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- BOOTSTRAP BUNDLE -->

  <script>
  function toggleComentario(id) {
      const element = document.getElementById(id);
      if (element) {
          element.classList.toggle('show');
      }
  }
  </script>

  <!-- JQUERY EASING -->

  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- JQUERY EASING -->







  <!-- CUSTOM -->



  <script src="js/scriptcarrito.js"></script>



  <!-- CUSTOM -->



  



<!-- Modal de Descuento Monedas Devaluadas (ARS, CLP, PYG) -->
<div class="modal fade" id="modalDescuentoARS" tabindex="-1" role="dialog" aria-labelledby="modalDescuentoARSLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="modalDescuentoARSLabel">
          <i class="fas fa-gift"></i> ¡Felicitaciones!
        </h5>
      </div>
      <div class="modal-body text-center">
        <h4 class="text-success mb-3">¡TE HAS GANADO UN DESCUENTO POR TU COMPRA!</h4>
        <div class="alert alert-success">
          <h5>Descuento ganado: <strong id="descuentoGanadoText"><?= $_SESSION['moneda_sel_sym'] ?>0</strong></h5>
          <p class="mb-0">Este descuento se aplicará automáticamente a tu compra.</p>
        </div>
        <p class="text-muted">
          Al continuar, confirmarás tu reserva con el precio final ya descontado.
        </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success btn-lg" id="btnAceptarDescuento">
          <i class="fas fa-check"></i> Aceptar Descuento y Continuar
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Script para Modal de Descuento Monedas Devaluadas (ARS, CLP, PYG) -->
<script>
$(document).ready(function() {
    // Verificar si es moneda devaluada y mostrar modal (solo si no se ha aceptado antes)
    <?php if (in_array($_SESSION['moneda_sel'], [270, 271, 225]) && $descuentoGanado > 0 && !isset($_SESSION['descuento_ars_aceptado'])): ?>
        var monedaSimbolo = '<?= $_SESSION['moneda_sel_sym'] ?>';
        var descuentoValor = <?= round($descuentoGanado) ?>;
        $('#descuentoGanadoText').text(monedaSimbolo + descuentoValor.toLocaleString('es-AR'));
        $('#modalDescuentoARS').modal('show');
    <?php endif; ?>

    // Manejar el botón de aceptar descuento
    $('#btnAceptarDescuento').click(function() {
        var descuentoMonto = '<?php echo $descuentoGanado; ?>';
        var simboloMoneda = '<?php echo $_SESSION['moneda_sel_sym']; ?>';

        // Usar AJAX para guardar que se aceptó el descuento en la sesión
        $.post('ajax/guardar_descuento_ars.php', {
            descuento_aceptado: '1',
            descuento_ganado: descuentoMonto
        }, function(response) {
            if (response.success) {
                $('#modalDescuentoARS').modal('hide');

                // Actualizar dinámicamente el resumen del carrito
                actualizarResumenDescuento(descuentoMonto, simboloMoneda);

                // Mostrar mensaje de éxito con SweetAlert2
                Swal.fire({
                    title: '¡Descuento Aplicado!',
                    text: 'El descuento se ha aplicado a tu reserva.',
                    icon: 'success',
                    confirmButtonText: 'Continuar'
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: 'Error al aplicar el descuento. Intente nuevamente.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            }
        }, 'json').fail(function() {
            $('#modalDescuentoARS').modal('hide');
            Swal.fire({
                title: 'Error de conexión',
                text: 'No se pudo conectar al servidor. El descuento no se aplicó.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        });
    });

    // Función para actualizar dinámicamente el resumen con el descuento
    function actualizarResumenDescuento(descuentoMonto, simboloMoneda) {
        // Buscar la lista del resumen del carrito
        var $listaResumen = $('.lista-caracteristicas-r');

        if ($listaResumen.length > 0) {
            // Verificar si ya existe una fila de descuento
            var $descuentoExistente = $listaResumen.find('li:contains("Descuento Promocional")');

            if ($descuentoExistente.length === 0) {
                // Agregar la fila del descuento después del subtotal general
                var descuentoHTML = '<li><strong>Descuento Promocional:</strong> -' + simboloMoneda + descuentoMonto + '</li>';

                // Buscar el elemento "Subtotal Geral" y agregar el descuento después
                var $subtotalGeral = $listaResumen.find('li:contains("Subtotal Geral")');
                if ($subtotalGeral.length > 0) {
                    $subtotalGeral.after(descuentoHTML);
                } else {
                    // Si no encuentra Subtotal Geral, agregar al final de la lista
                    $listaResumen.append(descuentoHTML);
                }
            }

            // Actualizar el total final
            actualizarTotalFinal(descuentoMonto, simboloMoneda);
        }
    }

    // Función para actualizar el total final restando el descuento
    function actualizarTotalFinal(descuentoMonto, simboloMoneda) {
        // Buscar el elemento que contiene el total (puede ser <strong> o <tron>)
        var $totalElement = $('.div-precio-t h5 strong, .div-precio-t h5 tron');

        if ($totalElement.length > 0) {
            // Obtener el total actual del elemento
            var totalTexto = $totalElement.text().trim();

            // Extraer solo el valor numérico (remover símbolo de moneda y formateo)
            var valorActualTexto = totalTexto.replace(simboloMoneda, '').replace(/[^\d,]/g, '');
            var valorActual = parseFloat(valorActualTexto.replace(',', '.'));

            if (!isNaN(valorActual)) {
                // Restar el descuento
                var nuevoTotal = valorActual - parseFloat(descuentoMonto);

                // Formatear el nuevo total
                //var nuevoTotalFormateado = simboloMoneda + nuevoTotal.toLocaleString('es-AR');
                var nuevoTotalFormateado = simboloMoneda + String(Math.round(nuevoTotal));

                // Actualizar el texto del total
                $totalElement.text(nuevoTotalFormateado);
            }
        }
    }
});
</script>

<!-- FIN SCRIPTS NECESARIOS-->



</div>



</body>







</html>



