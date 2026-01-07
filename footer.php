<!----- Modal servicios ---------->





<div class="modal fade" id="modalservicios" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document" >
    <div class="modal-content" style="border-radius:0!important;">
    <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true" class="fa fa-arrow-left float-left text-white"></span>
        </button>
        <h5 class="text-white" style="position:absolute;margint-left:0;margin-right:0; left:70px;"><?=$lang["ingresa_tu_email_para_recibir_novedades"]?></h5>
        </div>
<!---- Cuerpo del modal -------->
         <div class="modal-body" style="">
            <div class="container">
            <div class="row">
              <div class="col-md-12">
                
                


                <div class="col-lg-12 py-2 d-md-block d-none">
              <h5 class="text-uppercase mb-4"><?=$lang["recibe_las_ultimas_noticias_de_este_evento"];?></h5>
              <form class="form-buscar">
                <div class="input-group">
                <input class="field form-control"  name="buscar" type="text" placeholder="Escribe tu mail" value="">
                <span class="input-group-append">
                <button class="submit btn btn-primary" id="searchsubmit" name="submit" type="submit"><i class="fa fa-arrow-right"></i></button>
                </span>
                </div>
              </form>
            </div>

              </div>
            </div>
          </div>
        </div>

  </div>
</div>
</div>

<!---- Fin de modal servicios ---------->

<footer class="footer ">
    <div class="container d-md-none mb-4">
      <div class="row">
         <div class="col-lg-3 py-2">
          <h5 class="text-uppercase mb-4 text-m-footer"><?=$lang["recibe_las_ultimas_ofertas"];?></h5>
          <form class="form-buscar">
                  <div class="input-group">
                <input class="field form-control"  name="buscar" type="text" placeholder="<?=$lang['escribe_tu_mail'];?>" value="">
                <span class="input-group-append">
                  <button class="submit btn btn-primary" id="searchsubmit" name="submit" type="submit"><i class="fa fa-arrow-right"></i></button>
                </span>
              </div>
            </form>
        </div>
      </div>
    </div>
  <!--ACORDEON FOOTER-->
<div class="container d-md-none">
   <div class="row mb-2 ">
   <div class="col-12 no-padding">
    <!--acordeon #1-->
    <div class="accordion" >
                 <div class="card card-accordion">
                    <div class="" id="headingOneMetelebrasil">
                      <h5 class="mb-0">
                        <button class="btn btn-accordion btn-footer"  data-toggle="collapse" data-target="#metele" aria-expanded="true" aria-controls="collapseOne" style="border-bottom: 2px solid;">
                          Metele Brasil <i class="fa fa-sort-down float-right"></i>
                        </button>
                      </h5>
                    </div>

                    <div id="metele" class="collapse div-btn-footer py-3" aria-labelledby="headingOne" data-parent="#headingOneMetelebrasil">
                     <div class="container">
                      <ul class="footer-ul mb-0">
                  <li><a href="acercaDe"><?=$lang["quienes_somos"]?></a></li>
                  <li><a href="categorias"><?=$lang["servicios"]?></a></li>
                  <li><a href="guias"><?=$lang["guia_de_viajes"]?></a></li>
                   <li><a href="sostenibilidad"><?=$lang["sostenibilidad"]?></a></li>
                  <!--<li><a href="#">METELEBRASIL Magazine</a></li>
                 
                  <li><a href="#">Prensa</a></li>-->
                </ul> 
                     </div>
                    </div>
                  </div>
           </div>
    <!--acordeon #1-->
    <!--acordeon #2-->
    <div class="accordion">
                 <div class="card card-accordion">
                    <div class="" id="headingOneSoporte">
                      <h5 class="mb-0">
                        <button class="btn btn-accordion btn-footer" id="btn-cancelaciones" href="#" data-toggle="collapse" data-target="#soporte" aria-expanded="true" aria-controls="collapseOne" style="border-bottom: 2px solid;"><?=$lang["soporte"]?><i class="fa fa-sort-down float-right"></i>
                        </button>
                      </h5>
                    </div>

                    <div id="soporte" class="collapse div-btn-footer py-3" aria-labelledby="headingOne" data-parent="#headingOneSoporte">
                     <div class="container">
                      <ul class="footer-ul mb-0">
                    <li><a href="faq"><?=$lang["preguntas_frecuentes"]?></a></li>
                          <li><a href="contact"><?=$lang["contacto"]?></a></li>
                </ul> 
                     </div>
                    </div>
                  </div>
           </div>
    <!--acordeon #2-->
    <!--acordeon #3-->
    <div class="accordion">
                 <div class="card card-accordion">
                    <div class="" id="headingOnePolitica-de-uso">
                      <h5 class="mb-0">
                        <button class="btn btn-accordion btn-footer" id="btn-cancelaciones" href="#" data-toggle="collapse" data-target="#politica" aria-expanded="true" aria-controls="collapseOne" style="border-bottom: 2px solid;"><?=$lang["politica_de_uso"]?><i class="fa fa-sort-down float-right"></i>
                        </button>
                      </h5>
                    </div>

                    <div id="politica" class="collapse div-btn-footer py-3" aria-labelledby="headingOne" data-parent="#headingOnePolitica-de-uso">
                     <div class="container">
                      <ul class="footer-ul mb-0">
                                 <!-- Aqui condiciones generales fueron escondidos porque necesita ser hecho en ES PT EN con un dead line 25/6/2021
                                  <li><a href="condiciones"><?=$lang["condiciones_generales"]?></a></li> -->

                    <li><a href="aviso"><?=$lang["aviso_legal"]?></a></li>
                    <li><a href="privacy"><?=$lang["politica_de_privacidad"]?></a></li>
                    <li><a href="cookies"><?=$lang["cookies"]?></a></li>
                </ul> 
                     </div>
                    </div>
                  </div>
           </div>
    <!--acordeon #3-->
    <!--acordeon #4-->
    <div class="accordion" >
                 <div class="card card-accordion">
                    <div class="" id="headingOneTrabajo">
                      <h5 class="mb-0">
                        <button class="btn btn-accordion btn-footer" id="btn-cancelaciones" href="#" data-toggle="collapse" data-target="#trabajo" aria-expanded="true" aria-controls="collapseOne" style="border-bottom: 2px solid;"><?=$lang["trabaja_con_nosotros"]?><i class="fa fa-sort-down float-right"></i>
                        </button>
                      </h5>
                    </div>

                    <div id="trabajo" class="collapse div-btn-footer py-3" aria-labelledby="headingOne" data-parent="#headingOneTrabajo">
                     <div class="container">
                      <ul class="footer-ul mb-0">
                        <li><a href="prestadores"><?=$lang["prestadores_de_servicio"]?></a></li>
                        <li><a href="freelancers"><?=$lang["venta_freelance"]?></a></li>
                        <li><a href="agencias"><?=$lang["agencias_de_viajes"]?></a></li>
                        <li><a href="operadoresMaioristas"><?=$lang["operadores_mayoristas"]?></a></li>
                   
                </ul> 
                     </div>
                    </div>
                  </div>
           </div>
    <!--acordeon #4-->
    <!--acordeon #5-->
    <!-- <div class="accordion">
                 <div class="card card-accordion">
                    <div class="" id="headingOne">
                      <h5 class="mb-0">
                        <a class="btn btn-accordion btn-footer" id="btn-cancelaciones" href="#" data-toggle="collapse" data-target="#Idioma-fter" aria-expanded="true" aria-controls="collapseOne" style="border-bottom: 2px solid;">
                          Idioma <i class="fa fa-sort-down float-right"></i>
                        </a>
                      </h5>
                    </div>

                    <div id="Idioma-fter" class="collapse div-btn-footer py-3" aria-labelledby="headingOne" data-parent="#idioma-f-ter">
                     <div class="container">
                      <ul class="footer-ul mb-0">
                        <li><a href="#">Ingles</a></li>
                    <li><a href="#">Italiano</a></li>
                    <li><a href="#">Portugues</a></li>
                    <li><a href="#">Frances</a></li>
                </ul> 
                     </div>
                    </div>
                  </div>
           </div> -->
    <!--acordeon #5-->
     <!--ACORDEON DE MONEDAS acordeon #5-->
    <div class="accordion" >
                 <div class="card card-accordion">
                    <div class="" id="headingOneMonedaFooter">
                      <h5 class="mb-0">
                        <button class="btn btn-accordion btn-footer" id="btn-cancelaciones" href="#" data-toggle="collapse" data-target="#Moneda-fter" aria-expanded="true" aria-controls="collapseOne" style="border-bottom: 2px solid;">
                          <?=$lang["moneda"]?><i class="fa fa-sort-down float-right"></i>
                        </button>
                      </h5>
                    </div>

                    <div id="Moneda-fter" class="collapse div-btn-footer py-3" aria-labelledby="headingOne" data-parent="#headingOneMonedaFooter">
                     <div class="container">
                      <ul class="footer-ul mb-0">
                        <li><a href="#" onClick="tipoMoneda(188)" ><i class="fa fa-dollar-sign"></i> Dolar Americano</a></li>
                    <li><a href=""  onClick="tipoMoneda(213)"><i class="fa fa-euro-sign"></i> Euro</a></li>
                    <li><a href="" onClick="tipoMoneda(225)"><i>PYS/</i> Guaranies</a></li>
                    <li> <a href=""  onClick="tipoMoneda(270)"><i>ARS</i> Peso Argentino</a></li>
                    <li> <a href=""  onClick="tipoMoneda(271)"><i>CL</i><i class="fa fa-dollar-sign"></i> Peso Chileno</a></li>
                    <li> <a href=""  onClick="tipoMoneda(283)"><i>R</i><i class="fa fa-dollar-sign"></i> <?=$lang["real_brasileno"]?></a></li>
                </ul> 
                     </div>
                    </div>
                  </div>
           </div>
    <!--acordeon #5-->
      </div>
      <!-- Botón Mi Cuenta Mobile -->
      <div class="row mt-3 mb-3">
        <div class="col-12">
          <button class="btn btn-primary btn-block" data-toggle="modal" data-target="#modalLoginForm">
            <i class="fa fa-user mr-2"></i><?=$lang["mi_cuenta"]?>
          </button>
        </div>
      </div>
 </div>
</div>
  <!-- FIN ACORDEON FOOTER-->
  <div class="container">
      <div class="row mb-2 ">

        <!-- Footer Location -->
        <div class="col-lg-3 mb-5 mb-lg-0 d-md-block d-none ">
          <h4 class="text-uppercase mb-4"> METELE BRASIL</h4>
          <ul class="footer-ul">
            <li><a href="acercaDe"><?=$lang["quienes_somos"]?></a></li>
            <li><a href="categorias"><?=$lang["destinos"]?></a></li>
            <li><a href="guias"><?=$lang["guia_de_viajes"]?></a></li>
            <!--<li><a href="#">METELEBRASIL Magazine</a></li>-->
            <li><a href="sostenibilidad"><?=$lang["sostenibilidad"]?></a></li>
           <!-- <li><a href="#">Prensa</a></li>-->
          </ul>
        </div>

        <!-- Footer Social Icons -->
        <div class="col-lg-3 mb-5 mb-lg-0 d-md-block d-none">
          <h4 class="text-uppercase mb-4"><?=$lang["soporte"]?></h4>
          <ul class="footer-ul">
            <li><a href="faq"><?=$lang["preguntas_frecuentes"]?></a></li>
            <li><a href="contact"><?=$lang["contacto"]?></a></li>
          </ul>
        </div>

        <!-- Footer About Text -->
        <div class="col-lg-3 d-md-block d-none">
          <h4 class="text-up percase mb-4"><?=$lang["politica_de_uso_"]?></h4>
          <ul class="footer-ul">

             <!-- Aqui condiciones generales fueron escondidos porque necesita ser hecho en ES PT EN con un dead line 25/6/2021
                                  <li><a href="condiciones"><?=$lang["condiciones_generales"]?></a></li> -->


            <li><a href="aviso"><?=$lang["aviso_legal"]?></a></li>
            <li><a href="privacy"><?=$lang["politica_de_privacidad"]?></a></li>
            <li><a href="cookies"><?=$lang["cookies"]?></a></li>
          </ul>
        </div>

        <div class="col-lg-3 d-md-block d-none">
          <h4 class="text-uppercase mb-4"><?=$lang["trabaja_con_nosotros"]?></h4>
          <ul class="footer-ul">
            <li><a href="prestadores"><?=$lang["prestadores_de_servicio"]?></a></li>
            <li><a href="freelancers"><?=$lang["venta_freelance"]?></a></li>
            <li><a href="agencias"><?=$lang["agencias_de_viajes"]?></a></li>
            <li><a href="operadoresMaioristas"><?=$lang["operadores_mayoristas"]?></a></li>
            
            <!--<li><a href="#">Empleo</a></li>-->
          </ul>
        </div>

      </div>
<!--app
      
      <div class="row">
        <div class="col-lg-3 py-2">
          <h5 class="text-uppercase mb-4 text-m-footer">Cómo nos valoran</h5>
          <img src="img/ekomi-190x190.png" class="img-fluid" width="50">
        </div>
         <div class="col-lg-3 py-2">
          <h5 class="text-uppercase mb-4 text-m-footer">Descarga nuestra APP</h5>
          <img src="img/app-store-2_en.png" class="img-fluid mb-4" width="150">
          <img src="img/google-play-2_en.png" class="img-fluid mb-4" width="150">
        </div>
        <div class="col-12 d-md-none text-center">
           <a class="btn btn-outline-light btn-social mx-1" href="#">
            <i class="fab fa-fw fa-facebook-f"></i>
          </a>
          <a class="btn btn-outline-light btn-social mx-1" href="#">
            <i class="fab fa-fw fa-twitter"></i>
          </a>
          <a class="btn btn-outline-light btn-social mx-1" href="#">
            <i class="fab fa-fw fa-linkedin-in"></i>
          </a>
          <a class="btn btn-outline-light btn-social mx-1" href="#">
            <i class="fab fa-fw fa-dribbble"></i>
          </a>
        </div>
        <div class="col-lg-3 py-2 d-md-block d-none">
          <h5 class="text-uppercase mb-4">Recibe las últimas ofertas</h5>
          <form class="form-buscar">
                  <div class="input-group">
                <input class="field form-control" id="buscar" name="buscar" type="text" placeholder="Escribe tu mail" value="">
                <span class="input-group-append">
                  <button class="submit btn btn-primary" id="searchsubmit" name="submit" type="submit"><i class="fa f
              </div>a-arrow-right"></i></button>
                </span>
            </form>
        </div>
      </div>



      <hr class="hr">-->
      <div class="row">
        <div class="col-lg-4"></div>
        <div class="col-lg-2">
         <p class="text-gris text-center"> <i class="fa fa-lock mx-2 "></i><?=$lang["pago_seguro"]?></p>
        </div>
        <div class="col-lg-2 col-4">
          <img src="img/paypal-2.png" class="img-fluid img-foter">
        </div>
        <div class="col-lg-2 col-4">
          <img src="img/mastercard-2.png" class="img-fluid img-foter">
        </div>
        <div class="col-lg-2 col-4">
          <img src="img/visa-2.png" class="img-fluid img-foter">
        </div>
      </div>
      <!-- Botón Mi Cuenta Desktop -->
      <div class="row mt-4 d-md-block d-none">
        <div class="col-lg-12">
          <button class="btn btn-primary" data-toggle="modal" data-target="#modalLoginForm">
            <i class="fa fa-user mr-2"></i><?=$lang["mi_cuenta"]?>
          </button>
        </div>
      </div>
    </div>
</footer>
  <!-- Copyright Section -->
  <section class="copyright py-4 text-center text-white">
    <div class="container">
      <div class="row">
        <div class="col-lg-8 col-md-8 col-12">
          <h4 class="text-left"><small><span> <img src="img/favicon.png" sizes="20x20" style=" width: 25px; height: 28px border: 5px", />  METELE BRASIL</span><?=$lang["es_una_marca_registrada_de_reservate_sl"]?></small></h4>
        </div>
        <div class="col-lg-4 col-md-4 col-12 d-md-block d-none">
           <a class="btn btn-outline-light btn-social mx-1" target="_blank" href="https://www.facebook.com/Metele-Brasil-2731641913727960/">
            <i class="fab fa-fw fa-facebook-f"></i>
          </a>
          <!--<a class="btn btn-outline-light btn-social mx-1" href="#">
            <i class="fab fa-fw fa-twitter"></i>
          </a>
          <a class="btn btn-outline-light btn-social mx-1" href="#">
            <i class="fab fa-fw fa-linkedin-in"></i>
          </a>-->
          <a class="btn btn-outline-light btn-social mx-1" target="_blank" href="https://www.instagram.com/metelebrasil/">
            <i class="fab fa-fw fa-instagram"></i>
          </a>
        </div>
      </div>
    </div>
  </section>

</body>
</html>

