



  <!-- Main Sidebar Container -->

  <aside class="main-sidebar sidebar-dark-primary elevation-4">

    <!-- Brand Logo -->

    <a href="index" class="brand-link">

      <img src="dist/img/AdminLTELogo.png" class="brand-image img-circle elevation-3"

           style="opacity: .8">

      <span class="brand-text font-weight-light">Reservate</span>

    </a>



    <!-- Sidebar -->

    <div class="sidebar">

      <!-- Sidebar user panel (optional) -->

      <div class="user-panel mt-3 pb-3 mb-3 d-flex">

        <div class="image">

          <!--<img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">-->

        </div>

        <div class="info">

          <a href="#" class="d-block"><?=$_SESSION["login"]["usuario"]?></a>

        </div>

      </div>



      <!-- Sidebar Menu -->

      <nav class="mt-2">

        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

          <!-- Add icons to the links using the .nav-icon class

               with font-awesome or any other icon font library -->

               <?php 

          //boton home

              $indexClass="";

               $indexIcon="";



          if ($archivo_actual=="index_ppal.php") {

               $indexClass="active";

               $indexIcon="fas";

             }

echo' <li class="nav-item has-treeview menu-open">

            <a href="index" class="nav-link '.$indexClass.'">

              <i class="nav-icon fas fa-home"></i>

              <p>

                Home

               

              </p>

            </a>

     

          </li>';



             //********fin boton home

          ?>

          

<?php





   if($_SESSION["login"]["rol"]==1){



 

   

$clientesIcon="far";

$clientesClass=" ";

$clientesListClass=" ";

$treeviewClientes="   ";

if ($archivo_actual=="prestadores.php"||$archivo_actual=="altaPrestador.php") {

$treeviewClientes=" menu-open ";

  $clientesListClass="active";

              

             }



if ($archivo_actual=="prestadores.php") {

               $clientesClass="active";

               $clientesIcon="fas";

             }

?> 

                                <li class="nav-item has-treeview <?=$treeviewClientes?>">

                                      <a class="nav-link <?=$clientesListClass?>">

                                        <i class="fas fa-id-card-alt"></i> 

                                        <p>

                                           <?=$lang["prestadores_"];?>

                                          <i class="right fas fa-angle-left"></i>

                                        </p>

                                      </a>

                                      <ul class="nav nav-treeview ">



                                      <li class="nav-item">

                                        <a href="prestadores" class="nav-link <?=$clientesClass?>">

                                        <i class="fas fa-id-card"></i>

                                          <p><?=$lang["lista_de_prestadores"];?></p>

                                        </a>

                                      </li>

                                    <?php



                        //btn alta clientes

                                       $altaClienteIcon="far";

                                       $altaClienteClass="";

                                    if ($archivo_actual=="altaPrestador.php") {

                                       $altaClienteClass="active";

                                       $altaClienteIcon="fas";

                                     }

                                     ?>

                          <li class="nav-item ">

                          <a href="./altaPrestador" class="nav-link <?=$altaClienteClass?>">

                                    <i class="fas fa-plus"></i>

                                      <p><?=$lang["alta_prestador"];?></p>

                                    </a>

                                  </li> 

                                </ul>

                              </li>

<?php   } ?>







<!-- blog-->

        

<?php





   if($_SESSION["login"]["rol"]==1){ //



 

   

$clientesIcon="far";

$clientesClass=" ";

$clientesListClass=" ";

$treeviewClientes="   ";

if ($archivo_actual=="blogLista.php"||$archivo_actual=="blogAlta.php") {

$treeviewClientes=" menu-open ";

  $clientesListClass="active";

              

             }



if ($archivo_actual=="blogLista.php") {

               $clientesClass="active";

               $clientesIcon="fas";

             }

?> 

                                <li class="nav-item has-treeview <?=$treeviewClientes?>">

                                      <a class="nav-link <?=$clientesListClass?>">

                                        <i class="fas fa-id-card-alt"></i> 

                                        <p>

                                           Blog

                                          <i class="right fas fa-angle-left"></i>

                                        </p>

                                      </a>

                                      <ul class="nav nav-treeview ">



                                      <li class="nav-item">

                                        <a href="blogLista" class="nav-link <?=$clientesClass?>">

                                        <i class="fas fa-id-card"></i>

                                          <p><?=$lang["lista_de_articulo"];?></p>

                                        </a>

                                      </li>

                                    <?php



                        //btn alta clientes

                                       $altaClienteIcon="far";

                                       $altaClienteClass="";

                                    if ($archivo_actual=="blogAlta.php") {

                                       $altaClienteClass="active";

                                       $altaClienteIcon="fas";

                                     }

                                     ?>

                          <li class="nav-item ">

                          <a href="./blogAlta" class="nav-link <?=$altaClienteClass?>">

                                    <i class="fas fa-plus"></i>

                                      <p><?=$lang["alta_articulo"];?></p>

                                    </a>

                                  </li> 

                                </ul>

                              </li>

<?php   } ?>



<!-- fin blog -->





















<!--COMIENZA-->

                     <?php

                                   

                              $clientesIcon="far";

                              $clientesClass="";

                              $clientesListClass=" ";

                              $treeviewClientes="   ";

                              if ($archivo_actual=="altaServicio.php"||$archivo_actual=="pasajerosLista.php"||$archivo_actual=="serviciosLista.php"||$archivo_actual=="servicioVer.php") {

                              $treeviewClientes=" menu-open ";

                                $clientesListClass="active";

                                            

                                           }



                              if ($archivo_actual=="altaServicio.php") {

                                             $clientesClass="active";

                                             $clientesIcon="fas";

                                           }

                              ?> 

                                  <li class="nav-item has-treeview <?=$treeviewClientes?>">

                                        <a class="nav-link <?=$clientesListClass?>">

                                         <i class="fa fa-concierge-bell" aria-hidden="true"></i>

                                          <p>

                                             <?=$lang["servicios"];?>

                                            <i class="right fas fa-angle-left"></i>

                                          </p>

                                        </a>



                                          <ul class="nav nav-treeview ">

                                            <?php  if($_SESSION["login"]["rol"]==1){ ?>

                                            <li class="nav-item">

                                             <a href="altaServicio" class="nav-link <?=$clientesClass?>">

                                              <i class="fa fa-plus" aria-hidden="true"></i>

                                              <p><?=$lang["alta_servicio"];?></p>

                                              </a>

                                            </li>

                                          <?php } ?>

                                                <?php



           //btn alta clientes

                                                   $altaClienteIcon="far";

                                                   $altaClienteClass="";

                                                if ($archivo_actual=="servicioVer.php"||$archivo_actual=="serviciosLista.php"||$archivo_actual=="pasajerosLista.php") {

                                                   $altaClienteClass="active";

                                                   $altaClienteIcon="fas";

                                                    }

                                                           ?>

                                    <li class="nav-item ">

                                     <a href="serviciosLista" class="nav-link <?=$altaClienteClass?>">

                                     <i class="fa fa-file-alt" aria-hidden="true"></i>

                                     <p><?=$lang["lista_de_servicios"];?></p>

                                    </a>

                                </li> 

                           </ul>

                     </li>

<!--TERMINA-->





     <?php  if($_SESSION["login"]["rol"]==1 || $_SESSION["login"]["idPrestador"]>0){ ?>

<!--COMIENZA-->

<?php

     

$clientesIcon="far";

$clientesClass=" ";

$clientesListClass=" ";

$treeviewClientes="   ";

if ($archivo_actual=="carritosLista.php"||$archivo_actual=="carritoDetalles.php"||$archivo_actual=="reservasEstado.php") {

$treeviewClientes=" menu-open ";

  $clientesListClass="active";

              

             }


if ($archivo_actual=="carritosLista.php"||$archivo_actual=="carritoDetalles.php") {

               $clientesClass="active";

               $clientesIcon="fas";

             }

?> 

      <li class="nav-item has-treeview <?=$treeviewClientes?>">

            <a class="nav-link <?=$clientesListClass?>">

             <i class="fa fa-calendar-check" aria-hidden="true"></i>



              <p>

                 <?=$lang["reservas"];?>

                <i class="right fas fa-angle-left"></i>

              </p>

            </a>

            <ul class="nav nav-treeview ">

<?php if ($_SESSION["login"]["rol"]==1 ) {

 ?>

 <li class="nav-item">

                <a href="carritosLista" class="nav-link <?=$clientesClass?>">

                <i class="fa fa-cart-arrow-down" aria-hidden="true"></i>

                  <p><?=$lang["lista_carritos"];?></p>

                </a>

              </li>

<?php } ?>

<?php $clientesClass=" ";

if ($archivo_actual=="reservasEstado.php") {

               $clientesClass="active";

               $clientesIcon="fas";

             } ?>



              <li class="nav-item">

                <a href="reservasEstado" class="nav-link <?=$clientesClass?>">

                <i class="fa fa-compact-disc" aria-hidden="true"></i>

                  <p><?=$lang["estado_reservas"];?></p>

                </a>

              </li>





              </ul>

          </li>

<!--TERMINA-->



     <?php } ?>







<?php  if($_SESSION["login"]["rol"]==1){ ?>



<!--COMIENZA-->

<?php

     

$clientesIcon="far";

$clientesClass=" ";

$clientesListClass=" ";

$treeviewClientes="   ";

if ($archivo_actual=="usuariosLista.php"||$archivo_actual=="usuarioAlta.php" ||$archivo_actual=="monedaAdmin.php" ||$archivo_actual=="solicitudes.php"||$archivo_actual=="contacto.php" ||$archivo_actual=="cupones.php" ||$archivo_actual=="comisionesEditor.php" ||$archivo_actual=="categoriasLista.php" ||$archivo_actual=="categoriaVer.php" || $archivo_actual=="categoriaServiciosAdicionales.php" ||$archivo_actual=="categoriaOpiniones.php" || $archivo_actual=="parametros.php"  || $archivo_actual=="textoMiniaturaLista.php" || $archivo_actual=="textoMiniaturaBlogLista.php" || $archivo_actual=="serviciosAdicionalesAlta.php" || $archivo_actual=="textosAccesibilidad.php" || $archivo_actual=="destinosAlta.php" || $archivo_actual=="edades.php" || $archivo_actual=="cancelaciones.php") {

$treeviewClientes=" menu-open ";

  $clientesListClass="active";

              

             }





?> 

      <li class="nav-item has-treeview <?=$treeviewClientes?>">

            <a class="nav-link <?=$clientesListClass?>">

             <i class="fa fa-briefcase" aria-hidden="true"></i>



              <p>

               <?=$lang["administracion"];?>

                <i class="right fas fa-angle-left"></i>

              </p>

            </a>

            <ul class="nav nav-treeview ">





              <!-- individual Usuarios-->

<?php  $clientesClass=" ";

 if ($archivo_actual=="usuariosLista.php" || $archivo_actual=="usuarioAlta.php" ) {

               $clientesClass="active";

               $clientesIcon="fas";

             } ?>

 <li class="nav-item">

                <a href="usuariosLista" class="nav-link <?=$clientesClass?>">

                <i class="fa fa-address-book" aria-hidden="true"></i>

                  <p><?=$lang["usuarios"];?></p>

                </a>

              </li>

      <!-- individual-->

              <!-- individual Usuarios-->

<?php  $clientesClass=" ";

 if ($archivo_actual=="solicitudes.php" ) {

               $clientesClass="active";

               $clientesIcon="fas";

             } ?>

 <li class="nav-item">

                <a href="solicitudes" class="nav-link <?=$clientesClass?>">

                <i class="fa fa-address-book" aria-hidden="true"></i>

                  <p><?=$lang["solicitudes"];?></p>

                </a>

              </li>

      <!-- individual-->

              <!-- individual Usuarios-->

<?php  $clientesClass=" ";

 if ($archivo_actual=="contacto.php" ) {

               $clientesClass="active";

               $clientesIcon="fas";

             } ?>

 <li class="nav-item">

                <a href="contacto" class="nav-link <?=$clientesClass?>">

                <i class="fa fa-address-book" aria-hidden="true"></i>

                  <p>Contacto</p>

                </a>

              </li>

      <!-- individual-->


              <!-- individual Usuarios-->

<?php  $clientesClass=" ";

 if ($archivo_actual=="cupones.php" ) {

               $clientesClass="active";

               $clientesIcon="fas";

             } ?>

 <li class="nav-item">

                <a href="cupones" class="nav-link <?=$clientesClass?>">
<i class="fas fa-percent"></i>

                  <p>Cupones</p>

                </a>

              </li>

      <!-- individual-->


    <!-- individual Usuarios-->

<?php  $clientesClass=" ";

 if ($archivo_actual=="edades.php" ) {

               $clientesClass="active";

               $clientesIcon="fas";

             } ?>

 <li class="nav-item">

                <a href="edades" class="nav-link <?=$clientesClass?>">

                <i class="fas fa-user" aria-hidden="true"></i>

                  <p><?=$lang["edades"];?></p>

                </a>

              </li>

      <!-- individual-->


 <!-- individual Usuarios-->

<?php  $clientesClass=" ";

 if ($archivo_actual=="cancelaciones.php" ) {

               $clientesClass="active";

               $clientesIcon="fas";

             } ?>

 <li class="nav-item">

                <a href="cancelaciones" class="nav-link <?=$clientesClass?>">

           <i class="fas fa-user-times"></i>
                  <p><?=$lang["textos_cancelaciones"];?></p>

                </a>

              </li>

      <!-- individual-->




              <!-- individual Usuarios-->

<?php $clientesClass=" ";

if ($archivo_actual=="monedaAdmin.php") {

               $clientesClass="active";

               $clientesIcon="fas";

             } ?>

 <li class="nav-item">

                <a href="monedaAdmin" class="nav-link <?=$clientesClass?>">

                <i class="fa fa-file-invoice-dollar" aria-hidden="true"></i>

                  <p><?=$lang["moneda"];?></p>

                </a>

              </li>

      <!-- individual-->
              <!-- individual-->

<?php 

$clientesClass=" ";

if ($archivo_actual=="textoMiniaturaLista.php") {

               $clientesClass="active";

               $clientesIcon="fas";

             } ?>

 <li class="nav-item">

                <a href="textoMiniaturaLista" class="nav-link <?=$clientesClass?>">

                <i class="fa fa-hand-holding-usd" aria-hidden="true"></i>

                  <p><?=$lang["editor_de_textos"];?></p>

                </a>

              </li>

      <!-- individual-->

 <!-- individual-->

<?php 

$clientesClass=" ";

if ($archivo_actual=="textosAccesibilidad.php") {

               $clientesClass="active";

               $clientesIcon="fas";

             } ?>

 <li class="nav-item">

                <a href="textosAccesibilidad" class="nav-link <?=$clientesClass?>">

                <i class="fa fa-hand-holding-usd" aria-hidden="true"></i>

                  <p><?=$lang["editor_de_textos_accesibilidad"];?></p>

                </a>

              </li>

      <!-- individual-->
 <!-- individual-->

<?php 

$clientesClass=" ";

if ( $archivo_actual=="destinosAlta.php") {

               $clientesClass="active";

               $clientesIcon="fas";

             } ?>

 <li class="nav-item">

                <a href="destinosAlta" class="nav-link <?=$clientesClass?>">

                <i class="fa fa-hand-holding-usd" aria-hidden="true"></i>

                  <p><?=$lang["editor_de_destinos"];?></p>

                </a>

              </li>

      <!-- individual-->

              <!-- individual-->

<?php 

$clientesClass=" ";

if ($archivo_actual=="serviciosAdicionalesAlta.php") {

               $clientesClass="active";

               $clientesIcon="fas";

             } ?>

 <li class="nav-item">

                <a href="serviciosAdicionalesAlta" class="nav-link <?=$clientesClass?>">

                <i class="fa fa-hand-holding-usd" aria-hidden="true"></i>

                  <p><?=$lang["editor_de_servicios_adicionales"];?></p>

                </a>

              </li>

      <!-- individual-->
              <!-- individual-->

<?php 

$clientesClass=" ";

if ($archivo_actual=="textoMiniaturaBlogLista.php") {

               $clientesClass="active";

               $clientesIcon="fas";

             } ?>

 <li class="nav-item">

                <a href="textoMiniaturaBlogLista" class="nav-link <?=$clientesClass?>">

                <i class="fa fa-hand-holding-usd" aria-hidden="true"></i>

                  <p><?=$lang["editor_de_textos_miniatura_blog"];?></p>

                </a>

              </li>

      <!-- individual-->

              <!-- individual-->

<?php 

$clientesClass=" ";

if ($archivo_actual=="comisionesEditor.php") {

               $clientesClass="active";

               $clientesIcon="fas";

             } ?>

 <li class="nav-item">

                <a href="comisionesEditor" class="nav-link <?=$clientesClass?>">

                <i class="fa fa-hand-holding-usd" aria-hidden="true"></i>

                  <p><?=$lang["editor_de_comiciones"];?></p>

                </a>

              </li>

      <!-- individual-->

   <!-- individual-->

<?php 

$clientesClass=" ";

if ($archivo_actual=="categoriasLista.php" ||$archivo_actual=="categoriaServiciosAdicionales.php" ||$archivo_actual=="categoriaOpiniones.php" ||$archivo_actual=="categoriaVer.php" ) {

               $clientesClass="active";

               $clientesIcon="fas";

             } ?>

 <li class="nav-item">

                <a href="categoriasLista" class="nav-link <?=$clientesClass?>">

                <i class="fa fa-calendar-day" aria-hidden="true"></i>

                  <p><?=$lang["editor_de_categorias"];?></p>

                </a>

              </li>


<?php 

$clientesClass=" ";

if ($archivo_actual=="parametros.php") {

               $clientesClass="active";

               $clientesIcon="fas";

             } ?>

 <li class="nav-item">

                <a href="parametros" class="nav-link <?=$clientesClass?>">

                <i class="fa fa-calendar-day" aria-hidden="true"></i>

                  <p><?=$lang["editor_parametros"];?></p>

                </a>

              </li>





      <!-- individual-->

              </ul>

          </li>

<!--TERMINA-->



<?php  } ?>







<?php  if($_SESSION["login"]["rol"]==1 || $_SESSION["login"]["idVendedor"]>0 || $_SESSION['login']['idPrestador']>0 || $_SESSION['login']['idCobrador']>0){ ?>

<!--COMIENZA-->

<?php

   

$clientesIcon="far";
$clientesClass=" ";
$clientesListClass=" ";
$treeviewClientes="   ";

if ($archivo_actual=="comprobantesLista.php" ||$archivo_actual=="comisionesLista.php" || $archivo_actual=="financieroSalidas.php"|| $archivo_actual=="cobroSignal.php") {

$treeviewClientes=" menu-open ";
  $clientesListClass="active";
             }
?> 

      <li class="nav-item has-treeview <?=$treeviewClientes?>">
            <a class="nav-link <?=$clientesListClass?>">
             <i class="fa fa-comment-dollar" aria-hidden="true"></i>
              <p>
               <?=$lang["financiero"];?>
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>

            <ul class="nav nav-treeview ">
              <!-- individual Usuarios-->
<?php  

if ($_SESSION["login"]["rol"]==1) {
  // code...

$clientesClass=" ";
 if ($archivo_actual=="comprobantesLista.php") {
               $clientesClass="active";
               $clientesIcon="fas";
             } ?>

 <li class="nav-item">
                <a href="comprobantesLista" class="nav-link <?=$clientesClass?>">
                <i class="fa fa-check-square" aria-hidden="true"></i>
                  <p><?=$lang["comprovantes"];?></p>
                </a>
              </li>

      <!-- individual-->

<?php } ?>

<?php  

if ($_SESSION["login"]["idPrestador"]>0) {
  // code...

$clientesClass=" ";
 if ($archivo_actual=="financieroSalidas.php") {
               $clientesClass="active";
               $clientesIcon="fas";
             } ?>

 <li class="nav-item">
                <a href="financieroSalidas" class="nav-link <?=$clientesClass?>">
               <i class="fas fa-check"></i>
                  <p>Comissões Prestador</p>
                </a>
              </li>

      <!-- individual-->

<?php } ?>

<?php  

if ($_SESSION["login"]["idCobrador"]>0) {
  // code...

$clientesClass=" ";
 if ($archivo_actual=="cobroSignal.php") {
               $clientesClass="active";
               $clientesIcon="fas";
             } ?>

 <li class="nav-item">
                <a href="cobroSignal" class="nav-link <?=$clientesClass?>">
              <i class="fas fa-cash-register"></i>
                  <p>Cobro Signal</p>
                </a>
              </li>

      <!-- individual-->

<?php } ?>



              <!-- individual Usuarios-->

<?php $clientesClass=" ";
if ($archivo_actual=="comisionesLista.php") {
               $clientesClass="active";
               $clientesIcon="fas";
             } ?>

 <li class="nav-item">
                <a href="comisionesLista" class="nav-link <?=$clientesClass?>">
                <i class="fa fa-handshake" aria-hidden="true"></i>
                <p><?=$lang["comisiones"];?> Vendedor</p>
                </a>
              </li>

      <!-- individual-->
              </ul>
          </li>

<!--TERMINA-->


<?php  } ?>


<?php  if($_SESSION["login"]["rol"]==1){ ?>
<!--COMIENZA-->
<?php
 
$clientesIcon="far";
$clientesClass=" ";
$clientesListClass=" ";
$treeviewClientes="   ";
if ($archivo_actual=="emailsLista.php" ) {
$treeviewClientes=" menu-open ";
$clientesListClass="active";
             }
?> 

      <li class="nav-item has-treeview <?=$treeviewClientes?>">
            <a class="nav-link <?=$clientesListClass?>">
     <i class="fas fa-vial"></i>
              <p>
               Test
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview ">


              <!-- individual Usuarios-->

<?php  $clientesClass=" ";
 if ($archivo_actual=="emailsLista.php") {
               $clientesClass="active";
               $clientesIcon="fas";
             } ?>

 <li class="nav-item">
                <a href="emailsLista" class="nav-link <?=$clientesClass?>">
              <i class="fas fa-list-ol"></i>
                  <p>Lista emails guias</p>
                </a>
              </li>
      <!-- individual-->

              </ul>
          </li>

<!--TERMINA-->
<?php  } ?>





























       </ul>

      </nav>

      <!-- /.sidebar-menu -->

    </div>

    <!-- /.sidebar -->

  </aside>

