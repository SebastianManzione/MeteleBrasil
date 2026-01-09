  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="index" class="nav-link">Inicio</a>
      </li>
    <!--  <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Contact</a>
      </li>-->
    </ul>

    <!-- SEARCH FORM -->
    <!--<form class="form-inline ml-3">
      <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar" type="search" placeholder="Buscar" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-navbar" type="submit">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </form>-->

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <?php
      // Badges de rol para claridad
      $badges = [];
      $login = $_SESSION['login'] ?? [];
      if (isset($login['rol']) && (int)$login['rol'] === 1) {
        $badges[] = ['label' => 'Admin', 'class' => 'badge-danger'];
      } elseif (isset($login['rol']) && (int)$login['rol'] === 3) {
        $badges[] = ['label' => 'Agente', 'class' => 'badge-secondary'];
      }
      if (!empty($login['idPrestador'])) {
        $badges[] = ['label' => 'Prestador', 'class' => 'badge-success'];
      }
      if (!empty($login['idVendedor'])) {
        $badges[] = ['label' => 'Vendedor', 'class' => 'badge-info'];
      }
      if (!empty($login['idCobrador'])) {
        $badges[] = ['label' => 'Cobrador', 'class' => 'badge-warning'];
      }
      if (!empty($badges)) {
        echo '<li class="nav-item d-flex align-items-center pr-2">';
        foreach ($badges as $b) {
          echo '<span class="badge ' . $b['class'] . ' mr-1">' . htmlspecialchars($b['label']) . '</span>';
        }
        echo '</li>';
      }
      ?>
      <!-- Messages Dropdown Menu -->
      <!-- <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-comments"></i>
          <span class="badge badge-danger navbar-badge">3</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <a href="#" class="dropdown-item">
         
            <div class="media">
              <img src="dist/img/user1-128x128.jpg" alt="User Avatar" class="img-size-50 mr-3 img-circle">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  Brad Diesel
                  <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">Call me whenever you can...</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
           
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
           
            <div class="media">
              <img src="dist/img/user8-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  John Pierce
                  <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">I got your message bro</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
          
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            
            <div class="media">
              <img src="dist/img/user3-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  Nora Silvester
                  <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">The subject goes here</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
           
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
        </div>
      </li>-->
      <!-- Notifications Dropdown Menu -->
     <!-- <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge">15</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">15 Notifications</span>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-envelope mr-2"></i> 4 new messages
            <span class="float-right text-muted text-sm">3 mins</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-users mr-2"></i> 8 friend requests
            <span class="float-right text-muted text-sm">12 hours</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-file mr-2"></i> 3 new reports
            <span class="float-right text-muted text-sm">2 days</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
        </div>
      </li>-->
<?php 
// verificamos la sesion creada

if(isset($_SESSION['idioma'])){

  // si es true, se crea el require y la variable lang

  $lang = $_SESSION["idioma"];



  require "lang/".$lang.".php";



  // si no hay sesion por default se carga el lenguaje espanol

}else{

  $_SESSION["idioma_bandera"]='../img/countries/Brazil-icon.png';

   $_SESSION["idioma"]="PT";

  require "lang/PT.php";

}

 ?>


           <li class="nav-item dropdown">

            <a class="nav-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img class="nav-item mx-0 mx-lg-1 dropdown" id="iconbandeira" src="<?= $_SESSION["idioma_bandera"];?>" style="height: 15; width: 15px;">

            <span class="dropdown-item-title"><?=$_SESSION["idioma"];?></span>

            </a>

            <div class="dropdown-menu menu-civa" aria-labelledby="navbarDropdownMenuLink">

        <a class="dropdown-item" onclick="cambiaIdioma('ES');"><img src="../img/countries/Spain-icon.png" style="height: 20px; width: 20px;"> &nbsp;<?=$lang["espanol"];?></a>

              <a class="dropdown-item" onclick="cambiaIdioma('EN');"><img src="../img/countries/United-States-of-Americ-icon.png" style="height: 20px; width: 20px;"> &nbsp;<?=$lang["ingles"];?></a>

              <a class="dropdown-item" onclick="cambiaIdioma('IT');"><img src="img/countries/italy-icon.png" style="height: 20px; width: 20px;"> &nbsp;Italiano</a>

              <a class="dropdown-item" onclick="cambiaIdioma('PT');"><img src="../img/countries/Brazil-icon.png" style="height: 20px; width: 20px;"> &nbsp;<?=$lang["portugues"];?></a>

             <!-- <a class="dropdown-item" onclick="cambiaIdioma('FR');"><img src="img/countries/France-icon.png"  style="height: 20px; width: 20px;"> &nbsp;Frances</a>-->

            </li>


<script type="">
        function cambiaIdioma(idioma){



      $.post("ctrl/ctrlIdioma", {cambiaIdioma: idioma}, function(data, status){console.log(data);

if (data==1) {



  location.reload();



}



  });

   }
</script>

<?php 

if (isset($_SESSION["moneda_sel"])) {
 $monedaSelSym=$_SESSION["moneda_sel_sym"];
}
else{
  $_SESSION["moneda_sel"]=283;
$_SESSION["moneda_sel_sym"]="R$";
} ?>
 <li class="nav-item dropdown">

        <a class="nav-link" data-toggle="dropdown">
        <h3 class="dropdown-item-title">  <?=$_SESSION["moneda_sel_sym"];?></h3>
        </a>
        <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
         <span class="badge badge-warning navbar-badge">Monedas</span>
         <div class="dropdown-divider"></div>
               <?php 
    require("classes/moneda.php");
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
<script type="text/javascript">
   function cambiaMoneda(cambiaMoneda){

      $.post("ctrl/ctrlMoneda", {cambiaMoneda: cambiaMoneda}, function(data, status){
if (data==1) {

  location.reload();

}

  });
   }
</script>

  <!-- User Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-user"></i>
          <span class="badge badge-warning navbar-badge"></span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
         
     <a href="#" class="dropdown-item">

              <img src="./img/user_default.png" class="img-size-50 img-circle elevation-2" alt="User Image">    <?=$_SESSION["login"]["usuario"]?>
            <span class="float-right text-muted text-sm"></span>
          </a>
       
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
             <i class="far fa-user"></i> Mi cuenta
            <span class="float-right text-muted text-sm"></span>
          </a>

          <div class="dropdown-divider"></div>
          <a href="../logout.php" class="dropdown-item dropdown-footer"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a>
        </div>
      </li>



     <!-- <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#"><i
            class="fas fa-th-large"></i></a>
      </li>-->
    </ul>
  </nav>
  <!-- /.navbar -->


