<?php                                                                                                                                                                                                                                                                                                                                                                                                
 

include("includes/navbar.php");
  include($GLOBALS['path'].'/conectar.php');


?>



 <!--SECCION HEADER-->
<section id="header-visitas" class="menu-h" style="background-image: url('img/slider4.jpg');">
  <div class="container d-md-block d-none">
    <div class="row">
      <div class="col-lg-12">
                  
          <!--TITULO-->
        <h1 class="text-white texto-shadow py-2 bold" style=" text-shadow: -1px 0px 6px #000000;">Preguntas Frecuentes</h1>
         <!--TITULO-->
        
      
      </div>
    </div>
  </div>
 <!--header-->
  
 <!-- CONTENEDOR DE CARACTERISTICAS-->



  <br>
  
  <!-- CONTENEDOR DE FRANJA TRANSPARENTE-->
  <div class="container  d-md-block d-none ">
     <div class="row">
      <div class="col-lg-12">
        <div class="div-fondo-visita-">
          <ul class="lista-visitas text-white">
          </ul>
        </div>
      </div>
    </div>
  </div>
    <!-- FIN CONTENEDOR DE FRANJA TRANSPARENTE-->
    
    
    <!-- CONTENEDOR DE BOTONES EN MOVIL-->
  <div class="container-fluid  d-md-none">
     <div class="row">
      <div class="col-6">
          <a  data-toggle="collapse" class="btn-reservar" href="#compartir" role="button" aria-expanded="false" aria-controls="collapseExample" ><i class="text-dark fa fa-share-alt"></i></a>
                 <div class="collapse" id="compartir">
                  <div class="card card-body">
                   <a class="btn btn-outline-light btn-social facebook mx-1 mb-2" href="#">
                    <i class="fab fa-fw fa-facebook-f"></i>
                  </a>
                  <a class="btn btn-outline-light btn-social linkedin mx-1 mb-2" href="#">
                    <i class="fab fa-fw fa-instagram"></i>
                  </a>
                  </div>
                </div>
      </div>
    </div>
  </div>
    <!-- FIN CONTENEDOR DE BOTONES EN MOVIL-->
  
</section>
 <!--FIN SECCION HEADER-->




<!--FIN MENU FIJO-->


<!--SECCION INFORMACION DE VISITA MOVIL-->


<!--FIN SECCION INFORMACION DE VISITA MOVIL-->




<section class="py-5 d-md-block d-none">
  <div class="container container_r clearfix">
      <div class="row">
           <!--COL INFORMACION IZQUIERDA-->
          <div class="col-lg-12">
             <div id="content">
                 
            <div class="o-container-work-us">
             <!--ACORDEON HORA-->
             <div class="accordion mb-2" id="Seleccionar_descuento">
                 <div class="card card-accordion">                
                    <div id="seleccionar_descuento" class=" " aria-labelledby="headingOne" data-parent="#seleccionar_descuento">
                      <div class="form-group">
                        <!---- Acá va el acordeon de preguntas generales ---->
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#puntoencuentro" aria-expanded="false" aria-controls="collapseOne">
                                  ¿Cual es el punto de encuentro de la actividad? 
                                  </a>
                                </h5>
                             </div>
                            <div id="puntoencuentro" class="collapse " aria-labelledby="headingOne" data-parent="#puntoencuentro">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p>El punto de encuentro está detallado en cada actividad así como en el email de confirmación una vez realizada la reserva. Si tienes dudas una vez en el destino, puedes contactar con el proveedor local en el teléfono que verás en el bono.</p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#otrasciudades" aria-expanded="false" aria-controls="collapseOne">
                                  ¿Vamos a viajar a otras ciudades?¿Donde más tienen tours? 
                                  </a>
                                </h5>
                             </div>
                            <div id="otrasciudades" class="collapse " aria-labelledby="headingOne" data-parent="#otrasciudades">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p>Podrán ver todas nuestras guías de viajes en el home de nuestra página, en la mayoría de ellas, tenemos servicios de visitas guiadas, excursiones y traslados.</p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#otrostours" aria-expanded="false" aria-controls="collapseOne">
                                  ¿Tienen otros tours o actividades además de los publicados en cada destino? 
                                  </a>
                                </h5>
                             </div>
                            <div id="otrostours" class="collapse " aria-labelledby="headingOne" data-parent="#otrostours">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p>En algunos destinos ofrecemos servicios privados para actividades no publicadas en la web. En todo caso, puedes ver todos los servicios con los que contamos en Nuestra página.</p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#bono" aria-expanded="false" aria-controls="collapseOne">
                                  ¿Es necesario imprimir un bono o un justificante? 
                                  </a>
                                </h5>
                             </div>
                            <div id="bono" class="collapse " aria-labelledby="headingOne" data-parent="#bono">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p>Algunos servicios requieren un bono impreso, otros en el móvil, y otros no requieren llevar nada. Ésto es específico de cada actividad y lo podréis ver en su ficha.</p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#sillasniños" aria-expanded="false" aria-controls="collapseOne">
                                  En las excursiones en vehículo ¿Hay sillas para niños pequeños? 
                                  </a>
                                </h5>
                             </div>
                            <div id="sillasniños" class="collapse " aria-labelledby="headingOne" data-parent="#sillasniños">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p>Los proveedores disponen de sillitas de niño en función de las leyes del país (en el 90% de los destinos son obligatorias). Al hacer la reserva no olvidéis indicar la edad de los niños en los comentarios.</p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#itinerario" aria-expanded="false" aria-controls="collapseOne">
                                  ¿Se accede a todos los lugares indicados en el itinerario? 
                                  </a>
                                </h5>
                             </div>
                            <div id="itinerario" class="collapse " aria-labelledby="headingOne" data-parent="#itinerario">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p>Siempre tratamos de dejar claro si a un monumento se accede o solo se pasa por él. En caso de que no indiquemos nada, lo normal es que no se acceda a su interior.</p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#tarde" aria-expanded="false" aria-controls="collapseOne">
                                  No sé si me dará el tiempo para llegar al tour ¿Si llego tarde me esperarán? 
                                  </a>
                                </h5>
                             </div>
                            <div id="tarde" class="collapse " aria-labelledby="headingOne" data-parent="#tarde">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p>Los tours regulares salen siempre puntuales y no es posible modificar la hora de inicio. Respecto a los tours privados, si queréis empezar a otra hora podéis consultarnos antes de hacer la reserva.</p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#otrapersona" aria-expanded="false" aria-controls="collapseOne">
                                  ¿Puedo reservar para otra persona? 
                                  </a>
                                </h5>
                             </div>
                            <div id="otrapersona" class="collapse " aria-labelledby="headingOne" data-parent="#otrapersona">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p>¡Claro! Simplemente debes indicar los datos de la persona que realizará la actividad en lugar de los tuyos. En la información de pago sí que deberás indicar tus datos para que la transacción se complete correctamente.</p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#pago" aria-expanded="false" aria-controls="collapseOne">
                                  ¿Como puedo pagar mis reservas? 
                                  </a>
                                </h5>
                             </div>
                            <div id="pago" class="collapse " aria-labelledby="headingOne" data-parent="#pago">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p>Nuestra web ofrece un servicio de reserva muy fácil e intuitivo y te permite realizar el pago de manera segura con Visa, MasterCard o PayPal. No es posible pagar en el destino o al guía directamente. Todas las reservas deben hacerse a través de la página web.</p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#pago2" aria-expanded="false" aria-controls="collapseOne">
                                  ¿El pago es seguro? 
                                  </a>
                                </h5>
                             </div>
                            <div id="pago2" class="collapse " aria-labelledby="headingOne" data-parent="#pago2">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p>En METELEBRASIL disponemos de un sistema de pago online 100% seguro y encriptado contra fraudes y transacciones no autorizadas, estando certificados como comercio electrónico Trustwave Trustkeeper.</p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#pago3" aria-expanded="false" aria-controls="collapseOne">
                                  Creo que he realizado el pago y no me ha llegado la confirmación
                                  </a>
                                </h5>
                             </div>
                            <div id="pago3" class="collapse " aria-labelledby="headingOne" data-parent="#pago3">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p>Muy probablemente el pago no llegó a completarse y por lo tanto el servicio no está confirmado, deberías volver a hacer la reserva y finalizar el proceso. Si compruebas que te han cargado el importe en tu banco / paypal, posiblemente hayas puesto mal el email. En este caso, escríbenos y te ayudaremos a solucionarlo. </p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#pago4" aria-expanded="false" aria-controls="collapseOne">
                                  ¿Es posible hacer la reserva y pagar todo en destino?
                                  </a>
                                </h5>
                             </div>
                            <div id="pago4" class="collapse " aria-labelledby="headingOne" data-parent="#pago4">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p>En la actualidad es necesario abonar el importe total en el momento de hacer la reserva. Es posible pagar con tarjeta de crédito (excepto American Express), tarjeta de débito o mediante cuenta de PayPal. </p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#pago5" aria-expanded="false" aria-controls="collapseOne">
                                  ¿Cuanto tiempo tengo que esperar para recibir el email de confirmación?
                                  </a>
                                </h5>
                             </div>
                            <div id="pago5" class="collapse " aria-labelledby="headingOne" data-parent="#pago5">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p>El correo de confirmación es inmediato una vez realizado el pago. Si no te llega hay dos posibles motivos:<br>
                                    Que no se haya completado correctamente el pago. Comprueba tu cuenta bancaria o Paypal. <br>Que no hayas escrito correctamente tu email. Escríbenos un email y te ayudaremos a solucionarlo lo antes posible. </p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#opiniones1" aria-expanded="false" aria-controls="collapseOne">
                                  Las opiniones son demasiado buenas ¿Son reales?
                                  </a>
                                </h5>
                             </div>
                            <div id="opiniones1" class="collapse " aria-labelledby="headingOne" data-parent="#opiniones1">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p>Todas las opiniones son de personas que han reservado a través de Civitatis. Al acabar la actividad recibiréis un email para que podáis publicar vuestra valoración de la que esperemos haya sido una fantástica experiencia.</p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#opiniones2" aria-expanded="false" aria-controls="collapseOne">
                                  ¿Cómo puedo dejar mi opinión sobre una actividad?
                                  </a>
                                </h5>
                             </div>
                            <div id="opiniones2" class="collapse " aria-labelledby="headingOne" data-parent="#opiniones2">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p>Nuestra razón de ser es su satisfacción. Recibirás automáticamente una encuesta por email al finalizar cada actividad reservada. ¡Ayúdanos a mejorar!</p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#funcionarios" aria-expanded="false" aria-controls="collapseOne">
                                  ¿Tienen descuentos para un rubro específico o funcionarios públicos?
                                  </a>
                                </h5>
                             </div>
                            <div id="funcionarios" class="collapse " aria-labelledby="headingOne" data-parent="#funcionarios">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p>Lo sentimos; los precios disponibles en cada actividad son los únicos que podemos ofrecer. Son los mismos para para todos los clientes. </p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#minusvalidos" aria-expanded="false" aria-controls="collapseOne">
                                  ¿Hacen descuentos para jubilados, niños o minusválidos?
                                  </a>
                                </h5>
                             </div>
                            <div id="minusvalidos" class="collapse " aria-labelledby="headingOne" data-parent="#minusvalidos">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p>Solo se realizan estos descuentos si están indicados en la ficha de la actividad, es algo que depende de nuestros colaboradores locales.</p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#descuentos" aria-expanded="false" aria-controls="collapseOne">
                                  ¿Hay decuentos por contratar varias excursiones o servicios?
                                  </a>
                                </h5>
                             </div>
                            <div id="descuentos" class="collapse " aria-labelledby="headingOne" data-parent="#descuentos">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p>Tratamos de ofrecer el precio mínimo en cada actividad, por lo que no hacemos descuentos adicionales por reservar dos o más tours.</p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#cancelar" aria-expanded="false" aria-controls="collapseOne">
                                  ¿Las reservas se pueden cancelar?
                                  </a>
                                </h5>
                             </div>
                            <div id="cancelar" class="collapse " aria-labelledby="headingOne" data-parent="#cancelar">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p>En la ficha de cada actividad se indica su política de cancelación. Si la cancelación se realiza fuera de plazo y es por causas graves o ajenas, contactad con nosotros para que tratemos de solucionarlo.</p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#cancelar1" aria-expanded="false" aria-controls="collapseOne">
                                  ¿Cuál es la política de cancelación?
                                  </a>
                                </h5>
                             </div>
                            <div id="cancelar1" class="collapse " aria-labelledby="headingOne" data-parent="#cancelar1">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p>Siempre tratamos de maximizar el periodo de cancelación sin gastos. No obstante, cada actividad y cada destino es diferente por lo que tendrás que visitar el apartado "cancelaciones" de cada actividad para ver las condiciones específicas de cancelación.</p>
                                  <p>En caso de tener derecho a cancelación, se devolverá el importe mediante la misma forma de pago en la que se realizó la reserva. Es una operación automática que por seguridad no es posible modificar. </p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#lluvia" aria-expanded="false" aria-controls="collapseOne">
                                  ¿Qué sucede si llueve o hace mal tiempo?
                                  </a>
                                </h5>
                             </div>
                            <div id="lluvia" class="collapse " aria-labelledby="headingOne" data-parent="#lluvia">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p>Todas las actividades se realizan con normalidad a lo largo de todo el año independientemente de la lluvia. Si por condiciones extremas (por ejemplo, una fuerte nevada) el proveedor tuviera que cancelar un tour, se ofrecería una fecha alternativa o el reintegro inmediato del pago.</p>
                                  
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#cancelar2" aria-expanded="false" aria-controls="collapseOne">
                                  ¿Puedo cambiar la forma de pago en caso de cancelación?
                                  </a>
                                </h5>
                             </div>
                            <div id="cancelar2" class="collapse " aria-labelledby="headingOne" data-parent="#cancelar2">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p>En caso de tener derecho a cancelación, se devolverá el importe mediante la misma forma de pago en la que se realizó la reserva. Es una operación automática que por seguridad no es posible modificar.</p>
                                  
                              </div>
                            </div>
                            </div>
                        </div>
                      </div>
                     
                            
                    </div>
                  </div>
              </div>
            
              <!--FIN ACORDEON HORA-->
          </div>
        </div>
      </div>
        <!--FIN PRIVACIDAD-->
  </div> 
</div>
</section>



    <div class="container py-2">
         <div class="row">
          <div class="col-12">
            <div class="accordion mb-2" id="Seleccionar_descuento">
                 <div class="card card-accordion">
                    <h1 class="a-title-empleo afiliados text-center">Presuntas Frecuentes</h1>
                    <div id="seleccionar_descuento" class=" " aria-labelledby="headingOne" data-parent="#seleccionar_descuento">
                      <div class="form-group">
                        <!---- Acá va el acordeon de preguntas generales ---->
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#puntoencuentro" aria-expanded="false" aria-controls="collapseOne">
                                  ¿Cual es el punto de encuentro de la actividad? 
                                  </a>
                                </h5>
                             </div>
                            <div id="puntoencuentro" class="collapse " aria-labelledby="headingOne" data-parent="#puntoencuentro">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p>El punto de encuentro está detallado en cada actividad así como en el email de confirmación una vez realizada la reserva. Si tienes dudas una vez en el destino, puedes contactar con el proveedor local en el teléfono que verás en el bono.</p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#otrasciudades" aria-expanded="false" aria-controls="collapseOne">
                                  ¿Vamos a viajar a otras ciudades?¿Donde más tienen tours? 
                                  </a>
                                </h5>
                             </div>
                            <div id="otrasciudades" class="collapse " aria-labelledby="headingOne" data-parent="#otrasciudades">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p>Podrán ver todas nuestras guías de viajes en el home de nuestra página, en la mayoría de ellas, tenemos servicios de visitas guiadas, excursiones y traslados.</p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#otrostours" aria-expanded="false" aria-controls="collapseOne">
                                  ¿Tienen otros tours o actividades además de los publicados en cada destino? 
                                  </a>
                                </h5>
                             </div>
                            <div id="otrostours" class="collapse " aria-labelledby="headingOne" data-parent="#otrostours">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p>En algunos destinos ofrecemos servicios privados para actividades no publicadas en la web. En todo caso, puedes ver todos los servicios con los que contamos en Nuestra página.</p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#bono" aria-expanded="false" aria-controls="collapseOne">
                                  ¿Es necesario imprimir un bono o un justificante? 
                                  </a>
                                </h5>
                             </div>
                            <div id="bono" class="collapse " aria-labelledby="headingOne" data-parent="#bono">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p>Algunos servicios requieren un bono impreso, otros en el móvil, y otros no requieren llevar nada. Ésto es específico de cada actividad y lo podréis ver en su ficha.</p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#sillasniños" aria-expanded="false" aria-controls="collapseOne">
                                  En las excursiones en vehículo ¿Hay sillas para niños pequeños? 
                                  </a>
                                </h5>
                             </div>
                            <div id="sillasniños" class="collapse " aria-labelledby="headingOne" data-parent="#sillasniños">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p>Los proveedores disponen de sillitas de niño en función de las leyes del país (en el 90% de los destinos son obligatorias). Al hacer la reserva no olvidéis indicar la edad de los niños en los comentarios.</p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#itinerario" aria-expanded="false" aria-controls="collapseOne">
                                  ¿Se accede a todos los lugares indicados en el itinerario? 
                                  </a>
                                </h5>
                             </div>
                            <div id="itinerario" class="collapse " aria-labelledby="headingOne" data-parent="#itinerario">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p>Siempre tratamos de dejar claro si a un monumento se accede o solo se pasa por él. En caso de que no indiquemos nada, lo normal es que no se acceda a su interior.</p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#tarde" aria-expanded="false" aria-controls="collapseOne">
                                  No sé si me dará el tiempo para llegar al tour ¿Si llego tarde me esperarán? 
                                  </a>
                                </h5>
                             </div>
                            <div id="tarde" class="collapse " aria-labelledby="headingOne" data-parent="#tarde">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p>Los tours regulares salen siempre puntuales y no es posible modificar la hora de inicio. Respecto a los tours privados, si queréis empezar a otra hora podéis consultarnos antes de hacer la reserva.</p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#otrapersona" aria-expanded="false" aria-controls="collapseOne">
                                  ¿Puedo reservar para otra persona? 
                                  </a>
                                </h5>
                             </div>
                            <div id="otrapersona" class="collapse " aria-labelledby="headingOne" data-parent="#otrapersona">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p>¡Claro! Simplemente debes indicar los datos de la persona que realizará la actividad en lugar de los tuyos. En la información de pago sí que deberás indicar tus datos para que la transacción se complete correctamente.</p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#pago" aria-expanded="false" aria-controls="collapseOne">
                                  ¿Como puedo pagar mis reservas? 
                                  </a>
                                </h5>
                             </div>
                            <div id="pago" class="collapse " aria-labelledby="headingOne" data-parent="#pago">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p>Nuestra web ofrece un servicio de reserva muy fácil e intuitivo y te permite realizar el pago de manera segura con Visa, MasterCard o PayPal. No es posible pagar en el destino o al guía directamente. Todas las reservas deben hacerse a través de la página web.</p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#pago2" aria-expanded="false" aria-controls="collapseOne">
                                  ¿El pago es seguro? 
                                  </a>
                                </h5>
                             </div>
                            <div id="pago2" class="collapse " aria-labelledby="headingOne" data-parent="#pago2">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p>En METELEBRASIL disponemos de un sistema de pago online 100% seguro y encriptado contra fraudes y transacciones no autorizadas, estando certificados como comercio electrónico Trustwave Trustkeeper.</p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#pago3" aria-expanded="false" aria-controls="collapseOne">
                                  Creo que he realizado el pago y no me ha llegado la confirmación
                                  </a>
                                </h5>
                             </div>
                            <div id="pago3" class="collapse " aria-labelledby="headingOne" data-parent="#pago3">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p>Muy probablemente el pago no llegó a completarse y por lo tanto el servicio no está confirmado, deberías volver a hacer la reserva y finalizar el proceso. Si compruebas que te han cargado el importe en tu banco / paypal, posiblemente hayas puesto mal el email. En este caso, escríbenos y te ayudaremos a solucionarlo. </p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#pago4" aria-expanded="false" aria-controls="collapseOne">
                                  ¿Es posible hacer la reserva y pagar todo en destino?
                                  </a>
                                </h5>
                             </div>
                            <div id="pago4" class="collapse " aria-labelledby="headingOne" data-parent="#pago4">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p>En la actualidad es necesario abonar el importe total en el momento de hacer la reserva. Es posible pagar con tarjeta de crédito (excepto American Express), tarjeta de débito o mediante cuenta de PayPal. </p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#pago5" aria-expanded="false" aria-controls="collapseOne">
                                  ¿Cuanto tiempo tengo que esperar para recibir el email de confirmación?
                                  </a>
                                </h5>
                             </div>
                            <div id="pago5" class="collapse " aria-labelledby="headingOne" data-parent="#pago5">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p>El correo de confirmación es inmediato una vez realizado el pago. Si no te llega hay dos posibles motivos:<br>
                                    Que no se haya completado correctamente el pago. Comprueba tu cuenta bancaria o Paypal. <br>Que no hayas escrito correctamente tu email. Escríbenos un email y te ayudaremos a solucionarlo lo antes posible. </p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#opiniones1" aria-expanded="false" aria-controls="collapseOne">
                                  Las opiniones son demasiado buenas ¿Son reales?
                                  </a>
                                </h5>
                             </div>
                            <div id="opiniones1" class="collapse " aria-labelledby="headingOne" data-parent="#opiniones1">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p>Todas las opiniones son de personas que han reservado a través de Civitatis. Al acabar la actividad recibiréis un email para que podáis publicar vuestra valoración de la que esperemos haya sido una fantástica experiencia.</p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#opiniones2" aria-expanded="false" aria-controls="collapseOne">
                                  ¿Cómo puedo dejar mi opinión sobre una actividad?
                                  </a>
                                </h5>
                             </div>
                            <div id="opiniones2" class="collapse " aria-labelledby="headingOne" data-parent="#opiniones2">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p>Nuestra razón de ser es su satisfacción. Recibirás automáticamente una encuesta por email al finalizar cada actividad reservada. ¡Ayúdanos a mejorar!</p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#funcionarios" aria-expanded="false" aria-controls="collapseOne">
                                  ¿Tienen descuentos para un rubro específico o funcionarios públicos?
                                  </a>
                                </h5>
                             </div>
                            <div id="funcionarios" class="collapse " aria-labelledby="headingOne" data-parent="#funcionarios">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p>Lo sentimos; los precios disponibles en cada actividad son los únicos que podemos ofrecer. Son los mismos para para todos los clientes. </p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#minusvalidos" aria-expanded="false" aria-controls="collapseOne">
                                  ¿Hacen descuentos para jubilados, niños o minusválidos?
                                  </a>
                                </h5>
                             </div>
                            <div id="minusvalidos" class="collapse " aria-labelledby="headingOne" data-parent="#minusvalidos">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p>Solo se realizan estos descuentos si están indicados en la ficha de la actividad, es algo que depende de nuestros colaboradores locales.</p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#descuentos" aria-expanded="false" aria-controls="collapseOne">
                                  ¿Hay decuentos por contratar varias excursiones o servicios?
                                  </a>
                                </h5>
                             </div>
                            <div id="descuentos" class="collapse " aria-labelledby="headingOne" data-parent="#descuentos">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p>Tratamos de ofrecer el precio mínimo en cada actividad, por lo que no hacemos descuentos adicionales por reservar dos o más tours.</p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#cancelar" aria-expanded="false" aria-controls="collapseOne">
                                  ¿Las reservas se pueden cancelar?
                                  </a>
                                </h5>
                             </div>
                            <div id="cancelar" class="collapse " aria-labelledby="headingOne" data-parent="#cancelar">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p>En la ficha de cada actividad se indica su política de cancelación. Si la cancelación se realiza fuera de plazo y es por causas graves o ajenas, contactad con nosotros para que tratemos de solucionarlo.</p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#cancelar1" aria-expanded="false" aria-controls="collapseOne">
                                  ¿Cuál es la política de cancelación?
                                  </a>
                                </h5>
                             </div>
                            <div id="cancelar1" class="collapse " aria-labelledby="headingOne" data-parent="#cancelar1">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p>Siempre tratamos de maximizar el periodo de cancelación sin gastos. No obstante, cada actividad y cada destino es diferente por lo que tendrás que visitar el apartado "cancelaciones" de cada actividad para ver las condiciones específicas de cancelación.</p>
                                  <p>En caso de tener derecho a cancelación, se devolverá el importe mediante la misma forma de pago en la que se realizó la reserva. Es una operación automática que por seguridad no es posible modificar. </p>
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#lluvia" aria-expanded="false" aria-controls="collapseOne">
                                  ¿Qué sucede si llueve o hace mal tiempo?
                                  </a>
                                </h5>
                             </div>
                            <div id="lluvia" class="collapse " aria-labelledby="headingOne" data-parent="#lluvia">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p>Todas las actividades se realizan con normalidad a lo largo de todo el año independientemente de la lluvia. Si por condiciones extremas (por ejemplo, una fuerte nevada) el proveedor tuviera que cancelar un tour, se ofrecería una fecha alternativa o el reintegro inmediato del pago.</p>
                                  
                              </div>
                            </div>
                            </div>
                        </div>
                        <div class="accordion mb-2" id="Seleccionar_descuento">
                            <div class="card card-accordion">
                              <div class="" id="headingOne">
                                <h5 class="mb-0">
                                  <a class="btn btn-accordion btn-calendar text-white" href="#" data-toggle="collapse" data-target="#cancelar2" aria-expanded="false" aria-controls="collapseOne">
                                  ¿Puedo cambiar la forma de pago en caso de cancelación?
                                  </a>
                                </h5>
                             </div>
                            <div id="cancelar2" class="collapse " aria-labelledby="headingOne" data-parent="#cancelar2">
                              <div class="form-group" style="padding-top: 20px;">
                                  <p>En caso de tener derecho a cancelación, se devolverá el importe mediante la misma forma de pago en la que se realizó la reserva. Es una operación automática que por seguridad no es posible modificar.</p>
                                  
                              </div>
                            </div>
                            </div>
                        </div>
                      </div>
                     
                            
                    </div>
                  </div>
              </div>
                              </div>
                          </div>
</div>
        

  
 <!-- Footer -->
<?php include "footer.php"; ?>

  
  
  <!-- MODAL BUSCAR MOVIL-->
<div class="modal fade" id="modalbuscar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="form-buscar">
            <label class="sr-only" for="s">¿Dónde vamos?</label>
          <div class="input-group ">
            <input class="field form-control" id="buscar" name="buscar" type="text" placeholder="¿Dónde vamos?" value="">
            <span class="input-group-append">
              <button class="submit btn btn-primary" id="searchsubmit2" name="submit" type="submit">Buscar <i class="fa fa-arrow-right"></i></button>
            </span>
          </div>
              <!--EMPIEZA DESPLEGABLE DEL BANNER--> 
              <div class="form-group">
               <div class="container">
                <div class="row">
                  <div class="col-lg-12">
                     <div class="top-destinos-movil" >
                         <div class="container">
                        <div class="row mb-4">
                          <div class="col-lg-12">
                            <h3 class="text-center text-primary">Top Destinos</h3>
                          </div>
                        </div>
                        <div class="row  mb-4">
                            
                            <!--EL BUCLE DE LOS RESULTADOS DEBE IR ACA-->   
                            <div class="col-md-3 col-6 mb-3">
                            <h4 class=" mb-0"><a href="#" class="text-destinos">Nueva York</a></h4>
                            <small>Estados Unidos</small>
                            </div>
                            <!--FIN BUCLE DE LOS RESULTADOS DEBE IR ACA-->   
                  
                         </div>
                         <div class="row py-4">
                          <div class="col-lg-12">
                            <h3 class="text-center"><a href="" class="btn btn-outline-primary btn-white" style="border-radius:25px;">Ver todos los destinos</a></h3>
                          </div>
                        </div>
                        </div>
                     </div>
                  </div>
                </div>
                </div>
              </div>
               <!--EMPIEZA DESPLEGABLE DEL BANNER-->  
              
            </form>
      </div>
    </div>
  </div>
</div>
<!-- FIN MODAL BUSCAR MOVIL-->



 <!-- SCRIPTS NECESARIOS-->

  
  <!-- WOW ANIMACION -->
  <script src="js/wow.min.js"></script>
  <!-- WOW ANIMACION -->
  

  
  <!-- BOOTSTRAP BUNDLE -->
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- BOOTSTRAP BUNDLE -->
  
  <!-- JQUERY EASING -->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
  <!-- JQUERY EASING -->

  <!-- CUSTOM -->
  <script src="js/script.js"></script>
  <!-- CUSTOM -->
  
  <script type="text/javascript" src="js/rAF.js"></script>
  <script type="text/javascript" src="js/ResizeSensor.js"></script>
  <script type="text/javascript" src="js/sticky-sidebar.js"></script>
  <script type="text/javascript">

    var stickySidebar = new StickySidebar('#sidebar', {
      topSpacing: 90,
      bottomSpacing: 100,
      containerSelector: '.container_r',
      innerWrapperSelector: '.sidebar__inner'
    });
</script>
</body>
</html>