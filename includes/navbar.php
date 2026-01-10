<?php
// ========== INICIALIZACIÃ“N DE SESIÃ“N Y HEADERS ==========
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
header('Content-Type: text/html; charset=utf-8');
@setcookie("googtrans","/en/en", time()+3600, "/",'.metelebrasil.com');

// ========== DETECTAR AMBIENTE (DEV/PROD) ==========
if (empty(getenv('APP_ENV'))) {
    $hostname = getenv('COMPUTERNAME') ?: gethostname() ?: $_SERVER['SERVER_NAME'] ?? '';
    if (strpos($hostname, 'server') === false) {
        putenv('APP_ENV=dev');
    } else {
        putenv('APP_ENV=prod');
    }
}

// ========== MIDDLEWARE DE MANTENIMIENTO ==========
require_once(__DIR__ . '/../admin/classes/middleware_mantenimiento.php');

// ========== CARGAR CLASES CORE ==========
require("admin/classes/functions.php");
require("admin/classes/parametros.php");
require("admin/classes/servicio.php");
require("admin/classes/fotos_servicio.php");
include("admin/classes/geolocalizacion.php");
require("admin/classes/visitas.php");

$parametros=getParametros();

// ========== REGISTRO DE VISITANTES ==========
// Solo registrar si no es una solicitud AJAX y el usuario no está logueado en admin
if (!isset($_SESSION['visitante_registrado_hoy']) && 
    (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest') &&
    (!isset($_SESSION['login']['idUsuario']) || $_SESSION['login']['idUsuario'] != 1)) {
  
  // Determinar referencia (de dónde llega el visitante)
  $referencia = $_SERVER['HTTP_REFERER'] ?? 'Directo';
  if (strpos($referencia, $_SERVER['HTTP_HOST']) !== false) {
    $referencia = 'Interno';
  } elseif (empty($referencia) || $referencia === '') {
    $referencia = 'Directo';
  }
  
  // Registrar visitante
  registrarVisitante(
    'Visitante',
    '',
    '',
    0,
    $referencia
  );
  
  // Marcar que se registró hoy (para no registrar múltiples veces por sesión)
  $_SESSION['visitante_registrado_hoy'] = true;
}


// ========== DEBUG MODE (solo admin) ==========
if (isset($_SESSION["login"]['idUsuario']) && $_SESSION['login']['idUsuario']==1 && false) {
  print_r('*****Dev mode*****');
  print_r($_SESSION);
}

// ========== GEOLOCALIZACIÃ“N (DEBE ir ANTES de determinar idioma) ==========
if (!isset($_SESSION['geoFinal'])) {
  require_once("admin/classes/geolocalizacion.php");
  $geoData = getGeolocalizacionData();
  $_SESSION['geoFinal'] = $geoData;
}

// ========== DETERMINAR IDIOMA ==========
// Respeta selección manual (idioma_manual). Si no hay, usa geoFinal; si tampoco, BD; si falla, ES.
if (!empty($_SESSION['idioma_manual'])) {
  // Mantener selección manual.
} elseif (isset($_SESSION['geoFinal']['lang']) && !empty($_SESSION['geoFinal']['lang'])) {
  $_SESSION['idioma'] = $_SESSION['geoFinal']['lang'];
} elseif (!isset($_SESSION['idioma'])) {
  require_once("admin/classes/idioma_pais.php");
  $geoFinal = $_SESSION['geoFinal'];
  $countryCode = strtoupper($geoFinal['countryCode'] ?? '');
  
  // Consultar mapeo desde BD
  $idioma = getIdiomaPorPais($countryCode);
  
  // Si no hay mapeo, usar ES por defecto
  if (!$idioma) {
    $idioma = 'ES';
  }
  
  $_SESSION['idioma'] = $idioma;
}

// ========== CARGAR ARCHIVO DE IDIOMA Y OBTENER ARRAY $lang ==========
$idiomaActual = $_SESSION['idioma'];
require_once("admin/lang/".$idiomaActual.".php");

// ========== ASIGNAR BANDERA POR IDIOMA ==========
if (!isset($_SESSION['idioma_bandera']) || empty($_SESSION['idioma_bandera'])) {
  switch ($idiomaActual) {
    case 'ES': $_SESSION['idioma_bandera'] = 'img/countries/Spain-icon.png'; break;
    case 'EN': $_SESSION['idioma_bandera'] = 'img/countries/United-States-of-Americ-icon.png'; break;
    case 'PT': $_SESSION['idioma_bandera'] = 'img/countries/Brazil-icon.png'; break;
    case 'IT': $_SESSION['idioma_bandera'] = 'img/countries/italy-icon.png'; break;
    default:   $_SESSION['idioma_bandera'] = 'img/countries/United-States-of-Americ-icon.png'; break;
  }
}

// ========== ESTABLECER MONEDA ==========
// Respeta selección manual (se marca en ctrlMoneda); si no hay, usa geoFinal como fallback.
if (!empty($_SESSION['moneda_manual'])) {
  // Mantener selección manual.
} elseif (isset($_SESSION['geoFinal']['idMoneda']) && isset($_SESSION['geoFinal']['sym'])) {
  $_SESSION['moneda_sel'] = $_SESSION['geoFinal']['idMoneda'];
  $_SESSION['moneda_sel_sym'] = $_SESSION['geoFinal']['sym'];
} elseif (!isset($_SESSION['moneda_sel']) || !isset($_SESSION['moneda_sel_sym'])) {
  // Fallback: moneda por defecto (USD)
  $_SESSION['moneda_sel'] = 188;
  $_SESSION['moneda_sel_sym'] = 'U$D';
}

// ========== METADATOS Y VARIABLES DE CARRITO ==========
$version = date('Y-m-d H:i:s');
$nombre_servicio = "Metele Brasil";
$descripcion_corta = "Atividades, excursÃµes, visitas guiadas em Brasil. Reserve online! preÃ§o mÃ­nimo antecipado e garantido.";
$carrito = $_SESSION['reserva'] ?? [];
$cantCarrito = is_array($carrito) ? count($carrito) : 0;

// ========== INICIALIZAR $_SESSION['login'] SI NO EXISTE ==========
if (!isset($_SESSION['login']) || !is_array($_SESSION['login'])) {
  $_SESSION['login'] = [];
}
if (!isset($_SESSION['login']['idVendedor'])) {
  $_SESSION['login']['idVendedor'] = 0;
}

// ========== AQUÃ COMIENZA EL HTML HEAD/NAVBAR ==========
?>
<!DOCTYPE html>
<html lang="es">
<head>

<title>Metele Brasil</title>
<meta property="fb:app_id" content="964551587611699" />

<?php if(isset($_GET['id'])){
  $idServicio=$_GET['id'];
  $servicio=getServicio($idServicio)[0];
  $fotos=getFotosServicio($idServicio);
?>
<meta property="og:url" content="https://www.metelebrasil.com/servicio" />
<meta property="og:title"  content="<?=$servicio["nombre_servicio"];?> | Metele Brasil" />
<meta property="og:description" content="<?=strip_tags($servicio["descripcion_corta"]);?>" />
<meta name="keywords" content="excursÃµes, visitas guiadas, passeios, atividades, traslados, circuitos, guias turÃ­sticos, guias de viagem" />
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta property="og:type" content="article" />
<meta property="og:image:width" content="400" />
<meta property="og:image:height" content="300" />
<link rel="icon" href="img/favicon.ico" sizes="32x32">
<meta property="og:image" content="https://metelebrasil.com/admin/classes/imgServicio/<?=($fotos[0]['ruta'] ?? '');?>" />
<?php } else if(isset($_GET['post'])){
  require("admin/classes/blog.php"); 
  require("admin/classes/fotos_blog.php");
  $idPost=$_GET['post'];
  $articulo=getArticuloBlog($idPost);
  $descripcionCorta=$articulo[0]["descripcionCorta"] ?? '';
  $fotos=getFotosBlogIdPost($idPost);  
  $titulo=$articulo[0]["titulo"] ?? '';
?>
<meta property="og:title"  content="<?=$titulo;?> | Metele Brasil" />
<meta property="og:description" content="<?=strip_tags($descripcionCorta);?>" />
<meta name="keywords" content="excursÃµes, visitas guiadas, passeios, atividades, traslados, circuitos, guias turÃ­sticos, guias de viagem" />
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta property="og:type" content="article" />
<meta property="og:image:width" content="400" />
<meta property="og:image:height" content="300" />
<link rel="icon" href="img/favicon.ico" sizes="32x32">
<meta property="og:image" content="https://metelebrasil.com/admin/classes/imgBlog/<?=($fotos[0]['ruta'] ?? '');?>" />
<?php } else { ?>
<meta property="og:title"  content="Metele Brasil" />
<meta property="og:url" content="https://metelebrasil.com" />
<meta property="og:description" content="<?=strip_tags("Atividades, excursões, visitas guiadas em Brasil. Reserve online! preço mínimo antecipado e garantido.");?>" />
<meta name="keywords" content="excursÃµes, visitas guiadas, passeios, atividades, traslados, circuitos, guias turÃ­sticos, guias de viagem" />
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta property="og:image" content="https://metelebrasil.com/img/slider4.jpg" />
<meta property="og:image:width" content="1280" />
<meta property="og:image:height" content="720" />
<meta property="og:type" content="website" />
<link rel="icon" href="img/favicon.ico" sizes="32x32">
<?php } ?>

<script type="text/javascript">
try {
  window.logout = function(){
    window.location.href = 'logout.php';
  };
} catch(e) {}
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/jquery.redirect.js?v=<?php echo $version?>"></script>

<link href="admin/plugins/fontawesome-free/css/all.min.css?v=<?php echo $version?>" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="css/animate.min.css?v=<?php echo $version?>">
<link href="css/bootstrap.css?v=<?php echo $version?>" rel="stylesheet">
<link href="css/styles.css?v=<?php echo $version?>" rel="stylesheet">
<link href="css/barra-movil.css?v=<?php echo $version?>" rel="stylesheet">
<link href="css/responsive.css?v=<?php echo $version?>" rel="stylesheet">
<link href="css/clnr.css?v=<?php echo $version?>" rel="stylesheet">

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

<?= isset($parametros[0]["head"]) ? $parametros[0]["head"] : "" ?>

<style>
  html, body { margin: 0 !important; padding: 0 !important; }
  #mainNav .navbar-nav .nav-link { display: flex; align-items: center; }
  #mainNav .navbar-nav .nav-link i { line-height: 1; display: inline-block; vertical-align: middle; }
  #mainNav .navbar-nav .nav-link { padding-top: .75rem; padding-bottom: .75rem; }
  #mainNav { margin-top: 0 !important; padding-top: 0 !important; }
  @media (min-width: 992px) {
    #mainNav .navbar-nav .nav-link i { font-size: 1.1rem; }
    #mainNav .navbar-nav .nav-link .fa-user { transform: translateY(1px); }
  }
  .navbar .nav-item.user > a { display: flex; align-items: center; }
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
  .nav-password-wrapper {
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .nav-password-wrapper .form-control {
    flex: 1;
  }
  .nav-eye-inline {
    border: 0;
    background: transparent;
    padding: 2px;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #4a5568;
    cursor: pointer;
    flex-shrink: 0;
  }
  .nav-eye-inline:focus { outline: none; box-shadow: none; }
  .nav-eye-inline i {
    font-size: .75rem;
    line-height: 1;
  }
</style>

</head>

<body id="page-top" class="body-visita">

<?= isset($parametros[0]["body"]) ? $parametros[0]["body"] : "" ?>

<div id="fb-root"></div>

<script async defer crossorigin="anonymous" src="https://connect.facebook.net/es_ES/sdk.js#xfbml=1&version=v11.0&appId=964551587611699&autoLogAppEvents=1" nonce="uv9I2f53"></script>



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
            <form class="search-menu form-inline my-2 my-lg-0">
              <input class="form-control mr-sm-2 text-white" type="search" id="search-pc" placeholder="O que vocÃª quer fazer?" aria-label="Search">
            </form>
          <div class="mostrarenmobile">
            <li class="nav-item mx-0 mx-lg-1">
              <a class="nav-link py-3 px-0 px-lg-3 rounded-sm cursor" id="buscar-pc" href="categorias"><i class="fas fa-search"></i></a>
              <a class="nav-link py-3 px-0 px-lg-3 rounded-sm cursor" style="display: none;" id="cerrar-buscar-pc"><i class="fas fa-search"></i></a>
            </li>
          </div>
           <li class="nav-item mx-0 mx-lg-1 dropdown">
            <a class="nav-link py-3 px-0 px-lg-3 rounded-sm dropdown-toggle nomelinguagem" href id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img class="nav-item mx-0 mx-lg-1 dropdown" id="iconbandeira" src="<?= $_SESSION["idioma_bandera"];?>" style="height: 20px; width: 20px;">
            <span class="nomelinguagemx"><?=$_SESSION["idioma"];?></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right menu-civa p-2" aria-labelledby="navbarDropdownMenuLink" style="min-width:240px; max-height:400px; overflow-y:auto;">
              <a class="dropdown-item" onclick="cambiaIdioma('ES');"><img src="img/countries/Spain-icon.png" style="height: 20px; width: 20px;"> &nbsp;<?=$lang["espanol"];?></a>
              <a class="dropdown-item" onclick="cambiaIdioma('EN');"><img src="img/countries/United-States-of-Americ-icon.png" style="height: 20px; width: 20px;"> &nbsp;<?=$lang["ingles"];?></a>
              <a class="dropdown-item" onclick="cambiaIdioma('PT');"><img src="img/countries/Brazil-icon.png" style="height: 20px; width: 20px;"> &nbsp;<?=$lang["portugues"];?></a>
            </div>
           </li>

            <li class="nav-item mx-0 mx-lg-1 dropdown d-none d-lg-block"  id="drpMonedaSel">
   <a class="nav-link py-3 px-0 px-lg-3 rounded-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="currencyDropdown"><?= $_SESSION["moneda_sel_sym"] ?? 'U$D'; ?></a> 
     <div class="dropdown-menu menu-civa" aria-labelledby="currencyDropdown" id="divMonedaSel">
       <?php 
    require_once("admin/classes/moneda.php");
        $monedas=getMonedas();
        for ($i=0; $i < count($monedas); $i++) { 
        ?>
   <a onclick="cambiaMoneda(<?=$monedas[$i]["idMoneda"];?>);" class="dropdown-item">
                     <?=$monedas[$i]["Symbol"]." ".$monedas[$i]["CurrencyName"];?>
          </a>
        <?php } ?>
                    </div>
          </li>

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

               <?php if (isset($_SESSION["login"]["active"])) { ?>
                 <!-- NAV-ITEM-->
          <li class="nav-item dropdown mx-0 mx-lg-1 user" style="position: relative;">
            <a class="nav-link py-3 px-0 px-lg-3 rounded-sm dropdown-toggle" href="#" id="userDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <?php 
$foto='admin/img/user_default.png';
if (isset($_SESSION['login']['foto']) && strlen($_SESSION['login']['foto']) > 3) {
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
    Swal.fire('Reservate','<?=$lang["gracias_por_usar"]?>','success');
  
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
            <?php } else { ?> 
           <li class="nav-item mx-0 mx-lg-1 user">
            <a class="nav-link py-3 px-0 px-lg-3 rounded-sm d-flex align-items-center"  data-toggle="modal" data-target="#modalLoginForm">
              <i class="fas fa-user text-white"></i> 
            </a>
          </li>

   <div class="modal fade" id="modalLoginForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
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
                      <input type="password" name="clave" id="nav-clave" autocomplete="off" class="form-control" placeholder="Contrase&ntilde;a">
                      <button class="nav-eye-inline" type="button" onclick="toggleNavClave('nav-clave', this)"><i class="fas fa-eye" aria-hidden="true"></i></button>
                    </div>
                    <small class="float-right text-primary py-2"><a href="recuperar_contrasena"><?=$lang["he_olvidado_mi_contrasena"];?></a></small>
                    <div class="my-signin2" id="my-signin2" style="padding-top: 38px; width: 10%; height: 10%; font-size: 13px;"></div>
                  </div>
                  <button class="btn btn-primary bd-highlight"><?=$lang["iniciar_sesion"];?></button>
                </form>
                <div class="row mb-4">
                  <h5><?=$lang["no_tienes_cuenta"];?><span><?=$lang["registrate"];?> <a href="registro"><?=$lang["aqui"];?></a></span></h5>
                </div>
                  </div>
                </div>
                    </div>
             <!-- FIN CONTENEDOR LOGIN -->
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

<?php } ?>

          <!-- NAV-ITEM-->
          <li class="nav-item mx-0 mx-lg-1 dropdown" style="position: relative;">
            <a class="nav-link py-3 px-0 px-lg-3 rounded-sm dropdown-toggle" href="#" id="helpDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fas fa-question-circle"></i>
            </a>
            <div class="dropdown-menu menu-civa" aria-labelledby="helpDropdown">
              <a class="dropdown-item" href="faq.php"><?=$lang["preguntas_frecuentes"];?></a>
              <a class="dropdown-item" href="contact.php"><?=$lang["contactar_con_metelebrasil.com"];?></a>
            </div>
           </li>

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
      $reserva=$carrito[$i][0] ?? [];
      $idServicio=($reserva[0]['idServicioSeleccionado'] ?? null);
            $servicio=getServicio($reserva[0]['idServicioSeleccionado'] ?? null);
           $fotos=getFotosServicio($idServicio);
               $precioReserva=0;
       $cantidadPasajeros=0;
             for ($j=0; $j < count($reserva); $j++) { 
      $servicio=getServicio($reserva[$j]['idServicioSeleccionado']);
      $idServicioSalidasTarifas=$reserva[$j]['idServicioSalidasTarifas'];
      $cantidad=$reserva[$j]['cantidad'];
      $cantidadPasajeros+=$reserva[$j]['cantidad']; 
        $tarifa=calculaTarifa($reserva[$j]["idServicioSalidasTarifas"],$reserva[$j]["cantidad"]);
          $precioReserva+=($tarifa[0]["valor"] ?? 0);
$precioTotalCarrito+=($tarifa[0]["valor"] ?? 0);
      }
  ?>

  <div class="col-md-12 col-12" style="padding: 6px 0; border-bottom: 1px solid #f0f0f0;">
    <div class="row" style="margin: 0;">     
      <div class="col-md-4 col-4" style="padding: 0;"><img src="admin/classes/imgServicio/<?=($fotos[0]['ruta'] ?? '');?>" style="width: 40px; height: 40px; object-fit: cover; border-radius: 3px;"></div>
          <div class="col-md-5 col-5" style="padding: 0 8px;">
            <p style="font-size: 14px; font-weight: 600; margin: 0; color: #333;"><?=($servicio[0]["nombre_servicio"] ?? '');?></p>
            <small style="font-size: 12px; color: #999; margin: 3px 0 0 0;"><?=$cantidadPasajeros;?> pax</small>
          </div>
          <div class="col-md-3 col-3" style="padding: 0; display: flex; justify-content: space-between; align-items: center;">
            <span style="font-size: 13px; font-weight: 700; color: #007bff;"><?=($_SESSION['moneda_sel_sym'] ?? 'U$D').' '.$precioReserva;?></span>
     
          </div>
         </div>
       </div>

  <?php }   ?>

         <div class="col-md-12 col-12 carrinhovazio" style="margin-top:15px !important; margin-bottom:10px !important">
                                   <div class="d-flex justify-content-between align-items-center mb-3" style="padding-top: 10px; border-top: 1px solid #eee;">
                                     <span style="font-size:13px; font-weight:600; color:#666;">Total</span>
                                     <span style="font-size:18px; font-weight:bold; color:#007bff;"><?=($_SESSION['moneda_sel_sym'] ?? 'U$D').' '.$precioTotalCarrito;?></span>
                                   </div>
                                   <a href="carrito.php" class="btn-finalizar-carrito">
                                     <?=$lang["finalizar_reserva"];?> <i class="fas fa-arrow-right ml-1"></i>
                                   </a>
         </div> 

 <?php } else{ ?>

 <div class="col-md-12 col-12"><div class="row">     
          <div class="col-md-8 col-8"><p class="vacio2"><?=$lang["tu_carrito_esta_vacio"];?></p></div>
         </div></div>

  <?php } ?>

            </div>
           </li>

        </ul>
      </div>
    </div>
  </nav>
     <!-- FIN MENU PC-->

     <!-- DESPLIEGUE MENU PC-->
            <div class="container row" id="mostrar-pc">
                <div class="col-lg-12">
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
                        <div class="row mb-4">
                         </div>
                         <div class="row py-4">
                          <div class="col-lg-12">
                            <h3 class="text-center"><a href="index" class="btn btn-outline-primary btn-white" style="border-radius:25px;"><?=$lang["ver_todos_los_destinos"];?></a></h3>
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
    <!-- DESPLIEGUE MENU PC-->

 <!--MENU MOVIL-->
  <section class="bg-celeste ptb-10 d-lg-none">
 	<div class="container">
 		<div class="row">
 		    <div class="col-1">
 				<a class="cursor" id="abrir-menu"><i class="fas fa-bars fa-2x text-white"></i></a>
 				<a  class="cursor  collapse text-white" id="cerrar-menu">X</a>
 			</div>
      <div class="col-4 text-left" >
          <a style="margin-right: 2px;margin-left: 2px;" class="navbar-brand navbar-movil  " href="index"><h5 class="mb-0 text-white" style="font-size: 11px; font-weight: 700; white-space: nowrap;">METELE BRASIL</h5> </a>
      </div>

<div class="col-7 text-right" style="padding-right: 5px;">
  <ul class="lista-iconos" style="display: flex; align-items: center; gap: 5px; justify-content: flex-end;">
    <li class="dropdown" style="position: relative;">
      <a class="text-white color-w cursor dropdown-toggle" type="button" id="txtIdiomaSelMovil" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="display: inline-flex; align-items: center; gap: 3px; font-size: 13px;">
        <img class="nav-item" id="iconbandeira" src="<?= $_SESSION["idioma_bandera"];?>" style="height: 18px; width: 18px;">
        <span class="nomelinguagemx" style="font-size: 13px;"><?=$_SESSION["idioma"];?></span>
      </a>
      <div class="dropdown-menu dropdown-menu-right idiomamobile" aria-labelledby="txtIdiomaSelMovil">
        <a class="dropdown-item" onclick="cambiaIdioma('ES');"><img src="img/countries/Spain-icon.png" style="height: 20px; width: 20px;"> &nbsp;<?=$lang["espanol"];?></a>
        <a class="dropdown-item" onclick="cambiaIdioma('EN');"><img src="img/countries/United-States-of-Americ-icon.png" style="height: 20px; width: 20px;"> &nbsp;<?=$lang["ingles"];?></a>
        <a class="dropdown-item" onclick="cambiaIdioma('PT');"><img src="img/countries/Brazil-icon.png" style="height: 20px; width: 20px;"> &nbsp;<?=$lang["portugues"];?></a>
      </div>
    </li>
    <li class="dropdown" style="position: relative;">
      <a class="text-white color-w cursor dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="currencyDropdownMobile" data-display="static" style="font-size: 13px;"><?= $_SESSION["moneda_sel_sym"] ?? 'U$D'; ?></a> 
     <div class="dropdown-menu menu-civa" aria-labelledby="currencyDropdownMobile" style="position: fixed !important; right: 70px !important; top: 55px !important; left: auto !important; max-width: 250px !important; transform: none !important; margin: 0 !important; z-index: 9999 !important;">
       <?php 
for ($i=0; $i < count($monedas); $i++) { 
 ?>
   <a onclick="cambiaMoneda(<?=$monedas[$i]["idMoneda"];?>);" class="dropdown-item">
                     <?=$monedas[$i]["Symbol"]." ".$monedas[$i]["CurrencyName"];?>
          </a>
 <?php } ?>
                    </div>
          </li>

<li class="dropdown" style="position: relative; overflow: visible;">
            <a class="text-white color-w cursor dropdown-toggle" id="navbarDropdownMenuLinkMobile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-right: 0; font-size: 16px;">
            <i class="fas fa-shopping-cart text-white"><span class="badge badge-danger navbar-badge" style="font-size: 11px; padding: 2px 5px;"><?=count($carrito);?></span></i>
            </a>
            <div class="dropdown-menu menu-civa menumobile p-3" aria-labelledby="navbarDropdownMenuLinkMobile" style="max-height:400px; overflow-y:auto; position: fixed !important; right: 5px !important; top: 55px !important; left: auto !important; width: 90vw !important; max-width: 350px !important; transform: none !important; margin: 0 !important;">
         <?php
$precioTotalCarrito=0;
for ($i=0; $i < count($carrito); $i++) { 
            $reserva=$carrito[$i][0] ?? [];
            $idServicio=($reserva[0]['idServicioSeleccionado'] ?? null);
            $servicio=getServicio($idServicio);
            $fotos=getFotosServicio($idServicio);
            $precioReserva=0;
            $cantidadPasajeros=0;
            for ($j=0; $j < count($reserva); $j++) {
              $idServicioSalidasTarifas=$reserva[$j]['idServicioSalidasTarifas'];
              $cantidad=$reserva[$j]['cantidad'];
              $cantidadPasajeros+=$cantidad;
              $tarifa=calculaTarifa($idServicioSalidasTarifas,$cantidad);
              $precioReserva+=($tarifa[0]['valor'] ?? 0);
              $precioTotalCarrito+=($tarifa[0]['valor'] ?? 0);
            }
         ?>

<div class="col-md-12 col-12" style="padding: 6px 0; border-bottom: 1px solid #f0f0f0;"><div class="row" style="margin: 0;">
<div class="col-md-4 col-4" style="padding: 0;"><img src="admin/classes/imgServicio/<?=($fotos[0]['ruta'] ?? '');?>" style="width: 40px; height: 40px; object-fit: cover; border-radius: 3px;"></div>
<div class="col-md-5 col-5" style="padding: 0 8px;">
<p style="font-size: 14px; font-weight: 600; margin: 0; color: #333;"><?=($servicio[0]["nombre_servicio"] ?? '');?></p>
<small style="font-size: 12px; color: #999; margin: 3px 0 0 0;"><?=$cantidadPasajeros;?> pax</small>
</div>
<div class="col-md-3 col-3" style="padding: 0; display: flex; justify-content: space-between; align-items: center;">
<span style="font-size: 13px; font-weight: 700; color: #007bff;"><?=($_SESSION['moneda_sel_sym'] ?? 'U$D').' '.$precioReserva;?></span>
<i class="fas fa-trash excluircarrinhoactividades" data-id="<?=$i?>" style="cursor: pointer; font-size: 12px; color: #dc3545; padding: 0;"></i>
</div>
</div></div>

   
<?php } ?>

   <?php if (count($carrito)>0 ){?>
<div class="col-md-12 col-12 carrinhovazio" style="margin-top:15px !important; margin-bottom:10px !important">
                   <a href="carrito" class="btn-finalizar-carrito"> <?=$lang["finalizar_reserva"];?> <i class="fas fa-arrow-right ml-1"></i> </a>
</div> 
   <?php } ?>
            </div>
           </li>
  </ul>
</div>
 		</div>
</div>
 	</div>
 </section>

    <div class="collapse navbar-collapse menu-mobile">
			  <ul class="navbar-nav mr-auto">
			        <form class="form-buscar" action="categorias">
              <div class="input-group">
            		<input class="field form-control" id="buscar-movil"  name="buscar" type="text" placeholder="<?=$lang['donde_vamos'];?>" value="">
            		  <span class="input-group-append">
                  <button class="submit btn btn-secondary" id="buscar-destinos" type="submit"><i class="fas fa-search"></i></button>
            		  </span>
              	</div>
            </form>

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
                        <a class="dropdown-item" href="admin/"><i class="fas fa-unlock" aria-hidden="true"></i> <?=$lang["iniciar_extranet"]?> </a> <?php } ?> 
                    <button class="dropdown-item" ><i class="fas fa-sign-out-alt"></i> <?=$lang["cerrar_sesion"];?></button>
                      </form>
                    </div>
<?php } else { ?>
    <div class="dropdown" style="position: relative;">
    <a  class="nav-link text-white dropdown-toggle"  id="clickLoginMovil" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <?=$lang["mi_cuenta"];?>
                       </a>
    </div>
<?php } ?>
             <div id="usuario-movil">
            <div >
               <?php if (isset($_SESSION["active"])) { ?>
      <div class="dropdown-menu menu-civa" aria-labelledby="clickLoginMovil">
                      <form action="ctrlLogin" method="post" class="">
                      <input type="hidden" name="login" value="0">
                      <?php if($_SESSION['rol']==1 || $_SESSION['rol']==5) { ?>
                        <a class="dropdown-item" href="sistema/"><i class="fas fa-unlock" aria-hidden="true"></i> <?=$lang["iniciar_extranet"]?> </a> <?php } ?> 
                    <button class="dropdown-item" ><i class="fas fa-sign-out-alt"></i><?=$lang["cerrar_sesion"];?></button>
                      </form>
                    </div>
            <?php } else { ?>   
<div class="col-lg-5 bg-white ">
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
                      <input type="password" name="clave" id="nav-clave-mobile" class="form-control" placeholder="Contrase&ntilde;a">
                      <button class="nav-eye-inline" type="button" onclick="toggleNavClave('nav-clave-mobile', this)"><i class="fas fa-eye" aria-hidden="true"></i></button>
                    </div>
                    <small class="float-right text-primary py-2"><a href=""><?=$lang["he_olvidado_mi_contrasena"];?></a></small>
                  </div>
                  <button action="submit" class="btn btn-primary bd-highlight"><?=$lang["iniciar_sesion"];?></button>
                </form>
          <B style="color: black;"><?=$lang["no_tienes_cuenta"];?><span><?=$lang["registrate"];?> <a href="registro"><?=$lang["aqui"];?></a></span></B>     
                  </div>
                </div>
                    </div>
<?php } ?>
            </div>
          </div>
          </li>

			      <li class="nav-item">
			        <a class="nav-link text-white" href="faq"><?=$lang["preguntas_frecuentes"];?></a>
			      </li>
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
document.addEventListener('DOMContentLoaded', function() {
  if (window.innerWidth <= 991) {
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

</body>
