 <?php

// Detectar ambiente: si el hostname no contiene "server", estamos en desarrollo
if (empty(getenv('APP_ENV'))) {
    $hostname = getenv('COMPUTERNAME') ?: gethostname() ?: $_SERVER['SERVER_NAME'] ?? '';
    if (strpos($hostname, 'server') === false) {
        putenv('APP_ENV=dev');
    } else {
        putenv('APP_ENV=prod');
    }
}

session_start();
header('Content-Type: text/html; charset=utf-8');

// Middleware de mantenimiento
require_once(__DIR__ . '/../admin/classes/middleware_mantenimiento.php');



if (isset($_SESSION["login"]['idUsuario']) && $_SESSION['login']['idUsuario']==1 && 0) {

  print_r('*****Dev mode*****');

  print_r($_SESSION);

  echo "idpais".$idPais."<br>";

  echo "impuestos_pais".$impuestos_pais;

  // code...

}





require("admin/classes/functions.php");



require("admin/classes/parametros.php");



    require("admin/classes/servicio.php");



  require("admin/classes/fotos_servicio.php");



include("admin/classes/geolocalizacion.php");

$parametros=getParametros();







//verificamos si hay cambios de lenguaje mediante POST







// verificamos la sesion creada



if(isset($_SESSION['idioma'])){



  // si es true, se crea el require y la variable lang



  $lang = $_SESSION["idioma"];







  require "admin/lang/".$lang.".php";

  // Asegurar imagen de banderita según idioma actual
  if (!isset($_SESSION["idioma_bandera"]) || empty($_SESSION["idioma_bandera"])) {
    switch ($lang) {
      case 'ES':
        $_SESSION["idioma_bandera"] = 'img/countries/Spain-icon.png';
        break;
      case 'EN':
        $_SESSION["idioma_bandera"] = 'img/countries/United-States-of-Americ-icon.png';
        break;
      case 'PT':
        $_SESSION["idioma_bandera"] = 'img/countries/Brazil-icon.png';
        break;
      case 'IT':
        $_SESSION["idioma_bandera"] = 'img/countries/italy-icon.png';
        break;
      default:
        $_SESSION["idioma_bandera"] = 'img/countries/Brazil-icon.png';
        break;
    }
  }







  // si no hay sesion por default se carga el lenguaje espanol



}else{



  $_SESSION["idioma_bandera"]='img/countries/Brazil-icon.png';



   $_SESSION["idioma"]="PT";



  require "admin/lang/PT.php";



}





 



if ( !is_bot($_SERVER['HTTP_USER_AGENT']) ) {



 





$url=getUrlGeoUser();



//$url='http://www.geoplugin.net/php.gp?ip='.'179.36.137.63';







$geo=(geoLocalizacionIp($url,0,0));



$langd=$geo["lang"];



//print_r($_SESSION);











$idPais=$geo["idPais"];



$_SESSION['geo']=$geo;



include("admin/classes/impuestos_pais.php");



//echo "idpais".$idPais;|



$impuestos_pais=getImpuestosPais($idPais);



//echo "impuestos_pais".$impuestos_pais;







$_SESSION['impuestos_pais']=$impuestos_pais;







if (!isset($_SESSION["moneda_sel"])) {



   $_SESSION['moneda_sel']=283;



$_SESSION['moneda_sel_sym']='R$';



 $monedaSelSym='R$';



}











//$_SESSION['login']['idVendedor']=1;



if (!isset($_SESSION['login']['idVendedor'])) {



  $_SESSION['login']['idVendedor']=0;



}



?>



<script type="text/javascript">



  var symMoneda='<?=$_SESSION['moneda_sel_sym'];?>';



  var idVendedor="<?=$_SESSION["login"]["idVendedor"]?>";



</script>



<?php







}





$version = date('Y-m-d H:i:s');



if (isset($_SESSION['reserva'])) {



  $carrito=$_SESSION['reserva'];



}







else{



  $carrito=array();



}





//print_r($carrito);



$cantCarrito=count($carrito);











?>







<!DOCTYPE html>



<html lang="es">



<?php



$nombre_servicio="Metele Brasil";



$descripcion_corta="Atividades, excursões, visitas guiadas em Brasil. Reserve online! preço mínimo antecipado e garantido.";



 ?>



<head><!--



<div id="fb-root"></div>



<script async defer crossorigin="anonymous" src="https://connect.facebook.net/es_ES/sdk.js#xfbml=1&version=v5.0&appId=533299343733095&autoLogAppEvents=1"></script>-->







<!-- Global site tag (gtag.js) - Google Analytics -->



<script async src="https://www.googletagmanager.com/gtag/js?id=UA-175922201-1"></script>



<script>



  window.dataLayer = window.dataLayer || [];



  function gtag(){dataLayer.push(arguments);}



  gtag('js', new Date());







  gtag('config', 'UA-175922201-1');



</script>











  <title>Metele Brasil</title>

<meta property="fb:app_id" content="964551587611699" />

  

<?php if(isset($_GET['id'])){



  $idServicio=$_GET['id'];



                    $servicio=getServicio($idServicio)[0];

                      $fotos=getFotosServicio($idServicio);?>

<meta property="og:url" content="https://www.metelebrasil.com.com/servicio" />

    <meta property="og:title"  content="<?=$servicio["nombre_servicio"];?> | Metele Brasil" />



    <meta property="og:description" content="<?=$servicio["descripcion_corta"];?>" />



    <meta name="keywords" content="excursões, visitas guiadas, passeios, atividades, traslados, circuitos, guias turísticos, guias de viagem" />



    <meta charset="utf-8">



    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">





<meta property="og:type" content="article" />



<meta property="og:image:width" content="400" />



<meta property="og:image:height" content="300" />



<link rel="icon" href="img/favicon.ico" sizes="32x32">

<meta property="og:image" content="https://metelebrasil.com/admin/classes/imgServicio/<?=$fotos[0]['ruta'];?>" /> 

                      <?php } 

                      else if(isset($_GET['post'])){

                        require("admin/classes/blog.php"); 

require("admin/classes/fotos_blog.php");

                        $idPost=$_GET['post'];

                         $articulo=getArticuloBlog($idPost);

                         $descripcionCorta=$articulo[0]["descripcionCorta"];

                         $fotos=getFotosBlogIdPost($idPost);  

                            $titulo=$articulo[0]["titulo"];

?>



    <meta property="og:title"  content="<?=$titulo;?> | Metele Brasil" />



    <meta property="og:description" content="<?=$descripcionCorta;?>" />



    <meta name="keywords" content="excursões, visitas guiadas, passeios, atividades, traslados, circuitos, guias turísticos, guias de viagem" />



    <meta charset="utf-8">



    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">





<meta property="og:type" content="article" />



<meta property="og:image:width" content="400" />



<meta property="og:image:height" content="300" />



<link rel="icon" href="img/favicon.ico" sizes="32x32">

<meta property="og:image" content="https://metelebrasil.com/admin/classes/imgBlog/<?=$fotos[0]['ruta'];?>" /> 

<?php

                      } 

else{

  ?>



    <meta property="og:title"  content="Metele Brasil" />

<meta property="og:url" content="https://metelebrasil.com" />



    <meta property="og:description" content="Atividades, excursões, visitas guiadas em Brasil. Reserve online! preço mínimo antecipado e garantido." />



    <meta name="keywords" content="excursões, visitas guiadas, passeios, atividades, traslados, circuitos, guias turísticos, guias de viagem" />



    <meta charset="utf-8">



    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">



<meta property="og:image" content="https://metelebrasil.com/img/slider4.jpg" />  



<meta property="og:image:width" content="1280" />



<meta property="og:image:height" content="720" />

<meta property="og:type" content="website" />





<link rel="icon" href="img/favicon.ico" sizes="32x32">



  <?php

}

?>









   



<script type="text/javascript">
// Override seguro de logout para evitar bucles en modo mantenimiento
try {
  window.logout = function(){
    window.location.href = 'logout.php';
  };
} catch(e) {}
</script>
   



   <!-- ESTILOS NECESARIOS -->



   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

   <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>

   <script src="js/jquery.redirect.js?v=<?php echo $version?>"></script>











   <!-- FONT-AWESOME -->



   <link href="admin/plugins/fontawesome-free/css/all.min.css?v=<?php echo $version?>" rel="stylesheet" type="text/css">



   <!-- FONT-AWESOME -->



   



   <!-- ANIMATE -->



   <link rel="stylesheet" href="css/animate.min.css?v=<?php echo $version?>">



   <!-- ANIMATE -->



   



   <!-- BOOTSTRAP V4-->



   <link href="css/bootstrap.css?v=<?php echo $version?>" rel="stylesheet">



   <!-- BOOTSTRAP V4 -->



   



   <!-- STYLES GENERALES -->



   <link href="css/styles.css?v=<?php echo $version?>" rel="stylesheet">



   <!-- STYLES GENERALES -->



   



   <!-- RESPONSIVE DESING-->



   <link href="css/responsive.css?v=<?php echo $version?>" rel="stylesheet">



   <!-- RESPONSIVE DESING -->



   



   <!-- ESTILOS CALENDARIO-->



   <link href="css/clnr.css?v=<?php echo $version?>" rel="stylesheet">



   <!-- ESTILOS CALENDARIO-->



   



    <!-- FUENTES-->









   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>



<?= $parametros[0]["head"]?>

<!-- Google reCAPTCHA v3 -->
<script src="https://www.google.com/recaptcha/api.js?render=6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI"></script>

<!-- Sistema Anti-Bot -->
<script src="js/antibot.js"></script>

</head>







<div id="bodyCarga"></div>



<body id="page-top" class="body-visita">

<?= $parametros[0]["body"]?>

<div id="fb-root"></div>

<script async defer crossorigin="anonymous" src="https://connect.facebook.net/es_ES/sdk.js#xfbml=1&version=v11.0&appId=964551587611699&autoLogAppEvents=1" nonce="uv9I2f53"></script>







<script>



 var comboGoogleTradutor = null; //Varialvel global







    function googleTranslateElementInit() {



        new google.translate.TranslateElement({



            pageLanguage: 'es',



            includedLanguages: 'es,pt,en,fr,it',



            layout: google.translate.TranslateElement.InlineLayout.HORIZONTAL



        }, 'google_translate_element');







        comboGoogleTradutor = document.getElementById("google_translate_element").querySelector(".goog-te-combo");



    }







    function changeEvent(el) {



        if (el.fireEvent) {



            el.fireEvent('onchange');



        } else {



            var evObj = document.createEvent("HTMLEvents");







            evObj.initEvent("change", false, true);



            el.dispatchEvent(evObj);



        }



    }



<?php 

setcookie("googtrans","/en/en", time()+3600, "/",'.metelebrasil.com');
 ?>



    function trocarIdioma(sigla) {


        if (comboGoogleTradutor) {

            comboGoogleTradutor.value = sigla;
      			document.cookie = "sigla=" + sigla;
      			var novalng = '';
      			var novaimg = '';


if(sigla == 'es'){
			 novalng = 'Español';	
			 novaimg = 'img/countries/Spain-icon.png';

			} else if(sigla == 'en'){

		 novalng = 'Ingles';
     novaimg = 'img/countries/United-States-of-Americ-icon.png';			 

			}  else if(sigla == 'pt'){

			 novalng = 'Portugues';		
			 novaimg = 'img/countries/Brazil-icon.png';



			}



			$(".dropdown a.nomelinguagem span.nomelinguagemx").html(novalng);



			$(".dropdown a.nomelinguagem img#iconbandeira").attr({'src':novaimg});



			$("#txtIdiomaSelMovil img#iconbandeira").attr({'src':novaimg});



			$("#idioma").css('display','none');



            changeEvent(comboGoogleTradutor);//Dispara a troca



        }



    }

trocarIdioma("en");


</script>



<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>





</body>

<style>
  .btn-finalizar-carrito {
    display: block;
    width: 100%;
    border-radius: 999px !important;
    padding: 10px 14px !important;
    font-weight: 700;
    background: #029ce2 !important;
    background-color: #029ce2 !important;
    border: 1px solid #029ce2 !important;
    color: #fff !important;
    text-decoration: none !important;
    text-align: center;
    box-shadow: 0 2px 4px rgba(0,0,0,0.15);
  }
  .btn-finalizar-carrito:hover,
  .btn-finalizar-carrito:focus,
  .btn-finalizar-carrito:active {
    color: #fff !important;
    background: #0284c7 !important;
    background-color: #0284c7 !important;
    border-color: #0284c7 !important;
    text-decoration: none !important;
  }
  @media (max-width: 991px) {
    .menumobile {
      right: 10px !important;
      left: auto !important;
      width: calc(100vw - 30px) !important;
      max-width: 340px !important;
      min-width: unset !important;
    }
  }
</style>

   <!-- MENU PC-->



  <nav class="navbar navbar-expand-lg bg-celeste text-uppercase  d-none d-lg-block" id="mainNav">



    <div class="container">





 <a class="navbar-brand js-scroll-trigger" href="index">

<h1>



  <img src="img/favicon.png" sizes="20x20" style=" width: 35px; height: 38px; border: 5px;",>



METELE BRASIL



</h1>



</a>

 







          



        <ul class="navbar-nav ml-auto">



            



             <!-- FORM BUSCADOR-->



            <form class="search-menu form-inline my-2 my-lg-0">



              <input class="form-control mr-sm-2 text-white" type="search" id="search-pc" placeholder="O que você quer fazer?" aria-label="Search">



            </form>



             <!-- FIN FORM BUSCADOR-->



             



              <!-- NAV-ITEM-->



          <div class="mostrarenmobile">



          <li class="nav-item mx-0 mx-lg-1">



            <a class="nav-link py-3 px-0 px-lg-3 rounded-sm cursor" id="buscar-pc" href="categorias"><i class="fa fa-search"></i></a>



            <a class="nav-link py-3 px-0 px-lg-3 rounded-sm cursor" style="display: none;" id="cerrar-buscar-pc"><i class="fa fa-search"></i></a>



          </li>



          </div>



           <!-- FIN  NAV-ITEM-->



           <li class="nav-item mx-0 mx-lg-1 dropdown">



            <a class="nav-link py-3 px-0 px-lg-3 rounded-sm dropdown-toggle nomelinguagem" href id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img class="nav-item mx-0 mx-lg-1 dropdown" id="iconbandeira" src="<?= $_SESSION["idioma_bandera"];?>" style="height: 20px; width: 20px;">



            <span class="nomelinguagemx"><?=$_SESSION["idioma"];?></span>



            </a>



            <div class="dropdown-menu dropdown-menu-right menu-civa p-2" aria-labelledby="navbarDropdownMenuLink" style="min-width:240px; max-height:400px; overflow-y:auto;">



			  <a class="dropdown-item" onclick="cambiaIdioma('ES');"><img src="img/countries/Spain-icon.png" style="height: 20px; width: 20px;"> &nbsp;<?=$lang["espanol"];?></a>



              <a class="dropdown-item" onclick="cambiaIdioma('EN');"><img src="img/countries/United-States-of-Americ-icon.png" style="height: 20px; width: 20px;"> &nbsp;<?=$lang["ingles"];?></a>



              <!--<a class="dropdown-item" onclick="cambiaIdioma('IT');"><img src="img/countries/italy-icon.png" style="height: 20px; width: 20px;"> &nbsp;Italiano</a>-->



              <a class="dropdown-item" onclick="cambiaIdioma('PT');"><img src="img/countries/Brazil-icon.png" style="height: 20px; width: 20px;"> &nbsp;<?=$lang["portugues"];?></a>



             <!-- <a class="dropdown-item" onclick="cambiaIdioma('FR');"><img src="img/countries/France-icon.png"  style="height: 20px; width: 20px;"> &nbsp;Frances</a>-->



            </li>



            <!-- FIN NAV-ITEM-->



           



            <!-- NAV-ITEM-->



    <li class="nav-item mx-0 mx-lg-1 dropdown"  id="drpMonedaSel">







   <a class="nav-link py-3 px-0 px-lg-3 rounded-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="currencyDropdown" onclick="muestraMenuMonedas()" ><?= $_SESSION["moneda_sel_sym"]; ?></a> 







     <div class="dropdown-menu menu-civa" aria-labelledby="currencyDropdown" id="divMonedaSel">



                                   <?php 



    require("admin/classes/moneda.php");



$monedas=getMonedas();







for ($i=0; $i < count($monedas); $i++) { 



 ?>



   <a onclick="cambiaMoneda(<?=$monedas[$i]["idMoneda"];?>);" class="dropdown-item">



                     <?=$monedas[$i]["Symbol"]." ".$monedas[$i]["CurrencyName"];?>



          </a>







 <?php



}



       ?>



                    



                      



                   



                    </div>



          </li>



          <!-- FIN NAV-ITEM-->











<script type="text/javascript">



   function cambiaMoneda(cambiaMoneda){







      $.post("admin/ctrl/ctrlMoneda", {cambiaMoneda: cambiaMoneda}, function(data, status){



if (data==1) {







  location.reload();







}







  });



   }











      function cambiaIdioma(idioma){







      $.post("admin/ctrl/ctrlIdioma", {cambiaIdioma: idioma, funte:'admin'}, function(data, status){console.log(data);



if (data==1) {







  location.reload();







}







  });



   }



</script>







<meta name="google-signin-client_id" content="269820256021-c7q9cfo6medjj2incjosne73cop5gngf.apps.googleusercontent.com">



  <script src="https://apis.google.com/js/platform.js?onload=renderButton" async defer></script>







           



               <?php if (isset($_SESSION["login"]["active"])) {



            



                ?>



                 <!-- NAV-ITEM-->



          <li class="nav-item dropdown mx-0 mx-lg-1 user" style="position: relative;">



            <a class="nav-link py-3 px-0 px-lg-3 rounded-sm dropdown-toggle" href="#" id="userDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">



            

<?php 

$foto='admin/img/user_default.png';

if (strlen($_SESSION['login']['foto']>3)) {

  $foto='admin/img/usuarios/'.$_SESSION['login']['foto'];

}?>

              <img style="width: 30px; border-radius: 10px;" src="<?=$foto;?>"></img> <?php if (isset( $_SESSION["login"]["usuario"])) {



                echo $_SESSION["login"]["usuario"];



              } ?>



            </a>



             <!-- CONTENEDOR USUARIO LOGIN -->



             <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">



<?php if($_SESSION["login"]['idVendedor']>0 || $_SESSION["login"]['idCobrador']>0 || $_SESSION["login"]['idPrestador']>0 ) { ?>



  <a class="dropdown-item" href="admin/"><i class="fa fa-unlock" aria-hidden="true"></i> <?=$lang["iniciar_extranet"]?> </a> <?php } ?> 



   



                   



                    <button onclick="logout();" class="dropdown-item" ><i class="fas fa-sign-out-alt"></i><?=$lang["cerrar_sesion"]?></button>



                    



                     <div class="my-signin2" id="my-signin2" style="display:none;"></div>



                      



                   



                    </div>



           <!-- FIN CONTENEDOR USUARIO LOGIN -->



          </li>



          <!-- FIN NAV-ITEM-->



          <script type="text/javascript">



         function logout(){



        



          $.post('ctrlLogin', {



    logout:{    'data' : 2 }



  }, function(response) {







 if (response==1) { }



    Swal.fire('Reservate','<?=$lang["gracias_por_usar"]?>','success'); //gracias por usar reservate



  



setTimeout(location.reload(), 5000);



   });



}   



               function renderButton() {



      gapi.signin2.render('my-signin2', {



        'scope': 'profile email',



        'width': 240,



        'height': 50,



        'longtitle': true,



        'theme': 'dark'



      });



    }



          </script>



    



            <?php   } /*    $.post('ctrlLogin.php', {



    alert();



    logout:{    'data' : "2" }



  }, function(response) {



console.log(response);



 if (response==1) { }



    Swal.fire('Gracias por utilizar MeteleBrasil.com','','success');



 setTimeout(location.reload(), 30000);



 















      });*/



else{  ?> 



           <li class="nav-item mx-0 mx-lg-1 user">



            <a class="nav-link py-3 px-0 px-lg-3 rounded-sm "  data-toggle="modal" data-target="#modalLoginForm">



           



              <i class="fa fa-user-alt"></i> 



            </a>



             <!-- CONTENEDOR USUARIO LOGIN -->



         



           <!-- FIN CONTENEDOR USUARIO LOGIN -->



          </li>







   <div class="modal fade" id="modalLoginForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"



  aria-hidden="true">



  <div class="modal-dialog" role="document">



    <div class="modal-content">



      <div class="row">



   <div class="col bg-white ">



             <div class="card card-usuario">



                  <div class="card-body">



                    <h3 class="text-primary"><?=$lang["mi_cuenta"];?></h3>



                <p><?=$lang["ya_tiene_cuenta_accede_a_tu_panel_de_usuario"];?></p>



                <form action="ctrlLogin" method="post" class="form-group">



                  <div class="form-group  ">



                    <input style="display:none">



                    <input type="email" name="email" autocomplete="off" class="form-control " placeholder="Email">



                  </div>



                   <div class="form-group">



                   <input type="hidden" name="login" value="1">



                    <div class="nav-password-wrapper">
                      <input type="password" name="clave" id="nav-clave" autocomplete="off" class="form-control" placeholder="Contraseña">
                      <button class="nav-eye-inline" type="button" onclick="toggleNavClave('nav-clave', this)"><i class="fa fa-eye" aria-hidden="true"></i></button>
                    </div>



                    <small class="float-right text-primary py-2"><a href="recuperar_contrasena"><?=$lang["he_olvidado_mi_contrasena"];?></a></small>



                    <div class="my-signin2" id="my-signin2" style="padding-top: 38px; width: 10%; height: 10%; font-size: 13px;"></div>



                  </div>



                  <button class="btn btn-primary bd-highlight"><?=$lang["iniciar_sesion"];?></button>



                </form>



                <div class="row mb-4">



                  <div class="col-lg-5">



                     







                  </div>



                  <h5><?=$lang["no_tienes_cuenta"];?><span><?=$lang["registrate"];?> <a href="registro"><?=$lang["aqui"];?></a></span></h5>



                  <div class="col-lg-5">



                     







                   



                  </div>



                </div>



                



                  </div>



                </div>



                    </div>



















  <script>







/*



  var profile







    function onSuccess(googleUser) {



        profile = googleUser.getBasicProfile();



          $.post('ctrlLogin.php', {



    loginGoogle:{    'data' : JSON.stringify({name:profile.getName(), email:profile.getEmail(), foto:profile.getImageUrl()}) }



  }, function(response) {



console.log(response);



 if (response==1) { }



    Swal.fire('Oi, bem-vindo '+profile.getName()+' no Metelebrasil','','success');



  



setTimeout(location.reload(), 3000);



   });







    }



    function onFailure(error) {



      Swal.fire('Error en la comunicación inténtelo mas tarde','','warning');



    }



    function renderButton() {



      gapi.signin2.render('my-signin2', {



        'scope': 'profile email',



        'width': 180,



        'height': 36,



        'longtitle': true,



        'theme': 'dark',



        'onsuccess': onSuccess,



        'onfailure': onFailure



      });



    }

*/

  </script>











 <!-- CONTENEDOR RESERVAS -->



              <div class="col bg-light">



               <div class="card card-usuario ">



                 <div class="card-body">



                    <h3 class="text-primary"><?=$lang["mis_reservas"];?></h3>



                <p><?=$lang["puedes_gestionar_tu_reserva_sin_estar_registrado"];?></p>



                <form action="consultaReserva">



            



                   <div class="form-group">



                    <input type="text" name="reserva" class="form-control" placeholder="Codigo Reserva">



                  </div>



                  <button class="btn btn-primary"><?=$lang["ir_a_reserva"];?></button>



                </form>



                 </div>



               </div>



              </div>



              <!-- FIN CONTENEDOR RESERVAS -->



</div>



    </div>



  </div>



</div>







<?php    }











               ?>



   



          



      















              



   



          



          <!-- NAV-ITEM-->



          <li class="nav-item mx-0 mx-lg-1 dropdown" style="position: relative;">



            <a class="nav-link py-3 px-0 px-lg-3 rounded-sm dropdown-toggle" href="#" id="helpDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">



              <i class="fa fa-question-circle"></i>



            </a>



            <div class="dropdown-menu menu-civa" aria-labelledby="helpDropdown">



              <a class="dropdown-item" href="faq.php"><?=$lang["preguntas_frecuentes"];?></a>



              <a class="dropdown-item" href="contact.php"><?=$lang["contactar_con_metelebrasil.com"];?></a>



            </div>



           </li>



      <!-- FIN NAV-ITEM-->



      



      <!-- FIN NAV-ITEM-->



          <li class="nav-item mx-0 mx-lg-1 dropdown" style="position: relative;">



            <a class="nav-link py-3 px-0 px-lg-3 rounded-sm dropdown-toggle" href="#" id="cartDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">



              <i class="fas fa-shopping-cart text-white"><span class="badge badge-danger navbar-badge"><?=$cantCarrito;?></span></i>



            </a>



            <div class="dropdown-menu dropdown-menu-right menu-civa p-2" aria-labelledby="cartDropdown" style="min-width:240px; max-height:400px; overflow-y:auto;">



	<?php





    require("admin/classes/tarifas.php");



      require("admin/classes/salidas.php");



       require("admin/classes/edades.php");



         require("admin/classes/comisiones.php");



           require("admin/classes/cancelaciones.php");



         require("admin/classes/convierte_monedas.php");



         ?>











         <?php



  if ($cantCarrito == 0) { ?>



     <p class="vacio" style="text-align:center"><?=$lang["tu_carrito_esta_vacio"];?></p> 



<?php  } 







$precioTotalCarrito=0;



if (count($carrito)>0) { 



for ($i=0; $i < count($carrito); $i++) { 



      $reserva=$carrito[$i][0];



      $idServicio=$reserva[0]['idServicioSeleccionado'];



            $servicio=getServicio($reserva[0]['idServicioSeleccionado']);



           $fotos=getFotosServicio($idServicio);



               $precioReserva=0;



       $cantidadPasajeros=0;



             for ($j=0; $j < count($reserva); $j++) { 



       



      $servicio=getServicio($reserva[$j]['idServicioSeleccionado']);



      $idServicioSalidasTarifas=$reserva[$j]['idServicioSalidasTarifas'];



      $cantidad=$reserva[$j]['cantidad'];







      $cantidadPasajeros+=$reserva[$j]['cantidad']; 



  



        $tarifa=calculaTarifa($reserva[$j]["idServicioSalidasTarifas"],$reserva[$j]["cantidad"]);







          $precioReserva+=$tarifa[0]["valor"];



 



    



$precioTotalCarrito+=$tarifa[0]["valor"];







      }



  ?>







  <div class="col-md-12 col-12" id="carrinhonovoactividades' . $key . '" style="padding: 6px 0; border-bottom: 1px solid #f0f0f0;">

    <div class="row" style="margin: 0;">     

         



      <div class="col-md-4 col-4" style="padding: 0;"><img src="admin/classes/imgServicio/<?=$fotos[0]['ruta'];?>" style="width: 40px; height: 40px; object-fit: cover; border-radius: 3px;"></div>

          <div class="col-md-5 col-5" style="padding: 0 8px;">
            <p style="font-size: 14px; font-weight: 600; margin: 0; color: #333;"><?=$servicio[0]["nombre_servicio"];?></p>
            <small style="font-size: 12px; color: #999; margin: 3px 0 0 0;"><?=$cantidadPasajeros;?> pax</small>
          </div>

          <div class="col-md-3 col-3" style="padding: 0; display: flex; justify-content: space-between; align-items: center;">
            <span style="font-size: 13px; font-weight: 700; color: #007bff;"><?=$_SESSION['moneda_sel_sym'].' '.$precioReserva;?></span>
            <i class="fas fa-trash excluircarrinhoactividades" data-id="<?=$i?>" style="cursor: pointer; font-size: 12px; color: #dc3545; padding: 0;"></i>
          </div>



         </div>

       </div>







  <?php



}   ?>



		 







         <div class="col-md-12 col-12 carrinhovazio" style="margin-top:15px !important; margin-bottom:10px !important">



         <div class="custom-control custom-checkbox mr-sm-2">



                                   <div class="d-flex justify-content-between align-items-center mb-3" style="padding-top: 10px; border-top: 1px solid #eee;">
                                     <span style="font-size:13px; font-weight:600; color:#666;">Total</span>
                                     <span style="font-size:18px; font-weight:bold; color:#007bff;"><?=$_SESSION['moneda_sel_sym'].' '.$precioTotalCarrito;?></span>
                                   </div>
                                   <a href="carrito.php" class="btn-finalizar-carrito">
                                     <?=$lang["finalizar_reserva"];?> <i class="fas fa-arrow-right ml-1"></i>
                                   </a>



         </div>



        </div> 



 <?php } else{ ?>



	



 <div class="col-md-12 col-12" id="carrinhonovoactividades' . $key . '"><div class="row">     



      



          



  



          <div class="col-md-8 col-8"><p class="vacio2"><?=$lang["tu_carrito_esta_vacio"];?></p></div>



         



         </div></div>



  <?php } ?>



	















            </div>



           </li>



          <!-- FIN NAV-ITEM-->



      



        </ul>



      </div>



    </div>



  </nav>



     <!-- FIN MENU PC-->



 



     <!-- DESPLIEGUE MENU PC-->



            <div class="container row" id="mostrar-pc">



                <div class="col-lg-12">



                    	<!--EMPIEZA DESPLEGABLE DEL BANNER-->	



              <div class="form-group">



               <div class="container">



                <div class="row">



                  <div class="col-lg-12">



                     <div id="destinos-pc">



                         <div class="container">



                        <div class="row mb-4">



                          <div class="col-lg-12">



                            <h3 class="text-center"><?=$lang["destacados"];?></h3>



                          </div>



                        </div>



                        <div class="row  mb-4">



                            



                            <!--EL BUCLE DE LOS RESULTADOS DEBE IR ACA-->	  



                         









            <!--<div class="col-md-3 col-6 mb-3">



         <p class=" mb-0"><a '.$link.' class="text-destinos"><strong>'.$data['nombre_servicio'].'</strong>



<img src="sistema/img/uploads/'.$fotos[0].'" class="img-fluid img-card-top img-destacada " >







                            </p>



                            <small>'.substr($data['descripcion_corta'], 0,60).'</a></small>



                            </div>-->











                            <!--FIN BUCLE DE LOS RESULTADOS DEBE IR ACA-->	 



                  



                         </div>



                         <div class="row py-4">



                          <div class="col-lg-12">



                            <h3 class="text-center"><a href="index.php" class="btn btn-outline-primary btn-white" style="border-radius:25px;"><?=$lang["ver_todos_los_destinos"];?></a></h3>



                          </div>



                        </div>



                        </div>



                     </div>



                  </div>



                </div>



                </div>



              </div>



               <!--EMPIEZA DESPLEGABLE DEL BANNER-->



                </div>



            </div>	



    <!-- DESPLIEGUE MENU PC-->



 



 <!--MENU MOVIL-->



  <section class="bg-celeste ptb-10 d-lg-none">



 	<div class="container">



 		<div class="row">



 		    <div class="col-1">



 				<a class="cursor" id="abrir-menu">



 				<i class="fa fa-bars fa-2x text-white"></i>



 				</a>



 				<a  class="cursor  collapse text-white" id="cerrar-menu">X</a>



 			</div>



      <div class="col-5 text-left" >



          <a style="margin-right: 5px;margin-left: 5px;" class="navbar-brand navbar-movil  " href="index.php"><h4 class="mb-0 text-white">METELE BRASIL</h4> </a>







      </div>







<div class="col-6  text-right" style="padding-right: 0px;">



  <ul class="lista-iconos">



   



    











    <li>



      <a  class="text-white color-w cursor-size cursor"  type="button"  id="txtIdiomaSelMovil"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

        <img class="nav-item mx-0 mx-lg-1 dropdown" id="iconbandeira" src="<?= $_SESSION["idioma_bandera"];?>" style="height: 20px; width: 20px; margin-left: 70px;">   



         <span class="nomelinguagemx"><?=$_SESSION["idioma"];?></span></a>



        <div class="dropdown-menu collapse idiomamobile" aria-labelledby="txtIdiomaSelMovil" id="idioma">



		  <a class="dropdown-item" onclick="cambiaIdioma('ES');"><img src="img/countries/Spain-icon.png" style="height: 20px; width: 20px;"> &nbsp;<?=$lang["espanol"];?></a>



              <a class="dropdown-item" onclick="cambiaIdioma('EN');"><img src="img/countries/United-States-of-Americ-icon.png" style="height: 20px; width: 20px;"> &nbsp;<?=$lang["ingles"];?></a>

 <!--  <a class="dropdown-item" onclick="cambiaIdioma('IT');"><img src="img/countries/italy-icon.png" style="height: 20px; width: 20px;"> &nbsp;<?=$lang["italiano"];?></a>---------------------------------------------------------------------------------------------------------- -->

             



              <a class="dropdown-item" onclick="cambiaIdioma('PT');"><img src="img/countries/Brazil-icon.png" style="height: 20px; width: 20px;"> &nbsp;<?=$lang["portugues"];?></a>



 <!--    <a class="dropdown-item" onclick="cambiaIdioma('FR');"><img src="img/countries/France-icon.png"  style="height: 20px; width: 20px;"> &nbsp;<?=$lang["frances"];?></a>---------------------------------------------------------------------------------------------------------- -->



           



        </div>



    </li>



    <li>







   <a style="margin-right: 0px;margin-right: 0px;" class="text-white color-w cursor-size cursor dropdown-toggle"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="currencyDropdownMobile" onclick="muestraMenuMonedas()" ><?= $_SESSION["moneda_sel_sym"]; ?></a> 







     <div class="dropdown-menu menu-civa" aria-labelledby="currencyDropdownMobile" id="divMonedaSel">



                                   <?php 











for ($i=0; $i < count($monedas); $i++) { 



 ?>



   <a onclick="cambiaMoneda(<?=$monedas[$i]["idMoneda"];?>);" class="dropdown-item">



                     <?=$monedas[$i]["Symbol"]." ".$monedas[$i]["CurrencyName"];?>



          </a>







 <?php



}



       ?>



                    



                      



                   



                    </div>



          </li>



    



<li style="position: relative; overflow: visible;">



            <a class="text-white color-w cursor-size cursor dropdown-toggle" id="navbarDropdownMenuLinkMobile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-right: 5px;">



            <i class="fas fa-shopping-cart text-white"><span class="badge badge-danger navbar-badge"><?=count($carrito);?></span></i>



            </a>



            <div class="dropdown-menu menu-civa menumobile p-3" aria-labelledby="navbarDropdownMenuLinkMobile" style="max-height:400px; overflow-y:auto; position: fixed !important; right: 5px !important; top: 55px !important; left: auto !important; width: 90vw !important; max-width: 350px !important; transform: none !important; margin: 0 !important;">



                       <?php



   if ($cantCarrito=0) { ?>



     <p class="vacio" style="text-align:center"><?=$lang["tu_carrito_esta_vacio"];?></p>



<?php  } 



?>



         <?php



$precioTotalCarrito=0;



				for ($i=0; $i < count($carrito); $i++) { 

            $reserva=$carrito[$i][0];
            $idServicio=$reserva[0]['idServicioSeleccionado'];
            $servicio=getServicio($idServicio);
            $fotos=getFotosServicio($idServicio);
            $precioReserva=0;
            $cantidadPasajeros=0;
            for ($j=0; $j < count($reserva); $j++) {
              $idServicioSalidasTarifas=$reserva[$j]['idServicioSalidasTarifas'];
              $cantidad=$reserva[$j]['cantidad'];
              $cantidadPasajeros+=$cantidad;
              $tarifa=calculaTarifa($idServicioSalidasTarifas,$cantidad);
              $precioReserva+=$tarifa[0]['valor'];
              $precioTotalCarrito+=$tarifa[0]['valor'];
            }

         ?>



<div class="col-md-12 col-12" id="carrinhonovoactividades' . $key . '" style="padding: 6px 0; border-bottom: 1px solid #f0f0f0;"><div class="row" style="margin: 0;">

<div class="col-md-4 col-4" style="padding: 0;"><img src="admin/classes/imgServicio/<?=$fotos[0]['ruta'];?>" style="width: 40px; height: 40px; object-fit: cover; border-radius: 3px;"></div>

<div class="col-md-5 col-5" style="padding: 0 8px;">
<p style="font-size: 14px; font-weight: 600; margin: 0; color: #333;"><?=$servicio[0]["nombre_servicio"];?></p>
<small style="font-size: 12px; color: #999; margin: 3px 0 0 0;"><?=$cantidadPasajeros;?> pax</small>
</div>

<div class="col-md-3 col-3" style="padding: 0; display: flex; justify-content: space-between; align-items: center;">
<span style="font-size: 13px; font-weight: 700; color: #007bff;"><?=$_SESSION['moneda_sel_sym'].' '.$precioReserva;?></span>
<i class="fas fa-trash excluircarrinhoactividades" data-id="<?=$i?>" style="cursor: pointer; font-size: 12px; color: #dc3545; padding: 0;"></i>
</div>

</div></div>



   



		<?php } ?>







			   <?php if (count($carrito)>0 ){?>



				<div class="col-md-12 col-12 carrinhovazio" style="margin-top:15px !important; margin-bottom:10px !important">



				 <div class="custom-control custom-checkbox mr-sm-2">



                   <a href="carrito.php" class="btn-finalizar-carrito"> <?=$lang["finalizar_reserva"];?> <i class="fas fa-arrow-right ml-1"></i> </a>



				 </div>



				</div> 



			   <?php } ?>



            </div>



           </li>



  </ul>



</div>



 		</div>

</nav>

<style>
@media (max-width: 991px) {
  .navbar-nav .dropdown-menu.menumobile {
    position: fixed !important;
    right: 5px !important;
    top: 55px !important;
    width: calc(100vw - 15px) !important;
    max-width: 350px !important;
    left: auto !important;
    transform: none !important;
    margin: 0 !important;
  }
}
</style>

<script>
// Forzar posición de los dropdowns móviles cuando se abren
document.addEventListener('DOMContentLoaded', function() {
  if (window.innerWidth <= 991) {
    // Dropdown carrito
    const dropdownToggle = document.getElementById('navbarDropdownMenuLinkMobile');
    if (dropdownToggle) {
      dropdownToggle.addEventListener('click', function() {
        setTimeout(function() {
          const dropdown = document.querySelector('.menumobile');
          if (dropdown) {
            dropdown.style.position = 'fixed';
            dropdown.style.right = '5px';
            dropdown.style.left = 'auto';
            dropdown.style.top = '55px';
            dropdown.style.width = '90vw';
            dropdown.style.maxWidth = '350px';
            dropdown.style.transform = 'none';
            dropdown.style.margin = '0';
          }
        }, 10);
      });
    }
    
    // Dropdown idioma
    const idiomaToggle = document.getElementById('txtIdiomaSelMovil');
    if (idiomaToggle) {
      idiomaToggle.addEventListener('click', function() {
        setTimeout(function() {
          const dropdown = document.querySelector('.idiomamobile');
          if (dropdown) {
            dropdown.style.position = 'fixed';
            dropdown.style.right = '5px';
            dropdown.style.left = 'auto';
            dropdown.style.top = '55px';
            dropdown.style.width = '90vw';
            dropdown.style.maxWidth = '250px';
            dropdown.style.transform = 'none';
            dropdown.style.margin = '0';
          }
        }, 10);
      });
    }
  }
});
</script>



 	</div>



 </section>



























    <div class="collapse navbar-collapse menu-mobile">



			  <ul class="navbar-nav mr-auto">



			        <form class="form-buscar" action="categorias">



              <div class="input-group">



            		<input class="field form-control" id="buscar-movil"  name="buscar" type="text" placeholder="<?=$lang['donde_vamos'];?>" value="">



            		  <span class="input-group-append">



            			<button class="submit btn btn-secondary" id="buscar-destinos" type="submit"><i class="fa fa-search"></i></button>



            		  </span>



              	</div>



            	<!--EMPIEZA DESPLEGABLE DEL BANNER-->	



              <div class="form-group">



               <div class="container">



                <div class="row">



                  <div class="col-lg-12">



                     <div id="destinos-movil" class="d-none">



                         <div class="container">



                        <div class="row mb-4">



                          <div class="col-lg-12">



                            <h3 class="text-center">Top</h3>



                          </div>



                        </div>



                        <div class="row  mb-4">



                            



                            <!--EL BUCLE DE LOS RESULTADOS DEBE IR ACA-->	  



                            <div class="col-md-3 col-6 mb-3">



                            <p class=" mb-0"><a href="#" class="text-destinos"><strong>Rio de Janeiro</strong></a></p>



                            <small>Brasil</small>



                            </div>



                            <!--FIN BUCLE DE LOS RESULTADOS DEBE IR ACA-->	 



                  



                         </div>



                         <div class="row py-4">



                          <div class="col-lg-12">



                            <h3 class="text-center"><a href="" class="btn btn-outline-primary btn-white" style="border-radius:25px;"><?=$lang["ver_todos_los_destinos"];?></a></h3>



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



                      <!-- NAV-ITEM-->







          <li class="nav-item mx-0 mx-lg-1 user">





               <?php if (isset($_SESSION["login"]["active"])){

                ?>

  <a  class="nav-link text-white dropdown-toggle" id="userDropdownMobile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                 <?=$lang["sea_bienvenido"]?>  <?=$_SESSION["login"]["usuario"]?>

               </a>

                     <div class="dropdown-menu menu-civa" aria-labelledby="userDropdownMobile" >



                      <form action="ctrlLogin" method="post" class="">



                      <input type="hidden" name="login" value="0">



                      <?php if($_SESSION["login"]['rol']==1 || $_SESSION["login"]['rol']==5) { ?>



                        <a class="dropdown-item" href="admin/"><i class="fa fa-unlock" aria-hidden="true"></i> <?=$lang["iniciar_extranet"]?> </a> <?php } ?> 



                      



                    <button class="dropdown-item" ><i class="fas fa-sign-out-alt"></i> <?=$lang["cerrar_sesion"];?></button>



                      </form>



                     



                      



                   



                    </div>

<?php

              }



              else{

?>

    <div class="dropdown" style="position: relative;">
    <a  class="nav-link text-white dropdown-toggle"  id="clickLoginMovil" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                <?=$lang["mi_cuenta"];?>

                       </a>
    </div>

<?php

              } ?>



     

             <!-- CONTENEDOR USUARIO LOGIN -->



             <div id="usuario-movil">



            <div >



                



                 <!-- CONTENEDOR LOGIN -->



   



               <?php if (isset($_SESSION["active"])) {



               	?>



                



      <div class="dropdown-menu menu-civa" aria-labelledby="clickLoginMovil" id="divMonedaSel">



                      <form action="ctrlLogin" method="post" class="">



                      <input type="hidden" name="login" value="0">



                      <?php if($_SESSION['rol']==1 || $_SESSION['rol']==5) { ?>

                        <a class="dropdown-item" href="sistema/"><i class="fa fa-unlock" aria-hidden="true"></i> <?=$lang["iniciar_extranet"]?> </a> <?php } ?> 



                      



                    <button class="dropdown-item" ><i class="fas fa-sign-out-alt"></i><?=$lang["cerrar_sesion"];?></button>



                      </form>



                     



                      



                   



                    </div>



                    



            <?php   } 



else{



?>   <div class="col-lg-5 bg-white ">



             <div class="card card-usuario ">



                  <div class="card-body">



                    <h3 class="text-primary"><?=$lang["mi_cuenta"];?></h3>



                <p><?=$lang["ya_tiene_cuenta_accede_a_tu_panel_de_usuario"];?></p>



                <form action="ctrlLogin" method="post" class="">



                  <div class="form-group">



                    <input type="email" name="email" class="form-control" placeholder="Email">



                  </div>



                   <div class="form-group">



                   <input type="hidden" name="login" value="1">



                    <div class="nav-password-wrapper">
                      <input type="password" name="clave" id="nav-clave-mobile" class="form-control" placeholder="Contraseña">
                      <button class="nav-eye-inline" type="button" onclick="toggleNavClave('nav-clave-mobile', this)"><i class="fa fa-eye" aria-hidden="true"></i></button>
                    </div>



                    <small class="float-right text-primary py-2"><a href=""><?=$lang["he_olvidado_mi_contrasena"];?></a></small>



                  </div>



                  <button action="submit" class="btn btn-primary bd-highlight"><?=$lang["iniciar_sesion"];?></button>



                </form>



                <br>



                <!--<div class="row mb-4" >



                  <div class="col-lg-6">



                    <div class="fb-login-button" style="padding-top: 38px; " data-width="" data-size="medium" data-button-type="login_with" data-auto-logout-link="false" data-use-continue-as="false"></div>







                  </div>



                  <div class="col-lg-6" style="display:none;">



                     <div class="g-signin2" style="padding-top: 38px; width: 200%; height: 30%; font-size: 13px;" data-onsuccess="onSignIn"></div>







                   



                  </div>



                </div> -->



          <B style="color: black;"><?=$lang["no_tienes_cuenta"];?><span><?=$lang["registrate"];?> <a href="registro"><?=$lang["aqui"];?></a></span></B>     



                  </div>



                </div>



                    </div>



                             <!-- FIN CONTENEDOR LOGIN -->



     



  <?php



}











               ?>



   



          



      



              



            </div>



          </div>



           <!-- FIN CONTENEDOR USUARIO LOGIN -->



          </li>







                   <div class="dropdown-divider"></div>



			      <li class="nav-item">



			        <a class="nav-link text-white" href=""><?=$lang["mis_reservas"];?></a>



			      </li>



			      <div class="dropdown-divider"></div>



			      <li class="nav-item">



			        <a class="nav-link text-white" href="faq.php"><?=$lang["preguntas_frecuentes"];?></a>



			      </li>



			      <div class="dropdown-divider"></div>



			      <li class="nav-item">



			        <a class="nav-link text-white" href="contact.php"><?=$lang["contactar_con_metelebrasil.com"];?></a>



			      </li>



			    </ul>



			  </div>



 <!--FIN MENU MOVIL-->

<script>
  function toggleNavClave(id, btn) {
    var input = document.getElementById(id);
    if (!input) return;
    if (input.type === 'password') {
      input.type = 'text';
      if (btn && btn.querySelector('i')) btn.querySelector('i').classList.replace('fa-eye','fa-eye-slash');
    } else {
      input.type = 'password';
      if (btn && btn.querySelector('i')) btn.querySelector('i').classList.replace('fa-eye-slash','fa-eye');
    }
  }
</script>
<style>
  .nav-password-wrapper {
    position: relative;
  }
  .nav-password-wrapper .form-control {
    padding-right: 2.75rem;
  }
  .nav-eye-inline {
    position: absolute;
    top: 0;
    bottom: 0;
    right: 10px;
    border: 0;
    background: transparent;
    padding: 0;
    width: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #4a5568;
    cursor: pointer;
  }
  .nav-eye-inline:focus { outline: none; box-shadow: none; }
  .nav-eye-inline i {
    font-size: .85rem;
    line-height: 1;
  }
</style>




















