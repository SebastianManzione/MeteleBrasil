

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
                                           Prestadores
                                          <i class="right fas fa-angle-left"></i>
                                        </p>
                                      </a>
                                      <ul class="nav nav-treeview ">

                                      <li class="nav-item">
                                        <a href="prestadores" class="nav-link <?=$clientesClass?>">
                                        <i class="fas fa-id-card"></i>
                                          <p>Lista de Prestadores</p>
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
                                      <p>Alta Prestador</p>
                                    </a>
                                  </li> 
                                </ul>
                              </li>
<?php   } ?>

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
                                             Servicios
                                            <i class="right fas fa-angle-left"></i>
                                          </p>
                                        </a>

                                          <ul class="nav nav-treeview ">
                                            <?php  if($_SESSION["login"]["rol"]==1){ ?>
                                            <li class="nav-item">
                                             <a href="altaServicio" class="nav-link <?=$clientesClass?>">
                                              <i class="fa fa-plus" aria-hidden="true"></i>
                                              <p>Alta Servicios</p>
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
                                     <p>Lista de servicios</p>
                                    </a>
                                </li> 
                           </ul>
                     </li>
<!--TERMINA-->


     <?php  if($_SESSION["login"]["rol"]==1){ ?>
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
                 Reservas
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview ">

 <li class="nav-item">
                <a href="carritosLista" class="nav-link <?=$clientesClass?>">
                <i class="fa fa-cart-arrow-down" aria-hidden="true"></i>
                  <p>Lista de Carritos</p>
                </a>
              </li>

<?php $clientesClass=" ";
if ($archivo_actual=="reservasEstado.php") {
               $clientesClass="active";
               $clientesIcon="fas";
             } ?>

              <li class="nav-item">
                <a href="reservasEstado" class="nav-link <?=$clientesClass?>">
                <i class="fa fa-compact-disc" aria-hidden="true"></i>
                  <p>Estado de Reservas</p>
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
if ($archivo_actual=="usuariosLista.php"||$archivo_actual=="usuarioAlta.php" ||$archivo_actual=="monedaAdmin.php" ||$archivo_actual=="comisionesEditor.php" ||$archivo_actual=="categoriasLista.php" ||$archivo_actual=="categoriaServiciosAdicionales.php" ||$archivo_actual=="categoriaOpiniones.php" ||$archivo_actual=="altaArticulo.php" ||$archivo_actual=="listaBlog.php" ) {
$treeviewClientes=" menu-open ";
  $clientesListClass="active";
              
             }


?> 
      <li class="nav-item has-treeview <?=$treeviewClientes?>">
            <a class="nav-link <?=$clientesListClass?>">
             <i class="fa fa-briefcase" aria-hidden="true"></i>

              <p>
               Administracion
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
                  <p>Usuarios</p>
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
                  <p>Moneda</p>
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
                  <p>Editor de comisiones</p>
                </a>
              </li>
      <!-- individual-->
   <!-- individual-->
<?php 
$clientesClass=" ";
if ($archivo_actual=="categoriasLista.php" ||$archivo_actual=="categoriaServiciosAdicionales.php" ||$archivo_actual=="categoriaOpiniones.php") {
               $clientesClass="active";
               $clientesIcon="fas";
             } ?>
 <li class="nav-item">
                <a href="categoriasLista" class="nav-link <?=$clientesClass?>">
                <i class="fa fa-calendar-day" aria-hidden="true"></i>
                  <p>Editor de categorías</p>
                </a>
              </li>


<?php 
$clientesClass=" ";
if ($archivo_actual=="altaArticulo.php" ||$archivo_actual=="listaBlog.php") {
               $clientesClass="active";
               $clientesIcon="fas";
             } ?>
 <li class="nav-item">
                <a href="listaBlog" class="nav-link <?=$clientesClass?>">
                <i class="fa fa-calendar-day" aria-hidden="true"></i>
                  <p>Blog</p>
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
if ($archivo_actual=="comprobantesLista.php" ||$archivo_actual=="comisionesLista.php") {
$treeviewClientes=" menu-open ";
  $clientesListClass="active";
              
             }


?> 
      <li class="nav-item has-treeview <?=$treeviewClientes?>">
            <a class="nav-link <?=$clientesListClass?>">
             <i class="fa fa-comment-dollar" aria-hidden="true"></i>

              <p>
               Financiero
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview ">


              <!-- individual Usuarios-->
<?php  $clientesClass=" ";
 if ($archivo_actual=="comprobantesLista.php") {
               $clientesClass="active";
               $clientesIcon="fas";
             } ?>
 <li class="nav-item">
                <a href="comprobantesLista" class="nav-link <?=$clientesClass?>">
                <i class="fa fa-check-square" aria-hidden="true"></i>
                  <p>Comprobantes</p>
                </a>
              </li>
      <!-- individual-->



              <!-- individual Usuarios-->
<?php $clientesClass=" ";
if ($archivo_actual=="comisionesLista.php") {
               $clientesClass="active";
               $clientesIcon="fas";
             } ?>
 <li class="nav-item">
                <a href="comisionesLista" class="nav-link <?=$clientesClass?>">
                <i class="fa fa-handshake" aria-hidden="true"></i>
                  <p>Comisiones</p>
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
