  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="./js/jquery.redirect.js"></script>
  <?php 
include ("sistema/functions.php");
if (isset($_GET['idReserva'])) {
  if (is_numeric($_GET["idReserva"])) {
      $idReserva=$_GET["idReserva"];
  }

}
$totalReserva=0;

include($GLOBALS['path'].'/conectar.php');

        $query2=mysqli_query($conection,"SELECT * FROM reservas WHERE idReserva=".$idReserva);
        $result2=mysqli_num_rows($query2);
        $datos=Array();
        $i=0;


  if ($result2 > 0) {
      while ($data2 = mysqli_fetch_array($query2)) {
      $monedaNativa=$data2["monedaSel"];
$sym=DevuelveMoneda($monedaNativa)[3];
$deuda=devuelveDeudaReserva($idReserva, $monedaNativa);
$compPagos=DevuelveTotalComprobantesPagosReserva($idReserva, $monedaNativa);
    $i++;
    }
  }
else{
  echo "<h1>Error, la reserva no existe!!!!</h1>";
   //header('Location: index.php');
}



  $query2=mysqli_query($conection,"SELECT * FROM reserva_horarios WHERE idReserva=".$idReserva);
        $result2=mysqli_num_rows($query2);
        $datos=Array();
        $i=0;
  if ($result2 > 0) {
      while ($data2 = mysqli_fetch_array($query2)) {


   $servicios[$i]= Array();
  $servicios[$i][0]=$data2["servicioId"];
  $servicios[$i][1]=$data2["horarioId"];
  $servicios[$i][2]=$data2["idReservaHorario"];

  $servicios[$i][4]=$data2["precioAdulto"];
      $servicios[$i][5]=$data2["cantAdultos"];
      $servicios[$i][6]=$data2["cant12"];
      $servicios[$i][7]=$data2["precioMenor12"];
      $servicios[$i][8]=$data2["cant5"];
      $servicios[$i][9]=$data2["precioMenor5"];
       $servicios[$i][10]=$data2["total"];
 
      $servicios[$i][13]=$data2["iva"];

      $servicios[$i][14]=$data2["cant3"];
      $servicios[$i][15]=$data2["precioMenor3"];
       

        $totalReserva+= $servicios[$i][10];


$servicios[$i][11]=Array();

$consulta="SELECT * FROM reserva_adicionales RA INNER JOIN servicios_adicionales SA ON RA.idServiciosAdicionales=SA.idServiciosAdicionales WHERE RA.idReservaHorario =".$servicios[$i][2];

  $query3=mysqli_query($conection,$consulta);
        $result3=mysqli_num_rows($query3);
     
        $z=0;
  if ($result3 > 0) {
      while ($data3 = mysqli_fetch_array($query3)) {

$servicios[$i][11][$z]=Array();
$servicios[$i][11][$z][0]=$data3["nombre"];
$servicios[$i][11][$z][1]=$data3["cantidad_reserva_adicionales"];
$servicios[$i][11][$z][2]=$data3["precioUnitarioSIva"];

$servicios[$i][11][$z][3]=$data3["valorImpuestos"];
$servicios[$i][11][$z][4]=$data3["total"];


 $totalReserva+= $data3["total"];

 
   $z++;
    }
  }
        







 
    $i++;
    }
  }



$query2=mysqli_query($conection,"SELECT * FROM reserva_horarios_paquetes WHERE idReserva=".$idReserva);
        $result2=mysqli_num_rows($query2);
 
        $i=0;
        $paquetes = Array();
  if ($result2 > 0) {
      while ($data2 = mysqli_fetch_array($query2)) {
      $paquetes[$i]= Array();
      $paquetes[$i][0]=$data2["servicioId"];
      $paquetes[$i][1]=$data2["idReservaHorarioPaquete"];
      $paquetes[$i][2]=$data2["idHotel"];
      $paquetes[$i][3]=DevuelveHotel($paquetes[$i][2]);
      $paquetes[$i][4]=$data2["precioAdulto"];
      $paquetes[$i][5]=$data2["cantAdultos"];
      $paquetes[$i][6]=$data2["precioAdultoAdicional"];
      $paquetes[$i][7]=$data2["cantAdultosAdicionales"];
      $paquetes[$i][8]=$data2["precioMenor"];
      $paquetes[$i][9]=$data2["cantMenores"];
       $paquetes[$i][10]=$data2["total"];
      $paquetes[$i][12]=$data2["idHotelCuarto"];
  
     

        $totalReserva+= $paquetes[$i][10];


  $query3=mysqli_query($conection,"SELECT * FROM reserva_adicionales RA INNER JOIN servicios_adicionales SA ON RA.idServiciosAdicionales=SA.idServiciosAdicionales WHERE RA.idReservaHorarioPaquete =".$paquetes[$i][1]);
        $result3=mysqli_num_rows($query3);
     
        $z=0;
  if ($result3 > 0) {
      while ($data3 = mysqli_fetch_array($query3)) {

$paquetes[$i][11][$z]=Array();
$paquetes[$i][11][$z][0]=$data3["nombre"];
$paquetes[$i][11][$z][1]=$data3["cantidad_reserva_adicionales"];
$paquetes[$i][11][$z][2]=$data3["precioUnitarioSIva"];

$paquetes[$i][11][$z][3]=$data3["valorImpuestos"];
$paquetes[$i][11][$z][4]=$data3["total"];


 $totalReserva+= $data3["total"];


   $z++;
    }
  }$i++;




    } 
  }
$totalAPagar=$totalReserva-$compPagos;

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
         <a class="navbar-brand text-white" ><h3>METELEBRASIL</h3></a>
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
      <div class="col-lg-12  ">
        <ul class="lista-pasos-form">
      <li class="active"> <strong>Consulta de reserva <?= $idReserva;?></strong></li>
        </ul>
      </div>
    </div>
  </div>
</section>
<!--FIN PASOS PARA RESERVA-->

<!--SECCION DATOS PERSONALES-->
<section>
  <div class="container">
    <div class="row">

        <!--RESUMEN DE PEDIDO-->
      <div class="col-lg-4 col-md-4">
        <div class=" py-3">
             <div class="card card-visitas ">
              <div class="card-body">
                 <h5>Resumen <a  class="float-right"><small> </small></a></h5>
                 <!--ACORDEON CARACTERISTICAS-->
                  <div class="accordion" id="faq1">
                 <div class="card card-accordion">
                    <div class="" id="headingOne">
                      <h5 class="mb-0">
                        <a class="btn btn-accordion text-primary bg-white " data-toggle="collapse" data-target="#collapseOne2" aria-expanded="true" aria-controls="collapseOne">
                          <p> <small class="float-right"></small></p>
                   
                        </a>
                      </h5>
                    </div>

                  <!--de aca le saque class="collapse"-->  <div id="collapseOne2" class=" " aria-labelledby="headingOne" data-parent="#faq1"><ul>
          <?php 
if (count($paquetes)>0) {
  foreach ($paquetes as $key => $value) {
            $idServicio=$value[0];
      
echo '<p>'.NombreServicio($idServicio).'</p>';
echo '<p>Hotel: '.$value[3][0].'<small class="float-right">Cuarto '.DevuelveCuartoHotel($value[12], 270)[7].'</small></p>';
 echo "<p>".$value[5]." Adultos ".$sym." ".$value[4]." X PAX</p>";
if ($value[7]>0) {
 echo "<p>".$value[7]." Adultos adicionales ".$sym." ".$value[6]." X PAX</p>";
}
if ($value[9]>0) {
 echo "<p>".$value[9]." Menores ".$sym." ".$value[8]." X PAX</p>";
}

if (isset($cupon[2])>0) {
  echo '<p>'.$cupon[1].' te brindo un descuento de '.$cupon[2].' % </p>';
}
$totalAdicionales=0;
$impuestosAdicionales=0;
for ($i=0; $i < count($value[11]); $i++) { 
  echo '
  <p>'.$value[11][$i][1]." ".$value[11][$i][0]." ".$sym." ".$value[11][$i][2].' X PAX</p>

  ';

$totalAdicionales+=$value[11][$i][4];
$impuestosAdicionales+=$value[11][$i][3];


}
  echo "<p>Impuestos y Tasas: ".$sym." ".($value[13]+$impuestosAdicionales)."</p>";

    echo "<p>Total: ".$sym." ".($value[10]+$totalAdicionales)."</p>";
echo "<hr></hr>";

}
}
//fin paquetes****************************



if (isset($servicios) && count($servicios) > 0 ) {
  foreach ($servicios as $key => $value) {
            $idServicio=$value[0];
      
echo '<p>'.NombreServicio($idServicio).'</p>';

 echo "<p>".$value[5]." Adultos ".$sym." ".$value[4]." X PAX</p>";

if ($value[6]>0) {
 echo "<p>".$value[6]." Menores de 12 años ".$sym." ".$value[7]." X PAX</p>";
}

if ($value[8]>0) {
 echo "<p>".$value[8]." Menores de 5 años ".$sym." ".$value[9]." X PAX</p>";
}
if ($value[14]>0) {
 echo "<p>".$value[14]." Menores de 3 años ".$sym." ".$value[15]." X PAX</p>";
}

if (isset($cupon[2])) {
  echo '<p>'.$cupon[1].' te brindo un descuento de '.$cupon[2].' % </p>';
}
$totalAdicionales=0;
$impuestosAdicionales=0;
for ($i=0; $i < count($value[11]); $i++) { 
  echo '
  <p>'.$value[11][$i][1]." ".$value[11][$i][0]." ".$sym." ".$value[11][$i][2].' X PAX</p>

  ';

$totalAdicionales+=$value[11][$i][4];
$impuestosAdicionales+=$value[11][$i][3];


}
  echo "<p>Impuestos y Tasas: ".$sym." ".($value[13]+$impuestosAdicionales)."</p>";

    echo "<p>Total: ".$sym." ".($value[10]+$totalAdicionales)."</p>";
echo "<hr></hr>";

}
}
?>
                            
                            </ul>
        
                    </div>
                  </div>
              </div>
                 <!--FIN ACORDEON CARACTERISTICAS-->
                <hr class="hr-puntuada">
                 <!--PRECIO TOTAL-->

                 <div class="div-precio-t">
                   <p class="mb-0 float-left"><strong>Precio total</strong></p>
<p class="mb-0 float-right"><strong><?= $sym." ".$totalReserva?></strong></p>
                 </div>

                 <!--FIN PRECIO TOTAL-->

              </div>
            </div>
          </div>
      </div>
      <!--FIN RESUMEN DE PEDIDO-->

      <!--DATOS DE PAGO-->
      <div class="col-lg-8 col-md-8">
        <!--METODOS DE PAGO-->
        <div class=" py-3">
             <div class="card card-visitas" id="cardVisitas">
              <div class="card-body">
                 <h5 class="mb-4" id="textoMetodoDePago">Mi Reserva</h5>
                 <!--FORM DE PAGO-->
                <form class="datos-p" id="divMetodosDePago">
                  <div class="container paymentCont mb-4">
                      <div class="row paymentWrap">
                   <div class="btn-group col-lg-12">
                    <!--AQUI VA LA CARGA DE DIVISA-->
                  
                     
                      <!--FIN AQUI VA LA CARGA DE DIVISA-->
                      </div>        
                </div>
                    <?php 
if (count($paquetes)>0) {
  foreach ($paquetes as $key => $value) {
            $idServicio=$value[0];
      
echo '<p>'.NombreServicio($idServicio).'</p>';

?><div class="google-maps">
 <iframe src = "https://maps.google.com/maps?q=<?=DevuelveCoordenadasServicio($idServicio)["latitud"]?>,<?=DevuelveCoordenadasServicio($idServicio)["longitud"]?>&hl=es;z=14&amp;output=embed"></iframe>

 </div>
<?php
print_r($horarios);
}
}
//fin paquetes****************************



if (isset($servicios) && count($servicios) > 0 ) {
  foreach ($servicios as $key => $value) {
            $idServicio=$value[0];
            echo "idReserva".$idReserva;
$horarios=DevuelveDatosReservaHorarios($idReserva);
print_r($horarios);
?>

<div class="a100">
  <h3><?=NombreServicio($idServicio);?></h3>
  <p>Su servicio sale de: </p>
 <iframe src = "https://maps.google.com/maps?q=<?=DevuelveCoordenadasServicio($idServicio)["latitud"]?>,<?=DevuelveCoordenadasServicio($idServicio)["longitud"]?>&hl=es;z=14&amp;output=embed"></iframe>
</div>

<?php

}
}
?>
              </div>                    
                </form>
                 <!--FIN FORM DE PAGO-->
              </div>
            </div>
          </div>
           <!--FIN METODOS DE PAGO-->

          
      </div>
      <!--FIN DATOS DE PAGO-->
    </div>

  </div>


</section>
<!--FIN SECCION DATOS PERSONALES-->



  <!-- Footer -->
  
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
           <h4 class="text-left"><small><span>METELEBRASIL</span> es una marca de RESERVARTE SL.</small></h4>
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
