<?php 
if (isset($_GET["idReserva"])) {
  $idReserva=$_GET["idReserva"];
}
//print_r($_GET["external_reference"]);
 ?>
<!DOCTYPE html>
<html lang="en">

<head>

   <title>MeteleBrasil.com</title>
  
    <meta name="title" content="MeteleBrasil.com" />
    <meta name="description" content="Actividades, traslados, entradas, visitas guiadas y excursiones en español en todo el mundo. Reserva online con precio mínimo garantizado." />
    <meta name="keywords" content="excursiones, visitas guiadas, tours, actividades, traslados, transfers, circuitos, guias turísticas, guias de viaje" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
     <link rel="icon" href="img/favicon.png" sizes="32x32">
   
  <!-- ESTILOS NECESARIOS -->
    
   <!-- FONT-AWESOME -->
   <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
   <!-- FONT-AWESOME -->
   
   <!-- ANIMATE -->
   <link rel="stylesheet" href="css/animate.min.css">
   <!-- ANIMATE -->
   
   <!-- BOOTSTRAP V4-->
   <link href="css/bootstrap.css" rel="stylesheet">
   <!-- BOOTSTRAP V4 -->
   
   <!-- STYLES GENERALES -->
   <link href="css/styles.css" rel="stylesheet">
   <!-- STYLES GENERALES -->
   
   <!-- RESPONSIVE DESING-->
   <link href="css/responsive.css" rel="stylesheet">
   <!-- RESPONSIVE DESING -->
   
   <!-- ESTILOS CALENDARIO-->
   <link href="css/clnr.css" rel="stylesheet">
   <!-- ESTILOS CALENDARIO-->
   
   <!-- FUENTES-->
   <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,600,700" rel="stylesheet">
   <!-- FUENTES-->
   
  <!-- ESTILOS NECESARIOS -->

</head>

<body id="page-top">

<!--HEADER PAGO SEGURO-->

<section class="py-2 bg-primary">
  <div class="container">
    <div class="row">
      <div class="col-lg-2 col-6">
         <a class="navbar-brand text-white" href="#page-top"><h3>METELEBRASIL</h3></a>
      </div>
      <div class="col-lg-10 col-6">
        <p class="text-white text-pagos mb-0"> <i class="fa fa-lock mx-2 "></i> PAGO SEGURO</p>
      </div>
    </div>
  </div>
</section>

<!--FIN HEADER PAGO SEGURO-->

<!--PASOS PARA RESERVA-->
<section class="py-2 bg-white">
  <div class="container">
    <div class="row">
      <div class="col-lg-12  text-center ">
        <ul class="lista-pasos-form">
        
        </ul>
      </div>
    </div>
  </div>
</section>
<!--FIN PASOS PARA RESERVA-->


<!--SECCION RETORNO DE PAGO-->
<section>
  <div class="container">
    <div class="row">

      <!--CONTENEDOR RETORNO DE PAGO-->
      <div class="col-lg-8 offset-lg-2">
        <div class=" py-3">
             <div class="card card-visitas">
              <div class="card-body">
                <h5 class="mb-4">Reservacion</h5>
                  <li class="active-success"><span><i class="fa fa-check"></i></span> <strong>Pago de la reserva nro<?=$_GET["external_reference"];?> aceptado correctamente!!</strong></li>
              </div>
            </div>
          </div>
      </div>
      <!--FIN CONTENEDOR RETORNO DE PAGO-->
    </div>

  </div>

<!--BOTON SIGUIENTE-->
    <div class="container py-4">
      <div class="row">
        <div class="col-lg-8 col-md-8"></div>
        <div class="col-lg-4 col-md-4 col-12 text-right">
          <a href="index.php" class="btn btn-primary btn-lg btn-radius" style="width: 100% !important;">Regresar</a>
        </div>
      </div>
    </div>
<!--FIN BOTON SIGUIENTE-->

</section>
<!--FIN RETORNO DE PAGO-->



  <!-- Footer -->
  <?php include "footer.php"; ?>

  



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
  
  <!-- CALENDARIO -->
  <script src="js/clndr.min.js"></script>
  <!-- CALENDARIO -->
  
  <!-- BOOTSTRAP BUNDLE -->
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- BOOTSTRAP BUNDLE -->
  
  <!-- JQUERY EASING -->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
  <!-- JQUERY EASING -->

  <!-- CUSTOM -->
  <script src="js/script.js"></script>
  <!-- CUSTOM -->
  
<!-- FIN SCRIPTS NECESARIOS-->

</body>

</html>
