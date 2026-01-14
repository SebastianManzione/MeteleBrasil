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

$codigoAmigable=$_GET['reserva'];

if ($_SERVER["REQUEST_METHOD"]=="GET" && isset($_GET["pais"]) && isset($_GET["reserva"])) {
  $_SESSION['geo']['countryCode']=$_GET['pais'];

redireccionar('consultaReserva.php?reserva='.$codigoAmigable);
}



?>

<br>
<br>

<h1 class="text-center">Pague com a moeda de seu país</h1>

<br>
<br>

<!--PASOS PARA RESERVA-->


<!--FIN PASOS PARA RESERVA-->
<form method="post">
 <div class="row ">

<div class="col-lg-4 col-md-6 col-12 mb-4">

         <a href="monedasPago?reserva=<?=$codigoAmigable?>&pais=AR" class="imagen">
             
           <div class="img-c" style="background-image: url(img/countries/argentina.png)">
              
             <!-- EMPIEZA LA INFO AL PASAR EL HOVER -->   
             <div class="info d-md-block d-none">
             <h3 class="headline text-uppercase semibold">Argentina</h3>
               <div class="container">
                 <div class="row">
                   <div class="col-md-6">
                   <div class="descripcion text-white">
                     <p class=" mb-0">
                        <strong style="font-size:30px;"></strong> 
                     </p>
                      <p class="mb-0 p"></p>
                   </div>
                   </div>
                    <div class="col-md-6">
                    <div class="descrip-opinion text-white">
                    <p class=" mb-0">
                       <strong style="font-size:30px;"></strong> 
                    </p>
                    <p class="mb-0 p">
                      
                    </p>
                    </div>
                   </div>
                 </div>
               </div>
             </div>
             <!-- FIN LA INFO AL PASAR EL HOVER -->   
             
           </div>
            <h3 class="title-categoria text-uppercase texto-shadow text-white"></h3>

         </a>

       </div>


<div class="col-lg-4 col-md-6 col-12 mb-4">

         <a href="monedasPago?reserva=<?=$codigoAmigable?>&pais=UY" class="imagen">
             
           <div class="img-c" style="background-image: url(img/countries/uruguay.png)">
              
             <!-- EMPIEZA LA INFO AL PASAR EL HOVER -->   
             <div class="info d-md-block d-none">
             <h3 class="headline text-uppercase semibold">Uruguay</h3>
               <div class="container">
                 <div class="row">
                   <div class="col-md-6">
                   <div class="descripcion text-white">
                     <p class=" mb-0">
                        <strong style="font-size:30px;"></strong> 
                     </p>
                      <p class="mb-0 p"></p>
                   </div>
                   </div>
                    <div class="col-md-6">
                    <div class="descrip-opinion text-white">
                    <p class=" mb-0">
                       <strong style="font-size:30px;"></strong> 
                    </p>
                    <p class="mb-0 p">
                      
                    </p>
                    </div>
                   </div>
                 </div>
               </div>
             </div>
             <!-- FIN LA INFO AL PASAR EL HOVER -->   
             
           </div>
           
  <h3 class="title-categoria text-uppercase texto-shadow text-white"></h3>
         </a>

       </div>


<div class="col-lg-4 col-md-6 col-12 mb-4" style="display: none;">

         <a href="monedasPago?reserva=<?=$codigoAmigable?>&pais=PY" class="imagen">
             
           <div class="img-c" style="background-image: url(img/countries/paraguay.png)">
              
             <!-- EMPIEZA LA INFO AL PASAR EL HOVER -->   
             <div class="info d-md-block d-none">
             <h3 class="headline text-uppercase semibold">Paraguay</h3>
               <div class="container">
                 <div class="row">
                   <div class="col-md-6">
                   <div class="descripcion text-white">
                     <p class=" mb-0">
                        <strong style="font-size:30px;"></strong> 
                     </p>
                      <p class="mb-0 p"></p>
                   </div>
                   </div>
                    <div class="col-md-6">
                    <div class="descrip-opinion text-white">
                    <p class=" mb-0">
                       <strong style="font-size:30px;"></strong> 
                    </p>
                    <p class="mb-0 p">
                      
                    </p>
                    </div>
                   </div>
                 </div>
               </div>
             </div>
             <!-- FIN LA INFO AL PASAR EL HOVER -->   
             
           </div>
           
  <h3 class="title-categoria text-uppercase texto-shadow text-white"></h3>
         </a>

       </div>

<div class="col-lg-4 col-md-6 col-12 mb-4" >

         <a href="monedasPago?reserva=<?=$codigoAmigable?>&pais=BR" class="imagen">
             
           <div class="img-c" style="background-image: url(img/countries/brasil.png); ">
              
             <!-- EMPIEZA LA INFO AL PASAR EL HOVER -->   
             <div class="info d-md-block d-none">
             <h3 class="headline text-uppercase semibold">Brasil</h3>
               <div class="container">
                 <div class="row">
                   <div class="col-md-6">
                   <div class="descripcion text-white">
                     <p class=" mb-0">
                        <strong style="font-size:30px;"></strong> 
                     </p>
                      <p class="mb-0 p"></p>
                   </div>
                   </div>
                    <div class="col-md-6">
                    <div class="descrip-opinion text-white">
                    <p class=" mb-0">
                       <strong style="font-size:30px;"></strong> 
                    </p>
                    <p class="mb-0 p">
                      
                    </p>
                    </div>
                   </div>
                 </div>
               </div>
             </div>
             <!-- FIN LA INFO AL PASAR EL HOVER -->   
             
           </div>
           
  <h3 class="title-categoria text-uppercase texto-shadow text-white"></h3>
         </a>

       </div>






        </div>
</form>

<div class="row ">

<div class="col-lg-4 col-md-6 col-12 mb-4">

         <a href="monedasPago?reserva=<?=$codigoAmigable?>&pais=PE" class="imagen">
             
           <div class="img-c" style="background-image: url(img/countries/peru.png)">
              
             <!-- EMPIEZA LA INFO AL PASAR EL HOVER -->   
             <div class="info d-md-block d-none">
             <h3 class="headline text-uppercase semibold">Perú</h3>
               <div class="container">
                 <div class="row">
                   <div class="col-md-6">
                   <div class="descripcion text-white">
                     <p class=" mb-0">
                        <strong style="font-size:30px;"></strong> 
                     </p>
                      <p class="mb-0 p"></p>
                   </div>
                   </div>
                    <div class="col-md-6">
                    <div class="descrip-opinion text-white">
                    <p class=" mb-0">
                       <strong style="font-size:30px;"></strong> 
                    </p>
                    <p class="mb-0 p">
                      
                    </p>
                    </div>
                   </div>
                 </div>
               </div>
             </div>
             <!-- FIN LA INFO AL PASAR EL HOVER -->   
             
           </div>
           
  <h3 class="title-categoria text-uppercase texto-shadow text-white"></h3>
         </a>

       </div>


<div class="col-lg-4 col-md-6 col-12 mb-4">

         <a href="monedasPago?reserva=<?=$codigoAmigable?>&pais=USA" class="imagen">
             
           <div class="img-c" style="background-image: url(img/countries/estados_unidos.png)">
              
             <!-- EMPIEZA LA INFO AL PASAR EL HOVER -->   
             <div class="info d-md-block d-none">
             <h3 class="headline text-uppercase semibold">Estados Unidos</h3>
               <div class="container">
                 <div class="row">
                   <div class="col-md-6">
                   <div class="descripcion text-white">
                     <p class=" mb-0">
                        <strong style="font-size:30px;"></strong> 
                     </p>
                      <p class="mb-0 p"></p>
                   </div>
                   </div>
                    <div class="col-md-6">
                    <div class="descrip-opinion text-white">
                    <p class=" mb-0">
                       <strong style="font-size:30px;"></strong> 
                    </p>
                    <p class="mb-0 p">
                      
                    </p>
                    </div>
                   </div>
                 </div>
               </div>
             </div>
             <!-- FIN LA INFO AL PASAR EL HOVER -->   
             
           </div>
           
  <h3 class="title-categoria text-uppercase texto-shadow text-white"></h3>
         </a>

       </div>


<div class="col-lg-4 col-md-6 col-12 mb-4">

         <a href="monedasPago?reserva=<?=$codigoAmigable?>&pais=CL" class="imagen">
             
           <div class="img-c" style="background-image: url(img/countries/chile.png)">
              
             <!-- EMPIEZA LA INFO AL PASAR EL HOVER -->   
             <div class="info d-md-block d-none">
             <h3 class="headline text-uppercase semibold">Chile</h3>
               <div class="container">
                 <div class="row">
                   <div class="col-md-6">
                   <div class="descripcion text-white">
                     <p class=" mb-0">
                        <strong style="font-size:30px;"></strong> 
                     </p>
                      <p class="mb-0 p"></p>
                   </div>
                   </div>
                    <div class="col-md-6">
                    <div class="descrip-opinion text-white">
                    <p class=" mb-0">
                       <strong style="font-size:30px;"></strong> 
                    </p>
                    <p class="mb-0 p">
                      
                    </p>
                    </div>
                   </div>
                 </div>
               </div>
             </div>
             <!-- FIN LA INFO AL PASAR EL HOVER -->   
             
           </div>
           
  <div class="shadow-none p-3 mb-5 
            bg-light rounded"></div><h3 class="title-categoria text-uppercase texto-shadow text-white"></h3>
         </a>

       </div>






        </div>




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

  

  <!-- JQUERY EASING -->

  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- JQUERY EASING -->



  <!-- CUSTOM -->

  <script src="js/scriptcarrito.js"></script>

  <!-- CUSTOM -->

  

<!-- FIN SCRIPTS NECESARIOS-->

</div>

<?php
// Inyectar código del body desde configuración
if (!isset($parametros)) {
  require_once(__DIR__ . '/admin/classes/parametros.php');
  $parametros = getParametros();
}
if (isset($parametros[0]["body"]) && $parametros[0]["body"] !== '') {
  echo $parametros[0]["body"];
}
?>
</body>



</html>

