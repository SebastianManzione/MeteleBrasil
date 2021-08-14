
<?php 



include("includes/navbar.php"); 
require("admin/classes/opiniones_categoria.php");

require_once("admin/classes/salidas.php");

require("admin/classes/categoria.php");
require("admin/classes/servicio_opiniones.php");
require("admin/classes/texto_miniaturas.php"); 

  

/*
if (!isset($_SESSION["visitante"])) {

 $_SESSION["visitante"]=Array();
$visitante=array();

$visitante[0]=$_SESSION["realIP"];
$visitante[1]=$_SESSION["paisConexion"];
$visitante[2]="index.php";
Visitante($visitante);
}
*/
?>

<!-- header -->

<head>

                <section style="background-color:#2D8EFF;">

                    <div class="container">
<br>
<h1 class="text-white text-uppercase titulo" style='text-align:center'>
      
                                  <?=$lang["sumale_clientes_empresa"];?></h1>
                                  




          </div>


        <br>                  


</head>

 </section>


<body>

 <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
 
  
  <div class="carousel-inner">

     <div class="carousel-item active">

      <img class="img-fluid img-slider" src="img/slider4.jpg">



    </div>

<br><br><br><br><br>

<div class="col-lg text-center text-white div-bottom">
         
              <div class="row">
                <div class="col">
                 <h1><span class="blue">1</span></h1>
                  <h4 class="texto-bottom" style="margin-top: 20px;"><?=$lang["completa_empresa"];?></h4>
                </div>
                <div class="col">
                  <h1><span class="blue">2</span></h1>
                 <h4 class="texto-bottom" style="margin-top: 20px;"><?=$lang["activaremos_tu_cuenta_agencia"];?></h4>
                </div>
                <div class="col">
                  <h1><span class="blue">3</span></h1>
                  <h4 class="texto-bottom" style="margin-top: 20px;"><?=$lang["comienza_a_recibir_reservas"];?></h4>
                </div>

              </div>
              <br><br><br><br>

 <a class="btn btn-reservar-calendar btn-lg h-90" style="background-color:#FEFF00; " href="registroOperadores" ><b><?=$lang["activar_mis_servicios"];?></b></a>



          </div>

  </div>
  




    <section class="div-absolute">
      <div class="container"> 
        >
          <!--FIN FOOTER DEL BANNER-->
          
      

        </div>
    
  </section>
  
</div>
  <!-- FIN HEADER -->

 
<!--EMPIEZA SECCION PRINCIPALES DESTINOS -->
 
<!--EMPIEZA SECCION PRINCIPALES DESTINOS -->


<!--EMPIEZA SECCION Actividades destacadas-->


 <!--EMPIEZA SECCION Actividades destacadas-->

<br><br>
<h1 style='text-align:center'>
      
      <?=$lang["motivos_para_elegirnos"];?>
              
            </h1>
<br><br>
<div class="row">
         <div class="col-lg-12 text-center">
              <div class="container">
              <div class="row">
                <div class="col-sm-3">
                 <i class="fas fa-piggy-bank fa-3x  text-primary"></i><br>
                  <h5 class="texto"  style="margin-top: 20px;"><?=$lang["gratis_sin_costo"];?></h5>
                  <p class="texto"  style="margin-top: 20px;"><?=$lang["totalmente_gratis"];?></p>
                </div>
                <div class="col-sm-3">
                  <i class="fas fa-chalkboard-teacher fa-3x text-primary"></i>
                  <h5 class="texto"  style="margin-top: 20px;"><?=$lang["crm_facil_intuitivo"];?></h5>
                  <p class="texto"  style="margin-top: 20px;"><?=$lang["controla_reservas_prestador"];?></p>
                </div>
                <div class="col-sm-3">
                  <i class="fas fa-user-plus fa-3x text-primary"></i>
                  <h5 class="texto"  style="margin-top: 20px;"><?=$lang["sume_agencias_freelance"];?></h5>
                  <p class="texto"  style="margin-top: 20px;"><?=$lang["aumente_su_red_de_venta"];?></p>
                </div>
                <div class="col-sm-3">
                  <i class="fas fa-user-shield fa-3x text-primary"></i>
                  <h5 class="texto"  style="margin-top: 20px;"><?=$lang["valoramos_cada_prestador"];?></h5>
                   <p class="texto"  style="margin-top: 20px;"><?=$lang["acompanamos_cada_prestador"];?></p>
                </div>
              </div>



            </div>

          </div>



            </div>



<div class="row">
         <div class="col-lg-12 text-center">
              <div class="container">
              <div class="row">
                <div class="col-sm-3">
                 <i class="fa fa-calendar-check fa-3x  text-primary"></i><br>
                 
                  <h5 class="texto"  style="margin-top: 20px;"><?=$lang["disponibilidad_en_tiempo_real"];?></h5>
                  <p class="texto"  style="margin-top: 20px;"><?=$lang["acelere_sus_ventas"];?></p>
                </div>
                <div class="col-sm-3">
<i class="fas fa-street-view fa-3x text-primary"></i>
                  <h5 class="texto"  style="margin-top: 20px;"><?=$lang["soporte_humano"];?></h5>
                  <p class="texto"  style="margin-top: 20px;"><?=$lang["resolvemos_agil_rapido"];?></p>
                </div>
                <div class="col-sm-3">
                  <i class="fa fa-headset fa-3x text-primary"></i>
                  <h5 class="texto"  style="margin-top: 20px;"><?=$lang["comunicacion_eficaz"];?></h5>
                  <p class="texto"  style="margin-top: 20px;"><?=$lang["nos_comunicamos"];?></p>
                </div>
                <div class="col-sm-3">
                  <i class="far fa-list-alt fa-3x text-primary"></i>
                  <h5 class="texto"  style="margin-top: 20px;"><?=$lang["control_de_todas_reservas"];?></h5>
                  <p class="texto"  style="margin-top: 20px;"><?=$lang["tiene_la_posibilidad_administrar_servicio"];?></p>
                </div>
              </div>



            </div>

          </div>



            </div>


<div class="row">
         <div class="col-lg-12 text-center">
              <div class="container">
              <div class="row">
                <div class="col-sm-3">
                 <i class="fas fa-funnel-dollar fa-3x  text-primary"></i>
                  <h5 class="texto"  style="margin-top: 20px;"><?=$lang["recibi_con_un_click"];?></h5>
                  <p class="texto"  style="margin-top: 20px;"><?=$lang["reciba_sus_ventas"];?></p>
                </div>
                <div class="col-sm-3">
                  <i class="fa fa-hand-holding-usd fa-3x text-primary"></i>
                  <h5 class="texto"  style="margin-top: 20px;"><?=$lang["aceptamos_en_otras_monedas"];?></h5>
                  <p class="texto"  style="margin-top: 20px;"><?=$lang["cobramos_en_moneda_local"];?></p>
                </div>
                <div class="col-sm-3">
                  <i class="fas fa-people-arrows"></i>
                  <i class="fas fa-tachometer-alt fa-3x text-primary"></i>
                  <h5 class="texto"  style="margin-top: 20px;"><?=$lang["agiliza_tus_ventas"];?></h5>
                  <p class="texto"  style="margin-top: 20px;"><?=$lang["ofrece_en_tiempo_real"];?></p>
                </div>
                <div class="col-sm-3">
                  <i class="far fa-address-card fa-3x text-primary"></i>
                  <h5 class="texto"  style="margin-top: 20px;"><?=$lang["guia_de_turismo_personalizado"];?></h5>
                  <p class="texto"  style="margin-top: 20px;"><?=$lang["acompanamos_tus_Clientes"];?></p>
                </div>
                
              </div>



            </div>

          </div>



            </div>


<br><br>

</body>>

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
            <label class="sr-only" for="s"><?=$lang["donde_vamos"]?>?</label>
          <div class="input-group ">
            <input class="field form-control" id="buscar" name="buscar" type="text" placeholder="¿Dónde vamos?" value="">
            <span class="input-group-append">
              <button class="submit btn btn-primary" id="searchsubmit" name="submit" type="submit"><?=$lang["buscar"]?><i class="fa fa-arrow-right"></i></button>
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
                            <h3 class="text-center text-primary"><?=$lang["ver_todos_los_destinos"]?></h3>
                          </div>
                        </div>
                        <div class="row  mb-4">
                            
                            <!--EL BUCLE DE LOS RESULTADOS DEBE IR ACA-->   
                            <div class="col-md-3 col-6 mb-3">
                            <h4 class=" mb-0"><a href="#" class="text-destinos">Rio de Janeiro</a></h4>
                            <small>Salvador Bahia</small>
                            </div>
                            <!--FIN BUCLE DE LOS RESULTADOS DEBE IR ACA-->   
                  
                         </div>
                         <div class="row py-4">
                          <div class="col-lg-12">
                            <h3 class="text-center"><a href="" class="btn btn-outline-primary btn-white" style="border-radius:25px;"><?=$lang["ver_todos_los_destinos"]?></a></h3>
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


<!-- BOTON AYUDA-
   <a class="btn-ayuda d-md-block d-none" href="#">
  <div class="boton-ayuda">
   
      <i class="fa fa-question-circle"></i> Ayuda
  </div>
     </a>


  -- FIN BOTON AYUDA-->

  <!--AVISO DE COOKIES-->
  
  <?php if (!isset($_COOKIE['politicaCookies'])){?>
     <div class="cookies" id="cookies">
      
          <div class="container">
             <div class="row">
               <div class="col-8"><p class="text-white mb-0 text-justify cookies-sm " style="font-size:14px;"><?=$lang["utilizamos_cookies_propias"]?><a href="cookies.php"><?=$lang["politicas_de_cookies"]?></a> 
               </p> 
              </div>
              <div class="col-4"><a style="cursor: pointer;" id="cerrar-cookies"><p class="text-center"><input type="hidden" value="geo"><button type="button" class="btn btn-info btn-circle"><i class="fa fa-check"></i></button></p></a>
              </div>
            </div>
          </div>

</div>
  <?php } ?>
  

  
  <!--FIN AVISO DE COOKIES-->

  <!-- BOTON SUBIR
  <div class="scroll-to-top  position-fixed ">
    <a class="js-scroll-trigger d-block text-center text-white rounded" href="#page-top">
      <i class="fa fa-chevron-up"></i>
    </a>
  </div>
  FIN BOTON SUBIR-->


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
  <script src="js/wow.min.js?v=<?php echo $version?>"></script>
  <!-- WOW ANIMACION -->

  
  <!-- BOOTSTRAP BUNDLE -->
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js?v=<?php echo $version?>"></script>
  <!-- BOOTSTRAP BUNDLE -->
  
  <!-- JQUERY EASING -->
  <script src="vendor/jquery-easing/jquery.easing.min.js?v=<?php echo $version?>"></script>
  <!-- JQUERY EASING -->
<script type="text/javascript">
$(document).ready(function(){  
  var i = 1;
  $('#alternar-panel-oculto-1').click(function() {

  
      if(i==1){
        $('#alternar-panel-oculto-1').html('Ver menos');
        i = 0;
      }else{
        $('#alternar-panel-oculto-1').html('Ver más');
        i = 1;
      }                
});
});
</script>
<script type="text/javascript">
$(document).ready(function(){  
  var k = 1;
  $('#alternar-panel-oculto-2').click(function() {

  
      if(k==1){
        $('#alternar-panel-oculto-2').html('Ver menos');
        k = 0;
      }else{
        $('#alternar-panel-oculto-2').html('Ver más');
        k = 1;
      }                
});
});
</script>
 
  <!-- CUSTOM -->
  <script src="js/script.js?v=<?php echo $version?>"></script>
  <!-- CUSTOM -->
  
<!-- FIN SCRIPTS NECESARIOS-->

</body>

</html>
